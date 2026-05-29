@extends('layouts.app')
{{--
    Settings View:
    Provides the General Admin interface for managing system constants and business rules (e.g. default currency, subscription %, max loan limits).
--}}

@section('title', 'لوحة تحكم الإعدادات')

@include('partials.common.flash')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/settings.css') }}">
    <main class="flex-1 py-5 px-3">
        <!-- start header main -->
        <div class="flex flex-col gap-2 mb-6">
            <h2 class="text-[#124375] text-[20px] font-bold font-semibold flex items-center gap-2">
                <span>إعدادات اللائحة الأساسية</span>
            </h2>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                إدارة المتغيرات والمحددات الآلية لصندوق التأمين الخاص لأعضاء هيئة التدريس والعاملين بجامعة العاصمة
            </p>
        </div>
        <!-- end header main -->

        <div class="bg-white border border-[#124375] surface-shadow rounded-2xl overflow-hidden flex flex-row min-h-[700px]"
            dir="rtl">

            <!-- INNER RIGHT MENU -->
            <aside
                class="w-[320px] px-4 py-8 bg-[#F4F7F9] border-l border-[#124375] flex flex-col justify-start items-center gap-6 flex-shrink-0">
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
                            <iconify-icon icon="material-symbols:list-alt-check-rounded" width="28" height="28"
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
                                class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                <iconify-icon icon="mdi:pencil" class="text-primary text-[20px]"></iconify-icon>
                                <span>اسم الصندوق</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="system_name"
                                    value="{{ old('system_name', $settings['system_name']) }}"
                                    class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('system_name') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
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
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="mdi:calendar" class="text-primary text-[20px]"></iconify-icon>
                                    <span>سن التقاعد القانوني (عاماً)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="retirement_age"
                                        value="{{ old('retirement_age', $settings['retirement_age']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('retirement_age') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>

                            <!-- CURRENCY -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="mdi:currency-usd-circle-outline"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>العملة الافتراضية</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="default_currency"
                                        value="{{ old('default_currency', $settings['default_currency']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('default_currency') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Subscriptions -->
                    <div id="tab-subscriptions-content" class="tab-content w-full hidden">
                        <!-- SUBSCRIPTION -->
                        {{-- <div class="grid grid-cols-2 gap-6 mb-12">

                            <!-- SUBSCRIPTION PERCENTAGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="majesticons:percent"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>نسبة الاشتراك الشهري للعضو (%)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="subscription_percentage"
                                        value="{{ old('subscription_percentage', $settings['subscription_percentage']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('subscription_percentage') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>

                            <!-- EMPLOYER CONTRIBUTION PERCENTAGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="majesticons:percent"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>نسبة مساهمة جهة العمل (%)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="employer_contribution_percentage"
                                        value="{{ old('employer_contribution_percentage', $settings['employer_contribution_percentage']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('employer_contribution_percentage') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>
                        </div>
                        --}}

                        <!-- AGE LIMITS -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- MIN AGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="mdi:account-arrow-up"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>الحد الأدنى لسن التسجيل (عاماً)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="membership_min_age"
                                        value="{{ old('membership_min_age', $settings['membership_min_age']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('membership_min_age') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>

                            <!-- MAX AGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="mdi:account-multiple-plus"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>الحد الأقصي لسن الانضمام (عاماً)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="membership_max_age"
                                        value="{{ old('membership_max_age', $settings['membership_max_age']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('membership_max_age') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL & DISMISSAL NOTICE -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- DISMISSAL NOTICE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="bxs:error" class="text-primary text-[20px]"></iconify-icon>
                                    <span>فترة الإنذار قبل الفصل (شهراً)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="dismissal_notice_months"
                                        value="{{ old('dismissal_notice_months', $settings['dismissal_notice_months']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('dismissal_notice_months') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>

                            <!-- JOIN FEE (BUTTON MODAL) -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="fa6-solid:money-bill-wave"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>رسوم الانضمام أو العضوية</span>
                                </label>
                                <div class="relative">
                                    <!-- Hidden input for actual submission -->
                                    <input type="hidden" name="membership_join_fee" id="membership_join_fee_hidden"
                                        value="{{ old('membership_join_fee', $settings['membership_join_fee']) }}">

                                    <!-- Styled Button -->
                                    <button type="button" data-target="membershipFeeModal"
                                        class="open-modal w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('membership_join_fee') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} px-6 font-bold outline-none cursor-pointer hover:bg-slate-100 transition relative flex items-center justify-center">
                                        <span>عرض وتعديل جدول الرسوم</span>
                                        <iconify-icon icon="mdi:chevron-left"
                                            class="text-[#124375] text-[28px] absolute left-4"></iconify-icon>
                                    </button>
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
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="majesticons:percent"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>نسبة القرض من إجمالي الاشتراكات (%)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_percentage"
                                        value="{{ old('loan_percentage', $settings['loan_percentage']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('loan_percentage') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>

                            <!-- INTEREST RATE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="majesticons:percent"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>معدل الفائدة السنوية (%)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_interest_rate"
                                        value="{{ old('loan_interest_rate', $settings['loan_interest_rate']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('loan_interest_rate') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 2: Max Amount & Repayment -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- MAX AMOUNT -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="fa6-solid:money-bill-wave"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>الحد الأقصى للقرض الشخصي (ج.م)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_max_amount"
                                        value="{{ old('loan_max_amount', $settings['loan_max_amount']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('loan_max_amount') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>

                            <!-- REPAYMENT MONTHS -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="sidekickicons:arrow-path-clock-16-solid"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>أقصى فترة سداد للقرض (شهراً)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_repayment_months"
                                        value="{{ old('loan_repayment_months', $settings['loan_repayment_months']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('loan_repayment_months') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 3: Centered Min Subscription Years -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="pixel:calender-solid"
                                        class="text-primary text-[20px]"></iconify-icon>
                                    <span>الحد الأدنى للاشتراك لطلب القرض (عاماً)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="loan_min_years_subscribed"
                                        value="{{ old('loan_min_years_subscribed', $settings['loan_min_years_subscribed']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('loan_min_years_subscribed') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
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
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="mdi:percent" class="text-primary text-[20px]"></iconify-icon>
                                    <span>نسبة الميزة التأمينية الأساسية (%)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="claim_basic_percentage"
                                        value="{{ old('claim_basic_percentage', $settings['claim_basic_percentage']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('claim_basic_percentage') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>

                            <!-- TRANSFER/RESIGNATION PERCENTAGE -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="mdi:percent" class="text-primary text-[20px]"></iconify-icon>
                                    <span>نسبة الميزة في حالة النقل / الاستقالة (%)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="claim_transfer_resignation_percentage"
                                        value="{{ old('claim_transfer_resignation_percentage', $settings['claim_transfer_resignation_percentage']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('claim_transfer_resignation_percentage') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 2: Funeral & Min Years -->
                        <div class="grid grid-cols-2 gap-6 mb-12">
                            <!-- FUNERAL EXPENSES -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="mdi:cash" class="text-primary text-[20px]"></iconify-icon>
                                    <span>مصاريف الجنازة (ج.م)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="claim_funeral_expenses"
                                        value="{{ old('claim_funeral_expenses', $settings['claim_funeral_expenses']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('claim_funeral_expenses') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>

                            <!-- MIN YEARS -->
                            <div class="relative">
                                <label
                                    class="absolute -top-5 right-5 bg-[#F7F9FC] px-3 py-0.5 text-[#124375] text-[16px] font-bold z-10 flex items-center gap-2">
                                    <iconify-icon icon="mdi:history" class="text-primary text-[20px]"></iconify-icon>
                                    <span>الحد الأدنى لسنوات الاشتراك لاستحقاق الميزة (سنوات)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="claim_min_years_subscribed"
                                        value="{{ old('claim_min_years_subscribed', $settings['claim_min_years_subscribed']) }}"
                                        class="w-full rounded-md py-2 bg-[#F7F9FC] border-2 text-[18px] text-[#021219] text-center {{ $errors->has('claim_min_years_subscribed') ? 'border-[#D92D20]' : 'border-[#1e5a97]' }} bg-white px-6 text-[18px] font-bold outline-none"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOT & BUTTONS -->
                <div class="self-stretch inline-flex justify-between items-center mt-8 pt-6 border-t border-slate-200">
                    <!-- LAST UPDATE -->
                    <div class="flex justify-start items-center gap-2 text-[#6D6D6D]">
                        <iconify-icon icon="mdi:clock-outline" class="text-lg"></iconify-icon>

                        <div class="justify-start text-sm font-medium font-['Noto_Sans_Arabic']">
                            آخر تعديل:
                            {{ $lastUpdate ? $lastUpdate->updated_at->timezone('Africa/Cairo')->locale('ar')->translatedFormat('j F Y \ف\ي h:i أ') : 'لا يوجد تعديلات سابقة' }}
                            @if ($lastUpdateUser)
                                (بواسطة: {{ $lastUpdateUser }})
                            @endif
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex justify-center items-center gap-4">
                        <button type="button" onclick="submitResetForm()"
                            class="w-64 h-12 p-2 bg-[#EFEFEF] hover:bg-red-50 text-[#6D6D6D] rounded-xl flex justify-center items-center gap-4 text-center text-base font-semibold font-['Noto_Sans_Arabic'] transition cursor-pointer border-none outline-none">
                            <span>استعادة قيم اللائحة الافتراضية</span>
                        </button>

                        <button type="submit"
                            class="w-64 h-12 px-6 py-2 bg-[#124375] hover:bg-[#0e3560] text-white surface-shadow rounded-xl flex justify-center items-center gap-4 text-center text-[16px] font-semibold font-['Noto_Sans_Arabic'] transition cursor-pointer border-none outline-none">
                            <iconify-icon icon="fluent:save-16-filled" class="text-xl text-white"></iconify-icon>
                            <span>حفظ التعديلات</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Join Fee Modal -->
        <div id="membershipFeeModal"
            class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center backdrop-blur-sm transition-opacity opacity-0"
            dir="rtl">
            <div
                class="bg-[#F8F9FA] rounded-[24px] shadow-2xl w-full max-w-3xl transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col p-8 max-h-[90vh]">
                <!-- Modal Header -->
                <div class="flex justify-between items-start mb-6">
                    <div class="text-right">
                        <h3 class="text-3xl font-bold text-[#021219] font-['Noto_Sans_Arabic'] mb-2">جدول رسوم الانضمام
                        </h3>
                        <p class="text-[#6D6D6D] text-lg font-medium font-['Noto_Sans_Arabic']">يرجى تحديد رسوم الانضمام
                            بناءً على الفئة العمرية للعضو عند الاشتراك.</p>
                    </div>
                    <button type="button"
                        class="close-modal bg-white border border-[#D0D5DD] rounded-xl p-2 text-[#021219] hover:bg-gray-50 transition focus:outline-none flex items-center justify-center shadow-sm">
                        <iconify-icon icon="mdi:close" class="text-2xl"></iconify-icon>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto">
                    <div class="bg-white rounded-2xl border border-[#D0D5DD] overflow-hidden">
                        <table class="w-full text-center border-collapse" id="joinFeeTable">
                            <thead class="bg-[#F0F7FF] text-[#021219] border-b border-[#D0D5DD]">
                                <tr>
                                    <th class="py-4 px-6 font-bold text-lg w-1/2">المدة المتبقية علي بلوغ سن التقاعد</th>
                                    <th class="py-4 px-6 font-bold text-lg w-1/2">رسم العضوية</th>
                                    <th class="py-4 px-4 w-20"></th>
                                </tr>
                            </thead>
                            <tbody id="joinFeeTableBody" class="divide-y divide-[#D0D5DD]">
                                <!-- Rows will be generated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="addRowBtn"
                        class="mt-6 flex items-center justify-center w-full py-3 border-2 border-dashed border-[#124375] text-[#124375] rounded-xl hover:bg-[#EEF7FF] font-bold text-lg transition">
                        <iconify-icon icon="mdi:plus-circle-outline" class="text-2xl ml-2"></iconify-icon>
                        <span class="mr-2">إضافة صف جديد</span>
                    </button>
                </div>

                <!-- Modal Footer -->
                <div class="mt-8 flex justify-between gap-4">
                    <button type="button"
                        class="close-modal flex-1 py-3 rounded-xl text-[#124375] bg-[#F8F9FA] border-2 border-[#D0D5DD] hover:bg-gray-100 font-bold text-lg transition">
                        إلغاء الأمر
                    </button>
                    <!-- Trigger the main form save -->
                    <button type="button"
                        onclick="document.querySelector('form[action=\'{{ route('admin.settings.update') }}\']').submit();"
                        class="flex-1 py-3 rounded-xl text-white bg-[#124375] hover:bg-[#0e3560] font-bold text-lg transition shadow-md flex justify-center items-center gap-2">
                        <span>حفظ التعديلات</span>
                        <iconify-icon icon="fluent:save-16-filled" class="text-xl"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>

        <form id="reset-form" method="POST" action="{{ route('admin.settings.reset') }}" class="hidden">
            @csrf
        </form>
    </main>

    <script src="{{ asset('js/admin/settings.js') }}"></script>
@endsection

