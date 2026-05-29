<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/receipts.css">
</head>
<body>
    <div class="py-7 px-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-xl text-[#124375]  font-semibold">
                الإيصالات
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                سجل بجميع الإيصالات وحالة الدفع الخاصة بك
            </p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-7 px-12">
        <div class="space-y-7 bg-[#F4F7F9] surface-shadow rounded-[16px] py-7 px-5">
            <div class="flex justify-between">
                <iconify-icon icon="material-symbols:list-alt-check-rounded" class="text-3xl text-[#124375] bg-[#EAF5FF] rounded-[12px] py-3 px-4"></iconify-icon>
                <span class="flex items-center gap-2 border border-[#F79009] text-[#F79009] bg-[#FFF7ED] rounded-[8px] h-fit px-4">
                    <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                    مستحق
                </span>
            </div> 
            <div class="flex flex-col">
                <h2 class="text-[20px] text-[#021219] font-extrabold">رسوم اشتراك العضوية</h2>
                <p class="text-[#6D6D6D] text-[16px] font-medium">رقم: <span>REC-2023-001</span></p>
            </div>
            <div>
                <div class="flex justify-between">
                    <p class="text-[#6D6D6D] font-medium text-[14px]">التاريخ</p>
                    <p class="text-[#021219] text-[16px]">2023-05-10</p>
                </div>
                <hr class="border border-[#A8A8A8] my-3">
                <div class="flex justify-between">
                    <p class="text-[#6D6D6D] font-medium text-[14px]">المبلغ الإجمالي</p>
                    <p class="text-[#124375] text-[16px]">500 ج.م</p>
                </div>
            </div> 
            <button class="hover:bg-[#0e3560] transition-colors w-full py-3 font-medium surface-shadow rounded-[12px] flex items-center text-[#F4F7F9] gap-3 bg-[#124375] justify-center open-modal" data-modal="modal1">
                <iconify-icon icon="solar:eye-outline" class="text-2xl mt-1"></iconify-icon>
                عرض الإيصال
            </button>
        </div>

        <div class="space-y-7 bg-[#F4F7F9] surface-shadow rounded-[16px] py-7 px-5">
            <div class="flex justify-between">
                <iconify-icon icon="ion:cash" class="text-3xl text-[#124375] bg-[#EAF5FF] rounded-[12px] py-3 px-4"></iconify-icon>
                <span class="flex items-center gap-2 border border-[#019168] text-[#019168] bg-[#F0FFF6] rounded-[8px] h-fit px-4">
                    <iconify-icon icon="healthicons:yes" class="text-xl"></iconify-icon>
                    مدفوع
                </span>
            </div> 
            <div class="flex flex-col">
                <h2 class="text-[20px] text-[#021219] font-extrabold">قسط قرض شخصي (1/12)</h2>
                <p class="text-[#6D6D6D] text-[16px] font-medium">رقم: <span>REC-2023-001</span></p>
            </div>
            <div>
                <div class="flex justify-between">
                    <p class="text-[#6D6D6D] font-medium text-[14px]">التاريخ</p>
                    <p class="text-[#021219] text-[16px]">2023-05-10</p>
                </div>
                <hr class="border border-[#A8A8A8] my-3">
                <div class="flex justify-between">
                    <p class="text-[#6D6D6D] font-medium text-[14px]">المبلغ الإجمالي</p>
                    <p class="text-[#124375] text-[16px]">1,500 ج.م</p>
                </div>
            </div> 
            <button class="hover:bg-[#0e3560] transition-colors w-full py-3 font-medium surface-shadow rounded-[12px] flex items-center text-[#F4F7F9] gap-3 bg-[#124375] justify-center open-modal" data-modal="modal2">
                <iconify-icon icon="solar:eye-outline" class="text-2xl mt-1"></iconify-icon>
                عرض الإيصال
            </button>
        </div>
    </div>

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

    <div id="modal1" class="hidden flex flex-col max-h-[95vh] w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
        <div class="flex shrink-0">
            <button class="modal-close text-[#124375] text-2xl  surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>  
        </div>
        <div class="modal-title px-12 shrink-0">
            <div class="flex flex-col justify-center items-center space-y-4 border-b border-[#A8A8A8] pb-5">
                <h1 class="text-[#124375] text-[28px] font-semibold">صندوق الزمالة للعاملين بجامعة العاصمة</h1>
                <p class="text-[16px] text-[#021219] font-medium"> إيصال دفع</p>
            </div>
        </div>
        <div class="modal-body space-y-7 py-3 px-9 mt-5 flex-1 overflow-y-auto no-scrollbar ">
            <div class="flex justify-center">
                <p class="flex items-center gap-2 text-[#F79009] border border-[#F79009] bg-[#FFF7ED] w-fit rounded-[8px] px-5">
                    <iconify-icon icon="material-symbols:info-rounded" class="text-lg mt-1"></iconify-icon>
                    مستحق ( غير مدفوع )
                </p>
            </div>
            <div class="grid grid-cols-4 gap-3 bg-[#F4F7F9] surface-shadow rounded-[16px] py-4 px-2">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="bxs:user" class="text-xl mt-1 text-[#124375]"></iconify-icon>
                        <p class="text-[#6D6D6D] text-[14px] font-medium">اسم العضو</p>
                    </div>
                    <p class="text-[#021219] font-medium text-[16px]">أحمد محمد</p>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-xl mt-1 text-[#124375]"></iconify-icon>
                        <p class="text-[#6D6D6D] text-[14px] font-medium">رقم العضوية</p>
                    </div>
                    <p class="text-[#021219] font-medium text-[16px]">1234564</p>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="iconamoon:invoice-fill" class="text-xl mt-1 text-[#124375]"></iconify-icon>
                        <p class="text-[#6D6D6D] text-[14px] font-medium"> رقم الإيصال</p>
                    </div>
                    <p class="text-[#021219] font-medium text-[16px]">REC-2023-001</p>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="uil:calender" class="text-xl mt-1 text-[#124375]"></iconify-icon>
                        <p class="text-[#6D6D6D] text-[14px] font-medium">التاريخ</p>
                    </div>
                    <p class="text-[#021219] font-medium text-[16px]">1 مايو 2026</p>
                </div>
            </div>
            <div class="flex flex-col gap-2 bg-[#EEF7FF] surface-shadow rounded-[16px] py-4 px-2">
                <p class="text-[#6D6D6D] text-[14px] font-medium">البيان / الغرض من الدفع</p>
                <p class="text-[#124375] text-[20px] font-semibold">رسوم اشتراك العضوية</p>
            </div>
            <div class="flex justify-between items-center bg-[#F4F7F9] surface-shadow rounded-[16px] py-4 px-2">
                <p class="text-[#021219] text-[14px] font-medium">المبلغ المطلوب</p>
                <p class="text-[20px] text-[#124375] font-semibold">500 ج.م</p>
            </div>
            <div class="space-y-4 surface-shadow rounded-[16px] py-4 px-2">
                <div class="flex gap-4 items-center border-b-2 border-[#A8A8A8] pb-2">
                    <iconify-icon icon="basil:card-outline" class="text-2xl text-[#124375] flex items-center"></iconify-icon>
                    <p class="text-[#021219] text-[16px] font-medium">بيانات الدفع والتحويل البنكي أو (InstaPay)</p>
                </div>
                <div class="space-y-4 border-b-2 border-[#A8A8A8] pb-2">
                    <div class="flex justify-between items-center">
                        <p class="text-[14px] font-medium text-[#6D6D6D]">البنك :</p>
                        <p class="text-[16px] font-semibold text-[#021219]">بنك مصر</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-[14px] font-medium text-[#6D6D6D]">أسم الحساب :</p>
                        <p class="text-[16px] font-semibold text-[#021219]">صندوق الزمالة للعاملين بجامعة العاصمة</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-[14px] font-medium text-[#6D6D6D]">رقم الحساب :</p>
                        <p class="text-[16px] font-semibold text-[#124375]">10002938475638</p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-[14px] font-medium text-[#6D6D6D]">عنوان InstaPay :</p>
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="tabler:copy-filled" class="cursor-pointer copy-btn text-2xl text-[#94A3B8] flex items-center"></iconify-icon>
                        <p class="text-[16px] font-semibold text-[#155DFC] insta-pay-value">cu_fund@nbe</p>
                    </div>
                </div>
                <div class="flex gap-2 bg-[#FFF7ED] rounded-[16px] py-2 px-2 orange-shadow"> 
                    <iconify-icon icon="material-symbols:info-rounded" class="text-2xl text-[#F79009]"></iconify-icon>
                    <p class="text-[14px] text-[#973C00] font-medium leading-loose ">
                        هام جداً: يرجى كتابة "الاسم" و "رقم العضوية" في حقل الملاحظات (Notes) أثناء التحويل لضمان تسجيل السداد في حسابك، والاحتفاظ بصورة التحويل.
                    </p>
                </div>
            </div> 
        </div> 
        <div class="btns flex gap-4 shrink-0 px-9 mt-7">
            <div class="w-full">  
                <button class=" rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] surface-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon icon="material-symbols:download-rounded"  class="flex items-center text-2xl"></iconify-icon></span>تحميل PDF</button>
            </div>
        </div>
    </div>

    <div id="modal2" class="hidden flex flex-col max-h-[95vh] w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
        <div class="flex shrink-0">
            <button class="modal-close text-[#124375] text-2xl  surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>  
        </div>
        <div class="modal-title px-12 shrink-0">
            <div class="flex flex-col justify-center items-center space-y-4 border-b border-[#A8A8A8] pb-5">
                <h1 class="text-[#124375] text-[28px] font-semibold">صندوق الزمالة للعاملين بجامعة العاصمة</h1>
                <p class="text-[16px] text-[#021219] font-medium"> إيصال دفع</p>
            </div>
        </div>
        <div class="modal-body space-y-7 py-3 px-9 mt-5 flex-1 overflow-y-auto no-scrollbar ">
            <div class="flex justify-center">
                <p class="flex items-center gap-2 text-[#019168] border border-[#019168] bg-[#F0FFF6] w-fit rounded-[8px] px-5">
                    <iconify-icon icon="healthicons:yes" class="text-lg mt-1"></iconify-icon>
                    تم الدفع
                </p>
            </div>
            <div class="grid grid-cols-4 gap-3 bg-[#F4F7F9] surface-shadow rounded-[16px] py-4 px-2">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="bxs:user" class="text-xl mt-1 text-[#124375]"></iconify-icon>
                        <p class="text-[#6D6D6D] text-[14px] font-medium">اسم العضو</p>
                    </div>
                    <p class="text-[#021219] font-medium text-[16px]">أحمد محمد</p>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-xl mt-1 text-[#124375]"></iconify-icon>
                        <p class="text-[#6D6D6D] text-[14px] font-medium">رقم العضوية</p>
                    </div>
                    <p class="text-[#021219] font-medium text-[16px]">1234564</p>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="iconamoon:invoice-fill" class="text-xl mt-1 text-[#124375]"></iconify-icon>
                        <p class="text-[#6D6D6D] text-[14px] font-medium"> رقم الإيصال</p>
                    </div>
                    <p class="text-[#021219] font-medium text-[16px]">REC-2023-001</p>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="uil:calender" class="text-xl mt-1 text-[#124375]"></iconify-icon>
                        <p class="text-[#6D6D6D] text-[14px] font-medium">التاريخ</p>
                    </div>
                    <p class="text-[#021219] font-medium text-[16px]">1 مايو 2026</p>
                </div>
            </div>
            <div class="flex flex-col gap-2 bg-[#EEF7FF] surface-shadow rounded-[16px] py-4 px-2">
                <p class="text-[#6D6D6D] text-[14px] font-medium">البيان / الغرض من الدفع</p>
                <p class="text-[#124375] text-[20px] font-semibold">قسط قرض شخصي (1/12)</p>
            </div>
            <div class="flex justify-between items-center bg-[#F4F7F9] surface-shadow rounded-[16px] py-4 px-2">
                <p class="text-[#021219] text-[14px] font-medium">المبلغ المطلوب</p>
                <p class="text-[20px] text-[#124375] font-semibold">500 ج.م</p>
            </div> 
        </div> 
        <div class="btns flex gap-4 shrink-0 px-9 mt-7">
            <div class="w-full">  
                <button class=" rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] surface-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon icon="material-symbols:download-rounded"  class="flex items-center text-2xl"></iconify-icon></span>تحميل PDF</button>
            </div>
        </div>
    </div>

    <script src="../js/receipts.js"></script>
</body>
</html>