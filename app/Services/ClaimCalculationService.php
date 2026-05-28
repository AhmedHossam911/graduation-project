<?php

namespace App\Services;

use App\Models\Services\Claim;
use App\Models\System\SystemSetting;
use Carbon\Carbon;

class ClaimCalculationService
{
    /**
     * Calculate financial details for a claim based on business rules.
     *
     * @param Claim $claim
     * @return array
     */
    public function calculate(Claim $claim)
    {
        $basic_percentage = (float) SystemSetting::get('claim_basic_percentage', 145) / 100;
        $transfer_percentage = (float) SystemSetting::get('claim_transfer_resignation_percentage', 80) / 100;
        $funeral_expenses = (float) SystemSetting::get('claim_funeral_expenses', 0);

        $membership = $claim->membership;
        $subscriptions = $membership->subscriptions;

        $joinFeeSub = $subscriptions->where('name', 'رسم الاشتراك بالصندوق')->first() 
            ?? $subscriptions->sortBy('id')->first();
        $joinFee = ($joinFeeSub && $joinFeeSub->status === 'paid') ? $joinFeeSub->amount : 0;

        $paidSubsCollection = $subscriptions->where('status', 'paid');
        if ($joinFeeSub) {
            $paidSubsCollection = $paidSubsCollection->where('id', '!=', $joinFeeSub->id);
        }

        $paidSubscriptionsAmount = $paidSubsCollection->sum('amount');
        $paidSubscriptionsCount = $paidSubsCollection->count();

        $now = Carbon::now();
        $overdueSubsCollection = $subscriptions->whereIn('status', ['unpaid', 'overdue'])->where('due_date', '<=', $now);

        $overdueSubscriptionsAmount = $overdueSubsCollection->sum('amount');
        $overdueSubscriptionsCount = $overdueSubsCollection->count();

        $totalPaid = $joinFee + $paidSubscriptionsAmount;

        if (in_array($claim->type, ['transfer', 'resignation'])) {
            $insurance_benefit = $totalPaid * $transfer_percentage;
        } else {
            $insurance_benefit = $totalPaid * $basic_percentage;
            if ($claim->type === 'death') {
                $insurance_benefit += $funeral_expenses;
            }
        }

        $remaining_loan = $membership->remaining_loan_balance;
        $net_amount = $insurance_benefit - ($remaining_loan + $overdueSubscriptionsAmount);

        $unpaidMonths = $overdueSubscriptionsCount * 3;
        $paidMonths = $paidSubscriptionsCount * 3;

        $employmentJoinDate = Carbon::parse($membership->member->employmentInfo->join_date);
        $serviceDuration = $employmentJoinDate->diff($now);
        $serviceYears = $serviceDuration->y;
        $serviceMonths = $serviceDuration->m;

        return [
            'joinFee' => $joinFee,
            'paidSubscriptionsAmount' => $paidSubscriptionsAmount,
            'insurance_benefit' => $insurance_benefit,
            'net_amount' => $net_amount,
            'unpaidMonths' => $unpaidMonths,
            'paidMonths' => $paidMonths,
            'serviceYears' => $serviceYears,
            'serviceMonths' => $serviceMonths,
            'overdueSubscriptionsAmount' => $overdueSubscriptionsAmount,
        ];
    }
}
