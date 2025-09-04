<?php

namespace App\Notifications;

use App\Models\RepairRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepairStatusUpdated extends Notification
{
    use Queueable;

    protected $repairRequest;

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
        return ['mail', 'database']; // เพิ่ม 'database' เข้าไป
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->repairRequest->status;
        $assetNumber = $this->repairRequest->asset_number ?? 'your request';

        return (new MailMessage)
                    ->subject('Update on your Repair Request')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('The status of your repair request for asset "' . $assetNumber . '" has been updated.')
                    ->line('New Status: ' . $status)
                    ->action('View My Requests', route('repair_requests.my'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // เพิ่มเมธอดนี้เพื่อกำหนดข้อมูลที่จะบันทึกลงฐานข้อมูล
    public function toArray(object $notifiable): array
    {
        return [
            'repair_request_id' => $this->repairRequest->id,
            'asset_number' => $this->repairRequest->asset_number,
            'new_status' => $this->repairRequest->status,
            'message' => 'Your repair request for ' . ($this->repairRequest->asset_number ?? 'an asset') . ' has been updated to ' . $this->repairRequest->status . '.',
        ];
    }
}
