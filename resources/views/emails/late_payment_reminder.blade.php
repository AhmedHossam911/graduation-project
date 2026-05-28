{{--
    Late Payment Reminder Email:
    HTML email template sent to members who have overdue subscription payments (1-6 months).
--}}
<x-mail::message>
# تنبيه تأخر سداد اشتراك صندوق الزمالة

عزيزي العضو / **{{ $subscription->membership->member->full_name }}**،

نحيط سيادتكم علماً بأنه يوجد اشتراك متأخر السداد على عضويتكم بصندوق الزمالة.

**تفاصيل الاشتراك:**
- تاريخ الاستحقاق: {{ \Carbon\Carbon::parse($subscription->due_date)->format('Y-m-d') }}
- قيمة الاشتراك: {{ $subscription->amount }} جنيه

يرجى سرعة سداد الاشتراك المستحق لتجنب اتخاذ الإجراءات القانونية وإيقاف العضوية.

إذا قمتم بالسداد بالفعل، يرجى تجاهل هذه الرسالة.

<div style="text-align: left;" dir="rtl">
مع تحيات،<br>
{{ config('app.name') }}
</div>
</x-mail::message>
