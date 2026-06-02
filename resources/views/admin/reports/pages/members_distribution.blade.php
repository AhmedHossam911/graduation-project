@extends('layouts.app')

@section('title', 'توزيع الأعضاء حسب الكليات')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-12 py-5 gap-4 md:gap-0 print:hidden">
        <div>
            <h1 class="text-[24px] md:text-[32px] font-medium text-[#124375]">
                توزيع الأعضاء حسب الكليات
            </h1>
            <p class="text-[#6D6D6D] text-[14px] md:text-[16px] font-normal mt-2">تقرير إحصائي يوضح أعداد المشتركين موزعة على كليات وإدارات الجامعة.</p>
        </div>
        <div class="btns flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.reports.index') }}"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 px-5 bg-[#F4F7F9] text-[#124375] font-semibold navy-shadow hover:bg-[#E2E8F0] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('admin.reports.export_members_distribution') }}" onclick="confirmExport(event, this.href)"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <section class="px-4 md:px-12 py-4 print:hidden">
        <div class="rounded-[14px] overflow-hidden border-0 md:border border-[#6D6D6D]">
            <table class="hidden md:table w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم الكلية / الإدارة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">عدد الأعضاء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr class="text-center {{ $loop->even ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $dept->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#124375] font-bold text-xl">{{ $dept->members_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-[#6D6D6D] font-medium">لا توجد إدارات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile Cards View -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse($departments as $dept)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex justify-between items-center">
                        <span class="text-[#021219] font-bold">{{ $dept->name }}</span>
                        <div class="flex flex-col items-end">
                            <span class="text-[#124375] font-bold text-xl">{{ $dept->members_count }}</span>
                            <span class="text-xs text-[#6D6D6D]">عضو</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500 bg-white rounded-xl border border-gray-200">لا توجد إدارات مسجلة</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
