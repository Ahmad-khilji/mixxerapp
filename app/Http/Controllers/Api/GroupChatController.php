<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendMessageGroup;
use App\Models\CreateGroup;
use App\Models\GroupMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class GroupChatController extends Controller
{
    public function createGroup($user_id){
        $user = User::where('uuid', $user_id)->first();
        if($user){
            $create = new CreateGroup();
            $create->user_id = $user_id;
            $create->first_name = $user->first_name;
            $create->save();
            return response()->json([
                'status' => true,
                'action' => 'Group created successfully',
                'data' => $create
            ]);
        }else{
            return response()->json([
                'status' => false,
                'action' => "No account found against this userId",
            ]);
        }
    }
   
    public function sendMessagegGroup(SendMessageGroup $request){
        $user = createGroup::where('id', $request->group_id)->where('user_id', $request->user_id)->first();
        if($user){
         $message = new GroupMessage();
         $message->group_id = $request->group_id;
         $message->user_id = $request->user_id;
         $message->message = $request->message;
         $message->save();
         return response()->json([
            'status' => true,
            'action' => 'Message sent successfully',
            'action' => $message
        ]);

        }else{
            return response()->json([
                'status' => false,
                'action' => 'No group found against this userId',
                
            ]);
        }
    }

    
    public function messageList($group_id)
    {
        $messageList =  GroupMessage::where('group_id', $group_id)->get();
        if($messageList){
            return response()->json([
                'status' => true,
                'action' => 'Group messages listed',
                'data' => $messageList,
            ]);
        }
    }
}
