<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $membership = $user->member?->membershipInfo;
        
        if ($membership && $membership->status === 'active') {
            $joinDate = $membership->created_at->format('Y');
            $claimsCount = $membership->claims()->count();
            
            // Subscriptions Logic
            $latestSubscription = $membership->subscriptions()->latest()->first();
            $subscriptionStatus = 'غير مسدد';
            $subscriptionColor = 'text-[#D92D20] bg-[#FFE4E6] border-[#D92D20]';
            $subscriptionIcon = 'healthicons:no';
            $subscriptionYear = date('Y');
            $subscriptionFee = 0;
            
            if ($latestSubscription) {
                if ($latestSubscription->status === 'paid') {
                    $subscriptionStatus = 'مسدد';
                    $subscriptionColor = 'text-[#019168] bg-[#F0FFF6] border-[#019168]';
                    $subscriptionIcon = 'healthicons:yes';
                }
                $subscriptionYear = $latestSubscription->created_at->format('Y');
                $subscriptionFee = $latestSubscription->amount;
            }
            
            // Loan Logic
            $activeLoan = $membership->loans()->where('status', 'active')->first();
            
            // Recent Requests
            $recentClaims = $membership->claims()->latest()->take(3)->get();
            $recentLoans = $membership->loans()->latest()->take(3)->get();
            $lastRequests = collect($recentClaims)->merge($recentLoans)->sortByDesc('created_at')->take(3);
            
            return view('member.active.dashboard.index', compact(
                'user', 'membership', 'joinDate', 'claimsCount', 
                'subscriptionStatus', 'subscriptionColor', 'subscriptionIcon', 'subscriptionYear', 'subscriptionFee',
                'activeLoan', 'lastRequests'
            ));
        }
        
        // Guest Logic
        $statusText = 'غير مسجل';
        $statusColor = 'text-[#019168] bg-[#D8FFE8]';
        $statusIcon = 'material-symbols:list-alt-check-rounded';
        
        if ($membership) {
            if ($membership->status === 'pending') {
                $statusText = 'قيد المراجعة';
                $statusColor = 'text-[#EAB308] bg-[#FEF08A]'; // Yellow
                $statusIcon = 'material-symbols:hourglass-top-rounded';
            } elseif ($membership->status === 'rejected') {
                $statusText = 'مرفوض';
                $statusColor = 'text-[#E11D48] bg-[#FFE4E6]'; // Red
                $statusIcon = 'material-symbols:cancel-rounded';
            }
        }

        $joinDate = $membership ? $membership->created_at->format('Y-m-d') : 'لم يتم التسجيل';
        $claimsCount = 0; // Guests don't have claims yet

        return view('member.guest.dashboard.index', compact(
            'user', 'membership', 'statusText', 'statusColor', 'statusIcon', 'joinDate', 'claimsCount'
        ));
    }
}
