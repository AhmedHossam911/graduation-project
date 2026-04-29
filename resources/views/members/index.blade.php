@extends('layouts.app')

@section('title', 'قائمة الأعضاء')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-[24px] font-bold text-[#124375]">قائمة الأعضاء</h2>
        <a href="{{ route('members.create') }}"
            class="inline-flex items-center surface-shadow gap-2 bg-[#124375] text-white py-4 px-5 rounded-xl font-semibold transition-colors duration-150 hover:bg-primary-light w-[322px] h-[50px] justify-center">
            <iconify-icon icon="ic:round-group-add" width="24" height="24"></iconify-icon>
            تسجيل عضو جديد
        </a>
    </div>

    <form action="{{ route('members.index') }}" method="GET">
        <div class="flex flex-wrap gap-4 mb-6">
            <!-- start search -->
            <div class="flex-1 items-center gap-5">
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder=" الاسم  أو  رقم العضوية  أو  الرقم القومي" icon="bitcoin-icons:search-outline"
                    class="w-full rounded-xl py-2 pr-2 surface-shadow outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
            </div>
            <!-- end search -->

            <div class="relative min-w-[200px]">
                @php
                    $deptOptions = ['all' => 'جميع الجهات'];
                    if (isset($departments) && $departments->count() > 0) {
                        foreach ($departments as $department) {
                            $deptOptions[$department->id] = $department->name;
                        }
                    }
                @endphp
                @include('partials.dropdown', [
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

            <div class="relative min-w-[200px]">
                @php
                    $statusOptions = ['all' => 'الكل'];
                    if (isset($statusMap)) {
                        foreach ($statusMap as $key => $statusData) {
                            $statusOptions[$key] = $statusData['label'];
                        }
                    }
                @endphp
                @include('partials.dropdown', [
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
            <button class="bg-[#124375] text-white rounded-xl px-7 surface-shadow">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl"></iconify-icon>
            </button>
        </div>
    </form>

    <!-- start table -->
    <section>
        <div class="rounded-2xl overflow-hidden surface-shadow">
            <table class="w-full">
                <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                    <th class="py-3 border-l border-[#6D6D6D]">رقم العضوية</th>
                    <th class="py-3 border-l border-[#6D6D6D]">اسم العضو</th>
                    <th class="py-3 border-l border-[#6D6D6D]">الرقم القومي</th>
                    <th class="py-3 border-l border-[#6D6D6D]">الجهة</th>
                    <th class="py-3 border-l border-[#6D6D6D]">رقم الهاتف</th>
                    <th class="py-3 border-l border-[#6D6D6D]">الحالة</th>
                    <th class="py-3 border-l border-[#6D6D6D]">تاريخ الانضمام</th>
                    <th class="py-3 border-l border-[#6D6D6D]">الإجراءات</th>
                </tr>
                @if ($members->count() > 0)
                    @foreach ($members as $member)
                        <tr class="text-center even:bg-[#F4F7F9] odd:bg-[#EFEFEF]">
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ $member->membershipInfo->membership_number ?? '-' }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">{{ $member->full_name }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">{{ $member->national_id }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ $member->department?->name ?? '-' }}
                            </td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">{{ $member->phone }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                @php
                                    $statusCode = $member->membershipInfo->status ?? 'unknown';
                                    $statusData = $statusMap[$statusCode] ?? [
                                        'label' => 'غير معروف',
                                        'class' => 'unknown',
                                    ];
                                    // Map CSS classes for visual consistency
                                    $classMap = [
                                        'active' => 'text-[#067647] border-[#067647] bg-[#ECFDF3]',
                                        'pending' => 'text-[#175CD3] border-[#175CD3] bg-[#EFF8FF]',
                                        'loan' => 'text-[#5925DC] border-[#5925DC] bg-[#5925DC]',
                                        'pension' => 'text-[#E6B800] border-[#E6B800] bg-[#FFF8E1]',
                                        'withdrawn' => 'text-[#F79009] border-[#F79009] bg-[#F79009]',
                                        'dismissed' => 'text-[#D92D20] border-[#D92D20] bg-[#FFEAE8]',
                                        'unpaid_leave' => 'text-[#4B5A70] border-[#4B5A70] bg-[#4B5A70]',
                                        'expired' => 'text-[#021219] border-[#021219] bg-[#021219]',
                                        'suspended' => 'text-[#D92D20] border-[#D92D20] bg-[#FFEAE8]',
                                    ];
                                    $badgeClass = $classMap[$statusCode] ?? 'text-gray-500 border-gray-400';
                                @endphp
                                <span class="{{ $badgeClass }} w-[127px] py-1 block mx-auto rounded-full border bg-white text-center font-medium">
                                    {{ $statusData['label'] }}
                                </span>
                            </td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ $member->created_at->isoFormat('D MMMM YYYY', 'ar') }}
                            </td>
                            <td class="p-3 border-l border-b border-[#6D6D6D]">
                                <a href="{{ route('members.show', $member->id) }}">
                                    <iconify-icon
                                        class="text-[#124375] hover:scale-110 hover:rounded-md transition-all hover:duration-1000 hover:border-[1px] hover:border-[#124375] hover:p-1 cursor-pointer"
                                        icon="ic:baseline-remove-red-eye" width="24" height="24"></iconify-icon> </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500 text-lg">لا توجد بيانات متاحة حالياً</td>
                    </tr>
                @endif
            </table>
        </div>
    </section>

    <div class="sticky bottom-0 bg-[#F4F7FE] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $members->links() }}
    </div>

@endsection
