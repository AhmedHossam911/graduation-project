@extends('layouts.app')
@section('title', 'الإشعارات')
@section('content')
    <style src="{{ asset('assets/css/notification.css') }}"></style>
    <div class="heading flex justify-center py-7">
        <h1 class="text-xl font-semibold text-[#124375]">
            الإشعارات
        </h1>
    </div>

    <div class="tabs flex justify-between px-7">
        <div class="flex gap-3">
            <button class="bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow">الكل
                <span>()</span></button>
            <button class="bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow">غير مقروء
                <span>()</span></button>
            <button class="bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow">مقروء
                <span>()</span></button>
        </div>
        <div class="flex gap-3">
            <div class="relative">
                <button
                    class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow flex gap-3">الفترة
                    : <span class="text-[#021219]">كل الفترات</span><span class="flex items-center"><iconify-icon
                            icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                <div
                    class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl surface-shadow max-w-fit">
                    <button class=" surface-shadow py-2 px-5 rounded-xl text-base ">اليوم</button>
                    <button class=" surface-shadow py-2 px-5 rounded-xl text-base ">هذا الأسبوع</button>
                    <button class=" surface-shadow py-2 px-5 rounded-xl text-base ">هذا الشهر</button>
                    <button class=" surface-shadow py-2 px-5 rounded-xl text-base ">أخر 30 يوم</button>
                    <button class=" surface-shadow py-2 px-5 rounded-xl text-base ">كل الفترات</button>
                </div>
            </div>
            <div class="relative">
                <button
                    class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow flex gap-3">الترتيب
                    : <span class="text-[#021219]">الأحدث أولاً</span><span class="flex items-center"><iconify-icon
                            icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                <div
                    class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl surface-shadow max-w-fit">
                    <button class="surface-shadow py-2 px-5 rounded-xl text-base ">الأحدث اولاً</button>
                    <button class="surface-shadow py-2 px-5 rounded-xl text-base ">الأقدم اولاً</button>
                </div>
            </div>
        </div>
        <div>
            <button
                class="flex items-center bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow">تحديد
                الكل كمقروء <iconify-icon icon="material-symbols:check" class="text-2xl"></iconify-icon></button>
        </div>
    </div>
    <hr class="m-5 border border-[#A8A8A8]">

    <div class="notifications px-7 space-y-5">
        <div class="notification flex justify-between bg-[#EAF5FF] rounded-xl px-7 py-4 surface-shadow">
            <p class="text-[#6D6D6D]">العضو: <span class="text-[#021219]"></span></p>
            <p class="text-[#6D6D6D]">رقم العضوية: <span class="text-[#021219]"></span></p>
            <p class="text-[#6D6D6D]">التاريخ : <span class="text-[#021219]"></span></p>
            <p class="text-[#6D6D6D]">الوقت : <span class="text-[#021219]"></span></p>
            <p class="text-[#021219]">تمت الموافقة علي طلب القرض</p>
        </div>
        <div class="notifcation flex justify-between bg-[#F4F7F9] rounded-xl px-7 py-4 surface-shadow">
            <p class="text-[#6D6D6D]">العضو: <span class="text-[#021219]"></span></p>
            <p class="text-[#6D6D6D]">رقم العضوية: <span class="text-[#021219]"></span></p>
            <p class="text-[#6D6D6D]">التاريخ : <span class="text-[#021219]"></span></p>
            <p class="text-[#6D6D6D]">الوقت : <span class="text-[#021219]"></span></p>
            <p class="text-[#021219]">تمت الموافقة علي طلب القرض</p>
        </div>
    </div>
    <script src="{{ asset('assets/js/notification.js') }}"></script>
@endsection
