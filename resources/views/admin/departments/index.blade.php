@extends('layouts.app')

@section('title', 'إدارة كليات وقطاعات الجامعة')

@include('partials.flash')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/departments.css') }}">

        <div class="flex justify-between items-center py-7 px-12">
            <div class="flex flex-col gap-3">
                <div class="flex gap-4 items-center">
                    <h1 class="text-xl text-[#124375] font-semibold">
                        إدارة كليات وقطاعات الجامعة
                    </h1>
                    <div class="flex items-center text-[#019168] bg-[#F0FFF6] gap-2 px-1">
                        <p>إجمالي الأعضاء المسجلين :</p>
                        <p class="mt-1">{{ number_format($totalMembers) }}</p>
                    </div>
                </div>
                <p class="text-[#6D6D6D] text-[16px] font-normal">
                    إدارة قائمة الكليات وتعديل بياناتها، ومتابعة الأعضاء المسجلين في كل قطاع.
                </p>
            </div>
            <div>
                <button
                    class="open-modal cursor-pointer hover:bg-[#0e3560] transition-colors text-[16px] navy-shadow flex items-center justify-center gap-4 bg-[#124375] text-[#F4F7F9] py-2.5 px-20 rounded-[12px]"
                    data-modal="modal1">
                    <iconify-icon icon="ic:round-plus" class="text-2xl mt-1"></iconify-icon>
                    إضافة عنصر جديد
                </button>
            </div>
        </div>

        <div class="px-12">
            <form action="{{ route('admin.departments.index') }}" method="GET" class="grid grid-cols-6 gap-10 items-center navy-shadow rounded-[16px] py-5 px-3">
                <div class="flex gap-2 col-span-3">
                    <div class="w-full relative">
                        <input name="search" value="{{ request('search') }}"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow w-full outline-none navy-shadow rounded-[10px] py-2 pr-8"
                            placeholder="اسم أو كود الكلية " />
                        <iconify-icon icon="ri:search-line"
                            class="text-2xl text-[#124375] absolute top-1/2 -translate-y-1/2 right-1 "></iconify-icon>
                    </div>
                    <button type="submit" class="bg-[#124375] rounded-[12px] px-8 navy-shadow hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="ri:search-line"
                            class="text-2xl text-[#F4F7F9] flex items-center"></iconify-icon>
                    </button>
                </div>
                <div class="bg-[#EAF5FF] text-center py-2 px-3 rounded-[10px] col-span-1 tab-content"
                    data-tab="الكليات الحالية">
                    <p class="text-[#124375]">إجمالي الجهات :<span>{{ $totalActive }}</span></p>
                </div>
                <div class="bg-[#FFEAE8] hidden text-center py-2 px-3 rounded-[10px] col-span-1 tab-content"
                    data-tab="الأرشيف">
                    <p class="text-[#D92D20]">إجمالي الجهات :<span>{{ $totalArchived }}</span></p>
                </div>
                <div class="flex items-center bg-[#F4F7F9] navy-shadow col-span-2 py-2 px-3 gap-3 rounded-[8px]">
                    <button type="button"
                        class="tab bg-[#124375] flex items-center navy-shadow gap-2 text-[#EEF7FF] w-full justify-center rounded-[8px] py-2 hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="healthicons:yes" class="text-xl flex items-center"></iconify-icon>
                        الكليات الحالية
                    </button>
                    <button type="button"
                        class="tab text-[#124375] bg-[#EEF7FF] flex items-center gap-2 border border-[#124375] w-full justify-center rounded-[8px] py-2">
                        <iconify-icon icon="fluent:archive-16-filled" class="text-xl  flex items-center"></iconify-icon>
                        الأرشيف
                    </button>
                </div>
            </form>
        </div>

        <section class="px-12 py-8">
            <div class=" rounded-[14px] tab-content overflow-hidden border border-[#6D6D6D]" data-tab="الكليات الحالية">
                <table class="w-full border-collapse">
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
                <div class="mt-4 px-4 py-2">
                    {{ $activeDepartments->appends(['archived_page' => request('archived_page'), 'search' => request('search')])->links('pagination::tailwind') }}
                </div>
            </div>

            <div class=" rounded-[14px] hidden tab-content overflow-hidden border border-[#6D6D6D]" data-tab="الأرشيف">
                <table class="w-full border-collapse">
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
                <div class="mt-4 px-4 py-2">
                    {{ $archivedDepartments->appends(['active_page' => request('active_page'), 'search' => request('search')])->links('pagination::tailwind') }}
                </div>
            </div>
        </section>

        <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

        <div id="modal1"
            class=" w-full hidden max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <div class="flex justify-end">
                <button type="button"
                    class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                    <iconify-icon icon="weui:close-filled"></iconify-icon>
                </button>
            </div>
            <form action="{{ route('admin.departments.store') }}" method="POST">
                @csrf
                <div class="modal-body space-y-7 px-12">
                    <div class="modal-title space-y-2">
                        <h1 class="text-[28px] font-semibold text-[#021219] ">
                            إضافة عنصر جديد
                        </h1>
                        <p class="text-[#6D6D6D] text-[16px] font-medium">
                            يمكنك إضافة كلية أو قطاع جديد للنظام.
                        </p>
                    </div>
                    <div class="space-y-7">
                        <div class="flex flex-col gap-7">
                            <div class="relative w-full">
                                <label
                                    class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                                    الاسم / الوصف الأساسي
                                </label>
                                <input type="text" name="name" required placeholder="مثال : كلية الطب"
                                    class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                            </div>
                            <div class="relative w-full">
                                <label
                                    class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                                    عدد الأعضاء المسجلين
                                </label>
                                <input type="text" disabled placeholder="يتم حسابه تلقائيا"
                                    class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                            </div>
                        </div>
                    </div>
                    <div class="btns flex gap-4 ">
                        <div class="w-full">
                            <button type="submit"
                                class=" rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                        icon="ic:round-plus"
                                        class="flex items-center text-2xl"></iconify-icon></span>إضافة</button>
                        </div>
                        <div class="w-full">
                            <button type="button"
                                class="close-btn rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center  bg-[#F4F7F9] text-[#124375] navy-shadow ">إلغاء
                                الأمر</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div id="modal2"
            class=" w-full hidden max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
            <div class="flex justify-end">
                <button type="button"
                    class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                    <iconify-icon icon="weui:close-filled"></iconify-icon>
                </button>
            </div>
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body space-y-7 px-12">
                    <div class="modal-title space-y-2">
                        <h1 class="text-[28px] font-semibold text-[#021219] ">
                            تعديل البيانات
                        </h1>
                        <p class="text-[#6D6D6D] text-[16px] font-medium">
                            يمكنك تعديل بيانات الكلية أو القطاع الحالي وحفظ التغييرات.
                        </p>
                    </div>
                    <div class="space-y-7">
                        <div class="flex flex-col gap-7">
                            <div class="relative w-full">
                                <label
                                    class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                                    الاسم / الوصف الأساسي
                                </label>
                                <input type="text" id="edit_name" name="name" required placeholder="مثال : كلية الطب"
                                    class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                            </div>
                            <div class="relative w-full">
                                <label
                                    class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                                    عدد الأعضاء المسجلين
                                </label>
                                <input type="text" id="edit_members" disabled placeholder=""
                                    class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                            </div>
                        </div>
                    </div>
                    <div class="btns flex gap-4 ">
                        <div class="w-full">
                            <button type="submit"
                                class=" rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                        icon="fluent:save-16-filled"
                                        class="flex items-center text-2xl"></iconify-icon></span>حفظ
                                التعديلات</button>
                        </div>
                        <div class="w-full">
                            <button type="button"
                                class="close-btn rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center  bg-[#F4F7F9] text-[#124375] navy-shadow ">إلغاء
                                الأمر</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

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
