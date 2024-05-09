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

         $reported_user = User::find($item->report_id);
                $item->user = $user;
                // dd( $item->user);
                $item->reported_user = $reported_user;

            }
// dd($reports);
            return view('report.user', compact('reports'));
        }




        if ($type == 'post') {

              $reports = Report::where('type', 'post')->get();

            foreach ($reports as $item) {

                $reported_post = User::find($item->user_id);
                // dd($reported_post);
                    $item->user= $reported_post;

                $post = Post::where('user_id', $item->report_id)->first();
    //    dd($post);
                $item->post = $post;
                // dd($item->post);
                 $item->reported_post = $reported_post;
                //  dd($item->reported_post);
            }
            // dd($reports);
            return view('report.post', compact('reports'));
        }

    
}
    
}
