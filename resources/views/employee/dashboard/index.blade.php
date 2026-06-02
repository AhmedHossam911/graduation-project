@extends('layouts.app')
{{--
 Employee Dashboard View:
 The main control panel for Employees.
 Displays global search, quick action cards (members, subscriptions, installments, claims),
 daily tasks/reminders, and alerts for delayed payments.
--}}

@section('title', 'الصفحة الرئيسية')

@section('content')
 <link rel="stylesheet" href="{{ asset('css/employee/dashboard.css') }}">

 <div class="flex ">
 <!-- start main -->
 <main class="flex-1 py-5 px-3">
 <!-- start header main -->
 <div class="flex flex-col gap-2">
 <h2 class="text-[#021219] text-xl font-semibold"> مرحباً ، <span>{{ auth()->user()->name ?? '' }}</span></h2>
 <p class="text-[#6D6D6D] text-base font-normal">
 نظام إدارة الصندوق – لوحة الموظف
 </p>
 </div> <!-- end header main -->

 <!-- start search -->
 <div class="relative mt-6">
 <div class="flex items-center gap-5">
 <input type="search" id="global-search-input"
 placeholder="الاسم أو رقم العضوية أو الرقم القومي أو رقم القرض"
 class="w-full rounded-xl py-2 pr-2 surface-shadow outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
 <button id="global-search-btn" class="bg-[#124375] text-white rounded-xl px-7 surface-shadow">
 <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl"></iconify-icon>
 </button>
 </div>

 <div id="global-search-results"
 class="hidden absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-2xl z-[999] p-4 border border-[#124375]">
 <div class="flex justify-between items-center mb-3">
 <h2 class="text-[#124375] font-semibold text-lg">نتائج البحث</h2>
 <button id="close-global-search" class="text-[#124375] hover:text-red-500"><iconify-icon
 icon="weui:close-filled" class="text-2xl"></iconify-icon></button>
 </div>
 <div class="rounded-[14px] overflow-hidden border border-[#6D6D6D]">
 <div class="hidden md:block overflow-x-auto">
 <table class="w-full text-center">
 <thead>
 <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
 <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم العضوية
 </th>
 <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
 <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الرقم القومي
 </th>
 <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم القرض</th>
 <th class="py-3 font-medium text-[#021219]">إجراءات</th>
 </tr>
 </thead>
 <tbody id="search-results-tbody">
 <!-- Populated via JS -->
 </tbody>
 </table>
 </div>
 <div class="md:hidden flex flex-col gap-4 p-4" id="search-results-cards">
 <!-- Populated via JS -->
 </div>
 </div>
 </div>
 </div>
 <!-- end search -->

 <!-- start cards -->
 <div class="py-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
 <div
 class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 md:px-7 py-4 border-s-8 border-s-[#124375] hover:border-4 hover:border-[#124375] transition border-4 border-transparent">
 <div>
 <iconify-icon icon="mdi:account-group"
 class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
 </div>
 <div class="flex flex-col items-center text-[#124375] gap-2">
 <p class="text-4xl font-extrabold">{{ $activeMembersCount }}</p>
 <p class="text-sm font-medium">عدد الأعضاء النشطين</p>
 </div>
 </div>
 <div
 class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 md:px-7 py-4 border-s-8 border-s-[#D4AF37] hover:border-4 hover:border-[#D4AF37] transition border-4 border-transparent">
 <div>
 <iconify-icon icon="mdi:account-file"
 class="surface-shadow text-4xl text-[#D4AF37] bg-[#FFFCEF] rounded-lg px-2 py-1"></iconify-icon>
 </div>
 <div class="flex flex-col items-center text-[#124375] gap-2">
 <p class="text-4xl font-extrabold">{{ $todaySubscriptionsCount }}</p>
 <p class="text-sm font-medium">اشتراكات اليوم</p>
 </div>
 </div>
 <div
 class="surface-shadow flex items-center justify-center gap-4 bg-[#124375] rounded-xl px-4 py-4 border-s-8 border-s-[#EEF7FF] hover:border-4 hover:border-[#EEF7FF] transition border-4 border-transparent">
 <div>
 <iconify-icon icon="material-symbols:assignment-late"
 class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
 </div>
 <div class="flex flex-col items-center text-[#F4F7F9] gap-2">
 <p class="text-4xl font-extrabold">{{ $dueTodayInstallmentsCount }}</p>
 <p class="text-sm font-medium">أقساط مستحقة اليوم</p>
 </div>
 </div>
 <div
 class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-s-[#D92D20] hover:border-4 hover:border-[#D92D20] transition border-4 border-transparent">
 <div>
 <iconify-icon icon="mdi:account-convert"
 class="surface-shadow text-4xl text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
 </div>
 <div class="flex flex-col items-center text-[#124375] gap-2">
 <p class="text-4xl font-extrabold">{{ $pendingClaimsCount }}</p>
 <p class="text-sm font-medium">طلبات تحت المراجعة</p>
 </div>
 </div>
 </div>
 <!-- end cards -->

 <!-- start tasks -->
 <div class="py-5 grid grid-cols-1 lg:grid-cols-3 gap-7">
 <div class="col-span-1 lg:col-span-2 space-y-5">
 <div class="flex items-center gap-2">
 <iconify-icon icon="material-symbols:edit-notifications-rounded" class="text-2xl"></iconify-icon>
 <h2 class="text-base font-medium">المهام المطلوبة اليوم <span
 class="text-[#124375]">({{ $totalTasksCount }})</span></h2>
 </div>
 <div class="py-2 surface-shadow rounded-2xl py-4 px-5 divide-y-2 divide-[#6D6D6D]">
 @if ($totalTasksCount == 0)
 <div class="py-5 text-center text-[#6D6D6D]">لا توجد مهام مطلوبة اليوم.</div>
 @else
 @foreach ($todaySubscriptions as $sub)
 <div class="flex justify-between py-5">
 <div class="flex items-center gap-2">
 <iconify-icon icon="dashicons:arrow-left"
 class="text-4xl text-[#175CD3]"></iconify-icon>
 <div>
 <h3 class="text-[#021219] text-sm font-medium">اشتراك مستحق اليوم</h3>
 <p class="text-[#6D6D6D] text-sm font-normal">
 {{ $sub->membership->member->user->name ?? 'عضو' }}</p>
 </div>
 </div>
 <a href="{{ route('members.show', $sub->membership->member->id) }}?tab=الاشتراكات"
 class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3">عرض
 التفاصيل</a>
 </div>
 @endforeach
 @foreach ($dueTodayInstallments as $installment)
 <div class="flex justify-between py-5">
 <div class="flex items-center gap-2">
 <iconify-icon icon="dashicons:arrow-left"
 class="text-4xl text-[#D92D20]"></iconify-icon>
 <div>
 <h3 class="text-[#021219] text-sm font-medium">قسط متأخر</h3>
 <p class="text-[#6D6D6D] text-sm font-normal">
 {{ $installment->loan->membership->member->user->name ?? 'عضو' }}</p>
 </div>
 </div>
 <a href="{{ route('members.show', $installment->loan->membership->member->id) }}?tab=قروض"
 class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3">عرض
 التفاصيل</a>
 </div>
 @endforeach
 @foreach ($pendingClaims as $claim)
 <div class="flex justify-between py-5">
 <div class="flex items-center gap-2">
 <iconify-icon icon="dashicons:arrow-left"
 class="text-4xl text-[#D4AF37]"></iconify-icon>
 <div>
 <h3 class="text-[#021219] text-sm font-medium">مطالبة قيد المراجعة</h3>
 <p class="text-[#6D6D6D] text-sm font-normal">
 {{ $claim->membership->member->user->name ?? 'عضو' }}</p>
 </div>
 </div>
 <a href="{{ route('members.show', $claim->membership->member->id) }}?tab=مطالبات&view_claim={{ $claim->id }}"
 class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3">عرض
 التفاصيل</a>
 </div>
 @endforeach
 @endif
 </div>
 </div>
 <div class="col-span-1 h-full">
 <div class="grid grid-cols-2 gap-4 h-full">
 <a href="{{ route('members.create') }}"
 class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124 75] hover:border-4 hover:border-[#124375] transition border-4 border-transparent">
 <div class="my-auto text-center">
 <iconify-icon icon="mdi:account-multiple-plus"
 class="text-5xl text-[#124375]"></iconify-icon>
 <h3 class="text-base font-medium text-[#124375]">تسجيل عضو جديد</h3>
 </div>
 </a>
 <div data-modal="modal1"
 class="open-modal cursor-pointer surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375 hover:border-4 hover:border-[#124375] transition border-4 border-transparent">
 <div class="my-auto text-center">
 <iconify-icon icon="material-symbols:list-alt-check-rounded"
 class="text-5xl text-[#124375]"></iconify-icon>
 <h3 class="text-base font-medium text-[#124375]">تسجيل سداد إشتراك</h3>
 </div>
 </div>
 <div data-modal="modal2"
 class="open-modal cursor-pointer surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375 hover:border-4 hover:border-[#124375] transition border-4 border-transparent">
 <div class="my-auto text-center">
 <iconify-icon icon="ion:cash" class="text-5xl text-[#124375]"></iconify-icon>
 <h3 class="text-base font-medium text-[#124375]">تسجيل سداد قسط</h3>
 </div>
 </div>
 <div data-modal="modal3"
 class="open-modal cursor-pointer surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375 hover:border-4 hover:border-[#124375] transition border-4 border-transparent">
 <div class="my-auto text-center">
 <iconify-icon icon="mdi:account-file" class="text-5xl text-[#124375]"></iconify-icon>
 <h3 class="text-base font-medium text-[#124375]">إنشاء مطالبة</h3>
 </div>
 </div>
 </div>
 </div>
 </div>
 <!-- end tasks -->

 <!-- start table -->
 <section>
 <div class="flex items-center gap-2 py-3">
 <iconify-icon icon="bxs:error" class="text-2xl"></iconify-icon>
 <h3 class="text-base font-medium ">
 تنبيهات التأخير العاجلة
 </h3>
 </div>
 @if ($lateInstallments->isEmpty())
 <div class="rounded-2xl overflow-hidden text-center surface-shadow">
 <img src="{{ asset('IMGs/Dashboard-no-members.png') }}" alt="no alerts" style="width: 250px"
 class="mx-auto py-10">
 </div>
 @else
 <div
 class="rounded-2xl overflow-hidden surface-shadow bg-transparent md:bg-white border-0 md:border border-[#6D6D6D]">
 <div class="hidden md:block">
 <table class="w-full text-center">
 <thead>
 <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
 <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو
 </th>
 <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم العضوية
 </th>
 <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ
 المستحق</th>
 <th class="py-3 font-medium text-[#021219]">مدة التأخير</th>
 </tr>
 </thead>
 <tbody>
 @foreach ($lateInstallments as $installment)
 <tr class="border-b border-[#6D6D6D] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
 <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
 {{ $installment->loan->membership->member->user->name ?? 'عضو' }}
 </td>
 <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
 {{ $installment->loan->membership->membership_number ?? '-' }}
 </td>
 <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
 {{ $installment->amount }} ج.م
 </td>
 <td class="py-3 text-[#021219]">
 {{ \Carbon\Carbon::parse($installment->due_date)->diffForHumans() }}
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>

 <!-- Mobile Cards -->
 <div class="md:hidden flex flex-col gap-4">
 @foreach ($lateInstallments as $installment)
 <div
 class="bg-white rounded-[14px] border border-[#D92D20] p-4 flex flex-col gap-3 shadow-sm relative overflow-hidden">
 <div class="absolute top-0 right-0 w-2 h-full bg-[#D92D20]"></div>
 <div class="flex flex-col gap-2 mr-3">
 <div class="flex justify-between items-start">
 <h3 class="text-[#021219] font-bold text-lg">
 {{ $installment->loan->membership->member->user->name ?? 'عضو' }}</h3>
 <span
 class="text-[#D92D20] bg-[#FFEAE880] border border-[#FDA29B] rounded-[8px] py-[1px] px-2 text-xs flex items-center gap-1">
 <iconify-icon icon="mdi:clock-alert-outline"></iconify-icon>
 {{ \Carbon\Carbon::parse($installment->due_date)->diffForHumans() }}
 </span>
 </div>
 <div class="flex gap-2 items-center text-sm">
 <span class="text-[#6D6D6D]">رقم العضوية:</span>
 <span
 class="text-[#124375] font-semibold">{{ $installment->loan->membership->membership_number ?? '-' }}</span>
 </div>
 <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
 <span class="text-[#6D6D6D] text-sm">المبلغ المستحق</span>
 <span class="text-[#D92D20] font-bold text-lg">{{ $installment->amount }}
 ج.م</span>
 </div>
 </div>
 </div>
 @endforeach
 </div>
 </div>
 @endif
 </section>
 </main>
 </div>
 <!-- end table -->

 <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>


 <!-- تسجيل سداد اشتراك -->
 <div id="modal1"
 class="modal hidden w-full max-w-2xl mx-auto absolute top-0 left-1/2 -translate-x-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
 <button
 class="modal-close text-[#124375] text-2xl surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
 <iconify-icon icon="weui:close-filled"></iconify-icon>
 </button>
 <div class="modal-body space-y-7 px-12">
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
 <input type="search" id="sub-search-input"
 placeholder="الاسم أو رقم العضوية أو الرقم القومي" autocomplete="off"
 class="w-full py-2 pr-9 outline-none surface-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
 <iconify-icon icon="mynaui:search"
 class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
 <div id="sub-member-results"
 class="hidden absolute z-[60] bg-[#F4F7F9] w-full mt-2 rounded-xl navy-shadow max-h-60 overflow-y-auto">
 </div>
 </div>
 <button id="sub-search-btn" type="button"
 class="bg-[#124375] text-white rounded-xl px-4 py-1 f ex items-center justify-center hover:bg-[#0e3560] transition border-4 border-transparent">
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
 class="dropDown hidden absolute rounded-[10px] bg-[#F4F7F9] surface-shadow z-50 px-5 py-4 space-y-2 left-0 top-full mt-3 max-h-60 overflow-y-auto w-full">
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
 class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition border-4 border-transparent"><span><iconify-icon
 icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تسجيل
 سداد
 الإشتراك</button>
 </form>
 <button
 class="close-btn border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
 </div>
 </div>
 </div>
 <!-- تسجيل سداد اشتراك -->
 <!-- تسجيل سداد قسط -->
 <div id="modal2"
 class="modal hidden w-full max-w-2xl mx-auto absolute top-0 left-1/2 -translate-x-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
 <button
 class="modal-close text-[#124375] text-2xl surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
 <iconify-icon icon="weui:close-filled"></iconify-icon>
 </button>
 <div class="modal-body space-y-7 px-12">
 <div class="modal-title text-center">
 <h1 class="text-xl font-semibold text-[#124375]">
 تسجيل سداد قسط
 </h1>
 </div>
 <div class="space-y-4">
 <div id="inst-member-info" class="hidden flex gap-3 items-center">
 <p class="text-base font-medium text-[#124375]">اسم العضو :<span id="inst-member-name"
 class="text-[#021219] font-semibold text-base"></span></p>
 <p class="text-base font-medium text-[#124375]">رقم القرض :<span id="inst-loan-number"
 class="text-[#021219] font-semibold text-base"></span></p>
 <p class="text-base font-medium text-[#124375]">المبلغ المتبقي :<span id="inst-loan-remaining"
 class="text-[#021219] font-semibold text-base"></span></p>
 <iconify-icon icon="pajamas:redo"
 class="cursor-pointer text-xl bg-[#124375] text-[#F4F7F9] rounded-[8px] py-1.5 px-2"
 onclick="document.getElementById('inst-member-info').classList.add('hidden')"></iconify-icon>
 </div>
 <div class="flex items-center justify-between gap-4 ">
 <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
 <div class="relative flex-1 ">
 <input type="search" id="inst-search-input"
 placeholder="الاسم أو رقم العضوية أو الرقم القومي" autocomplete="off"
 class="w-full py-2 pr-9 outline-none surface-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
 <iconify-icon icon="mynaui:search"
 class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
 <div id="inst-member-results"
 class="hidden absolute z-[60] bg-[#F4F7F9] w-full mt-2 rounded-xl navy-shadow max-h-60 overflow-y-auto">
 </div>
 </div>
 <button id="inst-search-btn" type="button"
 class="bg-[#124375] text-white rounded-xl px-4 py-1 f ex items-center justify-center hover:bg-[#0e3560] transition border-4 border-transparent">
 <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
 </button>
 </div>
 <div class="space-y-4">
 <h2 class="text-[#021219] text-base font-medium">
 بيانات السداد
 </h2>
 <div class="flex gap-3">
 <p class="text-base font-medium text-[#124375]">المبلغ المحدد (ج.م) :<span
 id="inst-amount-selected" class="text-[#021219] font-semibold text-base">0</span></p>
 </div>
 </div>
 <div class="relative w-fit">
 <button type="button"
 class="dropDownBtn surface-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-fit px-2 rounded-xl text-base gap-2 font-medium flex items-center">سداد
 عن شهر / شهور<span class="text-[#D92D20]">*</span> : <span class="text-[#021219] text-[14px]">اختر
 الشهر</span> <span class="flex items-center mt-1"><iconify-icon icon="lucide:calendar"
 class="text-xl "></iconify-icon></span></button>
 <div id="inst-months-dropdown"
 class="dropDown hidden absolute rounded-[10px] bg-[#F4F7F9] surface-shadow z-50 px-5 py-4 space-y-2 left-0 top-full mt-3 max-h-60 overflow-y-auto w-full">
 <!-- Populated by JS -->
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
 <input type="text" id="inst-receipt-number" placeholder="FJB2116708086230"
 class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
 </div>
 <div class="border border-[#124375] rounded-[12px] ">
 <label for="inst-receipt-image"
 class=" cursor-pointer py-7 text-[#124375] flex items-center justify-center gap-1">
 <p>اضغط لإرفاق صورة إيصال السداد</p>
 <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
 <input type="file" id="inst-receipt-image" class="hidden" accept=".pdf, image/*">
 </label>
 </div>
 </div>
 </div>
 <div class="btns flex gap-2 ">
 <form class="w-full">
 <input type="hidden" id="inst-member-id">
 <input type="hidden" id="inst-loan-id">
 <button type="button" id="inst-submit-btn"
 class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition border-4 border-transparent"><span><iconify-icon
 icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تسجيل
 سداد
 القسط</button>
 </form>
 <button
 class="close-btn border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
 </div>
 </div>
 </div>
 <!-- تسجيل سداد قسط -->

 <!-- انشاء مطالبة -->
 <div id="modal3"
 class="modal hidden w-full max-w-4xl mx-auto absolute top-0 left-1/2 -translate-x-1/2 z-[70] rounded-2xl bg-[#F4F7F9] surface-shadow pt-2 pb-10">
 <button
 class="modal-close text-[#124375] text-2xl surface-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
 <iconify-icon icon="weui:close-filled"></iconify-icon>
 </button>
 <div class="modal-body space-y-7 px-12">
 <div class="modal-title text-center">
 <h1 class="text-xl font-semibold text-[#124375]">
 إنشاء المطالبة
 </h1>
 </div>
 <div class="space-y-3">
 <div id="claim-member-info" class="hidden flex gap-3 items-center">
 <p class="text-base font-medium text-[#124375]">الأسم : <span id="claim-member-name"
 class="text-[#021219] font-semibold text-base"></span></p>
 <p class="text-base font-medium text-[#124375]">رقم العضوية : <span id="claim-membership-number"
 class="text-[#021219] font-semibold text-base"></span></p>
 <p class="text-base font-medium text-[#124375]">الرقم القومي : <span id="claim-national-id"
 class="text-[#021219] font-semibold text-base"></span></p>
 <iconify-icon icon="pajamas:redo"
 class="cursor-pointer text-xl bg-[#124375] text-[#F4F7F9] rounded-[8px] py-1.5 px-2"
 onclick="document.getElementById('claim-member-info').classList.add('hidden')"></iconify-icon>
 </div>
 <div class="flex items-center justify-between gap-4 ">
 <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
 <div class="relative flex-1 ">
 <input type="search" id="claim-search-input"
 placeholder="الاسم أو رقم العضوية أو الرقم القومي" autocomplete="off"
 class="w-full py-2 pr-7 outline-none surface-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
 <iconify-icon icon="mynaui:search"
 class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
 <div id="claim-member-results"
 class="hidden absolute z-[60] bg-[#F4F7F9] w-full mt-2 rounded-xl navy-shadow max-h-60 overflow-y-auto">
 </div>
 </div>
 <button id="claim-search-btn" type="button"
 class="bg-[#124375] text-white rounded-xl px-4 py-1 f ex items-center justify-center hover:bg-[#0e3560] transition border-4 border-transparent">
 <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
 </button>
 </div>
 </div>
 <div class="requirements space-y-5">
 <div class="relative">
 <button type="button"
 class="dropDownBtn surface-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 items-center">نوع
 المطالبة <span class="text-[#D92D20]">*</span> : <span class="text-[#021219] ">اختر</span><span
 class="flex items-center"><iconify-icon icon="fe:arrow-down"
 class="text-xl"></iconify-icon></span></button>
 <div
 class="dropDown hidden absolute z-50 bg-[#F4F7F9] right-3 top-full mt-3 grid grid-cols-4 gap-3 px-3 py-4 rounded-xl surface-shadow ">
 <button type="button" data-value="occupational_disability"
 class=" surface-shadow py-2 rounded-xl text-sm font-medium ">عجز مهني</button>
 <button type="button" data-value="death"
 class=" surface-shadow py-2 rounded-xl text-sm font-medium ">وفاة</button>
 <button type="button" data-value="transfer"
 class=" surface-shadow py-2 rounded-xl text-sm font-medium ">نقل</button>
 <button type="button" data-value="legal_retirement"
 class=" surface-shadow py-2 px-2 rounded-xl text-sm font-medium ">بلوغ سن التقاعد
 القانوني</button>
 <button type="button" data-value="dismissal"
 class=" surface-shadow py-2 rounded-xl text-sm font-medium ">فصل</button>
 <button type="button" data-value="withdrawal"
 class=" surface-shadow py-2 rounded-xl text-sm font-medium ">انسحاب</button>
 <button type="button" data-value="early_retirement"
 class=" surface-shadow py-2 rounded-xl text-sm font-medium ">معاش مبكر</button>
 <button type="button" data-value="resignation"
 class=" surface-shadow py-2 rounded-xl text-sm font-medium ">استقالة</button>
 </div>
 </div>
 </div>
 <div class="btns flex gap-2 ">
 <form class="w-full">
 <input type="hidden" id="claim-member-id">
 <input type="hidden" id="claim-type">
 <button type="button" id="claim-submit-btn"
 class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition border-4 border-transparent"><span><iconify-icon
 icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
 الأختيار</button>
 </form>
 <button
 class="close-btn border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
 </div>
 </div>
 </div>
 <!-- انشاء مطالبة -->

 <!-- نتائج البحث -->

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
 <script src="{{ asset('js/employee/dashboard.js') }}?v={{ time() }}"></script>
@endsection
