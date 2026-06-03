@extends('layouts.print')
{{-- 
    Print Loan Declaration View:
    Printable legal document where a member officially requests a loan and
    commits to the specific repayment terms and payroll deductions.
--}}

@php
    $title = 'إقرار طلب قرض';
    $reference = $member->membership_number ?? '';
@endphp

@section('title', $title)

@section('content')
<div class="declaration space-y-3 px-8">
    <h3 class="text-center font-bold text-2xl text-[#124375] mb-8">
        إقرار
    </h3>
    <p class="font-medium text-xl leading-loose border-2 border-[#124375] p-6 rounded-xl bg-[#F4F7F9] text-[#021219]">
        أقر أنا / <span class="font-bold ">{{ $member->user->name }}</span>
        برغبتي في الحصول على قرض من {{ \App\Models\System\SystemSetting::get('system_name') }}. وأتعهد بالالتزام بكافة الشروط والأحكام،
        كما أفوض الإدارة المالية بالجامعة بخصم قيمة الأقساط الشهرية من راتبي أو من أي مستحقات مالية أخرى لي،
        وذلك حتى يتم سداد كامل قيمة القرض.
    </p>
    <p class="font-medium text-lg mt-6 text-[#124375]">
        تحريراً في: {{ now()->timezone('Africa/Cairo')->format('d / m / Y') }} م
    </p>
    <div class="font-medium text-lg mt-8 flex justify-between text-[#124375]">
        <span>الاسم / <span class="font-bold text-[#021219]">{{ $member->user->name }}</span></span>
        <span>الوظيفة / <span class="font-bold text-[#021219]">{{ $member->employmentInfo->job_title ?? '________________' }}</span></span>
    </div>
    <div class="font-medium text-lg mt-6 flex justify-between text-[#124375]">
        <span>الرقم القومي / <span class="font-bold text-[#021219]">{{ $member->user->national_id }}</span></span>
        <span>التوقيع / ________________</span>
    </div>
    <div class="font-medium text-lg mt-6 flex justify-between text-[#124375]">
        <span>قيمة القرض / ________________</span>
        <span>مدته / ________________</span>
    </div>
</div>
@endsection
