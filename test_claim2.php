<?php
$claim = \App\Models\Services\Claim::with(['membership.member.employmentInfo', 'membership.subscriptions'])->latest()->first();

$firstSubscription = $claim->membership->subscriptions()->where('status', 'paid')->orderBy('created_at', 'asc')->first();
$feesPaid = $firstSubscription ? $firstSubscription->amount : 0;

$subscriptionTotal = $claim->membership->subscriptions()
    ->where('status', 'paid')
    ->where('id', '!=', $firstSubscription ? $firstSubscription->id : 0)
    ->sum('amount');

$employmentJoinDate = \Carbon\Carbon::parse($claim->membership->member->employmentInfo->join_date);
$diff = $employmentJoinDate->diff($claim->created_at);

$totalAmountPaid = $feesPaid + $subscriptionTotal;
$unpaidYears = $claim->membership->subscriptions()->where('status', 'unpaid')->count();
$unpaidMonths = $unpaidYears * 12;

$remainingLoanBalance = $claim->membership->remaining_loan_balance ?? 0;
$netPayable = $totalAmountPaid - $remainingLoanBalance;
if ($claim->type === 'death') {
    $netPayable += (float) \App\Models\System\SystemSetting::get('claim_funeral_expenses', 0);
}

dump([
    'تاريخ استلام العمل' => $employmentJoinDate->format('Y-m-d'),
    'تاريخ المطالبة' => $claim->created_at->format('Y-m-d'),
    'مدة الخدمة (سنوات وشهور)' => "{$diff->y} سنة و {$diff->m} شهر",
    'رسم العضوية المسدد' => $feesPaid,
    'إجمالي الاشتراكات المسددة (بدون رسم الانضمام)' => $subscriptionTotal,
    'إجمالي المبلغ المدفوع' => $totalAmountPaid,
    'قيمة الميزة التأمينية (الأساسية)' => $totalAmountPaid,
    'إجمالي المديونية المتبقية' => $remainingLoanBalance,
    'صافي المستحق' => $netPayable,
]);
