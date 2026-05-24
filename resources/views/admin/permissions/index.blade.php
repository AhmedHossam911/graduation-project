@extends('layouts.app')

@section('title', 'صلاحيات المستخدمين')

@include('partials.flash')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/permissions.css') }}">

    <div class="flex justify-between items-center py-7 px-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-xl text-[#124375] font-semibold">
                إدارة النظام والصلاحيات
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                إدارة حسابات الموظفين ومجلس الإدارة ، طلبت الانضمام ، وتحديد الصلاحيات الممنوحة
            </p>
        </div>
        <div>
            <a href="#"
                class="cursor-pointer hover:bg-[#0e3560] transition-colors text-[16px] navy-shadow flex items-center justify-center gap-4 bg-[#124375] text-[#F4F7F9] py-2.5 px-14 rounded-[12px]">
                <iconify-icon icon="ic:round-plus" class="text-2xl mt-1"></iconify-icon>
                إضافة و تفويض مستخدم
            </a>
        </div>
    </div>

    <div class="px-12">
        <div class="grid grid-cols-3 gap-10 items-center navy-shadow rounded-[16px] py-5 px-3">
            <div class="flex gap-2 col-span-2">
                <form action="{{ route('admin.permissions.index') }}" method="GET" class="w-full flex gap-2 m-0 p-0">
                    <div class="w-full relative">
                        <input name="search" value="{{ request('search') }}"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow w-full outline-none navy-shadow rounded-[10px] py-2 pr-8"
                            placeholder="الاسم أو البريد الإلكتروني أو  الصفة الإدارية أو الصلاحيات الممنوحة" />
                        <iconify-icon icon="ri:search-line"
                            class="text-2xl text-[#124375] absolute top-1/2 -translate-y-1/2 right-1 "></iconify-icon>
                    </div>
                    <button type="submit" class="bg-[#124375] rounded-[12px] px-8 navy-shadow hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="ri:search-line" class="text-2xl text-[#F4F7F9] flex items-center"></iconify-icon>
                    </button>
                </form>
            </div>
            <div class="flex justify-between ">
                <div class="bg-[#EAF5FF] py-2 px-3 rounded-[10px]">
                    <p class="text-[#124375]">إجمالي الحسابات :<span>{{ $activeUsers->total() }}</span></p>
                </div>
                <div class="bg-[#EFEFEF] py-2 px-3 rounded-[10px]">
                    <p class="text-[#021219]">حسابات موقوفة :<span>{{ $suspendedUsers->total() }}</span></p>
                </div>
                <div class="bg-[#FFF7ED] py-2 px-3 rounded-[10px]">
                    <p class="text-[#F79009]">طلبات جديدة :<span>{{ $pendingRequests->total() }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="tabs mx-12  pt-7 flex gap-8 border-b border-[#A8A8A8]">
        <div class="tab-item active px-7 flex gap-2 items-center pb-3 relative hover:text-[#019168] transition-colors cursor-pointer green-active"
            data-active="green-active">
            <iconify-icon icon="mdi:account-group" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الحسابات النشطة <span>({{ $activeUsers->total() }})</span></button>
            <span class="absolute bg-[#019168] bottom-[-1px] left-0 h-[2px] w-full"></span>
        </div>
        <div class="tab-item px-7 flex gap-2 items-center  pb-3 relative hover:text-[#021219] transition-colors cursor-pointer"
            data-active="black-active">
            <iconify-icon icon="ri:user-minus-fill" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الحسابات الموقوفة <span>({{ $suspendedUsers->total() }})</span></button>
            <span class="absolute bg-[#021219] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
        <div class="tab-item px-7 flex gap-2 items-center  pb-3 relative hover:text-[#F79009] transition-colors cursor-pointer "
            data-active="orange-active">
            <iconify-icon icon="octicon:shield-16" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">طلبات الانضمام المعلقة <span>({{ $pendingRequests->total() }})</span></button>
            <span class="absolute bg-[#F79009] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
        <div class="tab-item px-7 flex gap-2  items-center pb-3 relative hover:text-[#D92D20] transition-colors cursor-pointer "
            data-active="red-active">
            <iconify-icon icon="mingcute:user-x-fill" class="text-3xl  flex items-center "></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الطلبات المرفوضة <span>({{ $rejectedRequests->total() }})</span></button>
            <span class="absolute bg-[#D92D20] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
    </div>

    <!-- start table -->
    <section class="px-12 py-8">
        <div class=" rounded-[14px] tab-content overflow-hidden border border-[#6D6D6D]" data-tab="الحسابات النشطة">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم </th>
                        <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني</th>
                        <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الصفة الإدراية</th>
                        <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                        <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">مسميات الصلاحيات الممنوحة</th>
                        <th class="py-4 font-medium text-[#021219]">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeUsers as $user)
                    <tr class="text-center border-b border-[#6D6D6D] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $user->name }}</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">{{ $user->email }}</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#019168] bg-[#F0FFF6] border border-[#019168] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                {{ $user->role ? $user->role->name : 'غير محدد' }}</span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            @if($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                <span
                                    class="text-[#16A34A] bg-[#DCFCE7] border border-[#16A34A] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">{{ $user->custom_permissions[0] }}</span>
                                @if(count($user->custom_permissions) > 1)
                                <span
                                    class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">+{{ count($user->custom_permissions) - 1 }}
                                    صلاحيات أخري</span>
                                @endif
                            @else
                                <span class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">لا يوجد صلاحيات</span>
                            @endif
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear" class="text-2xl text-[#021219] cursor-pointer open-modal"
                                data-modal="modal-{{ $user->id }}"></iconify-icon>
                            <a href="{{ route('admin.permissions.edit', $user->id) }}" class="flex items-center">
                                <iconify-icon icon="ic:round-edit"
                                    class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            </a>
                            <form action="{{ route('admin.permissions.suspend', $user->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('هل أنت متأكد من إيقاف حساب هذا المستخدم؟');">
                                @csrf
                                <button type="submit" class="border-none bg-transparent m-0 p-0 flex items-center justify-center">
                                    <iconify-icon icon="zondicons:close-solid"
                                        class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center">لا توجد حسابات نشطة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($activeUsers->hasPages())
                <div class="p-4">
                    {{ $activeUsers->links() }}
                </div>
            @endif
        </div>

        <div class=" rounded-[14px] hidden tab-content overflow-hidden border border-[#6D6D6D]"
            data-tab="الحسابات الموقوفة">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم </th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الصفة الإدراية</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">مسميات الصلاحيات الممنوحة
                        </th>
                        <th class="py-3 font-medium text-[#021219]">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suspendedUsers as $user)
                    <tr class="text-center border-b border-[#6D6D6D] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $user->name }}</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">{{ $user->email }}</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#019168] bg-[#F0FFF6] border border-[#019168] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                {{ $user->role ? $user->role->name : 'غير محدد' }}</span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">موقوف</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            @if($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                <span
                                    class="text-[#16A34A] bg-[#DCFCE7] border border-[#16A34A] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">{{ $user->custom_permissions[0] }}</span>
                                @if(count($user->custom_permissions) > 1)
                                <span
                                    class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">+{{ count($user->custom_permissions) - 1 }}
                                    صلاحيات أخري</span>
                                @endif
                            @else
                                <span class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">لا يوجد صلاحيات</span>
                            @endif
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer open-modal"
                                data-modal="modal-{{ $user->id }}"></iconify-icon>
                            <a href="{{ route('admin.permissions.edit', $user->id) }}" class="flex items-center">
                                <iconify-icon icon="ic:round-edit"
                                    class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            </a>
                            <form action="{{ route('admin.permissions.reactivate', $user->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('هل أنت متأكد من إعادة تفعيل هذا المستخدم؟');">
                                @csrf
                                <button type="submit" class="border-none bg-transparent m-0 p-0 flex items-center justify-center">
                                    <iconify-icon icon="healthicons:yes"
                                        class="text-xl text-[#019168] cursor-pointer "></iconify-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center">لا توجد حسابات موقوفة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($suspendedUsers->hasPages())
                <div class="p-4">
                    {{ $suspendedUsers->links() }}
                </div>
            @endif
        </div>

        <div class="hidden tab-content" data-tab="طلبات الانضمام المعلقة">
            <div
                class="bg-[#FFF8E1] border border-[#F79009] rounded-t-[14px] py-4 px-6 flex justify-start items-center gap-3 text-[#F79009]">
                <iconify-icon icon="octicon:shield-16" class="text-3xl mt-1"></iconify-icon>
                <p class="text-[18px] font-medium">
                    تحتاج إلي مراجعة و تحديد الصلاحيات
                </p>
            </div>
            <div class="rounded-b-[14px] overflow-hidden border-x border-b border-[#6D6D6D]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم </th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219] px-7">تاريخ الطلب</th>
                            <th class="py-3 font-medium text-[#021219]">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingRequests as $user)
                        <tr class="text-center border-b border-[#6D6D6D] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $user->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">{{ $user->email }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] ">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                            <td class="py-3 flex gap-5 justify-center text-[#124375]">
                                <a href="{{ route('admin.permissions.edit', $user->id) }}"
                                    class="flex items-center gap-3 border border-[#124375] bg-white navy-shadow py-2 px-4 rounded-[12px]">
                                    <iconify-icon icon="material-symbols:check-circle-rounded"
                                        class="text-xl text-[#124375]"></iconify-icon>
                                    أعتماد الصلاحيات
                                </a>
                                <form action="{{ route('admin.permissions.reject', $user->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('هل أنت متأكد من رفض هذا الطلب؟');">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-3 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-4 text-[#D92D20] rounded-[12px]">
                                        <iconify-icon icon="material-symbols:cancel-rounded"
                                            class="text-xl text-[#D92D20]"></iconify-icon>
                                        رفض
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center">لا توجد طلبات انضمام معلقة.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pendingRequests->hasPages())
                <div class="p-4 border-x border-b border-[#6D6D6D] rounded-b-[14px]">
                    {{ $pendingRequests->links() }}
                </div>
            @endif
        </div>

        <div class="hidden tab-content" data-tab="الطلبات المرفوضة">
            <div
                class="bg-[#FFEAE880] border border-[#D92D20] rounded-t-[14px] py-4 px-6 flex justify-start items-center gap-3 text-[#D92D20]">
                <iconify-icon icon="mingcute:user-x-fill" class="text-3xl mt-1"></iconify-icon>
                <p class="text-[18px] font-medium">
                    طلبات انضمام تم رفضها
                </p>
            </div>
            <div class="rounded-b-[14px] overflow-hidden border-x border-b border-[#6D6D6D]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم </th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني</th>
                            <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219] px-7">تاريخ الطلب</th>
                            <th class="py-3 font-medium text-[#021219]">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rejectedRequests as $user)
                        <tr class="text-center border-b border-[#6D6D6D] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $user->name }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">{{ $user->email }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] ">{{ $user->deleted_at ? $user->deleted_at->format('d M Y') : '-' }}</td>
                            <td class="py-3 flex gap-5 justify-center text-[#124375]">
                                <form action="{{ route('admin.permissions.restore', $user->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('هل أنت متأكد من إستعادة هذا الطلب؟');">
                                    @csrf
                                    <button type="submit"
                                        class="flex border border-[#124375] items-center gap-3 bg-white navy-shadow py-2 px-4 rounded-[12px]">
                                        <iconify-icon icon="pajamas:redo" class="text-xl text-[#124375]"></iconify-icon>
                                        إستعادة للمراجعة
                                    </button>
                                </form>
                                <form action="{{ route('admin.permissions.destroy', $user->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center gap-3 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-4 text-[#D92D20] rounded-[12px]">
                                        <iconify-icon icon="material-symbols:delete-rounded"
                                            class="text-xl text-[#D92D20]"></iconify-icon>
                                        حذف نهائي
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center">لا توجد طلبات مرفوضة.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($rejectedRequests->hasPages())
                <div class="p-4 border-x border-b border-[#6D6D6D] rounded-b-[14px]">
                    {{ $rejectedRequests->links() }}
                </div>
            @endif
        </div>
    </section>
    <!-- end table -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

    @php
        $allUsersForModals = collect($activeUsers->items())->merge($suspendedUsers->items());
    @endphp
    @foreach($allUsersForModals as $user)
    <div id="modal-{{ $user->id }}"
        class="flex flex-col hidden w-full max-w-2xl mx-auto fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-5 max-h-[95vh]">
        <div class="flex justify-end">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
        </div>
        <div class="modal-body px-10 flex-1 flex flex-col overflow-hidden">
            <div class="space-y-2 shrink-0">
                <div class="space-y-1">
                    <h2 class="text-[#021219] text-[28px] font-semibold">
                        صلاحيات {{ $user->name }}
                    </h2>
                    <p class="text-[#6D6D6D] text-[16px] font-medium">مراجعة المهام ونطاق العمل المكاني ( الكليات ) للموظف</p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="bi:buildings-fill" class="text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[20px] font-semibold">النطاق المكاني : الكليات المسؤول عنها
                            <span>({{ is_array($user->faculties) ? count($user->faculties) : 0 }})</span>
                        </p>
                    </div>
                    @if(is_array($user->faculties) && count($user->faculties) > 0)
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($user->faculties as $faculty)
                        <div class="flex items-center bg-[#F4F7F9] navy-shadow rounded-[8px] py-1 px-2">
                            <iconify-icon icon="tabler:point-filled" class="text-3xl text-[#124375]"></iconify-icon>
                            <span class="text-[14px] text-[#021219] font-medium">{{ $faculty }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="flex items-center gap-1 bg-[#E6F1FD80] border-[1.5px] border-[#124375] rounded-[8px] py-3 px-3 text-[#124375]">
                        <iconify-icon icon="ic:round-place" class="text-3xl"></iconify-icon>
                        <p class="text-[16px] font-semibold">لم يتم تحديد نطاق مكاني بعد.</p>
                    </div>
                    @endif
                </div>
                <div class="space-y-4 pt-3">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="iconamoon:shield-yes-fill" class="text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[20px] font-semibold">قائمة الصلاحيات الممنوحة <span>({{ is_array($user->custom_permissions) ? count($user->custom_permissions) : 0 }})</span></p>
                    </div>
                    @if($user->role && $user->role->name === 'Admin')
                    <div class="bg-[#F0FFF6] space-y-2 border-[1.5px] border-[#019168] rounded-[8px] py-5 px-3 text-[#019168]">
                        <div class="flex items-center gap-1 ">
                            <iconify-icon icon="icon-park-solid:success" class="text-2xl"></iconify-icon>
                            <p class="text-[16px] font-semibold">مدير نظام بصلاحيات كاملة</p>
                        </div>
                        <p class="text-[14px] font-medium">هذا المستخدم لديه وصول غير مقيد لجميع إعدادات النظام</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 flex-1 py-3 overflow-y-auto px-2 no-scrollbar">
                @if(is_array($user->custom_permissions))
                    @foreach($user->custom_permissions as $perm)
                    <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                        <iconify-icon icon="mdi:success"
                            class="text-3xl text-[#0284C7] bg-[#E0F2FE] py-3 px-3 rounded-[8px]"></iconify-icon>
                        <div class="space-y-4">
                            <p class="text-[16px] font-medium text-[#021219]">{{ $perm }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-2 text-center text-[#6D6D6D] py-4">لا توجد صلاحيات مخصصة.</div>
                @endif
            </div>
            <div class="btns flex gap-2 shrink-0 pt-4">
                <div class="w-full flex justify-end">
                    <button type="button"
                        class="modal-close close-btn rounded-[14px]  py-3 px-20 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script src="{{ asset('JS/permissions.js') }}"></script>

@endsection
