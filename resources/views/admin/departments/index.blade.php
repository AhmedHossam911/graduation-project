@extends('layouts.app')
{{-- 
    Departments View:
    Manage university faculties/departments, view their codes, and see the total member count registered under each department.
--}}

@section('title', 'إدارة كليات وقطاعات الجامعة')

@include('partials.common.flash')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/departments.css') }}">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center py-4 md:py-7 px-4 md:px-12 gap-4 md:gap-0">
            <div class="flex flex-col gap-3">
                <div class="flex flex-col md:flex-row gap-2 md:gap-4 items-start md:items-center">
                    <h1 class="text-xl text-[#124375] font-semibold">
                        إدارة كليات وقطاعات الجامعة
                    </h1>
                    <div class="flex items-center text-[#019168] bg-[#F0FFF6] gap-2 px-2 py-1 rounded-[6px]">
                        <p>إجمالي الأعضاء المسجلين :</p>
                        <p class="mt-1 font-bold">{{ number_format($totalMembers) }}</p>
                    </div>
                </div>
                <p class="text-[#6D6D6D] text-[16px] font-normal">
                    إدارة قائمة الكليات وتعديل بياناتها، ومتابعة الأعضاء المسجلين في كل قطاع.
                </p>
            </div>
            <div class="w-full md:w-auto">
                <button
                    class="open-modal cursor-pointer hover:bg-[#0e3560] transition-colors text-[16px] navy-shadow flex items-center justify-center gap-4 bg-[#124375] text-[#F4F7F9] py-2.5 w-full md:w-auto px-6 md:px-20 rounded-[12px]"
                    data-modal="modal1">
                    <iconify-icon icon="ic:round-plus" class="text-2xl mt-1"></iconify-icon>
                    إضافة عنصر جديد
                </button>
            </div>
        </div>

        <div class="px-4 md:px-12">
            <form action="{{ route('admin.departments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 md:gap-10 items-center navy-shadow rounded-[16px] py-5 px-3">
                <div class="flex gap-2 col-span-1 md:col-span-3">
                    <div class="w-full relative">
                        <input name="search" value="{{ request('search') }}"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow w-full outline-none navy-shadow rounded-[10px] py-2 pr-8"
                            placeholder="اسم أو كود الكلية " />
                        <iconify-icon icon="ri:search-line"
                            class="text-2xl text-[#124375] absolute top-1/2 -translate-y-1/2 right-1 "></iconify-icon>
                    </div>
                    <button type="submit" class="bg-[#124375] rounded-[12px] px-6 md:px-8 shrink-0 navy-shadow hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="ri:search-line"
                            class="text-2xl text-[#F4F7F9] flex items-center"></iconify-icon>
                    </button>
                </div>
                <div class="bg-[#EAF5FF] text-center py-2 px-3 rounded-[10px] col-span-1 md:col-span-1 tab-content"
                    data-tab="الكليات الحالية">
                    <p class="text-[#124375] text-sm md:text-base">إجمالي الجهات : <span class="font-bold">{{ $totalActive }}</span></p>
                </div>
                <div class="bg-[#FFEAE8] hidden text-center py-2 px-3 rounded-[10px] col-span-1 md:col-span-1 tab-content"
                    data-tab="الأرشيف">
                    <p class="text-[#D92D20] text-sm md:text-base">إجمالي الجهات : <span class="font-bold">{{ $totalArchived }}</span></p>
                </div>
                <div class="flex flex-col sm:flex-row items-center bg-[#F4F7F9] navy-shadow col-span-1 md:col-span-2 py-2 px-3 gap-3 rounded-[8px]">
                    <button type="button"
                        class="tab bg-[#124375] flex items-center navy-shadow gap-2 text-[#EEF7FF] w-full justify-center rounded-[8px] py-2 hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="healthicons:yes" class="text-xl flex items-center"></iconify-icon>
                        <span class="text-sm md:text-base whitespace-nowrap">الكليات الحالية</span>
                    </button>
                    <button type="button"
                        class="tab text-[#124375] bg-[#EEF7FF] flex items-center gap-2 border border-[#124375] w-full justify-center rounded-[8px] py-2">
                        <iconify-icon icon="fluent:archive-16-filled" class="text-xl  flex items-center"></iconify-icon>
                        <span class="text-sm md:text-base whitespace-nowrap">الأرشيف</span>
                    </button>
                </div>
            </form>
        </div>

        <section class="px-4 md:px-12 py-8">
            <div class=" rounded-[14px] tab-content overflow-hidden border border-[#6D6D6D]" data-tab="الكليات الحالية">
                <table class="hidden md:table w-full border-collapse">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم / الوصف </th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">كود الكلية</th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">عدد الأعضاء</th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeDepartments as $index => $department)
                        <tr class="text-center {{ $index % 2 !== 0 ? 'bg-[#EFEFEF]' : 'border-b border-[#6D6D6D]' }}">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $department->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">{{ $department->code }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $department->members_count }} عضو</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                    class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                            </td>
                            <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                                <a href="{{ route('admin.departments.show', $department->id) }}">
                                    <iconify-icon icon="solar:eye-linear"
                                        class="text-2xl text-[#021219] cursor-pointer"></iconify-icon>
                                </a>
                                <button type="button" class="open-modal border-0 bg-transparent flex items-center" data-modal="modal2" data-id="{{ $department->id }}" data-name="{{ $department->name }}" data-members="{{ $department->members_count }}">
                                    <iconify-icon icon="ic:round-edit" class="text-2xl text-[#124375] cursor-pointer"></iconify-icon>
                                </button>
                                <form action="{{ route('admin.departments.archive', $department->id) }}" method="POST" class="inline m-0 p-0">
                                    @csrf
                                    <button type="submit" class="border-0 bg-transparent flex items-center">
                                        <iconify-icon icon="zondicons:close-solid" class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center">لا توجد بيانات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Cards View -->
                <div class="md:hidden flex flex-col gap-4 mt-2 px-2">
                    @forelse($activeDepartments as $department)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex flex-col gap-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-[#021219] font-bold text-lg">{{ $department->name }}</h3>
                                    <p class="text-sm text-[#6D6D6D]">كود: {{ $department->code }}</p>
                                </div>
                                <span class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 text-sm min-w-[60px] text-center">نشط</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#6D6D6D] text-sm">
                                <iconify-icon icon="mdi:account-group" class="text-xl"></iconify-icon>
                                <span>{{ $department->members_count }} عضو</span>
                            </div>
                            <div class="flex gap-4 items-center justify-end border-t border-gray-100 pt-3 mt-1">
                                <a href="{{ route('admin.departments.show', $department->id) }}" class="flex items-center justify-center p-2 bg-[#F4F7F9] rounded-lg">
                                    <iconify-icon icon="solar:eye-linear" class="text-xl text-[#021219]"></iconify-icon>
                                </a>
                                <button type="button" class="open-modal flex items-center justify-center p-2 bg-[#F4F7F9] rounded-lg" data-modal="modal2" data-id="{{ $department->id }}" data-name="{{ $department->name }}" data-members="{{ $department->members_count }}">
                                    <iconify-icon icon="ic:round-edit" class="text-xl text-[#124375]"></iconify-icon>
                                </button>
                                <form action="{{ route('admin.departments.archive', $department->id) }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-center p-2 bg-[#FFEAE8] rounded-lg">
                                        <iconify-icon icon="zondicons:close-solid" class="text-xl text-[#D92D20]"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500 bg-white rounded-xl border border-gray-200">لا توجد بيانات</div>
                    @endforelse
                </div>
                <div class="mt-4 px-4 py-2">
                    {{ $activeDepartments->appends(['archived_page' => request('archived_page'), 'search' => request('search')])->links('pagination::tailwind') }}
                </div>
            </div>

            <div class=" rounded-[14px] hidden tab-content overflow-hidden border border-[#6D6D6D]" data-tab="الأرشيف">
                <table class="hidden md:table w-full border-collapse">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم / الوصف </th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">كود الكلية</th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">عدد الأعضاء</th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 font-medium text-[#021219]">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archivedDepartments as $index => $department)
                        <tr class="text-center {{ $index % 2 !== 0 ? 'bg-[#EFEFEF]' : 'border-b border-[#6D6D6D]' }}">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $department->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">{{ $department->code }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $department->members_count }} عضو</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                    class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">موقوف</span>
                            </td>
                            <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                                <a href="{{ route('admin.departments.show', $department->id) }}">
                                    <iconify-icon icon="solar:eye-linear"
                                        class="text-2xl text-[#021219] cursor-pointer"></iconify-icon>
                                </a>
                                <form action="{{ route('admin.departments.restore', $department->id) }}" method="POST" class="inline m-0 p-0">
                                    @csrf
                                    <button type="submit" class="border-0 bg-transparent flex items-center">
                                        <iconify-icon icon="healthicons:yes" class="text-xl text-[#019168] cursor-pointer "></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center">لا توجد بيانات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Cards View for Archived -->
                <div class="md:hidden flex flex-col gap-4 mt-2 px-2">
                    @forelse($archivedDepartments as $department)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-[#6D6D6D]/30 flex flex-col gap-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-[#021219] font-bold text-lg">{{ $department->name }}</h3>
                                    <p class="text-sm text-[#6D6D6D]">كود: {{ $department->code }}</p>
                                </div>
                                <span class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 text-sm min-w-[60px] text-center">موقوف</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#6D6D6D] text-sm">
                                <iconify-icon icon="mdi:account-group" class="text-xl"></iconify-icon>
                                <span>{{ $department->members_count }} عضو</span>
                            </div>
                            <div class="flex gap-4 items-center justify-end border-t border-gray-100 pt-3 mt-1">
                                <a href="{{ route('admin.departments.show', $department->id) }}" class="flex items-center justify-center p-2 bg-[#F4F7F9] rounded-lg">
                                    <iconify-icon icon="solar:eye-linear" class="text-xl text-[#021219]"></iconify-icon>
                                </a>
                                <form action="{{ route('admin.departments.restore', $department->id) }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-center p-2 bg-[#ECFDF3] rounded-lg">
                                        <iconify-icon icon="healthicons:yes" class="text-xl text-[#019168]"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500 bg-white rounded-xl border border-gray-200">لا توجد بيانات</div>
                    @endforelse
                </div>
                <div class="mt-4 px-4 py-2">
                    {{ $archivedDepartments->appends(['active_page' => request('active_page'), 'search' => request('search')])->links('pagination::tailwind') }}
                </div>
            </div>
        </section>

        @include('admin.components.departments_modals')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editButtons = document.querySelectorAll('.open-modal[data-modal="modal2"]');
                const baseUrl = "{{ url('admin/departments') }}";
                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const name = this.getAttribute('data-name');
                        const membersCount = this.getAttribute('data-members');

                        document.getElementById('edit_name').value = name;
                        document.getElementById('edit_members').value = membersCount + ' عضو';
                        
                        document.getElementById('editForm').action = `${baseUrl}/${id}`;
                    });
                });
            });
        </script>
        <script src="{{ asset('js/admin/departments.js') }}"></script>

    @endsection

