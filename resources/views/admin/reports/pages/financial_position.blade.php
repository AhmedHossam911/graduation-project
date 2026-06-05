@extends('layouts.app')

@section('title', 'الموقف المالي الختامي')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-12 py-5 gap-4 md:gap-0 print:hidden">
        <div>
            <h1 class="text-[24px] md:text-[32px] font-medium text-[#124375]">
                الموقف المالي الختامي للصندوق
            </h1>
            <p class="text-[#6D6D6D] text-[14px] md:text-[16px] font-normal mt-2">كشف تفصيلي بالميزانية العمومية والمركز المالي للصندوق.</p>
        </div>
        <div class="btns flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.reports.index') }}"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 px-5 bg-[#F4F7F9] text-[#124875] font-semibold border-2
                border-[#124375] navy-shadow hover:bg-[#E2E8F0] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('admin.reports.export_financial_position', request()->query()) }}" onclick="confirmExport(event, this.href)"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <!-- filteration buttons -->
    <form action="{{ route('admin.reports.financial_position') }}" method="GET" class="px-4 md:px-12 flex flex-wrap w-full items-center gap-6 print:hidden">
        <div class="relative flex-1 min-w-[200px]">
            @php
                $years = [];
                $currentYear = date('Y');
                for($i = $currentYear; $i >= 2020; $i--) {
                    $years[$i] = $i;
                }
            @endphp
            @include('partials.common.dropdown', [
                'name' => 'year',
                'options' => $years,
                'selected' => request('year', $currentYear),
                'autoSubmit' => true,
                'label' => 'السنة',
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
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">البند</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">القيمة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center bg-[#EFEFEF] border-b border-[#6D6D6D]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-bold">إجمالي الإيرادات</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#067647] font-bold">{{ number_format($totalRevenues, 2) }} ج.م</td>
                    </tr>
                    <tr class="text-center border-b border-[#6D6D6D]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-bold">إجمالي المصروفات</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#D92D20] font-bold">{{ number_format($totalExpenses, 2) }} ج.م</td>
                    </tr>
                    <tr class="text-center bg-[#EFEFEF] border-b border-[#6D6D6D]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-bold">صافي الرصيد</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#124375] font-bold text-xl">{{ number_format($netBalance, 2) }} ج.م</td>
                    </tr>
                    <tr class="text-center border-b border-[#6D6D6D]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-bold">رصيد القروض المستحقة</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#E6B800] font-bold">{{ number_format($activeLoansBalance, 2) }} ج.م</td>
                    </tr>
                </tbody>
            </table>

            <!-- Mobile Cards View -->
            <div class="md:hidden flex flex-col gap-4">
                <div class="bg-[#EFEFEF] p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex justify-between items-center">
                    <span class="text-[#021219] font-bold">إجمالي الإيرادات</span>
                    <span class="text-[#067647] font-bold">{{ number_format($totalRevenues, 2) }} ج.م</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex justify-between items-center">
                    <span class="text-[#021219] font-bold">إجمالي المصروفات</span>
                    <span class="text-[#D92D20] font-bold">{{ number_format($totalExpenses, 2) }} ج.م</span>
                </div>
                <div class="bg-[#EFEFEF] p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex justify-between items-center">
                    <span class="text-[#021219] font-bold">صافي الرصيد</span>
                    <span class="text-[#124375] font-bold text-xl">{{ number_format($netBalance, 2) }} ج.م</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex justify-between items-center">
                    <span class="text-[#021219] font-bold">رصيد القروض المستحقة</span>
                    <span class="text-[#E6B800] font-bold">{{ number_format($activeLoansBalance, 2) }} ج.م</span>
                </div>
            </div>
        </div>
    </section>
@endsection

