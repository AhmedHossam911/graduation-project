{{--
    Main App Layout:
    The primary layout structure used for authenticated user dashboards (Admins, Employees).
    Includes Sidebar, Navbar, and Footer components dynamically based on roles.
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>

    <link rel="stylesheet" href="{{ asset('css/layouts/Dashboard.css') }}">
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
    @stack('styles')
</head>

<body class="bg-[#F4F7F9]">
    @include('partials.common.flash')

    {{-- Navbar --}}
    @include('partials.common.navbar')

    {{-- Main Layout --}}
    <div class="flex-1 flex min-w-0 print:overflow-visible print:h-auto relative">
        {{-- Sidebar --}}
        @if (strtolower(auth()->user()->role->name) === 'employee')
            @include('partials.employee.sidebar')
        @elseif (strtolower(auth()->user()->role->name) === 'admin')
            @include('partials.admin.sidebar')
        @else
            @include('partials.employee.sidebar')
        @endif

        {{-- Sidebar Overlay for Mobile --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden print:overflow-visible print:h-auto">
            {{-- Page Content --}}
            <main class="flex-1 py-5 px-3">
                @yield('content')
            </main>

            {{-- Pagination (always above footer) --}}
            <div class="print:hidden">
                @yield('pagination')
            </div>
        </div>
    </div>

    {{-- Footer (full width) --}}
    @include('partials.common.footer')

    @stack('scripts')
    <script src="{{ asset('js/layouts/Dashboard.js') }}?v={{ time() }}"></script>
    <script>
        const observer = new MutationObserver(() => {
            if (!overlay.classList.contains("hidden")) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                })
            }
        })
        observer.observe(overlay, {
            attributes: true,
            attributeFilter: ['class']
        })
    </script>
</body>

</html>
