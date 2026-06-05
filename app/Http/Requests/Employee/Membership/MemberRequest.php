<?php

namespace App\Http\Requests\Employee\Membership;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Membership\Member;
use App\Models\Auth\User;

class MemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is strictly managed by our route middleware, so we simply allow the request to proceed here.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'full_name'              => ['required', 'string', 'max:255', 'regex:/^[\x{0600}-\x{06FF}\s]+(?:\s+[\x{0600}-\x{06FF}\s]+){3,}$/u'],
            'email'                  => ['required', 'email', 'max:255'],
            'department_id'          => ['nullable', 'exists:departments,id'],
            'national_id_digits'     => ['required', 'array', 'size:14'],
            'national_id_digits.*'   => ['required', 'digits:1'],
            'phone_digits'           => ['nullable', 'array'],
            'phone_digits.*'         => ['nullable', 'digits:1'],
            'landline_digits'        => ['nullable', 'array'],
            'landline_digits.*'      => ['nullable', 'digits:1'],
            'address'                => ['required', 'string', 'max:1000'],
            'marital_status'         => ['required', 'string', 'in:متزوج,مطلق,أعزب,أرمل'],
            'employer_name'          => ['required', 'string', 'max:255'],
            'job_title'              => ['required', 'string', 'max:255'],
            'financial_category'     => ['required', 'string', 'max:255'],
            'hire_day'               => [
                'required', 'integer', 'between:1,31',
                function ($attribute, $value, $fail) {
                    $month = $this->input('hire_month');
                    $year = $this->input('hire_year');
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
        ];

        // Define validation rules specifically for file uploads.
        if ($this->isMethod('post')) {
            $rules['documents'] = ['nullable', 'array'];
            $rules['documents.national_id_card']    = ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.basic_salary_letter'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.work_declaration']    = ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.over_21_request']     = ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.appointment_decision']= ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.manual_request']      = ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
        } else {
            // When updating a member's profile, documents are not required unless the user is specifically uploading a replacement.
            $rules['documents'] = ['nullable', 'array'];
            $rules['documents.national_id_card']    = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.basic_salary_letter'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.work_declaration']    = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.over_21_request']     = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.appointment_decision']= ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
            $rules['documents.manual_request']      = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function messages(): array
    {
        return [
            'required'                 => 'حقل مطلوب.',
            'string'                   => 'نص غير صالح.',
            'max'                      => 'الحد الأقصى :max حرف.',
            'email'                    => 'بريد إلكتروني غير صالح.',
            'exists'                   => 'القيمة غير موجودة.',
            'array'                    => 'قيمة غير صالحة.',
            'size'                     => 'يجب إدخال :size رقم.',
            'digits'                   => 'يجب إدخال :digits رقم.',
            'integer'                  => 'رقم غير صالح.',
            'between'                  => 'القيمة بين :min و :max.',
            'in'                       => 'قيمة غير صالحة.',
            'numeric'                  => 'رقم غير صالح.',
            'min'                      => 'الحد الأدنى :min.',
            'mimes'                    => 'ملف غير مدعوم (:values).',
            'documents.*.max'          => 'الحد الأقصى للملف 5 ميجابايت.',
            'national_id_digits.required' => 'الرقم القومي مطلوب.',
            'national_id_digits.size'     => 'يجب إدخال 14 رقم.',
            'email.unique'             => 'البريد مسجل مسبقاً.',
            'full_name.regex'          => 'الاسم رباعي بالعربية.',
            'spouse_name.regex'        => 'الاسم رباعي بالعربية.',
            'child_name.regex'         => 'الاسم رباعي بالعربية أو "لا يوجد".',
            'spouse_phone_digits.required' => 'مطلوب.',
            'spouse_phone_digits.size'     => '11 رقم.',
            'spouse_name.required_if'      => 'مطلوب للمتزوج.',
            'spouse_workplace.required_if' => 'مطلوب للمتزوج.',
            'salary.gt'                    => 'يجب أن يكون المرتب أكبر من صفر.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $maritalStatus = $this->input('marital_status');
            $childrenCount = (int) $this->input('children_count', 0);

            if ($maritalStatus === 'أعزب' && $childrenCount > 0) {
                $validator->errors()->add('children_count', 'غير مسموح للأعزب.');
            }

            if ($childrenCount > 0) {
                $childName = $this->input('child_name');
                if (empty($childName) || trim($childName) === 'لا يوجد') {
                    $validator->errors()->add('child_name', 'الاسم مطلوب.');
                }

                $childWorkplace = $this->input('child_workplace');
                if (empty($childWorkplace) || trim($childWorkplace) === 'لا يوجد') {
                    $validator->errors()->add('child_workplace', 'جهة العمل مطلوبة.');
                }
            }

            $nationalId = null;
            $nationalIdDigits = $this->input('national_id_digits');
            $memberParam = $this->route('member') ?? $this->route('id');
            $memberId = $memberParam instanceof \App\Models\Membership\Member ? $memberParam->id : $memberParam;

            if (is_array($nationalIdDigits) && count($nationalIdDigits) === 14) {
                ksort($nationalIdDigits);
                $nationalId = implode('', $nationalIdDigits);
            } else if ($memberId) {
                $member = Member::find($memberId);
                if ($member && $member->user) {
                    $nationalId = $member->user->national_id;
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

                $hireMonth = $this->input('hire_month');
                $hireYear = $this->input('hire_year');
                $hireDay = $this->input('hire_day');

                if ($hireMonth && $hireYear && $hireDay && checkdate((int)$hireMonth, (int)$hireDay, (int)$hireYear)) {
                    $hireDate = sprintf('%04d-%02d-%02d', $hireYear, $hireMonth, $hireDay);
                    if (strtotime($hireDate) <= strtotime($birthDate)) {
                        $validator->errors()->add('hire_day', 'يجب أن يكون بعد تاريخ الميلاد.');
                    }
                }
            }

            if ($nationalId) {

                $query = User::where('national_id', $nationalId);
                if ($memberId) {
                    $member = Member::find($memberId);
                    if ($member && $member->user_id) {
                        $query->where('id', '!=', $member->user_id);
                    }
                }

                if ($query->exists()) {
                    $validator->errors()->add('national_id_digits', 'الرقم القومي مسجل مسبقاً.');
                }
            }

            $email = $this->input('email');
            if ($email) {
                $query = User::where('email', $email);
                $memberParam = $this->route('member') ?? $this->route('id');
                $memberId = $memberParam instanceof \App\Models\Membership\Member ? $memberParam->id : $memberParam;

                if ($memberId) {
                    $member = Member::find($memberId);
                    if ($member && $member->user_id) {
                        $query->where('id', '!=', $member->user_id);
                    }
                }
                if ($query->exists()) {
                    $validator->errors()->add('email', 'البريد مسجل مسبقاً.');
                }
            }

            $phoneDigits = $this->input('phone_digits');
            if (is_array($phoneDigits) && count($phoneDigits) === 11) {
                ksort($phoneDigits);
                $phone = implode('', $phoneDigits);
                $query = Member::where('phone', $phone);
                $memberParam = $this->route('member') ?? $this->route('id');
                $memberId = $memberParam instanceof \App\Models\Membership\Member ? $memberParam->id : $memberParam;

                if ($memberId) {
                    $query->where('id', '!=', $memberId);
                }

                if ($query->exists()) {
                    $validator->errors()->add('phone_digits', 'رقم الهاتف مسجل مسبقاً.');
                }
            }
        });
    }

    /**
     * Helper to get the concatenated national ID.
     */
    public function getNationalId(): string
    {
        $digits = $this->input('national_id_digits', []);
        if (is_array($digits)) {
            ksort($digits);
        }
        return implode('', $digits);
    }
}
