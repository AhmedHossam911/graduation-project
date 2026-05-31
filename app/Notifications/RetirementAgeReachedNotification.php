<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Membership\Member;

class RetirementAgeReachedNotification extends Notification
{
    use Queueable;

    protected $member;

    /**
     * Create a new notification instance.
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // We send mail separately via Mailable
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'بلوغ سن التقاعد',
            'message' => 'لقد بلغت سن التقاعد وتم تحويل حالة العضوية إلى منتهية، يرجى التكرم بالتقديم على مطالبة صرف مستحقات المعاش.',
        ];
    }
}
