<div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

@php
    $allUsersForModals = collect($activeUsers->items())->merge($suspendedUsers->items());
@endphp
@foreach ($allUsersForModals as $user)
    <div id="modal-{{ $user->id }}"
        class="flex flex-col hidden w-full max-w-2xl mx-auto fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-5 max-h-[95vh]">
        <div class="flex justify-end">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
        </div>
        <div class="modal-body px-10 flex-1 flex flex-col overflow-hidden">
            <div class="space-y-2 shrink-0">
                <div class="space-y-1">
                    <h2 class="text-[#021219] text-[28px] font-semibold">
                        صلاحيات {{ $user->name }}
                    </h2>
                    <p class="text-[#6D6D6D] text-[16px] font-medium">مراجعة المهام ونطاق العمل المكاني ( الكليات )
                        للموظف</p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="bi:buildings-fill" class="text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[20px] font-semibold">النطاق المكاني : الكليات المسؤول عنها
                            <span>({{ is_array($user->faculties) ? count($user->faculties) : 0 }})</span>
                        </p>
                    </div>
                    @if (is_array($user->faculties) && count($user->faculties) > 0)
                        <div class="grid grid-cols-3 gap-3">
                            @foreach ($user->faculties as $faculty)
                                <div class="flex items-center bg-[#F4F7F9] navy-shadow rounded-[8px] py-1 px-2">
                                    <iconify-icon icon="tabler:point-filled"
                                        class="text-3xl text-[#124375]"></iconify-icon>
                                    <span class="text-[14px] text-[#021219] font-medium">{{ $faculty }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="flex items-center gap-1 bg-[#E6F1FD80] border-[1.5px] border-[#124375] rounded-[8px] py-3 px-3 text-[#124375]">
                            <iconify-icon icon="ic:round-place" class="text-3xl"></iconify-icon>
                            <p class="text-[16px] font-semibold">لم يتم تحديد نطاق مكاني بعد.</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-4 pt-3">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="iconamoon:shield-yes-fill" class="text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[20px] font-semibold">قائمة الصلاحيات الممنوحة
                            <span>({{ is_array($user->custom_permissions) ? count($user->custom_permissions) : 0 }})</span>
                        </p>
                    </div>
                    @if ($user->role && $user->role->name === 'Admin')
                        <div
                            class="bg-[#F0FFF6] space-y-2 border-[1.5px] border-[#019168] rounded-[8px] py-5 px-3 text-[#019168]">
                            <div class="flex items-center gap-1 ">
                                <iconify-icon icon="icon-park-solid:success" class="text-2xl"></iconify-icon>
                                <p class="text-[16px] font-semibold">مدير نظام بصلاحيات كاملة</p>
                            </div>
                            <p class="text-[14px] font-medium">هذا المستخدم لديه وصول غير مقيد لجميع إعدادات النظام</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 flex-1 py-3 overflow-y-auto px-2 no-scrollbar">
                @if (is_array($user->custom_permissions))
                    @foreach ($user->custom_permissions as $perm)
                        <div class="flex items-center gap-7 bg-[#F4F7F9] navy-shadow px-4 py-3 rounded-[8px]">
                            <iconify-icon icon="mdi:success"
                                class="text-3xl text-[#0284C7] bg-[#E0F2FE] py-3 px-3 rounded-[8px]"></iconify-icon>
                            <div class="space-y-4">
                                <p class="text-[16px] font-medium text-[#021219]">{{ $perm }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-2 text-center text-[#6D6D6D] py-4">لا توجد صلاحيات مخصصة.</div>
                @endif
            </div>
            <div class="btns flex gap-2 shrink-0 pt-4">
                <div class="w-full flex justify-end">
                    <button type="button"
                        class="modal-close close-btn rounded-[14px]  py-3 px-20 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
