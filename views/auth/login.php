<!-- views/auth/login.php -->
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

        @keyframes bounceShort {
            0% { transform: translate(-50%, -20px); opacity: 0; }
            100% { transform: translate(-50%, 0); opacity: 1; }
        }
        .animate-bounce-short { animation: bounceShort 0.4s ease-out forwards; }

        /* Membunuh ikon mata bawaan Microsoft Edge */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
</head>
<body class="bg-[#0F172A] min-h-screen flex items-center justify-center p-4 lg:p-8">

    <div class="bg-white w-full max-w-5xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        
        <!-- Kolom Kiri: Form Login -->
        <div class="w-full md:w-1/2 p-8 lg:p-14 flex flex-col justify-center">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-[#0F172A] mb-2">Masuk</h1>
                <p class="text-gray-500 text-sm">Selamat Datang Kembali</p>
            </div>

            <form action="/anvo/public/auth/proses_login" method="POST" class="space-y-6">
                
                <div>
                    <label class="text-xs text-gray-500 block mb-1.5 ml-1">No. Telp/Email</label>
                    <input type="text" name="login_id" placeholder="No. Telepon atau Email" required 
                           class="w-full border border-gray-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-[#0F172A]">
                </div>

                <div>
                    <label class="text-xs text-gray-500 block mb-1.5 ml-1">Password</label>
                    <div class="relative flex items-center">
                        <input type="password" name="password" id="login_password" placeholder="Masukkan Password" required 
                               class="w-full border border-gray-200 rounded-2xl pl-4 pr-12 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-[#0F172A]">
                        
                        <button type="button" onclick="togglePasswordVisibility('login_password', 'eye_icon_login')" class="absolute right-4 text-gray-400 hover:text-[#8C6239] transition-colors focus:outline-none">
                            <i id="eye_icon_login" class="fa-regular fa-eye-slash text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pl-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="ingat" class="w-4 h-4 text-[#8C6239] border-gray-300 rounded focus:ring-[#8C6239] cursor-pointer">
                        <label for="ingat" class="text-xs text-gray-500 cursor-pointer select-none">Ingat saya</label>
                    </div>
                    <a href="#" class="text-xs text-[#2B9BFB] font-medium hover:underline">Lupa password?</a>
                </div>

                <button type="submit" class="w-full bg-[#8C6239] hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] text-white py-4 rounded-2xl font-semibold transition-all shadow-lg hover:shadow-xl mt-4">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                Belum punya akun? <a href="/anvo/public/auth/register" class="text-[#2B9BFB] font-medium hover:underline">Daftar</a>
            </p>
        </div>

        <!-- Kolom Kanan: Gambar Background -->
        <div class="hidden md:block w-1/2 relative bg-[#0F172A]">
            <img src="/anvo/public/img/Eksterior Kereta Sawah Real 1.png" alt="ANVO Kereta" class="absolute inset-0 w-full h-full object-cover opacity-70 grayscale-[20%]">
            
            <div class="absolute inset-0 bg-gradient-to-t from-[#0F172A] via-[#0F172A]/40 to-transparent"></div>
            
            <div class="absolute top-10 left-10 flex items-center gap-3">
                <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="Logo" class="h-7 invert brightness-0">
            </div>
            
            <!-- PERUBAHAN: Teks Pemanis Khusus Halaman Login -->
            <div class="absolute bottom-12 left-10 right-10">
                <h3 class="text-white text-2xl font-bold mb-2">Lanjutkan Perjalanan Anda</h3>
                <p class="text-white/80 text-sm leading-relaxed">Akses kembali akun Anda untuk mengelola tiket, mengecek jadwal keberangkatan, dan menikmati layanan prioritas eksekutif dari ANVO.</p>
            </div>
        </div>
    </div>
    <!-- ========================================== -->
    <!-- FLASH MESSAGE (TOAST NOTIFICATION) AWAL  -->
    <!-- ========================================== -->

    <!-- Alert Error (Merah) -->
    <?php if (isset($_SESSION['error'])): ?>
        <div id="toast-alert" class="fixed top-8 left-1/2 -translate-x-1/2 z-[100] flex items-center w-full max-w-md p-4 text-gray-700 bg-white rounded-2xl shadow-2xl border-l-4 border-red-500 animate-bounce-short">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-50 rounded-xl">
                <i class="fa-solid fa-circle-exclamation text-xl"></i>
            </div>
            <div class="ml-4 text-sm font-medium leading-relaxed"><?= $_SESSION['error'] ?></div>
            <button type="button" onclick="document.getElementById('toast-alert').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Alert Success (Hijau) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div id="toast-alert" class="fixed top-8 left-1/2 -translate-x-1/2 z-[100] flex items-center w-full max-w-md p-4 text-gray-700 bg-white rounded-2xl shadow-2xl border-l-4 border-emerald-500 animate-bounce-short">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-emerald-500 bg-emerald-50 rounded-xl">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>
            <div class="ml-4 text-sm font-medium leading-relaxed"><?= $_SESSION['success'] ?></div>
            <button type="button" onclick="document.getElementById('toast-alert').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Auto-hide script (hilang dalam 5 detik) -->
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-alert');
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => toast.remove(), 500); // Hapus elemen dari DOM setelah animasi fade out selesai
            }
        }, 5000);

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                icon.classList.add('text-[#8C6239]');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                icon.classList.remove('text-[#8C6239]');
            }
        }
    </script>
    <!-- ========================================== -->
    <!-- FLASH MESSAGE (TOAST NOTIFICATION) AKHIR -->
    <!-- ========================================== -->
</body>
</html>