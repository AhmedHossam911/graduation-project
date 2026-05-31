@extends('layouts.member')
{{--
    Member Create Membership View:
    Digital form for members to register for membership manually.
--}}
@section('title', 'استمارة اشتراك في صندوق الزمالة')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/employee/MembershipForm.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- start Header -->
    <div class="flex justify-between items-center py-5 px-7">
        <div class="title text-right text-[#124375]">
            <h1 class="text-2xl font-bold mb-2">
                استمارة اشتراك في صندوق الزمالة
            </h1>
            <p class="text-sm font-medium text-[#6D6D6D]">
                يرجى تعبئة كافة البيانات المطلوبة بدقة للتسجيل في صندوق الزمالة لأعضاء هيئة التدريس والعاملين بجامعة
                العاصمة. يشترط أن يكون<br>
                من العاملين الدائمين أو المؤقتين بالجامعة .
            </p>
        </div>
        <div class="flex items-center gap-4 relative">
            <a href="{{ route('member.dashboard') }}" class="btn-close">
                <button type="button"
                    class="bg-[#F4F7F9] text-[#D92D20] border border-[#D92D20] rounded-2xl py-2 px-12 text-base font-medium">
                    إلغاء
                </button>
            </a>
        </div>
    </div>
    <!-- end Header -->

    <div class="print:hidden">

        <form action="{{ route('member.membership.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- start Form -->
            <!-- start personalData section -->
            <section class="px-6 py-7">
                <div class="personal-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative z-50">
                    <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">
                        البيانات الشخصية
                    </h2>
                    <div class="space-y-7">
                        <!-- START FULL NAME & EMAIL -->
                        <div class="flex flex-col md:flex-row gap-6 md:gap-5">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 @error('full_name') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">
                                    الأسم رباعي <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="full_name"
                                    value="{{ old('full_name', $user->member->user->name ?? ($user->name ?? '')) }}"
                                    placeholder="مثال : أحمد محمد إسماعيل محمود" disabled
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border @error('full_name') border-[#D92D20] @else border-[#124375] @enderror outline-none rounded-xl px-16 py-2 bg-[#E8EDF2] cursor-not-allowed">
                                @error('full_name')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="relative w-full">
                                <label
                                    class="absolute top-[-15px] right-5 @error('email') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">
                                    البريد الإلكتروني <span class="text-[#D92D20]">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                                    placeholder="ahmed@gmail.com : مثال" disabled
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border @error('email') border-[#D92D20] @else border-[#124375] @enderror outline-none rounded-xl px-16 py-2 bg-[#E8EDF2] cursor-not-allowed">
                                @error('email')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- END FULL NAME & EMAIL -->

                        <!-- START LANDLINE & PHONE -->
                        <div class="flex flex-col md:flex-row gap-6 md:gap-6 mt-4 md:mt-0">
                            <div
                                class="phone relative border @error('phone_digits') border-[#D92D20] @elseif($errors->has('phone_digits.*')) border-[#D92D20] @else border-[#124375] @enderror rounded-xl flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 @error('phone_digits') text-[#D92D20] @elseif($errors->has('phone_digits.*')) text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">رقم
                                    التليفون <span class="text-[#D92D20]">*</span></label>
                                <div class="grid grid-cols-[repeat(11,minmax(0,max-content))] gap-1 md:gap-3 justify-end py-3 px-2 md:px-3"
                                    dir="ltr">
                                    @php
                                        $phoneStr = old('phone', $user->member->phone ?? '');
                                        $phoneDigits = str_split(str_pad($phoneStr, 11, ' ', STR_PAD_LEFT));
                                    @endphp
                                    @for ($i = 0; $i < 11; $i++)
                                        <input type="tel" name="phone_digits[]"
                                            value="{{ old('phone_digits.' . $i, trim($phoneDigits[$i] ?? '')) }}"
                                            placeholder="{{ $i + 1 }}" maxlength="1" disabled
                                            class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-7 sm:w-10 md:w-16 max-w-full text-sm md:text-base min-w-0 py-1 input-shadow bg-[#E8EDF2] text-center cursor-not-allowed">
                                    @endfor
                                </div>
                                @if ($errors->has('phone_digits') || $errors->has('phone_digits.*'))
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                                        إدخال 11 رقم.</span>
                                @endif
                            </div>
                            <div
                                class="HomePhone relative border @error('landline_digits') border-[#D92D20] @elseif($errors->has('landline_digits.*')) border-[#D92D20] @else border-[#124375] @enderror rounded-xl min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 @error('landline_digits') text-[#D92D20] @elseif($errors->has('landline_digits.*')) text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">رقم
                                    هاتف المنزل</label>
                                <div class="grid grid-cols-[repeat(8,minmax(0,max-content))] gap-1 md:gap-2 justify-end py-3 px-2 md:px-3"
                                    dir="ltr">
                                    @for ($i = 0; $i < 8; $i++)
                                        <input type="tel" name="landline_digits[]"
                                            value="{{ old('landline_digits.' . $i) }}" placeholder="{{ $i + 1 }}"
                                            maxlength="1"
                                            class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-7 sm:w-10 md:w-14 max-w-full text-sm md:text-base min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    @endfor
                                </div>
                                @if ($errors->has('landline_digits') || $errors->has('landline_digits.*'))
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                                        إدخال 8 أرقام.</span>
                                @endif
                            </div>
                        </div>
                        <!-- END LANDLINE & PHONE -->
                        <!-- START DATE OF BIRTH -->
                        <div class="flex flex-col md:flex-row gap-6 md:gap-5 mt-4 md:mt-0">
                            <div
                                class=" relative border @error('national_id_digits') border-[#D92D20] @elseif($errors->has('national_id_digits.*')) border-[#D92D20] @else border-[#124375] @enderror rounded-xl flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 @error('national_id_digits') text-[#D92D20] @elseif($errors->has('national_id_digits.*')) text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">الرقم
                                    القومي <span class="text-[#D92D20]">*</span></label>
                                <div class="grid grid-cols-[repeat(14,minmax(0,max-content))] gap-1 md:gap-3 justify-end py-3 px-2 md:px-3"
                                    dir="ltr">
                                    @php
                                        $nidStr = old('national_id', $user->member->user->national_id ?? '');
                                        $nidDigits = str_split(str_pad($nidStr, 14, ' ', STR_PAD_LEFT));
                                    @endphp
                                    @for ($i = 0; $i < 14; $i++)
                                        <input type="text" name="national_id_digits[]"
                                            value="{{ old('national_id_digits.' . $i, trim($nidDigits[$i] ?? '')) }}"
                                            placeholder="{{ $i + 1 }}" maxlength="1" disabled
                                            class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-7 sm:w-9 md:w-16 max-w-full text-xs md:text-base min-w-0 py-1 input-shadow bg-[#E8EDF2] text-center cursor-not-allowed">
                                    @endfor
                                </div>
                                @error('national_id_digits')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                                @if ($errors->has('national_id_digits.*') && !$errors->has('national_id_digits'))
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                                        إدخال 14 رقم.</span>
                                @endif
                            </div>
                            <div
                                class="relative border @if ($errors->has('birth_day') || $errors->has('birth_month') || $errors->has('birth_year')) border-[#D92D20] @else border-[#124375] @endif rounded-xl  min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 @if ($errors->has('birth_day') || $errors->has('birth_month') || $errors->has('birth_year')) text-[#D92D20] @else text-[#124375] @endif text-base font-medium bg-[#F4F7F9] px-1">تاريخ
                                    الميلاد <span class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-2 md:gap-3 justify-end py-3 px-2 md:px-3">
                                    <input type="text" id="birth_day" name="birth_day" value="{{ old('birth_day') }}"
                                        placeholder="اليوم" maxlength="2"
                                        class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 sm:w-20 md:w-28 text-sm md:text-base min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" id="birth_month" name="birth_month"
                                        value="{{ old('birth_month') }}" placeholder="الشهر" maxlength="2"
                                        class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 sm:w-20 md:w-28 text-sm md:text-base min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" id="birth_year" name="birth_year" value="{{ old('birth_year') }}"
                                        placeholder="السنة" maxlength="4"
                                        class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full text-sm md:text-base min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @if ($errors->has('birth_year'))
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $errors->first('birth_year') }}</span>
                                @elseif($errors->has('birth_day') || $errors->has('birth_month'))
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">هذا
                                        الحقل مطلوب</span>
                                @endif
                            </div>
                        </div>
                        <!-- END LANDLINE & PHONE -->
                        <!-- START SOCIAL STATUS & PLACE OF RESIDENCE -->
                        <div class="flex flex-col md:flex-row gap-6 md:gap-5 mt-4 md:mt-0">
                            <div class=" relative flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 @error('address') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">محل
                                    الاقامة <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    placeholder="كما البطاقة"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('address') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('address')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full md:w-80">
                                @include('partials.common.dropdown', [
                                    'name' => 'marital_status',
                                    'label' => 'الحالة الاجتماعية',
                                    'options' => [
                                        'متزوج' => 'متزوج',
                                        'مطلق' => 'مطلق',
                                        'أعزب' => 'أعزب',
                                        'أرمل' => 'أرمل',
                                    ],
                                    'selected' => old('marital_status'),
                                    'placeholder' => 'أختر',
                                    'required' => true,
                                    'floatingLabel' => true,
                                    'showConfirm' => false,
                                ])
                            </div>
                        </div>
                        <!-- END SOCIAL STATUS & PLACE OF RESIDENCE -->
                    </div>
                </div>
            </section>
            <!-- end PersonalData section -->

            <!-- start FunctionalData section -->
            <section class="px-6">
                <div class="functional-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                    <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">
                        البيانات
                        الوظيفية</div>
                    <div class="flex flex-col lg:flex-row gap-6 md:gap-5 mt-4 lg:mt-0">
                        <div class="flex-1 min-w-0 space-y-7">
                            <div class="w-full relative pt-2">
                                <label
                                    class="absolute top-[-15px] right-5 @error('employer_name') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">جهة
                                    العمل <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="employer_name"
                                    value="{{ old('employer_name', $user->member->employmentInfo->workplace ?? '') }}"
                                    disabled
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border @error('employer_name') border-[#D92D20] @else border-[#124375] @enderror outline-none rounded-xl px-16 py-2 bg-[#E8EDF2] cursor-not-allowed">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 @error('job_title') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">الوظيفة
                                    <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="job_title"
                                    value="{{ old('job_title', $user->member->employmentInfo->job_title ?? '') }}"
                                    placeholder="مثال : مدرس مساعد مادة المحاسبة" disabled
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('job_title') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#E8EDF2] cursor-not-allowed">
                                @error('job_title')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col md:flex-row gap-6 md:gap-5 mt-4 md:mt-0">
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 @error('financial_category') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">الفئة
                                        المالية الحالية <span class="text-[#D92D20]">*</span></label>
                                    <input type="text" name="financial_category"
                                        value="{{ old('financial_category') }}" placeholder="مثال : الفئة الثالثة"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('financial_category') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    @error('financial_category')
                                        <span
                                            class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 @error('salary') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">المرتب
                                        الشهري الأساسي عند التعيين <span class="text-[#D92D20]">*</span></label>
                                    <input type="number" name="salary" value="{{ old('salary') }}"
                                        placeholder="مثال : 360 "
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('salary') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    @error('salary')
                                        <span
                                            class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row lg:flex-col gap-6 md:gap-5 lg:gap-12 mt-4 md:mt-0">
                            <div
                                class="relative border @if ($errors->has('hire_day') || $errors->has('hire_month') || $errors->has('hire_year')) border-[#D92D20] @else border-[#124375] @endif rounded-xl flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 @if ($errors->has('hire_day') || $errors->has('hire_month') || $errors->has('hire_year')) text-[#D92D20] @else text-[#124375] @endif text-base font-medium bg-[#F4F7F9] px-1">تاريخ
                                    إستلام العمل <span class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-2 md:gap-3 justify-end py-3 px-2 md:px-3">
                                    <input type="text" name="hire_day" value="{{ old('hire_day') }}"
                                        placeholder="اليوم" maxlength="2"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 sm:w-20 md:w-28 text-sm md:text-base min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="hire_month" value="{{ old('hire_month') }}"
                                        placeholder="الشهر" maxlength="2"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 sm:w-20 md:w-28 text-sm md:text-base min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="hire_year" value="{{ old('hire_year') }}"
                                        placeholder="السنة" maxlength="4"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full text-sm md:text-base min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @if ($errors->has('hire_day') || $errors->has('hire_month') || $errors->has('hire_year'))
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">تاريخ
                                        الاستلام مطلوب بصيغة صحيحة</span>
                                @endif
                            </div>
                            <div
                                class="relative border @if ($errors->has('retirement_day') || $errors->has('retirement_month') || $errors->has('retirement_year')) border-[#D92D20] @else border-[#124375] @endif rounded-xl flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 @if ($errors->has('retirement_day') || $errors->has('retirement_month') || $errors->has('retirement_year')) text-[#D92D20] @else text-[#124375] @endif text-base font-medium bg-[#F4F7F9] px-1">تاريخ
                                    الإحالة إلي المعاش <span class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-2 md:gap-3 justify-end py-3 px-2 md:px-3">
                                    <input type="text" id="retirement_day" name="retirement_day"
                                        value="{{ old('retirement_day') }}" placeholder="اليوم" maxlength="2" readonly
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 sm:w-20 md:w-28 text-sm md:text-base min-w-0 py-1 input-shadow bg-[#E8EDF2] text-center cursor-not-allowed">
                                    <input type="text" id="retirement_month" name="retirement_month"
                                        value="{{ old('retirement_month') }}" placeholder="الشهر" maxlength="2"
                                        readonly
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 sm:w-20 md:w-28 text-sm md:text-base min-w-0 py-1 input-shadow bg-[#E8EDF2] text-center cursor-not-allowed">
                                    <input type="text" id="retirement_year" name="retirement_year"
                                        value="{{ old('retirement_year') }}" placeholder="السنة" maxlength="4" readonly
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full text-sm md:text-base min-w-0 py-1 input-shadow bg-[#E8EDF2] text-center cursor-not-allowed">
                                </div>
                                @if ($errors->has('retirement_day') || $errors->has('retirement_month') || $errors->has('retirement_year'))
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">تاريخ
                                        الإحالة للمعاش مطلوب بصيغة صحيحة</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end FunctionalData section -->

            <!-- start familyData section -->
            <section class="px-6 py-7">
                <div class="family-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                    <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">
                        البيانات
                        العائلية</div>
                    <div class="wrapper space-y-5">
                        <div class="flex flex-col md:flex-row gap-6 md:gap-5 mt-4 md:mt-0">
                            <div
                                class="childern flex-1 min-w-0 relative border @error('children_count') border-[#D92D20] @else border-[#124375] @enderror rounded-xl">
                                <label
                                    class="absolute top-[-15px] right-5 @error('children_count') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">عدد
                                    الأبناء</label>
                                <div class="py-3 text-center">
                                    <input type="number" name="children_count" value="{{ old('children_count', 0) }}"
                                        placeholder="0"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>

                            </div>
                            <div
                                class="phone relative border @error('spouse_phone_digits') border-[#D92D20] @elseif($errors->has('spouse_phone_digits.*')) border-[#D92D20] @else border-[#124375] @enderror rounded-xl flex-[3] min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 @error('spouse_phone_digits') text-[#D92D20] @elseif($errors->has('spouse_phone_digits.*')) text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">رقم
                                    تليفون الزوج أو الزوجة أو أحد الأبناء أو أحد الأقارب</label>
                                <div class="grid grid-cols-[repeat(11,minmax(0,max-content))] gap-1 md:gap-2 justify-end py-3 px-2 md:px-3"
                                    dir="ltr">
                                    @for ($i = 0; $i < 11; $i++)
                                        <input type="tel" name="spouse_phone_digits[]"
                                            value="{{ old('spouse_phone_digits.' . $i) }}"
                                            placeholder="{{ $i + 1 }}" maxlength="1"
                                            class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-7 sm:w-10 md:w-16 max-w-full text-sm md:text-base min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    @endfor
                                </div>
                                @if ($errors->has('spouse_phone_digits') || $errors->has('spouse_phone_digits.*'))
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                                        إدخال 11 رقم.</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-6 md:gap-5 mt-4 md:mt-0">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 @error('spouse_name') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">اسم
                                    الزوج أو الزوجة</label>
                                <input type="text" name="spouse_name" value="{{ old('spouse_name') }}"
                                    placeholder="مثال : رباب عبدالعليم أحمد محمد"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('spouse_name') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('spouse_name')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 @error('spouse_workplace') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">وظيفته
                                    أو جهة عمله</label>
                                <input type="text" name="spouse_workplace" value="{{ old('spouse_workplace') }}"
                                    placeholder="مثال : محاسبة بشركة الحديد و الصلب"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('spouse_workplace') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('spouse_workplace')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-6 md:gap-5 mt-4 md:mt-0">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 @error('child_name') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">اسم
                                    أحد الأبناء</label>
                                <input type="text" name="child_name" value="{{ old('child_name') }}"
                                    placeholder="مثال : لا يوجد"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('child_name') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('child_name')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 @error('child_workplace') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">وظيفته
                                    أو جهة عمله</label>
                                <input type="text" name="child_workplace" value="{{ old('child_workplace') }}"
                                    placeholder="مثال : لا يوجد"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('child_workplace') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('child_workplace')
                                    <span
                                        class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end familyData section -->

            <!-- start files section -->
            <section class="px-6 ">
                <div class="files rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                    <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">
                        المرفقات
                    </h2>
                    <div class="wrapper space-y-4">

                        <!-- File 1 -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0 border @error('documents.basic_salary_letter') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-4 py-3 bg-[#F4F7F9]">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:document-text-bold"
                                    class="text-2xl text-[#6D6D6D]"></iconify-icon>
                                <span class="text-base font-medium text-[#124375]">خطاب الأجر الأساسي في 1/7/2014
                                    <span class="text-[#D92D20]">*</span></span>
                                </span>
                            </div>
                            <label for="file-1"
                                class="text-[#124375] cursor-pointer flex items-center gap-2 bg-transparent border border-[#124375] rounded-lg px-4 py-1 hover:bg-[#E8EDF2] transition">
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-xl file-icon"></iconify-icon>
                                <span class="text-sm font-medium file-name">إرفاق الملف</span>
                                <input type="file" name="documents[basic_salary_letter]" id="file-1"
                                    class="input-file hidden text-[#6D6D6D] font-medium" onchange="updateFileLabel(this)">
                            </label>

                        </div>
                        @error('documents.basic_salary_letter')
                            <span class="text-[#D92D20] text-sm">{{ $message }}</span>
                        @enderror

                        <!-- File 2 -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0 border @error('documents.national_id_card') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-4 py-3 bg-[#F4F7F9]">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:document-text-bold"
                                    class="text-2xl text-[#6D6D6D]"></iconify-icon>
                                <span class="text-base font-medium text-[#124375]">بطاقة الرقم القومي
                                    <span class="text-[#D92D20]">*</span></span>
                                </span>
                            </div>
                            <label for="file-2"
                                class="text-[#124375] cursor-pointer flex items-center gap-2 bg-transparent border border-[#124375] rounded-lg px-4 py-1 hover:bg-[#E8EDF2] transition">
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-xl file-icon"></iconify-icon>
                                <span class="text-sm font-medium file-name">إرفاق الملف</span>
                                <input type="file" name="documents[national_id_card]" id="file-2"
                                    class="input-file hidden text-[#6D6D6D] font-medium" onchange="updateFileLabel(this)">
                            </label>

                        </div>
                        @error('documents.national_id_card')
                            <span class="text-[#D92D20] text-sm">{{ $message }}</span>
                        @enderror

                        <!-- File 3 -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0 border @error('documents.appointment_decision') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-4 py-3 bg-[#F4F7F9]">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:document-text-bold"
                                    class="text-2xl text-[#6D6D6D]"></iconify-icon>
                                <span class="text-base font-medium text-[#124375]">قرار التعيين
                                    <span class="text-[#D92D20]">*</span></span>
                                </span>
                            </div>
                            <label for="file-3"
                                class="text-[#124375] cursor-pointer flex items-center gap-2 bg-transparent border border-[#124375] rounded-lg px-4 py-1 hover:bg-[#E8EDF2] transition">
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-xl file-icon"></iconify-icon>
                                <span class="text-sm font-medium file-name">إرفاق الملف
                                </span>
                                <input type="file" name="documents[appointment_decision]" id="file-3"
                                    class="input-file hidden text-[#6D6D6D] font-medium" onchange="updateFileLabel(this)">
                            </label>

                        </div>
                        @error('documents.appointment_decision')
                            <span class="text-[#D92D20] text-sm">{{ $message }}</span>
                        @enderror

                        <!-- File 4 -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0 border @error('documents.over_21_request') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-4 py-3 bg-[#F4F7F9]">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:document-text-bold"
                                    class="text-2xl text-[#6D6D6D]"></iconify-icon>
                                <span class="text-base font-medium text-[#124375]">طلب تجاوز لفوق سن 21 عاماً
                                    <span class="text-[#D92D20]">*</span></span>
                                </span>
                            </div>
                            <label for="file-4"
                                class="text-[#124375] cursor-pointer flex items-center gap-2 bg-transparent border border-[#124375] rounded-lg px-4 py-1 hover:bg-[#E8EDF2] transition">
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-xl file-icon"></iconify-icon>
                                <span class="text-sm font-medium file-name">إرفاق الملف</span>
                                <input type="file" name="documents[over_21_request]" id="file-4"
                                    class="input-file hidden text-[#6D6D6D] font-medium" onchange="updateFileLabel(this)">
                            </label>

                        </div>
                        @error('documents.over_21_request')
                            <span class="text-[#D92D20] text-sm">{{ $message }}</span>
                        @enderror

                        <!-- File 5 -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0 border @error('documents.work_declaration') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-4 py-3 bg-[#F4F7F9]">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:document-text-bold"
                                    class="text-2xl text-[#6D6D6D]"></iconify-icon>

                                <span class="text-base font-medium text-[#124375]">إقرار القيام بالعمل <span
                                        class="text-[#D92D20]">*</span></span>
                            </div>
                            <label for="file-5"
                                class="text-[#124375] cursor-pointer flex items-center gap-2 bg-transparent border border-[#124375] rounded-lg px-4 py-1 hover:bg-[#E8EDF2] transition">
                                <iconify-icon icon="mingcute:upload-3-line" class="text-xl file-icon"></iconify-icon>
                                <span class="text-sm font-medium file-name">إرفاق الملف</span>
                                <input type="file" name="documents[work_declaration]" id="file-5"
                                    class="input-file hidden text-[#6D6D6D] font-medium" onchange="updateFileLabel(this)">
                            </label>
                        </div>
                        @error('documents.work_declaration')
                            <span class="text-[#D92D20] text-sm">{{ $message }}</span>
                        @enderror

                    </div>
                </div>
            </section>
            <!-- end files section -->

            <!-- start declaration section -->
            <div class="px-6 mt-8 flex items-start gap-3 justify-end text-right flex-row-reverse">
                <label for="declaration" class="text-sm font-medium text-[#124375] cursor-pointer leading-relaxed ">
                    أقر باطلاعي على اللائحة التنفيذية الخاصة بصندوق التأمين الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين
                    بجامعة حلوان، وأقبل عضويتي في الصندوق اعتباراً من تاريخ التسجيل، وأوافق على خصم قيمة قسط المشاركة خصماً
                    من مكافآت الامتحانات المستحقة لي كل عام بما يعادل قيمة الأقساط السنوية.
                </label>
                <input type="checkbox" name="declaration_accepted" id="declaration"
                    class="mt-1 w-5 h-5 accent-[#124375] cursor-pointer" required>
            </div>

            <div class="flex justify-center mt-10 mb-16 px-6">
                <button type="submit"
                    class="flex gap-3 py-3 w-full justify-center rounded-2xl surface-shadow items-center bg-[#124375] text-white text-lg font-bold hover:bg-[#0e3560] transition-colors">
                    تقديم الطلب <iconify-icon icon="mingcute:send-fill" class="text-2xl"></iconify-icon>
                </button>
            </div>
        </form>

        <script>
            const SYSTEM_RETIREMENT_AGE = {{ \App\Models\System\SystemSetting::get('retirement_age', 60) }};

            function updateFileLabel(input) {
                const label = input.closest('label');
                const fileNameSpan = label.querySelector('.file-name');
                const fileIcon = label.querySelector('.file-icon');

                if (input.files && input.files[0]) {
                    fileNameSpan.textContent = 'تم إرفاق الملف';
                    fileIcon.setAttribute('icon', 'mingcute:check-circle-fill');
                    label.classList.add('bg-[#E8EDF2]');
                    label.classList.remove('bg-transparent');
                } else {
                    fileNameSpan.textContent = 'إرفاق الملف';
                    fileIcon.setAttribute('icon', 'mingcute:upload-3-line');
                    label.classList.remove('bg-[#E8EDF2]');
                    label.classList.add('bg-transparent');
                }
            }
        </script>
        <script src="{{ asset('js/member/MembershipForm.js') }}?v={{ time() }}"></script>

    @endsection
