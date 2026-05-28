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
    <form action="{{ route('admin.reports.installments') }}" method="GET" class="px-12 flex items-center justify-start gap-5 print:hidden">
        <div class="relative min-w-[200px]">
            @include('partials.calendar', [
                'name' => 'date_from',
                'id' => 'report-datepicker-from',
                'value' => request('date_from'),
                'autoSubmit' => false,
            ])
            <span class="absolute top-[-10px] right-2 bg-[#F4F7F9] px-1 text-[12px] text-[#124375] font-medium">من تاريخ</span>
        </div>
        <div class="relative min-w-[200px]">
            @include('partials.calendar', [
                'name' => 'date_to',
                'id' => 'report-datepicker-to',
                'value' => request('date_to'),
                'autoSubmit' => false,
            ])
            <span class="absolute top-[-10px] right-2 bg-[#F4F7F9] px-1 text-[12px] text-[#124375] font-medium">إلى تاريخ</span>
        </div>
        <div class="relative min-w-[150px]">
            <input type="hidden" name="status" id="status-input" value="{{ request('status', 'all') }}">
            <button type="button" onclick="document.getElementById('status-dropdown').classList.toggle('hidden')"
                class="navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">الحالة
                : <span class="text-[#021219] ">
                    @if (request('status') === 'paid')
                        محصل
                    @elseif(request('status') === 'unpaid')
                        غير محصل
                    @elseif(request('status') === 'overdue')
                        متأخر
                    @else
                        الكل
                    @endif
                </span><span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                        class="text-xl"></iconify-icon></span></button>
            <div id="status-dropdown"
                class="hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium"
                    onclick="document.getElementById('status-input').value='all'; this.closest('form').submit();">الكل</button>
                <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium"
                    onclick="document.getElementById('status-input').value='paid'; this.closest('form').submit();">محصل</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium"
                    onclick="document.getElementById('status-input').value='unpaid'; this.closest('form').submit();">غير محصل</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium"
                    onclick="document.getElementById('status-input').value='overdue'; this.closest('form').submit();">متأخر</button>
            </div>
        </div>
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
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
