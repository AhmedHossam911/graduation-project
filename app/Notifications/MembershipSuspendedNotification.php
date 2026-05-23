<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Services\Membership;

class MembershipSuspendedNotification extends Notification
{
    use Queueable;

    protected $membership;

    /**
     * Create a new notification instance.
     */
    public function __construct(Membership $membership)
    {
        $this->membership = $membership;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'membership_id' => $this->membership->id,
            'message' => 'تم إيقاف العضوية رقم ' . $this->membership->membership_number . ' تلقائياً بسبب تأخر سداد الاشتراكات لأكثر من 6 أشهر وإرسال الإخطار المسجل.',
            'member_name' => $this->membership->member->full_name ?? 'غير معروف',
        ];
    }
}
