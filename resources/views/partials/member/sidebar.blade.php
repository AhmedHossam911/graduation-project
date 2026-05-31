<!-- start SideBar -->
<aside
    class="w-fit SideBar px-4 py-5 bg-[#124375]  flex flex-col justify-between min-h-screen border-l border-[#124375] overflow-y-auto custom-scrollbar pb-6">
    <div class="sidebar-pages flex flex-col  ">
        <a href="{{ route('member.dashboard') }}">
            <div class="flex gap-5 items-center border-b border-[#A8A8A8] pb-5">
                <img src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="" class="bg-[#F4F7F9] w-[60px] p-1 rounded-md">
                <h1 class="text-[24px] text-[#F4F7F9] font-semibold">صندوق الزمالة</h1>
            </div>
        </a>
        <div class="py-4 space-y-3">
            <a href="{{ route('member.dashboard') }}"
                class="{{ request()->routeIs('member.dashboard') ? 'active bg-[#F4F7F933] side-bar-shadow' : '' }} cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 px-2 page rounded-xl text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <div>
                    <iconify-icon icon="ic:round-dashboard" class="text-3xl mt-1"></iconify-icon>
                </div>
                <p>الرئيسية</p>
            </a>
            <a href="{{ route('member.loans.index') }}"
                class="{{ request()->routeIs('member.loans.*') ? 'active bg-[#F4F7F933] side-bar-shadow' : '' }} cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <div>
                    <iconify-icon icon="fluent:money-16-filled" class="text-3xl mt-1"></iconify-icon>
                </div>
                <p>القروض</p>
            </a>
            <a href="{{ route('member.claims.index') }}"
                class="{{ request()->routeIs('member.claims.*') ? 'active bg-[#F4F7F933] side-bar-shadow' : '' }} cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <div>
                    <iconify-icon icon="f7:exclamationmark-shield-fill" class="text-3xl mt-1"></iconify-icon>
                </div>
                <p>المطالبات</p>
            </a>
            <a href="{{ route('member.receipts.index') }}"
                class="{{ request()->routeIs('member.receipts.*') ? 'active bg-[#F4F7F933] side-bar-shadow' : '' }} cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <div>
                    <iconify-icon icon="iconamoon:invoice-fill" class="text-3xl mt-1"></iconify-icon>
                </div>
                <p>سجل الإيصالات</p>
            </a>
        </div>
    </div>
    <div class="mt-8 flex flex-col gap-3">
        <a href="{{ route('profile.index') }}"
            class="md:hidden {{ request()->routeIs('profile.*') ? 'active bg-[#F4F7F933] side-bar-shadow' : '' }} cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
            <div>
                <iconify-icon icon="boxicons:user-filled" class="text-3xl mt-1"></iconify-icon>
            </div>
            <p>الملف الشخصي</p>
        </a>
        <form method="POST" action="{{ route('log-out') }}">
            @csrf
            <button type="submit"
                class="w-full cursor-pointer bg-[#D92D201A] py-3 surface-shadow px-2 rounded-xl text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <iconify-icon icon="ic:round-logout" class="text-3xl text-[#D92D20]"></iconify-icon>
                <p>تسجيل الخروج</p>
            </button>
        </form>
    </div>
</aside>
<!-- end SideBar -->
