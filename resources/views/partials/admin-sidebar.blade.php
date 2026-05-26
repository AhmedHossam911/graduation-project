    <div class="flex ">
        <!-- start SideBar -->
        <aside class="SideBar bg-[#EEF7FF] active flex flex-col justify-between min-h-screen border-l border-[#124375]">
            <div class="sidebar-pages flex flex-col  gap-5">
                <a href="{{ route('dashboard') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="mdi:home" class="text-3xl"></iconify-icon>
                        <p>الصفحة الرئيسية</p>
                    </div>
                </a>
                <a href="{{ route('members.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="mdi:account-group" class="text-3xl"></iconify-icon>
                        <p>الأعضاء</p>
                    </div>
                </a>
                <a href="{{ route('memberships.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="material-symbols:list-alt-check-rounded" class="text-3xl"></iconify-icon>
                        <p>الاشتراكات</p>
                    </div>
                </a>
                <a href="{{ route('loans.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="fluent:money-16-filled" class="text-3xl"></iconify-icon>
                        <p>القروض</p>
                    </div>
                </a>
                <a href="{{ route('finance.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="fluent-mdl2:financial-solid" class="text-3xl"></iconify-icon>
                        <p>المالية</p>
                    </div>
                </a>
                <a href="{{ route('claims.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="mdi:account-file" class="text-3xl"></iconify-icon>
                        <p>المطالبات</p>
                    </div>
                </a>
                <p>
                    الرقابة والتقارير
                </p>
                <a href="{{ route('admin.auditlog.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="mdi:file-clock" class="text-3xl"></iconify-icon>
                        <p>سجل العمليات</p>
                    </div>
                </a>
                <p>
                    إعدادات النظام
                </p>
                <a href="{{ route('admin.settings.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="mdi:cog" class="text-3xl"></iconify-icon>
                        <p>الإعدادات</p>
                    </div>
                </a>
                <a href="{{ route('admin.permissions.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="fa7-solid:user-cog" class="text-3xl"></iconify-icon>
                        <p>الصلاحيات</p>
                    </div>
                </a>
                <a href="{{ route('admin.departments.index') }}">
                    <div
                        class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
                        <iconify-icon icon="bi:buildings-fill" class="text-3xl"></iconify-icon>
                        <p>ادارة القطاعات</p>
                    </div>
                </a>
            </div>
            <div>
                <div
                    class="page surface-shadow rounded-xl text-lg font-medium px-2 py-3 flex items-center gap-2  text-[#124375] bg-[#F4F7F9] ">
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
