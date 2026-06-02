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
            'birth_day'              => [
                'required', 'integer', 'between:1,31',
                function ($attribute, $value, $fail) {
                    $month = $this->input('birth_month');
                    $year = $this->input('birth_year');
                    if ($month && $year && !checkdate((int)$month, (int)$value, (int)$year)) {
                        $fail('تاريخ الميلاد المدخل غير صحيح (يرجى التأكد من الأيام مع الشهر).');
                    }
                }
            ],
            'birth_month'            => ['required', 'integer', 'between:1,12'],
            'birth_year'             => ['required', 'integer', 'between:1900,2100'],
            'address'                => ['nullable', 'string', 'max:1000'],
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
                        $fail('تاريخ استلام العمل المدخل غير صحيح (يرجى التأكد من الأيام مع الشهر).');
                    }
                }
            ],
            'hire_month'             => ['required', 'integer', 'between:1,12'],
            'hire_year'              => ['required', 'integer', 'between:1900,2100'],
            'retirement_day'         => [
                'required', 'integer', 'between:1,31',
                function ($attribute, $value, $fail) {
                    $month = $this->input('retirement_month');
                    $year = $this->input('retirement_year');
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
            'required'                 => 'هذا الحقل مطلوب ولا يمكن تركه فارغاً.',
            'string'                   => 'يجب إدخال بيانات نصية صحيحة.',
            'max'                      => 'يجب ألا يزيد عدد الأحرف عن :max حرفاً.',
            'email'                    => 'يرجى إدخال بريد إلكتروني صحيح بصيغة صحيحة.',
            'exists'                   => 'القيمة التي قمت باختيارها غير موجودة.',
            'array'                    => 'يجب أن يحتوي الحقل على مجموعة من القيم.',
            'size'                     => 'يجب أن يحتوي الحقل على :size رقم بالضبط.',
            'digits'                   => 'يجب إدخال :digits رقم بالضبط.',
            'integer'                  => 'يجب إدخال رقم صحيح  .',
            'between'                  => 'يجب أن تكون القيمة بين :min و :max.',
            'in'                       => 'القيمة المختارة غير صحيحة، يرجى اختيار قيمة من القائمة.',
            'numeric'                  => 'يجب إدخال رقم صالح.',
            'min'                      => 'يجب ألا تقل القيمة عن :min.',
            'mimes'                    => 'نوع الملف غير مدعوم. الأنواع المسموحة: :values.',
            'documents.*.max'          => 'حجم الملف كبير جداً، الحد الأقصى 5 ميجابايت.',
            'national_id_digits.required' => 'من فضلك أدخل الرقم القومي كاملاً.',
            'national_id_digits.size'     => 'الرقم القومي يجب أن يتكون من 14 رقم بالضبط.',
            'email.unique'             => 'البريد الإلكتروني مسجل من قبل.',
            'full_name.regex'          => 'يجب إدخال الاسم رباعي باللغة العربية.',
            'spouse_name.regex'        => 'يجب إدخال الاسم رباعي باللغة العربية.',
            'child_name.regex'         => 'يجب إدخال الاسم رباعي باللغة العربية أو "لا يوجد".',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $nationalIdDigits = $this->input('national_id_digits');
            if (is_array($nationalIdDigits) && count($nationalIdDigits) === 14) {
                ksort($nationalIdDigits);
                $nationalId = implode('', $nationalIdDigits);
                $memberParam = $this->route('member') ?? $this->route('id');
                $memberId = $memberParam instanceof \App\Models\Membership\Member ? $memberParam->id : $memberParam;

                $query = User::where('national_id', $nationalId);
                if ($memberId) {
                    $member = Member::find($memberId);
                    if ($member && $member->user_id) {
                        $query->where('id', '!=', $member->user_id);
                    }
                }

                if ($query->exists()) {
                    $validator->errors()->add('national_id_digits', 'الرقم القومي مسجل من قبل.');
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
                    $validator->errors()->add('email', 'البريد الإلكتروني مسجل من قبل.');
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
                    $validator->errors()->add('phone_digits', 'رقم الهاتف مسجل من قبل عضو آخر.');
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
