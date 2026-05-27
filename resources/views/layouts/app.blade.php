<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - صندوق الزمالة كابيتال</title>
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

<body class="bg-[#F4F7F9] text-slate-800 min-h-screen flex flex-col antialiased">

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Main Layout --}}
    <div class="flex-1 flex flex-col min-w-0 print:overflow-visible print:h-auto">
        {{-- Sidebar --}}
        @if (strtolower(auth()->user()->role->name) === 'employee')
            @include('partials.sidebar')
        @elseif (strtolower(auth()->user()->role->name) === 'admin')
            @include('partials.admin-sidebar')
        @else
            @include('partials.sidebar')
        @endif

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden print:overflow-visible print:h-auto">
            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto relative print:overflow-visible print:h-auto print:p-0">
                @yield('content')
            </main>

            {{-- Pagination (always above footer) --}}
            <div class="print:hidden">
                @yield('pagination')
            </div>
        </div>
    </div>

    {{-- Footer (full width) --}}
    @include('partials.footer')

    @stack('scripts')
    <script src="{{ asset('js/layouts/Dashboard.js') }}?v={{ time() }}"></script>
</body>

</html>
