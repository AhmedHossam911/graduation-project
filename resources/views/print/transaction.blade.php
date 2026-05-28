@extends('layouts.print')
{{-- 
    Print General Transaction View:
    A versatile, printable receipt for any general financial transaction
    (like incoming revenues or outgoing expenses) processed through the finance module.
--}}

@php
    $title = 'إيصال استلام/صرف';
    $reference = $transaction->transaction_number ?? 'حركة-' . $transaction->id;
@endphp

@section('title', $title)

@section('content')
<div class="receipt-container p-6 text-right" style="background-color: #F4F7F9; border-radius: 12px; direction: rtl;">
    <h2 class="text-2xl font-bold text-[#124375] mb-6 text-center">إيصال
        {{ $transaction->type === 'IN' ? 'استلام' : 'صرف' }} نقدية / شيك
    </h2>

    <div class="flex justify-between items-center mb-6 text-[#124375] font-medium border-b border-[#124375] pb-4">
        <div><span>التاريخ :</span> <span>{{ $transaction->created_at->format('Y-m-d') }}</span></div>
        <div><span>الوقت :</span> <span>{{ $transaction->created_at->locale('ar')->translatedFormat('h:i A') }}</span></div>
        <div><span>المبلغ :</span> <span class="font-bold">{{ number_format($transaction->amount, 2) }}</span></div>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-bold mb-2 text-[#124375]">بيانات العضو</h3>
        <div class="flex flex-row-reverse justify-between items-center text-[#124375]">
            <div><span>الأسم رباعي :</span> <span class="text-[#6D6D6D]">{{ $transaction->membership?->member?->full_name ?? '-' }}</span></div>
            <div><span>رقم العضوية :</span> <span class="text-[#6D6D6D]">{{ $transaction->membership?->membership_number ?? '-' }}</span></div>
        </div>
    </div>

    <div class="border-t border-[#124375] pt-4 mb-6">
        <h3 class="text-lg font-bold mb-2 text-[#124375]">بيانات الدفع</h3>
        <div class="grid grid-cols-2 gap-4 text-[#124375]">
            <div><span>البند :</span> <span class="text-[#6D6D6D]">{{ $transaction->category_label }}</span></div>
            <div><span>طريقة الدفع :</span> <span class="text-[#6D6D6D]">{{ $transaction->method_label }}</span></div>
            @if ($transaction->payment_reference)
                <div class="col-span-2"><span>المرجع المالي :</span> <span class="text-[#6D6D6D]">{{ $transaction->payment_reference }}</span></div>
            @endif
            @if ($transaction->notes)
                <div class="col-span-2"><span>ملاحظات :</span> <span class="text-[#6D6D6D]">{{ $transaction->notes }}</span></div>
            @endif
        </div>
    </div>
    
    <div class="border-t border-[#124375] pt-4 flex justify-between items-center">
        <div class="text-[#124375]"><span>توقيع الموظف :</span> ................................</div>
        <div class="text-[#124375]"><span>توقيع العضو :</span> ................................</div>
    </div>
</div>
@endsection
