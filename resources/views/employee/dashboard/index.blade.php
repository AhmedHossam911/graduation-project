@extends('layouts.app')

@section('title', 'الصفحة الرئيسية')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/employee/dashboard.css') }}">

    <div class="flex ">

        <!-- start main -->
        <main class="flex-1 py-5 px-3">
            <!-- start header main -->
            <div class="flex flex-col gap-2">
                <h2 class="text-[#021219] text-xl font-semibold"> مرحباً ، <span></span></h2>
                <p class="text-[#6D6D6D] text-base font-normal">
                    نظام إدارة الصندوق – لوحة الموظف
                </p>
            </div> <!-- end header main -->

            <!-- start search -->
            <div class="flex items-center gap-5 mt-6 ">
                <input type="search" placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض"
                    class="w-full  rounded-xl py-2 pr-2 surface-shadow outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
                <button class="bg-[#124375] text-white rounded-xl px-7 surface-shadow">
                    <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl"></iconify-icon>
                </button>
            </div>
            <!-- end search -->

            <!-- start cards -->
            <div class="py-4 grid grid-cols-4 gap-4">
                <div
                    class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
                    <div>
                        <iconify-icon icon="mdi:account-group"
                            class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
                    </div>
                    <div class="flex flex-col items-center text-[#124375] gap-2">
                        <p class="text-4xl font-extrabold">9</p>
                        <p class="text-sm font-medium">عدد الأعضاء النشطين</p>
                    </div>
                </div>
                <div
                    class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
                    <div>
                        <iconify-icon icon="mdi:account-file"
                            class="surface-shadow text-4xl text-[#D4AF37] bg-[#FFFCEF] rounded-lg px-2 py-1"></iconify-icon>
                    </div>
                    <div class="flex flex-col items-center text-[#124375] gap-2">
                        <p class="text-4xl font-extrabold">0</p>
                        <p class="text-sm font-medium">اشتراكات اليوم</p>
                    </div>
                </div>
                <div
                    class="surface-shadow flex  items-center justify-center gap-4 bg-[#124375] rounded-xl px-4 py-4 border-s-8 border-[#EEF7FF]">
                    <div>
                        <iconify-icon icon="material-symbols:assignment-late"
                            class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
                    </div>
                    <div class="flex flex-col items-center text-[#F4F7F9] gap-2">
                        <p class="text-4xl font-extrabold">3</p>
                        <p class="text-sm font-medium">أقساط مستحقة اليوم</p>
                    </div>
                </div>
                <div
                    class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
                    <div>
                        <iconify-icon icon="mdi:account-convert"
                            class="surface-shadow text-4xl text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
                    </div>
                    <div class="flex flex-col items-center text-[#124375] gap-2">
                        <p class="text-4xl font-extrabold">5</p>
                        <p class="text-sm font-medium">طلبات تحت المراجعة</p>
                    </div>
                </div>
            </div>
            <!-- end cards -->

            <!-- start tasks -->
            <div class="py-5 grid grid-cols-3 gap-7">
                <div class="col-span-2 space-y-5">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="material-symbols:edit-notifications-rounded" class="text-2xl"></iconify-icon>
                        <h2 class="text-base font-medium">المهام المطلوبة اليوم <span class="text-[#124375]">(3)</span></h2>
                    </div>
                    <div class="py-2 surface-shadow rounded-2xl py-4 px-5 divide-y-2 divide-[#6D6D6D]">
                        <div class="flex justify-between py-5">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#175CD3]"></iconify-icon>
                                <div>
                                    <h3 class="text-[#021219] text-sm font-medium">اشتراك مستحق اليوم</h3>
                                    <p class="text-[#6D6D6D] text-sm font-normal">غير مسجل بعد</p>
                                </div>
                            </div>
                            <button
                                class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3">عرض
                                التفاصيل</button>
                        </div>
                        <div class="flex justify-between py-5">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#D92D20]"></iconify-icon>
                                <div>
                                    <h3 class="text-[#021219] text-sm font-medium">قسط متأخر</h3>
                                    <p class="text-[#6D6D6D] text-sm font-normal">متأخر منذ 4 ايام</p>
                                </div>
                            </div>
                            <button
                                class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3">عرض
                                التفاصيل</button>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                            <iconify-icon icon="mdi:account-multiple-plus" class="text-5xl text-[#124375]"></iconify-icon>
                            <h3 class="text-base font-medium text-[#124375]">تسجيل عضو جديد</h3>
                        </div>
                        <div
                            class="open-modal cursor-pointer surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                            <iconify-icon icon="material-symbols:list-alt-check-rounded"
                                class="text-5xl text-[#124375]"></iconify-icon>
                            <h3 class="text-base font-medium text-[#124375]">تسجيل سداد إشتراك</h3>
                        </div>
                        <div
                            class="open-modal cursor-pointer surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                            <iconify-icon icon="ion:cash" class="text-5xl text-[#124375]"></iconify-icon>
                            <h3 class="text-base font-medium text-[#124375]">تسجيل سداد قسط</h3>
                        </div>
                        <div
                            class="open-modal cursor-pointer surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                            <iconify-icon icon="mdi:account-file" class="text-5xl text-[#124375]"></iconify-icon>
                            <h3 class="text-base font-medium text-[#124375]">إنشاء مطالبة</h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tasks -->

            <!-- start table -->
            <section>
                <div class="flex items-center gap-2 py-3">
                    <iconify-icon icon="bxs:error" class="text-2xl"></iconify-icon>
                    <h3 class="text-base font-medium ">
                        تنبيهات التأخير العاجلة
                    </h3>
                </div>
                <div class="rounded-2xl overflow-hidden text-center surface-shadow">
                    <img src="{{ asset('IMGs/no-members-alert.png') }}" alt="no alerts" style="width: 200px"
                        class="mx-auto py-10">
                    <p class="text-base font-medium text-[#124375]">
                        ممتاز! لا يوجد أعضاء متأخرين يستوجب إنذارهم حالياً.
                    </p>
                </div>
                <div class="rounded-2xl overflow-hidden  surface-shadow">
                    <table class="w-full">
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D]">رقم العضوية</th>
                            <th class="py-3 border-l border-[#6D6D6D]">المبلغ المستحق</th>
                            <th class="py-3 border-l border-[#6D6D6D]">مدة التأخير</th>
                        </tr>
                        <tr class="text-center">
                            <td class="py-3 border-l border-[#6D6D6D]"></td>
                            <td class="py-3 border-l border-[#6D6D6D]"></td>
                            <td class="py-3 border-l border-[#6D6D6D]"></td>
                            <td class="py-3 border-l border-[#6D6D6D]"></td>
                        </tr>
                    </table>
                </div>
            </section>
        </main>
    </div>
    <!-- end table -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

    <!-- تسجيل سداد اشتراك -->
    <div
        class="modal hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد إشتراك
                </h1>
            </div>
            <div class="space-y-4">
                <!-- <div class="flex gap-3 items-center">
                                                                                            <p class="text-base font-medium text-[#124375]">اسم العضو :<span class="text-[#021219] font-semibold text-base">أحمد محمد</span></p>
                                                                                            <p class="text-base font-medium text-[#124375]">رقم القرض :<span class="text-[#021219] font-semibold text-base">512345678922</span></p>
                                                                                            <p class="text-base font-medium text-[#124375]">المبلغ المتبقي :<span class="text-[#021219] font-semibold text-base">4500 ج.م</span></p>
                                                                                            <iconify-icon icon="pajamas:redo" class="text-xl bg-[#124375] text-[#F4F7F9] rounded-[8px] py-1.5 px-2"></iconify-icon>
                                                                                        </div> -->
                <div class="flex items-center justify-between gap-4 ">
                    <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
                    <div class="relative flex-1 ">
                        <input type="search" placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي"
                            class="w-full py-2 pr-9 outline-none surface-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                        <iconify-icon icon="mynaui:search"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
                    </div>
                    <button
                        class="bg-[#124375] text-white rounded-xl px-4 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-5">
                        <p class="text-base font-medium text-[#124375]">تاريخ السداد : <span
                                class="text-[#021219] font-semibold text-base">1 إبريل 2026</span></p>
                        <p class="text-base font-medium text-[#124375]">المبلغ : <span
                                class="text-[#021219] font-semibold text-base">4500 ج.م</span></p>
                    </div>
                </div>
                <div class="relative w-fit">
                    <button type="button"
                        class="dropDownBtn surface-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-fit px-2 rounded-xl text-base gap-2 font-medium flex items-center">سداد
                        عن شهر / شهور<span class="text-[#D92D20]">*</span> : <span class="text-[#021219] text-[14px]">اختر
                            الشهر</span> <span class="flex items-center mt-1"><iconify-icon icon="lucide:calendar"
                                class="text-xl "></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute rounded-[10px] bg-[#F4F7F9] surface-shadow z-50 px-5 py-4 space-y-2 left-0 top-full mt-3">
                        <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="يناير 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>يناير 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="فبراير 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>فبراير 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="مارس 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>مارس 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="أبريل 2027">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>أبريل 2027</span>
                        </label>
                    </div>
                </div>
                <div class="relative w-fit">
                    <button type="button"
                        class="dropDownBtn surface-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-fit px-2 rounded-xl text-base gap-2 font-medium flex items-center">طريقة
                        الدفع<span class="text-[#D92D20]">*</span> : <span class="text-[#021219] text-[14px]"> خصم من
                            المرتب</span><span class="flex items-center mt-1"><iconify-icon icon="fe:arrow-down"
                                class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl surface-shadow w-full">
                        <button type="button" class=" surface-shadow py-2  rounded-xl text-sm font-medium">خصم من
                            المرتب</button>
                        <button type="button" class=" surface-shadow py-2 px-1 rounded-xl text-sm font-medium">تحويل
                            بنكي</button>
                        <button type="button" class=" surface-shadow py-2 px-5 rounded-xl text-sm font-medium">دفع بجواب
                            مسبق</button>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <p class="text-[16px] font-medium text-[#021219]">
                        يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.
                    </p>
                    <div class="relative w-full">
                        <label class="px-1 text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-1"
                            class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تسجيل سداد
                        الإشتراك</button>
                </form>
                <button
                    class="close-payment-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- تسجيل سداد اشتراك -->
    <!-- تسجيل سداد قسط -->
    <div
        class="modal hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد قسط
                </h1>
            </div>
            <div class="space-y-4">
                <!-- <div class="flex gap-3 items-center">
                                                                                            <p class="text-base font-medium text-[#124375]">اسم العضو :<span class="text-[#021219] font-semibold text-base">أحمد محمد</span></p>
                                                                                            <p class="text-base font-medium text-[#124375]">رقم القرض :<span class="text-[#021219] font-semibold text-base">512345678922</span></p>
                                                                                            <p class="text-base font-medium text-[#124375]">المبلغ المتبقي :<span class="text-[#021219] font-semibold text-base">4500 ج.م</span></p>
                                                                                            <iconify-icon icon="pajamas:redo" class="text-xl bg-[#124375] text-[#F4F7F9] rounded-[8px] py-1.5 px-2"></iconify-icon>
                                                                                        </div> -->
                <div class="flex items-center justify-between gap-4 ">
                    <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
                    <div class="relative flex-1 ">
                        <input type="search" placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي"
                            class="w-full py-2 pr-9 outline-none surface-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                        <iconify-icon icon="mynaui:search"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
                    </div>
                    <button
                        class="bg-[#124375] text-white rounded-xl px-4 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                    </button>
                </div>
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات السداد
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">المبلغ المدفوع (ج.م) :<span
                                class="text-[#021219] font-semibold text-base">1500</span></p>
                        <p class="text-base font-medium text-[#124375]">تاريخ السداد :<span
                                class="text-[#021219] font-semibold text-base">16 أبريل 2026</span></p>
                    </div>
                </div>
                <div class="relative w-fit">
                    <button type="button"
                        class="dropDownBtn surface-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-fit px-2 rounded-xl text-base gap-2 font-medium flex items-center">سداد
                        عن شهر / شهور<span class="text-[#D92D20]">*</span> : <span class="text-[#021219] text-[14px]">اختر
                            الشهر</span> <span class="flex items-center mt-1"><iconify-icon icon="lucide:calendar"
                                class="text-xl "></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute rounded-[10px] bg-[#F4F7F9] surface-shadow z-50 px-5 py-4 space-y-2 left-0 top-full mt-3">
                        <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="يناير 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>يناير 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="فبراير 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>فبراير 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="مارس 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>مارس 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="أبريل 2027">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>أبريل 2027</span>
                        </label>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <p class="text-[16px] font-medium text-[#021219]">
                        يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.
                    </p>
                    <div class="relative w-full">
                        <label class="px-1 text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-1"
                            class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تسجيل سداد
                        القسط</button>
                </form>
                <button
                    class="close-payment-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- تسجيل سداد قسط -->

    <!-- انشاء مطالبة -->
    <div
        class="modal hidden w-full max-w-4xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    إنشاء المطالبة
                </h1>
            </div>
            <div class="space-y-3">
                <!-- <div class="flex gap-3 items-center">
                                                                                            <p class="text-base font-medium text-[#124375]">الأسم : <span class="text-[#021219] font-semibold text-base">أحمد محمد</span></p>
                                                                                            <p class="text-base font-medium text-[#124375]">رقم العضوية : <span class="text-[#021219] font-semibold text-base">123456789</span></p>
                                                                                            <p class="text-base font-medium text-[#124375]">الرقم القومي : <span class="text-[#021219] font-semibold text-base">12345678901234</span></p>
                                                                                            <p class="text-base font-medium text-[#124375]">رقم القرض : <span class="text-[#021219] font-semibold text-base">123456789</span></p>
                                                                                            <iconify-icon icon="pajamas:redo" class="text-xl bg-[#124375] text-[#F4F7F9] rounded-[8px] py-1.5 px-2"></iconify-icon>
                                                                                        </div> -->
                <div class="flex items-center justify-between gap-4 ">
                    <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
                    <div class="relative flex-1 ">
                        <input type="search" placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي"
                            class="w-full py-2 pr-7 outline-none surface-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                        <iconify-icon icon="mynaui:search"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
                    </div>
                    <button
                        class="bg-[#124375] text-white rounded-xl px-4 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="requirements space-y-5">
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn surface-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">نوع
                        المطالبة <span class="text-[#D92D20]">*</span> : <span class="text-[#021219] ">اختر</span><span
                            class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute z-50 bg-[#F4F7F9] right-3 top-full mt-3 grid grid-cols-4 gap-3 px-3 py-4 rounded-xl surface-shadow ">
                        <button type="button" class=" surface-shadow py-2 rounded-xl text-sm font-medium ">عجز
                            مهني</button>
                        <button type="button" class=" surface-shadow py-2 rounded-xl text-sm font-medium "> وفاة</button>
                        <button type="button" class=" surface-shadow py-2 rounded-xl text-sm font-medium ">نقل</button>
                        <button type="button" class=" surface-shadow py-2 px-2 rounded-xl text-sm font-medium  ">بلوغ سن
                            التقاعد القانوني</button>
                        <button type="button" class=" surface-shadow py-2 rounded-xl text-sm font-medium ">فصل</button>
                        <button type="button"
                            class=" surface-shadow py-2 rounded-xl text-sm font-medium ">انسحاب</button>
                        <button type="button" class=" surface-shadow py-2 rounded-xl text-sm font-medium ">معاش
                            مبكر</button>
                        <button type="button" class=" surface-shadow py-2 rounded-xl text-sm font-medium ">
                            استقالة</button>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        الأختيار</button>
                </form>
                <button
                    class="close-loan-request-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- انشاء مطالبة -->

    <!-- نتائج البحث -->
    <div
        class="modal hidden w-full max-w-4xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    نتائج البحث
                </h1>
            </div>
            <div>
                <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                                <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم العضوية</th>
                                <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                                <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الرقم القومي</th>
                                <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم القرض</th>
                                <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">5123456789</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">روان محمد فتحي</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">12345678912345</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">123456789</td>
                                <td class="py-3  text-[#124375]">
                                    <button>
                                        <iconify-icon icon="solar:eye-linear" class="text-2xl"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('JS/employee/dashboard.js') }}"></script>
@endsection
