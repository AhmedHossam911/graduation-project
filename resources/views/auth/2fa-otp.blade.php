@extends('layouts.auth')
@section('title', 'إدخال رمز التحقق')
@include('partials.flash')
@section('content')
    <h2 class="auth-subtitle">تأكيد الدخول عبر الإيميل</h2>
    <p style="text-align: center; margin-bottom: 20px; color: #666;">تم إرسال رمز التحقق المكون من 6 أرقام إلى بريدك الإلكتروني.</p>

    <form method="POST" action="{{ route('login.2fa.otp.verify') }}">
        @csrf
        <div class="form-group">
            <div class="input-wrapper" style="{{ $errors->has('code') ? 'border-color: red;' : '' }}">
                <div class="input-icon-right">
                    <i class="fa-solid fa-key"></i>
                </div>
                <input type="text" name="code" value="{{ old('code') }}" class="input-field" placeholder="رمز التحقق (6 أرقام)" required maxlength="6" style="letter-spacing: 5px; text-align: center;">
            </div>
            @if ($errors->has('code'))
                <span class="text-danger" style="font-size: 0.9em; margin-top: 5px; display: block;">{{ $errors->first('code') }}</span>
            @endif
        </div>

        <button type="submit" class="btn-primary" style="margin-top: 15px;">تأكيد الدخول</button>
    </form>

    <div class="auth-footer" style="margin-top: 20px;">
        <form method="POST" action="{{ route('login.2fa.otp.send') }}" style="display: inline;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #0056b3; cursor: pointer; text-decoration: underline; font-family: inherit; font-size: inherit;">إعادة إرسال الرمز</button>
        </form>
        <br><br>
        <a href="{{ route('login.2fa') }}">تغيير وسيلة التحقق</a>
    </div>
@endsection
