<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صندوق الزمالة - {{ config('app.name', 'HU Capital') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                            soft: '#eef7ff',
                            gold: '#d4af37',
                            danger: '#d92d20',
                            ink: '#021219',
                            muted: '#5f6f7d',
                        },
                    },
                    boxShadow: {
                        soft: '0 20px 55px rgba(8, 43, 80, 0.14)',
                        button: '0 10px 22px rgba(18, 67, 117, 0.24)',
                        logo: '0 8px 24px rgba(18, 67, 117, 0.12)',
                    },
                },
            },
        }
    </script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
</head>

<body class="min-h-screen overflow-x-hidden bg-brand-soft font-cairo text-brand-ink">
    <div class="fixed inset-0 -z-10">
        <img class="h-full w-full object-cover" src="{{ asset('IMGs/HU IMG.png') }}" alt="">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-soft/95 to-white/80"></div>
    </div>

    <div class="flex min-h-screen flex-col">
        <header class="py-5">
            <div class="mx-auto flex w-[min(1160px,calc(100%-32px))] items-center justify-between gap-4 max-[620px]:flex-col max-[620px]:items-start">
                <a class="flex items-center gap-3 font-extrabold text-brand" href="{{ url('/') }}" aria-label="صندوق الزمالة">
                    <img class="h-[58px] w-[58px] rounded-xl bg-white object-contain p-2 shadow-logo" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="شعار الجامعة">
                    <span>
                        صندوق الزمالة كابيتال
                        <small class="mt-0.5 block text-[13px] font-semibold text-brand-muted">نظام إدارة الزمالة الجامعي</small>
                    </span>
                </a>

                <nav class="flex flex-wrap items-center gap-2.5 max-[620px]:w-full" aria-label="روابط الدخول">
                    @auth
                        <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-brand px-5 py-2.5 font-bold text-white shadow-button transition hover:-translate-y-0.5 hover:bg-brand-dark max-[620px]:w-full" href="{{ route('dashboard') }}">
                            <iconify-icon icon="mdi:view-dashboard"></iconify-icon>
                            لوحة التحكم
                        </a>
                    @else
                        <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-brand/15 bg-white/90 px-5 py-2.5 font-bold text-brand transition hover:-translate-y-0.5 max-[620px]:w-full" href="{{ route('register') }}">إنشاء حساب</a>
                        <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-brand px-5 py-2.5 font-bold text-white shadow-button transition hover:-translate-y-0.5 hover:bg-brand-dark max-[620px]:w-full" href="{{ route('login') }}">
                            <iconify-icon icon="mdi:login"></iconify-icon>
                            تسجيل الدخول
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="mx-auto flex w-[min(1160px,calc(100%-32px))] flex-1 items-center gap-10 py-10 pb-14 max-[900px]:flex-col max-[900px]:items-stretch max-[900px]:pt-6">
            <section class="flex-[1.05]">
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand/15 bg-brand-soft/95 px-3.5 py-2 font-bold text-brand">
                    <iconify-icon icon="mdi:shield-check"></iconify-icon>
                    منصة رقمية آمنة لإدارة صندوق الزمالة
                </div>

                <h1 class="m-0 max-w-[780px] text-[clamp(34px,5vw,62px)] font-extrabold leading-[1.16] text-brand-dark">
                    إدارة العضويات والاشتراكات والقروض والمطالبات في مكان واحد.
                </h1>

                <p class="my-5 mb-7 max-w-[660px] text-lg leading-[1.9] text-brand-muted">
                    صمم هذا النظام لتحويل إجراءات صندوق الزمالة من ملفات ورقية ومتابعات متفرقة
                    إلى دورة عمل واضحة، موثقة، وسهلة المتابعة للموظفين والإدارة.
                </p>

                <div class="mb-7 flex flex-wrap items-center gap-3 max-[620px]:w-full">
                    @auth
                        <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-brand px-5 py-2.5 font-bold text-white shadow-button transition hover:-translate-y-0.5 hover:bg-brand-dark max-[620px]:w-full" href="{{ route('dashboard') }}">ابدأ من لوحة التحكم</a>
                    @else
                        <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-brand px-5 py-2.5 font-bold text-white shadow-button transition hover:-translate-y-0.5 hover:bg-brand-dark max-[620px]:w-full" href="{{ route('login') }}">الدخول إلى النظام</a>
                        <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-brand/15 bg-white/90 px-5 py-2.5 font-bold text-brand transition hover:-translate-y-0.5 max-[620px]:w-full" href="{{ route('register') }}">طلب حساب جديد</a>
                    @endauth
                </div>

                <div class="grid max-w-[670px] grid-cols-3 gap-3 max-[620px]:grid-cols-1" aria-label="مميزات النظام">
                    <div class="rounded-lg border border-brand/15 bg-white/90 p-[18px] shadow-soft">
                        <strong class="mb-2 block text-[28px] leading-none text-brand">4</strong>
                        <span class="text-sm font-semibold text-brand-muted">مسارات مالية وتشغيلية</span>
                    </div>
                    <div class="rounded-lg border border-brand/15 bg-white/90 p-[18px] shadow-soft">
                        <strong class="mb-2 block text-[28px] leading-none text-brand">OTP</strong>
                        <span class="text-sm font-semibold text-brand-muted">استعادة كلمة مرور آمنة</span>
                    </div>
                    <div class="rounded-lg border border-brand/15 bg-white/90 p-[18px] shadow-soft">
                        <strong class="mb-2 block text-[28px] leading-none text-brand">RBAC</strong>
                        <span class="text-sm font-semibold text-brand-muted">صلاحيات حسب دور المستخدم</span>
                    </div>
                </div>
            </section>

            <aside class="min-w-80 flex-[0.85] overflow-hidden rounded-lg border border-brand/15 bg-white/90 shadow-soft max-[900px]:min-w-0" aria-label="وحدات النظام">
                <div class="flex items-center justify-between gap-3 bg-brand p-5 text-white">
                    <div>
                        <strong>نظرة سريعة</strong>
                        <p class="mt-1 text-sm text-white/75">أهم العمليات اليومية داخل النظام</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-white/15 px-3 py-1.5 text-[13px] font-bold">
                        <iconify-icon icon="mdi:check-circle"></iconify-icon>
                        جاهز للعمل
                    </span>
                </div>

                <div class="p-5">
                    <div class="grid gap-3">
                        <div class="flex items-center justify-between gap-3.5 rounded-lg border-r-[5px] border-brand bg-slate-50 p-3.5">
                            <div class="flex items-center gap-2.5 font-extrabold text-brand-dark">
                                <iconify-icon icon="mdi:account-group"></iconify-icon>
                                العضويات
                            </div>
                            <span class="text-[13px] font-bold text-brand-muted">تسجيل ومتابعة</span>
                        </div>
                        <div class="flex items-center justify-between gap-3.5 rounded-lg border-r-[5px] border-brand-gold bg-slate-50 p-3.5">
                            <div class="flex items-center gap-2.5 font-extrabold text-brand-dark">
                                <iconify-icon icon="material-symbols:list-alt-check-rounded"></iconify-icon>
                                الاشتراكات
                            </div>
                            <span class="text-[13px] font-bold text-brand-muted">سداد وتصدير</span>
                        </div>
                        <div class="flex items-center justify-between gap-3.5 rounded-lg border-r-[5px] border-brand-danger bg-slate-50 p-3.5">
                            <div class="flex items-center gap-2.5 font-extrabold text-brand-dark">
                                <iconify-icon icon="fluent:money-16-filled"></iconify-icon>
                                القروض
                            </div>
                            <span class="text-[13px] font-bold text-brand-muted">أقساط واعتماد</span>
                        </div>
                        <div class="flex items-center justify-between gap-3.5 rounded-lg border-r-[5px] border-brand bg-slate-50 p-3.5">
                            <div class="flex items-center gap-2.5 font-extrabold text-brand-dark">
                                <iconify-icon icon="mdi:account-file"></iconify-icon>
                                المطالبات
                            </div>
                            <span class="text-[13px] font-bold text-brand-muted">مراجعة وصرف</span>
                        </div>
                    </div>
                </div>
            </aside>
        </main>

        <section class="mx-auto w-[min(1160px,calc(100%-32px))] pb-11">
            <h2 class="mb-4 text-2xl font-bold text-brand-dark">ماذا يقدم المشروع؟</h2>
            <div class="flex items-stretch gap-3.5 max-[900px]:flex-col">
                <article class="flex-1 rounded-lg border border-brand/15 bg-white/90 p-5 shadow-soft">
                    <iconify-icon class="text-[34px] text-brand" icon="mdi:file-document-edit"></iconify-icon>
                    <h3 class="mb-2 mt-3 text-lg font-bold text-brand-dark">ملف عضوية متكامل</h3>
                    <p class="m-0 text-sm leading-[1.8] text-brand-muted">بيانات شخصية ووظيفية وعائلية ومرفقات، مع دورة اعتماد واضحة لكل عضو.</p>
                </article>
                <article class="flex-1 rounded-lg border border-brand/15 bg-white/90 p-5 shadow-soft">
                    <iconify-icon class="text-[34px] text-brand" icon="mdi:cash-sync"></iconify-icon>
                    <h3 class="mb-2 mt-3 text-lg font-bold text-brand-dark">متابعة مالية دقيقة</h3>
                    <p class="m-0 text-sm leading-[1.8] text-brand-muted">إدارة الاشتراكات، القروض، الأقساط، والمطالبات مع تنبيهات للمهام المستحقة.</p>
                </article>
                <article class="flex-1 rounded-lg border border-brand/15 bg-white/90 p-5 shadow-soft">
                    <iconify-icon class="text-[34px] text-brand" icon="mdi:chart-box"></iconify-icon>
                    <h3 class="mb-2 mt-3 text-lg font-bold text-brand-dark">رؤية إدارية أفضل</h3>
                    <p class="m-0 text-sm leading-[1.8] text-brand-muted">لوحة تحكم وتقارير تساعد الفريق على اتخاذ قرارات أسرع وأكثر شفافية.</p>
                </article>
            </div>
        </section>
    </div>
</body>

</html>
