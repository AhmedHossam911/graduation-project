@extends('layouts.app')
{{-- 
    Permissions View:
    Interface for the Super Admin to manage employee/admin roles and permissions.
    Handles active, suspended, pending, and rejected account requests.
--}}

@section('title', 'صلاحيات المستخدمين')

@include('partials.common.flash')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/permissions.css') }}">

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
            <a href="{{ route('admin.permissions.create') }}" class="cursor-pointer hover:bg-[#0e3560] transition-colors text-[16px] navy-shadow flex items-center justify-center gap-4 bg-[#124375] text-[#F4F7F9] py-2.5 px-14 rounded-[12px]">
                <iconify-icon icon="ic:round-plus" class="text-2xl mt-1"></iconify-icon>
                إضافة و تفويض مستخدم
            </a>
        </div>
    </div>

    <div class="px-12">
        <div class="grid grid-cols-3 gap-10 items-center navy-shadow rounded-[16px] py-5 px-3">
            <div class="flex gap-2 col-span-2 w-full">
                <form action="{{ route('admin.permissions.index') }}" method="GET" class="w-full flex gap-2 m-0 p-0">
                    <div class="w-full relative">
                        <input name="search" value="{{ request('search') }}"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow w-full outline-none navy-shadow rounded-[10px] py-2 pr-8"
                            placeholder="الاسم أو البريد الإلكتروني أو  الصفة الإدارية أو الصلاحيات الممنوحة" />
                        <iconify-icon icon="ri:search-line"
                            class="text-2xl text-[#124375] absolute top-1/2 -translate-y-1/2 right-1 "></iconify-icon>
                    </div>
                    <button type="submit"
                        class="bg-[#124375] rounded-[12px] px-8 navy-shadow hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="ri:search-line"
                            class="text-2xl text-[#F4F7F9] flex items-center"></iconify-icon>
                    </button>
                </form>
            </div>
            <div class="flex justify-between">
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

    <div class="tabs mx-12 pt-7 flex gap-8 border-b border-[#A8A8A8]">
        <div class="tab-item active px-7 flex gap-2 items-center pb-3 relative hover:text-[#019168] transition-colors cursor-pointer green-active" data-active="green-active">
            <iconify-icon icon="mdi:account-group" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الحسابات النشطة <span>({{ $activeUsers->total() }})</span></button>
            <span class="absolute bg-[#019168] bottom-[-1px] left-0 h-[2px] w-full"></span>
        </div>
        <div class="tab-item px-7 flex gap-2 items-center  pb-3 relative hover:text-[#021219] transition-colors cursor-pointer" data-active="black-active">
            <iconify-icon icon="ri:user-minus-fill" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الحسابات الموقوفة <span>({{ $suspendedUsers->total() }})</span></button>
            <span class="absolute bg-[#021219] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
        <div class="tab-item px-7 flex gap-2 items-center  pb-3 relative hover:text-[#F79009] transition-colors cursor-pointer " data-active="orange-active">
            <iconify-icon icon="octicon:shield-16" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">طلبات الانضمام المعلقة <span>({{ $pendingRequests->total() }})</span></button>
            <span class="absolute bg-[#F79009] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
        <div class="tab-item px-7 flex gap-2  items-center pb-3 relative hover:text-[#D92D20] transition-colors cursor-pointer " data-active="red-active">
            <iconify-icon icon="mingcute:user-x-fill" class="text-3xl  flex items-center "></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الطلبات المرفوضة <span>({{ $rejectedRequests->total() }})</span></button>
            <span class="absolute bg-[#D92D20] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
    </div>

    <!-- start table -->
    <section class="px-12 py-8">
        <div class=" rounded-[14px] tab-content overflow-hidden border border-[#6D6D6D]" data-tab="الحسابات النشطة">
            <div class="hidden md:block">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم </th>
                        <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني</th>
                        <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الصفة الإدارية</th>
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
                                    {{ $user->role ? $user->role->arabic_name : 'غير محدد' }}</span></td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                    class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] ">
                                @if ($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                    <span
                                        class="text-[#16A34A] bg-[#DCFCE7] border border-[#16A34A] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">{{ $user->custom_permissions[0] }}</span>
                                    @if (count($user->custom_permissions) > 1)
                                        <span
                                            class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">+{{ count($user->custom_permissions) - 1 }}
                                            صلاحيات أخري</span>
                                    @endif
                                @else
                                    <span
                                        class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">لا
                                        يوجد صلاحيات</span>
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
                                <form action="{{ route('admin.permissions.suspend', $user->id) }}" method="POST"
                                    class="inline m-0 p-0"
                                    data-confirm-message="هل أنت متأكد من إيقاف حساب هذا المستخدم؟">
                                    @csrf
                                    <button type="submit"
                                        class="border-none bg-transparent m-0 p-0 flex items-center justify-center">
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
            </div>

            <!-- Mobile Cards (Active Accounts) -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse($activeUsers as $user)
                    <div class="bg-white rounded-[14px] border border-[#6D6D6D] p-4 flex flex-col gap-3 shadow-sm">
                        <div class="flex justify-between items-start">
                            <h3 class="text-[#021219] font-semibold text-lg">{{ $user->name }}</h3>
                            <span class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 text-sm">نشط</span>
                        </div>
                        <div class="flex flex-col gap-2 text-sm text-[#021219]">
                            <div class="flex gap-2 items-center">
                                <iconify-icon icon="mdi:email" class="text-[#6D6D6D]"></iconify-icon>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="flex gap-2 items-center">
                                <iconify-icon icon="mdi:badge-account" class="text-[#6D6D6D]"></iconify-icon>
                                <span class="text-[#019168] bg-[#F0FFF6] border border-[#019168] rounded-[8px] py-[1px] px-2">
                                    {{ $user->role ? $user->role->arabic_name : 'غير محدد' }}
                                </span>
                            </div>
                            <div class="flex gap-2 flex-wrap mt-1">
                                @if ($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                    <span class="text-[#16A34A] bg-[#DCFCE7] border border-[#16A34A] rounded-[8px] py-[1px] px-2 text-xs">{{ $user->custom_permissions[0] }}</span>
                                    @if (count($user->custom_permissions) > 1)
                                        <span class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-2 text-xs">+{{ count($user->custom_permissions) - 1 }} أخري</span>
                                    @endif
                                @else
                                    <span class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-2 text-xs">لا يوجد صلاحيات</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-4 items-center justify-end mt-2 pt-3 border-t border-gray-100 text-[#124375]">
                            <iconify-icon icon="solar:eye-linear" class="text-2xl text-[#021219] cursor-pointer open-modal" data-modal="modal-{{ $user->id }}"></iconify-icon>
                            <a href="{{ route('admin.permissions.edit', $user->id) }}" class="flex items-center">
                                <iconify-icon icon="ic:round-edit" class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            </a>
                            <form action="{{ route('admin.permissions.suspend', $user->id) }}" method="POST" class="inline m-0 p-0" data-confirm-message="هل أنت متأكد من إيقاف حساب هذا المستخدم؟">
                                @csrf
                                <button type="submit" class="border-none bg-transparent m-0 p-0 flex items-center justify-center">
                                    <iconify-icon icon="zondicons:close-solid" class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">لا توجد حسابات نشطة.</div>
                @endforelse
            </div>

            @if ($activeUsers->hasPages())
                <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t md:border-t border-[#A8A8A8] mt-4 backdrop-blur-md bg-white/80 z-10 px-6 rounded-b-[14px]">
                    {{ $activeUsers->links() }}
                </div>
            @endif
        </div>

        <div class=" rounded-[14px] hidden tab-content overflow-hidden border border-[#6D6D6D]" data-tab="الحسابات الموقوفة">
            <div class="hidden md:block">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم </th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الصفة الإدارية</th>
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
                                    {{ $user->role ? $user->role->arabic_name : 'غير محدد' }}</span></td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                    class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">موقوف</span>
                            </td>
                            <td class="py-3 border-l border-[#6D6D6D] ">
                                @if ($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                    <span
                                        class="text-[#16A34A] bg-[#DCFCE7] border border-[#16A34A] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">{{ $user->custom_permissions[0] }}</span>
                                    @if (count($user->custom_permissions) > 1)
                                        <span
                                            class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">+{{ count($user->custom_permissions) - 1 }}
                                            صلاحيات أخري</span>
                                    @endif
                                @else
                                    <span
                                        class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">لا
                                        يوجد صلاحيات</span>
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
                                <form action="{{ route('admin.permissions.reactivate', $user->id) }}" method="POST"
                                    class="inline m-0 p-0"
                                    data-confirm-message="هل أنت متأكد من إعادة تفعيل هذا المستخدم؟">
                                    @csrf
                                    <button type="submit"
                                        class="border-none bg-transparent m-0 p-0 flex items-center justify-center">
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
            </div>

            <!-- Mobile Cards (Suspended Accounts) -->
            <div class="md:hidden flex flex-col gap-4">
                @forelse($suspendedUsers as $user)
                    <div class="bg-white rounded-[14px] border border-[#6D6D6D] p-4 flex flex-col gap-3 shadow-sm">
                        <div class="flex justify-between items-start">
                            <h3 class="text-[#021219] font-semibold text-lg">{{ $user->name }}</h3>
                            <span class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 text-sm">موقوف</span>
                        </div>
                        <div class="flex flex-col gap-2 text-sm text-[#021219]">
                            <div class="flex gap-2 items-center">
                                <iconify-icon icon="mdi:email" class="text-[#6D6D6D]"></iconify-icon>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="flex gap-2 items-center">
                                <iconify-icon icon="mdi:badge-account" class="text-[#6D6D6D]"></iconify-icon>
                                <span class="text-[#019168] bg-[#F0FFF6] border border-[#019168] rounded-[8px] py-[1px] px-2">
                                    {{ $user->role ? $user->role->arabic_name : 'غير محدد' }}
                                </span>
                            </div>
                            <div class="flex gap-2 flex-wrap mt-1">
                                @if ($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                    <span class="text-[#16A34A] bg-[#DCFCE7] border border-[#16A34A] rounded-[8px] py-[1px] px-2 text-xs">{{ $user->custom_permissions[0] }}</span>
                                    @if (count($user->custom_permissions) > 1)
                                        <span class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-2 text-xs">+{{ count($user->custom_permissions) - 1 }} أخري</span>
                                    @endif
                                @else
                                    <span class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-2 text-xs">لا يوجد صلاحيات</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-4 items-center justify-end mt-2 pt-3 border-t border-gray-100 text-[#124375]">
                            <iconify-icon icon="solar:eye-linear" class="text-2xl text-[#021219] cursor-pointer open-modal" data-modal="modal-{{ $user->id }}"></iconify-icon>
                            <a href="{{ route('admin.permissions.edit', $user->id) }}" class="flex items-center">
                                <iconify-icon icon="ic:round-edit" class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            </a>
                            <form action="{{ route('admin.permissions.reactivate', $user->id) }}" method="POST" class="inline m-0 p-0" data-confirm-message="هل أنت متأكد من إعادة تفعيل هذا المستخدم؟">
                                @csrf
                                <button type="submit" class="border-none bg-transparent m-0 p-0 flex items-center justify-center">
                                    <iconify-icon icon="healthicons:yes" class="text-xl text-[#019168] cursor-pointer "></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">لا توجد حسابات موقوفة.</div>
                @endforelse
            </div>

            @if ($suspendedUsers->hasPages())
                <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t md:border-t border-[#A8A8A8] mt-4 backdrop-blur-md bg-white/80 z-10 px-6 rounded-b-[14px]">
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
                <div class="hidden md:block">
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
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $user->name }}
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">{{ $user->email }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] ">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                                <td class="py-3 flex gap-5 justify-center text-[#124375]">
                                    <a href="{{ route('admin.permissions.edit', $user->id) }}"
                                        class="flex items-center gap-3 border border-[#124375] bg-white navy-shadow py-2 px-4 rounded-[12px]">
                                        <iconify-icon icon="material-symbols:check-circle-rounded"
                                            class="text-xl text-[#124375]"></iconify-icon>
                                        أعتماد الصلاحيات
                                    </a>
                                    <form action="{{ route('admin.permissions.reject', $user->id) }}" method="POST"
                                        class="inline m-0 p-0"
                                        data-confirm-message="هل أنت متأكد من رفض هذا الطلب؟">
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

                <!-- Mobile Cards (Pending) -->
                <div class="md:hidden flex flex-col gap-4">
                    @forelse($pendingRequests as $user)
                        <div class="bg-white rounded-[14px] border border-[#6D6D6D] p-4 flex flex-col gap-3 shadow-sm">
                            <div class="flex justify-between items-start">
                                <h3 class="text-[#021219] font-semibold text-lg">{{ $user->name }}</h3>
                                <span class="text-xs text-[#6D6D6D]">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-2 text-sm text-[#021219]">
                                <div class="flex gap-2 items-center">
                                    <iconify-icon icon="mdi:email" class="text-[#6D6D6D]"></iconify-icon>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="flex gap-3 justify-center mt-2 pt-3 border-t border-gray-100">
                                <a href="{{ route('admin.permissions.edit', $user->id) }}" class="flex-1 flex justify-center items-center gap-2 border border-[#124375] bg-white navy-shadow py-2 px-2 rounded-[8px] text-[#124375] text-sm">
                                    <iconify-icon icon="material-symbols:check-circle-rounded" class="text-lg"></iconify-icon>
                                    أعتماد
                                </a>
                                <form action="{{ route('admin.permissions.reject', $user->id) }}" method="POST" class="flex-1 m-0 p-0" data-confirm-message="هل أنت متأكد من رفض هذا الطلب؟">
                                    @csrf
                                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-2 text-[#D92D20] rounded-[8px] text-sm">
                                        <iconify-icon icon="material-symbols:cancel-rounded" class="text-lg"></iconify-icon>
                                        رفض
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">لا توجد طلبات انضمام معلقة.</div>
                    @endforelse
                </div>
            </div>
            @if ($pendingRequests->hasPages())
                <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] backdrop-blur-md bg-white/80 z-10 px-6 md:border-x md:border-b border-t border-[#6D6D6D] rounded-b-[14px]">
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
                <div class="hidden md:block">
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
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $user->name }}
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">{{ $user->email }}</td>
                                <td class="py-3 border-l border-[#6D6D6D] ">
                                    {{ $user->deleted_at ? $user->deleted_at->format('d M Y') : '-' }}</td>
                                <td class="py-3 flex gap-5 justify-center text-[#124375]">
                                    <form action="{{ route('admin.permissions.restore', $user->id) }}" method="POST"
                                        class="inline m-0 p-0"
                                        data-confirm-message="هل أنت متأكد من إستعادة هذا الطلب؟">
                                        @csrf
                                        <button type="submit"
                                            class="flex border border-[#124375] items-center gap-3 bg-white navy-shadow py-2 px-4 rounded-[12px]">
                                            <iconify-icon icon="pajamas:redo"
                                                class="text-xl text-[#124375]"></iconify-icon>
                                            إستعادة للمراجعة
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.permissions.destroy', $user->id) }}" method="POST"
                                        class="inline m-0 p-0"
                                        data-confirm-message="هل أنت متأكد من الحذف النهائي؟">
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

                <!-- Mobile Cards (Rejected) -->
                <div class="md:hidden flex flex-col gap-4">
                    @forelse($rejectedRequests as $user)
                        <div class="bg-white rounded-[14px] border border-[#6D6D6D] p-4 flex flex-col gap-3 shadow-sm">
                            <div class="flex justify-between items-start">
                                <h3 class="text-[#021219] font-semibold text-lg">{{ $user->name }}</h3>
                                <span class="text-xs text-[#6D6D6D]">{{ $user->deleted_at ? $user->deleted_at->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-2 text-sm text-[#021219]">
                                <div class="flex gap-2 items-center">
                                    <iconify-icon icon="mdi:email" class="text-[#6D6D6D]"></iconify-icon>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3 justify-center mt-2 pt-3 border-t border-gray-100">
                                <form action="{{ route('admin.permissions.restore', $user->id) }}" method="POST" class="w-full m-0 p-0" data-confirm-message="هل أنت متأكد من إستعادة هذا الطلب؟">
                                    @csrf
                                    <button type="submit" class="w-full flex justify-center border border-[#124375] items-center gap-3 bg-white navy-shadow py-2 px-4 rounded-[8px] text-[#124375] text-sm">
                                        <iconify-icon icon="pajamas:redo" class="text-lg"></iconify-icon>
                                        إستعادة للمراجعة
                                    </button>
                                </form>
                                <form action="{{ route('admin.permissions.destroy', $user->id) }}" method="POST" class="w-full m-0 p-0" data-confirm-message="هل أنت متأكد من الحذف النهائي؟">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex justify-center items-center gap-3 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-4 text-[#D92D20] rounded-[8px] text-sm">
                                        <iconify-icon icon="material-symbols:delete-rounded" class="text-lg"></iconify-icon>
                                        حذف نهائي
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">لا توجد طلبات مرفوضة.</div>
                    @endforelse
                </div>
            </div>
            @if ($rejectedRequests->hasPages())
                <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] backdrop-blur-md bg-white/80 z-10 px-6 md:border-x md:border-b border-[#6D6D6D] rounded-b-[14px]">
                    {{ $rejectedRequests->links() }}
                </div>
            @endif
        </div>
    </section>
    <!-- end table -->

    @include('admin.components.permissions_modals')

    <script src="{{ asset('js/admin/permissions.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const tabs = document.querySelectorAll(".tab-item");

            let activeTabName = null;

            if (params.has('rejected_page')) {
                activeTabName = "الطلبات المرفوضة";
            } else if (params.has('pending_page')) {
                activeTabName = "طلبات الانضمام المعلقة";
            } else if (params.has('suspended_page')) {
                activeTabName = "الحسابات الموقوفة";
            } else if (params.has('active_page')) {
                activeTabName = "الحسابات النشطة";
            }

            if (activeTabName) {
                tabs.forEach(tab => {
                    const tabName = tab.querySelector('button').textContent.split('(')[0].trim();
                    if (tabName === activeTabName) {
                        tab.click();
                    }
                });
            }
        });
    </script>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const forms = document.querySelectorAll('form[data-confirm-message]');
                forms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const message = this.getAttribute('data-confirm-message');
                        Swal.fire({
                            title: 'تأكيد الإجراء',
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#124375',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'نعم، متأكد!',
                            cancelButtonText: 'إلغاء'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection

