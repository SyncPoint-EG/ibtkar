<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(15);

        return response()->json($notifications);
    }

    public function unread(Request $request)
    {
        $notifications = $request->user()->unreadNotifications()->paginate(15);

        return response()->json($notifications);
    }

    public function read(Request $request)
    {
        $notifications = $request->user()->readNotifications()->paginate(15);

        return response()->json($notifications);
    }

    public function show(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);

        return response()->json($notification);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read.']);
    }

    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['message' => 'Notification deleted.']);
    }

    public function destroyAll(Request $request)
    {
        $request->user()->notifications()->delete();

        return response()->json(['message' => 'All notifications deleted.']);
    }

    public function count(Request $request)
    {
        $count = $request->user()->notifications()->count();

        return response()->json(['count' => $count]);
    }

    public function unreadCount(Request $request)
    {
        $count = $request->user()->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    public function readCount(Request $request)
    {
        $count = $request->user()->readNotifications()->count();

        return response()->json(['count' => $count]);
    }
}
