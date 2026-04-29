@extends('layouts.pages')
@section('title', 'عرض بيانات العضو')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
    @php
        $claims = $claimTypes ?? [];

        if (request('claim_type')) {
            $claim_type = request('claim_type');
        }

        $statusCode = $member->membershipInfo->status ?? 'unknown';
        $statusData = $statusMap[$statusCode] ?? [
            'label' => 'غير معروف',
            'class' => 'unknown',
        ];
        $classMap = [
            'active' => 'text-[#067647] border-[#067647] bg-[#ECFDF3]',
            'pending' => 'text-[#175CD3] border-[#175CD3] bg-[#EFF8FF]',
            'loan' => 'text-[#5925DC] border-[#5925DC] bg-[#5925DC]',
            'pension' => 'text-[#E6B800] border-[#E6B800] bg-[#FFF8E1]',
            'withdrawn' => 'text-[#F79009] border-[#F79009] bg-[#F79009]',
            'dismissed' => 'text-[#D92D20] border-[#D92D20] bg-[#FFEAE8]',
            'unpaid_leave' => 'text-[#4B5A70] border-[#4B5A70] bg-[#4B5A70]',
            'expired' => 'text-[#021219] border-[#021219] bg-[#021219]',
            'suspended' => 'text-[#D92D20] border-[#D92D20] bg-[#FFEAE8]',
        ];
        $badgeClass = $classMap[$statusCode] ?? 'text-gray-500 border-gray-400 bg-gray-100';
    @endphp
    <!-- start header -->
    <div class="flex justify-between items-center px-10 py-5">
        <div class="flex flex-col gap-4">
            <div class="flex gap-7 items-center ">
                <p class="text-[28px] font-semibold text-[#124375]">{{ $member->full_name }}</p>
                <p class="mt-3 status {{ $badgeClass }} rounded-lg px-10 border ">{{ $statusData['label'] }}</p>
            </div>
            <P class="text-[#021219] text-sm font-medium flex items-center gap-4">رقم العضوية : <span
                    class="text-[#124375] font-semibold text-xl">{{ $member->membershipInfo->membership_number ?? '-' }}</span>
            </P>
        </div>
        <div class="space-y-2 mt-3">
            <a href="{{ route('members.edit', $member->id) }}"
                class="flex items-center justify-center navy-shadow bg-[#124375] text-[#FEFFFC] rounded-xl gap-2 w-full  py-3 ">
                <iconify-icon icon="ic:round-edit" class="mt-1 text-xl"></iconify-icon> تعديل بيانات
            </a>
            <button
                class="flex suspension-btn items-center red-shadow bg-[#F4F7F9] text-[#D92D20] rounded-xl gap-2 px-20 py-3 border border-[#D92D20]">
                <iconify-icon icon="carbon:close-filled" class="mt-1 text-xl"></iconify-icon> إيقاف العضوية
            </button>
        </div>
    </div>
    <!-- end header -->
    <hr class="border border-[#124375] mx-7 my-2">

    <!-- start personal informationn -->
    <section class="py-5 px-7">
        <div class="personal-info relative border border-[#124375] rounded-[20px]">
            <h2 class="absolute text-[#124375] px-1 right-3 top-[-15px] text-lg font-medium bg-[#F4F7F9] ">
                البيانات الشخصية
            </h2>
            <div class="information py-7 px-7">
                <form class="space-y-5">
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الأسم
                                كامل</label>
                            <input type="text" disabled value="{{ $member->full_name ?? 'بيانات مفقودة' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="احمد محمد ابراهيم خليل">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الرقم
                                القومي</label>
                            <input type="text" disabled value="{{ $member->national_id ?? 'بيانات مفقودة' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="12345678912345">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">رقم
                                الهاتف</label>
                            <input type="text" disabled value="{{ $member->phone ?? 'بيانات مفقودة' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="01234567891">
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الوظيفة</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->job_title ?? 'بيانات مفقودة' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="دكتور في التربية النوعية">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">جهة
                                العمل</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->workplace ?? 'بيانات مفقودة ' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="كلية التربية -جامعة حلوان">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">البريد
                                الإلكتروني</label>
                            <input type="text" disabled value="{{ $member->user->email ?? 'بيانات مفقودة' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="ahmed@gmail.com">
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">تاريخ
                                الانضمام</label>
                            <input type="text" disabled
                                value="{{ $member->created_at ? $member->created_at->isoFormat('D MMMM YYYY', 'ar') : '' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="22 أكتوبر 2026">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الحالة
                                الوظيفية</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->financial_category ?? 'بيانات مفقودة' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="موظف بالجامعة">
                        </div>
                        <div class="relative w-full">
                            <label
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9] ">الراتب
                                الأساسي (في 1/7/2014)</label>
                            <input type="text" disabled value="{{ $member->employmentInfo->starting_salary ?? ' ج.م' }}"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition outline-none w-full border border-[#124375] rounded-xl text-base text-[#6D6D6D] text-center bg-[#F4F7F9] py-2"
                                placeholder="416 ج.م">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- end personal information -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>
    <!-- suspension of membership MODAL -->
    <form action="{{ route('members.suspend', $member->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div
            class="suspension-modal hidden max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button
                class="close-btn text-[#124375] text-2xl  navy-shadow rounded m-4 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body px-6 space-y-7">
                <div class="moda-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        تأكيد إيقاف العضوية
                    </h1>
                </div>
                <div class="reason space-y-3">
                    <p class="mr-1 text-[#021219] font-medium ">لإيقاف العضوية، يرجى توضيح سبب الرفض وإرفاق صورة من طلب
                        إيقاف العضوية الموقَّع من العضو أو قرار صندوق الزمالة برفض العضوية.</p>
                    <textarea name="reason" required rows="4"
                        class="resize-none bg-[#F4F7F9] w-full border border-[#124375] rounded-xl outline-none px-2"
                        placeholder="سبب إيقاف العضوية"></textarea>
                </div>
                <div class="document border border-[#124375] rounded-xl text-center">
                    <label for="suspension-file"
                        class="w-full cursor-pointer  py-6 text-[#124375] flex items-center justify-center gap-1">
                        <p>اضغط لإرفاق صورة الملف</p>
                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                        <input type="file" name="suspension_file" id="suspension-file" class="hidden">
                    </label>
                </div>
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class=" rounded-[14px] w-full py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">إيقاف
                            العضوية</button>
                    </div>
                    <button
                        class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
                </div>
            </div>
        </div>
    </form>
    <!-- end suspension of membership MODAL -->

    <section class="px-7">
        <div class="flex justify-between border border-[#124375] p-3 rounded-xl">
            <div class="tabs flex gap-2">
                <button
                    class="bg-[#E6F1FD80] text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">الاشتراكات</button>
                <button
                    class="bg-[#E6F1FD80] text-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">قروض</button>
                <button
                    class="bg-[#F4F7F9] text-[#124375] border-b-[3px] border-[#124375] text-base font-medium rounded-tl-2xl rounded-tr-2xl py-3 px-4 navy-shadow">مطالبات</button>
            </div>
            <div class="relative">

                <button
                    class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base navy-shadow flex gap-3">نوع
                    المطالبة : @if (isset($claim_type))
                        {{ $claims[$claim_type] }}
                    @else
                        أختر
                    @endif <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                            class="text-xl"></iconify-icon></span></button>
                <div
                    class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow max-w-fit">
                    <a href="{{ url('/members/' . $member->id . '/?claim_type=retirement') }}"
                        class="button cursor-pointer text-center navy-shadow py-2 px-2 rounded-xl text-base ">بلوغ سن
                        التقاعد القانوني</a>
                    <a href="{{ url('/members/' . $member->id . '/?claim_type=transfer') }}"
                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">نقل</a>
                    <a href="{{ url('/members/' . $member->id . '/?claim_type=death') }}"
                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">
                        وفاة</a>
                    <a href="{{ url('/members/' . $member->id . '/?claim_type=resignation') }}"
                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base "> استقالة</a>
                    <a href="{{ url('/members/' . $member->id . '/?claim_type=early_retirement') }}"
                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">معاش مبكر</a>
                    <a href="{{ url('/members/' . $member->id . '/?claim_type=withdrawal') }}"
                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">انسحاب</a>
                    <a href="{{ url('/members/' . $member->id . '/?claim_type=expulsion') }}"
                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">فصل</a>
                    <a href="{{ url('/members/' . $member->id . '/?claim_type=professional_disability') }}"
                        class="button cursor-pointer text-center navy-shadow py-2  rounded-xl text-base ">عجز مهني</a>
                </div>
            </div>
        </div>
    </section>

    <!-- first step of request -->
    @if (request('claim_type') !== null)
        <form action="{{ route('members.storeClaim', $member->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="claim_type" value="{{ request('claim_type') }}">
            <div class="modal-body space-y-7 px-5">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375] mt-4">
                        {{ $claims[$claim_type] }}
                    </h1>
                </div>
                <div class="documents grid grid-cols-2 gap-y-5 gap-x-4">
                    <!-- common inputs -->
                    <!-- خطاب بالمرتب الاساسي مش موجود في النقل -->
                    <!-- بطاقة الرقم القومي مش موجودة في الوفاة  -->
                    <!-- قرار الاحالة للمعاش مش موجودة في النقل و الوفاة  -->
                    <!-- التوقيع في الوفاة هيبقي توقيع الوريث بدل العضو -->
                    @if (request('claim_type') === 'retirement' ||
                            request('claim_type') === 'resignation' ||
                            request('claim_type') === 'early_retirement' ||
                            request('claim_type') === 'withdrawal' ||
                            request('claim_type') === 'expulsion' ||
                            request('claim_type') === 'professional_disability' ||
                            request('claim_type') === 'death' ||
                            request('claim_type') === 'transfer')
                        @if (request('claim_type') !== 'transfer')
                            {{-- خطاب بالمرتب الاساسي --}}
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                                    بالمرتب الأساسي <span class="text-[#D92D20]">*</span></span>
                                <label for="file-1"
                                    class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" id="file-1" class="hidden" required>
                                </label>
                            </div>
                        @endif
                        @if (request('claim_type') !== 'death')
                            {{-- بطاقه الرقم القومي --}}
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه
                                    الرقم القومي<span class="text-[#D92D20]">*</span></span>
                                <label for="file-4"
                                    class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" id="file-4" class="hidden" required>
                                </label>
                            </div>
                        @endif
                        @if (request('claim_type') !== 'death' && request('claim_type') !== 'transfer')
                            {{-- صورة قرار الإحالة للمعاش --}}
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                    قرار الإحالة للمعاش<span class="text-[#D92D20]">*</span></span>
                                <label for="file-5"
                                    class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" id="file-5" class="hidden" required>
                                </label>
                            </div>
                        @endif
                        {{-- بيان بالمبالغ المخصومة --}}
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان
                                بالمبالغ المخصومة<span class="text-[#D92D20]">*</span></span>
                            <label for="file-2"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-2" class="hidden" required>
                            </label>
                        </div>
                        {{-- خطاب بتاريخ التعيين --}}
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                                بتاريخ التعيين<span class="text-[#D92D20]">*</span></span>
                            <label for="file-3"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-3" class="hidden" required>
                            </label>
                        </div>
                        {{-- توقيع العضو بصرف مستحقاته --}}
                        @if (request('claim_type') !== 'death')
                            <div class="relative border border-[#6D6D6D] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#6D6D6D] font-medium bg-[#F4F7F9]">توقيع
                                    العضو بصرف مستحقاته</span>
                                <label for="file-6"
                                    class=" cursor-pointer py-3  text-[#6D6D6D] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" id="file-6" class="hidden">
                                </label>
                            </div>
                        @endif
                        <!-- end common inputs -->
                    @endif
                    @if (request('claim_type') === 'transfer')
                        <!-- موجودين في النقل بس  -->
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                إخلاء طرف<span class="text-[#D92D20]">*</span></span>
                            <label for="file-7"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-7" class="hidden" required>
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                قرار النقل <span class="text-[#D92D20]">*</span></span>
                            <label for="file-8"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-8" class="hidden">
                            </label>
                        </div>
                        <!-- كده النقل خلص -->
                    @elseif(request('claim_type') === 'death')
                        <!-- دول في الوفاة بس -->
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                قرار إنهاء الخدمة <span class="text-[#D92D20]">*</span></span>
                            <label for="file-9"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-9" class="hidden" required>
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                شهادة الوفاة <span class="text-[#D92D20]">*</span></span>
                            <label for="file-10"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-10" class="hidden">
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                بطاقة الرقم القومي للورثة المستحقين<span class="text-[#D92D20]">*</span></span>
                            <label for="file-11"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-11" class="hidden" required>
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                إعلام الوراثة الشرعي <span class="text-[#D92D20]">*</span></span>
                            <label for="file-12"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-12" class="hidden">
                            </label>
                        </div>
                        <div class="relative border border-[#6D6D6D] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#6D6D6D] font-medium bg-[#F4F7F9]">توقيع
                                العضو بصرف مستحقاته</span>
                            <label for="file-6"
                                class=" cursor-pointer py-3  text-[#6D6D6D] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-6" class="hidden">
                            </label>
                        </div>
                        <div class="col-span-2">
                            <div class="flex justify-between">
                                <div>
                                    <p class="text-base font-medium text-[#124375]">هل يوجد قصر ؟ <span
                                            class="text-[#D92D20]">*</span>
                                    </p>
                                </div>
                                <div class=" flex gap-3">
                                    <label for="yes" class="cursor-pointer flex items-center gap-3">
                                        <input type="radio" name="answer" id="yes" class="hidden peer"
                                            required>
                                        <span
                                            class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                        <span>نعم</span>
                                    </label>
                                    <label for="no" class="cursor-pointer flex items-center gap-3">
                                        <input type="radio" name="answer" id="no" class="hidden peer"
                                            required>
                                        <span
                                            class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                        <span>لا</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                قرار الوصاية في حالة وجود قصر<span class="text-[#D92D20]">*</span></span>
                            <label for="file-13"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-13" class="hidden" required>
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                شهادات ميلاد القصر بالرقم القومي<span class="text-[#D92D20]">*</span></span>
                            <label for="file-14"
                                class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="file-14" class="hidden">
                            </label>
                        </div>
                        <!-- كده الوفاة خلصت -->
                    @endif
                </div>
                {{-- @if (optional($member->membershipInfo)->claims == !null || $member->membershipInfo->claims->count() > 0) --}}
                <div class="declaration space-y-3">
                    <h3 class="text-center font-medium">
                        إقرار
                    </h3>
                    <p class="font-medium">أقر أنا /{{ $member->full_name }} بأنني قد قمت باستلام كافة مستحقاتي من صندوق
                        الزمالة الخاص
                        بأعضاء هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان،
                        وذلك اعتبارًا من تاريخ {{ date('Y-m-d') }}، وأقر بعدم أحقيتي في المطالبة بأي مستحقات أخرى بعد هذا
                        التاريخ
                    </p>
                    <p class="font-medium">
                        الاسم / {{ $member->full_name }}
                        الوظيفة / {{ $member->employmentInfo->job_title ?? '-' }}
                        الرقم القومي / {{ $member->national_id }}
                        التوقيع /
                        ________________________________
                    </p>
                </div>
                {{-- @endif --}}
                <div class="btns flex gap-2 ">
                    <div class="w-full">
                        <button type="submit"
                            class="submit-btn rounded-[14px] w-full py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2"><span><iconify-icon
                                    icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>
                            تقديم المطالبة</button>
                    </div>
                    <button type="button" onclick="window.location.href='{{ route('members.show', $member->id) }}'"
                        class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
                </div>
            </div>
            </div>
        </form>
    @endif
    <!-- end first step -->

    <!-- second step of request -->
    <div
        class="request-modal-2 hidden w-full max-w-5xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="close-btn text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-5">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    مطالبة ببلوغ سن التقاعد القانوني
                </h1>
            </div>
            <div class="space-y-3">
                <div class="flex gap-4 ">
                    <p class="text-[#124375] text-base font-medium">الأسم : <span
                            class="text-[#021219] text-base font-semibold">{{ $member->full_name }}</span></p>
                    <p class="text-[#124375] text-base font-medium">رقم العضوية : <span
                            class="text-[#021219] text-base font-semibold">{{ $member->membershipInfo->membership_number ?? '-' }}</span>
                    </p>
                    <p class="text-[#124375] text-base font-medium">الرقم القومي : <span
                            class="text-[#021219] text-base font-semibold">{{ $member->national_id }}</span></p>
                    <p class="text-[#124375] text-base font-medium">رقم المطالبة : <span
                            class="text-[#021219] text-base font-semibold">123456789</span></p>
                </div>
                <p>يرجى إرفاق المستندات التالية لإتمام طلب الإحالة إلى المعاش واستلام المستحقات.</p>
            </div>
            <div class="documents space-y-5">
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                            بالمرتب الأساسي</span>
                        <label for="file-1"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                            قرار الإحالة للمعاش</span>
                        <label for="file-1"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان
                            بالمبالغ المخصومة</span>
                        <label for="file-1"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                            بتاريخ التعيين</span>
                        <label for="file-1"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه
                            الرقم القومي</span>
                        <label for="file-1"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                    <div class="relative border border-[#124375] rounded-2xl w-full">
                        <span
                            class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">توقيع
                            العضو بصرف مستحقاته <span class="text-[#D92D20]">*</span></span>
                        <label for="file-1"
                            class=" cursor-pointer py-3  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الملف</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden" required>
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تقديم
                        المطالبة</button>
                </form>
                <button
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- end second step -->

    <!-- start requests section -->
    <section class="px-7 py-5">
        <!-- no requests -->
        <!-- hidden -->
        @if (request('claim_type') === null)
            <div class="no-requests flex justify-center py-14 ">
                <div class="flex flex-col items-center gap-5">
                    <img src="{{ asset('/IMGs/no-requests.png') }}" alt="no-requests">
                    <p>لم يتم إضافة مطالبة حتي الآن</p>
                </div>
            </div>
        @elseif (optional(optional($member->membershipInfo)->claims)->isNotEmpty())
            <!-- requests table -->
            <div class="requests-table rounded-[14px] overflow-hidden border border-[#D1D5DB]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم المطالبة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">نوع المطالبة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">حالة الطلب</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ التقديم</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($member->membershipInfo->claims as $claim)
                            <tr class="text-center">
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $claim->id }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $claims[$claim->type] ?? $claim->type }}</td>
                                <td class="py-4 border-l border-[#D1D5DB]">
                                    @if ($claim->status === 'pending')
                                        <span
                                            class="bg-[#FFFCEF] text-[#D4AF37] border border-[#D4AF37] px-4 py-1.5 text-sm rounded-lg">بإنتظار
                                            الاعتماد</span>
                                    @elseif($claim->status === 'approved')
                                        <span
                                            class="bg-[#ECFDF3] text-[#067647] border border-[#067647] px-4 py-1.5 text-sm rounded-lg">معتمد</span>
                                    @elseif($claim->status === 'rejected')
                                        <span
                                            class="bg-[#FFEAE8] text-[#D92D20] border border-[#D92D20] px-4 py-1.5 text-sm rounded-lg">مرفوض</span>
                                    @else
                                        <span
                                            class="bg-[#F4F7F9] text-[#6D6D6D] border border-[#6D6D6D] px-4 py-1.5 text-sm rounded-lg">{{ $claim->status ?? 'غير معروف' }}</span>
                                    @endif
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $claim->created_at ? $claim->created_at->format('Y-m-d') : '' }}</td>
                                <td class="py-5"><a href="#"
                                        class="bg-[#124375] text-white py-3 navy-shadow px-8 rounded-xl font-medium">اعتماد
                                        الصرف</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- end requests table -->
        @endif
    </section>
    <!-- end requests section -->

    <script src="{{ asset('js/member.js') }}"></script>
@endsection
