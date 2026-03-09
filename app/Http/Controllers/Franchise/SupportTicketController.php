<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReply;
use Auth;
use App\Mail\SupportMailManager;
use Mail;
use App\Models\User;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('franchise.support_ticket.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'details' => 'required',
        ]);

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
        }
        $ticket->files = count($photos) > 0 ? implode(',', $photos) : null;

        if ($ticket->save()) {
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();
            return redirect()->route('franchise.support_tickets.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail(decrypt($id));
        $ticket->client_viewed = 1;
        $ticket->save();
        $ticket_replies = $ticket->ticketreplies;
        return view('franchise.support_ticket.show', compact('ticket', 'ticket_replies'));
    }

    public function ticket_reply_store(Request $request)
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
        }
        $ticket_reply->files = count($photos) > 0 ? implode(',', $photos) : null;
        $ticket_reply->ticket->viewed = 0;
        $ticket_reply->ticket->status = 'pending';
        $ticket_reply->ticket->save();
        
        if ($ticket_reply->save()) {
            flash(translate('Reply has been sent successfully'))->success();
            return back();
        }
        
        flash(translate('Something went wrong'))->error();
        return back();
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
            $admin_user = User::where('user_type', 'admin')->first();
            if ($admin_user) {
                Mail::to($admin_user->email)->queue(new SupportMailManager($array));
            }
        } catch (\Exception $e) {
            // Log error or ignore
        }
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
            $upload->user_id = Auth::user()->id;
            $upload->type = $type[$extension];
            $upload->file_size = $file->getSize();
            $upload->save();

            $file->move(public_path('uploads/all'), $filename);

            return $upload->id;
        }
        return null;
    }
}
