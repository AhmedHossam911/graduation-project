@extends('layouts.member')

@section('title', 'الصفحة الرئيسية')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/memberhome.css') }}">

    <div class="flex">

        <!-- start main -->
        <main class="flex-1">
            <div class="py-7 px-4 space-y-9">
                <!-- start header main -->
                <div class="flex flex-col gap-2 bg-[#F4F7F9] surface-shadow rounded-[16px] py-4 px-4">
                    <h2 class="text-[28px] text-[#124375] font-semibold">مرحباً بك في صندوق الزمالة</h2>
                    <p class="text-[16px] text-[#6D6D6D] font-medium">يكمنك من خلال هذه اللوحة متابعة حالة عضويتك وتقديم
                        الطلبات المختلفة.</p>
                </div>
                <!-- end header main -->

                <!-- start cards -->
                <div class=" grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="surface-shadow flex items-center  gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4">
                        <div>
                            <iconify-icon icon="{{ $statusIcon }}"
                                class=" text-4xl {{ $statusColor }} rounded-lg px-3 py-3"></iconify-icon>
                        </div>
                        <div class="flex flex-col items-center text-[#124375] gap-2">
                            <p class="text-[16px] font-medium text-[#6D6D6D]">حالة العضوية</p>
                            <p class="text-4xl font-extrabold">{{ $statusText }}</p>
                        </div>
                    </div>
                    <div class="surface-shadow flex items-center  gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4">
                        <div>
                            <iconify-icon icon="sidekickicons:arrow-path-clock-16-solid"
                                class=" text-4xl text-[#175CD3] bg-[#D2EBFF] rounded-lg px-3 py-3"></iconify-icon>
                        </div>
                        <div class="flex flex-col items-center text-[#124375] gap-2">
                            <p class="text-[16px] font-medium text-[#6D6D6D]">تاريخ الانضمام</p>
                            <p class="text-4xl font-extrabold">
                                {{ $user->created_at ? $user->created_at->format('Y-m-d') : '---' }} </p>
                        </div>
                    </div>
                    <div class="surface-shadow flex items-center  gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4">
                        <div>
                            <iconify-icon icon="f7:exclamationmark-shield-fill"
                                class=" text-4xl text-[#E11D48] bg-[#FFE4E6] rounded-lg px-3 py-3"></iconify-icon>
                        </div>
                        <div class="flex flex-col items-center text-[#124375] gap-2">
                            <p class="text-[16px] font-medium text-[#6D6D6D]">المطالبات السابقة</p>
                            <p class="text-4xl font-extrabold">{{ $claimsCount }}</p>
                        </div>
                    </div>
                </div>
                <!-- end cards -->

                <div class=" grid grid-cols-1 md:grid-cols-5 gap-7">
                    <div class="md:col-span-2 space-y-7 bg-[#F4F7F9] surface-shadow rounded-[16px] py-7 px-5">
                        <div class="flex items-center gap-4">
                            <div>
                                <iconify-icon icon="uil:calender"
                                    class=" text-3xl text-[#175CD3] bg-[#D2EBFF] rounded-lg px-3 py-3"></iconify-icon>
                            </div>
                            <div class="flex flex-col text-[#124375] gap-2">
                                <p class="text-[20px] font-medium">الاشتراكات و الرسوم</p>
                                <p class="text-[16px] font-medium text-[#6D6D6D]">موقف سداد اشتراكات العضوية</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-6 md:gap-12">
                            <div class="flex flex-col gap-5">
                                <p class="text-[#6D6D6D] text-[16px] font-medium"> موقف السداد</p>
                                <div class="flex gap-1 items-center {{ $subscriptionColor }} rounded-[8px] px-4">
                                    <iconify-icon icon="{{ $subscriptionIcon }}" class=" text-lg "></iconify-icon>
                                    <p class=" text-[14px] font-medium">{{ $subscriptionStatus }} (لعام
                                        <span>{{ $subscriptionYear }}</span>)</p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-5">
                                <p class="text-[#6D6D6D] text-[16px] font-medium">قيمة الاشتراك</p>
                                <p class="text-[#021219] text-[20px] font-semibold">{{ $subscriptionFee }} ج.م</p>
                            </div>
                        </div>
                        <div class=" text-[#124375]">
                            <a href="{{ route('member.receipts.index') }}"
                                class="underline cursor-pointer text-[20px] font-semibold flex items-center gap-2">
                                عرض كل الإيصالات
                                <iconify-icon icon="iconamoon:invoice-fill" class=" text-2xl mt-1"></iconify-icon>
                            </a>
                        </div>
                    </div>
                    <div class="md:col-span-3 space-y-7 bg-[#F4F7F9] surface-shadow rounded-[16px] py-7 px-5">
                        <div class="flex flex-col sm:flex-row justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div>
                                    <iconify-icon icon="fluent:money-16-filled"
                                        class=" text-3xl text-[#F79009] bg-[#FFF7ED] rounded-lg px-3 py-3"></iconify-icon>
                                </div>
                                <div class="flex flex-col text-[#124375] gap-2">
                                    <p class="text-[20px] font-medium">القرض الحالي</p>
                                    <p class="text-[16px] font-medium text-[#6D6D6D]">متابعة سداد أقساط قرضك الفعال</p>
                                </div>
                            </div>
                            @if ($activeLoan)
                                <div
                                    class="flex h-fit gap-1 items-center bg-[#FFEDD5] border border-[#EA580C] text-[#EA580C] rounded-[8px] px-4">
                                    <iconify-icon icon="material-symbols:info-rounded" class=" text-lg "></iconify-icon>
                                    <p class=" text-[14px] font-medium">قيد السداد</p>
                                </div>
                            @endif
                        </div>
                        @if ($activeLoan)
                            @php
                                $paidAmount = $activeLoan->installments()->where('status', 'paid')->sum('amount');
                                $remainingAmount = $activeLoan->total_amount - $paidAmount;
                                $progress =
                                    $activeLoan->total_amount > 0 ? ($paidAmount / $activeLoan->total_amount) * 100 : 0;
                                $paidMonths = $activeLoan->installments()->where('status', 'paid')->count();
                            @endphp
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div>
                                    <p class="text-[#6D6D6D] text-[16px] font-medium">الأقساط المسددة : <span
                                            class="text-[20px] text-[#021219]">{{ $paidAmount }} ج.م</span></p>
                                </div>
                                <div>
                                    <p class="text-[#6D6D6D] text-[16px] font-medium">المتبقي : <span
                                            class="text-[20px] text-[#021219]">{{ $remainingAmount }} ج.م</span></p>
                                </div>
                            </div>
                            <div class="bg-[#EFEFEF] rounded-[50px] w-full h-4">
                                <span class="bg-[#F79009] rounded-[50px] w-[{{ $progress }}%] h-full block"></span>
                            </div>
                            <div class="flex justify-between bg-[#FFF7ED] py-2 px-2">
                                <div class="flex flex-col gap-3">
                                    <p class="text-[#6D6D6D] text-[16px] font-medium">الأقساط المسددة</p>
                                    <p class="text-[20px] text-[#021219] font-semibold">{{ $paidMonths }} من
                                        {{ $activeLoan->months }} شهر</p>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <p class="text-[#6D6D6D] text-[16px] font-medium">القسط القادم</p>
                                    <p class="text-[20px] text-[#021219] font-semibold">
                                        {{ $activeLoan->installment_amount }} ج.م</p>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-5">
                                <p class="text-[16px] font-medium text-[#6D6D6D]">ليس لديك قرض فعال حالياً</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-4 bg-[#F4F7F9] surface-shadow rounded-[16px] py-7 px-5">
                    <h2 class="text-[#124375] text-[28px] font-semibold">
                        أخر الطلبات
                    </h2>
                    @if (count($lastRequests) > 0)
                        @foreach ($lastRequests as $req)
                            @php
                                $isLoan = class_basename($req) === 'Loan';
                                $title = $isLoan
                                    ? 'طلب قرض شخصي'
                                    : 'طلب مطالبة - ' . ($req->type ?? 'مكافأة نهاية الخدمة');
                                $reqId = $isLoan
                                    ? 'LOAN-' . str_pad($req->id, 3, '0', STR_PAD_LEFT)
                                    : 'CLM-' . str_pad($req->id, 3, '0', STR_PAD_LEFT);
                                $statusColors = [
                                    'pending' => 'bg-[#FFF8E1] border border-[#E6B800] text-[#E6B800]',
                                    'approved' => 'bg-[#F0FFF6] border border-[#019168] text-[#019168]',
                                    'rejected' => 'bg-[#FFE4E6] border border-[#E11D48] text-[#E11D48]',
                                    'active' => 'bg-[#EAF5FF] border border-[#175CD3] text-[#175CD3]',
                                    'completed' => 'bg-[#F0FFF6] border border-[#019168] text-[#019168]',
                                ];
                                $statusIcons = [
                                    'pending' => 'tabler:clock-filled',
                                    'approved' => 'healthicons:yes',
                                    'rejected' => 'material-symbols:cancel-rounded',
                                    'active' => 'mdi:check-decagram',
                                    'completed' => 'healthicons:yes',
                                ];
                                $statusLabels = [
                                    'pending' => 'قيد المراجعة',
                                    'approved' => 'مقبول',
                                    'rejected' => 'مرفوض',
                                    'active' => 'فعال',
                                    'completed' => 'مكتمل',
                                ];
                            @endphp
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex items-start md:items-center gap-4">
                                    <div class="flex items-center">
                                        <iconify-icon icon="{{ $statusIcons[$req->status] ?? 'tabler:clock-filled' }}"
                                            class=" text-2xl text-[#6D6D6D] bg-[#EFEFEF] rounded-full px-3 py-3 "></iconify-icon>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <p class="text-[20px] font-medium text-[#124375]">{{ $title }}</p>
                                        <div
                                            class="flex flex-col sm:flex-row gap-1 sm:gap-2 text-[14px] sm:text-[16px] font-medium text-[#6D6D6D]">
                                            <p>رقم الطلب: </p>
                                            <p>{{ $reqId }}</p>
                                            <p> {{ $req->created_at->format('Y-m-d') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="flex h-fit gap-1 items-center rounded-[8px] px-4 {{ $statusColors[$req->status] ?? '' }}">
                                    <p class=" text-[14px] font-medium">{{ $statusLabels[$req->status] ?? $req->status }}
                                    </p>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <hr class="border border-[#A8A8A8] mt-3">
                            @endif
                        @endforeach
                    @else
                        <p class="text-[#6D6D6D] text-[16px] font-medium text-center py-5">لا توجد طلبات سابقة</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-7">
                    <a href="{{ route('member.loans.index') }}"
                        class="flex flex-col gap-2 py-5 text-[#124375] items-center bg-[#F4F7F9] surface-shadow rounded-[16px] border-s-8 border-[#124375]">
                        <div>
                            <iconify-icon icon="fluent:money-24-filled"
                                class=" text-4xl bg-[#EAF5FF] rounded-lg px-3 py-2"></iconify-icon>
                        </div>
                        <p class="text-[16px] font-medium">طلب قرض جديد</p>
                    </a>
                    <a href="{{ route('member.claims.index') }}"
                        class="flex flex-col gap-2 py-5 text-[#E11D48] items-center bg-[#F4F7F9] surface-shadow rounded-[16px] border-s-8 border-[#E11D48]">
                        <div>
                            <iconify-icon icon="octicon:shield-16"
                                class=" text-4xl bg-[#FFE4E6] rounded-lg px-3 py-2"></iconify-icon>
                        </div>
                        <p class="text-[16px] font-medium">تقديم مطالبة</p>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('JS/member/memberhome.js') }}"></script>

@endsection
