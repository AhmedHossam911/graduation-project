{{--
    Pages Layout:
    Alternative layout used for secondary pages (like forms or details views) where the side menu is hidden,
    focusing the user's attention on the content with a top navigation bar for going back.
--}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - صندوق الزمالة جامعة العاصمة</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/layouts/Dashboard.css') }}">
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
</head>

<body class="bg-[#F4F7F9] text-slate-800 min-h-screen flex flex-col antialiased">
    <!-- start header -->
    <header class="bg-[#EEF7FF] border-b border-[#124375] px-4 md:px-7 py-3">
        <nav class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 md:gap-5 text-[#124375]">

                <a href="{{ back()->getTargetUrl() }}"
                    class="bg-[#124375] text-[#EEF7FF] rounded-lg flex items-center justify-center shadow-md cursor-pointer px-4 md:px-6 py-2 transition-colors hover:bg-opacity-90 shrink-0">
                    <iconify-icon icon="ooui:previous-rtl" class="text-base md:text-lg"></iconify-icon>
                    <span class="text-sm md:text-lg mr-1 md:mr-2">رجوع</span>
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 md:gap-3">
                        <div class="logo shrink-0">
                            <img class="w-10 md:w-[54px]" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" />
                        </div>
                        <h1 class="text-sm md:text-xl font-semibold">صندوق الزمالة</h1>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 md:gap-3">
                        <div class="logo shrink-0">
                            <img class="w-10 md:w-[54px]" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" />
                        </div>
                        <h1 class="text-sm md:text-xl font-semibold">صندوق الزمالة</h1>
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-3 md:gap-4 text-[#124375] text-3xl md:text-4xl z-50 shrink-0">
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
                <a href="{{ route('profile.index') }}" class="cursor-pointer hidden">
                    <iconify-icon icon="boxicons:user-filled"></iconify-icon>
                </a>
            </div>
        </nav>
    </header>
    <!-- end header -->

    {{-- Main Layout --}}
    <div class="flex-1 flex flex-col print:overflow-visible print:h-auto">
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden print:overflow-visible print:h-auto">
            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto print:overflow-visible print:h-auto print:p-0">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Footer (full width) --}}
    @include('partials.common.footer')

    @stack('scripts')
    <script src="{{ asset('js/layouts/Dashboard.js') }}"></script>
</body>

</html>

