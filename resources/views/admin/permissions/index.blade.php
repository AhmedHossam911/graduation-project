@extends('layouts.app')
{{--
    Permissions View:
    Interface for the Super Admin to manage employee/admin roles and permissions.
    Handles active, suspended, pending, and rejected account requests.
--}}

@section('title', 'صلاحيات المستخدمين')

@include('partials.common.flash')

@section('content')

    @php
        $roleColors = [
            'مدير النظام' => ['text' => '#175CD3', 'bg' => '#EFF4FF', 'border' => '#175CD3'],
            'موظف' => ['text' => '#5925DC', 'bg' => '#F4F0FF', 'border' => '#5925DC'],
            'عضو' => ['text' => '#019168', 'bg' => '#F0FFF6', 'border' => '#019168'],
        ];

        $permissionColors = [
            'إدارة الأعضاء' => ['text' => '#F59E0B', 'bg' => '#FEF3C7', 'border' => '#F59E0B'],
            'إدارة الصلاحيات' => ['text' => '#4F46E5', 'bg' => '#E0E7FF', 'border' => '#4F46E5'],
            'إدارة الاشتراكات' => ['text' => '#0284C7', 'bg' => '#E0F2FE', 'border' => '#0284C7'],
            'إعدادات اللائحة' => ['text' => '#9333EA', 'bg' => '#F3E8FF', 'border' => '#9333EA'],
            'إدارة القروض' => ['text' => '#EA580C', 'bg' => '#FFEDD5', 'border' => '#EA580C'],
            'عرض التقارير' => ['text' => '#0D9488', 'bg' => '#CCFBF1', 'border' => '#0D9488'],
            'إدارة المطالبات' => ['text' => '#E11D48', 'bg' => '#FFE4E6', 'border' => '#E11D48'],
            'الشؤون المالية' => ['text' => '#16A34A', 'bg' => '#DCFCE7', 'border' => '#16A34A'],
            'سجل العمليات' => ['text' => '#475569', 'bg' => '#F1F5F9', 'border' => '#475569'],
        ];
    @endphp

    <link rel="stylesheet" href="{{ asset('css/admin/permissions.css') }}">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center py-5 lg:py-7 px-3 gap-4 lg:gap-0">
        <div class="flex flex-col gap-2 lg:gap-3">
            <h1 class="text-lg lg:text-xl text-[#124375] font-semibold">
                إدارة النظام والصلاحيات
            </h1>
            <p class="text-[#6D6D6D] text-[14px] lg:text-[16px] font-normal">
                إدارة حسابات الموظفين ومجلس الإدارة ، طلبت الانضمام ، وتحديد الصلاحيات الممنوحة
            </p>
        </div>
        <div class="w-full lg:w-auto">
            <a href="{{ route('admin.permissions.create') }}"
                class="cursor-pointer hover:bg-[#0e3560] transition-colors text-[15px] lg:text-[16px] navy-shadow flex items-center justify-center gap-2 lg:gap-4 bg-[#124375] text-[#F4F7F9] py-2.5 px-6 lg:px-14 rounded-[12px] w-full lg:w-auto">
                <iconify-icon icon="ic:round-plus" class="text-xl lg:text-2xl mt-1"></iconify-icon>
                إضافة و تفويض مستخدم
            </a>
        </div>
    </div>

    <div class="px-3">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-center navy-shadow rounded-[16px] py-4 lg:py-5 px-3">
            <div class="flex gap-2 w-full">
                <form action="{{ route('admin.permissions.index') }}" method="GET" class="w-full flex gap-2 m-0 p-0">
                    <div class="w-full relative">
                        <input name="search" value="{{ request('search') }}"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow w-full outline-none navy-shadow rounded-[10px] py-2 pr-8 text-[14px] lg:text-[16px]"
                            placeholder="الاسم أو البريد الإلكتروني أو  الصفة الإدارية  " />
                        <iconify-icon icon="ri:search-line"
                            class="text-xl lg:text-2xl text-[#124375] absolute top-1/2 -translate-y-1/2 right-2 "></iconify-icon>
                    </div>
                    <button type="submit"
                        class="bg-[#124375] rounded-[12px] px-4 lg:px-8 navy-shadow hover:bg-[#0e3560] transition-colors shrink-0">
                        <iconify-icon icon="ri:search-line"
                            class="text-xl lg:text-2xl text-[#F4F7F9] flex items-center"></iconify-icon>
                    </button>
                </form>
            </div>
            <div class="flex gap-2 w-full">
                <div class="bg-[#EAF5FF] py-2 px-1 rounded-[10px] w-full text-center flex flex-col justify-center">
                    <p class="text-[#124375] text-[12px] sm:text-[14px] lg:text-[16px]">إجمالي الحسابات :<br class="sm:hidden"><span class="font-bold sm:font-normal text-[14px] sm:text-auto"> {{ $activeUsers->total() + $suspendedUsers->total() }}</span></p>
                </div>
                <div class="bg-[#EFEFEF] py-2 px-1 rounded-[10px] w-full text-center flex flex-col justify-center">
                    <p class="text-[#021219] text-[12px] sm:text-[14px] lg:text-[16px]">حسابات موقوفة :<br class="sm:hidden"><span class="font-bold sm:font-normal text-[14px] sm:text-auto"> {{ $suspendedUsers->total() }}</span></p>
                </div>
                {{-- <div class="bg-[#FFF7ED] py-2 px-1 rounded-[10px] w-full text-center flex flex-col justify-center">
                    <p class="text-[#F79009] text-[12px] sm:text-[14px] lg:text-[16px]">طلبات جديدة :<br class="sm:hidden"><span class="font-bold sm:font-normal text-[14px] sm:text-auto"> {{ $pendingRequests->total() }}</span></p>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="tabs mx-2 md:mx-12 pt-7 flex gap-3 md:gap-8 border-b border-[#A8A8A8] overflow-x-auto no-scrollbar whitespace-nowrap px-2">
        <div class="tab-item active px-3 md:px-7 flex gap-1 md:gap-2 items-center relative hover:text-[#019168] transition-colors cursor-pointer green-active"
            data-active="green-active">
            <iconify-icon icon="mdi:account-group" class="text-2xl md:text-3xl flex items-center"></iconify-icon>
            <button class="text-[14px] md:text-[16px] font-medium pointer-events-none">الحسابات النشطة
                <span>({{ $activeUsers->total() }})</span></button>
            <span class="absolute bg-[#019168] bottom-[-1px] left-0 h-[2px] w-full"></span>
        </div>
        <div class="tab-item px-3 md:px-7 flex gap-1 md:gap-2 items-center pb-3 relative hover:text-[#021219] transition-colors cursor-pointer"
            data-active="black-active">
            <iconify-icon icon="ri:user-minus-fill" class="text-2xl md:text-3xl flex items-center"></iconify-icon>
            <button class="text-[14px] md:text-[16px] font-medium pointer-events-none">الحسابات الموقوفة
                <span>({{ $suspendedUsers->total() }})</span></button>
            <span class="absolute bg-[#021219] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
        {{-- <div class="tab-item px-3 md:px-7 flex gap-1 md:gap-2 items-center pb-3 relative hover:text-[#F79009] transition-colors cursor-pointer "
            data-active="orange-active">
            <iconify-icon icon="octicon:shield-16" class="text-2xl md:text-3xl flex items-center"></iconify-icon>
            <button class="text-[14px] md:text-[16px] font-medium pointer-events-none">طلبات الانضمام المعلقة
                <span>({{ $pendingRequests->total() }})</span></button>
            <span class="absolute bg-[#F79009] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
        <div class="tab-item px-3 md:px-7 flex gap-1 md:gap-2 items-center pb-3 relative hover:text-[#D92D20] transition-colors cursor-pointer "
            data-active="red-active">
            <iconify-icon icon="mingcute:user-x-fill" class="text-2xl md:text-3xl flex items-center "></iconify-icon>
            <button class="text-[14px] md:text-[16px] font-medium pointer-events-none">الطلبات المرفوضة
                <span>({{ $rejectedRequests->total() }})</span></button>
            <span class="absolute bg-[#D92D20] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div> --}}
    </div>

    <!-- start table -->
    <section class="px-3 py-8">
        <div class=" rounded-[14px] tab-content overflow-hidden border border-[#6D6D6D]" data-tab="الحسابات النشطة">
            <div class="hidden md:block">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الاسم </th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني</th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الصفة الإدارية</th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                            <th class="py-4 border-l border-[#6D6D6D] font-medium text-[#021219]">مسميات الصلاحيات الممنوحة
                            </th>
                            <th class="py-4 font-medium text-[#021219]">إدارة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeUsers as $user)
                            <tr class="text-center border-b border-[#6D6D6D] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $user->name }}
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]" title="{{ $user->email }}">
                                    <span
                                        class="inline-block max-w-[180px] truncate align-middle cursor-pointer hover:text-[#124375] transition-colors"
                                        onclick="copyEmailToClipboard('{{ $user->email }}')"
                                        dir="ltr">{{ $user->email }}</span>
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    @php
                                        $roleName = $user->role ? $user->role->arabic_name : 'غير محدد';
                                        $rColor = ['text' => '#019168', 'bg' => '#F0FFF6', 'border' => '#019168'];
                                        foreach ($roleColors as $key => $colors) {
                                            if (str_contains($roleName, $key)) {
                                                $rColor = $colors;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <span
                                        style="color: {{ $rColor['text'] }}; background-color: {{ $rColor['bg'] }}; border-color: {{ $rColor['border'] }};"
                                        class="border rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                        {{ $roleName }}</span>
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                        class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] ">
                                    @if ($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                        @php
                                            $firstPerm = $user->custom_permissions[0];
                                            $pColor = $permissionColors[$firstPerm] ?? [
                                                'text' => '#6D6D6D',
                                                'bg' => '#F2F4F7',
                                                'border' => '#6D6D6D',
                                            ];
                                        @endphp
                                        <span
                                            style="color: {{ $pColor['text'] }}; background-color: {{ $pColor['bg'] }}; border-color: {{ $pColor['border'] }};"
                                            class="border rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">{{ $firstPerm }}</span>
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
                            <span
                                class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 text-sm">نشط</span>
                        </div>
                        <div class="flex flex-col gap-2 text-sm text-[#021219]">
                            <div class="flex gap-2 items-center">
                                <iconify-icon icon="mdi:email" class="text-[#6D6D6D]"></iconify-icon>
                                <span class="truncate max-w-[200px] cursor-pointer hover:text-[#124375] transition-colors"
                                    onclick="copyEmailToClipboard('{{ $user->email }}')" title="{{ $user->email }}"
                                    dir="ltr">{{ $user->email }}</span>
                            </div>
                            <div class="flex gap-2 items-center">
                                <iconify-icon icon="mdi:badge-account" class="text-[#6D6D6D]"></iconify-icon>
                                @php
                                    $roleName = $user->role ? $user->role->arabic_name : 'غير محدد';
                                    $rColor = ['text' => '#019168', 'bg' => '#F0FFF6', 'border' => '#019168'];
                                    foreach ($roleColors as $key => $colors) {
                                        if (str_contains($roleName, $key)) {
                                            $rColor = $colors;
                                            break;
                                        }
                                    }
                                @endphp
                                <span
                                    style="color: {{ $rColor['text'] }}; background-color: {{ $rColor['bg'] }}; border-color: {{ $rColor['border'] }};"
                                    class="border rounded-[8px] py-[1px] px-2 text-xs">
                                    {{ $roleName }}
                                </span>
                            </div>
                            <div class="flex gap-2 flex-wrap mt-1">
                                @if ($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                    @php
                                        $firstPerm = $user->custom_permissions[0];
                                        $pColor = $permissionColors[$firstPerm] ?? [
                                            'text' => '#6D6D6D',
                                            'bg' => '#F2F4F7',
                                            'border' => '#6D6D6D',
                                        ];
                                    @endphp
                                    <span
                                        style="color: {{ $pColor['text'] }}; background-color: {{ $pColor['bg'] }}; border-color: {{ $pColor['border'] }};"
                                        class="border rounded-[8px] py-[1px] px-2 text-xs">{{ $firstPerm }}</span>
                                    @if (count($user->custom_permissions) > 1)
                                        <span
                                            class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-2 text-xs">+{{ count($user->custom_permissions) - 1 }}
                                            أخري</span>
                                    @endif
                                @else
                                    <span
                                        class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-2 text-xs">لا
                                        يوجد صلاحيات</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-4 items-center justify-end mt-2 pt-3 border-t border-gray-100 text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer open-modal"
                                data-modal="modal-{{ $user->id }}"></iconify-icon>
                            <a href="{{ route('admin.permissions.edit', $user->id) }}" class="flex items-center">
                                <iconify-icon icon="ic:round-edit"
                                    class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            </a>
                            <form action="{{ route('admin.permissions.suspend', $user->id) }}" method="POST"
                                class="inline m-0 p-0" data-confirm-message="هل أنت متأكد من إيقاف حساب هذا المستخدم؟">
                                @csrf
                                <button type="submit"
                                    class="border-none bg-transparent m-0 p-0 flex items-center justify-center">
                                    <iconify-icon icon="zondicons:close-solid"
                                        class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">لا توجد حسابات نشطة.
                    </div>
                @endforelse
            </div>

            @if ($activeUsers->hasPages())
                <div
                    class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t md:border-t border-[#A8A8A8] mt-4 backdrop-blur-md bg-white/80 z-10 px-6 rounded-b-[14px]">
                    {{ $activeUsers->links() }}
                </div>
            @endif
        </div>

        <div class=" rounded-[14px] hidden tab-content overflow-hidden border border-[#6D6D6D]"
            data-tab="الحسابات الموقوفة">
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
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">{{ $user->name }}
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]" title="{{ $user->email }}">
                                    <span
                                        class="inline-block max-w-[180px] truncate align-middle cursor-pointer hover:text-[#124375] transition-colors"
                                        onclick="copyEmailToClipboard('{{ $user->email }}')"
                                        dir="ltr">{{ $user->email }}</span>
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                    @php
                                        $roleName = $user->role ? $user->role->arabic_name : 'غير محدد';
                                        $rColor = ['text' => '#019168', 'bg' => '#F0FFF6', 'border' => '#019168'];
                                        foreach ($roleColors as $key => $colors) {
                                            if (str_contains($roleName, $key)) {
                                                $rColor = $colors;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <span
                                        style="color: {{ $rColor['text'] }}; background-color: {{ $rColor['bg'] }}; border-color: {{ $rColor['border'] }};"
                                        class="border rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                        {{ $roleName }}</span>
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                        class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">موقوف</span>
                                </td>
                                <td class="py-3 border-l border-[#6D6D6D] ">
                                    @if ($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                        @php
                                            $firstPerm = $user->custom_permissions[0];
                                            $pColor = $permissionColors[$firstPerm] ?? [
                                                'text' => '#6D6D6D',
                                                'bg' => '#F2F4F7',
                                                'border' => '#6D6D6D',
                                            ];
                                        @endphp
                                        <span
                                            style="color: {{ $pColor['text'] }}; background-color: {{ $pColor['bg'] }}; border-color: {{ $pColor['border'] }};"
                                            class="border rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">{{ $firstPerm }}</span>
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
                            <span
                                class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 text-sm">موقوف</span>
                        </div>
                        <div class="flex flex-col gap-2 text-sm text-[#021219]">
                            <div class="flex gap-2 items-center">
                                <iconify-icon icon="mdi:email" class="text-[#6D6D6D]"></iconify-icon>
                                <span class="truncate max-w-[200px] cursor-pointer hover:text-[#124375] transition-colors"
                                    onclick="copyEmailToClipboard('{{ $user->email }}')" title="{{ $user->email }}"
                                    dir="ltr">{{ $user->email }}</span>
                            </div>
                            <div class="flex gap-2 items-center">
                                <iconify-icon icon="mdi:badge-account" class="text-[#6D6D6D]"></iconify-icon>
                                @php
                                    $roleName = $user->role ? $user->role->arabic_name : 'غير محدد';
                                    $rColor = ['text' => '#019168', 'bg' => '#F0FFF6', 'border' => '#019168'];
                                    foreach ($roleColors as $key => $colors) {
                                        if (str_contains($roleName, $key)) {
                                            $rColor = $colors;
                                            break;
                                        }
                                    }
                                @endphp
                                <span
                                    style="color: {{ $rColor['text'] }}; background-color: {{ $rColor['bg'] }}; border-color: {{ $rColor['border'] }};"
                                    class="border rounded-[8px] py-[1px] px-2 text-xs">
                                    {{ $roleName }}
                                </span>
                            </div>
                            <div class="flex gap-2 flex-wrap mt-1">
                                @if ($user->custom_permissions && is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                                    @php
                                        $firstPerm = $user->custom_permissions[0];
                                        $pColor = $permissionColors[$firstPerm] ?? [
                                            'text' => '#6D6D6D',
                                            'bg' => '#F2F4F7',
                                            'border' => '#6D6D6D',
                                        ];
                                    @endphp
                                    <span
                                        style="color: {{ $pColor['text'] }}; background-color: {{ $pColor['bg'] }}; border-color: {{ $pColor['border'] }};"
                                        class="border rounded-[8px] py-[1px] px-2 text-xs">{{ $firstPerm }}</span>
                                    @if (count($user->custom_permissions) > 1)
                                        <span
                                            class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-2 text-xs">+{{ count($user->custom_permissions) - 1 }}
                                            أخري</span>
                                    @endif
                                @else
                                    <span
                                        class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-2 text-xs">لا
                                        يوجد صلاحيات</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-4 items-center justify-end mt-2 pt-3 border-t border-gray-100 text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer open-modal"
                                data-modal="modal-{{ $user->id }}"></iconify-icon>
                            <a href="{{ route('admin.permissions.edit', $user->id) }}" class="flex items-center">
                                <iconify-icon icon="ic:round-edit"
                                    class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            </a>
                            <form action="{{ route('admin.permissions.reactivate', $user->id) }}" method="POST"
                                class="inline m-0 p-0" data-confirm-message="هل أنت متأكد من إعادة تفعيل هذا المستخدم؟">
                                @csrf
                                <button type="submit"
                                    class="border-none bg-transparent m-0 p-0 flex items-center justify-center">
                                    <iconify-icon icon="healthicons:yes"
                                        class="text-xl text-[#019168] cursor-pointer "></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">لا توجد حسابات موقوفة.
                    </div>
                @endforelse
            </div>

            @if ($suspendedUsers->hasPages())
                <div
                    class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t md:border-t border-[#A8A8A8] mt-4 backdrop-blur-md bg-white/80 z-10 px-6 rounded-b-[14px]">
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
                                <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني
                                </th>
                                <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219] px-7">تاريخ الطلب</th>
                                <th class="py-3 font-medium text-[#021219]">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingRequests as $user)
                                <tr class="text-center border-b border-[#6D6D6D] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                                    <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">
                                        {{ $user->name }}
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
                                            class="inline m-0 p-0" data-confirm-message="هل أنت متأكد من رفض هذا الطلب؟">
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
                                <span
                                    class="text-xs text-[#6D6D6D]">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-2 text-sm text-[#021219]">
                                <div class="flex gap-2 items-center">
                                    <iconify-icon icon="mdi:email" class="text-[#6D6D6D]"></iconify-icon>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="flex gap-3 justify-center mt-2 pt-3 border-t border-gray-100">
                                <a href="{{ route('admin.permissions.edit', $user->id) }}"
                                    class="flex-1 flex justify-center items-center gap-2 border border-[#124375] bg-white navy-shadow py-2 px-2 rounded-[8px] text-[#124375] text-sm">
                                    <iconify-icon icon="material-symbols:check-circle-rounded"
                                        class="text-lg"></iconify-icon>
                                    أعتماد
                                </a>
                                <form action="{{ route('admin.permissions.reject', $user->id) }}" method="POST"
                                    class="flex-1 m-0 p-0" data-confirm-message="هل أنت متأكد من رفض هذا الطلب؟">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex justify-center items-center gap-2 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-2 text-[#D92D20] rounded-[8px] text-sm">
                                        <iconify-icon icon="material-symbols:cancel-rounded"
                                            class="text-lg"></iconify-icon>
                                        رفض
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">لا توجد طلبات انضمام
                            معلقة.</div>
                    @endforelse
                </div>
            </div>
            @if ($pendingRequests->hasPages())
                <div
                    class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] backdrop-blur-md bg-white/80 z-10 px-6 md:border-x md:border-b border-t border-[#6D6D6D] rounded-b-[14px]">
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
                                <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">البريد الإلكتروني
                                </th>
                                <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219] px-7">تاريخ الطلب</th>
                                <th class="py-3 font-medium text-[#021219]">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rejectedRequests as $user)
                                <tr class="text-center border-b border-[#6D6D6D] {{ $loop->even ? 'bg-[#EFEFEF]' : '' }}">
                                    <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">
                                        {{ $user->name }}
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
                                            class="inline m-0 p-0" data-confirm-message="هل أنت متأكد من الحذف النهائي؟">
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
                                <span
                                    class="text-xs text-[#6D6D6D]">{{ $user->deleted_at ? $user->deleted_at->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-2 text-sm text-[#021219]">
                                <div class="flex gap-2 items-center">
                                    <iconify-icon icon="mdi:email" class="text-[#6D6D6D]"></iconify-icon>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3 justify-center mt-2 pt-3 border-t border-gray-100">
                                <form action="{{ route('admin.permissions.restore', $user->id) }}" method="POST"
                                    class="w-full m-0 p-0" data-confirm-message="هل أنت متأكد من إستعادة هذا الطلب؟">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex justify-center border border-[#124375] items-center gap-3 bg-white navy-shadow py-2 px-4 rounded-[8px] text-[#124375] text-sm">
                                        <iconify-icon icon="pajamas:redo" class="text-lg"></iconify-icon>
                                        إستعادة للمراجعة
                                    </button>
                                </form>
                                <form action="{{ route('admin.permissions.destroy', $user->id) }}" method="POST"
                                    class="w-full m-0 p-0" data-confirm-message="هل أنت متأكد من الحذف النهائي؟">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full flex justify-center items-center gap-3 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-4 text-[#D92D20] rounded-[8px] text-sm">
                                        <iconify-icon icon="material-symbols:delete-rounded"
                                            class="text-lg"></iconify-icon>
                                        حذف نهائي
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 bg-white rounded-[14px] border border-[#6D6D6D]">لا توجد طلبات مرفوضة.
                        </div>
                    @endforelse
                </div>
            </div>
            @if ($rejectedRequests->hasPages())
                <div
                    class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] backdrop-blur-md bg-white/80 z-10 px-6 md:border-x md:border-b border-[#6D6D6D] rounded-b-[14px]">
                    {{ $rejectedRequests->links() }}
                </div>
            @endif
        </div>

    </section>
    <!-- end table -->

    @include('admin.components.permissions_modals')

    <script src="{{ asset('js/admin/permissions.js') }}"></script>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('form[data-confirm-message]');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
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

            function copyEmailToClipboard(email) {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(email).then(() => {
                        showCopySuccessToast(email);
                    });
                } else {
                    let textArea = document.createElement("textarea");
                    textArea.value = email;
                    textArea.style.position = "fixed";
                    textArea.style.left = "-999999px";
                    textArea.style.top = "-999999px";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        showCopySuccessToast(email);
                    } catch (err) {
                        console.error('Fallback: Oops, unable to copy', err);
                    }
                    document.body.removeChild(textArea);
                }
            }

            function showCopySuccessToast(email) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم النسخ بنجاح!',
                    html: `تم نسخ البريد الإلكتروني <br><b dir="ltr" class="text-[#124375] mt-1 inline-block">${email}</b>`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            }
        </script>
    @endpush
@endsection
