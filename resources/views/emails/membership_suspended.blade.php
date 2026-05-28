{{--
    Membership Suspended Email:
    Sent to members whose membership is formally suspended after exceeding the maximum allowed overdue period.
--}}
<x-mail::message>
# إيقاف عضوية صندوق الزمالة

عزيزي العضو / **{{ $membership->member->full_name }}**،

نأسف لإبلاغكم بأنه قد تم إيقاف عضويتكم في صندوق الزمالة، وذلك نظراً لمرور أكثر من شهر على إرسال الإخطار الرسمي لتأخركم في سداد الاشتراكات لمدة تتجاوز 6 أشهر.

يرجى التوجه إلى إدارة الصندوق في أقرب وقت لتسوية موقفكم المالي ومعرفة إجراءات إعادة التفعيل إن أمكن.

<div style="text-align: left;" dir="rtl">
مع تحيات،<br>
{{ config('app.name') }}
</div>
</x-mail::message>
