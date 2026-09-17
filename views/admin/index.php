<?php 
// Tetapkan status menu aktif untuk sidebar
$data['active_menu'] = 'dashboard';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- TOP BAR -->
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
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

            <!-- Flash Toast Notification -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm flex items-center justify-between">
                    <span class="text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i> <?= $_SESSION['success'] ?>
                    </span>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- TOP METRICS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jadwal Aktif</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#8C6239] flex items-center justify-center font-bold">
                            <i class="fa-solid fa-train-subway"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= count($data['jadwal']) ?></h3>
                        <p class="text-[11px] text-emerald-500 font-semibold mt-1"><i class="fa-solid fa-arrow-trend-up"></i> Beroperasi Normal</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Armada Kereta</span>
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-gauge-high"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= count($data['kereta']) ?></h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">Series Eksekutif & Luminary</p>
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
                        <h3 class="text-3xl font-extrabold text-[#0F172A]">0</h3>
                        <p class="text-[11px] text-purple-500 font-semibold mt-1">Manifes Hari Ini</p>
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
                        <h3 class="text-3xl font-extrabold text-[#0F172A]">12</h3>
                        <p class="text-[11px] text-emerald-500 font-semibold mt-1">Standby Bertugas</p>
                    </div>
                </div>
            </div>

            <!-- TABEL UTAMA OPERASIONAL KERETA -->
            <div id="tabel-kereta" class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Monitor Operasional Kereta & Kru</h2>
                        <p class="text-xs text-slate-400">Daftar perjalanan kereta cepat, manifes penumpang, serta kontak kru aktif.</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Nama Kereta</th>
                                <th class="py-3.5 px-4">Series</th>
                                <th class="py-3.5 px-4">Stasiun Berangkat</th>
                                <th class="py-3.5 px-4">Stasiun Tujuan</th>
                                <th class="py-3.5 px-4">Total Penumpang</th>
                                <th class="py-3.5 px-4">Total Kru</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['jadwal'])): ?>
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400 font-medium">Belum ada data operasional kereta aktif.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($data['jadwal'] as $j): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-4 px-4 font-bold text-[#0F172A]"><?= $j['nama_kereta'] ?></td>
                                        <td class="py-4 px-4">
                                            <span class="text-xs bg-sky-50 text-[#2B9BFB] px-2.5 py-1 rounded-lg font-bold"><?= $j['jenis_kelas'] ?></span>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-slate-700"><?= $j['stasiun_asal'] ?> <span class="text-xs text-slate-400 block font-normal"><?= $j['jam_berangkat'] ?></span></td>
                                        <td class="py-4 px-4 font-semibold text-slate-700"><?= $j['stasiun_tujuan'] ?> <span class="text-xs text-slate-400 block font-normal"><?= $j['jam_tiba'] ?></span></td>
                                        <td class="py-4 px-4 font-bold text-purple-600">0 / 200 Kursi</td>
                                        <td class="py-4 px-4 font-bold text-slate-700">4 Orang</td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button onclick="openModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Detail Kereta">
                                                    <i class="fa-solid fa-circle-info"></i> Detail
                                                </button>
                                                <button onclick="openModal('modal-kru-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl text-xs font-semibold transition-all" title="Kontak Kru">
                                                    <i class="fa-solid fa-headset"></i> Kru
                                                </button>
                                                <button onclick="openModal('modal-cari-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Cari Penumpang">
                                                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                                                </button>
                                                <a href="/anvo/public/admin/hapus_jadwal/<?= $j['id_jadwal'] ?>" onclick="return confirm('Hapus jadwal ini?')" class="p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus Jadwal">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL POPUP: DETAIL KERETA -->
                                    <div id="modal-detail-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-lg rounded-[2rem] p-6 shadow-2xl space-y-4 animate-fade-in">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-lg text-[#0F172A]"><i class="fa-solid fa-train text-[#8C6239] mr-2"></i> Detail Kereta: <?= $j['nama_kereta'] ?></h3>
                                                <button onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            <div class="space-y-3 text-sm text-slate-600">
                                                <div class="flex justify-between bg-slate-50 p-3 rounded-xl"><span>Series / Kelas:</span> <strong class="text-[#0F172A]"><?= $j['jenis_kelas'] ?></strong></div>
                                                <div class="flex justify-between bg-slate-50 p-3 rounded-xl"><span>Rute Perjalanan:</span> <strong class="text-[#0F172A]"><?= $j['stasiun_asal'] ?> → <?= $j['stasiun_tujuan'] ?></strong></div>
                                                <div class="flex justify-between bg-slate-50 p-3 rounded-xl"><span>Jadwal Waktu:</span> <strong class="text-[#0F172A]"><?= $j['jam_berangkat'] ?> - <?= $j['jam_tiba'] ?> WIB</strong></div>
                                                <div class="flex justify-between bg-slate-50 p-3 rounded-xl"><span>Harga Tiket:</span> <strong class="text-[#8C6239]">Rp <?= number_format($j['harga'], 0, ',', '.') ?></strong></div>
                                                <div class="flex justify-between bg-slate-50 p-3 rounded-xl"><span>Kapasitas Maksimal:</span> <strong class="text-emerald-600">200 Kursi</strong></div>
                                            </div>
                                            <button onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="w-full bg-[#0F172A] text-white py-3 rounded-xl font-semibold text-sm">Tutup</button>
                                        </div>
                                    </div>

                                    <!-- MODAL POPUP: HUBUNGI KRU -->
                                    <div id="modal-kru-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-lg rounded-[2rem] p-6 shadow-2xl space-y-4 animate-fade-in">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-lg text-[#0F172A]"><i class="fa-solid fa-headset text-emerald-500 mr-2"></i> Kontak Kru Bertugas</h3>
                                                <button onclick="closeModal('modal-kru-<?= $j['id_jadwal'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            <div class="space-y-3 text-sm">
                                                <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                                                    <div><strong>Capt. Ahmad Fauzi</strong><span class="text-xs text-slate-400 block">Masinis Utama</span></div>
                                                    <a href="https://wa.me/6281234567890" target="_blank" class="px-3 py-2 bg-emerald-500 text-white rounded-xl text-xs font-semibold hover:bg-emerald-600"><i class="fa-brands fa-whatsapp mr-1"></i> WhatsApp</a>
                                                </div>
                                                <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                                                    <div><strong>Siti Rahma, S.Par</strong><span class="text-xs text-slate-400 block">Kondektur Eksekutif</span></div>
                                                    <a href="https://wa.me/6281234567891" target="_blank" class="px-3 py-2 bg-emerald-500 text-white rounded-xl text-xs font-semibold hover:bg-emerald-600"><i class="fa-brands fa-whatsapp mr-1"></i> WhatsApp</a>
                                                </div>
                                                <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                                                    <div><strong>Budi Santoso</strong><span class="text-xs text-slate-400 block">Teknisi Onboard</span></div>
                                                    <a href="https://wa.me/6281234567892" target="_blank" class="px-3 py-2 bg-emerald-500 text-white rounded-xl text-xs font-semibold hover:bg-emerald-600"><i class="fa-brands fa-whatsapp mr-1"></i> WhatsApp</a>
                                                </div>
                                            </div>
                                            <button onclick="closeModal('modal-kru-<?= $j['id_jadwal'] ?>')" class="w-full bg-[#0F172A] text-white py-3 rounded-xl font-semibold text-sm">Tutup</button>
                                        </div>
                                    </div>

                                    <!-- MODAL POPUP: CARI DATA PENUMPANG -->
                                    <div id="modal-cari-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-lg rounded-[2rem] p-6 shadow-2xl space-y-4 animate-fade-in">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-lg text-[#0F172A]"><i class="fa-solid fa-magnifying-glass text-[#8C6239] mr-2"></i> Cari Manifes Penumpang</h3>
                                                <button onclick="closeModal('modal-cari-<?= $j['id_jadwal'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            <div>
                                                <input type="text" placeholder="Masukkan Nama atau NIK Penumpang..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                                            </div>
                                            <div class="py-6 text-center text-slate-400 text-sm">
                                                <i class="fa-solid fa-folder-open text-3xl mb-2 block text-slate-300"></i>
                                                Belum ada data penumpang terdaftar untuk jadwal ini.
                                            </div>
                                            <button onclick="closeModal('modal-cari-<?= $j['id_jadwal'] ?>')" class="w-full bg-[#0F172A] text-white py-3 rounded-xl font-semibold text-sm">Tutup</button>
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

<?php 
// Panggil footer layout admin
require_once __DIR__ . '/../layouts/admin/footer.php'; 
?>