<?php
$claim = \App\Models\Services\Claim::with('membership.member.employmentInfo', 'membership.subscriptions')->first();
if (!$claim) {
    echo "No claim found.\n";
    exit;
}
dd([
    'join_date' => $claim->membership->member->employmentInfo->join_date ?? null,
    'member_created_at' => $claim->membership->member->created_at ?? null,
    'membership_created_at' => $claim->membership->created_at ?? null,
    'fees_paid' => $claim->membership->fees_paid ?? 0,
    'subscription_total' => $claim->membership->subscription_total ?? 0,
    'paid_count' => $claim->membership->subscriptions()->where('status', 'paid')->count(),
    'unpaid_count' => $claim->membership->subscriptions()->where('status', 'unpaid')->count(),
    'claim_amount' => $claim->amount,
    'claim_created_at' => $claim->created_at
]);
