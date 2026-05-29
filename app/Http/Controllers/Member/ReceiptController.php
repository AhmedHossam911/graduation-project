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
        
        if ($membership && $membership->status === 'active') {
            $subscriptions = $membership->subscriptions()->latest()->get();
            $installments = $membership->loans()->with('installments')->get()->pluck('installments')->flatten();
            
            $allReceipts = collect();
            foreach ($subscriptions as $sub) {
                $allReceipts->push((object)[
                    'id' => 'sub_'.$sub->id,
                    'type' => 'اشتراك شهرية',
                    'title' => 'رسوم اشتراك العضوية',
                    'receipt_no' => 'REC-SUB-' . str_pad($sub->id, 3, '0', STR_PAD_LEFT),
                    'date' => $sub->created_at->format('Y-m-d'),
                    'amount' => $sub->amount,
                    'status' => $sub->status,
                    'icon' => 'material-symbols:list-alt-check-rounded',
                ]);
            }
            foreach ($installments as $inst) {
                $allReceipts->push((object)[
                    'id' => 'inst_'.$inst->id,
                    'type' => 'قسط قرض',
                    'title' => 'قسط قرض شخصي',
                    'receipt_no' => 'REC-LOAN-' . str_pad($inst->id, 3, '0', STR_PAD_LEFT),
                    'date' => $inst->due_date ?? $inst->created_at->format('Y-m-d'),
                    'amount' => $inst->amount,
                    'status' => $inst->status,
                    'icon' => 'ion:cash',
                ]);
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
