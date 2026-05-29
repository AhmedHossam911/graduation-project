<link rel="stylesheet" href="{{ asset('css/member/memberSidebar.css') }}">
<!-- start SideBar -->
<aside
    class="w-fit SideBar px-4 py-5 bg-[#124375] active flex flex-col justify-between min-h-screen border-l border-[#124375]">
    <div class="sidebar-pages flex flex-col  ">
        <div class="flex gap-5 items-center border-b border-[#A8A8A8] pb-5">
            <img src="../IMGs/Hu Logo 2.png" alt="" class="bg-[#F4F7F9]">
            <h1 class="text-[24px] text-[#F4F7F9] font-semibold">صندوق الزمالة</h1>
        </div>
        <div class="py-4 space-y-3">
            <a href="memberhome.html"
                class="active cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 px-2 page rounded-xl text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <div>
                    <iconify-icon icon="ic:round-dashboard" class="text-3xl mt-1"></iconify-icon>
                </div>
                <p>الرئيسية</p>
            </a>
            <a href="../memberLoanPage/memberLoan.html"
                class="cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <div>
                    <iconify-icon icon="fluent:money-16-filled" class="text-3xl mt-1"></iconify-icon>
                </div>
                <p>القروض</p>
            </a>
            <a href="../memberClaimsPage/memberClaims.html"
                class= "cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <div>
                    <iconify-icon icon="f7:exclamationmark-shield-fill" class="text-3xl mt-1"></iconify-icon>
                </div>
                <p>المطالبات</p>
            </a>
            <a href="../receiptsPage/receipts.html"
                class="cursor-pointer hover:bg-[#F4F7F933] hover:side-bar-shadow transition py-2 page  rounded-xl px-2 text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
                <div>
                    <iconify-icon icon="iconamoon:invoice-fill" class="text-3xl mt-1"></iconify-icon>
                </div>
                <p>سجل الإيصالات</p>
            </a>
        </div>
    </div>
    <div>
        <div
            class="cursor-pointer bg-[#D92D201A] py-3 surface-shadow px-2 rounded-xl text-lg font-medium flex items-center gap-2  text-[#F4F7F9]">
            <iconify-icon icon="ic:round-logout" class="text-3xl text-[#D92D20]"></iconify-icon>
            <p>تسجيل الخروج</p>
        </div>
    </div>
</aside>
<!-- end SideBar -->

<script src="{{ asset('JS/member/memberSidebar.js') }}"></script>
