@extends('layouts.member')

@section('title', 'المطالبات')

@section('content')
    @include('partials.common.flash')
    <link rel="stylesheet" href="{{ asset('css/member/memberClaims.css') }}">
    <section class="py-7 px-12">
        <div class="flex items-center justify-between">
            <div class="flex flex-col gap-3">
                <h1 class="text-xl text-[#124375]  font-semibold">
                    طلب صرف مستحقات (مطالبة)
                </h1>
                <p class="text-[#6D6D6D] text-[16px] font-normal">
                    يرجى اختيار نوع المطالبة وإرفاق كافة المستندات المطلوبة بدقة لضمان سرعة إنجاز الطلب وصرف المستحقات.
                </p>
            </div>
            <div>
                <a href="{{ route('member.dashboard') }}"
                    class="block text-center text-[#D92D20] bg-[#F4F7F9] rounded-[16px] py-2 px-12 red-shadow">
                    إلغاء
                </a>
            </div>
        </div>
    </section>

    <!-- start tabs -->
    <div class="px-12">
        <div class="tabs grid grid-cols-6  bg-[#F4F7F9] navy-shadow rounded-[16px] px-4 py-5 ">
            <button class="active-tab text-[16px]">
                بلوغ سن التقاعد القانوني
            </button>
            <button class="tab text-[16px] ">
                النقل
            </button>
            <button class="tab  text-[16px] ">
                الاستقالة
            </button>
            <button class="tab text-[16px] ">
                المعاش المبكر
            </button>
            <button class="tab  text-[16px] ">
                العجز المهني
            </button>
            <button class="tab  text-[16px] ">
                الانسحاب
            </button>
        </div>
    </div>
    <!-- end tabs -->

    <section class="px-12 py-7">
        <form action="{{ route('member.claims.store') }}" method="POST" enctype="multipart/form-data"  class=" rounded-2xl border-2 border-[#124375] py-7 px-7 relative tab-content"
            data-tab="بلوغ سن التقاعد القانوني">
            @csrf
            <input type="hidden" name="claim_type" value="retirement">
            <h2 class="absolute top-[-15px] right-5 px-2 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                المستندات المطلوبة لإتمام الطلب <span class="text-[#D92D20]">*</span>
            </h2>
            <div class="space-y-5">
                <div class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">خطاب الأجر الأساسي في 1/7/2014 <span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_1" class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_1" name="claim_documents[doc_1]" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">صورة قرار إحالة للمعاش<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_2" class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_2" name="claim_documents[doc_2]" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بطاقة الرقم القومي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_3" class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_3" name="claim_documents[doc_3]" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">قرار التعيين<span class="text-[#D92D20]">*</span>
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_4" class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_4" name="claim_documents[doc_4]" class="hidden">
                        </label>
                    </div>
                </div>
                <div class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بيان بالمبالغ المخصومة من إدارة الاستحقاقات
                            <span class="text-[#D92D20]">*</span>
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_5" class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_5" name="claim_documents[doc_5]" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <label class="flex  gap-5 cursor-pointer py-7">
                <input type="checkbox" required class="hidden peer item" value="يناير 2026">
                <span
                    class="mt-1 custom-checkbox flex items-center justify-center h-[20px] w-[20px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                </span>
                <p>أقر أنا العضو، بطلب صرف كافة مستحقاتي المالية المقررة من صندوق الزمالة، وذلك نظراً لانتهاء مدة الخدمة
                    وبلوغ سن التقاعد القانوني. كما أقر بأن جميع البيانات والمستندات المرفقة صحيحة وتتوافق مع لائحة الصندوق.
                </p>
            </label>
            <div class="flex justify-between items-center ">
                <div>
                    <p class="text-[14px] text-[#6D6D6D] font-medium">
                        * تأكد من رفع جميع المستندات بصيغة واضحة (PDF أو صور).
                    </p>
                </div>
                <div>
                    <button
                        class="hover:bg-[#0e3560] transition-colors  flex items-center gap-3 bg-[#124375] text-[#F4F7F9] w-full justify-center py-3 px-20 navy-shadow rounded-[12px] ">
                        <iconify-icon icon="boxicons:send-filled" class="text-2xl mt-1"></iconify-icon>
                        تقديم الطلب
                    </button>
                </div>
            </div>
        </form>

        <form action="{{ route('member.claims.store') }}" method="POST" enctype="multipart/form-data" class="hidden rounded-2xl border-2 border-[#124375] py-7 px-7 relative tab-content" data-tab="النقل">
            @csrf
            <input type="hidden" name="claim_type" value="transfer">
            <h2 class="absolute top-[-15px] right-5 px-2 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                المستندات المطلوبة لإتمام الطلب <span class="text-[#D92D20]">*</span>
            </h2>
            <div class="space-y-5">
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">صورة قرار النقل<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_6"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_6" name="claim_documents[doc_6]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بيان بالمبالغ المخصومة <span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_7"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_7" name="claim_documents[doc_7]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بطاقة الرقم القومي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_8"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_8" name="claim_documents[doc_8]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">قرار التعيين<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_9"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_9" name="claim_documents[doc_9]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">صورة إخلاء الطرف
                            <span class="text-[#D92D20]">*</span>
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_10"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_10" name="claim_documents[doc_10]" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <label class="flex  gap-5 cursor-pointer py-7">
                <input type="checkbox" required class="hidden peer item" value="يناير 2026">
                <span
                    class="mt-1 custom-checkbox flex items-center justify-center h-[20px] w-[20px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                </span>
                <p>أقر أنا العضو، بطلب تصفية وصرف كافة مستحقاتي المالية المقررة من صندوق الزمالة، وذلك بناءً على صدور قرار
                    رسمي بنقلي خارج جهة العمل الحالية. كما أقر بأن جميع البيانات المرفقة وقرار إخلاء الطرف صحيحة وتتوافق مع
                    اللائحة الداخلية للصندوق.</p>
            </label>
            <div class="flex justify-between items-center ">
                <div>
                    <p class="text-[14px] text-[#6D6D6D] font-medium">
                        * تأكد من رفع جميع المستندات بصيغة واضحة (PDF أو صور).
                    </p>
                </div>
                <div>
                    <button
                        class="hover:bg-[#0e3560] transition-colors  flex items-center gap-3 bg-[#124375] text-[#F4F7F9] w-full justify-center py-3 px-20 navy-shadow rounded-[12px] ">
                        <iconify-icon icon="boxicons:send-filled" class="text-2xl mt-1"></iconify-icon>
                        تقديم الطلب
                    </button>
                </div>
            </div>
        </form>

        <form action="{{ route('member.claims.store') }}" method="POST" enctype="multipart/form-data" class="hidden rounded-2xl border-2 border-[#124375] py-7 px-7 relative tab-content" data-tab="الاستقالة">
            @csrf
            <input type="hidden" name="claim_type" value="resignation">
            <h2 class="absolute top-[-15px] right-5 px-2 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                المستندات المطلوبة لإتمام الطلب <span class="text-[#D92D20]">*</span>
            </h2>
            <div class="space-y-5">
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">خطاب بالمرتب الأساسي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_11"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_11" name="claim_documents[doc_11]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بيان بالمبالغ المخصومة <span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_12"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_12" name="claim_documents[doc_12]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بطاقة الرقم القومي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_13"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_13" name="claim_documents[doc_13]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">قرار التعيين<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_14"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_14" name="claim_documents[doc_14]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">صورة قرار الإحالة للمعاش
                            <span class="text-[#D92D20]">*</span>
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_15"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_15" name="claim_documents[doc_15]" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <label class="flex  gap-5 cursor-pointer py-7">
                <input type="checkbox" required class="hidden peer item" value="يناير 2026">
                <span
                    class="mt-1 custom-checkbox flex items-center justify-center h-[20px] w-[20px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                </span>
                <p>أقر أنا العضو، بطلب صرف كافة مستحقاتي المالية المقررة من صندوق الزمالة، وذلك نظراً لاستقالتي من جهة عملي
                    طرفكم. كما أقر بأن جميع البيانات والمستندات المرفقة صحيحة وتتوافق مع لائحة الصندوق.</p>
            </label>
            <div class="flex justify-between items-center ">
                <div>
                    <p class="text-[14px] text-[#6D6D6D] font-medium">
                        * تأكد من رفع جميع المستندات بصيغة واضحة (PDF أو صور).
                    </p>
                </div>
                <div>
                    <button
                        class="hover:bg-[#0e3560] transition-colors  flex items-center gap-3 bg-[#124375] text-[#F4F7F9] w-full justify-center py-3 px-20 navy-shadow rounded-[12px] ">
                        <iconify-icon icon="boxicons:send-filled" class="text-2xl mt-1"></iconify-icon>
                        تقديم الطلب
                    </button>
                </div>
            </div>
        </form>

        <form action="{{ route('member.claims.store') }}" method="POST" enctype="multipart/form-data" class="hidden rounded-2xl border-2 border-[#124375] py-7 px-7 relative tab-content" data-tab="المعاش المبكر">
            @csrf
            <input type="hidden" name="claim_type" value="early_retirement">
            <h2 class="absolute top-[-15px] right-5 px-2 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                المستندات المطلوبة لإتمام الطلب <span class="text-[#D92D20]">*</span>
            </h2>
            <div class="space-y-5">
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">خطاب بالمرتب الأساسي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_16"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_16" name="claim_documents[doc_16]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بيان بالمبالغ المخصومة <span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_17"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_17" name="claim_documents[doc_17]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بطاقة الرقم القومي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_18"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_18" name="claim_documents[doc_18]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">قرار التعيين<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_19"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_19" name="claim_documents[doc_19]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">صورة قرار الإحالة للمعاش
                            <span class="text-[#D92D20]">*</span>
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_20"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_20" name="claim_documents[doc_20]" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <label class="flex  gap-5 cursor-pointer py-7">
                <input type="checkbox" required class="hidden peer item" value="يناير 2026">
                <span
                    class="mt-1 custom-checkbox flex items-center justify-center h-[20px] w-[20px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                </span>
                <p>أقر أنا العضو، بطلب صرف كافة مستحقاتي المالية المقررة من صندوق الزمالة، وذلك نظراً لانتهاء فترة عملي
                    بالمعاش المبكر. كما أقر بأن جميع البيانات والمستندات المرفقة صحيحة وتتوافق مع لائحة الصندوق.</p>
            </label>
            <div class="flex justify-between items-center ">
                <div>
                    <p class="text-[14px] text-[#6D6D6D] font-medium">
                        * تأكد من رفع جميع المستندات بصيغة واضحة (PDF أو صور).
                    </p>
                </div>
                <div>
                    <button
                        class="hover:bg-[#0e3560] transition-colors  flex items-center gap-3 bg-[#124375] text-[#F4F7F9] w-full justify-center py-3 px-20 navy-shadow rounded-[12px] ">
                        <iconify-icon icon="boxicons:send-filled" class="text-2xl mt-1"></iconify-icon>
                        تقديم الطلب
                    </button>
                </div>
            </div>
        </form>

        <form action="{{ route('member.claims.store') }}" method="POST" enctype="multipart/form-data" class="hidden rounded-2xl border-2 border-[#124375] py-7 px-7 relative tab-content" data-tab="العجز المهني">
            @csrf
            <input type="hidden" name="claim_type" value="professional_disability">
            <h2 class="absolute top-[-15px] right-5 px-2 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                المستندات المطلوبة لإتمام الطلب <span class="text-[#D92D20]">*</span>
            </h2>
            <div class="space-y-5">
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">خطاب بالمرتب الأساسي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_21"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_21" name="claim_documents[doc_21]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بيان بالمبالغ المخصومة <span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_22"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_22" name="claim_documents[doc_22]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بطاقة الرقم القومي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_23"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_23" name="claim_documents[doc_23]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">قرار التعيين<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_24"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_24" name="claim_documents[doc_24]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">صورة قرار الإحالة للمعاش
                            <span class="text-[#D92D20]">*</span>
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_25"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_25" name="claim_documents[doc_25]" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <label class="flex  gap-5 cursor-pointer py-7">
                <input type="checkbox" required class="hidden peer item" value="يناير 2026">
                <span
                    class="mt-1 custom-checkbox flex items-center justify-center h-[20px] w-[20px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                </span>
                <p>أقر أنا العضو، بطلب صرف كافة مستحقاتي المالية المقررة من صندوق الزمالة، وذلك بناء علي صدور قرار رسمي
                    بالعجز المهني. كما أقر بأن جميع البيانات والمستندات المرفقة صحيحة وتتوافق مع لائحة الصندوق.</p>
            </label>
            <div class="flex justify-between items-center ">
                <div>
                    <p class="text-[14px] text-[#6D6D6D] font-medium">
                        * تأكد من رفع جميع المستندات بصيغة واضحة (PDF أو صور).
                    </p>
                </div>
                <div>
                    <button
                        class="hover:bg-[#0e3560] transition-colors  flex items-center gap-3 bg-[#124375] text-[#F4F7F9] w-full justify-center py-3 px-20 navy-shadow rounded-[12px] ">
                        <iconify-icon icon="boxicons:send-filled" class="text-2xl mt-1"></iconify-icon>
                        تقديم الطلب
                    </button>
                </div>
            </div>
        </form>

        <form action="{{ route('member.claims.store') }}" method="POST" enctype="multipart/form-data" class="hidden rounded-2xl border-2 border-[#124375] py-7 px-7 relative tab-content" data-tab="الانسحاب">
            @csrf
            <input type="hidden" name="claim_type" value="withdrawal">
            <h2 class="absolute top-[-15px] right-5 px-2 text-[#124375] text-base font-medium bg-[#F4F7F9]">
                المستندات المطلوبة لإتمام الطلب <span class="text-[#D92D20]">*</span>
            </h2>
            <div class="space-y-5">
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">خطاب بالمرتب الأساسي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_26"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_26" name="claim_documents[doc_26]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بيان بالمبالغ المخصومة <span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_27"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_27" name="claim_documents[doc_27]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">بطاقة الرقم القومي<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_28"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_28" name="claim_documents[doc_28]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">قرار التعيين<span
                                class="text-[#D92D20]">*</span></p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_29"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_29" name="claim_documents[doc_29]" class="hidden">
                        </label>
                    </div>
                </div>
                <div
                    class="flex justify-between bg-[#F4F7F9] border border-[#124375] navy-shadow rounded-[16px] py-3 px-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="ion:document" class="text-2xl text-[#6D6D6D]"></iconify-icon>
                        <p class="text-[16px] font-medium text-[#021219]">صورة قرار الإحالة للمعاش
                            <span class="text-[#D92D20]">*</span>
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] bg-[#F4F7F9] navy-shadow py-3 px-3">
                        <label for="file_30"
                            class=" cursor-pointer text-[#124375] flex items-center justify-center gap-1">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <p>إرفاق الملف</p>
                            <input type="file" id="file_30" name="claim_documents[doc_30]" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <label class="flex  gap-5 cursor-pointer py-7">
                <input type="checkbox" required class="hidden peer item" value="يناير 2026">
                <span
                    class="mt-1 custom-checkbox flex items-center justify-center h-[20px] w-[20px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                </span>
                <p>أقر أنا العضو، بطلب صرف كافة مستحقاتي المالية المقررة من صندوق الزمالة، وذلك نظراً لرغبتي في عدم
                    الاستمرار في صندوق الزمالة. كما أقر بأن جميع البيانات والمستندات المرفقة صحيحة وتتوافق مع لائحة الصندوق.
                </p>
            </label>
            <div class="flex justify-between items-center ">
                <div>
                    <p class="text-[14px] text-[#6D6D6D] font-medium">
                        * تأكد من رفع جميع المستندات بصيغة واضحة (PDF أو صور).
                    </p>
                </div>
                <div>
                    <button
                        class="hover:bg-[#0e3560] transition-colors  flex items-center gap-3 bg-[#124375] text-[#F4F7F9] w-full justify-center py-3 px-20 navy-shadow rounded-[12px] ">
                        <iconify-icon icon="boxicons:send-filled" class="text-2xl mt-1"></iconify-icon>
                        تقديم الطلب
                    </button>
                </div>
            </div>
        </form>
    </section>

    <script src="{{ asset('JS/member/memberClaims.js') }}"></script>

@endsection
