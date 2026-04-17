{{-- Sidebar --}}
<aside class="w-[260px] bg-sidebar border-l-[3px] border-primary flex flex-col justify-between py-6 px-4 sticky top-[64px] h-[calc(100vh-64px)] overflow-y-auto shrink-0 transition-all duration-300 z-[900] overflow-x-hidden" id="main-sidebar">
    <nav class="flex-1">
        <ul class="flex flex-col gap-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 py-3 px-[18px] rounded-2xl text-[15px] font-semibold text-primary bg-white border-[1.5px] border-slate-200 transition-all duration-250 hover:bg-[#193e6a0f] hover:border-primary hover:-translate-x-[3px] {{ request()->routeIs('dashboard') ? '!bg-primary !text-white !border-primary shadow-[0_4px_14px_rgba(25,62,106,0.3)] hover:!bg-primary-light' : '' }}" id="nav-home">
                    <i class="fa-solid fa-house text-[18px] w-6 text-center shrink-0"></i>
                    <span class="sidebar-label whitespace-nowrap">الصفحة الرئيسية</span>
                </a>
            </li>
            <li>
                <a href="{{ route('members.index') }}" class="flex items-center gap-3 py-3 px-[18px] rounded-2xl text-[15px] font-semibold text-primary bg-white border-[1.5px] border-slate-200 transition-all duration-250 hover:bg-[#193e6a0f] hover:border-primary hover:-translate-x-[3px] {{ request()->routeIs('members.*') ? '!bg-primary !text-white !border-primary shadow-[0_4px_14px_rgba(25,62,106,0.3)] hover:!bg-primary-light' : '' }}" id="nav-members">
                    <i class="fa-solid fa-users text-[18px] w-6 text-center shrink-0"></i>
                    <span class="sidebar-label whitespace-nowrap">الأعضاء</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 py-3 px-[18px] rounded-2xl text-[15px] font-semibold text-primary bg-white border-[1.5px] border-slate-200 transition-all duration-250 hover:bg-[#193e6a0f] hover:border-primary hover:-translate-x-[3px] {{ request()->routeIs('subscriptions.*') ? '!bg-primary !text-white !border-primary shadow-[0_4px_14px_rgba(25,62,106,0.3)] hover:!bg-primary-light' : '' }}" id="nav-subscriptions">
                    <i class="fa-solid fa-clipboard-list text-[18px] w-6 text-center shrink-0"></i>
                    <span class="sidebar-label whitespace-nowrap">الأشتراكات</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 py-3 px-[18px] rounded-2xl text-[15px] font-semibold text-primary bg-white border-[1.5px] border-slate-200 transition-all duration-250 hover:bg-[#193e6a0f] hover:border-primary hover:-translate-x-[3px] {{ request()->routeIs('loans.*') ? '!bg-primary !text-white !border-primary shadow-[0_4px_14px_rgba(25,62,106,0.3)] hover:!bg-primary-light' : '' }}" id="nav-loans">
                    <i class="fa-solid fa-money-bill-wave text-[18px] w-6 text-center shrink-0"></i>
                    <span class="sidebar-label whitespace-nowrap">القروض</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 py-3 px-[18px] rounded-2xl text-[15px] font-semibold text-primary bg-white border-[1.5px] border-slate-200 transition-all duration-250 hover:bg-[#193e6a0f] hover:border-primary hover:-translate-x-[3px] {{ request()->routeIs('finance.*') ? '!bg-primary !text-white !border-primary shadow-[0_4px_14px_rgba(25,62,106,0.3)] hover:!bg-primary-light' : '' }}" id="nav-finance">
                    <i class="fa-solid fa-chart-line text-[18px] w-6 text-center shrink-0"></i>
                    <span class="sidebar-label whitespace-nowrap">المالية</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 py-3 px-[18px] rounded-2xl text-[15px] font-semibold text-primary bg-white border-[1.5px] border-slate-200 transition-all duration-250 hover:bg-[#193e6a0f] hover:border-primary hover:-translate-x-[3px] {{ request()->routeIs('claims.*') ? '!bg-primary !text-white !border-primary shadow-[0_4px_14px_rgba(25,62,106,0.3)] hover:!bg-primary-light' : '' }}" id="nav-claims">
                    <i class="fa-solid fa-users-gear text-[18px] w-6 text-center shrink-0"></i>
                    <span class="sidebar-label whitespace-nowrap">المطالبات</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- Logout --}}
    <div class="pt-4 mt-4 border-t border-slate-200">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-3 py-3 px-[18px] rounded-2xl text-[15px] font-semibold text-primary bg-[#fff5f5] border-[1.5px] border-[rgba(220,53,69,0.2)] !text-red-600 transition-all duration-250 hover:border-red-600 hover:bg-[#fde8e8] w-full hover:-translate-x-[3px]" id="btn-logout">
                <i class="fa-solid fa-right-from-bracket text-[18px] w-6 text-center shrink-0 !text-red-600"></i>
                <span class="sidebar-label whitespace-nowrap">تسجيل خروج</span>
            </button>
        </form>
    </div>
</aside>

{{-- Sidebar overlay for mobile --}}
<div class="hidden fixed inset-0 bg-black/40 z-[899] backdrop-blur-sm opacity-0 transition-opacity duration-250" id="sidebar-overlay"></div>

{{-- Toggle script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('main-sidebar');
        const toggle = document.getElementById('btn-sidebar-toggle');
        const labels = sidebar.querySelectorAll('.sidebar-label');
        let isExpanded = true;

        function toggleSidebar() {
            isExpanded = !isExpanded;
            if (isExpanded) {
                sidebar.style.width = '260px';
                sidebar.style.padding = '24px 16px';
                labels.forEach(function(label) {
                    label.style.display = '';
                });
                // Restore link padding
                sidebar.querySelectorAll('a, button[id=btn-logout]').forEach(function(el) {
                    el.style.justifyContent = '';
                    el.style.padding = '';
                });
            } else {
                sidebar.style.width = '72px';
                sidebar.style.padding = '24px 8px';
                labels.forEach(function(label) {
                    label.style.display = 'none';
                });
                // Center icons
                sidebar.querySelectorAll('a, button[id=btn-logout]').forEach(function(el) {
                    el.style.justifyContent = 'center';
                    el.style.padding = '12px 0';
                });
            }
        }

        if (toggle) {
            toggle.addEventListener('click', toggleSidebar);
        }
    });
</script>
