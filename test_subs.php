<?php
echo "Subscriptions:\n";
$subs = \App\Models\Services\Subscription::take(5)->get();
foreach($subs as $s) {
    echo $s->id . " - " . $s->name . " - " . $s->amount . " - " . $s->due_date . "\n";
}
