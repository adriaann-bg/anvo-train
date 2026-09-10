<!-- views/layouts/footer.php -->
    </main>
    
    <!-- Footer Full Width dengan Warna Netral ANVO (#FAFAFA) -->
    <footer class="w-full bg-[#FAFAFA] pt-10 pb-8 mt-auto border-t border-gray-200">


        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <!-- Kiri: Logo -->
            <div class="flex-shrink-0">
                <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO Logo" class="h-7 w-auto">
            </div>

            <!-- Tengah: Social Media -->
            <div class="flex items-center gap-3">
                <a href="#"
                    class="w-9 h-9 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:opacity-50 transition-opacity duration-300"><i
                        class="fa-brands fa-instagram text-lg"></i></a>
                <a href="#"
                    class="w-9 h-9 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:opacity-50 transition-opacity duration-300"><i
                        class="fa-brands fa-tiktok text-lg"></i></a>
                <a href="#"
                    class="w-9 h-9 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:opacity-50 transition-opacity duration-300"><i
                        class="fa-brands fa-youtube text-lg"></i></a>
                <a href="#"
                    class="w-9 h-9 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:opacity-50 transition-opacity duration-300">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z" />
                    </svg>
                </a>
                <a href="#"
                    class="w-9 h-9 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:opacity-50 transition-opacity duration-300"><i
                        class="fa-brands fa-facebook-f text-base"></i></a>
            </div>

            <!-- Kanan: Custom Language Selector (Terhubung ke Hidden Google Translate) -->
            <div class="relative custom-lang-dropdown notranslate">
                <!-- Widget Asli Google (Disembunyikan) -->
                <div id="google_translate_element" class="hidden"></div>

                <button onclick="toggleLang()"
                    class="flex items-center gap-2 bg-transparent text-gray-700 px-3 py-1.5 rounded-full hover:bg-black/5 transition-colors text-sm font-medium">
                    <img id="active-flag" src="https://flagcdn.com/w20/id.png" alt="Indonesia"
                        class="w-5 h-auto rounded-sm border border-gray-200">
                    <span id="active-lang">Indonesia (ID)</span>
                    <i class="fa-solid fa-chevron-up text-xs ml-1 text-gray-500 transition-transform duration-300"
                        id="lang-arrow"></i>
                </button>

                <div id="lang-menu"
                    class="hidden absolute bottom-full right-0 mb-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg z-50 overflow-hidden origin-bottom-right transition-all">
                    <ul class="py-2">
                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-sm flex items-center gap-3"
                            onclick="triggerGoogleTranslate('id', 'Indonesia (ID)', 'id')">
                            <img src="https://flagcdn.com/w20/id.png" class="w-5 h-auto rounded-sm border border-gray-200">
                            Indonesia (ID)
                        </li>
                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-sm flex items-center gap-3"
                            onclick="triggerGoogleTranslate('en', 'English (US)', 'us')">
                            <img src="https://flagcdn.com/w20/us.png" class="w-5 h-auto rounded-sm border border-gray-200">
                            English (US)
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <hr class="border-gray-300/80 my-6">

        <!-- Baris 2: Links -->
        <div class="flex flex-wrap justify-center items-center gap-x-3 gap-y-3 text-gray-700 text-sm mb-6">
            <a href="#" class="hover:opacity-60 transition-opacity">Tentang Kami</a>
            <span class="text-gray-400">|</span>
            <a href="#" class="hover:opacity-60 transition-opacity">FAQ</a>
            <span class="text-gray-400">|</span>
            <a href="#" class="hover:opacity-60 transition-opacity">Syarat & Ketentuan</a>
            <span class="text-gray-400">|</span>
            <a href="#" class="hover:opacity-60 transition-opacity">Hubungan Investor</a>
            <span class="text-gray-400">|</span>
            <a href="#" class="hover:opacity-60 transition-opacity">Karir</a>
            <span class="text-gray-400">|</span>
            <a href="#" class="hover:opacity-60 transition-opacity">Kebijakan Privasi</a>
            <span class="text-gray-400">|</span>
            <a href="#" class="hover:opacity-60 transition-opacity">Hubungi Kami</a>
        </div>

        <div class="text-center text-gray-500 text-xs font-medium">
            Copyrights &copy; 2024-2026 PT ANVO Sejahtera Raya Tbk. All rights reserved.
        </div>

    </footer>

    <!-- CSS Brutal untuk Membunuh UI Google Translate -->
    <style>
        /* Sembunyikan top banner Google */
        .goog-te-banner-frame.skiptranslate { display: none !important; }
        /* Paksa body tetap di atas dan cegah lompatan */
        body { top: 0px !important; position: static !important; }
        /* Sembunyikan tooltip Google saat teks di-hover */
        .goog-tooltip, .goog-tooltip:hover { display: none !important; box-shadow: none !important; }
        .goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }
    </style>

    <!-- Script Google Translate Asli -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id', 
                includedLanguages: 'en,id', // Hanya tampilkan ID dan EN
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Script Penghubung Dropdown Custom ke Engine Google -->
    <script>
        function toggleLang() {
            document.getElementById('lang-menu').classList.toggle('hidden');
            document.getElementById('lang-arrow').classList.toggle('rotate-180');
        }

        function triggerGoogleTranslate(langCode, langText, flagCode) {
            // 1. Ubah visual UI kita (Bendera & Teks)
            document.getElementById('active-flag').src = `https://flagcdn.com/w20/${flagCode}.png`;
            document.getElementById('active-lang').innerText = langText;
            
            // Tutup dropdown
            toggleLang();

            // 2. Tembak event ke elemen select Google Translate yang disembunyikan
            var selectField = document.querySelector(".goog-te-combo");
            if (selectField) {
                selectField.value = langCode;
                selectField.dispatchEvent(new Event("change"));
            }
        }

        // Tutup dropdown jika klik di tempat lain
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.custom-lang-dropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                document.getElementById('lang-menu').classList.add('hidden');
                document.getElementById('lang-arrow').classList.remove('rotate-180');
            }
        });
    </script>
</body>
</html>