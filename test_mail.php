<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\MemberAccountCreatedMail;
use App\Models\Membership\Member;

$member = Member::first();
Mail::to('ahmedhossam91104@gmail.com')->send(new MemberAccountCreatedMail($member, 'TestPassword123'));
echo 'Email sent successfully.';
