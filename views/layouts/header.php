<!-- views/layouts/header.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? $judul : 'ANVO'; ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">

    <!-- Navbar Desktop & Mobile Header -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-transparent border-b border-transparent h-20">

        <!-- PERUBAHAN: Hapus max-w-7xl mx-auto, ganti dengan w-full dan atur padding responsif -->
        <div class="w-full h-full relative flex justify-between items-center px-4 sm:px-8 lg:px-12 xl:px-16">

            <!-- Kiri: Logo -->
            <div class="flex-shrink-0 flex items-center z-10">
                <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO Logo" class="h-8 w-auto">
            </div>

            <!-- Tengah: Desktop Menu (Posisi absolut di tengah layar tetap aman) -->
            <div class="hidden md:flex space-x-10 h-full items-center absolute left-1/2 -translate-x-1/2 z-0">

                <a href="/anvo/public/" class="relative h-full flex items-center text-[#8C6239] font-medium group">
                    Beranda
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-[4px] bg-[#8C6239] rounded-t-[16px] scale-x-100 origin-center transition-transform duration-300"></span>
                </a>

                <a href="/anvo/public/jadwal" class="relative h-full flex items-center text-[#0F172A] font-normal hover:text-[#8C6239] transition-colors group">
                    Beli Tiket
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-[4px] bg-[#8C6239] rounded-t-[16px] scale-x-0 group-hover:scale-x-100 origin-center transition-transform duration-300"></span>
                </a>

                <?php if(isset($_SESSION['user_id'])): ?>
                <a href="/anvo/public/tiket" class="relative h-full flex items-center text-[#0F172A] font-normal hover:text-[#8C6239] transition-colors group">
                    Tiket Saya
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-[4px] bg-[#8C6239] rounded-t-[16px] scale-x-0 group-hover:scale-x-100 origin-center transition-transform duration-300"></span>
                </a>
                <?php endif; ?>
            </div>

        <!-- Kanan: Desktop Auth Button & Profile -->
            <div class="hidden md:flex items-center z-20">
                <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- TAMPILAN JIKA SUDAH LOGIN: Tombol Profil -->
                    <div class="relative" id="profile-dropdown-wrapper">
                        <button onclick="toggleProfileMenu()" class="flex items-center gap-3 border border-slate-400 rounded-[32px] py-1.5 pl-1.5 pr-4 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8C6239]/30">
                            <!-- Avatar Placeholder (Bisa diganti foto asli dari database nantinya) -->
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_nama']) ?>&background=2B9BFB&color=fff&rounded=true" alt="Avatar" class="w-9 h-9 rounded-full object-cover">

                            <div class="text-left hidden lg:block">
                                <p class="text-[13px] font-semibold text-[#0F172A] leading-tight truncate max-w-[140px]"><?= htmlspecialchars($_SESSION['user_nama']) ?></p>
                                <p class="text-[11px] text-gray-500 leading-tight truncate max-w-[140px]"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
                            </div>
                            <i class="fa-solid fa-chevron-down text-gray-600 text-[10px] ml-1"></i>
                        </button>

                        <!-- Isi Dropdown Menu -->
                        <div id="profile-menu" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden transform transition-all">
                            <div class="p-2">
                                <a href="/anvo/public/profil" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#8C6239] rounded-xl transition-colors">
                                    <i class="fa-regular fa-user w-5 text-center mr-2"></i> Profil Saya
                                </a>
                                <a href="/anvo/public/pengaturan" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#8C6239] rounded-xl transition-colors">
                                    <i class="fa-solid fa-gear w-5 text-center mr-2"></i> Pengaturan
                                </a>
                            </div>
                            <hr class="border-gray-100">
                            <div class="p-2">
                                <a href="/anvo/public/auth/logout" onclick="confirmLogout(event)" class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center mr-2"></i> Keluar
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <!-- TAMPILAN JIKA BELUM LOGIN: Tombol Mulai -->
                    <a href="/anvo/public/auth/login" class="bg-[#8C6239] hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] text-white px-7 py-2.5 rounded-full font-medium transition-all duration-300 shadow-md">
                        Mulai
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="md:hidden flex items-center z-10">
                <button id="mobile-menu-btn" class="text-[#0F172A] focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Full-Screen Popup Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-[#1E293B] z-[60] transform translate-x-full transition-transform duration-300 flex flex-col justify-center items-center space-y-5 hidden">
        <button id="close-menu-btn" class="absolute top-6 right-6 text-white focus:outline-none hover:rotate-90 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- JIKA SUDAH LOGIN: Tampilkan Info Profil di Mobile -->
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="flex flex-col items-center mb-4">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_nama']) ?>&background=2B9BFB&color=fff&rounded=true" alt="Avatar" class="w-20 h-20 rounded-full border-2 border-[#8C6239] shadow-lg mb-3">
                <h3 class="text-white text-xl font-bold tracking-wide"><?= htmlspecialchars($_SESSION['user_nama']) ?></h3>
                <p class="text-slate-400 text-sm"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
            </div>
            <!-- Garis pembatas -->
            <div class="w-16 h-px bg-slate-700 my-2"></div>
        <?php endif; ?>

        <!-- Menu Utama -->
        <a href="/anvo/public/" class="text-white text-lg font-medium">Beranda</a>
        <a href="/anvo/public/jadwal" class="text-slate-400 text-lg hover:text-white transition-colors">Beli Tiket</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- Menu Khusus Member -->
            <a href="/anvo/public/tiket" class="text-slate-400 text-lg hover:text-white transition-colors">Tiket Saya</a>
            <a href="/anvo/public/profil" class="text-slate-400 text-lg hover:text-white transition-colors">Profil Saya</a>
            <a href="/anvo/public/pengaturan" class="text-slate-400 text-lg hover:text-white transition-colors">Pengaturan</a>
            
            <!-- Tombol Keluar Mobile -->
            <a href="/anvo/public/auth/logout" class="bg-red-500/10 text-red-400 border border-red-500/30 px-10 py-3 rounded-full text-base font-semibold mt-6 hover:bg-red-500 hover:text-white transition-all">Keluar</a>
        <?php else: ?>
            <!-- Tombol Mulai Mobile -->
            <a href="/anvo/public/auth/login" class="bg-gradient-to-r from-[#8C6239] to-[#AF8B69] text-white px-10 py-3 rounded-full text-lg font-medium mt-6 shadow-lg">Mulai</a>
        <?php endif; ?>
    </div>

    <!-- Mobile Full-Screen Popup Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-[#1E293B] z-[60] transform translate-x-full transition-transform duration-300 flex flex-col justify-center items-center space-y-8 hidden">
        <button id="close-menu-btn" class="absolute top-6 right-6 text-white focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <a href="/anvo/public/" class="text-white text-2xl font-medium">Beranda</a>
        <a href="/anvo/public/jadwal" class="text-slate-400 text-2xl hover:text-white transition-colors">Beli Tiket</a>
        <a href="/anvo/public/tiket" class="text-slate-400 text-2xl hover:text-white transition-colors">Tiket Saya</a>
        <a href="/anvo/public/auth/login" class="bg-gradient-to-r from-[#8C6239] to-[#AF8B69] text-white px-8 py-3 rounded-full text-xl mt-4">Mulai</a>
    </div>

    <!-- Mobile Full-Screen Popup Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-[#1E293B] z-[60] transform translate-x-full transition-transform duration-300 flex flex-col justify-center items-center space-y-8 hidden">
        <button id="close-menu-btn" class="absolute top-6 right-6 text-white focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <a href="/anvo/public/" class="text-white text-2xl font-medium">Beranda</a>
        <a href="/anvo/public/jadwal" class="text-slate-400 text-2xl hover:text-white transition-colors">Beli Tiket</a>
        <a href="/anvo/public/auth/login" class="bg-gradient-to-r from-[#8C6239] to-[#AF8B69] text-white px-8 py-3 rounded-full text-xl mt-4">Mulai</a>
    </div>

    <main class="flex-grow">

    <?php if (isset($_SESSION['success'])): ?>
        <div id="toast-success" class="fixed top-8 left-1/2 -translate-x-1/2 z-[100] flex items-center w-full max-w-md p-4 text-gray-700 bg-white rounded-2xl shadow-2xl border-l-4 border-emerald-500 transition-all duration-500">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-emerald-500 bg-emerald-50 rounded-xl">
                <i class="fa-solid fa-check-circle text-xl"></i>
            </div>
            <div class="ml-4 text-sm font-medium leading-relaxed"><?= $_SESSION['success'] ?></div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-success');
                if(toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translate(-50%, -20px)';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 3000);
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <!-- Script Vanilla JS untuk Navbar Behavior -->
    <script>
        // Logika Scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.replace('bg-transparent', 'bg-[#FAFAFA]');
                navbar.classList.add('shadow-sm', 'border-gray-200');
                navbar.classList.replace('border-transparent', 'border-gray-200');
            } else {
                navbar.classList.replace('bg-[#FAFAFA]', 'bg-transparent');
                navbar.classList.remove('shadow-sm');
                navbar.classList.replace('border-gray-200', 'border-transparent');
            }
        });

        // Logika Mobile Menu
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const closeBtn = document.getElementById('close-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => mobileMenu.classList.remove('translate-x-full'), 10);
        });

        closeBtn.addEventListener('click', () => {
            mobileMenu.classList.add('translate-x-full');
            setTimeout(() => mobileMenu.classList.add('hidden'), 300);
        });

        function toggleProfileMenu() {
            document.getElementById('profile-menu').classList.toggle('hidden');
        }
        // Menutup dropdown profil jika klik di luar
        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('profile-dropdown-wrapper');
            if(wrapper && !wrapper.contains(e.target)) {
                document.getElementById('profile-menu').classList.add('hidden');
            }
        });

        function confirmLogout(e) {
            e.preventDefault(); // Mencegah klik langsung pindah halaman
            if (confirm("Apakah Anda yakin ingin keluar dari akun ANVO?")) {
                window.location.href = "/anvo/public/auth/logout";
            }
        }
    </script>