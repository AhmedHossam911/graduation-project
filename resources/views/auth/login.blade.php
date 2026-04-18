@extends('layouts.auth')
@section('title', 'تسجيل الدخول')
@include('partials.flash')
@section('content')
    <h2 class="auth-subtitle">تسجيل دخول</h2>
    
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <div class="input-wrapper" style="{{ $errors->has('national_id') ? 'border-color: red;' : '' }}">
                <div class="input-icon-right">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <input type="text" name="national_id" value="{{ old('national_id') }}" class="input-field" placeholder="الرقم القومي" required>
            </div>
        </div>

        <div class="form-group">
            <div class="input-wrapper" style="{{ $errors->has('password') ? 'border-color: red;' : '' }}">
                <div class="input-icon-right">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" name="password" id="password" class="input-field" placeholder="كلمة المرور" required>
                <div class="input-icon-left" onclick="togglePassword('password', this)">
                    <i class="fa-regular fa-eye"></i>
                </div>
            </div>
        </div>

        <div class="auth-links" style="justify-content: flex-end;">
            <a href="{{ route('password.request') }}" class="auth-link">هل نسيت كلمة المرور ؟</a>
        </div>

        <button type="submit" class="btn-primary">تسجيل</button>
    </form>

    <div class="auth-footer">
        ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a>
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
