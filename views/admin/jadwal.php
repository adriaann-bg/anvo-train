<?php 
$data['active_menu'] = 'jadwal';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>
    <!-- Sisipkan CSS DataTables & jQuery di Head -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Penyesuaian Style Dasar DataTables agar rapi dengan Tailwind -->
    <style>
        .dataTables_wrapper .dataTables_filter input { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.3rem 0.75rem; outline: none; margin-left: 0.5rem; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #8C6239; }
        .dataTables_wrapper .dataTables_length select { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.2rem 0.5rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #0F172A !important; color: white !important; border: none; border-radius: 0.5rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #8C6239 !important; color: white !important; border: none; border-radius: 0.5rem; }
        table.dataTable thead th { border-bottom: 2px solid #f1f5f9; }
        table.dataTable.no-footer { border-bottom: none; }
    </style>

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

        <main class="flex-1 p-8 lg:p-10 space-y-8 overflow-y-auto">

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
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Daftar Jadwal Operasional</h2>
                        <p class="text-xs text-slate-400">Menampilkan hasil rute aktif (Harian & Khusus).</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table id="jadwalTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4 text-center">No.</th>
                                <th class="py-3.5 px-4">Jenis</th>
                                <th class="py-3.5 px-4">Kereta & Kelas</th>
                                <th class="py-3.5 px-4">Rute Asal → Tujuan</th>
                                <th class="py-3.5 px-4">Waktu & Tanggal</th>
                                <th class="py-3.5 px-4">Harga Tiket</th>
                                <th class="py-3.5 px-4 text-center">Map</th>
                                <th class="py-3.5 px-4 text-center">Aksi (Detail, Edit, Crew)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(!empty($data['jadwal'])): ?>
                                <?php $no = 1; foreach($data['jadwal'] as $j): ?>
                                    <?php 
                                        // AMAN DARI FATAL ERROR: Normalisasi JSON untuk Modal
                                        $transitRaw = json_decode($j['stasiun_transit'], true);
                                        if (is_string($transitRaw)) { $transitRaw = json_decode($transitRaw, true); }
                                        
                                        $transitData = [];
                                        if (is_array($transitRaw)) {
                                            foreach ($transitRaw as $item) {
                                                if (is_array($item)) {
                                                    $transitData[] = $item;
                                                } else {
                                                    $transitData[] = ['nama' => $item, 'waktu' => ''];
                                                }
                                            }
                                        } else {
                                            $transitData = [
                                                ['nama' => $j['stasiun_asal'], 'waktu' => $j['jam_berangkat']],
                                                ['nama' => $j['stasiun_tujuan'], 'waktu' => $j['jam_tiba']]
                                            ];
                                        }
                                        
                                        $namaTransitSederhana = array_column($transitData, 'nama');
                                        $totalTransit = count($transitData) > 2 ? count($transitData) - 2 : 0;
                                        
                                        $fullStasiun = $j['full_stasiun'] ?? [];
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
                                            $ruteAktual = $fullStasiun;
                                        }
                                        $skippedStasiun = array_diff($ruteAktual, $namaTransitSederhana);
                                        $totalDilewati = count($skippedStasiun);
                                    ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500 text-center"><?= $no++ ?></td>
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
                                            <button type="button" onclick="openModal('modal-map-<?= $j['id_jadwal'] ?>')" class="w-10 h-10 bg-indigo-50 text-indigo-500 hover:bg-indigo-500 hover:text-white rounded-xl text-sm transition-all shadow-sm flex items-center justify-center mx-auto" title="Lihat Peta Rute">
                                                <i class="fa-solid fa-map-location-dot"></i>
                                            </button>
                                        </td>

                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button" onclick="openModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Detail">
                                                    <i class="fa-solid fa-circle-info"></i> Detail
                                                </button>
                                                <a href="/anvo/public/jadwal/edit_page/<?= $j['id_jadwal'] ?>" class="px-2.5 py-1.5 bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </a>
                                                <a href="/anvo/public/jadwal/crew/<?= $j['id_jadwal'] ?>" class="px-2.5 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl text-xs font-semibold transition-all" title="Penugasan Crew">
                                                    <i class="fa-solid fa-user-shield"></i> Crew
                                                </a>
                                                <a href="/anvo/public/jadwal/hapus/<?= $j['id_jadwal'] ?>" onclick="return confirm('Hapus jadwal ini?')" class="p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL MAP -->
                                    <div id="modal-map-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar border border-slate-200/60 text-left">
                                            <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                                                <div class="flex items-center gap-4">
                                                    <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO" class="w-10 h-10 object-contain">
                                                    <div>
                                                        <span class="text-[10px] uppercase font-bold text-[#8C6239] tracking-wider block">Official Route Map</span>
                                                        <h3 class="font-extrabold text-xl text-[#0F172A]">Jadwal #<?= $j['id_jadwal'] ?>: <?= explode(' - ', $j['stasiun_asal'])[0] ?> - <?= explode(' - ', $j['stasiun_tujuan'])[0] ?></h3>
                                                    </div>
                                                </div>
                                                <button type="button" onclick="closeModal('modal-map-<?= $j['id_jadwal'] ?>')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                                            </div>

                                            <div class="space-y-3 relative pt-2">
                                                <h5 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-4">Linier Rute Perjalanan & Jam Singgah</h5>
                                                <div class="p-6 bg-slate-50/80 rounded-3xl border border-slate-200/60 space-y-4 relative pl-8 before:absolute before:left-5 before:top-4 before:bottom-4 before:w-0.5 before:bg-[#8C6239]">
                                                    <?php foreach($transitData as $index => $tst): 
                                                        $isAkhir = ($index == count($transitData) - 1);
                                                    ?>
                                                        <div class="relative flex items-center justify-between bg-white p-3.5 rounded-2xl border border-slate-200/60 shadow-sm">
                                                            <div class="absolute -left-8 w-3 h-3 rounded-full bg-[#8C6239] border-2 border-slate-50 ring-2 ring-[#8C6239]/20"></div>
                                                            <div>
                                                                <span class="font-bold text-sm text-[#0F172A] block"><?= htmlspecialchars($tst['nama']) ?></span>
                                                                <?php if(!empty($tst['waktu'])): ?>
                                                                    <span class="text-xs text-slate-400 font-semibold"><i class="fa-regular fa-clock mr-1 text-[#2B9BFB]"></i> <?= $tst['waktu'] ?> WIB</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            
                                                            <?php if($index == 0): ?>
                                                                <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg font-bold">Berangkat</span>
                                                            <?php elseif($isAkhir): ?>
                                                                <span class="text-[10px] bg-rose-50 text-rose-500 px-2.5 py-1 rounded-lg font-bold">Tiba</span>
                                                            <?php else: ?>
                                                                <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2.5 py-1 rounded-lg font-bold">Transit</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

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

                                    <!-- MODAL DETAIL -->
                                    <div id="modal-detail-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 shadow-2xl space-y-5 animate-fade-in text-left">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-base text-[#0F172A]"><i class="fa-solid fa-circle-info text-[#2B9BFB] mr-2"></i> Detail Jadwal #<?= $j['id_jadwal'] ?></h3>
                                                <button type="button" onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            
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
                                            <button type="button" onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="w-full bg-[#0F172A] hover:bg-slate-800 text-white py-3 rounded-xl font-bold text-sm transition-all shadow-md">Tutup Detail</button>
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

    <script>
        // Init DataTables (Fitur Tabel Paginated & Sortir)
        $(document).ready(function() {
            $('#jadwalTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ada data jadwal yang sesuai",
                    "info": "Halaman _PAGE_ dari _PAGES_ (Total: _MAX_ jadwal)",
                    "infoEmpty": "Tidak ada jadwal tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "search": "Cari Cepat:",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Lanjut",
                        "previous": "Kembali"
                    }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6, 7] } // Index 0 (No.), 6 (Map), 7 (Aksi) tidak bisa disortir
                ]
            });
        });

        // FUNGSI UNTUK MEMBUKA DAN MENUTUP MODAL
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        setTimeout(function() {
            const alertBox = document.getElementById('flash-alert');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);

        document.getElementById('btn-swap-filter').addEventListener('click', function() {
            const selectAsal = document.getElementById('filter-asal');
            const selectTujuan = document.getElementById('filter-tujuan');
            
            const temp = selectAsal.value;
            selectAsal.value = selectTujuan.value;
            selectTujuan.value = temp;

            const icon = this.querySelector('i');
            icon.classList.add('fa-spin');
            setTimeout(() => icon.classList.remove('fa-spin'), 300);
        });
    </script>

<?php 
require_once __DIR__ . '/../layouts/admin/footer.php'; 
?>