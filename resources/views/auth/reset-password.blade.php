@extends('layouts.auth')
{{-- 
    Reset Password View:
    The final step in password recovery where the user enters a new secure password.
--}}
@section('title', 'تعيين كلمة مرور جديدة')
@include('partials.flash')
@section('content')
    <h2 class="text-[#333] text-lg font-semibold mb-2.5">تعيين كلمة مرور جديدة</h2>

    <form method="POST" action="{{ route('password.reset.post') }}">
        @csrf
        
        <div class="mb-5 text-right">
            <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('password') ? 'border-red-500' : 'border-[#193e6a]' }}">
                <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" name="password" id="password" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="كلمة المرور الجديدة" required>
                <div class="w-[40px] flex justify-center items-center text-[#888] cursor-pointer bg-transparent ml-1 hover:text-[#193e6a]" onclick="togglePassword('password', this)">
                    <i class="fa-regular fa-eye"></i>
                </div>
            </div>
            @if($errors->has('password'))
                <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="mb-5 text-right">
            <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] border-[#193e6a]">
                <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" name="password_confirmation" id="password_confirmation" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="تأكيد كلمة المرور" required>
                <div class="w-[40px] flex justify-center items-center text-[#888] cursor-pointer bg-transparent ml-1 hover:text-[#193e6a]" onclick="togglePassword('password_confirmation', this)">
                    <i class="fa-regular fa-eye"></i>
                </div>
            </div>
        </div>

        <button type="submit" class="bg-[#193e6a] text-white border-none rounded-lg w-full h-[50px] text-lg font-semibold cursor-pointer transition-all duration-300 mt-2.5 hover:bg-[#27568f] hover:shadow-[0_4px_15px_rgba(25,62,106,0.3)] flex items-center justify-center gap-2">حفظ كلمة المرور والدخول</button>
    </form>
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
