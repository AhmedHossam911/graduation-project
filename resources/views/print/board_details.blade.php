@extends('layouts.print')

@php
    $title = 'تفاصيل طلب قرض لعرضه على المجلس';
    $reference = $member->membership_number ?? '';
@endphp

@section('title', $title)

@section('content')
<div class="space-y-6 px-8">
    <h3 class="text-center font-bold text-2xl text-[#124375] mb-6">بيانات العضو وطلب القرض</h3>
    
    <div class="grid grid-cols-2 gap-6 text-xl border-b-2 pb-6 border-[#124375]">
        <p><strong>اسم العضو:</strong> <span class="font-medium">{{ $member->full_name }}</span></p>
        <p><strong>رقم العضوية:</strong> <span class="font-medium">{{ $membership->membership_number ?? '-' }}</span></p>
        <p><strong>الرقم القومي:</strong> <span class="font-medium">{{ $member->national_id }}</span></p>
        <p><strong>الراتب الأساسي:</strong> <span class="font-medium">{{ $member->employmentInfo->starting_salary ? number_format($member->employmentInfo->starting_salary, 2) . ' ج.م' : 'غير متوفر' }}</span></p>
    </div>
    
    <div class="grid grid-cols-2 gap-6 text-xl pt-6">
        <p><strong>قيمة القرض المطلوبة:</strong> <span class="font-medium">{{ number_format($activeLoan->amount, 2) }} ج.م</span></p>
        <p><strong>عدد الأشهر:</strong> <span class="font-medium">{{ $activeLoan->installments->count() }} شهر</span></p>
        <p><strong>تاريخ الطلب:</strong> <span class="font-medium">{{ $activeLoan->created_at->format('Y-m-d') }}</span></p>
    </div>
    
    <div class="mt-16 text-xl space-y-12">
        <p class="mb-4 flex items-end"><strong>رأي اللجنة المختصة:</strong> <span class="border-b-2 border-dotted border-gray-400 flex-1 ml-4 h-6"></span></p>
        <p class="mb-4 flex items-end"><strong>قرار مجلس الإدارة:</strong> <span class="border-b-2 border-dotted border-gray-400 flex-1 ml-4 h-6"></span></p>
    </div>
</div>
@endsection
