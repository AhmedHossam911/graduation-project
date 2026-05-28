<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financial\Transaction;
use App\Models\Financial\Loan;
use App\Models\Financial\Installment;
use App\Models\Services\Subscription;
use App\Models\Services\Claim;
use App\Models\Membership\Member;
use App\Models\System\AuditLog;
use App\Models\System\Department;
use App\Http\Controllers\Employee\Finance\FinanceController;
use App\Http\Controllers\Employee\Membership\SubscriptionController;
use App\Http\Controllers\Employee\Loans\LoanController;
use App\Http\Controllers\Employee\Claims\ClaimController;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    // --- 1. بيان الإيرادات والمصروفات ---
    public function revenueExpenses(Request $request)
    {
        $query = Transaction::with(['membership.member.user', 'membership.member.department'])->latest();
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(10)->withQueryString();

        return view('admin.reports.pages.revenue_expenses', compact('transactions'));
    }

    // --- 2. الموقف المالي الختامي للصندوق ---
    public function financialPosition(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // Generate a simple, aggregated summary of the financial position for the selected year.
        $totalRevenues = Transaction::where('type', 'IN')->whereYear('created_at', $year)->sum('amount');
        $totalExpenses = Transaction::where('type', 'OUT')->whereYear('created_at', $year)->sum('amount');
        $netBalance = $totalRevenues - $totalExpenses;
        
        $activeLoansBalance = Loan::whereIn('status', ['active', 'pending'])->whereYear('created_at', $year)->sum('total_amount') 
                            - Installment::where('status', 'paid')->whereYear('created_at', $year)->sum('amount');

        return view('admin.reports.pages.financial_position', compact('totalRevenues', 'totalExpenses', 'netBalance', 'activeLoansBalance', 'year'));
    }

    public function exportFinancialPosition(Request $request)
    {
        $year = $request->input('year', date('Y'));
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\FinancialPositionExport($year), 'financial_position_' . $year . '.xlsx');
    }

    // --- 3. بيان الاستقطاعات والاشتراكات الشهرية ---
    public function subscriptions(Request $request)
    {
        $departments = Department::all();
        $query = Subscription::with(['membership.member.department'])->latest('due_date');

        if ($request->filled('department') && $request->department !== 'all') {
            $query->whereHas('membership.member', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        $subscriptions = $query->paginate(10)->withQueryString();
        return view('admin.reports.pages.subscriptions', compact('subscriptions', 'departments'));
    }

    // --- 4. المتأخرات والمديونيات المعلقة ---
    public function arrears(Request $request)
    {
        $departments = Department::all();
        $query = Subscription::with(['membership.member.department'])
            ->whereIn('status', ['unpaid', 'overdue'])
            ->latest('due_date');

        if ($request->filled('department') && $request->department !== 'all') {
            $query->whereHas('membership.member', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        $subscriptions = $query->paginate(10)->withQueryString();
        return view('admin.reports.pages.arrears', compact('subscriptions', 'departments'));
    }

    // --- 5. موقف القروض والسلف المنصرفة ---
    public function loans(Request $request)
    {
        $query = Loan::with(['membership.member'])->where('status', 'active')->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $loans = $query->paginate(10)->withQueryString();
        return view('admin.reports.pages.loans', compact('loans'));
    }

    // --- 6. بيان الأقساط والتحصيل الشهري ---
    public function installments(Request $request)
    {
        $query = Installment::with(['loan.membership.member'])->latest('due_date');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        $installments = $query->paginate(10)->withQueryString();
        return view('admin.reports.pages.installments', compact('installments'));
    }

    public function exportInstallments(Request $request)
    {
        $query = Installment::with(['loan.membership.member'])->latest('due_date');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\InstallmentsExport($query), 'installments.xlsx');
    }

    // --- 7. بيان المزايا التأمينية والمطالبات ---
    public function claims(Request $request)
    {
        $query = Claim::with(['membership.member'])->where('status', 'paid')->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $claims = $query->paginate(10)->withQueryString();
        return view('admin.reports.pages.claims', compact('claims'));
    }

    // --- 8. المطالبات المعلقة وتحت التسوية ---
    public function pendingClaims(Request $request)
    {
        $query = Claim::with(['membership.member'])->whereIn('status', ['pending', 'approved'])->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $claims = $query->paginate(10)->withQueryString();
        return view('admin.reports.pages.pending_claims', compact('claims'));
    }

    // --- 9. توزيع الأعضاء حسب الكليات ---
    public function membersDistribution(Request $request)
    {
        $departments = Department::withCount('members')->get();
        return view('admin.reports.pages.members_distribution', compact('departments'));
    }

    public function exportMembersDistribution(Request $request)
    {
        $departments = Department::withCount('members')->get();
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\MembersDistributionExport($departments), 'members_distribution.xlsx');
    }

    // --- 10. سجل نشاط النظام ---
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user')->latest();
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(10)->withQueryString();
        return view('admin.reports.pages.audit_logs', compact('logs'));
    }

    public function exportAuditLogs(Request $request)
    {
        $query = AuditLog::with('user')->latest();
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AuditLogsExport($query), 'audit_logs.xlsx');
    }
}
