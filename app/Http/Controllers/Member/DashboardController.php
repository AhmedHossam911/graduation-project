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
        $status = $membership->status ?? null;
        
        // Define status map
        $statusMap = [
            'active'               => ['text' => 'نشط',                  'color' => 'text-[#019168] bg-[#D8FFE8]', 'icon' => 'material-symbols:list-alt-check-rounded'],
            'registering'          => ['text' => 'قيد التسجيل',          'color' => 'text-[#EAB308] bg-[#FEF08A]', 'icon' => 'material-symbols:how-to-reg'],
            'pending_registration' => ['text' => 'قيد الانتظار',         'color' => 'text-[#F59E0B] bg-[#FEF3C7]', 'icon' => 'material-symbols:hourglass-top-rounded'],
            'loaned'               => ['text' => 'إعارة',                'color' => 'text-[#3B82F6] bg-[#DBEAFE]', 'icon' => 'material-symbols:flight-takeoff'],
            'pension_eligible'     => ['text' => 'محال لسن التقاعد',      'color' => 'text-[#8B5CF6] bg-[#EDE9FE]', 'icon' => 'material-symbols:elderly'],
            'withdrawn'            => ['text' => 'منسحب',                'color' => 'text-[#6B7280] bg-[#F3F4F6]', 'icon' => 'material-symbols:exit-to-app'],
            'dismissed'            => ['text' => 'تم فصل العضوية',       'color' => 'text-[#EF4444] bg-[#FEE2E2]', 'icon' => 'material-symbols:person-off'],
            'unpaid_leave'         => ['text' => 'اجازة بدون مرتب',      'color' => 'text-[#F97316] bg-[#FFEDD5]', 'icon' => 'material-symbols:event-busy'],
            'membership_expired'   => ['text' => 'انتهت صلاحية العضوية',  'color' => 'text-[#9CA3AF] bg-[#F3F4F6]', 'icon' => 'material-symbols:history'],
            'suspended'            => ['text' => 'موقوف',                'color' => 'text-[#DC2626] bg-[#FEF2F2]', 'icon' => 'material-symbols:block'],
            'rejected'             => ['text' => 'مرفوض',                'color' => 'text-[#E11D48] bg-[#FFE4E6]', 'icon' => 'material-symbols:cancel-rounded'],
        ];

        $statusText  = $status && isset($statusMap[$status]) ? $statusMap[$status]['text'] : 'غير مسجل';
        $statusColor = $status && isset($statusMap[$status]) ? $statusMap[$status]['color'] : 'text-[#019168] bg-[#D8FFE8]';
        $statusIcon  = $status && isset($statusMap[$status]) ? $statusMap[$status]['icon'] : 'material-symbols:list-alt-check-rounded';
        
        // Members who are completely approved/active or any other state except pending/registering/rejected
        // should probably see the active dashboard, or at least they are not "guests".
        // Let's route active, loaned, pension_eligible, withdrawn, dismissed, unpaid_leave, membership_expired, suspended to active dashboard.
        $activeStatuses = ['active', 'loaned', 'pension_eligible', 'withdrawn', 'dismissed', 'unpaid_leave', 'membership_expired', 'suspended'];
        
        if ($membership && in_array($status, $activeStatuses)) {
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
                'activeLoan', 'lastRequests',
                'statusText', 'statusColor', 'statusIcon'
            ));
        }
        
        // Guest Logic
        $joinDate = $membership ? $membership->created_at->format('Y-m-d') : 'لم يتم التسجيل';
        $claimsCount = 0; // Guests don't have claims yet

        return view('member.guest.dashboard.index', compact(
            'user', 'membership', 'statusText', 'statusColor', 'statusIcon', 'joinDate', 'claimsCount'
        ));
    }
}
