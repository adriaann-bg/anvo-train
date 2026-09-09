<!-- views/layouts/header.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? $judul : 'ANVO'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">

    <!-- Navbar Desktop & Mobile Header -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-transparent border-b border-transparent h-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex justify-between h-full items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO Logo" class="h-8 w-auto">
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 h-full items-center">
                    <!-- Menu Aktif -->
                    <a href="/anvo/public/" class="relative h-full flex items-center text-[#8C6239] font-medium group">
                        Beranda
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-[4px] bg-[#8C6239] rounded-t-[16px] scale-x-100 origin-center transition-transform duration-300"></span>
                    </a>
                    <!-- Menu Inaktif -->
                    <a href="/anvo/public/jadwal" class="relative h-full flex items-center text-[#0F172A] font-normal hover:text-[#8C6239] transition-colors group">
                        Beli Tiket
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-[4px] bg-[#8C6239] rounded-t-[16px] scale-x-0 group-hover:scale-x-100 origin-center transition-transform duration-300"></span>
                    </a>
                </div>
                
                <!-- Desktop Auth Button -->
                <div class="hidden md:flex items-center">
                    <a href="/anvo/public/auth/login" class="bg-[#8C6239] hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] text-white px-6 py-2.5 rounded-full font-medium transition-all duration-300 shadow-md">
                        Mulai
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-[#0F172A] focus:outline-none">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

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
    </script>