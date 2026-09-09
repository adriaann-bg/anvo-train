<!-- views/layouts/footer.php -->
    </main>
    
    <!-- Bagian Footer dengan Background luar terang agar box footer terlihat floating -->
    <footer class="bg-slate-50 pt-12 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Box Footer Gelap -->
            <div class="bg-[#0F172A] rounded-[32px] p-10 lg:p-16">
                <!-- Grid 1 Kolom (Mobile) -> 2 Kolom (Tablet) -> 4 Kolom (Desktop) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
                    
                    <!-- Kolom 1: Brand & Tagline -->
                    <div class="flex flex-col">
                        <div class="flex items-center gap-3 mb-6">
                            <!-- Menggunakan logo yang di-invert agar putih -->
                            <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO Logo" class="h-8 invert brightness-0">
                            <!-- <span class="text-2xl font-bold text-white tracking-wide">ANVO</span> -->
                        </div>
                        <p class="text-white/80 italic text-sm leading-relaxed pr-4">
                            Menghubungkan Jarak,<br>
                            Memanjakan Perjalanan
                        </p>
                    </div>

                    <!-- Kolom 2: Layanan Perjalanan -->
                    <div class="flex flex-col">
                        <h4 class="text-white font-bold text-lg mb-6">Layanan Perjalanan</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-white/80 hover:text-white hover:underline transition-colors text-sm">Pesan Tiket Kelas Eksekutif</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white hover:underline transition-colors text-sm">Pilihan Gerbong Armada</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white hover:underline transition-colors text-sm">Rute & Kota Tujuan</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white hover:underline transition-colors text-sm">Jadwal Keberangkatan Kereta</a></li>
                        </ul>
                    </div>

                    <!-- Kolom 3: Pusat Bantuan -->
                    <div class="flex flex-col">
                        <h4 class="text-white font-bold text-lg mb-6">Pusat Bantuan</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-white/80 hover:text-white hover:underline transition-colors text-sm leading-relaxed block">Panduan & Cara Pemesanan</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white hover:underline transition-colors text-sm leading-relaxed block">Syarat Reschedule & Pembatalan Tiket</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white hover:underline transition-colors text-sm leading-relaxed block">Customer Care Online (VIP Concierge)</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white hover:underline transition-colors text-sm leading-relaxed block">Faq (Pertanyaan Sering Diajukan)</a></li>
                        </ul>
                    </div>

                    <!-- Kolom 4: Kontak & Social Media -->
                    <div class="flex flex-col">
                        <h4 class="text-white font-bold text-lg mb-6">Kontak & Kantor Pusat</h4>
                        <p class="text-white/80 text-sm leading-relaxed mb-8">
                            Jl. Raya Sidokerto, Buduran,<br>
                            Sidoarjo, Jawa Timur (bisa<br>
                            disesuaikan dengan wilayah<br>
                            operasional).
                        </p>
                        
                        <!-- Social Media Icons -->
                        <div class="flex gap-5 items-center">
                            <!-- WhatsApp -->
                            <a href="#" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.386 0 12.032c0 2.128.553 4.205 1.604 6.035L.175 24l6.096-1.597C8.04 23.366 10.015 24 12.031 24 18.675 24 24 18.614 24 11.968 24 5.322 18.675 0 12.031 0zm0 22.016c-1.782 0-3.528-.479-5.06-1.387l-.362-.214-3.766.988.998-3.673-.235-.374a9.967 9.967 0 0 1-1.523-5.325C2.083 5.485 7.485.083 14.032.083c6.545 0 11.947 5.402 11.947 11.95 0 6.546-5.402 11.949-11.948 11.949v-1.966zm5.823-7.973c-.319-.16-1.892-.935-2.185-1.042-.293-.106-.506-.16-.72.16-.213.319-.826 1.041-1.013 1.254-.187.213-.374.24-.693.08-1.527-.76-2.65-1.465-3.677-2.923-.213-.304.22-.29.53-.902.106-.214.053-.401-.027-.561-.08-.16-.72-1.737-.986-2.378-.26-.626-.525-.541-.72-.55-.187-.01-.4-.01-.614-.01-.213 0-.56.08-.853.401-.293.319-1.12 1.094-1.12 2.668 0 1.575 1.147 3.1 1.306 3.313.16.213 2.26 3.447 5.474 4.835 2.128.922 2.915.992 3.974.836 1.155-.173 2.684-1.096 3.063-2.155.378-1.06.378-1.967.265-2.156-.112-.187-.424-.294-.743-.453z"/></svg>
                            </a>
                            <!-- Instagram -->
                            <a href="#" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            <!-- X (Twitter) -->
                            <a href="#" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                            </a>
                            <!-- Threads / Generic Icon (Menggunakan @ / Globe ala Threads) -->
                            <a href="#" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </footer>
</body>
</html>