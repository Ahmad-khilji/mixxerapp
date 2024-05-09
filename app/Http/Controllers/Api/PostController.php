<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostRequest;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function createPost(PostRequest $request)
    {
// return ( $request);
        $images = $request->file('cover_image');

        $imagePaths = [];

        foreach ($images as $file) {
            $extension = $file->getClientOriginalExtension();
            $mime = explode('/', $file->getClientMimeType());
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('covers/user/' . $request->user_id . '/post/', $filename)) {
                $imagePaths[] = '/covers/user/' . $request->user_id . '/post/' . $filename;
            }
        }
        $coverString = implode(',', $imagePaths);

        $addPost = new Post();
        $addPost->user_id = $request->user_id;
        $addPost->cover_image = $coverString;
        $addPost->title = $request->title;
        $addPost->category = $request->category;
        $addPost->organizedBy = $request->organizedBy;
        $addPost->start_date = $request->start_date;
        $addPost->end_date = $request->end_date;
        $addPost->all_day = $request->all_day;
        $addPost->start_time = $request->start_time;
        $addPost->end_time = $request->end_time;
        $addPost->availability = $request->availability;
        $addPost->repeat = $request->repeat;
        $addPost->audience_limit = $request->audience_limit;
        $addPost->event_price = $request->event_price;
        $addPost->password = $request->password;
        $addPost->city = $request->city;
        $addPost->address = $request->address;
        $addPost->website = $request->website;
        $addPost->registration_link = $request->registration_link;
        $addPost->zoom_link = $request->zoom_link;

        $images = $request->file('upload_images');

        $imagePaths = [];

        foreach ($images as $file) {
            $extension = $file->getClientOriginalExtension();
            $mime = explode('/', $file->getClientMimeType());
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('uploads/user/' . $request->user_id . '/post/', $filename)) {
                $imagePaths[] = '/uploads/user/' . $request->user_id . '/post/' . $filename;
            }
        }
        $uploadString = implode(',', $imagePaths);
        $addPost->upload_images = $uploadString;


        $attachment = $request->file('attachments');
        $imagePaths = [];
        foreach ($attachment as $file) {
            $extension = $file->getClientOriginalExtension();
            $mime = explode('/', $file->getClientMimeType());
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('attachment/user/' . $request->user_id . '/attachment/', $filename)) {
                $imagePaths[] = '/attachment/user/' . $request->user_id . '/attachment/' . $filename;
            }
        }
        $attachmentString = implode(',', $imagePaths);

        $addPost->attachments = $attachmentString;
        $addPost->description = $request->description;
        $addPost->save();
        return response()->json([
            'status' => true,
            'action' =>  'Post Create',
            'data' => $addPost
        ]);
    }


    


    public function delete($post_id)
    {
       
        $post = Post::find($post_id);

        if ($post) {
            $post->delete();
    return response()->json([
                'status' => true,
                'action' =>  "Post deleted",
            ]);
        }
        return response()->json([
            'status' => false,
            'action' =>  "No post found",
        ]);
    }


}
