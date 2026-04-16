@extends('layouts.auth')
@section('title', 'تعيين كلمة مرور جديدة')
@include('partials.flash')
@section('content')
    <h2 class="auth-subtitle">تعيين كلمة مرور جديدة</h2>

    <form method="POST" action="{{ route('password.reset.post') }}">
        @csrf
        
        <div class="form-group">
            <div class="input-wrapper" style="{{ $errors->has('password') ? 'border-color: red;' : '' }}">
                <div class="input-icon-right">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" name="password" id="password" class="input-field" placeholder="كلمة المرور الجديدة" required>
                <div class="input-icon-left" onclick="togglePassword('password', this)">
                    <i class="fa-regular fa-eye"></i>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="input-wrapper">
                <div class="input-icon-right">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" name="password_confirmation" id="password_confirmation" class="input-field" placeholder="تأكيد كلمة المرور" required>
                <div class="input-icon-left" onclick="togglePassword('password_confirmation', this)">
                    <i class="fa-regular fa-eye"></i>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary">حفظ كلمة المرور والدخول</button>
    </form>
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
