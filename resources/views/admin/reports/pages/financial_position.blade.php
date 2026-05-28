@extends('layouts.app')

@section('title', 'الموقف المالي الختامي')

@section('content')
    <div class="flex justify-between px-12 py-5 print:hidden">
        <div>
            <h1 class="text-[32px] font-medium text-[#124375]">
                الموقف المالي الختامي للصندوق
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal mt-2">كشف تفصيلي بالميزانية العمومية والمركز المالي للصندوق.</p>
        </div>
        <div class="btns flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}"
                class="rounded-xl flex items-center justify-center py-3 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('admin.reports.export_financial_position', request()->query()) }}"
                class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <!-- filteration buttons -->
    <form action="{{ route('admin.reports.financial_position') }}" method="GET" class="px-12 flex flex-wrap w-full items-center gap-6 print:hidden">
        <div class="relative flex-1 min-w-[200px]">
            @php
                $years = [];
                $currentYear = date('Y');
                for($i = $currentYear; $i >= 2020; $i--) {
                    $years[$i] = $i;
                }
            @endphp
            @include('partials.dropdown', [
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

    <section class="px-12 py-4 print:hidden">
        <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
            <table class="w-full">
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
        </div>
    </section>
@endsection
