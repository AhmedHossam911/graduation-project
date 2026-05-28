@extends('layouts.print')

@section('title', 'بيان مطالبة صرف ميزة تأمينية')

@php
    // We can set a title variable here to be used by the layout if we want, 
    // but we need to pass it to the component. Actually the layout uses $title from view data.
    // If we want to override it cleanly, we can do it in the controller, but since we are just 
    // editing the view, let's keep it simple. We will just remove the manual header.
@endphp

@section('content')
<div class="px-2 py-4 bg-white" dir="rtl">

    <!-- Member Info Table -->
    <div class="mb-6 break-inside-avoid">
        <h2 class="text-lg font-bold text-black mb-2">أولاً: بيانات العضو</h2>
        <table class="w-full border-collapse border border-black text-right text-black">
            <tbody>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100 w-1/4">الاسم</th>
                    <td class="border border-black px-4 py-2 w-1/4">{{ $claim->membership->member->full_name }}</td>
                    <th class="border border-black px-4 py-2 bg-gray-100 w-1/4">رقم العضوية</th>
                    <td class="border border-black px-4 py-2 w-1/4 font-bold">{{ $claim->membership->membership_number }}</td>
                </tr>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100">الوظيفة</th>
                    <td class="border border-black px-4 py-2">{{ $claim->membership->member->employmentInfo->job_title ?? '-' }}</td>
                    <th class="border border-black px-4 py-2 bg-gray-100">جهة العمل / القسم</th>
                    <td class="border border-black px-4 py-2">{{ $claim->membership->member->department->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100">تاريخ الانضمام</th>
                    <td class="border border-black px-4 py-2">{{ $claim->membership->member->employmentInfo->join_date ? \Carbon\Carbon::parse($claim->membership->member->employmentInfo->join_date)->translatedFormat('Y/m/d') : '-' }}</td>
                    <th class="border border-black px-4 py-2 bg-gray-100">تاريخ الانتهاء</th>
                    <td class="border border-black px-4 py-2">{{ $claim->membership->member->employmentInfo->retirement_date ? \Carbon\Carbon::parse($claim->membership->member->employmentInfo->retirement_date)->translatedFormat('Y/m/d') : '-' }}</td>
                </tr>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100">مدة الخدمة</th>
                    <td class="border border-black px-4 py-2 font-bold" colspan="3">{{ $serviceYears }} عام و {{ $serviceMonths }} شهر</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Financial Details Table -->
    <div class="mb-6 break-inside-avoid">
        <h2 class="text-lg font-bold text-black mb-2">ثانياً: البيانات المالية والاشتراكات</h2>
        <table class="w-full border-collapse border border-black text-right text-black">
            <tbody>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100 w-1/2">رسم العضوية المسدد</th>
                    <td class="border border-black px-4 py-2 w-1/2 font-semibold">{{ number_format($joinFee, 2) }} ج.م</td>
                </tr>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100">إجمالي الاشتراكات المسددة ({{ $paidMonths }} شهر)</th>
                    <td class="border border-black px-4 py-2 font-semibold">{{ number_format($paidSubscriptionsAmount, 2) }} ج.م</td>
                </tr>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100">إجمالي المبلغ المدفوع</th>
                    <td class="border border-black px-4 py-2 font-bold">{{ number_format($joinFee + $paidSubscriptionsAmount, 2) }} ج.م</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Deductions Table -->
    <div class="mb-6 break-inside-avoid">
        <h2 class="text-lg font-bold text-black mb-2">ثالثاً: المديونيات والاستقطاعات</h2>
        <table class="w-full border-collapse border border-black text-right text-black">
            <tbody>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100 w-1/2">رصيد القروض المتبقي ({{ $claim->membership->active_loans_count }} قرض)</th>
                    <td class="border border-black px-4 py-2 font-semibold">{{ number_format($claim->membership->remaining_loan_balance, 2) }} ج.م</td>
                </tr>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100">مديونية الاشتراكات المتأخرة</th>
                    <td class="border border-black px-4 py-2 font-semibold">{{ number_format($overdueSubscriptionsAmount, 2) }} ج.م</td>
                </tr>
                <tr>
                    <th class="border border-black px-4 py-2 bg-gray-100">إجمالي المديونية</th>
                    <td class="border border-black px-4 py-2 font-bold text-red-700">{{ number_format($claim->membership->remaining_loan_balance + $overdueSubscriptionsAmount, 2) }} ج.م</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Final Calculation Box -->
    <div class="mb-6 border-2 border-black p-4 bg-gray-50 break-inside-avoid">
        <h2 class="text-xl font-bold text-black text-center mb-4 underline">التسوية النهائية</h2>
        
        <div class="flex justify-between items-center mb-3 px-8">
            <span class="text-lg font-bold text-black">قيمة الميزة التأمينية المستحقة</span>
            <span class="text-lg font-bold">{{ number_format($insurance_benefit, 2) }} ج.م</span>
        </div>
        
        <div class="flex justify-between items-center mb-3 px-8 text-red-700">
            <span class="text-lg font-bold">يُخصم منها (إجمالي المديونية)</span>
            <span class="text-lg font-bold">- {{ number_format($claim->membership->remaining_loan_balance + $overdueSubscriptionsAmount, 2) }} ج.م</span>
        </div>
        
        <hr class="border-black mb-3 mx-8">
        
        <div class="flex justify-between items-center px-8 bg-gray-200 py-3 border border-black">
            <span class="text-xl font-bold text-black">صافي المبلغ المستحق صرفه</span>
            <span class="text-2xl font-black text-black">{{ number_format($net_amount, 2) }} ج.م</span>
        </div>
    </div>
</div>
@endsection
