{{-- 
    Member Subscriptions Tab Partial:
    Shows all subscriptions associated with the member,
    along with their status, and provides tools for recording payments.
--}}
    <!-- subscription table -->
    <div data-tab="الاشتراكات"
        class="{{ $activeTabName === 'الاشتراكات' ? '' : 'hidden' }} tab-content px-7 py-2 print:hidden">
        @if ($member->membershipInfo && $member->membershipInfo->subscriptions->count() > 0)
            <div class="rounded-[14px] overflow-hidden border-0 md:border border-[#D1D5DB] bg-transparent md:bg-white p-0">
                <div class="hidden md:block overflow-x-auto">
                <table class="w-full md:min-w-max md:whitespace-nowrap">
                    <thead class="hidden md:table-header-group">
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">اسم الاشتراك</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الإستحقاق</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ السداد</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group">
                        @foreach ($member->membershipInfo->subscriptions as $subscription)
                            <tr class="block md:table-row bg-white md:bg-transparent shadow-sm md:shadow-none rounded-xl md:rounded-none mb-4 md:mb-0 border md:border-none border-gray-200 text-right md:text-center {{ $loop->even ? 'md:bg-[#EFEFEF]' : '' }}">
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">اسم الاشتراك:</span>
                                    <span>{{ $subscription->name }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">المبلغ:</span>
                                    <span>{{ number_format($subscription->amount, 2) }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l">
                                    <span class="md:hidden font-bold text-[#124375]">الحالة:</span>
                                    @if ($subscription->status === 'paid')
                                        <span class="bg-[#ECFDF333] text-[#067647CC] border border-[#067647CC] px-4 md:px-12 py-1 text-sm rounded-lg">مدفوع</span>
                                    @elseif($subscription->status === 'unpaid' && \Carbon\Carbon::parse($subscription->due_date)->isPast())
                                        <span class="bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] px-4 md:px-12 py-1 text-sm rounded-lg">متأخر</span>
                                    @else
                                        <span class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-4 md:px-12 py-1 text-sm rounded-lg">مستحق</span>
                                    @endif
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">تاريخ الإستحقاق:</span>
                                    <span>{{ \Carbon\Carbon::parse($subscription->due_date)->format('Y-m-d') }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">تاريخ السداد:</span>
                                    <span>{{ $subscription->transaction ? \Carbon\Carbon::parse($subscription->transaction->created_at)->format('Y-m-d') : '-' }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">طريقة الدفع:</span>
                                    <span>{{ match ($subscription->transaction?->method ?? '') {'cash' => 'نقدي','bank_transfer' => 'تحويل بنكي','salary_deduction' => 'خصم من المرتب','university_payment_order' => 'أمر دفع من الجامعة',default => '-'} }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-5 border-b-0 md:border-b md:border-[#D1D5DB]">
                                    <span class="md:hidden font-bold text-[#124375]">الإجراء:</span>
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
                                        <div class="text-2xl flex gap-4 md:gap-7 items-center justify-center text-[#124375]">
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
                                        @if(!isset($firstUnpaidSubscriptionDesktop))
                                            @php $firstUnpaidSubscriptionDesktop = true; @endphp
                                            @if(!$isMembershipClosed)
                                                <div>
                                                    <button data-modal="modal7"
                                                        onclick="document.getElementById('paySubscriptionForm').action='{{ route('subscriptions.pay', $subscription->id) }}'"
                                                        class="open-modal bg-[#124375] text-[14px] md:text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-1.5 md:py-2 px-3 md:px-4">
                                                        تسجيل السداد
                                                    </button>
                                                </div>
                                            @else
                                                <div class="text-sm text-[#D92D20] font-medium bg-[#FFEAE8] px-2 py-1 rounded text-center">مغلقة</div>
                                            @endif
                                        @else
                                            <div class="text-sm text-[#D92D20] font-medium bg-[#FFEAE8] px-2 py-1 rounded text-center">يجب سداد السابق أولاً</div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden flex flex-col gap-4">
                    @foreach ($member->membershipInfo->subscriptions as $subscription)
                        @php
                            $statusHtml = '';
                            $statusBorder = '';
                            if ($subscription->status === 'paid') {
                                $statusHtml = 'مدفوع';
                                $statusBorder = 'bg-[#ECFDF333] text-[#067647CC] border-[#067647CC]';
                            } elseif ($subscription->status === 'unpaid' && \Carbon\Carbon::parse($subscription->due_date)->isPast()) {
                                $statusHtml = 'متأخر';
                                $statusBorder = 'bg-[#FFEAE880] text-[#D92D20] border-[#D92D20]';
                            } else {
                                $statusHtml = 'مستحق';
                                $statusBorder = 'bg-[#F2F4F7] text-[#6D6D6D] border-[#6D6D6D]';
                            }
                        @endphp
                        <div class="bg-white rounded-[14px] border border-[#D1D5DB] p-4 flex flex-col gap-3 shadow-sm relative overflow-hidden">
                            <div class="flex justify-between items-start">
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-[#124375] font-bold text-lg">{{ $subscription->name }}</h3>
                                    <span class="text-sm text-[#067647] font-semibold">{{ number_format($subscription->amount, 2) }} ج.م</span>
                                </div>
                                <span class="{{ $statusBorder }} border rounded-[8px] py-[2px] px-3 text-xs text-center font-medium">
                                    {{ $statusHtml }}
                                </span>
                            </div>
                            
                            <div class="flex flex-col gap-2 mt-2">
                                <div class="flex gap-2 items-center text-sm">
                                    <iconify-icon icon="mdi:calendar-clock" class="text-[#6D6D6D]"></iconify-icon>
                                    <span class="text-[#6D6D6D]">تاريخ الإستحقاق:</span>
                                    <span class="text-[#021219] font-medium">{{ \Carbon\Carbon::parse($subscription->due_date)->format('Y-m-d') }}</span>
                                </div>
                                @if($subscription->transaction)
                                <div class="flex gap-2 items-center text-sm">
                                    <iconify-icon icon="mdi:calendar-check" class="text-[#6D6D6D]"></iconify-icon>
                                    <span class="text-[#6D6D6D]">تاريخ السداد:</span>
                                    <span class="text-[#021219] font-medium">{{ \Carbon\Carbon::parse($subscription->transaction->created_at)->format('Y-m-d') }}</span>
                                </div>
                                @endif
                                @if($subscription->transaction)
                                <div class="flex gap-2 items-center text-sm">
                                    <iconify-icon icon="mdi:cash" class="text-[#6D6D6D]"></iconify-icon>
                                    <span class="text-[#6D6D6D]">طريقة الدفع:</span>
                                    <span class="text-[#021219] font-medium">{{ match ($subscription->transaction?->method ?? '') {'cash' => 'نقدي','bank_transfer' => 'تحويل بنكي','salary_deduction' => 'خصم من المرتب','university_payment_order' => 'أمر دفع من الجامعة',default => '-'} }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex justify-center mt-2 pt-3 border-t border-gray-100">
                                @php
                                    $hasReceipt = \App\Models\Financial\Transaction::where('reference_type', \App\Models\Services\Subscription::class)
                                                    ->where('reference_id', $subscription->id)
                                                    ->whereNotNull('attachment_path')
                                                    ->exists() ||
                                                \App\Models\Membership\Attachment::where('member_id', $member->id)
                                                    ->where('type', "subscription_{$subscription->id}_receipt")
                                                    ->exists();
                                @endphp
                                @if ($subscription->status === 'paid')
                                    <div class="flex gap-4 items-center justify-center w-full">
                                        @if ($hasReceipt)
                                            <a href="{{ route('subscriptions.view_receipt', $subscription->id) }}" target="_blank"
                                                class="flex-1 flex justify-center items-center gap-2 border border-[#124375] bg-white text-[#124375] py-2 rounded-[8px] text-sm hover:bg-[#F4F7F9] transition-colors" title="عرض الإيصال">
                                                <iconify-icon icon="solar:eye-linear" class="text-lg"></iconify-icon> عرض
                                            </a>
                                            <a href="{{ route('subscriptions.download_receipt', $subscription->id) }}"
                                                class="flex-1 flex justify-center items-center gap-2 border border-[#124375] bg-[#124375] text-white py-2 rounded-[8px] text-sm hover:bg-[#0e3560] transition-colors" title="تحميل الإيصال">
                                                <iconify-icon icon="material-symbols:download-rounded" class="text-lg"></iconify-icon> تحميل
                                            </a>
                                        @else
                                            <span class="flex-1 flex justify-center items-center gap-2 border border-gray-300 bg-gray-100 text-gray-400 py-2 rounded-[8px] text-sm cursor-not-allowed" title="لا يوجد إيصال مرفق">
                                                <iconify-icon icon="solar:eye-linear" class="text-lg"></iconify-icon> لا يوجد
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    @if(!isset($firstUnpaidSubscriptionMobile))
                                        @php $firstUnpaidSubscriptionMobile = true; @endphp
                                        @if(!$isMembershipClosed)
                                            <button data-modal="modal7"
                                                onclick="document.getElementById('paySubscriptionForm').action='{{ route('subscriptions.pay', $subscription->id) }}'"
                                                class="open-modal w-full text-center bg-[#124375] text-white py-2 navy-shadow rounded-[8px] font-medium text-sm hover:bg-[#0e3560] transition-colors">
                                                تسجيل السداد
                                            </button>
                                        @else
                                            <div class="w-full text-sm text-[#D92D20] font-medium bg-[#FFEAE8] px-2 py-2 rounded-[8px] text-center">مغلقة</div>
                                        @endif
                                    @else
                                        <div class="w-full text-sm text-[#D92D20] font-medium bg-[#FFEAE8] px-2 py-2 rounded-[8px] text-center">يجب سداد السابق أولاً</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
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