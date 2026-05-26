@component('mail::message')
# مرحباً {{ $user->name }}،

تم إنشاء حسابك بنجاح في نظام **صندوق الزمالة كابيتال**.

يسعدنا انضمامك لفريق العمل، وإليك تفاصيل الدخول الخاصة بك:

**البريد الإلكتروني:** {{ $user->email }}<br>
**كلمة المرور الافتراضية:** {{ $passwordStr }}

@if(!empty($permissions) && count($permissions) > 0)
**الصلاحيات الممنوحة لك بالنظام:**
@foreach($permissions as $permission)
- {{ $permission }}
@endforeach
@endif

@component('mail::button', ['url' => route('login')])
تسجيل الدخول للنظام
@endcomponent

@component('mail::panel')
**نصيحة أمنية هامة:** يرجى تغيير كلمة المرور الافتراضية فور تسجيل دخولك الأول من خلال صفحة الملف الشخصي.
@endcomponent

مع تحيات،<br>
إدارة النظام
@endcomponent
