<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auth\User;

/**
 * Mailable class responsible for sending the initial welcome email to newly created Admin or Employee accounts.
 * Transmits the auto-generated password and assigned permissions securely.
 */
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
