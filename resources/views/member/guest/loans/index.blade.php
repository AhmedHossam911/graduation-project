@extends('layouts.member')

@section('title', 'القروض')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/member/guestLoan.css') }}">

    <div class="flex flex-col justify-center items-center min-h-screen">
        <div class="flex flex-col gap-12">
            <div class="flex justify-center">
                <img src="{{ asset('IMGs/no-requests.png') }}" class="w-[220px]">
            </div>
            <div class="max-w-xl space-y-7">
                <div>
                    <h1 class="text-[#124375] text-[28px] font-semibold">
                        العضوية مطلوبة
                    </h1>
                </div>
                <div>
                    <p class="text-[#021219] text-[16px] font-medium">
                        لفتح تقديم طلب قرض من "صندوق الزمالة"، يلزم وجود عضوية نشطة. الانضمام يدعم المجتمع ويمنحك الوصول
                        لمزايا مالية حصرية.
                    </p>
                </div>
                <div>
                    <a href="{{ route('member.membership.create') }}"
                        class="hover:bg-[#0e3560] transition-colors gap-3 flex items-center rounded-[12px] bg-[#124375] text-[#F4F7F9] w-full justify-center py-2">
                        <iconify-icon icon="ic:round-plus" class="text-3xl mt-1"></iconify-icon>
                        تقديم طلب عضوية
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/member/memberLoan.js') }}"></script>

@endsection
