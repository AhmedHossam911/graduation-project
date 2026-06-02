@extends('layouts.app')
{{--
 Subscriptions Index View:
 Displays all financial subscriptions (paid, unpaid, overdue, suspended) across all members.
 Includes filtering options and modals for manual payment registration.
--}}

@section('title', 'قائمة الاشتراكات')

@section('content')
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-2 gap-4 md:gap-0 print:hidden">
        <h2 class="text-[24px] font-bold text-[#124375]">الاشتراكات</h2>
        <div class="flex flex-col md:flex-row w-full md:w-auto gap-3 md:gap-5">
            <button data-modal="modal1"
                class="open-modal inline-flex items-center surface-shadow gap-2 bg-[#124375] text-white py-4 rounded-xl font-semibold transition-colors duration-150 hover:bg-primary-light w-full md:w-[334px] h-[50px] justify-center">
                <iconify-icon icon="material-symbols:add-notes" width="24" height="24"></iconify-icon>
                تسجيل سداد اشتراك
            </button>
            <a href="{{ route('subscriptions.export', request()->query()) }}"
                class="inline-flex items-center surface-shadow gap-2 bg-[#F4F7F9] text-[#124375] py-4 rounded-xl font-semibold transition-colors duration-150 hover:bg-primary-light w-full md:w-[150px] h-[50px] justify-center">
                <iconify-icon icon="mdi:file-excel" width="24" height="24"></iconify-icon>
                تنزيل
            </a>
        </div>
    </div>

    <!-- start cards -->
    <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4 mb-2 print:hidden">
        <div
            class="shadow-[0_0_5px_1px_rgba(18,67,117,0.5)] shadow-md flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 md:px-7 py-4 border-s-8 border-[#124375]">
            <div>
                <iconify-icon icon="fa7-solid:money-bill-wave" width="48" height="48"
                    class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-[36px] font-extrabold">{{ $stats['month_total'] }}</p>
                <p class="text-[14px] font-medium">محصلات الشهر</p>
            </div>
        </div>
        <div
            class="shadow-[0_0_5px_1px_rgba(212,175,55,0.5)] shadow-md flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 md:px-7 py-4 border-s-8 border-[#D4AF37]">
            <div>
                <iconify-icon icon="material-symbols:calendar-check" width="48" height="48"
                    class="surface-shadow text-4xl text-[#D4AF37] bg-[#FFFCEF] rounded-lg"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-[36px] font-extrabold">{{ $stats['today_total'] }}</p>
                <p class="text-[14px] font-medium">عمليات اليوم</p>
            </div>
        </div>
        <div
            class="shadow-[0_0_5px_1px_rgba(217,45,32,0.5)] shadow-md flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
            <div>
                <iconify-icon icon="mdi:calendar-warning" width="48" height="48"
                    class="surface-shadow text-4xl text-[#D92D20] bg-[#FFEAE880] rounded-lg"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-[36px] font-extrabold">{{ $stats['late_total'] }}</p>
                <p class="text-[14px] font-medium">متأخرات الشهر</p>
            </div>
        </div>
    </div>
    <!-- end cards -->

    <form action="{{ route('subscriptions.index') }}" method="GET" class="print:hidden">
        <div class="flex flex-wrap items-center gap-4 mb-6">
            <!-- start search -->
            <div class="relative flex-grow min-w-[280px] w-full md:w-auto">
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder="الاسم أو رقم العضوية أو الرقم القومي"
                    class="pr-10 pl-4 py-2.5 w-full outline-none surface-shadow bg-white md:bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
                <iconify-icon icon="mynaui:search"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
            </div>
            <!-- end search -->

            <div class="relative w-full md:w-[240px] shrink-0">
                @include('partials.common.calendar', [
                    'name' => 'date',
                    'id' => 'subscriptions-datepicker',
                    'value' => request('date'),
                    'autoSubmit' => true,
                    'floatingLabel' => true,
                    'clearable' => true,
                ])
            </div>

            <div class="relative w-full md:w-[200px] shrink-0">
                @php
                    $statusOptions = [
                        'all' => 'الكل',
                        'paid' => 'مسدد',
                        'unpaid' => 'مستحق',
                        'overdue_0_6' => 'متأخر ( تم إرسال إخطار )',
                        'overdue_6_no_notice' => 'متأخر ( يستوجب إخطار )',
                        'overdue_6_notice' => 'متأخر ( تم إرسال التنبيه )',
                        'suspended' => 'تم فصل العضوية',
                    ];
                @endphp
                @include('partials.common.dropdown', [
                    'name' => 'status',
                    'label' => 'الحالة',
                    'options' => $statusOptions,
                    'selected' => request('status', 'all'),
                    'clearable' => true,
                    'required' => false,
                    'autoSubmit' => true,
                    'floatingLabel' => true,
                ])
            </div>

            <div class="w-full md:w-auto shrink-0">
                <button type="submit"
                    class="bg-[#124375] w-full md:w-auto text-white rounded-xl px-6 py-2.5 flex items-center justify-center hover:bg-[#0e3560] transition-colors surface-shadow">
                    <iconify-icon icon="bitcoin-icons:search-outline" class="text-3xl"></iconify-icon>
                </button>
            </div>
        </div>
    </form>س


    <!-- start table -->
    <section class="print:hidden">
        <div class="rounded-2xl overflow-hidden surface-shadow border-0 md:border p-0 md:p-0 bg-transparent md:bg-white">
            <div class="hidden md:block">
                <table class="w-full text-center">
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D]">رقم العضوية</th>
                        <th class="py-3 border-l border-[#6D6D6D]">اسم العضو</th>
                        <th class="py-3 border-l border-[#6D6D6D]">المبلغ</th>
                        <th class="py-3 border-l border-[#6D6D6D]">الحالة</th>
                        <th class="py-3 border-l border-[#6D6D6D]">الشهر</th>
                        <th class="py-3 border-l border-[#6D6D6D]">الإجراءات</th>
                    </tr>
                    @if ($subscriptions->count() > 0)
                        @foreach ($subscriptions as $subscription)
                            <tr class="even:bg-[#F4F7F9] odd:bg-[#EFEFEF]">
                                <td class="px-3 py-3 border-l border-[#6D6D6D]">
                                    {{ $subscription->membership->membership_number ?? '---' }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#124375] font-medium hover:underline">
                                    <a
                                        href="{{ route('members.show', ['member' => $subscription->membership->member_id, 'tab' => 'subscriptions']) }}">
                                        {{ $subscription->membership->member->user->name ?? 'حدث خطأ' }}
                                    </a>
                                </td>
                                <td class="px-3 py-3 border-l border-[#6D6D6D]">
                                    {{ number_format($subscription->amount, 2) }}
                                    ج.م</td>
                                <td class="px-3 py-3 border-l border-[#6D6D6D]">
                                    @php
                                        $isPast = \Carbon\Carbon::parse($subscription->due_date)->isPast();
                                        $monthsLate = $isPast
                                            ? \Carbon\Carbon::parse($subscription->due_date)->diffInMonths(now())
                                            : 0;
                                        $noticeSent = $subscription->notice_sent_at !== null;
                                        $memStatus = $subscription->membership->status ?? '';
                                    @endphp

                                    @if ($memStatus === 'suspended')
                                        <div
                                            class="border-[#D92D20] text-[#D92D20] bg-[#FFEAE8] rounded-xl border-[1px] flex items-center justify-center gap-2 px-2 py-1.5 w-full max-w-[210px] mx-auto">
                                            <iconify-icon icon="mdi:account-cancel" width="20"
                                                height="20"></iconify-icon>
                                            <span class="font-bold whitespace-nowrap">تم فصل العضوية</span>
                                        </div>
                                    @elseif ($subscription->status === 'paid')
                                        <div
                                            class="border-[#067647] text-[#067647] bg-[#ECFDF3] rounded-xl border-[1px] flex items-center justify-center gap-2 px-2 py-1.5 w-full max-w-[210px] mx-auto">
                                            <iconify-icon icon="mdi:check-circle" width="20"
                                                height="20"></iconify-icon>
                                            <span class="font-bold whitespace-nowrap">مسدد</span>
                                        </div>
                                    @elseif ($isPast)
                                        @if ($monthsLate > 6 && !$noticeSent)
                                            <div
                                                class="border-[#D92D20] text-[#D92D20] bg-[#FFEAE8] rounded-xl border-[1px] flex items-center justify-center gap-2 px-2 py-1.5 w-full max-w-[210px] mx-auto">
                                                <iconify-icon icon="mdi:information" width="20"
                                                    height="20"></iconify-icon>
                                                <span class="font-bold whitespace-nowrap text-sm">متأخر ( يستوجب إخطار
                                                    )</span>
                                            </div>
                                        @elseif ($monthsLate > 6 && $noticeSent)
                                            <div
                                                class="border-[#F79009] text-[#F79009] bg-[#FFF7ED] rounded-xl border-[1px] flex items-center justify-center gap-2 px-2 py-1.5 w-full max-w-[210px] mx-auto">
                                                <iconify-icon icon="mdi:alert" width="20" height="20"></iconify-icon>
                                                <span class="font-bold whitespace-nowrap text-sm">متأخر ( تم إرسال التنبيه
                                                    )</span>
                                            </div>
                                        @else
                                            <div
                                                class="border-[#175CD3] text-[#175CD3] bg-[#EFF8FF] rounded-xl border-[1px] flex items-center justify-center gap-2 px-2 py-1.5 w-full max-w-[210px] mx-auto">
                                                <iconify-icon icon="mdi:information" width="20"
                                                    height="20"></iconify-icon>
                                                <span class="font-bold whitespace-nowrap text-sm">متأخر ( تم إرسال إخطار
                                                    )</span>
                                            </div>
                                        @endif
                                    @else
                                        <div
                                            class="border-[#6D6D6D] text-[#6D6D6D] bg-[#F2F4F7] rounded-xl border-[1px] flex items-center justify-center gap-2 px-2 py-1.5 w-full max-w-[210px] mx-auto">
                                            <span class="font-bold whitespace-nowrap text-sm">مستحق</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-3 border-l border-[#6D6D6D]">
                                    {{ $subscription->due_date->isoFormat('MMMM YYYY') }}
                                </td>
                                <td class="px-3 py-3 border-l border-[#6D6D6D]">

                                    <a href="{{ route('members.show', ['member' => $subscription->membership->member_id, 'tab' => 'subscriptions']) }}"
                                        class="text-[#124375] hover:underline">
                                        <iconify-icon
                                            class="text-[#124375] hover:rounded-md hover:scale-110 transition-all hover:duration-1000 hover:border-[1px] hover:border-[#124375] hover:p-1 cursor-pointer"
                                            icon="ic:baseline-remove-red-eye" width="24"
                                            height="24"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="py-4 text-center text-gray-500">
                                <img src="{{ asset('IMGs/No-results.png') }}" alt="" class="w-[15%] mx-auto">
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden flex flex-col gap-4">
                @if ($subscriptions->count() > 0)
                    @foreach ($subscriptions as $subscription)
                        @php
                            $isPast = \Carbon\Carbon::parse($subscription->due_date)->isPast();
                            $monthsLate = $isPast
                                ? \Carbon\Carbon::parse($subscription->due_date)->diffInMonths(now())
                                : 0;
                            $noticeSent = $subscription->notice_sent_at !== null;
                            $memStatus = $subscription->membership->status ?? '';

                            $statusHtml = '';
                            $statusBorder = '';

                            if ($memStatus === 'suspended') {
                                $statusHtml =
                                    '<iconify-icon icon="mdi:account-cancel" width="16" height="16"></iconify-icon><span>تم فصل العضوية</span>';
                                $statusBorder = 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]';
                            } elseif ($subscription->status === 'paid') {
                                $statusHtml =
                                    '<iconify-icon icon="mdi:check-circle" width="16" height="16"></iconify-icon><span>مسدد</span>';
                                $statusBorder = 'bg-[#ECFDF3] text-[#067647] border-[#067647]';
                            } elseif ($isPast) {
                                if ($monthsLate > 6 && !$noticeSent) {
                                    $statusHtml =
                                        '<iconify-icon icon="mdi:information" width="16" height="16"></iconify-icon><span>متأخر ( يستوجب إخطار )</span>';
                                    $statusBorder = 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]';
                                } elseif ($monthsLate > 6 && $noticeSent) {
                                    $statusHtml =
                                        '<iconify-icon icon="mdi:alert" width="16" height="16"></iconify-icon><span>متأخر ( تم إرسال التنبيه )</span>';
                                    $statusBorder = 'bg-[#FFF7ED] text-[#F79009] border-[#F79009]';
                                } else {
                                    $statusHtml =
                                        '<iconify-icon icon="mdi:information" width="16" height="16"></iconify-icon><span>متأخر ( تم إرسال إخطار )</span>';
                                    $statusBorder = 'bg-[#EFF8FF] text-[#175CD3] border-[#175CD3]';
                                }
                            } else {
                                $statusHtml = '<span>مستحق</span>';
                                $statusBorder = 'bg-[#F2F4F7] text-[#6D6D6D] border-[#6D6D6D]';
                            }
                        @endphp

                        <div
                            class="bg-white rounded-[14px] border border-[#6D6D6D] p-4 flex flex-col gap-3 shadow-sm relative overflow-hidden">
                            <div class="flex justify-between items-start">
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-[#124375] font-bold text-lg hover:underline">
                                        <a
                                            href="{{ route('members.show', ['member' => $subscription->membership->member_id, 'tab' => 'subscriptions']) }}">
                                            {{ $subscription->membership->member->user->name ?? 'حدث خطأ' }}
                                        </a>
                                    </h3>
                                    <span class="text-sm text-[#6D6D6D]">رقم العضوية:
                                        {{ $subscription->membership->membership_number ?? '---' }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <div
                                    class="bg-[#F4F7F9] p-2 rounded-lg border border-[#1243751A] flex flex-col items-center justify-center">
                                    <span class="text-[#6D6D6D] text-xs">الشهر</span>
                                    <span
                                        class="text-[#124375] font-bold text-sm">{{ $subscription->due_date->isoFormat('MMMM YYYY') }}</span>
                                </div>
                                <div
                                    class="bg-[#F4F7F9] p-2 rounded-lg border border-[#1243751A] flex flex-col items-center justify-center">
                                    <span class="text-[#6D6D6D] text-xs">المبلغ</span>
                                    <span class="text-[#067647] font-bold">{{ number_format($subscription->amount, 2) }}
                                        ج.م</span>
                                </div>
                            </div>

                            <div class="mt-1">
                                <div
                                    class="border {{ $statusBorder }} rounded-[8px] flex items-center justify-center gap-1.5 px-2 py-1 text-xs font-bold w-full">
                                    {!! $statusHtml !!}
                                </div>
                            </div>

                            <div class="flex justify-center mt-2 pt-3 border-t border-gray-100">
                                <a href="{{ route('members.show', ['member' => $subscription->membership->member_id, 'tab' => 'subscriptions']) }}"
                                    class="w-full flex justify-center items-center gap-2 border border-[#124375] bg-white py-2 rounded-[8px] text-[#124375] text-sm hover:bg-[#F4F7F9]">
                                    <iconify-icon icon="ic:baseline-remove-red-eye" class="text-lg"></iconify-icon>
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 bg-white rounded-[14px] border border-[#6D6D6D]">
                        <img class="w-[30%] md:w-[15%] m-auto" src="{{ asset('IMGs/No-results.png') }}"
                            alt="لا توجد بيانات">
                        <p class="text-[#6D6D6D] font-medium mt-4">لا توجد بيانات</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
@section('pagination')
    <div
        class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-4 md:-mx-6 px-4 md:px-6 backdrop-blur-md bg-white/80 z-[100]">
        {{ $subscriptions->links() }}
    </div>

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

    <!-- تسجيل سداد اشتراك -->
    <div id="modal1"
        class="modal hidden w-full max-w-2xl mx-auto fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10 w-[95%] md:w-full">
        <button
            class="modal-close text-[#124375] text-2xl surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-4 md:px-12 max-h-[80vh] overflow-y-auto">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد إشتراك
                </h1>
            </div>
            <div class="space-y-4">
                <div id="sub-member-info" class="hidden flex gap-3 items-center">
                    <p class="text-base font-medium text-[#124375]">اسم العضو :<span id="sub-member-name"
                            class="text-[#021219] font-semibold text-base"></span></p>
                    <p class="text-base font-medium text-[#124375]">رقم العضوية :<span id="sub-membership-number"
                            class="text-[#021219] font-semibold text-base"></span></p>
                    <iconify-icon icon="pajamas:redo"
                        class="cursor-pointer text-xl bg-[#124375] text-[#F4F7F9] rounded-[8px] py-1.5 px-2"
                        onclick="document.getElementById('sub-member-info').classList.add('hidden')"></iconify-icon>
                </div>
                <div class="flex items-center justify-between gap-4 ">
                    <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
                    <div class="relative flex-1 ">
                        <input type="search" id="sub-search-input" placeholder="الاسم أو رقم العضوية أو الرقم القومي"
                            autocomplete="off"
                            class="w-full py-2 pr-9 outline-none surface-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
                        <iconify-icon icon="mynaui:search"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
                        <div id="sub-member-results"
                            class="hidden absolute z-[60] bg-[#F4F7F9] w-full mt-2 rounded-xl navy-shadow max-h-60 overflow-y-auto">
                        </div>
                    </div>
                    <button id="sub-search-btn" type="button"
                        class="bg-[#124375] text-white rounded-xl px-4 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-5">
                        <p class="text-base font-medium text-[#124375]">تاريخ أقرب إشتراك غير مدفوع : <span
                                id="sub-due-date" class="text-[#021219] font-semibold text-base">-</span></p>
                        <p class="text-base font-medium text-[#124375]">المبلغ : <span id="sub-amount"
                                class="text-[#021219] font-semibold text-base">-</span></p>
                    </div>
                </div>
                <div class="flex gap-4 justify-betwwen">
                    <div class="relative w-fit">
                        <button type="button"
                            class="dropDownBtn surface-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-fit px-2 rounded-xl text-base gap-2 font-medium flex items-center">سداد
                            عن شهر / شهور<span class="text-[#D92D20]">*</span> : <span
                                class="text-[#021219] text-[14px]">اختر
                                الشهر</span> <span class="flex items-center mt-1"><iconify-icon icon="lucide:calendar"
                                    class="text-xl "></iconify-icon></span></button>
                        <div id="sub-months-dropdown"
                            class="dropDown hidden absolute rounded-[10px] bg-[#F4F7F9] surface-shadow z-50 px-5 py-4 space-y-2 left-0 top-full mt-3 h-auto max-h-60 overflow-y-auto w-full">
                            <!-- Will be populated dynamically via JS -->
                        </div>
                    </div>
                    <div class="relative w-fit">
                        <input type="hidden" id="sub-payment-method" value="salary_deduction">
                        <button type="button"
                            class="dropDownBtn surface-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-fit px-2 rounded-xl text-base gap-2 font-medium flex items-center">طريقة
                            الدفع<span class="text-[#D92D20]">*</span> : <span id="sub-payment-method-text"
                                class="text-[#021219] text-[14px]"> خصم من
                                المرتب</span><span class="flex items-center mt-1"><iconify-icon icon="fe:arrow-down"
                                    class="text-xl"></iconify-icon></span></button>
                        <div id="sub-payment-methods"
                            class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl surface-shadow w-full">
                            <button type="button" data-value="salary_deduction"
                                class=" surface-shadow py-2 rounded-xl text-sm font-medium">خصم من المرتب</button>
                            <button type="button" data-value="bank_transfer"
                                class=" surface-shadow py-2 px-1 rounded-xl text-sm font-medium">تحويل بنكي</button>
                            <button type="button" data-value="university_payment_order"
                                class=" surface-shadow py-2 px-5 rounded-xl text-sm font-medium">دفع بجواب مسبق</button>
                            <button type="button" data-value="cash"
                                class=" surface-shadow py-2 px-5 rounded-xl text-sm font-medium">نقدي</button>
                        </div>
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
                        <input type="text" id="sub-receipt-number" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="sub-receipt-image"
                            class=" cursor-pointer py-7 text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="sub-receipt-image" class="hidden" accept=".pdf, image/*">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <input type="hidden" id="sub-member-id">
                    <button type="button" id="sub-submit-btn"
                        class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تسجيل سداد
                        الإشتراك</button>
                </form>
                <button
                    class="close-btn border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- تسجيل سداد اشتراك -->

    <script>
        window.appRoutes = {
            searchMember: "{{ route('dashboard.searchMember') }}",
            searchMembersList: "{{ route('loans.searchMembers') }}",
            paySubscription: function(id) {
                return "{{ url('/subscriptions') }}/" + id + "/pay";
            },
            payInstallment: function(id) {
                return "{{ url('/loans/installments') }}/" + id + "/pay";
            },
            createClaim: function(id) {
                return "{{ url('/members') }}/" + id + "/claim";
            },
            memberProfile: function(id) {
                return "{{ url('/members') }}/" + id;
            }
        };
    </script>
    <script src="{{ asset('js/employee/membership.js') }}?v={{ time() }}"></script>
@endsection
