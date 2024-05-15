<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;

class AdminReportController extends Controller
{
    public function report($type)
    {
        if ($type == 'user') {
            $reports = Report::where('type', 'user')->get();
            foreach ($reports as $item) {
                $user = User::find($item->user_id);
                $item->user = $user;
                // dd($item->user);
                $reported_user = User::find($item->report_id);
                // dd($reported_user);
                $item->reported_user = $reported_user;
                // dd($item->reported_user);
            }
            return view('report.user', compact('reports'));
        }

        if ($type == 'post') {
            $reports = Report::where('type', 'post')->get();
            foreach ($reports as $item) {
                $user = User::find($item->user_id);
                $item->user = $user;
                $post = Post::where('user_id', $item->report_id)->first();
                // dd($post);
                $item->post = $post;
                // dd($item->post);
            }
            return view('report.post', compact('reports'));
        }
    }

    public function deleteReport($id)
    {
        $find = Report::find($id);
        $find->delete();
        return redirect()->back();
    }

    public function deleteUser($user_id)
    {
        $find = User::find($user_id);
        Report::where('type', 'user')->where('report_id', $user_id)->delete();
        $find->delete();
        return redirect()->back();
    }

    public function deletePost($user_id)
    {
        $post = Post::where('user_id', $user_id)->first();
        Report::where('type', 'post')->where('report_id', $user_id)->delete();
        $post->delete();
        return redirect()->back();
    }
}
