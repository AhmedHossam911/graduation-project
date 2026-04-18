<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\Membership\Employment;
use App\Models\Membership\FamilyMember;
use Illuminate\Http\Request;
use App\Models\System\Department;
use App\Models\System\Document;
use App\Models\Membership\Member;
use App\Models\Membership\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'national_id_digits' => ['required', 'array', 'size:14'],
            'national_id_digits.*' => ['required', 'digits:1'],
            'phone_digits' => ['nullable', 'array'],
            'phone_digits.*' => ['nullable', 'digits:1'],
            'birth_day' => ['nullable', 'integer', 'between:1,31'],
            'birth_month' => ['nullable', 'integer', 'between:1,12'],
            'birth_year' => ['nullable', 'integer', 'between:1900,2100'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'marital_status' => ['nullable', Rule::in(['single', 'married', 'divorced', 'widowed'])],
            'address' => ['nullable', 'string', 'max:1000'],
            'employer_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'hire_day' => ['nullable', 'integer', 'between:1,31'],
            'hire_month' => ['nullable', 'integer', 'between:1,12'],
            'hire_year' => ['nullable', 'integer', 'between:1900,2100'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'child_name' => ['nullable', 'string', 'max:255'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $nationalId = implode('', $validated['national_id_digits']);

        $request->validate([
            'national_id_digits' => [
                function ($attribute, $value, $fail) use ($nationalId) {
                    if (Person::where('national_id', $nationalId)->exists()) {
                        $fail('الرقم القومي مسجل من قبل.');
                    }
                },
            ],
        ]);

        $member = DB::transaction(function () use ($request, $validated, $nationalId) {
            $nameParts = $this->splitArabicName($validated['full_name']);

            $person = Person::create([
                'first_name' => $nameParts[0],
                'second_name' => $nameParts[1],
                'third_name' => $nameParts[2],
                'fourth_name' => $nameParts[3],
                'national_id' => $nationalId,
                'date_of_birth' => $this->dateFromParts($request, 'birth'),
                'gender' => $validated['gender'],
                'marital_status' => $validated['marital_status'] ?? null,
                'nationality' => 'Egyptian',
                'email' => $validated['email'] ?? null,
                'phone' => $request->filled('phone_digits') ? implode('', array_filter($request->input('phone_digits', []), 'strlen')) : null,
                'address' => $validated['address'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $member = Member::create([
                'person_id' => $person->id,
                'member_number' => $this->nextMemberNumber(),
                'status' => 'active',
                'join_date' => now()->toDateString(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            Employment::create([
                'member_id' => $member->id,
                'job_title' => $validated['job_title'],
                'employer_name' => $validated['employer_name'],
                'hire_date' => $this->dateFromParts($request, 'hire'),
                'salary' => $validated['salary'] ?? null,
                'employment_type' => 'full_time',
                'is_current' => true,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            if (!empty($validated['spouse_name']) && $validated['spouse_name'] !== 'لا يوجد') {
                FamilyMember::create([
                    'member_id' => $member->id,
                    'name' => $validated['spouse_name'],
                    'relationship' => 'spouse',
                    'is_beneficiary' => true,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            if (!empty($validated['child_name']) && $validated['child_name'] !== 'لا يوجد') {
                FamilyMember::create([
                    'member_id' => $member->id,
                    'name' => $validated['child_name'],
                    'relationship' => 'son',
                    'is_beneficiary' => true,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }

            foreach (self::INITIAL_DOCUMENT_TYPES as $type => $label) {
                if (!$request->hasFile("documents.$type")) {
                    continue;
                }

                $this->storeDocument($member, $request->file("documents.$type"), $type);
            }

            return $member;
        });

        return redirect()
            ->route('members.print', $member)
            ->with('success', 'تم حفظ بيانات العضو. يمكنك طباعة الاستمارة الآن بدون المرفقات.');
    }

    public function print(Member $member)
    {
        $member->load(['person', 'employments', 'familyMembers', 'documents']);

        return view('members.create', [
            'member' => $member,
            'departments' => Department::all(),
            'documentTypes' => self::INITIAL_DOCUMENT_TYPES,
            'printMode' => true,
        ]);
    }

    public function uploadSignedForm(Request $request, Member $member)
    {
        $request->validate([
            'signed_form' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->storeDocument($member, $request->file('signed_form'), 'signed_membership_form');

        return redirect()
            ->route('members.print', $member)
            ->with('success', 'تم رفع الاستمارة بعد التوقيع بنجاح.');
    }

    public function index(Request $request)
    {
        $departments = Department::all();
        
        $query = Member::with(['person', 'divisions.department', 'employments']);
        
        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('member_number', 'like', "%{$search}%")
                  ->orWhereHas('person', function($q) use ($search) {
                      $q->where('national_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('second_name', 'like', "%{$search}%")
                        ->orWhere('third_name', 'like', "%{$search}%")
                        ->orWhere('fourth_name', 'like', "%{$search}%");
                  });
        }
        
        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Department Filter
        if ($request->filled('department') && $request->department !== 'all') {
            $departmentId = $request->department;
            $query->whereHas('divisions', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }
        
        $members = $query->paginate(25)->withQueryString();
        
        $statusMap = [
            'active' => ['label' => 'نشط', 'class' => 'active'],
            'registering' => ['label' => 'قيد التسجيل', 'class' => 'registering'],
            'loan' => ['label' => 'إعارة', 'class' => 'loan'],
            'pension' => ['label' => 'محال للمعاش', 'class' => 'pension'],
            'withdrawn' => ['label' => 'منسحب', 'class' => 'withdrawn'],
            'dismissed' => ['label' => 'مفصول', 'class' => 'dismissed'],
            'unpaid_leave' => ['label' => 'إجازة بدون راتب', 'class' => 'unpaid-leave'],
            'expired' => ['label' => 'منتهي العضوية', 'class' => 'expired'],
            // Fallbacks for DB enums if they differ
            'suspended' => ['label' => 'موقوف', 'class' => 'expired'],
            'terminated' => ['label' => 'منتهي', 'class' => 'expired'],
            'deceased' => ['label' => 'متوفي', 'class' => 'expired'],
        ];
        
        return view('members.index', compact('departments', 'members', 'statusMap'));
    }

    private function splitArabicName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];

        return [
            $parts[0] ?? $fullName,
            $parts[1] ?? '-',
            $parts[2] ?? null,
            implode(' ', array_slice($parts, 3)) ?: null,
        ];
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

    private function nextMemberNumber(): string
    {
        $nextId = ((int) Member::max('id')) + 1;

        return 'M-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }

    private function storeDocument(Member $member, $file, string $type): void
    {
        $path = $file->store("members/{$member->id}", 'public');

        Document::create([
            'documentable_type' => Member::class,
            'documentable_id' => $member->id,
            'type' => $type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);
    }
}
