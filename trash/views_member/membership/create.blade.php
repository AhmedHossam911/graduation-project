@extends('layouts.pages')
@section('title', 'إستمارة العضوية')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/MembershipForm.css') }}">

    <!-- start Header -->
    <div class="flex justify-between items-center py-5 px-7">
        <div class="logo surface-shadow">
            <img src="../IMGs/Hu Logo 2.png" alt="Logo">
        </div>
        <div class="title text-xl font-semibold text-[#124375]">
            <h1>
                إستمارة العضوية
            </h1>
        </div>
        <div class="btn-close">
            <button class="bg-[#F4F7F9] text-[#D92D20] rounded-2xl py-2 px-12 text-base font-medium">
                إلغاء
            </button>
        </div>
    </div>
    <!-- end Header -->

    <!-- start Form -->
    <!-- start personalData section -->
    <section class="px-6 py-7">
        <div class="personal-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
            <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                البيانات الشخصية
            </h2>
            <div class="space-y-7">
                <!-- START FULL NAME & EMAIL -->
                <div class="flex gap-5">
                    <div class="w-full relative">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]"> الأسم
                            رباعي <span class="text-[#D92D20]">*</span></label>
                        <input type="text" placeholder="مثال : أحمد محمد إسماعيل محمود"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                    <div class="relative w-full">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                            البريد الإلكتروني <span class="text-[#D92D20]">*</span></label>
                        <input type="email" placeholder="ahmed@gmail.com : مثال"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                </div>
                <!-- END FULL NAME & EMAIL -->
                <!-- START LANDLINE & PHONE -->
                <div class="flex gap-6">
                    <div class="phone relative border border-[#124375] rounded-xl flex-1 min-w-0">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                            التليفون <span class="text-[#D92D20]">*</span></label>
                        <div class="flex gap-3 justify-end py-3 px-3">
                            <input type="tel" placeholder="10" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="9" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="8" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="7" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="6" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="5" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="4" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="3" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="2" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="1" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="0" maxlength="1"
                                class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                        </div>
                    </div>
                    <div class="HomePhone relative border border-[#124375]  rounded-xl min-w-0">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                            هاتف المنزل</label>
                        <div class="flex gap-2 justify-end py-3 px-3">
                            <input type="tel" placeholder="7" maxlength="1"
                                class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="6" maxlength="1"
                                class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="5" maxlength="1"
                                class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="4" maxlength="1"
                                class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="3" maxlength="1"
                                class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="2" maxlength="1"
                                class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="1" maxlength="1"
                                class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="0" maxlength="1"
                                class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                        </div>
                    </div>
                </div>
                <!-- END LANDLINE & PHONE -->
                <!-- START DATE OF BIRTH -->
                <div class="flex gap-5">
                    <div class=" relative border border-[#124375] rounded-xl flex-1 min-w-0">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الرقم
                            القومي <span class="text-[#D92D20]">*</span></label>
                        <div class="flex gap-3 justify-end py-3 px-3">
                            <input type="text" placeholder="14" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="13" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="12" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="11" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="10" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="9" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="8" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="7" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="6" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="5" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="4" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="3" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="2" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="1" maxlength="1"
                                class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                        </div>
                    </div>
                    <div class="relative border border-[#124375] rounded-xl  min-w-0">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                            الميلاد <span class="text-[#D92D20]">*</span></label>
                        <div class="flex gap-3 justify-end py-3 px-3">
                            <input type="text" placeholder="اليوم" maxlength="1"
                                class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="الشهر" maxlength="1"
                                class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="السنة" maxlength="1"
                                class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                        </div>
                    </div>
                </div>
                <!-- END LANDLINE & PHONE -->
                <!-- START SOCIAL STATUS & PLACE OF RESIDENCE -->
                <div class="flex gap-5">
                    <div class=" relative flex-1 min-w-0">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">محل
                            الاقامة <span class="text-[#D92D20]">*</span></label>
                        <input type="text" placeholder="كما البطاقة"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                    <div class="border border-[#124375] rounded-xl py-2 w-80 bg-[#F4F7F9] relative">
                        <label
                            class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الحالة
                            الاجتماعية <span class="text-[#D92D20]">*</span></label>
                        <span id="status-text" class="px-3 text-[#6D6D6D] text-sm font-medium">أختر</span>
                        <iconify-icon id="dropdown-btn" icon="oui:arrow-down"
                            class="cursor-pointer absolute left-[5px] top-[12px] text-xl text-[#124375]"></iconify-icon>
                        <!-- Dropdown -->
                        <div
                            class="dropdown hidden absolute top-[calc(100%+8px)]  left-0 w-fit bg-[#F4F7F9] py-4 px-4 rounded-2xl surface-shadow z-50">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                                    <span class="text-xl font-semibold text-[#124375]">متزوج</span>
                                    <input type="radio" name="gender" class="peer hidden" value="متزوج">
                                    <span
                                        class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                </label>
                                <label
                                    class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                                    <span class="text-xl font-semibold text-[#124375]">مطلق</span>
                                    <input type="radio" name="gender" class="peer hidden" value="مطلق">
                                    <span
                                        class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                </label>
                                <label
                                    class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                                    <span class="text-xl font-semibold text-[#124375]">أعزب</span>
                                    <input type="radio" name="gender" class="peer hidden" value="أعزب">
                                    <span
                                        class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                </label>
                                <label
                                    class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                                    <span class="text-xl font-semibold text-[#124375]">أرمل</span>
                                    <input type="radio" name="gender" class="peer hidden" value="أرمل">
                                    <span
                                        class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                </label>
                                <button type="button" id="confirm-status-btn"
                                    class="surface-shadow bg-[#124375] text-white text-lg font-semibold py-3 rounded-xl mt-1 hover:bg-[#0e3560] transition-colors">تأكيد</button>
                            </div>
                        </div>
                        <!-- end dropdown -->
                    </div>
                </div>
                <!-- END SOCIAL STATUS & PLACE OF RESIDENCE -->
            </div>
        </div>
    </section>
    <!-- end PersonalData section -->

    <!-- start FunctionalData section -->
    <section class="px-6">
        <div class="functional-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
            <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البيانات الوظيفية
            </div>
            <div class="flex gap-5">
                <div class="flex-1 min-w-0 space-y-7">
                    <div class="w-full relative">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">جهة
                            العمل <span class="text-[#D92D20]">*</span></label>
                        <input type="text" placeholder="مثال : كلية تجارة و إدارة أعمال"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                    <div class="w-full relative">
                        <label
                            class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الوظيفة
                            <span class="text-[#D92D20]">*</span></label>
                        <input type="text" placeholder="مثال : مدرس مساعد مادة المحاسبة"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الفئة
                                المالية الحالية <span class="text-[#D92D20]">*</span></label>
                            <input type="text" placeholder="مثال : الفئة الثالثة"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                        </div>
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">المرتب
                                الشهري الأساسي عند التعيين <span class="text-[#D92D20]">*</span></label>
                            <input type="text" placeholder="مثال : 360 "
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                        </div>
                    </div>
                </div>
                <div class="space-y-12">
                    <div class="relative border border-[#124375] rounded-xl flex-1 min-w-0">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                            إستلام العمل <span class="text-[#D92D20]">*</span></label>
                        <div class="flex gap-3 justify-end py-3 px-3">
                            <input type="text" placeholder="اليوم" maxlength="1"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="الشهر" maxlength="1"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="السنة" maxlength="1"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                        </div>
                    </div>
                    <div class="relative border border-[#124375] rounded-xl flex-1 min-w-0">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                            الإحالة إلي المعاش <span class="text-[#D92D20]">*</span></label>
                        <div class="flex gap-3 justify-end py-3 px-3">
                            <input type="text" placeholder="اليوم" maxlength="1"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="الشهر" maxlength="1"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="text" placeholder="السنة" maxlength="1"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end FunctionalData section -->

    <!-- start familyData section -->
    <section class="px-6 py-7">
        <div class="family-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
            <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البيانات العائلية
            </div>
            <div class="wrapper space-y-5">
                <div class="flex gap-5">
                    <div class="childern flex-1 min-w-0 relative border border-[#124375]  rounded-xl">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">عدد
                            الأبناء <span class="text-[#D92D20]">*</span></label>
                        <div class="py-3 text-center">
                            <input type="text" placeholder="0"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                        </div>
                    </div>
                    <div class="phone relative border border-[#124375] rounded-xl min-w-0">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                            تليفون الزوج أو الزوجة أو أحد الأبناء أو أحد الأقارب <span
                                class="text-[#D92D20]">*</span></label>
                        <div class="flex gap-2 justify-end py-3 px-3">
                            <input type="tel" placeholder="10" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24  min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="9" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="8" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="7" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="6" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="5" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="4" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="3" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="2" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="1" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            <input type="tel" placeholder="0" maxlength="1"
                                class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                        </div>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="w-full relative">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اسم
                            الزوج أو الزوجة <span class="text-[#D92D20]">*</span></label>
                        <input type="text" placeholder="مثال : رباب عبدالعليم أحمد محمد"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                    <div class="w-full relative">
                        <label
                            class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">وظيفته
                            أو جهة عمله <span class="text-[#D92D20]">*</span></label>
                        <input type="text" placeholder="مثال : محاسبة بشركة الحديد و الصلب"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="w-full relative">
                        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اسم
                            أحد الأبناء <span class="text-[#D92D20]">*</span></label>
                        <input type="text" placeholder="مثال : لا يوجد"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                    <div class="w-full relative">
                        <label
                            class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">وظيفته
                            أو جهة عمله <span class="text-[#D92D20]">*</span></label>
                        <input type="text" placeholder="مثال : لا يوجد"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end familyData section -->

    <!-- start files section -->
    <section class="px-6 ">
        <div class="files rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
            <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                المرفقات
            </h2>
            <div class="wrapper space-y-7">
                <div class="flex gap-5">
                    <div class="w-full relative">
                        <span class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]"> صورة
                            بطاقة الرقم القومي <span class="text-[#D92D20]">*</span></span>
                        <label for="file-1"
                            class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            <span class="text-base file-name">ارفاق المستند المطلوب</span>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                            <input type="file" id="file-1" class="input-file hidden text-[#6D6D6D] font-medium ">
                        </label>
                    </div>
                    <div class="w-full relative">
                        <span class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">طلب
                            تجاوز لفوق سن 21 عاماً <span class="text-[#D92D20]">*</span></span>
                        <label for="file-2"
                            class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            <span class="text-base file-name">ارفاق المستند المطلوب</span>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                            <input type="file" id="file-2" class="input-file hidden text-[#6D6D6D] font-medium ">
                        </label>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="w-full relative">
                        <span class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]"> خطاب
                            الأجر الأساسي <span class="text-[#D92D20]">*</span></span>
                        <label for="file-3"
                            class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            <span class="text-base file-name">ارفاق المستند المطلوب</span>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                            <input type="file" id="file-3" class="input-file hidden text-[#6D6D6D] font-medium ">
                        </label>
                    </div>
                    <div class="w-full relative">
                        <span class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">قرار
                            التعين <span class="text-[#D92D20]">*</span></span>
                        <label for="file-4"
                            class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            <span class="text-base file-name">ارفاق المستند المطلوب</span>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                            <input type="file" id="file-4" class="input-file hidden text-[#6D6D6D] font-medium ">
                        </label>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="w-full relative">
                        <span class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اقرار
                            القيام بالعمل <span class="text-[#D92D20]">*</span></span>
                        <label for="file-5"
                            class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            <span class="text-base file-name">ارفاق المستند المطلوب</span>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                            <input type="file" id="file-5" class="input-file hidden text-[#6D6D6D] font-medium ">
                        </label>
                    </div>
                    <div class="w-full relative">
                        <span
                            class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الاستمارة
                            بعد التوقيع</span>
                        <label for="file-6"
                            class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            <span class="text-base file-name">ارفاق المستند المطلوب</span>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                            <input type="file" id="file-6" class="input-file hidden text-[#6D6D6D] font-medium">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end files section -->

    <!-- start declaration section -->
    <section class="px-6 py-14">
        <h3 class=" text-base font-medium text-end">
            توقيع طالب العضوية بصحة البيانات
        </h3>
        <div class="mt-8 space-y-7 font-medium">
            <h4 class="text-lg text-center underline underline-offset-8">
                إقرار
            </h4>
            <p class="leading-loose">
                أقر أنا / ______________________ بإطلاعي علي الائحة التنفيذية الخاصة بصندوق التأمين الخاص بأعضاء هيئة
                التدريس و معانيهم و العاملين بجامعة العاصمة و أقبل عضويتي في الصندوق اعتباراً من
                &ensp;&ensp;&ensp;&ensp;/&ensp;&ensp;&ensp;&ensp;/&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp; و أوافق علي خصم
                قيمة قسط المشاركة خصماً من مكافأت الامتحانات المستحقة لي كل عام بما يعادل قيمة جملة الأقساط السنوية.
            </p>
            <h5 class="text-end text-base " id="date">
                تحريراً في :
            </h5>
            <h5>
                المقر بما فيه
            </h5>
            <div class="flex gap-5">
                <p class="flex-1 flex items-end gap-1">الأسم / <span class="flex-1 border-b border-black"></span></p>
                <p class="flex-1 flex items-end gap-1">الوظيفة / <span class="flex-1 border-b border-black"></span></p>
                <p class="flex-1 flex items-end gap-1">الرقم القومي / <span class="flex-1 border-b border-black"></span>
                </p>
                <p class="flex-1 flex items-end gap-1">التوقيع / <span class="flex-1 border-b border-black"></span></p>
            </div>
            <div class="flex flex-col gap-5 items-end">
                <p class="px-12">
                    مدير الادارة
                </p>
                <p>
                    و يعتمد ، / ___________________
                </p>
            </div>
        </div>
        <div class="flex justify-center mt-16">
            <button
                class="flex gap-3 py-3 w-2/4 justify-center rounded-2xl surface-shadow items-center bg-[#124375] text-white text-base font-medium hover:bg-[#0e3560] transition-colors"><iconify-icon
                    icon="material-symbols:print-rounded" class="text-2xl"></iconify-icon> حفظ البيانات
                </button>
        </div>
    </section>

    <script src="{{ asset('JS/MembershipForm.js') }}"></script>
@endsection
