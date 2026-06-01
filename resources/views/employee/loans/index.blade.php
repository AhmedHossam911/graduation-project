@extends('layouts.app')
{{--
    Loans Index View (Employee):
    Overview of all loans across the system, categorized by status (Active, Pending, Overdue).
    Allows creating new loan requests and registering manual installment payments.
--}}

@section('title', 'قائمة القروض')

@section('content')
    @include('partials.common.flash')
    <link rel="stylesheet" href="{{ asset('css/employee/loans.css') }}">
    <!-- start header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 py-4 md:py-5 gap-4 md:gap-0 print:hidden">
        <div class="w-full text-right md:w-auto">
            <h1 class="text-[32px] font-medium text-[#124375]">
                القروض
            </h1>
        </div>
        <div class="btns flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
            <button data-target="createLoanModal"
                class="open-modal w-full md:w-auto rounded-xl flex items-center justify-center py-3 px-6 md:px-16 gap-2 text-[#F4F7F9] bg-[#124375] navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ic:round-plus" class="flex items-center text-2xl"></iconify-icon> إنشاء طلب قرض جديد
            </button>
            <a href="{{ route('loans.export', request()->all()) }}"
                class="rounded-xl w-full md:w-auto flex items-center justify-center py-3 gap-2 px-5 text-[#124375] bg-[#F4F7F9] navy-shadow">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل
            </a>
        </div>
    </div>
    <!-- end header -->

    <!-- start cards -->
    <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4 px-4 print:hidden">
        <div
            class="navy-shadow flex items-center justify-center gap-4 md:gap-7 bg-[#F4F7F9] rounded-xl px-4 md:px-7 py-4 border-s-8 border-[#124375]">
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
            class="yellow-shadow flex items-center justify-center gap-4 md:gap-7 bg-[#F4F7F9] rounded-xl px-4 md:px-7 py-4 border-s-8 border-[#D4AF37]">
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
            class="red-shadow flex items-center justify-center gap-4 md:gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
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
    <form action="{{ route('loans.index') }}" method="GET" class="px-4 flex flex-wrap items-center gap-4 print:hidden">
        <div class="relative flex-grow min-w-[280px] w-full md:w-auto">
            <input type="search" name="search" value="{{ request('search') }}"
                placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
        </div>
        <div class="relative w-full md:w-[240px] shrink-0">
            @include('partials.common.calendar', [
                'name' => 'date',
                'id' => 'loans-datepicker',
                'value' => request('date'),
                'autoSubmit' => true,
            ])
        </div>

        <div class="relative w-full md:w-[200px] shrink-0">
            @php

                $statusMapping = [
                    'all' => 'كل الحالات',
                    'pending' => 'قيد المراحعة',
                    'active' => 'نشط',
                    'completed' => 'مكتمل',
                    'rejected' => 'مرفوض',
                ];

                if (isset($departments) && $departments->count() > 0) {
                    foreach ($departments as $department) {
                        $statusMapping[$department->id] = $department->name;
                    }
                }
            @endphp
            @include('partials.common.dropdown', [
                'name' => 'department',
                'label' => 'الجهة',
                'options' => $statusMapping,
                'selected' => request('department', 'all'),
                'required' => false,
                'clearable' => true,
                'autoSubmit' => true,
                'showConfirm' => false,
            ])
        </div>

        <div class="w-full md:w-auto shrink-0">
            <button type="submit"
                class="bg-[#124375] w-full md:w-auto text-white rounded-xl px-6 py-2.5 flex items-center justify-center hover:bg-[#0e3560] transition-colors surface-shadow">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-3xl"></iconify-icon>
            </button>
        </div>
    </form>
    <!-- end filteration buttons -->


    <!-- start table -->
    <section class="px-4 py-4 print:hidden">
        <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D] font-medium border-0 md:border p-0 md:p-0 bg-transparent md:bg-white">
            <div class="hidden md:block">
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
                        @endphp
                        <tr class="text-center {{ $loop->even ? 'bg-[#EFEFEF]' : 'border-b border-[#6D6D6D]' }}">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $loan->id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#124375] font-medium hover:underline">
                                <a href="{{ route('members.show', ['member' => $loan->membership->member_id, 'tab' => 'loans']) }}">
                                    {{ $loan->membership->member->user->name ?? 'غير متوفر' }}
                                </a>
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                {{ number_format($loan->total_amount) }} ج .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ number_format($paidAmount) }} ج
                                .م</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ number_format($remaining) }} ج .م
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] w-[200px]">
                                @if ($loan->status === 'overdue')
                                    <span
                                        class="inline-block w-[140px] text-center text-[#F79009] bg-[#FFF7ED] border border-[#F79009] rounded-[8px] py-[1px]">متأخر</span>
                                @elseif ($loan->status === 'completed')
                                    <span
                                        class="inline-block w-[140px] text-center text-[#124375] bg-[#EEF7FF] border border-[#124375] rounded-[8px] py-[1px]">مكتمل</span>
                                @elseif ($loan->status === 'active')
                                    <span
                                        class="inline-block w-[140px] text-center text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px]">نشط</span>
                                @elseif ($loan->status === 'pending')
                                    <span
                                        class="inline-block w-[140px] text-center text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] rounded-[8px] py-[1px]">تحت
                                        المراجعة</span>
                                @elseif ($loan->status === 'rejected')
                                    <span
                                        class="inline-block w-[140px] text-center text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[1px]">مرفوض</span>
                                @else
                                    <span
                                        class="inline-block w-[140px] text-center text-[#6D6D6D] bg-[#EFEFEF] border border-[#6D6D6D] rounded-[8px] py-[1px]">{{ $loan->status }}</span>
                                @endif
                            </td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <a href="{{ route('members.show', ['member' => $loan->membership->member_id, 'tab' => 'loans']) }}"
                                    class="hover:text-[#0e3560] transition-colors">
                                    <iconify-icon icon="solar:eye-linear" class="text-2xl"></iconify-icon>
                                </a>
                                @if ($loan->status === 'active' || $loan->status === '')
                                    <button class="open-modal hover:text-[#0e3560] transition-colors"
                                        data-target="paymentModal" data-loan-id="{{ $loan->id }}">
                                        <iconify-icon icon="ion:cash" class="text-2xl"></iconify-icon>
                                    </button>
                                @else
                                    <button class="text-[#6D6D6D] cursor-not-allowed">
                                        <iconify-icon icon="ion:cash" class="text-2xl"></iconify-icon>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-500">
                                <img class="w-[15%] m-auto" src="{{ asset('imgs/loans.png') }}"
                                    alt="لا توجد بيانات للقروض">
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse ($loans as $loan)
                    @php
                        $paidAmount = $loan->installments->where('status', 'paid')->sum('amount');
                        $remaining = $loan->total_amount - $paidAmount;

                        $statusColor = 'bg-[#EFEFEF] text-[#6D6D6D] border-[#6D6D6D]';
                        $statusLabel = $loan->status;

                        if ($loan->status === 'overdue') {
                            $statusColor = 'bg-[#FFF7ED] text-[#F79009] border-[#F79009]';
                            $statusLabel = 'متأخر';
                        } elseif ($loan->status === 'completed') {
                            $statusColor = 'bg-[#EEF7FF] text-[#124375] border-[#124375]';
                            $statusLabel = 'مكتمل';
                        } elseif ($loan->status === 'active') {
                            $statusColor = 'bg-[#ECFDF3] text-[#067647] border-[#067647]';
                            $statusLabel = 'نشط';
                        } elseif ($loan->status === 'pending') {
                            $statusColor = 'bg-[#FFF8E1] text-[#E6B800] border-[#E6B800]';
                            $statusLabel = 'تحت المراجعة';
                        } elseif ($loan->status === 'rejected') {
                            $statusColor = 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]';
                            $statusLabel = 'مرفوض';
                        }
                    @endphp
                    <div class="bg-white rounded-[14px] border border-[#6D6D6D] p-4 flex flex-col gap-3 shadow-sm relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div class="flex flex-col gap-1">
                                <h3 class="text-[#021219] font-bold text-lg hover:underline text-[#124375]">
                                    <a href="{{ route('members.show', ['member' => $loan->membership->member_id, 'tab' => 'loans']) }}">
                                        {{ $loan->membership->member->user->name ?? 'غير متوفر' }}
                                    </a>
                                </h3>
                                <span class="text-sm text-[#6D6D6D]">رقم القرض: {{ $loan->id }}</span>
                            </div>
                            <span class="inline-block text-xs text-center border rounded-[8px] py-[2px] px-3 font-medium {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <div class="bg-[#F4F7F9] p-2 rounded-lg border border-[#1243751A] flex flex-col items-center justify-center">
                                <span class="text-[#6D6D6D] text-xs">قيمة القرض</span>
                                <span class="text-[#124375] font-bold">{{ number_format($loan->total_amount) }} ج.م</span>
                            </div>
                            <div class="bg-[#F4F7F9] p-2 rounded-lg border border-[#1243751A] flex flex-col items-center justify-center">
                                <span class="text-[#6D6D6D] text-xs">المبلغ المتبقي</span>
                                <span class="text-[#D92D20] font-bold">{{ number_format($remaining) }} ج.م</span>
                            </div>
                        </div>

                        <div class="flex gap-2 items-center text-sm px-1">
                            <span class="text-[#6D6D6D]">المبلغ المسدد:</span>
                            <span class="text-[#067647] font-semibold">{{ number_format($paidAmount) }} ج.م</span>
                        </div>

                        <div class="flex gap-2 justify-center mt-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('members.show', ['member' => $loan->membership->member_id, 'tab' => 'loans']) }}"
                                class="flex-1 flex justify-center items-center gap-2 border border-[#124375] bg-white py-2 rounded-[8px] text-[#124375] text-sm hover:bg-[#F4F7F9]">
                                <iconify-icon icon="solar:eye-linear" class="text-lg"></iconify-icon>
                                عرض
                            </a>
                            @if ($loan->status === 'active' || $loan->status === '')
                                <button type="button" class="open-modal flex-1 flex justify-center items-center gap-2 border border-[#124375] bg-[#124375] text-white py-2 rounded-[8px] text-sm hover:bg-[#0e3560]"
                                    data-target="paymentModal" data-loan-id="{{ $loan->id }}">
                                    <iconify-icon icon="ion:cash" class="text-lg"></iconify-icon>
                                    سداد
                                </button>
                            @else
                                <button type="button" class="flex-1 flex justify-center items-center gap-2 border border-[#6D6D6D] bg-[#6D6D6D] text-white py-2 rounded-[8px] text-sm cursor-not-allowed opacity-50">
                                    <iconify-icon icon="ion:cash" class="text-lg"></iconify-icon>
                                    سداد
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 bg-white rounded-[14px] border border-[#6D6D6D]">
                        <img class="w-[30%] md:w-[15%] m-auto" src="{{ asset('imgs/loans.png') }}" alt="لا توجد بيانات للقروض">
                        <p class="text-[#6D6D6D] font-medium mt-4">لا توجد بيانات للقروض</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- end table -->


    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden  z-[60]"></div>

    <!-- MODALS -->
    <div id="createLoanModal"
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
                <div class="flex items-center justify-between gap-4 ">
                    <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
                    <div class="relative flex-1 ">
                        <input type="search" id="memberSearchInput"
                            placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض"
                            class="w-full py-2 px-2  pr-7 outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                        <iconify-icon icon="mynaui:search"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
                        <div id="memberSearchResults"
                            class="absolute z-50 bg-[#F4F7F9] left-0 right-0 top-full mt-1 hidden flex-col gap-1 px-2 py-2 rounded-xl navy-shadow max-h-48 overflow-y-auto">
                        </div>
                    </div>
                    <button id="memberSearchBtn" type="button"
                        class="bg-[#124375] text-white rounded-xl px-4 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="requirements space-y-5">
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">قيمة
                        القرض :<span class="text-[#021219] " id="loanAmountSpan">اختر</span><span
                            class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                class="text-xl"></iconify-icon></span></button>
                    <div id="loanAmountDropdown"
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                        <button type="button" data-value="5000"
                            class=" navy-shadow py-2 px-5 rounded-xl text-base ">5000</button>
                        <button type="button" data-value="10000"
                            class=" navy-shadow py-2 px-1 rounded-xl text-base ">10000</button>
                        <button type="button" data-value="20000"
                            class=" navy-shadow py-2 px-5 rounded-xl text-base ">20000</button>
                    </div>
                </div>
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">مدة
                        السداد :<span class="text-[#021219] " id="loanMonthsSpan">اختر</span><span
                            class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                class="text-xl"></iconify-icon></span></button>
                    <div id="loanMonthsDropdown"
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                        <button type="button" data-value="12"
                            class=" navy-shadow py-2 px-5 rounded-xl text-base ">12</button>
                        <button type="button" data-value="24"
                            class=" navy-shadow py-2 px-1 rounded-xl text-base ">24</button>
                        <button type="button" data-value="32"
                            class=" navy-shadow py-2 px-5 rounded-xl text-base ">32</button>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form action="{{ route('loans.store') }}" method="POST" class="w-full" id="createLoanForm">
                    @csrf
                    <input type="hidden" name="member_id" id="selectedMemberId">
                    <input type="hidden" name="total_amount" id="selectedLoanAmount">
                    <input type="hidden" name="months" id="selectedLoanMonths">
                    <button type="submit" id="createLoanSubmitBtn"
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        الأختيار</button>
                </form>
                <button
                    class="close-btn border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>

    <div id="paymentModal"
        class="modal hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <form action="" method="POST" class="w-full" id="paymentForm" enctype="multipart/form-data">
            @csrf
            <div id="paymentHiddenInputs"></div>
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
                                    class="text-[#021219] font-semibold text-base" id="paymentMemberName">-</span></p>
                            <p class="text-base font-medium text-[#124375]">رقم العضوية : <span
                                    class="text-[#021219] font-semibold text-base" id="paymentMembershipNum">-</span></p>
                            <p class="text-base font-medium text-[#124375]">الرقم القومي : <span
                                    class="text-[#021219] font-semibold text-base" id="paymentNationalId">-</span></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h2 class="text-[#021219] text-base font-medium">
                            بيانات السداد
                        </h2>
                        <div class="flex gap-3">
                            <p class="text-base font-medium text-[#124375]">المبلغ المطلوب سداده (ج.م) :<span
                                    class="text-[#021219] font-semibold text-base" id="paymentTotalAmount">0</span></p>
                            <p class="text-base font-medium text-[#124375]">تاريخ السداد :<span
                                    class="text-[#021219] font-semibold text-base">{{ now()->locale('ar')->translatedFormat('j F Y') }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="relative w-fit">
                        <button type="button"
                            class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-fit px-2 rounded-xl text-base gap-2 font-medium flex items-center">سداد
                            عن شهر / شهور<span class="text-[#D92D20]">*</span> : <span
                                class="text-[#021219] text-[14px]">اختر
                                الشهر</span> <span class="flex items-center mt-1"><iconify-icon icon="lucide:calendar"
                                    class="text-xl "></iconify-icon></span></button>
                        <div id="paymentInstallmentsDropdown"
                            class="dropDown hidden absolute rounded-[10px] bg-[#F4F7F9] navy-shadow z-50 px-5 py-4 space-y-2 left-0 top-full mt-3 max-h-48 overflow-y-auto min-w-[200px]">
                            <!-- Checkboxes will be injected here via JS -->
                        </div>
                    </div>
                    <div class="flex flex-col gap-6">
                        <p class="text-[16px] font-medium text-[#021219]">
                            يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.
                        </p>
                        <div class="relative w-full">
                            <label
                                class="px-1 text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                                رقم الإيصال <span class="text-[#D92D20]">*</span>
                            </label>
                            <input type="text" id="paymentReceiptInput" name="receipt_number"
                                placeholder="FJB2116708086230" required
                                class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                        </div>
                        <div class="border border-[#124375] rounded-[12px] ">
                            <label for="receipt_file"
                                class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة إيصال السداد</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" id="receipt_file" name="receipt_file" class="hidden" required>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-2 ">
                    <button type="submit"
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تسجيل سداد
                        القسط</button>
        </form>
        <button type="button"
            class="close-payment-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
    </div>

    <!-- END MODALS -->

    <script>
        window.APP_URL = "{{ url('/') }}";
    </script>
    <script src="{{ asset('js/employee/loans.js') }}?v={{ time() }}"></script>
@endsection

@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-4 md:-mx-6 px-4 md:px-6 backdrop-blur-md bg-white/80 ">
        {{ $loans->links() }}
    </div>
@endsection

