<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RemoveFriend;
use App\Models\FriendRequest;
use App\Http\Requests\Api\sendRequest;
use App\Models\Friend;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{

    public function sendRequest(sendRequest $request)
    {
        $createRequest = new FriendRequest();

            $createRequest->sender_id = $request->sender_id;
        $createRequest->receiver_id = $request->receiver_id;
         $createRequest->save();

return response()->json([
            'status' => true,
            'action' => 'Friend request sent successfully.',
            'data' => $createRequest
        ]);
    }



    public function acceptRequest(sendRequest $request)
    {
        $friendRequest = FriendRequest::where('sender_id', $request->sender_id)
            ->where('receiver_id', $request->receiver_id)
            ->first();

        if ($friendRequest) {
            
            $acceptRequest= new Friend();

     $acceptRequest->user_id= $request->receiver_id;
             $acceptRequest->friend_id= $request->sender_id;

            $acceptRequest->save();

            $friendRequest->delete();

            return response()->json([
                'status' => true,
                'action' => 'Friend request accepted.',
            ]);
        } else {
            return response()->json([
                'status' => true,
                'action' => 'No friend request found.',
            ]);
        }
    }


    public function cancelRequest(sendRequest $request)
    {
       

        $friendRequest = FriendRequest::where('sender_id', $request->sender_id)
            ->where('receiver_id', $request->receiver_id)
            ->first();

        if ($friendRequest) {

            $friendRequest->delete();

            return response()->json([
                'status' => true,
                'action' => 'Friend request canceled.',
            ]);
        } else {
            return response()->json([
                'status' => true,
                'action' => 'No friend request found.',
            ]);
        }
    }

public function removeFriend(RemoveFriend $request)
    {
        Friend::where('user_id', $request->user_id)
            ->where('friend_id', $request->friend_id)
            ->delete();

        return response()->json(['message' => 'Friend removed.']);
    }

}
