<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\System\Department;
use App\Services\MemberService;
use Exception;

class MembershipController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function create()
    {
        $user = Auth::user();
        $membership = $user->member?->membershipInfo;
        
        if ($membership) {
            return redirect()->route('member.dashboard')->with('error', 'لديك طلب عضوية مقدم بالفعل.');
        }
        
        $departments = Department::where('status', 'active')->get();

        return view('member.guest.membership.create', compact('user', 'departments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('member.dashboard')->with('error', 'بيانات العضو غير مكتملة.');
        }

        if ($member->membershipInfo) {
            return redirect()->route('member.dashboard')->with('error', 'لديك طلب عضوية مقدم بالفعل.');
        }

        // DB values
        $dbName = $user->name;
        $dbEmail = $user->email;
        $dbPhone = $member->phone ?? null;
        $dbNid = $user->national_id ?? null;
        $dbEmployer = $member->employmentInfo->workplace ?? null;
        $dbJobTitle = $member->employmentInfo->job_title ?? null;

        $rules = [
            'landline_digits'        => ['nullable', 'array'],
            'landline_digits.*'      => ['nullable', 'digits:1'],
            'address'                => ['required', 'string', 'max:1000'],
            'marital_status'         => ['required', 'string', 'in:متزوج,مطلق,أعزب,أرمل'],

            'financial_category'     => ['required', 'string', 'max:255'],
            'hire_day'               => [
                'required', 'integer', 'between:1,31',
                function ($attribute, $value, $fail) use ($request) {
                    $month = $request->input('hire_month');
                    $year = $request->input('hire_year');
                    if ($month && $year && !checkdate((int)$month, (int)$value, (int)$year)) {
                        $fail('تاريخ استلام العمل غير صالح.');
                    }
                }
            ],
            'hire_month'             => ['required', 'integer', 'between:1,12'],
            'hire_year'              => ['required', 'integer', 'between:1900,2100'],
            'hire_year'              => ['required', 'integer', 'between:1900,2100'],
            'salary'                 => ['required', 'numeric', 'gt:0'],
            'children_count'         => ['nullable', 'integer', 'min:0'],
            'spouse_phone_digits'    => ['required', 'array', 'size:11'],
            'spouse_phone_digits.*'  => ['required', 'digits:1'],
            'spouse_name'            => ['required_if:marital_status,متزوج', 'nullable', 'string', 'max:255', 'regex:/^[\x{0600}-\x{06FF}\s]+(?:\s+[\x{0600}-\x{06FF}\s]+){3,}$/u'],
            'spouse_workplace'       => ['required_if:marital_status,متزوج', 'nullable', 'string', 'max:255'],
            'child_name'             => ['nullable', 'string', 'max:255', 'regex:/^(لا يوجد|[\x{0600}-\x{06FF}\s]+(?:\s+[\x{0600}-\x{06FF}\s]+){3,})$/u'],
            'child_workplace'        => ['nullable', 'string', 'max:255'],
            'declaration_accepted'   => ['required', 'accepted'],
            
            // Documents
            'documents' => ['required', 'array'],
            'documents.national_id_card'    => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'documents.basic_salary_letter' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'documents.work_declaration'    => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'documents.over_21_request'     => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'documents.appointment_decision'=> ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ];

        if (empty($dbName)) {
            $rules['full_name'] = ['required', 'string', 'regex:/^[\x{0600}-\x{06FF}\s]+(?:\s+[\x{0600}-\x{06FF}\s]+){3,}$/u'];
        }
        if (empty($dbEmail)) {
            $rules['email'] = ['required', 'email', 'unique:users,email'];
        }
        if (empty($dbPhone)) {
            $rules['phone_digits'] = ['required', 'array', 'size:11'];
            $rules['phone_digits.*'] = ['required', 'digits:1'];
        }
        if (empty($dbNid)) {
            $rules['national_id_digits'] = ['required', 'array', 'size:14'];
            $rules['national_id_digits.*'] = ['required', 'digits:1'];
        }
        if (empty($dbEmployer)) {
            $rules['employer_name'] = ['required', 'string', 'max:255'];
        }
        if (empty($dbJobTitle)) {
            $rules['job_title'] = ['required', 'string', 'max:255'];
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, [
            'required' => 'حقل مطلوب.',
            'accepted' => 'يجب الموافقة.',
            'full_name.regex' => 'يجب إدخال الاسم رباعي باللغة العربية.',
            'spouse_name.regex' => 'الاسم رباعي بالعربية.',
            'child_name.regex'  => 'الاسم رباعي بالعربية أو "لا يوجد".',
            'spouse_phone_digits.required' => 'مطلوب.',
            'spouse_phone_digits.size'     => '11 رقم.',
            'spouse_name.required_if'      => 'مطلوب للمتزوج.',
            'spouse_workplace.required_if' => 'مطلوب للمتزوج.',
            'salary.gt'                    => 'يجب أن يكون المرتب أكبر من صفر.',
            'email.unique'                 => 'هذا البريد الإلكتروني مسجل مسبقاً.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $maritalStatus = $request->input('marital_status');
            $childrenCount = (int) $request->input('children_count', 0);
            
            if ($maritalStatus === 'أعزب' && $childrenCount > 0) {
                $validator->errors()->add('children_count', 'غير مسموح للأعزب.');
            }

            if ($childrenCount > 0) {
                $childName = $request->input('child_name');
                if (empty($childName) || trim($childName) === 'لا يوجد') {
                    $validator->errors()->add('child_name', 'الاسم مطلوب.');
                }
                
                $childWorkplace = $request->input('child_workplace');
                if (empty($childWorkplace) || trim($childWorkplace) === 'لا يوجد') {
                    $validator->errors()->add('child_workplace', 'جهة العمل مطلوبة.');
                }
            }

            $nationalId = null;
            if (!empty($dbNid)) {
                $nationalId = $dbNid;
            } else {
                $nationalIdDigits = $request->input('national_id_digits');
                if (is_array($nationalIdDigits) && count($nationalIdDigits) === 14) {
                    ksort($nationalIdDigits);
                    $nationalId = implode('', $nationalIdDigits);
                }
            }

            if ($nationalId && strlen($nationalId) >= 7) {
                $centuryCode = substr($nationalId, 0, 1);
                $year = substr($nationalId, 1, 2);
                $month = substr($nationalId, 3, 2);
                $day = substr($nationalId, 5, 2);
                $fullYear = ($centuryCode === '2') ? '19' . $year : '20' . $year;
                $month = $month == '00' ? '01' : $month;
                $day = $day == '00' ? '01' : $day;
                $birthDate = sprintf('%04d-%02d-%02d', $fullYear, $month, $day);

                $hireMonth = $request->input('hire_month');
                $hireYear = $request->input('hire_year');
                $hireDay = $request->input('hire_day');

                if ($hireMonth && $hireYear && $hireDay && checkdate((int)$hireMonth, (int)$hireDay, (int)$hireYear)) {
                    $hireDate = sprintf('%04d-%02d-%02d', $hireYear, $hireMonth, $hireDay);
                    if (strtotime($hireDate) <= strtotime($birthDate)) {
                        $validator->errors()->add('hire_day', 'يجب أن يكون بعد تاريخ الميلاد.');
                    }
                }
            }
        });

        $validated = $validator->validate();

        // Inject the values back into the validated array 
        // If they exist in DB, force DB value. Otherwise, take from request.
        $validated['full_name']     = $dbName ?: $request->input('full_name');
        $validated['employer_name'] = $dbEmployer ?: $request->input('employer_name');
        $validated['job_title']     = $dbJobTitle ?: $request->input('job_title');
        
        if (empty($dbEmail)) {
            $validated['email'] = $request->input('email');
        }

        // phone digits and national id digits are used by digitsToString in the service, 
        // so we merge DB values back into the request if they existed to prevent overrides.
        if (!empty($dbPhone)) {
            $request->merge(['phone_digits' => str_split($dbPhone)]);
        }
        if (!empty($dbNid)) {
            $request->merge(['national_id_digits' => str_split($dbNid)]);
        }

        try {
            $result = $this->memberService->registerMembership($member, $validated, $request);
            $totalFee = number_format($result['totalFee'], 2);

            return redirect()
                ->route('member.dashboard')
                ->with('success', "تم تقديم طلب الاشتراك بنجاح. يرجى سداد رسوم الانضمام وقدرها ({$totalFee} جنيه).");

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['national_id_digits' => $e->getMessage()]);
        }
    }
}
