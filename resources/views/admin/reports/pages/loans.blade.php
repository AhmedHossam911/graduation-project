@extends('layouts.app')

@section('title', 'موقف القروض والسلف المنصرفة')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-12 py-5 gap-4 md:gap-0 print:hidden">
        <div>
            <h1 class="text-[24px] md:text-[32px] font-medium text-[#124375]">
                موقف القروض والسلف المنصرفة
            </h1>
            <p class="text-[#6D6D6D] text-[14px] md:text-[16px] font-normal mt-2">إجمالي القروض التي تم الموافقة عليها وصرفها للأعضاء خلال فترة محددة.</p>
        </div>
        <div class="btns flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.reports.index') }}"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 px-5 bg-[#F4F7F9] text-[#124875] font-semibold border-2
                border-[#124375] navy-shadow hover:bg-[#E2E8F0] transition-colors">
                العودة للتقارير
            </a>
            <a href="{{ route('admin.reports.export_loans', request()->query()) }}" onclick="confirmExport(event, this.href)"
                class="w-full sm:w-auto rounded-xl flex items-center justify-center py-3 gap-2 px-5 bg-[#124375] text-white navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل (Excel)
            </a>
        </div>
    </div>

    <!-- filteration buttons -->
    <form action="{{ route('admin.reports.loans') }}" method="GET" class="px-4 md:px-12 flex flex-wrap w-full items-center gap-6 print:hidden">
        <div class="relative flex-1 min-w-[200px]">
            @php
                $statusMapping = [
                    'all' => 'كل الحالات',
                    'pending' => 'قيد المراجعة',
                    'approved' => 'معتمد',
                    'active' => 'نشط',
                    'completed' => 'مكتمل',
                    'rejected' => 'مرفوض',
                ];
            @endphp
            @include('partials.common.dropdown', [
                'name' => 'status',
                'label' => 'الحالة',
                'options' => $statusMapping,
                'selected' => request('status', 'all'),
                'required' => false,
                'clearable' => false,
                'autoSubmit' => true,
                'showConfirm' => false,
            ])
        </div>
        <div class="relative flex-1 min-w-[200px]">
            @include('partials.common.calendar', [
                'name' => 'date_from',
                'id' => 'report-datepicker-from',
                'value' => request('date_from'),
                'autoSubmit' => true,
                'label' => 'من تاريخ',
                'floatingLabel' => true,
            ])
        </div>
        <div class="relative flex-1 min-w-[200px]">
            @include('partials.common.calendar', [
                'name' => 'date_to',
                'id' => 'report-datepicker-to',
                'value' => request('date_to'),
                'autoSubmit' => true,
                'label' => 'إلى تاريخ',
                'floatingLabel' => true,
            ])
        </div>
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-8 h-[46px] flex items-center justify-center hover:bg-[#0e3560] transition-colors navy-shadow mt-[5px]">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-3xl"></iconify-icon>
            </button>
        </div>
    </form>

    <section class="px-4 md:px-12 py-4 print:hidden">
        <div class="rounded-[14px] overflow-hidden border-0 md:border border-[#6D6D6D]">
            <table class="hidden md:table w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم القرض</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">قيمة القرض</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">عدد الأقساط</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">القسط الشهري</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ الصرف</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr class="text-center {{ $loop->even ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">LOAN-{{ $loan->id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $loan->membership->member->user->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#124375] font-bold">{{ number_format($loan->total_amount, 2) }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $loan->months }} شهر</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-bold">{{ number_format($loan->installment_amount, 2) }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                @if ($loan->status === 'overdue')
                                    <span class="inline-block w-[140px] text-center text-[#F79009] bg-[#FFF7ED] border border-[#F79009] rounded-[8px] py-[1px]">متأخر</span>
                                @elseif ($loan->status === 'completed')
                                    <span class="inline-block w-[140px] text-center text-[#124375] bg-[#EEF7FF] border border-[#124375] rounded-[8px] py-[1px]">مكتمل</span>
                                @elseif ($loan->status === 'active')
                                    <span class="inline-block w-[140px] text-center text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px]">نشط</span>
                                @elseif ($loan->status === 'approved')
                                    <span class="inline-block w-[140px] text-center text-[#124375] bg-[#EEF7FF] border border-[#124375] rounded-[8px] py-[1px]">معتمد</span>
                                @elseif ($loan->status === 'pending')
                                    <span class="inline-block w-[140px] text-center text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] rounded-[8px] py-[1px]">قيد المراجعة</span>
                                @elseif ($loan->status === 'rejected')
                                    <span class="inline-block w-[140px] text-center text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[1px]">مرفوض</span>
                                @else
                                    <span class="inline-block w-[140px] text-center text-[#6D6D6D] bg-[#EFEFEF] border border-[#6D6D6D] rounded-[8px] py-[1px]">{{ $loan->status }}</span>
                                @endif
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $loan->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-[#6D6D6D] font-medium">لا توجد قروض</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile Cards View -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse($loans as $loan)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex flex-col gap-3">
                        <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                            <div>
                                <h3 class="text-[#021219] font-bold text-lg">{{ $loan->membership->member->user->name }}</h3>
                                <p class="text-sm text-[#6D6D6D]">قرض: LOAN-{{ $loan->id }}</p>
                            </div>
                            <div class="text-left">
                                <p class="text-[#124375] font-bold">{{ number_format($loan->total_amount, 2) }} ج.م</p>
                                <p class="text-sm text-[#6D6D6D]">الإجمالي</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm text-[#021219]">
                            <div>
                                <p class="text-[#6D6D6D]">القسط الشهري:</p>
                                <p class="font-bold">{{ number_format($loan->installment_amount, 2) }} ج.م</p>
                            </div>
                            <div>
                                <p class="text-[#6D6D6D]">عدد الأقساط:</p>
                                <p>{{ $loan->months }} شهر</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[#6D6D6D] mb-1">الحالة:</p>
                                @php
                                    $statusColor = 'bg-[#EFEFEF] text-[#6D6D6D] border-[#6D6D6D]';
                                    $statusLabel = $loan->status;

                                    if ($loan->status === 'overdue') {
                                        $statusColor = 'bg-[#FFF7ED] text-[#F79009] border-[#F79009]';
                                        $statusLabel = 'متأخر';
                                    } elseif ($loan->status === 'completed') {
                                        $statusColor = 'bg-[#EEF7FF] text-[#124375] border-[#124375]';
                                        $statusLabel = 'مكتمل';
                                    } elseif ($loan->status === 'active') {
                                        $statusColor = 'bg-[#ECFDF3] text-[#067647] border-[#067647]';
                                        $statusLabel = 'نشط';
                                    } elseif ($loan->status === 'approved') {
                                        $statusColor = 'bg-[#EEF7FF] text-[#124375] border-[#124375]';
                                        $statusLabel = 'معتمد';
                                    } elseif ($loan->status === 'pending') {
                                        $statusColor = 'bg-[#FFF8E1] text-[#E6B800] border-[#E6B800]';
                                        $statusLabel = 'قيد المراجعة';
                                    } elseif ($loan->status === 'rejected') {
                                        $statusColor = 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]';
                                        $statusLabel = 'مرفوض';
                                    }
                                @endphp
                                <span class="inline-block text-xs text-center border rounded-[8px] py-[2px] px-3 font-medium {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="mt-1">
                                <p><span class="text-[#6D6D6D]">تاريخ الصرف:</span> {{ $loan->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500 bg-white rounded-xl border border-gray-200">لا توجد قروض</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $loans->links() }}
    </div>
@endsection

