<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = AdminNotification::latest()->paginate(20);

        return Inertia::render('Admin/Notifications/Index', [
            'notifications' => $notifications,
            'unread_count' => AdminNotification::where('is_read', false)->count(),
        ]);
    }

    public function markAsRead($id)
    {
        AdminNotification::where('id', $id)->update(['is_read' => true]);
        return back();
    }
}
