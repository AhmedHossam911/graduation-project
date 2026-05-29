@extends('layouts.member')

@section('title', 'الصفحة الرئيسية')

@section('content')

    <div class="flex">

        <link rel="stylesheet" href="{{ asset('css/member/guestHome.css') }}">

        <!-- start main -->
        <main class="flex-1">
            <div class="py-7 px-7 space-y-9">
                <!-- start header main -->
                <div class="flex flex-col gap-2 bg-[#F4F7F9] surface-shadow rounded-[16px] py-4 px-4">
                    <h2 class="text-[28px] text-[#124375] font-semibold">مرحباً بك في صندوق الزمالة</h2>
                    <p class="text-[16px] text-[#6D6D6D] font-medium">يكمنك من خلال هذه اللوحة متابعة حالة عضويتك وتقديم
                        الطلبات المختلفة.</p>
                </div>
                <!-- end header main -->

                <!-- start cards -->
                <div class=" grid grid-cols-3 gap-5">
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
                            <p class="text-4xl font-extrabold">{{ $joinDate }}</p>
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
                <div class="space-y-4 bg-[#F4F7F9] surface-shadow rounded-[16px] py-7 px-5">
                    <h2 class="text-[#124375] text-[28px] font-semibold">
                        أخر الطلبات
                    </h2>
                    <div class="flex flex-col justify-center py-3 items-center gap-5">
                        <img src="{{ asset('IMGs/no-requests.png') }}">
                        <p class="text-[16px] font-medium text-[#6D6D6D]">لم تقم بتقديم أي طلبات بعد.</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-7">
                    <a href="{{ route('member.membership.create') }}"
                        class="flex flex-col gap-2 py-5 text-[#16A34A] items-center bg-[#F4F7F9] surface-shadow rounded-[16px] border-s-8 border-[#16A34A]">
                        <div>
                            <iconify-icon icon="material-symbols:list-alt-rounded"
                                class=" text-4xl bg-[#DCFCE7] rounded-lg px-3 py-2"></iconify-icon>
                        </div>
                        <p class="text-[16px] font-medium">تقديم استمارة عضوية</p>
                    </a>
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

    <script src="{{ asset('JS/member/guestHome.js') }}"></script>
@endsection
