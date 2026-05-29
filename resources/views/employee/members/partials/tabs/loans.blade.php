{{-- 
    Member Loans Tab Partial:
    Displays a member's active loan details, installment history, 
    and handles payment recording for specific loan installments.
--}}
    <!-- loan table -->
    @if ($activeLoan)
        <div class="tab-content hidden mx-7 rounded-[12px] bg-[#F4F7F9] border-2 border-[#124375] py-3 px-3 my-2"
            data-tab="قروض">
            <div class="flex flex-col md:flex-row flex-wrap items-start md:items-center justify-between gap-4">
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">رقم القرض : <span
                            class="text-[16px] text-[#021219]">{{ $activeLoan->id }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">قيمة القرض : <span
                            class="text-[16px] text-[#021219]">{{ number_format($activeLoan->amount, 2) }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">قيمة القرض بالفائدة : <span
                            class="text-[16px] text-[#021219]">{{ number_format($activeLoan->amount, 2) }}</span></p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">إجمالي المتبقي : <span
                            class="text-[16px] text-[#021219]">{{ number_format($activeLoan->installments->where('status', 'unpaid')->sum('amount'), 2) }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">عدد الأقساط المتبقية : <span
                            class="text-[16px] text-[#021219]">{{ $activeLoan->installments->where('status', 'unpaid')->count() }}
                            قسط</span></p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">تاريخ إنتهاء القرض : <span
                            class="text-[16px] text-[#021219]">{{ $activeLoan->installments->last() ? \Carbon\Carbon::parse($activeLoan->installments->last()->due_date)->format('Y-m-d') : 'غير محدد' }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-[#6D6D6D] text-[14px]">حالة القرض :
                        @if ($activeLoan->status === 'overdue')
                            <span
                                class="inline-block px-2 text-center text-[#F79009] bg-[#FFF7ED] border border-[#F79009] rounded-[8px] py-[1px]">متأخر</span>
                        @elseif ($activeLoan->status === 'completed')
                            <span
                                class="inline-block px-2 text-center text-[#124375] bg-[#EEF7FF] border border-[#124375] rounded-[8px] py-[1px]">مكتمل</span>
                        @elseif ($activeLoan->status === 'active')
                            <span
                                class="inline-block px-2 text-center text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px]">نشط</span>
                        @elseif ($activeLoan->status === 'pending')
                            <span
                                class="inline-block px-2 text-center text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] rounded-[8px] py-[1px]">تحت
                                المراجعة</span>
                        @elseif ($activeLoan->status === 'rejected')
                            <span
                                class="inline-block px-2 text-center text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[1px]">مرفوض</span>
                        @else
                            <span
                                class="inline-block px-2 text-center text-[#6D6D6D] bg-[#EFEFEF] border border-[#6D6D6D] rounded-[8px] py-[1px]">{{ $activeLoan->status }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div id="loans-content-container" data-tab="قروض"
        class="{{ $activeTabName === 'قروض' ? '' : 'hidden' }}  tab-content px-7 py-5 print:hidden">
        @if ($activeLoan && $activeLoan->installments->count() > 0)
            <div class="rounded-[14px] overflow-x-auto border border-[#D1D5DB]">
                <table class="w-full md:min-w-max md:whitespace-nowrap" id="installments-table">
                    <thead class="hidden md:table-header-group">
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم القسط</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الإستحقاق</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ السداد</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">طريقة الدفع</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group">
                        @foreach ($activeLoan->installments as $index => $installment)
                            <tr class="block md:table-row bg-white md:bg-transparent shadow-sm md:shadow-none rounded-xl md:rounded-none mb-4 md:mb-0 border md:border-none border-gray-200 text-right md:text-center {{ $loop->even ? 'md:bg-[#EFEFEF]' : '' }}">
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">رقم القسط:</span>
                                    <span>{{ $index + 1 }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">المبلغ:</span>
                                    <span>{{ number_format($installment->amount, 2) }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l">
                                    <span class="md:hidden font-bold text-[#124375]">الحالة:</span>
                                    @if ($installment->status === 'paid')
                                        <span class="bg-[#ECFDF333] text-[#067647CC] border border-[#067647CC] px-4 md:px-12 py-1 text-sm rounded-lg">مدفوع</span>
                                    @elseif($installment->status === 'unpaid' && \Carbon\Carbon::parse($installment->due_date)->isPast())
                                        <span class="bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] px-4 md:px-12 py-1 text-sm rounded-lg">متأخر</span>
                                    @else
                                        <span class="bg-[#F2F4F7] text-[#6D6D6D] border border-[#6D6D6D] px-4 md:px-12 py-1 text-sm rounded-lg">مستحق</span>
                                    @endif
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">تاريخ الإستحقاق:</span>
                                    <span>{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">تاريخ السداد:</span>
                                    <span>{{ $installment->paid_at ? \Carbon\Carbon::parse($installment->paid_at)->format('Y-m-d') : '-' }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">طريقة الدفع:</span>
                                    <span>{{ match ($installment->transaction?->method ?? '') {'cash' => 'نقدي','bank_transfer' => 'تحويل بنكي','salary_deduction' => 'خصم من المرتب','university_payment_order' => 'أمر دفع من الجامعة',default => '-'} }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-5 border-b-0 md:border-b md:border-[#D1D5DB]">
                                    <span class="md:hidden font-bold text-[#124375]">الإجراء:</span>
                                    @php
                                        $receipt = \App\Models\Membership\Attachment::where('member_id', $member->id)
                                            ->where('type', "installment_{$installment->id}_receipt")
                                            ->first();
                                    @endphp
                                    @if ($installment->status === 'paid')
                                        <div class="text-2xl flex gap-4 md:gap-7 items-center justify-center text-[#124375]">
                                            @if ($receipt)
                                                <a href="{{ route('documents.view', $receipt->id) }}" target="_blank"
                                                    class="hover:text-[#0e3560] transition-colors" title="عرض الإيصال">
                                                    <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                                </a>
                                                <a href="{{ route('documents.download', $receipt->id) }}"
                                                    class="hover:text-[#0e3560] transition-colors" title="تحميل الإيصال">
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
                                        @if (auth()->user() && auth()->user()->hasPermission('إدارة القروض'))
                                            <div>
                                                <button data-modal="modal5"
                                                    onclick="document.getElementById('payInstallmentForm').action='{{ route('loans.installments.pay', $installment->id) }}'"
                                                    class="open-modal bg-[#124375] text-[14px] md:text-[16px] text-[#F4F7F9] navy-shadow rounded-[10px] py-1.5 md:py-2 px-3 md:px-4">
                                                    تسجيل السداد
                                                </button>
                                            </div>
                                        @endif
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
                    <img src="{{ asset('IMGs/no-requests.png') }}" alt="no-loans">
                    <p>لا يوجد قروض مسجلة حالياً</p>
                </div>
            </div>
        @endif
    </div>
    <!-- loan table -->

