<!-- views/auth/lupa.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Animasi Toast */
        @keyframes bounceShort {
            0% { transform: translate(-50%, -20px); opacity: 0; }
            100% { transform: translate(-50%, 0); opacity: 1; }
        }
        .animate-bounce-short { animation: bounceShort 0.4s ease-out forwards; }
    </style>
</head>
<body class="bg-[#0F172A] min-h-screen flex items-center justify-center p-4 lg:p-8">

    <div class="bg-white w-full max-w-5xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        
        <!-- Kolom Kiri: Form Lupa Akun -->
        <div class="w-full md:w-1/2 p-8 lg:p-14 flex flex-col justify-center relative">
            
            <!-- Tombol Kembali -->
            <a href="/anvo/public/auth/login" class="absolute top-8 left-8 text-gray-400 hover:text-[#8C6239] transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>

            <div class="text-center mb-10 mt-6">
                <div class="w-16 h-16 bg-blue-50 text-[#2B9BFB] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-[#0F172A] mb-2">Pemulihan Akun</h1>
                <p class="text-gray-500 text-sm leading-relaxed px-4">Masukkan 3 lapis data keamanan di bawah ini untuk memverifikasi kepemilikan akun Anda.</p>
            </div>

            <form action="/anvo/public/auth/proses_lupa" method="POST" class="space-y-5">
                
                <div>
                    <label class="text-xs text-gray-500 block mb-1.5 ml-1">NIK (Sesuai KTP)</label>
                    <input type="number" name="nik" placeholder="Masukkan 16 Digit NIK" required 
                           class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-[#0F172A]">
                </div>

                <div>
                    <label class="text-xs text-gray-500 block mb-1.5 ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan Nama Sesuai KTP" required 
                           class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-[#0F172A]">
                </div>

                <div>
                    <label class="text-xs text-gray-500 block mb-1.5 ml-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" required 
                           class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-gray-500">
                </div>

                <button type="submit" class="w-full bg-[#0F172A] hover:bg-gray-800 text-white py-4 rounded-2xl font-semibold transition-all shadow-lg hover:shadow-xl mt-6">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari Akun Saya
                </button>
            </form>

            <!-- Jalur Manual: Customer Care WhatsApp -->
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-500 mb-3">Kehilangan akses ke semua data Anda?</p>
                <!-- Nanti href-nya bisa diisi dengan link wa.me yang terintegrasi WhatsApp Business Quick Replies -->
                <a href="#" class="inline-flex items-center justify-center gap-2 text-sm text-[#25D366] bg-[#25D366]/10 hover:bg-[#25D366]/20 px-6 py-2.5 rounded-full font-semibold transition-colors">
                    <i class="fa-brands fa-whatsapp text-lg"></i> Hubungi Customer Care
                </a>
            </div>
        </div>

        <!-- Kolom Kanan: Gambar Background -->
        <div class="hidden md:block w-1/2 relative bg-[#0F172A]">
            <img src="/anvo/public/img/Eksterior Kereta Sawah Real 1.png" alt="ANVO Kereta" class="absolute inset-0 w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0F172A] via-[#0F172A]/60 to-transparent"></div>
            
            <div class="absolute top-10 left-10 flex items-center gap-3">
                <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="Logo" class="h-7 invert brightness-0">
            </div>
            
            <div class="absolute bottom-12 left-10 right-10">
                <h3 class="text-white text-2xl font-bold mb-2">Privasi Anda, Prioritas Kami</h3>
                <p class="text-white/80 text-sm leading-relaxed">Sistem keamanan berlapis kami memastikan bahwa hanya Anda yang memiliki kendali penuh atas tiket dan perjalanan eksekutif Anda.</p>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if (isset($_SESSION['error'])): ?>
        <div id="toast-alert" class="fixed top-8 left-1/2 -translate-x-1/2 z-[100] flex items-center w-full max-w-md p-4 text-gray-700 bg-white rounded-2xl shadow-2xl border-l-4 border-red-500 animate-bounce-short">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-50 rounded-xl">
                <i class="fa-solid fa-circle-exclamation text-xl"></i>
            </div>
            <div class="ml-4 text-sm font-medium leading-relaxed"><?= $_SESSION['error'] ?></div>
            <button type="button" onclick="document.getElementById('toast-alert').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 hover:bg-gray-100 h-8 w-8 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-alert');
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>