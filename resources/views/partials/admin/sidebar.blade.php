{{--
    Admin Sidebar Partial:
    Navigation links and structure specifically for the Super Admin dashboard,
    including advanced reports, audit logs, and system settings.
--}}

        <!-- start SideBar -->
        <aside
            class="print:hidden SideBar bg-[#EEF7FF] active flex flex-col justify-between min-h-screen border-l border-[#124375] overflow-y-auto custom-scrollbar pb-6">
            <div class="sidebar-pages flex flex-col  gap-5">
                <a href="{{ route('admin.dashboard') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('admin.dashboard') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="mdi:home" class="text-3xl"></iconify-icon>
                        <p>الصفحة الرئيسية</p>
                    </div>
                </a>
                <a href="{{ route('members.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('members.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="mdi:account-group" class="text-3xl"></iconify-icon>
                        <p>الأعضاء</p>
                    </div>
                </a>
                <a href="{{ route('memberships.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('memberships.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="material-symbols:list-alt-check-rounded" class="text-3xl"></iconify-icon>
                        <p>الاشتراكات</p>
                    </div>
                </a>
                <a href="{{ route('loans.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('loans.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="fluent:money-16-filled" class="text-3xl"></iconify-icon>
                        <p>القروض</p>
                    </div>
                </a>
                <a href="{{ route('finance.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('finance.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="fluent-mdl2:financial-solid" class="text-3xl"></iconify-icon>
                        <p>المالية</p>
                    </div>
                </a>
                <a href="{{ route('claims.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('claims.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="mdi:account-file" class="text-3xl"></iconify-icon>
                        <p>المطالبات</p>
                    </div>
                </a>
                <p>
                    الرقابة والتقارير
                </p>
                <a href="{{ route('admin.reports.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('admin.reports.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="ion:document" class="text-3xl"></iconify-icon>
                        <p>التقارير الكاملة</p>
                    </div>
                </a>
                <a href="{{ route('admin.auditlog.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('admin.auditlog.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="mdi:file-clock" class="text-3xl"></iconify-icon>
                        <p>سجل العمليات</p>
                    </div>
                </a>
                <p>
                    إعدادات النظام
                </p>
                <a href="{{ route('admin.settings.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('admin.settings.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="mdi:cog" class="text-3xl"></iconify-icon>
                        <p>الإعدادات</p>
                    </div>
                </a>
                <a href="{{ route('admin.permissions.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('admin.permissions.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="fa7-solid:user-cog" class="text-3xl"></iconify-icon>
                        <p>الصلاحيات</p>
                    </div>
                </a>
                <a href="{{ route('admin.departments.index') }}">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('admin.departments.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="bi:buildings-fill" class="text-3xl"></iconify-icon>
                        <p>ادارة القطاعات</p>
                    </div>
                </a>
            </div>
            <div class="mt-8 flex flex-col gap-3">
                <a href="{{ route('profile.index') }}" class="md:hidden">
                    <div
                        class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('profile.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }}">
                        <iconify-icon icon="boxicons:user-filled" class="text-3xl"></iconify-icon>
                        <p>الملف الشخصي</p>
                    </div>
                </a>
                <div
                    class="page surface-shadow rounded-xl text-lg font-medium px-4  mt-8 py-3 flex items-center gap-3 text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all ">
                    <form method="POST" action="{{ route('log-out') }}" class="flex items-center gap-2 w-full">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 w-full bg-transparent border-none cursor-pointer text-inherit text-lg font-medium">
                            <iconify-icon icon="ic:round-logout" class="text-3xl text-[#D92D20]"></iconify-icon>
                            <p>تسجيل الخروج</p>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        <!-- end SideBar -->
