@extends('layouts.app')

@section('title', 'لوحة تحكم الإدارة')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/admindashboard.css') }}">
    <div class="py-7 px-12">
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
    <div class="py-4 grid grid-cols-4 gap-4 px-12">
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
        class="px-12 flex items-center justify-between gap-5 py-3">
        <div class="relative flex-1">
            <input type="search" id="globalSearchInput" autocomplete="off"
                placeholder="الاسم أو رقم العضوية أو رقم الحركة أو رقم القرض أو رقم المطالبة أو رقم الإيصال"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>

            <!-- Search Results Dropdown -->
            <div id="searchResultsContainer"
                class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 z-50 max-h-96 overflow-y-auto">
                <ul id="searchResultsList" class="py-2">
                    <!-- Results will be injected here -->
                </ul>
                <div id="searchLoading" class="hidden py-4 text-center text-gray-500">جاري البحث...</div>
            </div>
        </div>

        <input type="hidden" name="year" id="yearInput" value="{{ $year }}">
        <div class="relative min-w-[150px]">
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
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
            </button>
        </div>
    </form>

    <!-- Charts section -->
    <div class="px-12 py-6 grid grid-cols-2 gap-6">
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
    <div class="px-12 py-6 grid grid-cols-5 gap-6">
        <!-- Latest Disbursements Table -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 col-span-3">
            <div class="flex items-center  gap-2 mb-4 border-b pb-2">
                <iconify-icon icon="mdi:cash-multiple" class="text-xl text-[#D4AF37]"></iconify-icon>
                <h2 class="text-[#124375] text-lg text-right font-semibold">أحدث عمليات الصرف</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
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
                                    {{ $transaction->membership->member->full_name ?? 'غير معروف' }}</td> --}}
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
                                    @if (Route::has('finance.show'))
                                        <a href="{{ route('finance.show', $transaction->id) }}"
                                            class="text-[#124375] hover:text-[#0e3560]">
                                            <iconify-icon icon="mdi:eye" class="text-xl"></iconify-icon>
                                        </a>
                                    @else
                                        <iconify-icon icon="mdi:eye" class="text-xl opacity-50"></iconify-icon>
                                    @endif
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
            </div>
        </div>
        <!-- Faculty Participation Pie Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 col-span-2 flex flex-col items-center">
            <h2 class="text-center text-[#6D6D6D] text-lg font-semibold mb-6">نسب مشاركة الكليات في الصندوق</h2>
            <div class="relative w-full max-w-[300px] aspect-square flex-1">
                <canvas id="facultyChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Register DataLabels plugin
                Chart.register(ChartDataLabels);

                const monthsLabels = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر',
                    'أكتوبر', 'نوفمبر', 'ديسمبر'
                ];

                // 1. Installments Chart
                const ctxInstallments = document.getElementById('installmentsChart').getContext('2d');
                new Chart(ctxInstallments, {
                    type: 'bar',
                    data: {
                        labels: monthsLabels,
                        datasets: [{
                                label: 'أقساط متأخرة',
                                data: @json($lateInstallments),
                                backgroundColor: '#D4AF37', // Gold
                            },
                            {
                                label: 'أقساط تم تحصيلها',
                                data: @json($paidInstallments),
                                backgroundColor: '#124375', // Navy
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true
                                }
                            },
                            datalabels: {
                                display: false
                            } // Hide labels for stacked
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
                                data: @json($revenues),
                                backgroundColor: '#124375', // Navy
                            },
                            {
                                label: 'مصروفات',
                                data: @json($expenses),
                                backgroundColor: '#D4AF37', // Gold
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true
                                }
                            },
                            datalabels: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // 3. Faculty Chart
                const ctxFaculty = document.getElementById('facultyChart').getContext('2d');
                new Chart(ctxFaculty, {
                    type: 'pie',
                    data: {
                        labels: @json($facultyLabels),
                        datasets: [{
                            data: @json($facultyData),
                            backgroundColor: @json($facultyColors),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            datalabels: {
                                display: false
                            }
                        }
                    }
                });
            });
        </script>
    @endpush

    <script src="{{ asset('JS/admin/dashboard.js') }}"></script>
@endsection
