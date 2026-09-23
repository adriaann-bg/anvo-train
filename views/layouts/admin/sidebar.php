<!-- SIDEBAR NAVIGATION -->
    <aside id="sidebar" class="w-64 bg-white border-r border-slate-200/60 flex flex-col justify-between sidebar-transition sticky top-0 h-screen z-30 select-none">
        <div>
            <!-- Header Sidebar & Tombol Collapse -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
                <div id="brand-container" class="flex items-center gap-3 overflow-hidden transition-all duration-300">
                    <div class="w-10 h-10 min-w-[40px] rounded-2xl bg-[#0F172A] text-white flex items-center justify-center font-bold tracking-wider shadow-md">
                        AV
                    </div>
                    <div class="brand-text whitespace-nowrap">
                        <span class="font-extrabold text-[#0F172A] text-lg tracking-tight block leading-none">ANVO</span>
                        <span class="text-[10px] uppercase font-bold text-[#8C6239] tracking-wider mt-1 block">Backoffice</span>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-all border border-slate-200/60" title="Sembunyikan Menu">
                    <i id="collapse-icon" class="fa-solid fa-chevron-left text-xs transition-transform duration-300"></i>
                </button>
            </div>

            <!-- Menu List -->
            <nav class="p-3 space-y-1.5">
                <a href="/anvo/public/admin" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= ($data['active_menu'] == 'dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                    <i class="fa-solid fa-chart-pie w-5 text-center <?= ($data['active_menu'] == 'dashboard') ? '' : 'text-slate-400' ?> text-base"></i>
                    <span class="menu-label whitespace-nowrap">Dashboard</span>
                </a>
                <a href="/anvo/public/jadwal" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= ($data['active_menu'] == 'jadwal') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                    <i class="fa-solid fa-calendar-days w-5 text-center <?= ($data['active_menu'] == 'jadwal') ? '' : 'text-slate-400' ?> text-base"></i>
                    <span class="menu-label whitespace-nowrap">Kelola Jadwal</span>
                </a>
                <a href="/anvo/public/route" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= ($data['active_menu'] == 'route') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                    <i class="fa-solid fa-route w-5 text-center <?= ($data['active_menu'] == 'route') ? '' : 'text-slate-400' ?> text-base"></i>
                    <span class="menu-label whitespace-nowrap">Kelola Rute Koridor</span>
                </a>
                <a href="/anvo/public/armada" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= ($data['active_menu'] == 'armada') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                    <i class="fa-solid fa-train w-5 text-center <?= ($data['active_menu'] == 'armada') ? '' : 'text-slate-400' ?> text-base"></i>
                    <span class="menu-label whitespace-nowrap">Armada Kereta</span>
                </a>
                <a href="#" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= ($data['active_menu'] == 'stasiun') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                    <i class="fa-solid fa-map-location-dot w-5 text-center <?= ($data['active_menu'] == 'stasiun') ? '' : 'text-slate-400' ?> text-base"></i>
                    <span class="menu-label whitespace-nowrap">Data Stasiun</span>
                </a>

                <!-- Master User -->
                <a href="/anvo/public/user" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= ($data['active_menu'] == 'user') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                    <i class="fa-solid fa-users w-5 text-center <?= ($data['active_menu'] == 'user') ? '' : 'text-slate-400' ?> text-base"></i>
                    <span class="menu-label whitespace-nowrap">Master User</span>
                </a>

                <!-- Master Kru -->
                <a href="/anvo/public/kru" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= ($data['active_menu'] == 'kru') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                    <i class="fa-solid fa-user-tie w-5 text-center <?= ($data['active_menu'] == 'kru') ? '' : 'text-slate-400' ?> text-base"></i>
                    <span class="menu-label whitespace-nowrap">Master Kru</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-3 border-t border-slate-100">
            <div class="bg-slate-50 p-3 rounded-2xl flex items-center justify-between border border-slate-100 overflow-hidden">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 min-w-[36px] rounded-xl bg-[#8C6239] text-white flex items-center justify-center font-bold text-xs">
                        AD
                    </div>
                    <div class="user-info whitespace-nowrap">
                        <span class="text-xs font-bold text-[#0F172A] block">Administrator</span>
                        <span class="text-[10px] text-slate-400">Super Admin</span>
                    </div>
                </div>
                <a href="/anvo/public/auth/logout" onclick="return confirm('Keluar dari sistem?')" class="text-slate-400 hover:text-rose-500 transition-colors p-1.5" title="Logout">
                    <i class="fa-solid fa-power-off text-sm"></i>
                </a>
            </div>
        </div>
    </aside>