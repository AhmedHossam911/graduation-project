   <link rel="stylesheet" href="{{ asset('css/member/memberNavbar.css') }}">
   <!-- start header -->
   <header class="bg-[#F4F7F9] surface-shadow px-7 py-3">
       <nav class="flex items-center justify-between  ">
           <div class="flex items-center gap-3 text-[#124375]">
               <h1 class="text-xl font-semibold">الرئيسية</h1>
           </div>
           <div class="flex items-center gap-4 text-[#124375] text-4xl">
               <div class="relative">
                   <button class="cursor-pointer notification-btn">
                       <iconify-icon icon="ion:notifcations"></iconify-icon>
                   </button>
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
                       <a href="../MemberNotificationPage/memberNotification.html"
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
   <script src="{{ asset('JS/member/memberNavbar.js') }}"></script>
