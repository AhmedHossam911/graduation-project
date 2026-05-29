@extends('layouts.pages')
{{--
    Create Member View:
    Digital form for employees to register new fund members manually.
    Captures Personal Data, Functional Data, Family Data, and required Document Uploads.
--}}
@section('title', 'إستمارة العضوية')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/employee/MembershipForm.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- start Header -->
    <div class="flex justify-between items-center py-5 px-7">
        <div class="logo surface-shadow">
            <img src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" class="w-16">
        </div>
        <div class="title text-xl font-semibold text-[#124375]">
            <h1>
                إستمارة العضوية
            </h1>
        </div>

        <div class="flex items-center gap-4 relative">
            @if ($errors->any())
                <div
                    class="absolute left-[calc(100%)] ml-12 flex items-center w-max gap-2 border border-[#D92D20] bg-[#FFF4F2] text-[#D92D20] px-4 py-2 rounded-xl font-medium">
                    <span>تعذر تسجيل العضو، لعدم إكتمال البيانات .</span>
                    <iconify-icon icon="ph:x-circle-fill" class="text-xl"></iconify-icon>
                </div>
            @endif
            <a href="{{route('members.index')}}" class="btn-close">
                <button type="button"
                    class="bg-[#F4F7F9] text-[#D92D20] border border-[#D92D20] rounded-2xl py-2 px-12 text-base font-medium">
                    إلغاء
                </button>
            </a>
        </div>
    </div>
    <!-- end Header -->

    <div class="print:hidden">

    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- start Form -->
        <!-- start personalData section -->
        <section class="px-6 py-7">
            <div class="personal-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">
                    البيانات الشخصية
                </h2>
                <div class="space-y-7">
                    <!-- START FULL NAME & EMAIL -->
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 @error('full_name') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">
                                الأسم رباعي <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                placeholder="مثال : أحمد محمد إسماعيل محمود"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border @error('full_name') border-[#D92D20] @else border-[#124375] @enderror outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            @error('full_name')
                                <span
                                    class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="relative w-full">
                            <label
                                class="absolute top-[-15px] right-5 @error('email') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">
                                البريد الإلكتروني <span class="text-[#D92D20]">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="ahmed@gmail.com : مثال"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border @error('email') border-[#D92D20] @else border-[#124375] @enderror outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            @error('email')
                                <span
                                    class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- END FULL NAME & EMAIL -->

                    <!-- START LANDLINE & PHONE -->
                    <div class="flex gap-6">
                        <div
                            class="phone relative border @error('phone_digits') border-[#D92D20] @elseif($errors->has('phone_digits.*')) border-[#D92D20] @else border-[#124375] @enderror rounded-xl flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 @error('phone_digits') text-[#D92D20] @elseif($errors->has('phone_digits.*')) text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">رقم
                                التليفون <span class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-3 justify-end py-3 px-3">
                                @for ($i = 10; $i >= 0; $i--)
                                    <input type="tel" name="phone_digits[]" value="{{ old('phone_digits.' . (10 - $i)) }}"
                                        placeholder="{{ $i }}" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
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
                            <div class="flex gap-2 justify-end py-3 px-3">
                                @for ($i = 7; $i >= 0; $i--)
                                    <input type="tel" name="landline_digits[]"
                                        value="{{ old('landline_digits.' . (7 - $i)) }}" placeholder="{{ $i }}"
                                        maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
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
                    <div class="flex gap-5">
                        <div
                            class=" relative border @error('national_id_digits') border-[#D92D20] @elseif($errors->has('national_id_digits.*')) border-[#D92D20] @else border-[#124375] @enderror rounded-xl flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 @error('national_id_digits') text-[#D92D20] @elseif($errors->has('national_id_digits.*')) text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">الرقم
                                القومي <span class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-3 justify-end py-3 px-3">
                                @for ($i = 13; $i >= 0; $i--)
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.' . (13 - $i)) }}" placeholder="{{ $i + 1 }}"
                                        maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
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
                            <div class="flex gap-3 justify-end py-3 px-3">
                                <input type="text" id="birth_day" name="birth_day" value="{{ old('birth_day') }}"
                                    placeholder="اليوم" maxlength="2"
                                    class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" id="birth_month" name="birth_month" value="{{ old('birth_month') }}"
                                    placeholder="الشهر" maxlength="2"
                                    class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" id="birth_year" name="birth_year" value="{{ old('birth_year') }}"
                                    placeholder="السنة" maxlength="4"
                                    class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
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
                    <div class="flex gap-5">
                        <div class=" relative flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 @error('address') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">محل
                                الاقامة <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="كما البطاقة"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('address') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            @error('address')
                                <span
                                    class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-80">
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
                                'showConfirm' => true,
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
                <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">البيانات
                    الوظيفية</div>
                <div class="flex gap-5">
                    <div class="flex-1 min-w-0 space-y-7">
                        <div class="w-full relative pt-2">
                            @php
                                $deptOptions = [];
                                foreach ($departments as $dept) {
                                    $deptOptions[$dept->name] = $dept->name;
                                }
                            @endphp
                            @include('partials.common.dropdown', [
                                'name' => 'employer_name',
                                'label' => 'جهة العمل',
                                'options' => $deptOptions,
                                'selected' => old('employer_name'),
                                'placeholder' => 'أختر',
                                'required' => true,
                                'floatingLabel' => true,
                                'showConfirm' => true,
                            ])
                        </div>
                        <div class="w-full relative">
                            <label
                                class="absolute top-[-15px] right-5 @error('job_title') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">الوظيفة
                                <span class="text-[#D92D20]">*</span></label>
                            <input type="text" name="job_title" value="{{ old('job_title') }}"
                                placeholder="مثال : مدرس مساعد مادة المحاسبة"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border @error('job_title') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            @error('job_title')
                                <span
                                    class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex gap-5">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 @error('financial_category') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">الفئة
                                    المالية الحالية <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="financial_category" value="{{ old('financial_category') }}"
                                    placeholder="مثال : الفئة الثالثة"
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
                    <div class="space-y-12">
                        <div
                            class="relative border @if ($errors->has('hire_day') || $errors->has('hire_month') || $errors->has('hire_year')) border-[#D92D20] @else border-[#124375] @endif rounded-xl flex-1 min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 @if ($errors->has('hire_day') || $errors->has('hire_month') || $errors->has('hire_year')) text-[#D92D20] @else text-[#124375] @endif text-base font-medium bg-[#F4F7F9] px-1">تاريخ
                                إستلام العمل <span class="text-[#D92D20]">*</span></label>
                            <div class="flex gap-3 justify-end py-3 px-3">
                                <input type="text" name="hire_day" value="{{ old('hire_day') }}" placeholder="اليوم"
                                    maxlength="2"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" name="hire_month" value="{{ old('hire_month') }}"
                                    placeholder="الشهر" maxlength="2"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                <input type="text" name="hire_year" value="{{ old('hire_year') }}"
                                    placeholder="السنة" maxlength="4"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
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
                            <div class="flex gap-3 justify-end py-3 px-3">
                                <input type="text" id="retirement_day" name="retirement_day"
                                    value="{{ old('retirement_day') }}" placeholder="اليوم" maxlength="2" readonly
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#E8EDF2] text-center cursor-not-allowed">
                                <input type="text" id="retirement_month" name="retirement_month"
                                    value="{{ old('retirement_month') }}" placeholder="الشهر" maxlength="2" readonly
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#E8EDF2] text-center cursor-not-allowed">
                                <input type="text" id="retirement_year" name="retirement_year"
                                    value="{{ old('retirement_year') }}" placeholder="السنة" maxlength="4" readonly
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#E8EDF2] text-center cursor-not-allowed">
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
                <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">البيانات
                    العائلية</div>
                <div class="wrapper space-y-5">
                    <div class="flex gap-5">
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
                            class="phone relative border @error('spouse_phone_digits') border-[#D92D20] @elseif($errors->has('spouse_phone_digits.*')) border-[#D92D20] @else border-[#124375] @enderror rounded-xl min-w-0">
                            <label
                                class="absolute top-[-15px] right-5 @error('spouse_phone_digits') text-[#D92D20] @elseif($errors->has('spouse_phone_digits.*')) text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">رقم
                                تليفون الزوج أو الزوجة أو أحد الأبناء أو أحد الأقارب</label>
                            <div class="flex gap-2 justify-end py-3 px-3">
                                @for ($i = 10; $i >= 0; $i--)
                                    <input type="tel" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.' . (10 - $i)) }}"
                                        placeholder="{{ $i }}" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                @endfor
                            </div>
                            @if ($errors->has('spouse_phone_digits') || $errors->has('spouse_phone_digits.*'))
                                <span
                                    class="absolute bottom-[-11px] right-5 text-[#D92D20] text-sm font-medium bg-[#F4F7F9] px-2">يجب
                                    إدخال 11 رقم.</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-5">
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
                    <div class="flex gap-5">
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
                <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">المرفقات
                </h2>
                <div class="wrapper space-y-7">
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 @error('documents.national_id_card') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">
                                صورة بطاقة الرقم القومي <span class="text-[#D92D20]">*</span></span>
                            <label for="file-1"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border @error('documents.national_id_card') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[national_id_card]" id="file-1"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">
                            </label>
                        </div>
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 @error('documents.over_21_request') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">طلب
                                تجاوز لفوق سن 21 عاماً <span class="text-[#D92D20]">*</span></span>
                            <label for="file-2"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border @error('documents.over_21_request') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[over_21_request]" id="file-2"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">
                            </label>
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 @error('documents.basic_salary_letter') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">
                                خطاب الأجر الأساسي <span class="text-[#D92D20]">*</span></span>
                            <label for="file-3"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border @error('documents.basic_salary_letter') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[basic_salary_letter]" id="file-3"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">
                            </label>
                        </div>
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 @error('documents.appointment_decision') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">قرار
                                التعين <span class="text-[#D92D20]">*</span></span>
                            <label for="file-4"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border @error('documents.appointment_decision') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
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
                                class="absolute top-[-15px] right-5 @error('documents.work_declaration') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">اقرار
                                القيام بالعمل <span class="text-[#D92D20]">*</span></span>
                            <label for="file-5"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border @error('documents.work_declaration') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[work_declaration]" id="file-5"
                                    class="input-file hidden text-[#6D6D6D] font-medium ">
                            </label>
                        </div>
                        <div class="w-full relative">
                            <span
                                class="absolute top-[-15px] right-5 @error('documents.manual_request') text-[#D92D20] @else text-[#124375] @enderror text-base font-medium bg-[#F4F7F9] px-1">طلب
                                يدوي بالتسجيل من خلال الموظف <span class="text-[#D92D20]">*</span></span>
                            <label for="file-6"
                                class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border @error('documents.manual_request') border-[#D92D20] @else border-[#124375] @enderror rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                <input type="file" name="documents[manual_request]" id="file-6"
                                    class="input-file hidden text-[#6D6D6D] font-medium">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end files section -->

        <!-- start declaration section -->
        <div class="flex justify-center mt-16 mb-16">
            <button type="submit"
                class="flex gap-3 py-3 w-2/4 justify-center rounded-2xl surface-shadow items-center bg-[#124375] text-white text-base font-medium hover:bg-[#0e3560] transition-colors"><iconify-icon
                    icon="material-symbols:print-rounded" class="text-2xl"></iconify-icon> حفظ البيانات
            </button>
        </div>
    </form>

    <script>
        const SYSTEM_RETIREMENT_AGE = {{ \App\Models\System\SystemSetting::get('retirement_age', 60) }};
    </script>
    <script src="{{ asset('js/employee/MembershipForm.js') }}?v={{ time() }}"></script>

    @if (session('receipt_data'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let receiptData = {!! session('receipt_data') !!};
                Swal.fire({
                    html: `
                    <x-print-layout title="إيصال اشتراك عضوية جديدة" reference="${receiptData.membership_number}">
                    <div class="receipt-container p-6 text-right" style="background-color: #F4F7F9; border-radius: 12px; font-family: 'Tajawal', sans-serif; direction: rtl;">
                        <h2 class="text-2xl font-bold text-[#124375] mb-6 text-center">إيصال دفع إشتراك عضوية جديدة</h2>

                        <div class="flex justify-between items-center mb-6 text-[#124375] font-medium border-b border-[#124375] pb-4">
                            <div><span>التاريخ :</span> <span>${receiptData.date}</span></div>
                            <div><span>الوقت :</span> <span>${new Date().toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'})}</span></div>
                            <div><span>قيمة الأشتراك :</span> <span class="font-bold">${receiptData.amount}</span></div>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-bold mb-2">بيانات العضو</h3>
                            <div class="flex flex-row-reverse justify-between items-center text-[#124375]">
                                <div><span>الأسم رباعي :</span> <span class="text-[#6D6D6D]">${receiptData.name}</span></div>
                                <div><span>رقم العضوية :</span> <span class="text-[#6D6D6D]">${receiptData.membership_number}</span></div>
                            </div>
                        </div>

                        <div class="border-t border-[#124375] pt-4 mb-6">
                            <h3 class="text-lg font-bold mb-2">بيانات الجهة</h3>
                            <div class="flex flex-row-reverse justify-between items-center text-[#124375]">
                                <div><span>البنك :</span> <span class="text-[#6D6D6D]">بنك مصر</span></div>
                                <div><span>الجهة :</span> <span class="text-[#6D6D6D]">صندوق الزمالة - جامعة العاصمة</span></div>
                                <div><span>رقم حساب المستفيد :</span> <span class="text-[#6D6D6D]">077777777777777</span></div>
                            </div>
                        </div>

                        <button onclick="window.print()" class="w-full bg-[#124375] text-white py-3 rounded-xl font-bold text-lg flex justify-center items-center gap-2 hover:bg-[#0e3560] transition-colors print:hidden no-print">
                            <iconify-icon icon="material-symbols:print-rounded" class="text-2xl"></iconify-icon> طباعة الإيصال
                        </button>
                    </div>
                    </x-print-layout>
                `,
                    showConfirmButton: false,
                    width: '800px',
                    background: '#F4F7F9',
                    customClass: {
                        popup: 'rounded-2xl border border-[#124375]'
                    }
                });
            });
        </script>
    @endif
@endsection

