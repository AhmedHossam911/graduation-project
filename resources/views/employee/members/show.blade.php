@extends('layouts.pages')
{{--
    Show Member Profile View (Employee):
    A comprehensive 360-degree view of a member's record.
    Includes tabs for managing their Subscriptions, Loans, and Claims dynamically.
--}}

@section('title', 'عرض بيانات العضو')

@section('content')
    @include('partials.common.flash')
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
            'pending_registration' => 'bg-[#EFF8FF] text-[#175CD3] border-[#175CD3]',
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

    @include('employee.members.partials.header')
    @include('employee.members.partials.personal-info')
    <!-- tabs -->
    <section class="px-4 md:px-7 print:hidden">
        <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between border border-[#124375] p-3 rounded-xl gap-4 md:gap-0">
            <div class="tabs flex flex-wrap gap-2 w-full md:w-auto">
                <button
                    class="{{ $activeTabName === 'الاشتراكات' ? 'active-tab' : 'tab' }} text-[#124375] text-sm md:text-base font-medium rounded-tl-2xl rounded-tr-2xl py-2 md:py-3 px-3 md:px-4 navy-shadow flex-1 md:flex-none">الاشتراكات</button>
                <button
                    class="{{ $activeTabName === 'قروض' ? 'active-tab' : 'tab' }} text-[#124375] text-sm md:text-base font-medium rounded-tl-2xl rounded-tr-2xl py-2 md:py-3 px-3 md:px-4 navy-shadow flex-1 md:flex-none">قروض</button>
                <button
                    class="{{ $activeTabName === 'مطالبات' ? 'active-tab' : 'tab' }} text-[#124375] text-sm md:text-base font-medium rounded-tl-2xl rounded-tr-2xl py-2 md:py-3 px-3 md:px-4 navy-shadow flex-1 md:flex-none">مطالبات</button>
            </div>
            <div class="w-full md:w-auto">
                <!-- requests only -->
                <div class="tab-content relative w-full md:w-auto" data-tab="مطالبات">
                    @if (auth()->user() && auth()->user()->hasPermission('إدارة المطالبات'))
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                            @php
                                $pendingClaim = $memberClaims->where('status', 'pending')->first();
                            @endphp
                            @if ($pendingClaim)
                                <button data-modal="modal-reject-claim"
                                    class="open-modal flex text-[16px] font-medium items-center w-full sm:w-52 justify-center gap-2 border-2 border-[#D92D20] red-shadow text-[#D92D20] py-2 rounded-[12px] bg-white">
                                    <iconify-icon icon="zondicons:close-solid"
                                    class="text-xl flex items-center"></iconify-icon>
                                    رفض المطالبة
                                </button>
                            @endif

                            <div class="relative w-full md:w-auto">
                                <button
                                    class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base navy-shadow flex justify-between md:justify-start gap-3 w-full md:w-auto">نوع
                                    المطالبة : @if (isset($selectedClaimType))
                                        <span class="text-[#021219]">{{ $claims[$selectedClaimType] ?? 'أختر' }}</span>
                                    @else
                                        <span class="text-[#021219]">أختر</span>
                                    @endif
                                    <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                            class="text-xl"></iconify-icon></span></button>
                                <div
                                    class="dropDown hidden absolute z-[80] bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow max-w-fit max-h-60 overflow-y-auto w-full">
                                    <a href="{{ url('/members/' . $member->id . '?claim_type=retirement&tab=مطالبات') }}"
                                        class="button cursor-pointer text-center navy-shadow py-2 px-1 rounded-xl text-base ">بلوغ
                                        سن
                                        التقاعد القانوني</a>
                                    <a href="{{ url('/members/' . $member->id . '?claim_type=transfer&tab=مطالبات') }}"
                                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">نقل</a>
                                    <a href="{{ url('/members/' . $member->id . '?claim_type=death&tab=مطالبات') }}"
                                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">
                                        وفاة</a>
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
                            </div>
                        </div>
                    @endif
                </div>
                <!-- requests only -->
                <!-- loans only -->
                @if (auth()->user() && auth()->user()->hasPermission('إدارة القروض'))
                    <div class="flex gap-3">
                        @if ($activeLoan === null)
                            <button id="request-loan-btn"
                                class="tab-content {{ $activeTabName === 'قروض' ? '' : 'hidden' }} flex gap-3 py-3 px-4 sm:px-20 w-full sm:w-auto rounded-[12px] justify-center items-center text-[#F4F7F9] text-[16px] font-medium bg-[#124375]"
                                data-tab="قروض">
                                <iconify-icon icon="ic:baseline-plus" class="flex items-center text-2xl"></iconify-icon>
                                طلب قرض
                            </button>
                        @endif
                        <div id="loans-action-buttons"
                            class="tab-content flex flex-col sm:flex-row flex-wrap gap-3 {{ $activeTabName === 'قروض' ? '' : 'hidden' }}"
                            data-tab="قروض">
                            @if ($activeLoan && $activeLoan->status === 'active')
                                <button data-modal="modal6"
                                    class="open-modal text-[16px] font-medium w-full sm:w-52 bg-[#124375] navy-shadow text-[#F4F7F9] py-2 rounded-[12px]">
                                    تسديد القرض بالكامل
                                </button>
                            @endif
                            @if ($activeLoan && $activeLoan->status === 'pending')
                                <button type="button"
                                    onclick="window.open('{{ route('print.board_details', $activeLoan->id) }}', '_blank')"
                                    class="flex w-full sm:w-52 text-[16px] font-medium items-center justify-center gap-2 bg-[#F4F7F9] navy-shadow py-2 rounded-[12px] text-[#124375]">
                                    <iconify-icon icon="material-symbols:print"
                                        class="text-xl flex items-center"></iconify-icon>
                                    طباعة التفاصيل للمجلس
                                </button>
                                <button data-modal="modal3"
                                    class="open-modal text-[16px] font-medium bg-[#124375] w-full sm:w-52 py-2 rounded-[12px] text-[#F4F7F9] navy-shadow">
                                    بدء القرض
                                </button>
                            @endif
                            @if ($memberLoans->count() > 0)
                                <a href="{{ route('members.previous-loans', $member->id) }}"
                                    class="text-center text-[16px] font-medium bg-[#F4F7F9] w-full sm:w-52 navy-shadow py-2 rounded-[12px] text-[#124375]">
                                    عرض القروض السابقة
                                </a>
                            @endif
                            @if ($activeLoan && ($activeLoan->status === 'pending' || $activeLoan->status === 'rejected'))
                                <button data-modal="modal4"
                                    class="open-modal flex text-[16px] font-medium items-center w-full sm:w-52 justify-center gap-2 border-2 border-[#D92D20] red-shadow text-[#D92D20] py-2 rounded-[12px]">
                                    <iconify-icon icon="zondicons:close-solid"
                                        class="text-xl flex items-center"></iconify-icon>
                                    إلغاء أو رفض الطلب
                                </button>
                            @endif
                        </div>
                    </div>

                @endif
                <!-- end loans only -->
                <div class="tab-content flex flex-col sm:flex-row flex-wrap gap-2 {{ $activeTabName === 'الاشتراكات' ? '' : 'hidden' }}"
                    data-tab="الاشتراكات">
                    @if (auth()->user() && auth()->user()->hasPermission('إدارة الاشتراكات'))
                        @if ($hasOverdue6Months)
                            <button type="button" data-modal="modal8"
                                class="open-modal flex gap-3 py-3 px-4 sm:px-12 w-full sm:w-auto rounded-[12px] justify-center items-center border border-[#F79009] text-[#F79009] text-[16px] font-medium bg-[#FFF7ED]">
                                <iconify-icon icon="fluent:mail-warning-24-filled"
                                    class="flex items-center text-2xl"></iconify-icon>
                                إرسال إخطار مسجل بعلم الوصول
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('members.documents', $member->id) }}"
                        class=" flex gap-3 py-3 px-4 sm:px-20 w-full sm:w-auto rounded-[12px] justify-center items-center text-[#124375] text-[16px] font-medium bg-[#F4F7F9] navy-shadow">
                        <iconify-icon icon="mdi:file-account" class="flex items-center text-2xl"></iconify-icon>
                        عرض المستندات
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- end tabs -->

    @include('employee.members.partials.tabs.claims')
    @include('employee.members.partials.tabs.loans')
    @include('employee.members.partials.tabs.subscriptions')


    @include('employee.members.partials.modals')
    @include('employee.members.partials.scripts')
@endsection
