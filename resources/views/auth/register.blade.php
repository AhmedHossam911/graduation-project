@extends('layouts.auth')
@section('title', 'إنشاء حساب جديد')
@include('partials.flash')

@section('content')
    <h2 class="auth-subtitle">إنشاء حساب جديد</h2>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="grid grid-cols-2">

            <div class="form-group">
                <div class="input-wrapper" style="{{ $errors->has('name') ? 'border-color: red;' : '' }}">
                    <div class="input-icon-right">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" class="input-field"
                        placeholder="الأسم كامل" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper" style="{{ $errors->has('email') ? 'border-color: red;' : '' }}">
                    <div class="input-icon-right">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                        placeholder="البريد الإلكتروني" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper" style="{{ $errors->has('national_id') ? 'border-color: red;' : '' }}">
                    <div class="input-icon-right">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <input type="text" name="national_id" value="{{ old('national_id') }}" minlength="14" maxlength="14"
                        class="input-field" placeholder="الرقم القومي" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper" style="{{ $errors->has('phone') ? 'border-color: red;' : '' }}">
                    <div class="input-icon-right">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input-field"
                        placeholder="رقم التليفون" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper" style="{{ $errors->has('workplace') ? 'border-color: red;' : '' }}">
                    <div class="input-icon-right">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <input type="text" name="workplace" value="{{ old('workplace') }}" class="input-field"
                        placeholder="جهة العمل" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper" style="{{ $errors->has('job_title') ? 'border-color: red;' : '' }}">
                    <div class="input-icon-right">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" class="input-field"
                        placeholder="الوظيفة" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper" style="{{ $errors->has('password') ? 'border-color: red;' : '' }}">
                    <div class="input-icon-right">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" name="password" id="password" class="input-field" placeholder="كلمة المرور"
                        required>
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
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-field"
                        placeholder="تأكيد كلمة المرور" required>
                    <div class="input-icon-left" onclick="togglePassword('password_confirmation', this)">
                        <i class="fa-regular fa-eye"></i>
                    </div>
                </div>
            </div>
            <div class="password-requirements">
                يجب أن تتكون كلمة المرور من 6 إلى 20 حرفًا أو رقمًا، وأن تحتوي على حرف كبير (A-Z) واحد على الأقل ورمز خاص.
            </div>
        </div>

        <button type="submit" class="btn-primary">إنشاء الحساب</button>
    </form>

    <div class="auth-footer">
        لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل دخول</a>
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
