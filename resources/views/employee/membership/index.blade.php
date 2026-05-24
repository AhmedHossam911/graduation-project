@extends('layouts.app')

@section('title', 'قائمة الاشتراكات')

@section('content')
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-[24px] font-bold text-[#124375]">الاشتراكات</h2>
        <div class="flex gap-5">
            <a href="#"
                class="inline-flex items-center surface-shadow gap-2 bg-[#124375] text-white py-4 rounded-xl font-semibold transition-colors duration-150 hover:bg-primary-light w-[334px] h-[50px] justify-center">
                <iconify-icon icon="material-symbols:add-notes" width="24" height="24"></iconify-icon>
                تسجيل سداد اشتراك
            </a>
            <a href="{{ route('subscriptions.export', request()->query()) }}"
                class="inline-flex items-center surface-shadow gap-2 bg-[#F4F7F9] text-[#124375] py-4 rounded-xl font-semibold transition-colors duration-150 hover:bg-primary-light w-[150px] h-[50px] justify-center">
                <iconify-icon icon="mdi:file-excel" width="24" height="24"></iconify-icon>
                تنزيل
            </a>
        </div>
    </div>

    <!-- start cards -->
    <div class="py-4 grid grid-cols-3 gap-4 mb-2">
        <div
            class="shadow-[0_0_5px_1px_rgba(18,67,117,0.5)] shadow-md flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
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
            class="shadow-[0_0_5px_1px_rgba(212,175,55,0.5)] shadow-md flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
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

    <form action="{{ route('subscriptions.index') }}" method="GET">
        <div class="flex flex-wrap gap-4 mb-6">
            <!-- start search -->
            <div class="flex-1 items-center gap-5">
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder=" الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض" icon="bitcoin-icons:search-outline"
                    class="w-full rounded-xl py-2 px-2 pr-2 surface-shadow outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
            </div>
            <!-- end search -->

            <div class="relative min-w-[200px]">
                @include('partials.calendar', [
                    'name' => 'date',
                    'id' => 'subscriptions-datepicker',
                    'value' => request('date'),
                    'autoSubmit' => false,
                ])
            </div>

            <div class="relative min-w-[200px]">
                @php
                    $statusOptions = [
                        'all' => 'الكل',
                        'paid' => 'مسدد',
                        'unpaid' => 'غير مسدد',
                    ];
                    if (isset($statusMap)) {
                        foreach ($statusMap as $key => $statusData) {
                            $statusOptions[$key] = $statusData['label'];
                        }
                    }
                @endphp
                @include('partials.dropdown', [
                    'name' => 'status',
                    'label' => 'الحالة',
                    'options' => $statusOptions,
                    'selected' => request('status', 'all'),
                    'clearable' => true,
                    'required' => false,
                    'autoSubmitClear' => true,
                ])
            </div>

            <button class="bg-[#124375] text-white rounded-xl px-7 surface-shadow">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl"></iconify-icon>
            </button>
        </div>
    </form>


    <!-- start table -->
    <section>
        <div class="rounded-2xl overflow-hidden surface-shadow">
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
                                <a href="{{ route('members.show', ['member' => $subscription->membership->member_id, 'tab' => 'subscriptions']) }}">
                                    {{ $subscription->membership->member->full_name ?? 'حدث خطأ' }}
                                </a>
                            </td>
                            <td class="px-3 py-3 border-l border-[#6D6D6D]">{{ number_format($subscription->amount, 2) }}
                                ج.م</td>
                            <td class="px-3 py-3 border-l border-[#6D6D6D]">
                                @if ($subscription->status == 'paid')
                                    <div
                                        class="border-[#067647] text-[#067647] bg-[#ECFDF3] rounded-xl border-[1px] flex items-center justify-right gap-2 px-4 py-1.5 w-full max-w-[160px] mx-auto">
                                        <iconify-icon icon="mdi:check-circle" width="20" height="20"></iconify-icon>
                                        <span class="font-bold">مسدد</span>
                                    </div>
                                @elseif ($subscription->status == 'unpaid')
                                    <div
                                        class="border-[#D92D20] text-[#D92D20] bg-[#FEE4E2] rounded-xl border-[1px] flex items-center justify-right gap-2 px-2 py-1.5 w-full max-w-[160px] mx-auto">
                                        <iconify-icon icon="mdi:alert-circle" width="20" height="20"></iconify-icon>
                                        <span class="font-bold">غير مسدد</span>
                                    </div>
                                @elseif($subscription->status == 'overdue')
                                    <div
                                        class="border-[#124375] text-[#124375] bg-[#EEF7FF] rounded-xl border-[1px] flex items-center justify-right gap-2 px-4 py-1.5 w-full max-w-[160px] mx-auto">
                                        <iconify-icon icon="mdi:information" width="20" height="20"></iconify-icon>
                                        <span class="font-bold">متأخر</span>
                                    </div>
                                @else
                                    <span class="text-gray-500">---</span>
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
                                        icon="ic:baseline-remove-red-eye" width="24" height="24"></iconify-icon> </a>
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
    </section>

@endsection
@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7FE] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $subscriptions->links() }}
    </div>
@endsection
