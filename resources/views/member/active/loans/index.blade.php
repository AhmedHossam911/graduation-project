@extends('layouts.member')

@section('title', 'طلب الحصول على قرض')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/member/memberLoan.css') }}">

    @if (!$activeLoan)
        {{-- لو معندوش قرض ده اللي يظهر --}}
        <section class="py-7 px-4 md:px-12">
            @if (session('success'))
                <div class="mb-4 bg-[#ECFDF3] text-[#067647] border border-[#067647] p-4 rounded-xl flex items-center gap-3">
                    <iconify-icon icon="healthicons:yes" class="text-2xl"></iconify-icon>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mb-4 bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] p-4 rounded-xl flex items-center gap-3">
                    <iconify-icon icon="material-symbols:error-outline" class="text-2xl"></iconify-icon>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] p-4 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex flex-col gap-3">
                    <h1 class="text-xl text-[#124375]  font-semibold">
                        طلب الحصول على قرض
                    </h1>
                    <p class="text-[#6D6D6D] text-[16px] font-normal">
                        الحد الأقصى للقرض 20,000 جنيه، يسدد على مدة أقصاها 36 شهراً، ويضاف معدل فائدة بواقع 8% سنوياً على
                        قيمة القرض.
                    </p>
                </div>
                <div class="w-full md:w-auto">
                    <a href="{{ route('member.dashboard') }}"
                        class="block text-center text-[#D92D20] bg-[#F4F7F9] rounded-[16px] py-2 px-12 red-shadow w-full md:w-auto">
                        إلغاء
                    </a>
                </div>
            </div>
        </section>

        <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            <input type="hidden" name="total_amount" id="total_amount_input">
            <input type="hidden" name="months" id="months_input">

            <div class="py-3 px-4 md:px-12 ">
                <div class="bg-[#EEF7FF] space-y-7 navy-shadow rounded-[16px] py-5 px-4">
                    <div class="flex gap-4 items-center">
                        <iconify-icon icon="material-symbols:info-rounded" class="text-2xl text-[#175CD3]"></iconify-icon>
                        <p class="text-[#124375] text-[18px] font-medium">ملخص حساب القرض</p>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between px-2 sm:px-7 gap-4">
                        <div class="flex flex-col gap-3 ">
                            <p class="text-[16px] text-[#175CD3] font-medium">المبلغ المطلوب</p>
                            <p class="text-[#124375] text-[20px] font-semibold"><span id="summary_amount">0</span> ج.م</p>
                        </div>
                        <div class="flex flex-col gap-3 ">
                            <p class="text-[16px] text-[#175CD3] font-medium">الفائدة الإجمالية (8%)</p>
                            <p class="text-[#124375] text-[20px] font-semibold"><span id="summary_interest">0</span> ج.م</p>
                        </div>
                        <div class="flex flex-col gap-3 ">
                            <p class="text-[16px] text-[#175CD3] font-medium">إجمالي المديونية</p>
                            <p class="text-[#124375] text-[20px] font-semibold"><span id="summary_total">0</span> ج.م</p>
                        </div>
                        <div class="flex flex-col gap-3 ">
                            <p class="text-[16px] text-[#175CD3] font-medium">القسط الشهري</p>
                            <p class="text-[#124375] text-[20px] font-semibold"><span id="summary_installment">0</span> ج.م
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <section class="py-3 px-4 md:px-12 ">
                <div class="bg-[#F4F7F9] navy-shadow rounded-[16px] py-8 px-3 space-y-7">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-4 mt-2">
                        <div class="relative">
                            <button type="button"
                                class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base gap-3 flex justify-between items-center"><span
                                    class="text-[#021219] text-center flex-1" id="amount_text">اختر</span><span
                                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl "></iconify-icon></span></button>
                            <label
                                class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">قيمة
                                القرض</label>
                            <div
                                class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-2 px-5 py-4 rounded-xl navy-shadow w-full">
                                <button type="button"
                                    class="amount-option navy-shadow py-2 px-7 rounded-xl text-sm font-medium"
                                    data-value="5000">5,000</button>
                                <button type="button" class="amount-option navy-shadow py-2 rounded-xl text-sm font-medium"
                                    data-value="10000">10,000</button>
                                <button type="button" class="amount-option navy-shadow py-2 rounded-xl text-sm font-medium"
                                    data-value="20000">20,000</button>
                            </div>
                        </div>
                        <div class="relative">
                            <button type="button"
                                class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base gap-3 flex justify-between items-center"><span
                                    class="text-[#021219] text-center flex-1" id="months_text">اختر</span><span
                                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl "></iconify-icon></span></button>
                            <label
                                class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">مدة
                                السداد</label>
                            <div
                                class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-2 px-5 py-4 rounded-xl navy-shadow w-full">
                                <button type="button"
                                    class="months-option navy-shadow py-2 px-7 rounded-xl text-sm font-medium"
                                    data-value="12">12 شهر</button>
                                <button type="button" class="months-option navy-shadow py-2 rounded-xl text-sm font-medium"
                                    data-value="24">24 شهر</button>
                                <button type="button" class="months-option navy-shadow py-2 rounded-xl text-sm font-medium"
                                    data-value="32">32 شهر</button>
                            </div>
                        </div>
                    </div>

                    <label class="flex  gap-5 cursor-pointer">
                        <input type="checkbox" required name="digital_declaration_checkbox" class="hidden peer item"
                            value="1">
                        <span
                            class="mt-1 custom-checkbox flex items-center justify-center h-[20px] w-[20px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                            <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                        </span>
                        <p>أقر أنا الموقع أعلاه بصحة البيانات المذكورة، وأفوض إدارة الجامعة بخصم الأقساط الشهرية للقرض
                            المذكور أعلاه
                            من راتبي الشهري وتوريدها لحساب صندوق الزمالة حتى سداد كامل المديونية. وأتعهد بعدم ممانعة جهة
                            العمل.</p>
                    </label>
                    <div>
                        <button type="submit" id="submit_btn"
                            class="hover:bg-[#0e3560] transition-colors  flex items-center gap-5 bg-[#124375] text-[#F4F7F9] w-full justify-center py-3 rounded-[12px] opacity-50 cursor-not-allowed">
                            <iconify-icon icon="boxicons:send-filled" class="text-2xl mt-1"></iconify-icon>
                            تقديم طلب القرض
                        </button>
                    </div>
                </div>
            </section>
        </form>
    @else
        {{-- لو العضو عنده قرض ده اللي يظهر --}}
        <div
            class="rounded-[12px] bg-[#FFF7ED] max-w-xl orange-shadow py-4 px-5 mx-4 md:mx-auto mt-7 flex flex-col gap-7 justify-center items-center text-center">
            <div>
                <iconify-icon icon="material-symbols:info-rounded"
                    class="text-4xl text-[#F79009] bg-[#FEF3C7] rounded-full py-4 px-4"></iconify-icon>
            </div>
            <div>
                <p class="text-[#124375] text-[20px] font-semibold">عذرا، لايمكنك طلب قرض جديد</p>
            </div>
            <div>
                <p class="text-[#6D6D6D] text-[14px] font-medium">لا يجوز للعضو الحصول علي أكثر من قرض واحد في نفس الوقت.
                    يجد
                    الانتهاء من سدادالقرض الحالي بالكامل قبل التقدم بطلب للحصول علي قرض جديد</p>
            </div>
            <div>
                <a href="{{ route('member.dashboard') }}"
                    class="cursor-pointer bg-[#124375] hover:bg-[#0e3560] transition-colors text-[#F4F7F9] flex items-center gap-4 w-full justify-center py-3 px-8 rounded-[12px] navy-shadow ">
                    متابعة سداد القرض الحالي
                    <iconify-icon icon="fe:arrow-left" class="text-2xl mt-1"></iconify-icon>
                </a>
            </div>
        </div>
    @endif

    <script src="{{ asset('js/member/memberLoan.js') }}"></script>
@endsection
