{{--
    Member Claims Tab Partial:
    Handles the presentation of a member's financial claims,
    allowing submission of new claims based on type and viewing claim history.
--}}
<!-- start requests section -->
<div class="tab-content {{ $activeTabName === 'مطالبات' ? '' : 'hidden' }} print:hidden" data-tab="مطالبات">
    <!-- first step of request -->
    @if (request('claim_type') !== null && request('view_claim') === null)
        @if (auth()->user() && auth()->user()->hasPermission('إدارة المطالبات'))
            <form action="{{ route('members.storeClaim', $member->id) }}" method="POST" enctype="multipart/form-data"
                class="w-full">
                @csrf
                <input type="hidden" name="claim_type" value="{{ request('claim_type') }}">
                <div class="tab-content modal-body space-y-7 mt-6 px-5" data-tab="مطالبات">
                    <div class="modal-title text-center">
                        <h1 class="text-xl font-semibold text-[#124375]">
                            {{ $claims[request('claim_type')] ?? '' }}
                        </h1>
                    </div>
                    <div class="space-y-3">
                        <div class="flex gap-4">
                            <p class="text-[#124375] text-base font-medium">الأسم : <span
                                    class="text-[#021219] text-base font-semibold">{{ $member->user->name }}</span></p>
                            <p class="text-[#124375] text-base font-medium">رقم العضوية : <span
                                    class="text-[#021219] text-base font-semibold">{{ $member->membershipInfo->membership_number ?? '-' }}</span>
                            </p>
                            <p class="text-[#124375] text-base font-medium">الرقم القومي : <span
                                    class="text-[#021219] text-base font-semibold">{{ $member->user->national_id }}</span>
                            </p>
                        </div>
                        <p>يرجى إرفاق المستندات التالية لإتمام مطالبة ({{ $claims[request('claim_type')] ?? '' }})
                            واستلام
                            المستحقات.</p>
                    </div>

                    <div class="documents grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-4">
                        <!-- common inputs -->
                        @if (request('claim_type') !== 'transfer')
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                                    بالمرتب الأساسي<span class="text-[#D92D20]">*</span></span>
                                <label for="file-salary"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[salary_letter]" id="file-salary"
                                        class="hidden" required>
                                </label>
                            </div>
                        @endif

                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان
                                بالمبالغ المخصومة<span class="text-[#D92D20]">*</span></span>
                            <label for="file-deductions"
                                class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[deductions_statement]" id="file-deductions"
                                    class="hidden" required>
                            </label>
                        </div>

                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span
                                class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب
                                بتاريخ التعيين<span class="text-[#D92D20]">*</span></span>
                            <label for="file-appointment"
                                class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[appointment_letter]" id="file-appointment"
                                    class="hidden" required>
                            </label>
                        </div>

                        @if (request('claim_type') !== 'death')
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه
                                    الرقم القومي<span class="text-[#D92D20]">*</span></span>
                                <label for="file-national"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[national_id]" id="file-national"
                                        class="hidden" required>
                                </label>
                            </div>
                        @endif

                        @if (request('claim_type') !== 'transfer' && request('claim_type') !== 'death')
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                    قرار الإحالة للمعاش<span class="text-[#D92D20]">*</span></span>
                                <label for="file-retirement"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[retirement_decision]" id="file-retirement"
                                        class="hidden" required>
                                </label>
                            </div>
                        @endif



                        @if (request('claim_type') === 'transfer')
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                    إخلاء طرف<span class="text-[#D92D20]">*</span></span>
                                <label for="file-release"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[release_form]" id="file-release"
                                        class="hidden" required>
                                </label>
                            </div>
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                    قرار النقل<span class="text-[#D92D20]">*</span></span>
                                <label for="file-transfer"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[service_end_decision]" id="file-transfer"
                                        class="hidden" required>
                                </label>
                            </div>
                        @endif

                        @if (request('claim_type') === 'death')
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                    قرار إنهاء الخدمة<span class="text-[#D92D20]">*</span></span>
                                <label for="file-death-end"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[service_end_decision]"
                                        id="file-death-end" class="hidden" required>
                                </label>
                            </div>
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                    شهادة الوفاة<span class="text-[#D92D20]">*</span></span>
                                <label for="file-death-cert"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[death_certificate]" id="file-death-cert"
                                        class="hidden" required>
                                </label>
                            </div>
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                    بطاقة الرقم القومي للورثة المستحقين<span class="text-[#D92D20]">*</span></span>
                                <label for="file-heirs"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[heirs_ids]" id="file-heirs"
                                        class="hidden" required>
                                </label>
                            </div>
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                    إعلام الوراثة الشرعي<span class="text-[#D92D20]">*</span></span>
                                <label for="file-inheritance"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[inheritance_notice]"
                                        id="file-inheritance" class="hidden" required>
                                </label>
                            </div>
                            <div class="col-span-2">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-base font-medium text-[#124375]">هل يوجد قصر ؟ <span
                                                class="text-[#D92D20]">*</span></p>
                                    </div>
                                    <div class="flex gap-3">
                                        <label for="yes" class="cursor-pointer flex items-center gap-3">
                                            <input type="radio" name="has_minors" value="1" id="yes"
                                                class="hidden peer" required
                                                onclick="document.getElementById('minors_files').classList.remove('hidden')">
                                            <span
                                                class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                            <span>نعم</span>
                                        </label>
                                        <label for="no" class="cursor-pointer flex items-center gap-3">
                                            <input type="radio" name="has_minors" value="0" id="no"
                                                class="hidden peer" required checked
                                                onclick="document.getElementById('minors_files').classList.add('hidden')">
                                            <span
                                                class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                            <span>لا</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="minors_files" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-4 hidden">
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        قرار الوصاية في حالة وجود قصر<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-guardianship"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[guardianship_decision]"
                                            id="file-guardianship" class="hidden">
                                    </label>
                                </div>
                                <div class="relative border border-[#124375] rounded-2xl w-full">
                                    <span
                                        class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة
                                        شهادات ميلاد القصر بالرقم القومي<span class="text-[#D92D20]">*</span></span>
                                    <label for="file-minors-certs"
                                        class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                        <p>اضغط لإرفاق صورة الملف</p>
                                        <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                        <input type="file" name="attachments[minors_birth_certs]"
                                            id="file-minors-certs" class="hidden">
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- <div class="declaration space-y-3" id="declaration-content">
                        <h3 class="text-center font-medium">إقرار</h3>
                        <p class="font-medium text-lg leading-loose">
                            أقر أنا / <span class="font-bold">{{ $member->user->name }}</span> بأنني قد قمت باستلام كافة مستحقاتي من صندوق الزمالة الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان،
                            وذلك اعتبارًا من تاريخ {{ date('Y-m-d') }}، وأقر بعدم أحقيتي في المطالبة بأي مستحقات أخرى بعد هذا التاريخ
                        </p>
                        <p class="font-medium text-lg mt-8 text-left">
                            <span>التوقيع / ________________</span>
                        </p>
                    </div> --}}

                    <div class="btns flex gap-4 mt-6">

                        <button type="submit"
                            class="submit-btn rounded-[14px] w-full py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2 hover:bg-opacity-90 transition-colors">
                            تقديم الطلب
                        </button>
                    </div>
                </div>
            </form>
        @endif

    @endif

    <!-- claims table section -->
    <section class="px-7 py-5">
        @if ($memberClaims->isEmpty() && request('claim_type') === null)
            <!-- no requests -->
            <div class="no-requests flex justify-center py-14">
                <div class="flex flex-col items-center gap-5">
                    <img src="{{ asset('IMGs/no-requests.png') }}" alt="no-requests">
                    <p>لم يتم إضافة مطالبة حتي الآن</p>
                </div>
            </div>
        @elseif ($memberClaims->isNotEmpty() && request('claim_type') === null)
            <!-- requests table -->
            <div class="requests-table rounded-[14px] overflow-x-auto border border-[#D1D5DB]">
                <table class="w-full md:min-w-max md:whitespace-nowrap">
                    <thead class="hidden md:table-header-group">
                        <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">رقم المطالبة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">نوع المطالبة</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">حالة الطلب</th>
                            <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ التقديم</th>
                            <th class="py-4 font-medium text-[#021219]">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group">
                        @foreach ($memberClaims as $claim)
                            <tr class="block md:table-row bg-white md:bg-transparent shadow-sm md:shadow-none rounded-xl md:rounded-none mb-4 md:mb-0 border md:border-none border-gray-200 text-right md:text-center {{ $loop->even ? 'md:bg-[#EFEFEF]' : '' }}">
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">رقم المطالبة:</span>
                                    <span>{{ $claim->id }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">نوع المطالبة:</span>
                                    <span>{{ $claims[$claim->type] ?? $claim->type }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l">
                                    <span class="md:hidden font-bold text-[#124375]">حالة الطلب:</span>
                                    <span class="{{ $claimStatusClasses[$claim->status] ?? 'bg-gray-100' }} border px-4 md:px-4 py-1.5 text-sm rounded-lg">
                                        {{ $claimStatusLabels[$claim->status] ?? $claim->status }}
                                    </span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-4 border-b border-dashed md:border-solid border-gray-300 md:border-[#D1D5DB] md:border-l text-[#021219]">
                                    <span class="md:hidden font-bold text-[#124375]">تاريخ التقديم:</span>
                                    <span>{{ $claim->created_at->format('Y-m-d') }}</span>
                                </td>
                                <td class="flex justify-between items-center md:table-cell px-4 py-5 border-b-0 md:border-b md:border-[#D1D5DB]">
                                    <span class="md:hidden font-bold text-[#124375]">الإجراء:</span>
                                    @if ($claim->status === 'approved')
                                        <a href="?tab=مطالبات&view_claim={{ $claim->id }}"
                                            class="bg-[#124375] text-white py-2 md:py-3 navy-shadow px-4 md:px-8 rounded-xl font-medium inline-block text-[14px] md:text-[16px]">عرض التفاصيل</a>
                                    @elseif($claim->status === 'pending')
                                        @if (auth()->user() && auth()->user()->hasPermission('إدارة المطالبات'))
                                            <a href="{{ route('claims.show', $claim->id) }}"
                                                class="bg-[#124375] text-white py-2 md:py-3 navy-shadow px-4 md:px-8 rounded-xl font-medium inline-block text-[14px] md:text-[16px]">اعتماد</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
    <!-- end requests section -->

    <!-- view claim section -->
    @if (request('view_claim') !== null)
        @php
            $viewedClaim = $memberClaims->find(request('view_claim'));
        @endphp
        <section class="px-7 py-5 border-t border-gray-200 mt-5">
            <div class="flex items-center gap-2 mb-4">
                <p class="text-2xl font-semibold text-[#124375]">تفاصيل المطالبة</p>
            </div>

            @if ($viewedClaim->status === 'approved')
                @if (auth()->user() && auth()->user()->hasPermission('إدارة المطالبات'))
                    <form action="{{ route('claims.finalize', $viewedClaim->id) }}" method="POST"
                        enctype="multipart/form-data" class="w-full">
                        @csrf
                        <div>
                            <div class="mt-8 border border-[#F4F7F9] rounded-2xl w-full relative p-6 bg-[#F4F7F9]">
                                <span
                                    class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">رفع
                                    الإقرار بعد توقيعه <span class="text-[#D92D20]">*</span></span>
                                <label for="file-receipt"
                                    class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-2 border-2 border-dashed border-[#124375] rounded-xl hover:bg-[#F4F7F9] transition-colors">
                                    <p class="text-lg">اضغط لإرفاق صورة الإقرار الموقع</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-3xl"></iconify-icon>
                                    <input type="file" name="signed_receipt" id="file-receipt" class="hidden"
                                        required>
                                </label>
                            </div>

                            <div class="btns flex flex-col sm:flex-row gap-4 mt-6">
                                <button type="button" id="print-claim-declaration-btn"
                                    onclick="window.open('{{ route('print.claim_declaration', $claim->id) }}', '_blank'); document.getElementById('confirm-claim-payment-btn').disabled = false; document.getElementById('confirm-claim-payment-btn').classList.remove('btn-disabled', 'bg-[#A8A8A8]', 'cursor-not-allowed'); document.getElementById('confirm-claim-payment-btn').classList.add('bg-[#124375]', 'hover:bg-opacity-90');"
                                    class="border-2 border-[#124375] text-[#124375] font-bold w-full sm:w-1/3 py-3 rounded-[14px] flex items-center justify-center gap-2 navy-shadow hover:bg-[#F4F7F9] transition-colors">
                                    <iconify-icon icon="material-symbols:print" class="text-2xl"></iconify-icon>
                                    طباعة الإقرار
                                </button>
                                <button type="submit" id="confirm-claim-payment-btn" disabled
                                    class="submit-btn rounded-[14px] w-full sm:w-2/3 py-3 bg-[#A8A8A8] btn-disabled cursor-not-allowed text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2 transition-colors">
                                    تأكيد دفع الشيك (رفع الإقرار الموقع)
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            @else
                <div class="bg-[#FFF8E1] text-[#E6B800] border border-[#E6B800] p-4 rounded-xl text-center">
                    <p class="text-lg font-medium">يجب اعتماد هذه المطالبة أولاً حتى تتمكن من طباعة الإقرار وإرفاق
                        التوقيع.</p>
                </div>
            @endif
        </section>
    @endif
</div>
