<x-mail::message>
# اعتماد مطالبة المستحقات وتجهيز الشيك

عزيزي العضو / **{{ $claim->membership->member->user->name }}**،

نحيط سيادتكم علماً بأنه قد تم اعتماد مطالبة الصرف الخاصة بكم بنجاح، وأن الشيك الخاص بالمستحقات أصبح جاهزاً للاستلام.

**تفاصيل المطالبة:**
- نوع المطالبة: {{ \App\Models\Services\Claim::CLAIM_TYPES[$claim->claim_type] ?? $claim->claim_type }}
- تاريخ الاعتماد: {{ now()->format('Y-m-d') }}

يرجى التوجه إلى إدارة الصندوق في أقرب وقت لاستلام الشيك الخاص بكم وصرفه.

<div style="text-align: left;" dir="rtl">
مع تحيات،<br>
إدارة {{ config('app.name') }}
</div>
</x-mail::message>
