{{--
    Account Created Email:
    Sent to newly registered users/employees.
    Contains their login credentials and assigned permissions.
--}}
<x-mail::message>
# مرحباً {{ $user->name }}،

تم إنشاء حسابك بنجاح في نظام **صندوق الزمالة**.

يسعدنا انضمامك لفريق العمل، للوصول إلى لوحة التحكم يمكنك تسجيل الدخول باستخدام البيانات التالية:

<x-mail::panel>
**اسم المستخدم:** (الرقم القومي الخاص بك)<br>
**كلمة المرور الافتراضية:** `{{ $passwordStr }}`
</x-mail::panel>

<x-mail::button :url="route('login')">
تسجيل الدخول للنظام
</x-mail::button>

**نصيحة أمنية هامة:** يرجى تغيير كلمة المرور الافتراضية من صفحة ملفك الشخصي فور تسجيل دخولك الأول للحفاظ على أمان حسابك.

<div style="text-align: right;" dir="rtl">
مع خالص التحيات،<br>
إدارة {{ config('app.name', 'صندوق الزمالة') }}
</div>
</x-mail::message>
