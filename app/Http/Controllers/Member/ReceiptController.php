<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $membership = $user->member?->membershipInfo;
        $activeStatuses = ['active', 'loaned', 'pension_eligible', 'withdrawn', 'dismissed', 'unpaid_leave', 'membership_expired', 'suspended', 'pending_registration'];

        if ($membership && in_array($membership->status, $activeStatuses)) {
            $allSubscriptions = $membership->subscriptions()->orderBy('due_date')->get();
            $paidSubscriptions = $allSubscriptions->where('status', 'paid');
            $firstUnpaidSubscription = $allSubscriptions->where('status', 'unpaid')->first();
            $subscriptions = collect($paidSubscriptions);
            if ($firstUnpaidSubscription) {
                $subscriptions->push($firstUnpaidSubscription);
            }

            $allInstallments = $membership->loans()->with('installments')->get()->pluck('installments')->flatten()->sortBy('due_date');
            $paidInstallments = $allInstallments->where('status', 'paid');
            $firstUnpaidInstallment = $allInstallments->where('status', 'unpaid')->first();
            $installments = collect($paidInstallments);
            if ($firstUnpaidInstallment) {
                $installments->push($firstUnpaidInstallment);
            }

            $allReceipts = collect();
            foreach ($subscriptions as $sub) {
                $allReceipts->push((object)[
                    'id' => 'sub_'.$sub->id,
                    'type' => 'اشتراك شهرية',
                    'title' => $sub->name,
                    'receipt_no' => 'REC-SUB-' . str_pad($sub->id, 3, '0', STR_PAD_LEFT),
                    'date' => $sub->due_date->format('Y-m-d') ?? $sub->created_at->format('Y-m-d'),
                    'amount' => $sub->amount,
                    'status' => $sub->status,
                    'icon' => 'material-symbols:list-alt-check-rounded',
                ]);
            }
            foreach ($installments as $inst) {
                $allReceipts->push((object)[
                    'id' => 'inst_'.$inst->id,
                    'type' => 'قسط قرض',
                    'title' => 'قسط قرض شخصي' . ' - ' . $inst->loan->created_at->format('Y-m'),
                    'receipt_no' => 'REC-LOAN-' . str_pad($inst->id, 3, '0', STR_PAD_LEFT),
                    'date' => $inst->due_date->format('Y-m-d') ?? $inst->created_at->format('Y-m-d'),
                    'amount' => $inst->amount,
                    'status' => $inst->status,
                    'icon' => 'ion:cash',
                ]);
            }

            // Apply Filters
            if ($request->filled('type')) {
                $allReceipts = $allReceipts->where('type', $request->type);
            }
            if ($request->filled('status')) {
                $allReceipts = $allReceipts->where('status', $request->status);
            }
            if ($request->filled('date_from')) {
                $allReceipts = $allReceipts->filter(function ($receipt) use ($request) {
                    return $receipt->date >= $request->date_from;
                });
            }
            if ($request->filled('date_to')) {
                $allReceipts = $allReceipts->filter(function ($receipt) use ($request) {
                    return $receipt->date <= $request->date_to;
                });
            }

            $allReceipts = $allReceipts->sortByDesc('date')->values();

            // Manual pagination
            $perPage = 9; // Display 9 per page
            $page = $request->get('page', 1);
            $paginatedReceipts = new LengthAwarePaginator(
                $allReceipts->forPage($page, $perPage),
                $allReceipts->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('member.active.receipts.index', compact('user', 'paginatedReceipts'));
        }

        return view('member.guest.receipts.index');
    }
}
