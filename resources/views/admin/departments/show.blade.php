@extends('layouts.app')

@section('title', 'عرض كلية او قطاع')

@include('partials.flash')

@section('content')

    <link rel="stylesheet" href="{{ asset('CSS/departmentsView.css') }}">

    <div class="py-7 px-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-xl text-[#124375] font-semibold">
                قائمة أعضاء {{ $department->name }}
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                إدارة بيانات الأعضاء المسجلين بالكلية ومتابعة حالتهم.
            </p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.departments.show', $department->id) }}"
        class="px-12 flex items-center justify-between gap-5">
        <div class="relative flex-1">
            <input type="search" name="search" value="{{ request('search') }}"
                placeholder="الاسم أو رقم العضوية أو رقم المطالبة"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
        </div>
        <div class="relative min-w-[150px]">
            <input type="hidden" name="status" id="status_input" value="{{ request('status', 'الكل') }}">
            <button type="button"
                class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">الحالة
                : <span class="text-[#021219] " id="status_display">{{ request('status', 'الكل') }}</span><span
                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                        class="text-xl"></iconify-icon></span></button>
            <div
                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                <button type="button" onclick="setStatus('الكل')"
                    class=" navy-shadow py-2  rounded-xl text-sm font-medium">الكل</button>
                <button type="button" onclick="setStatus('نشط')"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">نشط</button>
                <button type="button" onclick="setStatus('قيد التسجيل')"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">قيد
                    التسجيل</button>
                <button type="button" onclick="setStatus('إعارة')"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">إعارة</button>
                <button type="button" onclick="setStatus('محال للمعاش')"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">محال
                    للمعاش</button>
                <button type="button" onclick="setStatus('منسحب')"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">منسحب</button>
                <button type="button" onclick="setStatus('مفصول')"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">مفصول</button>
                <button type="button" onclick="setStatus('أجازه بدون مرتب')"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">أجازه بدون
                    مرتب</button>
                <button type="button" onclick="setStatus('منتهية العضوية')"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">منتهية
                    العضوية</button>
            </div>
        </div>
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
            </button>
        </div>
    </form>

    <!-- start table -->
    <section class="px-12 py-7">
        <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
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
                        <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $index => $member)
                    {{-- @dd($member->membershipInfo->membership_number) --}}
                        <tr class="text-center {{ $index % 2 !== 0 ? 'bg-[#EFEFEF]' : 'border-b border-[#6D6D6D]' }}">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $member->membershipInfo->membership_number ?? '-' }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $member->full_name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $member->national_id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $department->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $member->phone ?? '-' }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] ">
                                @php
                                    $dbStatusToAr = [
                                        'active' => 'نشط',
                                        'pending' => 'قيد التسجيل',
                                        'loan' => 'إعارة',
                                        'pension' => 'محال للمعاش',
                                        'withdrawn' => 'منسحب',
                                        'dismissed' => 'مفصول',
                                        'unpaid_leave' => 'أجازه بدون مرتب',
                                        'expired' => 'منتهية العضوية',
                                        'suspended' => 'موقوف'
                                    ];
                                    $statusColors = [
                                        'نشط' => 'text-[#067647] bg-[#ECFDF3] border-[#067647]',
                                        'قيد التسجيل' => 'text-[#175CD3] bg-[#EFF8FF] border-[#175CD3]',
                                        'محال للمعاش' => 'text-[#E6B800] bg-[#FFF8E1] border-[#E6B800]',
                                        'منسحب' => 'text-[#F79009] bg-[#FFF7ED] border-[#F79009]',
                                        'إعارة' => 'text-[#5925DC] bg-[#F4F3FF] border-[#5925DC]',
                                        'مفصول' => 'text-[#D92D20] bg-[#FFEAE8] border-[#D92D20]',
                                        'أجازه بدون مرتب' => 'text-[#4B5A70] bg-[#F3F6FA] border-[#4B5A70]',
                                        'منتهية العضوية' => 'text-[#021219] bg-[#F2F4F7] border-[#021219]',
                                        'موقوف' => 'text-[#021219] bg-[#F2F4F7] border-[#021219]'
                                    ];
                                    $memStatus = $member->membershipInfo->status ?? null;
                                    $arStatus = $memStatus ? ($dbStatusToAr[$memStatus] ?? 'غير محدد') : 'غير محدد';
                                    $colorClass = $statusColors[$arStatus] ?? 'text-[#021219] bg-[#F2F4F7] border-[#021219]';
                                @endphp
                                <span class="{{ $colorClass }} border rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">{{ $arStatus }}</span>
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                {{ $member->created_at->format('j F Y') }}</td>
                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                <a href="{{ route('members.show', $member->id) }}">
                                    <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-center">لا يوجد أعضاء</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <!-- end table -->

    <!-- start pagination -->
    <div class="mt-auto px-12">
        <hr class="border border-[#A8A8A8] mt-5 mb-4">
        <div class="mb-4">
            {{ $members->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
    <!-- end pagination -->

    <script>
        function setStatus(status) {
            document.getElementById('status_input').value = status;
            document.getElementById('status_display').innerText = status;
        }
    </script>
    <script src="{{ asset('JS/departmentsView.js') }}"></script>

@endsection
