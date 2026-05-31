@extends('layouts.app')

@section('title', 'بيان الإيرادات والمصروفات')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-12 py-5 gap-4 md:gap-0 print:hidden">
        <div>
            <h1 class="text-[24px] md:text-[32px] font-medium text-[#124375]">
                بيان الإيرادات والمصروفات
            </h1>
            <p class="text-[#6D6D6D] text-[14px] md:text-[16px] font-normal mt-2">كشف تفصيلي بحركة الإيرادات والمصروفات الإدارية والتشغيلية.</p>
        </div>
        <div class="btns flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.reports.index') }}"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('finance.export', request()->query()) }}"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <!-- filteration buttons -->
    <form action="{{ route('admin.reports.revenue_expenses') }}" method="GET" class="px-4 md:px-12 flex flex-wrap w-full items-center gap-6 print:hidden">
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
        <div class="relative min-w-[150px]">
            <input type="hidden" name="type" id="type-input" value="{{ request('type', 'all') }}">
            <button type="button" onclick="document.getElementById('type-dropdown').classList.toggle('hidden')"
                class="navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">النوع
                : <span class="text-[#021219] ">
                    @if (request('type') === 'IN')
                        إيرادات
                    @elseif(request('type') === 'OUT')
                        مصروفات
                    @else
                        الكل
                    @endif
                </span><span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                        class="text-xl"></iconify-icon></span></button>
            <div id="type-dropdown"
                class="hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium"
                    onclick="document.getElementById('type-input').value='all'; this.closest('form').submit();">الكل</button>
                <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium"
                    onclick="document.getElementById('type-input').value='IN'; this.closest('form').submit();">إيرادات</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium"
                    onclick="document.getElementById('type-input').value='OUT'; this.closest('form').submit();">مصروفات</button>
            </div>
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
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم المعاملة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">النوع</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الوصف</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ العملية</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr class="text-center {{ $loop->even ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-{{ $trx->id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D]">
                                @if($trx->type === 'IN')
                                    <span class="text-[#067647] bg-[#ECFDF3] border border-[#067647] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[100px]">إيراد</span>
                                @else
                                    <span class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[100px]">مصروف</span>
                                @endif
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-bold">{{ number_format($trx->amount, 2) }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $trx->description }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $trx->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-[#6D6D6D] font-medium">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile Cards View -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse($transactions as $trx)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex flex-col gap-3">
                        <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                            <div>
                                <h3 class="text-[#021219] font-bold text-lg">TRX-{{ $trx->id }}</h3>
                                <p class="text-sm text-[#6D6D6D]">{{ $trx->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-[#124375] font-bold text-lg">{{ number_format($trx->amount, 2) }} ج.م</span>
                                @if($trx->type === 'IN')
                                    <span class="text-[#067647] bg-[#ECFDF3] border border-[#067647] px-2 rounded-md py-0.5 text-xs text-center">إيراد</span>
                                @else
                                    <span class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] px-2 rounded-md py-0.5 text-xs text-center">مصروف</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-sm text-[#021219]">
                            <p><span class="text-[#6D6D6D] font-medium">الوصف:</span> {{ $trx->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500 bg-white rounded-xl border border-gray-200">لا توجد بيانات</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $transactions->links() }}
    </div>
@endsection

