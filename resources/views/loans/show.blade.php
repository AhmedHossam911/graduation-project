@extends('layouts.app')
@section('title', 'القروض السابقة')
@section('content')
    <!-- start header -->
    <div class="py-4 px-12">
        <h1 class="text-[#124375] text-[28px] font-semibold">
            القروض السابقة
        </h1>
        <p class="text-[#124375] text-[18px] font-medium">القروض التي تم طلبها سابقاً</p>
    </div>
    <!-- end header -->


    <section class="px-12 py-5">
        <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم القرض</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">قيمة القرض</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">مدة السداد</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ تقديم الطلب</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ البداية</th>
                        <th class="py-4 font-medium text-[#021219]">تاريخ الإنتهاء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center border-b border-[#D1D5DB]">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">123456785</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">5,000</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#ECFDF3] text-[#067647] border border-[#067647] px-12 py-1  text-sm rounded-lg">نشط</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">12 شهر</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2025</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-5">1 يناير 2027</td>
                    </tr>
                    <tr class="text-center border-b border-[#D1D5DB] bg-[#EFEFEF]">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">123456785</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">5,000</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-12 py-1  text-sm rounded-lg">نشط</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">12 شهر</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2025</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-5">1 يناير 2027</td>
                    </tr>
                    <tr class="text-center border-b border-[#D1D5DB]">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">123456785</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">5,000</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#FFEAE8] text-[#D92D20] border border-[#D92D20] px-12 py-1  text-sm rounded-lg">نشط</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">12 شهر</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2025</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-5">1 يناير 2027</td>
                    </tr>
                    <tr class="text-center bg-[#EFEFEF]">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">123456785</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">5,000</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#E6F1FD80] text-[#124375] border border-[#124375] px-12 py-1  text-sm rounded-lg">نشط</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">12 شهر</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2025</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-5">1 يناير 2027</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection
