<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlockRequest;
use App\Http\Requests\Api\EditProfileRequest;
use App\Http\Requests\Api\RemoveSocialRequest;
use App\Http\Requests\Api\SocialConnectRequest;
use App\Models\Block;
use App\Models\SocialConnect;
use Dotenv\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function editProfile(EditProfileRequest $request)
    {
        // return ($request);
        $user = User::find($request->user_id);
        if ($user) {

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


            $updateRecord = User::find($request->user_id);

            if (!$updateRecord) {
                return response()->json([
                    'status' => false,
                    'action' => 'User not found',
                ]);
            }
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $extension = $file->getClientOriginalExtension();
                $mime = explode('/', $file->getClientMimeType());
                $filename = time() . '-' . uniqid() . '.' . $extension;
                if ($file->move('uploads/user/' . $request->user_id . '/profile/', $filename))
                    $path = '/uploads/user/' . $request->user_id . '/profile/' . $filename;
                $user->profile_image = $path;
            }
            $updateRecord->profile_image = $user->profile_image;
            $updateRecord->email = $request->email;
            $updateRecord->first_name = $request->first_name;
            $updateRecord->last_name = $request->last_name;

            if ($request->location) {
                $updateRecord->location = $request->location;
                $updateRecord->lat = $request->lat;
                $updateRecord->lng = $request->lng;
            } else {
                $updateRecord->location = '';
                $updateRecord->lat = '';
                $updateRecord->lng = '';
            }

            if ($request->dob) {
                $updateRecord->dob = $request->dob;
            } else {
                $updateRecord->dob = '';
            }
            if ($request->gender) {
                $updateRecord->gender = $request->gender;
            } else {
                $updateRecord->gender = '';
            }
            if ($request->bio) {
                $updateRecord->bio = $request->bio;
            } else {
                $updateRecord->bio = '';
            }
            if ($request->age) {
                $updateRecord->age = $request->age;
            } else {
                $updateRecord->bio = '';
            }
            if ($request->education) {
                $updateRecord->education = $request->education;
            } else {
                $updateRecord->education = '';
            }
            if ($request->religion) {
                $updateRecord->religion = $request->religion;
            } else {
                $updateRecord->religion = '';
            }

            if ($request->occupation) {
                $updateRecord->occupation = $request->occupation;
            } else {
                $updateRecord->occupation = '';
            }
            if ($request->language) {
                $updateRecord->language = $request->language;
            } else {
                $updateRecord->language = '';
            }
            if ($request->ethnicity) {
                $updateRecord->ethnicity = $request->ethnicity;
            } else {
                $updateRecord->ethnicity = '';
            }
            $updateRecord->update();

            return response()->json([
                'status' => true,
                'action' => 'Profile updated successfully',
                'data' =>  $updateRecord,
            ]);
        }
    }


    public function removeImage(Request $request)
{
    $user_id = $request->user_id;

    $user = User::find($user_id);

    if ($user) {
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
            'action' => "User not found"
        ]);
    }
}


public function socialConnect(SocialConnectRequest $request)
{
    $existingSocial = SocialConnect::where('user_id', $request->user_id)->where('platform', $request->platform)->first();

    if ($existingSocial) {
        return response()->json([
            'status' => false,
            'action' => 'Already exist',
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
            'action' => 'Social account connect',

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
                'action' => 'Remove social connect',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'Platform not found',
            ]);
        }
    }


    public function socialList($user_id)
    {
        $social = SocialConnect::where('user_id', $user_id)->get();
        return response()->json([
            'status' => true,
            'action' => 'Social list',
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
                'action' => 'Unblock ',
            ]);
        } else {

            $block = new Block();
            $block->user_id = $request->user_id;
            $block->block_id = $request->block_id;
            $block->save();

            return response()->json([
                'status' => true,
                'action' => 'Block ',


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
            'action' => 'Blocked list',
            'data' => $user,
        ]);
    }


    
}
