<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/memberLoan.css">
</head>

<body>

    <section class="py-7 px-12">
        <div class="flex items-center justify-between">
            <div class="flex flex-col gap-3">
                <h1 class="text-xl text-[#124375]  font-semibold">
                    طلب الحصول على قرض
                </h1>
                <p class="text-[#6D6D6D] text-[16px] font-normal">
                    الحد الأقصى للقرض 20,000 جنيه، يسدد على مدة أقصاها 36 شهراً، ويضاف معدل فائدة بواقع 8% سنوياً على
                    قيمة القرض.
                </p>
            </div>
            <div>
                <button class="text-[#D92D20] bg-[#F4F7F9] rounded-[16px] py-2 px-12 red-shadow">
                    إلغاء
                </button>
            </div>
        </div>
    </section>

    <div class="py-3 px-12 ">
        <div class="bg-[#EEF7FF] space-y-7 navy-shadow rounded-[16px] py-5 px-4">
            <div class="flex gap-4 items-center">
                <iconify-icon icon="material-symbols:info-rounded" class="text-2xl text-[#175CD3]"></iconify-icon>
                <p class="text-[#124375] text-[18px] font-medium">ملخص حساب القرض</p>
            </div>
            <div class="flex justify-between px-7">
                <div class="flex flex-col gap-3 ">
                    <p class="text-[16px] text-[#175CD3] font-medium">المبلغ المطلوب</p>
                    <p class="text-[#124375] text-[20px] font-semibold">5000 ج.م</p>
                </div>
                <div class="flex flex-col gap-3 ">
                    <p class="text-[16px] text-[#175CD3] font-medium">الفائدة الإجمالية (8%)</p>
                    <p class="text-[#124375] text-[20px] font-semibold">400 ج.م</p>
                </div>
                <div class="flex flex-col gap-3 ">
                    <p class="text-[16px] text-[#175CD3] font-medium">إجمالي المديونية</p>
                    <p class="text-[#124375] text-[20px] font-semibold">5400 ج.م</p>
                </div>
                <div class="flex flex-col gap-3 ">
                    <p class="text-[16px] text-[#175CD3] font-medium">القسط الشهري</p>
                    <p class="text-[#124375] text-[20px] font-semibold">450 ج.م</p>
                </div>
            </div>
        </div>
    </div>

    <section class="py-3 px-12 ">
        <div class="bg-[#F4F7F9] navy-shadow rounded-[16px] py-8 px-3 space-y-7">
            <div class="grid grid-cols-2 gap-4">
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base gap-3 flex justify-between items-center"><span
                            class="text-[#021219] text-center flex-1">اختر</span><span
                            class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                class="text-xl "></iconify-icon></span></button>
                    <label
                        class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">قيمة القرض</label>
                    <div
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-2 px-5 py-4 rounded-xl navy-shadow w-full">
                        <button type="button" class=" navy-shadow py-2 px-7 rounded-xl text-sm font-medium">5,000</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium">10,000</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium">20,000</button>
                    </div>
                </div>
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base gap-3 flex justify-between items-center"><span
                            class="text-[#021219] text-center flex-1">اختر</span><span
                            class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                class="text-xl "></iconify-icon></span></button>
                    <label
                        class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">مدة السداد</label>
                    <div
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-2 px-5 py-4 rounded-xl navy-shadow w-full">
                        <button type="button" class=" navy-shadow py-2 px-7 rounded-xl text-sm font-medium">12 شهر</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium">24 شهر</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium">32 شهر</button>
                    </div>
                </div>
            </div>
            <div>
                <div class="relative w-full">
                    <label class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">السبب أو الغرض من القرض</label>
                    <textarea class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] px-2 py-3 resize-none"></textarea>
                </div>
            </div>
            <label class="flex  gap-5 cursor-pointer">
                <input type="checkbox" class="hidden peer item" value="يناير 2026">
                <span class="mt-1 custom-checkbox flex items-center justify-center h-[20px] w-[20px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                </span>
                <p>أقر أنا الموقع أعلاه بصحة البيانات المذكورة، وأفوض إدارة الجامعة بخصم الأقساط الشهرية للقرض المذكور أعلاه من راتبي الشهري وتوريدها لحساب صندوق الزمالة حتى سداد كامل المديونية. وأتعهد بعدم ممانعة جهة العمل.</p>
            </label>
            <div>
                <button class="hover:bg-[#0e3560] transition-colors  flex items-center gap-5 bg-[#124375] text-[#F4F7F9] w-full justify-center py-3 rounded-[12px] ">
                    <iconify-icon icon="boxicons:send-filled" class="text-2xl mt-1"></iconify-icon>
                    تقديم طلب القرض
                </button>
            </div>
        </div> 
    </section>

    <!-- <div class="rounded-[12px] bg-[#FFF7ED] max-w-xl orange-shadow py-4 px-5 mx-auto flex flex-col gap-7 justify-center items-center">
        <div>
            <iconify-icon icon="material-symbols:info-rounded" class="text-4xl text-[#F79009] bg-[#FEF3C7] rounded-full py-4 px-4"></iconify-icon>
        </div>
        <div>
            <p class="text-[#124375] text-[20px] font-semibold">عذرا، لايمكنك طلب قرض جديد</p>
        </div>
        <div>
            <p class="text-[#6D6D6D] text-[14px] font-medium">لا يجوز للعضو الحصول علي أكثر من قرض واحد في نفس الوقت. يجد الانتهاء من سدادالقرض الحالي بالكامل قبل التقدم بطلب للحصول علي قرض جديد</p>
        </div>
        <div>
            <a href="../MemberHomePage/memberhome.html" class="cursor-pointer bg-[#124375] hover:bg-[#0e3560] transition-colors text-[#F4F7F9] flex items-center gap-4 w-full py-3 px-8 rounded-[12px] navy-shadow ">
                متابعة سداد القرض الحالي 
                <iconify-icon icon="fe:arrow-left" class="text-2xl mt-1"></iconify-icon>
            </a>
        </div>
    </div> -->

    <script src="../js/memberLoan.js"></script>
</body>
</html>