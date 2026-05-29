@extends('layouts.app')
{{--
    Show Claim Details View:
    Displays the comprehensive breakdown of a specific claim, calculating insurance benefits,
    subtracting any outstanding loans/subscriptions, and allowing employees to approve the final disbursement.
--}}
@section('title', 'عرض بيانات المطالبة')
@section('content')
    @php
        use App\Models\System\SystemSetting;
    @endphp
    <link rel="stylesheet" href="{{ asset('css/employee/claims-approve.css') }}">
    <!-- header -->
    <div class="flex justify-between py-5 px-12 print:hidden">
        <h1 class="text-[#124375] text-3xl font-medium">
            مطالبة صرف ميزة تأمينية
        </h1>
        <div class="btns flex items-center gap-2">
            <button onclick="window.open('{{ route('print.claim_details', $claim->id) }}', '_blank')"
                class="bg-[#F4F7F9] flex items-center justify-center gap-1 navy-shadow w-48  py-3 rounded-xl text-[#124375] no-print print:hidden">
                <iconify-icon icon="material-symbols:print" class="flex items-center text-2xl"></iconify-icon>
                طباعة
            </button>
            <button
                class="flex approval-btn items-center justify-center gap-1 navy-shadow text-[#F4F7F9] bg-[#124375] navy-shadow w-48  py-3 rounded-xl ">
                <iconify-icon icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon>
                أعتماد الصرف
            </button>
        </div>
    </div>
    <!-- end header -->


    <!-- first section -->
    <section class="px-12 py-4">
        <div class="flex items-stretch bg-[#F4F7F9] navy-shadow rounded-[12px] overflow-hidden border border-[#1243751a]">
            <!-- Member Primary Info -->
            <div class="flex-[2] flex flex-col justify-center gap-3 py-6 px-8 border-l border-[#1243751a]">
                <div class="space-y-1">
                    <h2 class="text-[#124375] font-bold text-[26px] leading-tight">
                        {{ $claim->membership->member->user->name }}</h2>
                    <p class="text-sm text-[#6D6D6D] font-medium">
                        {{ $claim->membership->member->employmentInfo->job_title ?? '' }}
                        <span class="mx-1 text-[#1243754d]">|</span>
                        {{ $claim->membership->member->department->name ?? '' }}
                    </p>
                </div>
                <div class="pt-1">
                    <span
                        class="text-sm text-[#124375] font-bold bg-[#F0FFF6] py-1.5 px-4 rounded-full border border-[#1243751a]">
                        سبب المطالبة : {{ $claimTypes[$claim->type] ?? $claim->type }}
                    </span>
                </div>
            </div>

            <!-- Stats Columns -->
            <div
                class="flex-1 flex flex-col justify-center items-center gap-2 py-6 px-4 border-l border-[#1243751a] bg-[#F8FAFC]">
                <h3 class="text-[11px] text-[#6D6D6D] font-bold uppercase tracking-widest">رقم العضوية</h3>
                <p class="font-bold text-[#000000] text-xl tracking-tight">{{ $claim->membership->membership_number }}</p>
            </div>

            <div class="flex-1 flex flex-col justify-center items-center gap-2 py-6 px-4 border-l border-[#1243751a]">
                <h3 class="text-[11px] text-[#6D6D6D] font-bold uppercase tracking-widest">مدة الخدمة</h3>
                <p class="font-bold text-[#000000] text-xl">{{ $serviceYears }} عام و {{ $serviceMonths }} شهر</p>
            </div>

            <div
                class="flex-1 flex flex-col justify-center items-center gap-2 py-6 px-4 border-l border-[#1243751a] bg-[#F8FAFC]">
                <h3 class="text-[11px] text-[#6D6D6D] font-bold uppercase tracking-widest">تاريخ الانضمام</h3>
                <p class="font-bold text-[#000000] text-[15px]">
                    {{ $claim->membership->member->employmentInfo->join_date ? \Carbon\Carbon::parse($claim->membership->member->employmentInfo->join_date)->translatedFormat('d F Y') : '-' }}
                </p>
            </div>

            <div class="flex-1 flex flex-col justify-center items-center gap-2 py-6 px-4">
                <h3 class="text-[11px] text-[#6D6D6D] font-bold uppercase tracking-widest">تاريخ الانتهاء</h3>
                <p class="font-bold text-[#000000] text-[15px]">
                    {{ $claim->membership->member->employmentInfo->retirement_date ? \Carbon\Carbon::parse($claim->membership->member->employmentInfo->retirement_date)->translatedFormat('d F Y') : '-' }}
                </p>
            </div>
        </div>
    </section>
    <!-- end first section -->


    <!-- second section -->
    <section class="px-12 py-4">
        <div class="flex gap-5 items-stretch">
            <!-- green card -->
            <div class="bg-[#F0FFF6] green-shadow py-3 px-7 rounded-2xl w-full ">
                <div class="flex items-center gap-4">
                    <div class="icon bg-[#F0FFF6] py-2 px-2 rounded-[8px] green-shadow">
                        <iconify-icon icon="material-symbols:calendar-check"
                            class="text-[#019168] text-3xl flex items-center"></iconify-icon>
                    </div>
                    <div class="flex flex-col gap-3">
                        <h3 class="text-sm text-[#6D6D6D] font-medium">
                            عدد شهور الاشتراك
                        </h3>
                        <p class="text-base text-[#124375] font-semibold">
                            {{ $paidMonths . ' شهر' }}
                        </p>
                    </div>
                </div>
                <hr class="border border-[#6D6D6D] my-4">
                <div class="flex justify-between">
                    <h3 class="text-sm text-[#6D6D6D] font-medium">
                        رسم العضوية المسدد
                    </h3>
                    <p class="text-base text-[#021219] font-semibold">
                        {{ number_format($joinFee, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#6D6D6D] my-4">
                <div class="flex justify-between">
                    <h3 class="text-sm text-[#6D6D6D] font-medium">
                        إجمالي الاشتراكات المسددة
                    </h3>
                    <p class="text-base text-[#021219] font-semibold">
                        {{ number_format($paidSubscriptionsAmount, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#6D6D6D] my-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm text-[#6D6D6D] font-medium">
                        إجمالي المبلغ المدفوع
                    </h3>
                    <p class="text-[28px] text-[#019168] font-semibold">
                        {{ number_format($joinFee + $paidSubscriptionsAmount, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#6D6D6D] my-4">
            </div>
            <!-- end green card -->

            <!-- start red card -->
            <div class="bg-[#FFEAE8] red-shadow py-3 px-7 rounded-2xl w-full flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-4">
                        <div class="icon bg-[#FFEAE8] py-2 px-2 rounded-[8px] red-shadow">
                            <iconify-icon icon="lucide:calendar-x-2"
                                class="text-[#D92D20] text-3xl flex items-center"></iconify-icon>
                        </div>
                        <div class="flex flex-col gap-3">
                            <h3 class="text-sm text-[#6D6D6D] font-medium">
                                عدد شهور الاشتراك الغير مدفوعة
                            </h3>
                            <p class="text-base text-[#124375] font-semibold">
                                {{ $unpaidMonths . ' شهر' }}
                            </p>
                        </div>
                    </div>
                    <hr class="border border-[#6D6D6D] my-4">
                    <div class="flex justify-between">
                        <h3 class="text-sm text-[#6D6D6D] font-medium">
                            رصيد القروض المتبقي
                        </h3>
                        <p class="text-base text-[#021219] font-semibold">
                            {{ number_format($claim->membership->remaining_loan_balance, 2) . ' ج.م' }}
                        </p>
                    </div>
                    <hr class="border border-[#6D6D6D] my-4">
                    <div class="flex justify-between">
                        <h3 class="text-sm text-[#6D6D6D] font-medium">
                            إجمالي الاشتراكات الغير مدفوعة
                        </h3>
                        <p class="text-base text-[#021219] font-semibold">
                            {{ number_format($overdueSubscriptionsAmount, 2) . ' ج.م' }}
                        </p>
                    </div>
                    <hr class="border border-[#6D6D6D] my-4">
                </div>
                <div class="flex justify-between items-center">
                    <h3 class="text-sm text-[#6D6D6D] font-medium">
                        إجمالي المديونية المتبقية
                    </h3>
                    <p class="text-[28px] text-[#D92D20] font-semibold">
                        {{ number_format($claim->membership->remaining_loan_balance + $overdueSubscriptionsAmount, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#6D6D6D] mb-4">
            </div>
            <!-- end red card -->
        </div>
    </section>
    <!-- end second section -->

    <!-- third section -->
    <section class="px-12 py-4">
        <div class="flex bg-[#124375] gap-7 rounded-2xl navy-shadow px-7 py-4">
            <div class="flex flex-col justify-between w-full py-5">
                <div class="flex justify-between text-base text-[#F4F7F9]">
                    <h3>
                        قيمة الميزة التأمينية ( الأساسية )
                    </h3>
                    <p class=" font-semibold">
                        {{ number_format($insurance_benefit, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#A8A8A8] my-3">
                <div class="flex justify-between text-base text-[#F4F7F9]">
                    <h3>
                        رصيد القروض المتبقي
                    </h3>
                    <p class=" font-semibold text-[#D92D20]">
                        {{ number_format($claim->membership->remaining_loan_balance, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#A8A8A8] my-3">
                <div class="flex justify-between text-base text-[#F4F7F9]">
                    <h3>
                        إجمالي الاشتراكات الغير مدفوعة
                    </h3>
                    <p class=" font-semibold text-[#D92D20]">
                        {{ number_format($overdueSubscriptionsAmount, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#A8A8A8] my-3">
                <div class="flex justify-between text-base text-[#F4F7F9]">
                    <h3 class="font-bold">
                        إجمالي المديونية
                    </h3>
                    <p class=" font-bold text-[#D92D20]">
                        {{ number_format($claim->membership->remaining_loan_balance + $overdueSubscriptionsAmount, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#A8A8A8] mt-3">
            </div>
            <div
                class="w-72 gap-3 flex flex-col items-center border border-[#F4F7F933] rounded-[8px] bg-[#F4F7F91A] py-7 px-2 my-auto">
                <h3 class="text-base font-normal text-[#F4F7F9]">
                    صافي المبلغ المستحق صرفه
                </h3>
                <p class="text-[32px] font-medium text-[#85F8C4]">
                    {{ number_format($net_amount, 2) . ' ج.م' }}
                </p>
            </div>
        </div>
    </section>
    <!-- end third section -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>
    <form method="POST" action="{{ route('claims.approve', $claim->id) }}" enctype="multipart/form-data">
        @csrf
        <div
            class="modal hidden max-w-5xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="close-btn text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-5">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        {{ \App\Models\Services\Claim::CLAIM_TYPES[$claim->type] ?? $claim->type }}
                    </h1>
                </div>
                <div class="space-y-3">
                    <div class="flex gap-4 ">
                        <p class="text-[#124375] text-base font-medium">الأسم : <span
                                class="text-[#021219] text-base font-semibold">{{ $claim->membership->member->user->name }}</span>
                        </p>
                        <p class="text-[#124375] text-base font-medium">رقم العضوية : <span
                                class="text-[#021219] text-base font-semibold">{{ $claim->membership->membership_number }}</span>
                        </p>
                        <p class="text-[#124375] text-base font-medium">قيمة المستحقات :<span
                                class="text-[#021219] text-base font-semibold">{{ number_format($net_amount, 2) . ' ج.م' }}</span>
                        </p>
                    </div>
                    <p class="text-[#124375] text-base font-medium">تاريخ صرف المستحقات :<span
                            class="text-[#021219] text-base font-semibold">{{ now()->translatedFormat('d F Y') }}</span>
                    </p>
                    <p> يرجى إدخال رقم الشيك وإرفاق المستندات المطلوبة لإتمام الطلب.</p>
                </div>
                <div class="documents">
                    <div class="flex flex-col gap-4">
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">رقم
                                الشيك <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="receipt_number" required
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="ABC1010101010101">
                        </div>
                        <div class="border border-[#124375] rounded-[12px] ">
                            <label for="file-1"
                                class=" cursor-pointer  py-9  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة من الموافقة والتوقيع الأول والثاني</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="receipt_file" id="file-1" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class="bg-[#124375] text-[#F4F7F9] rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2"><span><iconify-icon
                                    icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                            صرف
                            المستحقات</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="{{ asset('js/employee/payment.js') }}"></script>

@endsection
