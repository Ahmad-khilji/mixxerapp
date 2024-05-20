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
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'No found userid',
            ]);
        }
    }

    public function notificationsList(Request $request)
    {
        $notifications = Notification::get();
        return response()->json([
            'status' => true,
            'action' => 'Notifications listed successfully',
            'notifications' => $notifications,
        ]);
    }

    public function markasRead(Request $request)
    {

        $notification = Notification::where('id', $request->notification_id)
            ->where('user_id', $request->user_id)
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
                'action' => 'Notification not found',
            ]);
        }
    }
}
