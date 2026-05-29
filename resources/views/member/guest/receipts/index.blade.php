@extends('layouts.member')

@section('title', 'الإيصالات')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/member/receipts.css') }}">

    <div class="flex flex-col justify-center items-center min-h-screen">
        <div class="flex flex-col gap-12">
            <div class="flex justify-center">
                <img src="../IMGs/guest.png" class="w-[220px]">
            </div>
            <div class="max-w-xl space-y-7">
                <div>
                    <h1 class="text-[#124375] text-[28px] font-semibold">
                        العضوية مطلوبة
                    </h1>
                </div>
                <div>
                    <p class="text-[#021219] text-[16px] font-medium">
                        لفتح الإيصالات من "صندوق الزمالة"، يلزم وجود عضوية نشطة. الانضمام يدعم المجتمع ويمنحك الوصول لمزايا
                        مالية حصرية.
                    </p>
                </div>
                <div>
                    <button
                        class="hover:bg-[#0e3560] transition-colors flex items-center gap-3 rounded-[12px] bg-[#124375] text-[#F4F7F9] w-full justify-center py-2">
                        <iconify-icon icon="ic:round-plus" class="text-3xl mt-1"></iconify-icon>
                        تقديم طلب عضوية
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('JS/member/receipts.js') }}"></script>
@endsection
