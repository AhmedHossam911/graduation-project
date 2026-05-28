{{--
    OTP Email:
    Generic template for sending One-Time Passwords for login, password reset, or transaction verification.
--}}
<x-mail::message>
# مرحباً،

نرسل إليك هذا الإيميل بناءً على طلبك لـ: **{{ $reason }}**.

لإتمام العملية، يرجى استخدام رمز التحقق المؤقت التالي:

<x-mail::panel>
<div style="text-align: center; font-size: 24px; letter-spacing: 5px; font-weight: bold; color: #124375;">
{{ $otp }}
</div>
</x-mail::panel>

*هذا الرمز صالح لمدة 10 دقائق فقط.*

إذا لم تكن أنت من طلب هذا الرمز، يرجى تجاهل هذا الإيميل.

<div style="text-align: left;" dir="rtl">
مع تحيات،<br>
{{ config('app.name') }}
</div>
</x-mail::message>
