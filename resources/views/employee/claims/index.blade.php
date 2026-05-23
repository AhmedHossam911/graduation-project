@extends('layouts.app')

@section('title', 'قائمة المطالبات')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/claims-approve.css') }}">
    <!-- start header -->
    <div class="flex justify-between px-4 py-5">
        <div>
            <h1 class="text-[32px] font-medium text-[#124375]">
                المطالبات
            </h1>
        </div>
        <div class="btns flex items-center gap-3  ">
            <button
                class="open-modal rounded-xl flex items-center justify-center py-3 px-20 gap-2 text-[#F4F7F9] bg-[#124375] navy-shadow hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ic:round-plus" class="flex items-center text-2xl"></iconify-icon> إنشاء مطالبة
            </button>
            <a href=""
                class="rounded-xl flex items-center justify-center py-3 gap-2 px-5 text-[#124375] bg-[#F4F7F9] navy-shadow">
                <iconify-icon icon="ri:file-excel-fill" class="flex items-center text-2xl"></iconify-icon> تنزيل
            </a>
        </div>
    </div>
    <!-- end header -->

    <!-- start cards -->
    <div class="py-4 grid grid-cols-3 gap-4 px-4">
        <div
            class="navy-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#124375]">
            <div>
                <iconify-icon icon="ph:trend-up-fill"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#124375] bg-[#EEF7FF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $paidCount }}</p>
                <p class="text-sm font-medium">مطالبات تم صرفها</p>
            </div>
        </div>
        <div
            class="yellow-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-7 py-4 border-s-8 border-[#D4AF37]">
            <div>
                <iconify-icon icon="tabler:clipboard-list-filled"
                    class="navy-shadow text-[40px] px-2 py-1 text-[#D4AF37] bg-[#FFFCEF] rounded-lg "></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $pendingApprovalCount }}</p>
                <p class="text-sm font-medium">مطالبات بانتظار الأعتماد</p>
            </div>
        </div>
        <div
            class="red-shadow flex items-center justify-center gap-7 bg-[#F4F7F9] rounded-xl px-4 py-4 border-s-8 border-[#D92D20]">
            <div>
                <iconify-icon icon="ph:trend-down-fill"
                    class="navy-shadow text-[40px] text-[#D92D20] bg-[#FFEAE880] rounded-lg px-2 py-1"></iconify-icon>
            </div>
            <div class="flex flex-col items-center text-[#124375] gap-2">
                <p class="text-4xl font-extrabold">{{ $pendingSettlementCount }}</p>
                <p class="text-sm font-medium">مطالبات بانتظار التسوية</p>
            </div>
        </div>
    </div>
    <!-- end cards -->

    <!-- filteration buttons -->
    <form action="{{ route('claims.index') }}" method="GET" class="px-4 flex items-center justify-between gap-5">
        <div class="relative flex-1">
            <input type="search" name="search" value="{{ request('search') }}"
                placeholder="الاسم أو رقم العضوية أو رقم المطالبة"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
        </div>
        <div class="relative min-w-[240px]">
            @include('partials.calendar', [
                'name' => 'date',
                'id' => 'subscriptions-datepicker',
                'value' => request('date'),
                'autoSubmit' => false,
            ])
        </div>

        @php
            $statusMapping = [
                'all' => 'الكل',
                'pending' => 'بانتظار التسوية',
                'under_review' => 'تحت المراجعة',
                'approved' => 'تم الصرف',
            ];
            $currentStatus = request('status', 'all');
        @endphp
        <div class="relative min-w-[150px]">
            <input type="hidden" name="status" id="statusInput" value="{{ $currentStatus }}">
            <button type="button"
                class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">الحالة
                : <span class="text-[#021219] ">{{ $statusMapping[$currentStatus] ?? 'الكل' }}</span><span
                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                        class="text-xl"></iconify-icon></span></button>
            <div
                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow w-full">
                <button type="button" onclick="document.getElementById('statusInput').value='all';"
                    class=" navy-shadow py-2 px-5 rounded-xl text-sm font-medium">الكل</button>
                <button type="button" onclick="document.getElementById('statusInput').value='pending';"
                    class=" navy-shadow py-2  rounded-xl text-sm font-medium">بانتظار التسوية</button>
                <button type="button" onclick="document.getElementById('statusInput').value='under_review';"
                    class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium">تحت المراجعة</button>
                <button type="button" onclick="document.getElementById('statusInput').value='approved';"
                    class=" navy-shadow py-2 px-5 rounded-xl text-sm font-medium">تم الصرف</button>
            </div>
        </div>

        @php
            $currentType = request('type', 'all');
            $typeLabel = $currentType === 'all' ? 'الكل' : $claimTypes[$currentType] ?? 'الكل';
        @endphp
        <div class="relative min-w-[150px]">
            <input type="hidden" name="type" id="typeInput" value="{{ $currentType }}">
            <button type="button"
                class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 justify-center items-center">نوع
                المطالبة :<span class="text-[#021219] ">{{ $typeLabel }}</span><span
                    class="flex items-center"><iconify-icon icon="fe:arrow-down"
                        class="text-xl"></iconify-icon></span></button>
            <div
                class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-3 py-4 rounded-xl navy-shadow w-full">
                <button type="button" onclick="document.getElementById('typeInput').value='all';"
                    class=" navy-shadow py-2 px-5 rounded-xl text-sm font-medium">الكل</button>
                @foreach ($claimTypes as $key => $val)
                    <button type="button" onclick="document.getElementById('typeInput').value='{{ $key }}';"
                        class=" navy-shadow py-2 px-1 rounded-xl text-sm font-medium ">{{ $val }}</button>
                @endforeach
            </div>
        </div>
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
            </button>
        </div>
    </form>
    <!-- end filteration buttons -->

    <!-- start table -->
    <section class="px-4 py-4">
        <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم المطالبة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">اسم العضو</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">تاريخ التقديم</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">نوع المطالبة</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">الحالة</th>
                        <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($claims as $claim)
                        <tr class="text-center {{ $loop->odd ? 'bg-[#EFEFEF]' : '' }} border-b border-[#6D6D6D]">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">TRX-{{ $claim->id }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                {{ $claim->membership->member->name ?? 'غير متوفر' }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                {{ \Carbon\Carbon::parse($claim->created_at)->translatedFormat('d F Y') }}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">
                                {{ $claimTypes[$claim->type] ?? $claim->type }}</td>

                            @if ($claim->status === 'pending')
                                <td class="py-3 border-l border-[#6D6D6D] "><span
                                        class="text-[#D92D20] bg-[#FFEAE8] border border-[#D92D20] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">بانتظار
                                        التسوية</span></td>
                            @elseif ($claim->status === 'pending_approval')
                                <td class="py-3 border-l border-[#6D6D6D] "><span
                                        class="text-[#E6B800] bg-[#FFF8E1] border border-[#E6B800] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[145px]">بانتظار
                                        الأعتماد</span></td>
                            @elseif ($claim->status === 'under_review')
                                <td class="py-3 border-l border-[#6D6D6D] "><span
                                        class="text-[#124375] bg-[#EEF7FF] border border-[#124375] rounded-[8px] py-[2px] px-3 inline-block text-center min-w-[145px]">تحت
                                        المراجعة</span></td>
                            @elseif ($claim->status === 'approved')
                                <td class="py-3 border-l border-[#6D6D6D] "><span
                                        class="text-[#067647] bg-[#ECFDF3] border border-[#067647] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[145px]">تم
                                        الصرف</span></td>
                            @else
                                <td class="py-3 border-l border-[#6D6D6D] "><span
                                        class="text-[#6D6D6D] bg-[#EFEFEF] border border-[#6D6D6D] px-3 rounded-[8px] py-[2px] inline-block text-center min-w-[145px]">{{ $claim->status }}</span>
                                </td>
                            @endif

                            <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                                @if ($claim->status === 'pending')
                                    <a href="{{ route('claims.show', $claim->id) }}"
                                        class="flex items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2 px-10 rounded-[12px] navy-shadow hover:bg-[#EEF7FF] transition-colors">
                                        <iconify-icon icon="majesticons:calculator" class="text-2xl"></iconify-icon>
                                        تسوية
                                    </a>
                                @elseif ($claim->status === 'pending_approval')
                                    <a href="{{ route('claims.show', $claim->id) }}"
                                        class="flex items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2 px-10 rounded-[12px] navy-shadow hover:bg-[#EEF7FF] transition-colors">
                                        <iconify-icon icon="healthicons:yes" class="text-2xl"></iconify-icon>
                                        أعتماد
                                    </a>
                                @else
                                    <a href="{{ route('claims.show', $claim->id) }}"
                                        class="flex items-center gap-2 text-[14px] font-medium bg-[#F4F7F9] py-2 px-10 rounded-[12px] navy-shadow hover:bg-[#EEF7FF] transition-colors">
                                        <iconify-icon icon="solar:eye-outline" class="text-2xl"></iconify-icon>
                                        عرض
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center border-b border-[#6D6D6D]">
                            <td colspan="6" class="py-5 text-[#124375] font-medium">لا توجد مطالبات متطابقة مع البحث
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <!-- end table -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden  z-[60]"></div>

    <!-- MODALS -->
    <div
        class="modal hidden w-full max-w-4xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-4">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    إنشاء المطالبة
                </h1>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between gap-4 ">
                    <p class="text-[#124375] text-base font-medium">البحث عن العضو :</p>
                    <div class="relative flex-1 ">
                        <input type="search" placeholder="الاسم  أو  رقم العضوية  أو  الرقم القومي"
                            class="w-full py-2 pr-7 outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
                        <iconify-icon icon="mynaui:search"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
                    </div>
                    <button
                        class="bg-[#124375] text-white rounded-xl px-4 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                        <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="requirements space-y-5">
                <div class="relative">
                    <button type="button"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3  items-center">نوع
                        المطالبة :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <div
                        class="dropDown hidden absolute z-50 bg-[#F4F7F9] right-3 top-full mt-3 grid grid-cols-4 gap-3 px-3 py-4 rounded-xl navy-shadow ">
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium ">عجز مهني</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium "> وفاة</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium ">نقل</button>
                        <button type="button" class=" navy-shadow py-2 px-2 rounded-xl text-sm font-medium  ">بلوغ سن
                            التقاعد القانوني</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium ">فصل</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium ">انسحاب</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium ">معاش
                            مبكر</button>
                        <button type="button" class=" navy-shadow py-2 rounded-xl text-sm font-medium "> استقالة</button>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        الأختيار</button>
                </form>
                <button
                    class="close-loan-request-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>

    <div
        class="modal hidden w-full max-w-4xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-5">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-7 py-2">
            <div
                class="text-[#F4F7F9] flex items-center justify-between bg-[#124375] navy-shadow py-3 px-4 rounded-[16px]">
                <h1 class=" text-[20px] font-semibold">
                    إيصال صرف مستحقات تأمينية
                </h1>
                <div class="flex flex-col items-center gap-2">
                    <p class="text-[16px] ">رقم المعاملة :</p>
                    <p class=" text-[20px] font-semibold">TRX-1052</p>
                </div>
            </div>
            <div class="bg-[#F4F7F9] navy-shadow py-3 px-4 rounded-[16px]">
                <div class="flex justify-between items-center">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-[14px] text-[#6D6D6D] font-medium">اسم العضو</h2>
                        <p class="text-[#124375] text-[25px] font-semibold">أحمد محمد عبد العزيز</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <h2 class="text-[14px] text-[#6D6D6D] font-medium">رقم العضوية </h2>
                        <p class="text-[#021219] text-[20px] font-semibold">123456789</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <h2 class="text-[14px] text-[#6D6D6D] font-medium">تاريخ نهاية الخدمة</h2>
                        <p class="text-[#021219] text-[20px] font-semibold">16 أبريل 2026</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <h2 class="text-[14px] text-[#6D6D6D] font-medium">حالة الصرف</h2>
                        <p
                            class="text-[14px] text-[#067647] border border-[#067647] py-1 px-4 bg-[#ECFDF3] rounded-[8px] font-medium">
                            تم الصرف</p>
                    </div>
                </div>
                <hr class="border border-[#A8A8A8] mx-1 my-4">
                <div class="flex justify-between items-center">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-[14px] text-[#6D6D6D] font-medium">سبب الاستحقاق</h2>
                        <p class="text-[#021219] text-[20px] font-semibold">بلوغ سن التقاعد القانوني</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <h2 class="text-[14px] text-[#6D6D6D] font-medium">رقم الشيك </h2>
                        <p class="text-[#021219] text-[20px] font-semibold">ABC1010101010101</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <h2 class="text-[14px] text-[#6D6D6D] font-medium">تاريخ ووقت التنفيذ</h2>
                        <p class="text-[#021219] text-[20px] font-semibold">20 أبريل 2026 - 11:45 ص</p>
                    </div>
                </div>
            </div>
            <div class="bg-[#F4F7F9] navy-shadow py-3 px-4 rounded-[16px]">
                <div class="flex justify-between">
                    <p class="text-[#6D6D6D] text-[16px] font-normal">قيمة الميزة التأمينية ( الأساسية )</p>
                    <p class="text-[#124375] text-[16px] font-semibold">45,500 ج.م</p>
                </div>
                <hr class="border border-[#A8A8A8] mx-1 my-4">
                <div class="flex justify-between">
                    <p class="text-[#6D6D6D] text-[16px] font-normal">رصيد القروض المتبقي</p>
                    <p class="text-[#D92D20] text-[16px] font-semibold"> -5,000 ج.م</p>
                </div>
                <hr class="border border-[#A8A8A8] mx-1 my-4">
                <div
                    class="flex items-center justify-between border border-[#1243751A] bg-[#1243751A] rounded-[8px] py-4 px-4 ">
                    <p class="text-[16px] text-[#124375]">صافي المبلغ المستحق صرفه</p>
                    <p class="text-[32px] text-[#001E3D] font-medium">40,500 ج.م</p>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <form class="w-full">
                    <button
                        class="submit-btn  rounded-[14px] w-full py-3 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="fluent:save-16-filled"
                                class="flex items-center text-2xl"></iconify-icon></span>طباعة الإيصال</button>
                </form>
                <button
                    class=" border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] flex items-center justify-center gap-2"><iconify-icon
                        icon="material-symbols:download-rounded" class="flex items-center text-2xl"></iconify-icon>تحميل
                    PDF</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('JS/claims.js') }}"></script>
@endsection
@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7FE] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $claims->links() }}
    </div>
@endsection
