<?php

namespace App\Http\Controllers\Employee\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Department;
use App\Models\Membership\Member;
use App\Models\Membership\Attachment;
use App\Models\Services\Claim;
use App\Http\Requests\Employee\Membership\MemberRequest;
use App\Services\MemberService;
use Carbon\Carbon;
use Exception;

class MemberController extends Controller
{
    /**
     * Shared status map used across index, show, and other views.
     */
    public const STATUS_MAP = [
        'active'               => ['label' => 'نشط',              'class' => 'active'],
        'registering'          => ['label' => 'قيد التسجيل',      'class' => 'registering'],
        'pending_registration' => ['label' => 'قيد الانتظار',     'class' => 'pending'],
        'loaned'               => ['label' => 'إعارة',            'class' => 'loan'],
        'pension_eligible'     => ['label' => 'محال للمعاش',      'class' => 'pension'],
        'withdrawn'            => ['label' => 'منسحب',            'class' => 'withdrawn'],
        'dismissed'            => ['label' => 'مفصول',            'class' => 'dismissed'],
        'unpaid_leave'         => ['label' => 'أجازه بدون مرتب',  'class' => 'unpaid_leave'],
        'membership_expired'   => ['label' => 'منتهية العضوية',    'class' => 'expired'],
        'suspended'            => ['label' => 'موقوف',            'class' => 'suspended'],
    ];

    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    // ─── Index ───────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $departments = Department::all();

        $query = Member::with(['user', 'department', 'employmentInfo', 'membershipInfo']);

        $this->applyMemberFilters($query, $request);

        $members = $query->paginate(10)->withQueryString();

        return view('employee.members.index', [
            'departments' => $departments,
            'members'     => $members,
            'statusMap'   => self::STATUS_MAP,
        ]);
    }

    // ─── Create & Store ──────────────────────────────────────────────

    public function create()
    {
        return view('employee.members.create', [
            'documentTypes' => MemberService::INITIAL_DOCUMENT_TYPES,
            'mode'          => 'create',
            'departments'   => Department::all(),
        ]);
    }

    public function store(MemberRequest $request)
    {
        $validated = $request->validated();
        $nationalId = $request->getNationalId();

        try {
            $result = $this->memberService->createMember($validated, $request, $nationalId);

            $receiptData = [
                'name' => $result['member']->full_name,
                'national_id' => $result['member']->national_id,
                'membership_number' => $result['membership']->membership_number,
                'amount' => number_format($result['totalFee'], 2),
                'date' => now()->format('Y-m-d')
            ];

            return redirect()
                ->route('members.show', $result['member']->id)
                ->with('success', 'تم حفظ بيانات العضو بنجاح وتوليد الإيصال.')
                ->with('receipt_data', json_encode($receiptData));

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors([
                'birth_year' => $e->getMessage(),
            ]);
        }
    }

    // ─── Edit & Update ───────────────────────────────────────────────

    public function edit($id)
    {
        $member = Member::with(['user', 'department', 'employmentInfo', 'familyInfo', 'attachments'])
            ->findOrFail($id);

        $departments = Department::all();

        return view('employee.members.create', [
            'member'        => $member,
            'departments'   => $departments,
            'documentTypes' => MemberService::INITIAL_DOCUMENT_TYPES,
            'mode'          => 'edit',
        ]);
    }

    public function update(MemberRequest $request, $id)
    {
        $member = Member::with(['employmentInfo', 'familyInfo'])->findOrFail($id);
        $validated = $request->validated();
        $nationalId = $request->getNationalId();

        $this->memberService->updateMember($member, $validated, $request, $nationalId);

        return redirect()
            ->route('members.show', $member->id)
            ->with('success', 'تم تحديث بيانات العضو بنجاح.');
    }

    // ─── Show & Print ────────────────────────────────────────────────

    public function show($id)
    {
        $member = Member::with([
            'user', 'department', 'employmentInfo', 'familyInfo',
            'membershipInfo.claims',
            'membershipInfo.subscriptions',
            'membershipInfo.loans.installments',
        ])->findOrFail($id);

        $totalPaidSubscriptions = 0;
        $activeLoan = null;
        $hasOverdueInstallment6Months = false;

        if ($member->membershipInfo) {
            $totalPaidSubscriptions = $member->membershipInfo->subscriptions()->where('status', 'paid')->sum('amount');
            
            // Only 1 active/pending/approved loan is allowed at a time based on LoanController rules
            $activeLoan = $member->membershipInfo->loans()
                ->whereIn('status', ['active', 'pending', 'approved'])
                ->first();

            // Check for any subscription overdue for 6+ months
            $hasOverdueSubscription6Months = $member->membershipInfo->subscriptions()
                ->whereIn('status', ['unpaid', 'overdue'])
                ->where('due_date', '<=', now()->subMonths(6))
                ->exists();

            // Check for any installment overdue for 6+ months
            $hasOverdueInstallment6Months = \App\Models\Financial\Installment::whereHas('loan', function($query) use ($member) {
                    $query->where('membership_id', $member->membershipInfo->id);
                })
                ->whereIn('status', ['unpaid', 'overdue'])
                ->where('due_date', '<=', now()->subMonths(6))
                ->exists();

            // User requested notice is ONLY for subscriptions
            $hasOverdue6Months = $hasOverdueSubscription6Months;
        }

        return view('employee.members.show', [
            'member'                 => $member,
            'statusMap'              => self::STATUS_MAP,
            'claimTypes'             => Claim::CLAIM_TYPES,
            'totalPaidSubscriptions' => $totalPaidSubscriptions,
            'activeLoan'             => $activeLoan,
            'hasOverdue6Months'      => $hasOverdue6Months ?? false,
        ]);
    }

    // ─── Documents ───────────────────────────────────────────────────

    public function documents(Request $request, $id)
    {
        $member = Member::with('attachments')->findOrFail($id);

        return view('employee.documents.index', [
            'member' => $member,
        ]);
    }

    public function storeAdditionalDocument(Request $request, $id)
    {
        $request->validate([
            'document_name' => ['required', 'string', 'max:255'],
            'document_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $member = Member::findOrFail($id);

        $this->memberService->storeAdditionalDocument($member, $request->file('document_file'), $request->document_name);

        return redirect()->back()->with('success', 'تم إرفاق المستند بنجاح.');
    }

    public function viewDocument($id)
    {
        $attachment = Attachment::findOrFail($id);
        $path = storage_path('app/public/' . $attachment->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        $member = Member::find($attachment->member_id);
        $memberName = $member ? $member->full_name : 'عضو';
        $documentTypes = MemberService::INITIAL_DOCUMENT_TYPES;
        $docTypeLabel = $documentTypes[$attachment->type] ?? $attachment->type;
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', "{$memberName} - {$docTypeLabel}");
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return response()->file($path, [
            'Content-Disposition' => 'inline; filename="' . $fileName . '.' . $extension . '"'
        ]);
    }

    public function downloadDocument($id)
    {
        $attachment = Attachment::findOrFail($id);
        $path = storage_path('app/public/' . $attachment->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        $member = Member::find($attachment->member_id);
        $memberName = $member ? $member->full_name : 'عضو';
        $documentTypes = MemberService::INITIAL_DOCUMENT_TYPES;
        $docTypeLabel = $documentTypes[$attachment->type] ?? $attachment->type;
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', "{$memberName} - {$docTypeLabel}");
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return response()->download($path, "{$fileName}.{$extension}");
    }

    // ─── Signed Form Upload ──────────────────────────────────────────

    public function uploadSignedState($id)
    {
        $member = Member::with(['user', 'department', 'employmentInfo', 'familyInfo', 'attachments'])
            ->findOrFail($id);

        return view('employee.members.create', [
            'member'        => $member,
            'departments'   => Department::all(),
            'documentTypes' => MemberService::INITIAL_DOCUMENT_TYPES,
            'mode'          => 'upload_signed',
        ]);
    }

    public function uploadSignedForm(Request $request, $id)
    {
        $request->validate([
            'documents.signed_membership_form' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $member = Member::with('membershipInfo')->findOrFail($id);
        $this->memberService->uploadSignedForm($member, $request->file('documents.signed_membership_form'));

        return redirect()
            ->route('members.show', $member->id)
            ->with('success', 'تم رفع الاستمارة وإنشاء الاشتراك بنجاح.');
    }

    // ─── Suspend Membership ──────────────────────────────────────────

    public function suspend(Request $request, $id)
    {
        $request->validate([
            'reason'          => ['required', 'string', 'max:1000'],
            'suspension_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $member = Member::with('membershipInfo')->findOrFail($id);

        $this->memberService->suspendMember($member, $request->reason, $request->file('suspension_file'));

        return redirect()
            ->route('members.show', $member->id)
            ->with('success', 'تم إيقاف العضوية بنجاح.');
    }

    // ─── Destroy (Soft Delete) ───────────────────────────────────────

    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        $this->memberService->deleteMember($member);

        return redirect()
            ->route('members.index')
            ->with('success', 'تم حذف العضو بنجاح.');
    }

    // ─── Private Helpers ─────────────────────────────────────────────

    /**
     * Apply search, status, and department filters to a member query.
     */
    private function applyMemberFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhereHas('membershipInfo', function ($sq) use ($search) {
                      $sq->where('membership_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            $query->whereHas('membershipInfo', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('department_id', $request->department);
        }
    }

    /**
     * Send official notice for overdue payments (subscriptions > 6 months).
     */
    public function notify(Request $request, Member $member)
    {
        if (!$member->membershipInfo) {
            return back()->with('error', 'لا يوجد اشتراك متاح لهذا العضو.');
        }

        // Update notice_sent_at for subscriptions that are overdue for 6+ months
        $member->membershipInfo->subscriptions()
            ->whereIn('status', ['unpaid', 'overdue'])
            ->where('due_date', '<=', now()->subMonths(6))
            ->whereNull('notice_sent_at')
            ->update([
                'notice_sent_at' => now()
            ]);

        // Log the action
        \App\Models\System\AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'send_official_notice',
            'table_name' => 'memberships',
            'record_id'  => $member->membershipInfo->id,
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'تم إرسال الإخطار المسجل وتحديث الحالة.');
    }
}
