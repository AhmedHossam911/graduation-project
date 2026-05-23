@extends('layouts.app')

@section('title', 'لوحة تحكم العضو')

@section('content')
<main class="flex-1 py-5 px-3">
    <div class="flex flex-col gap-2">
        <h2 class="text-[#021219] text-xl font-semibold"> مرحباً ، <span>{{ Auth::user()->name ?? 'عضو' }}</span></h2>
        <p class="text-[#6D6D6D] text-base font-normal">
            نظام إدارة الصندوق – لوحة تحكم العضو
        </p>
    </div>

    @if(!$member)
    <div class="py-10 flex justify-center mt-10">
        <div class="bg-white border-2 border-[#124375] p-10 rounded-3xl text-center shadow-lg max-w-2xl">
            <iconify-icon icon="mdi:card-account-details-star" class="text-7xl text-[#124375] mb-5"></iconify-icon>
            <h3 class="text-3xl font-bold text-[#124375] mb-4">أهلاً بك في صندوق الزمالة</h3>
            <p class="text-[#6D6D6D] text-lg mb-8 leading-relaxed">
                أنت الآن مسجل في النظام، ولكنك لم تقدم طلب العضوية بعد. <br>
                يرجى البدء في تقديم طلب العضوية لرفع مستنداتك ودفع رسوم الاشتراك وتفعيل حسابك بالكامل.
            </p>
            <a href="{{ route('member.membership.create') }}" class="inline-flex items-center gap-2 bg-[#124375] text-white text-xl font-medium px-8 py-4 rounded-xl hover:bg-[#0e3560] transition-colors shadow-md hover:shadow-lg">
                <iconify-icon icon="mdi:file-document-edit" class="text-2xl"></iconify-icon>
                طلب اشتراك عضوية جديد
            </a>
        </div>
    </div>
    @else
    
    @if($membership->status == 'pending')
    <div class="py-10 flex justify-center mt-10 mb-5">
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-8 py-6 rounded-2xl text-center shadow-sm w-full max-w-3xl">
            <iconify-icon icon="mdi:clock-outline" class="text-5xl mb-3 text-yellow-500 block mx-auto"></iconify-icon>
            <h3 class="text-2xl font-bold mb-2">طلب العضوية قيد المراجعة</h3>
            <p class="text-lg">لقد قمت بتقديم طلب العضوية وهو الآن قيد المراجعة من قبل الإدارة.</p>
        </div>
    </div>
    @endif

    <!-- start cards -->
    <div class="py-4 grid grid-cols-4 gap-4 mt-5">
        <div
            class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
            <div>
                <iconify-icon icon="mdi:account-star"
                    class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-2xl font-extrabold">{{ $membership->status === 'active' ? 'نشط' : ($membership->status === 'pending' ? 'قيد المراجعة' : 'معلق') }}</p>
                <p class="text-sm font-medium">حالة العضوية</p>
            </div>
        </div>
        <div
            class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
            <div>
                <iconify-icon icon="mdi:cash-multiple"
                    class="surface-shadow text-4xl text-[#D4AF37] bg-[#FFFCEF] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-2xl font-extrabold">{{ $totalPaidSubscriptions }} ج.م</p>
                <p class="text-sm font-medium">إجمالي الاشتراكات المسددة</p>
            </div>
        </div>
        <div
            class="surface-shadow flex items-center justify-center gap-4 bg-[#124375] rounded-xl px-4 py-4 border-s-8 border-[#EEF7FF]">
            <div>
                <iconify-icon icon="material-symbols:account-balance-wallet"
                    class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#F4F7F9] gap-2">
                <p class="text-2xl font-extrabold">{{ $activeLoansCount }}</p>
                <p class="text-sm font-medium">القروض النشطة</p>
            </div>
        </div>
        <div
            class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
            <div>
                <iconify-icon icon="mdi:file-document-outline"
                    class="surface-shadow text-4xl text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-2xl font-extrabold">{{ $pendingClaimsCount }}</p>
                <p class="text-sm font-medium">طلبات الإعانة المعلقة</p>
            </div>
        </div>
    </div>
    <!-- end cards -->

    <!-- start tasks -->
    <div class="py-5 grid grid-cols-3 gap-7">
        <div class="col-span-2 space-y-5">
            <div class="flex items-center gap-2">
                <iconify-icon icon="material-symbols:edit-notifications-rounded" class="text-2xl"></iconify-icon>
                <h2 class="text-base font-medium">المهام و التنبيهات
                </h2>
            </div>
            <div class="py-2 surface-shadow rounded-2xl py-4 px-5 divide-y-2 divide-[#6D6D6D]">
                
                {{-- Overdue installments --}}
                @if($overdueInstallmentsCount > 0)
                    <div class="flex justify-between items-center py-5">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#D92D20]"></iconify-icon>
                            <div>
                                <h3 class="text-[#021219] text-sm font-medium">يوجد لديك متأخرات</h3>
                                <p class="text-[#D92D20] text-sm font-bold">
                                    لديك عدد {{ $overdueInstallmentsCount }} أقساط متأخرة يرجى سدادها.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Next Installment --}}
                @if($nextInstallment && \Carbon\Carbon::parse($nextInstallment->due_date)->startOfDay()->isSameDay(\Carbon\Carbon::today()))
                    <div class="flex justify-between items-center py-5">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#F79009]"></iconify-icon>
                            <div>
                                <h3 class="text-[#021219] text-sm font-medium">قسط مستحق اليوم</h3>
                                <p class="text-[#6D6D6D] text-sm font-normal">
                                    قسط قرض مستحق اليوم بقيمة {{ $nextInstallment->loan->installment_amount }} ج.م
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Claims --}}
                @foreach ($claims as $claim)
                    <div class="flex justify-between items-center py-5">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#124375]"></iconify-icon>
                            <div>
                                <h3 class="text-[#021219] text-sm font-medium">طلب إعانة: {{ \App\Models\Services\Claim::CLAIM_TYPES[$claim->type] ?? $claim->type }}</h3>
                                <p class="text-[#6D6D6D] text-sm font-normal">
                                    الحالة: 
                                    @if($claim->status === 'pending') قيد المراجعة 
                                    @elseif($claim->status === 'approved') تمت الموافقة 
                                    @elseif($claim->status === 'rejected') مرفوض 
                                    @else {{ $claim->status }} @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Empty state --}}
                @if ($overdueInstallmentsCount == 0 && !$nextInstallment && $claims->isEmpty())
                    <div class="flex justify-center py-5">
                        <p class="text-[#6D6D6D] text-sm font-normal">لا توجد تنبيهات حالية</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-span-1">
            <div class="grid grid-cols-2 gap-4">
                <a href="#">
                    <div
                        class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                        <iconify-icon icon="mdi:account-file" class="text-5xl text-[#124375]"></iconify-icon>
                        <h3 class="text-base font-medium text-[#124375]">تقديم مطالبة جديدة</h3>
                    </div>
                </a>
                <a href="#">
                    <div
                        class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                        <iconify-icon icon="mdi:cash-plus" class="text-5xl text-[#124375]"></iconify-icon>
                        <h3 class="text-base font-medium text-[#124375]">طلب قرض جديد</h3>
                    </div>
                </a>
                <a href="#">
                    <div
                        class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                        <iconify-icon icon="mdi:file-document-edit" class="text-5xl text-[#124375]"></iconify-icon>
                        <h3 class="text-base font-medium text-[#124375]">استعلام عن الأقساط</h3>
                    </div>
                </a>
                <a href="#">
                    <div
                        class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                        <iconify-icon icon="mdi:cash-multiple" class="text-5xl text-[#124375]"></iconify-icon>
                        <h3 class="text-base font-medium text-[#124375]">سجل الاشتراكات</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- end tasks -->
    
    @endif
</main>
@endsection
