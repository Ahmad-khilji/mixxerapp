<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageRequest;
use App\Http\Requests\Api\TicketRequest;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function ticket(TicketRequest $request)
    {
         
           $ticket = new Ticket();
    $ticket->user_id = $request->user_id;
        $ticket->message = $request->message;
           $ticket->save();

        $message= new Message();
        $message->user_id = $request->user_id;
           $message->type = 'text';

        $message->sendby = 'user';
        $message->ticket_id = $ticket->id;
 $message->message = $request->message;
        $message->save();


             $adminMessage =new Message();
           $adminMessage->ticket_id =$ticket->id;
        $adminMessage->user_id = $request->user_id;
         $adminMessage->type = 'text';
        $adminMessage->sendby = "admin";
         $adminMessage->message = 'Hi,👋Thanks for your message. We ll get back to you within 24 hours.';
        $adminMessage->save();

        $new= Ticket::find($ticket->id);
        return response()->json([
            'status' => true,
            'action' => "Ticket Added",
            'data' =>  $new
        ]);
    }


    public function list($user_id, $status)
{
    $tickets = Ticket::where('user_id', $user_id)->where('status', $status)->latest()->get();
    // return($tickets);
    return response()->json([
        'status' => true,
        'action' => "User Ticket",
        'data' => $tickets,
    ]);
}

public function closeTicket($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if ($ticket) {
            $ticket->status = 0;
            $ticket->save();
            return response()->json([
                'status' => true,
                'action' => "Ticket close",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "Tickets not found againt this ticketId",
            ]);
        }
    }


    public function messageSend(MessageRequest $request)
    {

        $message= new Message();
        $message->user_id = $request->user_id;
           $message->ticket_id= $request->ticket_id;
        $message->message = $request->message;
 $message->type = $request->type;
        $message->sendBy = 'user';
        $message->save();


        $newMessage = Message::find($message->id);

        return response()->json([
            'status' => true,
            'action' => 'message send',
            'data' => $newMessage,
        ]);
    }


    public function messageList($ticket_id)
    {
        $messagelist = Message::where('ticket_id', $ticket_id)->get();
        if ($messagelist) {
            return response()->json([
                'status' => true,
                'action' => 'message listed',
                'data' => $messagelist,
            ]);
        }
    }

    public function categoryList()
    {
        $category =  Category::all();
        return response()->json([
            'status' => true,
            'action' => 'category list',
            'data' => $category,
        ]);
    }


    public function faqs()
    {
        $list = Faq::all();
        return response()->json([
            'status' => true,
            'action' =>  'Faqs',
            'data' => $list
        ]);
    }

}
