<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlockRequest;
use App\Http\Requests\Api\EditProfileRequest;
use App\Http\Requests\Api\ProfileRequest;
use App\Http\Requests\Api\RemoveSocialRequest;
use App\Http\Requests\Api\SocialConnectRequest;
use App\Http\Requests\Api\UserMessageRequest;
use App\Models\Block;
use App\Models\Friend;
use App\Models\Post;
use App\Models\SocialConnect;

use App\Models\User;
use App\Models\UserMessage;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function profile(ProfileRequest $request)
    {
        if ($request->user_id == $request->to_id) {
            $user = User::where('uuid', $request->user_id)->first();

            $posts = Post::where('user_id', $user->uuid)->get();
            // return($posts);

            $user->posts = $posts;
            $friendsList = Friend::where('user_id', $user->uuid)->get();
            $user->friendsList = $friendsList;
        } else {
            $user = User::where('uuid', $request->to_id)->first();
            $posts = Post::where('user_id', $user->uuid)->get();
            $user->posts = $posts;
            $friendsList = Friend::where('user_id', $user->uuid)->get();
            $user->friendsList = $friendsList;
        }
        return response()->json([
            'status' => true,
            'action' => 'User Profile',
            'data' => $user

        ]);
    }

    public function editProfile(EditProfileRequest $request)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'action' => 'User not found',
            ]);
        }

        if ($request->has('email')) {
            if (User::where('email', $request->email)->where('uuid', '!=', $request->user_id)->exists()) {
                return response()->json([
                    'status' => false,
                    'action' => 'Email Address is already registered'
                ]);
            } else {
                $user->email = $request->email;
            }
        }

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('uploads/user/' . $request->user_id . '/profile/', $filename)) {
                $user->profile_image = '/uploads/user/' . $request->user_id . '/profile/' . $filename;
            }
        }

        if ($request->has('first_name')) {
            $user->first_name = $request->first_name;
        }
        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }
        if ($request->has('dob')) {
            $user->dob = $request->dob;
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }
        if ($request->has('age')) {
            $user->age = $request->age;
        }
        if ($request->has('education')) {
            $user->education = $request->education;
        }
        if ($request->has('religion')) {
            $user->religion = $request->religion;
        }
        if ($request->has('occupation')) {
            $user->occupation = $request->occupation;
        }
        if ($request->has('language')) {
            $user->language = $request->language;
        }
        if ($request->has('ethnicity')) {
            $user->ethnicity = $request->ethnicity;
        }
        if ($request->has('location')) {
            $user->location = $request->location;
            if ($request->has('lat')) {
                $user->lat = $request->lat;
            }
            if ($request->has('lng')) {
                $user->lng = $request->lng;
            }
        }

        $user->save();
        return response()->json([
            'status' => true,
            'action' => 'Profile updated successfully',
            'data' =>  $user,
        ]);
    }


    public function removeImage(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if ($user) {
            $profileImagePath = public_path($user->profile_image);
            if (file_exists($profileImagePath)) {
                unlink($profileImagePath);
            }
            $user->profile_image = '';
            $user->save();

            return response()->json([
                'status' => true,
                'action' => "Profile image removed",
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "No user found against this userId"
            ]);
        }
    }

    public function socialConnect(SocialConnectRequest $request)
    {
        $existingSocial = SocialConnect::where('user_id', $request->user_id)->where('platform', $request->platform)->first();
        if ($existingSocial) {
            return response()->json([
                'status' => false,
                'action' => 'UserId and platform already exist',
            ]);
        } else {
            $socaial = new SocialConnect();
            $socaial->user_id = $request->user_id;
            $socaial->platform = $request->platform;
            $socaial->platform_id = $request->platform_id;
            $socaial->platform_email = $request->platform_email;
            $socaial->save();
            return response()->json([
                'status' => true,
                'action' => 'Social account connected',

            ]);
        }
    }

    public function removeSocial(RemoveSocialRequest $request)
    {
        // return($request);
        $existingSocial = SocialConnect::where('user_id', $request->user_id)->where('platform', $request->platform)->first();
        // return ($existingSocial);
        if ($existingSocial) {
            $existingSocial->delete();
            return response()->json([
                'status' => true,
                'action' => 'Remove social connected',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'UserId and Platform not found',
            ]);
        }
    }

    public function socialList($user_id)
    {
        $social = SocialConnect::where('user_id', $user_id)->get();
        return response()->json([
            'status' => true,
            'action' => 'Social listed',
            'data' => $social,
        ]);
    }

    public function block(BlockRequest $request)
    {
        $existingBlock = Block::where('user_id', $request->user_id)->where('block_id', $request->block_id)->first();
        if ($existingBlock) {
            $existingBlock->delete();
            return response()->json([
                'status' => false,
                'action' => 'User Unblocked successfully',
            ]);
        } else {
            $block = new Block();
            $block->user_id = $request->user_id;
            $block->block_id = $request->block_id;
            $block->save();
            return response()->json([
                'status' => true,
                'action' => 'User Blocked successfully',
            ]);
        }
    }

    public function blockList($block_id)
    {
        $blocked = Block::where('user_id', $block_id)->get();
        $user = [];
        foreach ($blocked as $block) {
            $user[] = user::where("uuid", $block->block_id)->first();
        }
        return response()->json([
            'status' => true,
            'action' => 'Block listed',
            'data' => $user,
        ]);
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

    public function usermessageList()
    {
        $userMessage = userMessage::get();
        return response()->json([
            'status' => true,
            'action' => 'User message listed',
            'data' => $userMessage,
        ]);
    }

    public function home($user_id)
    {
        $user = User::where('uuid', $user_id)->first();

        if ($user) {
            $posts = Post::where('location', '<=', 50)->get();
            // return($posts);
            $friends = Friend::where('user_id', $user_id)->get();
            // return($friends);
            if ($friends->isNotEmpty()) {

                $friendIds = $friends->pluck('friend_id');
                // return($friendIds);
                $friendPosts = Post::whereIn('user_id', $friendIds)->get();
                $posts = $posts->merge($friendPosts);
                // return($posts);
            }

            return response()->json([
                'status' => true,
                'action' => 'Home',
                'data' => $posts,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found',
            ]);
        }
    }
}
