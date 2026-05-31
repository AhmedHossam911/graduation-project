   <link rel="stylesheet" href="{{ asset('css/member/memberNavbar.css') }}">
   <!-- start header -->
   <header class="bg-[#F4F7F9] surface-shadow px-7 py-3">
       <nav class="flex items-center justify-between  ">
           <div class="flex items-center gap-3 text-[#124375]">
               <!-- Mobile Menu Toggle Button -->
               <button id="Nav-menu" class="md:hidden text-3xl flex items-center">
                   <iconify-icon icon="ic:round-menu"></iconify-icon>
               </button>
               <h1 class="text-xl font-semibold">
                     @yield('title', 'الرئيسية')
               </h1>
           </div>
            <div class="flex items-center gap-4 text-[#124375] text-4xl">
                <div class="relative">
                    <button class="cursor-pointer notification-btn relative flex items-center">
                        <iconify-icon icon="ion:notifcations"></iconify-icon>
                        @php
                            $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-[3px] -right-[3px] bg-red-500 text-white text-[9px] font-bold px-[4px] py-[1px] rounded-full min-w-[16px] h-[16px] text-center flex items-center justify-center shadow-md border border-white">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <div
                        class="notifications-box hidden absolute w-max left-0 z-50 bg-white  text-center surface-shadow rounded-lg   p-4 space-y-3">
                        <h1 class="text-lg font-semibold text-[#124375]">
                            إشعارات
                        </h1>

                        @php
                            $unreadNotifications = auth()->user()->notifications()->whereNull('read_at')->latest()->take(2)->get();
                        @endphp

                        @forelse($unreadNotifications as $notification)
                        <div class="notification surface-shadow bg-[#EEF7FF] rounded-xl p-2">
                            <p class="notifcation-body text-sm font-medium text-[#124375]">{{ $notification->message ?? $notification->title ?? 'إشعار جديد' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @empty
                        <div class="notification surface-shadow rounded-xl p-2">
                            <p class="notifcation-body text-sm font-medium text-[#6D6D6D]">لا توجد إشعارات جديدة</p>
                        </div>
                        @endforelse

                        <a href="{{ route('member.notifications') }}"
                            class="text-base bg-[#124375]  text-white surface-shadow rounded-xl py-2 block">عرض
                            كل الإشعارات</a>
                    </div>
                </div>
                <a href="{{ route('member.profile') }}" class="cursor-pointer hidden md:block">
                    <iconify-icon icon="boxicons:user-filled"></iconify-icon>
                </a>
            </div>
       </nav>
   </header>
   <!-- end header -->
