@extends('layouts.app')
{{--
    Claims Index View (Employee):
    Displays all financial claims grouped by their lifecycle status (Paid, Pending Approval, Pending Settlement).
    Allows employees to search, filter by date/status/type, and create new claim requests.
--}}

@section('title', 'قائمة المطالبات')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/employee/claims.css') }}">
    <!-- start header -->
    <div
        class="flex flex-col md:flex-row justify-between items-center px-4 md:px-12 py-4 md:py-5 gap-4 md:gap-0 text-center md:text-right print:hidden">
        <div>
            <h1 class="text-[32px] font-medium text-[#124375]">
                المطالبات
            </h1>
        </div>
        <div class="btns flex items-center gap-3 w-full md:w-auto">
            <button
                class="open-modal w-full md:w-auto rounded-xl flex items-center justify-center py-3 px-6 md:px-20 gap-2 text-[#F4F7F9] bg-[#124375] navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ic:round-plus" class="flex items-center text-2xl"></iconify-icon> إنشاء مطالبة
            </button>
            <a href="{{ route('claims.export', request()->query()) }}"
                class="rounded-xl w-full md:w-auto flex items-center justify-center py-3 gap-2 px-5 text-[#124375] bg-[#F4F7F9] navy-shadow">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل
            </a>
        </div>
    </div>
    <!-- end header -->

    <!-- start cards -->
    <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4 px-4 md:px-12 print:hidden">
        <div
            class="navy-shadow flex items-center justify-center gap-4 md:gap-7 bg-[#F4F7F9] rounded-xl px-4 md:px-7 py-4 border-s-8 border-[#124375]">
            <div>
                <iconify-icon icon="ph:trend-up-fill"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#124375] bg-[#EEF7FF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $paidCount }}</p>
                <p class="text-sm font-medium">مطالبات تم صرفها</p>
            </div>
        </div>
        <div
            class="yellow-shadow flex items-center justify-center gap-4 md:gap-7 bg-[#F4F7F9] rounded-xl px-4 md:px-7 py-4 border-s-8 border-[#D4AF37]">
            <div>
                <iconify-icon icon="tabler:clipboard-list-filled"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#D4AF37] bg-[#FFFCEF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $pendingApprovalCount }}</p>
                <p class="text-sm font-medium">مطالبات بانتظار الأعتماد</p>
            </div>
        </div>
        <div
            class="red-shadow flex items-center justify-center gap-4 md:gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
            <div>
                <iconify-icon icon="ph:trend-down-fill"
                    class="navy-shadow text-[40px] text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $pendingSettlementCount }}</p>
                <p class="text-sm font-medium">مطالبات بانتظار التسوية</p>
            </div>
        </div>
    </div>
    <!-- end cards -->

    <!-- filteration buttons -->
    <form action="{{ route('claims.index') }}" method="GET"
        class="px-4 md:px-12 flex flex-wrap items-center gap-4 print:hidden">
        <div class="relative flex-grow min-w-[280px] w-full md:w-auto">
            <input type="search" name="search" value="{{ request('search') }}"
                placeholder="الاسم أو رقم العضوية أو رقم المطالبة"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
        </div>
        <div class="relative w-full md:w-[240px] shrink-0">
            @include('partials.common.calendar', [
                'name' => 'date',
                'id' => 'subscriptions-datepicker',
                'value' => request('date'),
                'autoSubmit' => false,
            ])
        </div>
        <div class="relative w-full md:w-[200px] shrink-0">
            @php
                $statusOptions = [
                    'all' => 'الكل',
                    'approved' => 'بانتظار التسوية',
                    'pending' => 'بانتظار الأعتماد',
                    'paid' => 'تم الصرف',
                    'rejected' => 'ملغية',
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
            ])
        </div>
        <div class="relative w-full md:w-[200px] shrink-0">
            @php
                $typeOptions = ['all' => 'الكل'] + \App\Models\Services\Claim::CLAIM_TYPES;
            @endphp
            @include('partials.common.dropdown', [
                'name' => 'type',
                'label' => 'نوع المطالبة',
                'options' => $typeOptions,
                'selected' => request('type', 'all'),
                'clearable' => true,
                'required' => false,
                'autoSubmit' => true,
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
    <section class="px-4 md:px-12 py-4 print:hidden">
        <div
            class=" rounded-[14px] overflow-hidden border border-[#6D6D6D] border-0 md:border p-0 md:p-0 bg-transparent md:bg-white">
            <div class="hidden md:block">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم المطالبة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ التقديم</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">نوع المطالبة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $claim)
                            <tr class="text-center {{ $loop->even ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-{{ $claim->id }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    <a
                                        href="{{ route('members.show', ['member' => $claim->membership->member_id, 'tab' => 'claims']) }}">
                                        {{ $claim->membership->member->user->name ?? 'غير متوفر' }}
                                    </a>
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $claim->created_at->translatedFormat('d F Y') }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ \App\Models\Services\Claim::CLAIM_TYPES[$claim->type] ?? $claim->type }}</td>
                                <td class="py-3 border-l border-[#6D6D6D]">
                                    @if ($claim->status === 'approved')
                                        <span
                                            class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">بانتظار
                                            التسوية</span>
                                    @elseif($claim->status === 'paid')
                                        <span
                                            class="text-[#067647] bg-[#ECFDF3] border border-[#067647] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[145px]">تم
                                            الصرف</span>
                                    @elseif($claim->status === 'pending')
                                        <span
                                            class="text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[145px]">بانتظار
                                            الأعتماد</span>
                                    @elseif($claim->status === 'rejected')
                                        <span
                                            class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">ملغية</span>
                                    @else
                                        <span
                                            class="text-gray-600 bg-gray-100 border border-gray-600 px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[145px]">{{ $claim->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                    @if ($claim->status === 'approved')
                                        <a href="{{ route('members.show', ['member' => $claim->membership->member_id, 'tab' => 'claims']) }}"
                                            class="flex w-[140px] justify-center items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2 rounded-[12px] navy-shadow">
                                            <iconify-icon icon="majesticons:calculator" class="text-2xl"></iconify-icon>
                                            تسوية
                                        </a>
                                    @elseif($claim->status === 'pending')
                                        <a href="{{ route('claims.show', $claim->id) }}"
                                            class="flex w-[140px] justify-center items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2 rounded-[12px] navy-shadow">
                                            <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                                            أعتماد
                                        </a>
                                    @elseif($claim->status === 'paid')
                                        <button type="button"
                                            onclick="document.getElementById('modal-receipt-{{ $claim->id }}').classList.remove('hidden'); document.querySelector('.overlay').classList.remove('hidden');"
                                            class="flex w-[140px] justify-center items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2 rounded-[12px] navy-shadow">
                                            <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                                            عرض
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-[#6D6D6D] font-medium">لا توجد مطالبات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse($claims as $claim)
                    <div
                        class="bg-white rounded-[14px] border border-[#6D6D6D] p-4 flex flex-col gap-3 shadow-sm relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div class="flex flex-col">
                                <h3 class="text-[#021219] font-bold text-lg">
                                    {{ $claim->membership->member->user->name ?? 'N/A' }}</h3>
                                <span class="text-sm text-[#6D6D6D]">TRX-{{ $claim->id }}</span>
                            </div>
                            <div>
                                @if ($claim->status === 'approved')
                                    <span
                                        class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-xs font-medium">بانتظار
                                        التسوية</span>
                                @elseif($claim->status === 'paid')
                                    <span
                                        class="text-[#067647] bg-[#ECFDF3] border border-[#067647] px-3 rounded-[8px] py-[2px] inline-block text-xs font-medium">تم
                                        الصرف</span>
                                @elseif($claim->status === 'pending')
                                    <span
                                        class="text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] px-3 rounded-[8px] py-[2px] inline-block text-xs font-medium">بانتظار
                                        الأعتماد</span>
                                @elseif($claim->status === 'rejected')
                                    <span
                                        class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-xs font-medium">ملغية</span>
                                @else
                                    <span
                                        class="text-gray-600 bg-gray-100 border border-gray-600 px-3 rounded-[8px] py-[2px] inline-block text-xs font-medium">{{ $claim->status }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 mt-2">
                            <div class="flex gap-2 items-center text-sm">
                                <iconify-icon icon="mdi:calendar" class="text-[#6D6D6D]"></iconify-icon>
                                <span class="text-[#6D6D6D]">تاريخ التقديم:</span>
                                <span
                                    class="text-[#021219] font-semibold">{{ $claim->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="flex gap-2 items-center text-sm">
                                <iconify-icon icon="mdi:file-document-outline" class="text-[#6D6D6D]"></iconify-icon>
                                <span class="text-[#6D6D6D]">نوع المطالبة:</span>
                                <span
                                    class="text-[#021219] font-semibold">{{ \App\Models\Services\Claim::CLAIM_TYPES[$claim->type] ?? $claim->type }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end mt-2 pt-3 border-t border-gray-100">
                            @if ($claim->status === 'approved')
                                <a href="{{ route('members.show', ['member' => $claim->membership->member_id, 'tab' => 'claims']) }}"
                                    class="flex w-full justify-center items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2.5 rounded-[12px] border border-[#124375] text-[#124375]">
                                    <iconify-icon icon="majesticons:calculator" class="text-xl"></iconify-icon>
                                    تسوية
                                </a>
                            @elseif($claim->status === 'pending')
                                <a href="{{ route('claims.show', $claim->id) }}"
                                    class="flex w-full justify-center items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2.5 rounded-[12px] border border-[#124375] text-[#124375]">
                                    <iconify-icon icon="solar:eye-outline" class="text-xl"></iconify-icon>
                                    أعتماد
                                </a>
                            @elseif($claim->status === 'paid')
                                <button type="button"
                                    onclick="document.getElementById('modal-receipt-{{ $claim->id }}').classList.remove('hidden'); document.querySelector('.overlay').classList.remove('hidden');"
                                    class="flex w-full justify-center items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2.5 rounded-[12px] border border-[#124375] text-[#124375]">
                                    <iconify-icon icon="solar:eye-outline" class="text-xl"></iconify-icon>
                                    عرض
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D] text-[#6D6D6D] font-medium">
                        لا توجد مطالبات</div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- end table -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60] print:hidden"></div>

    <!-- MODALS -->
    <div
        class="modal hidden w-full max-w-4xl mx-auto absolute top-0 left-1/2 -translate-x-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    إنشاء المطالبة
                </h1>
            </div>
            <div class="space-y-3">
                <div id="selected-member-details" class="hidden flex gap-3 items-center">
                    <p class="text-base font-medium text-[#124375]">الاسم : <span id="selected-member-name"
                            class="text-[#021219] font-semibold text-base"></span></p>
                    <p class="text-base font-medium text-[#124375]">رقم العضوية : <span id="selected-member-number"
                            class="text-[#021219] font-semibold text-base"></span></p>
                    <p class="text-base font-medium text-[#124375]">الرقم القومي : <span id="selected-member-national-id"
                            class="text-[#021219] font-semibold text-base"></span></p>
                    <button type="button" id="clear-selected-member"
                        class="text-xl bg-[#124375] text-[#F4F7F9] rounded-[8px] py-1.5 px-2 hover:bg-[#D92D20] transition-colors">
                        <iconify-icon icon="weui:close-filled"></iconify-icon>
                    </button>
                </div>
                <div id="search-member-wrapper" class="flex items-center justify-between gap-4 ">
                    <p class="text-[#124375] text-base font-medium min-w-fit">البحث عن العضو :</p>
                    <div class="relative flex-1 ">
                        <input type="search" id="claim-member-search"
                            placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي"
                            class="w-full py-2 pr-7 outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"
                            autocomplete="off">
                        <iconify-icon icon="mynaui:search"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>

                        <!-- Search Results Dropdown -->
                        <div id="claim-member-results"
                            class="hidden absolute z-[60] bg-[#F4F7F9] w-full mt-2 rounded-xl navy-shadow max-h-60 overflow-y-auto">
                        </div>
                    </div>
                </div>
            </div>
            <div class="requirements space-y-5">
                <div class="relative claim-type-dropdown">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">نوع
                        المطالبة :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute z-50 bg-[#F4F7F9] right-3 top-full mt-3 grid grid-cols-4 gap-3 px-3 py-4 rounded-xl navy-shadow ">
                        @foreach (\App\Models\Services\Claim::CLAIM_TYPES as $key => $label)
                            <button type="button" data-type="{{ $key }}"
                                class=" navy-shadow py-2 rounded-xl text-sm font-medium ">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button id="submit-create-claim"
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        الأختيار</button>
                </form>
                <button type="button"
                    onclick="this.closest('.modal').classList.add('hidden'); document.querySelector('.overlay').classList.add('hidden');"
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>

    @foreach ($claims as $claim)
        @if ($claim->status === 'paid')
            <div id="modal-receipt-{{ $claim->id }}"
                class="modal hidden w-full max-w-4xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-5">
                <button
                    onclick="this.closest('.modal').classList.add('hidden'); document.querySelector('.overlay').classList.add('hidden');"
                    class="text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                    <iconify-icon icon="weui:close-filled"></iconify-icon>
                </button>
                <div class="modal-body space-y-7 px-7 py-2">
                    <div
                        class="text-[#F4F7F9] flex items-center justify-between bg-[#124375] navy-shadow py-3 px-4 rounded-[16px]">
                        <h1 class=" text-[20px] font-semibold">
                            إيصال صرف مستحقات تأمينية
                        </h1>
                        <div class="flex flex-col items-center gap-2">
                            <p class="text-[16px] ">رقم المعاملة :</p>
                            <p class=" text-[20px] font-semibold">TRX-{{ $claim->id }}</p>
                        </div>
                    </div>
                    <div class="bg-[#F4F7F9] navy-shadow py-3 px-4 rounded-[16px]">
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[14px] text-[#6D6D6D] font-medium">اسم العضو</h2>
                                <p class="text-[#124375] text-[25px] font-semibold">
                                    {{ $claim->membership->member->user->name }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[14px] text-[#6D6D6D] font-medium">رقم العضوية </h2>
                                <p class="text-[#021219] text-[20px] font-semibold">
                                    {{ $claim->membership->membership_number }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[14px] text-[#6D6D6D] font-medium">تاريخ نهاية الخدمة</h2>
                                <p class="text-[#021219] text-[20px] font-semibold">
                                    {{ $claim->membership->member->employmentInfo->retirement_date ? \Carbon\Carbon::parse($claim->membership->member->employmentInfo->retirement_date)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[14px] text-[#6D6D6D] font-medium">حالة الصرف</h2>
                                <p
                                    class="text-[14px] text-[#067647] border border-[#067647] py-1 px-12 bg-[#ECFDF3] rounded-[8px] font-medium">
                                    تم الصرف</p>
                            </div>
                        </div>
                        <hr class="border border-[#A8A8A8] mx-1 my-4">
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[14px] text-[#6D6D6D] font-medium">سبب الاستحقاق</h2>
                                <p class="text-[#021219] text-[20px] font-semibold">
                                    {{ \App\Models\Services\Claim::CLAIM_TYPES[$claim->type] ?? $claim->type }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[14px] text-[#6D6D6D] font-medium">رقم الشيك </h2>
                                <p class="text-[#021219] text-[20px] font-semibold">
                                    {{ $claim->receipt_number ?? 'غير متوفر' }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h2 class="text-[14px] text-[#6D6D6D] font-medium">تاريخ ووقت التنفيذ</h2>
                                <p class="text-[#021219] text-[20px] font-semibold">
                                    {{ $claim->updated_at->translatedFormat('d F Y - h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#F4F7F9] navy-shadow py-3 px-4 rounded-[16px]">
                        <div class="flex justify-between">
                            <p class="text-[#6D6D6D] text-[16px] font-normal">قيمة الميزة التأمينية ( الأساسية )</p>
                            <p class="text-[#124375] text-[16px] font-semibold">{{ number_format($claim->amount, 2) }} ج.م
                            </p>
                        </div>
                        @if ($claim->type === 'death')
                            <hr class="border border-[#A8A8A8] mx-1 my-4">
                            <div class="flex justify-between">
                                <p class="text-[#6D6D6D] text-[16px] font-normal">مصاريف الجنازة</p>
                                <p class="text-[#067647] text-[16px] font-semibold">
                                    +{{ number_format((float) \App\Models\System\SystemSetting::get('claim_funeral_expenses', 0), 2) }}
                                    ج.م</p>
                            </div>
                        @endif
                        <hr class="border border-[#A8A8A8] mx-1 my-4">
                        <div class="flex justify-between">
                            <p class="text-[#6D6D6D] text-[16px] font-normal">رصيد القروض المتبقي</p>
                            <p class="text-[#D92D20] text-[16px] font-semibold">
                                -{{ number_format($claim->membership->remaining_loan_balance, 2) }} ج.م</p>
                        </div>
                        <hr class="border border-[#A8A8A8] mx-1 my-4">
                        <div
                            class="flex items-center justify-between border border-[#1243751A] bg-[#1243751A] rounded-[8px] py-4 px-4 ">
                            <p class="text-[16px] text-[#124375]">صافي المبلغ المستحق صرفه</p>
                            <p class="text-[32px] text-[#001E3D] font-medium">
                                {{ number_format($claim->amount - $claim->membership->remaining_loan_balance + ($claim->type === 'death' ? (float) \App\Models\System\SystemSetting::get('claim_funeral_expenses', 0) : 0), 2) }}
                                ج.م</p>
                        </div>
                    </div>
                    <div class="btns flex gap-2 no-print print:hidden">
                        <div class="w-full">
                            <button type="button"
                                onclick="window.open('{{ route('print.claim_receipt', $claim->id) }}', '_blank')"
                                class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                        icon="fluent:save-16-filled"
                                        class="flex items-center text-2xl"></iconify-icon></span>طباعة الإيصال</button>
                        </div>
                        <div class="w-full">
                            <a href="{{ route('members.show', ['member' => $claim->membership->member_id, 'tab' => 'claims']) }}"
                                class="flex w-full justify-center items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-3 rounded-[12px] border border-[#124375] text-[#124375]">
                                <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                                عرض ملف العضو
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- MODALS -->
    <script src="{{ asset('js/employee/claims.js') }}"></script>
@endsection

@section('pagination')
    <div
        class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-4 md:-mx-6 px-4 md:px-6 backdrop-blur-md bg-white/80">
        {{ $claims->links() }}
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('claim-member-search');
            const resultsContainer = document.getElementById('claim-member-results');
            let selectedMemberId = null;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    if (query.length < 2) {
                        resultsContainer.classList.add('hidden');
                        return;
                    }

                    fetch(`{{ route('loans.searchMembers') }}?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            resultsContainer.innerHTML = '';
                            if (data.length === 0) {
                                resultsContainer.innerHTML =
                                    '<div class="p-3 text-center text-gray-500">لا يوجد نتائج</div>';
                            } else {
                                data.forEach(member => {
                                    const div = document.createElement('div');
                                    div.className =
                                        'p-3 cursor-pointer hover:bg-[#124375] hover:text-white transition-colors border-b border-gray-200 last:border-0 text-right';
                                    div.innerHTML = `
                                <div class="font-bold">${member.full_name}</div>
                                <div class="text-sm">رقم العضوية: ${member.membership_number} | القومي: ${member.national_id}</div>
                            `;
                                    div.addEventListener('click', () => {
                                        searchInput.value = '';
                                        selectedMemberId = member.id;
                                        resultsContainer.classList.add('hidden');

                                        // Hide search, show details
                                        document.getElementById('search-member-wrapper')
                                            .classList.add('hidden');
                                        document.getElementById('selected-member-name')
                                            .textContent = member.full_name;
                                        document.getElementById(
                                                'selected-member-number').textContent =
                                            member.membership_number;
                                        document.getElementById(
                                                'selected-member-national-id')
                                            .textContent = member.national_id;
                                        document.getElementById(
                                                'selected-member-details').classList
                                            .remove('hidden');
                                        document.getElementById(
                                                'selected-member-details').classList
                                            .add('flex');
                                    });
                                    resultsContainer.appendChild(div);
                                });
                            }
                            resultsContainer.classList.remove('hidden');
                        });
                });

                // Clear selected member
                const clearBtn = document.getElementById('clear-selected-member');
                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        selectedMemberId = null;
                        document.getElementById('selected-member-details').classList.add('hidden');
                        document.getElementById('selected-member-details').classList.remove('flex');
                        document.getElementById('search-member-wrapper').classList.remove('hidden');
                        searchInput.focus();
                    });
                }

                // Hide dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                        resultsContainer.classList.add('hidden');
                    }
                });
            }

            let selectedClaimType = null;
            const typeButtons = document.querySelectorAll('.claim-type-dropdown .dropDown button');
            typeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    selectedClaimType = this.dataset.type;
                    const span = this.closest('.claim-type-dropdown').querySelector(
                        '.dropDownBtn span.text-\\[\\#021219\\]');
                    if (span) span.textContent = this.textContent;

                    // Close the dropdown
                    const dropDownMenu = this.closest('.claim-type-dropdown').querySelector(
                        '.dropDown');
                    if (dropDownMenu) dropDownMenu.classList.add('hidden');
                });
            });

            const submitBtn = document.getElementById('submit-create-claim');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!selectedMemberId) {
                        showFlash('تنبيه', 'الرجاء اختيار العضو أولاً', 'error');
                        return;
                    }
                    if (!selectedClaimType) {
                        showFlash('تنبيه', 'الرجاء اختيار نوع المطالبة', 'error');
                        return;
                    }

                    const baseUrl = `{{ url('/members') }}/${selectedMemberId}`;
                    window.location.href = `${baseUrl}?tab=claims&claim_type=${selectedClaimType}`;
                });
            }
        });
    </script>
@endpush
