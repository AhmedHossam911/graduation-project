<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/memberNotification.css">
</head>
<body>

    <section class="px-12 py-7">
        <div class="flex justify-between items-center bg-[#F4F7F9] navy-shadow rounded-[16px] py-5 px-4">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ion:notifications"
                    class="text-3xl text-[#124375] bg-[#EAF5FF] rounded-[12px] py-2 px-3"></iconify-icon>
                <h1 class="text-[28px] text-[#124375] font-semibold">الإشعارات</h1>
            </div>
            <div>
                <button class="mark-as-read text-[#124375] underline text-[20px] font-semibold">
                    تحديد الكل كمقروء
                </button>
            </div>
        </div>
    </section>

    <section class="px-12 py-7 space-y-5"> 
        <div class="noti flex justify-between bg-[#F4F7F9] unread rounded-[16px] py-5 px-4">
            <div class="flex gap-2">
                <iconify-icon icon="healthicons:yes" class="text-2xl text-[#019168] mt-1"></iconify-icon>
                <div class="flex flex-col gap-2">
                    <h2 class="text-[#021219] text-[18px] font-medium">تم الموافقة على طلب القرض</h2>
                    <p class="text-[#6D6D6DCC] text-[14px] font-medium">تم الموافقة على طلب القرض رقم REQ-005. يرجي الذهاب لمقر صندوق الزمالة لاستلام الشيك.</p>
                </div> 
            </div>
            <div class="flex items-center text-[#6D6D6DCC] gap-1">
                <iconify-icon icon="tabler:clock-filled" class="text-xl mt-1"></iconify-icon>
                <span>منذ ساعتين</span>
            </div>
        </div>
        <div class="noti flex justify-between read bg-[#F4F7F9] rounded-[16px] py-5 px-4">
            <div class="flex gap-2">
                <iconify-icon icon="healthicons:yes" class="text-2xl text-[#019168CC] mt-1"></iconify-icon>
                <div class="flex flex-col gap-2">
                    <h2 class="text-[#021219] text-[18px] font-medium">تم الموافقة على استمارة العضوية</h2>
                    <p class="text-[#6D6D6DCC] text-[14px] font-medium">تمت الموافقة على طلب انضمامك لصندوق الزمالة بنجاح. يرجى سداد رسوم الاشتراك المقررة لتفعيل العضوية رسمياً.</p>
                </div> 
            </div>
            <div class="flex items-center text-[#6D6D6DCC] gap-1">
                <iconify-icon icon="tabler:clock-filled" class="text-xl mt-1"></iconify-icon>
                <span>منذ يوم</span>
            </div>
        </div>
        <div class="noti flex justify-between read bg-[#F4F7F9] rounded-[16px] py-5 px-4">
            <div class="flex gap-2">
                <iconify-icon icon="material-symbols:info-rounded" class="text-2xl text-[#EA580C] mt-1"></iconify-icon>
                <div class="flex flex-col gap-2">
                    <h2 class="text-[#021219] text-[18px] font-medium">تحديث في حالة استمارة العضوية</h2>
                    <p class="text-[#6D6D6DCC] text-[14px] font-medium">استمارة العضوية الخاصة بك قيد المراجعة حالياً من قبل الأدارة.</p>
                </div> 
            </div>
            <div class="flex items-center text-[#6D6D6DCC] gap-1">
                <iconify-icon icon="tabler:clock-filled" class="text-xl mt-1"></iconify-icon>
                <span>منذ 3 أيام</span>
            </div>
        </div>
    </section>
    

    <script src="../js/memberNotification.js"></script>
</body>
</html>