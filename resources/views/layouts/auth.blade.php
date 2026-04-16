<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - صندوق الزمالة كابيتال</title>
    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Include Custom Auth CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
</head>
<body>
    <div class="auth-card">
        <img src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="شعار الجامعة" class="auth-logo">
        <h1 class="auth-title">صندوق الزمالة - جامعة العاصمة</h1>
        
        @yield('content')
        
    </div>

    @yield('scripts')
</body>
</html>
