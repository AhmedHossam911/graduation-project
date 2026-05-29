<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'صفحة العضو') - صندوق الزمالة جامعة العاصمة</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>

    <link rel="stylesheet" href="{{ asset('css/layouts/Dashboard.css') }}">
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
    @stack('styles')
</head>

<body>
    <div class="flex">

        {{-- Sidebar --}}
        @include('partials.member.sidebar')
        <!-- start main -->

        <main class="flex-1 flex flex-col min-h-screen">
            <!-- start header -->
            @include('partials.member.navbar')
            <!-- end header -->

            <div class="flex-1">
                @yield('content')
            </div>

            <!-- start footer -->
            <div class="mt-12">
                @include('partials.common.footer')
            </div>
        </main>
    </div>
</body>

</html>
