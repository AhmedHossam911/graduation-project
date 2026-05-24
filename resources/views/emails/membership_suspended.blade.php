<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f9; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; border-top: 4px solid #D92D20; }
        .header { text-align: center; margin-bottom: 20px; color: #D92D20; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>إيقاف عضوية صندوق الزمالة</h2>
        </div>
        <p>عزيزي العضو / <strong>{{ $membership->member->full_name }}</strong>،</p>
        <p>نأسف لإبلاغكم بأنه قد تم إيقاف عضويتكم في صندوق الزمالة، وذلك نظراً لمرور أكثر من شهر على إرسال الإخطار الرسمي لتأخركم في سداد الاشتراكات لمدة تتجاوز 6 أشهر.</p>
        
        <p>يرجى التوجه إلى إدارة الصندوق في أقرب وقت لتسوية موقفكم المالي ومعرفة إجراءات إعادة التفعيل إن أمكن.</p>

        <div class="footer">
            <p>مع تحيات،<br>إدارة صندوق الزمالة</p>
        </div>
    </div>
</body>
</html>
