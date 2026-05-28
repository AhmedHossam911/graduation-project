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
                        @if ($member->user->role_id === 3)
                            <tr class="text-center even:bg-[#EFEFEF] odd:bg-[#F4F7F9] text-[#021219] font-medium">
                                <td class="px-3 py-3 border-l border-b border-[#6D6D6D]">
                                    {{ $member->membershipInfo->membership_number ?? 'لا يوجد بيانات' }}</td>
                                <td class="px-3 py-3 border-l border-b border-[#6D6D6D]">{{ $member->full_name }}</td>
                                <td class="px-3 py-3 border-l border-b border-[#6D6D6D]">{{ $member->national_id }}</td>
                                <td class="px-3 py-3 border-l border-b border-[#6D6D6D]">
                                    {{ $member->department?->name ?? 'لا يوجد بيانات' }}
                                </td>
                                <td class="px-3 py-3 border-l border-b border-[#6D6D6D]">{{ $member->phone }}</td>
                                <td class="py-3 ص border-l border-b border-[#6D6D6D]">
                                    @php
                                        $statusCode = $member->membershipInfo->status ?? 'unknown';
                                        $statusData = $statusMap[$statusCode] ?? [
                                            'label' => 'غير معروف'
                                        ];

                                        $badgeClass = match ($statusCode) {
                                            'active' => 'bg-[#ECFDF3] text-[#067647] border-[#067647]',
                                            'registering', 'pending_registration' => 'bg-[#EFF8FF] text-[#175CD3] border-[#175CD3]',
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
                                <td class="px-3 py-3 border-l border-b border-[#6D6D6D]">
                                    {{ $member->membershipInfo?->created_at?->isoFormat('D MMMM YYYY', 'ar') ?? 'لا يوجد بيانات' }}
                                </td>
                                <td class="p-3 border-l border-b border-[#6D6D6D]">
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
                                alt="ممتاز! لا يوجد أعضاء متأخرين يستوجب إنذارهم حالياً.">
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    </section>

@endsection
@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $members->links() }}
    </div>
@endsection
