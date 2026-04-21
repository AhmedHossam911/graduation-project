@extends('layouts.app')
@section('title', 'تغيير كلمة المرور')
@section('content')
    <style src="{{ asset('assets/css/modal.css') }}"></style>
    <div class="modal surface-shadow rounded-2xl max-w-lg mx-auto bg-[#F4F7F9] px-4 pb-10 pt-5">
        <button class="close-btn text-[#124375] surface-shadow flex justify-center items-center p-2 rounded bg-[#F4F7F9] ">
            <iconify-icon icon="mingcute:close-fill" class="text-xl"></iconify-icon>
        </button>
        <div class="modal-header text-center">
            <h2 class="text-xl font-semibold text-[#124375] py-5 ">
                تغيير كلمة المرور
            </h2>
        </div>
        <form class="px-7">
            <div class="modal-body flex flex-col gap-5 ">
                <p class="px-2 text-[#021219] text-sm font-medium ">يرجى إدخال كلمة المرور الحالية ثم تعيين كلمة مرور جديدة.
                </p>
                <div class="input-group relative">
                    <iconify-icon icon="mingcute:lock-fill"
                        class="absolute right-0 bg-[#124375] text-white h-full flex items-center text-xl px-3 rounded-s-xl"></iconify-icon>
                    <input type="password" placeholder="كلمة المرور الحالية"
                        class="input-field focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition bg-[#F4F7F9] border-2 border-[#124375] outline-none w-full rounded-xl text-center py-2">
                    <iconify-icon icon="solar:eye-outline"
                        class="show-btn cursor-pointer absolute left-4 top-[50%] translate-y-[-50%] text-[#A8A8A8] text-xl"></iconify-icon>
                </div>
                <div class="new-pass space-y-2">
                    <div class="input-group relative">
                        <iconify-icon icon="mingcute:lock-fill"
                            class="absolute right-0 bg-[#124375] text-white h-full flex items-center text-xl px-3 rounded-s-xl"></iconify-icon>
                        <input type="password" placeholder="كلمة المرور الجديدة"
                            class="input-field focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition bg-[#F4F7F9] border-2 border-[#124375] outline-none w-full rounded-xl text-center py-2">
                        <iconify-icon icon="solar:eye-outline"
                            class="show-btn cursor-pointer absolute left-4 top-[50%] translate-y-[-50%] text-[#A8A8A8] text-xl"></iconify-icon>
                    </div>
                    <p class="px-2 text-xs text-[#124375]">يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل وتتضمن أحرفًا
                        وأرقامًا.</p>
                </div>
                <div class="confirm-pass space-y-2">
                    <div class="input-group relative">
                        <iconify-icon icon="mingcute:lock-fill"
                            class="absolute right-0 bg-[#124375] text-white h-full flex items-center text-xl px-3 rounded-s-xl"></iconify-icon>
                        <input type="password" placeholder="تأكيد كلمة المرور "
                            class="input-field focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition bg-[#F4F7F9] border-2 border-[#124375] outline-none w-full rounded-xl text-center py-2">
                        <iconify-icon icon="solar:eye-outline"
                            class="show-btn cursor-pointer absolute left-4 top-[50%] translate-y-[-50%] text-[#A8A8A8] text-xl"></iconify-icon>
                    </div>
                    <p class="px-2 text-xs text-[#124375]">يجب أن تكون كلمتا المرور متطابقتين.</p>
                </div>
                <button
                    class="text-white surface-shadow bg-[#124375] rounded-xl py-2 hover:bg-[#0e3560] transition-colors">تحديث
                    كلمة المرور</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('assets/js/Modal.js') }}"></script>
@endsection
