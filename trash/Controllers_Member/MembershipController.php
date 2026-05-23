<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Department;
use App\Models\Membership\Member;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\FamilyInfo;
use App\Models\Membership\Attachment;
use App\Models\Services\Membership;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    private const INITIAL_DOCUMENT_TYPES = [
        'national_id_card'    => 'بطاقة الرقم القومي',
        'basic_salary_letter' => 'خطاب الأجر الأساسي',
        'work_declaration'    => 'إقرار القيام بالعمل',
        'over_21_request'     => 'طلب تجاوز فوق سن ٢١ عام',
        'appointment_decision'=> 'قرار التعيين',
    ];

    private function validationRules(): array
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
            'salary'                 => ['required', 'numeric', 'min:0'], // Made required for calculation
            'children_count'         => ['nullable', 'integer', 'min:0'],
            'spouse_phone_digits'    => ['nullable', 'array'],
            'spouse_phone_digits.*'  => ['nullable', 'digits:1'],
            'spouse_name'            => ['nullable', 'string', 'max:255'],
            'spouse_workplace'       => ['nullable', 'string', 'max:255'],
            'child_name'             => ['nullable', 'string', 'max:255'],
            'child_workplace'        => ['nullable', 'string', 'max:255'],
            'documents'              => ['required', 'array'],
            'documents.national_id_card' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'documents.basic_salary_letter' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'documents.work_declaration' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'documents.over_21_request' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'documents.appointment_decision' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'electronic_declaration' => ['accepted'],
        ];
    }

    private function validationMessages(): array
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
            'national_id_digits.size'     => 'الرقم القومي يجب أن يتكون من 14 رقم بالضبط.',
            'electronic_declaration.accepted' => 'يجب الموافقة على الإقرار الإلكتروني لإتمام التسجيل.',
        ];
    }

    public function create()
    {
        $userId = auth()->id();
        // Check if user already has a membership application
        if (Member::where('user_id', $userId)->exists()) {
            return redirect()->route('member.dashboard')->with('info', 'لقد قمت بتقديم طلب عضوية بالفعل.');
        }

        return view('member.membership.create', [
            'documentTypes' => self::INITIAL_DOCUMENT_TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        if (Member::where('user_id', $userId)->exists()) {
            return redirect()->route('member.dashboard')->with('info', 'لقد قمت بتقديم طلب عضوية بالفعل.');
        }

        $validated = $request->validate($this->validationRules(), $this->validationMessages());

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

        // Business rule: member age validation based on system settings
        $birthDate = $this->dateFromParts($request, 'birth');
        $age = 0;
        if ($birthDate) {
            $age = \Carbon\Carbon::parse($birthDate)->age;
            $minAge = (int) \App\Models\System\SystemSetting::get('membership_min_age', 21);
            $maxAge = (int) \App\Models\System\SystemSetting::get('membership_max_age', 59);

            if ($age < $minAge || $age > $maxAge) {
                return redirect()->back()->withInput()->withErrors([
                    'birth_year' => "عمرك ($age عامًا) غير مطابق للوائح الصندوق. السن المسموح به للتسجيل من $minAge إلى $maxAge عامًا.",
                ]);
            }
        }

        $result = DB::transaction(function () use ($request, $validated, $nationalId, $age, $userId) {

            $departmentId = $this->resolveDepartmentId($validated);

            $member = Member::create([
                'user_id'        => $userId,
                'department_id'  => $departmentId,
                'full_name'      => $validated['full_name'],
                'national_id'    => $nationalId,
                'birth_date'     => $this->dateFromParts($request, 'birth'),
                'phone'          => $this->digitsToString($request, 'phone_digits'),
                'landline'       => $this->digitsToString($request, 'landline_digits'),
                'address'        => $validated['address'] ?? null,
                'marital_status' => $validated['marital_status'],
            ]);

            $membership = Membership::create([
                'member_id'            => $member->id,
                'membership_number'    => 'MS-' . str_pad($member->id, 5, '0', STR_PAD_LEFT),
                'status'               => 'pending',
                'declaration_accepted' => true,
                'approved_by'          => null,
            ]);

            // Calculate fees based on remaining years to retirement
            $retirementAge = (int) \App\Models\System\SystemSetting::get('retirement_age', 60);
            $remainingYears = max(0, $retirementAge - $age);

            $feesSettings = json_decode(\App\Models\System\SystemSetting::get('membership_join_fee', '[]'), true);
            $feeMonths = 0;

            if (is_array($feesSettings)) {
                foreach ($feesSettings as $setting) {
                    $settingYearsStr = preg_replace('/[^0-9]/', '', $setting['years'] ?? '');
                    if (is_numeric($settingYearsStr)) {
                        $settingYears = (int) $settingYearsStr;
                        if (str_contains($setting['years'], 'فأكثر') && $remainingYears >= $settingYears) {
                            $feeMonths = (float) ($setting['fee_months'] ?? 0);
                            break;
                        } elseif ($remainingYears == $settingYears) {
                            $feeMonths = (float) ($setting['fee_months'] ?? 0);
                            break;
                        }
                    }
                }
            }

            $basicSalary = (float) ($validated['salary'] ?? 0);
            $totalFee = $feeMonths * $basicSalary;

            \App\Models\Services\Subscription::create([
                'membership_id' => $membership->id,
                'amount'        => $totalFee,
                'due_date'      => now(),
                'status'        => 'unpaid',
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

            return [
                'member' => $member,
                'membership' => $membership,
                'totalFee' => $totalFee
            ];
        });

        $receiptData = [
            'name' => $result['member']->full_name,
            'national_id' => $result['member']->national_id,
            'membership_number' => $result['membership']->membership_number,
            'amount' => number_format($result['totalFee'], 2),
            'date' => now()->format('Y-m-d')
        ];

        return redirect()
            ->route('member.dashboard')
            ->with('success', 'تم تقديم طلب العضوية بنجاح. هو الآن قيد المراجعة.')
            ->with('receipt_data', json_encode($receiptData));
    }

    private function resolveDepartmentId(array $validated): int
    {
        if (!empty($validated['department_id'])) {
            return $validated['department_id'];
        }

        return Department::firstOrCreate(['name' => 'Pending Registration'])->id;
    }

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

    private function digitsToString(Request $request, string $field): ?string
    {
        if (!$request->filled($field)) {
            return null;
        }

        $digits = array_filter($request->input($field, []), 'strlen');
        return !empty($digits) ? implode('', $digits) : null;
    }

    private function nullIfPlaceholder(?string $value): ?string
    {
        if ($value === null || $value === 'لا يوجد' || trim($value) === '') {
            return null;
        }
        return $value;
    }

    private function storeDocument(Member $member, $file, string $type): void
    {
        $path = $file->store("members/{$member->id}", 'public');

        Attachment::create([
            'member_id' => $member->id,
            'type'      => $type,
            'file_path' => $path,
        ]);
    }
}
