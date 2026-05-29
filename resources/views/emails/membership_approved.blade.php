<x-mail::message>
# اعتماد طلب العضوية

عزيزي العضو / **{{ $membership->member->user->name }}**،

نحيط سيادتكم علماً بأنه قد تم اعتماد طلب عضويتكم في صندوق الزمالة بنجاح، والعضوية الآن نشطة.

**تفاصيل العضوية:**
- رقم العضوية: {{ $membership->membership_number }}
- تاريخ الاعتماد: {{ now()->format('Y-m-d') }}

مرحباً بك في صندوق الزمالة!

<div style="text-align: left;" dir="rtl">
مع تحيات،<br>
إدارة {{ config('app.name') }}
</div>
</x-mail::message>
