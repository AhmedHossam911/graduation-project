@extends('layouts.auth')
@section('title', 'إعادة تعيين كلمة المرور')
@include('partials.flash')

@section('content')
    <h2 class="auth-subtitle">إعادة تعيين كلمة المرور</h2>
    <p class="auth-description">
        أدخل بياناتك وسيتم إرسال كلمة مرور مؤقتة إلى بريدك الإلكتروني المسجل.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <div class="input-wrapper" style="{{ $errors->has('national_id') ? 'border-color: red;' : '' }}">
                <div class="input-icon-right">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <input type="text" name="national_id" value="{{ old('national_id') }}" class="input-field" placeholder="الرقم القومي" required>
            </div>
            @if($errors->has('national_id'))
                <span style="color: #dc3545; font-size: 12px; margin-top: 5px; display: block;">{{ $errors->first('national_id') }}</span>
            @endif
        </div>

        <div class="form-group">
            <div class="input-wrapper" style="{{ $errors->has('email') ? 'border-color: red;' : '' }}">
                <div class="input-icon-right">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" class="input-field" placeholder="البريد الإلكتروني" required>
            </div>
            @if($errors->has('email'))
                <span style="color: #dc3545; font-size: 12px; margin-top: 5px; display: block;">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <button type="submit" class="btn-primary">إرسال كلمة المرور المؤقتة</button>
    </form>

    <div class="auth-links" style="justify-content: flex-start; margin-top: 15px;">
        <a href="{{ route('login') }}" class="auth-link">الرجوع لتسجيل الدخول</a>
    </div>
@endsection
