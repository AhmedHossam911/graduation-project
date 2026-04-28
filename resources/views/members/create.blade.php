@extends('layouts.pages')
@section('title', 'إستمارة العضوية')
@section('content')
    <style src="{{ asset('CSS/MembershipForm.css') }}"></style>
    <!-- start Header -->
    <div class="flex justify-between items-center py-5 px-7 relative">
        <div class="logo surface-shadow">
            <img width="54" src="{{ asset('IMgs/Hu Logo 1.png') }}" alt="Logo">
        </div>
        <div class="title text-xl font-semibold text-[#124375]">
            <h1>
                إستمارة العضوية
            </h1>
        </div>
        <div class="btn-close">
            <a href="{{ route('members.index') }}"
                class=" bg-[#F4F7F9] text-[#D92D20] rounded-2xl py-2 px-12 text-base font-medium">
                إلغاء
            </a>
        </div>
        @include('partials.flash')
    </div>
    <!-- end Header -->

    @if (isset($printMode) && $printMode)
        <style>
            @media print {

                .no-print,
                .btn-close,
                .sidebar,
                .navbar,
                footer {
                    display: none !important;
                }

                body {
                    background: white !important;
                }

                .personal-data,
                .functional-data,
                .family-data,
                .files {
                    border-color: black !important;
                }
            }
        </style>
    @endif


    <!-- start Form -->
    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- start personalData section -->
        <section class="px-6 py-7">
            <div class="personal-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                    البيانات الشخصية
                </h2>
                <div class="space-y-7">
                    <!-- START FULL NAME & EMAIL -->
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                                الأسم
                                رباعي <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name', $member->full_name ?? '') }}"
                                placeholder="مثال : أحمد محمد إسماعيل محمود"
                                {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                        </div>
                        <div class="relative w-full">
                            <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                                البريد الإلكتروني <span class="text-[#D92D20]">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $member->email ?? '') }}"
                                placeholder="ahmed@gmail.com : مثال" {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                        </div>
                    </div>

                    <!-- END FULL NAME & EMAIL -->
                    <!-- START LANDLINE & PHONE -->
                    <div class="flex gap-6">
                        <div class="phone relative border border-[#124375] rounded-xl flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                التليفون <span class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-3 justify-end py-3 px-3">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.0', isset($member->phone) ? substr($member->phone, 0, 1) : '') }}"
                                    placeholder="10" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.1', isset($member->phone) ? substr($member->phone, 1, 1) : '') }}"
                                    placeholder="9" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.2', isset($member->phone) ? substr($member->phone, 2, 1) : '') }}"
                                    placeholder="8" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.3', isset($member->phone) ? substr($member->phone, 3, 1) : '') }}"
                                    placeholder="7" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.4', isset($member->phone) ? substr($member->phone, 4, 1) : '') }}"
                                    placeholder="6" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.5', isset($member->phone) ? substr($member->phone, 5, 1) : '') }}"
                                    placeholder="5" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.6', isset($member->phone) ? substr($member->phone, 6, 1) : '') }}"
                                    placeholder="4" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.7', isset($member->phone) ? substr($member->phone, 7, 1) : '') }}"
                                    placeholder="3" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.8', isset($member->phone) ? substr($member->phone, 8, 1) : '') }}"
                                    placeholder="2" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.9', isset($member->phone) ? substr($member->phone, 9, 1) : '') }}"
                                    placeholder="1" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="phone_digits[]"
                                    value="{{ old('phone_digits.10', isset($member->phone) ? substr($member->phone, 10, 1) : '') }}"
                                    placeholder="0" max="9" min="0"
                                    class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">

                            </div>
                        </div>
                        <div class="HomePhone relative border border-[#124375]  rounded-xl min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                هاتف المنزل</label>
                            <div class="flex gap-2 justify-end py-3 px-3">
                                <input type="number" max="9" min="0" name="landline_digits[]"
                                    value="{{ old('landline_digits.0', isset($member->landline) ? substr($member->landline, 0, 1) : '') }}"
                                    placeholder="7" max="9" min="0"
                                    class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="landline_digits[]"
                                    value="{{ old('landline_digits.1', isset($member->landline) ? substr($member->landline, 1, 1) : '') }}"
                                    placeholder="6" max="9" min="0"
                                    class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="landline_digits[]"
                                    value="{{ old('landline_digits.2', isset($member->landline) ? substr($member->landline, 2, 1) : '') }}"
                                    placeholder="5" max="9" min="0"
                                    class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="landline_digits[]"
                                    value="{{ old('landline_digits.3', isset($member->landline) ? substr($member->landline, 3, 1) : '') }}"
                                    placeholder="4" max="9" min="0"
                                    class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="landline_digits[]"
                                    value="{{ old('landline_digits.4', isset($member->landline) ? substr($member->landline, 4, 1) : '') }}"
                                    placeholder="3" max="9" min="0"
                                    class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="landline_digits[]"
                                    value="{{ old('landline_digits.5', isset($member->landline) ? substr($member->landline, 5, 1) : '') }}"
                                    placeholder="2" max="9" min="0"
                                    class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="landline_digits[]"
                                    value="{{ old('landline_digits.6', isset($member->landline) ? substr($member->landline, 6, 1) : '') }}"
                                    placeholder="1" max="9" min="0"
                                    class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="landline_digits[]"
                                    value="{{ old('landline_digits.7', isset($member->landline) ? substr($member->landline, 7, 1) : '') }}"
                                    placeholder="0" max="9" min="0"
                                    class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            </div>
                        </div>
                    </div>
                    <!-- END LANDLINE & PHONE -->
                    <!-- START DATE OF BIRTH -->
                    <div class="flex gap-5">
                        <div class=" relative border border-[#124375] rounded-xl flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الرقم
                                القومي <span class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-3 justify-end py-3 px-3">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.0', isset($member->national_id) ? substr($member->national_id, 0, 1) : '') }}"
                                    placeholder="14" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.1', isset($member->national_id) ? substr($member->national_id, 1, 1) : '') }}"
                                    placeholder="13" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.2', isset($member->national_id) ? substr($member->national_id, 2, 1) : '') }}"
                                    placeholder="12" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.3', isset($member->national_id) ? substr($member->national_id, 3, 1) : '') }}"
                                    placeholder="11" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.4', isset($member->national_id) ? substr($member->national_id, 4, 1) : '') }}"
                                    placeholder="10" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.5', isset($member->national_id) ? substr($member->national_id, 5, 1) : '') }}"
                                    placeholder="9" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.6', isset($member->national_id) ? substr($member->national_id, 6, 1) : '') }}"
                                    placeholder="8" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.7', isset($member->national_id) ? substr($member->national_id, 7, 1) : '') }}"
                                    placeholder="7" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.8', isset($member->national_id) ? substr($member->national_id, 8, 1) : '') }}"
                                    placeholder="6" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.9', isset($member->national_id) ? substr($member->national_id, 9, 1) : '') }}"
                                    placeholder="5" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.10', isset($member->national_id) ? substr($member->national_id, 10, 1) : '') }}"
                                    placeholder="4" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.11', isset($member->national_id) ? substr($member->national_id, 11, 1) : '') }}"
                                    placeholder="3" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.12', isset($member->national_id) ? substr($member->national_id, 12, 1) : '') }}"
                                    placeholder="2" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" name="national_id_digits[]"
                                    value="{{ old('national_id_digits.13', isset($member->national_id) ? substr($member->national_id, 13, 1) : '') }}"
                                    placeholder="1" max="9" min="0"
                                    class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">

                            </div>
                        </div>
                        <div class="relative border border-[#124375] rounded-xl  min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                                الميلاد <span class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-3 justify-end py-3 px-3">
                                <input type="text" name="birth_day"
                                    value="{{ old('birth_day', isset($member) && $member->birth_date ? $member->birth_date->format('d') : '') }}"
                                    placeholder="اليوم" max="9" min="0"
                                    {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                    class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" name="birth_month"
                                    value="{{ old('birth_month', isset($member) && $member->birth_date ? $member->birth_date->format('m') : '') }}"
                                    placeholder="الشهر" max="9" min="0"
                                    {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                    class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" name="birth_year"
                                    value="{{ old('birth_year', isset($member) && $member->birth_date ? $member->birth_date->format('Y') : '') }}"
                                    placeholder="السنة" max="9" min="0"
                                    {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                    class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">

                            </div>
                        </div>
                    </div>
                    <!-- END LANDLINE & PHONE -->
                    <!-- START SOCIAL STATUS & PLACE OF RESIDENCE -->
                    <div class="flex gap-5">
                        <div class=" relative flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">محل
                                الاقامة <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="address" value="{{ old('address', $member->address ?? '') }}"
                                placeholder="كما البطاقة" {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">

                        </div>
                        @include('partials.dropdown', [
                            'name' => 'marital_status',
                            'label' => 'الحالة الاجتماعية',
                            'options' => ['متزوج' => 'متزوج', 'مطلق' => 'مطلق', 'أعزب' => 'أعزب', 'أرمل' => 'أرمل'],
                            'selected' => old('marital_status', $member->marital_status ?? null),
                            'required' => true,
                            'floatingLabel' => true,
                        ])
                    </div>
                    <!-- END SOCIAL STATUS & PLACE OF RESIDENCE -->
                </div>
            </div>
        </section>
        <!-- end PersonalData section -->

        <!-- start FunctionalData section -->
        <section class="px-6">
            <div class="functional-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البيانات
                    الوظيفية
                </div>
                <div class="flex gap-5">
                    <div class="flex-1 min-w-0 space-y-7">
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">جهة
                                العمل <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="employer_name"
                                value="{{ old('employer_name', $member->employmentInfo->workplace ?? '') }}"
                                placeholder="مثال : كلية تجارة و إدارة أعمال"
                                {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">

                        </div>
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الوظيفة
                                <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="job_title"
                                value="{{ old('job_title', $member->employmentInfo->job_title ?? '') }}"
                                placeholder="مثال : مدرس مساعد مادة المحاسبة"
                                {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">

                        </div>
                        <div class="flex gap-5">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الفئة
                                    المالية الحالية <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="financial_category"
                                    value="{{ old('financial_category', $member->employmentInfo->financial_category ?? '') }}"
                                    placeholder="مثال : الفئة الثالثة"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">المرتب
                                    الشهري الأساسي عند التعيين <span class="text-[#D92D20]">*</span></label>
                                <input type="number" name="salary"
                                    value="{{ old('salary', $member->employmentInfo->salary ?? '') }}"
                                    placeholder="مثال : 360 " {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">

                            </div>
                        </div>
                    </div>
                    <div class="space-y-12">
                        <div class="relative border border-[#124375] rounded-xl flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                                إستلام العمل <span class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-3 justify-end py-3 px-3">
                                <input type="text" name="hire_day"
                                    value="{{ old('hire_day', isset($member) && $member->employmentInfo && $member->employmentInfo->join_date ? $member->employmentInfo->join_date->format('d') : '') }}"
                                    placeholder="اليوم" max="9" min="0"
                                    {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" name="hire_month"
                                    value="{{ old('hire_month', isset($member) && $member->employmentInfo && $member->employmentInfo->join_date ? $member->employmentInfo->join_date->format('m') : '') }}"
                                    placeholder="الشهر" max="9" min="0"
                                    {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" name="hire_year"
                                    value="{{ old('hire_year', isset($member) && $member->employmentInfo && $member->employmentInfo->join_date ? $member->employmentInfo->join_date->format('Y') : '') }}"
                                    placeholder="السنة" max="9" min="0"
                                    {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">

                            </div>
                        </div>
                        <div class="relative border border-[#124375] rounded-xl flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                                الإحالة إلي المعاش <span class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-3 justify-end py-3 px-3">
                                <input type="text" name="retirement_day"
                                    value="{{ old('retirement_day', isset($member) && $member->employmentInfo && $member->employmentInfo->retirement_date ? $member->employmentInfo->retirement_date->format('d') : '') }}"
                                    placeholder="اليوم" max="9" min="0"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" name="retirement_month"
                                    value="{{ old('retirement_month', isset($member) && $member->employmentInfo && $member->employmentInfo->retirement_date ? $member->employmentInfo->retirement_date->format('m') : '') }}"
                                    placeholder="الشهر" max="9" min="0"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" name="retirement_year"
                                    value="{{ old('retirement_year', isset($member) && $member->employmentInfo && $member->employmentInfo->retirement_date ? $member->employmentInfo->retirement_date->format('Y') : '') }}"
                                    placeholder="السنة" max="9" min="0"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end FunctionalData section -->

        <!-- start familyData section -->
        <section class="px-6 py-7">
            <div class="family-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البيانات
                    العائلية
                </div>
                <div class="wrapper space-y-5">
                    <div class="flex gap-5">
                        <div class="childern flex-1 min-w-0 relative border border-[#124375]  rounded-xl">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">عدد
                                الأبناء <span class="text-[#D92D20]">*</span></label>
                            <div class="py-3 text-center">
                                <input type="number" name="children_count"
                                    value="{{ old('children_count', $member->familyInfo->children_count ?? 0) }}"
                                    placeholder="0"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            </div>
                        </div>
                        <div class="phone relative border border-[#124375] rounded-xl min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                تليفون الزوج أو الزوجة أو أحد الأبناء أو أحد الأقارب <span
                                    class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-2 justify-end py-3 px-3">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.0', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 0, 1) : '') }}"
                                    placeholder="10" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24  min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.1', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 1, 1) : '') }}"
                                    placeholder="9" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.2', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 2, 1) : '') }}"
                                    placeholder="8" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.3', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 3, 1) : '') }}"
                                    placeholder="7" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.4', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 4, 1) : '') }}"
                                    placeholder="6" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.5', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 5, 1) : '') }}"
                                    placeholder="5" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.6', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 6, 1) : '') }}"
                                    placeholder="4" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.7', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 7, 1) : '') }}"
                                    placeholder="3" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.8', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 8, 1) : '') }}"
                                    placeholder="2" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.9', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 9, 1) : '') }}"
                                    placeholder="1" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="number" max="9" min="0" name="spouse_phone_digits[]"
                                    value="{{ old('spouse_phone_digits.10', isset($member->familyInfo->spouse_phone) ? substr($member->familyInfo->spouse_phone, 10, 1) : '') }}"
                                    placeholder="0" max="9" min="0"
                                    class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اسم
                                الزوج أو الزوجة <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="spouse_name"
                                value="{{ old('spouse_name', $member->familyInfo->spouse_name ?? '') }}"
                                placeholder="مثال : رباب عبدالعليم أحمد محمد"
                                {{ isset($printMode) && $printMode ? 'readonly' : '' }}
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">

                        </div>
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">وظيفته
                                أو جهة عمله <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="spouse_workplace"
                                value="{{ old('spouse_workplace', $member->familyInfo->spouse_workplace ?? '') }}"
                                placeholder="مثال : محاسبة بشركة الحديد و الصلب"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اسم
                                أحد الأبناء <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="child_name" value="{{ old('child_name') }}"
                                placeholder="مثال : لا يوجد"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">

                        </div>
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">وظيفته
                                أو جهة عمله <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="child_workplace"
                                value="{{ old('child_workplace', $member->familyInfo->child_workplace ?? '') }}"
                                placeholder="مثال : لا يوجد"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] rounded-xl px-16 py-2 bg-[#F4F7F9]">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end familyData section -->

        <!-- start files section -->
        <section class="px-6 ">
            <div class="files rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                    المرفقات
                </h2>
                <div class="wrapper space-y-7">
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <span class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                                صورة
                                بطاقة الرقم القومي <span class="text-[#D92D20]">*</span></span>
                            <label for="file-1"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[national_id_card]" id="file-1"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">

                            </label>
                        </div>
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">طلب
                                تجاوز لفوق سن 21 عاماً <span class="text-[#D92D20]">*</span></span>
                            <label for="file-2"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[over_21_request]" id="file-2"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">

                            </label>
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <span class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                                خطاب
                                الأجر الأساسي <span class="text-[#D92D20]">*</span></span>
                            <label for="file-3"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[basic_salary_letter]" id="file-3"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">

                            </label>
                        </div>
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">قرار
                                التعين <span class="text-[#D92D20]">*</span></span>
                            <label for="file-4"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[appointment_decision]" id="file-4"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">

                            </label>
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اقرار
                                القيام بالعمل <span class="text-[#D92D20]">*</span></span>
                            <label for="file-5"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[work_declaration]" id="file-5"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">

                            </label>
                        </div>
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الاستمارة
                                بعد التوقيع</span>
                            <label for="file-6"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border border-[#124375]  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[signed_membership_form]" id="file-6"
                                    class="input-file hidden text-[#6D6D6D] font-medium">

                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end files section -->

        <!-- start declaration section -->
        <section class="px-6 py-14">
            <h3 class=" text-base font-medium text-end">
                توقيع طالب العضوية بصحة البيانات
            </h3>
            <div class="mt-8 space-y-7 font-medium">
                <h4 class="text-lg text-center underline underline-offset-8">
                    إقرار
                </h4>
                <p class="leading-loose">
                    أقر أنا / ______________________ بإطلاعي علي الائحة التنفيذية الخاصة بصندوق التأمين الخاص بأعضاء هيئة
                    التدريس و معانيهم و العاملين بجامعة العاصمة و أقبل عضويتي في الصندوق اعتباراً من
                    &ensp;&ensp;&ensp;&ensp;/&ensp;&ensp;&ensp;&ensp;/&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp; و أوافق علي
                    خصم
                    قيمة قسط المشاركة خصماً من مكافأت الامتحانات المستحقة لي كل عام بما يعادل قيمة جملة الأقساط السنوية.
                </p>
                <h5 class="text-end text-base " id="date">
                    تحريراً في :
                </h5>
                <h5>
                    المقر بما فيه
                </h5>
                <div class="flex gap-5">
                    <p class="flex-1 flex items-end gap-1">الأسم / <span class="flex-1 border-b border-black"></span></p>
                    <p class="flex-1 flex items-end gap-1">الوظيفة / <span class="flex-1 border-b border-black"></span>
                    </p>
                    <p class="flex-1 flex items-end gap-1">الرقم القومي / <span
                            class="flex-1 border-b border-black"></span>
                    </p>
                    <p class="flex-1 flex items-end gap-1">التوقيع / <span class="flex-1 border-b border-black"></span>
                    </p>
                </div>
                <div class="flex flex-col gap-5 items-end">
                    <p class="px-12">
                        مدير الادارة
                    </p>
                    <p>
                        و يعتمد ، / ___________________
                    </p>
                </div>
            </div>
            <div class="flex justify-center mt-16">
                <button type="submit"
                    class="flex gap-3 py-3 w-2/4 justify-center rounded-2xl surface-shadow items-center bg-[#124375] text-white text-base font-medium hover:bg-[#0e3560] transition-colors"><iconify-icon
                        icon="material-symbols:print-rounded" class="text-2xl"></iconify-icon> حفظ البيانات و طباعة
                    الاستمارة</button>
            </div>
    </form>
    </section>
    <script src="{{ asset('JS/MembershipForm.js') }}"></script>
@endsection
