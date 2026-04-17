<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - صندوق الزمالة كابيتال</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN & Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Cairo', 'sans-serif'],
            },
            colors: {
              primary: '#124375',
              'primary-light': '#27568f',
              'primary-dark': '#0f2a4a',
              navbar: '#EEF7FF',
              sidebar: '#EEF7FF',
              body: '#F4F7F9',
            }
          }
        }
      }
    </script>
    <style>
      body {
        font-family: 'Cairo', sans-serif;
      }
    </style>
    @stack('styles')

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
</head>
<body class="bg-body text-slate-800 min-h-screen flex flex-col antialiased">

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Flash Messages --}}
    @include('partials.flash')

    {{-- Main Layout --}}
    <div class="flex flex-1 overflow-hidden">
        {{-- Sidebar --}}
        @include('partials.sidebar')

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
</body>
</html>
