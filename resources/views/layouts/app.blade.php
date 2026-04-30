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

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Main Layout --}}
    <div>
        {{-- Sidebar --}}
        @include('partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto relative">
                @include('partials.components')
                @yield('content')
            </main>

            {{-- Pagination (always above footer) --}}
            @yield('pagination')
        </div>
    </div>

    {{-- Footer (full width) --}}
    @include('partials.footer')

    @stack('scripts')
    <script src="{{ asset('JS/Dashboard.js') }}?v={{ time() }}"></script>
</body>

</html>
