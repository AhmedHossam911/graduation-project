<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/memberProfile.css">
</head>

<body>

    <section class="px-12 py-7">
        <div class="flex justify-between items-center bg-[#F4F7F9] navy-shadow rounded-[16px] py-5 px-4">
            <div class="flex items-center gap-2">
                <iconify-icon icon="bxs:user"
                    class="text-3xl text-[#124375] bg-[#EAF5FF] rounded-[12px] py-2 px-3"></iconify-icon>
                <h1 class="text-[28px] text-[#124375] font-semibold">الملف الشخصي</h1>
            </div>
            <div>
                <button
                    class="edit-btn hover:bg-[#0e3560] transition-colors flex items-center gap-3 bg-[#124375] text-[#F4F7F9] py-3 px-14 rounded-[12px] navy-shadow ">
                    <iconify-icon icon="ic:round-edit" class="text-2xl"></iconify-icon>
                    تعديل البيانات
                </button>
            </div>
        </div>
    </section>

    <section class="px-12 relative py-3">
        <div class="bg-[#F4F7F9] navy-shadow rounded-2xl space-y-20">
            <div class="relative">
                <div class="bg-gradient-to-t from-[#124375] to-[#2A6B9E] h-[150px] rounded-tl-2xl rounded-tr-2xl"></div>
                <div
                    class="absolute -bottom-12 right-9 bg-[#F4F7F9] rounded-full navy-shadow w-24 h-24 flex flex-col items-center">
                    <p class="text-[60px] font-extrabold text-[#124375]">ي</p>
                </div>
            </div>
            <div class="px-5 pb-5 space-y-7">
                <div class="grid grid-cols-2 gap-x-7 gap-y-10">
                    <div class="relative w-full">
                        <label
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] flex items-center gap-1 ">
                            <iconify-icon icon="bxs:user" class="text-2xl text-[#124375]"></iconify-icon>
                            <span class="text-[#021219]">
                                الاسم رباعي
                            </span>
                        </label>
                        <p
                            class="bg-[#F4F7F9] px-1 absolute text-[12px] font-medium text-[#124375] right-7 bottom-[-6px]">
                            لا يمكن تعديل الاسم بعد التسجيل</p>
                        <input type="text" disabled
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                            placeholder="احمد محمد ابراهيم خليل">
                    </div>
                    <div class="relative w-full">
                        <label
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] flex items-center gap-1 ">
                            <iconify-icon icon="fa6-solid:id-card" class="text-2xl text-[#124375]"></iconify-icon>
                            <span class="text-[#021219]">
                                الرقم القومي
                            </span>
                        </label>
                        <p
                            class="bg-[#F4F7F9] px-1 absolute text-[12px] font-medium text-[#124375] right-7 bottom-[-6px]">
                            لا يمكن تعديل الرقم القومي بعد التسجيل</p>
                        <input type="number" disabled
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                            placeholder="12345678901234">
                    </div>
                    <div class="relative w-full">
                        <label
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] flex items-center gap-1 ">
                            <iconify-icon icon="ic:round-email" class="text-2xl text-[#124375]"></iconify-icon>
                            <span class="text-[#021219]">
                                البريد الإلكتروني
                            </span>
                        </label>
                        <input type="email" disabled
                            class="input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                            placeholder="youssef@gmail.com">
                    </div>
                    <div class="relative w-full">
                        <label
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] flex items-center gap-1 ">
                            <iconify-icon icon="ic:round-phone" class="text-2xl text-[#124375]"></iconify-icon>
                            <span class="text-[#021219]">
                                رقم التليفون
                            </span>
                        </label>
                        <input type="tel" disabled
                            class="input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                            placeholder="010000000000">
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-[#124375] text-[18px] font-medium">بيانات العضوية</h2>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="navy-shadow flex items-center  gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4">
                            <div>
                                <iconify-icon icon="iconamoon:shield-yes-fill"
                                    class=" text-4xl text-[#019168]"></iconify-icon>
                            </div>
                            <div class="flex flex-col items-center text-[#124375] gap-2">
                                <p class="text-[16px] font-medium text-[#6D6D6D]">حالة العضوية</p>
                                <p class="text-[20px] font-semibold text-[#021219]">نشط</p>
                            </div>
                        </div>
                        <div class="navy-shadow flex items-center  gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4">
                            <div>
                                <iconify-icon icon="uil:calender" class=" text-4xl text-[#124375]"></iconify-icon>
                            </div>
                            <div class="flex flex-col items-center text-[#124375] gap-2">
                                <p class="text-[16px] font-medium text-[#6D6D6D]">تاريخ الانضمام</p>
                                <p class="text-[20px] font-semibold text-[#021219]">10 مايو 2024</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        class="save-btn hidden hover:bg-[#0e3560] transition-colors flex items-center gap-3 bg-[#124375] text-[#F4F7F9] py-3 px-14 rounded-[12px] navy-shadow ">
                        <iconify-icon icon="fluent:save-16-filled" class="text-2xl"></iconify-icon>
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </div>
    </section>


    <script src="../js/memberProfile.js"></script>
</body>

</html>