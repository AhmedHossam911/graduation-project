@extends('layouts.print')

@section('title', 'مطالبة صرف ميزة تأمينية')

@section('content')

<div class="px-12 py-4 space-y-4" dir="rtl">
    <!-- first section -->
    <section class="mb-6">
        <div class="flex items-stretch bg-[#F4F7F9] navy-shadow rounded-[12px] overflow-hidden border border-[#1243751a]">
            <!-- Member Primary Info -->
            <div class="flex-[2] flex flex-col justify-center gap-3 py-6 px-8 border-l border-[#1243751a]">
                <div class="space-y-1">
                    <h2 class="text-[#124375] font-bold text-[26px] leading-tight">
                        {{ $claim->membership->member->full_name }}</h2>
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
    <section class="mb-6">
        <div class="flex gap-5 items-stretch">
            <!-- green card -->
            <div class="bg-[#F0FFF6] green-shadow py-3 px-7 rounded-2xl w-full border border-[#01916833]">
                <div class="flex items-center gap-4">
                    <div class="icon bg-[#F0FFF6] py-2 px-2 rounded-[8px] green-shadow border border-[#01916833]">
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
            </div>
            <!-- end green card -->

            <!-- start red card -->
            <div class="bg-[#FFEAE8] red-shadow py-3 px-7 rounded-2xl w-full flex flex-col justify-between border border-[#D92D2033]">
                <div>
                    <div class="flex items-center gap-4">
                        <div class="icon bg-[#FFEAE8] py-2 px-2 rounded-[8px] red-shadow border border-[#D92D2033]">
                            <iconify-icon icon="lucide:calendar-x-2"
                                class="text-[#D92D20] text-3xl flex items-center"></iconify-icon>
                        </div>
                        <div class="flex flex-col gap-3">
                            <h3 class="text-sm text-[#6D6D6D] font-medium">
                                عدد القروض القائمة
                            </h3>
                            <p class="text-base text-[#124375] font-semibold">
                                {{ $claim->membership->active_loans_count . ' قرض' }}
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
                </div>
                <div class="flex justify-between items-center">
                    <h3 class="text-sm text-[#6D6D6D] font-medium">
                        إجمالي المديونية المتبقية
                    </h3>
                    <p class="text-[28px] text-[#D92D20] font-semibold">
                        {{ number_format($claim->membership->remaining_loan_balance + $overdueSubscriptionsAmount, 2) . ' ج.م' }}
                    </p>
                </div>
            </div>
            <!-- end red card -->
        </div>
    </section>
    <!-- end second section -->

    <!-- third section -->
    <section class="mb-6">
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
                    <p class=" font-semibold text-[#FFB3B3]">
                        {{ number_format($claim->membership->remaining_loan_balance, 2) . ' ج.م' }}
                    </p>
                </div>
                <hr class="border border-[#A8A8A8] mt-3">
            </div>
            <div
                class="w-72 gap-3 flex flex-col items-center border border-[#F4F7F933] rounded-[8px] bg-[#F4F7F91A] py-7 px-2">
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
</div>
@endsection
