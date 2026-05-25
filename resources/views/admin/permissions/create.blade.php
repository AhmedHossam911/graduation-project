@extends('layouts.app')

@section('title', 'تعديل صلاحية المستخدم')

@include('partials.flash')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/grantaccess.css') }}">

    <div class="flex justify-between items-center py-7 px-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-xl text-[#124375] font-semibold">
                إضافة و تفويض مستخدم جديد
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                أدخل بيانات الموظف وحدد الصلاحيات الللازمة للوصول للنظام
            </p>
        </div>
        <a href="{{ route('admin.permissions.index') }}"
            class="cursor-pointer text-[16px] red-shadow flex justify-center bg-[#F4F7F9] text-[#D92D20] py-2.5 px-10 rounded-[12px] hover:bg-gray-100 transition-colors">
            إلغاء الأمر
        </a>
    </div>

    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf
        <div class="px-12 py-4 ">
            <div class="space-y-10 py-10 px-7 bg-[#F4F7F9] navy-shadow rounded-[16px]">
                <div class="flex items-center gap-4">
                    <iconify-icon icon="tdesign:user-filled" class="text-3xl text-[#124375] mt-1"></iconify-icon>
                    <h2 class="text-[20px] text-[#021219] font-semibold">البيانات الأساسية</h2>
                </div>
                <div class="grid grid-cols-2 gap-7">
                    <div class="relative">
                        <label class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">الاسم رباعي</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="الاسم المدرج بقرار التعيين"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full text-center rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] py-2" required>
                    </div>
                    <div class="relative">
                        <label class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">البريد الإلكتروني الرسمي</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="example@helwan.edu.eg"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full text-center rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] py-2" required>
                    </div>
                    <div class="relative">
                        <label class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">الرقم القومي</label>
                        <input type="text" name="national_id" value="{{ old('national_id') }}" placeholder="12345678912345"
                            class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition w-full text-center rounded-[12px] outline-none border border-[#124375] bg-[#F4F7F9] py-2" minlength="14" maxlength="14">
                    </div>
                    <div class="relative">
                        <button type="button"
                            class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base gap-3 flex justify-between items-center"><span
                            class="text-[#021219] text-center flex-1">{{ old('role_name', 'اختر') }}</span><span
                                class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                    class="text-xl "></iconify-icon></span></button>
                        <label class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">الصفة الإدارية / المسمي الوظيفي</label>
                        <input type="hidden" name="role_name" id="role_name" value="{{ old('role_name') }}">
                        <div class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-2 px-5 py-4 rounded-xl navy-shadow w-full">
                            <button type="button" class=" navy-shadow py-2 px-4 rounded-xl text-sm font-medium">رئيس مجلس الإدارة</button>
                            <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium">المدير المالي</button>
                            <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium">المدير التنفيذي</button>
                            <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium">موظف</button>
                            <button type="button" class=" navy-shadow py-2  rounded-xl text-sm font-medium">عضو</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php

            $userFaculties = old('faculties', []);
            if (!is_array($userFaculties)) $userFaculties = [];
        @endphp

        <div class="px-12 py-4">
            <div class="bg-[#F4F7F9] navy-shadow rounded-[16px] pt-10 pb-3 px-7 ">
                <div class="flex justify-between items-center ">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="bi:buildings-fill" class="text-3xl mt-1 text-[#124375]"></iconify-icon>
                            <h2 class="text-[#021219] text-[20px] font-semibold">النطاق المكاني ( تحديد الكليات )</h2>
                        </div>
                        <p class="text-[#6D6D6D] text-[12px]">اختر الكليات التي سيكون الموظف مسؤولاً عن بيانات أعضائها.</p>
                    </div>
                    <div>
                        <button type="button"
                            class="select-btn text-[#124375] text-[16px] font-medium bg-[#F4F7F9] navy-shadow py-3 px-10 rounded-[10px]">تحديد كافة الكليات</button>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-7 py-5 ">
                    @foreach($departments as $department)
                    <label class="flex items-center gap-2 cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <input type="checkbox" name="faculties[]" class="hidden peer item" value="{{ $department->name }}" {{ in_array($department->name, $userFaculties) ? 'checked' : '' }}>
                        <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                            <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                        </span>
                        <span>{{ $department->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        @php
            $userPermissions = old('permissions', []);
            if (!is_array($userPermissions)) $userPermissions = [];
        @endphp

        <div class="px-12 py-4">
            <div class="bg-[#F4F7F9] navy-shadow rounded-[16px] pt-10 pb-3 px-7">
                <div class="flex justify-between items-center">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="mage:key-fill" class="text-3xl mt-1 text-[#124375]"></iconify-icon>
                            <h2 class="text-[#021219] text-[20px] font-semibold">صلاحيات الوصول و تخصيص المهام</h2>
                        </div>
                        <p class="text-[#6D6D6D] text-[12px]">اختر الصلاحيات التي سيتمكن المستخدم من الوصول إليها. يمكنك تحديد ما تراه مناسباً له بكل دقة</p>
                    </div>
                    <div>
                        <button type="button"
                            class="select-btn text-[#124375] text-[16px] font-medium bg-[#F4F7F9] navy-shadow py-3 px-10 rounded-[10px]">تحديد الكل (مدير النظام)</button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5 py-5 ">
                    <label class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" class="hidden peer item" value="إدارة الصلاحيات" {{ in_array('إدارة الصلاحيات', $userPermissions) ? 'checked' : '' }}>
                                <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span class="text-[#021219] text-[18px] font-medium">إدارة الصلاحيات</span>
                            </div>
                            <p class="text-[14px] text-[#6D6D6D] font-medium">إضافة مستخدمين جدد وتعديل صلاحياتهم</p>
                        </div>
                        <div>
                            <p class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] px-3 rounded-[8px] [label:has(:checked)_&]:text-[#4F46E5] [label:has(:checked)_&]:border-[#4F46E5] [label:has(:checked)_&]:bg-[#E0E7FF] transition">مسؤول الصلاحيات</p>
                        </div>
                    </label>

                    <label class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" class="hidden peer item" value="إدارة الاشتراكات" {{ in_array('إدارة الاشتراكات', $userPermissions) ? 'checked' : '' }}>
                                <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span class="text-[#021219] text-[18px] font-medium">إدارة الاشتراكات</span>
                            </div>
                            <p class="text-[14px] text-[#6D6D6D] font-medium">تسجيل وتعديل ومتابعة اشتراكات الأعضاء</p>
                        </div>
                        <div>
                            <p class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] px-3 rounded-[8px] [label:has(:checked)_&]:text-[#0284C7] [label:has(:checked)_&]:border-[#0284C7] [label:has(:checked)_&]:bg-[#E0F2FE] transition">مسؤول الاشتراكات</p>
                        </div>
                    </label>

                    <label class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" class="hidden peer item" value="إعدادات اللائحة" {{ in_array('إعدادات اللائحة', $userPermissions) ? 'checked' : '' }}>
                                <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span class="text-[#021219] text-[18px] font-medium">إعدادات اللائحة</span>
                            </div>
                            <p class="text-[14px] text-[#6D6D6D] font-medium">تعديل ثوابت النظام ، نسب الاشتراك ، ومعدلات الصرف</p>
                        </div>
                        <div>
                            <p class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] px-3 rounded-[8px] [label:has(:checked)_&]:text-[#9333EA] [label:has(:checked)_&]:border-[#9333EA] [label:has(:checked)_&]:bg-[#F3E8FF] transition">إعدادات اللائحة</p>
                        </div>
                    </label>

                    <label class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" class="hidden peer item" value="إدارة القروض" {{ in_array('إدارة القروض', $userPermissions) ? 'checked' : '' }}>
                                <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span class="text-[#021219] text-[18px] font-medium">إدارة القروض</span>
                            </div>
                            <p class="text-[14px] text-[#6D6D6D] font-medium">تسجيل ومتابعة قروض السلف والتحصيل</p>
                        </div>
                        <div>
                            <p class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] px-3 rounded-[8px] [label:has(:checked)_&]:text-[#EA580C] [label:has(:checked)_&]:border-[#EA580C] [label:has(:checked)_&]:bg-[#FFEDD5] transition">مسؤول القروض</p>
                        </div>
                    </label>

                    <label class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" class="hidden peer item" value="عرض التقارير" {{ in_array('عرض التقارير', $userPermissions) ? 'checked' : '' }}>
                                <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span class="text-[#021219] text-[18px] font-medium">عرض التقارير</span>
                            </div>
                            <p class="text-[14px] text-[#6D6D6D] font-medium">استخراج وعرض التقارير المالية والإحصائية</p>
                        </div>
                        <div>
                            <p class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] px-3 rounded-[8px] [label:has(:checked)_&]:text-[#0D9488] [label:has(:checked)_&]:border-[#0D9488] [label:has(:checked)_&]:bg-[#CCFBF1] transition">عرض التقارير</p>
                        </div>
                    </label>

                    <label class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" class="hidden peer item" value="إدارة المطالبات" {{ in_array('إدارة المطالبات', $userPermissions) ? 'checked' : '' }}>
                                <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span class="text-[#021219] text-[18px] font-medium">إدارة المطالبات</span>
                            </div>
                            <p class="text-[14px] text-[#6D6D6D] font-medium">مراجعة وصرف مطالبات نهاية الخدمة والوفاة</p>
                        </div>
                        <div>
                            <p class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] px-3 rounded-[8px] [label:has(:checked)_&]:text-[#E11D48] [label:has(:checked)_&]:border-[#E11D48] [label:has(:checked)_&]:bg-[#FFE4E6] transition">مسؤول المطالبات</p>
                        </div>
                    </label>

                    <label class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" class="hidden peer item" value="الشؤون المالية" {{ in_array('الشؤون المالية', $userPermissions) ? 'checked' : '' }}>
                                <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span class="text-[#021219] text-[18px] font-medium">الشؤون المالية</span>
                            </div>
                            <p class="text-[14px] text-[#6D6D6D] font-medium">إدارة الخزينة،البنوك،المصروفات والإيرادات</p>
                        </div>
                        <div>
                            <p class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] px-3 rounded-[8px] [label:has(:checked)_&]:text-[#16A34A] [label:has(:checked)_&]:border-[#16A34A] [label:has(:checked)_&]:bg-[#DCFCE7] transition">مسؤول المالية</p>
                        </div>
                    </label>

                    <label class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" class="hidden peer item" value="سجل العمليات" {{ in_array('سجل العمليات', $userPermissions) ? 'checked' : '' }}>
                                <span class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span class="text-[#021219] text-[18px] font-medium">سجل العمليات</span>
                            </div>
                            <p class="text-[14px] text-[#6D6D6D] font-medium">تتبع حركات النظام وسجل نشاط المستخدمين</p>
                        </div>
                        <div>
                            <p class="text-[#6D6D6D] bg-[#F2F4F7] border border-[#6D6D6D] px-3 rounded-[8px] [label:has(:checked)_&]:text-[#475569] [label:has(:checked)_&]:border-[#475569] [label:has(:checked)_&]:bg-[#F1F5F9] transition">سجل العمليات</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="px-12 py-4 mb-4">
            <button type="submit"
                class="bg-[#124375] text-[#F4F7F9] w-full  rounded-[12px] flex items-center py-3 justify-center gap-5 hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="mage:key-fill" class="text-xl mt-1"></iconify-icon>
                إصدار و حفظ التفويض
            </button>
        </div>
    </form>

    <script src="{{ asset('js/admin/grantaccess.js') }}"></script>

@endsection
