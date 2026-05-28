@extends('layouts.print')
{{-- 
    Print New Membership Receipt View:
    Printable receipt issued upon the successful registration of a new member
    and the payment of their initial joining fees.
--}}

@php
    $title = 'إيصال اشتراك عضوية';
    $reference = $member->membershipInfo->membership_number ?? '';
@endphp

@section('title', $title)

@section('content')
<div class="receipt-container p-6 text-right" style="background-color: #F4F7F9; border-radius: 12px; direction: rtl;">
    <h2 class="text-2xl font-bold text-[#124375] mb-6 text-center">إيصال دفع إشتراك عضوية جديدة</h2>

    <div class="flex justify-between items-center mb-6 text-[#124375] font-medium border-b border-[#124375] pb-4">
        <div><span>التاريخ :</span> <span>{{ date('Y-m-d') }}</span></div>
        <div><span>الوقت :</span> <span>{{ date('h:i A') }}</span></div>
        <div><span>قيمة الأشتراك :</span> <span class="font-bold">{{ number_format($member->membershipInfo->subscriptions->first()->amount ?? 0, 2) }}</span></div>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-bold mb-2 text-[#124375]">بيانات العضو</h3>
        <div class="flex flex-row-reverse justify-between items-center text-[#124375]">
            <div><span>الأسم رباعي :</span> <span class="text-[#6D6D6D]">{{ $member->full_name }}</span></div>
            <div><span>رقم العضوية :</span> <span class="text-[#6D6D6D]">{{ $member->membershipInfo->membership_number }}</span></div>
        </div>
    </div>

    <div class="border-t border-[#124375] pt-4 mb-6">
        <h3 class="text-lg font-bold mb-2 text-[#124375]">بيانات الجهة</h3>
        <div class="flex flex-row-reverse justify-between items-center text-[#124375]">
            <div><span>البنك :</span> <span class="text-[#6D6D6D]">بنك مصر</span></div>
            <div><span>الجهة :</span> <span class="text-[#6D6D6D]">صندوق الزمالة - جامعة العاصمة</span></div>
            <div><span>رقم حساب المستفيد :</span> <span class="text-[#6D6D6D]">077777777777777</span></div>
        </div>
    </div>
</div>
@endsection
