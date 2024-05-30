<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendNotificationRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notificationSend(SendNotificationRequest $request)
    {
        $user = User::where('uuid', $request->user_id)->first();

        if ($user) {
            $newNotification = new Notification();
            $newNotification->user_id = $request->user_id;
            $newNotification->title = $request->title;
            $newNotification->message = $request->message;
            $newNotification->save();
            return response()->json([
                'status' => true,
                'action' => 'Notification sent successfully',
                'notification' => $newNotification,
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'profile_image' => $user->profile_image,
                ],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found',
            ]);
        }
    }


    public function notificationsList(Request $request)
    {
        $notifications = Notification::get();
        foreach ($notifications as $notification) {
            $user = User::where('uuid', $notification->user_id)->first(['first_name', 'last_name', 'profile_image']);
            $notification->user = $user;
        }

        return response()->json([
            'status' => true,
            'action' => 'Notifications listed successfully',
            'notifications' => $notifications,
        ]);
    }

    public function markasRead(Request $request)
    {
        $notification = Notification::where('user_id', $request->user_id)
            ->first();

        if ($notification) {
            $notification->is_read = 1;
            $notification->save();

            return response()->json([
                'status' => true,
                'action' => 'Notification marked as read',
                'notification' => $notification,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'UserId not found',
            ]);
        }
    }
}
