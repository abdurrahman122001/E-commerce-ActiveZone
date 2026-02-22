<?php

namespace App\Http\Controllers\Franchise\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReply;
use Auth;
use App\Mail\SupportMailManager;
use Mail;
use DB;

class SupportTicketController extends Controller
{
    /**
     * Get the franchise employee guard user.
     */
    protected function employee()
    {
        return Auth::guard('franchise_employee')->user();
    }

    /**
     * List tickets for this franchise employee.
     * Tickets are identified by user_role='franchise_employee' and code suffix matching their employee id.
     */
    public function index()
    {
        $employee = $this->employee();
        $tickets = DB::table('tickets')
            ->where('user_role', 'franchise_employee')
            ->where('code', 'like', '%_emp' . $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('backend.franchise.employees.support_tickets.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        $employee = $this->employee();

        $ticket = new Ticket;
        // Use a composite code to identify employee tickets
        $ticket->code = strtotime(date('Y-m-d H:i:s')) . '_emp' . $employee->id;
        $ticket->user_id = 1; // placeholder; actual submitter is tracked via user_role and subject prefix
        $ticket->user_role = 'franchise_employee';
        $ticket->subject = '[' . $employee->name . '] ' . $request->subject;
        $ticket->details = $request->details;
        $ticket->files = $request->attachments;

        if ($ticket->save()) {
            $this->notifyAdmin($ticket, $employee);
            flash(translate('Ticket has been sent successfully'))->success();
            return redirect()->route('franchise.employee.support_tickets.index');
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail(decrypt($id));
        $ticket->client_viewed = 1;
        $ticket->save();
        $ticket_replies = $ticket->ticketreplies;
        return view('backend.franchise.employees.support_tickets.show', compact('ticket', 'ticket_replies'));
    }

    public function reply(Request $request)
    {
        $ticket_reply = new TicketReply;
        $ticket_reply->ticket_id = $request->ticket_id;
        $ticket_reply->user_id = 1; // placeholder
        $ticket_reply->reply = $request->reply;
        $ticket_reply->files = $request->attachments;
        if ($ticket_reply->save()) {
            // Mark ticket as pending/unread by admin
            DB::table('tickets')->where('id', $request->ticket_id)->update(['viewed' => 0, 'status' => 'pending']);
            flash(translate('Reply has been sent successfully'))->success();
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    protected function notifyAdmin($ticket, $employee)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support ticket Code is') . ':- ' . $ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. A ticket has been created by Franchise Employee: ') . $employee->name . ' (' . $employee->email . ')';
        $array['link'] = route('support_ticket.admin_show', encrypt($ticket->id));
        $array['sender'] = $employee->name;
        $array['details'] = $ticket->details;
        try {
            Mail::to(get_admin()->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {}
    }
}
