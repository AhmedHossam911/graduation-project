<?php

namespace App\Http\Controllers\Employee\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financial\Transaction;
use App\Models\System\AuditLog;
use App\Exports\FinanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display the finance page with stats, filtered & paginated transactions.
     */
    public function index(Request $request)
    {
        // ── Summary cards ────────────────────────────────────────────
        $totalRevenue = Transaction::where('type', 'IN')->sum('amount');
        $totalExpense = Transaction::where('type', 'OUT')->sum('amount');
        $todayCount   = Transaction::whereDate('created_at', today())->count();

        // ── Filtered queries ─────────────────────────────────────────
        $allQuery     = $this->buildFilteredQuery($request);
        $revenueQuery = $this->buildFilteredQuery($request)->where('type', 'IN');
        $expenseQuery = $this->buildFilteredQuery($request)->where('type', 'OUT');

        $transactions        = $allQuery->latest()->paginate(10, ['*'], 'page')->withQueryString();
        $revenueTransactions = $revenueQuery->latest()->paginate(10, ['*'], 'revenue_page')->withQueryString();
        $expenseTransactions = $expenseQuery->latest()->paginate(10, ['*'], 'expense_page')->withQueryString();

        // ── Label maps for the view ──────────────────────────────────
        $categoryLabels = Transaction::CATEGORY_LABELS;
        $methodLabels   = Transaction::METHOD_LABELS;

        return view('employee.finance.index', compact(
            'totalRevenue',
            'totalExpense',
            'todayCount',
            'transactions',
            'revenueTransactions',
            'expenseTransactions',
            'categoryLabels',
            'methodLabels',
        ));
    }

    /**
     * Store a new revenue or expense transaction (from the create modal).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'        => ['required', 'string', 'in:IN,OUT'],
            'category'    => ['required', 'string', 'in:' . implode(',', array_keys(
                                array_merge(Transaction::REVENUE_CATEGORIES, Transaction::EXPENSE_CATEGORIES)
                            ))],
            'method'      => ['required', 'string', 'in:' . implode(',', array_keys(Transaction::METHOD_LABELS))],
            'amount'      => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:2000'],
            'attachment'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $transaction = DB::transaction(function () use ($request, $validated) {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')
                    ->store('finance/attachments', 'public');
            }

            $transaction = Transaction::create([
                'type'            => $validated['type'],
                'category'        => $validated['category'],
                'method'          => $validated['method'],
                'amount'          => $validated['amount'],
                'description'     => $validated['description'] ?? null,
                'attachment_path' => $attachmentPath,
                'created_by'      => auth()->id(),
            ]);

            $this->logAudit('create', 'transactions', $transaction->id, null, $transaction->toArray());

            return $transaction;
        });

        return redirect()->route('finance.index')
            ->with('success', 'تم إضافة الحركة المالية بنجاح.');
    }

    /**
     * Return transaction details as JSON (for detail modals).
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['membership.member', 'creator']);

        $memberName       = $transaction->membership?->member?->full_name ?? '-';
        $membershipNumber = $transaction->membership?->membership_number ?? '-';
        $creatorName      = $transaction->creator?->name ?? '-';

        $date = Carbon::parse($transaction->created_at)
            ->locale('ar')
            ->translatedFormat('d F Y - h:i A');

        return response()->json([
            'id'                => $transaction->id,
            'transaction_number'=> $transaction->transaction_number,
            'type'              => $transaction->type,
            'type_label'        => $transaction->type_label,
            'category'          => $transaction->category,
            'category_label'    => $transaction->category_label,
            'method'            => $transaction->method,
            'method_label'      => $transaction->method_label,
            'amount'            => number_format($transaction->amount, 2),
            'description'       => $transaction->description,
            'date'              => $date,
            'member_name'       => $memberName,
            'membership_number' => $membershipNumber,
            'creator_name'      => $creatorName,
            'attachment_path'   => $transaction->attachment_path,
        ]);
    }

    /**
     * Export filtered transactions to Excel.
     */
    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $query->latest();

        return Excel::download(new FinanceExport($query), 'finance_transactions.xlsx');
    }

    /* ──────────────── Private helpers ──────────────── */

    /**
     * Build a filtered query based on the request parameters.
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Transaction::with(['membership.member']);

        // Search by member name, membership number, or transaction ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Match TRX-#### pattern
                if (preg_match('/^TRX-?(\d+)$/i', $search, $matches)) {
                    $q->where('id', $matches[1]);
                } else {
                    $q->where('id', 'LIKE', "%{$search}%")
                      ->orWhereHas('membership', function ($q2) use ($search) {
                          $q2->where('membership_number', 'LIKE', "%{$search}%")
                             ->orWhereHas('member', function ($q3) use ($search) {
                                 $q3->where('full_name', 'LIKE', "%{$search}%");
                             });
                      });
                }
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } catch (\Exception $e) {
                // Try other date formats
                try {
                    $date = Carbon::parse($request->date)->format('Y-m-d');
                    $query->whereDate('created_at', $date);
                } catch (\Exception $e2) {
                    // Ignore invalid date
                }
            }
        }

        // Filter by payment method
        if ($request->filled('method') && $request->method !== 'all') {
            $query->where('method', $request->method);
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        return $query;
    }

    /**
     * Log an audit trail entry.
     */
    private function logAudit(string $action, string $tableName, int $recordId, ?array $oldValues, ?array $newValues): void
    {
        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
        ]);
    }
}
