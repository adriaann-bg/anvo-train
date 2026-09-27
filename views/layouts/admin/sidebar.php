<!-- app/Views/layouts/admin/sidebar.php -->

<!-- Mobile Backdrop Overlay -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 z-20 hidden md:hidden transition-opacity" onclick="toggleMobileSidebar()"></div>

<!-- SIDEBAR NAVIGATION -->
<aside id="sidebar" class="fixed md:sticky top-0 h-screen w-64 bg-white border-r border-slate-200/60 flex flex-col justify-between sidebar-transition z-30 select-none -translate-x-full md:translate-x-0 shadow-xl md:shadow-none">
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
            <!-- Tombol Sembunyikan Menu (Desktop Only) -->
            <button onclick="toggleSidebar()" class="hidden md:flex w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 items-center justify-center transition-all border border-slate-200/60" title="Sembunyikan Menu">
                <i id="collapse-icon" class="fa-solid fa-chevron-left text-xs transition-transform duration-300"></i>
            </button>
            <!-- Tombol Tutup Sidebar (Mobile Only) -->
            <button onclick="toggleMobileSidebar()" class="md:hidden w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-all border border-slate-200/60">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Menu List (Dengan Prefix /anvo/public/admin/) -->
        <nav class="p-3 space-y-1.5 overflow-y-auto max-h-[calc(100vh-160px)] custom-scrollbar">
            <a href="/anvo/public/admin" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= (($data['active_menu'] ?? '') == 'dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                <i class="fa-solid fa-chart-pie w-5 text-center <?= (($data['active_menu'] ?? '') == 'dashboard') ? '' : 'text-slate-400' ?> text-base"></i>
                <span class="menu-label whitespace-nowrap">Dashboard</span>
            </a>
            
            <a href="/anvo/public/admin/jadwal" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= (($data['active_menu'] ?? '') == 'jadwal') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                <i class="fa-solid fa-calendar-days w-5 text-center <?= (($data['active_menu'] ?? '') == 'jadwal') ? '' : 'text-slate-400' ?> text-base"></i>
                <span class="menu-label whitespace-nowrap">Kelola Jadwal</span>
            </a>
            
            <a href="/anvo/public/admin/route" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= (($data['active_menu'] ?? '') == 'route') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                <i class="fa-solid fa-route w-5 text-center <?= (($data['active_menu'] ?? '') == 'route') ? '' : 'text-slate-400' ?> text-base"></i>
                <span class="menu-label whitespace-nowrap">Kelola Rute Koridor</span>
            </a>
            
            <a href="/anvo/public/admin/armada" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= (($data['active_menu'] ?? '') == 'armada') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                <i class="fa-solid fa-train w-5 text-center <?= (($data['active_menu'] ?? '') == 'armada') ? '' : 'text-slate-400' ?> text-base"></i>
                <span class="menu-label whitespace-nowrap">Armada Kereta</span>
            </a>
            
            <a href="/anvo/public/admin/stasiun" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= (($data['active_menu'] ?? '') == 'stasiun') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                <i class="fa-solid fa-map-location-dot w-5 text-center <?= (($data['active_menu'] ?? '') == 'stasiun') ? '' : 'text-slate-400' ?> text-base"></i>
                <span class="menu-label whitespace-nowrap">Data Stasiun</span>
            </a>

            <!-- Master User -->
            <a href="/anvo/public/admin/user" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= (($data['active_menu'] ?? '') == 'user') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                <i class="fa-solid fa-users w-5 text-center <?= (($data['active_menu'] ?? '') == 'user') ? '' : 'text-slate-400' ?> text-base"></i>
                <span class="menu-label whitespace-nowrap">Master User</span>
            </a>

            <!-- Master Kru -->
            <a href="/anvo/public/admin/kru" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold <?= (($data['active_menu'] ?? '') == 'kru') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' ?> transition-all group relative">
                <i class="fa-solid fa-user-tie w-5 text-center <?= (($data['active_menu'] ?? '') == 'kru') ? '' : 'text-slate-400' ?> text-base"></i>
                <span class="menu-label whitespace-nowrap">Master Kru</span>
            </a>
        </nav>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-3 border-t border-slate-100 bg-white">
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

<!-- Skrip Pengendali Sidebar -->
<script>
    let isCollapsed = false;

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const brandContainer = document.getElementById('brand-container');
        const menuLabels = document.querySelectorAll('.menu-label');
        const userInfo = document.querySelector('.user-info');
        const collapseIcon = document.getElementById('collapse-icon');

        isCollapsed = !isCollapsed;

        if (isCollapsed) {
            sidebar.style.width = '80px';
            brandContainer.style.opacity = '0';
            setTimeout(() => brandContainer.style.display = 'none', 150);
            menuLabels.forEach(label => label.style.display = 'none');
            userInfo.style.display = 'none';
            collapseIcon.classList.remove('fa-chevron-left');
            collapseIcon.classList.add('fa-chevron-right');
        } else {
            sidebar.style.width = '256px';
            brandContainer.style.display = 'flex';
            setTimeout(() => brandContainer.style.opacity = '1', 50);
            menuLabels.forEach(label => label.style.display = 'inline');
            userInfo.style.display = 'block';
            collapseIcon.classList.remove('fa-chevron-right');
            collapseIcon.classList.add('fa-chevron-left');
        }
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>