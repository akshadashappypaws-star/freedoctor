<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = AdminMessage::findOrFail($id);
        $notification->update(['read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read successfully.'
        ]);
    }
    
    public function markAllAsRead()
    {
        AdminMessage::where('read', false)->update(['read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read successfully.'
        ]);
    }
    
    public function destroy($id)
    {
        $notification = AdminMessage::findOrFail($id);
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully.'
        ]);
    }
    
    public function getUnreadCount()
    {
        $unreadCount = AdminMessage::where('read', false)->count();
        
        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }
}
