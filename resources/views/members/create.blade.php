<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>استمارة العضوية - صندوق الزمالة</title>

    <link rel="icon" href="{{ asset('IMGs/Hu Logo 1.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif']
                    },
                    colors: {
                        primary: '#124375',
                        'primary-light': '#27568f',
                        'primary-dark': '#0f2a4a',
                        navbar: '#eef7ff',
                        body: '#f4f7f9',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            .print-page {
                max-width: none !important;
                padding: 0 !important;
            }

            .form-section {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-body text-primary antialiased">
    @section('navbar_back_url', route('members.index'))
    <div class="no-print">
        @include('partials.navbar')
    </div>

    @if (false)
        <header class="hidden">
            <div class="mx-auto flex h-[68px] max-w-[1220px] items-center justify-between px-5">
                <div class="flex items-center gap-2">
                    <a href="{{ route('members.index') }}"
                        class="inline-flex h-10 items-center gap-2 rounded-md bg-primary px-4 text-sm font-bold text-white transition hover:bg-primary-light">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                        رجوع
                    </a>
                    <img src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="شعار جامعة العاصمة"
                        class="h-11 w-11 rounded object-contain">
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-lg font-extrabold">صندوق الزمالة</span>
                    <img src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="شعار صندوق الزمالة"
                        class="h-10 w-10 rounded object-contain">
                </div>

                <div class="flex items-center gap-2 text-primary">
                    <button type="button"
                        class="relative flex h-10 w-10 items-center justify-center rounded-md transition hover:bg-primary/10"
                        title="الإشعارات">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    </button>
                    <button type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-md transition hover:bg-primary/10"
                        title="الملف الشخصي">
                        <i class="fa-solid fa-user text-xl"></i>
                    </button>
                </div>
            </div>
        </header>
    @endif

    @php
        $printMode = $printMode ?? false;
        $person = $member->person ?? null;
        $employment = isset($member) ? $member->employments->first() : null;
        $spouse = isset($member) ? $member->familyMembers->firstWhere('relationship', 'spouse') : null;
        $child = isset($member) ? $member->familyMembers->whereIn('relationship', ['son', 'daughter'])->first() : null;
        $birthParts = $person && $person->date_of_birth ? explode('-', $person->date_of_birth) : [null, null, null];
        $hireParts = $employment && $employment->hire_date ? explode('-', $employment->hire_date) : [null, null, null];
        $nationalDigits = old('national_id_digits', $person ? str_split($person->national_id) : []);
        $phoneDigits = old('phone_digits', $person && $person->phone ? str_split($person->phone) : []);
    @endphp

    <main class="print-page mx-auto max-w-[1220px] px-5 py-5 pb-10">
        @if (session('success'))
            <div
                class="no-print mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="no-print mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
                برجاء مراجعة البيانات المطلوبة قبل الحفظ.
            </div>
        @endif

        <div class="mb-5 grid grid-cols-3 items-center gap-4 no-print">
            <a href="{{ route('members.index') }}"
                class="inline-flex h-11 w-32 items-center justify-center rounded-md border border-red-200 text-sm font-bold text-red-600 transition hover:bg-red-50">
                إلغاء
            </a>
            <h1 class="text-center text-xl font-extrabold underline underline-offset-4">استمارة العضوية</h1>
            <div class="flex justify-end">
                <img src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="شعار جامعة العاصمة"
                    class="h-14 w-14 rounded border border-primary/20 bg-white object-contain p-1">
            </div>
        </div>

        <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data" class="space-y-7">
            @csrf

            <section
                class="form-section relative rounded-md border-[1.5px] border-primary bg-body px-4 pb-5 pt-6 shadow-[inset_0_0_0_1px_rgba(18,67,117,0.04)]">
                <h2 class="absolute -top-3 right-5 w-fit bg-body px-3 text-sm font-bold">البيانات الشخصية</h2>

                <div class="grid grid-cols-1 gap-4 pt-2 lg:grid-cols-12">
                    <div class="lg:col-span-6">
                        <label class="mb-1 block text-sm font-bold">الاسم رباعي <span
                                class="text-red-600">*</span></label>
                        <input name="full_name" type="text" value="{{ old('full_name', $person?->full_name) }}"
                            placeholder="مثال : أحمد محمد إبراهيم محمود"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="lg:col-span-6">
                        <label class="mb-1 block text-sm font-bold">البريد الإلكتروني <span
                                class="text-red-600">*</span></label>
                        <input name="email" type="email" value="{{ old('email', $person?->email) }}"
                            placeholder="مثال : ahmed@gmail.com"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="lg:col-span-7">
                        <label class="mb-1 block text-sm font-bold">الرقم القومي <span
                                class="text-red-600">*</span></label>
                        <div class="grid grid-cols-7 gap-1 sm:grid-cols-[repeat(14,minmax(0,1fr))]">
                            @for ($i = 1; $i <= 14; $i++)
                                <input name="national_id_digits[]" inputmode="numeric" maxlength="1"
                                    value="{{ $nationalDigits[$i - 1] ?? '' }}"
                                    class="h-8 rounded-md border border-primary/25 bg-white/50 text-center text-sm outline-none focus:border-primary">
                            @endfor
                        </div>
                    </div>
                    <div class="lg:col-span-5 ">
                        <label class="mb-1 block text-sm font-bold">رقم هاتف العضو <span
                                class="text-red-600">*</span></label>
                        <div class="grid grid-cols-5 gap-1 sm:grid-cols-10">
                            @for ($i = 1; $i <= 10; $i++)
                                <input name="phone_digits[]" inputmode="numeric" maxlength="1"
                                    value="{{ $phoneDigits[$i - 1] ?? '' }}"
                                    class="h-8 rounded-md border border-primary/25 bg-white/50 text-center text-sm outline-none focus:border-primary">
                            @endfor
                        </div>
                    </div>

                    <div class="lg:col-span-9">
                        <label class="mb-1 block text-sm font-bold">تاريخ الميلاد <span
                                class="text-red-600">*</span></label>
                        <div class="grid grid-cols-3 gap-1">
                            <input name="birth_day" inputmode="numeric"
                                value="{{ old('birth_day', $birthParts[2] ?? null) }}" placeholder="اليوم"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                            <input name="birth_month" inputmode="numeric"
                                value="{{ old('birth_month', $birthParts[1] ?? null) }}" placeholder="الشهر"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                            <input name="birth_year" inputmode="numeric"
                                value="{{ old('birth_year', $birthParts[0] ?? null) }}" placeholder="السنة"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="mb-1 block text-sm font-bold">الحالة الاجتماعية <span
                                class="text-red-600">*</span></label>
                        <select name="marital_status"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-sm outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="">اختر</option>
                            <option value="single" @selected(old('marital_status', $person?->marital_status) === 'single')>أعزب</option>
                            <option value="married" @selected(old('marital_status', $person?->marital_status) === 'married')>متزوج</option>
                            <option value="divorced" @selected(old('marital_status', $person?->marital_status) === 'divorced')>مطلق</option>
                            <option value="widowed" @selected(old('marital_status', $person?->marital_status) === 'widowed')>أرمل</option>
                        </select>
                    </div>

                    <div class="lg:col-span-1">
                        <label class="mb-1 block text-sm font-bold">النوع <span class="text-red-600">*</span></label>
                        <select name="gender"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-sm outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="male" @selected(old('gender', $person?->gender) === 'male')>ذكر</option>
                            <option value="female" @selected(old('gender', $person?->gender) === 'female')>أنثى</option>
                        </select>
                    </div>

                    <div class="lg:col-span-12">
                        <label class="mb-1 block text-sm font-bold">عنوان محل الإقامة <span
                                class="text-red-600">*</span></label>
                        <input name="address" type="text" value="{{ old('address', $person?->address) }}"
                            placeholder="كما في البطاقة"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
            </section>

            <section
                class="form-section relative rounded-md border-[1.5px] border-primary bg-body px-4 pb-5 pt-6 shadow-[inset_0_0_0_1px_rgba(18,67,117,0.04)]">
                <h2 class="absolute -top-3 right-5 w-fit bg-body px-3 text-sm font-bold">البيانات الوظيفية</h2>

                <div class="grid grid-cols-1 gap-4 pt-2 lg:grid-cols-12">
                    <div class="lg:col-span-9">
                        <label class="mb-1 block text-sm font-bold">جهة العمل <span
                                class="text-red-600">*</span></label>
                        <input name="employer_name" type="text"
                            value="{{ old('employer_name', $employment?->employer_name) }}"
                            placeholder="مثال : كلية تجارة وإدارة أعمال"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="lg:col-span-3">
                        <label class="mb-1 block text-sm font-bold">تاريخ استلام العمل <span
                                class="text-red-600">*</span></label>
                        <div class="grid grid-cols-3 gap-1">
                            <input name="hire_day" value="{{ old('hire_day', $hireParts[2] ?? null) }}"
                                placeholder="اليوم"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                            <input name="hire_month" value="{{ old('hire_month', $hireParts[1] ?? null) }}"
                                placeholder="الشهر"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                            <input name="hire_year" value="{{ old('hire_year', $hireParts[0] ?? null) }}"
                                placeholder="السنة"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                        </div>
                    </div>

                    <div class="lg:col-span-9">
                        <label class="mb-1 block text-sm font-bold">الوظيفة <span
                                class="text-red-600">*</span></label>
                        <input name="job_title" type="text"
                            value="{{ old('job_title', $employment?->job_title) }}"
                            placeholder="مثال : مدرس مساعد مادة المحاسبة"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="lg:col-span-3">
                        <label class="mb-1 block text-sm font-bold">تاريخ الإحالة إلى المعاش <span
                                class="text-red-600">*</span></label>
                        <div class="grid grid-cols-3 gap-1">
                            <input name="retirement_day" placeholder="اليوم"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                            <input name="retirement_month" placeholder="الشهر"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                            <input name="retirement_year" placeholder="السنة"
                                class="h-9 rounded-md border border-primary/25 bg-white/50 px-2 text-center text-sm outline-none focus:border-primary">
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <label class="mb-1 block text-sm font-bold">الفئة المالية الحالية <span
                                class="text-red-600">*</span></label>
                        <input name="current_financial_grade" type="text" placeholder="مثال : الفئة الثالثة"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="lg:col-span-7">
                        <label class="mb-1 block text-sm font-bold">المرتب الشهري الأساسي عند التعيين <span
                                class="text-red-600">*</span></label>
                        <input name="salary" type="number" step="0.01"
                            value="{{ old('salary', $employment?->salary) }}" placeholder="مثال : 360"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
            </section>

            <section
                class="form-section relative rounded-md border-[1.5px] border-primary bg-body px-4 pb-5 pt-6 shadow-[inset_0_0_0_1px_rgba(18,67,117,0.04)]">
                <h2 class="absolute -top-3 right-5 w-fit bg-body px-3 text-sm font-bold">البيانات العائلية</h2>

                <div class="grid grid-cols-1 gap-4 pt-2 lg:grid-cols-12">
                    <div class="lg:col-span-3">
                        <label class="mb-1 block text-sm font-bold">عدد الأبناء <span
                                class="text-red-600">*</span></label>
                        <input name="children_count" inputmode="numeric" placeholder="0"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="lg:col-span-9">
                        <label class="mb-1 block text-sm font-bold">رقم تليفون الزوج أو الزوجة أو أحد الأبناء أو أحد
                            الأقارب <span class="text-red-600">*</span></label>
                        <div class="grid grid-cols-5 gap-1 sm:grid-cols-10">
                            @for ($i = 1; $i <= 10; $i++)
                                <input name="relative_phone_digits[]" inputmode="numeric" maxlength="1"
                                    class="h-8 rounded-md border border-primary/25 bg-white/50 text-center text-sm outline-none focus:border-primary">
                            @endfor
                        </div>
                    </div>

                    <div class="lg:col-span-6">
                        <label class="mb-1 block text-sm font-bold">اسم الزوج أو الزوجة <span
                                class="text-red-600">*</span></label>
                        <input name="spouse_name" type="text" value="{{ old('spouse_name', $spouse?->name) }}"
                            placeholder="مثال : رباب عبد الحليم أحمد محمد"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="lg:col-span-6">
                        <label class="mb-1 block text-sm font-bold">وظيفته أو جهة عمله <span
                                class="text-red-600">*</span></label>
                        <input name="spouse_job" type="text" placeholder="مثال : محاسبة بشركة الحديد والصلب"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="lg:col-span-6">
                        <label class="mb-1 block text-sm font-bold">اسم أحد الأبناء <span
                                class="text-red-600">*</span></label>
                        <input name="child_name" type="text" value="{{ old('child_name', $child?->name) }}"
                            placeholder="مثال : لا يوجد"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="lg:col-span-6">
                        <label class="mb-1 block text-sm font-bold">وظيفته أو جهة عمله <span
                                class="text-red-600">*</span></label>
                        <input name="child_job" type="text" placeholder="مثال : لا يوجد"
                            class="h-10 w-full rounded-md border border-primary bg-white/40 px-3 text-center text-sm outline-none transition focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
            </section>

            @unless ($printMode)
                <section
                    class="form-section relative rounded-md border-[1.5px] border-primary bg-body px-4 pb-5 pt-6 shadow-[inset_0_0_0_1px_rgba(18,67,117,0.04)]">
                    <h2 class="absolute -top-3 right-5 w-fit bg-body px-3 text-sm font-bold">المرفقات</h2>

                    <div class="grid grid-cols-1 gap-4 pt-2 md:grid-cols-2">
                        @foreach ($documentTypes ?? [] as $type => $document)
                            <label class="block">
                                <span class="mb-1 block text-sm font-bold">{{ $document }} <span
                                        class="text-red-600">*</span></span>
                                <span
                                    class="flex h-10 cursor-pointer items-center justify-center gap-2 rounded-md border border-primary bg-white/40 text-sm font-bold transition hover:bg-primary/5">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    إرفاق المستند المطلوب
                                </span>
                                <input type="file" name="documents[{{ $type }}]" class="sr-only">
                            </label>
                        @endforeach
                    </div>
                </section>
            @endunless

            <section class="form-section space-y-5 py-2 text-slate-950">
                <p class="font-bold">توقيع طالب العضوية بصحة البيانات</p>
                <h2 class="text-center text-base font-extrabold underline underline-offset-4">إقرار</h2>
                <p class="leading-9">
                    أقر أنا / <span class="inline-block min-w-[190px] border-b border-slate-500"></span>
                    باطلاعي على اللائحة التنفيذية الخاصة بصندوق التأمين الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين
                    بجامعة حلوان، وأقبل عضويتي في الصندوق اعتبارا من
                    <span class="inline-block min-w-[90px] border-b border-slate-500"></span> /
                    <span class="inline-block min-w-[90px] border-b border-slate-500"></span> /
                    <span class="inline-block min-w-[90px] border-b border-slate-500"></span>
                    وأوافق على خصم قيمة قسط المشاركة خصما من مكافآت الامتحانات المستحقة لي كل عام بما يعادل قيمة جملة
                    الأقساط السنوية.
                </p>
                <p class="text-left">تحريرا في : <span
                        class="inline-block min-w-[90px] border-b border-slate-500"></span> مارس 2026</p>

                <div class="grid grid-cols-1 gap-6 pt-2 md:grid-cols-4">
                    <div>الاسم / <span class="inline-block min-w-[140px] border-b border-slate-500"></span></div>
                    <div>الوظيفة / <span class="inline-block min-w-[140px] border-b border-slate-500"></span></div>
                    <div>الرقم القومي / <span class="inline-block min-w-[140px] border-b border-slate-500"></span>
                    </div>
                    <div>التوقيع / <span class="inline-block min-w-[140px] border-b border-slate-500"></span></div>
                </div>

                <div class="pt-3">
                    <p class="mb-6">المقر بما فيه</p>
                    <p class="mr-12">مدير الإدارة</p>
                    <p class="mt-6">ويعتمد ، <span
                            class="inline-block min-w-[180px] border-b border-slate-500"></span></p>
                </div>
            </section>

            <div class="no-print flex justify-center pt-1">
                <button type="{{ $printMode ? 'button' : 'submit' }}"
                    @if ($printMode) onclick="window.print()" @endif
                    class="inline-flex h-11 w-full max-w-[840px] items-center justify-center gap-3 rounded-md bg-slate-300 text-sm font-bold text-slate-600 transition hover:bg-slate-400 hover:text-slate-800">
                    {{ $printMode ? 'طباعة الاستمارة' : 'حفظ البيانات وطباعة الاستمارة' }}
                    <i class="fa-solid fa-print"></i>
                </button>
            </div>
        </form>

        @if ($printMode && isset($member))
            <form method="POST" action="{{ route('members.signed-form', $member) }}" enctype="multipart/form-data"
                class="no-print mt-6 rounded-md border-[1.5px] border-primary bg-body px-4 py-5">
                @csrf
                <label class="mb-2 block text-sm font-bold">رفع الاستمارة بعد التوقيع</label>
                <div class="flex flex-col gap-3 md:flex-row">
                    <input type="file" name="signed_form"
                        class="h-11 flex-1 rounded-md border border-primary bg-white/50 px-3 py-2 text-sm">
                    <button type="submit"
                        class="h-11 rounded-md bg-primary px-6 text-sm font-bold text-white transition hover:bg-primary-light">
                        حفظ الاستمارة الموقعة
                    </button>
                </div>
            </form>
        @endif
    </main>

    <footer class="border-t-[2.5px] border-primary bg-navbar px-6 py-3 text-center text-sm font-bold text-primary">
        جميع الحقوق محفوظة لجامعة العاصمة لعام 2026 <i class="fa-solid fa-copyright mr-1"></i>
    </footer>

    @if ($printMode && session('success'))
        <script>
            window.addEventListener('load', function() {
                window.print();
            });
        </script>
    @endif
</body>

</html>
