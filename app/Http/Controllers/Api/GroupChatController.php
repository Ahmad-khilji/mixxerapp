<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendMessageGroup;
use App\Models\CreateGroup;
use App\Models\GroupMessage;

use App\Models\User;


class GroupChatController extends Controller
{
    public function createGroup($user_id)
    {
        $user = User::where('uuid', $user_id)->first();
        // return ( $user );
        if ($user) {
            $create = new CreateGroup();
            $create->user_id = $user_id;
            $create->first_name = $user->first_name;
            $create->save();
            // return( $create);
            return response()->json([
                'status' => true,
                'action' => 'Group created successfully',
                'data' => $create
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "No account found against this userId",
            ]);
        }
    }

    public function sendMessageGroup(SendMessageGroup $request)
    {
        $group = createGroup::where('id', $request->group_id)->first();
        if ($group) {
            $message = new GroupMessage();
            $message->group_id = $request->group_id;
            $message->user_id = $request->user_id;
            $message->message = $request->message;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '-' . uniqid() . '.' . $extension;
                if ($file->move('groupimage/user/' . $request->user_id . '/groupimage/', $filename)) {
                    $message->image = '/groupimage/user/' . $request->user_id . '/groupimage/' . $filename;
                }
            }

            $message->save();
            // return($message);
            return response()->json([
                'status' => true,
                'action' => 'Message sent successfully',
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'No group found against this groupId',
            ]);
        }
    }

    public function messageList($group_id)
    {
        $messageList =  GroupMessage::where('group_id', $group_id)->get();
        // return( $messageList );
        if ($messageList) {
            return response()->json([
                'status' => true,
                'action' => 'Group messages listed',
                'data' => $messageList,
            ]);
        }
    }
}
