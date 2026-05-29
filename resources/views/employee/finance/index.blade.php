@extends('layouts.app')
{{-- 
    Finance Index View:
    Central dashboard for managing incoming (revenues) and outgoing (expenses) financial transactions.
    Provides tabs to filter between all transactions, revenues only, and expenses only, with manual entry capabilities.
--}}

@section('title', 'المالية')

@include('partials.common.flash')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/employee/finance.css') }}">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            div[id^="modal-detail-"]:not(.hidden) {
                visibility: visible !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                transform: none !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
                box-shadow: none !important;
                background: white !important;
            }

            div[id^="modal-detail-"]:not(.hidden) * {
                visibility: visible !important;
            }

            div[id^="modal-detail-"]:not(.hidden) .modal-close,
            div[id^="modal-detail-"]:not(.hidden) .btns {
                display: none !important;
            }

            /* Ensure text colors are dark for print */
            .text-\[\#124375\],
            .text-\[\#021219\] {
                color: #000 !important;
            }
        }
    </style>
    <div class="min-h-screen flex flex-col">
        <!-- start header -->
        <div class="flex justify-between px-12 py-5 print:hidden">
            <div>
                <h1 class="text-[32px] font-medium text-[s#124375]">
                    المالية
                </h1>
            </div>
            <div class="btns flex items-center gap-3  ">
                <button data-modal="modal1"
                    class="open-modal rounded-xl flex items-center justify-center py-3 px-20 gap-2 text-[#F4F7F9] bg-[#124375] navy-shadow hover:bg-[#0e3560] transition-colors">
                    <iconify-icon icon="ic:round-plus" class="flex items-center text-2xl"></iconify-icon> إضافة إيراد أو
                    مصروف
                </button>
                <a href="{{ route('finance.export', request()->query()) }}"
                    class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 text-[#124375] bg-[#F4F7F9] navy-shadow">
                    <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل
                </a>
            </div>
        </div>
        <!-- end header -->


        <!-- start cards -->
        <div class="py-4 grid grid-cols-3 gap-4 px-12 print:hidden">
            <div
                class="tab cursor-pointer navy-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
                <div>
                    <iconify-icon icon="ph:trend-up-fill"
                        class="navy-shadow text-[40px] px-2 py-1 text-[#124375] bg-[#EEF7FF] rounded-lg "></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">{{ number_format($totalRevenue, 0) }} ج.م</p>
                    <p class="text-sm font-medium tab-name">إجمالي الإيرادات</p>
                </div>
            </div>
            <div
                class="tab cursor-pointer yellow-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
                <div>
                    <iconify-icon icon="tabler:clipboard-list-filled"
                        class="navy-shadow text-[40px] px-2 py-1 text-[#D4AF37] bg-[#FFFCEF] rounded-lg "></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">{{ $todayCount }}</p>
                    <p class="text-sm font-medium tab-name">عدد الحركات اليوم</p>
                </div>
            </div>
            <div
                class="tab cursor-pointer red-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
                <div>
                    <iconify-icon icon="ph:trend-down-fill"
                        class="navy-shadow text-[40px] text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">{{ number_format($totalExpense, 0) }} ج.م</p>
                    <p class="text-sm font-medium tab-name">إجمالي المصروفات</p>
                </div>
            </div>
        </div>
        <!-- end cards -->

        <!-- filteration buttons -->
        <form action="{{ route('finance.index') }}" method="GET"
            class="px-12 flex items-center justify-between gap-5 print:hidden">
            <input type="hidden" name="active_tab" id="form-active-tab"
                value="{{ request('active_tab', 'عدد الحركات اليوم') }}">
            <div class="relative flex-1">
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder="الاسم أو رقم العضوية أو رقم الحركة"
                    class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                <iconify-icon icon="mynaui:search"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
            </div>
            <div class="relative min-w-[200px]">
                @include('partials.common.calendar', [
                    'name' => 'date',
                    'id' => 'finance-datepicker',
                    'value' => request('date'),
                    'autoSubmit' => true,
                ])
            </div>
            <div class="relative min-w-[200px]">
                @php
                    $methodOptions = ['all' => 'الكل'] + $methodLabels;
                @endphp
                @include('partials.common.dropdown', [
                    'name' => 'method',
                    'label' => 'طريقة الدفع',
                    'options' => $methodOptions,
                    'selected' => request('method', 'all'),
                    'clearable' => true,
                    'required' => false,
                    'autoSubmit' => true,
                ])
            </div>
            <div class="relative min-w-[200px]">
                @php
                    $categoryOptions = ['all' => 'الكل'] + $categoryLabels;
                @endphp
                @include('partials.common.dropdown', [
                    'name' => 'category',
                    'label' => 'بند الحركة',
                    'options' => $categoryOptions,
                    'selected' => request('category', 'all'),
                    'clearable' => true,
                    'required' => false,
                    'autoSubmit' => true,
                ])
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
        <section class="px-12 py-7 print:hidden">
            <!-- All Transactions Tab -->
            <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D] tab-content" data-tab="عدد الحركات اليوم">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">التاريخ والوقت</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">بند الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr class="text-center border-b border-[#6D6D6D]">
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->transaction_number ?? 'حركة-' . $transaction->id }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->membership?->member?->user?->name ?? 'معاملة غير مربوطة بعضو' }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->created_at->locale('ar')->translatedFormat('d F Y - h:i A') }}
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->category_label }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ number_format($transaction->amount, 2) }} ج .م</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $transaction->method_label }}
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] ">
                                    @if ($transaction->type === 'IN')
                                        <span
                                            class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">إيراد</span>
                                    @else
                                        <span
                                            class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">مصروف</span>
                                    @endif
                                </td>
                                <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                    <iconify-icon icon="solar:eye-outline" class="open-modal text-2xl cursor-pointer"
                                        data-modal="modal-detail-{{ $transaction->id }}"></iconify-icon>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 text-center">
                                    <img src="{{ asset('IMGs/No-results.png') }}" alt="NOT FOUND"
                                        class="w-48 mx-auto py-6">
                                </td>

                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Revenue Transactions Tab -->
            <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D] tab-content hidden"
                data-tab="إجمالي الإيرادات">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">التاريخ والوقت</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">بند الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revenueTransactions as $transaction)
                            <tr class="text-center border-b border-[#6D6D6D]">
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->transaction_number ?? 'حركة-' . $transaction->id }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->membership?->member?->user?->name ?? '-' }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->created_at->locale('ar')->translatedFormat('d F Y - h:i A') }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->category_label }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ number_format($transaction->amount, 2) }} ج .م</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $transaction->method_label }}
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] "><span
                                        class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">إيراد</span>
                                </td>
                                <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                    <iconify-icon icon="solar:eye-outline" class="open-modal text-2xl cursor-pointer"
                                        data-modal="modal-detail-{{ $transaction->id }}"></iconify-icon>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 text-center">
                                    <img src="{{ asset('IMGs/No-results.png') }}" alt="NOT FOUND"
                                        class="w-48 mx-auto py-6">

                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Expense Transactions Tab -->
            <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D] tab-content hidden"
                data-tab="إجمالي المصروفات">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">التاريخ والوقت</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">بند الحركة</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenseTransactions as $transaction)
                            <tr class="text-center border-b border-[#6D6D6D]">
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->transaction_number ?? 'حركة-' . $transaction->id }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->membership?->member?->user?->name ?? '-' }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->created_at->locale('ar')->translatedFormat('d F Y - h:i A') }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ $transaction->category_label }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    {{ number_format($transaction->amount, 2) }} ج .م</td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $transaction->method_label }}
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] "><span
                                        class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">مصروف</span>
                                </td>
                                <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                    <iconify-icon icon="solar:eye-outline" class="open-modal text-2xl cursor-pointer"
                                        data-modal="modal-detail-{{ $transaction->id }}"></iconify-icon>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 text-center">
                                    <img src="{{ asset('IMGs/No-results.png') }}" alt="NOT FOUND"
                                        class="w-48 mx-auto py-6">

                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        <!-- end table -->

        <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60] print:hidden"></div>

        <!-- Create Modal -->
        <div id="modal1"
            class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button type="button"
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <form action="{{ route('finance.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-body space-y-7 px-12">
                @csrf
                <input type="hidden" name="type" id="create-type-input" value="">
                <input type="hidden" name="category" id="create-category-input" value="">
                <input type="hidden" name="method" id="create-method-input" value="">
                <div class="modal-title text-center">
                    <h1 class="text-xl font-semibold text-[#124375]">
                        إضافة إيراد أو مصروف
                    </h1>
                </div>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <button type="button" data-type="IN"
                            class="modal-btn text-[16px] font-medium py-1 default-btn rounded-[12px]  flex items-center justify-center gap-2 w-full">
                            <iconify-icon icon="iconamoon:trend-up-fill" class="text-3xl mt-1"></iconify-icon>
                            إيراد
                        </button>
                        <button type="button" data-type="OUT"
                            class="modal-btn text-[16px] font-medium py-1  rounded-[12px] default-btn flex items-center justify-center gap-2 w-full">
                            <iconify-icon icon="iconamoon:trend-down-fill" class="text-3xl mt-1"></iconify-icon>
                            مصروف
                        </button>
                    </div>
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <button disabled type="button"
                                class="dropDownBtn drop-down-btn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">بند
                                الحركة :<span class="text-[#021219] ">اختر</span><span
                                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl"></iconify-icon></span></button>
                            <div
                                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 px-5 py-4 rounded-xl navy-shadow w-full">
                                <div class="flex flex-col gap-3 hidden dropdown-group" data-dropdown="مصروف">
                                    @foreach (App\Models\Financial\Transaction::EXPENSE_CATEGORIES as $key => $label)
                                        <button type="button" data-input="create-category-input"
                                            data-value="{{ $key }}"
                                            class=" navy-shadow py-2  rounded-xl text-sm font-medium">{{ $label }}</button>
                                    @endforeach
                                </div>
                                <div class="flex flex-col gap-3 hidden dropdown-group" data-dropdown="إيراد">
                                    @foreach (App\Models\Financial\Transaction::REVENUE_CATEGORIES as $key => $label)
                                        <button type="button" data-input="create-category-input"
                                            data-value="{{ $key }}"
                                            class=" navy-shadow py-2  rounded-xl text-sm font-medium">{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="relative w-full">
                            <button type="button"
                                class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">طريقة
                                الدفع :<span class="text-[#021219] ">اختر</span><span
                                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                        class="text-xl"></iconify-icon></span></button>
                            <div
                                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                                @foreach (App\Models\Financial\Transaction::METHOD_LABELS as $key => $label)
                                    <button type="button" data-input="create-method-input"
                                        data-value="{{ $key }}"
                                        class=" navy-shadow py-2  rounded-xl text-sm font-medium">{{ $label }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 pt-2">
                        <div class="relative w-full">
                            <label
                                class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">المبلغ<span
                                    class="text-[#D92D20]">*</span></label>
                            <input type="number" step="0.01" name="amount" required placeholder="مثال : 500 ج.م"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full text-center rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] py-2">
                        </div>
                        <div class="w-full">
                            <p class="text-[#124375] text-[16px] font-semibold">تاريخ الحركة : <span
                                    class="text-[#021219]">{{ now()->locale('ar')->translatedFormat('d F Y') }}</span></p>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="relative w-full">
                            <label
                                class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">بيان
                                الحركة<span class="text-[#D92D20]">*</span></label>
                            <textarea name="description" required placeholder="مثلا: شراء أحبار لطابعة مكتب الدور الرابع"
                                class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] px-2 py-3 resize-none"></textarea>
                        </div>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-1"
                            class=" cursor-pointer  py-7  text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الفاتورة أو الإيصال</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" name="attachment" id="file-1" class="hidden" required>
                        </label>
                    </div>
                </div>
                <div class="btns flex gap-4 ">
                    <div class="w-full">
                        <button type="submit"
                            class=" rounded-[14px] w-full py-2 bg-[#124375] navy-shadow text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2">
                            <iconify-icon icon="healthicons:yes" class="text-2xl mt-1"></iconify-icon>
                            حفظ وإضافة
                        </button>
                    </div>
                    <button type="button"
                        class="border border-[#124375] w-full rounded-[14px] py-2 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
                </div>
            </form>
        </div>

        @php
            $allTrans = collect($transactions->items())
                ->merge($revenueTransactions->items())
                ->merge($expenseTransactions->items())
                ->unique('id');
        @endphp
        @foreach ($allTrans as $t)
            <!-- Detail Modal for TRX {{ $t->id }} -->
            <div id="modal-detail-{{ $t->id }}"
                class="hidden w-full max-w-3xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
                <button type="button"
                    class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                    <iconify-icon icon="weui:close-filled"></iconify-icon>
                </button>
                <div class="modal-body space-y-7 px-12 py-4">
                    <div class="space-y-7">
                        <div class="flex justify-between items-center">
                            <div class="space-y-2">
                                <h1 class="text-[20px] text-[#124375] font-semibold">تفاصيل الحركة</h1>
                                <div class="flex gap-2 text-[#6D6D6D] text-[16px] font-medium">
                                    <p>رقم الحركة : <span>{{ $t->transaction_number ?? 'حركة-' . $t->id }}</span></p>
                                    <span>/</span>
                                    <p>{{ $t->created_at->locale('ar')->translatedFormat('d F Y - h:i A') }}</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-2 px-4 py-1 rounded-[10px] {{ $t->type === 'IN' ? 'bg-[#ECFDF3] text-[#067647]' : 'bg-[#FFEAE8] text-[#D92D20]' }}">
                                <iconify-icon
                                    icon="{{ $t->type === 'IN' ? 'ph:arrow-down-left-bold' : 'ph:arrow-up-right-bold' }}"
                                    class="text-3xl mt-1"></iconify-icon>
                                <p class="text-[16px] font-medium">{{ $t->type_label }}</p>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="flex gap-3">
                                <p class="text-[#124375] text-[16px] font-semibold">اسم العضو : <span
                                        class="text-[#021219]">{{ $t->membership?->member?->user?->name ?? '-' }}</span></p>
                                <p class="text-[#124375] text-[16px] font-semibold">رقم العضوية : <span
                                        class="text-[#021219]">{{ $t->membership?->membership_number ?? '-' }}</span></p>
                                <p class="text-[#124375] text-[16px] font-semibold">المبلغ الإجمالي : <span
                                        class="text-[#021219]">{{ number_format($t->amount, 2) }}</span></p>
                            </div>
                            <div class="flex gap-3">
                                <p class="text-[#124375] text-[16px] font-semibold">بند الحركة : <span
                                        class="text-[#021219]">{{ $t->category_label }}</span></p>
                                <p class="text-[#124375] text-[16px] font-semibold">طريقة الدفع : <span
                                        class="text-[#021219]">{{ $t->method_label }}</span></p>
                                <p class="text-[#124375] text-[16px] font-semibold">بواسطة : <span
                                        class="text-[#021219]">{{ $t->creator?->name ?? '-' }}</span></p>
                            </div>
                            <div class="pt-2">
                                <div class="relative w-full">
                                    <label
                                        class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">البيان</label>
                                    <textarea readonly
                                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] px-2 py-3 resize-none">{{ $t->description ?? '-' }}</textarea>
                                </div>
                            </div>

                            @if ($t->attachment_path)
                                <div class="w-full">
                                    <div
                                        class="border border-[#124375] rounded-[12px] py-4 text-[#124375] flex items-center justify-center gap-1">
                                        <iconify-icon icon="solar:paperclip-outline" class="text-2xl mt-1"></iconify-icon>
                                        <a href="{{ asset('storage/' . $t->attachment_path) }}" target="_blank"
                                            class="text-[#124375] font-medium underline mt-1 block">عرض المرفق</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="btns flex gap-4 mt-8 no-print print:hidden">
                        <div class="w-full">
                            <button type="button"
                                onclick="window.open('{{ route('print.transaction', $t->id) }}', '_blank')"
                                class=" rounded-[14px] w-full py-3 bg-[#124375] navy-shadow text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2">
                                <iconify-icon icon="fluent:save-16-filled" class="text-2xl mt-1"></iconify-icon>
                                طباعة الإيصال
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script src="{{ asset('JS/employee/finance.js') }}"></script>
@endsection

@section('pagination')
    <div
        class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80 z-[100]">
        <div class="tab-content {{ request('active_tab', 'عدد الحركات اليوم') === 'عدد الحركات اليوم' ? '' : 'hidden' }}"
            data-tab="عدد الحركات اليوم">
            {{ $transactions->links() }}
        </div>
        <div class="tab-content {{ request('active_tab') === 'إجمالي الإيرادات' ? '' : 'hidden' }}"
            data-tab="إجمالي الإيرادات">
            {{ $revenueTransactions->links() }}
        </div>
        <div class="tab-content {{ request('active_tab') === 'إجمالي المصروفات' ? '' : 'hidden' }}"
            data-tab="إجمالي المصروفات">
            {{ $expenseTransactions->links() }}
        </div>
    </div>
@endsection

