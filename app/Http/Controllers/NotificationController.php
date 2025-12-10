<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class NotificationController extends Controller
{
    public function index(){
        
        $notifications = Notification::where('user_id', Auth::id())
                        ->latest()
                        ->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id){
        $notifications = Notification::where('user_id', Auth::id())
                        ->where('id', $id)
                        ->firstOrFail();

        $notifications->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllasRead(){
        $notifications = Notification::where('user_id', Auth::id())
                        ->where('is_read', false)
                        ->update(['is_read' => true]);
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id){
        $notifications = Notification::where('user_id', Auth::id())
                        ->where('id', $id)
                        ->firstOrFail();

        $notifications->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }
}
