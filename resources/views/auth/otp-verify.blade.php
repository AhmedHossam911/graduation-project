@extends('layouts.auth')
@section('title', 'التحقق من الرمز')
@include('partials.flash')
@section('content')
    <h2 class="auth-subtitle">التحقق من الرمز المكون من 6 أرقام</h2>
    <p class="auth-description">
        أدخل الرمز الذي تم إرساله إلى بريدك الإلكتروني.
    </p>


    <form method="POST" action="{{ route('password.verify.post') }}">
        @csrf
        <div class="form-group">
            <div class="input-wrapper" style="{{ $errors->has('code') ? 'border-color: red;' : '' }}">
                <div class="input-icon-right">
                    <i class="fa-solid fa-key"></i>
                </div>
                <input type="text" name="code" class="input-field" placeholder="رمز التحقق (6 أرقام)" maxlength="6" required>
            </div>
        </div>

        <button type="submit" class="btn-primary">تحقق</button>
    </form>
@endsection
