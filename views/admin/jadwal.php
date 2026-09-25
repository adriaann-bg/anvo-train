<?php 
$data['active_menu'] = 'jadwal';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- TOP BAR -->
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Manajemen Kelola Jadwal & Filter</h1>
                <p class="text-xs text-slate-400 font-medium">Sistem filter operasional dan penjadwalan kereta cepat.</p>
            </div>

            <div>
                <a href="/anvo/public/jadwal/tambah_page" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Buat Jadwal Baru
                </a>
            </div>
        </header>

        <!-- CONTENT BODY -->
        <main class="flex-1 p-8 lg:p-10 space-y-8 overflow-y-auto">

            <!-- Flash Toast Notification dengan ID agar bisa di-fade out -->
            <?php if (isset($_SESSION['success'])): ?>
                <div id="flash-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm flex items-center justify-between transition-all duration-500">
                    <span class="text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i> <?= $_SESSION['success'] ?>
                    </span>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- KOTAK FILTER PENCARIAN -->
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
                <form action="/anvo/public/jadwal" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Filter Tanggal</label>
                        <input type="date" name="tanggal" value="<?= $data['filter']['tanggal'] ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Kelas Kereta</label>
                        <select name="kelas" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="">Semua Kelas</option>
                            <option value="Executive Prime" <?= ($data['filter']['kelas'] == 'Executive Prime') ? 'selected' : '' ?>>Executive Prime</option>
                            <option value="Luminary Capsule" <?= ($data['filter']['kelas'] == 'Luminary Capsule') ? 'selected' : '' ?>>Luminary Capsule</option>
                            <option value="VVIP Skybox Suite" <?= ($data['filter']['kelas'] == 'VVIP Skybox Suite') ? 'selected' : '' ?>>VVIP Skybox Suite</option>
                        </select>
                    </div>

                    <!-- KOTA KEBERANGKATAN & TUJUAN DENGAN TOMBOL SWAP -->
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-11 gap-2 items-end">
                        <div class="sm:col-span-5">
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Kota Keberangkatan</label>
                            <select id="filter-asal" name="asal" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                <option value="">Semua Asal</option>
                                <?php foreach($data['stasiun'] as $s): ?>
                                    <option value="<?= $s['nama_stasiun'] ?>" <?= ($data['filter']['asal'] == $s['nama_stasiun']) ? 'selected' : '' ?>><?= $s['nama_stasiun'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Tombol Tukar Posisi (Swap) -->
                        <div class="sm:col-span-1 flex justify-center pb-1">
                            <button type="button" id="btn-swap-filter" class="w-9 h-9 bg-slate-100 hover:bg-[#8C6239] hover:text-white text-slate-600 rounded-xl flex items-center justify-center transition-all shadow-sm" title="Tukar Keberangkatan & Tujuan">
                                <i class="fa-solid fa-arrow-right-arrow-left text-xs"></i>
                            </button>
                        </div>

                        <div class="sm:col-span-5">
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Kota Tujuan</label>
                            <select id="filter-tujuan" name="tujuan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                <option value="">Semua Tujuan</option>
                                <?php foreach($data['stasiun'] as $s): ?>
                                    <option value="<?= $s['nama_stasiun'] ?>" <?= ($data['filter']['tujuan'] == $s['nama_stasiun']) ? 'selected' : '' ?>><?= $s['nama_stasiun'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-[#8C6239] hover:bg-[#74502e] text-white py-2.5 rounded-xl font-semibold text-sm transition-all shadow-sm">
                            <i class="fa-solid fa-filter mr-1"></i> Cari
                        </button>
                        <a href="/anvo/public/jadwal" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition-all text-center flex items-center justify-center" title="Reset Filter">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- TABEL JADWAL KELOLA -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Daftar Jadwal Operasional</h2>
                        <p class="text-xs text-slate-400">Menampilkan hasil rute aktif (Harian & Khusus).</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Jenis Jadwal</th>
                                <th class="py-3.5 px-4">Kereta & Kelas</th>
                                <th class="py-3.5 px-4">Rute Asal → Tujuan</th>
                                <th class="py-3.5 px-4">Waktu & Tanggal</th>
                                <th class="py-3.5 px-4">Harga Tiket</th>
                                <th class="py-3.5 px-4 text-center">Map</th>
                                <th class="py-3.5 px-4 text-center">Aksi (Detail, Edit, Crew)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['jadwal'])): ?>
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400 font-medium">Tidak ada jadwal yang sesuai dengan filter pencarian.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($data['jadwal'] as $j): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-4 px-4">
                                            <span class="text-[10px] px-2.5 py-1 rounded-full font-bold <?= ($j['jenis_jadwal'] == 'Harian') ? 'bg-emerald-50 text-emerald-600' : 'bg-purple-50 text-purple-600' ?>">
                                                <?= $j['jenis_jadwal'] ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-bold text-[#0F172A] block"><?= $j['nama_kereta'] ?></span>
                                            <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded-md font-bold"><?= $j['jenis_kelas'] ?></span>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-slate-700">
                                            <?= explode(' - ', $j['stasiun_asal'])[0] ?> &rarr; <?= explode(' - ', $j['stasiun_tujuan'])[0] ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-bold text-slate-800 block"><?= $j['jam_berangkat'] ?> - <?= $j['jam_tiba'] ?> WIB</span>
                                            <span class="text-xs text-slate-400">
                                                <?= date('d M Y', strtotime($j['tanggal_mulai'])) ?> s.d. <?= date('d M Y', strtotime($j['tanggal_akhir'])) ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 font-bold text-[#8C6239]">
                                            Rp <?= number_format($j['harga'], 0, ',', '.') ?>
                                        </td>
                                        
                                        <!-- Tombol Modal MAP -->
                                        <td class="py-4 px-4 text-center">
                                            <button onclick="openModal('modal-map-<?= $j['id_jadwal'] ?>')" class="w-10 h-10 bg-indigo-50 text-indigo-500 hover:bg-indigo-500 hover:text-white rounded-xl text-sm transition-all shadow-sm flex items-center justify-center mx-auto" title="Lihat Peta Rute">
                                                <i class="fa-solid fa-map-location-dot"></i>
                                            </button>
                                        </td>

                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button onclick="openModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Detail">
                                                    <i class="fa-solid fa-circle-info"></i> Detail
                                                </button>
                                                <button onclick="openModal('modal-edit-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </button>
                                                <a href="/anvo/public/jadwal/crew/<?= $j['id_jadwal'] ?>" class="px-2.5 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl text-xs font-semibold transition-all" title="Penugasan Crew">
                                                    <i class="fa-solid fa-user-shield"></i> Crew
                                                </a>
                                                <a href="/anvo/public/jadwal/hapus/<?= $j['id_jadwal'] ?>" onclick="return confirm('Hapus jadwal ini?')" class="p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL POPUP: MAP RUTE KHUSUS JADWAL INI -->
                                    <div id="modal-map-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar border border-slate-200/60">
                                            <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                                                <div class="flex items-center gap-4">
                                                    <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO" class="w-10 h-10 object-contain">
                                                    <div>
                                                        <span class="text-[10px] uppercase font-bold text-[#8C6239] tracking-wider block">Official Route Map</span>
                                                        <h3 class="font-extrabold text-xl text-[#0F172A]">Jadwal #<?= $j['id_jadwal'] ?>: <?= explode(' - ', $j['stasiun_asal'])[0] ?> - <?= explode(' - ', $j['stasiun_tujuan'])[0] ?></h3>
                                                    </div>
                                                </div>
                                                <button onclick="closeModal('modal-map-<?= $j['id_jadwal'] ?>')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            
                                            <?php 
                                            // 1. Ambil data transit dari JSON
                                            $transitData = json_decode($j['stasiun_transit'], true);
                                            if(!is_array($transitData)) {
                                                $transitData = [$j['stasiun_asal'], $j['stasiun_tujuan']];
                                            }
                                            $fullStasiun = $j['full_stasiun'] ?? [];
                                            
                                            // 2. Ambil HANYA stasiun yang berada di antara Asal dan Tujuan (Rute Aktual Segmental)
                                            $idxAsal = array_search($j['stasiun_asal'], $fullStasiun);
                                            $idxTujuan = array_search($j['stasiun_tujuan'], $fullStasiun);
                                            
                                            $ruteAktual = [];
                                            if($idxAsal !== false && $idxTujuan !== false) {
                                                $start = min($idxAsal, $idxTujuan);
                                                $end = max($idxAsal, $idxTujuan);
                                                for($i = $start; $i <= $end; $i++) {
                                                    $ruteAktual[] = $fullStasiun[$i];
                                                }
                                            } else {
                                                $ruteAktual = $fullStasiun; // Fallback jika tidak terdeteksi
                                            }
                                            
                                            // 3. Cari stasiun yang dilewati: Ada di $ruteAktual tapi TIDAK ADA di $transitData
                                            $skippedStasiun = array_diff($ruteAktual, $transitData);
                                            ?>

                                            <!-- Peta Linier (Hanya yang Transit) -->
                                            <div class="space-y-3 relative pt-2">
                                                <h5 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-4">Linier Rute Perjalanan</h5>
                                                <div class="p-6 bg-slate-50/80 rounded-3xl border border-slate-200/60 space-y-4 relative pl-8 before:absolute before:left-5 before:top-4 before:bottom-4 before:w-0.5 before:bg-[#8C6239]">
                                                    <?php foreach($transitData as $index => $tst): 
                                                        $isAkhir = ($index == count($transitData) - 1);
                                                    ?>
                                                        <div class="relative flex items-center justify-between bg-white p-3.5 rounded-2xl border border-slate-200/60 shadow-sm">
                                                            <div class="absolute -left-8 w-3 h-3 rounded-full bg-[#8C6239] border-2 border-slate-50 ring-2 ring-[#8C6239]/20"></div>
                                                            <span class="font-bold text-sm text-[#0F172A]"><?= htmlspecialchars($tst) ?></span>
                                                            
                                                            <?php if($index == 0): ?>
                                                                <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg font-bold">Berangkat</span>
                                                            <?php elseif($isAkhir): ?>
                                                                <span class="text-[10px] bg-rose-50 text-rose-500 px-2.5 py-1 rounded-lg font-bold">Tiba</span>
                                                            <?php else: ?>
                                                                <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2.5 py-1 rounded-lg font-bold">Transit Utama</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <!-- Kotak Khusus Stasiun yang Di-skip (Dilewati Langsung) -->
                                            <?php if(!empty($skippedStasiun)): ?>
                                            <div class="mt-6 p-4 bg-slate-50 border border-slate-200 border-dashed rounded-2xl">
                                                <span class="text-[10px] font-bold text-slate-400 block mb-2 uppercase tracking-wider"><i class="fa-solid fa-forward-step mr-1.5"></i> Langsung / Tidak Transit Di Stasiun:</span>
                                                <div class="flex flex-wrap gap-2">
                                                    <?php foreach($skippedStasiun as $skip): ?>
                                                        <span class="text-xs text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg font-medium line-through">
                                                            <?= explode(' - ', $skip)[0] ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                    <!-- End Modal MAP -->

                                    <!-- MODAL DETAIL JADWAL (REVISI RATA KANAN & INFO LENGKAP) -->
                                    <div id="modal-detail-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 shadow-2xl space-y-5 animate-fade-in">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-base text-[#0F172A]"><i class="fa-solid fa-circle-info text-[#2B9BFB] mr-2"></i> Detail Jadwal #<?= $j['id_jadwal'] ?></h3>
                                                <button onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            
                                            <?php 
                                            $transitData = json_decode($j['stasiun_transit'], true) ?? [$j['stasiun_asal'], $j['stasiun_tujuan']];
                                            $totalTransit = count($transitData) > 2 ? count($transitData) - 2 : 0;
                                            
                                            $fullStasiun = $j['full_stasiun'] ?? [];
                                            $idxAsal = array_search($j['stasiun_asal'], $fullStasiun);
                                            $idxTujuan = array_search($j['stasiun_tujuan'], $fullStasiun);
                                            $ruteAktual = ($idxAsal !== false && $idxTujuan !== false) ? array_slice($fullStasiun, min($idxAsal, $idxTujuan), abs($idxAsal - $idxTujuan) + 1) : $fullStasiun;
                                            $totalDilewati = count(array_diff($ruteAktual, $transitData));
                                            ?>

                                            <div class="space-y-2.5 text-sm text-slate-600">
                                                <div class="flex justify-between items-center bg-slate-50 px-3.5 py-2.5 rounded-xl">
                                                    <span class="font-medium text-slate-500">Jenis Jadwal:</span> 
                                                    <strong class="text-[#0F172A] text-right"><?= $j['jenis_jadwal'] ?></strong>
                                                </div>
                                                <div class="flex justify-between items-center bg-slate-50 px-3.5 py-2.5 rounded-xl">
                                                    <span class="font-medium text-slate-500">Armada Kereta:</span> 
                                                    <strong class="text-[#0F172A] text-right"><?= $j['nama_kereta'] ?> <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-1.5 py-0.5 rounded ml-1"><?= $j['jenis_kelas'] ?></span></strong>
                                                </div>
                                                <div class="flex justify-between items-center bg-slate-50 px-3.5 py-2.5 rounded-xl">
                                                    <span class="font-medium text-slate-500">Waktu Operasional:</span> 
                                                    <strong class="text-[#0F172A] text-right"><?= $j['jam_berangkat'] ?> - <?= $j['jam_tiba'] ?> WIB</strong>
                                                </div>
                                                <div class="flex justify-between items-center bg-slate-50 px-3.5 py-2.5 rounded-xl">
                                                    <span class="font-medium text-slate-500">Rentang Tanggal:</span> 
                                                    <strong class="text-[#0F172A] text-right"><?= date('d M Y', strtotime($j['tanggal_mulai'])) ?> s.d. <?= date('d M Y', strtotime($j['tanggal_akhir'])) ?></strong>
                                                </div>
                                                <div class="flex justify-between items-center bg-amber-50/50 border border-amber-200/60 px-3.5 py-2.5 rounded-xl">
                                                    <span class="font-bold text-[#8C6239]">Tarif Tiket:</span> 
                                                    <strong class="text-[#8C6239] text-right">Rp <?= number_format($j['harga'], 0, ',', '.') ?></strong>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3 pt-1">
                                                <div class="bg-emerald-50 border border-emerald-100 p-3 rounded-xl text-center">
                                                    <span class="block text-[10px] uppercase font-bold text-emerald-600">Total Transit</span>
                                                    <span class="text-base font-extrabold text-[#0F172A]"><?= $totalTransit ?> Stasiun</span>
                                                </div>
                                                <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl text-center">
                                                    <span class="block text-[10px] uppercase font-bold text-rose-500">Dilewati (Skip)</span>
                                                    <span class="text-base font-extrabold text-[#0F172A]"><?= $totalDilewati ?> Stasiun</span>
                                                </div>
                                            </div>

                                            <button onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="w-full bg-[#0F172A] hover:bg-slate-800 text-white py-3 rounded-xl font-bold text-sm transition-all shadow-md">Tutup Detail</button>
                                        </div>
                                    </div>
                                    <div id="modal-edit-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 shadow-2xl space-y-4 animate-fade-in">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-base text-[#0F172A]"><i class="fa-solid fa-pen-to-square text-[#8C6239] mr-2"></i> Edit Jadwal</h3>
                                                <button onclick="closeModal('modal-edit-<?= $j['id_jadwal'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            <p class="text-xs text-slate-500">Fitur form pembaruan data jadwal ini dapat disesuaikan dengan aksi Controller update.</p>
                                            <button onclick="closeModal('modal-edit-<?= $j['id_jadwal'] ?>')" class="w-full bg-[#0F172A] text-white py-2.5 rounded-xl font-semibold text-xs">Tutup</button>
                                        </div>
                                    </div>
                                    <div id="modal-crew-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 shadow-2xl space-y-4 animate-fade-in">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-base text-[#0F172A]"><i class="fa-solid fa-user-shield text-emerald-500 mr-2"></i> Penugasan Kru Onboard</h3>
                                                <button onclick="closeModal('modal-crew-<?= $j['id_jadwal'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            <div class="space-y-3 text-xs">
                                                <div class="bg-slate-50 p-3 rounded-xl flex justify-between items-center">
                                                    <div><strong>Masinis:</strong> Capt. Budi Hartono</div>
                                                    <span class="text-emerald-600 font-bold">Siap</span>
                                                </div>
                                                <div class="bg-slate-50 p-3 rounded-xl flex justify-between items-center">
                                                    <div><strong>Kondektur:</strong> Siska Amelia</div>
                                                    <span class="text-emerald-600 font-bold">Siap</span>
                                                </div>
                                            </div>
                                            <button onclick="closeModal('modal-crew-<?= $j['id_jadwal'] ?>')" class="w-full bg-[#0F172A] text-white py-2.5 rounded-xl font-semibold text-xs">Tutup</button>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL POPUP: TAMBAH JADWAL BARU -->
    <div id="modal-tambah-jadwal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-lg text-[#0F172A]">Tambah Jadwal Kereta</h3>
                    <p class="text-xs text-slate-400">Kereta yang sedang maintenance otomatis disembunyikan.</p>
                </div>
                <button onclick="closeModal('modal-tambah-jadwal')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-700 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form id="form-tambah-jadwal" action="/anvo/public/jadwal/tambah" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Jenis Jadwal</label>
                    <select name="jenis_jadwal" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                        <option value="Harian">Jadwal Harian (Reguler)</option>
                        <option value="Khusus">Jadwal Khusus (Event/Ekstra)</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Koridor Jalur</label>
                    <select id="filter-koridor-jadwal" name="id_koridor" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                        <option value="">-- Pilih Koridor Perjalanan --</option>
                        <?php foreach($data['koridor_list'] ?? [] as $kor): ?>
                            <option value="<?= $kor['id_koridor'] ?>"><?= $kor['nama_koridor'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Armada Kereta</label>
                    <select id="select-armada-jadwal" name="id_kereta" required disabled class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239] disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="">-- Pilih Koridor Terlebih Dahulu --</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Stasiun Asal</label>
                        <select name="stasiun_asal" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <?php foreach($data['stasiun'] as $s): ?>
                                <option value="<?= $s['nama_stasiun'] ?>"><?= $s['nama_stasiun'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Stasiun Tujuan</label>
                        <select name="stasiun_tujuan" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <?php foreach($data['stasiun'] as $s): ?>
                                <option value="<?= $s['nama_stasiun'] ?>"><?= $s['nama_stasiun'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Jam Berangkat</label>
                        <input type="time" name="jam_berangkat" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Jam Tiba</label>
                        <input type="time" name="jam_tiba" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Harga Tiket (Rp)</label>
                    <input type="number" name="harga" placeholder="Contoh: 250000" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                </div>

                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md mt-4">
                    Simpan Jadwal Baru
                </button>
            </form>
        </div>
    </div>

    <script>
        setTimeout(function() {
            const alertBox = document.getElementById('flash-alert');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);

        // 1. AJAX Mengambil Kereta Berdasarkan Koridor
        document.getElementById('filter-koridor-jadwal').addEventListener('change', function() {
            const idKoridor = this.value;
            const selectArmada = document.getElementById('select-armada-jadwal');
            
            // Reset dropdown saat loading
            selectArmada.innerHTML = '<option value="">-- Sedang memuat armada... --</option>';
            selectArmada.disabled = true;

            if (!idKoridor) {
                selectArmada.innerHTML = '<option value="">-- Pilih Koridor Terlebih Dahulu --</option>';
                return;
            }

            // Fetch data ke JadwalController
            fetch('/anvo/public/jadwal/get_kereta_ajax/' + idKoridor)
                .then(response => response.json())
                .then(data => {
                    selectArmada.innerHTML = '<option value="">-- Pilih Armada Kereta --</option>';
                    
                    if(data.length === 0) {
                        selectArmada.innerHTML = '<option value="">-- Tidak ada armada terdaftar di koridor ini --</option>';
                    } else {
                        // Looping data kereta
                        data.forEach(k => {
                            const option = document.createElement('option');
                            option.value = k.id_kereta;
                            // Simpan status operasional di atribut custom 'data-status'
                            option.setAttribute('data-status', k.status_operasional);
                            
                            // Format Text: Nama - Kecepatan - Status
                            option.textContent = `${k.nama_kereta} (${k.kecepatan_maksimal} km/h) - Status: ${k.status_operasional}`;
                            selectArmada.appendChild(option);
                        });
                        selectArmada.disabled = false; // Aktifkan dropdown
                    }
                })
                .catch(err => {
                    console.error('Error fetching kereta:', err);
                    selectArmada.innerHTML = '<option value="">-- Gagal memuat armada --</option>';
                });
        });

        // 2. Cegah Simpan Jika Kereta Tidak Aktif
        document.getElementById('form-tambah-jadwal').addEventListener('submit', function(e) {
            const selectArmada = document.getElementById('select-armada-jadwal');
            const selectedOption = selectArmada.options[selectArmada.selectedIndex];
            
            if (selectedOption) {
                const status = selectedOption.getAttribute('data-status');
                // Jika statusnya bukan Aktif, blokir proses submit!
                if (status && status !== 'Aktif') {
                    e.preventDefault(); // Hentikan form submit
                    alert(`TIDAK DAPAT DISIMPAN!\n\nArmada kereta tidak dapat dijadwalkan karena sedang dalam status: [${status}].\nSilakan pilih armada yang berstatus Aktif.`);
                }
            }
        });

        // Script Tombol Putar Arah (Swap) Filter Jadwal
        document.getElementById('btn-swap-filter').addEventListener('click', function() {
            const selectAsal = document.getElementById('filter-asal');
            const selectTujuan = document.getElementById('filter-tujuan');
            
            // Tukar nilai (value) dropdown Asal dan Tujuan
            const temp = selectAsal.value;
            selectAsal.value = selectTujuan.value;
            selectTujuan.value = temp;

            // Efek putar halus pada ikon tombol saat diklik
            const icon = this.querySelector('i');
            icon.classList.add('fa-spin');
            setTimeout(() => icon.classList.remove('fa-spin'), 300);
        });
    </script>

<?php 
require_once __DIR__ . '/../layouts/admin/footer.php'; 
?>