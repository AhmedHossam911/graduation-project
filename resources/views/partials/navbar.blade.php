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
                        <div class="logo">
                            <img style="width: 54px" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" />
                        </div>
                        <h1 class="text-xl font-semibold">صندوق الزمالة - جامعة العاصمة</h1>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="logo">
                            <img style="width: 54px" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" />
                        </div>
                        <h1 class="text-xl font-semibold">صندوق الزمالة - جامعة العاصمة</h1>
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-4 text-[#124375] text-4xl z-50">
                <div class="relative">
                    <a class="cursor-pointer notification-btn">
                        <iconify-icon icon="ion:notifcations"></iconify-icon>
                    </a>
                    <div
                        class="notifications-box hidden absolute w-max left-0 z-50 bg-white  text-center surface-shadow rounded-lg   p-4 space-y-3 min-w-[250px]">
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
                <a href="{{ route('profile.index') }}" class="cursor-pointer">
                    <iconify-icon icon="boxicons:user-filled"></iconify-icon>
                </a>
            </div>
        </nav>
    </header>
    <!-- end header -->
