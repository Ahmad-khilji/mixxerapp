<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendMessageGroup;
use App\Models\GroupMessage;
use App\Models\Participant;
use App\Models\Post;

class GroupChatController extends Controller
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
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'No group found against this Status',
            ]);
        }
    }

    public function messageList($post_id)
    {
        $messageList = GroupMessage::where('post_id', $post_id)->get();

        if ($messageList->isEmpty()) {
            return response()->json([
                'status' => false,
                'action' => 'No messages found for this group',
            ]);
        }

        return response()->json([
            'status' => true,
            'action' => 'Group messages listed',
            'data' => $messageList,
        ]);
    }

    public function userleaveGroup($post_id, $user_id)
    {

        $leftGroup =  GroupMessage::where('user_id', $user_id);
        if ($leftGroup) {
            $postId =  GroupMessage::where('post_id', $post_id);
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
    public function participantList($post_id)
    {
        $participants = Participant::where('post_id', $post_id)
            ->where('status', 1)
            ->get();

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
}
