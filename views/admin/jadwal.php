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
                <button onclick="openModal('modal-tambah-jadwal')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Jadwal Baru
                </button>
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
                <form action="/anvo/public/jadwal" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Filter Tanggal</label>
                        <input type="date" name="tanggal" value="<?= $data['filter']['tanggal'] ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Kelas Kereta</label>
                        <select name="kelas" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="">Semua Kelas</option>
                            <option value="Eksekutif" <?= ($data['filter']['kelas'] == 'Eksekutif') ? 'selected' : '' ?>>Eksekutif</option>
                            <option value="Luminary" <?= ($data['filter']['kelas'] == 'Luminary') ? 'selected' : '' ?>>Luminary</option>
                            <option value="First Class" <?= ($data['filter']['kelas'] == 'First Class') ? 'selected' : '' ?>>First Class</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Rute Perjalanan</label>
                        <select name="rute" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="">Semua Rute</option>
                            <option value="Halim-Padalarang" <?= ($data['filter']['rute'] == 'Halim-Padalarang') ? 'selected' : '' ?>>Halim → Padalarang</option>
                            <option value="Padalarang-Tegalluar" <?= ($data['filter']['rute'] == 'Padalarang-Tegalluar') ? 'selected' : '' ?>>Padalarang → Tegalluar</option>
                            <option value="Halim-Tegalluar" <?= ($data['filter']['rute'] == 'Halim-Tegalluar') ? 'selected' : '' ?>>Halim → Tegalluar</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-[#8C6239] hover:bg-[#74502e] text-white py-2.5 rounded-xl font-semibold text-sm transition-all shadow-sm">
                            <i class="fa-solid fa-filter mr-1"></i> Cari
                        </button>
                        <a href="/anvo/public/jadwal" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition-all text-center flex items-center justify-center" title="Reset Filter">
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
                                <th class="py-3.5 px-4 text-center">Aksi (Detail, Edit, Crew)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['jadwal'])): ?>
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 font-medium">Tidak ada jadwal yang sesuai dengan filter pencarian.</td>
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
                                            <?= $j['stasiun_asal'] ?> → <?= $j['stasiun_tujuan'] ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-bold text-slate-800 block"><?= $j['jam_berangkat'] ?> - <?= $j['jam_tiba'] ?> WIB</span>
                                            <span class="text-xs text-slate-400"><?= date('d M Y', strtotime($j['tanggal'])) ?></span>
                                        </td>
                                        <td class="py-4 px-4 font-bold text-[#8C6239]">
                                            Rp <?= number_format($j['harga'], 0, ',', '.') ?>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- Tombol Detail -->
                                                <button onclick="openModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Detail">
                                                    <i class="fa-solid fa-circle-info"></i> Detail
                                                </button>
                                                <!-- Tombol Edit -->
                                                <button onclick="openModal('modal-edit-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </button>
                                                <!-- Tombol Penugasan Crew -->
                                                <button onclick="openModal('modal-crew-<?= $j['id_jadwal'] ?>')" class="px-2.5 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl text-xs font-semibold transition-all" title="Penugasan Crew">
                                                    <i class="fa-solid fa-user-shield"></i> Crew
                                                </button>
                                                <!-- Tombol Hapus -->
                                                <a href="/anvo/public/jadwal/hapus/<?= $j['id_jadwal'] ?>" onclick="return confirm('Hapus jadwal ini?')" class="p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL POPUP: DETAIL JADWAL -->
                                    <div id="modal-detail-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 shadow-2xl space-y-4 animate-fade-in">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-base text-[#0F172A]"><i class="fa-solid fa-circle-info text-[#2B9BFB] mr-2"></i> Detail Jadwal #<?= $j['id_jadwal'] ?></h3>
                                                <button onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            <div class="space-y-2.5 text-sm text-slate-600">
                                                <div class="flex justify-between bg-slate-50 p-2.5 rounded-xl"><span>Jenis:</span> <strong class="text-[#0F172A]"><?= $j['jenis_jadwal'] ?></strong></div>
                                                <div class="flex justify-between bg-slate-50 p-2.5 rounded-xl"><span>Kereta:</span> <strong class="text-[#0F172A]"><?= $j['nama_kereta'] ?> (<?= $j['jenis_kelas'] ?>)</strong></div>
                                                <div class="flex justify-between bg-slate-50 p-2.5 rounded-xl"><span>Rute:</span> <strong class="text-[#0F172A]"><?= $j['stasiun_asal'] ?> → <?= $j['stasiun_tujuan'] ?></strong></div>
                                                <div class="flex justify-between bg-slate-50 p-2.5 rounded-xl"><span>Waktu:</span> <strong class="text-[#0F172A]"><?= $j['jam_berangkat'] ?> - <?= $j['jam_tiba'] ?> WIB</strong></div>
                                                <div class="flex justify-between bg-slate-50 p-2.5 rounded-xl"><span>Tanggal:</span> <strong class="text-[#0F172A]"><?= date('d M Y', strtotime($j['tanggal'])) ?></strong></div>
                                                <div class="flex justify-between bg-slate-50 p-2.5 rounded-xl"><span>Tarif:</span> <strong class="text-[#8C6239]">Rp <?= number_format($j['harga'], 0, ',', '.') ?></strong></div>
                                            </div>
                                            <button onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="w-full bg-[#0F172A] text-white py-2.5 rounded-xl font-semibold text-xs">Tutup</button>
                                        </div>
                                    </div>

                                    <!-- MODAL POPUP: EDIT JADWAL -->
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

                                    <!-- MODAL POPUP: PENUGASAN CREW -->
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
            
            <form action="/anvo/public/jadwal/tambah" method="POST" class="space-y-4">
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
                        <!-- Loop data koridor -->
                        <?php foreach($data['koridor_list'] ?? [] as $kor): ?>
                            <option value="<?= $kor['id_koridor'] ?>"><?= $kor['nama_koridor'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Armada Kereta (Aktif)</label>
                    <select name="id_kereta" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                        <?php foreach($data['kereta'] as $k): ?>
                            <option value="<?= $k['id_kereta'] ?>"><?= $k['nama_kereta'] ?> (<?= $k['jenis_kelas'] ?>)</option>
                        <?php endforeach; ?>
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

    <!-- Script Tambahan untuk Auto-Hide Alert -->
    <script>
        setTimeout(function() {
            const alertBox = document.getElementById('flash-alert');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000); // Alert hilang otomatis setelah 3 detik

        // Data stasiun per koridor atau pembersihan otomatis bisa diletakkan di sini
        document.getElementById('filter-koridor-jadwal').addEventListener('change', function() {
            const idKoridor = this.value;
            // Kamu bisa melakukan fetch AJAX untuk mengambil stasiun khusus koridor tersebut jika diinginkan
        });
    </script>

<?php 
require_once __DIR__ . '/../layouts/admin/footer.php'; 
?>