<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - صندوق الزمالة كابيتال</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
</head>

<body class="bg-body text-slate-800 min-h-screen flex flex-col antialiased">
    <!-- start header -->
    <header class="bg-[#EEF7FF] border border-[#124375] px-7 py-3">
        <nav class="flex items-center justify-between  ">
            <div class="flex items-center gap-3 text-[#124375]">
                <a href="{{ route('members.index') }}"
                    class="text-lg w-[76px] h-[42px] bg-[#124375] text-[#EEF7FF] text-center rounded-lg flex items-center justify-center shadow-md cursor-pointer px-12 py-6">
                    <iconify-icon icon="ooui:previous-rtl" class="text-lg"></iconify-icon>
                    <span class="text-lg mr-2">رجوع</span>
                </a>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="logo">
                        <img style="width: 54px" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" />
                    </div>
                    <h1 class="text-xl font-semibold">صندوق الزمالة</h1>
                </a>
            </div>
            <div class="flex items-center gap-4 text-[#124375] text-4xl">
                <a class="cursor-pointer" href="{{ route('notifications.index') }}">
                    <iconify-icon icon="ion:notifcations"></iconify-icon>
                </a>
                <a class="cursor-pointer" href="{{ route('profile.index') }}">
                    <iconify-icon icon="boxicons:user-filled"></iconify-icon>
                </a>
            </div>
        </nav>

    </header>
    <!-- end header -->

    {{-- Main Layout --}}
    <div>
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Footer (full width) --}}
    @include('partials.footer')

    @stack('scripts')
    <script src="{{ asset('JS/Dashboard.js') }}"></script>
</body>

</html>
