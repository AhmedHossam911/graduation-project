<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/guestHome.css">
</head>

<body>
    <div class="flex">
        <!-- start SideBar -->
        <aside
            class="SideBar px-4 py-5 bg-[#124375] active flex flex-col justify-between min-h-screen border-l border-[#124375]">
            <div class="sidebar-pages flex flex-col  ">
                <div class="flex gap-5 items-center border-b border-[#A8A8A8] pb-5">
                    <img src="../IMGs/Hu Logo 2.png" alt="" class="bg-[#F4F7F9]">
                    <h1 class="text-[24px] text-[#F4F7F9] font-semibold">صندوق الزمالة</h1>
                </div>
                <div class="py-4 space-y-3">
                    <a href="" class="active cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 px-2 page rounded-xl text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                        <div>
                            <iconify-icon icon="ic:round-dashboard" class="text-3xl mt-1"></iconify-icon>
                        </div> 
                        <p>الرئيسية</p>
                    </a>
                    <a href="../GuestLoanPage/guestLoan.html" class="cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                        <div>
                            <iconify-icon icon="fluent:money-16-filled" class="text-3xl mt-1"></iconify-icon>
                        </div>
                        <p>القروض</p>
                    </a>
                    <a href="../GuestClaimPage/guestClaim.html" class= "cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                        <div>
                            <iconify-icon icon="f7:exclamationmark-shield-fill" class="text-3xl mt-1"></iconify-icon>
                        </div>
                        <p>المطالبات</p>
                    </a>
                    <a href="../GuestReceiptsPage/guestReceipts.html" class="cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                        <div>
                            <iconify-icon icon="iconamoon:invoice-fill" class="text-3xl mt-1"></iconify-icon>
                        </div>
                        <p>سجل الإيصالات</p>
                    </a>
                </div>
            </div>
            <div>
                <div
                    class="cursor-pointer bg-[#D92D201A] py-3 surface-shadow px-2 rounded-xl text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                    <iconify-icon icon="ic:round-logout" class="text-3xl text-[#D92D20]"></iconify-icon>
                    <p>تسجيل الخروج</p>
                </div>
            </div>
        </aside>
        <!-- end SideBar -->

        <!-- start main -->
        <main class="flex-1">
            <!-- start header -->
            <header class="bg-[#F4F7F9] surface-shadow px-7 py-3">
                <nav class="flex items-center justify-between  ">
                    <div class="flex items-center gap-3 text-[#124375]">
                        <h1 class="text-xl font-semibold">لوحة التحكم</h1>
                    </div>
                    <div class="flex items-center gap-4 text-[#124375] text-4xl">
                        <div class="relative">
                            <a href="../MemberNotificationPage/memberNotification.html" class="cursor-pointer notification-btn">
                                <iconify-icon icon="ion:notifcations"></iconify-icon>
                            </a>
                            <div
                                class="notifications-box hidden absolute w-max left-0 z-50 bg-white  text-center surface-shadow rounded-lg   p-4 space-y-3">
                                <h1 class="text-lg font-semibold text-[#124375]">
                                    إشعارات
                                </h1>
                                <div class="notification surface-shadow bg-[#EEF7FF] rounded-xl p-2">
                                    <p class="notifcation-body text-sm font-medium text-[#124375]">تم تغير كلمة المرور
                                        بنجاح</p>
                                </div>
                                <div class="notification surface-shadow rounded-xl p-2">
                                    <div class="flex gap-2">
                                        <p class="notifcation-body text-[#6D6D6D] text-sm">العضو : <span
                                                class="font-semibold text-[#021219] text-base">روان محمد</span></p>
                                        <p class="notifcation-body text-[#6D6D6D] text-sm">رقم العضوية : <span
                                                class="font-semibold text-[#021219] text-base">123456</span></p>
                                    </div>
                                    <p class="notifcation-body font-semibold text-[#021219] text-base">تمت الموافقة علي
                                        طلب القرض</p>
                                </div>
                                <a href="../NotificationsPage/notifications.html"
                                    class="text-base bg-[#124375]  text-white surface-shadow rounded-xl py-2 block">عرض
                                    كل الإشعارات</a>
                            </div>
                        </div>
                        <a href="../memberProfilePage/memberProfile.html" class="cursor-pointer">
                            <iconify-icon icon="boxicons:user-filled"></iconify-icon>
                        </a>
                    </div>
                </nav>
            </header>
            <!-- end header -->
            <div class="py-7 px-7 space-y-9">
                <!-- start header main -->
                <div class="flex flex-col gap-2 bg-[#F4F7F9] surface-shadow rounded-[16px] py-4 px-4">
                    <h2 class="text-[28px] text-[#124375] font-semibold">مرحباً بك في صندوق الزمالة</h2>
                    <p class="text-[16px] text-[#6D6D6D] font-medium">يكمنك من خلال هذه اللوحة متابعة حالة عضويتك وتقديم
                        الطلبات المختلفة.</p>
                </div>
                <!-- end header main -->

                <!-- start cards -->
                <div class=" grid grid-cols-3 gap-5">
                    <div class="surface-shadow flex items-center  gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4">
                        <div>
                            <iconify-icon icon="material-symbols:list-alt-check-rounded"
                                class=" text-4xl text-[#019168] bg-[#D8FFE8] rounded-lg px-3 py-3"></iconify-icon>
                        </div>
                        <div class="flex flex-col items-center text-[#124375] gap-2">
                            <p class="text-[16px] font-medium text-[#6D6D6D]">حالة العضوية</p>
                            <p class="text-4xl font-extrabold">غير مسجل</p>
                        </div>
                    </div>
                    <div class="surface-shadow flex items-center  gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4">
                        <div>
                            <iconify-icon icon="sidekickicons:arrow-path-clock-16-solid"
                                class=" text-4xl text-[#175CD3] bg-[#D2EBFF] rounded-lg px-3 py-3"></iconify-icon>
                        </div>
                        <div class="flex flex-col items-center text-[#124375] gap-2">
                            <p class="text-[16px] font-medium text-[#6D6D6D]">تاريخ الانضمام</p>
                            <p class="text-4xl font-extrabold">لم يتم التسجيل</p>
                        </div>
                    </div>
                    <div class="surface-shadow flex items-center  gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4">
                        <div>
                            <iconify-icon icon="f7:exclamationmark-shield-fill"
                                class=" text-4xl text-[#E11D48] bg-[#FFE4E6] rounded-lg px-3 py-3"></iconify-icon>
                        </div>
                        <div class="flex flex-col items-center text-[#124375] gap-2">
                            <p class="text-[16px] font-medium text-[#6D6D6D]">المطالبات السابقة</p>
                            <p class="text-4xl font-extrabold">0</p>
                        </div>
                    </div>
                </div>
                <!-- end cards -->
                <div class="space-y-4 bg-[#F4F7F9] surface-shadow rounded-[16px] py-7 px-5">
                    <h2 class="text-[#124375] text-[28px] font-semibold">
                        أخر الطلبات
                    </h2> 
                    <div class="flex flex-col justify-center py-3 items-center gap-5">
                        <img src="../IMGs/no-requests.png">
                        <p class="text-[16px] font-medium text-[#6D6D6D]">لم تقم بتقديم أي طلبات بعد.</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-7">
                    <a href="" class="flex flex-col gap-2 py-5 text-[#16A34A] items-center bg-[#F4F7F9] surface-shadow rounded-[16px] border-s-8 border-[#16A34A]">
                        <div>
                            <iconify-icon icon="material-symbols:list-alt-rounded"
                                class=" text-4xl bg-[#DCFCE7] rounded-lg px-3 py-2"></iconify-icon>
                        </div>
                        <p class="text-[16px] font-medium">تقديم استمارة عضوية</p>
                    </a>
                    <a href="../GuestLoanPage/guestLoan.html" class="flex flex-col gap-2 py-5 text-[#124375] items-center bg-[#F4F7F9] surface-shadow rounded-[16px] border-s-8 border-[#124375]">
                        <div>
                            <iconify-icon icon="fluent:money-24-filled"
                                class=" text-4xl bg-[#EAF5FF] rounded-lg px-3 py-2"></iconify-icon>
                        </div>
                        <p class="text-[16px] font-medium">طلب قرض جديد</p>
                    </a>
                    <a href="../GuestClaimPage/guestClaim.html" class="flex flex-col gap-2 py-5 text-[#E11D48] items-center bg-[#F4F7F9] surface-shadow rounded-[16px] border-s-8 border-[#E11D48]">
                        <div>
                            <iconify-icon icon="octicon:shield-16"
                                class=" text-4xl bg-[#FFE4E6] rounded-lg px-3 py-2"></iconify-icon>
                        </div>
                        <p class="text-[16px] font-medium">تقديم مطالبة</p>
                    </a>
                </div>
            </div>
            <!-- start footer -->
            <footer class="bg-[#EEF7FF] text-sm text-[#124375] font-normal text-center py-4 surface-shadow">
                <div class="flex items-center justify-center gap-2">
                    <p>
                        جميع الحقوق محفوظة لجامعة العاصمة لعام 2026
                    </p>
                    <iconify-icon icon="tabler:copyright-filled" class="text-lg"></iconify-icon>
                </div>
            </footer>
            <!-- end footer -->
        </main>
    </div>

    <script src="../js/guestHome.js"></script>
</body>

</html>