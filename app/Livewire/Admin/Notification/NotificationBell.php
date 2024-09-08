<?php

namespace App\Livewire\Admin\Notification;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public function markAsRead($id)
    {
        $id = Notification::findOrFail($id);

        $id->update([
            'read_at' => now()
        ]);

        return redirect()->route($id->route_name, $id->model_id);
    }
    public function render()
    {
        // Get Admin as user
        $user = Auth::guard('admin')->user();

        // Get all notifications read_at = null
        $notifications = Notification::where([
            'admin_id' => $user->id,
            'read_at' => null
        ])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.notification.notification-bell', [
            'notifications' => $notifications,
        ]);
    }
}