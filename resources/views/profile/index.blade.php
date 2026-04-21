@extends('layouts.app')
@section('title', 'الملف الشخصي')
@section('content')
    <style src="{{ asset('assets/css/profile.css') }}"></style>
    <div class="profile">
        <div class="profile-header py-7 text-center">
            <h1 class="text-xl font-semibold text-[#124375]">
                الملف الشخصي
            </h1>
        </div>
        <div class="relative profile-body border border-[#124375] max-w-lg mx-auto rounded-2xl bg-[#F4F7F9]">
            <h2 class=" text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                البيانات الشخصية
            </h2>
            <form class=" px-8 py-10 space-y-5">
                <div class="flex flex-col gap-5 gap-7">
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            الأسم كامل
                        </label>
                        <input type="text" placeholder="احمد محمد ابراهيم خليل"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            الرقم الوظيفي
                        </label>
                        <input type="number" placeholder="123456"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            البريد الإلكتروني
                        </label>
                        <input type="email" placeholder="ahmedkhalil@gmail.com"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            المسمي الوظيفي
                        </label>
                        <input type="text" placeholder="موظف إدخال بيانات"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            حالة الحساب
                        </label>
                        <input type="text" placeholder="نشط"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                </div>
                <p class="text-base font-medium text-[#D4AF37] underline px-2">تغيير كلمة المرور</p>
                <button
                    class="flex justify-center items-center gap-3 bg-[#124375] text-white w-full py-3 text-base font-medium rounded-xl hover:bg-[#0e3560] transition-colors">
                    <iconify-icon icon="ic:round-edit" class="text-2xl"></iconify-icon>
                    تعديل بيانات
                </button>
                <p class="text-base text-[#124375] px-2 font-medium">أخر تسجيل دخول :</p>
                <div class="text-base font-medium flex items-center gap-7 px-2">
                    <p>يوم : </p>
                    <p>الساعة :</p>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('assets/js/profile.js') }}"></script>
@endsection
