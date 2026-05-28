@extends('layouts.app')

@section('title', 'بيان المزايا التأمينية والمطالبات')

@section('content')
    <div class="flex justify-between px-12 py-5 print:hidden">
        <div>
            <h1 class="text-[32px] font-medium text-[#124375]">
                بيان المزايا التأمينية والمطالبات (المنصرفة)
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal mt-2">كشف تفصيلي بالمطالبات المعتمدة وقيمتها المنصرفة للمستفيدين.</p>
        </div>
        <div class="btns flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}"
                class="rounded-xl flex items-center justify-center py-3 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('claims.export', ['status' => 'paid', ...request()->query()]) }}"
                class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <!-- filteration buttons -->
    <form action="{{ route('admin.reports.claims') }}" method="GET" class="px-12 flex items-center justify-start gap-5 print:hidden">
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
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم المطالبة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">النوع</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ الصرف</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                        <tr class="text-center {{ $loop->even ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-{{ $claim->id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $claim->membership->member->full_name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ \App\Models\Services\Claim::CLAIM_TYPES[$claim->type] ?? $claim->type }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#124375] font-bold">{{ number_format($claim->amount, 2) }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $claim->updated_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-[#6D6D6D] font-medium">لا توجد مطالبات منصرفة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7FE] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $claims->links() }}
    </div>
@endsection
