@extends('layouts.app')

@section('title', 'قائمة القروض')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/loans.css') }}">
    <!-- start header -->
    <div class="flex justify-between px-12 py-5">
        <div>
            <h1 class="text-[32px] font-medium text-[#124375]">
                القروض
            </h1>
        </div>
        <div class="btns flex items-center gap-3  ">
            <button
                class="open-modal rounded-xl flex items-center justify-center py-3 px-16 gap-2 text-[#F4F7F9] bg-[#124375] navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ic:round-plus" class="flex items-center text-2xl"></iconify-icon> إنشاء طلب قرض جديد
            </button>
            <a href=""
                class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 text-[#124375] bg-[#F4F7F9] navy-shadow">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل
            </a>
        </div>
    </div>
    <!-- end header -->

    <!-- start cards -->
    <div class="py-4 grid grid-cols-3 gap-4 px-12">
        <div
            class="navy-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
            <div>
                <iconify-icon icon="tabler:file-check-filled"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#124375] bg-[#EEF7FF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $activeLoansThisMonth }}</p>
                <p class="text-sm font-medium">قروض مُفعلة هذا الشهر</p>
            </div>
        </div>
        <div
            class="yellow-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
            <div>
                <iconify-icon icon="mdi:file-clock"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#D4AF37] bg-[#FFFCEF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $pendingLoansCount }}</p>
                <p class="text-sm font-medium">طلبات قروض تحت المراجعة</p>
            </div>
        </div>
        <div
            class="red-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
            <div>
                <iconify-icon icon="mdi:clock-alert"
                    class="navy-shadow text-[40px] text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $overdueInstallmentsCount }}</p>
                <p class="text-sm font-medium">أقساط متأخره اليوم</p>
            </div>
        </div>
    </div>
    <!-- end cards -->

    <!-- filteration buttons -->
    @php
        $statusMapping = [
            'all' => 'الكل',
            'pending' => 'تحت المراجعة',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'overdue' => 'متأخر',
        ];
        $currentStatus = request('status', 'all');
    @endphp
    <form action="{{ route('loans.index') }}" method="GET" class="px-12 flex items-center justify-between gap-5">
        <div class="relative flex-1">
            <input type="search" name="search" value="{{ request('search') }}"
                placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
        </div>
        <div class="relative min-w-[240px]">
            <label for="datepicker"
                class="calendar-label navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full rounded-xl text-base flex gap-3 justify-center items-center">التاريخ
                : <span class="text-[#021219]">{{ request('date') ?: 'يوم/شهر/ سنة' }}</span><span
                    class="flex items-center"><iconify-icon icon="lucide:calendar" class="text-xl"></iconify-icon></span>
                <input type="text" name="date" id="datepicker" value="{{ request('date') }}"
                    class="absolute left-0 top-full mt-3 opacity-0 w-0 h-0 pointer-events-none">
            </label>
        </div>
        <div class="relative min-w-[150px]">
            <input type="hidden" name="status" id="statusInput" value="{{ $currentStatus }}">
            <button type="button"
                class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">الحالة
                : <span class="text-[#021219] ">{{ $statusMapping[$currentStatus] ?? 'الكل' }}</span><span
                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                        class="text-xl"></iconify-icon></span></button>
            <div
                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                <button type="button" onclick="document.getElementById('statusInput').value='all';"
                    class=" navy-shadow py-2 px-5 rounded-xl text-base ">الكل</button>
                <button type="button" onclick="document.getElementById('statusInput').value='pending';"
                    class=" navy-shadow py-2 px-5 rounded-xl text-base ">تحت المراجعة</button>
                <button type="button" onclick="document.getElementById('statusInput').value='active';"
                    class=" navy-shadow py-2 px-5 rounded-xl text-base ">نشط</button>
                <button type="button" onclick="document.getElementById('statusInput').value='completed';"
                    class=" navy-shadow py-2 px-5 rounded-xl text-base ">مكتمل</button>
                <button type="button" onclick="document.getElementById('statusInput').value='overdue';"
                    class=" navy-shadow py-2 px-5 rounded-xl text-base ">متأخر</button>
            </div>
        </div>
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
            </button>
        </div>
    </form>
    <!-- end filteration buttons -->


    <!-- start table -->
    <section class="px-12 py-4">
        <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم القرض</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">قيمة القرض</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ المسدد</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ المتبقي</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                        <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        @php
                            $paidAmount = $loan->installments->where('status', 'paid')->sum('amount');
                            $remaining = $loan->total_amount - $paidAmount;
                            $hasOverdue = $loan->installments->where('status', 'overdue')->count() > 0;
                        @endphp
                        <tr class="text-center {{ $loop->odd ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $loan->id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                {{ $loan->membership->member->full_name ?? 'غير متوفر' }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                {{ number_format($loan->total_amount) }} ج .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ number_format($paidAmount) }} ج
                                .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ number_format($remaining) }} ج .م
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] ">
                                @if ($hasOverdue)
                                    <span
                                        class="text-[#F79009] bg-[#FFF7ED] border border-[#F79009] px-9 rounded-[8px] py-[1px]">متأخر</span>
                                @elseif ($loan->status === 'completed')
                                    <span
                                        class="text-[#124375] bg-[#EEF7FF] border border-[#124375] px-9 rounded-[8px] py-[1px]">مكتمل</span>
                                @elseif ($loan->status === 'active')
                                    <span
                                        class="text-[#067647] bg-[#ECFDF3] border border-[#067647] px-9 rounded-[8px] py-[1px]">نشط</span>
                                @elseif ($loan->status === 'pending')
                                    <span
                                        class="text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] px-9 rounded-[8px] py-[1px]">تحت
                                        المراجعة</span>
                                @elseif ($loan->status === 'rejected')
                                    <span
                                        class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] px-9 rounded-[8px] py-[1px]">مرفوض</span>
                                @else
                                    <span
                                        class="text-[#6D6D6D] bg-[#EFEFEF] border border-[#6D6D6D] px-9 rounded-[8px] py-[1px]">{{ $loan->status }}</span>
                                @endif
                            </td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <a href="{{ route('loans.show', $loan->id) }}"
                                    class="hover:text-[#0e3560] transition-colors">
                                    <iconify-icon icon="solar:eye-linear" class="text-2xl"></iconify-icon>
                                </a>
                                @if ($loan->status === 'active')
                                    <button class="open-modal hover:text-[#0e3560] transition-colors"
                                        data-loan-id="{{ $loan->id }}">
                                        <iconify-icon icon="ion:cash" class="text-2xl"></iconify-icon>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center border-b border-[#6D6D6D]">
                            <td colspan="7" class="py-5 text-[#124375] font-medium">لا توجد قروض متطابقة مع البحث</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <!-- end table -->


    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden  z-[60]"></div>

    <!-- MODALS -->
    <div
        class="modal hidden w-full max-w-4xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    طلب تسجيل قرض
                </h1>
            </div>
            <div class="space-y-3">
                <div class="flex gap-3 items-center">
                    <p class="text-base font-medium text-[#124375]">الأسم : <span
                            class="text-[#021219] font-semibold text-base">أحمد محمد</span></p>
                    <p class="text-base font-medium text-[#124375]">رقم العضوية : <span
                            class="text-[#021219] font-semibold text-base">123456789</span></p>
                    <p class="text-base font-medium text-[#124375]">الرقم القومي : <span
                            class="text-[#021219] font-semibold text-base">12345678901234</span></p>
                    <p class="text-base font-medium text-[#124375]">رقم القرض : <span
                            class="text-[#021219] font-semibold text-base">123456789</span></p>
                    <iconify-icon icon="pajamas:redo"
                        class="text-xl bg-[#124375] text-[#F4F7F9] rounded-[8px] py-1.5 px-2"></iconify-icon>
                </div>
                <div class="flex items-center justify-between gap-4 ">
                    <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
                    <div class="relative flex-1 ">
                        <input type="search" placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض"
                            class="w-full py-2 pr-7 outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                        <iconify-icon icon="mynaui:search"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
                    </div>
                    <button
                        class="bg-[#124375] text-white rounded-xl px-4 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="requirements space-y-5">
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">قيمة
                        القرض :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                        <button type="button" class=" navy-shadow py-2 px-5 rounded-xl text-base ">5,000</button>
                        <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-base ">10,000</button>
                        <button type="button" class=" navy-shadow py-2 px-5 rounded-xl text-base ">20,000</button>
                    </div>
                </div>
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">مدة
                        السداد :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                        <button type="button" class=" navy-shadow py-2 px-5 rounded-xl text-base ">12 شهر</button>
                        <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-base ">24 شهر</button>
                        <button type="button" class=" navy-shadow py-2 px-5 rounded-xl text-base ">32 شهر</button>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form action="{{ route('loans.store') }}" method="POST" class="w-full" id="createLoanForm">
                    @csrf
                    <input type="hidden" name="member_id" id="selectedMemberId">
                    <input type="hidden" name="total_amount" id="selectedLoanAmount">
                    <input type="hidden" name="months" id="selectedLoanMonths">
                    <button type="submit"
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        الأختيار</button>
                </form>
                <button
                    class="close-loan-request-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>

    <div
        class="modal hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد قسط
                </h1>
            </div>
            <div class="space-y-4">
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات القرض
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">الأسم : <span
                                class="text-[#021219] font-semibold text-base">أحمد محمد</span></p>
                        <p class="text-base font-medium text-[#124375]">رقم العضوية : <span
                                class="text-[#021219] font-semibold text-base">123456789</span></p>
                        <p class="text-base font-medium text-[#124375]">الرقم القومي : <span
                                class="text-[#021219] font-semibold text-base">12345678901234</span></p>
                    </div>
                </div>
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات السداد
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">المبلغ المدفوع (ج.م) :<span
                                class="text-[#021219] font-semibold text-base">1500</span></p>
                        <p class="text-base font-medium text-[#124375]">تاريخ السداد :<span
                                class="text-[#021219] font-semibold text-base">16 أبريل 2026</span></p>
                    </div>
                </div>
                <div class="relative w-fit">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-fit px-2 rounded-xl text-base gap-2 font-medium flex items-center">سداد
                        عن شهر / شهور<span class="text-[#D92D20]">*</span> : <span class="text-[#021219] text-[14px]">اختر
                            الشهر</span> <span class="flex items-center mt-1"><iconify-icon icon="lucide:calendar"
                                class="text-xl "></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute rounded-[10px] bg-[#F4F7F9] navy-shadow z-50 px-5 py-4 space-y-2 left-0 top-full mt-3">
                        <label class="flex items-center gap-2 cursor-pointer navy-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="يناير 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>يناير 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer navy-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="فبراير 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>فبراير 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer navy-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="مارس 2026">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>مارس 2026</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer navy-shadow py-1 px-4 rounded-[8px]">
                            <input type="checkbox" class="hidden peer" value="أبريل 2027">
                            <span
                                class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>أبريل 2027</span>
                        </label>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <p class="text-[16px] font-medium text-[#021219]">
                        يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.
                    </p>
                    <div class="relative w-full">
                        <label class="px-1 text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-1"
                            class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-1" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form action="" method="POST" class="w-full" id="paymentForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="installment_ids[]" id="paymentInstallmentIds">
                    <input type="hidden" name="receipt_number" id="paymentReceiptNumber">
                    <button type="submit"
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تسجيل سداد
                        القسط</button>
                </form>
                <button
                    class="close-payment-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- END MODALS -->




    <script src="{{ asset('js/loans.js') }}"></script>
@endsection
