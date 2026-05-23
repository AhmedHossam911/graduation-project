@extends('layouts.pages')

@section('title', 'عرض بيانات العضو')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/member.css') }}">

    <!-- start header -->
    <div class="flex justify-between items-center px-10 py-5">
        <div class="flex flex-col gap-4">
            <div class="flex gap-7 items-center ">
                <p class="text-[28px] font-semibold text-[#124375]">أحمد محمد</p>
                <p class="mt-3 status bg-[#ECFDF3] text-[#067647] rounded-lg px-10 border border-[#067647] ">نشط</p>
            </div>
            <P class="text-[#021219] text-sm font-medium flex items-center gap-4">رقم العضوية : <span
                    class="text-[#124375] font-semibold text-xl">123456789</span></P>
        </div>
        <div class="space-y-2 mt-3">
            <button data-modal="modal-edit"
                class="open-modal flex items-center justify-center navy-shadow bg-[#124375] text-[#FEFFFC] rounded-xl gap-2 w-full  py-3 ">
                <iconify-icon icon="ic:round-edit" class="mt-1 text-xl"></iconify-icon> تعديل بيانات
            </button>
            <button data-modal="modal1"
                class="flex open-modal items-center red-shadow bg-[#F4F7F9] text-[#D92D20] rounded-xl gap-2 px-20 py-3 border border-[#D92D20]">
                <iconify-icon icon="carbon:close-filled" class="mt-1 text-xl"></iconify-icon> إيقاف العضوية
            </button>
        </div>
    </div>
    <!-- end header -->
    <hr class="border border-[#124375] mx-7 my-2">

    <!-- start personal informationn -->
    <section class="py-5 px-7">
        <div class="personal-info relative border border-[#124375] rounded-[20px]">
            <h2 class="absolute text-[#124375] px-1 right-3 top-[-15px] text-lg font-medium bg-[#F4F7F9] ">
                البيانات الشخصية
            </h2>
            <div class="information py-7 px-7">
                <form class="space-y-5">
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الأسم
                                كامل</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="احمد محمد ابراهيم خليل">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الرقم
                                القومي</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="12345678912345">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">رقم
                                الهاتف</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="01234567891">
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الوظيفة</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="دكتور في التربية النوعية">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">جهة
                                العمل</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="كلية التربية -جامعة حلوان">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">البريد
                                الإلكتروني</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="ahmed@gmail.com">
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">تاريخ
                                الانضمام</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="22 أكتوبر 2026">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الحالة
                                الوظيفية</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="موظف بالجامعة">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الراتب
                                الأساسي (في 1/7/2014)</label>
                            <input type="text" disabled
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="416 ج.م">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- end personal information -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>
    <!-- suspension of membership MODAL -->
    <form>
        <div id="modal1"
            class="modal hidden max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded m-4 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body px-6 space-y-7">
                <div class="moda-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        تأكيد إيقاف العضوية
                    </h1>
                </div>
                <div class="reason space-y-3">
                    <p class="mr-1 text-[#021219] font-medium ">لإيقاف العضوية، يرجى توضيح سبب الرفض وإرفاق صورة من طلب
                        إيقاف العضوية الموقَّع من العضو أو قرار صندوق الزمالة برفض العضوية.</p>
                    <textarea required rows="4"
                        class="resize-none bg-[#F4F7F9] w-full border border-[#124375] rounded-xl outline-none px-2"
                        placeholder="سبب إيقاف العضوية"></textarea>
                </div>
                <div class="document border border-[#124375] rounded-xl text-center">
                    <label for="suspension-file"
                        class="w-full cursor-pointer  py-6 text-[#124375] flex items-center justify-center gap-1">
                        <p>اضغط لإرفاق صورة الملف</p>
                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                        <input type="file" id="suspension-file" class="hidden">
                    </label>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class=" rounded-[14px] w-full py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">إيقاف
                            العضوية</button>
                    </div>
                    <button
                        class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
                </div>
            </div>
        </div>
    </form>
    <!-- end suspension of membership MODAL -->

    <!-- tabs -->
    <section class="px-7">
        <div class="flex items-center justify-between border border-[#124375] p-3 rounded-xl">
            <div class="tabs flex gap-2">
                <button
                    class="tab text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">الاشتراكات</button>
                <button
                    class="tab text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">قروض</button>
                <button
                    class="active-tab text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">مطالبات</button>
            </div>
            <div>
                <!-- requests only -->
                <div class="tab-content relative" data-tab="مطالبات">
                    <button
                        class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base navy-shadow flex gap-3">نوع
                        المطالبة : <span class="text-[#021219]">أختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow max-w-fit">
                        <a class="button cursor-pointer text-center navy-shadow py-2 px-1 rounded-xl text-base ">بلوغ سن
                            التقاعد القانوني</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">نقل</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base "> وفاة</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">
                            استقالة</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">معاش
                            مبكر</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">انسحاب</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">فصل</a>
                        <a class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">عجز
                            مهني</a>
                    </div>
                </div>
                <!-- requests only -->
                <!-- loans only -->
                <button
                    class="tab-content hidden flex gap-3 py-3 px-20 rounded-[12px] justify-center items-center text-[#F4F7F9] text-[16px] font-medium bg-[#124375]"
                    data-tab="قروض">
                    <iconify-icon icon="ic:baseline-plus" class="flex items-center text-2xl"></iconify-icon>
                    طلب قرض
                </button>
                <div class="tab-content flex gap-3 hidden" data-tab="قروض">
                            <button  data-modal="modal6" class="open-modal text-[16px] font-medium  w-52  bg-[#124375] navy-shadow text-[#F4F7F9] py-2 rounded-[12px]">
                                تسديد القرض بالكامل
                            </button>
                            <button class="flex w-52 text-[16px] font-medium items-center justify-center gap-2 bg-[#F4F7F9] navy-shadow py-2 rounded-[12px] text-[#124375]">
                                <iconify-icon icon="material-symbols:print" class="text-xl flex items-center"></iconify-icon>
                                طباعة التفاصيل للمجلس
                            </button>
                            <button data-modal="modal3" class="open-modal text-[16px] font-medium bg-[#124375] w-52 py-2 rounded-[12px] text-[#F4F7F9] navy-shadow">
                                بدء القرض
                            </button>
                            <a href="../previousLoansPage/previousLoans.html" class="text-center text-[16px] font-medium bg-[#F4F7F9] w-52 navy-shadow py-2 rounded-[12px] text-[#124375]">
                                عرض القروض السابقة
                            </a>
                            <button data-modal="modal4" class="open-modal flex text-[16px] font-medium items-center w-52 justify-center gap-2 border-2 border-[#D92D20] red-shadow text-[#D92D20] py-2 rounded-[12px]">
                                <iconify-icon icon="zondicons:close-solid" class="text-xl flex items-center"></iconify-icon>
                                إلغاء أو رفض الطلب
                            </button>
                        </div> 
                <!-- end loans only -->
                <div class="tab-content hidden flex gap-2" data-tab="الاشتراكات">
                    <button
                        class=" flex gap-3 py-3 px-12 rounded-[12px] justify-center items-center border border-[#F79009] text-[#F79009] text-[16px] font-medium bg-[#FFF7ED]">
                        <iconify-icon icon="fluent:mail-warning-24-filled"
                            class="flex items-center text-2xl"></iconify-icon>
                        إرسال إخطار مسجل بعلم الوصول
                    </button>
                    <a href="../DocumentsPage/documents.html"
                        class=" flex gap-3 py-3 px-20 rounded-[12px] justify-center items-center text-[#124375] text-[16px] font-medium bg-[#F4F7F9] navy-shadow">
                        <iconify-icon icon="mdi:file-account" class="flex items-center text-2xl"></iconify-icon>
                        عرض المستندات
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- end tabs -->

    <!-- start requests section -->
    <section class="px-7 py-5">
        <!-- no requests -->
        <!-- hidden -->
        <div class="no-requests flex justify-center py-14 hidden">
            <div class="flex flex-col items-center gap-5">
                <img src="../IMGs/no-requests.png" alt="no-requests">
                <p>لم يتم إضافة مطالبة حتي الآن</p>
            </div>
        </div>
        <!-- end no requests -->
        <!-- requests table -->
        <div class="tab-content requests-table rounded-[14px] overflow-hidden border border-[#D1D5DB]" data-tab="مطالبات">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم المطالبة</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">نوع المطالبة</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">حالة الطلب</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ التقديم</th>
                        <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1234567896451</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">بلوغ سن التقاعد القانوني</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#FFFCEF] text-[#D4AF37] border border-[#D4AF37] px-4 py-1.5 text-sm rounded-lg">بإنتظار
                                الأعتماد</span></td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1 فبراير 2026</td>
                        <td class="py-5"><a href="../paymentApprovalPage/payment.html"
                                class="bg-[#124375] text-white py-3 navy-shadow px-8 rounded-xl font-medium">أعتماد
                                الصرف</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- end requests table -->
    </section>
    <!-- end requests section -->

    <!-- loan table -->
    <div class="tab-content hidden mx-7 rounded-[12px] bg-[#F4F7F9] border-2 border-[#124375] py-3 px-3 my-2"
        data-tab="قروض">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#6D6D6D] text-[14px]">رقم القرض :<span class="text-[16px] text-[#021219]">123456789</span>
                </p>
            </div>
            <div>
                <p class="text-[#6D6D6D] text-[14px]">قيمة القرض :<span class="text-[16px] text-[#021219]">5,000</span>
                </p>
            </div>
            <div>
                <p class="text-[#6D6D6D] text-[14px]">قيمة القرض بالفائدة :<span
                        class="text-[16px] text-[#021219]">5,400</span></p>
            </div>
            <div>
                <p class="text-[#6D6D6D] text-[14px]">إجمالي المتبقي :<span
                        class="text-[16px] text-[#021219]">5,400</span></p>
            </div>
            <div>
                <p class="text-[#6D6D6D] text-[14px]">عدد الأقساط المتبقية :<span class="text-[16px] text-[#021219]">12
                        قسط</span></p>
            </div>
            <div>
                <p class="text-[#6D6D6D] text-[14px]">تاريخ إنتهاء القرض :<span class="text-[16px] text-[#021219]">لم
                        يحدد بعد</span></p>
            </div>
            <div>
                <p class="text-[#6D6D6D] text-[14px]">حالة القرض :<span
                        class="text-[16px] text-[#E6B800] border border-[#E6B800] bg-[#FFF8E1] px-1 rounded-[8px]">تحت
                        المراجعة</span></p>
            </div>
        </div>
    </div>

    <div data-tab="قروض" class="hidden  tab-content px-7 py-5">
        <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم القسط</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الإستحقاق</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ السداد</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">طريقة الدفع</th>
                        <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center border-b border-[#D1D5DB]">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">450</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#ECFDF333] text-[#067647CC] border border-[#067647CC] px-12 py-1  text-sm rounded-lg">مدفوع</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1 فبراير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">أمر دفع من الجامعة</td>
                        <td class="py-5">
                            <div class="text-2xl flex gap-7 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                            </div>
                        </td>
                    </tr>
                    <tr class="text-center border-b border-[#D1D5DB] bg-[#EFEFEF]">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">2</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">450</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-12 py-1  text-sm rounded-lg">مستحق</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1 فبراير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">أمر دفع من الجامعة</td>
                        <td class="py-5">
                            <div>
                                <button data-modal="modal5"
                                    class="open-modal bg-[#124375] text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-2 px-4">
                                    تسجيل السداد
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">3</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">450</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-12 py-1  text-sm rounded-lg">مستحق</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1 فبراير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">أمر دفع من الجامعة</td>
                        <td class="py-5">
                            <div>
                                <button class="bg-[#6D6D6D] text-[16px] text-[#F4F7F9] rounded-[10px] py-2 px-4">
                                    غير متاح حاليًا
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- loan table -->

    <!-- subscription table -->
    <div data-tab="الاشتراكات" class="hidden  tab-content px-7 py-2">
        <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم الأشتراك</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الإستحقاق</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ السداد</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">طريقة الدفع</th>
                        <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center border-b border-[#D1D5DB]">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">السنة الأولي</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">450</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#ECFDF333] text-[#067647CC] border border-[#067647CC] px-12 py-1  text-sm rounded-lg">مدفوع</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1 فبراير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">أمر دفع من الجامعة</td>
                        <td class="py-5">
                            <div class="text-2xl flex gap-7 items-center justify-center text-[#124375]">
                                <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                            </div>
                        </td>
                    </tr>
                    <tr class="text-center border-b border-[#D1D5DB] bg-[#EFEFEF]">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">السنة الأولي</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">450</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-12 py-1  text-sm rounded-lg">مستحق</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1 فبراير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">أمر دفع من الجامعة</td>
                        <td class="py-5">
                            <div>
                                <button data-modal="modal7"
                                    class="open-modal bg-[#124375] text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-2 px-4">
                                    تسجيل السداد
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">السنة الأولي</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">450</td>
                        <td class="py-4 border-l border-[#D1D5DB]"><span
                                class="bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] px-12 py-1  text-sm rounded-lg">متأخر</span>
                        </td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1 فبراير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">1يناير 2026</td>
                        <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">أمر دفع من الجامعة</td>
                        <td class="py-5">
                            <div>
                                <button class="bg-[#6D6D6D] text-[16px] text-[#F4F7F9] rounded-[10px] py-2 px-4">
                                    غير متاح حاليًا
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- end subscription table -->


    <!-- loan request form -->
    <div class="modal tab-content hidden mx-7  rounded-2xl bg-[#F4F7F9] border border-[#124375] py-3" data-tab="قروض">
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    طلب تسجيل قرض
                </h1>
            </div>
            <div class="requirements grid grid-cols-2 gap-2">
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">قيمة
                        القرض :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base ">5,000</a>
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base ">10,000</a>
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base ">20,000</a>
                    </div>
                </div>
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">مدة
                        السداد :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base ">12 شهر</a>
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base ">24 شهر</a>
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base ">32 شهر</a>
                    </div>
                </div>
            </div>
            <div class="declaration space-y-3">
                <h3 class="text-center font-medium">
                    إقرار
                </h3>
                <p class="font-medium">
                    أقر أنا / ____________________ برغبتي في الحصول على القرض الموضح بياناته أعلاه من صندوق الزمالة
                    الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان. وأتعهد بالالتزام بكافة الشروط والأحكام،
                    كما أفوض الإدارة المالية بالجامعة بخصم قيمة الأقساط الشهرية من راتبي أو من أي مستحقات مالية أخرى لي،
                    وذلك حتى يتم سداد كامل قيمة القرض.
                </p>
                <p class="font-medium">
                    تحريراً في: _____ / _____ /_____ ٢٠ م
                </p>
                <p class="font-medium">
                    الاسم / ____________________________ الوظيفة / ____________________________
                </p>
                <p class="font-medium">
                    الرقم القومي / ____________________________ التوقيع / ____________________________
                </p>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button data-modal="modal2"
                        class="open-modal rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="material-symbols:print"
                                class="flex items-center text-2xl"></iconify-icon></span>تقديم الطلب</button>
                </form>
                <button
                    class="close-loan-request-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- end loan request form -->

    <!-- Attach the signed declaration modal -->
    <div id="modal2"
        class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="close-btn text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-5">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    إرفاق الإقرار المُوَقَّع
                </h1>
            </div>
            <div class="documents space-y-5">
                <p class="text-[#021219] text-[16px] font-medium">يرجى رفع ملف الإقرار بعد طباعته وتوقيعه.</p>
                <div class="border border-[#124375] rounded-[12px] ">
                    <label for="file-1"
                        class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                        <p>اضغط هنا لإرفاق ملف الإقرار</p>
                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                        <input type="file" id="file-1" class="hidden">
                    </label>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        وإرسال</button>
                </form>
            </div>
        </div>
    </div>
    <!-- end Attach the signed declaration modal -->


    <!-- request form -->
    <div class="tab-content modal-body space-y-7 px-5" data-tab="مطالبات">
        <div class="modal-title text-center">
            <h1 class="text-xl font-semibold text-[#124375]">
                مطالبة ببلوغ سن التقاعد القانوني
            </h1>
        </div>
        <div class="space-y-3">
            <div class="flex gap-4 ">
                <p class="text-[#124375] text-base font-medium">الأسم : <span
                        class="text-[#021219] text-base font-semibold">أحمد محمد</span></p>
                <p class="text-[#124375] text-base font-medium">رقم العضوية : <span
                        class="text-[#021219] text-base font-semibold">123456789</span></p>
                <p class="text-[#124375] text-base font-medium">الرقم القومي : <span
                        class="text-[#021219] text-base font-semibold">12345678901234</span></p>
                <p class="text-[#124375] text-base font-medium">رقم المطالبة : <span
                        class="text-[#021219] text-base font-semibold">123456789</span></p>
            </div>
            <p>يرجى إرفاق المستندات التالية لإتمام طلب الإحالة إلى المعاش واستلام المستحقات.</p>
        </div>
        <div class="documents grid grid-cols-2 gap-y-5 gap-x-4">
            <!-- common inputs -->
            <!-- خطاب بالمرتب الاساسي مش موجود في النقل -->
            <!-- بطاقة الرقم القومي مش موجودة في الوفاة  -->
            <!-- قرار الاحالة للمعاش مش موجودة في النقل و الوفاة  -->
            <!-- التوقيع في الوفاة هيبقي توقيع الوريث بدل العضو -->
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                    بالمرتب الأساسي<span class="text-[#D92D20]">*</span></span>
                <label for="file-2" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-2" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان
                    بالمبالغ المخصومة<span class="text-[#D92D20]">*</span></span>
                <label for="file-3" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-3" class="hidden" required>
                </label>
            </div>


            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                    بتاريخ التعيين<span class="text-[#D92D20]">*</span></span>
                <label for="file-4" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-4" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه
                    الرقم القومي<span class="text-[#D92D20]">*</span></span>
                <label for="file-5" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-5" class="hidden" required>
                </label>
            </div>


            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    قرار الإحالة للمعاش<span class="text-[#D92D20]">*</span></span>
                <label for="file-6" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-6" class="hidden" required>
                </label>
            </div>

            <div class="relative border border-[#6D6D6D] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#6D6D6D] font-medium bg-[#F4F7F9]">توقيع
                    العضو بصرف مستحقاته</span>
                <label for="file-7" class=" cursor-pointer py-3  text-[#6D6D6D] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-7" class="hidden">
                </label>
            </div>
            <!-- end common inputs -->
            <!-- موجودين في النقل بس  -->
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    إخلاء طرف<span class="text-[#D92D20]">*</span></span>
                <label for="file-8" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-8" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    قرار النقل <span class="text-[#D92D20]">*</span></span>
                <label for="file-9" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-9" class="hidden">
                </label>
            </div>
            <!-- كده النقل خلص -->
            <!-- دول في الوفاة بس -->
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    قرار إنهاء الخدمة <span class="text-[#D92D20]">*</span></span>
                <label for="file-10" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-10" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    شهادة الوفاة <span class="text-[#D92D20]">*</span></span>
                <label for="file-11" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-11" class="hidden">
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    بطاقة الرقم القومي للورثة المستحقين<span class="text-[#D92D20]">*</span></span>
                <label for="file-12" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-12" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    إعلام الوراثة الشرعي <span class="text-[#D92D20]">*</span></span>
                <label for="file-13" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-13" class="hidden">
                </label>
            </div>
            <div class="col-span-2">
                <div class="flex justify-between">
                    <div>
                        <p class="text-base font-medium text-[#124375]">هل يوجد قصر ؟ <span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="flex gap-3">
                        <label for="yes" class="cursor-pointer flex items-center gap-3">
                            <input type="radio" name="answer" id="yes" class="hidden peer" required>
                            <span
                                class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                            <span>نعم</span>
                        </label>
                        <label for="no" class="cursor-pointer flex items-center gap-3">
                            <input type="radio" name="answer" id="no" class="hidden peer" required>
                            <span
                                class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                            <span>لا</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    قرار الوصاية في حالة وجود قصر<span class="text-[#D92D20]">*</span></span>
                <label for="file-14" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-14" class="hidden" required>
                </label>
            </div>
            <div class="relative border border-[#124375] rounded-2xl w-full">
                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                    شهادات ميلاد القصر بالرقم القومي<span class="text-[#D92D20]">*</span></span>
                <label for="file-15" class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" id="file-15" class="hidden">
                </label>
            </div>

            <!-- كده الوفاة خلصت -->
        </div>
        <div class="declaration space-y-3">
            <h3 class="text-center font-medium">
                إقرار
            </h3>
            <p class="font-medium">أقر أنا / __________ بأنني قد قمت باستلام كافة مستحقاتي من صندوق الزمالة الخاص بأعضاء
                هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان،
                وذلك اعتبارًا من تاريخ _____ / _____ / ________، وأقر بعدم أحقيتي في المطالبة بأي مستحقات أخرى بعد هذا
                التاريخ
            </p>
            <p class="font-medium">
                الاسم / _______________ الوظيفة / _______________ الرقم القومي / ____________________ التوقيع /
                ________________
            </p>
        </div>
        <div class="btns flex gap-2 ">
            <form class="w-full">
                <button type="button"
                    class="submit-btn rounded-[14px] w-full py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2"><span><iconify-icon
                            icon="material-symbols:print" class="flex items-center text-2xl"></iconify-icon></span>
                    طباعة الإقرار</button>
            </form>
            <button
                class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
        </div>
    </div>
    <!-- end request form -->


    <!-- second step of request form -->
    <div
        class="request-modal-2 hidden w-full max-w-5xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="close-btn text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-5">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    مطالبة ببلوغ سن التقاعد القانوني
                </h1>
            </div>
            <div class="space-y-3">
                <div class="flex gap-4 ">
                    <p class="text-[#124375] text-base font-medium">الأسم : <span
                            class="text-[#021219] text-base font-semibold">أحمد محمد</span></p>
                    <p class="text-[#124375] text-base font-medium">رقم العضوية : <span
                            class="text-[#021219] text-base font-semibold">123456789</span></p>
                    <p class="text-[#124375] text-base font-medium">الرقم القومي : <span
                            class="text-[#021219] text-base font-semibold">12345678901234</span></p>
                    <p class="text-[#124375] text-base font-medium">رقم المطالبة : <span
                            class="text-[#021219] text-base font-semibold">123456789</span></p>
                </div>
                <p>يرجى إرفاق المستندات التالية لإتمام طلب الإحالة إلى المعاش واستلام المستحقات.</p>
            </div>
            <div class="documents space-y-5">
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                            بالمرتب الأساسي</span>
                        <label for="file-1"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                            قرار الإحالة للمعاش</span>
                        <label for="file-16"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-16" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان
                            بالمبالغ المخصومة</span>
                        <label for="file-17"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-17" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                            بتاريخ التعيين</span>
                        <label for="file-18"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-18" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه
                            الرقم القومي</span>
                        <label for="file-19"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-19" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">توقيع
                            العضو بصرف مستحقاته <span class="text-[#D92D20]">*</span></span>
                        <label for="file-20"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-20" class="hidden" required>
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تقديم
                        المطالبة</button>
                </form>
                <button
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- end second step of request form -->

    <!-- start loan modal -->

    <div id="modal3"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    بدء القرض
                </h1>
            </div>
            <div class="space-y-4">
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات العضو
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">اسم العضو :<span
                                class="text-[#021219] font-semibold text-base">أحمد محمد</span></p>
                        <p class="text-base font-medium text-[#124375]">رقم القرض :<span
                                class="text-[#021219] font-semibold text-base">512345678922</span></p>
                        <p class="text-base font-medium text-[#124375]">قيمة القرض :<span
                                class="text-[#021219] font-semibold text-base">5,000 جنيه</span></p>
                    </div>
                </div>
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات القرض
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">المبلغ المتبقي :<span
                                class="text-[#021219] font-semibold text-base">4500 ج.م</span></p>
                        <p class="text-base font-medium text-[#124375]">عدد الأقساط :<span
                                class="text-[#021219] font-semibold text-base">12 شهر</span></p>
                        <p class="text-base font-medium text-[#124375]">تاريخ بداية القرض :<span
                                class="text-[#021219] font-semibold text-base">1 فبراير 2026</span></p>
                    </div>
                </div>
                <div class="flex flex-col gap-5">
                    <div>
                        <p class="text-[16px] font-medium text-[#021219]">
                            سيتم بدء القرض واحتساب الأقساط اعتبارًا من تاريخ اليوم.
                        </p>
                        <p class="text-[16px] font-medium text-[#021219]">
                            يرجى إدخال رقم الشيك وإرفاق المستندات المطلوبة قبل التأكيد.
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-21"
                            class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الشيك</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-21" class="hidden">
                        </label>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-22"
                            class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة موافقة المجلس</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-22" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        بدء القرض</button>
                </form>
            </div>
        </div>
    </div>


    <div id="modal4"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تأكيد إلغاء الطلب
                </h1>
            </div>
            <div class="space-y-4">
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات القرض
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">قيمة القرض :<span
                                class="text-[#021219] font-semibold text-base">5,000 جنيه</span></p>
                        <p class="text-base font-medium text-[#124375]">رقم القرض :<span
                                class="text-[#021219] font-semibold text-base">123456789</span></p>
                        <p class="text-base font-medium text-[#124375]">تاريخ تقديم الطلب :<span
                                class="text-[#021219] font-semibold text-base">1 يناير 2027</span></p>
                    </div>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-3">
                        <p class="text-[16px] font-medium text-[#021219]">
                            يرجى تحديد سبب عدم استكمال طلب القرض:
                        </p>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-2 w-fit cursor-pointer">
                                <input type="radio" name="reason" class="peer hidden">
                                <span
                                    class="mt-2 w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                <span class=" text-[16px] font-normal text-[#021219]">إلغاء بناءً على رغبة العضو</span>
                            </label>
                            <label class="flex items-center gap-2 w-fit cursor-pointer">
                                <input type="radio" name="reason" class="peer hidden">
                                <span
                                    class="mt-2 w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                <span class=" text-[16px] font-normal text-[#021219]">رفض الطلب بواسطة المجلس</span>
                            </label>
                        </div>
                    </div>
                    <div class="reason">
                        <textarea required rows="4"
                            class="resize-none bg-[#F4F7F9] w-full border border-[#0A2A4E] rounded-xl outline-none px-2 p-3 placeholder:text-[#0A2A4E]"
                            placeholder="سبب الإلغاء أو رقم المجلس الذي تم فيه الرفض"></textarea>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">تأكيد
                        إلغاء القرض</button>
                </div>
                <button
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
            </div>
        </div>
    </div>

    <div id="modal5"
        class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد قسط قرض
                </h1>
            </div>
            <div class="space-y-7">
                <div>
                    <p>يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.</p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class=" border border-[#124375] rounded-2xl w-full">
                        <label for="file-23"
                            class=" cursor-pointer py-10  text-[#6D6D6D] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-23" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        السداد</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal6"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-7">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد مبكر لأقساط القرض
                </h1>
            </div>
            <div class="space-y-7">
                <div class="flex flex-col gap-3">
                    <p class="text-[18px] font-medium text-[#021219]">سيتم إنهاء القرض بالكامل، وسيتم احتساب جميع
                        الأقساط المتبقية كمدفوعة.</p>
                    <p class="text-[18px]">المبلغ المطلوب للسداد الكامل : <span class="font-medium text-[#124375]">
                            2,000 </span> جنيه</p>
                    <p class="text-[16px] font-medium text-[#021219]">يرجى إرفاق رقم و صورة الإيصال السداد لإتمام
                        العملية.</p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class=" border border-[#124375] rounded-2xl w-full">
                        <label for="file-24"
                            class=" cursor-pointer py-10  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-24" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        السداد</button>
                </form>
            </div>
        </div>
    </div>

    <div id="modal7"
        class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد الاشتراك
                </h1>
            </div>
            <div class="space-y-7">
                <div>
                    <p>يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.</p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class=" border border-[#124375] rounded-2xl w-full">
                        <label for="file-25"
                            class=" cursor-pointer py-10  text-[#6D6D6D] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-25" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        السداد</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-edit"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تعديل بيانات
                </h1>
            </div>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <p class="text-base font-medium text-[#124375]">الأسم : <span
                            class="text-[#021219] font-semibold text-base">أحمد محمد</span></p>
                    <p class="text-base font-medium text-[#124375]">رقم العضوية : <span
                            class="text-[#021219] font-semibold text-base">123456789</span></p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-5">
                        <p class="text-[14px] text-[#021219]">
                            يمكنك تحديث البيانات الموضحة أدناه فقط. يرجى التأكد من دقة المعلومات قبل الحفظ.
                        </p>
                        <div class="flex flex-col gap-4">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                    التليفون</label>
                                <input type="text" placeholder="01234595684"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البريد
                                    الإلكتروني</label>
                                <input type="text" placeholder="ahmed@gmail.com"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الحالة
                                    الوظيفية</label>
                                <input type="text" placeholder="معيد بالجامعة"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">عنوان
                                    محل الإقامة</label>
                                <input type="text" placeholder="العنوان الحالي بالتفصيل"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الراتب
                                    الأساسي</label>
                                <input type="text" placeholder="514 ج.م"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3 bg-[#124375] text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2"><iconify-icon
                            icon="fluent:save-16-filled" class="text-2xl mt-1"></iconify-icon>حفظ التعديلات</button>
                </div>
                <button
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] modal-close">إلغاء</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('JS/member.js') }}"></script>
@endsection
