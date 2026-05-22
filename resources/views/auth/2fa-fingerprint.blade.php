@extends('layouts.auth')
@section('title', 'تأكيد الدخول بالبصمة')
@include('partials.flash')
@section('content')
    <h2 class="auth-subtitle">تأكيد الدخول بالبصمة</h2>
    
    <div id="fingerprint-container" style="text-align: center; margin: 30px 0;">
        <i class="fa-solid fa-fingerprint" style="font-size: 80px; color: #28a745; cursor: pointer;" id="start-fingerprint"></i>
        <p style="margin-top: 15px; font-weight: bold; color: #333;" id="fingerprint-status">اضغط على البصمة للبدء</p>
    </div>

    <form id="fallback-form" method="POST" action="{{ route('login.2fa.otp.send') }}" style="display: none; text-align: center;">
        @csrf
        <p style="color: red; margin-bottom: 10px;">لم نتمكن من التعرف على البصمة أو لم تقم بتسجيل بصمة مسبقاً.</p>
        <button type="submit" class="btn-primary" style="background-color: #0056b3;">استخدام OTP عبر الإيميل كبديل</button>
    </form>

    <div class="auth-footer" style="margin-top: 20px;">
        <a href="{{ route('login.2fa') }}">تغيير وسيلة التحقق</a>
    </div>
@endsection

@section('scripts')
<script type="module">
    import { get } from 'https://unpkg.com/@github/webauthn-json@2.1.1/dist/browser-ponyfill.js';

    const options = {!! json_encode($options) !!};
    
    document.getElementById('start-fingerprint').addEventListener('click', async () => {
        const statusEl = document.getElementById('fingerprint-status');
        const fallbackForm = document.getElementById('fallback-form');
        statusEl.innerText = 'جاري التحقق...';
        statusEl.style.color = '#ffc107';
        
        try {
            const credential = await get(options);
            
            // Send to backend
            const response = await fetch("{{ route('login.2fa.fingerprint.verify') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(credential)
            });

            const data = await response.json();
            
            if (response.ok) {
                statusEl.innerText = 'تم التحقق بنجاح!';
                statusEl.style.color = '#28a745';
                window.location.href = data.redirect || '/';
            } else {
                throw new Error(data.message || 'فشل التحقق');
            }
        } catch (error) {
            console.error(error);
            statusEl.innerText = 'فشل التحقق من البصمة.';
            statusEl.style.color = 'red';
            fallbackForm.style.display = 'block';
        }
    });

    // Auto start on load
    setTimeout(() => {
        document.getElementById('start-fingerprint').click();
    }, 500);
</script>
@endsection
