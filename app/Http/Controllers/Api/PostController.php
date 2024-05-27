<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostRequest;
use App\Models\Post;
use App\Models\User;

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
            if ($file->move('uploads/user/' . $request->user_id . '/post/', $filename)) {
                $imagePaths[] = '/uploads/user/' . $request->user_id . '/post/' . $filename;
            }
        }
        $coverString = implode(',', $imagePaths);

        $addPost = new Post();
        $addPost->user_id = $request->user_id ?? '';
        $addPost->cover_image = $coverString ?? '';
        $addPost->title = $request->title ?? '';
        $addPost->category = $request->category ?? '';
        $addPost->organizedBy = $request->organizedBy ?? '';
        $addPost->start_date = $request->start_date ?? '';
        $addPost->end_date = $request->end_date ?? '';
        $addPost->all_day = $request->all_day ?? '';
        $addPost->start_time = $request->start_time ?? '';
        $addPost->end_time = $request->end_time ?? '';
        $addPost->availability = $request->availability ?? '';
        $addPost->repeat = $request->repeat ?? '';
        $addPost->audience_limit = $request->audience_limit ?? '';
        $addPost->event_price = $request->event_price ?? '';
        $addPost->password = $request->password ?? '';
        $addPost->city = $request->city ?? '';
        $addPost->address = $request->address ?? '';
        $addPost->website = $request->website ?? '';
        $addPost->registration_link = $request->registration_link ?? '';
        $addPost->zoom_link = $request->zoom_link ?? '';
        if ($request->location) {
            $addPost->location = $request->location;
            $addPost->lat = $request->lat;
            $addPost->lng = $request->lng;
        } else {
            $addPost->location = '';
            $addPost->lat = '';
            $addPost->lng = '';
        }

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
        $addPost->upload_images = $uploadString ?? '';

        $attachment = $request->file('attachments');
        $imagePaths = [];
        foreach ($attachment as $file) {
            $extension = $file->getClientOriginalExtension();
            $mime = explode('/', $file->getClientMimeType());
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('uploads/user/' . $request->user_id . '/attachment/', $filename)) {
                $imagePaths[] = '/uploads/user/' . $request->user_id . '/attachment/' . $filename;
            }
        }
        $attachmentString = implode(',', $imagePaths);
        $addPost->attachments = $attachmentString ?? '';
        $addPost->description = $request->description ?? '';
        $addPost->save();
        return response()->json([
            'status' => true,
            'action' =>  'Post created successfully',
            'data' => $addPost
        ]);
    }

    public function delete($post_id)
    {
        $post = Post::find($post_id);
        if ($post) {
            $coverImages = explode(',', $post->cover_image);
            foreach ($coverImages as $coverImage) {
                $coverImagePath = public_path($coverImage);
                if (file_exists($coverImagePath)) {
                    unlink($coverImagePath);
                }
                // return ($coverImagePath);
            }
            $uploadImages = explode(',', $post->upload_images);
            foreach ($uploadImages as $uploadImage) {
                $uploadImagePath = public_path($uploadImage);
                if (file_exists($uploadImagePath)) {
                    unlink($uploadImagePath);
                }
                // return ($uploadImagePath);
            }
            $post->delete();
            return response()->json([
                'status' => true,
                'action' => "Post deleted",
            ]);
        }
        return response()->json([
            'status' => false,
            'action' => "No post found against this postId",
        ]);
    }

    public function postDetails($post_id)
    {
        $post = Post::with(['participants' => function($query) {
            $query->where('status', 1);
        }])->find($post_id);

        if ($post) {
            return response()->json([
                'status' => true,
                'action' => 'Post Details',
                'data' => $post,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'Post Not Found',
            ]);
        }
    }
    

    public function search($name)
    {
        $posts = Post::select('user_id', 'title', 'city','category','cover_image','start_date','end_date')
            ->where('title', 'like', '%' . $name . '%')
            ->orWhere('city', 'like', '%' . $name . '%')
            ->get();

        return response()->json([
            'status' => true,
            'action' => "Posts",
            'data' => $posts
        ]);
    }

}
