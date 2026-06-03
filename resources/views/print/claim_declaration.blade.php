@extends('layouts.print')
{{-- 
    Print Claim Declaration View:
    Printable legal declaration form signed by the member or their beneficiaries
    acknowledging the receipt of a financial claim and clearing the fund's liability.
--}}

@php
    $title = 'إقرار استلام المستحقات';
    $reference = $member->membership_number ?? '';
@endphp

@section('title', $title)

@section('content')
<div class="declaration space-y-3 px-8" id="declaration-content">
    <h3 class="text-center font-bold text-2xl text-[#124375] mb-8">إقرار استلام المستحقات</h3>
    <p class="font-medium text-xl leading-loose border-2 border-[#124375] p-6 rounded-xl bg-[#F4F7F9] text-[#021219]">
        أقر أنا / <span
            class="font-bold underline decoration-dotted">{{ $member->user->name }}</span>
        بأنني قد قمت باستلام كافة مستحقاتي من {{ \App\Models\System\SystemSetting::get('system_name') }}،
        وذلك اعتبارًا من تاريخ {{ now()->timezone('Africa/Cairo')->format('Y-m-d') }}، وأقر بعدم أحقيتي في المطالبة بأي
        مستحقات
        أخرى بعد هذا التاريخ.
    </p>
    <div class="font-medium text-lg mt-12 flex justify-between text-[#124375]">
        <span>الاسم / <span class="font-bold text-[#021219]">{{ $member->user->name }}</span></span>
        <span>الرقم القومي / <span class="font-bold text-[#021219]">{{ $member->user->national_id }}</span></span>
        <span>التوقيع / ________________</span>
    </div>
</div>
@endsection
