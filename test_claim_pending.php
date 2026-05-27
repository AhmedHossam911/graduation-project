<?php
$claim = \App\Models\Services\Claim::where('status', 'pending')->latest()->first();
if (!$claim) { echo "No pending claim.\n"; exit; }
$employmentJoinDate = \Carbon\Carbon::parse($claim->membership->member->employmentInfo->join_date);
$serviceYears = (int) $employmentJoinDate->diffInYears($claim->created_at);
$membershipJoinDate = \Carbon\Carbon::parse($claim->membership->member->created_at);
$subscriptionMonths = (int) $membershipJoinDate->diffInMonths(now());
$feesPaid = $claim->membership->fees_paid ?? 0;
$subscriptionTotal = $claim->membership->subscription_total ?? 0;
$unpaidYears = $claim->membership->subscriptions()->where('status', 'unpaid')->count();
$unpaidMonths = $unpaidYears * 12;

dump([
    'claim_id' => $claim->id,
    'claim_created_at' => $claim->created_at->format('Y-m-d'),
    'employment_join_date' => $employmentJoinDate->format('Y-m-d'),
    'service_years' => $serviceYears,
    'member_created_at' => $membershipJoinDate->format('Y-m-d'),
    'subscription_months' => $subscriptionMonths,
    'fees_paid' => $feesPaid,
    'subscription_total' => $subscriptionTotal,
    'unpaid_years' => $unpaidYears,
    'unpaid_months' => $unpaidMonths,
    'total_amount_paid' => $feesPaid + $subscriptionTotal,
    'remaining_loan_balance' => $claim->membership->remaining_loan_balance,
]);
