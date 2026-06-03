{{--
 Member Modals Partial:
 Contains all modal components used within the member profile view, including:
 suspension of membership, editing data, loan requests, early repayment, and subscription payments.
--}}
<!-- suspension of membership MODAL -->
<form action="{{ route('members.suspend', $member->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div id="modal1"
        class="modal hidden max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl navy-shadow rounded m-4 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body px-6 space-y-7">
            <div class="moda-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تأكيد إيقاف العضوية
                </h1>
            </div>
            <div class="reason space-y-3">
                <p class="mr-1 text-[#021219] font-medium ">لإيقاف العضوية، يرجى توضيح سبب الرفض وإرفاق صورة من طلب
                    إيقاف العضوية الموقَّع من العضو أو قرار صندوق الزمالة برفض العضوية.</p>
                <textarea name="reason" required rows="4"
                    class="resize-none bg-[#F4F7F9] w-full border border-[#124375] rounded-xl outline-none px-2"
                    placeholder="سبب إيقاف العضوية"></textarea>
            </div>
            <div class="document border border-[#124375] rounded-xl text-center">
                <label for="suspension-file"
                    class="w-full cursor-pointer py-6 text-[#124375] flex items-center justify-center gap-1">
                    <p>اضغط لإرفاق صورة الملف</p>
                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                    <input type="file" required name="suspension_file" id="suspension-file" class="hidden"
                        accept=".pdf, image/*">
                </label>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">إيقاف
                        العضوية</button>
                </div>
                <button
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
            </div>
        </div>
    </div>
</form>
<!-- end suspension of membership MODAL -->

{{-- edit modal --}}
<div id="modal-edit"
    class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
    <button
        class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
        <iconify-icon icon="weui:close-filled"></iconify-icon>
    </button>
    <div class="modal-body space-y-7 px-12">
        <div class="modal-title text-center">
            <h1 class="text-xl font-semibold text-[#124375]">
                تعديل بيانات
            </h1>
        </div>
        <div class="space-y-4">
            <div class="flex gap-3">
                <p class="text-base font-medium text-[#124375]">الأسم : <span
                        class="text-[#021219] font-semibold text-base">{{ $member->user->name }}</span></p>
                <p class="text-base font-medium text-[#124375]">رقم العضوية : <span
                        class="text-[#021219] font-semibold text-base">
                        {{ $membership->membership_number ?? '-' }}
                    </span></p>
            </div>
            <div class="flex flex-col gap-5">
                <div class="flex flex-col gap-5">
                    <p class="text-[14px] text-[#021219]">
                        يمكنك تحديث البيانات الموضحة أدناه فقط. يرجى التأكد من دقة المعلومات قبل الحفظ.
                    </p>
                    <form action="{{ route('members.quickUpdate', $member->id) }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="flex flex-col gap-4">
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">رقم
                                    التليفون</label>
                                <input type="text" name="phone" value="{{ $member->phone ?? '' }}"
                                    placeholder="01234595684" required
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">البريد
                                    الإلكتروني</label>
                                <input type="email" name="email" value="{{ $member->user->email ?? '' }}"
                                    placeholder="ahmed@gmail.com" required
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الحالة
                                    الوظيفية</label>
                                <input type="text" name="job_title" placeholder="معيد بالجامعة"
                                    value="{{ $member->employmentInfo->job_title ?? '' }}" required
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">عنوان
                                    محل الإقامة</label>
                                <input type="text" name="address" value="{{ $member->address ?? '' }}"
                                    placeholder="العنوان الحالي بالتفصيل" required
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                            <div class="w-full relative">
                                <label
                                    class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9]">الراتب
                                    الأساسي</label>
                                <input type="text" name="starting_salary" placeholder="514"
                                    value="{{ $member->employmentInfo->starting_salary ?? '' }}" required
                                    class="focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition text-[#6D6D6D] font-medium text-base w-full text-center border border-[#124375] outline-none rounded-xl px-16 py-2 bg-[#F4F7F9]">
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="btns flex gap-2 ">
            <div class="w-full">
                <button type="submit"
                    class=" rounded-[14px] w-full py-3 bg-[#124375] text-[#F4F7F9] text-base font-medium flex items-center justify-center gap-2"><iconify-icon
                        icon="fluent:save-16-filled" class="text-2xl mt-1"></iconify-icon>حفظ التعديلات</button>
            </div>
            <button
                class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] modal-close">إلغاء</button>
        </div>
    </div>
</div>
</form>


<!-- loan request form -->
<form action="{{ route('loans.store') }}" method="POST" enctype="multipart/form-data" id="loanStoreForm">
    @csrf
    <input type="hidden" name="member_id" value="{{ $member->id }}">
    <input type="hidden" name="total_amount" id="selected_total_amount" required>
    <input type="hidden" name="months" id="selected_months" required>
    <div id="loan-request-form" class="hidden mx-7 rounded-2xl bg-[#F4F7F9] border border-[#124375] py-3">
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    طلب تسجيل قرض
                </h1>
            </div>
            <div class="requirements grid grid-cols-2 gap-2">
                <div class="relative">
                    <button type="button" id="amount_dropdown_btn"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 items-center transition-colors">قيمة
                        القرض :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <p id="amount_error_msg" class="hidden text-[#D92D20] text-sm font-medium px-2 mt-1 text-right">
                        يجب
                        تحديد قيمة القرض</p>
                    <div
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-amount-option"
                            data-value="5000">5,000</a>
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-amount-option"
                            data-value="10000">10,000</a>
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-amount-option"
                            data-value="20000">20,000</a>
                    </div>
                </div>
                <div class="relative">
                    <button type="button" id="months_dropdown_btn"
                        class="dropDownBtn navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-2 rounded-xl text-base flex gap-3 items-center transition-colors">مدة
                        السداد :<span class="text-[#021219] ">اختر</span><span class="flex items-center"><iconify-icon
                                icon="fe:arrow-down" class="text-xl"></iconify-icon></span></button>
                    <p id="months_error_msg" class="hidden text-[#D92D20] text-sm font-medium px-2 mt-1 text-right">
                        يجب
                        تحديد مدة السداد</p>
                    <div
                        class="dropDown w-fit hidden absolute z-50 bg-[#F4F7F9] right-1/2 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow ">
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-months-option"
                            data-value="12">12 شهر</a>
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-months-option"
                            data-value="24">24 شهر</a>
                        <a class="cursor-pointer navy-shadow py-2 px-5 rounded-xl text-base loan-months-option"
                            data-value="32">32 شهر</a>
                    </div>
                </div>
            </div>

            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="button" id="proceed-to-declaration-btn"
                        class="rounded-[14px] w-full py-3 text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="solar:document-add-linear"
                                class="flex items-center text-2xl"></iconify-icon></span>متابعة وإرفاق الإقرار</button>
                </div>
                <button type="button"
                    class="close-loan-request-modal border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375]">إلغاء</button>
            </div>
        </div>
    </div>
    <!-- end loan request form -->

    <!-- Attach the signed declaration modal -->
    <div id="modal2"
        class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="close-btn text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-5">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    إرفاق الإقرار المُوَقَّع
                </h1>
            </div>
            <div class="documents space-y-5">
                <div id="loan-summary" class="bg-[#FFFCEF] p-4 rounded-xl border border-[#D4AF37] mb-4">
                    <h3 class="text-[#D4AF37] font-semibold mb-2 text-center">ملخص القرض</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm text-[#021219]">
                        <div><span class="font-bold">قيمة القرض:</span> <span id="summary_base_amount">0</span> ج.م
                        </div>
                        <div><span class="font-bold">فائدة القرض:</span> <span id="summary_interest_amount">0</span>
                            ج.م</div>
                        <div><span class="font-bold">الإجمالي بالفائدة:</span> <span
                                id="summary_total_amount">0</span> ج.م</div>
                        <div><span class="font-bold">القسط الشهري:</span> <span
                                id="summary_installment_amount">0</span> ج.م</div>
                        <div><span class="font-bold">المدة:</span> <span id="summary_months">0</span> شهر</div>
                    </div>
                </div>
                <div class="space-y-3 mb-4">
                    <p class="text-[#021219] text-[16px] font-medium text-center">الخطوة الأولى: طباعة الإقرار وتوقيعه
                    </p>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <a target="_blank" href="#" id="print-declaration-btn"
                            class=" cursor-pointer py-7 text-[#124375] flex items-center justify-center gap-1 hover:bg-[#F4F7F9] transition-colors rounded-[12px]">
                            <p>اضغط هنا لطباعة الإقرار</p>
                            <iconify-icon icon="material-symbols:print" class="text-2xl"></iconify-icon>
                        </a>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-[#021219] text-[16px] font-medium text-center">الخطوة الثانية: رفع ملف الإقرار
                        المُوَقَّع</p>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="declaration_file"
                            class=" cursor-pointer py-7 text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط هنا لإرفاق ملف الإقرار</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" name="declaration_file" id="declaration_file" class="hidden"
                                required accept=".pdf, image/*">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class="submit-btn mt-4 rounded-[14px] w-full py-3 text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        وإرسال</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- end Attach the signed declaration modal -->



<!-- start loan modal -->
<form action="{{ route('loans.start', $memberLoans->first()->id ?? 0) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div id="modal3"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    بدء القرض
                </h1>
            </div>
            <div class="space-y-4">
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات العضو
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">اسم العضو : <span
                                class="text-[#021219] font-semibold text-base">{{ $member->user->name }}</span></p>
                        <p class="text-base font-medium text-[#124375]">رقم القرض : <span
                                class="text-[#021219] font-semibold text-base">{{ $activeLoan->id ?? 'غير متوفر' }}</span>
                        </p>
                    </div>
                </div>
                <div class="">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات القرض
                    </h2>
                    <ul class="px-8">
                        <li class="text-base mt-2 font-medium text-[#124375]"> قيمة القرض + الفائدة :<span
                                class="text-[#021219] font-semibold text-base">{{ $activeLoan ? ($activeLoan->installments->count() > 0 ? number_format($activeLoan->installments->where('status', 'unpaid')->sum('amount'), 2) : number_format($activeLoan->total_amount, 2)) : '0.00' }}
                                ج.م</span></li>
                        <li class="text-base mt-2 font-medium text-[#124375]">عدد الأقساط :<span
                                class="text-[#021219] font-semibold text-base">{{ $activeLoan ? ($activeLoan->installments->count() > 0 ? $activeLoan->installments->count() : $activeLoan->months) : 0 }}
                                شهر</span></li>
                        <li class="text-base mt-2 font-medium text-[#124375]">تاريخ بداية القرض :<span
                                class="text-[#021219] font-semibold text-base">{{ now()->format('Y-m-d') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="flex flex-col gap-5">
                    <div>
                        <p class="text-[16px] font-medium text-[#021219]">
                            سيتم بدء القرض واحتساب الأقساط اعتبارًا من تاريخ اليوم.
                        </p>
                        <p class="text-[16px] font-medium text-[#021219]">
                            يرجى إدخال رقم الشيك وإرفاق المستندات المطلوبة قبل التأكيد.
                        </p>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-21"
                            class=" cursor-pointer py-7 text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة الشيك</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-21" name="check_image" class="hidden"
                                accept=".pdf, image/*">
                        </label>
                    </div>
                    <div class="border border-[#124375] rounded-[12px] ">
                        <label for="file-22"
                            class=" cursor-pointer py-7 text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة موافقة المجلس</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-22" name="board_approval_image" class="hidden"
                                accept=".pdf, image/*">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        بدء القرض</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('loans.cancel', $memberLoans->first()->id ?? 0) }}" method="POST">
    @csrf
    <div id="modal4"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تأكيد إلغاء الطلب
                </h1>
            </div>
            <div class="space-y-4">
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات القرض
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">قيمة القرض :<span
                                class="text-[#021219] font-semibold text-base">{{ number_format($activeLoan->base_amount ?? 0, 2) }}
                                جنيه</span></p>
                        <p class="text-base font-medium text-[#124375]">رقم القرض :<span
                                class="text-[#021219] font-semibold text-base">{{ $activeLoan->id ?? 'غير متوفر' }}</span>
                        </p>
                        <p class="text-base font-medium text-[#124375]">تاريخ تقديم الطلب :<span
                                class="text-[#021219] font-semibold text-base">{{ $activeLoan ? $activeLoan->created_at->format('Y-m-d') : 'غير متوفر' }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-3">
                        <p class="text-[16px] font-medium text-[#021219]">
                            يرجى تحديد سبب عدم استكمال طلب القرض:
                        </p>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-2 w-fit cursor-pointer">
                                <input type="radio" name="reason" value="member_request" class="peer hidden">
                                <span
                                    class="mt-2 w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                <span class=" text-[16px] font-normal text-[#021219]">إلغاء بناءً على رغبة العضو</span>
                            </label>
                            <label class="flex items-center gap-2 w-fit cursor-pointer">
                                <input type="radio" name="reason" value="board_rejection" class="peer hidden">
                                <span
                                    class="mt-2 w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                <span class=" text-[16px] font-normal text-[#021219]">رفض الطلب بواسطة المجلس</span>
                            </label>
                        </div>
                    </div>
                    <div class="reason">
                        <textarea required rows="4" name="details"
                            class="resize-none bg-[#F4F7F9] w-full border border-[#0A2A4E] rounded-xl outline-none px-2 p-3 placeholder:text-[#0A2A4E]"
                            placeholder="سبب الإلغاء أو رقم المجلس الذي تم فيه الرفض"></textarea>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">تأكيد
                        إلغاء القرض</button>
                </div>
                <button
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('claims.reject', $pendingClaim->id ?? 0) }}" method="POST">
    @csrf
    <div id="modal-reject-claim"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button type="button"
            class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تأكيد إلغاء أو رفض المطالبة
                </h1>
            </div>
            <div class="space-y-4">
                <div class="space-y-4">
                    <h2 class="text-[#021219] text-base font-medium">
                        بيانات المطالبة
                    </h2>
                    <div class="flex gap-3">
                        <p class="text-base font-medium text-[#124375]">رقم المطالبة :<span
                                class="text-[#021219] font-semibold text-base">{{ $pendingClaim->id ?? 'غير متوفر' }}</span>
                        </p>
                        <p class="text-base font-medium text-[#124375]">نوع المطالبة :<span
                                class="text-[#021219] font-semibold text-base">{{ $pendingClaim ? $claims[$pendingClaim->type] ?? 'غير متوفر' : 'غير متوفر' }}</span>
                        </p>
                        <p class="text-base font-medium text-[#124375]">تاريخ تقديم الطلب :<span
                                class="text-[#021219] font-semibold text-base">{{ $pendingClaim ? $pendingClaim->created_at->format('Y-m-d') : 'غير متوفر' }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3 bg-[#D92D20] red-shadow text-[#F4F7F9] text-base font-medium">تأكيد
                        إلغاء المطالبة</button>
                </div>
                <button type="button"
                    class="border border-[#124375] w-full rounded-[14px] py-3 navy-shadow text-base font-medium text-[#124375] close-btn">إلغاء</button>
            </div>
        </div>
    </div>
</form>


<form action="{{ route('loans.installments.pay', 0) }}" method="POST" enctype="multipart/form-data"
    id="payInstallmentForm">
    @csrf
    <div id="modal5"
        class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد قسط قرض
                </h1>
            </div>
            <div class="space-y-7">
                <div>
                    <p>يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.</p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="relative w-full mb-5 z-50">
                        <label
                            class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1 z-10">
                            طريقة الدفع <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="hidden" name="payment_method" class="payment-method-input" required>
                        <button type="button"
                            class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-4 rounded-xl text-base flex justify-between items-center transition-colors payment-method-btn">
                            <span class="text-[#021219]">اختر طريقة الدفع</span>
                            <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                    class="text-xl"></iconify-icon></span>
                        </button>
                        <p class="payment_error_msg hidden text-[#D92D20] text-sm font-medium px-2 mt-1 text-right">يجب
                            اختيار طريقة الدفع</p>
                        <div
                            class="dropDown w-full hidden absolute z-[60] bg-[#F4F7F9] right-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow">
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="cash">نقدي</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="bank_transfer">تحويل بنكي</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="salary_deduction">خصم من المرتب</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="university_payment_order">أمر دفع من الجامعة</a>
                        </div>
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" name="receipt_number" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class=" border border-[#124375] rounded-2xl w-full">
                        <label for="file-23"
                            class=" cursor-pointer py-10 text-[#6D6D6D] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-23" name="receipt_image" class="hidden"
                                accept=".pdf, image/*">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        السداد</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('loans.earlyRepayment', $memberLoans->first()->id ?? 0) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div id="modal6"
        class="hidden w-full max-w-2xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-7">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد مبكر لأقساط القرض
                </h1>
            </div>
            <div class="space-y-7">
                <div class="flex flex-col gap-3">
                    <p class="text-[18px] font-medium text-[#021219]">سيتم إنهاء القرض بالكامل، وسيتم احتساب جميع
                        الأقساط المتبقية كمدفوعة.</p>
                    <p class="text-[18px]">المبلغ المطلوب للسداد الكامل : <span class="font-medium text-[#124375]">
                            {{ $activeLoan ? ($activeLoan->installments->count() > 0 ? number_format($activeLoan->installments->where('status', 'unpaid')->sum('amount'), 2) : number_format($activeLoan->total_amount, 2)) : '0.00' }}
                        </span> جنيه</p>
                    <p class="text-[16px] font-medium text-[#021219]">يرجى إرفاق رقم و صورة الإيصال السداد لإتمام
                        العملية.</p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="relative w-full mb-5 z-50">
                        <label
                            class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1 z-10">
                            طريقة الدفع <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="hidden" name="payment_method" class="payment-method-input" required>
                        <button type="button"
                            class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-4 rounded-xl text-base flex justify-between items-center payment-method-btn">
                            <span class="text-[#021219]">اختر طريقة الدفع</span>
                            <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                    class="text-xl"></iconify-icon></span>
                        </button>
                        <p class="payment_error_msg hidden text-[#D92D20] text-sm font-medium px-2 mt-1 text-right">يجب
                            اختيار طريقة الدفع</p>
                        <div
                            class="dropDown w-full hidden absolute z-[60] bg-[#F4F7F9] right-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow">
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="cash">نقدي</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="bank_transfer">تحويل بنكي</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="salary_deduction">خصم من المرتب</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="university_payment_order">أمر دفع من الجامعة</a>
                        </div>
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" name="receipt_number" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class=" border border-[#124375] rounded-2xl w-full">
                        <label for="file-24"
                            class=" cursor-pointer py-10 text-[#124375] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-24" name="receipt_image" class="hidden"
                                accept=".pdf, image/*">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        السداد</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('subscriptions.pay', 0) }}" method="POST" enctype="multipart/form-data"
    id="paySubscriptionForm">
    @csrf
    <div id="modal7"
        class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    تسجيل سداد الاشتراك
                </h1>
            </div>
            <div class="space-y-7">
                <div>
                    <p>يرجى إرفاق رقم و صورة إيصال السداد لإتمام العملية.</p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="relative w-full mb-5 z-50">
                        <label
                            class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1 z-10">
                            طريقة الدفع <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="hidden" name="payment_method" class="payment-method-input" required>
                        <button type="button"
                            class="dropDownBtn border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full px-4 rounded-xl text-base flex justify-between items-center transition-colors payment-method-btn">
                            <span class="text-[#021219]">اختر طريقة الدفع</span>
                            <span class="flex items-center"><iconify-icon icon="fe:arrow-down"
                                    class="text-xl"></iconify-icon></span>
                        </button>
                        <p class="payment_error_msg hidden text-[#D92D20] text-sm font-medium px-2 mt-1 text-right">يجب
                            اختيار طريقة الدفع</p>
                        <div
                            class="dropDown w-full hidden absolute z-[60] bg-[#F4F7F9] right-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl navy-shadow">
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="cash">نقدي</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="bank_transfer">تحويل بنكي</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="salary_deduction">خصم من المرتب</a>
                            <a class="cursor-pointer navy-shadow hover:bg-[#EEF7FF] py-2 px-5 rounded-xl text-base payment-option"
                                data-value="university_payment_order">أمر دفع من الجامعة</a>
                        </div>
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            رقم الإيصال <span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" name="receipt_number" placeholder="FJB2116708086230"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class=" border border-[#124375] rounded-2xl w-full">
                        <label for="file-25"
                            class=" cursor-pointer py-10 text-[#6D6D6D] flex items-center justify-center gap-1">
                            <p>اضغط لإرفاق صورة إيصال السداد</p>
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                            <input type="file" id="file-25" name="receipt_image" class="hidden"
                                accept=".pdf, image/*">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <div class="w-full">
                    <button type="submit"
                        class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>تأكيد
                        السداد</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('members.notify', $member->id) }}" method="POST" id="notifySubscriptionForm">
    @csrf
    <div id="modal8"
        class="hidden w-full max-w-4xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    إرسال إخطار رسمي للسداد
                </h1>
            </div>
            <div class="space-y-7">
                <div class="flex gap-2">
                    <iconify-icon icon="bxs:error" class="text-[#E6B800] text-xl mt-1"></iconify-icon>
                    <p>تنبيه: العضو متأخر لمدة <span>
                            {{ $member->membershipInfo ? $member->membershipInfo->subscriptions->where('status', 'unpaid')->count() : 0 }}
                        </span>شهور ويستوجب الإنذار القانوني.</p>
                </div>
                <div class="grid grid-cols-3 gap-4 ">
                    <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                        <p class="text-[#124375] text-[16px] ">الاسم : <span
                                class="text-[#021219] text-[16px] font-semibold">{{ $member->user->name }}</span></p>
                    </div>
                    <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                        <p class="text-[#124375] text-[16px] ">رقم العضوية : <span
                                class="text-[#021219] text-[16px] font-semibold">{{ $member->membershipInfo->membership_number ?? 'غير متوفر' }}</span>
                        </p>
                    </div>
                    <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                        <p class="text-[#124375] text-[16px] ">شهور التأخير : <span
                                class="text-[#021219] text-[16px] font-semibold">{{ $member->membershipInfo ? $member->membershipInfo->subscriptions->where('status', 'unpaid')->count() : 0 }}
                                شهور</span></p>
                    </div>
                    <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                        <p class="text-[#124375] text-[16px] ">إجمالي المبلغ المستحق : <span
                                class="text-[#021219] text-[16px] font-semibold">{{ number_format($member->membershipInfo ? $member->membershipInfo->subscriptions->where('status', 'unpaid')->sum('amount') : 0, 2) }}
                                ج.م </span></p>
                    </div>
                    <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                        <p class="text-[#124375] text-[16px] ">أقدم شهر مستحق : <span
                                class="text-[#021219] text-[16px] font-semibold">{{ $member->membershipInfo ? optional($member->membershipInfo->subscriptions->where('status', 'unpaid')->sortBy('due_date')->first())->due_date?->format('M Y') ?? 'لا يوجد' : 'لا يوجد' }}</span>
                        </p>
                    </div>
                    <div class="flex bg-[#EFEFEF] py-4 px-2 rounded-[10px]">
                        <p class="text-[#124375] text-[16px] ">تاريخ إرسال الإخطار : <span
                                class="text-[#021219] text-[16px] font-semibold">{{ now()->format('Y-m-d') }}</span>
                        </p>
                    </div>
                </div>
                <div class="border border-[#124375] rounded-[10px] py-5 px-3">
                    <p>يحيطكم الصندوق علماً بتأخركم في سداد الاشتراكات لمدة
                        {{ $member->membershipInfo ? $member->membershipInfo->subscriptions->where('status', 'unpaid')->count() : 0 }}
                        أشهر، ويرجى السداد في موعد غايته "شهر" من
                        تاريخه وإلا سيتم فصل العضوية نهائياً وفقاً للمادة (8). </p>
                </div>
            </div>
            <div class="btns flex gap-2 ">
                <button type="submit"
                    class="submit-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                            icon="ic:round-email" class="flex items-center text-2xl"></iconify-icon></span>طباعة
                    وإرسال
                    الإخطار</button>
                <button
                    class="close-btn rounded-[14px] w-full py-3 btn-disabled text-base font-medium flex items-center justify-center gap-2 text-[#124375] border border-[#124375] navy-shadow ">إلغاء</button>
            </div>
        </div>
    </div>
</form>
