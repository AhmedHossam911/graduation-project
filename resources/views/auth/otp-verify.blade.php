@extends('layouts.auth')
{{-- 
    OTP Verify View:
    Used during password reset process to verify the OTP code sent to the user's email.
--}}
@section('title', 'التحقق من الرمز')
@include('partials.flash')
@section('content')
    <h2 class="text-[#333] text-lg font-semibold mb-2.5">التحقق من الرمز المكون من 6 أرقام</h2>
    <p class="text-[#666] text-sm mb-6 leading-relaxed">
        أدخل الرمز الذي تم إرساله إلى بريدك الإلكتروني.
    </p>

    <form method="POST" action="{{ route('password.verify.post') }}">
        @csrf
        <div class="mb-5 text-right">
            <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('code') ? 'border-red-500' : 'border-[#193e6a]' }}">
                <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                    <i class="fa-solid fa-key"></i>
                </div>
                <input type="text" name="code" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999]" placeholder="رمز التحقق (6 أرقام)" maxlength="6" required>
            </div>
            @if($errors->has('code'))
                <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('code') }}</span>
            @endif
        </div>

        <button type="submit" class="bg-[#193e6a] text-white border-none rounded-lg w-full h-[50px] text-lg font-semibold cursor-pointer transition-all duration-300 mt-2.5 hover:bg-[#27568f] hover:shadow-[0_4px_15px_rgba(25,62,106,0.3)] flex items-center justify-center gap-2">تحقق</button>
    </form>
@endsection
