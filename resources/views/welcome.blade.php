{{--
    Welcome Landing Page:
    The public-facing entry point for the Fellowship Fund Management System.
    Provides system overview, features, and links to login/registration.
--}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صندوق الزمالة - {{ config('app.name', 'HU Capital') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#124375',
                            dark: '#082b50',
                            light: '#1b60a3',
                            soft: '#eef7ff',
                            gold: '#d4af37',
                            danger: '#d92d20',
                            ink: '#021219',
                            muted: '#5f6f7d',
                        },
                    },
                    boxShadow: {
                        soft: '0 20px 55px rgba(8, 43, 80, 0.08)',
                        button: '0 10px 25px rgba(18, 67, 117, 0.25)',
                        logo: '0 8px 24px rgba(18, 67, 117, 0.12)',
                        glass: '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        }
                    }
                },
            },
        }
    </script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
</head>

<body class="min-h-screen overflow-x-hidden bg-brand-soft font-cairo text-brand-ink selection:bg-brand selection:text-white">

    <!-- Background Elements -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <img class="h-full w-full object-cover opacity-60 scale-105 animate-[pulse-slow_8s_ease-in-out_infinite]" src="{{ asset('IMGs/HU IMG.png') }}" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-soft/95 via-white/80 to-white/95"></div>

        <!-- Decorative blobs -->
        <div class="absolute -top-[20%] -right-[10%] w-[50%] h-[50%] rounded-full bg-brand/10 blur-[120px] mix-blend-multiply"></div>
        <div class="absolute bottom-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-brand-gold/15 blur-[100px] mix-blend-multiply"></div>
    </div>

    <div class="flex min-h-screen flex-col">

        <!-- Header -->
        <header class="sticky top-0 z-50 w-full bg-white/70 backdrop-blur-md border-b border-white/50 shadow-sm transition-all duration-300" data-aos="fade-down">
            <div class="mx-auto flex w-[min(1160px,calc(100%-32px))] items-center justify-between gap-4 py-4 max-[620px]:flex-col max-[620px]:items-start">
                <a class="group flex items-center gap-4 font-extrabold text-brand transition-all hover:opacity-90" href="{{ url('/') }}" aria-label="صندوق الزمالة">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-xl bg-brand opacity-20 blur group-hover:opacity-40 transition-opacity"></div>
                        <img class="relative h-[60px] w-[60px] rounded-xl bg-white object-contain p-2 shadow-logo transition-transform duration-300 group-hover:scale-105 group-hover:-rotate-3" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="شعار الجامعة">
                    </div>
                    <span>
                        صندوق الزمالة - جامعة العاصمة (حلوان سابقا)
                        <small class="mt-0.5 block text-[13px] font-semibold text-brand-muted tracking-wide">نظام إدارة صندوق الزمالة </small>
                    </span>
                </a>

                <nav class="flex flex-wrap items-center gap-3 max-[620px]:w-full" aria-label="روابط الدخول">
                    @auth
                        <a class="group relative overflow-hidden inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-gradient-to-l from-brand to-brand-light px-6 py-2.5 font-bold text-white shadow-button transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-brand/30 max-[620px]:w-full" href="{{ route('dashboard') }}">
                            <div class="absolute inset-0 bg-white/20 translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></div>
                            <iconify-icon icon="mdi:view-dashboard" class="text-xl"></iconify-icon>
                            <span class="relative z-10">لوحة التحكم</span>
                        </a>
                    @else
                        <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-brand/20 bg-white/80 backdrop-blur-sm px-6 py-2.5 font-bold text-brand shadow-sm transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-md max-[620px]:w-full" href="{{ route('register') }}">
                            إنشاء حساب
                        </a>
                        <a class="group relative overflow-hidden inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-gradient-to-l from-brand to-brand-light px-6 py-2.5 font-bold text-white shadow-button transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-brand/30 max-[620px]:w-full" href="{{ route('login') }}">
                            <div class="absolute inset-0 bg-white/20 translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></div>
                            <iconify-icon icon="mdi:login" class="text-xl relative z-10 group-hover:animate-bounce"></iconify-icon>
                            <span class="relative z-10">تسجيل الدخول</span>
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="mx-auto flex w-[min(1160px,calc(100%-32px))] flex-1 items-center gap-12 py-16 max-[900px]:flex-col max-[900px]:items-stretch max-[900px]:pt-10">

            <section class="flex-[1.1] relative z-10">
                <div data-aos="fade-up" data-aos-delay="100" class="mb-6 inline-flex items-center gap-2 rounded-full border border-brand/20 bg-white/60 backdrop-blur-md px-4 py-2 font-bold text-brand shadow-sm hover:shadow-md transition-shadow">
                    <span class="relative flex h-3 w-3 mr-1">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-brand"></span>
                    </span>
                    <iconify-icon icon="mdi:shield-check-outline" class="text-xl"></iconify-icon>
                    منصة رقمية آمنة وموثوقة
                </div>

                <h1 data-aos="fade-up" data-aos-delay="200" class="m-0 max-w-[780px] text-[clamp(36px,5vw,66px)] font-extrabold leading-[1.2] text-brand-dark drop-shadow-sm">
                    إدارة <span class="text-transparent bg-clip-text bg-gradient-to-l from-brand to-brand-light">العضويات والاشتراكات</span>
                    <br> والقروض في مكان واحد.
                </h1>

                <p data-aos="fade-up" data-aos-delay="300" class="my-6 max-w-[660px] text-lg leading-[2] text-brand-muted">
                    صُمم هذا النظام خصيصاً لتحويل إجراءات صندوق الزمالة من ملفات ورقية ومتابعات متفرقة إلى دورة عمل آلية، واضحة، وسهلة المتابعة للموظفين والإدارة.
                </p>

                <div data-aos="fade-up" data-aos-delay="400" class="mb-10 flex flex-wrap items-center gap-4 max-[620px]:w-full">
                    @auth
                        <a class="group relative overflow-hidden inline-flex min-h-12 items-center justify-center gap-3 rounded-xl bg-gradient-to-r from-brand to-brand-light px-8 py-3.5 font-bold text-white shadow-button transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-brand/30 max-[620px]:w-full" href="{{ route('dashboard') }}">
                            <div class="absolute inset-0 w-full h-full bg-white/20 scale-x-0 group-hover:scale-x-100 origin-right transition-transform duration-500 ease-out"></div>
                            <span class="relative z-10 text-lg">ابدأ العمل الآن</span>
                            <iconify-icon icon="line-md:arrow-left" class="relative z-10 text-xl group-hover:-translate-x-1 transition-transform"></iconify-icon>
                        </a>
                    @else
                        <a class="group relative overflow-hidden inline-flex min-h-12 items-center justify-center gap-3 rounded-xl bg-gradient-to-r from-brand to-brand-light px-8 py-3.5 font-bold text-white shadow-button transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-brand/30 max-[620px]:w-full" href="{{ route('login') }}">
                            <div class="absolute inset-0 w-full h-full bg-white/20 scale-x-0 group-hover:scale-x-100 origin-right transition-transform duration-500 ease-out"></div>
                            <span class="relative z-10 text-lg">الدخول إلى النظام</span>
                            <iconify-icon icon="line-md:arrow-left" class="relative z-10 text-xl group-hover:-translate-x-1 transition-transform"></iconify-icon>
                        </a>
                        <a class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border-2 border-brand/10 bg-white/70 backdrop-blur-sm px-8 py-3.5 font-bold text-brand transition-all duration-300 hover:-translate-y-1.5 hover:bg-white hover:border-brand/30 hover:shadow-lg max-[620px]:w-full text-lg" href="{{ route('register') }}">
                            طلب حساب جديد
                        </a>
                    @endauth
                </div>

                <!-- Stats Grid -->
                <div data-aos="fade-up" data-aos-delay="500" class="grid max-w-[670px] grid-cols-3 gap-4 max-[620px]:grid-cols-1" aria-label="مميزات النظام">
                    <div class="group rounded-2xl border border-white/40 bg-white/60 backdrop-blur-xl p-[20px] shadow-glass hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 hover:bg-white/90">
                        <div class="flex items-center justify-between mb-2">
                            <strong class="text-[32px] font-extrabold leading-none text-brand">4</strong>
                            <div class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center text-brand group-hover:scale-110 transition-transform">
                                <iconify-icon icon="carbon:flow-stream" class="text-xl"></iconify-icon>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-brand-muted">مسارات مالية وتشغيلية</span>
                    </div>
                    <div class="group rounded-2xl border border-white/40 bg-white/60 backdrop-blur-xl p-[20px] shadow-glass hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 hover:bg-white/90">
                        <div class="flex items-center justify-between mb-2">
                            <strong class="text-[28px] font-extrabold leading-none text-brand pt-1">OTP</strong>
                            <div class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center text-brand group-hover:scale-110 transition-transform">
                                <iconify-icon icon="mdi:security-lock" class="text-xl"></iconify-icon>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-brand-muted">حماية واستعادة آمنة</span>
                    </div>
                    <div class="group rounded-2xl border border-white/40 bg-white/60 backdrop-blur-xl p-[20px] shadow-glass hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 hover:bg-white/90">
                        <div class="flex items-center justify-between mb-2">
                            <strong class="text-[28px] font-extrabold leading-none text-brand pt-1">RBAC</strong>
                            <div class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center text-brand group-hover:scale-110 transition-transform">
                                <iconify-icon icon="ph:users-three-fill" class="text-xl"></iconify-icon>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-brand-muted">صلاحيات دقيقة للمستخدمين</span>
                    </div>
                </div>
            </section>

            <!-- Quick Overview Widget -->
            <aside data-aos="fade-right" data-aos-delay="400" class="relative min-w-80 flex-[0.85] rounded-2xl border border-white/50 bg-white/80 backdrop-blur-xl shadow-2xl max-[900px]:min-w-0 animate-float" aria-label="وحدات النظام">
                <div class="absolute -inset-0.5 bg-gradient-to-br from-brand to-brand-gold opacity-20 rounded-2xl blur"></div>
                <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl overflow-hidden h-full">
                    <div class="flex items-center justify-between gap-3 bg-gradient-to-r from-brand to-brand-dark p-6 text-white">
                        <div>
                            <strong class="text-xl font-bold">نظرة سريعة</strong>
                            <p class="mt-1 text-sm text-white/80 font-medium">أهم العمليات اليومية للجامعة</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-white/20 backdrop-blur-md border border-white/30 px-3 py-1.5 text-[13px] font-bold shadow-sm">
                            <iconify-icon icon="line-md:confirm-circle"></iconify-icon>
                            مُفعل بالكامل
                        </span>
                    </div>

                    <div class="p-6">
                        <div class="grid gap-4">
                            <div class="group flex items-center justify-between gap-3.5 rounded-xl border-r-[6px] border-brand bg-slate-50/80 hover:bg-white p-4 shadow-sm hover:shadow-md transition-all duration-300 cursor-default">
                                <div class="flex items-center gap-3 font-extrabold text-brand-dark">
                                    <div class="p-2 rounded-lg bg-brand/10 text-brand group-hover:scale-110 transition-transform">
                                        <iconify-icon icon="mdi:account-group" class="text-2xl"></iconify-icon>
                                    </div>
                                    <span class="text-lg">العضويات</span>
                                </div>
                                <span class="text-sm font-bold text-brand-muted bg-slate-200/50 px-3 py-1 rounded-md">تسجيل ومتابعة</span>
                            </div>

                            <div class="group flex items-center justify-between gap-3.5 rounded-xl border-r-[6px] border-brand-gold bg-slate-50/80 hover:bg-white p-4 shadow-sm hover:shadow-md transition-all duration-300 cursor-default">
                                <div class="flex items-center gap-3 font-extrabold text-brand-dark">
                                    <div class="p-2 rounded-lg bg-brand-gold/15 text-brand-gold group-hover:scale-110 transition-transform">
                                        <iconify-icon icon="material-symbols:list-alt-check-rounded" class="text-2xl"></iconify-icon>
                                    </div>
                                    <span class="text-lg">الاشتراكات</span>
                                </div>
                                <span class="text-sm font-bold text-brand-muted bg-slate-200/50 px-3 py-1 rounded-md">سداد وتصدير</span>
                            </div>

                            <div class="group flex items-center justify-between gap-3.5 rounded-xl border-r-[6px] border-brand-danger bg-slate-50/80 hover:bg-white p-4 shadow-sm hover:shadow-md transition-all duration-300 cursor-default">
                                <div class="flex items-center gap-3 font-extrabold text-brand-dark">
                                    <div class="p-2 rounded-lg bg-brand-danger/10 text-brand-danger group-hover:scale-110 transition-transform">
                                        <iconify-icon icon="fluent:money-16-filled" class="text-2xl"></iconify-icon>
                                    </div>
                                    <span class="text-lg">القروض</span>
                                </div>
                                <span class="text-sm font-bold text-brand-muted bg-slate-200/50 px-3 py-1 rounded-md">أقساط واعتماد</span>
                            </div>

                            <div class="group flex items-center justify-between gap-3.5 rounded-xl border-r-[6px] border-brand bg-slate-50/80 hover:bg-white p-4 shadow-sm hover:shadow-md transition-all duration-300 cursor-default">
                                <div class="flex items-center gap-3 font-extrabold text-brand-dark">
                                    <div class="p-2 rounded-lg bg-brand/10 text-brand group-hover:scale-110 transition-transform">
                                        <iconify-icon icon="mdi:account-file" class="text-2xl"></iconify-icon>
                                    </div>
                                    <span class="text-lg">المطالبات</span>
                                </div>
                                <span class="text-sm font-bold text-brand-muted bg-slate-200/50 px-3 py-1 rounded-md">مراجعة وصرف</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </main>

        <!-- Features Section -->
        <section class="mx-auto w-[min(1160px,calc(100%-32px))] pb-16 pt-8 relative z-10">
            <div class="text-center mb-10" data-aos="fade-up">
                <span class="text-brand font-bold tracking-wider uppercase text-sm mb-2 block">المميزات</span>
                <h2 class="text-3xl font-extrabold text-brand-dark">ماذا يقدم النظام للمؤسسة؟</h2>
            </div>

            <div class="grid grid-cols-3 items-stretch gap-6 max-[900px]:grid-cols-1">

                <article data-aos="fade-up" data-aos-delay="100" class="group relative rounded-2xl border border-white/50 bg-white/70 backdrop-blur-xl p-8 shadow-glass hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand/5 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center mb-6 group-hover:bg-brand group-hover:text-white transition-colors duration-300 text-brand shadow-sm">
                        <iconify-icon class="text-3xl" icon="mdi:file-document-edit-outline"></iconify-icon>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-brand-dark">ملف عضوية متكامل</h3>
                    <p class="m-0 text-[15px] font-medium leading-[1.8] text-brand-muted">
                        إدارة دقيقة للبيانات الشخصية والوظيفية والعائلية، مع أرشفة المرفقات ودورة اعتماد رقمية واضحة لكل عضو لضمان الشفافية.
                    </p>
                </article>

                <article data-aos="fade-up" data-aos-delay="200" class="group relative rounded-2xl border border-white/50 bg-white/70 backdrop-blur-xl p-8 shadow-glass hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-gold/10 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:bg-brand-gold group-hover:text-white transition-colors duration-300 text-brand-gold shadow-sm">
                        <iconify-icon class="text-3xl" icon="mdi:cash-sync"></iconify-icon>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-brand-dark">متابعة مالية دقيقة</h3>
                    <p class="m-0 text-[15px] font-medium leading-[1.8] text-brand-muted">
                        ضبط الاشتراك الشهري، تسجيل القروض بفوائدها، خصم الأقساط أوتوماتيكياً، وحساب مطالبات نهاية الخدمة مع تنبيهات لحظية.
                    </p>
                </article>

                <article data-aos="fade-up" data-aos-delay="300" class="group relative rounded-2xl border border-white/50 bg-white/70 backdrop-blur-xl p-8 shadow-glass hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand/5 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center mb-6 group-hover:bg-brand group-hover:text-white transition-colors duration-300 text-brand shadow-sm">
                        <iconify-icon class="text-3xl" icon="mdi:chart-box-outline"></iconify-icon>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-brand-dark">رؤية إدارية أفضل</h3>
                    <p class="m-0 text-[15px] font-medium leading-[1.8] text-brand-muted">
                        لوحة تحكم تفاعلية وتقارير مفصلة لمساعدة الإدارة والفريق التشغيلي على اتخاذ قرارات مالية أسرع وأكثر استناداً للبيانات.
                    </p>
                </article>

            </div>
        </section>

        <!-- Footer area (optional on landing page) -->
        <footer class="mt-auto border-t border-brand/10 bg-white/40 backdrop-blur-sm py-6">
            <div class="mx-auto flex w-[min(1160px,calc(100%-32px))] items-center justify-between text-sm font-semibold text-brand-muted max-[620px]:flex-col max-[620px]:gap-3">
                <p>&copy; {{ date('Y') }} جامعة العاصمة. جميع الحقوق محفوظة.</p>
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1.5"><iconify-icon icon="mdi:shield-lock-outline" class="text-lg"></iconify-icon> نظام آمن</span>
                    <span class="flex items-center gap-1.5"><iconify-icon icon="mdi:server-network" class="text-lg"></iconify-icon> متاح 24/7</span>
                </div>
            </div>
        </footer>
    </div>

    <!-- AOS Animation Init -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
        });
    </script>
</body>

</html>
