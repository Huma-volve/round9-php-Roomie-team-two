<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display all notifications
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $type = $request->get('type');

        $query = AdminNotification::where('admin_id', auth()->id())
            ->with('notifiable')
            ->orderBy('created_at', 'desc');

        // استخدام Scopes
        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        }

        if ($type) {
            $query->where('type', $type);
        }

        $notifications = $query->paginate(20);
        
        // Query مباشر
        $unreadCount = AdminNotification::where('admin_id', auth()->id())
            ->unread()
            ->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount', 'filter', 'type'));
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($id)
    {
        $notification = AdminNotification::where('admin_id', auth()->id())
            ->findOrFail($id);
        
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        // بث تحديث في الوقت الفعلي
        broadcast(new \App\Events\NotificationMarkedAsRead($notification))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'تم تعليم الإشعار كمقروء'
        ]);
    }

    /**
     * Mark all as read - Query مباشر
     */
    public function markAllAsRead()
    {
        $updated = AdminNotification::where('admin_id', auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // بث تحديث العدد
        broadcast(new \App\Events\AllNotificationsMarkedAsRead(auth()->id()))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'تم تعليم جميع الإشعارات كمقروءة',
            'updated_count' => $updated
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = AdminNotification::where('admin_id', auth()->id())
            ->findOrFail($id);
        
        $notification->delete();

        // بث حدث الحذف
        broadcast(new \App\Events\NotificationDeleted($id, auth()->id()))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الإشعار'
        ]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        $count = AdminNotification::where('admin_id', auth()->id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications
     */
    public function getRecent()
    {
        $notifications = AdminNotification::where('admin_id', auth()->id())
            ->with('notifiable')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'data' => $notification->data,
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => AdminNotification::where('admin_id', auth()->id())
                ->unread()
                ->count()
        ]);
    }
}