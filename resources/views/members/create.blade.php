@extends('layouts.pages')
@section('title', 'إستمارة العضوية')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/MembershipForm.css') }}">
    @php
        $mode = $mode ?? 'create';
        $isReadonly = in_array($mode, ['print', 'upload_signed']);
        $readonlyAttr = $isReadonly ? 'readonly disabled' : '';

        if ($mode === 'edit') {
            $formAction = route('members.update', $member->id ?? 0);
        } elseif ($mode === 'upload_signed') {
            $formAction = route('members.signed-form', $member->id ?? 0);
        } else {
            $formAction = route('members.store');
        }

        // ── Prepare member data for edit/print/upload_signed modes ──
        $m = $member ?? null;
        $emp = $m?->employmentInfo;
        $fam = $m?->familyInfo;

        // Helper: split a string into individual characters array
        $splitDigits = function(?string $value, int $count) {
            if (!$value) return array_fill(0, $count, '');
            return array_pad(str_split($value), $count, '');
        };

        // Helper: split a date into day/month/year
        $splitDate = function($date) {
            if (!$date) return ['day' => '', 'month' => '', 'year' => ''];
            $d = \Carbon\Carbon::parse($date);
            return ['day' => $d->day, 'month' => $d->month, 'year' => $d->year];
        };

        $nationalIdDigits = $splitDigits($m?->national_id, 14);
        $phoneDigits = $splitDigits($m?->phone, 11);
        $landlineDigits = $splitDigits($m?->landline, 8);
        $spousePhoneDigits = $splitDigits($fam?->spouse_phone, 11);

        $birthParts = $splitDate($m?->birth_date);
        $hireParts = $splitDate($emp?->join_date);
        $retirementParts = $splitDate($emp?->retirement_date);
    @endphp
    <!-- start Header -->
    <div class="flex justify-between items-center py-5 px-7">
        <div class="logo surface-shadow">
            <img class="w-20" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="Logo">
        </div>
        <div class="title text-xl font-semibold text-[#124375]">
            <h1>
                إستمارة العضوية
            </h1>
        </div>
        <div class="btn-close flex gap-3">
            @if ($mode === 'upload_signed')
                <a href="{{ route('members.edit', $member->id ?? 0) }}"
                    class="bg-[#124375] text-white rounded-2xl py-2 px-12 text-base font-medium hover:bg-[#0e3560] transition-colors">
                    تعديل البيانات
                </a>
            @endif
            <a href="{{ route('members.index') }}"
                class="bg-[#F4F7F9] text-[#D92D20] rounded-2xl py-2 px-12 text-base font-medium">
                إلغاء
            </a>
        </div>
    </div>
    <!-- end Header -->

    <!-- start Form -->
    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif
        <input type="hidden" id="member_id" value="{{ $member->id ?? '' }}">
        <!-- start personalData section -->
        <section class="px-6 py-7">
            <fieldset {{ $readonlyAttr }}>
                <div class="personal-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                    <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                        البيانات الشخصية
                    </h2>
                    <div class="space-y-7">
                        <!-- START FULL NAME & EMAIL -->
                        <div class="flex gap-5">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                                    الأسم
                                    رباعي <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="full_name" value="{{ old('full_name', $m?->full_name) }}"
                                    placeholder="مثال : أحمد محمد إسماعيل محمود"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('full_name') ? 'border-[#D92D20]' : 'border-[#124375]' }} outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('full_name')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative w-full">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                                    البريد الإلكتروني <span class="text-[#D92D20]">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $m?->user?->email) }}"
                                    placeholder="ahmed@gmail.com : مثال"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('email') ? 'border-[#D92D20]' : 'border-[#124375]' }} outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('email')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- END FULL NAME & EMAIL -->
                        <!-- START LANDLINE & PHONE -->
                        <div class="flex gap-6">
                            <div
                                class="phone relative border {{ $errors->has('phone_digits') || $errors->has('phone_digits.*') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                    التليفون <span class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-3 justify-end py-3 px-3">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.0', $phoneDigits[0]) }}"
                                        placeholder="10" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.1', $phoneDigits[1]) }}"
                                        placeholder="9" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.2', $phoneDigits[2]) }}"
                                        placeholder="8" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.3', $phoneDigits[3]) }}"
                                        placeholder="7" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.4', $phoneDigits[4]) }}"
                                        placeholder="6" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.5', $phoneDigits[5]) }}"
                                        placeholder="5" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.6', $phoneDigits[6]) }}"
                                        placeholder="4" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.7', $phoneDigits[7]) }}"
                                        placeholder="3" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.8', $phoneDigits[8]) }}"
                                        placeholder="2" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.9', $phoneDigits[9]) }}"
                                        placeholder="1" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="phone_digits[]" value="{{ old('phone_digits.10', $phoneDigits[10]) }}"
                                        placeholder="0" maxlength="1"
                                        class="phone-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @error('phone_digits')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('phone_digits.*')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div
                                class="HomePhone relative border {{ $errors->has('landline_digits') || $errors->has('landline_digits.*') ? 'border-[#D92D20]' : 'border-[#124375]' }}  rounded-xl min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                    هاتف المنزل</label>
                                <div class="flex gap-2 justify-end py-3 px-3">
                                    <input type="number" min="0" max="9" name="landline_digits[]"
                                        value="{{ old('landline_digits.0', $landlineDigits[0]) }}" placeholder="7" maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="landline_digits[]"
                                        value="{{ old('landline_digits.1', $landlineDigits[1]) }}" placeholder="6" maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="landline_digits[]"
                                        value="{{ old('landline_digits.2', $landlineDigits[2]) }}" placeholder="5" maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="landline_digits[]"
                                        value="{{ old('landline_digits.3', $landlineDigits[3]) }}" placeholder="4" maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="landline_digits[]"
                                        value="{{ old('landline_digits.4', $landlineDigits[4]) }}" placeholder="3" maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="landline_digits[]"
                                        value="{{ old('landline_digits.5', $landlineDigits[5]) }}" placeholder="2" maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="landline_digits[]"
                                        value="{{ old('landline_digits.6', $landlineDigits[6]) }}" placeholder="1" maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="landline_digits[]"
                                        value="{{ old('landline_digits.7', $landlineDigits[7]) }}" placeholder="0" maxlength="1"
                                        class="landline-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @error('landline_digits')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('landline_digits.*')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- END LANDLINE & PHONE -->
                        <!-- START DATE OF BIRTH -->
                        <div class="flex gap-5">
                            <div
                                class=" relative border {{ $errors->has('national_id_digits') || $errors->has('national_id_digits.*') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الرقم
                                    القومي <span class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-3 justify-end py-3 px-3">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.0', $nationalIdDigits[0]) }}" placeholder="14" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.1', $nationalIdDigits[1]) }}" placeholder="13" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.2', $nationalIdDigits[2]) }}" placeholder="12" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.3', $nationalIdDigits[3]) }}" placeholder="11" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.4', $nationalIdDigits[4]) }}" placeholder="10" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.5', $nationalIdDigits[5]) }}" placeholder="9" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.6', $nationalIdDigits[6]) }}" placeholder="8" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.7', $nationalIdDigits[7]) }}" placeholder="7" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.8', $nationalIdDigits[8]) }}" placeholder="6" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.9', $nationalIdDigits[9]) }}" placeholder="5" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.10', $nationalIdDigits[10]) }}" placeholder="4" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.11', $nationalIdDigits[11]) }}" placeholder="3" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.12', $nationalIdDigits[12]) }}" placeholder="2" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="national_id_digits[]"
                                        value="{{ old('national_id_digits.13', $nationalIdDigits[13]) }}" placeholder="1" maxlength="1"
                                        class="id-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-16 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @error('national_id_digits')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('national_id_digits.*')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div
                                class="relative border {{ $errors->has('birth_day') || $errors->has('birth_month') || $errors->has('birth_year') || $errors->has('date_of_birth') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl  min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                                    الميلاد <span class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-3 justify-end py-3 px-3">
                                    <input type="text" name="birth_day" value="{{ old('birth_day', $birthParts['day']) }}"
                                        placeholder="اليوم" maxlength="2"
                                        class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="birth_month" value="{{ old('birth_month', $birthParts['month']) }}"
                                        placeholder="الشهر" maxlength="2"
                                        class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="birth_year" value="{{ old('birth_year', $birthParts['year']) }}"
                                        placeholder="السنة" maxlength="4"
                                        class="date-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @error('birth_day')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('birth_month')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('birth_year')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('date_of_birth')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- END LANDLINE & PHONE -->
                        <!-- START SOCIAL STATUS & PLACE OF RESIDENCE -->
                        <div class="flex gap-5">
                            <div class=" relative flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">محل
                                    الاقامة <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="address" value="{{ old('address', $m?->address) }}"
                                    placeholder="كما البطاقة"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('address') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('address')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div
                                class="border {{ $errors->has('marital_status') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl py-2 w-80 bg-[#F4F7F9] relative">
                                <input type="hidden" name="marital_status" id="marital_status_hidden"
                                    value="{{ old('marital_status', $m?->marital_status) }}">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الحالة
                                    الاجتماعية <span class="text-[#D92D20]">*</span></label>
                                <span id="status-text" class="px-3 text-[#6D6D6D] text-sm font-medium">{{ old('marital_status', $m?->marital_status) ?: 'أختر' }}</span>
                                <iconify-icon id="dropdown-btn" icon="oui:arrow-down"
                                    class="cursor-pointer absolute left-[5px] top-[12px] text-xl text-[#124375]"></iconify-icon>
                                <!-- Dropdown -->
                                <div
                                    class="dropdown hidden absolute top-[calc(100%+8px)]  left-0 w-fit bg-[#F4F7F9] py-4 px-4 rounded-2xl surface-shadow z-50">
                                    <div class="flex flex-col gap-3">
                                        <label
                                            class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                                            <span class="text-xl font-semibold text-[#124375]">متزوج</span>
                                            <input type="radio" name="gender" class="peer hidden" value="متزوج">
                                            <span
                                                class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                        </label>
                                        <label
                                            class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                                            <span class="text-xl font-semibold text-[#124375]">مطلق</span>
                                            <input type="radio" name="gender" class="peer hidden" value="مطلق">
                                            <span
                                                class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                        </label>
                                        <label
                                            class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                                            <span class="text-xl font-semibold text-[#124375]">أعزب</span>
                                            <input type="radio" name="gender" class="peer hidden" value="أعزب">
                                            <span
                                                class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                        </label>
                                        <label
                                            class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                                            <span class="text-xl font-semibold text-[#124375]">أرمل</span>
                                            <input type="radio" name="gender" class="peer hidden" value="أرمل">
                                            <span
                                                class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                        </label>
                                        <button type="button" id="confirm-status-btn"
                                            class="surface-shadow bg-[#124375] text-white text-lg font-semibold py-3 rounded-xl mt-1 hover:bg-[#0e3560] transition-colors">تأكيد</button>
                                    </div>
                                </div>
                                @error('marital_status')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- END SOCIAL STATUS & PLACE OF RESIDENCE -->
                    </div>
                </div>
            </fieldset>
        </section>
        <!-- end PersonalData section -->

        <!-- start FunctionalData section -->
        <section class="px-6">
            <fieldset {{ $readonlyAttr }}>
                <div class="personal-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                    <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البيانات
                        الوظيفية
                    </div>
                    <div class="flex gap-5">
                        <div class="flex-1 min-w-0 space-y-7">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">جهة
                                    العمل <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="employer_name" value="{{ old('employer_name', $emp?->workplace) }}"
                                    placeholder="مثال : كلية تجارة و إدارة أعمال"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('employer_name') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('employer_name')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الوظيفة
                                    <span class="text-[#D92D20]">*</span></label>
                                <input type="text" name="job_title" value="{{ old('job_title', $emp?->job_title) }}"
                                    placeholder="مثال : مدرس مساعد مادة المحاسبة"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('job_title') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('job_title')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex gap-5">
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الفئة
                                        المالية الحالية <span class="text-[#D92D20]">*</span></label>
                                    <input type="text" name="financial_category"
                                        value="{{ old('financial_category', $emp?->financial_category) }}" placeholder="مثال : الفئة الثالثة"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('financial_category') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    @error('financial_category')
                                        <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full relative">
                                    <label
                                        class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">المرتب
                                        الشهري الأساسي عند التعيين <span class="text-[#D92D20]">*</span></label>
                                    <input type="text" name="salary" value="{{ old('salary', $emp?->starting_salary) }}"
                                        placeholder="مثال : 360 "
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('salary') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    @error('salary')
                                        <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="space-y-12">
                            <div
                                class="relative border {{ $errors->has('hire_day') || $errors->has('hire_month') || $errors->has('hire_year') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                                    إستلام العمل <span class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-3 justify-end py-3 px-3">
                                    <input type="text" name="hire_day" value="{{ old('hire_day', $hireParts['day']) }}"
                                        placeholder="اليوم" maxlength="2"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="hire_month" value="{{ old('hire_month', $hireParts['month']) }}"
                                        placeholder="الشهر" maxlength="2"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="hire_year" value="{{ old('hire_year', $hireParts['year']) }}"
                                        placeholder="السنة" maxlength="4"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @error('hire_day')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('hire_month')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('hire_year')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div
                                class="relative border {{ $errors->has('retirement_day') || $errors->has('retirement_month') || $errors->has('retirement_year') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl flex-1 min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">تاريخ
                                    الإحالة إلي المعاش <span class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-3 justify-end py-3 px-3">
                                    <input type="text" name="retirement_day" value="{{ old('retirement_day', $retirementParts['day']) }}"
                                        placeholder="اليوم" maxlength="2"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="retirement_month" value="{{ old('retirement_month', $retirementParts['month']) }}"
                                        placeholder="الشهر" maxlength="2"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-28 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="text" name="retirement_year" value="{{ old('retirement_year', $retirementParts['year']) }}"
                                        placeholder="السنة" maxlength="4"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-full min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @error('retirement_day')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('retirement_month')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('retirement_year')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </section>
        <!-- end FunctionalData section -->

        <!-- start familyData section -->
        <section class="px-6 py-7">
            <fieldset {{ $readonlyAttr }}>
                <div class="family-data rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                    <div class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البيانات
                        العائلية
                    </div>
                    <div class="wrapper space-y-5">
                        <div class="flex gap-5">
                            <div
                                class="childern flex-1 min-w-0 relative border {{ $errors->has('children_count') ? 'border-[#D92D20]' : 'border-[#124375]' }}  rounded-xl">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">عدد
                                    الأبناء </label>
                                <div class="py-3 text-center">
                                    <input type="number" name="children_count" value="{{ old('children_count', $fam?->children_count ?? 0) }}"
                                        placeholder="0"
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-14 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @error('children_count')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div
                                class="phone relative border {{ $errors->has('spouse_phone_digits') || $errors->has('spouse_phone_digits.*') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl min-w-0">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                    تليفون الزوج أو الزوجة أو أحد الأبناء أو أحد الأقارب <span
                                        class="text-[#D92D20]">*</span></label>
                                <div class="flex gap-2 justify-end py-3 px-3">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.0', $spousePhoneDigits[0]) }}" placeholder="10" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24  min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.1', $spousePhoneDigits[1]) }}" placeholder="9" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.2', $spousePhoneDigits[2]) }}" placeholder="8" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.3', $spousePhoneDigits[3]) }}" placeholder="7" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.4', $spousePhoneDigits[4]) }}" placeholder="6" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.5', $spousePhoneDigits[5]) }}" placeholder="5" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.6', $spousePhoneDigits[6]) }}" placeholder="4" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.7', $spousePhoneDigits[7]) }}" placeholder="3" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.8', $spousePhoneDigits[8]) }}" placeholder="2" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.9', $spousePhoneDigits[9]) }}" placeholder="1" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                    <input type="number" min="0" max="9" name="spouse_phone_digits[]"
                                        value="{{ old('spouse_phone_digits.10', $spousePhoneDigits[10]) }}" placeholder="0" maxlength="1"
                                        class="number-input focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none rounded-md w-24 min-w-0 py-1 input-shadow bg-[#F4F7F9] text-center">
                                </div>
                                @error('spouse_phone_digits')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                                @error('spouse_phone_digits.*')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اسم
                                    الزوج أو الزوجة </label>
                                <input type="text" name="spouse_name" value="{{ old('spouse_name', $fam?->spouse_name) }}"
                                    placeholder="مثال : رباب عبدالعليم أحمد محمد"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('spouse_name') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('spouse_name')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">وظيفته
                                    أو جهة عمله </label>
                                <input type="text" name="spouse_workplace" value="{{ old('spouse_workplace', $fam?->spouse_workplace) }}"
                                    placeholder="مثال : محاسبة بشركة الحديد و الصلب"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('spouse_workplace') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('spouse_workplace')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اسم
                                    أحد الأبناء </label>
                                <input type="text" name="child_name" value="{{ old('child_name', $fam?->child_name) }}"
                                    placeholder="مثال : لا يوجد"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('child_name') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('child_name')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">وظيفته
                                    أو جهة عمله </label>
                                <input type="text" name="child_workplace" value="{{ old('child_workplace', $fam?->child_workplace) }}"
                                    placeholder="مثال : لا يوجد"
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none text-[#6D6D6D] font-medium text-base w-full text-center border {{ $errors->has('child_workplace') ? 'border-[#D92D20]' : 'border-[#124375]' }} rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                @error('child_workplace')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </section>
        <!-- end familyData section -->

        <!-- start files section -->
        <section class="px-6 ">
            <div class="files rounded-2xl border-2 border-[#124375] py-7 px-7 relative">
                <h2 class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                    المرفقات
                </h2>
                <div class="wrapper space-y-7">
                    <fieldset {{ $readonlyAttr }}>
                        {{-- صورة بطاقة الرقم القومي --}}
                        <div class="flex gap-5 mt-8">
                            <div class="w-full relative">
                                <span
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                                    صورة
                                    بطاقة الرقم القومي <span class="text-[#D92D20]">*</span></span>
                                <label for="file-1"
                                    class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border {{ $errors->has('documents.national_id_card') ? 'border-[#D92D20]' : 'border-[#124375]' }}  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                    <input type="file" name="documents[national_id_card]" id="file-1"
                                        class="input-file hidden text-[#6D6D6D] font-medium ">
                                </label>
                                @error('documents.national_id_card')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full relative">
                                <span
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">طلب
                                    تجاوز لفوق سن 21 عاماً <span class="text-[#D92D20]">*</span></span>
                                <label for="file-2"
                                    class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border {{ $errors->has('documents.over_21_request') ? 'border-[#D92D20]' : 'border-[#124375]' }}  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                    <input type="file" name="documents[over_21_request]" id="file-2"
                                        class="input-file hidden text-[#6D6D6D] font-medium ">
                                </label>
                                @error('documents.over_21_request')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        {{-- خطاب الاجر الاساسي --}}
                        <div class="flex gap-5 mt-8">
                            <div class="w-full relative">
                                <span
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                                    خطاب
                                    الأجر الأساسي <span class="text-[#D92D20]">*</span></span>
                                <label for="file-3"
                                    class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border {{ $errors->has('documents.basic_salary_letter') ? 'border-[#D92D20]' : 'border-[#124375]' }}  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                    <input type="file" name="documents[basic_salary_letter]" id="file-3"
                                        class="input-file hidden text-[#6D6D6D] font-medium ">
                                </label>
                                @error('documents.basic_salary_letter')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- خطاب التعيين --}}
                            <div class="w-full relative">
                                <span
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">قرار
                                    التعين <span class="text-[#D92D20]">*</span></span>
                                <label for="file-4"
                                    class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border {{ $errors->has('documents.appointment_decision') ? 'border-[#D92D20]' : 'border-[#124375]' }}  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                    <input type="file" name="documents[appointment_decision]" id="file-4"
                                        class="input-file hidden text-[#6D6D6D] font-medium ">
                                </label>
                                @error('documents.appointment_decision')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex gap-5 mt-8">
                            <div class="w-full relative">
                                <span
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">اقرار
                                    القيام بالعمل <span class="text-[#D92D20]">*</span></span>
                                <label for="file-5"
                                    class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border {{ $errors->has('documents.work_declaration') ? 'border-[#D92D20]' : 'border-[#124375]' }}  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                    <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl file-icon"></iconify-icon>
                                    <input type="file" name="documents[work_declaration]" id="file-5"
                                        class="input-file hidden text-[#6D6D6D] font-medium ">
                                </label>
                                @error('documents.work_declaration')
                                    <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div
                                class="w-full relative {{ $mode === 'upload_signed' ? '' : 'opacity-50 pointer-events-none' }}">
                                <fieldset {{ $mode === 'upload_signed' ? '' : 'disabled' }}>
                                    <span
                                        class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الاستمارة
                                        بعد التوقيع</span>
                                    <label for="file-6"
                                        class="text-[#124375] cursor-pointer flex items-center justify-center gap-2 w-full border {{ $errors->has('documents.signed_membership_form') ? 'border-[#D92D20]' : 'border-[#124375]' }}  rounded-xl px-16 py-2 bg-[#F4F7F9]">
                                        <span class="text-base file-name">ارفاق المستند المطلوب</span>
                                        <iconify-icon icon="mingcute:upload-3-fill"
                                            class="text-2xl file-icon"></iconify-icon>
                                        <input type="file" name="documents[signed_membership_form]" id="file-6"
                                            class="input-file hidden text-[#6D6D6D] font-medium">
                                    </label>
                                    @error('documents.signed_membership_form')
                                        <p class="absolute bottom-[-8px] bg-[#F4F7F9] right-5 text-[#D92D20] text-sm font-medium px-2">{{ $message }}</p>
                                    @enderror
                                </fieldset>
                            </div>
                        </div>
                    </fieldset>
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
            <div class="flex justify-center mt-16 gap-5">
                @if ($mode === 'print')
                    <button type="button" id="print-btn"
                        class="flex gap-3 py-3 w-2/4 justify-center rounded-2xl surface-shadow items-center bg-[#124375] text-white text-base font-medium hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="material-symbols:print-rounded" class="text-2xl"></iconify-icon> طباعة
                        الاستمارة
                    </button>
                @elseif ($mode === 'upload_signed')
                    <button type="submit"
                        class="flex gap-3 py-3 w-2/4 justify-center rounded-2xl surface-shadow items-center bg-[#124375] text-white text-base font-medium hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="material-symbols:save" class="text-2xl"></iconify-icon> حفظ البيانات للدفع
                    </button>
                @else
                    <button type="submit"
                        class="flex gap-3 py-3 w-2/4 justify-center rounded-2xl surface-shadow items-center bg-[#124375] text-white text-base font-medium hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="material-symbols:save" class="text-2xl"></iconify-icon> حفظ البيانات
                    </button>
                @endif
            </div>
        </section>

        <!-- end declaration section -->
    </form>
    <script src="{{ asset('js/MembershipForm.js') }}"></script>
@endsection
