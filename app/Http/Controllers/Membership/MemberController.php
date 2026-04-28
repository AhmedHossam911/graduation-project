<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Department;
use App\Models\Membership\Member;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\FamilyInfo;
use App\Models\Membership\Attachment;
use App\Models\Services\Membership;
use Illuminate\Support\Facades\DB;


class MemberController extends Controller
{
    private const INITIAL_DOCUMENT_TYPES = [
        'national_id_card' => 'بطاقة الرقم القومي',
        'basic_salary_letter' => 'خطاب الأجر الأساسي',
        'work_declaration' => 'إقرار القيام بالعمل',
        'over_21_request' => 'طلب تجاوز فوق سن ٢١ عام',
        'appointment_decision' => 'قرار التعيين',
    ];

    public function create()
    {
        $departments = Department::all();

        return view('members.create', [
            'departments' => $departments,
            'documentTypes' => self::INITIAL_DOCUMENT_TYPES,
            'printMode' => false,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'national_id_digits' => ['required', 'array', 'size:14'],
            'national_id_digits.*' => ['required', 'digits:1'],
            'phone_digits' => ['nullable', 'array'],
            'phone_digits.*' => ['nullable', 'digits:1'],
            'landline_digits' => ['nullable', 'array'],
            'landline_digits.*' => ['nullable', 'digits:1'],
            'birth_day' => ['nullable', 'integer', 'between:1,31'],
            'birth_month' => ['nullable', 'integer', 'between:1,12'],
            'birth_year' => ['nullable', 'integer', 'between:1900,2100'],
            'address' => ['nullable', 'string', 'max:1000'],
            'marital_status' => ['required', 'string', 'in:متزوج,مطلق,أعزب,أرمل'],
            'employer_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'financial_category' => ['required', 'string', 'max:255'],
            'hire_day' => ['nullable', 'integer', 'between:1,31'],
            'hire_month' => ['nullable', 'integer', 'between:1,12'],
            'hire_year' => ['nullable', 'integer', 'between:1900,2100'],
            'retirement_day' => ['nullable', 'integer', 'between:1,31'],
            'retirement_month' => ['nullable', 'integer', 'between:1,12'],
            'retirement_year' => ['nullable', 'integer', 'between:1900,2100'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'children_count' => ['nullable', 'integer', 'min:0'],
            'spouse_phone_digits' => ['nullable', 'array'],
            'spouse_phone_digits.*' => ['nullable', 'digits:1'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'spouse_workplace' => ['nullable', 'string', 'max:255'],
            'child_name' => ['nullable', 'string', 'max:255'],
            'child_workplace' => ['nullable', 'string', 'max:255'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $nationalId = implode('', $validated['national_id_digits']);

        $request->validate([
            'national_id_digits' => [
                function ($attribute, $value, $fail) use ($nationalId) {
                    if (Member::where('national_id', $nationalId)->exists()) {
                        $fail('الرقم القومي مسجل من قبل.');
                    }
                },
            ],
        ]);

        $member = DB::transaction(function () use ($request, $validated, $nationalId) {
            
            $department = null;
            if(!empty($validated['department_id'])) {
                $department = $validated['department_id'];
            } else {
                $deptRecord = Department::firstOrCreate(['name' => 'Pending Registration']);
                $department = $deptRecord->id;
            }

            $member = Member::create([
                'user_id' => auth()->id(), 
                'department_id' => $department,
                'full_name' => $validated['full_name'],
                'national_id' => $nationalId,
                'birth_date' => $this->dateFromParts($request, 'birth'),
                'phone' => $request->filled('phone_digits') ? implode('', array_filter($request->input('phone_digits', []), 'strlen')) : null,
                'landline' => $request->filled('landline_digits') ? implode('', array_filter($request->input('landline_digits', []), 'strlen')) : null,
                'address' => $validated['address'] ?? null,
                'marital_status' => $validated['marital_status'],
            ]);

            Membership::create([
                'member_id' => $member->id,
                'membership_number' => 'MS-' . str_pad($member->id, 5, '0', STR_PAD_LEFT),
                'status' => 'pending',
                'declaration_accepted' => true,
                'approved_by' => null,
            ]);

            EmploymentInfo::create([
                'member_id' => $member->id,
                'workplace' => $validated['employer_name'],
                'job_title' => $validated['job_title'],
                'financial_category' => $validated['financial_category'],
                'join_date' => $this->dateFromParts($request, 'hire'),
                'retirement_date' => $this->dateFromParts($request, 'retirement'),
                'starting_salary' => $validated['salary'] ?? null,
            ]);

            FamilyInfo::create([
                'member_id' => $member->id,
                'children_count' => $validated['children_count'] ?? 0,
                'spouse_name' => ($validated['spouse_name'] === 'لا يوجد') ? null : $validated['spouse_name'],
                'spouse_phone' => $request->filled('spouse_phone_digits') ? implode('', array_filter($request->input('spouse_phone_digits', []), 'strlen')) : null,
                'spouse_workplace' => ($validated['spouse_workplace'] === 'لا يوجد') ? null : $validated['spouse_workplace'],
                'child_name' => ($validated['child_name'] === 'لا يوجد') ? null : $validated['child_name'],
                'child_workplace' => ($validated['child_workplace'] === 'لا يوجد') ? null : $validated['child_workplace'],
            ]);

            foreach (self::INITIAL_DOCUMENT_TYPES as $type => $label) {
                if ($request->hasFile("documents.$type")) {
                    $this->storeDocument($member, $request->file("documents.$type"), $type);
                }
            }

            return $member;
        });

        return redirect()
            ->route('members.print', $member)
            ->with('success', 'تم حفظ بيانات العضو.');
    }

    public function print($id)
    {
        $member = Member::with(['user', 'department', 'employmentInfo', 'familyInfo', 'attachments'])->findOrFail($id);

        return view('members.create', [
            'member' => $member,
            'departments' => Department::all(),
            'documentTypes' => self::INITIAL_DOCUMENT_TYPES,
            'printMode' => true,
        ]);
    }

    public function uploadSignedForm(Request $request, $id)
    {
        $request->validate([
            'signed_form' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $member = Member::findOrFail($id);
        $this->storeDocument($member, $request->file('signed_form'), 'signed_membership_form');

        return redirect()
            ->route('members.print', $member->id)
            ->with('success', 'تم رفع الاستمارة بنجاح.');
    }

    public function index(Request $request)
    {
        $departments = Department::all();
        
        $query = Member::with(['user', 'department', 'employmentInfo', 'membershipInfo']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhereHas('membershipInfo', function($sq) use ($search) {
                      $sq->where('membership_number', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            $query->whereHas('membershipInfo', function($q) use ($status) {
                $q->where('status', $status);
            });
        }
        
        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('department_id', $request->department);
        }
        
        $members = $query->paginate(10)->withQueryString();
        
        $statusMap = [
            'active' => ['label' => 'نشط', 'class' => 'active'],
            'registering' => ['label' => 'قيد التسجيل', 'class' => 'registering'],
            'pending' => ['label' => 'قيد الانتظار', 'class' => 'pending'],
            'loan' => ['label' => 'إعارة', 'class' => 'loan'],
            'pension' => ['label' => 'محال للمعاش', 'class' => 'pension'],
            'withdrawn' => ['label' => 'منسحب', 'class' => 'withdrawn'],
            'dismissed' => ['label' => 'مفصول', 'class' => 'dismissed'],
            'unpaid_leave' => ['label' => 'إجازة بدون راتب', 'class' => 'unpaid_leave'],
            'expired' => ['label' => 'منتهي العضوية', 'class' => 'expired'],
            'suspended' => ['label' => 'موقوف', 'class' => 'suspended'],
        ];
        
        return view('members.index', compact('departments', 'members', 'statusMap'));
    }

    private function dateFromParts(Request $request, string $prefix): ?string
    {
        $day = $request->input("{$prefix}_day");
        $month = $request->input("{$prefix}_month");
        $year = $request->input("{$prefix}_year");

        if (!$day || !$month || !$year || !checkdate((int) $month, (int) $day, (int) $year)) {
            return null;
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    private function storeDocument(Member $member, $file, string $type): void
    {
        $path = $file->store("members/{$member->id}", 'public');

        Attachment::create([
            'member_id' => $member->id,
            'type' => $type,
            'file_path' => $path,
        ]);
    }

    public function show($id)
    {
        $member = Member::with(['user', 'department', 'employmentInfo', 'membershipInfo.claims'])->findOrFail($id);
        $statusMap = [
            'active' => ['label' => 'نشط', 'class' => 'active'],
            'registering' => ['label' => 'قيد التسجيل', 'class' => 'registering'],
            'pending' => ['label' => 'قيد الانتظار', 'class' => 'pending'],
            'loan' => ['label' => 'إعارة', 'class' => 'loan'],
            'pension' => ['label' => 'محال للمعاش', 'class' => 'pension'],
            'withdrawn' => ['label' => 'منسحب', 'class' => 'withdrawn'],
            'dismissed' => ['label' => 'مفصول', 'class' => 'dismissed'],
            'unpaid_leave' => ['label' => 'إجازة بدون راتب', 'class' => 'unpaid_leave'],
            'expired' => ['label' => 'منتهي العضوية', 'class' => 'expired'],
            'suspended' => ['label' => 'موقوف', 'class' => 'suspended'],
        ];

        return view('members.show', compact('member', 'statusMap'));
    }

    public function suspend(Request $request, $id)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
            'suspension_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $member = Member::with('membershipInfo')->findOrFail($id);

        if ($member->membershipInfo) {
            $member->membershipInfo->update(['status' => 'suspended']);
        }

        if ($request->hasFile('suspension_file')) {
            $this->storeDocument($member, $request->file('suspension_file'), 'suspension_document');
        }

        return redirect()->route('members.show', $member->id)->with('success', 'تم إيقاف العضوية بنجاح.');
    }
}
