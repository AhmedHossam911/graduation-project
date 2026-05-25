    <!-- start header -->
    <header class="bg-[#EEF7FF] border border-[#124375] px-7 py-3">
        <nav class="flex items-center justify-between  ">
            <div class="flex items-center gap-3 text-[#124375]">
                <button id="Nav-menu">
                    <iconify-icon icon="material-symbols:menu-rounded" class="text-2xl"></iconify-icon>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="logo">
                        <img style="width: 54px" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" />
                    </div>
                    <h1 class="text-xl font-semibold">صندوق الزمالة</h1>
                </a>
            </div>
            <div class="flex items-center gap-4 text-[#124375] text-4xl">
                <div class="relative">
                    <a class="cursor-pointer notification-btn">
                        <iconify-icon icon="ion:notifcations"></iconify-icon>
                    </a>
                    <div
                        class="notifications-box hidden absolute w-max left-0 z-50 bg-white  text-center surface-shadow rounded-lg   p-4 space-y-3">
                        <h1 class="text-lg font-semibold text-[#124375]">
                            إشعارات
                        </h1>
                        <div class="notification surface-shadow bg-[#EEF7FF] rounded-xl p-2">
                            <p class="notifcation-body text-sm font-medium text-[#124375]">تم تغير كلمة المرور بنجاح</p>
                        </div>
                        <div class="notification surface-shadow rounded-xl p-2">
                            <div class="flex gap-2">
                                <p class="notifcation-body text-[#6D6D6D] text-sm">العضو : <span
                                        class="font-semibold text-[#021219] text-base">روان محمد</span></p>
                                <p class="notifcation-body text-[#6D6D6D] text-sm">رقم العضوية : <span
                                        class="font-semibold text-[#021219] text-base">123456</span></p>
                            </div>
                            <p class="notifcation-body font-semibold text-[#021219] text-base">تمت الموافقة علي طلب
                                القرض</p>
                        </div>
                        <a href="../NotificationsPage/notifications.html"
                            class="text-base bg-[#124375]  text-white surface-shadow rounded-xl py-2 block">عرض كل
                            الإشعارات</a>
                    </div>
                </div>
                <a class="cursor-pointer">
                    <iconify-icon icon="boxicons:user-filled"></iconify-icon>
                </a>
            </div>
        </nav>
    </header>
    <!-- end header -->
