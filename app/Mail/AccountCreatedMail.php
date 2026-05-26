<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auth\User;

class AccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $passwordStr;
    public $permissions;

    public function __construct(User $user, $passwordStr, $permissions)
    {
        $this->user = $user;
        $this->passwordStr = $passwordStr;
        $this->permissions = $permissions;
    }

    public function build()
    {
        return $this->subject('بيانات حسابك الجديد - صندوق الزمالة كابيتال')
                    ->markdown('emails.account_created');
    }
}
