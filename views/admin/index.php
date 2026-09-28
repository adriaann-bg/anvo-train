<?php 
$data['active_menu'] = 'dashboard';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>

<!-- DataTables CSS & JS CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
    .dataTables_wrapper .dataTables_filter input { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.3rem 0.75rem; outline: none; margin-left: 0.5rem; }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #8C6239; }
    .dataTables_wrapper .dataTables_length select { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.2rem 0.5rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #0F172A !important; color: white !important; border: none; border-radius: 0.5rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #8C6239 !important; color: white !important; border: none; border-radius: 0.5rem; }
    table.dataTable thead th { border-bottom: 2px solid #f1f5f9; }
    table.dataTable.no-footer { border-bottom: none; }
</style>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50/50">

        <!-- TOP BAR -->
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-40 shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Pusat Kendali Operasional Kereta Cepat</h1>
                <p class="text-xs text-slate-400 font-medium">Monitoring real-time perjalanan, manifes penumpang, dan kesiapan kru.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200/60 text-xs font-semibold text-slate-600">
                    <i class="fa-regular fa-calendar-days text-[#8C6239]"></i>
                    <span><?= date('d M Y') ?></span>
                </div>
            </div>
        </header>

        <!-- DASHBOARD BODY -->
        <main class="flex-1 p-8 lg:p-10 space-y-8 overflow-y-auto">

            <?php if (isset($_SESSION['success'])): ?>
                <div id="flash-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm flex items-center justify-between transition-all duration-500">
                    <span class="text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i> <?= $_SESSION['success'] ?></span>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- TOP METRICS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perjalanan Filter</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#8C6239] flex items-center justify-center font-bold">
                            <i class="fa-solid fa-train-subway"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= count($data['daily_operations']) ?></h3>
                        <p class="text-[11px] text-emerald-500 font-semibold mt-1"><i class="fa-solid fa-calendar-check"></i> Pada rentang terpilih</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Armada Kereta</span>
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-50 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-gauge-high"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= count($data['kereta']) ?></h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">Series Aktif Keseluruhan</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Penumpang</span>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= $data['total_penumpang'] ?></h3>
                        <p class="text-[11px] text-purple-500 font-semibold mt-1">Manifes Keseluruhan Sistem</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kesiapan Kru</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= $data['total_kru_aktif'] ?></h3>
                        <p class="text-[11px] text-emerald-500 font-semibold mt-1">Kru Status Aktif</p>
                    </div>
                </div>
            </div>

            <!-- CARD FILTER -->
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-200/60 shadow-sm relative overflow-visible">
                <form action="/anvo/public/admin" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                    <div class="lg:col-span-2 grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Tanggal Awal</label>
                            <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($data['filter']['start_date']) ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Tanggal Akhir (Maks 30 Hari)</label>
                            <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($data['filter']['end_date']) ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Kelas Kereta</label>
                        <select name="kelas" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="">Semua Kelas</option>
                            <option value="Executive Prime" <?= ($data['filter']['kelas'] == 'Executive Prime') ? 'selected' : '' ?>>Executive Prime</option>
                            <option value="Luminary Capsule" <?= ($data['filter']['kelas'] == 'Luminary Capsule') ? 'selected' : '' ?>>Luminary Capsule</option>
                            <option value="VVIP Skybox Suite" <?= ($data['filter']['kelas'] == 'VVIP Skybox Suite') ? 'selected' : '' ?>>VVIP Skybox Suite</option>
                        </select>
                    </div>
                    <div class="lg:col-span-1">
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Keberangkatan</label>
                        <select id="filter-asal" name="asal" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="">Semua Asal</option>
                            <?php foreach($data['stasiun'] as $s): ?>
                                <option value="<?= htmlspecialchars($s['nama_stasiun']) ?>" <?= ($data['filter']['asal'] == $s['nama_stasiun']) ? 'selected' : '' ?>><?= htmlspecialchars(explode(' - ', $s['nama_stasiun'])[0]) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="lg:col-span-1">
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Tujuan</label>
                        <select id="filter-tujuan" name="tujuan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="">Semua Tujuan</option>
                            <?php foreach($data['stasiun'] as $s): ?>
                                <option value="<?= htmlspecialchars($s['nama_stasiun']) ?>" <?= ($data['filter']['tujuan'] == $s['nama_stasiun']) ? 'selected' : '' ?>><?= htmlspecialchars(explode(' - ', $s['nama_stasiun'])[0]) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="lg:col-span-1 flex gap-2">
                        <button type="submit" class="flex-1 bg-[#8C6239] hover:bg-[#74502e] text-white py-2.5 rounded-xl font-semibold text-sm transition-all shadow-sm"><i class="fa-solid fa-filter mr-1"></i> Filter</button>
                        <a href="/anvo/public/admin" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition-all flex justify-center"><i class="fa-solid fa-rotate-right"></i></a>
                    </div>
                </form>
            </div>

            <!-- TABEL UTAMA OPERASIONAL HARIAN -->
            <div id="tabel-kereta" class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Monitor Operasional Per Hari</h2>
                        <p class="text-xs text-slate-400">Daftar operasi jadwal beserta detail manifes dan pencarian kru/penumpang per tanggal.</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table id="dataTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">Tanggal & Waktu</th>
                                <th class="py-3.5 px-4">Kereta & Series</th>
                                <th class="py-3.5 px-4">Stasiun Berangkat</th>
                                <th class="py-3.5 px-4">Stasiun Tujuan</th>
                                <th class="py-3.5 px-4 text-center">Manifes</th>
                                <th class="py-3.5 px-4 text-center">Kru</th>
                                <th class="py-3.5 px-4 text-center">Aksi & Pencarian</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(!empty($data['daily_operations'])): ?>
                                <?php foreach($data['daily_operations'] as $op): 
                                    $kruList = $op['kru_list'];
                                    $penumpangList = $op['penumpang_list'];
                                    $totalKru = count($kruList);
                                    $totalPenumpang = count($penumpangList);
                                    $modalId = "modal-detail-" . $op['id_jadwal'] . "-" . $op['tanggal_operasional'];
                                    
                                    // Raw timestamp untuk pengurutan DataTables yang akurat
                                    $rawSortDate = date('Y-m-d H:i:s', strtotime($op['tanggal_operasional'] . ' ' . $op['jam_berangkat']));
                                ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-[#8C6239] whitespace-nowrap" data-sort="<?= $rawSortDate ?>">
                                            <i class="fa-regular fa-calendar-check mr-1.5"></i> <?= date('d M Y', strtotime($op['tanggal_operasional'])) ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="block font-bold text-[#0F172A]"><?= htmlspecialchars($op['nama_kereta']) ?></span>
                                            <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded-lg font-bold mt-1 inline-block"><?= htmlspecialchars($op['jenis_kelas']) ?></span>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-slate-700"><?= htmlspecialchars($op['stasiun_asal']) ?> <span class="text-[11px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded ml-1 font-bold"><?= htmlspecialchars($op['jam_berangkat']) ?></span></td>
                                        <td class="py-4 px-4 font-semibold text-slate-700"><?= htmlspecialchars($op['stasiun_tujuan']) ?> <span class="text-[11px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded ml-1 font-bold"><?= htmlspecialchars($op['jam_tiba']) ?></span></td>
                                        <td class="py-4 px-4 text-center font-bold <?= $totalPenumpang > 0 ? 'text-purple-600' : 'text-slate-400' ?>"><?= $totalPenumpang ?> Penumpang</td>
                                        <td class="py-4 px-4 text-center font-bold <?= $totalKru > 0 ? 'text-emerald-600' : 'text-slate-400' ?>"><?= $totalKru ?> Kru</td>
                                        
                                        <!-- KOLOM 7: TOMBOL AKSI -->
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex flex-col gap-1.5">
                                                <button onclick="openModal('modal-detail-jadwal-op-<?= $op['id_jadwal'] . '-' . $op['tanggal_operasional'] ?>')" class="px-3 py-1.5 bg-[#0F172A] text-white hover:bg-[#8C6239] rounded-xl text-[11px] font-semibold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                                    <i class="fa-solid fa-train"></i> Detail & Kursi
                                                </button>
                                                <button onclick="openModal('<?= $modalId ?>')" class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-[#0F172A] hover:text-white rounded-xl text-[11px] font-semibold transition-all flex items-center justify-center gap-1.5">
                                                    <i class="fa-solid fa-file-lines"></i> Cek Dokumen
                                                </button>
                                                <div class="flex gap-1.5">
                                                    <button onclick="openModal('modal-cari-penumpang-<?= $modalId ?>')" class="flex-1 px-2 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white rounded-xl text-[11px] font-bold transition-all flex items-center justify-center gap-1" title="Cari Penumpang">
                                                        <i class="fa-solid fa-magnifying-glass"></i> Penumpang
                                                    </button>
                                                    <button onclick="openModal('modal-cari-kru-<?= $modalId ?>')" class="flex-1 px-2 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl text-[11px] font-bold transition-all flex items-center justify-center gap-1" title="Cari Kru">
                                                        <i class="fa-solid fa-magnifying-glass"></i> Kru
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- SEMUA MODAL DITEMPATKAN DI SINI (DILUAR TABLE) -->
    <?php if(!empty($data['daily_operations'])): ?>
        <?php foreach($data['daily_operations'] as $op): 
            $modalId = "modal-detail-" . $op['id_jadwal'] . "-" . $op['tanggal_operasional'];
            $modalDetailOpId = "modal-detail-jadwal-op-" . $op['id_jadwal'] . "-" . $op['tanggal_operasional'];
            
            $kruList = $op['kru_list'];
            $penumpangList = $op['penumpang_list'];
            
            $koridorStasiun = $op['koridor_stasiun'] ?? [];
            $kelasKapasitas = $op['kelas_kapasitas'] ?? [];
            $totalKapasitasKereta = array_sum($kelasKapasitas);
            $jumlahPenumpangTotal = count($penumpangList);
        ?>
            
            <!-- 1. MODAL DETAIL RUTE, TRANSIT, & SISA KURSI PER KELAS -->
            <div id="<?= $modalDetailOpId ?>" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl animate-fade-in relative rounded-[2rem] p-8 text-left space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-lg text-[#0F172A] flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-[#8C6239]"></i> Detail Operasional & Status Kursi
                            </h3>
                            <span class="text-xs text-slate-400 font-medium"><?= htmlspecialchars($op['nama_kereta']) ?> &bull; Tanggal: <?= date('d M Y', strtotime($op['tanggal_operasional'])) ?></span>
                        </div>
                        <button onclick="closeModal('<?= $modalDetailOpId ?>')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <!-- Informasi Jalur & Stasiun (Transit vs Dilewati Lengkap dengan Jam) -->
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2.5">Jalur Koridor & Titik Pemberhentian</h4>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 space-y-4">
                            
                            <!-- Stasiun Transit / Pemberhentian -->
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block mb-2 uppercase tracking-wider">Stasiun Transit & Pemberhentian:</span>
                                <div class="space-y-2">
                                    <?php if(!empty($op['stopping_stations'])): ?>
                                        <?php foreach($op['stopping_stations'] as $stop): 
                                            $badgeStyle = 'bg-slate-100 text-slate-600';
                                            $statusLower = strtolower($stop['status']);
                                            if(strpos($statusLower, 'berangkat') !== false) { $badgeStyle = 'bg-emerald-50 text-emerald-600'; }
                                            elseif(strpos($statusLower, 'tiba') !== false) { $badgeStyle = 'bg-rose-50 text-rose-600'; }
                                            else { $badgeStyle = 'bg-sky-50 text-[#2B9BFB]'; }
                                        ?>
                                            <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-slate-200/60 text-xs shadow-sm">
                                                <div class="flex items-center gap-2">
                                                    <i class="fa-solid fa-circle text-[6px] text-[#8C6239]"></i>
                                                    <span class="font-bold text-[#0F172A]"><?= htmlspecialchars($stop['nama']) ?></span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <?php if(!empty($stop['jam']) && $stop['jam'] !== '-'): ?>
                                                        <span class="font-mono font-semibold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200 text-[11px]">
                                                            <i class="fa-regular fa-clock mr-1 text-[#8C6239]"></i> <?= htmlspecialchars($stop['jam']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded <?= $badgeStyle ?>"><?= htmlspecialchars($stop['status']) ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-xs text-slate-400 italic">Tidak ada data perhentian.</div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Stasiun Langsung / Dilewati -->
                            <?php if(!empty($op['passed_stations'])): ?>
                                <div class="pt-3 border-t border-slate-200">
                                    <span class="text-[10px] font-bold text-slate-400 block mb-2 uppercase tracking-wider">Langsung / Tidak Transit di Stasiun:</span>
                                    <div class="space-y-2">
                                        <?php foreach($op['passed_stations'] as $passed): ?>
                                            <div class="flex justify-between items-center bg-white/60 p-2.5 rounded-xl border border-slate-200/40 text-xs">
                                                <div class="flex items-center gap-2">
                                                    <i class="fa-solid fa-train text-[10px] text-slate-400"></i>
                                                    <span class="font-semibold text-slate-600"><?= htmlspecialchars($passed['nama']) ?></span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <?php if(!empty($passed['jam']) && $passed['jam'] !== '-'): ?>
                                                        <span class="font-mono text-slate-400 text-[11px]">
                                                            <i class="fa-regular fa-clock mr-1"></i> <?= htmlspecialchars($passed['jam']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500">Dilewati</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- 2. Status Kursi & Sisa Kursi per Kelas -->
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2.5">Ketersediaan & Sisa Kursi Per Kelas</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php if(empty($kelasKapasitas)): ?>
                                <div class="col-span-2 text-center text-xs text-slate-400 py-4 bg-slate-50 rounded-xl">Konfigurasi kelas gerbong belum diatur.</div>
                            <?php else: ?>
                                <?php foreach($kelasKapasitas as $namaKelas => $kapasitasTotal): ?>
                                    <div class="bg-white p-4 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col justify-between space-y-2">
                                        <div class="flex justify-between items-start">
                                            <span class="text-xs font-bold text-[#0F172A]"><?= htmlspecialchars($namaKelas) ?></span>
                                            <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded font-bold">Aktif</span>
                                        </div>
                                        <div class="flex justify-between items-end pt-2 border-t border-slate-100">
                                            <div>
                                                <span class="text-[10px] text-slate-400 block font-bold uppercase">Kapasitas Kursi</span>
                                                <span class="text-sm font-extrabold text-slate-700"><?= $kapasitasTotal ?> Seat</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-[10px] text-emerald-500 block font-bold uppercase">Status</span>
                                                <span class="text-xs font-extrabold text-emerald-600">Tersedia</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Ringkasan Total Manifes -->
                    <div class="p-4 bg-slate-900 text-white rounded-2xl flex justify-between items-center">
                        <div>
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">Total Kapasitas Rangkaian</span>
                            <span class="text-lg font-extrabold text-[#2B9BFB]"><?= $totalKapasitasKereta ?> Kursi</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">Total Penumpang Terdaftar</span>
                            <span class="text-lg font-extrabold text-purple-400"><?= $jumlahPenumpangTotal ?> Penumpang</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button onclick="closeModal('<?= $modalDetailOpId ?>')" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-all">Tutup Pratinjau</button>
                    </div>
                </div>
            </div>

            <!-- 2. MODAL DOKUMEN RESMI A4 -->
            <div id="<?= $modalId ?>" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div id="dokumen-a4-<?= $modalId ?>" class="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl animate-fade-in relative rounded-xl text-left">
                    <div class="h-2 w-full bg-gradient-to-r from-[#8C6239] to-[#AF8B69] no-print"></div>
                    
                    <div class="p-8 sm:p-12 space-y-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start border-b-2 border-slate-800 pb-6">
                            <div>
                                <h2 class="text-lg sm:text-xl font-black text-[#0F172A] tracking-widest uppercase">Manifes & Operasional Harian</h2>
                                <p class="text-xs text-slate-500 font-mono mt-1">DOKUMEN NO: ANV-OPR-<?= str_pad($op['id_jadwal'], 4, '0', STR_PAD_LEFT) ?>/<?= str_replace('-','', $op['tanggal_operasional']) ?></p>
                            </div>
                            <div class="mt-4 sm:mt-0 text-left sm:text-right text-[11px] text-slate-500 font-mono space-y-1">
                                <p>TANGGAL KEBERANGKATAN:</p>
                                <p class="font-bold text-base text-[#8C6239]"><?= date('d F Y', strtotime($op['tanggal_operasional'])) ?></p>
                                <p class="pt-2 text-[10px] italic">Waktu Cetak: <?= date('d M Y - H:i') ?></p>
                            </div>
                        </div>

                        <div class="space-y-6 text-sm">
                            <div>
                                <h3 class="font-bold text-[#8C6239] uppercase tracking-wider text-xs mb-3 flex items-center gap-2"><i class="fa-solid fa-train-subway"></i> I. Informasi Kereta & Rute</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 bg-slate-50/50 p-5 border border-slate-200 rounded-lg">
                                    <div>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Nama Kereta & Series</span>
                                        <span class="block font-bold text-[#0F172A] text-base mt-0.5"><?= htmlspecialchars($op['nama_kereta']) ?></span>
                                        <span class="text-xs font-semibold text-[#8C6239]"><?= htmlspecialchars($op['jenis_kelas']) ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Stasiun Asal</span>
                                        <span class="block font-semibold text-slate-700 mt-0.5"><?= htmlspecialchars($op['stasiun_asal']) ?></span>
                                        <span class="text-xs font-mono text-slate-500 font-bold">Pukul: <?= htmlspecialchars($op['jam_berangkat']) ?> WIB</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Stasiun Tujuan</span>
                                        <span class="block font-semibold text-slate-700 mt-0.5"><?= htmlspecialchars($op['stasiun_tujuan']) ?></span>
                                        <span class="text-xs font-mono text-slate-500 font-bold">Pukul: <?= htmlspecialchars($op['jam_tiba']) ?> WIB</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="font-bold text-[#8C6239] uppercase tracking-wider text-xs mb-3 flex items-center gap-2"><i class="fa-solid fa-user-shield"></i> II. Daftar Kru Bertugas</h3>
                                <div class="border border-slate-200 rounded-lg overflow-hidden">
                                    <table class="w-full text-left border-collapse text-xs">
                                        <thead>
                                            <tr class="bg-slate-100 text-slate-600 font-bold border-b border-slate-200">
                                                <th class="py-2.5 px-3">No.</th>
                                                <th class="py-2.5 px-3">Nama Lengkap</th>
                                                <th class="py-2.5 px-3">Posisi</th>
                                                <th class="py-2.5 px-3">Identitas [NIK]/[NIP]</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <?php if(empty($kruList)): ?>
                                                <tr><td colspan="4" class="py-4 text-center text-slate-400 italic">Tidak ada kru terdaftar untuk tanggal ini.</td></tr>
                                            <?php else: ?>
                                                <?php $kNo=1; foreach($kruList as $kru): ?>
                                                    <tr>
                                                        <td class="py-2.5 px-3 font-bold text-slate-500"><?= $kNo++ ?></td>
                                                        <td class="py-2.5 px-3 font-bold text-[#0F172A]"><?= htmlspecialchars($kru['nama_lengkap']) ?></td>
                                                        <td class="py-2.5 px-3"><span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-bold"><?= htmlspecialchars($kru['posisi']) ?></span></td>
                                                        <td class="py-2.5 px-3 font-mono text-slate-600">[<?= htmlspecialchars($kru['nik']) ?>]/[<?= htmlspecialchars($kru['nip']) ?>]</td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div>
                                <h3 class="font-bold text-[#8C6239] uppercase tracking-wider text-xs mb-3 flex items-center gap-2"><i class="fa-solid fa-users"></i> III. Manifes Penumpang</h3>
                                <div class="border border-slate-200 rounded-lg overflow-hidden max-h-60 overflow-y-auto custom-scrollbar">
                                    <table class="w-full text-left border-collapse text-xs">
                                        <thead>
                                            <tr class="bg-slate-100 text-slate-600 font-bold border-b border-slate-200 sticky top-0">
                                                <th class="py-2.5 px-3">No.</th>
                                                <th class="py-2.5 px-3">Nama Penumpang</th>
                                                <th class="py-2.5 px-3">NIK (KTP)</th>
                                                <th class="py-2.5 px-3">Nomor Kursi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <?php if(empty($penumpangList)): ?>
                                                <tr><td colspan="4" class="py-4 text-center text-slate-400 italic">Tidak ada data penumpang untuk tanggal ini.</td></tr>
                                            <?php else: ?>
                                                <?php $pNo=1; foreach($penumpangList as $p): ?>
                                                    <tr>
                                                        <td class="py-2.5 px-3 font-bold text-slate-500"><?= $pNo++ ?></td>
                                                        <td class="py-2.5 px-3 font-bold text-[#0F172A]"><?= htmlspecialchars($p['nama']) ?></td>
                                                        <td class="py-2.5 px-3 font-mono text-slate-600"><?= htmlspecialchars($p['nik']) ?></td>
                                                        <td class="py-2.5 px-3 font-bold text-purple-600"><?= htmlspecialchars($p['nomor_kursi']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-8 pt-8 border-t border-slate-200 flex justify-between items-end">
                                <div class="flex gap-2 no-print">
                                    <button onclick="closeModal('<?= $modalId ?>')" class="px-5 py-2.5 bg-slate-100 hover:bg-[#0F172A] hover:text-white text-slate-600 rounded-lg font-bold text-xs transition-colors shadow-sm">Tutup</button>
                                    <button onclick="cetakDokumenJadwal('<?= $modalId ?>')" class="px-5 py-2.5 bg-[#8C6239] hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] text-white rounded-lg font-bold text-xs transition-colors shadow-sm flex items-center gap-2"><i class="fa-solid fa-print"></i> Cetak PDF</button>
                                </div>
                                <div class="text-center relative mr-2">
                                    <span class="block text-[9px] text-slate-400 mb-6 uppercase tracking-widest">Divalidasi Oleh</span>
                                    <span class="block text-[10px] font-extrabold text-[#0F172A] mt-2 border-b-2 border-slate-800 pb-1 relative z-10">PT KERETA CEPAT ANVO</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. MODAL PENCARIAN PENUMPANG -->
            <div id="modal-cari-penumpang-<?= $modalId ?>" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div class="bg-white w-full max-w-md rounded-[2rem] p-8 shadow-2xl animate-fade-in relative text-left">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                        <div>
                            <h3 class="font-extrabold text-lg text-[#0F172A]"><i class="fa-solid fa-user-magnifying-glass text-purple-600 mr-2"></i> Cari Penumpang</h3>
                            <span class="text-[10px] text-slate-400 font-bold block mt-1"><?= date('d M Y', strtotime($op['tanggal_operasional'])) ?> | <?= htmlspecialchars($op['jam_berangkat']) ?> WIB</span>
                        </div>
                        <button onclick="closeModal('modal-cari-penumpang-<?= $modalId ?>')" class="text-slate-400 hover:text-rose-500 w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    
                    <div class="relative mb-4">
                        <i class="fa-solid fa-search absolute left-4 top-3.5 text-slate-400"></i>
                        <input type="text" onkeyup="filterLiveSearch(this, 'list-penumpang-<?= $modalId ?>')" placeholder="Ketik nama penumpang..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-purple-500 transition-all">
                    </div>
                    
                    <div class="max-h-64 overflow-y-auto custom-scrollbar space-y-2 pr-1" id="list-penumpang-<?= $modalId ?>">
                        <?php if(empty($penumpangList)): ?>
                            <div class="text-center text-slate-400 text-xs py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">Manifes kosong pada jadwal ini.</div>
                        <?php else: ?>
                            <?php foreach($penumpangList as $p): ?>
                                <div class="search-item flex justify-between items-center p-3.5 bg-slate-50 hover:bg-purple-50 border border-slate-100 hover:border-purple-200 rounded-xl transition-colors group">
                                    <div>
                                        <span class="block font-bold text-sm text-[#0F172A] item-name group-hover:text-purple-700"><?= htmlspecialchars($p['nama']) ?></span>
                                        <span class="text-[10px] text-slate-400 font-mono mt-0.5 block">NIK: <?= htmlspecialchars($p['nik']) ?></span>
                                    </div>
                                    <span class="text-xs font-bold text-purple-600 bg-white px-3 py-1.5 rounded-lg border border-purple-100 shadow-sm"><?= htmlspecialchars($p['nomor_kursi']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 4. MODAL PENCARIAN KRU -->
            <div id="modal-cari-kru-<?= $modalId ?>" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div class="bg-white w-full max-w-md rounded-[2rem] p-8 shadow-2xl animate-fade-in relative text-left">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                        <div>
                            <h3 class="font-extrabold text-lg text-[#0F172A]"><i class="fa-solid fa-user-shield text-emerald-600 mr-2"></i> Cari Kru Bertugas</h3>
                            <span class="text-[10px] text-slate-400 font-bold block mt-1"><?= date('d M Y', strtotime($op['tanggal_operasional'])) ?> | <?= htmlspecialchars($op['jam_berangkat']) ?> WIB</span>
                        </div>
                        <button onclick="closeModal('modal-cari-kru-<?= $modalId ?>')" class="text-slate-400 hover:text-rose-500 w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    
                    <div class="relative mb-4">
                        <i class="fa-solid fa-search absolute left-4 top-3.5 text-slate-400"></i>
                        <input type="text" onkeyup="filterLiveSearch(this, 'list-kru-<?= $modalId ?>')" placeholder="Ketik nama kru..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-emerald-500 transition-all">
                    </div>
                    
                    <div class="max-h-64 overflow-y-auto custom-scrollbar space-y-2 pr-1" id="list-kru-<?= $modalId ?>">
                        <?php if(empty($kruList)): ?>
                            <div class="text-center text-slate-400 text-xs py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">Tidak ada kru yang ditugaskan.</div>
                        <?php else: ?>
                            <?php foreach($kruList as $kru): ?>
                                <div class="search-item flex justify-between items-center p-3.5 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 rounded-xl transition-colors group">
                                    <div>
                                        <span class="block font-bold text-sm text-[#0F172A] item-name group-hover:text-emerald-700"><?= htmlspecialchars($kru['nama_lengkap']) ?></span>
                                        <span class="text-[10px] text-slate-400 font-mono mt-0.5 block">NIP: <?= htmlspecialchars($kru['nip']) ?></span>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-600 bg-white px-2.5 py-1 rounded-md border border-emerald-100 uppercase"><?= htmlspecialchars($kru['posisi']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>

    <!-- SCRIPT LOGIKA PENCARIAN & LIMITASI -->
    <script>
        // Logika Limitasi 30 Hari pada Date Picker
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        function enforceDateLimits() {
            if (startInput.value) {
                const startDate = new Date(startInput.value);
                const maxDate = new Date(startDate);
                maxDate.setDate(startDate.getDate() + 30);
                
                endInput.min = startInput.value;
                endInput.max = maxDate.toISOString().split('T')[0];
            }
            if (endInput.value) {
                const endDate = new Date(endInput.value);
                const minDate = new Date(endDate);
                minDate.setDate(endDate.getDate() - 30);
                
                startInput.max = endInput.value;
                startInput.min = minDate.toISOString().split('T')[0];
            }
        }

        startInput.addEventListener('change', enforceDateLimits);
        endInput.addEventListener('change', enforceDateLimits);
        enforceDateLimits(); 

        setTimeout(function() {
            const alertBox = document.getElementById('flash-alert');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);

        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
            
            if(id.startsWith('modal-cari-')) {
                const input = modal.querySelector('input[type="text"]');
                if(input) {
                    input.value = '';
                    filterLiveSearch(input, id.replace('modal-cari-penumpang-', 'list-penumpang-').replace('modal-cari-kru-', 'list-kru-'));
                }
            }
        }

        function filterLiveSearch(inputElement, listId) {
            const filter = inputElement.value.toLowerCase();
            const list = document.getElementById(listId);
            if(!list) return;

            const items = list.getElementsByClassName('search-item');
            for (let i = 0; i < items.length; i++) {
                const nameElement = items[i].querySelector('.item-name');
                if (nameElement) {
                    const nameValue = nameElement.textContent || nameElement.innerText;
                    if (nameValue.toLowerCase().indexOf(filter) > -1) {
                        items[i].style.display = "";
                    } else {
                        items[i].style.display = "none";
                    }
                }
            }
        }

        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": [], 
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada operasional pada rentang tanggal ini.",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Data kosong",
                    "search": "Cari Cepat:",
                    "paginate": { "first": "Awal", "last": "Akhir", "next": "Lanjut", "previous": "Kembali" }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [6] } 
                ]
            });
        });

        function cetakDokumenJadwal(modalId) {
            const konten = document.getElementById('dokumen-a4-' + modalId).innerHTML;
            
            const printFrame = document.createElement('iframe');
            printFrame.name = "print_frame";
            printFrame.style.position = 'absolute';
            printFrame.style.top = '-100000px';
            document.body.appendChild(printFrame);
            
            const frameDoc = printFrame.contentWindow ? printFrame.contentWindow.document : printFrame.contentDocument;
            
            frameDoc.open();
            frameDoc.write(`
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Dokumen Manifes</title>
                        <script src="https://cdn.tailwindcss.com"><\/script>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                        <style>
                            @media print {
                                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
                                .no-print { display: none !important; }
                                .custom-scrollbar { overflow: visible !important; max-height: none !important; }
                                @page { margin: 10mm; size: A4; }
                            }
                        </style>
                    </head>
                    <body class="bg-white">
                        <div class="max-w-3xl mx-auto rounded-xl overflow-hidden relative font-sans text-sm p-4">${konten}</div>
                    </body>
                </html>
            `);
            frameDoc.close();
            
            setTimeout(function() {
                window.frames["print_frame"].focus();
                window.frames["print_frame"].print();
                setTimeout(() => document.body.removeChild(printFrame), 1000);
            }, 1200); 
        }
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>