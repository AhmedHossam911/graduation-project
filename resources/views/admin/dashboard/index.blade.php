@extends('layouts.app')

@section('title', 'لوحة تحكم الإدارة')

@section('content')
    <div class="py-7 px-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-xl text-[#124375]  font-semibold">
                مرحبا، <span>{{ Auth::user()->name ?? 'مدير النظام' }}</span>
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                نظام إدارة الصندوق – لوحة الإدارة
            </p>
        </div>
    </div>

    <!-- start cards -->
    <div class="py-4 grid grid-cols-4 gap-4 px-12">
        <div
            class="navy-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
            <div>
                <iconify-icon icon="mdi:account-group"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#124375] bg-[#EEF7FF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">2,500</p>
                <p class="text-sm font-medium">عدد الأعضاء النشطين</p>
            </div>
        </div>
        <div
            class="yellow-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
            <div>
                <iconify-icon icon="fluent:money-16-filled"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#D4AF37] bg-[#FFFCEF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">1,500,000</p>
                <p class="text-sm font-medium">إجمالي القروض الممنوحة</p>
            </div>
        </div>
        <div
            class="bg-[#124375] navy-shadow text-[#F4F7F9] flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#EEF7FF]">
            <div>
                <iconify-icon icon="fa7-solid:money-bill-wave"
                    class="navy-shadow text-[40px] text-[#124375]  bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center gap-2">
                <p class="text-4xl font-extrabold">15,000,000</p>
                <p class="text-sm font-medium">إجمالي رصيد الصندوق</p>
            </div>
        </div>
        <div
            class="red-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
            <div>
                <iconify-icon icon="tabler:clipboard-list-filled"
                    class="navy-shadow text-[40px] text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">5</p>
                <p class="text-sm font-medium">مطالبات بانتظار الأعتماد</p>
            </div>
        </div>
    </div>
    <!-- end cards -->

    <form class="px-12 flex items-center justify-between gap-5 py-3">
        <div class="relative flex-1">
            <input type="search"
                placeholder="الاسم أو رقم العضوية أو رقم الحركة أو رقم القرض أو رقم المطالبة أو رقم الإيصال"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
        </div>
        <div class="relative min-w-[150px]">
            <button type="button"
                class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">التاريخ
                :<span class="text-[#021219] ">2026</span><span class="flex items-center"><iconify-icon icon="uil:calender"
                        class="text-xl"></iconify-icon></span></button>
            <div
                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">2026</button>
                <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">2025</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">2024</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">2023</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">2022</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">2021</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">2020</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">2019</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">2018</button>
                <button type="button" class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">2017</button>
            </div>
        </div>
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
            </button>
        </div>
    </form>
@endsection
