@extends('layouts.app')
{{-- 
    Previous Loans View:
    Shows a historical list of all loans (completed, rejected, etc.) for a specific member context,
    providing transparency into their borrowing history.
--}}
@section('title', 'القروض السابقة')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/employee/previousLoans.css') }}">
    <!-- start header -->
    <div class="py-4 px-4 md:px-12">
        <h1 class="text-[#124375] text-[24px] md:text-[28px] font-semibold">
            القروض السابقة
        </h1>
        <p class="text-[#124375] text-[16px] md:text-[18px] font-medium">القروض التي تم طلبها سابقاً</p>
    </div>
    <!-- end header -->


    <section class="px-4 md:px-12 py-5">
        <div class="rounded-[14px] overflow-hidden border-0 md:border border-[#D1D5DB] bg-transparent md:bg-white p-0">
            <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم القرض</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">قيمة القرض</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">مدة السداد</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ تقديم الطلب</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ البداية</th>
                        <th class="py-4 font-medium text-[#021219]">تاريخ الإنتهاء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        @php
                            $firstInstallment = $loan->installments->sortBy('due_date')->first();
                            $lastInstallment = $loan->installments->sortByDesc('due_date')->first();
                        @endphp
                        <tr class="text-center border-b border-[#D1D5DB] even:bg-[#EFEFEF]">
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $loan->id }}</td>
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ number_format($loan->total_amount) }}</td>
                            <td class="py-4 border-l border-[#D1D5DB]">
                                @if ($loan->status === 'overdue')
                                    <span class="bg-[#FFF7ED] text-[#F79009] border border-[#F79009] px-12 py-1 text-sm rounded-lg">متأخر</span>
                                @elseif ($loan->status === 'completed')
                                    <span class="bg-[#EEF7FF] text-[#124375] border border-[#124375] px-12 py-1 text-sm rounded-lg">مكتمل</span>
                                @elseif ($loan->status === 'active')
                                    <span class="bg-[#ECFDF3] text-[#067647] border border-[#067647] px-12 py-1 text-sm rounded-lg">نشط</span>
                                @elseif ($loan->status === 'pending')
                                    <span class="bg-[#FFF8E1] text-[#E6B800] border border-[#E6B800] px-12 py-1 text-sm rounded-lg">قيد المراجعة</span>
                                @elseif ($loan->status === 'rejected')
                                    <span class="bg-[#FFEAE8] text-[#D92D20] border border-[#D92D20] px-12 py-1 text-sm rounded-lg">مرفوض</span>
                                @else
                                    <span class="bg-[#EFEFEF] text-[#6D6D6D] border border-[#6D6D6D] px-12 py-1 text-sm rounded-lg">{{ $loan->status }}</span>
                                @endif
                            </td>
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $loan->months }} شهر</td>
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $loan->created_at?->isoFormat('D MMMM YYYY') }}</td>
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $firstInstallment ? $firstInstallment->due_date?->isoFormat('D MMMM YYYY') : '-' }}</td>
                            <td class="py-5">{{ $lastInstallment ? $lastInstallment->due_date?->isoFormat('D MMMM YYYY') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-500">
                                <p>لا توجد قروض سابقة لهذا العضو</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse ($loans as $loan)
                    @php
                        $firstInstallment = $loan->installments->sortBy('due_date')->first();
                        $lastInstallment = $loan->installments->sortByDesc('due_date')->first();
                        
                        $statusHtml = '';
                        $statusBorder = '';
                        if ($loan->status === 'overdue') {
                            $statusHtml = 'متأخر';
                            $statusBorder = 'bg-[#FFF7ED] text-[#F79009] border-[#F79009]';
                        } elseif ($loan->status === 'completed') {
                            $statusHtml = 'مكتمل';
                            $statusBorder = 'bg-[#EEF7FF] text-[#124375] border-[#124375]';
                        } elseif ($loan->status === 'active') {
                            $statusHtml = 'نشط';
                            $statusBorder = 'bg-[#ECFDF3] text-[#067647] border-[#067647]';
                        } elseif ($loan->status === 'pending') {
                            $statusHtml = 'قيد المراجعة';
                            $statusBorder = 'bg-[#FFF8E1] text-[#E6B800] border-[#E6B800]';
                        } elseif ($loan->status === 'rejected') {
                            $statusHtml = 'مرفوض';
                            $statusBorder = 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]';
                        } else {
                            $statusHtml = $loan->status;
                            $statusBorder = 'bg-[#EFEFEF] text-[#6D6D6D] border-[#6D6D6D]';
                        }
                    @endphp
                    <div class="bg-white rounded-[14px] border border-[#D1D5DB] p-4 flex flex-col gap-3 shadow-sm relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-[#6D6D6D]">رقم القرض: {{ $loan->id }}</span>
                                <span class="text-lg text-[#067647] font-bold">{{ number_format($loan->total_amount) }} ج.م</span>
                            </div>
                            <span class="{{ $statusBorder }} border rounded-[8px] py-[2px] px-3 text-xs text-center font-medium">
                                {{ $statusHtml }}
                            </span>
                        </div>
                        
                        <div class="flex flex-col gap-2 mt-2">
                            <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-2">
                                <span class="text-[#6D6D6D]">مدة السداد:</span>
                                <span class="text-[#021219] font-medium">{{ $loan->months }} شهر</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-2">
                                <span class="text-[#6D6D6D]">تاريخ تقديم الطلب:</span>
                                <span class="text-[#021219] font-medium">{{ $loan->created_at?->isoFormat('D MMMM YYYY') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-2">
                                <span class="text-[#6D6D6D]">تاريخ البداية:</span>
                                <span class="text-[#021219] font-medium">{{ $firstInstallment ? $firstInstallment->due_date?->isoFormat('D MMMM YYYY') : '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-[#6D6D6D]">تاريخ الإنتهاء:</span>
                                <span class="text-[#021219] font-medium">{{ $lastInstallment ? $lastInstallment->due_date?->isoFormat('D MMMM YYYY') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[14px] border border-[#D1D5DB] p-8 flex flex-col items-center justify-center text-center shadow-sm">
                        <p class="text-[#6D6D6D] font-medium text-lg">لا توجد قروض سابقة لهذا العضو</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <script src="{{ asset('js/employee/previousLoans.js') }}"></script>
@endsection
