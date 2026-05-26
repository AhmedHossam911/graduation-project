import json
content = '''        <!-- first step of request -->
        @if (request('claim_type') !== null)
            <form action="{{ route('members.storeClaim', $member->id) }}" method="POST" enctype="multipart/form-data" class="w-full">
                @csrf
                <input type="hidden" name="claim_type" value="{{ request('claim_type') }}">
                <div class="tab-content modal-body space-y-7 px-5" data-tab="مطالبات">
                    <div class="modal-title text-center">
                        <h1 class="text-xl font-semibold text-[#124375]">
                            {{ $claims[request('claim_type')] ?? '' }}
                        </h1>
                    </div>
                    <div class="space-y-3">
                        <div class="flex gap-4">
                            <p class="text-[#124375] text-base font-medium">الأسم : <span class="text-[#021219] text-base font-semibold">{{ $member->full_name }}</span></p>
                            <p class="text-[#124375] text-base font-medium">رقم العضوية : <span class="text-[#021219] text-base font-semibold">{{ $member->membershipInfo->membership_number ?? '-' }}</span></p>
                            <p class="text-[#124375] text-base font-medium">الرقم القومي : <span class="text-[#021219] text-base font-semibold">{{ $member->national_id }}</span></p>
                        </div>
                        <p>يرجى إرفاق المستندات التالية لإتمام طلب ({{ $claims[request('claim_type')] ?? '' }}) واستلام المستحقات.</p>
                    </div>
                    
                    <div class="documents grid grid-cols-2 gap-y-5 gap-x-4">
                        <!-- common inputs -->
                        @if (request('claim_type') !== 'transfer')
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب بالمرتب الأساسي<span class="text-[#D92D20]">*</span></span>
                            <label for="file-salary" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[salary_letter]" id="file-salary" class="hidden" required>
                            </label>
                        </div>
                        @endif

                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بيان بالمبالغ المخصومة<span class="text-[#D92D20]">*</span></span>
                            <label for="file-deductions" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[deductions_statement]" id="file-deductions" class="hidden" required>
                            </label>
                        </div>

                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">خطاب بتاريخ التعيين<span class="text-[#D92D20]">*</span></span>
                            <label for="file-appointment" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[appointment_letter]" id="file-appointment" class="hidden" required>
                            </label>
                        </div>

                        @if (request('claim_type') !== 'death')
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">بطاقه الرقم القومي<span class="text-[#D92D20]">*</span></span>
                            <label for="file-national" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[national_id]" id="file-national" class="hidden" required>
                            </label>
                        </div>
                        @endif

                        @if (request('claim_type') !== 'transfer' && request('claim_type') !== 'death')
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة قرار الإحالة للمعاش<span class="text-[#D92D20]">*</span></span>
                            <label for="file-retirement" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[retirement_decision]" id="file-retirement" class="hidden" required>
                            </label>
                        </div>
                        @endif

                        <div class="relative border border-[#6D6D6D] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#6D6D6D] font-medium bg-[#F4F7F9]">توقيع {{ request('claim_type') === 'death' ? 'الوريث' : 'العضو' }} بصرف مستحقاته</span>
                            <label for="file-receipt" class="cursor-pointer py-3 text-[#6D6D6D] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[signed_receipt]" id="file-receipt" class="hidden">
                            </label>
                        </div>

                        @if (request('claim_type') === 'transfer')
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة إخلاء طرف<span class="text-[#D92D20]">*</span></span>
                            <label for="file-release" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[release_form]" id="file-release" class="hidden" required>
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة قرار النقل<span class="text-[#D92D20]">*</span></span>
                            <label for="file-transfer" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[service_end_decision]" id="file-transfer" class="hidden" required>
                            </label>
                        </div>
                        @endif

                        @if (request('claim_type') === 'death')
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة قرار إنهاء الخدمة<span class="text-[#D92D20]">*</span></span>
                            <label for="file-death-end" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[service_end_decision]" id="file-death-end" class="hidden" required>
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة شهادة الوفاة<span class="text-[#D92D20]">*</span></span>
                            <label for="file-death-cert" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[death_certificate]" id="file-death-cert" class="hidden" required>
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة بطاقة الرقم القومي للورثة المستحقين<span class="text-[#D92D20]">*</span></span>
                            <label for="file-heirs" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[heirs_ids]" id="file-heirs" class="hidden" required>
                            </label>
                        </div>
                        <div class="relative border border-[#124375] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة إعلام الوراثة الشرعي<span class="text-[#D92D20]">*</span></span>
                            <label for="file-inheritance" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[inheritance_notice]" id="file-inheritance" class="hidden" required>
                            </label>
                        </div>
                        <div class="col-span-2">
                            <div class="flex justify-between">
                                <div>
                                    <p class="text-base font-medium text-[#124375]">هل يوجد قصر ؟ <span class="text-[#D92D20]">*</span></p>
                                </div>
                                <div class="flex gap-3">
                                    <label for="yes" class="cursor-pointer flex items-center gap-3">
                                        <input type="radio" name="has_minors" value="1" id="yes" class="hidden peer" required onclick="document.getElementById('minors_files').classList.remove('hidden')">
                                        <span class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                        <span>نعم</span>
                                    </label>
                                    <label for="no" class="cursor-pointer flex items-center gap-3">
                                        <input type="radio" name="has_minors" value="0" id="no" class="hidden peer" required checked onclick="document.getElementById('minors_files').classList.add('hidden')">
                                        <span class="inline-block w-4 h-4 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                        <span>لا</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="minors_files" class="col-span-2 grid grid-cols-2 gap-y-5 gap-x-4 hidden">
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة قرار الوصاية في حالة وجود قصر<span class="text-[#D92D20]">*</span></span>
                                <label for="file-guardianship" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[guardianship_decision]" id="file-guardianship" class="hidden">
                                </label>
                            </div>
                            <div class="relative border border-[#124375] rounded-2xl w-full">
                                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-[#F4F7F9]">صورة شهادات ميلاد القصر بالرقم القومي<span class="text-[#D92D20]">*</span></span>
                                <label for="file-minors-certs" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-1">
                                    <p>اضغط لإرفاق صورة الملف</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                    <input type="file" name="attachments[minors_birth_certs]" id="file-minors-certs" class="hidden">
                                </label>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="declaration space-y-3" id="declaration-content">
                        <h3 class="text-center font-medium">إقرار</h3>
                        <p class="font-medium text-lg leading-loose">
                            أقر أنا / <span class="font-bold underline decoration-dotted">{{ $member->full_name }}</span> بأنني قد قمت باستلام كافة مستحقاتي من صندوق الزمالة الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان،
                            وذلك اعتبارًا من تاريخ {{ date('Y-m-d') }}، وأقر بعدم أحقيتي في المطالبة بأي مستحقات أخرى بعد هذا التاريخ
                        </p>
                        <p class="font-medium text-lg mt-8 flex justify-between">
                            <span>الاسم / <span class="font-bold">{{ $member->full_name }}</span></span>
                            <span>الرقم القومي / <span class="font-bold">{{ $member->national_id }}</span></span>
                            <span>التوقيع / ________________</span>
                        </p>
                    </div>
                    
                    <div class="btns flex gap-4 mt-6">
                        <button type="button" onclick="printDeclaration()"
                            class="border-2 border-[#124375] text-[#124375] font-bold w-1/3 py-3 rounded-[14px] flex items-center justify-center gap-2 navy-shadow hover:bg-[#F4F7F9] transition-colors">
                            <iconify-icon icon="material-symbols:print" class="text-2xl"></iconify-icon>
                            طباعة الإقرار
                        </button>
                        <button type="submit"
                            class="submit-btn rounded-[14px] w-2/3 py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2 hover:bg-opacity-90 transition-colors">
                            تقديم الطلب (Submit Request)
                        </button>
                    </div>
                </div>
            </form>
            <script>
                function printDeclaration() {
                    const content = document.getElementById('declaration-content').innerHTML;
                    const originalContent = document.body.innerHTML;
                    document.body.innerHTML = content;
                    window.print();
                    document.body.innerHTML = originalContent;
                    window.location.reload();
                }
            </script>
        @endif
'''

with open(r'c:\xampp\htdocs\graduation project\resources\views\employee\members\show.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

out_lines = lines[:473] + [content + '\n'] + lines[664:]

with open(r'c:\xampp\htdocs\graduation project\resources\views\employee\members\show.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(out_lines)
