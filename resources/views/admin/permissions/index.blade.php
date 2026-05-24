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
            <a href="../GrantAccessPage/grantaccess.html"
                class="cursor-pointer hover:bg-[#0e3560] transition-colors text-[16px] navy-shadow flex items-center justify-center gap-4 bg-[#124375] text-[#F4F7F9] py-2.5 px-14 rounded-[12px]">
                <iconify-icon icon="ic:round-plus" class="text-2xl mt-1"></iconify-icon>
                إضافة و تفويض مستخدم
            </a>
        </div>
    </div>

    <div class="px-12">
        <div class="grid grid-cols-3 gap-10 items-center navy-shadow rounded-[16px] py-5 px-3">
            <div class="flex gap-2 col-span-2">
                <div class="w-full relative">
                    <input
                        class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow w-full outline-none navy-shadow rounded-[10px] py-2 pr-8"
                        placeholder="الاسم أو البريد الإلكتروني أو  الصفة الإدارية أو الصلاحيات الممنوحة" />
                    <iconify-icon icon="ri:search-line"
                        class="text-2xl text-[#124375] absolute top-1/2 -translate-y-1/2 right-1 "></iconify-icon>
                </div>
                <button class="bg-[#124375] rounded-[12px] px-8 navy-shadow hover:bg-[#0e3560] transition-colors">
                    <iconify-icon icon="ri:search-line" class="text-2xl text-[#F4F7F9] flex items-center"></iconify-icon>
                </button>
            </div>
            <div class="flex justify-between ">
                <div class="bg-[#EAF5FF] py-2 px-3 rounded-[10px]">
                    <p class="text-[#124375]">إجمالي الحسابات :<span>3</span></p>
                </div>
                <div class="bg-[#EFEFEF] py-2 px-3 rounded-[10px]">
                    <p class="text-[#021219]">حسابات موقوفة :<span>3</span></p>
                </div>
                <div class="bg-[#FFF7ED] py-2 px-3 rounded-[10px]">
                    <p class="text-[#F79009]">طلبات جديدة :<span>3</span></p>
                </div>
            </div>
        </div>
    </div>


    <div class="tabs mx-12  pt-7 flex gap-8 border-b border-[#A8A8A8]">
        <div class="tab-item active px-7 flex gap-2 items-center pb-3 relative hover:text-[#019168] transition-colors cursor-pointer green-active"
            data-active="green-active">
            <iconify-icon icon="mdi:account-group" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الحسابات النشطة <span>(9)</span></button>
            <span class="absolute bg-[#019168] bottom-[-1px] left-0 h-[2px] w-full"></span>
        </div>
        <div class="tab-item px-7 flex gap-2 items-center  pb-3 relative hover:text-[#021219] transition-colors cursor-pointer"
            data-active="black-active">
            <iconify-icon icon="ri:user-minus-fill" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الحسابات الموقوفة <span>(9)</span></button>
            <span class="absolute bg-[#021219] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
        <div class="tab-item px-7 flex gap-2 items-center  pb-3 relative hover:text-[#F79009] transition-colors cursor-pointer "
            data-active="orange-active">
            <iconify-icon icon="octicon:shield-16" class="text-3xl  flex items-center"></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">طلبات الانضمام المعلقة <span>(9)</span></button>
            <span class="absolute bg-[#F79009] bottom-[-1px] left-0 h-[2px] w-full hidden"></span>
        </div>
        <div class="tab-item px-7 flex gap-2  items-center pb-3 relative hover:text-[#D92D20] transition-colors cursor-pointer "
            data-active="red-active">
            <iconify-icon icon="mingcute:user-x-fill" class="text-3xl  flex items-center "></iconify-icon>
            <button class="text-[16px] font-medium pointer-events-none">الطلبات المرفوضة <span>(9)</span></button>
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
                    <tr class="text-center border-b border-[#6D6D6D] bg-[#EFEFEF]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">chairman@helwan.edu.eg</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#019168] bg-[#F0FFF6] border border-[#019168] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                المدير المالي</span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            <span
                                class="text-[#16A34A] bg-[#DCFCE7] border border-[#16A34A] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">مسؤول
                                المالية</span>
                            <span
                                class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">+2
                                صلاحيات أخري</span>
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear" class="text-2xl text-[#021219] cursor-pointer open-modal "
                                data-modal="modal1"></iconify-icon>
                            <iconify-icon icon="ic:round-edit"
                                class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="zondicons:close-solid"
                                class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                        </td>
                    </tr>
                    <tr class="text-center border-b border-[#6D6D6D]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">chairman@helwan.edu.eg</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#5925DC] bg-[#F4F3FF] border border-[#5925DC] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                رئيس مجلس الإدارة</span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            <span
                                class="text-[#124375] bg-[#E6F1FD80] border border-[#124375] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">صلاحيات
                                كاملة (مدير نظام)</span>
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear" class="text-2xl text-[#021219] cursor-pointer  open-modal"
                                data-modal="modal2"></iconify-icon>
                            <iconify-icon icon="ic:round-edit"
                                class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="zondicons:close-solid"
                                class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                        </td>
                    </tr>
                    <tr class="text-center border-b border-[#6D6D6D] bg-[#EFEFEF]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">chairman@helwan.edu.eg</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                موظف </span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            <span
                                class="text-[#0284C7] bg-[#E0F2FE] border border-[#0284C7] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">مسؤول
                                الاشتراكات</span>
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="ic:round-edit"
                                class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="zondicons:close-solid"
                                class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                        </td>
                    </tr>
                    <tr class="text-center border-b border-[#6D6D6D]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">chairman@helwan.edu.eg</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#175CD3] bg-[#EFF8FF] border border-[#175CD3] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                المدير التنفيذي</span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            <span
                                class="text-[#E11D48] bg-[#FFE4E6] border border-[#E11D48] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">مسؤول
                                المطالبات</span>
                            <span
                                class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">+2
                                صلاحيات أخري</span>
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="ic:round-edit"
                                class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="zondicons:close-solid"
                                class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                        </td>
                    </tr>
                    <tr class="text-center bg-[#EFEFEF]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">chairman@helwan.edu.eg</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#1E3A8A] bg-[#EFF6FF] border border-[#1E3A8A] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">عضو</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#067647] bg-[#ECFDF3] border border-[#067647] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">نشط</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            <span
                                class="text-[#5D4037] bg-[#EFEBE9] border border-[#5D4037] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">حساب
                                عضو</span>
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="ic:round-edit"
                                class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="zondicons:close-solid"
                                class="text-xl text-[#D92D20] cursor-pointer "></iconify-icon>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                    <tr class="text-center border-b border-[#6D6D6D] bg-[#EFEFEF]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">chairman@helwan.edu.eg</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#019168] bg-[#F0FFF6] border border-[#019168] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                المدير المالي</span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">موقوف</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            <span
                                class="text-[#16A34A] bg-[#DCFCE7] border border-[#16A34A] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">مسؤول
                                المالية</span>
                            <span
                                class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">+2
                                صلاحيات أخري</span>
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer open-modal"
                                data-modal="modal1"></iconify-icon>
                            <iconify-icon icon="ic:round-edit"
                                class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="healthicons:yes"
                                class="text-xl text-[#019168] cursor-pointer "></iconify-icon>
                        </td>
                    </tr>
                    <tr class="text-center border-b border-[#6D6D6D] ">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">chairman@helwan.edu.eg</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#5925DC] bg-[#F4F3FF] border border-[#5925DC] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                رئيس مجلس الإدارة</span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">موقوف</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            <span
                                class="text-[#124375] bg-[#E6F1FD80] border border-[#124375] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">صلاحيات
                                كاملة (مدير نظام)</span>
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer  open-modal"
                                data-modal="modal2"></iconify-icon>
                            <iconify-icon icon="ic:round-edit"
                                class="text-2xl text-[#124375] cursor-pointer"></iconify-icon>
                            <iconify-icon icon="healthicons:yes"
                                class="text-xl text-[#019168] cursor-pointer "></iconify-icon>
                        </td>
                    </tr>
                    <tr class="text-center bg-[#EFEFEF]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">chairman@helwan.edu.eg</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#175CD3] bg-[#EFF8FF] border border-[#175CD3] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">
                                المدير التنفيذي</span></td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]"><span
                                class="text-[#021219] bg-[#F2F4F7] border border-[#021219] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[100px]">موقوف</span>
                        </td>
                        <td class="py-3 border-l border-[#6D6D6D] ">
                            <span
                                class="text-[#E11D48] bg-[#FFE4E6] border border-[#E11D48] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">مسؤول
                                المطالبات</span>
                            <span
                                class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] rounded-[8px] py-[1px] px-3 inline-block text-center min-w-[145px]">+2
                                صلاحيات أخري</span>
                        </td>
                        <td class="py-3 flex gap-7 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-linear"
                                class="text-2xl text-[#021219] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="ic:round-edit"
                                class="text-2xl text-[#124375] cursor-pointer "></iconify-icon>
                            <iconify-icon icon="healthicons:yes"
                                class="text-xl text-[#019168] cursor-pointer"></iconify-icon>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                        <tr class="text-center border-b border-[#6D6D6D] bg-[#EFEFEF]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">chairman@helwan.edu.eg</td>
                            <td class="py-3 border-l border-[#6D6D6D] ">1 مايو 2026</td>
                            <td class="py-3 flex gap-5 justify-center text-[#124375]">
                                <button
                                    class="flex items-center gap-3 border border-[#124375] bg-white navy-shadow py-2 px-4 rounded-[12px]">
                                    <iconify-icon icon="material-symbols:check-circle-rounded"
                                        class="text-xl text-[#124375]"></iconify-icon>
                                    أعتماد الصلاحيات
                                </button>
                                <button
                                    class="flex items-center gap-3 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-4 text-[#D92D20] rounded-[12px]">
                                    <iconify-icon icon="material-symbols:cancel-rounded"
                                        class="text-xl text-[#D92D20]"></iconify-icon>
                                    رفض
                                </button>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">chairman@helwan.edu.eg</td>
                            <td class="py-3 border-l border-[#6D6D6D] ">1 مايو 2026</td>
                            <td class="py-3 flex gap-5 justify-center  text-[#124375]">
                                <button
                                    class="flex items-center gap-3 border border-[#124375] bg-white navy-shadow py-2 px-4 rounded-[12px]">
                                    <iconify-icon icon="material-symbols:check-circle-rounded"
                                        class="text-xl text-[#124375] flex items-center"></iconify-icon>
                                    أعتماد الصلاحيات
                                </button>
                                <button
                                    class="flex items-center gap-3 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-4 text-[#D92D20] rounded-[12px]">
                                    <iconify-icon icon="material-symbols:cancel-rounded"
                                        class="text-xl text-[#D92D20] flex items-center"></iconify-icon>
                                    رفض
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
                        <tr class="text-center border-b border-[#6D6D6D] bg-[#EFEFEF]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] ">chairman@helwan.edu.eg</td>
                            <td class="py-3 border-l border-[#6D6D6D] ">1 مايو 2026</td>
                            <td class="py-3 flex gap-5 justify-center text-[#124375]">
                                <button
                                    class="flex border border-[#124375] items-center gap-3 border border-[#124375] bg-white navy-shadow py-2 px-4 rounded-[12px]">
                                    <iconify-icon icon="pajamas:redo" class="text-xl text-[#124375]"></iconify-icon>
                                    إستعادة للمراجعة
                                </button>
                                <button
                                    class="flex items-center gap-3 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-4 text-[#D92D20] rounded-[12px]">
                                    <iconify-icon icon="material-symbols:delete-rounded"
                                        class="text-xl text-[#D92D20]"></iconify-icon>
                                    حذف نهائي
                                </button>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219] font-medium">روان محمد فتحي</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">chairman@helwan.edu.eg</td>
                            <td class="py-3 border-l border-[#6D6D6D] ">1 مايو 2026</td>
                            <td class="py-3 flex gap-5 justify-center  text-[#124375]">
                                <button
                                    class="flex border border-[#124375] items-center gap-3 border border-[#124375] bg-white navy-shadow py-2 px-4 rounded-[12px]">
                                    <iconify-icon icon="pajamas:redo" class="text-xl text-[#124375]"></iconify-icon>
                                    إستعادة للمراجعة
                                </button>
                                <button
                                    class="flex items-center gap-3 bg-[#FFEAE880] border border-[#FDA29B] red-shadow py-2 px-4 text-[#D92D20] rounded-[12px]">
                                    <iconify-icon icon="material-symbols:delete-rounded"
                                        class="text-xl text-[#D92D20]"></iconify-icon>
                                    حذف نهائي
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- end table -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

    <div id="modal1"
        class="flex flex-col hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-5 max-h-[95vh]">
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
                        صلاحيات أحمد محمود
                    </h2>
                    <p class="text-[#6D6D6D] text-[16px] font-medium">مراجعة المهام ونطاق العمل المكاني ( الكليات ) للموظف
                    </p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="bi:buildings-fill" class="text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[20px] font-semibold">النطاق المكاني : الكليات المسؤول عنها
                            <span>(24)</span>
                        </p>
                    </div>
                    <div
                        class="flex items-center gap-1 bg-[#E6F1FD80] border-[1.5px] border-[#124375] rounded-[8px] py-3 px-3 text-[#124375]">
                        <iconify-icon icon="ic:round-place" class="text-3xl"></iconify-icon>
                        <p class="text-[16px] font-semibold">لديه وصول شامل لكافة كليات الجامعة وقطاعات الإدارة العامة.</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="iconamoon:shield-yes-fill" class="text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[20px] font-semibold">قائمة الصلاحيات الممنوحة <span>(24)</span></p>
                    </div>
                    <div
                        class="bg-[#F0FFF6] space-y-2 border-[1.5px] border-[#019168] rounded-[8px] py-5 px-3 text-[#019168]">
                        <div class="flex items-center gap-1 ">
                            <iconify-icon icon="icon-park-solid:success" class="text-2xl"></iconify-icon>
                            <p class="text-[16px] font-semibold">مدير نظام بصلاحيات كاملة</p>
                        </div>
                        <p class="text-[14px] font-medium">هذا المستخدم لديه وصول غير مقيد لجميع إعدادات النظام</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 flex-1 py-3 overflow-y-auto px-2 no-scrollbar">
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl text-[#124375] text-[#0284C7] bg-[#E0F2FE] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">إدارة الاشتراكات</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مسؤول الاشتراكات</p>
                    </div>
                </div>
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl text-[#124375] text-[#EA580C] bg-[#FFEDD5] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">إدارة القروض</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مسؤول القروض</p>
                    </div>
                </div>
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl text-[#124375] text-[#16A34A] bg-[#DCFCE7] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">الشؤون المالية</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مسؤول المالية</p>
                    </div>
                </div>
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl text-[#124375] text-[#E11D48] bg-[#FFE4E6] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">إدارة المطالبات</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مسؤول المطالبات</p>
                    </div>
                </div>
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl  text-[#0D9488] bg-[#CCFBF1] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">عرض التقارير</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مراقب التقارير</p>
                    </div>
                </div>
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl text-[#124375] text-[#E11D48] bg-[#FFE4E6] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">إدارة المطالبات</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مسؤول المطالبات</p>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 shrink-0 pt-4">
                <form class="w-full flex justify-end">
                    <button
                        class="close-btn rounded-[14px]  py-3 px-20 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors">إغلاق</button>
                </form>
            </div>
        </div>
    </div>

    <div id="modal2"
        class="flex flex-col hidden w-full max-w-3xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-5 max-h-[95vh]">
        <div class="flex justify-end">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
        </div>
        <div class="modal-body px-8 flex-1 flex flex-col overflow-hidden">
            <div class="space-y-3 shrink-0">
                <div class="space-y-1">
                    <h2 class="text-[#021219] text-[28px] font-semibold">
                        صلاحيات أحمد محمود
                    </h2>
                    <p class="text-[#6D6D6D] text-[16px] font-medium">مراجعة المهام ونطاق العمل المكاني ( الكليات ) للموظف
                    </p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="bi:buildings-fill" class="text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[20px] font-semibold">النطاق المكاني : الكليات المسؤول عنها
                            <span>(4)</span>
                        </p>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="flex items-center bg-[#F4F7F9] navy-shadow rounded-[8px] py-1">
                            <iconify-icon icon="tabler:point-filled" class="text-3xl text-[#124375]"></iconify-icon>
                            <span class="text-[14px] text-[#021219] font-medium">كلية التكنولوجيا والتعليم</span>
                        </div>
                        <div class="flex items-center bg-[#F4F7F9] navy-shadow rounded-[8px] py-1">
                            <iconify-icon icon="tabler:point-filled" class="text-3xl text-[#124375]"></iconify-icon>
                            <span class="text-[14px] text-[#021219] font-medium">كلية التمريض</span>
                        </div>
                        <div class="flex items-center bg-[#F4F7F9] navy-shadow rounded-[8px] py-1">
                            <iconify-icon icon="tabler:point-filled" class="text-3xl text-[#124375]"></iconify-icon>
                            <span class="text-[14px] text-[#021219] font-medium">كلية الحاسبات والذكاء الاصطناعي</span>
                        </div>
                        <div class="flex items-center bg-[#F4F7F9] navy-shadow rounded-[8px] py-1">
                            <iconify-icon icon="tabler:point-filled" class="text-3xl text-[#124375]"></iconify-icon>
                            <span class="text-[14px] text-[#021219] font-medium">كلية علوم الرياضة بنين</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="iconamoon:shield-yes-fill" class="text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[20px] font-semibold">قائمة الصلاحيات الممنوحة <span>(3)</span></p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 flex-1 py-3 overflow-y-auto px-2 no-scrollbar">
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl text-[#124375] text-[#0284C7] bg-[#E0F2FE] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">إدارة الاشتراكات</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مسؤول الاشتراكات</p>
                    </div>
                </div>
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl text-[#124375] text-[#EA580C] bg-[#FFEDD5] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">إدارة القروض</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مسؤول القروض</p>
                    </div>
                </div>
                <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                    <iconify-icon icon="mdi:success"
                        class="text-3xl text-[#124375] text-[#16A34A] bg-[#DCFCE7] py-3 px-3 rounded-[8px]"></iconify-icon>
                    <div class="space-y-4">
                        <p class="text-[16px] font-medium text-[#021219]">الشؤون المالية</p>
                        <p class="text-[14px] text-[#6D6D6D] font-medium">مسؤول المالية</p>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 shrink-0 pt-4">
                <form class="w-full flex justify-end">
                    <button
                        class="close-btn rounded-[14px]  py-3 px-20 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors">إغلاق</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('JS/permissions.js') }}"></script>

@endsection
