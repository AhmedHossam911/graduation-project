<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Calculate available years dynamically from transactions
        $availableYears = \App\Models\Financial\Transaction::select(\Illuminate\Support\Facades\DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        $year = $request->input('year', $availableYears[0] ?? date('Y'));

        // Top Cards Statistics
        $totalActiveMembers = \App\Models\Membership\Member::whereHas('membershipInfo', function($q) {
            $q->where('status', 'active');
        })->count();

        // Total Granted Loans (All active loans, not filtered by year)
        $totalGrantedLoans = \App\Models\Financial\Loan::where('status', 'active')
            ->sum('total_amount');

        // Total Fund Balance
        $totalFundBalance = \App\Models\Financial\Transaction::where('type', 'IN')->sum('amount') 
                          - \App\Models\Financial\Transaction::where('type', 'OUT')->sum('amount');

        // Pending Claims (All pending claims, not filtered by year)
        $pendingClaims = \App\Models\Services\Claim::where('status', 'pending')
            ->count();

        // 1. Loan Installments Collection Status (Count of paid vs late/unpaid by month)
        $installments = \App\Models\Financial\Installment::select(
            \Illuminate\Support\Facades\DB::raw('MONTH(due_date) as month'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid_count'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN status IN ("unpaid", "late") THEN 1 ELSE 0 END) as late_count')
        )
        ->whereYear('due_date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->keyBy('month');

        $loanMonths = range(1, 12);
        $paidInstallments = [];
        $lateInstallments = [];
        foreach ($loanMonths as $m) {
            $paidInstallments[] = $installments->has($m) ? (int)$installments[$m]->paid_count : 0;
            $lateInstallments[] = $installments->has($m) ? (int)$installments[$m]->late_count : 0;
        }

        // 2. Revenues and Expenses by month
        $transactions = \App\Models\Financial\Transaction::select(
            \Illuminate\Support\Facades\DB::raw('MONTH(created_at) as month'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN type = "IN" THEN amount ELSE 0 END) as revenue'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN type = "OUT" THEN amount ELSE 0 END) as expense')
        )
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->keyBy('month');

        $revenues = [];
        $expenses = [];
        foreach ($loanMonths as $m) {
            $revenues[] = $transactions->has($m) ? (float)$transactions[$m]->revenue : 0;
            $expenses[] = $transactions->has($m) ? (float)$transactions[$m]->expense : 0;
        }

        // 3. Faculty Participation Percentages
        $facultyParticipation = \App\Models\Membership\Member::select('department_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->with('department')
            ->groupBy('department_id')
            ->get();
            
        $totalMembers = $facultyParticipation->sum('count');
        
        $facultyLabels = [];
        $facultyData = [];
        $facultyColors = ['#124375', '#D4AF37', '#60A5FA', '#93C5FD', '#1E3A8A', '#FBBF24', '#FCD34D', '#1D4ED8', '#BFDBFE', '#2563EB'];
        
        foreach ($facultyParticipation as $index => $item) {
            $facultyLabels[] = $item->department ? $item->department->name : 'غير محدد';
            $facultyData[] = $totalMembers > 0 ? round(($item->count / $totalMembers) * 100, 1) : 0;
        }

        // 4. Latest Disbursement Operations
        $latestDisbursements = \App\Models\Financial\Transaction::with(['membership.member'])
            ->where('type', 'OUT')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'availableYears',
            'year',
            'totalActiveMembers',
            'totalGrantedLoans',
            'totalFundBalance',
            'pendingClaims',
            'paidInstallments',
            'lateInstallments',
            'revenues',
            'expenses',
            'facultyLabels',
            'facultyData',
            'facultyColors',
            'latestDisbursements'
        ));
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        if (!$q) return response()->json([]);

        $results = [];

        // Search Members (Name, National ID, Membership Number)
        $members = \App\Models\Membership\Member::with('membershipInfo')
            ->where('full_name', 'like', "%{$q}%")
            ->orWhere('national_id', 'like', "%{$q}%")
            ->orWhereHas('membershipInfo', function($query) use ($q) {
                $query->where('membership_number', 'like', "%{$q}%");
            })->limit(5)->get();

        foreach ($members as $member) {
            $results[] = [
                'type' => 'عضو',
                'title' => $member->full_name . ' (' . ($member->membershipInfo->membership_number ?? 'بدون رقم') . ')',
                'url' => route('members.show', $member->id),
                'icon' => 'mdi:account'
            ];
        }

        // Search Loans
        if (is_numeric($q)) {
            $loans = \App\Models\Financial\Loan::where('id', $q)->limit(3)->get();
            foreach ($loans as $loan) {
                $results[] = [
                    'type' => 'قرض',
                    'title' => 'قرض رقم #' . $loan->id,
                    'url' => route('loans.show', $loan->id),
                    'icon' => 'mdi:cash-multiple'
                ];
            }

            // Search Claims
            $claims = \App\Models\Services\Claim::where('id', $q)->limit(3)->get();
            foreach ($claims as $claim) {
                $results[] = [
                    'type' => 'مطالبة',
                    'title' => 'مطالبة رقم #' . $claim->id,
                    'url' => route('claims.show', $claim->id),
                    'icon' => 'mdi:file-document-outline'
                ];
            }

            // Search Transactions (Receipt No or ID)
            $transactions = \App\Models\Financial\Transaction::where('id', $q)
                ->orWhere('receipt_no', 'like', "%{$q}%")
                ->limit(3)->get();
            foreach ($transactions as $transaction) {
                $results[] = [
                    'type' => 'معاملة مالية',
                    'title' => 'حركة رقم #' . $transaction->id . ($transaction->receipt_no ? ' - إيصال: ' . $transaction->receipt_no : ''),
                    'url' => route('finance.show', $transaction->id),
                    'icon' => 'mdi:cash-register'
                ];
            }
        }

        return response()->json($results);
    }
}
