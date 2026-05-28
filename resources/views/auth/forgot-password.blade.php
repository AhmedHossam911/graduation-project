@extends('layouts.auth')
{{-- 
    Forgot Password View:
    Allows users to request a password reset link/temporary password using their National ID and Email.
--}}
@section('title', 'إعادة تعيين كلمة المرور')
@include('partials.flash')

@section('content')
    <h2 class="text-[#333] text-lg font-semibold mb-2.5">إعادة تعيين كلمة المرور</h2>
    <p class="text-[#666] text-sm mb-6 leading-relaxed">
        أدخل بياناتك وسيتم إرسال كلمة مرور مؤقتة إلى بريدك الإلكتروني المسجل.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-5 text-right">
            <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('national_id') ? 'border-red-500' : 'border-[#193e6a]' }}">
                <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <input type="text" name="national_id" value="{{ old('national_id') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="الرقم القومي" required>
            </div>
            @if($errors->has('national_id'))
                <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('national_id') }}</span>
            @endif
        </div>

        <div class="mb-5 text-right">
            <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('email') ? 'border-red-500' : 'border-[#193e6a]' }}">
                <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="البريد الإلكتروني" required>
            </div>
            @if($errors->has('email'))
                <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <button type="submit" class="bg-[#193e6a] text-white border-none rounded-lg w-full h-[50px] text-lg font-semibold cursor-pointer transition-all duration-300 mt-2.5 hover:bg-[#27568f] hover:shadow-[0_4px_15px_rgba(25,62,106,0.3)] flex items-center justify-center gap-2">إرسال كلمة المرور المؤقتة</button>
    </form>

    <div class="flex justify-start mt-4 text-sm text-left">
        <a href="{{ route('login') }}" class="text-[#193e6a] no-underline font-semibold transition-colors duration-200 hover:text-[#27568f]">الرجوع لتسجيل الدخول</a>
    </div>
@endsection
