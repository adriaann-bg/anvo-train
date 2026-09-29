<!-- views/home/index.php -->

<!-- 1. Jumbotron (Hero Section) Fix Posisi Bawah -->
<section class="relative h-[650px] md:h-[750px] w-full">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/anvo/public/img/Eksterior Kereta Sawah Real 1.png');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-[#0F172A]/10 via-[#0F172A]/50 to-[#0F172A]/90"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pb-32">
        <div class="max-w-2xl">
            <!-- Indikator Aktif di atas Logo -->
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full mb-6">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                </span>
                <span class="text-white text-xs font-medium tracking-wide">Sistem Reservasi Aktif 24/7</span>
            </div>
            
            <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO Logo" class="h-8 mb-4 invert brightness-0">
            
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-8 tracking-wide leading-tight">
                Menghubungkan Jarak,<br>Memanjakan Perjalanan.
            </h1>
            
            <button class="bg-transparent border border-white text-white px-8 py-3 rounded-full font-medium hover:border-transparent hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] transition-all duration-300">
                Beli Tiket
            </button>
        </div>
    </div>
</section>

<!-- 2. Form Beli Tiket -->
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20 mb-24">
    <div class="bg-white rounded-[32px] shadow-2xl p-8 border border-gray-100">
        <h2 class="text-2xl font-bold text-center text-[#0F172A] mb-8">Beli Tiket</h2>
        
        <!-- Tambahkan onsubmit untuk validasi wajib isi & tanggal kembali opsional -->
        <form action="/anvo/public/booking/jadwal" method="GET" onsubmit="return validateSearchForm()" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-6">
            
            <!-- Keberangkatan (Asal) -->
            <div class="relative custom-dropdown" data-target="asal">
                <label class="text-sm text-gray-500 mb-2 block">Keberangkatan <span class="text-red-500">*</span></label>
                <div class="border-b border-gray-300 py-2 flex justify-between items-center cursor-pointer hover:border-[#8C6239] transition-colors" onclick="toggleDropdown('dropdown-asal')">
                    <span id="label-asal" class="text-gray-400">Pilih Kota Asal</span>
                    <svg class="w-4 h-4 text-gray-400 dropdown-arrow transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <!-- Name disesuaikan dengan Controller: 'asal' -->
                <input type="hidden" name="asal" id="input-asal">
                
                <div id="dropdown-asal" class="hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                    <div class="p-3 bg-gray-50 border-b border-gray-100 sticky top-0 z-10">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" placeholder="Cari kota..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#2B9BFB] text-sm" onkeyup="filterDropdown(this, 'list-asal')">
                        </div>
                    </div>
                    <ul id="list-asal" class="max-h-48 overflow-y-auto">
                        <?php foreach($stasiuns as $stasiun): ?>
                        <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer text-sm border-b border-gray-50 last:border-0" 
                            onclick="selectOption('asal', '<?= $stasiun['nama_stasiun'] ?>', '<?= $stasiun['nama_stasiun'] ?>')">
                            <?= $stasiun['nama_stasiun'] ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tujuan -->
            <div class="relative custom-dropdown" data-target="tujuan">
                <label class="text-sm text-gray-500 mb-2 block">Tujuan <span class="text-red-500">*</span></label>
                <div class="border-b border-gray-300 py-2 flex justify-between items-center cursor-pointer hover:border-[#8C6239] transition-colors" onclick="toggleDropdown('dropdown-tujuan')">
                    <span id="label-tujuan" class="text-gray-400">Pilih Kota Tujuan</span>
                    <svg class="w-4 h-4 text-gray-400 dropdown-arrow transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <!-- Name disesuaikan dengan Controller: 'tujuan' -->
                <input type="hidden" name="tujuan" id="input-tujuan">
                
                <div id="dropdown-tujuan" class="hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                    <div class="p-3 bg-gray-50 border-b border-gray-100 sticky top-0 z-10">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" placeholder="Cari kota..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#2B9BFB] text-sm" onkeyup="filterDropdown(this, 'list-tujuan')">
                        </div>
                    </div>
                    <ul id="list-tujuan" class="max-h-48 overflow-y-auto">
                        <?php foreach($stasiuns as $stasiun): ?>
                        <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer text-sm border-b border-gray-50 last:border-0" 
                            onclick="selectOption('tujuan', '<?= $stasiun['nama_stasiun'] ?>', '<?= $stasiun['nama_stasiun'] ?>')">
                            <?= $stasiun['nama_stasiun'] ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tanggal Berangkat -->
            <div class="flex flex-col">
                <label class="text-sm text-gray-500 mb-2">Tanggal Berangkat <span class="text-red-500">*</span></label>
                <!-- Name disesuaikan: 'tanggal' -->
                <input type="date" name="tanggal" id="input-tanggal" value="<?= date('Y-m-d') ?>" class="border-b border-gray-300 py-2 focus:outline-none focus:border-[#8C6239] bg-transparent text-[#0F172A]">
            </div>
            
            <!-- Tanggal Kembali (Opsional via Switch) -->
            <div class="flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm text-gray-500">Tanggal Kembali</label>
                    <label class="inline-flex relative items-center cursor-pointer">
                        <input type="checkbox" id="toggle-pulang" class="sr-only peer">
                        <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#8C6239]"></div>
                    </label>
                </div>
                <!-- Name disesuaikan: 'tanggal_pulang' -->
                <input type="date" name="tanggal_pulang" id="input-pulang" disabled class="border-b border-gray-300 py-2 focus:outline-none focus:border-[#8C6239] bg-transparent opacity-50 cursor-not-allowed">
            </div>

            <!-- Penumpang -->
            <div class="relative custom-dropdown" data-target="penumpang">
                <label class="text-sm text-gray-500 mb-2 block">Penumpang <span class="text-red-500">*</span></label>
                <div class="border-b border-gray-300 py-2 flex justify-between items-center cursor-pointer hover:border-[#8C6239] transition-colors" onclick="toggleDropdown('dropdown-penumpang')">
                    <span id="label-penumpang" class="text-gray-400">Pilih Jumlah</span>
                    <svg class="w-4 h-4 text-gray-400 dropdown-arrow transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <!-- Name disesuaikan: 'penumpang' -->
                <input type="hidden" name="penumpang" id="input-penumpang">
                
                <div id="dropdown-penumpang" class="hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                    <ul class="max-h-48 overflow-y-auto py-2">
                        <?php for($i=1; $i<=10; $i++): ?>
                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-sm" onclick="selectOption('penumpang', '<?= $i ?> Penumpang', '<?= $i ?>')"><?= $i ?> Penumpang</li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>

            <!-- Kelas -->
            <!-- <div class="relative custom-dropdown" data-target="kelas">
                <label class="text-sm text-gray-500 mb-2 block">Kelas Armada <span class="text-red-500">*</span></label>
                <div class="border-b border-gray-300 py-2 flex justify-between items-center cursor-pointer hover:border-[#8C6239] transition-colors" onclick="toggleDropdown('dropdown-kelas')">
                    <span id="label-kelas" class="text-gray-400">Pilih Kelas</span>
                    <svg class="w-4 h-4 text-gray-400 dropdown-arrow transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                
                <input type="hidden" name="kelas" id="input-kelas">
                
                <div id="dropdown-kelas" class="hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                    <ul class="max-h-48 overflow-y-auto py-2">
                        <?php foreach($kelas_kereta as $kelas): ?>
                        <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer text-sm border-b border-gray-50" 
                            onclick="selectOption('kelas', '<?= $kelas['kelas'] ?>', '<?= $kelas['kelas'] ?>')">
                            <?= $kelas['kelas'] ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div> -->

            <div class="flex flex-col md:flex-row justify-between items-center md:col-span-2 lg:col-span-3 mt-4 border-t border-gray-100 pt-6 w-full">
                <p class="text-xs text-orange-500 flex items-center gap-2 mb-6 md:mb-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    Penumpang anak di atas 3 tahun wajib membeli tiket sendiri.
                </p>
                <button type="submit" class="w-full md:w-auto bg-transparent border border-[#0F172A] text-[#0F172A] px-10 py-3 rounded-full font-medium hover:border-transparent hover:text-white hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] transition-all duration-300">
                    Cari Tiket
                </button>
            </div>
        </form>
    </div>
</section>

<!-- 3. Informasi Layanan (Perbaikan Lebar Button Mobile) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-24">
    <!-- Row 1 -->
    <div class="flex flex-col-reverse md:flex-row items-center gap-12">
        <div class="w-full md:w-1/2">
            <h3 class="text-3xl font-bold text-[#0F172A] mb-4">Reservasi Kelas Eksekutif</h3>
            <p class="text-gray-600 mb-8 leading-relaxed">Akses eksklusif untuk memesan ruang privat dan layanan VIP di setiap armada. Manjakan diri Anda dengan pelayanan terbaik sejak langkah pertama.</p>
            <button class="w-full md:w-auto text-[#294E8B] border border-[#294E8B] px-8 py-3 md:py-2 rounded-full font-medium hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#1E293B] hover:to-[#294E8B] transition-all duration-300 text-center flex justify-center items-center gap-2">
                Pelajari lebih lanjut <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
        <div class="w-full md:w-1/2">
            <img src="/anvo/public/img/Interior VVIP Skybox Design 1.png" alt="VVIP Skybox" class="rounded-3xl shadow-lg w-full object-cover h-[250px] md:h-[300px]">
        </div>
    </div>
    <!-- Row 2 -->
    <div class="flex flex-col-reverse md:flex-row items-center gap-12">
        <div class="w-full md:w-1/2">
            <h3 class="text-3xl font-bold text-[#0F172A] mb-4">Perjalanan Cepat & Nyaman</h3>
            <p class="text-gray-600 mb-8 leading-relaxed">Nikmati perjalanan lancar bebas hambatan dengan fasilitas unggulan kelas Luminary. Kenyamanan paripurna hingga Anda tiba di tujuan.</p>
            <button class="w-full md:w-auto text-[#294E8B] border border-[#294E8B] px-8 py-3 md:py-2 rounded-full font-medium hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#1E293B] hover:to-[#294E8B] transition-all duration-300 text-center flex justify-center items-center gap-2">
                Pelajari lebih lanjut <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
        <div class="w-full md:w-1/2">
            <img src="/anvo/public/img/Interior Luminary Class Design 1.png" alt="Luminary Class" class="rounded-3xl shadow-lg w-full object-cover h-[250px] md:h-[300px]">
        </div>
    </div>
    <!-- Row 3 -->
    <div class="flex flex-col-reverse md:flex-row items-center gap-12">
        <div class="w-full md:w-1/2">
            <h3 class="text-3xl font-bold text-[#0F172A] mb-4">Kelola Perjalanan</h3>
            <p class="text-gray-600 mb-8 leading-relaxed">Akses dan kelola seluruh riwayat pemesanan serta e-ticket Anda dalam satu dasbor pintar. Kemudahan mutlak dalam genggaman.</p>
            <button class="w-full md:w-auto text-[#294E8B] border border-[#294E8B] px-8 py-3 md:py-2 rounded-full font-medium hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#1E293B] hover:to-[#294E8B] transition-all duration-300 text-center flex justify-center items-center gap-2">
                Pelajari lebih lanjut <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
        <div class="w-full md:w-1/2">
            <img src="/anvo/public/img/Interior Executive Prime Design 2.png" alt="Executive Prime" class="rounded-3xl shadow-lg w-full object-cover h-[250px] md:h-[300px]">
        </div>
    </div>
    <!-- Row 2 & 3 Asumsikan menggunakan struktur HTML dan class button yang sama (w-full md:w-auto) -->
</section>

<!-- 4. Kota Tujuan (Dinamis dari Database & Posisi Tengah) -->
<section class="bg-gray-50 py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <h2 class="text-3xl font-bold text-center text-[#0F172A]">Kota Tujuan</h2>
    </div>
        
    <!-- Kontainer Scrollable (Dihapus xl:justify-center agar tidak terpotong di kiri) -->
    <div class="flex overflow-x-auto gap-6 px-4 sm:px-6 lg:px-8 pb-10 snap-x snap-mandatory hide-scrollbar justify-start" style="-ms-overflow-style: none; scrollbar-width: none;">
        
        <?php foreach($top_destinasi as $kota): ?>
        <!-- Card Kota (Dinamis) -->
        <div class="group relative overflow-hidden rounded-[32px] h-[400px] min-w-[280px] md:min-w-[300px] cursor-pointer snap-center flex-shrink-0 shadow-sm hover:shadow-xl transition-shadow duration-300">
            <!-- Gambar dari Database -->
            <img src="<?= $kota['image_url'] ?>" alt="<?= $kota['nama_stasiun'] ?>" class="absolute inset-0 w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-gradient-to-t from-[#1E293B] to-transparent opacity-90 group-hover:from-[#8C6239] group-hover:via-[#8C6239]/60 transition-colors duration-500"></div>
            
            <!-- Wrapper Teks yang naik ke atas bersamaan (Julukan dan Nama Stasiun diambil dari DB) -->
            <div class="absolute bottom-0 left-0 p-8 w-full flex flex-col justify-end transform transition-transform duration-500 group-hover:-translate-y-16">
                <?php 
                    // Memecah "Halim (HLM) - Jakarta" untuk mengambil hanya nama kotanya saja "Jakarta"
                    $nama_kota = explode(' - ', $kota['nama_stasiun']); 
                    $kota_tampil = isset($nama_kota[1]) ? $nama_kota[1] : $kota['nama_stasiun'];
                ?>
                <h3 class="text-white text-3xl font-bold mb-1"><?= $kota_tampil ?></h3>
                <p class="text-white/90 font-light mb-0"><?= $kota['julukan'] ?></p>
            </div>
            
            <!-- Tombol Muncul Saat Hover -->
            <div class="absolute bottom-8 left-8 right-8 opacity-0 translate-y-8 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500">
                <button class="w-full bg-transparent border border-white text-white py-3 rounded-full font-medium hover:bg-white hover:text-[#8C6239] transition-colors">
                    Beli Tiket
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        
    </div>
</section>

<!-- 5. Pilihan Gerbong / Pricing Tier -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <h2 class="text-3xl font-bold text-center text-[#0F172A] mb-16">Pilihan Gerbong</h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Tier 1: Executive Prime -->
        <div class="bg-white rounded-[32px] border border-gray-200 p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 flex flex-col h-full relative">
            
            <!-- HEAD SECTION (Tinggi diseragamkan di layar besar) -->
            <div class="flex flex-col lg:h-[340px]">
                <!-- mt-12 untuk mobile (menyeimbangkan card lain), lg:mt-10 untuk desktop agar sejajar -->
                <h3 class="text-2xl font-bold text-[#0F172A] mb-4 mt-0 lg:mt-10">Executive Prime</h3>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Dapatkan kenyamanan standar tinggi perjalanan cepat antarkota untuk kebutuhan mobilitas harian atau bisnis Anda dengan fasilitas modern terintegrasi.
                </p>
                <!-- mt-auto mendorong tombol ini mentok ke bawah batas Head -->
                <button class="w-full lg:w-max text-[#0F172A] border border-[#0F172A] px-6 py-2.5 rounded-full font-medium hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#1E293B] hover:to-[#294E8B] transition-all duration-300 flex justify-center items-center gap-2 mt-auto mb-8">
                    Beli Sekarang <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            
            <!-- LINE DIVIDER (Otomatis segaris lurus di layar besar) -->
            <hr class="border-gray-100 mb-8">
            
            <!-- BODY SECTION -->
            <div class="flex-grow">
                <p class="text-sm text-gray-500 mb-1">Pemesanan:</p>
                <p class="font-bold text-[#0F172A] mb-8">Pilih Kursi</p>
                
                <p class="text-gray-600 mb-4">Mencakup kenyamanan armada cepat dan:</p>
                
                <p class="font-bold text-[#0F172A] mb-3">Fasilitas Kursi:</p>
                <ul class="list-disc pl-5 text-gray-600 space-y-3 mb-8">
                    <li><strong class="text-[#0F172A]">Kursi ergonomis</strong> dengan ruang kaki luas (<em>spacious legroom</em>).</li>
                    <li><strong class="text-[#0F172A]">Smart screen personal</strong> di setiap kursi untuk hiburan mandiri.</li>
                    <li><strong class="text-[#0F172A]">Meja lipat kayu premium</strong> dan port pengisian daya gadget (USB & AC plug).</li>
                </ul>
                
                <p class="font-bold text-[#0F172A] mb-3">Layanan Gerbong:</p>
                <ul class="list-disc pl-5 text-gray-600 space-y-3">
                    <li><strong class="text-[#0F172A]">Akses ke gerbong restorasi</strong> (pembelian makanan & minuman).</li>
                    <li><strong class="text-[#0F172A]">Koneksi Wi-Fi</strong> berkecepatan tinggi selama perjalanan.</li>
                    <li><strong class="text-[#0F172A]">Bagasi kabin standar</strong>.</li>
                </ul>
            </div>
        </div>

        <!-- Tier 2: Luminary Class -->
        <div class="bg-white rounded-[32px] border border-gray-200 p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 flex flex-col h-full relative">
            
            <!-- Badge (Absolute positioning agar tidak merusak alur flexbox di bawahnya) -->
            <div class="absolute top-8 left-8 border border-gray-300 text-gray-600 text-xs font-medium px-4 py-1.5 rounded-full">
                Pilihan Terpopuler
            </div>
            
            <!-- HEAD SECTION -->
            <div class="flex flex-col lg:h-[340px]">
                <h3 class="text-2xl font-bold text-[#0F172A] mb-4 mt-12 lg:mt-10">Luminary Class</h3>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Rasakan privasi dan relaksasi tingkat lanjut dengan kompartemen privat (<em>sleeper pod</em>) yang dirancang khusus untuk kenyamanan optimal Anda.
                </p>
                <button class="w-full lg:w-max text-[#0F172A] border border-[#0F172A] px-6 py-2.5 rounded-full font-medium hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#1E293B] hover:to-[#294E8B] transition-all duration-300 flex justify-center items-center gap-2 mt-auto mb-8">
                    Pesan Sekarang <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            
            <!-- LINE DIVIDER -->
            <hr class="border-gray-100 mb-8">
            
            <!-- BODY SECTION -->
            <div class="flex-grow">
                <p class="text-sm text-gray-500 mb-1">Pemesanan:</p>
                <p class="font-bold text-[#0F172A] mb-8">Pesan Pods</p>
                
                <p class="text-gray-600 mb-4">Mencakup semua fitur <strong class="text-[#0F172A]">Executive Prime</strong> dan:</p>
                
                <p class="font-bold text-[#0F172A] mb-3">Fasilitas Kursi:</p>
                <ul class="list-disc pl-5 text-gray-600 space-y-3 mb-8">
                    <li><strong class="text-[#0F172A]">Sleeper Pod privat</strong> dengan pintu geser minimalis untuk privasi total.</li>
                    <li><strong class="text-[#0F172A]">Kursi malas</strong> (<em>reclining seat</em>) elektrik yang dapat diatur hingga posisi semi-ranjang.</li>
                    <li><strong class="text-[#0F172A]">Smart TV personal</strong> berukuran lebih besar dengan opsi integrasi audio <em>headphone</em>.</li>
                </ul>
                
                <p class="font-bold text-[#0F172A] mb-3">Layanan Tambahan:</p>
                <ul class="list-disc pl-5 text-gray-600 space-y-3">
                    <li><strong class="text-[#0F172A]">Gratis 1x paket makanan premium pilihan</strong> dan minuman hangat dari gerbong restorasi.</li>
                    <li><strong class="text-[#0F172A]">Comfort kit eksklusif</strong> (selimut tebal, bantal leher, dan penutup mata).</li>
                </ul>
            </div>
        </div>

        <!-- Tier 3: VVIP Skybox -->
        <div class="bg-[#8C6239] rounded-[32px] p-8 shadow-xl hover:shadow-2xl transition-shadow duration-300 flex flex-col h-full relative">
            
            <!-- Badge -->
            <div class="absolute top-8 left-8 border border-white/40 text-white text-xs font-medium px-4 py-1.5 rounded-full">
                Layanan Eksklusif
            </div>
            
            <!-- HEAD SECTION -->
            <div class="flex flex-col lg:h-[340px]">
                <h3 class="text-2xl font-bold text-white mb-4 mt-12 lg:mt-10">VVIP Skybox</h3>
                <p class="text-white/90 mb-8 leading-relaxed">
                    Definisi kemewahan mutlak perjalanan kereta cepat. Ruang lounge privat eksklusif dengan pelayanan kelas atas dan asistensi pribadi sepanjang perjalanan.
                </p>
                <button class="w-full lg:w-max text-white border border-white px-6 py-2.5 rounded-full font-medium hover:text-[#8C6239] hover:bg-white transition-all duration-300 flex justify-center items-center gap-2 mt-auto mb-8">
                    Hubungi Sekarang <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            
            <!-- LINE DIVIDER -->
            <hr class="border-white/20 mb-8">
            
            <!-- BODY SECTION -->
            <div class="flex-grow">
                <p class="text-sm text-white/70 mb-1">Pemesanan:</p>
                <p class="font-bold text-white mb-8">Hubungi Konserge VIP</p>
                
                <p class="text-white/90 mb-4">Mencakup semua fitur <strong>Luminary Class</strong> dan:</p>
                
                <p class="font-bold text-white mb-3">Fasilitas Kursi:</p>
                <ul class="list-disc pl-5 text-white/90 space-y-3 mb-8">
                    <li><strong>Akses ke gerbong lounge mewah</strong> dengan sofa kulit premium berhadapan.</li>
                    <li><strong>Ruang rapat privat</strong> dan jendela panorama seamless yang lebih luas.</li>
                    <li><strong>Sistem pencahayaan ambien pintar</strong> (<em>smart ambient lighting</em>) yang dapat disesuaikan.</li>
                </ul>
                
                <p class="font-bold text-white mb-3">Layanan Tambahan:</p>
                <ul class="list-disc pl-5 text-white/90 space-y-3">
                    <li><strong>Pelayanan makanan dan minuman premium</strong> <em>free-flow</em> yang diantarkan langsung.</li>
                    <li><strong>Akses gratis ke Executive VIP Lounge</strong> di stasiun keberangkatan sebelum naik kereta.</li>
                    <li><strong>Layanan Concierge prioritas</strong> (asistensi online untuk bagasi dan reservasi fleksibel).</li>
                </ul>
            </div>
        </div>

    </div>
</section>

<!-- Vanilla JS Logika UI (Letakkan di paling bawah view) -->
<style>
    /* Menyembunyikan scrollbar bawaan browser tapi tetap bisa di-scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
</style>
<script>
    // Inisialisasi: Cegah pemilihan tanggal masa lalu dan atur batasan max 30 hari
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const inputTanggalBerangkat = document.getElementById('input-tanggal');
        const inputTanggalPulang = document.getElementById('input-pulang');

        if (inputTanggalBerangkat) {
            inputTanggalBerangkat.min = today;
        }

        function updateReturnDateConstraints() {
            if (!inputTanggalBerangkat.value) return;
            
            const depDateStr = inputTanggalBerangkat.value;
            inputTanggalPulang.min = depDateStr;

            // Hitung batas maksimal 30 hari dari tanggal keberangkatan
            let maxDate = new Date(depDateStr);
            maxDate.setDate(maxDate.getDate() + 30);
            const maxDateStr = maxDate.toISOString().split('T')[0];
            inputTanggalPulang.max = maxDateStr;

            // Jika tanggal pulang melebihi batas 30 hari, sesuaikan otomatis
            if (inputTanggalPulang.value && inputTanggalPulang.value > maxDateStr) {
                inputTanggalPulang.value = maxDateStr;
            }
            // Jika tanggal pulang lebih kecil dari keberangkatan, sesuaikan otomatis
            if (inputTanggalPulang.value && inputTanggalPulang.value < depDateStr) {
                inputTanggalPulang.value = depDateStr;
            }
        }

        if (inputTanggalBerangkat) {
            inputTanggalBerangkat.addEventListener('change', updateReturnDateConstraints);
        }
    });

    // 1. Logika Switch Tanggal Pulang
    document.getElementById('toggle-pulang').addEventListener('change', function() {
        const inputPulang = document.getElementById('input-pulang');
        const inputBerangkat = document.getElementById('input-tanggal').value;
        const today = new Date().toISOString().split('T')[0];

        if(this.checked) {
            inputPulang.disabled = false;
            inputPulang.classList.remove('opacity-50', 'cursor-not-allowed');
            
            const minDate = (inputBerangkat && inputBerangkat > today) ? inputBerangkat : today;
            inputPulang.min = minDate;
            
            // Tentukan max date (30 hari dari tanggal berangkat)
            let maxDate = new Date(minDate);
            maxDate.setDate(maxDate.getDate() + 30);
            inputPulang.max = maxDate.toISOString().split('T')[0];

            inputPulang.value = minDate; 
        } else {
            inputPulang.disabled = true;
            inputPulang.classList.add('opacity-50', 'cursor-not-allowed');
            inputPulang.value = ''; 
        }
    });

    // 2. Validasi Sebelum Form Dikirim 
    function validateSearchForm() {
        const asal = document.getElementById('input-asal').value;
        const tujuan = document.getElementById('input-tujuan').value;
        const tanggal = document.getElementById('input-tanggal').value;
        const isPulangActive = document.getElementById('toggle-pulang').checked;
        const tanggalPulang = document.getElementById('input-pulang').value;
        const penumpang = document.getElementById('input-penumpang').value;

        if (!asal) {
            alert('Mohon pilih kota keberangkatan terlebih dahulu.');
            return false;
        }
        if (!tujuan) {
            alert('Mohon pilih kota tujuan terlebih dahulu.');
            return false;
        }
        if (asal === tujuan) {
            alert('Kota keberangkatan dan kota tujuan tidak boleh sama!');
            return false;
        }
        if (!tanggal) {
            alert('Mohon tentukan tanggal keberangkatan.');
            return false;
        }
        if (isPulangActive && !tanggalPulang) {
            alert('Mohon tentukan tanggal kembali karena opsi pulang aktif.');
            return false;
        }
        
        // Validasi Tanggal Kembali (Minimal sama dengan keberangkatan & Maksimal 30 hari)
        if (isPulangActive) {
            const depDate = new Date(tanggal);
            const retDate = new Date(tanggalPulang);
            
            if (retDate < depDate) {
                alert('Tanggal kembali tidak boleh lebih awal dari tanggal keberangkatan!');
                return false;
            }

            const maxAllowedDate = new Date(tanggal);
            maxAllowedDate.setDate(maxAllowedDate.getDate() + 30);
            
            if (retDate > maxAllowedDate) {
                alert('Maksimal pengambilan tanggal kembali adalah 30 hari dari tanggal keberangkatan!');
                return false;
            }
        }

        if (!penumpang) {
            alert('Mohon pilih jumlah penumpang.');
            return false;
        }
        return true;
    }

    // 3. Pencegahan Submit dengan "Enter" secara keseluruhan form
    document.getElementById('form-pencarian').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); 
        }
    });

    // 4. Logika Custom Dropdown
    function toggleDropdown(id) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            if(el.id !== id) {
                el.classList.add('hidden');
                el.parentElement.querySelector('.dropdown-arrow').classList.remove('rotate-180');
            }
        });
        
        const dropdown = document.getElementById(id);
        const arrow = dropdown.parentElement.querySelector('.dropdown-arrow');
        dropdown.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
        
        const searchInput = dropdown.querySelector('input[type="text"]');
        if(searchInput && !dropdown.classList.contains('hidden')) {
            searchInput.focus();
        }
    }

    function selectOption(target, labelText, value) {
        const label = document.getElementById('label-' + target);
        label.innerText = labelText;
        label.classList.remove('text-gray-400');
        label.classList.add('text-[#0F172A]', 'font-medium');
        
        document.getElementById('input-' + target).value = value;
        toggleDropdown('dropdown-' + target);
    }

    function filterDropdown(input, listId) {
        const filter = input.value.toUpperCase();
        const ul = document.getElementById(listId);
        const li = ul.getElementsByTagName('li');

        for (let i = 0; i < li.length; i++) {
            let txtValue = li[i].textContent || li[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    // Tutup dropdown jika klik di luar area
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.custom-dropdown')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                el.classList.add('hidden');
                el.parentElement.querySelector('.dropdown-arrow').classList.remove('rotate-180');
            });
        }
    });
</script>