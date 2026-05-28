@extends('layouts.app')

@section('title', 'توزيع الأعضاء حسب الكليات')

@section('content')
    <div class="flex justify-between px-12 py-5 print:hidden">
        <div>
            <h1 class="text-[32px] font-medium text-[#124375]">
                توزيع الأعضاء حسب الكليات
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal mt-2">تقرير إحصائي يوضح أعداد المشتركين موزعة على كليات وإدارات الجامعة.</p>
        </div>
        <div class="btns flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}"
                class="rounded-xl flex items-center justify-center py-3 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('admin.reports.export_members_distribution') }}"
                class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <section class="px-12 py-4 print:hidden">
        <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
            <table class="w-full">
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
        </div>
    </section>
@endsection
