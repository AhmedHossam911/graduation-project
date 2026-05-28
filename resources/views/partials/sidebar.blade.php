    <div class="flex ">
        <!-- start SideBar -->
        <aside class="SideBar bg-[#EEF7FF] active flex flex-col justify-between min-h-screen border-l border-[#124375]">
            <div class="sidebar-pages flex flex-col  gap-5">
                <a href="{{ route('dashboard') }}">
                        <div
                            class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('dashboard') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                        <iconify-icon icon="mdi:home" class="text-3xl"></iconify-icon>
                        <p>الصفحة الرئيسية</p>
                    </div>
                </a>
                @permission('إدارة الأعضاء')
                    <a href="{{ route('members.index') }}">
                        <div
                            class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('members.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                            <iconify-icon icon="mdi:account-group" class="text-3xl"></iconify-icon>
                            <p>الأعضاء</p>
                        </div>
                    </a>
                @endpermission
                @permission('إدارة الاشتراكات')
                    <a href="{{ route('memberships.index') }}">
                        <div
                            class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('memberships.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                            <iconify-icon icon="material-symbols:list-alt-check-rounded" class="text-3xl"></iconify-icon>
                            <p>الاشتراكات</p>
                        </div>
                    </a>
                @endpermission
                @permission('إدارة القروض')
                    <a href="{{ route('loans.index') }}">
                        <div
                            class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('loans.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                            <iconify-icon icon="fluent:money-16-filled" class="text-3xl"></iconify-icon>
                            <p>القروض</p>
                        </div>
                    </a>
                @endpermission
                @permission('الشؤون المالية')
                    <a href="{{ route('finance.index') }}">
                        <div
                            class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('finance.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                            <iconify-icon icon="fluent-mdl2:financial-solid" class="text-3xl"></iconify-icon>
                            <p>المالية</p>
                        </div>
                    </a>
                @endpermission
                @permission('إدارة المطالبات')
                    <a href="{{ route('claims.index') }}">
                        <div
                            class="page rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 {{ request()->routeIs('claims.*') ? 'bg-[#124375] text-white shadow-lg shadow-[#124375]/40' : 'surface-shadow text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all' }} ">
                            <iconify-icon icon="mdi:account-file" class="text-3xl"></iconify-icon>
                            <p>المطالبات</p>
                        </div>
                    </a>
                @endpermission
            </div>
            <div>
                <div
                    class="page surface-shadow rounded-xl text-lg font-medium px-4 py-3 flex items-center gap-3 text-[#124375] bg-[#F4F7F9] hover:bg-[#e2edf8] transition-all ">
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


