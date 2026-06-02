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
        
        $departments = Department::all();

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

        // The signup inputs (full_name, email, national_id, phone, employer_name, job_title) 
        // are disabled in the view, so they are not pulled from the request.
        // We will inject them into the validated array before saving.

        // Validate request
        $validated = $request->validate([

            'landline_digits'        => ['nullable', 'array'],
            'landline_digits.*'      => ['nullable', 'digits:1'],
            'birth_day'              => [
                'required', 'integer', 'between:1,31',
                function ($attribute, $value, $fail) use ($request) {
                    $month = $request->input('birth_month');
                    $year = $request->input('birth_year');
                    if ($month && $year && !checkdate((int)$month, (int)$value, (int)$year)) {
                        $fail('تاريخ الميلاد المدخل غير صحيح (يرجى التأكد من الأيام مع الشهر).');
                    }
                }
            ],
            'birth_month'            => ['required', 'integer', 'between:1,12'],
            'birth_year'             => ['required', 'integer', 'between:1900,2100'],
            'address'                => ['required', 'string', 'max:1000'],
            'marital_status'         => ['required', 'string', 'in:متزوج,مطلق,أعزب,أرمل'],

            'financial_category'     => ['required', 'string', 'max:255'],
            'hire_day'               => [
                'required', 'integer', 'between:1,31',
                function ($attribute, $value, $fail) use ($request) {
                    $month = $request->input('hire_month');
                    $year = $request->input('hire_year');
                    if ($month && $year && !checkdate((int)$month, (int)$value, (int)$year)) {
                        $fail('تاريخ استلام العمل المدخل غير صحيح (يرجى التأكد من الأيام مع الشهر).');
                    }
                }
            ],
            'hire_month'             => ['required', 'integer', 'between:1,12'],
            'hire_year'              => ['required', 'integer', 'between:1900,2100'],
            'retirement_day'         => [
                'required', 'integer', 'between:1,31',
                function ($attribute, $value, $fail) use ($request) {
                    $month = $request->input('retirement_month');
                    $year = $request->input('retirement_year');
                    if ($month && $year && !checkdate((int)$month, (int)$value, (int)$year)) {
                        $fail('تاريخ الإحالة للمعاش المدخل غير صحيح (يرجى التأكد من الأيام مع الشهر).');
                    }
                }
            ],
            'retirement_month'       => ['required', 'integer', 'between:1,12'],
            'retirement_year'        => ['required', 'integer', 'between:1900,2100'],
            'salary'                 => ['required', 'numeric', 'min:0'],
            'children_count'         => ['nullable', 'integer', 'min:0'],
            'spouse_phone_digits'    => ['nullable', 'array'],
            'spouse_phone_digits.*'  => ['nullable', 'digits:1'],
            'spouse_name'            => ['nullable', 'string', 'max:255', 'regex:/^[\x{0600}-\x{06FF}\s]+(?:\s+[\x{0600}-\x{06FF}\s]+){3,}$/u'],
            'spouse_workplace'       => ['nullable', 'string', 'max:255'],
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
        ], [
            'required' => 'هذا الحقل مطلوب ولا يمكن تركه فارغاً.',
            'accepted' => 'يجب الموافقة على الإقرار.',
            'spouse_name.regex' => 'يجب إدخال الاسم رباعي باللغة العربية.',
            'child_name.regex'  => 'يجب إدخال الاسم رباعي باللغة العربية أو "لا يوجد".',
        ]);

        // Inject the fixed DB values back into the validated array 
        // so the service can still update them as needed.
        $validated['full_name']     = $user->name;
        $validated['employer_name'] = $member->employmentInfo->workplace ?? '';
        $validated['job_title']     = $member->employmentInfo->job_title ?? '';
        
        // phone digits are used by digitsToString in the service, so we temporarily inject them back into the request.
        $request->merge([
            'phone_digits' => str_split($member->phone ?? '')
        ]);

        try {
            $result = $this->memberService->registerMembership($member, $validated, $request);
            $totalFee = number_format($result['totalFee'], 2);

            return redirect()
                ->route('member.dashboard')
                ->with('success', "تم تقديم طلب الاشتراك بنجاح. يرجى سداد رسوم الانضمام وقدرها ({$totalFee} جنيه).");

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors([
                'birth_year' => $e->getMessage(),
            ]);
        }
    }
}
