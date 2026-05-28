@extends('layouts.app')
{{-- 
    AuditLog View:
    Lists historical system actions (who, what, when, IP address) for transparency and security monitoring.
--}}

@section('title', 'سجل العمليات والمراقبة')

@include('partials.flash')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/auditLogs.css') }}">
    <div class="py-7 px-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-xl text-[#124375] font-semibold">
                سجل العمليات والمراقبة
            </h1>
            <p class="text-[#6D6D6D] text-[16px] font-normal">
                سجل زمني دقيق وغير قابل للمسح يرصد كافة الإجراءات المالية والإدارية
            </p>
        </div>
    </div>

    <form class="px-12 flex items-center justify-between gap-5" action="{{ route('admin.auditlog.index') }}" method="GET">
        <div class="relative flex-1">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="رقم السجل أو اسم المستخدم"
                class="pr-10 pl-4 py-2.5 w-full outline-none navy-shadow bg-[#F4F7F9] rounded-xl text-[#021219] focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow"></input>
            <iconify-icon icon="mynaui:search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-2xl text-[#124375]"></iconify-icon>
        </div>
        <div>
            <button type="submit"
                class="bg-[#124375] text-white rounded-xl px-6 py-1 flex items-center justify-center hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl "></iconify-icon>
            </button>
        </div>
    </form>

    <!-- start table -->
    <section class="px-12 py-7">
        <div class=" rounded-[14px] overflow-hidden border border-[#6D6D6D]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">رقم السجل</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">المنفذ / الصلاحية</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">التاريخ والوقت</th>
                        <th class="py-3 border-l border-[#6D6D6D] font-medium text-[#021219]">وصف سريع</th>
                        <th class="py-3 font-medium text-[#021219]">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                    <tr class="text-center border-b border-[#6D6D6D]">
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">LOG-{{ $log->id }}</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $log->user->name ?? 'نظام' }}</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $log->created_at->translatedFormat('j F Y - g:i A') }}</td>
                        <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">{{ $log->action_description }}</td>
                        <td class="py-3 flex gap-4 items-center justify-center text-[#124375]">
                            <iconify-icon icon="solar:eye-outline" class="text-2xl open-modal cursor-pointer"
                                data-modal="modal-{{ $log->id }}"></iconify-icon>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center">لا توجد سجلات.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <!-- end table -->


    <!-- start pagination -->
    <!-- end pagination -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>


    @foreach($auditLogs as $log)
    <div id="modal-{{ $log->id }}"
        class="flex flex-col hidden w-full max-w-3xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-5 max-h-[95vh]">
        <div class="flex justify-end shrink-0">
            <button
                class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
                <iconify-icon icon="weui:close-filled"></iconify-icon>
            </button>
        </div>
        <div class="modal-body px-8 flex-1 flex flex-col overflow-hidden">
            <div class="space-y-3">
                <div class="space-y-1 shrink-0">
                    <h1 class="text-[28px] font-semibold text-[#021219] ">
                        بيانات الحركة بالتفصيل
                    </h1>
                    <p class="text-[#6D6D6D] text-[16px] font-medium">
                        LOG-{{ $log->id }}
                    </p>
                </div>
            </div>
            <div class="flex-1 py-3 overflow-y-auto px-2 no-scrollbar space-y-3">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2 navy-shadow bg-[#F4F7F9] rounded-[16px] py-3 px-4">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="tabler:clock-filled"
                                    class="mt-1 text-[#124375] text-3xl"></iconify-icon>
                                <p class="text-[14px] text-[#6D6D6D] font-medium">وقت التنفيذ</p>
                            </div>
                            <p class="text-[16px] text-[#021219] font-medium">{{ $log->created_at->translatedFormat('j F Y - g:i A') }}</p>
                        </div>
                        <div class="space-y-2 navy-shadow bg-[#F4F7F9] rounded-[16px]  py-3 px-4">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="iconamoon:shield-yes-fill"
                                    class="mt-1 text-[#124375] text-3xl"></iconify-icon>
                                <p class="text-[14px] text-[#6D6D6D] font-medium">المستخدم</p>
                            </div>
                            <p class="text-[16px] text-[#021219] font-medium">{{ $log->user->name ?? 'نظام' }}</p>
                        </div>
                        <div class="space-y-2 navy-shadow bg-[#F4F7F9] rounded-[16px]  py-3 px-4">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="tabler:device-imac-filled"
                                    class="mt-1 text-[#124375] text-3xl"></iconify-icon>
                                <p class="text-[14px] text-[#6D6D6D] font-medium">الجهاز و المتصفح</p>
                            </div>
                            <p class="text-[16px] text-[#021219] font-medium">{{ request()->userAgent() ?? 'غير متوفر' }}</p>
                        </div>
                        <div class="space-y-2 navy-shadow bg-[#F4F7F9] rounded-[16px]  py-3 px-4">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="oui:ip" class="mt-1 text-[#124375] text-3xl"></iconify-icon>
                                <p class="text-[14px] text-[#6D6D6D] font-medium">عنوان IP</p>
                            </div>
                            <p class="text-[16px] text-[#021219] font-medium">{{ $log->ip_address ?? 'غير متوفر' }}</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h3 class="text-[20px] text-[#124375] font-semibold">وصف الإجراء</h3>
                    <div class="bg-[#F4F7F9] navy-shadow py-5 px-3 rounded-[10px]">
                        <p class="text-[16px] text-[#021219] font-medium">{{ $log->action_description }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <h3 class="text-[20px] text-[#124375] font-semibold">البيانات التقنية (Payload)</h3>
                    <div class="bg-[#021219] rounded-[10px] py-4 px-4 navy-shadow overflow-x-auto dir-ltr text-left">
                        <pre class="text-green-400 text-sm font-mono">
{{ json_encode(['old' => $log->old_values, 'new' => $log->new_values], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                        </pre>
                    </div>
                </div>
            </div>
            <div class="btns flex gap-2 shrink-0 pt-4">
                <form class="w-full flex justify-end">
                    <button type="button"
                        class="modal-close close-btn rounded-[14px]  py-3 px-20 btn-disabled  text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors">إغلاق</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="{{ asset('js/admin/auditLogs.js') }}"></script>
@endsection

@section('pagination')
    <div class="sticky bottom-0 bg-[#F4F7F9] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $auditLogs->links() }}
    </div>
@endsection
