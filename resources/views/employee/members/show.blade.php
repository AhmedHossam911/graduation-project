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

        $tabMapping = [
            'subscriptions' => 'الاشتراكات',
            'loans' => 'قروض',
            'claims' => 'مطالبات',
        ];
        if (in_array(request('tab'), $tabMapping)) {
            $activeTabName = request('tab');
        } else {
            $activeTabName = $tabMapping[$activeTab] ?? 'الاشتراكات';
        }

        $statusCode = $membership->status ?? 'unknown';
        $statusData = $statusMap[$statusCode] ?? ['label' => 'غير معروف', 'class' => 'unknown'];
        $badgeClass = match ($statusCode) {
            'active' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'registering', 'pending_registration' => 'bg-[#EFF8FF] text-[#175CD3] border-[#175CD3]',
            'loaned' => 'bg-[#F9F5FF] text-[#6941C6] border-[#6941C6]',
            'pension_eligible' => 'bg-[#FFFCEF] text-[#D4AF37] border-[#D4AF37]',
            'withdrawn' => 'bg-[#FFF7ED] text-[#F79009] border-[#F79009]',
            'dismissed', 'suspended' => 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]',
            'unpaid_leave' => 'bg-[#F2F4F7] text-[#475467] border-[#475467]',
            'membership_expired' => 'bg-[#F2F4F7] text-[#101828] border-[#101828]',
            default => 'bg-[#F2F4F7] text-[#475467] border-[#475467]',
        };

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

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60] print:hidden"></div>

    {{-- head --}}
    <div class="flex justify-between items-center px-10 py-5 print:hidden">
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
            @if (auth()->user() && auth()->user()->hasPermission('إدارة الأعضاء'))
                <button data-modal="modal-edit"
                    class="open-modal flex items-center justify-center navy-shadow bg-[#124375] text-[#FEFFFC] rounded-xl gap-2 w-full  py-3 ">
                    <iconify-icon icon="ic:round-edit" class="mt-1 text-xl"></iconify-icon> تعديل بيانات
                </button>
                <button data-modal="modal1"
                    class="flex open-modal items-center red-shadow bg-[#F4F7F9] text-[#D92D20] rounded-xl gap-2 px-20 py-3 border border-[#D92D20]">
                    <iconify-icon icon="carbon:close-filled" class="mt-1 text-xl"></iconify-icon> إيقاف العضوية
                </button>
            @endif
        </div>
    </div>

    <hr class="border border-[#124375] mx-7 my-2 print:hidden">

    {{-- Member Info --}}
    <section class="py-5 px-7 print:hidden">
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
                                value="{{ $member->employmentInfo->job_title ?? 'بيانات مفقودة' }}"
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
    <section class="px-7 print:hidden">
        <div class="flex items-center justify-between border border-[#124375] p-3 rounded-xl">
            <div class="tabs flex gap-2">
                <button
                    class="{{ $activeTabName === 'الاشتراكات' ? 'active-tab' : 'tab' }} text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">الاشتراكات</button>
                <button
                    class="{{ $activeTabName === 'قروض' ? 'active-tab' : 'tab' }} text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">قروض</button>
                <button
                    class="{{ $activeTabName === 'مطالبات' ? 'active-tab' : 'tab' }} text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">مطالبات</button>
            </div>
            <div>
                <!-- requests only -->
                <div class="tab-content relative" data-tab="مطالبات">
                    @if (auth()->user() && auth()->user()->hasPermission('إدارة المطالبات'))
                        <button
                            class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base navy-shadow flex gap-3">نوع
                            المطالبة : @if (isset($selectedClaimType))
                                <span class="text-[#021219]">{{ $claims[$selectedClaimType] ?? 'أختر' }}</span>
                            @else
                                <span class="text-[#021219]">أختر</span>
                            @endif
                            <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                    class="text-xl"></iconify-icon></span></button>
                        <div
                            class="dropDown hidden absolute z-[80] bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow max-w-fit">
                            <a href="{{ url('/members/' . $member->id . '?claim_type=retirement&tab=مطالبات') }}"
                                class="button cursor-pointer text-center navy-shadow py-2 px-1 rounded-xl text-base ">بلوغ
                                سن
                                التقاعد القانوني</a>
                            <a href="{{ url('/members/' . $member->id . '?claim_type=transfer&tab=مطالبات') }}"
                                class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">نقل</a>
                            <a href="{{ url('/members/' . $member->id . '?claim_type=death&tab=مطالبات') }}"
                                class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base "> وفاة</a>
                            <a href="{{ url('/members/' . $member->id . '?claim_type=resignation&tab=مطالبات') }}"
                                class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">
                                استقالة</a>
                            <a href="{{ url('/members/' . $member->id . '?claim_type=early_retirement&tab=مطالبات') }}"
                                class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">معاش
                                مبكر</a>
                            <a href="{{ url('/members/' . $member->id . '?claim_type=withdrawal&tab=مطالبات') }}"
                                class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">انسحاب</a>
                            <a href="{{ url('/members/' . $member->id . '?claim_type=expulsion&tab=مطالبات') }}"
                                class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">فصل</a>
                            <a href="{{ url('/members/' . $member->id . '?claim_type=professional_disability&tab=مطالبات') }}"
                                class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">عجز
                                مهني</a>
                        </div>
                    @endif
                </div>
                <!-- requests only -->
                <!-- loans only -->
                @if (auth()->user() && auth()->user()->hasPermission('إدارة القروض'))
                    <div class="flex gap-3">
                        @if ($activeLoan === null)
                            <button id="request-loan-btn"
                                class="tab-content {{ $activeTabName === 'قروض' ? '' : 'hidden' }} flex gap-3 py-3 px-20 rounded-[12px] justify-center items-center text-[#F4F7F9] text-[16px] font-medium bg-[#124375]"
                                data-tab="قروض">
                                <iconify-icon icon="ic:baseline-plus" class="flex items-center text-2xl"></iconify-icon>
                                طلب قرض
                            </button>
                        @endif
                        <div id="loans-action-buttons"
                            class="tab-content flex gap-3 {{ $activeTabName === 'قروض' ? '' : 'hidden' }}"
                            data-tab="قروض">
                            @if ($activeLoan && $activeLoan->status === 'active')
                                <button data-modal="modal6"
                                    class="open-modal text-[16px] font-medium  w-52  bg-[#124375] navy-shadow text-[#F4F7F9] py-2 rounded-[12px]">
                                    تسديد القرض بالكامل
                                </button>
                            @endif
                            @if ($activeLoan && $activeLoan->status === 'pending')
                                <button type="button" onclick="window.open('{{ route('print.board_details', $activeLoan->id) }}', '_blank')"
                                    class="flex w-52 text-[16px] font-medium items-center justify-center gap-2 bg-[#F4F7F9] navy-shadow py-2 rounded-[12px] text-[#124375]">
                                    <iconify-icon icon="material-symbols:print"
                                        class="text-xl flex items-center"></iconify-icon>
                                    طباعة التفاصيل للمجلس
                                </button>
                                <button data-modal="modal3"
                                    class="open-modal text-[16px] font-medium bg-[#124375] w-52 py-2 rounded-[12px] text-[#F4F7F9] navy-shadow">
                                    بدء القرض
                                </button>
                            @endif
                            @if ($memberLoans->count() > 0)
                                <a href="{{ route('members.previous-loans', $member->id) }}"
                                    class="text-center text-[16px] font-medium bg-[#F4F7F9] w-52 navy-shadow py-2 rounded-[12px] text-[#124375]">
                                    عرض القروض السابقة
                                </a>
                            @endif
                            @if ($activeLoan && ($activeLoan->status === 'pending' || $activeLoan->status === 'rejected'))
                                <button data-modal="modal4"
                                    class="open-modal flex text-[16px] font-medium items-center w-52 justify-center gap-2 border-2 border-[#D92D20] red-shadow text-[#D92D20] py-2 rounded-[12px]">
                                    <iconify-icon icon="zondicons:close-solid"
                                        class="text-xl flex items-center"></iconify-icon>
                                    إلغاء أو رفض الطلب
                                </button>
                            @endif
                        </div>
                    </div>

                @endif
                <!-- end loans only -->
                <div class="tab-content flex gap-2 {{ $activeTabName === 'الاشتراكات' ? '' : 'hidden' }}"
                    data-tab="الاشتراكات">
                    @if (auth()->user() && auth()->user()->hasPermission('إدارة الاشتراكات'))
                        @if ($hasOverdue6Months)
                            <button type="button" data-modal="modal8"
                                class="open-modal flex gap-3 py-3 px-12 rounded-[12px] justify-center items-center border border-[#F79009] text-[#F79009] text-[16px] font-medium bg-[#FFF7ED]">
                                <iconify-icon icon="fluent:mail-warning-24-filled"
                                    class="flex items-center text-2xl"></iconify-icon>
                                إرسال إخطار مسجل بعلم الوصول
                            </button>
                        @endif
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
    <div class="tab-content {{ $activeTabName === 'مطالبات' ? '' : 'hidden' }} print:hidden" data-tab="مطالبات">
        <!-- first step of request -->
        @if (request('claim_type') !== null && request('view_claim') === null)
            @if (auth()->user() && auth()->user()->hasPermission('إدارة المطالبات'))
                <form action="{{ route('members.storeClaim', $member->id) }}" method="POST"
                    enctype="multipart/form-data" class="w-full">
                    @csrf
                    <input type="hidden" name="claim_type" value="{{ request('claim_type') }}">
                    <div class="tab-content modal-body space-y-7 mt-6 px-5" data-tab="مطالبات">
                        <div class="modal-title text-center">
                            <h1 class="text-xl font-semibold text-[#124375]">
                                {{ $claims[request('claim_type')] ?? '' }}
                            </h1>
                        </div>
                        <div class="space-y-3">
                            <div class="flex gap-4">
                                <p class="text-[#124375] text-base font-medium">الأسم : <span
                                        class="text-[#021219] text-base font-semibold">{{ $member->full_name }}</span></p>
                                <p class="text-[#124375] text-base font-medium">رقم العضوية : <span
                                        class="text-[#021219] text-base font-semibold">{{ $member->membershipInfo->membership_number ?? '-' }}</span>
                                </p>
                                <p class="text-[#124375] text-base font-medium">الرقم القومي : <span
                                        class="text-[#021219] text-base font-semibold">{{ $member->national_id }}</span>
                                </p>
                            </div>
                            <p>يرجى إرفاق المستندات التالية لإتمام مطالبة ({{ $claims[request('claim_type')] ?? '' }})
                                واستلام
                                المستحقات.</p>
                        </div>

                        <div class="documents grid grid-cols-2 gap-y-5 gap-x-4">
                            <!-- common inputs -->
                            @if (request('claim_type') !== 'transfer')
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                                        بالمرتب الأساسي<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-salary"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[salary_letter]" id="file-salary"
                                            class="hidden" required>
                                    </label>
                                </div>
                            @endif

                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان
                                    بالمبالغ المخصومة<span class="text-[#D92D20]">*</span></span>
                                <label for="file-deductions"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[deductions_statement]" id="file-deductions"
                                        class="hidden" required>
                                </label>
                            </div>

                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                                    بتاريخ التعيين<span class="text-[#D92D20]">*</span></span>
                                <label for="file-appointment"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[appointment_letter]" id="file-appointment"
                                        class="hidden" required>
                                </label>
                            </div>

                            @if (request('claim_type') !== 'death')
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه
                                        الرقم القومي<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-national"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[national_id]" id="file-national"
                                            class="hidden" required>
                                    </label>
                                </div>
                            @endif

                            @if (request('claim_type') !== 'transfer' && request('claim_type') !== 'death')
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        قرار الإحالة للمعاش<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-retirement"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[retirement_decision]"
                                            id="file-retirement" class="hidden" required>
                                    </label>
                                </div>
                            @endif



                            @if (request('claim_type') === 'transfer')
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        إخلاء طرف<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-release"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[release_form]" id="file-release"
                                            class="hidden" required>
                                    </label>
                                </div>
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        قرار النقل<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-transfer"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[service_end_decision]" id="file-transfer"
                                            class="hidden" required>
                                    </label>
                                </div>
                            @endif

                            @if (request('claim_type') === 'death')
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        قرار إنهاء الخدمة<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-death-end"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[service_end_decision]"
                                            id="file-death-end" class="hidden" required>
                                    </label>
                                </div>
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        شهادة الوفاة<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-death-cert"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[death_certificate]" id="file-death-cert"
                                            class="hidden" required>
                                    </label>
                                </div>
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        بطاقة الرقم القومي للورثة المستحقين<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-heirs"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[heirs_ids]" id="file-heirs"
                                            class="hidden" required>
                                    </label>
                                </div>
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        إعلام الوراثة الشرعي<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-inheritance"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[inheritance_notice]"
                                            id="file-inheritance" class="hidden" required>
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
                                                <input type="radio" name="has_minors" value="1" id="yes"
                                                    class="hidden peer" required
                                                    onclick="document.getElementById('minors_files').classList.remove('hidden')">
                                                <span
                                                    class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                                <span>نعم</span>
                                            </label>
                                            <label for="no" class="cursor-pointer flex items-center gap-3">
                                                <input type="radio" name="has_minors" value="0" id="no"
                                                    class="hidden peer" required checked
                                                    onclick="document.getElementById('minors_files').classList.add('hidden')">
                                                <span
                                                    class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                                <span>لا</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="minors_files" class="col-span-2 grid grid-cols-2 gap-y-5 gap-x-4 hidden">
                                    <div class="relative border border-[#124375] rounded-2xl w-full">
                                        <span
                                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                            قرار الوصاية في حالة وجود قصر<span class="text-[#D92D20]">*</span></span>
                                        <label for="file-guardianship"
                                            class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                            <p>اضغط لإرفاق صورة الملف</p>
                                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                            <input type="file" name="attachments[guardianship_decision]"
                                                id="file-guardianship" class="hidden">
                                        </label>
                                    </div>
                                    <div class="relative border border-[#124375] rounded-2xl w-full">
                                        <span
                                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                            شهادات ميلاد القصر بالرقم القومي<span class="text-[#D92D20]">*</span></span>
                                        <label for="file-minors-certs"
                                            class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                            <p>اضغط لإرفاق صورة الملف</p>
                                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                            <input type="file" name="attachments[minors_birth_certs]"
                                                id="file-minors-certs" class="hidden">
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- <div class="declaration space-y-3" id="declaration-content">
                        <h3 class="text-center font-medium">إقرار</h3>
                        <p class="font-medium text-lg leading-loose">
                            أقر أنا / <span class="font-bold">{{ $member->full_name }}</span> بأنني قد قمت باستلام كافة مستحقاتي من صندوق الزمالة الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان،
                            وذلك اعتبارًا من تاريخ {{ date('Y-m-d') }}، وأقر بعدم أحقيتي في المطالبة بأي مستحقات أخرى بعد هذا التاريخ
                        </p>
                        <p class="font-medium text-lg mt-8 text-left">
                            <span>التوقيع / ________________</span>
                        </p>
                    </div> --}}

                        <div class="btns flex gap-4 mt-6">

                            <button type="submit"
                                class="submit-btn rounded-[14px] w-full py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2 hover:bg-opacity-90 transition-colors">
                                تقديم الطلب
                            </button>
                        </div>
                    </div>
                </form>
            @endif

        @endif

        <!-- claims table section -->
        <section class="px-7 py-5">
            @if ($memberClaims->isEmpty() && request('claim_type') === null)
                <!-- no requests -->
                <div class="no-requests flex justify-center py-14">
                    <div class="flex flex-col items-center gap-5">
                        <img src="{{ asset('IMGs/no-requests.png') }}" alt="no-requests">
                        <p>لم يتم إضافة مطالبة حتي الآن</p>
                    </div>
                </div>
            @elseif ($memberClaims->isNotEmpty() && request('claim_type') === null)
                <!-- requests table -->
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
                                <tr class="text-center border-b border-[#D1D5DB] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                                    <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $claim->id }}</td>
                                    <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                        {{ $claims[$claim->type] ?? $claim->type }}</td>
                                    <td class="py-4 border-l border-[#D1D5DB]">
                                        <span
                                            class="{{ $claimStatusClasses[$claim->status] ?? 'bg-gray-100' }} border px-4 py-1.5 text-sm rounded-lg">
                                            {{ $claimStatusLabels[$claim->status] ?? $claim->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                        {{ $claim->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="py-5">
                                        @if ($claim->status === 'approved')
                                            <a href="?tab=مطالبات&view_claim={{ $claim->id }}"
                                                class="bg-[#124375] text-white py-3 navy-shadow px-8 rounded-xl font-medium inline-block">عرض
                                                التفاصيل</a>
                                        @elseif($claim->status === 'pending')
                                            @if (auth()->user() && auth()->user()->hasPermission('إدارة المطالبات'))
                                                <a href="{{ route('claims.show', $claim->id) }}"
                                                    class="bg-[#124375] text-white py-3 navy-shadow px-8 rounded-xl font-medium inline-block">اعتماد</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
        <!-- end requests section -->

        <!-- view claim section -->
        @if (request('view_claim') !== null)
            @php
                $viewedClaim = $memberClaims->find(request('view_claim'));
            @endphp
            <section class="px-7 py-5 border-t border-gray-200 mt-5">
                <div class="flex items-center gap-2 mb-4">
                    <p class="text-2xl font-semibold text-[#124375]">تفاصيل المطالبة</p>
                </div>

                @if ($viewedClaim->status === 'approved')
                    @if (auth()->user() && auth()->user()->hasPermission('إدارة المطالبات'))
                        <form action="{{ route('claims.finalize', $viewedClaim->id) }}" method="POST"
                            enctype="multipart/form-data" class="w-full">
                            @csrf
                            <div>


                                <div class="mt-8 border border-[#124375] rounded-2xl w-full relative p-6 bg-white">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-white">رفع
                                        الإقرار بعد توقيعه <span class="text-[#D92D20]">*</span></span>
                                    <label for="file-receipt"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-2 border-2 border-dashed border-[#124375] rounded-xl hover:bg-[#F4F7F9] transition-colors">
                                        <p class="text-lg">اضغط لإرفاق صورة الإقرار الموقع</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-3xl"></iconify-icon>
                                        <input type="file" name="signed_receipt" id="file-receipt" class="hidden"
                                            required>
                                    </label>
                                </div>

                                <div class="btns flex gap-4 mt-6">
                                    <button type="button" onclick="window.open('{{ route('print.claim_declaration', $claim->id) }}', '_blank')"
                                        class="border-2 border-[#124375] text-[#124375] font-bold w-1/3 py-3 rounded-[14px] flex items-center justify-center gap-2 navy-shadow hover:bg-[#F4F7F9] transition-colors">
                                        <iconify-icon icon="material-symbols:print" class="text-2xl"></iconify-icon>
                                        طباعة الإقرار
                                    </button>
                                    <button type="submit"
                                        class="submit-btn rounded-[14px] w-2/3 py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2 hover:bg-opacity-90 transition-colors">
                                        تأكيد دفع الشيك (رفع الإقرار الموقع)
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="bg-[#FFF8E1] text-[#E6B800] border border-[#E6B800] p-4 rounded-xl text-center">
                        <p class="text-lg font-medium">يجب اعتماد هذه المطالبة أولاً حتى تتمكن من طباعة الإقرار وإرفاق
                            التوقيع.</p>
                    </div>
                @endif
            </section>
        @endif
    </div>


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
                    <p class="text-[#6D6D6D] text-[14px]">حالة القرض :
                        @if ($activeLoan->status === 'overdue')
                            <span class="inline-block px-2 text-center text-[#F79009] bg-[#FFF7ED] border border-[#F79009] rounded-[8px] py-[1px]">متأخر</span>
                        @elseif ($activeLoan->status === 'completed')
                            <span class="inline-block px-2 text-center text-[#124375] bg-[#EEF7FF] border border-[#124375] rounded-[8px] py-[1px]">مكتمل</span>
                        @elseif ($activeLoan->status === 'active')
                            <span class="inline-block px-2 text-center text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px]">نشط</span>
                        @elseif ($activeLoan->status === 'pending')
                            <span class="inline-block px-2 text-center text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] rounded-[8px] py-[1px]">تحت المراجعة</span>
                        @elseif ($activeLoan->status === 'rejected')
                            <span class="inline-block px-2 text-center text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[1px]">مرفوض</span>
                        @else
                            <span class="inline-block px-2 text-center text-[#6D6D6D] bg-[#EFEFEF] border border-[#6D6D6D] rounded-[8px] py-[1px]">{{ $activeLoan->status }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div id="loans-content-container" data-tab="قروض"
        class="{{ $activeTabName === 'قروض' ? '' : 'hidden' }}  tab-content px-7 py-5 print:hidden">
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
                                    {{ match ($installment->transaction?->method ?? '') {'cash' => 'نقدي','bank_transfer' => 'تحويل بنكي','salary_deduction' => 'خصم من المرتب','university_payment_order' => 'أمر دفع من الجامعة',default => '-'} }}
                                </td>
                                <td class="py-5">
                                    @php
                                        $receipt = \App\Models\Membership\Attachment::where('member_id', $member->id)
                                                    ->where('type', "installment_{$installment->id}_receipt")
                                                    ->first();
                                    @endphp
                                    @if ($installment->status === 'paid')
                                        <div class="text-2xl flex gap-7 items-center justify-center text-[#124375]">
                                            @if($receipt)
                                                <a href="{{ route('documents.view', $receipt->id) }}" target="_blank" class="hover:text-[#0e3560] transition-colors" title="عرض الإيصال">
                                                    <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                                </a>
                                                <a href="{{ route('documents.download', $receipt->id) }}" class="hover:text-[#0e3560] transition-colors" title="تحميل الإيصال">
                                                    <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                                </a>
                                            @else
                                                <span class="text-gray-400 cursor-not-allowed" title="لا يوجد إيصال مرفق">
                                                    <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                                </span>
                                                <span class="text-gray-400 cursor-not-allowed" title="لا يوجد إيصال مرفق">
                                                    <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        @if (auth()->user() && auth()->user()->hasPermission('إدارة القروض'))
                                            <div>
                                                <button data-modal="modal5"
                                                    onclick="document.getElementById('payInstallmentForm').action='{{ route('loans.installments.pay', $installment->id) }}'"
                                                    class="open-modal bg-[#124375] text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-2 px-4">
                                                    تسجيل السداد
                                                </button>
                                            </div>
                                        @endif
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
    <div data-tab="الاشتراكات" class="{{ $activeTabName === 'الاشتراكات' ? '' : 'hidden' }} tab-content px-7 py-2 print:hidden">
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
                                    {{ match ($subscription->transaction?->method ?? '') {'cash' => 'نقدي','bank_transfer' => 'تحويل بنكي','salary_deduction' => 'خصم من المرتب','university_payment_order' => 'أمر دفع من الجامعة',default => '-'} }}
                                </td>
                                <td class="py-5">
                                    @php
                                        $hasReceipt = \App\Models\Financial\Transaction::where('reference_type', \App\Models\Services\Subscription::class)
                                            ->where('reference_id', $subscription->id)
                                            ->whereNotNull('attachment_path')
                                            ->exists() || \App\Models\Membership\Attachment::where('member_id', $member->id)->where('type', "subscription_{$subscription->id}_receipt")->exists();
                                    @endphp
                                    @if ($subscription->status === 'paid')
                                        <div class="text-2xl flex gap-7 items-center justify-center text-[#124375]">
                                            @if($hasReceipt)
                                                <a href="{{ route('subscriptions.view_receipt', $subscription->id) }}" target="_blank" class="hover:text-blue-700 transition-colors" title="عرض الإيصال">
                                                    <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                                </a>
                                                <a href="{{ route('subscriptions.download_receipt', $subscription->id) }}" class="hover:text-blue-700 transition-colors" title="تحميل الإيصال">
                                                    <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                                </a>
                                            @else
                                                <span class="text-gray-400 cursor-not-allowed" title="لا يوجد إيصال مرفق">
                                                    <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                                </span>
                                                <span class="text-gray-400 cursor-not-allowed" title="لا يوجد إيصال مرفق">
                                                    <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                                </span>
                                            @endif
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

                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="button" id="proceed-to-declaration-btn"
                            class="rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                    icon="solar:document-add-linear"
                                    class="flex items-center text-2xl"></iconify-icon></span>متابعة وإرفاق الإقرار</button>
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
                    <div id="loan-summary" class="bg-[#FFFCEF] p-4 rounded-xl border border-[#D4AF37] mb-4">
                        <h3 class="text-[#D4AF37] font-semibold mb-2 text-center">ملخص القرض</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm text-[#021219]">
                            <div><span class="font-bold">قيمة القرض:</span> <span id="summary_base_amount">0</span> ج.م</div>
                            <div><span class="font-bold">فائدة القرض:</span> <span id="summary_interest_amount">0</span> ج.م</div>
                            <div><span class="font-bold">الإجمالي بالفائدة:</span> <span id="summary_total_amount">0</span> ج.م</div>
                            <div><span class="font-bold">القسط الشهري:</span> <span id="summary_installment_amount">0</span> ج.م</div>
                            <div><span class="font-bold">المدة:</span> <span id="summary_months">0</span> شهر</div>
                        </div>
                    </div>
                    <div class="space-y-3 mb-4">
                        <p class="text-[#021219] text-[16px] font-medium text-center">الخطوة الأولى: طباعة الإقرار وتوقيعه</p>
                        <div class="border border-[#124375] rounded-[12px] ">
                            <a target="_blank" href="#" id="print-declaration-btn"
                                class=" cursor-pointer py-7 text-[#124375] flex items-center justify-center gap-1 hover:bg-[#F4F7F9] transition-colors rounded-[12px]">
                                <p>اضغط هنا لطباعة الإقرار</p>
                                <iconify-icon icon="material-symbols:print" class="text-2xl"></iconify-icon>
                            </a>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-[#021219] text-[16px] font-medium text-center">الخطوة الثانية: رفع ملف الإقرار المُوَقَّع</p>
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
                                    class="text-[#021219] font-semibold text-base">{{ now()->format('Y-m-d') }}</span>
                            </p>
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
                        <div class="relative w-full mb-5 z-50">
                            <label
                                class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1 z-10">
                                طريقة الدفع <span class="text-[#D92D20]">*</span>
                            </label>
                            <input type="hidden" name="payment_method" class="payment-method-input" required>
                            <button type="button"
                                class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-4 rounded-xl text-base flex justify-between items-center transition-colors payment-method-btn">
                                <span class="text-[#021219]">اختر طريقة الدفع</span>
                                <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl"></iconify-icon></span>
                            </button>
                            <span
                                class="payment_error_msg hidden absolute bottom-[-20px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                                اختيار طريقة الدفع</span>
                            <div
                                class="dropDown w-full hidden absolute z-[60] bg-[#F4F7F9] right-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow">
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="cash">نقدي</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="bank_transfer">تحويل بنكي</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="salary_deduction">خصم من المرتب</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="university_payment_order">أمر دفع من الجامعة</a>
                            </div>
                        </div>
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
                        <div class="relative w-full mb-5 z-50">
                            <label
                                class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1 z-10">
                                طريقة الدفع <span class="text-[#D92D20]">*</span>
                            </label>
                            <input type="hidden" name="payment_method" class="payment-method-input" required>
                            <button type="button"
                                class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-4 rounded-xl text-base flex justify-between items-center transition-colors payment-method-btn">
                                <span class="text-[#021219]">اختر طريقة الدفع</span>
                                <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl"></iconify-icon></span>
                            </button>
                            <span
                                class="payment_error_msg hidden absolute bottom-[-20px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                                اختيار طريقة الدفع</span>
                            <div
                                class="dropDown w-full hidden absolute z-[60] bg-[#F4F7F9] right-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow">
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="cash">نقدي</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="bank_transfer">تحويل بنكي</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="salary_deduction">خصم من المرتب</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="university_payment_order">أمر دفع من الجامعة</a>
                            </div>
                        </div>
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
                        <div class="relative w-full mb-5 z-50">
                            <label
                                class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1 z-10">
                                طريقة الدفع <span class="text-[#D92D20]">*</span>
                            </label>
                            <input type="hidden" name="payment_method" class="payment-method-input" required>
                            <button type="button"
                                class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-4 rounded-xl text-base flex justify-between items-center transition-colors payment-method-btn">
                                <span class="text-[#021219]">اختر طريقة الدفع</span>
                                <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl"></iconify-icon></span>
                            </button>
                            <span
                                class="payment_error_msg hidden absolute bottom-[-20px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                                اختيار طريقة الدفع</span>
                            <div
                                class="dropDown w-full hidden absolute z-[60] bg-[#F4F7F9] right-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow">
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="cash">نقدي</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="bank_transfer">تحويل بنكي</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="salary_deduction">خصم من المرتب</a>
                                <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                    data-value="university_payment_order">أمر دفع من الجامعة</a>
                            </div>
                        </div>
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

            const proceedBtn = document.getElementById('proceed-to-declaration-btn');
            if (proceedBtn) {
                proceedBtn.addEventListener('click', function(e) {
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

                    // Calculate and populate the summary
                    const amountVal = parseFloat(totalAmount.value);
                    const monthsVal = parseInt(months.value);

                    const interestRate = {{ \App\Models\System\SystemSetting::get('loan_interest_rate', 8) }};
                    const years = monthsVal / 12;
                    const interestAmount = (interestRate / 100) * amountVal * years;
                    const totalWithInterest = amountVal + interestAmount;
                    const installmentAmount = totalWithInterest / monthsVal;

                    document.getElementById('summary_base_amount').textContent = amountVal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    document.getElementById('summary_interest_amount').textContent = interestAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    document.getElementById('summary_total_amount').textContent = totalWithInterest.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    document.getElementById('summary_installment_amount').textContent = installmentAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    document.getElementById('summary_months').textContent = monthsVal;

                    // Set the href for the print button
                    const printBtn = document.getElementById('print-declaration-btn');
                    if (printBtn) {
                        const baseUrl = "{{ route('print.new_loan_declaration', $member->id) }}";
                        printBtn.href = `${baseUrl}?amount=${amountVal}&months=${monthsVal}&interest=${interestAmount}&total=${totalWithInterest}&installment=${installmentAmount}`;
                    }

                    // Open the modal and overlay manually
                    const modal2 = document.getElementById('modal2');
                    const overlay = document.querySelector('.overlay');
                    if (modal2) modal2.classList.remove('hidden');
                    if (overlay) overlay.classList.remove('hidden');
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
    @if (session('receipt_data'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let receiptData = {!! session('receipt_data') !!};
                Swal.fire({
                    html: `
                    <div class="receipt-container p-6 text-center" style="background-color: #F4F7F9; border-radius: 12px; font-family: 'Tajawal', sans-serif; direction: rtl;">
                        <h2 class="text-2xl font-bold text-[#124375] mb-4">تم تسجيل العضوية بنجاح</h2>
                        <div class="mb-6 flex flex-col items-center justify-center gap-2">
                            <iconify-icon icon="line-md:confirm-circle" class="text-6xl text-[#067647]"></iconify-icon>
                            <p class="text-lg text-[#021219]">رقم العضوية: <span class="font-bold">${receiptData.membership_number}</span></p>
                        </div>
                        <button onclick="window.open('{{ route('print.new_membership_receipt', $member->id) }}', '_blank')" class="w-full bg-[#124375] text-white py-3 rounded-xl font-bold text-lg flex justify-center items-center gap-2 hover:bg-[#0e3560] transition-colors">
                            <iconify-icon icon="material-symbols:print-rounded" class="text-2xl"></iconify-icon> طباعة إيصال الاشتراك
                        </button>
                    </div>
                `,
                    showConfirmButton: false,
                    width: '800px',
                    background: '#F4F7F9',
                    customClass: {
                        popup: 'rounded-2xl border border-[#124375]'
                    }
                });
            });
        </script>
    @endif

    <script>
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                const container = this.closest('.relative');
                const hiddenInput = container.querySelector('.payment-method-input');
                if (hiddenInput) hiddenInput.value = this.dataset.value;

                const btn = container.querySelector('.payment-method-btn');
                const errorMsg = container.querySelector('.payment_error_msg');
                if (btn) {
                    btn.classList.remove('border', 'border-[#D92D20]', 'text-[#D92D20]');
                    btn.classList.add('border-[#124375]', 'text-[#124375]');
                }
                if (errorMsg) errorMsg.classList.add('hidden');
            });
        });

        const paymentForms = document.querySelectorAll(
            'form[action*="installments"], form[action*="subscriptions"], form[action*="early-repayment"]');
        paymentForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const hiddenInput = this.querySelector('.payment-method-input');
                if (hiddenInput && !hiddenInput.value) {
                    e.preventDefault();
                    const container = hiddenInput.closest('.relative');
                    if (container) {
                        const btn = container.querySelector('.payment-method-btn');
                        if (btn) {
                            btn.classList.remove('border-[#124375]', 'text-[#124375]');
                            btn.classList.add('border', 'border-[#D92D20]', 'text-[#D92D20]');
                        }
                        const errorMsg = container.querySelector('.payment_error_msg');
                        if (errorMsg) {
                            errorMsg.classList.remove('hidden');
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open_declaration_modal') === '1') {
                const safeParse = (val) => {
                    if (!val) return 0;
                    const parsed = parseFloat(String(val).replace(/,/g, ''));
                    return isNaN(parsed) ? 0 : parsed;
                };

                const totalAmount = urlParams.get('create_loan_amount');
                const months = urlParams.get('create_loan_months');
                const safeMonths = parseInt(months) || 0;
                
                let baseAmount = safeParse(urlParams.get('loan_base_amount'));
                let interestAmount = safeParse(urlParams.get('loan_interest_amount'));
                let totalWithInterest = safeParse(urlParams.get('loan_total_amount'));
                let installmentAmount = safeParse(urlParams.get('loan_installment_amount'));

                // Fallback to calculation if URL params are missing
                if (!baseAmount && totalAmount) {
                    baseAmount = safeParse(totalAmount);
                    const interestRate = {{ \App\Models\System\SystemSetting::get('loan_interest_rate', 8) }};
                    const years = safeMonths / 12;
                    interestAmount = (interestRate / 100) * baseAmount * years;
                    totalWithInterest = baseAmount + interestAmount;
                    installmentAmount = safeMonths > 0 ? totalWithInterest / safeMonths : 0;
                }

                document.getElementById('selected_total_amount').value = totalAmount || baseAmount;
                document.getElementById('selected_months').value = months || safeMonths;

                // Populate summary
                document.getElementById('summary_base_amount').textContent = baseAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('summary_interest_amount').textContent = interestAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('summary_total_amount').textContent = totalWithInterest.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('summary_installment_amount').textContent = installmentAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('summary_months').textContent = safeMonths;
                
                // Set the href for the print button
                const printBtn = document.getElementById('print-declaration-btn');
                if (printBtn) {
                    const baseUrl = "{{ route('print.new_loan_declaration', $member->id) }}";
                    printBtn.href = `${baseUrl}?amount=${baseAmount}&months=${safeMonths}&interest=${interestAmount}&total=${totalWithInterest}&installment=${installmentAmount}`;
                }

                // Open Modal 2
                const modal2 = document.getElementById('modal2');
                const overlay = document.querySelector('.overlay');
                if (modal2) {
                    modal2.classList.remove('hidden');
                }
                if (overlay) {
                    overlay.classList.remove('hidden');
                }
            }
        });
    </script>
@endsection
