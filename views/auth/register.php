<!-- views/auth/register.php -->
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
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 8px; }

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

    <div class="bg-white w-full max-w-5xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[650px]">
        
        <!-- Kolom Kiri: Form Daftar -->
        <div class="w-full md:w-1/2 p-8 lg:p-14 flex flex-col justify-center">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-[#0F172A] mb-2">Daftar</h1>
                <p class="text-gray-500 text-sm">Bergabunglah untuk pengalaman perjalanan terbaik</p>
            </div>

            <form action="/anvo/public/auth/proses_register" method="POST" class="space-y-5">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1.5 ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan Nama Sesuai KTP" required class="w-full border border-gray-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1.5 ml-1">NIK (Sesuai KTP)</label>
                        <input type="text" name="nik" placeholder="Masukkan 16 Digit NIK" required maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full border border-gray-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1.5 ml-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required class="w-full border border-gray-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all text-[#0F172A] bg-gray-50/50 hover:bg-white">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1.5 ml-1">Email</label>
                        <input type="email" name="email" placeholder="Masukkan Email" required class="w-full border border-gray-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white">
                    </div>
                </div>

                <!-- Input No HP -->
                <div>
                    <label class="text-xs text-gray-500 block mb-1.5 ml-1">No. Telepon</label>
                    <div class="relative flex items-center border border-gray-200 rounded-2xl focus-within:border-[#8C6239] focus-within:ring-2 focus-within:ring-[#8C6239]/20 transition-all bg-gray-50/50 focus-within:bg-white hover:bg-white">
                        
                        <div class="relative" id="country-dropdown-wrapper">
                            <button type="button" onclick="toggleCountryMenu()" class="flex items-center gap-2 px-4 py-3.5 border-r border-gray-200 hover:bg-gray-100 rounded-l-2xl transition-colors min-w-[85px] justify-center">
                                <span id="selected-code" class="text-sm font-bold text-[#0F172A]">ID</span>
                                <span id="selected-dial" class="text-sm font-semibold text-gray-500">+62</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-0.5"></i>
                            </button>
                            
                            <input type="hidden" name="kode_negara" id="input-kode-negara" value="+62">

                            <div id="country-menu" class="hidden absolute top-full left-0 mt-2 w-80 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden transform transition-all">
                                <div class="p-3 border-b border-gray-100">
                                    <div class="relative">
                                        <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                        <input type="text" placeholder="Cari negara..." onkeyup="filterCountry(this)" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#2B9BFB] focus:ring-1 focus:ring-[#2B9BFB] transition-all bg-gray-50">
                                    </div>
                                </div>
                                <ul id="country-list" class="max-h-60 overflow-y-auto py-2 custom-scrollbar">
                                    <!-- Data akan dirender oleh JavaScript -->
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Input Nomor -->
                        <input type="text" name="no_hp" id="input-nomor" placeholder="Contoh: 81234567890" required 
                               oninput="cleanPhoneNumber(this)" 
                               class="w-full px-4 py-3.5 text-sm focus:outline-none bg-transparent rounded-r-2xl text-[#0F172A]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Password Utama -->
                    <div>
                        <label class="text-xs text-gray-500 block mb-1.5 ml-1">Password</label>
                        <div class="relative flex items-center">
                            <!-- id="password" -->
                            <input type="password" name="password" id="password" placeholder="Masukkan Password" required 
                                   oninput="checkPasswordStrength(this.value)"
                                   class="w-full border border-gray-200 rounded-2xl pl-4 pr-12 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-[#0F172A]">
                            
                            <!-- Toggle untuk id="password" dan id="eye_icon_1" -->
                            <button type="button" onclick="togglePasswordVisibility('password', 'eye_icon_1')" class="absolute right-4 text-gray-400 hover:text-[#8C6239] transition-colors focus:outline-none">
                                <i id="eye_icon_1" class="fa-regular fa-eye-slash text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- UI Indikator Kekuatan Password -->
                        <div class="mt-2.5 flex items-center gap-2 px-1">
                            <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div id="strength-bar" class="h-full w-0 transition-all duration-300"></div>
                            </div>
                            <span id="strength-text" class="text-[10px] font-bold text-gray-400 w-12 text-right"></span>
                        </div>
                        <ul class="mt-2 text-[10px] text-gray-400 space-y-1.5 px-1">
                            <li id="req-length" class="flex items-center gap-1.5 transition-colors"><i class="fa-solid fa-circle text-[5px]"></i> Minimal 8 karakter</li>
                            <li id="req-case" class="flex items-center gap-1.5 transition-colors"><i class="fa-solid fa-circle text-[5px]"></i> Huruf besar & kecil</li>
                            <li id="req-special" class="flex items-center gap-1.5 transition-colors"><i class="fa-solid fa-circle text-[5px]"></i> Karakter khusus (!@#$%)</li>
                        </ul>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="text-xs text-gray-500 block mb-1.5 ml-1">Konfirmasi Password</label>
                        <div class="relative flex items-center">
                            <!-- id="password_confirm" -->
                            <input type="password" name="password_confirm" id="password_confirm" placeholder="Konfirmasi Password" required 
                                   class="w-full border border-gray-200 rounded-2xl pl-4 pr-12 py-3.5 text-sm focus:outline-none focus:border-[#8C6239] focus:ring-2 focus:ring-[#8C6239]/20 transition-all bg-gray-50/50 hover:bg-white text-[#0F172A]">
                            
                            <!-- Toggle untuk id="password_confirm" dan id="eye_icon_2" -->
                            <button type="button" onclick="togglePasswordVisibility('password_confirm', 'eye_icon_2')" class="absolute right-4 text-gray-400 hover:text-[#8C6239] transition-colors focus:outline-none">
                                <i id="eye_icon_2" class="fa-regular fa-eye-slash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-3 pl-1">
                    <input type="checkbox" id="syarat" required class="w-4 h-4 text-[#8C6239] border-gray-300 rounded focus:ring-[#8C6239] cursor-pointer">
                    <label for="syarat" class="text-xs text-gray-500 cursor-pointer select-none">Saya setuju dengan <a href="#" class="text-[#8C6239] font-medium hover:underline">syarat dan ketentuan</a></label>
                </div>

                <button type="submit" class="w-full bg-[#8C6239] hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] text-white py-4 rounded-2xl font-semibold transition-all shadow-lg hover:shadow-xl mt-4">
                    Daftar Akun
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                Sudah punya akun? <a href="/anvo/public/auth/login" class="text-[#2B9BFB] font-medium hover:underline">Masuk</a>
            </p>
        </div>

        <!-- Kolom Kanan: Gambar Background -->
        <div class="hidden md:block w-1/2 relative bg-[#0F172A]">
            <img src="/anvo/public/img/Interior Luminary Class Design 1.png" alt="ANVO Kereta" class="absolute inset-0 w-full h-full object-cover opacity-70">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0F172A] via-[#0F172A]/40 to-transparent"></div>
            
            <div class="absolute top-10 left-10 flex items-center gap-3">
                <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="Logo" class="h-7 invert brightness-0">
            </div>
            
            <div class="absolute bottom-12 left-10 right-10">
                <h3 class="text-white text-2xl font-bold mb-2">Perjalanan Tanpa Batas</h3>
                <p class="text-white/80 text-sm leading-relaxed">Pesan tiket kereta cepat dengan mudah, aman, dan rasakan pengalaman layanan eksekutif yang tak terlupakan.</p>
            </div>
        </div>
    </div>

    <!-- Script Custom Dropdown Negara -->
    <script>
        const countryDataStr = "AF|Afghanistan|افغانستان|+93,AL|Albania|Shqipëria|+355,DZ|Algeria|الجزائر|+213,AD|Andorra|Andorra|+376,AO|Angola|Angola|+244,AR|Argentina|Argentina|+54,AM|Armenia|Հայաստան|+374,AU|Australia|Australia|+61,AT|Austria|Österreich|+43,AZ|Azerbaijan|Azərbaycan|+994,BH|Bahrain|البحرين|+973,BD|Bangladesh|বাংলাদেশ|+880,BY|Belarus|Беларусь|+375,BE|Belgium|België|+32,BZ|Belize|Belize|+501,BJ|Benin|Bénin|+229,BT|Bhutan|འབྲུག་ཡུལ|+975,BO|Bolivia|Bolivia|+591,BA|Bosnia and Herzegovina|Bosna i Hercegovina|+387,BW|Botswana|Botswana|+267,BR|Brazil|Brasil|+55,BN|Brunei|Brunei|+673,BG|Bulgaria|България|+359,KH|Cambodia|កម្ពុជា|+855,CM|Cameroon|Cameroun|+237,CA|Canada|Canada|+1,CL|Chile|Chile|+56,CN|China|中国|+86,CO|Colombia|Colombia|+57,CD|Congo (DRC)|Jamhuri|+243,CR|Costa Rica|Costa Rica|+506,HR|Croatia|Hrvatska|+385,CU|Cuba|Cuba|+53,CY|Cyprus|Κύπρος|+357,CZ|Czechia|Česko|+420,DK|Denmark|Danmark|+45,EC|Ecuador|Ecuador|+593,EG|Egypt|مصر|+20,FI|Finland|Suomi|+358,FR|France|France|+33,GE|Georgia|საქართველო|+995,DE|Germany|Deutschland|+49,GR|Greece|Ελλάδα|+30,HK|Hong Kong|香港|+852,HU|Hungary|Magyarország|+36,IS|Iceland|Ísland|+354,IN|India|भारत|+91,ID|Indonesia|Indonesia|+62,IR|Iran|ایران|+98,IQ|Iraq|العراق|+964,IE|Ireland|Éire|+353,IL|Israel|ישראל|+972,IT|Italy|Italia|+39,JM|Jamaica|Jamaica|+1,JP|Japan|日本|+81,JO|Jordan|الأردن|+962,KZ|Kazakhstan|Қазақстан|+7,KE|Kenya|Kenya|+254,KP|North Korea|북한|+850,KR|South Korea|대한민국|+82,KW|Kuwait|الكويت|+965,LA|Laos|ລາວ|+856,LB|Lebanon|لبنان|+961,LY|Libya|ليبيا|+218,MY|Malaysia|Malaysia|+60,MV|Maldives|Maldives|+960,MX|Mexico|México|+52,MA|Morocco|المغرب|+212,MM|Myanmar|မြန်မာ|+95,NP|Nepal|नेपाल|+977,NL|Netherlands|Nederland|+31,NZ|New Zealand|New Zealand|+64,NG|Nigeria|Nigeria|+234,NO|Norway|Norge|+47,OM|Oman|عُمان|+968,PK|Pakistan|پاکستان|+92,PS|Palestine|فلسطين|+970,PA|Panama|Panamá|+507,PE|Peru|Perú|+51,PH|Philippines|Pilipinas|+63,PL|Poland|Polska|+48,PT|Portugal|Portugal|+351,QA|Qatar|قطر|+974,RO|Romania|România|+40,RU|Russia|Россия|+7,SA|Saudi Arabia|السعودية|+966,RS|Serbia|Србија|+381,SG|Singapore|Singapura|+65,ZA|South Africa|South Africa|+27,ES|Spain|España|+34,LK|Sri Lanka|ශ්‍රී ලංකාව|+94,SE|Sweden|Sverige|+46,CH|Switzerland|Schweiz|+41,SY|Syria|سوريا|+963,TW|Taiwan|台灣|+886,TH|Thailand|ประเทศไทย|+66,TR|Turkey|Türkiye|+90,AE|United Arab Emirates|الإمارات|+971,GB|United Kingdom|United Kingdom|+44,US|United States|United States|+1,VN|Vietnam|Việt Nam|+84,YE|Yemen|اليمن|+967,ZW|Zimbabwe|Zimbabwe|+263";

        // KAMUS MASIF: Format Panjang Digit Asli Ratusan Negara
        const placeholderFormats = {
            'ID': '81234567890', 'SG': '81234567', 'MY': '123456789', 
            'CN': '13912345678', 'JP': '9012345678', 'US': '2025550123', 
            'GB': '7700900123', 'VN': '912345678', 'TH': '812345678',
            'IN': '9876543210', 'BR': '11987654321', 'RU': '9123456789',
            'DE': '15123456789', 'FR': '612345678', 'KR': '1012345678',
            'TW': '912345678', 'PH': '9123456789', 'AU': '412345678',
            'SA': '512345678', 'AE': '501234567', 'ZA': '601234567',
            'IT': '3123456789', 'ES': '612345678', 'TR': '5012345678',
            'CA': '4165550123', 'MX': '5512345678', 'AR': '1123456789',
            'CO': '3001234567', 'CL': '912345678', 'PE': '912345678',
            'NG': '8012345678', 'KE': '712345678', 'EG': '1012345678',
            'MA': '612345678', 'DZ': '551234567', 'PK': '3012345678',
            'BD': '1712345678', 'LK': '712345678', 'NP': '9812345678',
            'MM': '912345678', 'KH': '12345678', 'LA': '2012345678',
            'BN': '8123456', 'HK': '61234567', 'NZ': '211234567',
            'FI': '401234567', 'SE': '701234567', 'NO': '41234567',
            'DK': '20123456', 'NL': '612345678', 'BE': '471234567',
            'CH': '791234567', 'AT': '6641234567', 'PL': '501234567',
            'CZ': '601234567', 'HU': '301234567', 'RO': '712345678',
            'GR': '6912345678', 'PT': '912345678', 'IE': '851234567',
            'IL': '501234567', 'IR': '9123456789', 'IQ': '7901234567',
            'JO': '791234567', 'LB': '3123456', 'SY': '931234567',
            'QA': '33123456', 'KW': '61234567', 'OM': '91234567',
            'BH': '39123456', 'AF': '701234567'
        };

        const countries = countryDataStr.split(',').map(item => {
            const [code, name, nativeName, dialCode] = item.split('|');
            return {
                code: code,
                name: name,
                nativeName: nativeName,
                dialCode: dialCode,
                flagUrl: `https://flagcdn.com/w20/${code.toLowerCase()}.png`,
                // Fallback otomatis jika negara tidak ada di list atas (rata-rata dunia = 9 digit)
                placeholder: placeholderFormats[code] || '123456789' 
            };
        });

        const listEl = document.getElementById('country-list');
        countries.forEach(country => {
            const li = document.createElement('li');
            li.className = "px-4 py-2.5 hover:bg-gray-50 cursor-pointer flex justify-between items-center transition-colors border-b border-gray-50 last:border-0";
            li.innerHTML = `
                <div class="flex items-start gap-3">
                    <img src="${country.flagUrl}" class="w-5 h-auto rounded-sm shadow-sm mt-1" alt="${country.code}">
                    <div class="flex flex-col">
                        <span class="country-name text-sm text-[#0F172A] font-medium leading-tight">${country.name}</span>
                        <span class="text-[11px] text-gray-400 font-light mt-0.5 leading-none">${country.nativeName}</span>
                    </div>
                </div>
                <span class="text-gray-500 font-semibold text-xs">${country.dialCode}</span>
            `;
            li.onclick = () => selectCountry(country);
            listEl.appendChild(li);
        });

        function toggleCountryMenu() {
            document.getElementById('country-menu').classList.toggle('hidden');
        }

        function selectCountry(country) {
            document.getElementById('selected-code').innerText = country.code;
            document.getElementById('selected-dial').innerText = country.dialCode; 
            document.getElementById('input-kode-negara').value = country.dialCode; 
            
            // Ajaib! Placeholder langsung berubah sesuai format negara yang dipilih
            document.getElementById('input-nomor').placeholder = `Contoh: ${country.placeholder}`;
            
            toggleCountryMenu();
        }

        function filterCountry(input) {
            const filter = input.value.toUpperCase();
            const lis = listEl.getElementsByTagName('li');
            for (let i = 0; i < lis.length; i++) {
                let name = lis[i].querySelector('.country-name').textContent;
                if (name.toUpperCase().indexOf(filter) > -1) {
                    lis[i].style.display = "";
                } else {
                    lis[i].style.display = "none";
                }
            }
        }

        document.addEventListener('click', (e) => {
            if(!document.getElementById('country-dropdown-wrapper').contains(e.target)) {
                document.getElementById('country-menu').classList.add('hidden');
            }
        });

        function cleanPhoneNumber(input) {
            let cleaned = input.value.replace(/[^0-9]/g, '');
            if(cleaned.startsWith('0')) {
                cleaned = cleaned.substring(1);
            }
            input.value = cleaned;
        }

        // FUNGSI DETEKSI KEKUATAN PASSWORD REAL-TIME
        function checkPasswordStrength(password) {
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            const reqLength = document.getElementById('req-length');
            const reqCase = document.getElementById('req-case');
            const reqSpecial = document.getElementById('req-special');

            let strength = 0;

            // 1. Cek Minimal 8 Karakter
            if (password.length >= 8) {
                strength += 1;
                reqLength.classList.replace('text-gray-400', 'text-emerald-500');
            } else {
                reqLength.classList.replace('text-emerald-500', 'text-gray-400');
            }

            // 2. Cek Huruf Besar & Kecil
            if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                strength += 1;
                reqCase.classList.replace('text-gray-400', 'text-emerald-500');
            } else {
                reqCase.classList.replace('text-emerald-500', 'text-gray-400');
            }

            // 3. Cek Karakter Khusus
            if (password.match(/[^a-zA-Z\d]/)) {
                strength += 1;
                reqSpecial.classList.replace('text-gray-400', 'text-emerald-500');
            } else {
                reqSpecial.classList.replace('text-emerald-500', 'text-gray-400');
            }

            // Animasi Warna Bar & Teks
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
        }

        // Auto-hide script (hilang dalam 5 detik)
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
                icon.classList.add('text-[#8C6239]'); // Beri warna aktif
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                icon.classList.remove('text-[#8C6239]');
            }
        }
    </script>

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
    <!-- ========================================== -->
    <!-- FLASH MESSAGE (TOAST NOTIFICATION) AKHIR -->
    <!-- ========================================== -->
</body>
</html>