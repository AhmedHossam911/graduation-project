<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y')); // Default to current year

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
            'year',
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
}
