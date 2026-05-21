@extends('layouts.auth')
@section('title', 'تفعيل الحساب')
@include('partials.flash')
@section('content')
    <h2 class="auth-subtitle">التحقق من الرمز المكون من 6 أرقام</h2>
    <p class="auth-description">
        لقد أرسلنا رمز التفعيل إلى بريدك الإلكتروني. يرجى إدخاله لتفعيل حسابك.
    </p>

    <form method="POST" action="{{ route('register.verify.post') }}">
        @csrf
        <div class="form-group">
            <div class="input-wrapper" style="{{ $errors->has('code') ? 'border-color: red;' : '' }}">
                <div class="input-icon-right">
                    <i class="fa-solid fa-key"></i>
                </div>
                <input type="text" name="code" class="input-field" placeholder="رمز التحقق (6 أرقام)" maxlength="6" required>
            </div>
        </div>

        <button type="submit" class="btn-primary">تفعيل الحساب</button>
    </form>
@endsection
