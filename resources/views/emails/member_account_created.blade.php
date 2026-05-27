<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تم إنشاء حساب العضوية بنجاح</title>
</head>
<body style="font-family: Arial, sans-serif; text-align: right; direction: rtl;">
    <h2>مرحباً {{ $member->full_name }}،</h2>
    <p>تم إنشاء حساب العضوية الخاص بك في صندوق الزمالة بنجاح.</p>
    <p>بإمكانك الآن تسجيل الدخول للنظام باستخدام البيانات التالية:</p>
    <ul>
        <li><strong>البريد الإلكتروني:</strong> {{ $member->user->email ?? 'البريد المسجل' }}</li>
        <li><strong>كلمة المرور:</strong> {{ $password }}</li>
    </ul>
    <p>يرجى تغيير كلمة المرور بعد تسجيل الدخول لأول مرة حفاظاً على سرية بياناتك.</p>
    <p>مع تحيات،<br>إدارة صندوق الزمالة</p>
</body>
</html>
