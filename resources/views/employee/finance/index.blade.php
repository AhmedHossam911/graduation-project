@extends('layouts.app')

@section('title', 'قائمة الأعضاء')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/employee/finance.css') }}">
    <div class="min-h-screen flex flex-col">
        <!-- start header -->
        <div class="flex justify-between px-12 py-5">
            <div>
                <h1 class="text-[32px] font-medium text-[s#124375]">
                    المالية
                </h1>
            </div>
            <div class="btns flex items-center gap-3  ">
                <button data-modal="modal1"
                    class="open-modal rounded-xl flex items-center justify-center py-3 px-20 gap-2 text-[#F4F7F9] bg-[#124375] navy-shadow hover:bg-[#0e3560] transition-colors">
                    <iconify-icon icon="ic:round-plus" class="flex items-center text-2xl"></iconify-icon> إضافة إيراد أو مصروف
                </button>
                <a href=""
                    class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 text-[#124375] bg-[#F4F7F9] navy-shadow">
                    <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل
                </a>
            </div>
        </div>
        <!-- end header -->

        <!-- start cards -->
        <div class="py-4 grid grid-cols-3 gap-4 px-12">
            <div
                class="tab cursor-pointer navy-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
                <div>
                    <iconify-icon icon="ph:trend-up-fill"
                        class="navy-shadow text-[40px] px-2 py-1 text-[#124375] bg-[#EEF7FF] rounded-lg "></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">125,000 ج.م</p>
                    <p class="text-sm font-medium tab-name">إجمالي الإيرادات</p>
                </div>
            </div>
            <div
                class="tab cursor-pointer yellow-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
                <div>
                    <iconify-icon icon="tabler:clipboard-list-filled"
                        class="navy-shadow text-[40px] px-2 py-1 text-[#D4AF37] bg-[#FFFCEF] rounded-lg "></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">9</p>
                    <p class="text-sm font-medium tab-name">عدد الحركات اليوم</p>
                </div>
            </div>
            <div
                class="tab cursor-pointer red-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
                <div>
                    <iconify-icon icon="ph:trend-down-fill"
                        class="navy-shadow text-[40px] text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">45,000 ج.م</p>
                    <p class="text-sm font-medium tab-name">إجمالي المصروفات</p>
                </div>
            </div>
        </div>
        <!-- end cards -->

        <!-- filteration buttons -->
        <form class="px-12 flex items-center justify-between gap-5">
            <div class="relative flex-1">
                <input type="search" placeholder="الاسم أو رقم العضوية أو رقم الحركة"
                    class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                <iconify-icon icon="mynaui:search"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
            </div>
            <div class="relative min-w-[240px]">
                <label for="datepicker"
                    class="calendar-label navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full rounded-xl text-base flex gap-3 justify-center items-center">التاريخ
                    : <span class="text-[#021219]">يوم/شهر/ سنة</span><span class="flex items-center"><iconify-icon
                            icon="lucide:calendar" class="text-xl"></iconify-icon></span>
                    <input type="text" id="datepicker"
                        class="absolute left-0 top-full mt-3 opacity-0 w-0 h-0 pointer-events-none">
                </label>
            </div>
            <div class="relative min-w-[150px]">
                <button type="button"
                    class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">طريقة
                    الدفع :<span class="text-[#021219] ">الكل</span><span class="flex items-center"><iconify-icon
                            icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                <div
                    class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">خصم من المرتب</button>
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">تحويل بنكي</button>
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">دفع بجواب مسبق</button>
                </div>
            </div>
            <div class="relative min-w-[150px]">
                <button type="button"
                    class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">بند
                    الحركة :<span class="text-[#021219] ">الكل</span><span class="flex items-center"><iconify-icon
                            icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                <div
                    class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-3 py-4 rounded-xl navy-shadow w-full">
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium ">رسوم عضوية
                        جديدة</button>
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium ">اشتراكات
                        شهرية</button>
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium ">سداد قسط قرض</button>
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium ">صرف قرض جديد</button>
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium ">إيرادات
                        إدارية</button>
                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium ">مصروفات
                        إدارية</button>
                </div>
            </div>
            <div>
                <button type="submit"
                    class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                    <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                </button>
            </div>
        </form>
        <!-- end filteration buttons -->

        <!-- start table -->
        <section class="px-12 py-7">
            <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D] tab-content" data-tab="عدد الحركات اليوم">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">التاريخ والوقت</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">بند الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-1053</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">20 أبريل 2026 - 12:30 م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">سداد قسط قرض</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">200 ج .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">دفع بجواب مسبق</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">إيراد</span>
                            </td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl open-modal cursor-pointer"
                                    data-modal="modal2"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-1053</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">20 أبريل 2026 - 12:30 م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">سداد قسط قرض</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">200 ج .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">دفع بجواب مسبق</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">مصروف</span>
                            </td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl open-modal cursor-pointer"
                                    data-modal="modal3"></iconify-icon>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D] tab-content hidden"
                data-tab="إجمالي الإيرادات">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">التاريخ والوقت</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">بند الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-1053</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">20 أبريل 2026 - 12:30 م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">سداد قسط قرض</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">200 ج .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">دفع بجواب مسبق</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">إيراد</span>
                            </td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl cursor-pointer open-modal"
                                    data-modal="modal2"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-1053</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">20 أبريل 2026 - 12:30 م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">سداد قسط قرض</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">200 ج .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">دفع بجواب مسبق</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">إيراد</span>
                            </td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl cursor-pointer open-modal"
                                    data-modal="modal2"></iconify-icon>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D] tab-content hidden"
                data-tab="إجمالي المصروفات">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">التاريخ والوقت</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">بند الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-1053</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">20 أبريل 2026 - 12:30 م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">سداد قسط قرض</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">200 ج .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">دفع بجواب مسبق</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">مصروف</span>
                            </td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl cursor-pointer open-modal"
                                    data-modal="modal3"></iconify-icon>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-1053</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">20 أبريل 2026 - 12:30 م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">سداد قسط قرض</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">200 ج .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">دفع بجواب مسبق</td>
                            <td class="py-3 border-l border-[#6D6D6D] "><span
                                    class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">مصروف</span>
                            </td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl cursor-pointer open-modal"
                                    data-modal="modal3"></iconify-icon>
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

        <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

        <div id="modal1"
            class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-12">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        إضافة إيراد أو مصروف
                    </h1>
                </div>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <button
                            class="modal-btn text-[16px] font-medium py-1 default-btn rounded-[12px]  flex items-center justify-center gap-2 w-full">
                            <iconify-icon icon="iconamoon:trend-up-fill" class="text-3xl mt-1"></iconify-icon>
                            إيراد
                        </button>
                        <button
                            class="modal-btn text-[16px] font-medium py-1  rounded-[12px] default-btn flex items-center justify-center gap-2 w-full">
                            <iconify-icon icon="iconamoon:trend-down-fill" class="text-3xl mt-1"></iconify-icon>
                            مصروف
                        </button>
                    </div>
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <button disabled type="button"
                                class="dropDownBtn drop-down-btn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">بند
                                الحركة :<span class="text-[#021219] ">اختر</span><span
                                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl"></iconify-icon></span></button>
                            <div
                                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 px-5 py-4 rounded-xl navy-shadow w-full">
                                <div class="flex flex-col gap-3 hidden dropdown-group" data-dropdown="مصروف">
                                    <button type="button"
                                        class=" navy-shadow py-2  rounded-xl text-sm font-medium">مصروفات إدارية</button>
                                    <button type="button"
                                        class=" navy-shadow py-2  rounded-xl text-sm font-medium">تكاليف إدارة
                                        استثمارات</button>
                                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">رد
                                        اشتراكات / تسويات</button>
                                </div>
                                <div class="flex flex-col gap-3 hidden dropdown-group" data-dropdown="إيراد">
                                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">عائد
                                        استثمار أموال الصندوق</button>
                                    <button type="button"
                                        class=" navy-shadow py-2  rounded-xl text-sm font-medium">مساهمة الجامعة</button>
                                    <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">موارد
                                        أخرى</button>
                                </div>
                            </div>
                        </div>
                        <div class="relative w-full">
                            <button type="button"
                                class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">طريقة
                                الدفع :<span class="text-[#021219] ">نقدي</span><span
                                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl"></iconify-icon></span></button>
                            <div
                                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                                <button type="button"
                                    class=" navy-shadow py-2  rounded-xl text-sm font-medium">نقدي</button>
                                <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">تحويل
                                    بنكي</button>
                                <button type="button"
                                    class=" navy-shadow py-2  rounded-xl text-sm font-medium">شيك</button>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 pt-2">
                        <div class="relative w-full">
                            <label
                                class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">المبلغ<span
                                    class="text-[#D92D20]">*</span></label>
                            <input type="text" placeholder="مثال : 500 ج.م"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full text-center rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] py-2">
                        </div>
                        <div class="w-full">
                            <p class="text-[#124375] text-[16px] font-semibold">تاريخ الحركة : <span
                                    class="text-[#021219]">20 أبريل 2026</span></p>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="relative w-full">
                            <label
                                class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">بيان
                                الحركة<span class="text-[#D92D20]">*</span></label>
                            <textarea placeholder="مثلا: شراء أحبار لطابعة مكتب الدور الرابع"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] px-2 py-3 resize-none"></textarea>
                        </div>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-1"
                            class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الفاتورة أو الإيصال</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="btns flex gap-4 ">
                    <div class="w-full">
                        <button type="submit"
                            class=" rounded-[14px] w-full py-2 bg-[#124375] navy-shadow text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2">
                            <iconify-icon icon="healthicons:yes" class="text-2xl mt-1"></iconify-icon>
                            حفظ وإضافة
                        </button>
                    </div>
                    <button
                        class="border border-[#124375] w-full rounded-[14px] py-2 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
                </div>
            </div>
        </div>

        <div id="modal2"
            class="hidden w-full max-w-3xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-12 py-4">
                <div class="space-y-7">
                    <div class="flex justify-between items-center">
                        <div class="space-y-2">
                            <h1 class="text-[20px] text-[#124375] font-semibold">تفاصيل الحركة</h1>
                            <div class="flex gap-2 text-[#6D6D6D] text-[16px] font-medium">
                                <p>رقم الحركة : <span>TRX-1051</span></p>
                                <span>/</span>
                                <p> 16 أبريل 2026 - 10:30 صباحاً</p>
                            </div>
                        </div>
                        <div class="flex items-center text-[#F0FFF6] bg-[#019168] gap-2 px-4 py-1 rounded-[10px]">
                            <iconify-icon icon="iconamoon:trend-up-fill" class="text-3xl mt-1"></iconify-icon>
                            <p class="text-[16px] font-medium">إيراد</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div class="flex gap-3">
                            <p class="text-[#124375] text-[16px] font-semibold">اسم العضو : <span
                                    class="text-[#021219]">أحمد محمد</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold">رقم العضوية : <span
                                    class="text-[#021219]">12345687984</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold">المبلغ الإجمالي : <span
                                    class="text-[#021219]">500 ج.م</span></p>
                        </div>
                        <div class="flex gap-3">
                            <p class="text-[#124375] text-[16px] font-semibold">بند الحركة : <span
                                    class="text-[#021219]">رسوم عضوية جديدة</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold">طريقة الدفع : <span
                                    class="text-[#021219]">تحويل بنكي</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold">الموظف المسؤول : <span
                                    class="text-[#021219]">أبراهيم مصطفي</span></p>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="relative w-full">
                            <label
                                class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">بيان
                                الحركة<span class="text-[#D92D20]">*</span></label>
                            <textarea placeholder="مثلا: شراء أحبار لطابعة مكتب الدور الرابع"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] px-2 py-3 resize-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-4 ">
                    <div class="w-full">
                        <button type="submit"
                            class=" rounded-[14px] w-full py-3 bg-[#124375] navy-shadow text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2">
                            <iconify-icon icon="fluent:save-16-filled" class="text-2xl mt-1"></iconify-icon>
                            طباعة الإيصال
                        </button>
                    </div>
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3 bg-[#F4F7F9] navy-shadow text-[#124375] text-base font-medium flex items-center justify-center gap-2">
                        <iconify-icon icon="material-symbols:download-rounded" class="text-2xl mt-1"></iconify-icon>
                        تحميل PDF
                    </button>
                </div>
            </div>
        </div>

        <div id="modal3"
            class="hidden w-full max-w-3xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-12 py-4">
                <div class="space-y-7">
                    <div class="flex justify-between items-center">
                        <div class="space-y-2">
                            <h1 class="text-[20px] text-[#124375] font-semibold">تفاصيل الحركة</h1>
                            <div class="flex gap-2 text-[#6D6D6D] text-[16px] font-medium">
                                <p>رقم الحركة : <span>TRX-1051</span></p>
                                <span>/</span>
                                <p> 16 أبريل 2026 - 10:30 صباحاً</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center text-[#F0FFF6] red-shadow bg-[#D92D20] gap-2 px-3 py-1 rounded-[10px]">
                            <iconify-icon icon="iconamoon:trend-down-fill" class="text-3xl mt-1"></iconify-icon>
                            <p class="text-[16px] font-medium">مصروف </p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div class="flex gap-3">
                            <p class="text-[#124375] text-[16px] font-semibold">اسم العضو : <span
                                    class="text-[#021219]">أحمد محمد</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold">رقم العضوية : <span
                                    class="text-[#021219]">12345687984</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold">المبلغ الإجمالي : <span
                                    class="text-[#021219]">500 ج.م</span></p>
                        </div>
                        <div class="flex gap-3">
                            <p class="text-[#124375] text-[16px] font-semibold">بند الحركة : <span
                                    class="text-[#021219]">رسوم عضوية جديدة</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold">طريقة الدفع : <span
                                    class="text-[#021219]">تحويل بنكي</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold">الموظف المسؤول : <span
                                    class="text-[#021219]">أبراهيم مصطفي</span></p>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="relative w-full">
                            <label
                                class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">بيان
                                الحركة<span class="text-[#D92D20]">*</span></label>
                            <textarea placeholder="مثلا: شراء أحبار لطابعة مكتب الدور الرابع"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] px-2 py-3 resize-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-4 ">
                    <div class="w-full">
                        <button type="submit"
                            class=" rounded-[14px] w-full py-3 bg-[#124375] navy-shadow text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2">
                            <iconify-icon icon="fluent:save-16-filled" class="text-2xl mt-1"></iconify-icon>
                            طباعة الإيصال
                        </button>
                    </div>
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3 bg-[#F4F7F9] navy-shadow text-[#124375] text-base font-medium flex items-center justify-center gap-2">
                        <iconify-icon icon="material-symbols:download-rounded" class="text-2xl mt-1"></iconify-icon>
                        تحميل PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('JS/employee/finance.js') }}"></script>
@endsection
