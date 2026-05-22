@extends('layouts.auth')
@section('title', 'تأكيد تسجيل الدخول')
@include('partials.flash')
@section('content')
    <h2 class="auth-subtitle">اختر وسيلة تأكيد الدخول</h2>
    
    <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
        <form method="POST" action="{{ route('login.2fa.otp.send') }}">
            @csrf
            <button type="submit" class="btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="fa-solid fa-envelope"></i>
                تأكيد بـ رمز OTP عبر الإيميل
            </button>
        </form>
        
        <a href="{{ route('login.2fa.fingerprint') }}" class="btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; background-color: #28a745; color: white; text-decoration: none;">
            <i class="fa-solid fa-fingerprint"></i>
            تأكيد بالبصمة (Passkey)
        </a>
    </div>

    <div class="auth-footer" style="margin-top: 20px;">
        <a href="{{ route('login') }}">العودة لتسجيل الدخول</a>
    </div>
@endsection
