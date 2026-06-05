<div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

@php
    $allUsersForModals = collect($activeUsers->items())->merge($suspendedUsers->items());
    if (!isset($permissionColors)) {
        $permissionColors = [
            'إدارة الأعضاء' => ['text' => '#F59E0B', 'bg' => '#FEF3C7', 'border' => '#F59E0B'],
            'إدارة الصلاحيات' => ['text' => '#4F46E5', 'bg' => '#E0E7FF', 'border' => '#4F46E5'],
            'إدارة الاشتراكات' => ['text' => '#0284C7', 'bg' => '#E0F2FE', 'border' => '#0284C7'],
            'إعدادات اللائحة' => ['text' => '#9333EA', 'bg' => '#F3E8FF', 'border' => '#9333EA'],
            'إدارة القروض' => ['text' => '#EA580C', 'bg' => '#FFEDD5', 'border' => '#EA580C'],
            'عرض التقارير' => ['text' => '#0D9488', 'bg' => '#CCFBF1', 'border' => '#0D9488'],
            'إدارة المطالبات' => ['text' => '#E11D48', 'bg' => '#FFE4E6', 'border' => '#E11D48'],
            'الشؤون المالية' => ['text' => '#16A34A', 'bg' => '#DCFCE7', 'border' => '#16A34A'],
            'سجل العمليات' => ['text' => '#475569', 'bg' => '#F1F5F9', 'border' => '#475569'],
        ];
    }
@endphp
@foreach ($allUsersForModals as $user)
    <div id="modal-{{ $user->id }}"
        class="flex flex-col hidden w-[calc(100%-20px)] md:w-full max-w-3xl mx-auto fixed top-[10px] md:top-0 left-1/2 -translate-x-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-5 max-h-[95vh]">
        <div class="flex justify-end">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
        </div>
        <div class="modal-body px-4 md:px-8 flex-1 flex flex-col overflow-auto no-scrollbar">
            <div class="space-y-4 shrink-0">
                <div class="space-y-1">
                    <h2 class="text-[#021219] text-[24px] md:text-[28px] font-semibold">
                        صلاحيات {{ $user->name }}
                    </h2>
                    <p class="text-[#6D6D6D] text-[16px] font-medium">مراجعة المهام ونطاق العمل المكاني ( الكليات )
                        للموظف</p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="bi:buildings-fill" class="text-2xl md:text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[18px] md:text-[20px] font-semibold">النطاق المكاني : الكليات المسؤول عنها
                            <span>({{ is_array($user->faculties) ? count($user->faculties) : 0 }})</span>
                        </p>
                    </div>
                    @if (is_array($user->faculties) && count($user->faculties) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                            @foreach (array_slice($user->faculties, 0, 5) as $faculty)
                                <div class="flex items-center gap-2 bg-[#F4F7F9] navy-shadow rounded-[8px] py-2 px-3">
                                    <iconify-icon icon="tabler:point-filled"
                                        class="text-2xl text-[#124375] shrink-0"></iconify-icon>
                                    <span class="text-[14px] text-[#021219] font-medium leading-tight">{{ $faculty }}</span>
                                </div>
                            @endforeach
                            @if (count($user->faculties) > 10)
                                @php
                                    $remainingFaculties = array_slice($user->faculties, 10);
                                    $tooltipText = implode('، ', $remainingFaculties);
                                @endphp
                                <div class="flex items-center gap-2 bg-[#F4F7F9] navy-shadow rounded-[8px] py-2 px-3 cursor-help" title="{{ $tooltipText }}">
                                    <iconify-icon icon="tabler:dots"
                                        class="text-2xl text-[#124375] shrink-0"></iconify-icon>
                                    <span class="text-[14px] text-[#021219] font-medium leading-tight">وقطاعات أخرى</span>
                                </div>
                            @endif
                        </div>
                    @elseif ($user->role && $user->role->name === 'Admin')
                        <div
                            class="flex items-center gap-2 bg-[#E6F1FD80] border-[1.5px] border-[#124375] rounded-[8px] py-4 px-4 text-[#124375]">
                            <iconify-icon icon="ic:round-place" class="text-3xl shrink-0"></iconify-icon>
                            <p class="text-[16px] font-semibold">لديه وصول شامل لكافة كليات الجامعة وقطاعات الإدارة
                                العامة.</p>
                        </div>
                    @else
                        <div
                            class="flex items-center gap-2 bg-[#E6F1FD80] border-[1.5px] border-[#124375] rounded-[8px] py-4 px-4 text-[#124375]">
                            <iconify-icon icon="ic:round-place" class="text-3xl shrink-0"></iconify-icon>
                            <p class="text-[16px] font-semibold">لم يتم تحديد نطاق مكاني بعد.</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="iconamoon:shield-yes-fill" class="text-2xl md:text-3xl text-[#124375]"></iconify-icon>
                        <p class="text-[#021219] text-[18px] md:text-[20px] font-semibold">قائمة الصلاحيات الممنوحة
                            <span>({{ is_array($user->custom_permissions) ? count($user->custom_permissions) : 0 }})</span>
                        </p>
                    </div>
                    @if ($user->role && $user->role->name === 'Admin')
                        <div
                            class="bg-[#F0FFF6] space-y-2 border-[1.5px] border-[#019168] rounded-[8px] py-5 px-4 text-[#019168]">
                            <div class="flex items-center gap-2 ">
                                <iconify-icon icon="icon-park-solid:success" class="text-2xl shrink-0"></iconify-icon>
                                <p class="text-[16px] font-semibold">مدير نظام بصلاحيات كاملة</p>
                            </div>
                            <p class="text-[14px] font-medium">هذا المستخدم لديه وصول غير مقيد لجميع إعدادات النظام</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 flex-1 py-4 mt-2 overflow-y-auto px-2 -mx-2 no-scrollbar">
                @if (!($user->role && $user->role->name === 'Admin'))
                    @if (is_array($user->custom_permissions) && count($user->custom_permissions) > 0)
                        @foreach ($user->custom_permissions as $perm)
                            @php
                                $pColor = $permissionColors[$perm] ?? ['text' => '#0284C7', 'bg' => '#E0F2FE'];
                            @endphp
                            <div class="flex items-center gap-5 bg-[#F4F7F9] navy-shadow px-4 py-4 rounded-[8px]">
                                <iconify-icon icon="mdi:success" class="text-3xl py-3 px-3 rounded-[8px] shrink-0"
                                    style="color: {{ $pColor['text'] }}; background-color: {{ $pColor['bg'] }};"></iconify-icon>
                                <div class="space-y-2">
                                    <p class="text-[16px] font-semibold text-[#021219] leading-tight">{{ $perm }}</p>
                                    <p class="text-[14px] text-[#6D6D6D] font-medium leading-tight">مسؤول
                                        {{ str_replace('إدارة ', '', $perm) }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-1 md:col-span-2 text-center text-[#6D6D6D] py-4 font-medium">لا توجد صلاحيات مخصصة.</div>
                    @endif
                @endif
            </div>
            <div class="btns flex gap-2 shrink-0 pt-5">
                <form class="w-full flex justify-end">
                    <button type="button"
                        class="modal-close close-btn rounded-[14px] py-3 px-20 btn-disabled text-base font-semibold flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors">إغلاق</button>
                </form>
            </div>
        </div>
    </div>
@endforeach

