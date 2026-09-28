<?php 
$data['active_menu'] = 'route';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
$namaKoridor = $data['koridor']['nama_koridor'] ?? 'Detail Koridor';
$idKoridor = $data['koridor']['id_koridor'] ?? '';
$keteranganKoridor = $data['koridor']['keterangan'] ?? '';
?>

    <div class="flex-1 flex flex-col min-w-0 relative">

        <!-- TOAST NOTIFICATION KUSTOM -->
        <div id="custom-toast" class="fixed bottom-8 right-8 z-50 transform translate-y-20 opacity-0 transition-all duration-300 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-semibold">
            <i id="toast-icon" class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
            <span id="toast-message">Pesan notifikasi berhasil.</span>
        </div>

        <!-- TOP BAR -->
        <header class="h-20 bg-white border-b border-slate-200/60 px-6 sm:px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Diperbarui ke jalur admin -->
                <a href="/anvo/public/admin/route" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all" title="Kembali">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-base sm:text-lg font-extrabold text-[#0F172A] tracking-tight"><?= htmlspecialchars($namaKoridor) ?></h1>
                    <p class="text-xs text-slate-400 font-medium">Atur urutan stasiun linier & peta rute visual.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- TOMBOL EDIT KORIDOR -->
                <button onclick="openModal('modal-edit-koridor')" class="bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white px-3.5 py-2.5 rounded-2xl text-xs font-semibold transition-all shadow-sm flex items-center gap-1.5" title="Edit Nama & Deskripsi">
                    <i class="fa-solid fa-pen-to-square"></i> <span class="hidden sm:inline">Edit Koridor</span>
                </button>
                <!-- TOMBOL HAPUS KORIDOR -->
                <a href="/anvo/public/admin/route/hapus_koridor/<?= $idKoridor ?>" onclick="return confirm('PERINGATAN: Menghapus koridor akan melepaskan seluruh konfigurasi stasiun dan jalur terkait. Lanjutkan?')" class="bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white px-3.5 py-2.5 rounded-2xl text-xs font-semibold transition-all shadow-sm flex items-center gap-1.5" title="Hapus Koridor">
                    <i class="fa-solid fa-trash-can"></i> <span class="hidden sm:inline">Hapus</span>
                </a>
                <button onclick="openModal('modal-peta-rute')" class="bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white px-4 py-2.5 rounded-2xl text-xs font-semibold transition-all shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-map"></i> <span class="hidden sm:inline">Peta Rute Visual</span>
                </button>
            </div>
        </header>

        <!-- CONTENT BODY -->
        <main class="flex-1 p-6 sm:p-10 space-y-8 overflow-y-auto">

            <?php if (isset($_SESSION['success'])): ?>
                <div id="flash-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm flex items-center justify-between">
                    <span class="text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i> <?= $_SESSION['success'] ?>
                    </span>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- GRID UTAMA -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Form Sisipkan Stasiun (Col 4) -->
                <div class="lg:col-span-4 bg-white p-6 sm:p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm h-fit space-y-6">
                    <div>
                        <h2 class="text-base font-bold text-[#0F172A] tracking-tight mb-1">Sisipkan Stasiun Jalur</h2>
                        <p class="text-xs text-slate-400">Pilih stasiun sisa yang belum masuk ke koridor.</p>
                    </div>

                    <?php if(empty($data['stasiun_tersedia'])): ?>
                        <div class="p-4 bg-amber-50 text-amber-800 rounded-2xl text-xs font-medium">
                            <i class="fa-solid fa-circle-info mr-1"></i> Semua stasiun master sudah dimasukkan ke dalam koridor ini.
                        </div>
                    <?php else: ?>
                        <!-- Diperbarui ke jalur admin -->
                        <form id="form-tambah-stasiun" action="/anvo/public/admin/route/tambah_stasiun_koridor/<?= $idKoridor ?>" method="POST" class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Stasiun</label>
                                <select name="nama_stasiun" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                    <?php foreach($data['stasiun_tersedia'] as $st): ?>
                                        <option value="<?= $st['nama_stasiun'] ?>"><?= $st['nama_stasiun'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md mt-2">
                                Tambah ke Urutan Rute
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Daftar Urutan Jalur (Col 8) -->
                <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-base sm:text-lg font-extrabold text-[#0F172A] tracking-tight">Urutan Jalur Stasiun Linier</h2>
                            <p class="text-xs text-slate-400">Gunakan tombol panah untuk mengubah urutan sementara sebelum disimpan permanen.</p>
                        </div>
                    </div>

                    <div id="container-stasiun" class="space-y-3">
                        <?php if(empty($data['stasiun_terdaftar'])): ?>
                            <div class="py-12 text-center text-slate-400 text-sm border-2 border-dashed border-slate-100 rounded-3xl">
                                <i class="fa-solid fa-train text-3xl mb-2 text-slate-300 block"></i>
                                Belum ada stasiun dalam koridor ini.
                            </div>
                        <?php else: ?>
                            <?php foreach($data['stasiun_terdaftar'] as $index => $st): ?>
                                <div class="stasiun-row flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200/60 transition-all" data-id="<?= $st['id_koridor_stasiun'] ?>">
                                    <div class="flex items-center gap-4">
                                        <div class="nomor-urut w-9 h-9 rounded-xl bg-white text-[#8C6239] font-extrabold flex items-center justify-center text-xs shadow-sm border border-slate-200/60">
                                            <?= $index + 1 ?>
                                        </div>
                                        <div>
                                            <span class="nama-stasiun font-bold text-[#0F172A] text-sm block"><?= $st['nama_stasiun'] ?></span>
                                            <span class="text-[10px] text-slate-400 font-medium">Stasiun Transit Jalur</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1.5">
                                        <button onclick="geserItem(this, 'up')" class="p-2.5 bg-slate-200 hover:bg-[#8C6239] hover:text-white text-slate-700 rounded-xl text-xs transition-all shadow-sm" title="Geser ke Atas">
                                            <i class="fa-solid fa-arrow-up"></i>
                                        </button>
                                        <button onclick="geserItem(this, 'down')" class="p-2.5 bg-slate-200 hover:bg-[#8C6239] hover:text-white text-slate-700 rounded-xl text-xs transition-all shadow-sm" title="Geser ke Bawah">
                                            <i class="fa-solid fa-arrow-down"></i>
                                        </button>

                                        <button onclick="bukaModalEdit('<?= $st['id_koridor_stasiun'] ?>', '<?= $st['nama_stasiun'] ?>')" class="px-3.5 py-2 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                        </button>
                                        
                                        <!-- Diperbarui ke jalur admin -->
                                        <a href="/anvo/public/admin/route/hapus_stasiun/<?= $idKoridor ?>/<?= $st['id_koridor_stasiun'] ?>" onclick="return confirm('Hapus stasiun ini dari koridor?')" class="p-2.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Panel Simpan Perubahan Permanen -->
                    <div id="panel-simpan-perubahan" class="hidden pt-4 border-t border-slate-100 flex items-center justify-between bg-amber-50/60 p-4 rounded-2xl border border-amber-200/60">
                        <span class="text-xs font-bold text-amber-800"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Ada perubahan urutan jalur yang belum disimpan!</span>
                        <button onclick="simpanPerubahanPermanen()" class="px-5 py-2.5 bg-[#8C6239] hover:bg-[#74502e] text-white rounded-xl text-xs font-semibold shadow-md transition-all">
                            Simpan Perubahan Jalur
                        </button>
                    </div>
                </div>

            </div>

            <!-- BAGIAN TAMBAHAN: DATA KERETA / ARMADA YANG BERDINAS DI KORIDOR INI -->
            <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-[#0F172A] tracking-tight">Daftar Armada Kereta Berdinas di Koridor Ini</h2>
                        <p class="text-xs text-slate-400">Rangkaian kereta cepat yang dialokasikan melintasi jalur koridor ini.</p>
                    </div>
                    <button onclick="openModal('modal-tambah-kereta-koridor')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-4 py-2.5 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Kereta Koridor
                    </button>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3 px-4">No.</th>
                                <th class="py-3 px-4">Nama Kereta</th>
                                <th class="py-3 px-4">Jenis Kelas</th>
                                <th class="py-3 px-4">Kapasitas</th>
                                <th class="py-3 px-4">Status Operasional</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['kereta_berdinas'])): ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-medium text-xs">Belum ada armada kereta yang ditugaskan pada koridor ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no=1; foreach($data['kereta_berdinas'] as $kb): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-3.5 px-4 font-bold text-[#0F172A]"><?= $kb['nama_kereta'] ?></td>
                                        <td class="py-3.5 px-4"><span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded-md font-bold"><?= $kb['jenis_kelas'] ?></span></td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= $kb['kapasitas_kursi'] ?? 500 ?> Kursi</td>
                                        <td class="py-3.5 px-4">
                                            <span class="text-[10px] px-2.5 py-1 rounded-full font-bold <?= ($kb['status_operasional'] == 'Aktif') ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' ?>">
                                                <?= $kb['status_operasional'] ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <!-- Diperbarui ke jalur admin -->
                                            <a href="/anvo/public/admin/route/lepas_kereta_koridor/<?= $idKoridor ?>/<?= $kb['id_kereta'] ?>" onclick="return confirm('Kembalikan armada ini ke Pool (lepas dari koridor)?')" class="px-3 py-1.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs font-semibold transition-all">
                                                Lepas
                                            </a>
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

    <!-- MODAL EDIT KORIDOR (BARU) -->
    <div id="modal-edit-koridor" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="font-extrabold text-lg text-[#0F172A]">Edit Informasi Koridor</h3>
                <button onclick="closeModal('modal-edit-koridor')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="/anvo/public/admin/route/edit_koridor/<?= $idKoridor ?>" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Nama Koridor</label>
                    <input type="text" name="nama_koridor" value="<?= htmlspecialchars($namaKoridor) ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Keterangan / Deskripsi</label>
                    <textarea name="keterangan" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]"><?= htmlspecialchars($keteranganKoridor) ?></textarea>
                </div>
                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md">Simpan Perubahan Koridor</button>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT STASIUN -->
    <div id="modal-edit-stasiun" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="font-extrabold text-base text-[#0F172A]">Edit Stasiun Koridor</h3>
                <button onclick="closeModal('modal-edit-stasiun')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <!-- Diperbarui ke jalur admin -->
            <form action="/anvo/public/admin/route/edit_stasiun/<?= $idKoridor ?>" method="POST" class="space-y-4">
                <input type="hidden" name="id_koridor_stasiun" id="edit-id-koridor-stasiun">
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Ganti Nama Stasiun</label>
                    <select name="nama_stasiun" id="edit-nama-stasiun" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH KERETA KE KORIDOR -->
    <div id="modal-tambah-kereta-koridor" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="font-extrabold text-base text-[#0F172A]">Tugaskan Armada Kereta</h3>
                <button onclick="closeModal('modal-tambah-kereta-koridor')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <!-- Diperbarui ke jalur admin -->
            <form action="/anvo/public/admin/route/tambah_kereta_koridor/<?= $idKoridor ?>" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Seri / Nama Kereta</label>
                    <select name="id_kereta" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                        <?php foreach($data['kereta_tersedia'] ?? [] as $sk): ?>
                            <option value="<?= $sk['id_kereta'] ?>"><?= $sk['nama_kereta'] ?> (<?= $sk['kecepatan_maksimal'] ?> km/h)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md">Simpan Penugasan</button>
            </form>
        </div>
    </div>

    <!-- MODAL PETA RUTE VISUAL -->
    <div id="modal-peta-rute" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-3xl rounded-[2.5rem] p-8 sm:p-10 shadow-2xl space-y-6 animate-fade-in max-h-[95vh] overflow-y-auto custom-scrollbar border border-slate-200/60">
            <div id="modal-top-bar" class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-lg text-[#0F172A]">Dokumen Resmi Peta & Informasi Koridor</h3>
                    <p class="text-xs text-slate-400">Layout standar cetak format A4 - Ringkasan operasional jalur.</p>
                </div>
                <button onclick="closeModal('modal-peta-rute')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div id="print-area-map" class="p-8 sm:p-10 bg-white text-[#0F172A] rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="space-y-8">
                    <div class="flex justify-between items-start border-b border-slate-100 pb-6 relative z-10">
                        <div class="flex items-center gap-4">
                            <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO Logo" class="w-14 h-14 object-contain" onerror="this.style.display='none'">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-[#8C6239] tracking-wider block">ANVO Railway System</span>
                                <h4 class="font-extrabold text-2xl text-[#0F172A]"><?= htmlspecialchars($namaKoridor) ?></h4>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-bold text-slate-400 block">OFFICIAL ROUTE MAP</span>
                            <span class="text-xs font-semibold text-slate-600"><?= date('d M Y') ?></span>
                        </div>
                    </div>

                    <div class="space-y-1 relative z-10">
                        <h5 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Deskripsi Koridor</h5>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed">
                            <?= htmlspecialchars($keteranganKoridor ?: 'Koridor jalur utama kereta cepat berkecepatan tinggi dengan integrasi transit mutakhir.') ?>
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 relative z-10">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 text-[#2B9BFB] flex items-center justify-center font-bold">
                                <i class="fa-solid fa-train"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Armada Dinas</span>
                                <span class="text-base font-extrabold text-[#0F172A]"><?= count($data['kereta_berdinas']) ?> Unit</span>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#8C6239] flex items-center justify-center font-bold">
                                <i class="fa-solid fa-map-pin"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Jumlah Stasiun</span>
                                <span class="text-base font-extrabold text-[#0F172A]"><?= count($data['stasiun_terdaftar']) ?> Stasiun</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 relative z-10 pt-2">
                        <h5 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Linier Rute Perjalanan</h5>
                        <div class="p-6 bg-slate-50/80 rounded-3xl border border-slate-200/60 space-y-4 relative pl-8 before:absolute before:left-5 before:top-4 before:bottom-4 before:w-0.5 before:bg-[#8C6239]">
                            <?php if(empty($data['stasiun_terdaftar'])): ?>
                                <p class="text-xs text-slate-400">Belum ada stasiun dalam koridor.</p>
                            <?php else: ?>
                                <?php foreach($data['stasiun_terdaftar'] as $s): ?>
                                    <div class="relative flex items-center justify-between bg-white p-3.5 rounded-2xl border border-slate-200/60 shadow-sm">
                                        <div class="absolute -left-8 w-3 h-3 rounded-full bg-[#8C6239] border-2 border-slate-50 ring-2 ring-[#8C6239]/20"></div>
                                        <span class="font-bold text-sm text-[#0F172A]"><?= htmlspecialchars($s['nama_stasiun']) ?></span>
                                        <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2.5 py-1 rounded-lg font-bold">Transit Utama</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-[11px] text-slate-400 mt-8 relative z-10">
                    <span>Dokumen ini digenerate otomatis oleh Sistem Manajemen ANVO.</span>
                    <span class="font-bold text-slate-600">CONFIDENTIAL</span>
                </div>
            </div>

            <div id="modal-action-buttons" class="flex flex-col sm:flex-row gap-3 pt-2">
                <button onclick="window.print()" class="flex-1 bg-[#8C6239] hover:bg-[#74502e] text-white py-3.5 rounded-2xl font-semibold text-xs transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
                </button>
                <button onclick="closeModal('modal-peta-rute')" class="px-6 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3.5 rounded-2xl font-semibold text-xs transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <style>
        #print-area-map {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 275mm;
            box-sizing: border-box;
        }
        @media print {
            body * { visibility: hidden; }
            #print-area-map, #print-area-map * { visibility: visible; }
            #print-area-map {
                position: absolute; left: 0; top: 0; width: 210mm !important; min-height: 297mm !important;
                margin: 0 !important; padding: 15mm 20mm !important; box-shadow: none !important; border: none !important; background: white !important;
            }
        }
    </style>

    <script>
        setTimeout(() => { const el = document.getElementById('flash-alert'); if(el) el.remove(); }, 3000);

        function showToast(message, isError = false) {
            const toast = document.getElementById('custom-toast');
            const msg = document.getElementById('toast-message');
            const icon = document.getElementById('toast-icon');

            msg.innerText = message;
            icon.className = isError ? 'fa-solid fa-triangle-exclamation text-rose-400 text-sm' : 'fa-solid fa-circle-check text-emerald-400 text-sm';

            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 3500);
        }

        const stasiunTersedia = <?= json_encode($data['stasiun_tersedia']) ?>;

        function bukaModalEdit(idKoridorStasiun, namaStasiunAktif) {
            document.getElementById('edit-id-koridor-stasiun').value = idKoridorStasiun;
            const selectEl = document.getElementById('edit-nama-stasiun');
            selectEl.innerHTML = '';

            const optionAktif = document.createElement('option');
            optionAktif.value = namaStasiunAktif;
            optionAktif.textContent = namaStasiunAktif + ' (Aktif Saat Ini)';
            selectEl.appendChild(optionAktif);

            stasiunTersedia.forEach(st => {
                if(st.nama_stasiun !== namaStasiunAktif) {
                    const opt = document.createElement('option');
                    opt.value = st.nama_stasiun;
                    opt.textContent = st.nama_stasiun;
                    selectEl.appendChild(opt);
                }
            });

            openModal('modal-edit-stasiun');
        }

        function geserItem(button, arah) {
            const row = button.closest('.stasiun-row');
            const container = document.getElementById('container-stasiun');
            const panelSimpan = document.getElementById('panel-simpan-perubahan');

            if (arah === 'up') {
                const prevRow = row.previousElementSibling;
                if (prevRow && prevRow.classList.contains('stasiun-row')) {
                    container.insertBefore(row, prevRow);
                }
            } else if (arah === 'down') {
                const nextRow = row.nextElementSibling;
                if (nextRow && nextRow.classList.contains('stasiun-row')) {
                    container.insertBefore(nextRow, row);
                }
            }

            container.querySelectorAll('.stasiun-row').forEach((r, idx) => {
                r.querySelector('.nomor-urut').innerText = idx + 1;
            });

            panelSimpan.classList.remove('hidden');
        }

        function simpanPerubahanPermanen() {
            const rows = document.querySelectorAll('.stasiun-row');
            let dataUrutan = [];
            rows.forEach((r, idx) => {
                dataUrutan.push({
                    id: r.getAttribute('data-id'),
                    urutan: idx + 1
                });
            });

            // Diperbarui ke jalur admin
            fetch('/anvo/public/admin/route/simpan_urutan', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ urutan: dataUrutan })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    showToast(data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, true);
                }
            })
            .catch(() => showToast('Terjadi kesalahan jaringan.', true));
        }
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>