{{--
    Auth Layout:
    Layout specifically designed for authentication screens (Login, Register, OTP, Password Reset).
    Features a full background image with a centered glassmorphism card.
--}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - صندوق الزمالة جامعة العاصمة</title>
    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body
    class="bg-[url('{{ asset('IMGs/HU%20IMG.png') }}')] bg-cover bg-center bg-no-repeat bg-fixed min-h-screen flex justify-center items-center relative overflow-hidden font-['Cairo']">
    <div class="absolute inset-0 bg-white/10 backdrop-blur-[2px] z-[-1] h-[105%]"></div>

    <div
        class="bg-white/90 backdrop-blur-md rounded-2xl py-5 px-5 md:px-14 w-[95%] sm:w-[90%] md:w-full @yield('card-width', 'max-w-[568px]') mx-auto shadow-[0_10px_40px_rgba(0,0,0,0.1)] text-center">
        <img src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="شعار الجامعة" class="max-w-[120px] mx-auto">
        <h1 class="text-[#193e6a] text-2xl font-bold mb-1">صندوق الزمالة - جامعة العاصمة</h1>

        @yield('content')

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtns = form.querySelectorAll('button[type="submit"]');
                    submitBtns.forEach(btn => {
                        btn.style.width = btn.offsetWidth + 'px';
                        btn.disabled = true;
                        btn.classList.add('opacity-70', 'cursor-not-allowed');
                        btn.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i> جاري التحميل...';
                    });
                });
            });
        });
    </script>
    <script>
        const observer = new MutationObserver(() => {
            if (!overlay.classList.contains("hidden")) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                })
                document.body.style.overflow = 'hidden'
            } else {
                document.body.style.overflow = 'auto'
            }
        })
        observer.observe(overlay, {
            attributes: true,
            attributeFilter: ['class']
        })
    </script>

    @yield('scripts')
</body>

</html>
