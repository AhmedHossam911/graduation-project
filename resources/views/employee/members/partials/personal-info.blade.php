    {{-- Member Info --}}
    <section class="py-5 px-7 print:hidden">
        <div class="personal-info relative border border-[#124375] rounded-[20px]">
            <h2 class="absolute text-[#124375] px-1 right-3 top-[-15px] text-lg font-medium bg-[#F4F7F9]">
                البيانات الشخصية
            </h2>
            <div class="information py-7 px-7">
                <div class="space-y-5">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الاسم كامل</label>
                            <input type="text" disabled value="{{ $member->full_name ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الرقم القومي</label>
                            <input type="text" disabled value="{{ $member->national_id ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">رقم الهاتف</label>
                            <input type="text" disabled value="{{ $member->phone ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الوظيفة</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->job_title ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">جهة العمل</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->workplace ?? ($member->department?->name ?? 'بيانات مفقودة') }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">البريد الإلكتروني</label>
                            <input type="text" disabled value="{{ $member->user->email ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">تاريخ الانضمام</label>
                            <input type="text" disabled value="{{ $member->created_at?->isoFormat('D MMMM YYYY') }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الحالة الوظيفية</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->job_title ?? 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                        <div class="relative w-full">
                            <label class="{{ $labelClass }}">الراتب الأساسي</label>
                            <input type="text" disabled
                                value="{{ $member->employmentInfo->starting_salary ? number_format($member->employmentInfo->starting_salary, 2) . ' ج.م' : 'بيانات مفقودة' }}"
                                class="{{ $fieldClass }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

