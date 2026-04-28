<?php

namespace App\Http\Controllers\Membership;  

use App\Http\Controllers\Controller;
use App\Models\Membership\Member;
use App\Models\Services\Membership;
use App\Models\Services\Subscription;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubscriptionsExport;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $departments = \App\Models\System\Department::all();
        
        $query = Subscription::with(['membership.member.user', 'membership.member.department']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('membership.member', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhereHas('membershipInfo', function($sq) use ($search) {
                      $sq->where('membership_number', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('department') && $request->department !== 'all') {
            $query->whereHas('membership.member', function($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest('due_date')->paginate(10)->withQueryString();
        
        // Stats for cards (dummy for now, but could be calculated)
        $stats = [
            'month_total' => Subscription::whereMonth('due_date', now()->month)->count(),
            'today_total' => Subscription::whereDate('created_at', now()->toDateString())->count(),
            'late_total' => Subscription::where('status', 'unpaid')->where('due_date', '<', now())->count(),
        ];
        
        return view('Membership.index', compact('departments', 'subscriptions', 'stats'));
    }

    public function export(Request $request)
    {
        $query = Subscription::with(['membership.member.user', 'membership.member.department']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('membership.member', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhereHas('membershipInfo', function($sq) use ($search) {
                      $sq->where('membership_number', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('department') && $request->department !== 'all') {
            $query->whereHas('membership.member', function($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $query->latest('due_date');

        return Excel::download(new SubscriptionsExport($query), 'subscriptions.xlsx');
    }
}
