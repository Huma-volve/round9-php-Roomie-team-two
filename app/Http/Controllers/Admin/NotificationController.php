<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        return view('admin.notifications', compact('notifications', 'unreadCount', 'filter', 'type'));
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($id)

    {
        dd($id);
        try {
            $notification = AdminNotification::where('admin_id', auth()->id())
                ->findOrFail($id);
            
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            Log::info('Notification marked as read', [
                'notification_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read', [
                'error' => $e->getMessage(),
                'notification_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read'
            ], 500);
        }
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        try {
            $updated = AdminNotification::where('admin_id', auth()->id())
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            Log::info('All notifications marked as read', [
                'count' => $updated,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'updated_count' => $updated
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all as read'
            ], 500);
        }
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        try {
            $notification = AdminNotification::where('admin_id', auth()->id())
                ->findOrFail($id);
            
            $notification->delete();

            Log::info('Notification deleted', [
                'notification_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting notification', [
                'error' => $e->getMessage(),
                'notification_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification'
            ], 500);
        }
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