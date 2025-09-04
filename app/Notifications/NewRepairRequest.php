<?php

namespace App\Notifications;

use App\Models\RepairRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // ▼▼▼ 1. เพิ่ม use ใหม่ ▼▼▼

//                                          ▼▼▼ 2. เพิ่ม implements ShouldBroadcast ▼▼▼
class NewRepairRequest extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $repairRequest; // แก้เป็น public เพื่อให้ toBroadcast เข้าถึงได้

    /**
     * Create a new notification instance.
     */
    public function __construct(RepairRequest $repairRequest)
    {
        $this->repairRequest = $repairRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // ▼▼▼ 3. เพิ่ม 'broadcast' เข้าไปใน array ▼▼▼
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for the database.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $userName = $this->repairRequest->user->name;
        $assetType = $this->repairRequest->asset_type;

        return [
            'repair_request_id' => $this->repairRequest->id,
            'message' => "New repair request for a {$assetType} submitted by {$userName}.",
            'url' => route('repair_requests.edit', $this->repairRequest->id),
        ];
    }
    
    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): array
    {
        // เราสามารถใช้ข้อมูลเดียวกับที่เก็บลง database ได้เลย
        // Laravel จะแนบข้อมูลพื้นฐานเช่น ID และ type ของ notification ไปให้โดยอัตโนมัติ
        return $this->toArray($notifiable);
    }
}
