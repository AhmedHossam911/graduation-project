<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f9; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; border-top: 4px solid #F79009; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #124375; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>تنبيه تأخر سداد اشتراك صندوق الزمالة</h2>
        </div>
        <p>عزيزي العضو / <strong>{{ $subscription->membership->member->full_name }}</strong>،</p>
        <p>نحيط سيادتكم علماً بأنه يوجد اشتراك متأخر السداد على عضويتكم بصندوق الزمالة.</p>
        <p><strong>تفاصيل الاشتراك:</strong></p>
        <ul>
            <li>تاريخ الاستحقاق: {{ \Carbon\Carbon::parse($subscription->due_date)->format('Y-m-d') }}</li>
            <li>قيمة الاشتراك: {{ $subscription->amount }} جنيه</li>
        </ul>
        <p>يرجى سرعة سداد الاشتراك المستحق لتجنب اتخاذ الإجراءات القانونية وإيقاف العضوية.</p>
        
        <p>إذا قمتم بالسداد بالفعل، يرجى تجاهل هذه الرسالة.</p>

        <div class="footer">
            <p>مع تحيات،<br>إدارة صندوق الزمالة</p>
        </div>
    </div>
</body>
</html>
