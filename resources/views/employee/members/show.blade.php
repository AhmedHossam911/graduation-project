@extends('layouts.pages')

@section('title', 'عرض بيانات العضو')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/member.css') }}">

    @php
        $membership = $member->membershipInfo;
        $claims = $claimTypes ?? [];
        $memberClaims = $membership?->claims ?? collect();
        $memberLoans = $membership?->loans ?? collect();
        $memberSubscriptions = $membership?->subscriptions ?? collect();
        $activeTab = request('tab', request('claim_type') ? 'claims' : 'subscriptions');
        $selectedClaimType = request('claim_type');

        $statusCode = $membership->status ?? 'unknown';
        $statusData = $statusMap[$statusCode] ?? ['label' => 'غير معروف', 'class' => 'unknown'];
        $classMap = [
            'active' => 'text-[#067647] border-[#067647] bg-[#ECFDF3]',
            'pending' => 'text-[#175CD3] border-[#175CD3] bg-[#EFF8FF]',
            'loan' => 'text-[#5925DC] border-[#5925DC] bg-[#F4F3FF]',
            'pension' => 'text-[#E6B800] border-[#E6B800] bg-[#FFF8E1]',
            'withdrawn' => 'text-[#F79009] border-[#F79009] bg-[#FFF7ED]',
            'dismissed' => 'text-[#D92D20] border-[#D92D20] bg-[#FFEAE8]',
            'unpaid_leave' => 'text-[#4B5A70] border-[#4B5A70] bg-[#F3F6FA]',
            'expired' => 'text-[#021219] border-[#021219] bg-[#F2F4F7]',
            'suspended' => 'text-[#D92D20] border-[#D92D20] bg-[#FFEAE8]',
        ];
        $badgeClass = $classMap[$statusCode] ?? 'text-gray-500 border-gray-400 bg-gray-100';

        $claimDocumentLabels = [
            'salary_letter' => 'خطاب بالمرتب الأساسي',
            'national_id' => 'بطاقة الرقم القومي',
            'retirement_decision' => 'قرار الإحالة للمعاش',
            'deductions_statement' => 'بيان بالمبالغ المخصومة',
            'appointment_letter' => 'خطاب بتاريخ التعيين',
            'release_form' => 'إخلاء طرف',
            'transfer_decision' => 'قرار النقل',
            'service_end_decision' => 'قرار إنهاء الخدمة',
            'death_certificate' => 'شهادة الوفاة',
            'heirs_ids' => 'بطاقات الرقم القومي للورثة',
            'inheritance_notice' => 'إعلام الوراثة',
            'signed_receipt' => 'توقيع باستلام المستحقات',
        ];

        $claimDocumentsByType = [
            'retirement' => [
                'salary_letter',
                'national_id',
                'retirement_decision',
                'deductions_statement',
                'appointment_letter',
                'signed_receipt',
            ],
            'resignation' => [
                'salary_letter',
                'national_id',
                'retirement_decision',
                'deductions_statement',
                'appointment_letter',
                'signed_receipt',
            ],
            'early_retirement' => [
                'salary_letter',
                'national_id',
                'retirement_decision',
                'deductions_statement',
                'appointment_letter',
                'signed_receipt',
            ],
            'withdrawal' => [
                'salary_letter',
                'national_id',
                'retirement_decision',
                'deductions_statement',
                'appointment_letter',
                'signed_receipt',
            ],
            'expulsion' => [
                'salary_letter',
                'national_id',
                'retirement_decision',
                'deductions_statement',
                'appointment_letter',
                'signed_receipt',
            ],
            'professional_disability' => [
                'salary_letter',
                'national_id',
                'retirement_decision',
                'deductions_statement',
                'appointment_letter',
                'signed_receipt',
            ],
            'transfer' => [
                'national_id',
                'deductions_statement',
                'appointment_letter',
                'release_form',
                'transfer_decision',
            ],
            'death' => [
                'salary_letter',
                'deductions_statement',
                'appointment_letter',
                'service_end_decision',
                'death_certificate',
                'heirs_ids',
                'inheritance_notice',
            ],
        ];

        $claimStatusClasses = [
            'pending' => 'bg-[#FFFCEF] text-[#D4AF37] border-[#D4AF37]',
            'approved' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'paid' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'rejected' => 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]',
        ];
        $claimStatusLabels = [
            'pending' => 'بإنتظار الاعتماد',
            'approved' => 'معتمد',
            'paid' => 'تم الصرف',
            'rejected' => 'مرفوض',
        ];
        $paymentStatusClasses = [
            'paid' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'unpaid' => 'bg-[#F2F4F7] text-[#6D6D6D] border-[#6D6D6D]',
            'overdue' => 'bg-[#FFEAE880] text-[#D92D20] border-[#D92D20]',
            'pending' => 'bg-[#FFFCEF] text-[#D4AF37] border-[#D4AF37]',
            'active' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'completed' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'rejected' => 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]',
        ];
        $paymentStatusLabels = [
            'paid' => 'مدفوع',
            'unpaid' => 'مستحق',
            'overdue' => 'متأخر',
            'pending' => 'تحت المراجعة',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'rejected' => 'مرفوض',
        ];

        $fieldClass =
            'focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2';
        $labelClass = 'px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]';
    @endphp

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

    <div class="flex justify-between items-center px-10 py-5">
        <div class="flex flex-col gap-4">
            <div class="flex gap-7 items-center">
                <p class="text-[28px] font-semibold text-[#124375]">{{ $member->full_name }}</p>
                <p class="mt-3 status {{ $badgeClass }} rounded-lg px-10 border">{{ $statusData['label'] }}</p>
            </div>
            <p class="text-[#021219] text-sm font-medium flex items-center gap-4">
                رقم العضوية :
                <span class="text-[#124375] font-semibold text-xl">{{ $membership->membership_number ?? '-' }}</span>
            </p>
        </div>
        <div class="space-y-2 mt-3">
            <a href="{{ route('members.edit', $member->id) }}"
                class="flex items-center justify-center navy-shadow bg-[#124375] text-[#FEFFFC] rounded-xl gap-2 w-full py-3">
                <iconify-icon icon="material-symbols:edit-document-rounded" class="text-2xl"></iconify-icon>
                تعديل بيانات
            </a>
            <button type="button"
                class="open-modal flex items-center red-shadow bg-[#F4F7F9] text-[#D92D20] rounded-xl gap-2 px-20 py-3 border border-[#D92D20]"
                data-modal="suspension-modal">
                <iconify-icon icon="carbon:close-filled" class="mt-1 text-xl"></iconify-icon>
                إيقاف العضوية
            </button>
        </div>
    </div>

    <hr class="border border-[#124375] mx-7 my-2">

    <section class="py-5 px-7">
        <div class="personal-info relative border border-[#124375] rounded-[20px]">
            <h2 class="absolute text-[#124375] px-1 right-3 top-[-15px] text-lg font-medium bg-[#F4F7F9]">
                البيانات الشخصية
            </h2>
            <div class="information py-7 px-7">
                <div class="space-y-5">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الاسم كامل</label>
                            <input type="text" disabled value="{{ $member->full_name ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الرقم القومي</label>
                            <input type="text" disabled value="{{ $member->national_id ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">رقم الهاتف</label>
                            <input type="text" disabled value="{{ $member->phone ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الوظيفة</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->job_title ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">جهة العمل</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->workplace ?? ($member->department?->name ?? 'بيانات مفقودة') }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">البريد الإلكتروني</label>
                            <input type="text" disabled value="{{ $member->user->email ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">تاريخ الانضمام</label>
                            <input type="text" disabled value="{{ $member->created_at?->isoFormat('D MMMM YYYY') }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الحالة الوظيفية</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->financial_category ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الراتب الأساسي</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->starting_salary ? number_format($member->employmentInfo->starting_salary, 2) . ' ج.م' : 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <form action="{{ route('members.suspend', $member->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="suspension-modal"
            class="member-modal hidden max-w-2xl mx-auto fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button type="button"
                class="modal-close text-[#124375] text-2xl navy-shadow rounded m-4 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body px-6 space-y-7">
                <h1 class="text-xl font-semibold text-[#124375] text-center">تأكيد إيقاف العضوية</h1>
                <div class="reason space-y-3">
                    <p class="mr-1 text-[#021219] font-medium">يرجى توضيح سبب إيقاف العضوية وإرفاق المستند الداعم.</p>
                    <textarea name="reason" required rows="4"
                        class="resize-none bg-[#F4F7F9] w-full border border-[#124375] rounded-xl outline-none px-2"
                        placeholder="سبب إيقاف العضوية"></textarea>
                </div>
                <div class="document border border-[#124375] rounded-xl text-center">
                    <label for="suspension-file"
                        class="w-full cursor-pointer py-6 text-[#124375] flex items-center justify-center gap-1">
                        <p>اضغط لإرفاق صورة الملف</p>
                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                        <input type="file" name="suspension_file" id="suspension-file" class="hidden">
                    </label>
                </div>
                <div class="btns flex gap-2">
                    <button type="submit"
                        class="w-full rounded-[14px] py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">إيقاف
                        العضوية</button>
                    <button type="button"
                        class="modal-close border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
                </div>
            </div>
        </div>
    </form>

    <section class="px-7">
        <div class="flex items-center justify-between border border-[#124375] p-3 rounded-xl gap-4">
            <div class="tabs flex gap-2">
                <button type="button" data-member-tab="subscriptions"
                    class="member-tab {{ $activeTab === 'subscriptions' ? 'bg-[#F4F7F9] border-b-[3px] border-[#124375]' : 'bg-[#E6F1FD80]' }} text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">
                    الاشتراكات
                </button>
                <button type="button" data-member-tab="loans"
                    class="member-tab {{ $activeTab === 'loans' ? 'bg-[#F4F7F9] border-b-[3px] border-[#124375]' : 'bg-[#E6F1FD80]' }} text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">
                    قروض
                </button>
                <button type="button" data-member-tab="claims"
                    class="member-tab {{ $activeTab === 'claims' ? 'bg-[#F4F7F9] border-b-[3px] border-[#124375]' : 'bg-[#E6F1FD80]' }} text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">
                    مطالبات
                </button>
            </div>

            <div class="flex items-center gap-2">
                <form data-member-panel-action="claims"
                    class="{{ $activeTab === 'claims' ? '' : 'hidden' }} min-w-[260px]"
                    action="{{ route('members.show', $member->id) }}" method="GET">
                    <input type="hidden" name="tab" value="claims">
                    @include('partials.dropdown', [
                        'name' => 'claim_type',
                        'label' => 'نوع المطالبة',
                        'options' => ['' => 'اختر'] + $claims,
                        'selected' => $selectedClaimType,
                        'placeholder' => 'اختر',
                        'required' => false,
                        'clearable' => true,
                        'clearValue' => '',
                        'autoSubmit' => true,
                        'showConfirm' => false,
                    ])
                </form>

                <div data-member-panel-action="loans" class="{{ $activeTab === 'loans' ? '' : 'hidden' }}">
                    <a href="#member-loan-request"
                        class="flex gap-3 py-3 px-12 rounded-[12px] justify-center items-center text-[#F4F7F9] text-[16px] font-medium bg-[#124375] navy-shadow">
                        <iconify-icon icon="ic:baseline-plus" class="flex items-center text-2xl"></iconify-icon>
                        طلب قرض
                    </a>
                </div>

                <div data-member-panel-action="subscriptions"
                    class="{{ $activeTab === 'subscriptions' ? '' : 'hidden' }} flex gap-2">
                    <a href="{{ route('subscriptions.create') }}"
                        class="flex gap-3 py-3 px-12 rounded-[12px] justify-center items-center text-[#124375] text-[16px] font-medium bg-[#F4F7F9] navy-shadow">
                        <iconify-icon icon="material-symbols:list-alt-check-rounded"
                            class="flex items-center text-2xl"></iconify-icon>
                        تسجيل سداد اشتراك
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section data-member-panel="claims" class="{{ $activeTab === 'claims' ? '' : 'hidden' }} px-7 py-5">
        @if ($selectedClaimType)
            <form action="{{ route('members.storeClaim', $member->id) }}" method="POST" enctype="multipart/form-data"
                class="mb-5 rounded-2xl border border-[#124375] bg-[#F4F7F9] p-5 navy-shadow">
                @csrf
                <input type="hidden" name="claim_type" value="{{ $selectedClaimType }}">
                <h1 class="text-xl font-semibold text-[#124375] text-center mb-5">
                    {{ $claims[$selectedClaimType] ?? 'مطالبة' }}</h1>
                <div class="documents grid grid-cols-2 gap-y-5 gap-x-4">
                    @foreach ($claimDocumentsByType[$selectedClaimType] ?? ['salary_letter', 'national_id', 'deductions_statement'] as $docKey)
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">
                                {{ $claimDocumentLabels[$docKey] ?? $docKey }}
                            </span>
                            <label for="claim-doc-{{ $docKey }}"
                                class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="claim_documents[{{ $docKey }}]"
                                    id="claim-doc-{{ $docKey }}" class="hidden">
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="declaration space-y-3 mt-5">
                    <h3 class="text-center font-medium">إقرار</h3>
                    <p class="font-medium leading-8">
                        أقر أنا / {{ $member->full_name }} بأن البيانات والمستندات المرفقة صحيحة، وأتحمل المسؤولية عن أي
                        بيانات غير دقيقة.
                    </p>
                    <p class="font-medium">
                        الاسم / {{ $member->full_name }} - الوظيفة / {{ $member->employmentInfo->job_title ?? '-' }} -
                        الرقم القومي / {{ $member->national_id }}
                    </p>
                </div>
                <div class="btns flex gap-2 mt-5">
                    <button type="submit"
                        class="w-full rounded-[14px] py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2">
                        <iconify-icon icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon>
                        تقديم المطالبة
                    </button>
                    <a href="{{ route('members.show', ['member' => $member->id, 'tab' => 'claims']) }}"
                        class="text-center border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">
                        إلغاء
                    </a>
                </div>
            </form>
        @endif

        @if ($memberClaims->isEmpty())
            <div class="no-requests flex justify-center py-14">
                <div class="flex flex-col items-center gap-5">
                    <img src="{{ asset('IMGs/no-requests.png') }}" alt="لا توجد مطالبات" class="max-w-[220px]">
                    <p>لم يتم إضافة مطالبة حتى الآن</p>
                </div>
            </div>
        @else
            <div class="requests-table rounded-[14px] overflow-hidden border border-[#D1D5DB]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم المطالبة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">نوع المطالبة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">حالة الطلب</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ التقديم</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memberClaims as $claim)
                            <tr class="text-center even:bg-[#EFEFEF]">
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $claim->id }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $claims[$claim->type] ?? $claim->type }}</td>
                                <td class="py-4 border-l border-[#D1D5DB]">
                                    <span
                                        class="{{ $claimStatusClasses[$claim->status] ?? 'bg-[#F4F7F9] text-[#6D6D6D] border-[#6D6D6D]' }} border px-4 py-1.5 text-sm rounded-lg">
                                        {{ $claimStatusLabels[$claim->status] ?? $claim->status }}
                                    </span>
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $claim->created_at?->isoFormat('D MMMM YYYY') }}</td>
                                <td class="py-5">
                                    <a href="{{ route('claims.show', $claim->id) }}"
                                        class="bg-[#124375] text-white py-3 navy-shadow px-8 rounded-xl font-medium">عرض
                                        التفاصيل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section data-member-panel="loans" class="{{ $activeTab === 'loans' ? '' : 'hidden' }} px-7 py-5 space-y-5">
        @forelse ($memberLoans as $loan)
            @php
                $paidInstallments = $loan->installments->where('status', 'paid');
                $remainingInstallments = $loan->installments->where('status', '!=', 'paid')->count();
                $remainingAmount = max((float) $loan->total_amount - (float) $paidInstallments->sum('amount'), 0);
            @endphp
            <div class="rounded-[12px] bg-[#F4F7F9] border-2 border-[#124375] py-3 px-3">
                <div class="grid grid-cols-7 gap-3 text-center">
                    <p class="text-[#6D6D6D] text-[14px]">رقم القرض : <span
                            class="text-[16px] text-[#021219]">{{ $loan->id }}</span></p>
                    <p class="text-[#6D6D6D] text-[14px]">قيمة القرض : <span
                            class="text-[16px] text-[#021219]">{{ number_format($loan->total_amount, 2) }}</span></p>
                    <p class="text-[#6D6D6D] text-[14px]">قيمة القسط : <span
                            class="text-[16px] text-[#021219]">{{ number_format($loan->installment_amount, 2) }}</span>
                    </p>
                    <p class="text-[#6D6D6D] text-[14px]">المتبقي : <span
                            class="text-[16px] text-[#021219]">{{ number_format($remainingAmount, 2) }}</span></p>
                    <p class="text-[#6D6D6D] text-[14px]">الأقساط المتبقية : <span
                            class="text-[16px] text-[#021219]">{{ $remainingInstallments }}</span></p>
                    <p class="text-[#6D6D6D] text-[14px]">تاريخ الطلب : <span
                            class="text-[16px] text-[#021219]">{{ $loan->created_at?->isoFormat('D MMMM YYYY') }}</span>
                    </p>
                    <p class="text-[#6D6D6D] text-[14px]">الحالة :
                        <span
                            class="{{ $paymentStatusClasses[$loan->status] ?? 'bg-[#F4F7F9] text-[#6D6D6D] border-[#6D6D6D]' }} text-[14px] border px-2 py-1 rounded-[8px]">
                            {{ $paymentStatusLabels[$loan->status] ?? $loan->status }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم القسط</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الاستحقاق</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->installments->sortBy('due_date')->values() as $index => $installment)
                            <tr class="text-center border-b border-[#D1D5DB] even:bg-[#EFEFEF]">
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $index + 1 }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ number_format($installment->amount, 2) }}</td>
                                <td class="py-4 border-l border-[#D1D5DB]">
                                    <span
                                        class="{{ $paymentStatusClasses[$installment->status] ?? 'bg-[#F4F7F9] text-[#6D6D6D] border-[#6D6D6D]' }} border px-8 py-1 text-sm rounded-lg">
                                        {{ $paymentStatusLabels[$installment->status] ?? $installment->status }}
                                    </span>
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $installment->due_date?->isoFormat('D MMMM YYYY') }}</td>
                                <td class="py-5">
                                    @if ($installment->status === 'paid')
                                        <a href="{{ route('loans.show', $loan->id) }}"
                                            class="text-2xl text-[#124375] inline-flex">
                                            <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                        </a>
                                    @else
                                        <a href="{{ route('loans.show', $loan->id) }}"
                                            class="bg-[#124375] text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-2 px-4">
                                            تسجيل السداد
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="flex justify-center py-14">
                <div class="flex flex-col items-center gap-5">
                    <img src="{{ asset('IMGs/loans.png') }}" alt="لا توجد قروض" class="max-w-[220px]">
                    <p>لا توجد قروض مسجلة لهذا العضو حتى الآن</p>
                </div>
            </div>
        @endforelse

        <form id="member-loan-request" action="{{ route('loans.store') }}" method="POST"
            class="rounded-2xl bg-[#F4F7F9] border border-[#124375] py-5 px-12">
            @csrf
            <input type="hidden" name="member_id" value="{{ $member->id }}">
            <h1 class="text-xl font-semibold text-[#124375] text-center mb-5">طلب تسجيل قرض</h1>
            <div class="requirements grid grid-cols-2 gap-4">
                <div class="relative">
                    @include('partials.dropdown', [
                        'name' => 'total_amount',
                        'label' => 'قيمة القرض',
                        'options' => [
                            '5000' => '5,000',
                            '10000' => '10,000',
                            '20000' => '20,000',
                        ],
                        'selected' => old('total_amount'),
                        'placeholder' => 'اختر',
                        'required' => true,
                        'floatingLabel' => true,
                        'showConfirm' => false,
                    ])
                </div>
                <div class="relative">
                    @include('partials.dropdown', [
                        'name' => 'months',
                        'label' => 'مدة السداد',
                        'options' => [
                            '12' => '12 شهر',
                            '24' => '24 شهر',
                            '32' => '32 شهر',
                        ],
                        'selected' => old('months'),
                        'placeholder' => 'اختر',
                        'required' => true,
                        'floatingLabel' => true,
                        'showConfirm' => false,
                    ])
                </div>
            </div>
            <div class="declaration space-y-3 mt-5">
                <h3 class="text-center font-medium">إقرار</h3>
                <p class="font-medium leading-8">
                    أقر أنا / {{ $member->full_name }} برغبتي في الحصول على القرض الموضح بياناته أعلاه، وأتعهد بالالتزام
                    بكافة الشروط والأحكام.
                </p>
            </div>
            <button type="submit"
                class="mt-5 rounded-[14px] w-full py-3 text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon>
                تأكيد طلب القرض
            </button>
        </form>
    </section>

    <section data-member-panel="subscriptions" class="{{ $activeTab === 'subscriptions' ? '' : 'hidden' }} px-7 py-5">
        @if ($memberSubscriptions->isEmpty())
            <div class="flex justify-center py-14">
                <div class="flex flex-col items-center gap-5">
                    <img src="{{ asset('IMGs/No-results.png') }}" alt="لا توجد اشتراكات" class="max-w-[220px]">
                    <p>لا توجد اشتراكات مسجلة لهذا العضو حتى الآن</p>
                </div>
            </div>
        @else
            <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم الاشتراك</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الاستحقاق</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ التسجيل</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memberSubscriptions->sortByDesc('due_date') as $subscription)
                            <tr class="text-center border-b border-[#D1D5DB] even:bg-[#EFEFEF]">
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $subscription->id }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ number_format($subscription->amount, 2) }} ج.م</td>
                                <td class="py-4 border-l border-[#D1D5DB]">
                                    <span
                                        class="{{ $paymentStatusClasses[$subscription->status] ?? 'bg-[#F4F7F9] text-[#6D6D6D] border-[#6D6D6D]' }} border px-8 py-1 text-sm rounded-lg">
                                        {{ $paymentStatusLabels[$subscription->status] ?? $subscription->status }}
                                    </span>
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $subscription->due_date?->isoFormat('D MMMM YYYY') }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $subscription->created_at?->isoFormat('D MMMM YYYY') }}</td>
                                <td class="py-5">
                                    @if ($subscription->status === 'paid')
                                        <span
                                            class="text-2xl inline-flex gap-4 items-center justify-center text-[#124375]">
                                            <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                            <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                        </span>
                                    @else
                                        <a href="{{ route('subscriptions.create') }}"
                                            class="bg-[#124375] text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-2 px-4">
                                            تسجيل السداد
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const overlay = document.querySelector('.overlay');
                const tabs = document.querySelectorAll('[data-member-tab]');
                const panels = document.querySelectorAll('[data-member-panel]');
                const actions = document.querySelectorAll('[data-member-panel-action]');

                function setTab(tab) {
                    tabs.forEach((button) => {
                        const active = button.dataset.memberTab === tab;
                        button.classList.toggle('bg-[#F4F7F9]', active);
                        button.classList.toggle('border-b-[3px]', active);
                        button.classList.toggle('border-[#124375]', active);
                        button.classList.toggle('bg-[#E6F1FD80]', !active);
                    });

                    panels.forEach((panel) => panel.classList.toggle('hidden', panel.dataset.memberPanel !== tab));
                    actions.forEach((action) => action.classList.toggle('hidden', action.dataset.memberPanelAction !==
                        tab));
                }

                tabs.forEach((button) => button.addEventListener('click', () => setTab(button.dataset.memberTab)));

                document.querySelectorAll('.dropDownBtn').forEach((button) => {
                    button.addEventListener('click', function() {
                        const menu = button.nextElementSibling;
                        if (menu) menu.classList.toggle('hidden');
                    });
                });

                document.querySelectorAll('.open-modal').forEach((button) => {
                    button.addEventListener('click', function() {
                        const modal = document.getElementById(button.dataset.modal);
                        if (!modal) return;
                        modal.classList.remove('hidden');
                        overlay?.classList.remove('hidden');
                    });
                });

                document.querySelectorAll('.modal-close').forEach((button) => {
                    button.addEventListener('click', function() {
                        button.closest('.member-modal')?.classList.add('hidden');
                        overlay?.classList.add('hidden');
                    });
                });
            });
        </script>
    @endpush
@endsection
