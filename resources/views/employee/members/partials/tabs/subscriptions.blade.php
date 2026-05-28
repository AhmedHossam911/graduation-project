{{-- 
    Member Subscriptions Tab Partial:
    Shows all subscriptions associated with the member,
    along with their status, and provides tools for recording payments.
--}}
    <!-- subscription table -->
    <div data-tab="الاشتراكات"
        class="{{ $activeTabName === 'الاشتراكات' ? '' : 'hidden' }} tab-content px-7 py-2 print:hidden">
        @if ($member->membershipInfo && $member->membershipInfo->subscriptions->count() > 0)
            <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم الأشتراك</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الإستحقاق</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ السداد</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($member->membershipInfo->subscriptions as $subscription)
                            <tr class="text-center border-b border-[#D1D5DB] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $subscription->id }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ number_format($subscription->amount, 2) }}</td>
                                <td class="py-4 border-l border-[#D1D5DB]">
                                    @if ($subscription->status === 'paid')
                                        <span
                                            class="bg-[#ECFDF333] text-[#067647CC] border border-[#067647CC] px-12 py-1 text-sm rounded-lg">مدفوع</span>
                                    @elseif($subscription->status === 'unpaid' && \Carbon\Carbon::parse($subscription->due_date)->isPast())
                                        <span
                                            class="bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] px-12 py-1 text-sm rounded-lg">متأخر</span>
                                    @else
                                        <span
                                            class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-12 py-1 text-sm rounded-lg">مستحق</span>
                                    @endif
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ \Carbon\Carbon::parse($subscription->due_date)->format('Y-m-d') }}</td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ $subscription->paid_at ? \Carbon\Carbon::parse($subscription->paid_at)->format('Y-m-d') : '-' }}
                                </td>
                                <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                    {{ match ($subscription->transaction?->method ?? '') {'cash' => 'نقدي','bank_transfer' => 'تحويل بنكي','salary_deduction' => 'خصم من المرتب','university_payment_order' => 'أمر دفع من الجامعة',default => '-'} }}
                                </td>
                                <td class="py-5">
                                    @php
                                        $hasReceipt =
                                            \App\Models\Financial\Transaction::where(
                                                'reference_type',
                                                \App\Models\Services\Subscription::class,
                                            )
                                                ->where('reference_id', $subscription->id)
                                                ->whereNotNull('attachment_path')
                                                ->exists() ||
                                            \App\Models\Membership\Attachment::where('member_id', $member->id)
                                                ->where('type', "subscription_{$subscription->id}_receipt")
                                                ->exists();
                                    @endphp
                                    @if ($subscription->status === 'paid')
                                        <div class="text-2xl flex gap-7 items-center justify-center text-[#124375]">
                                            @if ($hasReceipt)
                                                <a href="{{ route('subscriptions.view_receipt', $subscription->id) }}"
                                                    target="_blank" class="hover:text-blue-700 transition-colors"
                                                    title="عرض الإيصال">
                                                    <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                                </a>
                                                <a href="{{ route('subscriptions.download_receipt', $subscription->id) }}"
                                                    class="hover:text-blue-700 transition-colors" title="تحميل الإيصال">
                                                    <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                                </a>
                                            @else
                                                <span class="text-gray-400 cursor-not-allowed" title="لا يوجد إيصال مرفق">
                                                    <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                                </span>
                                                <span class="text-gray-400 cursor-not-allowed" title="لا يوجد إيصال مرفق">
                                                    <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div>
                                            <button data-modal="modal7"
                                                onclick="document.getElementById('paySubscriptionForm').action='{{ route('subscriptions.pay', $subscription->id) }}'"
                                                class="open-modal bg-[#124375] text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-2 px-4">
                                                تسجيل السداد
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-requests flex justify-center py-14">
                <div class="flex flex-col items-center gap-5">
                    <img src="{{ asset('IMGs/no-requests.png') }}" alt="no-subscriptions">
                    <p>لا يوجد اشتراكات مسجلة حالياً</p>
                </div>
            </div>
        @endif
    </div>
    <!-- end subscription table -->