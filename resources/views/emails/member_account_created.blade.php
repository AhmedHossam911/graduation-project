{{--
    Member Account Created Email:
    Sent to members once their fund membership is approved and their dashboard account is generated.
--}}
<x-mail::message>
# مرحباً،

تم إنشاء حساب العضوية الخاص بك في صندوق الزمالة بنجاح.

بإمكانك الآن تسجيل الدخول للنظام باستخدام البيانات التالية:

- **البريد الإلكتروني:** {{ $member->user->email ?? 'البريد المسجل' }}
- **كلمة المرور:** {{ $password }}

يرجى تغيير كلمة المرور بعد تسجيل الدخول لأول مرة حفاظاً على سرية بياناتك.

<x-mail::button :url="route('login')">
تسجيل الدخول للنظام
</x-mail::button>

<div style="text-align: left;" dir="rtl">
مع تحيات،<br>
{{ config('app.name') }}
</div>
</x-mail::message>
