@extends('layouts.app')
{{--
    Members Index View (Employee):
    Displays the list of all members with search and filtering by department or status.
    Provides the primary gateway to view individual member profiles.
--}}

@section('title', 'قائمة الأعضاء')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 px-4 md:px-0">
        <h2 class="text-[24px] font-bold text-[#124375]">قائمة الأعضاء</h2>
        <a href="{{ route('members.create') }}"
            class="inline-flex items-center surface-shadow gap-2 bg-[#124375] text-white py-4 px-5 rounded-xl font-semibold transition-colors duration-150 hover:bg-primary-light w-full md:w-[322px] h-[50px] justify-center">
            <iconify-icon icon="ic:round-group-add" width="24" height="24"></iconify-icon>
            تسجيل عضو جديد
        </a>
    </div>

    <form action="{{ route('members.index') }}" method="GET" class="px-4 md:px-0">
        <div class="flex flex-wrap items-center gap-4 mb-6">
            <!-- start search -->
            <div class="relative flex-grow min-w-[280px] w-full md:w-auto">
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي"
                    class="pr-10 pl-4 py-2.5 w-full outline-none surface-shadow bg-white md:bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
                <iconify-icon icon="mynaui:search"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
            </div>
            <!-- end search -->

            <div class="relative w-full md:w-[200px] shrink-0">
                @php
                    $deptOptions = ['all' => 'جميع الجهات'];
                    if (isset($departments) && $departments->count() > 0) {
                        foreach ($departments as $department) {
                            $deptOptions[$department->id] = $department->name;
                        }
                    }
                @endphp
                @include('partials.common.dropdown', [
                    'name' => 'department',
                    'label' => 'الجهة',
                    'options' => $deptOptions,
                    'selected' => request('department', 'all'),
                    'required' => false,
                    'clearable' => true,
                    'autoSubmit' => true,
                    'showConfirm' => false,
                ])
            </div>

            <div class="relative w-full md:w-[200px] shrink-0">
                @php
                    $statusOptions = ['all' => 'الكل'];
                    if (isset($statusMap)) {
                        foreach ($statusMap as $key => $statusData) {
                            $statusOptions[$key] = $statusData['label'];
                        }
                    }
                @endphp
                @include('partials.common.dropdown', [
                    'name' => 'status',
                    'label' => 'الحالة',
                    'options' => $statusOptions,
                    'selected' => request('status', 'all'),
                    'clearable' => true,
                    'required' => false,
                    'autoSubmit' => true,
                    'showConfirm' => false,
                ])
            </div>

            <div class="w-full md:w-auto shrink-0">
                <button type="submit"
                    class="bg-[#124375] w-full md:w-auto text-white rounded-xl px-6 py-2.5 flex items-center justify-center hover:bg-[#0e3560] transition-colors surface-shadow">
                    <iconify-icon icon="bitcoin-icons:search-outline" class="text-3xl"></iconify-icon>
                </button>
            </div>
        </div>
    </form>

    <!-- start table -->
    <section class="px-4 md:px-0">
        <div class="rounded-[14px] overflow-hidden border border-[#6D6D6D] surface-shadow border-0 md:border p-0 md:p-0 bg-transparent md:bg-white">
            <div class="hidden md:block">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم العضوية</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الرقم القومي</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الجهة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم الهاتف</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ الانضمام</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                @if ($members->count() > 0)
                    @foreach ($members as $member)
                        @if ($member->user->role_id === 3)
                            <tr class="text-center bg-white border-b border-[#6D6D6D] even:bg-[#EFEFEF] text-[#021219] font-medium">
                                <td class="py-3 border-l border-[#6D6D6D]">{{ $member->membershipInfo->membership_number ?? 'لا يوجد بيانات' }}</td>
                                <td class="py-3 border-l border-[#6D6D6D]">{{ $member->user->name }}</td>
                                <td class="py-3 border-l border-[#6D6D6D]">{{ $member->user->national_id }}</td>
                                <td class="py-3 border-l border-[#6D6D6D]">{{ $member->department?->name ?? 'لا يوجد بيانات' }}</td>
                                <td class="py-3 border-l border-[#6D6D6D]">{{ $member->phone }}</td>
                                <td class="py-3 border-l border-[#6D6D6D]">
                                    @php
                                        $statusCode = $member->membershipInfo->status ?? 'unknown';
                                        $statusData = $statusMap[$statusCode] ?? [
                                            'label' => 'غير معروف'
                                        ];

                                        $badgeClass = match ($statusCode) {
                                            'active' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
                                            'pending_registration' => 'bg-[#EFF8FF] text-[#175CD3] border-[#175CD3]',
                                            'loaned' => 'bg-[#F9F5FF] text-[#6941C6] border-[#6941C6]',
                                            'pension_eligible' => 'bg-[#FFFCEF] text-[#D4AF37] border-[#D4AF37]',
                                            'withdrawn' => 'bg-[#FFF7ED] text-[#F79009] border-[#F79009]',
                                            'dismissed', 'suspended' => 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]',
                                            'unpaid_leave' => 'bg-[#F2F4F7] text-[#475467] border-[#475467]',
                                            'membership_expired' => 'bg-[#F2F4F7] text-[#101828] border-[#101828]',
                                            default => 'bg-[#F2F4F7] text-[#475467] border-[#475467]',
                                        };
                                    @endphp
                                    <span class="{{ $badgeClass }} flex w-[130px] mx-auto items-center justify-center border px-3 py-1 text-sm rounded-[10px] font-medium">
                                        {{ $statusData['label'] }}
                                    </span>
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D]">{{ $member->membershipInfo?->created_at?->isoFormat('D MMMM YYYY', 'ar') ?? 'لا يوجد بيانات' }}</td>
                                <td class="py-3 flex justify-center items-center gap-2">
                                    <a href="{{ route('members.show', ['member' => $member->id, 'tab' => 'subscriptions']) }}">
                                        <iconify-icon
                                            class="text-[#124375] hover:scale-110 hover:rounded-md transition-all hover:duration-1000 hover:border-[1px] hover:border-[#124375] hover:p-1 cursor-pointer"
                                            icon="ic:baseline-remove-red-eye" width="24" height="24"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500 text-lg">
                            <img class="mx-auto w-[20%]" src="{{ asset('IMGs/No-results.png') }}"
                                alt="لا توجد بيانات">
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden flex flex-col gap-4 md:gap-6 p-4">
                @if ($members->count() > 0)
                    @foreach ($members as $member)
                        @if ($member->user->role_id === 3)
                            @php
                                $statusCode = $member->membershipInfo->status ?? 'unknown';
                                $statusData = $statusMap[$statusCode] ?? [
                                    'label' => 'غير معروف'
                                ];

                                $badgeClass = match ($statusCode) {
                                    'active' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
                                    'pending_registration' => 'bg-[#EFF8FF] text-[#175CD3] border-[#175CD3]',
                                    'loaned' => 'bg-[#F9F5FF] text-[#6941C6] border-[#6941C6]',
                                    'pension_eligible' => 'bg-[#FFFCEF] text-[#D4AF37] border-[#D4AF37]',
                                    'withdrawn' => 'bg-[#FFF7ED] text-[#F79009] border-[#F79009]',
                                    'dismissed', 'suspended' => 'bg-[#FFEAE8] text-[#D92D20] border-[#D92D20]',
                                    'unpaid_leave' => 'bg-[#F2F4F7] text-[#475467] border-[#475467]',
                                    'membership_expired' => 'bg-[#F2F4F7] text-[#101828] border-[#101828]',
                                    default => 'bg-[#F2F4F7] text-[#475467] border-[#475467]',
                                };
                            @endphp
                            <div class="bg-white rounded-[14px] border border-[#6D6D6D] p-4 flex flex-col gap-3 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-2 h-full bg-[#124375]"></div>
                                <div class="flex justify-between items-start">
                                    <div class="flex flex-col gap-1 mr-3">
                                        <h3 class="text-[#021219] font-bold text-lg">{{ $member->user->name }}</h3>
                                        <span class="text-xs text-[#6D6D6D]">رقم العضوية: {{ $member->membershipInfo->membership_number ?? 'لا يوجد بيانات' }}</span>
                                    </div>
                                    <span class="{{ $badgeClass }} border rounded-[8px] py-[2px] px-3 inline-block text-xs text-center font-medium">
                                        {{ $statusData['label'] }}
                                    </span>
                                </div>
                                <div class="flex flex-col gap-2 mt-2 mr-3">
                                    <div class="flex gap-2 items-center text-sm">
                                        <iconify-icon icon="mdi:id-card" class="text-[#6D6D6D]"></iconify-icon>
                                        <span class="text-[#6D6D6D]">الرقم القومي:</span>
                                        <span class="text-[#021219] font-semibold">{{ $member->user->national_id }}</span>
                                    </div>
                                    <div class="flex gap-2 items-center text-sm">
                                        <iconify-icon icon="mdi:office-building" class="text-[#6D6D6D]"></iconify-icon>
                                        <span class="text-[#6D6D6D]">الجهة:</span>
                                        <span class="text-[#021219] font-semibold">{{ $member->department?->name ?? 'لا يوجد بيانات' }}</span>
                                    </div>
                                    <div class="flex gap-2 items-center text-sm">
                                        <iconify-icon icon="mdi:phone" class="text-[#6D6D6D]"></iconify-icon>
                                        <span class="text-[#6D6D6D]">رقم الهاتف:</span>
                                        <span class="text-[#021219] font-semibold">{{ $member->phone }}</span>
                                    </div>
                                    <div class="flex gap-2 items-center text-sm">
                                        <iconify-icon icon="mdi:calendar" class="text-[#6D6D6D]"></iconify-icon>
                                        <span class="text-[#6D6D6D]">تاريخ الانضمام:</span>
                                        <span class="text-[#021219] font-semibold">{{ $member->membershipInfo?->created_at?->isoFormat('D MMMM YYYY', 'ar') ?? 'لا يوجد بيانات' }}</span>
                                    </div>
                                </div>
                                <div class="flex justify-end mt-2 pt-2 border-t border-gray-100 mr-3">
                                    <a href="{{ route('members.show', ['member' => $member->id, 'tab' => 'subscriptions']) }}"
                                        class="flex w-full items-center justify-center bg-[#124375] text-white px-4 py-2.5 rounded-[8px] text-sm hover:bg-[#0e3560] transition-colors">
                                        <iconify-icon icon="ic:baseline-remove-red-eye" class="text-lg ml-2"></iconify-icon>
                                        عرض الملف
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">
                        <img class="mx-auto w-[40%]" src="{{ asset('IMGs/No-results.png') }}" alt="لا توجد بيانات">
                        <p class="text-[#6D6D6D] font-medium mt-2">لا توجد بيانات</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-4 md:-mx-6 px-4 md:px-6 backdrop-blur-md bg-white/80">
        {{ $members->links() }}
    </div>
@endsection

