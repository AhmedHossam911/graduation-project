<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Financial\Transaction;
use App\Models\Financial\Loan;
use App\Models\Financial\Installment;
use App\Models\Services\Claim;
use App\Models\Membership\Member;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // We cache the available years to avoid running a full table scan every time the dashboard loads.
        $availableYears = Cache::remember('dashboard_available_years', 3600, function () {
            $years = Transaction::select(DB::raw('YEAR(created_at) as year'))
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();
            return empty($years) ? [date('Y')] : $years;
        });

        $year = $request->input('year', $availableYears[0] ?? date('Y'));

        // Gather statistics for the top summary cards on the dashboard.
        $totalActiveMembers = Member::whereHas('membershipInfo', function($q) {
            $q->where('status', 'active');
        })->count();

        // Calculate the total amount of all currently active loans across all years.
        $totalGrantedLoans = Loan::where('status', 'active')->sum('base_amount');

        // We optimize the total fund balance calculation by using a single query instead of two separate queries for inputs and outputs.
        $fundTotals = Transaction::selectRaw('
            SUM(CASE WHEN type = "IN" THEN amount ELSE 0 END) as totalIn,
            SUM(CASE WHEN type = "OUT" THEN amount ELSE 0 END) as totalOut
        ')->first();
        $totalFundBalance = ($fundTotals->totalIn ?? 0) - ($fundTotals->totalOut ?? 0);

        // Count the number of claims that are still pending review, regardless of the year.
        $pendingClaims = Claim::where('status', 'pending')->count();

        // Fetch the 5 most recent disbursement operations (outgoing transactions) to display on the dashboard.
        $latestDisbursements = Transaction::with(['membership.member'])
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
            'latestDisbursements'
        ));
    }

    public function chartData(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // 1. Retrieve the collection status of loan installments, grouped by month, to show the paid vs. late trends.
        $installments = Installment::select(
            DB::raw('MONTH(due_date) as month'),
            DB::raw('SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid_count'),
            DB::raw('SUM(CASE WHEN status IN ("unpaid", "late") THEN 1 ELSE 0 END) as late_count')
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

        // 2. Calculate total revenues and expenses for each month to visualize financial health over the selected year.
        $transactions = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(CASE WHEN type = "IN" THEN amount ELSE 0 END) as revenue'),
            DB::raw('SUM(CASE WHEN type = "OUT" THEN amount ELSE 0 END) as expense')
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

        // 3. Calculate the percentage of member participation from each faculty/department to display in a pie chart.
        $facultyParticipation = Member::select('department_id', DB::raw('count(*) as count'))
            ->with('department:id,name')
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

        return response()->json([
            'paidInstallments' => $paidInstallments,
            'lateInstallments' => $lateInstallments,
            'revenues' => $revenues,
            'expenses' => $expenses,
            'facultyLabels' => $facultyLabels,
            'facultyData' => $facultyData,
            'facultyColors' => $facultyColors
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        if (!$q) return response()->json([]);

        $results = [];

        // Search through members by their full name, national ID, or membership number.
        $members = Member::with(['membershipInfo', 'user'])
            ->whereHas('user', function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('national_id', 'like', "%{$q}%");
            })
            ->orWhereHas('membershipInfo', function($query) use ($q) {
                $query->where('membership_number', 'like', "%{$q}%");
            })->limit(5)->get();

        foreach ($members as $member) {
            $results[] = [
                'type' => 'عضو',
                'title' => ($member->user ? $member->user->name : 'غير معروف') . ' (' . ($member->membershipInfo->membership_number ?? 'بدون رقم') . ')',
                'url' => route('members.show', $member->id),
                'icon' => 'mdi:account'
            ];
        }

        // If the query is numeric, search for specific loan records by their ID.
        if (is_numeric($q)) {
            $loans = Loan::with('membership.member.user')->where('id', $q)->limit(3)->get();
            foreach ($loans as $loan) {
                $memberName = $loan->membership->member->user->name ?? 'غير معروف';
                $memberId = $loan->membership->member->id ?? null;
                $results[] = [
                    'type' => 'قرض',
                    'title' => 'قرض يخص العضو: ' . $memberName,
                    'url' => $memberId ? route('members.show', ['member' => $memberId, 'tab' => 'loans']) : route('loans.show', $loan->id),
                    'icon' => 'mdi:cash-multiple'
                ];
            }

            // Also search for specific claim records by their ID if the query is numeric.
            $claims = Claim::with('membership.member.user')->where('id', $q)->limit(3)->get();
            foreach ($claims as $claim) {
                $memberName = $claim->membership->member->user->name ?? 'غير معروف';
                $memberId = $claim->membership->member->id ?? null;
                $results[] = [
                    'type' => 'مطالبة',
                    'title' => 'مطالبة تخص العضو: ' . $memberName,
                    'url' => $memberId ? route('members.show', ['member' => $memberId, 'tab' => 'claims']) : route('claims.show', $claim->id),
                    'icon' => 'mdi:file-document-outline'
                ];
            }

            // Finally, search for financial transactions by either their ID or receipt number.
            $transactions = Transaction::where('id', $q)
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
