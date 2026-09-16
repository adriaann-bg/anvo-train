<!-- views/auth/password_baru.php -->
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
        input[type="password"]::-ms-reveal, input[type="password"]::-ms-clear { display: none; }
    </style>
</head>
<body class="bg-[#0F172A] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl p-8 md:p-10">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-[#2B9BFB] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-unlock-keyhole text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-[#0F172A] mb-2">Buat Password Baru</h1>
            <p class="text-gray-500 text-sm">Silakan buat kata sandi baru yang kuat untuk mengamankan akun ANVO Anda.</p>
        </div>

        <form action="/anvo/public/auth/simpan_password_baru" method="POST" class="space-y-5">
            
            <!-- Input Password Baru -->
            <div>
                <label class="text-xs text-gray-500 block mb-1.5 ml-1">Password Baru</label>
                <div class="relative flex items-center">
                    <input type="password" name="password" id="password" placeholder="Masukkan Password Baru" required 
                           oninput="checkPasswordStrength(this.value)"
                           class="w-full border border-gray-200 rounded-2xl pl-4 pr-12 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-[#0F172A]">
                    <button type="button" onclick="togglePasswordVisibility('password', 'eye_icon_1')" class="absolute right-4 text-gray-400 hover:text-[#8C6239] transition-colors focus:outline-none">
                        <i id="eye_icon_1" class="fa-regular fa-eye-slash text-sm"></i>
                    </button>
                </div>
                
                <!-- Indikator Kekuatan -->
                <div class="mt-2.5 flex items-center gap-2 px-1">
                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div id="strength-bar" class="h-full w-0 transition-all duration-300"></div>
                    </div>
                </div>
                <ul class="mt-2 text-[10px] text-gray-400 space-y-1.5 px-1">
                    <li id="req-length" class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-[5px]"></i> Minimal 8 karakter</li>
                    <li id="req-case" class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-[5px]"></i> Huruf besar & kecil</li>
                    <li id="req-special" class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-[5px]"></i> Karakter khusus (!@#$%)</li>
                </ul>
            </div>

            <!-- Konfirmasi Password -->
                    <div>
                        <label class="text-xs text-gray-500 block mb-1.5 ml-1">Konfirmasi Password</label>
                        <div class="relative flex items-center">
                            <!-- id="password_confirm" -->
                            <input type="password" name="password_confirm" id="password_confirm" oninput="checkMatch()" placeholder="Konfirmasi Password" required class="w-full border border-gray-200 rounded-2xl pl-4 pr-12 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-[#0F172A]">
                            
                            <!-- Toggle untuk id="password_confirm" dan id="eye_icon_2" -->
                            <button type="button" onclick="togglePasswordVisibility('password_confirm', 'eye_icon_2')" class="absolute right-4 text-gray-400 hover:text-[#8C6239] transition-colors focus:outline-none">
                                <i id="eye_icon_2" class="fa-regular fa-eye-slash text-sm"></i>
                            </button>
                        </div>
                        <!-- Teks peringatan yang disembunyikan secara default -->
                        <p id="match-warning" class="text-red-500 text-[10px] mt-1 ml-1 hidden"><i class="fa-solid fa-triangle-exclamation"></i> Password tidak sama!</p>
                    </div>

            <button type="submit" id="btn-submit" disabled class="w-full bg-[#8C6239] text-white py-4 rounded-2xl font-semibold transition-all shadow-lg opacity-50 cursor-not-allowed mt-4">
                Simpan Password
            </button>
        </form>
    </div>

    <!-- Script Mata & Kekuatan Password -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text'; icon.classList.replace('fa-eye-slash', 'fa-eye'); icon.classList.add('text-[#8C6239]');
            } else {
                input.type = 'password'; icon.classList.replace('fa-eye', 'fa-eye-slash'); icon.classList.remove('text-[#8C6239]');
            }
        }

        // Fungsi Indikator Kekuatan & Validasi Menyeluruh
        function checkPasswordStrength(password) {
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            const reqLength = document.getElementById('req-length');
            const reqCase = document.getElementById('req-case');
            const reqSpecial = document.getElementById('req-special');

            let strength = 0;

            if (password.length >= 8) {
                strength += 1;
                reqLength.classList.replace('text-gray-400', 'text-emerald-500');
            } else {
                reqLength.classList.replace('text-emerald-500', 'text-gray-400');
            }

            if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                strength += 1;
                reqCase.classList.replace('text-gray-400', 'text-emerald-500');
            } else {
                reqCase.classList.replace('text-emerald-500', 'text-gray-400');
            }

            if (password.match(/[^a-zA-Z\d]/)) {
                strength += 1;
                reqSpecial.classList.replace('text-gray-400', 'text-emerald-500');
            } else {
                reqSpecial.classList.replace('text-emerald-500', 'text-gray-400');
            }

            if (password.length === 0) {
                bar.style.width = '0%';
                text.innerText = '';
            } else if (strength === 1) {
                bar.style.width = '33%';
                bar.className = 'h-full transition-all duration-300 bg-red-500';
                text.innerText = 'Lemah';
                text.className = 'text-[10px] font-bold w-12 text-right text-red-500';
            } else if (strength === 2) {
                bar.style.width = '66%';
                bar.className = 'h-full transition-all duration-300 bg-yellow-500';
                text.innerText = 'Sedang';
                text.className = 'text-[10px] font-bold w-12 text-right text-yellow-500';
            } else if (strength === 3) {
                bar.style.width = '100%';
                bar.className = 'h-full transition-all duration-300 bg-emerald-500';
                text.innerText = 'Kuat';
                text.className = 'text-[10px] font-bold w-12 text-right text-emerald-500';
            }

            validateForm();
        }

        function checkMatch() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            const warning = document.getElementById('match-warning');
            
            if (confirm.length > 0 && pass !== confirm) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }

            validateForm();
        }

        // FUNGSI UTAMA: Mengatur hidup/matinya tombol Daftar
        function validateForm() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            const btnSubmit = document.getElementById('btn-submit');

            // Pengecekan standar yang lebih akurat
            const isLengthValid = pass.length >= 8;
            const hasUppercase = /[A-Z]/.test(pass); // Memastikan ada minimal 1 huruf besar
            const hasLowercase = /[a-z]/.test(pass); // Memastikan ada minimal 1 huruf kecil
            const hasSpecial = /[^a-zA-Z\d]/.test(pass); // Memastikan ada minimal 1 simbol
            const isMatch = (pass === confirm) && (confirm.length > 0);

            // Semua syarat harus bernilai true
            if (isLengthValid && hasUppercase && hasLowercase && hasSpecial && isMatch) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                btnSubmit.classList.add('hover:bg-gradient-to-r', 'hover:from-[#8C6239]', 'hover:to-[#AF8B69]', 'hover:shadow-xl');
            } else {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                btnSubmit.classList.remove('hover:bg-gradient-to-r', 'hover:from-[#8C6239]', 'hover:to-[#AF8B69]', 'hover:shadow-xl');
            }
        }
        
        // Pasang event listener untuk checkbox syarat
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxSyarat = document.getElementById('syarat');
            if (checkboxSyarat) {
                checkboxSyarat.addEventListener('change', validateForm);
            }
        });

        function checkMatch() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            const warning = document.getElementById('match-warning');
            
            // Tampilkan peringatan jika tidak sama dan kolom konfirmasi tidak kosong
            if (confirm.length > 0 && pass !== confirm) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }
    </script>
</body>
</html>