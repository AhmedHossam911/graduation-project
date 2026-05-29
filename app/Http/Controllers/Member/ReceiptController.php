<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $membership = $user->member?->membershipInfo;
        
        if ($membership && $membership->status === 'active') {
            $subscriptions = $membership->subscriptions()->latest()->get();
            $installments = $membership->loans()->with('installments')->get()->pluck('installments')->flatten()->sortByDesc('due_date');
            
            return view('member.active.receipts.index', compact('user', 'subscriptions', 'installments'));
        }
        
        return view('member.guest.receipts.index');
    }
}
