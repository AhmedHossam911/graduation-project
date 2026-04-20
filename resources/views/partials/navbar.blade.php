    <!-- start header -->
    <header class="bg-[#EEF7FF] border border-[#124375] px-7 py-3">
        <nav class="flex items-center justify-between  ">
            <div class="flex items-center gap-3 text-[#124375]">
                <button id="Nav-menu">
                    <iconify-icon icon="material-symbols:menu-rounded" class="text-2xl"></iconify-icon>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="logo">
                        <img style="width: 54px" src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="logo" />
                    </div>
                    <h1 class="text-xl font-semibold">صندوق الزمالة</h1>
                </a>
            </div>
            <div class="flex items-center gap-4 text-[#124375] text-4xl">
                <a class="cursor-pointer">
                    <iconify-icon icon="ion:notifcations"></iconify-icon>
                </a>
                <a class="cursor-pointer">
                    <iconify-icon icon="boxicons:user-filled"></iconify-icon>
                </a>
            </div>
        </nav>
    </header>
    <!-- end header -->
