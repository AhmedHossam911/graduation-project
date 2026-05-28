@extends('layouts.auth')
{{--
    2FA OTP View:
    Handles Two-Factor Authentication login step.
    Prompts the user to enter the 6-digit code sent to their email.
--}}
@section('title', 'إدخال رمز التحقق')
@include('partials.flash')
@section('content')
    <h2 class="text-[#333] text-lg font-semibold mb-2.5">تأكيد الدخول عبر الإيميل</h2>
    <p class="text-[#666] text-sm mb-6 leading-relaxed">تم إرسال رمز التحقق المكون من 6 أرقام إلى بريدك
        الإلكتروني.</p>

    <form method="POST" action="{{ route('login.2fa.otp.verify') }}">
        @csrf
        <div class="mb-5 text-right">
            <div class="flex flex-row border rounded-xl overflow-hidden h-[50px] bg-[#eff4f8] {{ $errors->has('code') ? 'border-red-500' : 'border-[#193e6a]' }}">
                <div class="bg-[#193e6a] w-[50px] flex justify-center items-center text-white text-lg shrink-0">
                    <i class="fa-solid fa-key"></i>
                </div>
                <input type="text" name="code" value="{{ old('code') }}" class="grow border-none bg-transparent px-4 text-[15px] text-[#333] outline-none w-full placeholder-[#999] tracking-[5px] text-center"
                    placeholder="رمز التحقق (6 أرقام)" required maxlength="6">
            </div>
            @if ($errors->has('code'))
                <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('code') }}</span>
            @endif
        </div>

        <div class="mt-5 grid gap-3">
            <button type="submit" class="bg-[#193e6a] text-white border-none rounded-lg w-full h-[50px] text-lg font-semibold cursor-pointer transition-all duration-300 hover:bg-[#27568f] hover:shadow-[0_4px_15px_rgba(25,62,106,0.3)] flex items-center justify-center gap-2">تأكيد الدخول</button>
    </form>

    <form method="POST" action="{{ route('login.2fa.otp.send') }}">
        @csrf
        <button type="submit" class="bg-gray-200 text-[#333] border-none rounded-lg w-full h-[50px] text-lg font-semibold cursor-pointer transition-all duration-300 hover:bg-gray-300 flex items-center justify-center gap-2">إعادة إرسال الرمز</button>
    </form>
    </div>
@endsection
