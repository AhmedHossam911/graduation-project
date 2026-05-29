@extends('layouts.app')
{{-- 
    Reports View:
    The central hub for generating and viewing all financial, administrative, and statistical reports.
--}}

@section('title', 'إدارة كليات وقطاعات الجامعة')

@include('partials.common.flash')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/reports.css') }}">
    <div class="py-7 px-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-[20px] text-[#124375]  font-semibold">
                مركز التقارير والإحصائيات
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                المركز الشامل لاستخراج تمامی تقارير صندوق زمالة جامعة حلوان المعتمدة في اللائحة
            </p>
        </div>
    </div>

    <!-- start tabs -->
    <div class="px-12">
        <div class="tabs grid grid-cols-6  bg-[#F4F7F9] navy-shadow rounded-[16px] px-4 py-5 ">
            <button class="active-tab text-[16px]">
                كافة التقارير
            </button>
            <button class="tab  text-[16px] ">
                المالية والخزينة
            </button>
            <button class="tab text-[16px] ">
                الاشتراكات والعضوية
            </button>
            <button class="tab  text-[16px] ">
                القروض والسلف
            </button>
            <button class="tab text-[16px] ">
                المزايا والمطالبات
            </button>
            <button class="tab  text-[16px] ">
                إحصائيات إدارية
            </button>
        </div>
    </div>
    <!-- end tabs -->

    <!-- start cards -->
    <section class="px-12 py-10">
        <div data-tab="كافة التقارير" class="tab-content grid grid-cols-3 gap-20 px-4">
            <div
                class="space-y-10 bg-[#F4F7F9] green-shadow border-s-8 border-transparent hover:border-[#019168] transition  rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#019168] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="famicons:wallet" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">بيان الإيرادات والمصروفات</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف تفصيلي بحركة الإيرادات (اشتراكات، استثمارات،
                        عوائد) والمصروفات الإدارية والتشغيلية.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.revenue_expenses') }}"
                        class="green-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#019168] bg-[#F0FFF6] hover:bg-[#019168] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] green-shadow border-s-8 border-transparent hover:border-[#019168] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#019168] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="ri:pie-chart-fill" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">الموقف المالي الختامي للصندوق</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف تفصيلي بحركة الإيرادات (اشالميزانية العمومية
                        والمركز المالي (أصول، خصوم، احتياطيات) للسنة المالية الحالية والمنتهية.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.financial_position') }}"
                        class="green-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#019168] bg-[#F0FFF6] hover:bg-[#019168] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] navy-shadow border-s-8 border-transparent hover:border-[#124375] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#124375] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="material-symbols:list-alt-check-rounded"
                            class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">بيان الاستقطاعات والاشتراكات الشهرية</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف إجمالي وتفصيلي بالاشتراكات المستقطعة من رواتب
                        الأعضاء (يفرز حسب الكلية/الإدارة).</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.subscriptions') }}"
                        class="navy-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#124375] bg-[#EEF7FF] hover:bg-[#124375] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] navy-shadow border-s-8 border-transparent hover:border-[#124375] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#124375] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="mdi:clock-alert" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">المتأخرات والمديونيات المعلقة</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">حصر الأعضاء المتأخرين عن سداد الاشتراكات الدورية
                        وتحديد قيم المديونيات المستحقة عليهم.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.arrears') }}"
                        class="navy-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#124375] bg-[#EEF7FF] hover:bg-[#124375] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] yellow-shadow border-s-8 border-transparent hover:border-[#D4AF37] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#D4AF37] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="fluent:money-16-filled" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">موقف القروض والسلف المنصرفة</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">إجمالي القروض التي تم الموافقة عليها وصرفها للأعضاء
                        خلال فترة محددة.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.loans') }}"
                        class="yellow-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#D4AF37] bg-[#FFFDF2] hover:bg-[#D4AF37] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] yellow-shadow border-s-8 border-transparent hover:border-[#D4AF37] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#D4AF37] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="ion:cash" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">بيان الأقساط والتحصيل الشهري</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف بقيم أقساط القروض المستقطعة شهرياً من الأعضاء
                        ومقارنتها بالقروض الممنوحة.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.installments') }}"
                        class="yellow-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#D4AF37] bg-[#FFFDF2] hover:bg-[#D4AF37] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>
        </div>

        <div data-tab="المالية والخزينة" class="hidden tab-content grid grid-cols-3 gap-20 px-4">
            <div
                class="space-y-10 bg-[#F4F7F9] green-shadow border-s-8 border-transparent hover:border-[#019168] transition  rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#019168] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="famicons:wallet" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">بيان الإيرادات والمصروفات</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف تفصيلي بحركة الإيرادات (اشتراكات، استثمارات،
                        عوائد) والمصروفات الإدارية والتشغيلية.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.revenue_expenses') }}"
                        class="green-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#019168] bg-[#F0FFF6] hover:bg-[#019168] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] green-shadow border-s-8 border-transparent hover:border-[#019168] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#019168] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="ri:pie-chart-fill" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">الموقف المالي الختامي للصندوق</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف تفصيلي بحركة الإيرادات (اشالميزانية العمومية
                        والمركز المالي (أصول، خصوم، احتياطيات) للسنة المالية الحالية والمنتهية.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.financial_position') }}"
                        class="green-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#019168] bg-[#F0FFF6] hover:bg-[#019168] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>
        </div>

        <div data-tab="الاشتراكات والعضوية" class="hidden tab-content grid grid-cols-3 gap-20 px-4">
            <div
                class="space-y-10 bg-[#F4F7F9] navy-shadow border-s-8 border-transparent hover:border-[#124375] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#124375] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="material-symbols:list-alt-check-rounded"
                            class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">بيان الاستقطاعات والاشتراكات الشهرية</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف إجمالي وتفصيلي بالاشتراكات المستقطعة من رواتب
                        الأعضاء (يفرز حسب الكلية/الإدارة).</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.subscriptions') }}"
                        class="navy-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#124375] bg-[#EEF7FF] hover:bg-[#124375] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] navy-shadow border-s-8 border-transparent hover:border-[#124375] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#124375] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="mdi:clock-alert" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">المتأخرات والمديونيات المعلقة</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">حصر الأعضاء المتأخرين عن سداد الاشتراكات الدورية
                        وتحديد قيم المديونيات المستحقة عليهم.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.arrears') }}"
                        class="navy-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#124375] bg-[#EEF7FF] hover:bg-[#124375] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>
        </div>

        <div data-tab="القروض والسلف" class="hidden tab-content grid grid-cols-3 gap-20 px-4">
            <div
                class="space-y-10 bg-[#F4F7F9] yellow-shadow border-s-8 border-transparent hover:border-[#D4AF37] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#D4AF37] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="ion:cash" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">بيان الأقساط والتحصيل الشهري</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف بقيم أقساط القروض المستقطعة شهرياً من الأعضاء
                        ومقارنتها بالقروض الممنوحة.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.installments') }}"
                        class="yellow-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#D4AF37] bg-[#FFFDF2] hover:bg-[#D4AF37] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] yellow-shadow border-s-8 border-transparent hover:border-[#D4AF37] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#D4AF37] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="fluent:money-16-filled" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">موقف القروض والسلف المنصرفة</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">إجمالي القروض التي تم الموافقة عليها وصرفها للأعضاء
                        خلال فترة محددة.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.loans') }}"
                        class="yellow-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#D4AF37] bg-[#FFFDF2] hover:bg-[#D4AF37] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>
        </div>

        <div data-tab="المزايا والمطالبات" class="hidden tab-content grid grid-cols-3 gap-20 px-4">
            <div
                class="space-y-10 bg-[#F4F7F9] red-shadow border-s-8 border-transparent hover:border-[#E11D48] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#E11D48] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="iconamoon:invoice-fill" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">بيان المزايا التأمينية والمطالبات</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">كشف تفصيلي بالمطالبات المعتمدة (نهاية خدمة، معاش،
                        وفاة) وقيمتها المنصرفة للمستفيدين.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.claims') }}"
                        class="red-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#E11D48] bg-[#FFF0F2] hover:bg-[#E11D48] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] red-shadow border-s-8 border-transparent hover:border-[#E11D48] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#E11D48] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="uil:calender" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">المطالبات المعلقة وتحت التسوية</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">طلبات الأعضاء قيد المراجعة التي لم يتم صرفها لعدم
                        استيفاء المستندات أو بانتظار الاعتماد.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.pending_claims') }}"
                        class="red-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#E11D48] bg-[#FFF0F2] hover:bg-[#E11D48] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>
        </div>

        <div data-tab="إحصائيات إدارية" class="hidden tab-content grid grid-cols-3 gap-20 px-4">
            <div
                class="space-y-10 bg-[#F4F7F9] purple-shadow border-s-8 border-transparent hover:border-[#5925DC] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#5925DC] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="bi:buildings-fill" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">توزيع الأعضاء حسب الكليات</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">تقرير إحصائي يوضح أعداد المشتركين ونسبهم المئوية
                        موزعة على كليات وإدارات الجامعة.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.members_distribution') }}"
                        class="purple-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#5925DC] bg-[#F4F0FF] hover:bg-[#5925DC] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <div
                class="space-y-10 bg-[#F4F7F9] purple-shadow border-s-8 border-transparent hover:border-[#5925DC] transition rounded-[16px] py-7 px-9">
                <div class="flex justify-between ">
                    <div class="bg-[#5925DC] rounded-[12px] py-3 px-3 flex items-center">
                        <iconify-icon icon="mdi:file-clock" class="text-3xl text-[#F0FFF6]"></iconify-icon>
                    </div>
                    <div>
                        <div
                            class="flex items-center gap-2 py-1 bg-[#EEF7FF] rounded-[16px] px-2 border-[0.5px] border-[#185A9D] text-[#185A9D]">
                            <iconify-icon icon="material-symbols:info-rounded" class="text-xl"></iconify-icon>
                            <p>تحديث تلقائي</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-[#124375] text-[20px] font-medium">سجل نشاط النظام (Audit Log)</h3>
                    <p class="text-[16px] text-[#6D6D6D] font-normal">تقرير رقابي يرصد العمليات الحساسة (تعديل لوائح، منح
                        صلاحيات، اعتمادات مالية) التي تمت بالنظام.</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.audit_logs') }}"
                        class="purple-shadow py-3 w-full font-medium rounded-[10px] flex items-center gap-4 justify-center text-[#5925DC] bg-[#F4F0FF] hover:bg-[#5925DC] hover:text-white transition">
                        <iconify-icon icon="mdi:eye" class="text-3xl"></iconify-icon>
                        عرض التقرير
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('JS/admin/reports.js') }}"></script>
@endsection

