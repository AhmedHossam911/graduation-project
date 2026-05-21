@extends('layouts.app')
@section('title', 'تفاصيل القرض')
@section('content')
    <!-- start header -->
    <div class="py-4 px-12">
        <h1 class="text-[#124375] text-[28px] font-semibold flex items-center gap-2">
            <a href="{{ route('loans.index') }}" class="hover:text-[#0e3560] transition-colors">
                القروض
            </a>
            <span>/ تفاصيل القرض</span>
        </h1>
        <p class="text-[#124375] text-[18px] font-medium mt-2">عرض بيانات القرض والأقساط الخاصة به</p>
    </div>
    <!-- end header -->

    @php
        $paymentStatusClasses = [
            'paid' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'unpaid' => 'bg-[#F2F4F7] text-[#6D6D6D] border-[#6D6D6D]',
            'overdue' => 'bg-[#FFEAE880] text-[#D92D20] border-[#D92D20]',
            'pending' => 'bg-[#FFFCEF] text-[#D4AF37] border-[#D4AF37]',
            'active' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'completed' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
            'rejected' => 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]',
        ];
        $paymentStatusLabels = [
            'paid' => 'مدفوع',
            'unpaid' => 'مستحق',
            'overdue' => 'متأخر',
            'pending' => 'تحت المراجعة',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'rejected' => 'مرفوض',
        ];

        $paidInstallments = $loan->installments->where('status', 'paid');
        $remainingInstallments = $loan->installments->where('status', '!=', 'paid')->count();
        $remainingAmount = max((float) $loan->total_amount - (float) $paidInstallments->sum('amount'), 0);
    @endphp

    <section class="px-12 py-5 space-y-6">
        @if(session('success'))
            <div class="mb-4 bg-[#ECFDF3] text-[#067647] border border-[#067647] p-4 rounded-xl flex items-center gap-3">
                <iconify-icon icon="healthicons:yes" class="text-2xl"></iconify-icon>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] p-4 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Loan Details Card -->
        <div class="rounded-[12px] bg-[#F4F7F9] border-2 border-[#124375] py-5 px-6 shadow-sm">
            <h2 class="text-xl font-semibold text-[#124375] mb-5">بيانات القرض الأساسية</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-4 text-center">
                <div class="space-y-1">
                    <p class="text-[#6D6D6D] text-sm">اسم العضو</p>
                    <p class="text-base font-semibold text-[#021219]">
                        <a href="{{ route('members.show', ['member' => $loan->membership->member->id, 'tab' => 'loans']) }}" class="hover:text-[#124375] underline">
                            {{ $loan->membership->member->full_name ?? 'غير متوفر' }}
                        </a>
                    </p>
                </div>
                <div class="space-y-1">
                    <p class="text-[#6D6D6D] text-sm">رقم العضوية</p>
                    <p class="text-base font-semibold text-[#021219]">{{ $loan->membership->membership_number ?? '-' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[#6D6D6D] text-sm">رقم القرض</p>
                    <p class="text-base font-semibold text-[#021219]">{{ $loan->id }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[#6D6D6D] text-sm">تاريخ الطلب</p>
                    <p class="text-base font-semibold text-[#021219]">{{ $loan->created_at?->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[#6D6D6D] text-sm">قيمة القرض</p>
                    <p class="text-base font-semibold text-[#021219]">{{ number_format($loan->total_amount, 2) }} ج.م</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[#6D6D6D] text-sm">قيمة القسط</p>
                    <p class="text-base font-semibold text-[#021219]">{{ number_format($loan->installment_amount, 2) }} ج.م</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[#6D6D6D] text-sm">المبلغ المتبقي</p>
                    <p class="text-base font-semibold text-[#021219]">{{ number_format($remainingAmount, 2) }} ج.م</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[#6D6D6D] text-sm">الحالة</p>
                    <p>
                        <span class="{{ $paymentStatusClasses[$loan->status] ?? 'bg-[#F4F7F9] text-[#6D6D6D] border-[#6D6D6D]' }} text-sm border px-3 py-1 rounded-[8px]">
                            {{ $paymentStatusLabels[$loan->status] ?? $loan->status }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Installments Table -->
        <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB] bg-white shadow-sm">
            <h3 class="text-lg font-semibold text-[#124375] px-6 py-4 bg-[#F9FAFB] border-b border-[#D1D5DB]">جدول الأقساط</h3>
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم القسط</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الاستحقاق</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">المبلغ</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">الحالة</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ السداد</th>
                        <th class="py-4 font-medium text-[#021219]">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loan->installments->sortBy('due_date')->values() as $index => $installment)
                        <tr class="text-center border-b border-[#D1D5DB] even:bg-[#EFEFEF]">
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $index + 1 }}</td>
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ $installment->due_date?->isoFormat('D MMMM YYYY') }}</td>
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">{{ number_format($installment->amount, 2) }} ج.م</td>
                            <td class="py-4 border-l border-[#D1D5DB]">
                                <span class="{{ $paymentStatusClasses[$installment->status] ?? 'bg-[#F4F7F9] text-[#6D6D6D] border-[#6D6D6D]' }} border px-8 py-1 text-sm rounded-lg inline-block min-w-[100px]">
                                    {{ $paymentStatusLabels[$installment->status] ?? $installment->status }}
                                </span>
                            </td>
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                {{ $installment->status === 'paid' && $installment->updated_at ? $installment->updated_at->isoFormat('D MMMM YYYY') : '---' }}
                            </td>
                            <td class="py-4">
                                @if(in_array($installment->status, ['unpaid', 'overdue']))
                                    <button type="button" class="open-modal bg-[#124375] text-[14px] text-[#F4F7F9] navy-shadow rounded-[10px] py-1.5 px-4 hover:bg-[#0e3560] transition-colors"
                                        data-modal="paymentModal"
                                        onclick="openPaymentModal({{ $loan->id }}, {{ $installment->amount }}, '{{ $loan->membership->member->full_name }}', {{ $installment->id }})">
                                        تسجيل السداد
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <!-- Payment Modal -->
    <div id="paymentModal" class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button type="button" class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1" onclick="closePaymentModal()">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-7">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">تسجيل سداد</h1>
            </div>
            <form id="paymentForm" method="POST" action="">
                @csrf
                <div class="space-y-7">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative w-full">
                            <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">اسم العضو</label>
                            <input type="text" id="paymentMemberName" disabled class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center text-[#6D6D6D]">
                        </div>
                        <div class="relative w-full">
                            <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">قيمة القسط</label>
                            <input type="text" id="paymentAmount" disabled class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center text-[#6D6D6D]">
                        </div>
                    </div>
                    
                    <input type="hidden" name="installment_ids[]" id="paymentInstallmentId" value="">

                    <div class="flex justify-between items-center bg-white px-5 py-3 border border-[#D1D5DB] rounded-lg">
                        <p class="text-[#021219] font-medium">تأكيد السداد</p>
                        <div class="flex items-center">
                            <input type="checkbox" id="confirmPayment" class="w-5 h-5 text-[#124375] bg-gray-100 border-gray-300 rounded focus:ring-[#124375] focus:ring-2 cursor-pointer" required onchange="document.getElementById('submitPaymentBtn').disabled = !this.checked; document.getElementById('submitPaymentBtn').classList.toggle('btn-disabled', !this.checked); document.getElementById('submitPaymentBtn').classList.toggle('opacity-50', !this.checked);">
                        </div>
                    </div>
                </div>
                <div class="btns flex gap-2 mt-7">
                    <button type="submit" id="submitPaymentBtn" disabled class="submit-btn rounded-[14px] w-full py-3 btn-disabled opacity-50 text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow transition-colors">
                        <span><iconify-icon icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>
                        تأكيد السداد
                    </button>
                    <button type="button" class="modal-close border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]" onclick="closePaymentModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]" id="modalOverlay"></div>

    @push('scripts')
        <script src="{{ asset('JS/loans.js') }}"></script>
        <script>
            function openPaymentModal(loanId, amount, memberName, installmentId) {
                const modal = document.getElementById('paymentModal');
                const overlay = document.getElementById('modalOverlay');
                const form = document.getElementById('paymentForm');
                
                form.action = `/loans/${loanId}/payment`;
                document.getElementById('paymentAmount').value = amount;
                document.getElementById('paymentMemberName').value = memberName;
                document.getElementById('paymentInstallmentId').value = installmentId;
                
                document.getElementById('confirmPayment').checked = false;
                document.getElementById('submitPaymentBtn').disabled = true;
                document.getElementById('submitPaymentBtn').classList.add('btn-disabled', 'opacity-50');

                modal.classList.remove('hidden');
                overlay.classList.remove('hidden');
            }

            function closePaymentModal() {
                const modal = document.getElementById('paymentModal');
                const overlay = document.getElementById('modalOverlay');
                modal.classList.add('hidden');
                overlay.classList.add('hidden');
            }
        </script>
    @endpush
@endsection
