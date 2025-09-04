<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    /**
     * Listen for real-time events from Echo.
     */
    public function getListeners()
    {
        // เมื่อมีการแจ้งเตือนใหม่ ให้เรียกเมธอด notifyNew
        return [
            "echo-private:users.".Auth::id().",.Illuminate\\Notifications\\Events\\DatabaseNotificationCreated" => 'notifyNew',
        ];
    }

    /**
     * Dispatch an event to the frontend to trigger the shaking animation
     * and refresh the component to update the count.
     */
    public function notifyNew()
    {
        $this->dispatch('notification-received');
        // We don't need to call render() explicitly here, 
        // Livewire will re-render automatically after an action.
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Render the component.
     */
    public function render()
    {
        $user = Auth::user();

        return view('livewire.notification-bell', [
            'notifications' => $user->notifications()->latest()->take(5)->get(),
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }
}
