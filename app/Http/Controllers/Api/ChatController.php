<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ConversationRequest;
use App\Http\Requests\Api\SendMessageGroup;
use App\Http\Requests\Api\UserMessageRequest;
use App\Models\GroupMessage;
use App\Models\Participant;
use App\Models\Post;
use App\Models\User;
use App\Models\UserMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function requestParticipation($post_id, $user_id)
    {
        $post = Post::where('id', $post_id)->where('user_id', $user_id)->first();
        if ($post) {
            $participant = new Participant();
            $participant->user_id = $user_id;
            $participant->post_id = $post_id;
            $participant->status = 0;
            $participant->save();

            return response()->json([
                'status' => true,
                'action' => 'Requested Participation',
                'data' => $participant,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'Post not found',
            ]);
        }
    }

    public function acceptParticipation($participant_id)
    {
        $participant = Participant::find($participant_id);
        if ($participant) {
            $participant->status = 1;
            $participant->save();
            $user = User::find($participant->user_id);
            $participant->user = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_image' => $user->profile_image,
            ];

            return response()->json([
                'status' => true,
                'action' => 'Accepted Participation',
                'data' => $participant,
            ]);
        }

        return response()->json([
            'status' => false,
            'action' => 'Participant Not Found',
        ]);
    }


    public function participantList(Request $request)
    {
        $participants = Participant::where('post_id', $request->post_id)
            ->where('status', 1)
            ->limit(12)->get();
        foreach ($participants as $participant) {
            $participant->user = User::where('uuid', $participant->user_id)->first(['uuid', 'first_name', 'profile_image', 'location']);
        }
        if ($participants->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'action' => 'Participant List',
                'data' => $participants,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'No Participants Found',
            ]);
        }
    }

    public function sendMessageGroup(sendMessageGroup $request)
    {
        $groupChat = Participant::where('user_id', $request->user_id)->where('post_id', $request->post_id)->where('status', 1)->first();
        if ($groupChat) {
            $message = new GroupMessage();
            $message->user_id = $request->user_id;
            $message->post_id = $request->post_id;
            $message->message = $request->message;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '-' . uniqid() . '.' . $extension;
                if ($file->move('uploads/user/' . $request->user_id . '/groupimage/', $filename)) {
                    $message->image = '/uploads/user/' . $request->user_id . '/groupimage/' . $filename;
                }
            }
            // return($message);
            $message->save();

            return response()->json([
                'status' => true,
                'action' => 'Message sent successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'No group found against this Status',
            ]);
        }
    }

    public function groupMessageList($post_id)
    {
        $messageList = GroupMessage::where('post_id', $post_id)->get();

        if ($messageList->isEmpty()) {
            return response()->json([
                'status' => false,
                'action' => 'No messages found for this group',
            ]);
        }
        foreach ($messageList as $message) {
            $user = User::where('uuid', $message->user_id)->first(['first_name', 'last_name', 'profile_image']);
            $message->user = $user;
        }

        return response()->json([
            'status' => true,
            'action' => 'Group messages listed',
            'data' => $messageList,
        ]);
    }


    public function userleaveGroup(Request $request)
    {
        $leftGroup =  GroupMessage::where('user_id', $request->user_id);
        if ($leftGroup) {
            $postId =  GroupMessage::where('post_id', $request->post_id);
            if ($postId) {
                $postId->delete();
                return response()->json([
                    'status' => true,
                    'action' => 'User leave this group',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'PostId not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => 'UserId not found',
            ]);
        }
    }



    public function userMessage(UserMessageRequest $request)
    {
        $fromUser = User::where('uuid', $request->from)->first();
        $toUser = User::where('uuid', $request->to)->first();

        if (!$fromUser || !$toUser) {
            return response()->json([
                'status' => false,
                'action' => 'User not found',
            ]);
        }
        $userMessage = new UserMessage();
        $userMessage->from = $request->from;
        $userMessage->to = $request->to;
        $userMessage->message = $request->message;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('uploads/user/'  . '/usermessageimage/', $filename)) {
                $userMessage->image = '/uploads/user/' . '/usermessageimage/' . $filename;
            }
        }
        $userMessage->save();

        return response()->json([
            'status' => true,
            'action' => 'Message sent from user to another user',
        ]);
    }

    public function conversation(ConversationRequest $request)
    {
        userMessage::where('from', $request->to)->where('to', $request->from)->where('is_read', 0)->update(['is_read' => 1]);
        $messages = userMessage::where('from', $request->to)->where('to', $request->from)->get();

        // $user = User::select('uuid', 'first_name', 'last_name', 'profile_image')->where('uuid', $request->to)->first();
        // foreach ($messages as $message) {
        //     $message->user = $user;
        // }
        return response()->json([
            'status' => true,
            'action' => 'Conversation',
            'data' => $messages,
        ]);
    }
    

    public function usermessageList()
    {
        $userMessage = userMessage::get();

        return response()->json([
            'status' => true,
            'action' => 'User message listed',
            'data' => $userMessage,
        ]);
    }


    public function participantpostList($user_id)
    {
        $participant = Participant::where('user_id', $user_id)->first();
        if (!$participant || $participant->status != 1) {
            return response()->json([
                'status' => false,
                'action' => 'Participant not found or not accepted',
            ]);
        }

        $posts = Post::whereHas('participants', function ($query) use ($participant) {
            $query->where('user_id', $participant->user_id)
                ->where('status', 1);
        })->get();

        return response()->json([
            'status' => true,
            'action' => 'Participant posts listed',
            'data' => $posts,
        ]);
    }
}
