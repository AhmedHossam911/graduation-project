<?php

namespace App\Http\Controllers\Employee\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Department;
use App\Models\System\AuditLog;
use App\Models\Membership\Member;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\FamilyInfo;
use App\Models\Membership\Attachment;
use App\Models\Services\Membership;
use App\Models\Services\Claim;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Shared status map used across index, show, and other views.
     */
    public const STATUS_MAP = [
        'active'       => ['label' => 'نشط',              'class' => 'active'],
        'registering'  => ['label' => 'قيد التسجيل',      'class' => 'registering'],
        'pending'      => ['label' => 'قيد الانتظار',     'class' => 'pending'],
        'loan'         => ['label' => 'إعارة',            'class' => 'loan'],
        'pension'      => ['label' => 'محال للمعاش',      'class' => 'pension'],
        'withdrawn'    => ['label' => 'منسحب',            'class' => 'withdrawn'],
        'dismissed'    => ['label' => 'مفصول',            'class' => 'dismissed'],
        'unpaid_leave' => ['label' => 'إجازة بدون راتب',  'class' => 'unpaid_leave'],
        'expired'      => ['label' => 'منتهي العضوية',    'class' => 'expired'],
        'suspended'    => ['label' => 'موقوف',            'class' => 'suspended'],
    ];

    /**
     * Document types required for initial membership registration.
     */
    private const INITIAL_DOCUMENT_TYPES = [
        'national_id_card'    => 'بطاقة الرقم القومي',
        'basic_salary_letter' => 'خطاب الأجر الأساسي',
        'work_declaration'    => 'إقرار القيام بالعمل',
        'over_21_request'     => 'طلب تجاوز فوق سن ٢١ عام',
        'appointment_decision'=> 'قرار التعيين',
    ];

    // ─── Validation Rules ────────────────────────────────────────────

    /**
     * Core validation rules shared between store and update.
     */
    private function memberValidationRules(): array
    {
        return [
            'full_name'              => ['required', 'string', 'max:255'],
            'email'                  => ['nullable', 'email', 'max:255'],
            'department_id'          => ['nullable', 'exists:departments,id'],
            'national_id_digits'     => ['required', 'array', 'size:14'],
            'national_id_digits.*'   => ['required', 'digits:1'],
            'phone_digits'           => ['nullable', 'array'],
            'phone_digits.*'         => ['nullable', 'digits:1'],
            'landline_digits'        => ['nullable', 'array'],
            'landline_digits.*'      => ['nullable', 'digits:1'],
            'birth_day'              => ['nullable', 'integer', 'between:1,31'],
            'birth_month'            => ['nullable', 'integer', 'between:1,12'],
            'birth_year'             => ['nullable', 'integer', 'between:1900,2100'],
            'address'                => ['nullable', 'string', 'max:1000'],
            'marital_status'         => ['required', 'string', 'in:متزوج,مطلق,أعزب,أرمل'],
            'employer_name'          => ['required', 'string', 'max:255'],
            'job_title'              => ['required', 'string', 'max:255'],
            'financial_category'     => ['required', 'string', 'max:255'],
            'hire_day'               => ['nullable', 'integer', 'between:1,31'],
            'hire_month'             => ['nullable', 'integer', 'between:1,12'],
            'hire_year'              => ['nullable', 'integer', 'between:1900,2100'],
            'retirement_day'         => ['nullable', 'integer', 'between:1,31'],
            'retirement_month'       => ['nullable', 'integer', 'between:1,12'],
            'retirement_year'        => ['nullable', 'integer', 'between:1900,2100'],
            'salary'                 => ['nullable', 'numeric', 'min:0'],
            'children_count'         => ['nullable', 'integer', 'min:0'],
            'spouse_phone_digits'    => ['nullable', 'array'],
            'spouse_phone_digits.*'  => ['nullable', 'digits:1'],
            'spouse_name'            => ['nullable', 'string', 'max:255'],
            'spouse_workplace'       => ['nullable', 'string', 'max:255'],
            'child_name'             => ['nullable', 'string', 'max:255'],
            'child_workplace'        => ['nullable', 'string', 'max:255'],
            'documents'              => ['nullable', 'array'],
            'documents.*'            => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function memberValidationMessages(): array
    {
        return [
            'required'                 => 'هذا الحقل مطلوب ولا يمكن تركه فارغاً.',
            'string'                   => 'يجب إدخال بيانات نصية صحيحة.',
            'max'                      => 'يجب ألا يزيد عدد الأحرف عن :max حرفاً.',
            'email'                    => 'يرجى إدخال بريد إلكتروني صحيح بصيغة صحيحة.',
            'exists'                   => 'القيمة التي قمت باختيارها غير موجودة.',
            'array'                    => 'يجب أن يحتوي الحقل على مجموعة من القيم.',
            'size'                     => 'يجب أن يحتوي الحقل على :size رقم بالضبط.',
            'digits'                   => 'يجب إدخال :digits رقم بالضبط.',
            'integer'                  => 'يجب إدخال رقم صحيح بدون كسور.',
            'between'                  => 'يجب أن تكون القيمة بين :min و :max.',
            'in'                       => 'القيمة المختارة غير صحيحة، يرجى اختيار قيمة من القائمة.',
            'numeric'                  => 'يجب إدخال رقم صالح.',
            'min'                      => 'يجب ألا تقل القيمة عن :min.',
            'mimes'                    => 'نوع الملف غير مدعوم. الأنواع المسموحة: :values.',
            'documents.*.max'          => 'حجم الملف كبير جداً، الحد الأقصى 5 ميجابايت.',
            'national_id_digits.required' => 'من فضلك أدخل الرقم القومي كاملاً.',
            'national_id_digits.size'     => 'الرقم القومي يجب أن يتكون من 14 رقم بالضبط.',        ];
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
            'documentTypes' => self::INITIAL_DOCUMENT_TYPES,
            'mode'          => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->memberValidationRules(), $this->memberValidationMessages());

        $nationalId = implode('', $validated['national_id_digits']);

        // Ensure national ID is unique
        $request->validate([
            'national_id_digits' => [
                function ($attribute, $value, $fail) use ($nationalId) {
                    if (Member::where('national_id', $nationalId)->exists()) {
                        $fail('الرقم القومي مسجل من قبل.');
                    }
                },
            ],
        ]);

        // Business rule: member must be 45 years old or younger
        $birthDate = $this->dateFromParts($request, 'birth');
        if ($birthDate) {
            $age = \Carbon\Carbon::parse($birthDate)->age;
            if ($age > 45) {
                return redirect()->back()->withInput()->withErrors([
                    'birth_year' => 'لا يمكن تسجيل عضو يتجاوز عمره 45 عامًا. العمر الحالي: ' . $age . ' عامًا.',
                ]);
            }
        }

        $member = DB::transaction(function () use ($request, $validated, $nationalId) {

            $departmentId = $this->resolveDepartmentId($validated);

            $member = Member::create([
                'user_id'        => auth()->id(),
                'department_id'  => $departmentId,
                'full_name'      => $validated['full_name'],
                'national_id'    => $nationalId,
                'birth_date'     => $this->dateFromParts($request, 'birth'),
                'phone'          => $this->digitsToString($request, 'phone_digits'),
                'landline'       => $this->digitsToString($request, 'landline_digits'),
                'address'        => $validated['address'] ?? null,
                'marital_status' => $validated['marital_status'],
            ]);

            Membership::create([
                'member_id'            => $member->id,
                'membership_number'    => 'MS-' . str_pad($member->id, 5, '0', STR_PAD_LEFT),
                'status'               => 'pending',
                'declaration_accepted' => true,
                'approved_by'          => null,
            ]);

            EmploymentInfo::create([
                'member_id'          => $member->id,
                'workplace'          => $validated['employer_name'],
                'job_title'          => $validated['job_title'],
                'financial_category' => $validated['financial_category'],
                'join_date'          => $this->dateFromParts($request, 'hire'),
                'retirement_date'    => $this->dateFromParts($request, 'retirement'),
                'starting_salary'    => $validated['salary'] ?? null,
            ]);

            FamilyInfo::create([
                'member_id'       => $member->id,
                'children_count'  => $validated['children_count'] ?? 0,
                'spouse_name'     => $this->nullIfPlaceholder($validated['spouse_name'] ?? null),
                'spouse_phone'    => $this->digitsToString($request, 'spouse_phone_digits'),
                'spouse_workplace'=> $this->nullIfPlaceholder($validated['spouse_workplace'] ?? null),
                'child_name'      => $this->nullIfPlaceholder($validated['child_name'] ?? null),
                'child_workplace' => $this->nullIfPlaceholder($validated['child_workplace'] ?? null),
            ]);

            // Store uploaded documents
            foreach (self::INITIAL_DOCUMENT_TYPES as $type => $label) {
                if ($request->hasFile("documents.$type")) {
                    $this->storeDocument($member, $request->file("documents.$type"), $type);
                }
            }

            // Audit log
            $this->logAudit('create', 'members', $member->id, null, $member->toArray());

            return $member;
        });

        return redirect()
            ->route('members.print', $member)
            ->with('success', 'تم حفظ بيانات العضو.');
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
            'documentTypes' => self::INITIAL_DOCUMENT_TYPES,
            'mode'          => 'edit',
        ]);
    }

    public function update(Request $request, $id)
    {
        $member = Member::with(['employmentInfo', 'familyInfo'])->findOrFail($id);

        $validated = $request->validate($this->memberValidationRules(), $this->memberValidationMessages());

        $nationalId = implode('', $validated['national_id_digits']);

        // Ensure national ID is unique (excluding current member)
        $request->validate([
            'national_id_digits' => [
                function ($attribute, $value, $fail) use ($nationalId, $member) {
                    if (Member::where('national_id', $nationalId)->where('id', '!=', $member->id)->exists()) {
                        $fail('الرقم القومي مسجل من قبل.');
                    }
                },
            ],
        ]);

        DB::transaction(function () use ($request, $validated, $nationalId, $member) {

            $oldValues = $member->toArray();

            $departmentId = $this->resolveDepartmentId($validated);

            $member->update([
                'department_id'  => $departmentId,
                'full_name'      => $validated['full_name'],
                'national_id'    => $nationalId,
                'birth_date'     => $this->dateFromParts($request, 'birth'),
                'phone'          => $this->digitsToString($request, 'phone_digits'),
                'landline'       => $this->digitsToString($request, 'landline_digits'),
                'address'        => $validated['address'] ?? null,
                'marital_status' => $validated['marital_status'],
            ]);

            // Update or create employment info
            $member->employmentInfo()->updateOrCreate(
                ['member_id' => $member->id],
                [
                    'workplace'          => $validated['employer_name'],
                    'job_title'          => $validated['job_title'],
                    'financial_category' => $validated['financial_category'],
                    'join_date'          => $this->dateFromParts($request, 'hire'),
                    'retirement_date'    => $this->dateFromParts($request, 'retirement'),
                    'starting_salary'    => $validated['salary'] ?? null,
                ]
            );

            // Update or create family info
            $member->familyInfo()->updateOrCreate(
                ['member_id' => $member->id],
                [
                    'children_count'   => $validated['children_count'] ?? 0,
                    'spouse_name'      => $this->nullIfPlaceholder($validated['spouse_name'] ?? null),
                    'spouse_phone'     => $this->digitsToString($request, 'spouse_phone_digits'),
                    'spouse_workplace' => $this->nullIfPlaceholder($validated['spouse_workplace'] ?? null),
                    'child_name'       => $this->nullIfPlaceholder($validated['child_name'] ?? null),
                    'child_workplace'  => $this->nullIfPlaceholder($validated['child_workplace'] ?? null),
                ]
            );

            // Store any new uploaded documents (replaces existing ones of the same type)
            foreach (self::INITIAL_DOCUMENT_TYPES as $type => $label) {
                if ($request->hasFile("documents.$type")) {
                    // Delete old attachment of the same type
                    Attachment::where('member_id', $member->id)->where('type', $type)->delete();
                    $this->storeDocument($member, $request->file("documents.$type"), $type);
                }
            }

            // Audit log
            $this->logAudit('update', 'members', $member->id, $oldValues, $member->fresh()->toArray());
        });

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

        return view('employee.members.show', [
            'member'     => $member,
            'statusMap'  => self::STATUS_MAP,
            'claimTypes' => Claim::CLAIM_TYPES,
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

        $this->storeDocument($member, $request->file('document_file'), $request->document_name);

        $this->logAudit('upload_document', 'attachments', $member->id, null, [
            'type' => $request->document_name,
        ]);

        return redirect()->back()->with('success', 'تم إرفاق المستند بنجاح.');
    }

    public function viewDocument($id)
    {
        $attachment = Attachment::findOrFail($id);
        $path = storage_path('app/public/' . $attachment->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function downloadDocument($id)
    {
        $attachment = Attachment::findOrFail($id);
        $path = storage_path('app/public/' . $attachment->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function print($id)
    {
        $member = Member::with(['user', 'department', 'employmentInfo', 'familyInfo', 'attachments'])
            ->findOrFail($id);

        return view('employee.members.create', [
            'member'        => $member,
            'departments'   => Department::all(),
            'documentTypes' => self::INITIAL_DOCUMENT_TYPES,
            'mode'          => 'print',
        ]);
    }

    // ─── Signed Form Upload ──────────────────────────────────────────

    public function uploadSignedState($id)
    {
        $member = Member::with(['user', 'department', 'employmentInfo', 'familyInfo', 'attachments'])
            ->findOrFail($id);

        return view('employee.members.create', [
            'member'        => $member,
            'departments'   => Department::all(),
            'documentTypes' => self::INITIAL_DOCUMENT_TYPES,
            'mode'          => 'upload_signed',
        ]);
    }

    public function uploadSignedForm(Request $request, $id)
    {
        $request->validate([
            'documents.signed_membership_form' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $member = Member::with('membershipInfo')->findOrFail($id);
        $this->storeDocument($member, $request->file('documents.signed_membership_form'), 'signed_membership_form');

        // Create subscription
        if ($member->membershipInfo) {
            \App\Models\Services\Subscription::create([
                'membership_id' => $member->membershipInfo->id,
                'amount'        => 0, // Default fee, to be adjusted later
                'due_date'      => now(),
                'status'        => 'pending',
            ]);
        }

        $this->logAudit('upload_signed_form', 'attachments', $member->id, null, [
            'type' => 'signed_membership_form',
        ]);

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

        $oldStatus = $member->membershipInfo->status ?? null;

        if ($member->membershipInfo) {
            $member->membershipInfo->update(['status' => 'suspended']);
        }

        if ($request->hasFile('suspension_file')) {
            $this->storeDocument($member, $request->file('suspension_file'), 'suspension_document');
        }

        $this->logAudit('suspend', 'memberships', $member->membershipInfo->id ?? $member->id, [
            'status' => $oldStatus,
            'reason' => null,
        ], [
            'status' => 'suspended',
            'reason' => $request->reason,
        ]);

        return redirect()
            ->route('members.show', $member->id)
            ->with('success', 'تم إيقاف العضوية بنجاح.');
    }



    // ─── Destroy (Soft Delete) ───────────────────────────────────────

    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        $this->logAudit('delete', 'members', $member->id, $member->toArray(), null);

        $member->delete(); // soft delete

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
     * Resolve the department ID, creating a default if not provided.
     */
    private function resolveDepartmentId(array $validated): int
    {
        if (!empty($validated['department_id'])) {
            return $validated['department_id'];
        }

        return Department::firstOrCreate(['name' => 'Pending Registration'])->id;
    }

    /**
     * Build a date string from day/month/year request parts.
     */
    private function dateFromParts(Request $request, string $prefix): ?string
    {
        $day   = $request->input("{$prefix}_day");
        $month = $request->input("{$prefix}_month");
        $year  = $request->input("{$prefix}_year");

        if (!$day || !$month || !$year || !checkdate((int) $month, (int) $day, (int) $year)) {
            return null;
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    /**
     * Concatenate digit array inputs into a single string.
     */
    private function digitsToString(Request $request, string $field): ?string
    {
        if (!$request->filled($field)) {
            return null;
        }

        $digits = array_filter($request->input($field, []), 'strlen');
        return !empty($digits) ? implode('', $digits) : null;
    }

    /**
     * Return null if the value is a common "not applicable" placeholder.
     */
    private function nullIfPlaceholder(?string $value): ?string
    {
        if ($value === null || $value === 'لا يوجد' || trim($value) === '') {
            return null;
        }
        return $value;
    }

    /**
     * Store a document file as an attachment for a member.
     */
    private function storeDocument(Member $member, $file, string $type): void
    {
        $path = $file->store("members/{$member->id}", 'public');

        Attachment::create([
            'member_id' => $member->id,
            'type'      => $type,
            'file_path' => $path,
        ]);
    }

    /**
     * Create an audit log entry for any action.
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
