@extends('layouts.app')

@section('title', 'موقف القروض والسلف المنصرفة')

@section('content')
    <div class="flex justify-between px-12 py-5 print:hidden">
        <div>
            <h1 class="text-[32px] font-medium text-[#124375]">
                موقف القروض والسلف المنصرفة
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal mt-2">إجمالي القروض التي تم الموافقة عليها وصرفها للأعضاء خلال فترة محددة.</p>
        </div>
        <div class="btns flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}"
                class="rounded-xl flex items-center justify-center py-3 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('loans.export', ['status' => 'active', ...request()->query()]) }}"
                class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <!-- filteration buttons -->
    <form action="{{ route('admin.reports.loans') }}" method="GET" class="px-12 flex flex-wrap w-full items-center gap-6 print:hidden">
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
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم القرض</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">قيمة القرض</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">عدد الأقساط</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">القسط الشهري</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ الصرف</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr class="text-center {{ $loop->even ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">LOAN-{{ $loan->id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $loan->membership->member->user->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#124375] font-bold">{{ number_format($loan->total_amount, 2) }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $loan->months }} شهر</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-bold">{{ number_format($loan->monthly_installment, 2) }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $loan->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-[#6D6D6D] font-medium">لا توجد قروض نشطة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $loans->links() }}
    </div>
@endsection

