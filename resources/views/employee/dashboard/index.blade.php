@extends('layouts.app')

@section('title', 'الصفحة الرئيسية')

@section('content')
    <!-- start main -->
    <main class="flex-1 py-5 px-3">
        <!-- start header main -->
        <div class="flex flex-col gap-2">
            <h2 class="text-[#021219] text-xl font-semibold"> مرحباً ، <span>{{ Auth::user()->name }}</span></h2>
            <p class="text-[#6D6D6D] text-base font-normal">
                نظام إدارة الصندوق – لوحة الموظف
            </p>
        </div>
        <!-- end header main -->

        <!-- start search -->
        <form action="{{ route('members.index') }}" method="GET" class="flex items-center gap-5 mt-6 ">
            <input type="search" name="search" placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض"
                class="w-full  rounded-xl py-2 pr-2 surface-shadow outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
            <button type="submit" class="bg-[#124375] text-white rounded-xl px-7 surface-shadow">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl"></iconify-icon>
            </button>
        </form>
        <!-- end search -->

        <!-- start cards -->
        <div class="py-4 grid grid-cols-4 gap-4">
            <div
                class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
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
                class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
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
                class="surface-shadow flex  items-center justify-center gap-4 bg-[#124375] rounded-xl px-4 py-4 border-s-8 border-[#EEF7FF]">
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
                class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
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
        <div class="py-5 grid grid-cols-3 gap-7">
            <div class="col-span-2 space-y-5">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="material-symbols:edit-notifications-rounded" class="text-2xl"></iconify-icon>
                    <h2 class="text-base font-medium">المهام المطلوبة اليوم <span
                            class="text-[#124375]">({{ $totalTasksCount }})</span>
                    </h2>
                </div>
                <div class="py-2 surface-shadow rounded-2xl py-4 px-5 divide-y-2 divide-[#6D6D6D]">
                    {{-- Subscriptions due today --}}
                    @foreach ($todaySubscriptions as $subscription)
                        <div class="flex justify-between items-center py-5">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#175CD3]"></iconify-icon>
                                <div>
                                    <h3 class="text-[#021219] text-sm font-medium">اشتراك مستحق اليوم -
                                        {{ $subscription->membership->member->full_name ?? 'عضو' }}</h3>
                                    <p class="text-[#6D6D6D] text-sm font-normal">
                                        @if ($subscription->status === 'paid')
                                            تم السداد
                                        @else
                                            غير مسجل بعد
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('members.show', ['member' => $subscription->membership->member_id, 'tab' => 'subscriptions']) }}"
                                class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3 hover:bg-[#0e3560] transition-colors">عرض
                                التفاصيل</a>
                        </div>
                    @endforeach

                    {{-- Overdue / due installments --}}
                    @foreach ($dueTodayInstallments as $installment)
                        @php
                            $memberName = $installment->loan->membership->member->full_name ?? 'عضو';
                            $daysLate = (int) \Carbon\Carbon::parse($installment->due_date)->diffInDays(now());
                        @endphp
                        <div class="flex justify-between items-center py-5">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#D92D20]"></iconify-icon>
                                <div>
                                    <h3 class="text-[#021219] text-sm font-medium">قسط قرض متأخر - {{ $memberName }}</h3>
                                    <p class="text-[#6D6D6D] text-sm font-normal">
                                        @if ($daysLate > 0)
                                            متأخر منذ {{ $daysLate }} {{ $daysLate == 1 ? 'يوم' : 'ايام' }}
                                        @else
                                            مستحق اليوم
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('loans.show', $installment->loan_id) }}"
                                class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3 hover:bg-[#0e3560] transition-colors">عرض
                                التفاصيل</a>
                        </div>
                    @endforeach

                    {{-- Pending claims --}}
                    @foreach ($pendingClaims as $claim)
                        @php
                            $claimMemberName = $claim->membership->member->full_name ?? 'عضو';
                            $claimTypes = \App\Models\Services\Claim::CLAIM_TYPES;
                        @endphp
                        <div class="flex justify-between items-center py-5">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#D4AF37]"></iconify-icon>
                                <div>
                                    <h3 class="text-[#021219] text-sm font-medium">طلب إعانة تحت المراجعة -
                                        {{ $claimMemberName }}</h3>
                                    <p class="text-[#6D6D6D] text-sm font-normal">في انتظار المراجعة</p>
                                </div>
                            </div>
                            <a href="{{ route('claims.show', $claim->id) }}"
                                class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3 hover:bg-[#0e3560] transition-colors">عرض
                                التفاصيل</a>
                        </div>
                    @endforeach

                    {{-- Members with missing documents --}}
                    @foreach ($membersWithMissingDocs as $member)
                        <div class="flex justify-between items-center py-5">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#F79009]"></iconify-icon>
                                <div>
                                    <h3 class="text-[#021219] text-sm font-medium">مستند ناقص - {{ $member->full_name }}
                                    </h3>
                                    <p class="text-[#6D6D6D] text-sm font-normal">مطلوب رفع خطاب الأجر الأساسي</p>
                                </div>
                            </div>
                            <a href="{{ route('members.show', ['member' => $member->id, 'tab' => 'claims']) }}"
                                class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3 hover:bg-[#0e3560] transition-colors">عرض
                                التفاصيل</a>
                        </div>
                    @endforeach

                    {{-- Empty state --}}
                    @if ($totalTasksCount == 0)
                        <div class="flex justify-center py-5">
                            <p class="text-[#6D6D6D] text-sm font-normal">لا توجد مهام مطلوبة اليوم</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-span-1">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('members.create') }}">
                        <div
                            class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                            <iconify-icon icon="mdi:account-multiple-plus" class="text-5xl text-[#124375]"></iconify-icon>
                            <h3 class="text-base font-medium text-[#124375]">تسجيل عضو جديد</h3>
                        </div>
                    </a>
                    <a href="{{ route('subscriptions.create') }}">
                        <div
                            class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                            <iconify-icon icon="material-symbols:list-alt-check-rounded"
                                class="text-5xl text-[#124375]"></iconify-icon>
                            <h3 class="text-base font-medium text-[#124375]">تسجيل سداد إشتراك</h3>
                        </div>
                    </a>
                    <a href="{{ route('loans.index') }}">
                        <div
                            class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                            <iconify-icon icon="ion:cash" class="text-5xl text-[#124375]"></iconify-icon>
                            <h3 class="text-base font-medium text-[#124375]">تسجيل سداد قسط</h3>
                        </div>
                    </a>
                    <a href="{{ route('claims.index') }}">
                        <div
                            class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                            <iconify-icon icon="mdi:account-file" class="text-5xl text-[#124375]"></iconify-icon>
                            <h3 class="text-base font-medium text-[#124375]">إنشاء مطالبة</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- end tasks -->

        <!-- start table -->
        <section>
            <div class="flex items-center gap-2 py-3">
                <iconify-icon class="text-[#E6B800]" icon="tdesign:error-triangle-filled" width="24"
                    height="24"></iconify-icon>
                <h3 class="text-base font-medium text-[#000000]">
                    تنبيهات التأخيرات العاجلة
                </h3>
            </div>
            <div class="rounded-2xl overflow-hidden  surface-shadow">
                <table class="w-full">
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D]">اسم العضو</th>
                        <th class="py-3 border-l border-[#6D6D6D]">رقم العضوية</th>
                        <th class="py-3 border-l border-[#6D6D6D]">المبلغ المستحق</th>
                        <th class="py-3 border-l border-[#6D6D6D]">مدة التأخير</th>
                    </tr>

                    @forelse ($dueTodayInstallments as $installment)
                        <tr class="text-center even:bg-[#F4F7F9]">
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ $installment->loan->membership->member->full_name }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ $installment->loan->membership->membership_number }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ $installment->loan->installment_amount }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ intval("\Carbon\Carbon::parse($installment->due_date)->diffInDays(now())") }} يومًا
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="6" class="py-6 text-gray-500">
                                <img class="mx-auto w-[30%]" src="{{ asset('IMGs/Dashboard-no-members.png') }}"
                                    alt="ممتاز! لا يوجد أعضاء متأخرين يستوجب إنذارهم حالياً.">
                            </td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </section>
    </main>
    </div>
    <script src="{{ asset('js/Dashboard.js') }}"></script>
    <!-- end table -->
@endsection
