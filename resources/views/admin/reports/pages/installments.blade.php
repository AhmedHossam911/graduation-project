@extends('layouts.app')

@section('title', 'بيان الأقساط والتحصيل الشهري')

@section('content')
    <div class="flex justify-between px-12 py-5 print:hidden">
        <div>
            <h1 class="text-[32px] font-medium text-[#124375]">
                بيان الأقساط والتحصيل الشهري
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal mt-2">كشف بقيم أقساط القروض المستقطعة شهرياً من الأعضاء.</p>
        </div>
        <div class="btns flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}"
                class="rounded-xl flex items-center justify-center py-3 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('admin.reports.export_installments', request()->query()) }}"
                class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <!-- filteration buttons -->
    <form action="{{ route('admin.reports.installments') }}" method="GET" class="px-12 flex flex-wrap w-full items-center gap-6 print:hidden">
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

    <section class="px-12 py-4 print:hidden">
        <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
            <table class="w-full">
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
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $ins->loan->membership->member->full_name }}</td>
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
        </div>
    </section>
@endsection

@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $installments->links() }}
    </div>
@endsection

