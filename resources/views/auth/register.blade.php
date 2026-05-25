@extends('layouts.auth')
@section('title', 'إنشاء حساب جديد')
@section('card-width', 'max-w-[800px]')
@include('partials.flash')

@section('content')
    <h2 class="text-[#333] text-lg font-semibold mb-2.5">إنشاء حساب جديد</h2>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="flex flex-col md:flex-row gap-0 md:gap-6">
            <div class="flex-1 flex flex-col">
                <div class="mb-5 text-right">
                    <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('name') ? 'border-red-500' : 'border-[#193e6a]' }}">
                        <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="الأسم كامل" required>
                    </div>
                </div>

                <div class="mb-5 text-right">
                    <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('email') ? 'border-red-500' : 'border-[#193e6a]' }}">
                        <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="البريد الإلكتروني" required>
                    </div>
                </div>

                <div class="mb-5 text-right">
                    <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('national_id') ? 'border-red-500' : 'border-[#193e6a]' }}">
                        <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <input type="text" name="national_id" value="{{ old('national_id') }}" minlength="14" maxlength="14" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="الرقم القومي" required>
                    </div>
                </div>

                <div class="mb-5 text-right">
                    <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('phone') ? 'border-red-500' : 'border-[#193e6a]' }}">
                        <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="رقم التليفون" required>
                    </div>
                </div>
            </div>

            <div class="flex-1 flex flex-col">
                <div class="mb-5 text-right">
                    <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('workplace') ? 'border-red-500' : 'border-[#193e6a]' }}">
                        <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <input type="text" name="workplace" value="{{ old('workplace') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="جهة العمل" required>
                    </div>
                </div>

                <div class="mb-5 text-right">
                    <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('job_title') ? 'border-red-500' : 'border-[#193e6a]' }}">
                        <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="الوظيفة" required>
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
            </div>
        </div>
        <div class="text-[#666] text-[10px] text-right -mt-4 mb-4 font-medium">
            يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل، بما في ذلك حرف كبير واحد، حرف صغير واحد، رقم واحد، ورمز خاص (@#$%^&*).
        </div>

        <button type="submit" class="bg-[#193e6a] text-white border-none rounded-lg w-full h-[50px] text-lg font-semibold cursor-pointer transition-all duration-300 mt-2.5 hover:bg-[#27568f] hover:shadow-[0_4px_15px_rgba(25,62,106,0.3)] flex items-center justify-center gap-2">إنشاء الحساب</button>
    </form>

    <div class="mt-6 text-sm text-[#666]">
        لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="text-[#193e6a] no-underline font-semibold hover:underline">تسجيل دخول</a>
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
