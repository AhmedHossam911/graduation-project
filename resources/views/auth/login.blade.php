@extends('layouts.auth')
{{-- 
    Login View:
    The main authentication gateway for all system users (Admins, Employees, Members).
    Uses National ID and Password.
--}}
@section('title', 'تسجيل الدخول')
@include('partials.flash')
@section('content')
    <h2 class="text-[#333] text-lg font-semibold mb-2.5">تسجيل دخول</h2>
    
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-5 text-right">
            <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('national_id') ? 'border-red-500' : 'border-[#193e6a]' }}">
                <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <input type="text" name="national_id" value="{{ old('national_id') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="الرقم القومي" required>
            </div>
        </div>

        <div class="mb-5 text-right">
            <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('password') ? 'border-red-500' : 'border-[#193e6a]' }}">
                <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" name="password" id="password" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="كلمة المرور" required>
                <div class="w-[40px] flex justify-center items-center text-[#888] cursor-pointer bg-transparent ml-1 hover:text-[#193e6a]" onclick="togglePassword('password', this)">
                    <i class="fa-regular fa-eye"></i>
                </div>
            </div>
        </div>

        <div class="flex justify-end -mt-2.5 mb-6 text-sm text-left">
            <a href="{{ route('password.request') }}" class="text-[#193e6a] no-underline font-semibold transition-colors duration-200 hover:text-[#27568f]">هل نسيت كلمة المرور ؟</a>
        </div>

        <button type="submit" class="bg-[#193e6a] text-white border-none rounded-lg w-full h-[50px] text-lg font-semibold cursor-pointer transition-all duration-300 mt-2.5 hover:bg-[#27568f] hover:shadow-[0_4px_15px_rgba(25,62,106,0.3)] flex items-center justify-center gap-2">تسجيل</button>
    </form>

    <div class="mt-6 text-sm text-[#666]">
        ليس لديك حساب؟ <a href="{{ route('register') }}" class="text-[#193e6a] no-underline font-semibold hover:underline">إنشاء حساب جديد</a>
    </div>
@endsection

@section('scripts')
<script>
    function togglePassword(inputId, iconElement) {
        var input = document.getElementById(inputId);
        var icon = iconElement.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>
@endsection
