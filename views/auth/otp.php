<!-- views/auth/otp.php -->
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
        
        /* Menghilangkan panah spinner pada input number di berbagai browser */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield; /* Untuk Firefox */
        }
    </style>
</head>
<body class="bg-[#0F172A] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl p-8 md:p-12 text-center">
        
        <div class="w-20 h-20 bg-orange-50 text-[#8C6239] rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-envelope-open-text text-3xl"></i>
        </div>
        
        <h1 class="text-3xl font-bold text-[#0F172A] mb-2">Verifikasi Email</h1>
        <p class="text-gray-500 text-sm mb-8">Kami telah mengirimkan 6 digit kode OTP ke email <br><span class="font-bold text-[#0F172A]"><?= $_SESSION['recovery_data']['email'] ?></span></p>

        <form action="/anvo/public/auth/proses_otp" method="POST">
            <!-- 6 Kotak Input OTP -->
            <div class="flex justify-center gap-2 md:gap-3 mb-8" id="otp-container">
                <?php for($i=1; $i<=6; $i++): ?>
                    <input type="number" name="otp[]" maxlength="1" required
                           class="w-12 h-14 md:w-14 md:h-16 text-center text-2xl font-bold text-[#0F172A] bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all"
                           onkeyup="focusNext(this, <?= $i ?>)">
                <?php endfor; ?>
            </div>

            <button type="submit" class="w-full bg-[#8C6239] hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] text-white py-4 rounded-2xl font-semibold transition-all shadow-lg">
                Verifikasi & Lanjutkan
            </button>
        </form>

        <p class="text-sm text-gray-500 mt-8">
            Belum menerima kode? <a href="/anvo/public/auth/reset_password" class="text-[#2B9BFB] font-medium hover:underline">Kirim Ulang</a>
        </p>
    </div>

    <!-- FLASH MESSAGE / NOTIFIKASI MELAYANG -->
    <?php if (isset($_SESSION['error'])): ?>
        <div id="toast-alert" class="fixed top-8 left-1/2 -translate-x-1/2 z-[100] flex items-center w-full max-w-md p-4 text-gray-700 bg-white rounded-2xl shadow-2xl border-l-4 border-red-500 animate-bounce-short">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-50 rounded-xl">
                <i class="fa-solid fa-circle-xmark text-xl"></i>
            </div>
            <div class="ml-4 text-sm font-medium leading-relaxed"><?= $_SESSION['error'] ?></div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <!-- Overlay Gelap (Mencegah user klik apapun) -->
        <div class="fixed inset-0 bg-slate-900/40 z-40 backdrop-blur-sm"></div>
        
        <!-- Toast Sukses Melayang -->
        <div id="toast-success" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[100] flex flex-col items-center w-full max-w-sm p-8 bg-white rounded-[2rem] shadow-2xl scale-0 transition-transform duration-500 ease-out">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-check text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-[#0F172A] mb-2">Verifikasi Berhasil!</h3>
            <p class="text-sm text-gray-500 text-center leading-relaxed"><?= $_SESSION['success'] ?></p>
        </div>
        <?php unset($_SESSION['success']); ?>

        <script>
            // Tampilkan animasi pop-up skala membesar
            setTimeout(() => {
                document.getElementById('toast-success').classList.replace('scale-0', 'scale-100');
            }, 100);

            // Jeda 2,5 detik lalu pindah halaman ke Buat Password Baru
            setTimeout(() => {
                window.location.href = '/anvo/public/auth/password_baru';
            }, 2500);
        </script>
    <?php endif; ?>

    <script>
        <!-- Script agar saat mengetik 1 angka, kursor otomatis pindah ke kotak sebelahnya -->
        function focusNext(elem, index) {
            if (elem.value.length > 1) { elem.value = elem.value.slice(0,1); } // Batasi 1 digit
            if (elem.value !== '' && index < 6) {
                elem.nextElementSibling.focus();
            } else if (elem.value === '' && index > 1 && event.key === 'Backspace') {
                elem.previousElementSibling.focus();
            }
        }
        // Logika auto-focus input OTP 
        function focusNext(elem, index) {
            if (elem.value.length > 1) { elem.value = elem.value.slice(0,1); }
            if (elem.value !== '' && index < 6) {
                elem.nextElementSibling.focus();
            } else if (elem.value === '' && index > 1 && event.key === 'Backspace') {
                elem.previousElementSibling.focus();
            }
        }

        // Hapus otomatis toast error setelah 4 detik
        setTimeout(() => {
            const errorToast = document.getElementById('toast-alert');
            if (errorToast) {
                errorToast.style.opacity = '0';
                setTimeout(() => errorToast.remove(), 500);
            }
        }, 4000);
    </script>
</body>
</html>