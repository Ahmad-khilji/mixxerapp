<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EditPostRequest;
use App\Http\Requests\Api\PostRequest;
use App\Models\Friend;
use App\Models\Participant;
use App\Models\Post;
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


    public function editPost(EditPostRequest $request)
    {
        $updatePost = Post::find($request->post_id);
        if (!$updatePost) {
            return response()->json([
                'status' => false,
                'action' => 'Post not found',
            ]);
        }

        if ($request->has('title')) {
            $updatePost->title = $request->title;
        }
        if ($request->has('organizedBy')) {
            $updatePost->organizedBy = $request->organizedBy;
        }
        if ($request->has('all_day')) {
            $updatePost->all_day = $request->all_day;
        }
        if ($request->has('availability')) {
            $updatePost->availability = $request->availability;
        }
        if ($request->has('repeat')) {
            $updatePost->repeat = $request->repeat;
        }
        if ($request->has('audience_limit')) {
            $updatePost->audience_limit = $request->audience_limit;
        }
        if ($request->has('event_price')) {
            $updatePost->event_price = $request->event_price;
        }
        if ($request->has('start_time')) {
            $updatePost->start_time = $request->start_time;
        }
        if ($request->has('end_time')) {
            $updatePost->end_time = $request->end_time;
        }
        if ($request->has('start_date')) {
            $updatePost->start_date = $request->start_date;
        }
        if ($request->has('end_date')) {
            $updatePost->end_date = $request->end_date;
        }
        if ($request->has('location') && $request->location) {
            $updatePost->location = $request->location;
            if ($request->has('lat') && $request->lat) {
                $updatePost->lat = $request->lat;
            }
            if ($request->has('lng') && $request->lng) {
                $updatePost->lng = $request->lng;
            }
        }

        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImagePath = $this->uploadImage($coverImage, $request->user_id, 'post');
            $updatePost->cover_image = $coverImagePath;
        }

        if ($request->hasFile('upload_images')) {
            $uploadImages = $request->file('upload_images');
            $uploadImagesPaths = [];
            foreach ($uploadImages as $image) {
                $imagePath = $this->uploadImage($image, $request->user_id, 'post');
                $uploadImagesPaths[] = $imagePath;
            }
            $updatePost->upload_images = implode(',', $uploadImagesPaths);
        }

        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $attachmentsPaths = [];
            foreach ($attachments as $attachment) {
                $attachmentPath = $this->uploadImage($attachment, $request->user_id, 'attachment');
                $attachmentsPaths[] = $attachmentPath;
            }
            $updatePost->attachments = implode(',', $attachmentsPaths);
        }

        $updatePost->save();

        return response()->json([
            'status' => true,
            'action' => 'Post updated successfully',
            'data' =>  $updatePost,
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
        $post = Post::where('id', $post_id)->first();
        if ($post) {
            $participants = Participant::where('post_id', $post_id)->where('status', 1)->get();
            
            $hostBy = User::where('uuid', $post->user_id)->first(['uuid', 'first_name', 'last_name', 'profile_image']);
            $post->hostBy = $hostBy;
    
            if ($participants->isNotEmpty()) {
                $participantsDetails = [];
                foreach ($participants as $participant) {
                    $user = User::where('uuid', $participant->user_id)->first(['first_name', 'profile_image']);
                    if ($user) {
                        $participant->user = $user;
                        $participantsDetails[] = $participant;
                    }
                }
                $post->participants = $participantsDetails;
            } else {
                $post->participants = [];
            }
    
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
    


    public function home(Request $request)
    {
        $user = User::where('uuid', $request->user_id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'action' => 'User not found',
            ]);
        }

        $friends = Friend::where('user_id', $user->uuid)->limit(12)->get();
        $friendPosts = collect();
        $friendIds = collect();

        if ($friends->isNotEmpty()) {
            $friendIds = $friends->pluck('friend_id');
            $friendPosts = Post::whereIn('user_id', $friendIds)->limit(12)->get();
        }

        $radius = $request->radius;

        $nearbyPosts = Post::selectRaw("
            posts.*, 
            (6371 * acos(cos(radians(?)) * 
            cos(radians(lat)) * 
            cos(radians(lng) - radians(?)) + 
            sin(radians(?)) * 
            sin(radians(lat)))) AS distance
        ", [$user->lat, $user->lng, $user->lat])
            ->whereNotIn('user_id', $friendIds->toArray())
            ->havingRaw('distance < ?', [$radius])
            ->orderBy('distance')
            ->get();

        return response()->json([
            'status' => true,
            'action' => 'Home',
            'data' => [
                'friendPosts' => $friendPosts,
                'nearByPosts' => $nearbyPosts
            ]
        ]);
    }

    public function search(Request $request)
    {
        $posts = Post::select('user_id', 'title', 'city', 'category', 'cover_image', 'start_date', 'end_date')
            ->where('title', 'like', '%' . $request->name . '%')
            ->orWhere('city', 'like', '%' . $request->name . '%')
            ->get();

        return response()->json([
            'status' => true,
            'action' => "Posts",
            'data' => $posts
        ]);
    }
}
