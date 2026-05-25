@extends('layouts.pages')

@section('title', 'عرض بيانات العضو')

@section('content')
    @include('partials.flash')
    <link rel="stylesheet" href="{{ asset('css/employee/member.css') }}">

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

    {{-- head --}}
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
            <button data-modal="modal-edit"
                class="open-modal flex items-center justify-center navy-shadow bg-[#124375] text-[#FEFFFC] rounded-xl gap-2 w-full  py-3 ">
                <iconify-icon icon="ic:round-edit" class="mt-1 text-xl"></iconify-icon> تعديل بيانات
            </button>
            <button data-modal="modal1"
                class="flex open-modal items-center red-shadow bg-[#F4F7F9] text-[#D92D20] rounded-xl gap-2 px-20 py-3 border border-[#D92D20]">
                <iconify-icon icon="carbon:close-filled" class="mt-1 text-xl"></iconify-icon> إيقاف العضوية
            </button>
        </div>
    </div>

    <hr class="border border-[#124375] mx-7 my-2">

    {{-- Member Info --}}
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

    <!-- suspension of membership MODAL -->
    <form action="{{ route('members.suspend', $member->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="modal1"
            class="modal hidden max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded m-4 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body px-6 space-y-7">
                <div class="moda-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        تأكيد إيقاف العضوية
                    </h1>
                </div>
                <div class="reason space-y-3">
                    <p class="mr-1 text-[#021219] font-medium ">لإيقاف العضوية، يرجى توضيح سبب الرفض وإرفاق صورة من طلب
                        إيقاف العضوية الموقَّع من العضو أو قرار صندوق الزمالة برفض العضوية.</p>
                    <textarea name="reason" required rows="4"
                        class="resize-none bg-[#F4F7F9] w-full border border-[#124375] rounded-xl outline-none px-2"
                        placeholder="سبب إيقاف العضوية"></textarea>
                </div>
                <div class="document border border-[#124375] rounded-xl text-center">
                    <label for="suspension-file"
                        class="w-full cursor-pointer  py-6 text-[#124375] flex items-center justify-center gap-1">
                        <p>اضغط لإرفاق صورة الملف</p>
                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                        <input type="file" required name="suspension_file" id="suspension-file" class="hidden">
                    </label>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class=" rounded-[14px] w-full py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">إيقاف
                            العضوية</button>
                    </div>
                    <button
                        class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
                </div>
            </div>
        </div>
    </form>
    <!-- end suspension of membership MODAL -->

    {{-- edit modal --}}
    <div id="modal-edit"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تعديل بيانات
                </h1>
            </div>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <p class="text-base font-medium text-[#124375]">الأسم : <span
                            class="text-[#021219] font-semibold text-base">{{ $member->full_name }}</span></p>
                    <p class="text-base font-medium text-[#124375]">رقم العضوية : <span
                            class="text-[#021219] font-semibold text-base">
                            {{ $membership->membership_number ?? '-' }}
                        </span></p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-5">
                        <p class="text-[14px] text-[#021219]">
                            يمكنك تحديث البيانات الموضحة أدناه فقط. يرجى التأكد من دقة المعلومات قبل الحفظ.
                        </p>
                        <form action="{{ route('members.store', $member->id) }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="flex flex-col gap-4">
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                        التليفون</label>
                                    <input type="text" value="{{ $member->phone ?? 'بيانات مفقودة' }}"
                                        placeholder="01234595684"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                </div>
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البريد
                                        الإلكتروني</label>
                                    <input type="text" value="{{ $member->user->email ?? 'بيانات مفقودة' }}"
                                        placeholder="ahmed@gmail.com"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                </div>
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الحالة
                                        الوظيفية</label>
                                    <input type="text" placeholder="معيد بالجامعة"
                                        value="{{ $member->employmentInfo->job_title ?? 'بيانات مفقودة' }}"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                </div>
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">عنوان
                                        محل الإقامة</label>
                                    <input type="text" value="{{ $member->address ?? 'بيانات مفقودة' }}"
                                        placeholder="العنوان الحالي بالتفصيل"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                </div>
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الراتب
                                        الأساسي</label>
                                    <input type="text" placeholder="514 ج.م"
                                        value="{{ $member->employmentInfo->starting_salary ? number_format($member->employmentInfo->starting_salary, 2) . ' ج.م' : 'بيانات مفقودة' }}"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3 bg-[#124375] text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2"><iconify-icon
                            icon="fluent:save-16-filled" class="text-2xl mt-1"></iconify-icon>حفظ التعديلات</button>
                </div>
                <button
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] modal-close">إلغاء</button>
            </div>
        </div>
    </div>
    </form>

    <!-- tabs -->
    <section class="px-7">
        <div class="flex items-center justify-between border border-[#124375] p-3 rounded-xl">
            <div class="tabs flex gap-2">
                <button
                    class="tab text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">الاشتراكات</button>
                <button
                    class="tab text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">قروض</button>
                <button
                    class="active-tab text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">مطالبات</button>
            </div>
            <div>
                <!-- requests only -->
                <div class="tab-content relative" data-tab="مطالبات">
                    <button
                        class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base navy-shadow flex gap-3">نوع
                        المطالبة : <span class="text-[#021219]">أختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow max-w-fit">
                        <a class="button cursor-pointer text-center navy-shadow py-2 px-1 rounded-xl text-base ">بلوغ سن
                            التقاعد القانوني</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">نقل</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base "> وفاة</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">
                            استقالة</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">معاش
                            مبكر</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">انسحاب</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">فصل</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">عجز
                            مهني</a>
                    </div>
                </div>
                <!-- requests only -->
                <!-- loans only -->
                @if ($activeLoan === null)
                    <button id="request-loan-btn"
                        class="tab-content hidden flex gap-3 py-3 px-20 rounded-[12px] justify-center items-center text-[#F4F7F9] text-[16px] font-medium bg-[#124375]"
                        data-tab="قروض">
                        <iconify-icon icon="ic:baseline-plus" class="flex items-center text-2xl"></iconify-icon>
                        طلب قرض
                    </button>
                @endif
                <div id="loans-action-buttons" class="tab-content flex gap-3 hidden" data-tab="قروض">
                    @if ($activeLoan && $activeLoan->status === 'active')
                        <button data-modal="modal6"
                            class="open-modal text-[16px] font-medium  w-52  bg-[#124375] navy-shadow text-[#F4F7F9] py-2 rounded-[12px]">
                            تسديد القرض بالكامل
                        </button>
                    @endif
                    @if ($activeLoan && $activeLoan->status === 'pending')
                        <button
                            class="flex w-52 text-[16px] font-medium items-center justify-center gap-2 bg-[#F4F7F9] navy-shadow py-2 rounded-[12px] text-[#124375]">
                            <iconify-icon icon="material-symbols:print" class="text-xl flex items-center"></iconify-icon>
                            طباعة التفاصيل للمجلس
                        </button>
                        <button data-modal="modal3"
                            class="open-modal text-[16px] font-medium bg-[#124375] w-52 py-2 rounded-[12px] text-[#F4F7F9] navy-shadow">
                            بدء القرض
                        </button>
                    @endif
                    @if ($activeLoan && ($activeLoan->status === 'pending' || $activeLoan->status === 'rejected'))
                        <a href="{{ route('members.previous-loans', $member->id) }}"
                            class="text-center text-[16px] font-medium bg-[#F4F7F9] w-52 navy-shadow py-2 rounded-[12px] text-[#124375]">
                            عرض القروض السابقة
                        </a>
                        <button data-modal="modal4"
                            class="open-modal flex text-[16px] font-medium items-center w-52 justify-center gap-2 border-2 border-[#D92D20] red-shadow text-[#D92D20] py-2 rounded-[12px]">
                            <iconify-icon icon="zondicons:close-solid" class="text-xl flex items-center"></iconify-icon>
                            إلغاء أو رفض الطلب
                        </button>
                    @endif
                </div>
                <!-- end loans only -->
                <div class="tab-content hidden flex gap-2" data-tab="الاشتراكات">
                    @if ($hasOverdue6Months)
                        <button type="button" data-modal="modal8"
                            class="open-modal flex gap-3 py-3 px-12 rounded-[12px] justify-center items-center border border-[#F79009] text-[#F79009] text-[16px] font-medium bg-[#FFF7ED]">
                            <iconify-icon icon="fluent:mail-warning-24-filled"
                                class="flex items-center text-2xl"></iconify-icon>
                            إرسال إخطار مسجل بعلم الوصول
                        </button>
                    @endif
                    <a href="{{ route('members.documents', $member->id) }}"
                        class=" flex gap-3 py-3 px-20 rounded-[12px] justify-center items-center text-[#124375] text-[16px] font-medium bg-[#F4F7F9] navy-shadow">
                        <iconify-icon icon="mdi:file-account" class="flex items-center text-2xl"></iconify-icon>
                        عرض المستندات
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- end tabs -->

    <!-- start requests section -->
    <section class="px-7 py-5">
        <!-- no requests -->
        <!-- hidden -->
        <div class="no-requests flex justify-center py-14 hidden">
            <div class="flex flex-col items-center gap-5">
                <img src="../IMGs/no-requests.png" alt="no-requests">
                <p>لم يتم إضافة مطالبة حتي الآن</p>
            </div>
        </div>
        <!-- end no requests -->
        <!-- requests table -->
        <div class="tab-content requests-table rounded-[14px] overflow-hidden border border-[#D1D5DB]" data-tab="مطالبات">
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
                    <tr class="text-center">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1234567896451</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">بلوغ سن التقاعد القانوني</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#FFFCEF] text-[#D4AF37] border border-[#D4AF37] px-4 py-1.5 text-sm rounded-lg">بإنتظار
                                الأعتماد</span></td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1 فبراير 2026</td>
                        <td class="py-5"><a href="../paymentApprovalPage/payment.html"
                                class="bg-[#124375] text-white py-3 navy-shadow px-8 rounded-xl font-medium">أعتماد
                                الصرف</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- end requests table -->
    </section>
    <!-- end requests section -->

    <!-- loan table -->
    @if ($activeLoan)
        <div class="tab-content hidden mx-7 rounded-[12px] bg-[#F4F7F9] border-2 border-[#124375] py-3 px-3 my-2"
            data-tab="قروض">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">رقم القرض : <span
                            class="text-[16px] text-[#021219]">{{ $activeLoan->id }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">قيمة القرض : <span
                            class="text-[16px] text-[#021219]">{{ number_format($activeLoan->amount, 2) }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">قيمة القرض بالفائدة : <span
                            class="text-[16px] text-[#021219]">{{ number_format($activeLoan->amount, 2) }}</span></p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">إجمالي المتبقي : <span
                            class="text-[16px] text-[#021219]">{{ number_format($activeLoan->installments->where('status', 'unpaid')->sum('amount'), 2) }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">عدد الأقساط المتبقية : <span
                            class="text-[16px] text-[#021219]">{{ $activeLoan->installments->where('status', 'unpaid')->count() }}
                            قسط</span></p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">تاريخ إنتهاء القرض : <span
                            class="text-[16px] text-[#021219]">{{ $activeLoan->installments->last() ? \Carbon\Carbon::parse($activeLoan->installments->last()->due_date)->format('Y-m-d') : 'غير محدد' }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">حالة القرض : <span
                            class="text-[16px] text-[#E6B800] border border-[#E6B800] bg-[#FFF8E1] px-1 rounded-[8px]">{{ $activeLoan->status == 'active' ? 'نشط' : ($activeLoan->status == 'pending' ? 'تحت المراجعة' : 'معتمد') }}</span>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div id="loans-content-container" data-tab="قروض" class="hidden  tab-content px-7 py-5">
        @if ($activeLoan && $activeLoan->installments->count() > 0)
            <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB]">
                <table class="w-full" id="installments-table">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم القسط</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الإستحقاق</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ السداد</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activeLoan->installments as $index => $installment)
                            <tr class="text-center border-b border-[#D1D5DB] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $index + 1 }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ number_format($installment->amount, 2) }}</td>
                                <td class="py-4 border-l border-[#D1D5DB]">
                                    @if ($installment->status === 'paid')
                                        <span
                                            class="bg-[#ECFDF333] text-[#067647CC] border border-[#067647CC] px-12 py-1 text-sm rounded-lg">مدفوع</span>
                                    @elseif($installment->status === 'unpaid' && \Carbon\Carbon::parse($installment->due_date)->isPast())
                                        <span
                                            class="bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] px-12 py-1 text-sm rounded-lg">متأخر</span>
                                    @else
                                        <span
                                            class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-12 py-1 text-sm rounded-lg">مستحق</span>
                                    @endif
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $installment->paid_at ? \Carbon\Carbon::parse($installment->paid_at)->format('Y-m-d') : '-' }}
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $installment->payment_method ?? 'أمر دفع من الجامعة' }}</td>
                                <td class="py-5">
                                    @if ($installment->status === 'paid')
                                        <div class="text-2xl flex gap-7 items-center justify-center text-[#124375]">
                                            <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                            <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                        </div>
                                    @else
                                        <div>
                                            <button data-modal="modal5"
                                                onclick="document.getElementById('payInstallmentForm').action='{{ route('loans.installments.pay', $installment->id) }}'"
                                                class="open-modal bg-[#124375] text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-2 px-4">
                                                تسجيل السداد
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-requests flex justify-center py-14">
                <div class="flex flex-col items-center gap-5">
                    <img src="{{ asset('IMGs/no-requests.png') }}" alt="no-loans">
                    <p>لا يوجد قروض مسجلة حالياً</p>
                </div>
            </div>
        @endif
    </div>
    <!-- loan table -->

    <!-- subscription table -->
    <div data-tab="الاشتراكات" class="hidden  tab-content px-7 py-2">
        @if ($member->membershipInfo && $member->membershipInfo->subscriptions->count() > 0)
            <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم الأشتراك</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الإستحقاق</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ السداد</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($member->membershipInfo->subscriptions as $subscription)
                            <tr class="text-center border-b border-[#D1D5DB] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $subscription->id }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ number_format($subscription->amount, 2) }}</td>
                                <td class="py-4 border-l border-[#D1D5DB]">
                                    @if ($subscription->status === 'paid')
                                        <span
                                            class="bg-[#ECFDF333] text-[#067647CC] border border-[#067647CC] px-12 py-1 text-sm rounded-lg">مدفوع</span>
                                    @elseif($subscription->status === 'unpaid' && \Carbon\Carbon::parse($subscription->due_date)->isPast())
                                        <span
                                            class="bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] px-12 py-1 text-sm rounded-lg">متأخر</span>
                                    @else
                                        <span
                                            class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-12 py-1 text-sm rounded-lg">مستحق</span>
                                    @endif
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ \Carbon\Carbon::parse($subscription->due_date)->format('Y-m-d') }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $subscription->paid_at ? \Carbon\Carbon::parse($subscription->paid_at)->format('Y-m-d') : '-' }}
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $subscription->payment_method ?? 'أمر دفع من الجامعة' }}</td>
                                <td class="py-5">
                                    @if ($subscription->status === 'paid')
                                        <div class="text-2xl flex gap-7 items-center justify-center text-[#124375]">
                                            <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                            <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                        </div>
                                    @else
                                        <div>
                                            <button data-modal="modal7"
                                                onclick="document.getElementById('paySubscriptionForm').action='{{ route('subscriptions.pay', $subscription->id) }}'"
                                                class="open-modal bg-[#124375] text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-2 px-4">
                                                تسجيل السداد
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-requests flex justify-center py-14">
                <div class="flex flex-col items-center gap-5">
                    <img src="{{ asset('IMGs/no-requests.png') }}" alt="no-subscriptions">
                    <p>لا يوجد اشتراكات مسجلة حالياً</p>
                </div>
            </div>
        @endif
    </div>
    <!-- end subscription table -->

    <!-- loan request form -->
    <form action="{{ route('loans.store') }}" method="POST" enctype="multipart/form-data" id="loanStoreForm">
        @csrf
        <input type="hidden" name="member_id" value="{{ $member->id }}">
        <input type="hidden" name="total_amount" id="selected_total_amount" required>
        <input type="hidden" name="months" id="selected_months" required>
        <div id="loan-request-form" class="hidden mx-7  rounded-2xl bg-[#F4F7F9] border border-[#124375] py-3">
            <div class="modal-body space-y-7 px-12">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        طلب تسجيل قرض
                    </h1>
                </div>
                <div class="requirements grid grid-cols-2 gap-2">
                    <div class="relative">
                        <button type="button" id="amount_dropdown_btn"
                            class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center transition-colors">قيمة
                            القرض :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                    icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                        <span id="amount_error_msg"
                            class="hidden absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                            تحديد قيمة القرض</span>
                        <div
                            class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                            <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-amount-option"
                                data-value="5000">5,000</a>
                            <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-amount-option"
                                data-value="10000">10,000</a>
                            <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-amount-option"
                                data-value="20000">20,000</a>
                        </div>
                    </div>
                    <div class="relative">
                        <button type="button" id="months_dropdown_btn"
                            class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center transition-colors">مدة
                            السداد :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                    icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                        <span id="months_error_msg"
                            class="hidden absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                            تحديد مدة السداد</span>
                        <div
                            class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                            <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-months-option"
                                data-value="12">12 شهر</a>
                            <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-months-option"
                                data-value="24">24 شهر</a>
                            <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-months-option"
                                data-value="32">32 شهر</a>
                        </div>
                    </div>
                </div>
                <div class="declaration space-y-3">
                    <h3 class="text-center font-medium">
                        إقرار
                    </h3>
                    <p class="font-medium">
                        أقر أنا / ____________________ برغبتي في الحصول على القرض الموضح بياناته أعلاه من صندوق الزمالة
                        الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان. وأتعهد بالالتزام بكافة الشروط والأحكام،
                        كما أفوض الإدارة المالية بالجامعة بخصم قيمة الأقساط الشهرية من راتبي أو من أي مستحقات مالية أخرى لي،
                        وذلك حتى يتم سداد كامل قيمة القرض.
                    </p>
                    <p class="font-medium">
                        تحريراً في: _____ / _____ /_____ ٢٠ م
                    </p>
                    <p class="font-medium">
                        الاسم / ____________________________ الوظيفة / ____________________________
                    </p>
                    <p class="font-medium">
                        الرقم القومي / ____________________________ التوقيع / ____________________________
                    </p>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <!-- <button class="submit-btn  rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon icon="healthicons:yes"  class="flex items-center text-2xl"></iconify-icon></span>تأكيد الأختيار</button> -->
                        <button type="button" id="print-declaration-btn"
                            class="rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                    icon="material-symbols:print"
                                    class="flex items-center text-2xl"></iconify-icon></span>طباعة الإقرار</button>
                    </div>
                    <button type="button"
                        class="close-loan-request-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
                </div>
            </div>
        </div>
        <!-- end loan request form -->

        <!-- Attach the signed declaration modal -->
        <div id="modal2"
            class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="close-btn text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-5">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        إرفاق الإقرار المُوَقَّع
                    </h1>
                </div>
                <div class="documents space-y-5">
                    <p class="text-[#021219] text-[16px] font-medium">يرجى رفع ملف الإقرار بعد طباعته وتوقيعه.</p>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="declaration_file"
                            class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط هنا لإرفاق ملف الإقرار</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" name="declaration_file" id="declaration_file" class="hidden" required
                                accept=".pdf,.png,.jpg,.jpeg">
                        </label>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class="submit-btn mt-4 rounded-[14px] w-full py-3 text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow"><span><iconify-icon
                                    icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                            وإرسال</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end Attach the signed declaration modal -->

    <!-- request form -->
    <div class="tab-content modal-body space-y-7 px-5" data-tab="مطالبات">
        <div class="modal-title text-center">
            <h1 class="text-xl font-semibold text-[#124375]">
                مطالبة ببلوغ سن التقاعد القانوني
            </h1>
        </div>
        <div class="space-y-3">
            <div class="flex gap-4 ">
                <p class="text-[#124375] text-base font-medium">الأسم : <span
                        class="text-[#021219] text-base font-semibold">أحمد محمد</span></p>
                <p class="text-[#124375] text-base font-medium">رقم العضوية : <span
                        class="text-[#021219] text-base font-semibold">123456789</span></p>
                <p class="text-[#124375] text-base font-medium">الرقم القومي : <span
                        class="text-[#021219] text-base font-semibold">12345678901234</span></p>
                <p class="text-[#124375] text-base font-medium">رقم المطالبة : <span
                        class="text-[#021219] text-base font-semibold">123456789</span></p>
            </div>
            <p>يرجى إرفاق المستندات التالية لإتمام طلب الإحالة إلى المعاش واستلام المستحقات.</p>
        </div>
        <div class="documents grid grid-cols-2 gap-y-5 gap-x-4">
            <!-- common inputs -->
            <!-- خطاب بالمرتب الاساسي مش موجود في النقل -->
            <!-- بطاقة الرقم القومي مش موجودة في الوفاة  -->
            <!-- قرار الاحالة للمعاش مش موجودة في النقل و الوفاة  -->
            <!-- التوقيع في الوفاة هيبقي توقيع الوريث بدل العضو -->
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                    بالمرتب الأساسي<span class="text-[#D92D20]">*</span></span>
                <label for="file-2" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-2" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان
                    بالمبالغ المخصومة<span class="text-[#D92D20]">*</span></span>
                <label for="file-3" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-3" class="hidden" required>
                </label>
            </div>


            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                    بتاريخ التعيين<span class="text-[#D92D20]">*</span></span>
                <label for="file-4" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-4" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه
                    الرقم القومي<span class="text-[#D92D20]">*</span></span>
                <label for="file-5" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-5" class="hidden" required>
                </label>
            </div>


            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    قرار الإحالة للمعاش<span class="text-[#D92D20]">*</span></span>
                <label for="file-6" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-6" class="hidden" required>
                </label>
            </div>

            <div class="relative border border-[#6D6D6D] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#6D6D6D] font-medium bg-[#F4F7F9]">توقيع
                    العضو بصرف مستحقاته</span>
                <label for="file-7" class=" cursor-pointer py-3  text-[#6D6D6D] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-7" class="hidden">
                </label>
            </div>
            <!-- end common inputs -->
            <!-- موجودين في النقل بس  -->
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    إخلاء طرف<span class="text-[#D92D20]">*</span></span>
                <label for="file-8" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-8" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    قرار النقل <span class="text-[#D92D20]">*</span></span>
                <label for="file-9" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-9" class="hidden">
                </label>
            </div>
            <!-- كده النقل خلص -->
            <!-- دول في الوفاة بس -->
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    قرار إنهاء الخدمة <span class="text-[#D92D20]">*</span></span>
                <label for="file-10" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-10" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    شهادة الوفاة <span class="text-[#D92D20]">*</span></span>
                <label for="file-11" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-11" class="hidden">
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    بطاقة الرقم القومي للورثة المستحقين<span class="text-[#D92D20]">*</span></span>
                <label for="file-12" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-12" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    إعلام الوراثة الشرعي <span class="text-[#D92D20]">*</span></span>
                <label for="file-13" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-13" class="hidden">
                </label>
            </div>
            <div class="col-span-2">
                <div class="flex justify-between">
                    <div>
                        <p class="text-base font-medium text-[#124375]">هل يوجد قصر ؟ <span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="flex gap-3">
                        <label for="yes" class="cursor-pointer flex items-center gap-3">
                            <input type="radio" name="answer" id="yes" class="hidden peer" required>
                            <span
                                class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                            <span>نعم</span>
                        </label>
                        <label for="no" class="cursor-pointer flex items-center gap-3">
                            <input type="radio" name="answer" id="no" class="hidden peer" required>
                            <span
                                class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                            <span>لا</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    قرار الوصاية في حالة وجود قصر<span class="text-[#D92D20]">*</span></span>
                <label for="file-14" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-14" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    شهادات ميلاد القصر بالرقم القومي<span class="text-[#D92D20]">*</span></span>
                <label for="file-15" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-15" class="hidden">
                </label>
            </div>

            <!-- كده الوفاة خلصت -->
        </div>
        <div class="declaration space-y-3">
            <h3 class="text-center font-medium">
                إقرار
            </h3>
            <p class="font-medium">أقر أنا / __________ بأنني قد قمت باستلام كافة مستحقاتي من صندوق الزمالة الخاص بأعضاء
                هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان،
                وذلك اعتبارًا من تاريخ _____ / _____ / ________، وأقر بعدم أحقيتي في المطالبة بأي مستحقات أخرى بعد هذا
                التاريخ
            </p>
            <p class="font-medium">
                الاسم / _______________ الوظيفة / _______________ الرقم القومي / ____________________ التوقيع /
                ________________
            </p>
        </div>
        <div class="btns flex gap-2 ">
            <form class="w-full">
                <button type="button"
                    class="submit-btn rounded-[14px] w-full py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2"><span><iconify-icon
                            icon="material-symbols:print" class="flex items-center text-2xl"></iconify-icon></span>
                    طباعة الإقرار</button>
            </form>
            <button
                class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
        </div>
    </div>
    <!-- end request form -->

    <!-- second step of request form -->
    <div
        class="request-modal-2 hidden w-full max-w-5xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="close-btn text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-5">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    مطالبة ببلوغ سن التقاعد القانوني
                </h1>
            </div>
            <div class="space-y-3">
                <div class="flex gap-4 ">
                    <p class="text-[#124375] text-base font-medium">الأسم : <span
                            class="text-[#021219] text-base font-semibold">أحمد محمد</span></p>
                    <p class="text-[#124375] text-base font-medium">رقم العضوية : <span
                            class="text-[#021219] text-base font-semibold">123456789</span></p>
                    <p class="text-[#124375] text-base font-medium">الرقم القومي : <span
                            class="text-[#021219] text-base font-semibold">12345678901234</span></p>
                    <p class="text-[#124375] text-base font-medium">رقم المطالبة : <span
                            class="text-[#021219] text-base font-semibold">123456789</span></p>
                </div>
                <p>يرجى إرفاق المستندات التالية لإتمام طلب الإحالة إلى المعاش واستلام المستحقات.</p>
            </div>
            <div class="documents space-y-5">
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                            بالمرتب الأساسي</span>
                        <label for="file-1"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                            قرار الإحالة للمعاش</span>
                        <label for="file-16"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-16" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان
                            بالمبالغ المخصومة</span>
                        <label for="file-17"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-17" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                            بتاريخ التعيين</span>
                        <label for="file-18"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-18" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه
                            الرقم القومي</span>
                        <label for="file-19"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-19" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">توقيع
                            العضو بصرف مستحقاته <span class="text-[#D92D20]">*</span></span>
                        <label for="file-20"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-20" class="hidden" required>
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تقديم
                        المطالبة</button>
                </form>
                <button
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- end second step of request form -->

    <!-- start loan modal -->
    <form action="{{ route('loans.start', $memberLoans->first()->id ?? 0) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div id="modal3"
            class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-12">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        بدء القرض
                    </h1>
                </div>
                <div class="space-y-4">
                    <div class="space-y-4">
                        <h2 class="text-[#021219] text-base font-medium">
                            بيانات العضو
                        </h2>
                        <div class="flex gap-3">
                            <p class="text-base font-medium text-[#124375]">اسم العضو :<span
                                    class="text-[#021219] font-semibold text-base">{{ $member->full_name }}</span></p>
                            <p class="text-base font-medium text-[#124375]">رقم القرض :<span
                                    class="text-[#021219] font-semibold text-base">{{ $activeLoan->id ?? 'غير متوفر' }}</span>
                            </p>
                            <p class="text-base font-medium text-[#124375]">قيمة القرض :<span
                                    class="text-[#021219] font-semibold text-base">{{ number_format($activeLoan->amount ?? 0, 2) }}
                                    جنيه</span></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h2 class="text-[#021219] text-base font-medium">
                            بيانات القرض
                        </h2>
                        <div class="flex gap-3">
                            <p class="text-base font-medium text-[#124375]">المبلغ المتبقي :<span
                                    class="text-[#021219] font-semibold text-base">{{ $activeLoan ? number_format($activeLoan->installments->where('status', 'unpaid')->sum('amount'), 2) : '0.00' }}
                                    ج.م</span></p>
                            <p class="text-base font-medium text-[#124375]">عدد الأقساط :<span
                                    class="text-[#021219] font-semibold text-base">{{ $activeLoan ? $activeLoan->installments->count() : 0 }}
                                    شهر</span></p>
                            <p class="text-base font-medium text-[#124375]">تاريخ بداية القرض :<span
                                    class="text-[#021219] font-semibold text-base">{{ now()->format('Y-m-d') }}</span></p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-5">
                        <div>
                            <p class="text-[16px] font-medium text-[#021219]">
                                سيتم بدء القرض واحتساب الأقساط اعتبارًا من تاريخ اليوم.
                            </p>
                            <p class="text-[16px] font-medium text-[#021219]">
                                يرجى إدخال رقم الشيك وإرفاق المستندات المطلوبة قبل التأكيد.
                            </p>
                        </div>
                        <div class="border border-[#124375] rounded-[12px] ">
                            <label for="file-21"
                                class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الشيك</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-21" name="check_image" class="hidden">
                            </label>
                        </div>
                        <div class="border border-[#124375] rounded-[12px] ">
                            <label for="file-22"
                                class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة موافقة المجلس</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-22" name="board_approval_image" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                    icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                            بدء القرض</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('loans.cancel', $memberLoans->first()->id ?? 0) }}" method="POST">
        @csrf
        <div id="modal4"
            class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-12">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        تأكيد إلغاء الطلب
                    </h1>
                </div>
                <div class="space-y-4">
                    <div class="space-y-4">
                        <h2 class="text-[#021219] text-base font-medium">
                            بيانات القرض
                        </h2>
                        <div class="flex gap-3">
                            <p class="text-base font-medium text-[#124375]">قيمة القرض :<span
                                    class="text-[#021219] font-semibold text-base">{{ number_format($activeLoan->amount ?? 0, 2) }}
                                    جنيه</span></p>
                            <p class="text-base font-medium text-[#124375]">رقم القرض :<span
                                    class="text-[#021219] font-semibold text-base">{{ $activeLoan->id ?? 'غير متوفر' }}</span>
                            </p>
                            <p class="text-base font-medium text-[#124375]">تاريخ تقديم الطلب :<span
                                    class="text-[#021219] font-semibold text-base">{{ $activeLoan ? $activeLoan->created_at->format('Y-m-d') : 'غير متوفر' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-5">
                        <div class="flex flex-col gap-3">
                            <p class="text-[16px] font-medium text-[#021219]">
                                يرجى تحديد سبب عدم استكمال طلب القرض:
                            </p>
                            <div class="flex flex-col gap-3">
                                <label class="flex items-center gap-2 w-fit cursor-pointer">
                                    <input type="radio" name="reason" value="member_request" class="peer hidden">
                                    <span
                                        class="mt-2 w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                    <span class=" text-[16px] font-normal text-[#021219]">إلغاء بناءً على رغبة العضو</span>
                                </label>
                                <label class="flex items-center gap-2 w-fit cursor-pointer">
                                    <input type="radio" name="reason" value="board_rejection" class="peer hidden">
                                    <span
                                        class="mt-2 w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                    <span class=" text-[16px] font-normal text-[#021219]">رفض الطلب بواسطة المجلس</span>
                                </label>
                            </div>
                        </div>
                        <div class="reason">
                            <textarea required rows="4" name="details"
                                class="resize-none bg-[#F4F7F9] w-full border border-[#0A2A4E] rounded-xl outline-none px-2 p-3 placeholder:text-[#0A2A4E]"
                                placeholder="سبب الإلغاء أو رقم المجلس الذي تم فيه الرفض"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class=" rounded-[14px] w-full py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">تأكيد
                            إلغاء القرض</button>
                    </div>
                    <button
                        class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('loans.installments.pay', 0) }}" method="POST" enctype="multipart/form-data"
        id="payInstallmentForm">
        @csrf
        <div id="modal5"
            class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-12">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        تسجيل سداد قسط قرض
                    </h1>
                </div>
                <div class="space-y-7">
                    <div>
                        <p>يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.</p>
                    </div>
                    <div class="flex flex-col gap-5">
                        <div class="relative w-full">
                            <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                                رقم الإيصال <span class="text-[#D92D20]">*</span>
                            </label>
                            <input type="text" name="receipt_number" placeholder="FJB2116708086230"
                                class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                        </div>
                        <div class=" border border-[#124375] rounded-2xl w-full">
                            <label for="file-23"
                                class=" cursor-pointer py-10  text-[#6D6D6D] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة إيصال السداد</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-23" name="receipt_image" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                    icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                            السداد</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('loans.earlyRepayment', $memberLoans->first()->id ?? 0) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div id="modal6"
            class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-7">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        تسجيل سداد مبكر لأقساط القرض
                    </h1>
                </div>
                <div class="space-y-7">
                    <div class="flex flex-col gap-3">
                        <p class="text-[18px] font-medium text-[#021219]">سيتم إنهاء القرض بالكامل، وسيتم احتساب جميع
                            الأقساط المتبقية كمدفوعة.</p>
                        <p class="text-[18px]">المبلغ المطلوب للسداد الكامل : <span class="font-medium text-[#124375]">
                                {{ $activeLoan ? number_format($activeLoan->installments->where('status', 'unpaid')->sum('amount'), 2) : '0.00' }}
                            </span> جنيه</p>
                        <p class="text-[16px] font-medium text-[#021219]">يرجى إرفاق رقم و صورة الإيصال السداد لإتمام
                            العملية.</p>
                    </div>
                    <div class="flex flex-col gap-5">
                        <div class="relative w-full">
                            <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                                رقم الإيصال <span class="text-[#D92D20]">*</span>
                            </label>
                            <input type="text" name="receipt_number" placeholder="FJB2116708086230"
                                class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                        </div>
                        <div class=" border border-[#124375] rounded-2xl w-full">
                            <label for="file-24"
                                class=" cursor-pointer py-10  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة إيصال السداد</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-24" name="receipt_image" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                    icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                            السداد</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('subscriptions.pay', 0) }}" method="POST" enctype="multipart/form-data"
        id="paySubscriptionForm">
        @csrf
        <div id="modal7"
            class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-12">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        تسجيل سداد الاشتراك
                    </h1>
                </div>
                <div class="space-y-7">
                    <div>
                        <p>يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.</p>
                    </div>
                    <div class="flex flex-col gap-5">
                        <div class="relative w-full">
                            <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                                رقم الإيصال <span class="text-[#D92D20]">*</span>
                            </label>
                            <input type="text" name="receipt_number" placeholder="FJB2116708086230"
                                class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                        </div>
                        <div class=" border border-[#124375] rounded-2xl w-full">
                            <label for="file-25"
                                class=" cursor-pointer py-10  text-[#6D6D6D] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة إيصال السداد</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-25" name="receipt_image" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                    icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                            السداد</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('members.notify', $member->id) }}" method="POST" id="notifySubscriptionForm">
        @csrf
        <div id="modal8"
            class="hidden w-full max-w-4xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-12">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        إرسال إخطار رسمي للسداد
                    </h1>
                </div>
                <div class="space-y-7">
                    <div class="flex gap-2">
                        <iconify-icon icon="bxs:error" class="text-[#E6B800] text-xl mt-1"></iconify-icon>
                        <p>تنبيه: العضو متأخر لمدة <span>
                                {{ $member->membershipInfo ? $member->membershipInfo->subscriptions->where('status', 'unpaid')->count() : 0 }}
                            </span>شهور ويستوجب الإنذار القانوني.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4 ">
                        <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                            <p class="text-[#124375] text-[16px] ">الاسم : <span
                                    class="text-[#021219] text-[16px] font-semibold">{{ $member->full_name }}</span></p>
                        </div>
                        <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                            <p class="text-[#124375] text-[16px] ">رقم العضوية : <span
                                    class="text-[#021219] text-[16px] font-semibold">{{ $member->membershipInfo->membership_number ?? 'غير متوفر' }}</span>
                            </p>
                        </div>
                        <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                            <p class="text-[#124375] text-[16px] ">شهور التأخير : <span
                                    class="text-[#021219] text-[16px] font-semibold">{{ $member->membershipInfo ? $member->membershipInfo->subscriptions->where('status', 'unpaid')->count() : 0 }}
                                    شهور</span></p>
                        </div>
                        <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                            <p class="text-[#124375] text-[16px] ">إجمالي المبلغ المستحق : <span
                                    class="text-[#021219] text-[16px] font-semibold">{{ number_format($member->membershipInfo ? $member->membershipInfo->subscriptions->where('status', 'unpaid')->sum('amount') : 0, 2) }}
                                    ج.م </span></p>
                        </div>
                        <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                            <p class="text-[#124375] text-[16px] ">أقدم شهر مستحق : <span
                                    class="text-[#021219] text-[16px] font-semibold">{{ $member->membershipInfo ? optional($member->membershipInfo->subscriptions->where('status', 'unpaid')->sortBy('due_date')->first())->due_date?->format('M Y') ?? 'لا يوجد' : 'لا يوجد' }}</span>
                            </p>
                        </div>
                        <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                            <p class="text-[#124375] text-[16px] ">تاريخ إرسال الإخطار : <span
                                    class="text-[#021219] text-[16px] font-semibold">{{ now()->format('Y-m-d') }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="border border-[#124375] rounded-[10px] py-5 px-3">
                        <p>يحيطكم الصندوق علماً بتأخركم في سداد الاشتراكات لمدة
                            {{ $member->membershipInfo ? $member->membershipInfo->subscriptions->where('status', 'unpaid')->count() : 0 }}
                            أشهر، ويرجى السداد في موعد غايته "شهر" من
                            تاريخه وإلا سيتم فصل العضوية نهائياً وفقاً للمادة (8). </p>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <button type="submit"
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="ic:round-email" class="flex items-center text-2xl"></iconify-icon></span>طباعة
                        وإرسال
                        الإخطار</button>
                    <button
                        class="close-btn rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2  text-[#124375] border border-[#124375] navy-shadow ">إلغاء</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const requestLoanBtn = document.getElementById('request-loan-btn');
            const loanRequestForm = document.getElementById('loan-request-form');
            const loansContentContainer = document.getElementById('loans-content-container');
            const loansActionButtons = document.getElementById('loans-action-buttons');
            const closeLoanRequestBtn = document.querySelector('.close-loan-request-modal');

            if (requestLoanBtn && loanRequestForm) {
                requestLoanBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    loanRequestForm.classList.remove('hidden');
                    if (loansContentContainer) loansContentContainer.classList.add('hidden');
                    if (loansActionButtons) loansActionButtons.classList.add('hidden');
                });
            }

            if (closeLoanRequestBtn && loanRequestForm) {
                closeLoanRequestBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    loanRequestForm.classList.add('hidden');
                    if (loansContentContainer) loansContentContainer.classList.remove('hidden');
                    if (loansActionButtons) loansActionButtons.classList.remove('hidden');
                });
            }

            const tabs = document.querySelectorAll('.tabs button');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    if (loanRequestForm) {
                        loanRequestForm.classList.add('hidden');
                    }
                });
            });

            // Handle custom dropdowns for loan form
            document.querySelectorAll('.loan-amount-option').forEach(option => {
                option.addEventListener('click', function() {
                    const selectedAmount = document.getElementById('selected_total_amount');
                    if (selectedAmount) selectedAmount.value = this.dataset.value;

                    const amountBtn = document.getElementById('amount_dropdown_btn');
                    const amountError = document.getElementById('amount_error_msg');
                    if (amountBtn) {
                        amountBtn.classList.remove('border', 'border-[#D92D20]', 'text-[#D92D20]');
                        amountBtn.classList.add('text-[#124375]');
                    }
                    if (amountError) amountError.classList.add('hidden');
                });
            });
            document.querySelectorAll('.loan-months-option').forEach(option => {
                option.addEventListener('click', function() {
                    const selectedMonths = document.getElementById('selected_months');
                    if (selectedMonths) selectedMonths.value = this.dataset.value;

                    const monthsBtn = document.getElementById('months_dropdown_btn');
                    const monthsError = document.getElementById('months_error_msg');
                    if (monthsBtn) {
                        monthsBtn.classList.remove('border', 'border-[#D92D20]', 'text-[#D92D20]');
                        monthsBtn.classList.add('text-[#124375]');
                    }
                    if (monthsError) monthsError.classList.add('hidden');
                });
            });

            const printBtn = document.getElementById('print-declaration-btn');
            if (printBtn) {
                printBtn.addEventListener('click', function(e) {
                    const totalAmount = document.getElementById('selected_total_amount');
                    const months = document.getElementById('selected_months');
                    let isValid = true;

                    const amountBtn = document.getElementById('amount_dropdown_btn');
                    const amountError = document.getElementById('amount_error_msg');
                    if (!totalAmount || !totalAmount.value) {
                        if (amountBtn) {
                            amountBtn.classList.add('border', 'border-[#D92D20]', 'text-[#D92D20]');
                            amountBtn.classList.remove('text-[#124375]');
                        }
                        if (amountError) amountError.classList.remove('hidden');
                        isValid = false;
                    }

                    const monthsBtn = document.getElementById('months_dropdown_btn');
                    const monthsError = document.getElementById('months_error_msg');
                    if (!months || !months.value) {
                        if (monthsBtn) {
                            monthsBtn.classList.add('border', 'border-[#D92D20]', 'text-[#D92D20]');
                            monthsBtn.classList.remove('text-[#124375]');
                        }
                        if (monthsError) monthsError.classList.remove('hidden');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        return false;
                    }

                    // Open the modal and overlay manually since we removed open-modal class
                    const modal2 = document.getElementById('modal2');
                    const overlay = document.querySelector('.overlay');
                    if (modal2) modal2.classList.remove('hidden');
                    if (overlay) overlay.classList.remove('hidden');

                    // Print the declaration
                    const printContents = document.querySelector('.declaration').innerHTML;
                    const printWindow = window.open('', '_blank', 'height=600,width=800');
                    printWindow.document.write('<html dir="rtl"><head><title>طباعة الإقرار</title>');
                    printWindow.document.write(
                        '<style>body{font-family: "Tajawal", Arial, sans-serif; padding: 40px; font-size: 18px;} h3{text-align: center; margin-bottom: 30px;} p{margin-bottom: 30px; line-height: 1.8; font-weight: bold;}</style>'
                    );
                    printWindow.document.write('</head><body>');
                    printWindow.document.write(printContents);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();

                    // Small delay to ensure styles are applied
                    setTimeout(function() {
                        printWindow.focus();
                        printWindow.print();
                    }, 250);
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('installments-table');
            if (!table) return;
            const tbody = table.querySelector('tbody');
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const rowsPerPage = 12;

            if (rows.length > rowsPerPage) {
                const totalPages = Math.ceil(rows.length / rowsPerPage);

                function displayPage(page) {
                    rows.forEach((row, index) => {
                        if (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                displayPage(1);

                const paginationContainer = document.createElement('div');
                paginationContainer.className = 'flex justify-center items-center gap-2 mt-6 pb-6';

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.innerText = i;
                    btn.className =
                        'w-10 h-10 flex items-center justify-center rounded-lg border font-medium transition-colors ' +
                        (i === 1 ? 'bg-[#124375] text-white border-[#124375]' :
                            'bg-white text-[#124375] border-[#124375] hover:bg-gray-50');

                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        displayPage(i);

                        Array.from(paginationContainer.children).forEach(child => {
                            child.className =
                                'w-10 h-10 flex items-center justify-center rounded-lg border font-medium transition-colors bg-white text-[#124375] border-[#124375] hover:bg-gray-50';
                        });
                        this.className =
                            'w-10 h-10 flex items-center justify-center rounded-lg border font-medium transition-colors bg-[#124375] text-white border-[#124375]';
                    });

                    paginationContainer.appendChild(btn);
                }

                const tableWrapper = table.closest('.rounded-\\[14px\\]');
                if (tableWrapper) {
                    tableWrapper.parentNode.insertBefore(paginationContainer, tableWrapper.nextSibling);
                }
            }
        });
    </script>
    <script src="{{ asset('js/employee/member.js') }}?v={{ time() }}"></script>
@endsection
