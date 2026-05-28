<div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

<div id="modal1"
    class=" w-full hidden max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
    <div class="flex justify-end">
        <button type="button"
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
    </div>
    <form action="{{ route('admin.departments.store') }}" method="POST">
        @csrf
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title space-y-2">
                <h1 class="text-[28px] font-semibold text-[#021219] ">
                    إضافة عنصر جديد
                </h1>
                <p class="text-[#6D6D6D] text-[16px] font-medium">
                    يمكنك إضافة كلية أو قطاع جديد للنظام.
                </p>
            </div>
            <div class="space-y-7">
                <div class="flex flex-col gap-7">
                    <div class="relative w-full">
                        <label
                            class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                            الاسم / الوصف الأساسي
                        </label>
                        <input type="text" name="name" required placeholder="مثال : كلية الطب"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label
                            class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                            عدد الأعضاء المسجلين
                        </label>
                        <input type="text" disabled placeholder="يتم حسابه تلقائيا"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                </div>
            </div>
            <div class="btns flex gap-4 ">
                <div class="w-full">
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="ic:round-plus"
                                class="flex items-center text-2xl"></iconify-icon></span>إضافة</button>
                </div>
                <div class="w-full">
                    <button type="button"
                        class="close-btn rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center  bg-[#F4F7F9] text-[#124375] navy-shadow ">إلغاء
                        الأمر</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="modal2"
    class=" w-full hidden max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
    <div class="flex justify-end">
        <button type="button"
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
    </div>
    <form id="editForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body space-y-7 px-12">
            <div class="modal-title space-y-2">
                <h1 class="text-[28px] font-semibold text-[#021219] ">
                    تعديل البيانات
                </h1>
                <p class="text-[#6D6D6D] text-[16px] font-medium">
                    يمكنك تعديل بيانات الكلية أو القطاع الحالي وحفظ التغييرات.
                </p>
            </div>
            <div class="space-y-7">
                <div class="flex flex-col gap-7">
                    <div class="relative w-full">
                        <label
                            class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                            الاسم / الوصف الأساسي
                        </label>
                        <input type="text" id="edit_name" name="name" required placeholder="مثال : كلية الطب"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label
                            class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                            عدد الأعضاء المسجلين
                        </label>
                        <input type="text" id="edit_members" disabled placeholder=""
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                </div>
            </div>
            <div class="btns flex gap-4 ">
                <div class="w-full">
                    <button type="submit"
                        class=" rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors"><span><iconify-icon
                                icon="fluent:save-16-filled"
                                class="flex items-center text-2xl"></iconify-icon></span>حفظ
                        التعديلات</button>
                </div>
                <div class="w-full">
                    <button type="button"
                        class="close-btn rounded-[14px] w-full py-3  text-base font-medium flex items-center justify-center  bg-[#F4F7F9] text-[#124375] navy-shadow ">إلغاء
                        الأمر</button>
                </div>
            </div>
        </div>
    </form>
</div>
