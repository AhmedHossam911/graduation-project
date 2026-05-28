{{--
    Account Created Email:
    Sent to newly registered users/employees.
    Contains their login credentials and assigned permissions.
--}}
<x-mail::message>
    # مرحباً {{ $user->name }}،

    تم إنشاء حسابك بنجاح في نظام **صندوق الزمالة كابيتال**.

    يسعدنا انضمامك لفريق العمل، وإليك تفاصيل الدخول الخاصة بك:

    **البريد الإلكتروني:** {{ $user->email }}<br>
    **كلمة المرور الافتراضية:** {{ $passwordStr }}


    <x-mail::button :url="route('login')">
        تسجيل الدخول للنظام
    </x-mail::button>

    <x-mail::panel>
        **نصيحة أمنية هامة:** يرجى تغيير كلمة المرور الافتراضية فور تسجيل دخولك الأول من خلال صفحة الملف الشخصي.
    </x-mail::panel>

    <div style="text-align: left;" dir="rtl">
        مع تحيات،<br>
        {{ config('app.name') }}
    </div>
</x-mail::message>
