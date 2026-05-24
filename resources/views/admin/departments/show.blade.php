@extends('layouts.app')

@section('title', 'عرض كلية او قطاع')

@include('partials.flash')

@section('content')

    <link(rel="stylesheet" href="{{ asset('CSS/departmentsView.css') }}")>

        <div class="py-7 px-12">
            <div class="flex flex-col gap-3">
                <h1 class="text-xl text-[#124375] font-semibold">
                    قائمة أعضاء كلية التجارة وإدارة الأعمال
                </h1>
                <p class="text-[#6D6D6D] text-[16px] font-normal">
                    إدارة بيانات الأعضاء المسجلين بالكلية ومتابعة حالتهم.
                </p>
            </div>
        </div>

        <form class="px-12 flex items-center justify-between gap-5">
            <div class="relative flex-1">
                <input type="search" placeholder="الاسم أو رقم العضوية أو رقم المطالبة"
                    class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                <iconify-icon icon="mynaui:search"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
            </div>
            <div class="relative min-w-[150px]">
                <button type="button"
                    class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">الحالة
                    : <span class="text-[#021219] ">نشط</span><span class="flex items-center"><iconify-icon
                            icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                <div
                    class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">الكل</button>
                    <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">نشط</button>
                    <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">قيد
                        التسجيل</button>
                    <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">إعارة</button>
                    <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">محال
                        للمعاش</button>
                    <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">منسحب</button>
                    <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">مفصول</button>
                    <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">أجازه بدون
                        مرتب</button>
                    <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">منتهية
                        العضوية</button>
                </div>
            </div>
            <div>
                <button type="submit"
                    class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                    <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                </button>
            </div>
        </form>

        <!-- start table -->
        <section class="px-12 py-7">
            <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم العضوية</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الرقم القومي</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الجهة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم الهاتف</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ الانضمام</th>
                            <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">كلية تجارة وإدارة أعمال</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">01234567891</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">نشط</span>
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">1 يناير 2026</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center border-b border-[#6D6D6D] bg-[#EFEFEF]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">كلية تجارة وإدارة أعمال</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">01234567891</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#175CD3] bg-[#EFF8FF] border border-[#175CD3] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">قيد
                                    التسجيل</span></td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">1 يناير 2026</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">كلية تجارة وإدارة أعمال</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">01234567891</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">محال
                                    للمعاش</span></td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">1 يناير 2026</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center border-b border-[#6D6D6D] bg-[#EFEFEF]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">كلية تجارة وإدارة أعمال</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">01234567891</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#F79009] bg-[#FFF7ED] border border-[#F79009] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">منسحب</span>
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">1 يناير 2026</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center border-b border-[#6D6D6D] ">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">كلية تجارة وإدارة أعمال</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">01234567891</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#5925DC] bg-[#F4F3FF] border border-[#5925DC] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">إعارة</span>
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">1 يناير 2026</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center border-b border-[#6D6D6D] bg-[#EFEFEF]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">كلية تجارة وإدارة أعمال</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">01234567891</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">مفصول</span>
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">1 يناير 2026</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">كلية تجارة وإدارة أعمال</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">01234567891</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#4B5A70] bg-[#F3F6FA] border border-[#4B5A70] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">أجازه
                                    بدون مرتب</span></td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">1 يناير 2026</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center bg-[#EFEFEF]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">كلية تجارة وإدارة أعمال</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">01234567891</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">منتهية
                                    العضوية</span></td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">1 يناير 2026</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- end table -->

        <!-- start pagination -->
        <div class="mt-auto">
            <hr class="border border-[#A8A8A8] mt-5 mb-4">
            <div class="pagination-container flex items-center justify-center gap-4 mb-4">
                <div class="next-arr">
                    <button class="text-[#124375] text-[16px] font-medium">
                        التالي
                    </button>
                </div>
                <div class="pages-num flex items-center gap-2 text-[#124375] ">
                    <span
                        class="cursor-pointer bg-[#F4F7F9] navy-shadow rounded-[6px] px-3 py-1 font-medium hover:bg-[#124375] hover:text-white transition-colors">5</span>
                    <span
                        class="cursor-pointer bg-[#F4F7F9] navy-shadow rounded-[6px] px-3 py-1 font-medium hover:bg-[#124375] hover:text-white transition-colors">4</span>
                    <span
                        class="cursor-pointer bg-[#F4F7F9] navy-shadow rounded-[6px] px-3 py-1 font-medium hover:bg-[#124375] hover:text-white transition-colors">3</span>
                    <span
                        class="cursor-pointer bg-[#F4F7F9] navy-shadow rounded-[6px] px-3 py-1 font-medium hover:bg-[#124375] hover:text-white transition-colors">2</span>
                    <span
                        class="cursor-pointer bg-[#124375] text-white navy-shadow rounded-[6px] px-3 py-1 font-medium">1</span>
                </div>
                <div class="prev-arr">
                    <button class="text-[#6D6D6D] text-[16px] font-medium ">
                        السابق
                    </button>
                </div>
            </div>
        </div>
        <!-- end pagination -->

        <script src="{{ asset('JS/departmentsView.js') }}"></script>

    @endsection
