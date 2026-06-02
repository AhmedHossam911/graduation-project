@extends('layouts.app')

@section('title', 'بيان الأقساط والتحصيل الشهري')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-12 py-5 gap-4 md:gap-0 print:hidden">
        <div>
            <h1 class="text-[24px] md:text-[32px] font-medium text-[#124375]">
                بيان الأقساط والتحصيل الشهري
            </h1>
            <p class="text-[#6D6D6D] text-[14px] md:text-[16px] font-normal mt-2">كشف بقيم أقساط القروض المستقطعة شهرياً من الأعضاء.</p>
        </div>
        <div class="btns flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.reports.index') }}"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 px-5 bg-[#F4F7F9] text-[#124375] font-semibold navy-shadow hover:bg-[#E2E8F0] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('admin.reports.export_installments', request()->query()) }}" onclick="confirmExport(event, this.href)"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <!-- filteration buttons -->
    <form action="{{ route('admin.reports.installments') }}" method="GET" class="px-4 md:px-12 flex flex-wrap w-full items-center gap-6 print:hidden">
        <div class="relative flex-1 min-w-[200px]">
            @include('partials.common.calendar', [
                'name' => 'date_from',
                'id' => 'report-datepicker-from',
                'value' => request('date_from'),
                'autoSubmit' => true,
                'label' => 'من تاريخ',
                'floatingLabel' => true,
            ])
        </div>
        <div class="relative flex-1 min-w-[200px]">
            @include('partials.common.calendar', [
                'name' => 'date_to',
                'id' => 'report-datepicker-to',
                'value' => request('date_to'),
                'autoSubmit' => true,
                'label' => 'إلى تاريخ',
                'floatingLabel' => true,
            ])
        </div>
        <div class="relative flex-1 min-w-[200px]">
            @include('partials.common.dropdown', [
                'name' => 'status',
                'options' => ['all' => 'الكل', 'paid' => 'مسدد', 'unpaid' => 'غير مسدد', 'overdue' => 'متأخر'],
                'selected' => request('status', 'all'),
                'autoSubmit' => true,
                'label' => 'الحالة',
                'floatingLabel' => true,
            ])
        </div>
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-8 h-[46px] flex items-center justify-center hover:bg-[#0e3560] transition-colors navy-shadow mt-[5px]">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-3xl"></iconify-icon>
            </button>
        </div>
    </form>

    <section class="px-4 md:px-12 py-4 print:hidden">
        <div class="rounded-[14px] overflow-hidden border-0 md:border border-[#6D6D6D]">
            <table class="hidden md:table w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم القسط</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم القرض</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ الاستحقاق</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($installments as $ins)
                        <tr class="text-center {{ $loop->even ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">INS-{{ $ins->id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">LOAN-{{ $ins->loan_id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $ins->loan->membership->member->user->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#124375] font-bold">{{ number_format($ins->amount, 2) }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $ins->due_date }}</td>
                            <td class="py-3 border-l border-[#6D6D6D]">
                                @if($ins->status === 'paid')
                                    <span class="text-[#067647] bg-[#ECFDF3] border border-[#067647] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[100px]">محصل</span>
                                @elseif($ins->status === 'unpaid')
                                    <span class="text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[100px]">غير محصل</span>
                                @else
                                    <span class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[100px]">متأخر</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-[#6D6D6D] font-medium">لا توجد أقساط</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile Cards View -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse($installments as $ins)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex flex-col gap-3">
                        <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                            <div>
                                <h3 class="text-[#021219] font-bold text-lg">{{ $ins->loan->membership->member->user->name }}</h3>
                                <p class="text-sm text-[#6D6D6D]">قسط: INS-{{ $ins->id }} | قرض: LOAN-{{ $ins->loan_id }}</p>
                            </div>
                            <span class="text-[#124375] font-bold">{{ number_format($ins->amount, 2) }} ج.م</span>
                        </div>
                        <div class="flex justify-between items-center text-sm text-[#021219]">
                            <p><span class="text-[#6D6D6D] ml-1">تاريخ الاستحقاق:</span> <br>{{ $ins->due_date }}</p>
                            <div>
                                @if($ins->status === 'paid')
                                    <span class="text-[#067647] bg-[#ECFDF3] border border-[#067647] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[80px]">محصل</span>
                                @elseif($ins->status === 'unpaid')
                                    <span class="text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[80px]">غير محصل</span>
                                @else
                                    <span class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[80px]">متأخر</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500 bg-white rounded-xl border border-gray-200">لا توجد أقساط</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $installments->links() }}
    </div>
@endsection

