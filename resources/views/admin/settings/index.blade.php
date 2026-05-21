@extends('layouts.app')

@section('title', 'لوحة تحكم الإعدادات')

@include('partials.flash')

@section('content')
    link rel="stylesheet" href="{{ asset('css/settings.css') }}">
    <main class="flex-1 py-5 px-3">
        <!-- start header main -->
        <div class="flex flex-col gap-2 mb-6">
            <h2 class="text-[#021219] text-xl font-semibold flex items-center gap-2">
                <span>إعدادات اللائحة الأساسية</span>
                <iconify-icon icon="mdi:cog-outline" class="text-2xl text-[#124375]"></iconify-icon>
            </h2>
            <p class="text-[#6D6D6D] text-base font-normal">
                إدارة المتغيرات والمحددات الآلية لصندوق التأمين الخاص لأعضاء هيئة التدريس والعاملين بجامعة العاصمة
            </p>
        </div>
        <!-- end header main -->

        <div class="bg-white border border-[#124375] surface-shadow rounded-2xl overflow-hidden flex flex-row min-h-[700px]"
            dir="rtl">

            <!-- INNER RIGHT MENU -->
            <aside
                class="w-[320px] px-4 py-8 bg-[#EEF7FF] border-l border-[#124375] flex flex-col justify-start items-center gap-6 flex-shrink-0">
                <!-- ITEM (Basic) -->
                <div data-tab="basic" data-title="البيانات الأساسية للصندوق"
                    class="tab-button surface-shadow rounded-xl text-base font-semibold px-4 py-3 flex items-center gap-3.5 text-[#124375] bg-[#f7f9fc] w-full cursor-pointer transition-all duration-300 transform active:scale-[0.98] hover:translate-x-[-4px]">
                    <iconify-icon icon="material-symbols:list-alt-check-rounded" width="24" height="24">
                    </iconify-icon>
                    <div class="tab-label text-right">البيانات الأساسية للصندوق</div>
                </div>

                <!-- ITEM (Subscriptions) -->
                <div data-tab="subscriptions" data-title="الاشتراكات والرسوم"
                    class="tab-button rounded-xl text-base font-medium px-4 py-3 flex items-center gap-3.5 text-[#6D6D6D] hover:bg-white hover:text-[#124375] w-full cursor-pointer transition-all duration-300 transform active:scale-[0.98] hover:translate-x-[-4px]">
                    <iconify-icon icon="tabler:clipboard-list-filled" width="24" height="24" class="text-gray-500">
                    </iconify-icon>
                    <div class="tab-label text-right">الاشتراكات والرسوم</div>
                </div>

                <!-- ITEM (Loans) -->
                <div data-tab="loans" data-title="القروض والتمويل"
                    class="tab-button rounded-xl text-base font-medium px-4 py-3 flex items-center gap-3.5 text-[#6D6D6D] hover:bg-white hover:text-[#124375] w-full cursor-pointer transition-all duration-300 transform active:scale-[0.98] hover:translate-x-[-4px]">
                    <iconify-icon icon="fluent:money-16-filled" width="24" height="24" class="text-gray-500">
                    </iconify-icon>
                    <div class="tab-label text-right">القروض والتمويل</div>
                </div>

                <!-- ITEM (Claims) -->
                <div data-tab="claims" data-title="المزايا التأمينية"
                    class="tab-button rounded-xl text-base font-medium px-4 py-3 flex items-center gap-3.5 text-[#6D6D6D] hover:bg-white hover:text-[#124375] w-full cursor-pointer transition-all duration-300 transform active:scale-[0.98] hover:translate-x-[-4px]">
                    <iconify-icon icon="ph:user-list-fill" width="24" height="24" class="text-gray-500">
                    </iconify-icon>
                    <div class="tab-label text-right">المزايا التأمينية</div>
                </div>
            </aside>

            <!-- FORM -->
            <form method="POST" action="{{ route('admin.settings.update') }}"
                class="flex-1 px-6 py-8 bg-[#f7f9fc] flex flex-col justify-between items-stretch gap-8">
                @csrf
                <div class="self-stretch flex flex-col justify-start items-end gap-8">
                    <!-- FORM TITLE -->
                    <div id="form-title" class="flex items-center gap-4 self-stretch justify-start mb-4">
                        <div id="form-title-icon-container" class="size-8 flex justify-center items-center flex-shrink-0">
                            <iconify-icon icon="ph:user-list-fill" width="28" height="28"
                                class="text-[#1e5a97]"></iconify-icon>
                        </div>
                        <div id="form-title-text"
                            class="text-right text-[#021219] text-xl font-semibold font-['Noto_Sans_Arabic']">البيانات
                            الأساسية للصندوق</div>
                    </div>

                    <!-- TAB 1: Basic -->
                    <div id="tab-basic-content" class="tab-content w-full">
                        <!-- NAME -->
                        <div class="relative mb-12">
                            <label
                                class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                اسم الصندوق
                                <iconify-icon icon="mdi:pencil"
                                    class="absolute left--8 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                            </label>
                            <div class="relative">
                                <input type="text" name="system_name"
                                    value="{{ old('system_name', $settings['system_name']) }}"
                                    class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('system_name') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                    required>
                            </div>
                            <p class="mt-3 text-right text-[#b4b4b4] text-lg font-['Noto_Sans_Arabic']">
                                الاسم الرسمي كما هو مسجل في الهيئة العامة للرقابة المالية أو جهة الإدارة
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- RETIREMENT -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    <span>
                                        سن التقاعد القانوني
                                    </span>
                                    <iconify-icon icon="mdi:calendar"
                                        class="absolute left--8 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </label>
                                <div class="relative">
                                    <input type="number" name="retirement_age"
                                        value="{{ old('retirement_age', $settings['retirement_age']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('retirement_age') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        عاماً</div>
                                </div>
                            </div>

                            <!-- CURRENCY -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    <span>
                                        العملة الافتراضية
                                    </span>
                                    <iconify-icon icon="mdi:currency-usd-circle-outline"
                                        class="absolute left--8 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </label>
                                <div class="relative">
                                    <input type="text" name="default_currency"
                                        value="{{ old('default_currency', $settings['default_currency']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('default_currency') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Subscriptions -->
                    <div id="tab-subscriptions-content" class="tab-content w-full hidden">
                        <!-- SUBSCRIPTION AND JOIN FEE -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- SUBSCRIPTION AMOUNT -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    قيمة الاشتراك الشهري
                                </label>
                                <div class="relative">
                                    <input type="number" name="subscription_amount"
                                        value="{{ old('subscription_amount', $settings['subscription_amount']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('subscription_amount') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        ج.م</div>
                                    <iconify-icon icon="mdi:cash-multiple"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>

                            <!-- JOIN FEE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    رسوم الانضمام للعضوية
                                </label>
                                <div class="relative">
                                    <input type="number" name="membership_join_fee"
                                        value="{{ old('membership_join_fee', $settings['membership_join_fee']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('membership_join_fee') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        ج.م</div>
                                    <iconify-icon icon="mdi:card-account-details-outline"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>
                        </div>

                        <!-- AGE LIMITS -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- MIN AGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    الحد الأدنى لسن التسجيل
                                </label>
                                <div class="relative">
                                    <input type="number" name="membership_min_age"
                                        value="{{ old('membership_min_age', $settings['membership_min_age']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('membership_min_age') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        عاماً</div>
                                    <iconify-icon icon="mdi:account-arrow-up"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>

                            <!-- MAX AGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    الحد الأقصى لسن التسجيل
                                </label>
                                <div class="relative">
                                    <input type="number" name="membership_max_age"
                                        value="{{ old('membership_max_age', $settings['membership_max_age']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('membership_max_age') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        عاماً</div>
                                    <iconify-icon icon="mdi:account-arrow-down"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: Loans -->
                    <div id="tab-loans-content" class="tab-content w-full hidden">
                        <!-- ROW 1: Percentage & Interest -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- LOAN PERCENTAGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    نسبة القرض من إجمالي الاشتراكات
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_percentage"
                                        value="{{ old('loan_percentage', $settings['loan_percentage']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('loan_percentage') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        %</div>
                                    <iconify-icon icon="mdi:percent"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>

                            <!-- INTEREST RATE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    معدل الفائدة السنوية
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_interest_rate"
                                        value="{{ old('loan_interest_rate', $settings['loan_interest_rate']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('loan_interest_rate') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        %</div>
                                    <iconify-icon icon="mdi:percent"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 2: Max Amount & Repayment -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- MAX AMOUNT -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    الحد الأقصى للقرض الشخصي
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_max_amount"
                                        value="{{ old('loan_max_amount', $settings['loan_max_amount']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('loan_max_amount') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        ج.م</div>
                                    <iconify-icon icon="mdi:cash"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>

                            <!-- REPAYMENT MONTHS -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    أقصى فترة سداد للقرض
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_repayment_months"
                                        value="{{ old('loan_repayment_months', $settings['loan_repayment_months']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('loan_repayment_months') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        شهراً</div>
                                    <iconify-icon icon="mdi:calendar-clock"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 3: Centered Min Subscription Years -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    الحد الأدنى للاشتراك لطلب القرض
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_min_years_subscribed"
                                        value="{{ old('loan_min_years_subscribed', $settings['loan_min_years_subscribed']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('loan_min_years_subscribed') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        سنوات</div>
                                    <iconify-icon icon="mdi:history"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>
                            <div class="relative"></div> <!-- Spacer column -->
                        </div>
                    </div>

                    <!-- TAB 4: Claims -->
                    <div id="tab-claims-content" class="tab-content w-full hidden">
                        <!-- ROW 1: Claim Percentages -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- BASIC PERCENTAGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    نسبة الميزة التأمينية الأساسية
                                </label>
                                <div class="relative">
                                    <input type="number" name="claim_basic_percentage"
                                        value="{{ old('claim_basic_percentage', $settings['claim_basic_percentage']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('claim_basic_percentage') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        %</div>
                                    <iconify-icon icon="mdi:percent"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>

                            <!-- TRANSFER/RESIGNATION PERCENTAGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    نسبة الميزة في حالة النقل / الاستقالة
                                </label>
                                <div class="relative">
                                    <input type="number" name="claim_transfer_resignation_percentage"
                                        value="{{ old('claim_transfer_resignation_percentage', $settings['claim_transfer_resignation_percentage']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('claim_transfer_resignation_percentage') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        %</div>
                                    <iconify-icon icon="mdi:percent"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 2: Funeral & Min Years -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- FUNERAL EXPENSES -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    مصاريف الجنازة
                                </label>
                                <div class="relative">
                                    <input type="number" name="claim_funeral_expenses"
                                        value="{{ old('claim_funeral_expenses', $settings['claim_funeral_expenses']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('claim_funeral_expenses') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        ج.م</div>
                                    <iconify-icon icon="mdi:cash"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>

                            <!-- MIN YEARS -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-6 py-2 text-[#124375] text-[16px] font-bold z-10">
                                    الحد الأدنى لسنوات الاشتراك لاستحقاق الميزة
                                </label>
                                <div class="relative">
                                    <input type="number" name="claim_min_years_subscribed"
                                        value="{{ old('claim_min_years_subscribed', $settings['claim_min_years_subscribed']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[16px] text-[#021219] text-center {{ $errors->has('claim_min_years_subscribed') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[16px] font-bold outline-none"
                                        required>
                                    <div
                                        class="absolute left-16 top-1/2 -translate-y-1/2 text-[#b4b4b4] text-[20px] font-medium font-['Noto_Sans_Arabic'] pointer-events-none">
                                        سنوات</div>
                                    <iconify-icon icon="mdi:history"
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-primary text-[20px]"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOT & BUTTONS -->
                <div class="self-stretch inline-flex justify-between items-center mt-8 pt-6 border-t border-slate-200">
                    <!-- LAST UPDATE -->
                    <div class="flex justify-start items-center gap-2 text-[#6D6D6D]">
                        <div class="justify-start text-sm font-medium font-['Noto_Sans_Arabic']">
                            آخر تعديل:
                            {{ $lastUpdate ? $lastUpdate->updated_at->timezone('Africa/Cairo')->locale('ar')->translatedFormat('j F Y \ف\ي h:i أ') : 'لا يوجد تعديلات سابقة' }}
                            @if ($lastUpdateUser)
                                (بواسطة: {{ $lastUpdateUser }})
                            @endif
                        </div>
                        <iconify-icon icon="mdi:clock-outline" class="text-lg"></iconify-icon>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex justify-center items-center gap-4">
                        <button type="button" onclick="submitResetForm()"
                            class="w-64 h-12 p-2 bg-[#F4F7F9] hover:bg-red-50 text-[#D92D20] rounded-xl flex justify-center items-center gap-4 text-center text-base font-semibold font-['Noto_Sans_Arabic'] transition cursor-pointer border-none outline-none">
                            <span>استعادة قيم اللائحة الافتراضية</span>
                        </button>
                        <button type="submit"
                            class="w-64 h-12 px-6 py-2 bg-[#124375] hover:bg-[#0e3560] text-white surface-shadow rounded-xl flex justify-center items-center gap-4 text-center text-base font-semibold font-['Noto_Sans_Arabic'] transition cursor-pointer border-none outline-none">
                            <span>حفظ التعديلات</span>
                            <iconify-icon icon="mdi:content-save-outline" class="text-xl text-white"></iconify-icon>
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <form id="reset-form" method="POST" action="{{ route('admin.settings.reset') }}" class="hidden">
            @csrf
        </form>
    </main>

    <script src="{{ asset('js/settings.js') }}"></script>
@endsection
