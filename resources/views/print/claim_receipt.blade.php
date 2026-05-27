@extends('layouts.print')

@php
    $title = 'إيصال صرف مستحقات تأمينية';
    $reference = 'TRX-' . $claim->id;
@endphp

@section('title', $title)

@section('content')
<div class="modal-body space-y-7 px-7 py-2">
    <div
        class="text-[#F4F7F9] flex items-center justify-between bg-[#124375] navy-shadow py-3 px-4 rounded-[16px]">
        <h1 class=" text-[20px] font-semibold">
            إيصال صرف مستحقات تأمينية
        </h1>
        <div class="flex flex-col items-center gap-2">
            <p class="text-[16px] ">رقم المعاملة :</p>
            <p class=" text-[20px] font-semibold">TRX-{{ $claim->id }}</p>
        </div>
    </div>
    <div class="bg-[#F4F7F9] navy-shadow py-3 px-4 rounded-[16px]">
        <div class="flex justify-between items-center">
            <div class="flex flex-col gap-2">
                <h2 class="text-[14px] text-[#6D6D6D] font-medium">اسم العضو</h2>
                <p class="text-[#124375] text-[25px] font-semibold">{{ $claim->membership->member->full_name }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <h2 class="text-[14px] text-[#6D6D6D] font-medium">رقم العضوية </h2>
                <p class="text-[#021219] text-[20px] font-semibold">{{ $claim->membership->membership_number }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <h2 class="text-[14px] text-[#6D6D6D] font-medium">تاريخ نهاية الخدمة</h2>
                <p class="text-[#021219] text-[20px] font-semibold">{{ $claim->membership->member->employmentInfo->retirement_date ? \Carbon\Carbon::parse($claim->membership->member->employmentInfo->retirement_date)->translatedFormat('d F Y') : '-' }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <h2 class="text-[14px] text-[#6D6D6D] font-medium">حالة الصرف</h2>
                <p
                    class="text-[14px] text-[#067647] border border-[#067647] py-1 px-12 bg-[#ECFDF3] rounded-[8px] font-medium">
                    تم الصرف</p>
            </div>
        </div>
        <hr class="border border-[#A8A8A8] mx-1 my-4">
        <div class="flex justify-between items-center">
            <div class="flex flex-col gap-2">
                <h2 class="text-[14px] text-[#6D6D6D] font-medium">سبب الاستحقاق</h2>
                <p class="text-[#021219] text-[20px] font-semibold">{{ \App\Models\Services\Claim::CLAIM_TYPES[$claim->type] ?? $claim->type }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <h2 class="text-[14px] text-[#6D6D6D] font-medium">رقم الشيك </h2>
                <p class="text-[#021219] text-[20px] font-semibold">{{ $claim->receipt_number ?? 'غير متوفر' }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <h2 class="text-[14px] text-[#6D6D6D] font-medium">تاريخ ووقت التنفيذ</h2>
                <p class="text-[#021219] text-[20px] font-semibold">{{ $claim->updated_at->translatedFormat('d F Y - h:i A') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-[#F4F7F9] navy-shadow py-3 px-4 rounded-[16px]">
        <div class="flex justify-between">
            <p class="text-[#6D6D6D] text-[16px] font-normal">قيمة الميزة التأمينية ( الأساسية )</p>
            <p class="text-[#124375] text-[16px] font-semibold">{{ number_format($claim->amount, 2) }} ج.م</p>
        </div>
        <hr class="border border-[#A8A8A8] mx-1 my-4">
        <div class="flex justify-between">
            <p class="text-[#6D6D6D] text-[16px] font-normal">رصيد القروض المتبقي</p>
            <p class="text-[#D92D20] text-[16px] font-semibold"> -{{ number_format($claim->membership->remaining_loan_balance, 2) }} ج.م</p>
        </div>
        <hr class="border border-[#A8A8A8] mx-1 my-4">
        <div
            class="flex items-center justify-between border border-[#1243751A] bg-[#1243751A] rounded-[8px] py-4 px-4 ">
            <p class="text-[16px] text-[#124375]">صافي المبلغ المستحق صرفه</p>
            <p class="text-[32px] text-[#001E3D] font-medium">{{ number_format($claim->amount - $claim->membership->remaining_loan_balance, 2) }} ج.م</p>
        </div>
    </div>
</div>
@endsection
