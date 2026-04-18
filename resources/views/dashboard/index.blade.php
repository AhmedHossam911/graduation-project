@extends('layouts.dashboard')

@section('title', 'لوحة التحكم')

@section('content')
    <!-- start main -->
    <main class="flex-1 py-5 px-3">
        <!-- start header main -->
        <div class="flex flex-col gap-2">
            <h2 class="text-[#021219] text-xl font-semibold"> مرحباً ، <span>{{ Auth::user()->name }}</span></h2>
            <p class="text-[#6D6D6D] text-base font-normal">
                نظام إدارة الصندوق – لوحة الموظف
            </p>
        </div>
        <!-- end header main -->

        <!-- start search -->
        <div class="flex items-center gap-5 mt-6 ">
            <input type="search" placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض"
                class="w-full  rounded-xl py-2 pr-2 surface-shadow outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
            <button class="bg-[#124375] text-white rounded-xl px-7 surface-shadow">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl"></iconify-icon>
            </button>
        </div>
        <!-- end search -->

        <!-- start cards -->
        <div class="py-4 grid grid-cols-4 gap-4">
            <div
                class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
                <div>
                    <iconify-icon icon="mdi:account-group"
                        class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">9</p>
                    <p class="text-sm font-medium">عدد الأعضاء النشطين</p>
                </div>
            </div>
            <div
                class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
                <div>
                    <iconify-icon icon="mdi:account-file"
                        class="surface-shadow text-4xl text-[#D4AF37] bg-[#FFFCEF] rounded-lg px-2 py-1"></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">0</p>
                    <p class="text-sm font-medium">اشتراكات اليوم</p>
                </div>
            </div>
            <div
                class="surface-shadow flex  items-center justify-center gap-4 bg-[#124375] rounded-xl px-4 py-4 border-s-8 border-[#EEF7FF]">
                <div>
                    <iconify-icon icon="material-symbols:assignment-late"
                        class="surface-shadow text-4xl text-[#124375] bg-[#EEF7FF] rounded-lg px-2 py-1"></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#F4F7F9] gap-2">
                    <p class="text-4xl font-extrabold">3</p>
                    <p class="text-sm font-medium">أقساط مستحقة اليوم</p>
                </div>
            </div>
            <div
                class="surface-shadow flex items-center justify-center gap-4 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
                <div>
                    <iconify-icon icon="mdi:account-convert"
                        class="surface-shadow text-4xl text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
                </div>
                <div class="flex flex-col items-center text-[#124375] gap-2">
                    <p class="text-4xl font-extrabold">5</p>
                    <p class="text-sm font-medium">طلبات تحت المراجعة</p>
                </div>
            </div>
        </div>
        <!-- end cards -->

        <!-- start tasks -->
        <div class="py-5 grid grid-cols-3 gap-7">
            <div class="col-span-2 space-y-5">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="material-symbols:edit-notifications-rounded" class="text-2xl"></iconify-icon>
                    <h2 class="text-base font-medium">المهام المطلوبة اليوم <span class="text-[#124375]">(3)</span>
                    </h2>
                </div>
                <div class="py-2 surface-shadow rounded-2xl py-4 px-5 divide-y-2 divide-[#6D6D6D]">
                    <div class="flex justify-between py-5">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#175CD3]"></iconify-icon>
                            <div>
                                <h3 class="text-[#021219] text-sm font-medium">اشتراك مستحق اليوم</h3>
                                <p class="text-[#6D6D6D] text-sm font-normal">غير مسجل بعد</p>
                            </div>
                        </div>
                        <button
                            class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3">عرض
                            التفاصيل</button>
                    </div>
                    <div class="flex justify-between py-5">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="dashicons:arrow-left" class="text-4xl text-[#D92D20]"></iconify-icon>
                            <div>
                                <h3 class="text-[#021219] text-sm font-medium">قسط متأخر</h3>
                                <p class="text-[#6D6D6D] text-sm font-normal">متأخر منذ 4 ايام</p>
                            </div>
                        </div>
                        <button
                            class="surface-shadow text-[#F4F7F9] text-sm bg-[#124375] rounded-[10px] font-medium px-4 py-3">عرض
                            التفاصيل</button>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="grid grid-cols-2 gap-4">
                    <div
                        class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                        <iconify-icon icon="mdi:account-multiple-plus" class="text-5xl text-[#124375]"></iconify-icon>
                        <h3 class="text-base font-medium text-[#124375]">تسجيل عضو جديد</h3>
                    </div>
                    <div
                        class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                        <iconify-icon icon="material-symbols:list-alt-check-rounded"
                            class="text-5xl text-[#124375]"></iconify-icon>
                        <h3 class="text-base font-medium text-[#124375]">تسجيل سداد إشتراك</h3>
                    </div>
                    <div
                        class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                        <iconify-icon icon="ion:cash" class="text-5xl text-[#124375]"></iconify-icon>
                        <h3 class="text-base font-medium text-[#124375]">تسجيل سداد قسط</h3>
                    </div>
                    <div
                        class="surface-shadow flex flex-col items-center bg-[#F4F7F9] rounded-xl px-4 py-7 border-s-8 border-[#124375]">
                        <iconify-icon icon="mdi:account-file" class="text-5xl text-[#124375]"></iconify-icon>
                        <h3 class="text-base font-medium text-[#124375]">إنشاء مطالبة</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- end tasks -->

        <!-- start table -->
        <section>
            <div class="flex items-center gap-2 py-3">
                <iconify-icon icon="mingcute:time-fill" class="text-2xl"></iconify-icon>
                <h3 class="text-base font-medium ">
                    العمليات التي تمت اليوم
                </h3>
            </div>
            <div class="rounded-2xl overflow-hidden  surface-shadow">
                <table class="w-full">
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D]">العملية</th>
                        <th class="py-3 border-l border-[#6D6D6D]">اسم العضو</th>
                        <th class="py-3 border-l border-[#6D6D6D]">رقم العضوية</th>
                        <th class="py-3 border-l border-[#6D6D6D]">تفاصيل العملية</th>
                        <th class="py-3 border-l border-[#6D6D6D]">الحالة</th>
                        <th class="py-3">التوقيت</th>
                    </tr>
                    @foreach ($operations as $operation)
                    <tr class="text-center">
                        <td class="py-3 border-l border-[#6D6D6D]">{{ $operation->operation_type }}</td>
                        <td class="py-3 border-l border-[#6D6D6D]">{{ $operation->member->name }}</td>
                        <td class="py-3 border-l border-[#6D6D6D]">{{ $operation->member->national_id }}</td>
                        <td class="py-3 border-l border-[#6D6D6D]">{{ $operation->operation_details }}</td>
                        <td class="py-3 border-l border-[#6D6D6D]">{{ $operation->status }}</td>
                        <td class="py-3">{{ $operation->created_at }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </section>
    </main>
    </div>
    <!-- end table -->
@endsection
