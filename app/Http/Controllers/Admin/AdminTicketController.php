<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

class AdminTicketController extends Controller
{
    public function ticket($status)
    {
       
        if ($status == 'active') {
            $reports = Ticket::where('status', 1)->get();

            foreach ($reports as $report) {
                $user = User::find($report->user_id);
                $report->user = $user;
            }
        } else {

            $reports = Ticket::where('status', 0)->get();

            foreach ($reports as $report) {
                $user = User::find($report->user_id);
                $report->user = $user;
            }
        }
        return view('ticket.index', compact('reports', 'status'));
    }

    public function messages($status,$ticket_id)
    {
        

                $messages= Message::where('ticket_id', $ticket_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $ticket =Ticket::find($ticket_id);
        $user = User::find($ticket->user_id)->first();
        return view('ticket.show', compact('messages', 'user', 'ticket'));
    }

    public function closeTicket($report_id)
    {

 $report = Ticket::find($report_id);
        if ($report) {
               $report->status = 0;
     $report->save();
            return redirect()->route('dashboard-ticket-ticket', 'active');
        }
    }


    public function sendMessage(Request $request)
    {

          $message =new Message();
$message->ticket_id= $request->ticket_id;
        $message->user_id = $request->user_id;
        $message->sendBy= 'admin';
             $message->message = $request->message;
        $message->type = 'text';
        $message->save();

             return response()->json($message);
    }
}
