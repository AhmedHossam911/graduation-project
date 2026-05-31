{{--
    Global Navbar Partial:
    Top navigation bar containing the sidebar toggle, brand logo, notifications dropdown, and profile link.
--}}
    <!-- start header -->
    <header class="print:hidden bg-[#EEF7FF] border border-[#124375] px-7 py-3">
        <nav class="flex items-center justify-between  ">
            <div class="flex items-center gap-3 text-[#124375]">
                <button id="Nav-menu">
                    <iconify-icon icon="material-symbols:menu-rounded" class="text-2xl"></iconify-icon>
                </button>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div class="logo shrink-0">
                            <img style="width: 54px" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" class="w-10 md:w-[54px] h-auto" />
                        </div>
                        <h1 class="text-xl font-semibold hidden md:block truncate">صندوق الزمالة - جامعة العاصمة</h1>
                        <h1 class="text-xl font-semibold block md:hidden truncate">صندوق الزمالة</h1>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="logo shrink-0">
                            <img style="width: 54px" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" class="w-10 md:w-[54px] h-auto" />
                        </div>
                        <h1 class="text-xl font-semibold hidden md:block truncate">صندوق الزمالة - جامعة العاصمة</h1>
                        <h1 class="text-sm font-semibold block md:hidden truncate">صندوق الزمالة</h1>
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-4 text-[#124375] z-50">
                <div class="relative">
                    <a class="cursor-pointer notification-btn relative flex items-center text-3xl md:text-4xl">
                        <iconify-icon icon="ion:notifcations"></iconify-icon>
                        @php
                            $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-[3px] -right-[3px] bg-red-500 text-white text-[9px] font-bold px-[4px] py-[1px] rounded-full min-w-[16px] h-[16px] text-center flex items-center justify-center shadow-md border border-white">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                    <div
                        class="notifications-box hidden absolute w-[280px] max-w-[90vw] md:w-max left-0 md:-left-4 z-50 bg-white text-center surface-shadow rounded-lg p-4 space-y-3 min-w-[250px] top-full mt-2">
                        <h1 class="text-lg font-semibold text-[#124375] border-b pb-2">
                            إشعارات
                        </h1>

                        @php
                            $latestNotifications = auth()->user()->notifications()->latest()->take(3)->get();
                        @endphp

                        <div class="flex flex-col gap-2">
                            @forelse ($latestNotifications as $notification)
                                <div
                                    class="notification surface-shadow {{ is_null($notification->read_at) ? 'bg-[#EEF7FF]' : 'bg-white' }} rounded-xl p-3 text-right">
                                    @if ($notification->title)
                                        <h3 class="text-sm font-semibold text-[#124375] mb-1">{{ $notification->title }}
                                        </h3>
                                    @endif
                                    <p class="notifcation-body text-sm text-[#6D6D6D]">{{ $notification->message }}</p>
                                    <span
                                        class="text-[10px] text-gray-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 py-4 text-center">لا توجد إشعارات حالياً</p>
                            @endforelse
                        </div>

                        <a href="{{ route('notifications.index') }}"
                            class="text-base bg-[#124375]  text-white surface-shadow rounded-xl py-2 mt-2 block w-full text-center hover:bg-opacity-90 transition-colors">عرض
                            كل
                            الإشعارات</a>
                    </div>
                </div>
                <a href="{{ route('profile.index') }}" class="cursor-pointer hidden md:block text-4xl">
                    <iconify-icon icon="boxicons:user-filled"></iconify-icon>
                </a>
            </div>
        </nav>
    </header>
    <!-- end header -->
