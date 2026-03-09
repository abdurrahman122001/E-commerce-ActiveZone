<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Auth;
use App\Models\TicketReply;
use App\Mail\SupportMailManager;
use Mail;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_support_tickets'])->only('admin_index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise' || Auth::user()->user_type == 'state_franchise') {
            return redirect()->route('franchise.support_tickets.index');
        }
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.support_ticket.index', compact('tickets'));
    }

    public function admin_index(Request $request)
    {
        $sort_search = null;
        $tickets = Ticket::orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $tickets = $tickets->where('code', 'like', '%' . $sort_search . '%');
        }
        $tickets = $tickets->paginate(15);
        return view('backend.support.support_tickets.index', compact('tickets', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd();
        $ticket = new Ticket;
        $ticket->code = strtotime(date('Y-m-d H:i:s')) . Auth::user()->id;
        $ticket->user_id = Auth::user()->id;
        $ticket->user_role = Auth::user()->user_type;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        
        $photos = [];
        if($request->hasFile('attachments')){
            foreach ($request->file('attachments') as $file) {
                $upload_id = $this->upload_attachment($file);
                if($upload_id) $photos[] = $upload_id;
            }
            $ticket->files = count($photos) > 0 ? implode(',', $photos) : null;
        } else {
            $ticket->files = $request->attachments;
        }

        if ($ticket->save()) {
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();
            if (Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise' || Auth::user()->user_type == 'state_franchise') {
                return redirect()->route('franchise.support_tickets.index');
            }
            return redirect()->route('support_ticket.index');
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    public function send_support_mail_to_admin($ticket)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support ticket Code is') . ':- ' . $ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. A ticket has been created. Please check the ticket.');
        $array['link'] = route('support_ticket.admin_show', encrypt($ticket->id));
        $array['sender'] = $ticket->user->name;
        $array['details'] = $ticket->details;
        try {
            Mail::to(get_admin()->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {}
    }

    public function send_support_reply_email_to_user($ticket, $tkt_reply)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support ticket Code is') . ':- ' . $ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. You have a new response for this ticket. Please check the ticket.');
        if ($ticket->user_role == 'seller') {
            $array['link'] = route('seller.support_ticket.show', encrypt($ticket->id));
        } elseif ($ticket->user_role == 'franchise' || $ticket->user_role == 'sub_franchise') {
            $array['link'] = route('franchise.support_tickets.show', encrypt($ticket->id));
        } elseif ($ticket->user_role == 'franchise_employee') {
            $array['link'] = route('franchise.employee.support_tickets.show', encrypt($ticket->id));
        } else {
            $array['link'] = route('support_ticket.show', encrypt($ticket->id));
        }
        $array['sender'] = $tkt_reply->user->name;
        $array['details'] = $tkt_reply->reply;

        try {
            Mail::to($ticket->user->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {}
    }

    public function admin_store(Request $request)
    {
        $ticket_reply = new TicketReply;
        $ticket_reply->ticket_id = $request->ticket_id;
        $ticket_reply->user_id = Auth::user()->id;
        $ticket_reply->reply = $request->reply;
        
        $photos = [];
        if($request->hasFile('attachments')){
            foreach ($request->file('attachments') as $file) {
                $upload_id = $this->upload_attachment($file);
                if($upload_id) $photos[] = $upload_id;
            }
            $ticket_reply->files = count($photos) > 0 ? implode(',', $photos) : null;
        } else {
            $ticket_reply->files = $request->attachments;
        }
        $ticket_reply->ticket->client_viewed = 0;
        $ticket_reply->ticket->status = $request->status;
        $ticket_reply->ticket->save();

        if ($ticket_reply->save()) {
            flash(translate('Reply has been sent successfully'))->success();
            $this->send_support_reply_email_to_user($ticket_reply->ticket, $ticket_reply);
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    public function seller_store(Request $request)
    {
        $ticket_reply = new TicketReply;
        $ticket_reply->ticket_id = $request->ticket_id;
        $ticket_reply->user_id = $request->user_id;
        $ticket_reply->reply = $request->reply;
        
        $photos = [];
        if($request->hasFile('attachments')){
            foreach ($request->file('attachments') as $file) {
                $upload_id = $this->upload_attachment($file);
                if($upload_id) $photos[] = $upload_id;
            }
            $ticket_reply->files = count($photos) > 0 ? implode(',', $photos) : null;
        } else {
            $ticket_reply->files = $request->attachments;
        }
        $ticket_reply->ticket->viewed = 0;
        $ticket_reply->ticket->status = 'pending';
        $ticket_reply->ticket->save();
        if ($ticket_reply->save()) {

            flash(translate('Reply has been sent successfully'))->success();
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise' || Auth::user()->user_type == 'state_franchise') {
            return redirect()->route('franchise.support_tickets.show', $id);
        }
        $ticket = Ticket::findOrFail(decrypt($id));
        $ticket->client_viewed = 1;
        $ticket->save();
        $ticket_replies = $ticket->ticketreplies;
        return view('frontend.user.support_ticket.show', compact('ticket', 'ticket_replies'));
    }

    public function admin_show($id)
    {
        $ticket = Ticket::findOrFail(decrypt($id));
        $ticket->viewed = 1;
        $ticket->save();
        return view('backend.support.support_tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function upload_attachment($file)
    {
        $type = [
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
        ];
        $extension = strtolower($file->getClientOriginalExtension());
        if (isset($type[$extension])) {
            $filename = str_replace(' ', '_', $file->getClientOriginalName());
            $filename = time() . '_' . $filename;

            $upload = new \App\Models\Upload;
            $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $upload->extension = $extension;
            $upload->file_name = 'uploads/all/' . $filename;
            $upload->user_id = Auth::user()->id ?? 0;
            $upload->type = $type[$extension];
            $upload->file_size = $file->getSize();
            $upload->save();

            $file->move(public_path('uploads/all'), $filename);

            return $upload->id;
        }
        return null;
    }
}
