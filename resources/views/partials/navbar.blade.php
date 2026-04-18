{{-- Breadcrumb (Hidden on desktop in original design unless yield, keeping it simple or omitting if not used, but let's keep it styled) --}}
<div class="flex items-center gap-1.5 px-5 pt-2 text-[12px] text-slate-400 font-medium tracking-wide">
    @hasSection('breadcrumb')
        <span class="text-[10px] opacity-60"><i class="fa-solid fa-chevron-left"></i></span>
        <span class="text-slate-500">@yield('breadcrumb')</span>
    @endif
</div>

{{-- Navbar --}}
<nav class="sticky top-0 z-[1000] bg-navbar border-b-[2.5px] border-[#193e6a] px-5" id="main-navbar">
    <div class="flex items-center justify-between h-[64px] max-w-full">

        {{-- Left side: User actions --}}
        <div class="flex items-center gap-1.5 order-2">
            {{-- Profile --}}
            <button class="relative flex items-center justify-center w-10 h-10 rounded-md text-primary text-[20px] transition-all duration-150 hover:bg-[#193e6a0f] hover:text-primary-light" id="btn-profile" title="الملف الشخصي">
                <i class="fa-solid fa-user"></i>
            </button>

            {{-- Notifications --}}
            <button class="relative flex items-center justify-center w-10 h-10 rounded-md text-primary text-[20px] transition-all duration-150 hover:bg-[#193e6a0f] hover:text-primary-light" id="btn-notifications" title="الإشعارات">
                <i class="fa-solid fa-bell"></i>
                <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-navbar animate-[pulse_2s_infinite]"></span>
            </button>
        </div>

        {{-- Right side: Brand --}}
        <div class="flex items-center gap-3 order-1">
            @hasSection('navbar_back_url')
                <a href="@yield('navbar_back_url')" class="inline-flex h-10 items-center gap-2 rounded-md bg-primary px-4 text-[14px] font-bold text-white transition-all duration-150 hover:bg-primary-light" title="رجوع">
                    <i class="fa-solid fa-chevron-right text-[12px]"></i>
                    <span>رجوع</span>
                </a>
            @else
                {{-- Hamburger (to the right of the logo in RTL) --}}
                <button class="flex items-center justify-center w-10 h-10 rounded-md text-primary text-[22px] transition-all duration-150 hover:bg-[#193e6a0f] hover:text-primary-light" id="btn-sidebar-toggle" title="القائمة">
                    <i class="fa-solid fa-bars"></i>
                </button>
            @endif
            {{-- Logo + Title --}}
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('IMGs/Hu Logo 1.png') }}" alt="شعار جامعة العاصمة" class="w-[42px] h-[42px] object-contain rounded">
                <span class="text-[17px] font-bold text-primary whitespace-nowrap tracking-tight">صندوق الزمالة</span>
            </div>

        </div>

    </div>
</nav>
