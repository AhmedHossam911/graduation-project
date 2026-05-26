import os

filepath = r'c:\xampp\htdocs\graduation project\resources\views\employee\members\show.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update the @if condition for the create form
content = content.replace(
    "@if (request('claim_type') !== null)", 
    "@if (request('claim_type') !== null && request('view_claim') === null)"
)

# 2. Remove the receipt input from the create form
receipt_html = '''                        <div class="relative border border-[#6D6D6D] rounded-2xl w-full">
                            <span class="px-1 absolute right-3 top-[-15px] text-base text-[#6D6D6D] font-medium bg-[#F4F7F9]">توقيع {{ request('claim_type') === 'death' ? 'الوريث' : 'العضو' }} بصرف مستحقاته</span>
                            <label for="file-receipt" class="cursor-pointer py-3 text-[#6D6D6D] flex items-center justify-center gap-1">
                                <p>اضغط لإرفاق صورة الملف</p>
                                <iconify-icon icon="mingcute:upload-3-fill" class="text-2xl"></iconify-icon>
                                <input type="file" name="attachments[signed_receipt]" id="file-receipt" class="hidden">
                            </label>
                        </div>'''
content = content.replace(receipt_html, "")

# 3. Remove the declaration content from the create form
declaration_html = '''                    <div class="declaration space-y-3" id="declaration-content">
                        <h3 class="text-center font-medium">إقرار</h3>
                        <p class="font-medium text-lg leading-loose">
                            أقر أنا / <span class="font-bold underline decoration-dotted">{{ ->full_name }}</span> بأنني قد قمت باستلام كافة مستحقاتي من صندوق الزمالة الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان،
                            وذلك اعتبارًا من تاريخ {{ date('Y-m-d') }}، وأقر بعدم أحقيتي في المطالبة بأي مستحقات أخرى بعد هذا التاريخ
                        </p>
                        <p class="font-medium text-lg mt-8 flex justify-between">
                            <span>الاسم / <span class="font-bold">{{ ->full_name }}</span></span>
                            <span>الرقم القومي / <span class="font-bold">{{ ->national_id }}</span></span>
                            <span>التوقيع / ________________</span>
                        </p>
                    </div>'''
content = content.replace(declaration_html, "")

# 4. Remove the print button from the create form
print_btn = '''                        <button type="button" onclick="printDeclaration()"
                            class="border-2 border-[#124375] text-[#124375] font-bold w-1/3 py-3 rounded-[14px] flex items-center justify-center gap-2 navy-shadow hover:bg-[#F4F7F9] transition-colors">
                            <iconify-icon icon="material-symbols:print" class="text-2xl"></iconify-icon>
                            طباعة الإقرار
                        </button>'''
content = content.replace(print_btn, "")
# And adjust the submit button width from w-2/3 to w-full
content = content.replace('class="submit-btn rounded-[14px] w-2/3', 'class="submit-btn rounded-[14px] w-full')

# 5. Change the claims table button to pass view_claim
old_table_btn = '''                                    <td class="py-5">
                                        <button class="bg-[#124375] text-white py-3 navy-shadow px-8 rounded-xl font-medium">عرض التفاصيل</button>
                                    </td>'''
new_table_btn = '''                                    <td class="py-5">
                                        <a href="?tab=مطالبات&view_claim={{ ->id }}" class="bg-[#124375] text-white py-3 navy-shadow px-8 rounded-xl font-medium inline-block">عرض التفاصيل</a>
                                    </td>'''
content = content.replace(old_table_btn, new_table_btn)

# 6. Add the view_claim section below the claims table section
view_claim_html = '''
        <!-- view claim section -->
        @if (request('view_claim') !== null &&  = ->find(request('view_claim')))
            <section class="px-7 py-5 border-t border-gray-200 mt-5">
                <div class="flex items-center gap-2 mb-4">
                    <p class="text-2xl font-semibold text-[#124375]">تفاصيل المطالبة ({{ [->claim_type] ?? ->claim_type }})</p>
                </div>
                
                @if (->status === 'approved')
                    <form action="{{ route('claims.finalize', ->id) }}" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf
                        <div class="tab-content modal-body space-y-7 px-5">
                            
                            <div class="declaration space-y-3" id="declaration-content">
                                <h3 class="text-center font-medium text-xl text-[#124375]">إقرار استلام المستحقات</h3>
                                <p class="font-medium text-lg leading-loose border p-4 rounded-xl bg-[#F4F7F9]">
                                    أقر أنا / <span class="font-bold underline decoration-dotted">{{ ->full_name }}</span> بأنني قد قمت باستلام كافة مستحقاتي من صندوق الزمالة الخاص بأعضاء هيئة التدريس ومعاونيهم والعاملين بجامعة حلوان،
                                    وذلك اعتبارًا من تاريخ {{ date('Y-m-d') }}، وأقر بعدم أحقيتي في المطالبة بأي مستحقات أخرى بعد هذا التاريخ.
                                </p>
                                <p class="font-medium text-lg mt-8 flex justify-between">
                                    <span>الاسم / <span class="font-bold">{{ ->full_name }}</span></span>
                                    <span>الرقم القومي / <span class="font-bold">{{ ->national_id }}</span></span>
                                    <span>التوقيع / ________________</span>
                                </p>
                            </div>

                            <div class="mt-8 border border-[#124375] rounded-2xl w-full relative p-6 bg-white">
                                <span class="px-1 absolute right-3 top-[-15px] text-base text-[#124375] font-medium bg-white">رفع الإقرار بعد توقيعه <span class="text-[#D92D20]">*</span></span>
                                <label for="file-receipt" class="cursor-pointer py-3 text-[#124375] flex items-center justify-center gap-2 border-2 border-dashed border-[#124375] rounded-xl hover:bg-[#F4F7F9] transition-colors">
                                    <p class="text-lg">اضغط لإرفاق صورة الإقرار الموقع</p>
                                    <iconify-icon icon="mingcute:upload-3-fill" class="text-3xl"></iconify-icon>
                                    <input type="file" name="signed_receipt" id="file-receipt" class="hidden" required>
                                </label>
                            </div>
                            
                            <div class="btns flex gap-4 mt-6">
                                <button type="button" onclick="printDeclaration()"
                                    class="border-2 border-[#124375] text-[#124375] font-bold w-1/3 py-3 rounded-[14px] flex items-center justify-center gap-2 navy-shadow hover:bg-[#F4F7F9] transition-colors">
                                    <iconify-icon icon="material-symbols:print" class="text-2xl"></iconify-icon>
                                    طباعة الإقرار
                                </button>
                                <button type="submit"
                                    class="submit-btn rounded-[14px] w-2/3 py-3 bg-[#124375] text-[#EEF7FF] navy-shadow text-base font-medium flex items-center justify-center gap-2 hover:bg-opacity-90 transition-colors">
                                    تأكيد دفع الشيك (رفع الإقرار الموقع)
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="bg-[#FFF8E1] text-[#E6B800] border border-[#E6B800] p-4 rounded-xl text-center">
                        <p class="text-lg font-medium">يجب اعتماد هذه المطالبة أولاً حتى تتمكن من طباعة الإقرار وإرفاق التوقيع.</p>
                    </div>
                @endif
            </section>
        @endif
'''
content = content.replace('<!-- end requests section -->', '<!-- end requests section -->\n' + view_claim_html)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("Done replacing show.blade.php")
