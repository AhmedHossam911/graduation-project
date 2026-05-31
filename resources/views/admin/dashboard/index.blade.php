@extends('layouts.app')
{{-- 
    Dashboard View:
    Displays the primary system analytics for Admins, including:
    - Active members count
    - Total granted loans
    - Fund balance
    - Charts for installments vs revenues and faculty distributions.
--}}

@section('title', 'لوحة تحكم الإدارة')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/admindashboard.css') }}">
    <div class="py-4 md:py-7 px-4 md:px-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-xl text-[#124375]  font-semibold">
                مرحبا، <span>{{ Auth::user()->name ?? 'مدير النظام' }}</span>
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                نظام إدارة الصندوق – لوحة الإدارة
            </p>
        </div>
    </div>

    <!-- start cards -->
    <div class="py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 px-4 md:px-12">
        <div
            class="navy-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
            <div>
                <iconify-icon icon="mdi:account-group"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#124375] bg-[#EEF7FF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ number_format($totalActiveMembers) }}</p>
                <p class="text-sm font-medium">عدد الأعضاء النشطين</p>
            </div>
        </div>
        <div
            class="yellow-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
            <div>
                <iconify-icon icon="fluent:money-16-filled"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#D4AF37] bg-[#FFFCEF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ number_format($totalGrantedLoans) }}</p>
                <p class="text-sm font-medium">إجمالي القروض الممنوحة</p>
            </div>
        </div>
        <div
            class="bg-[#124375] navy-shadow text-[#F4F7F9] flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#EEF7FF]">
            <div>
                <iconify-icon icon="fa7-solid:money-bill-wave"
                    class="navy-shadow text-[40px] text-[#124375]  bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center gap-2">
                <p class="text-4xl font-extrabold">{{ number_format($totalFundBalance) }}</p>
                <p class="text-sm font-medium">إجمالي رصيد الصندوق</p>
            </div>
        </div>
        <div
            class="red-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
            <div>
                <iconify-icon icon="tabler:clipboard-list-filled"
                    class="navy-shadow text-[40px] text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $pendingClaims }}</p>
                <p class="text-sm font-medium">مطالبات بانتظار الأعتماد</p>
            </div>
        </div>
    </div>
    <!-- end cards -->

    <form id="dashboardForm" action="{{ route('admin.dashboard') }}" method="GET"
        class="px-4 md:px-12 flex flex-col md:flex-row items-center justify-between gap-5 py-3">
        <div class="relative flex-1 w-full">
            <input type="search" id="globalSearchInput" autocomplete="off"
                data-search-url="{{ route('admin.search') }}"
                placeholder="الاسم أو رقم العضوية أو رقم الحركة أو رقم القرض أو رقم المطالبة أو رقم الإيصال"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>

            <!-- Search Results Modal -->
            <div id="searchModalContainer" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4">
                <!-- Overlay inside modal just for click-to-close if needed, or we use the global overlay -->
                <div id="searchResultsModal" class="w-full max-w-4xl max-h-[90vh] bg-[#F4F7F9] rounded-2xl navy-shadow flex flex-col relative z-[71]">
                    <div class="flex justify-between items-center p-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-[#124375] flex items-center gap-2">
                            <iconify-icon icon="mynaui:search"></iconify-icon>
                            نتائج البحث
                        </h2>
                        <button type="button" class="modal-close text-[#124375] text-2xl navy-shadow rounded p-1 hover:bg-gray-200 transition">
                            <iconify-icon icon="weui:close-filled"></iconify-icon>
                        </button>
                    </div>
                    <div class="overflow-y-auto p-4 flex-1">
                        <div id="searchLoading" class="hidden py-10 text-center text-[#124375]">
                            <iconify-icon icon="line-md:loading-loop" class="text-4xl"></iconify-icon>
                            <p class="mt-2 font-medium">جاري البحث...</p>
                        </div>
                        <div id="noSearchResults" class="hidden py-10 text-center text-gray-500">
                            <img src="{{ asset('IMGs/No-results.png') }}" alt="NOT FOUND" class="w-32 mx-auto mb-4">
                            <p>لا توجد نتائج مطابقة للبحث</p>
                        </div>
                        <ul id="searchResultsList" class="flex flex-col gap-3">
                            <!-- Results will be injected here via JS -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="year" id="yearInput" value="{{ $year }}">
        <div class="relative min-w-[150px] w-full md:w-auto">
            <button type="button"
                class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">التاريخ
                :<span class="text-[#021219]">{{ $year }}</span><span class="flex items-center"><iconify-icon
                        icon="uil:calender" class="text-xl"></iconify-icon></span></button>
            <div
                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                @foreach ($availableYears as $y)
                    <button type="button"
                        onclick="document.getElementById('yearInput').value='{{ $y }}'; document.getElementById('dashboardForm').submit();"
                        class=" navy-shadow py-2 rounded-xl text-sm font-medium">{{ $y }}</button>
                @endforeach
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button type="submit"
                class="w-full bg-[#124375] text-white rounded-xl px-6 py-2 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-3xl "></iconify-icon>
            </button>
        </div>
    </form>

    <!-- Charts section -->
    <div class="px-4 md:px-12 py-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Installments Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-center text-[#6D6D6D] text-lg font-semibold mb-4">موقف تحصيل أقساط القروض</h2>
            <div class="relative h-64">
                <canvas id="installmentsChart"></canvas>
            </div>
        </div>

        <!-- Revenues/Expenses Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-center text-[#6D6D6D] text-lg font-semibold mb-4">حركة الإيرادات والمصروفات</h2>
            <div class="relative h-64">
                <canvas id="revenuesExpensesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Table and Pie Chart -->
    <div class="px-4 md:px-12 py-6 grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Latest Disbursements Table -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 col-span-1 lg:col-span-3">
            <div class="flex items-center  gap-2 mb-4 border-b pb-2">
                <iconify-icon icon="mdi:cash-multiple" class="text-xl text-[#D4AF37]"></iconify-icon>
                <h2 class="text-[#124375] text-lg text-right font-semibold">أحدث عمليات الصرف</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="hidden md:table w-full text-center border-collapse">
                    <thead>
                        <tr class="bg-[#EEF7FF] text-[#6D6D6D] text-sm">
                            {{-- <th class="py-3 px-4 border border-gray-200">اسم العضو</th> --}}
                            <th class="py-3 px-4 border border-gray-200">التاريخ</th>
                            <th class="py-3 px-4 border border-gray-200">بند الحركة</th>
                            <th class="py-3 px-4 border border-gray-200">المبلغ</th>
                            <th class="py-3 px-4 border border-gray-200">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestDisbursements as $transaction)
                            <tr class="border-b border-gray-200 text-[#124375] font-medium text-sm">
                                {{-- <td class="py-3 px-4 border border-gray-200">
                                    {{ $transaction->membership->member->user->name ?? 'غير معروف' }}</td> --}}
                                <td class="py-3 px-4 border border-gray-200">
                                    {{ $transaction->created_at->translatedFormat('d F Y') }}</td>
                                @if ($transaction->type === 'IN')
                                    <td class="py-3 px-4 border border-gray-200">ايراد</td>
                                @elseif ($transaction->type === 'OUT')
                                    <td class="py-3 px-4 border border-gray-200">مصروف</td>
                                @else
                                    <td class="py-3 px-4 border border-gray-200">غير معروف</td>
                                @endif
                                <td class="py-3 px-4 border border-gray-200">{{ number_format($transaction->amount) }} ج.م
                                </td>
                                <td class="py-3 px-4 border border-gray-200">
                                    <iconify-icon icon="solar:eye-outline" class="open-modal text-xl cursor-pointer hover:text-[#0e3560]" data-modal="modal-detail-{{ $transaction->id }}"></iconify-icon>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 border border-gray-200 text-gray-500">لا توجد عمليات صرف
                                    حديثة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Cards View -->
                <div class="md:hidden flex flex-col gap-4">
                    @forelse($latestDisbursements as $transaction)
                        <div class="bg-[#F4F7F9] p-4 rounded-xl border border-gray-200 flex flex-col gap-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $transaction->created_at->translatedFormat('d F Y') }}</span>
                                @if ($transaction->type === 'IN')
                                    <span class="text-xs font-bold text-[#019168] bg-[#F0FFF6] px-2 py-1 rounded">ايراد</span>
                                @elseif ($transaction->type === 'OUT')
                                    <span class="text-xs font-bold text-[#D92D20] bg-[#FFEAE8] px-2 py-1 rounded">مصروف</span>
                                @else
                                    <span class="text-xs font-bold text-gray-600 bg-gray-200 px-2 py-1 rounded">غير معروف</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <span class="font-bold text-[#124375] text-lg">{{ number_format($transaction->amount) }} ج.م</span>
                                @if (Route::has('finance.show'))
                                    <iconify-icon icon="solar:eye-outline" class="open-modal text-2xl cursor-pointer text-[#124375] hover:text-[#0e3560] p-2 bg-white rounded-lg shadow-sm border" data-modal="modal-detail-{{ $transaction->id }}"></iconify-icon>
                                @else
                                    <iconify-icon icon="solar:eye-outline" class="text-2xl opacity-50 p-2 bg-white rounded-lg shadow-sm border"></iconify-icon>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500 border border-gray-200 rounded-xl bg-white">لا توجد عمليات صرف حديثة</div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Faculty Participation Pie Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 col-span-1 lg:col-span-2 flex flex-col">
            <h2 class="text-center text-[#6D6D6D] text-lg font-semibold mb-4">نسب مشاركة الكليات في الصندوق</h2>
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-2 h-auto lg:h-[250px] w-full">
                <!-- Chart Container -->
                <div class="relative w-full lg:w-1/2 h-[200px] lg:h-full flex justify-center items-center">
                    <canvas id="facultyChart"></canvas>
                </div>
                <!-- Custom Legend Container -->
                <div id="custom-legend" class="w-full lg:w-1/2 h-[200px] lg:h-full overflow-y-auto pl-2 pr-1 flex flex-col gap-1 custom-scrollbar">
                    <!-- Legend items will be injected here -->
                </div>
            </div>
        </div>

        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f1f1; 
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #c1c1c1; 
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8; 
            }
        </style>
    </div>

    <!-- Overlay and Modals -->
    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60] print:hidden"></div>

    @foreach ($latestDisbursements as $t)
        <!-- Detail Modal for TRX {{ $t->id }} -->
        <div id="modal-detail-{{ $t->id }}"
            class="hidden w-[95%] md:w-full max-w-3xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <button type="button"
                class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
            <div class="modal-body space-y-7 px-4 md:px-12 py-4">
                <div class="space-y-7">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="space-y-2">
                            <h1 class="text-[20px] text-[#124375] font-semibold">تفاصيل الحركة</h1>
                            <div class="flex gap-2 text-[#6D6D6D] text-sm md:text-[16px] font-medium flex-wrap">
                                <p>رقم الحركة : <span>{{ $t->transaction_number ?? 'حركة-' . $t->id }}</span></p>
                                <span>/</span>
                                <p>{{ $t->created_at->locale('ar')->translatedFormat('d F Y - h:i A') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 px-4 py-1 rounded-[10px] {{ $t->type === 'IN' ? 'bg-[#ECFDF3] text-[#067647]' : 'bg-[#FFEAE8] text-[#D92D20]' }}">
                            <iconify-icon
                                icon="{{ $t->type === 'IN' ? 'ph:arrow-down-left-bold' : 'ph:arrow-up-right-bold' }}"
                                class="text-3xl mt-1"></iconify-icon>
                            <p class="text-[16px] font-medium">{{ $t->type_label }}</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div class="flex flex-col md:flex-row gap-3">
                            <p class="text-[#124375] text-[16px] font-semibold w-full md:w-1/3">اسم العضو : <span
                                    class="text-[#021219]">{{ $t->membership?->member?->user?->name ?? '-' }}</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold w-full md:w-1/3">رقم العضوية : <span
                                    class="text-[#021219]">{{ $t->membership?->membership_number ?? '-' }}</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold w-full md:w-1/3">المبلغ الإجمالي : <span
                                    class="text-[#021219]">{{ number_format($t->amount, 2) }}</span></p>
                        </div>
                        <div class="flex flex-col md:flex-row gap-3">
                            <p class="text-[#124375] text-[16px] font-semibold w-full md:w-1/3">بند الحركة : <span
                                    class="text-[#021219]">{{ $t->category_label }}</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold w-full md:w-1/3">طريقة الدفع : <span
                                    class="text-[#021219]">{{ $t->method_label }}</span></p>
                            <p class="text-[#124375] text-[16px] font-semibold w-full md:w-1/3">بواسطة : <span
                                    class="text-[#021219]">{{ $t->creator?->name ?? '-' }}</span></p>
                        </div>
                        <div class="pt-2">
                            <div class="relative w-full">
                                <label
                                    class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">البيان</label>
                                <textarea readonly
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] px-2 py-3 resize-none">{{ $t->description ?? '-' }}</textarea>
                            </div>
                        </div>

                        @if ($t->attachment_path)
                            <div class="w-full">
                                <div
                                    class="border border-[#124375] rounded-[12px] py-4 text-[#124375] flex items-center justify-center gap-1">
                                    <iconify-icon icon="solar:paperclip-outline" class="text-2xl mt-1"></iconify-icon>
                                    <a href="{{ asset('storage/' . $t->attachment_path) }}" target="_blank"
                                        class="text-[#124375] font-medium underline mt-1 block">عرض المرفق</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="btns flex gap-4 mt-8 no-print print:hidden">
                    <div class="w-full">
                        <button type="button"
                            onclick="window.open('{{ route('print.transaction', $t->id) }}', '_blank')"
                            class=" rounded-[14px] w-full py-3 bg-[#124375] navy-shadow text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2">
                            <iconify-icon icon="fluent:save-16-filled" class="text-2xl mt-1"></iconify-icon>
                            طباعة الإيصال
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Register DataLabels plugin
                Chart.register(ChartDataLabels);

                const monthsLabels = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
                const year = document.getElementById('yearInput').value;

                fetch(`{{ route('admin.dashboard.chartData') }}?year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        // 1. Installments Chart
                        const ctxInstallments = document.getElementById('installmentsChart').getContext('2d');
                        new Chart(ctxInstallments, {
                            type: 'bar',
                            data: {
                                labels: monthsLabels,
                                datasets: [{
                                        label: 'أقساط متأخرة',
                                        data: data.lateInstallments,
                                        backgroundColor: '#D4AF37', // Gold
                                    },
                                    {
                                        label: 'أقساط تم تحصيلها',
                                        data: data.paidInstallments,
                                        backgroundColor: '#124375', // Navy
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: { stacked: true },
                                    y: { stacked: true, beginAtZero: true }
                                },
                                plugins: {
                                    legend: { position: 'bottom', labels: { usePointStyle: true } },
                                    datalabels: { display: false }
                                }
                            }
                        });

                        // 2. Revenues vs Expenses Chart
                        const ctxRevExp = document.getElementById('revenuesExpensesChart').getContext('2d');
                        new Chart(ctxRevExp, {
                            type: 'bar',
                            data: {
                                labels: monthsLabels,
                                datasets: [{
                                        label: 'إيرادات',
                                        data: data.revenues,
                                        backgroundColor: '#124375', // Navy
                                    },
                                    {
                                        label: 'مصروفات',
                                        data: data.expenses,
                                        backgroundColor: '#D4AF37', // Gold
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'bottom', labels: { usePointStyle: true } },
                                    datalabels: { display: false }
                                },
                                scales: { y: { beginAtZero: true } }
                            }
                        });

                        // 3. Faculty Chart
                        const ctxFaculty = document.getElementById('facultyChart').getContext('2d');
                        const facultyChart = new Chart(ctxFaculty, {
                            type: 'doughnut',
                            data: {
                                labels: data.facultyLabels,
                                datasets: [{
                                    data: data.facultyData,
                                    backgroundColor: data.facultyColors,
                                    borderWidth: 2,
                                    borderColor: '#ffffff',
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: { display: false },
                                    datalabels: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.label || '';
                                                if (label) { label += ': '; }
                                                if (context.parsed !== null) { label += context.parsed; }
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        // Generate Custom HTML Legend
                        const legendContainer = document.getElementById('custom-legend');
                        const labels = data.facultyLabels;
                        const facultyData = data.facultyData;
                        const bgColors = data.facultyColors;
                        const total = facultyData.reduce((a, b) => Number(a) + Number(b), 0);

                        let legendHTML = '';
                        labels.forEach((label, index) => {
                            const value = facultyData[index];
                            const color = bgColors[index];
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;

                            legendHTML += `
                                <div class="flex items-center justify-between text-sm hover:bg-[#EEF7FF] p-2 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-[#124375]/20 group" 
                                     onclick="toggleChartSector(this, ${index})">
                                    <div class="flex items-center gap-2.5 w-2/3">
                                        <span class="w-3 h-3 rounded-full shadow-sm flex-shrink-0" style="background-color: ${color}"></span>
                                        <span class="text-[#6D6D6D] group-hover:text-[#124375] font-medium whitespace-nowrap overflow-hidden text-ellipsis transition-colors" title="${label}">${label}</span>
                                    </div>
                                    <div class="flex items-center gap-2 w-1/3 justify-end">
                                        <span class="text-[#124375] font-bold">${value}</span>
                                        <span class="text-[10px] text-[#D4AF37] bg-[#FFFCEF] border border-[#D4AF37]/30 px-1.5 py-0.5 rounded-md flex-shrink-0" dir="ltr">${percentage}%</span>
                                    </div>
                                </div>
                            `;
                        });
                        legendContainer.innerHTML = legendHTML;

                        window.toggleChartSector = function(element, index) {
                            const meta = facultyChart.getDatasetMeta(0);
                            const isHidden = meta.data[index].hidden;
                            
                            if (isHidden) {
                                meta.data[index].hidden = false;
                                element.style.opacity = '1';
                                element.classList.remove('grayscale');
                            } else {
                                meta.data[index].hidden = true;
                                element.style.opacity = '0.5';
                                element.classList.add('grayscale');
                            }
                            facultyChart.update();
                        };
                    });
            });
        </script>
    @endpush

    <script src="{{ asset('JS/admin/dashboard.js') }}"></script>
@endsection
