<?php 
$data['active_menu'] = 'jadwal';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
$jadwal = $data['jadwal'];
?>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="/anvo/public/jadwal" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Manajemen Kru Onboard - Jadwal #<?= $jadwal['id_jadwal'] ?></h1>
                    <p class="text-xs text-slate-400 font-medium"><?= $jadwal['stasiun_asal'] ?> → <?= $jadwal['stasiun_tujuan'] ?> (<?= date('d M Y', strtotime($jadwal['tanggal_mulai'])) ?> s.d. <?= date('d M Y', strtotime($jadwal['tanggal_akhir'])) ?>)</p>
                </div>
            </div>
            <div>
                <button onclick="openModal('modal-tambah-crew')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Tambah Petugas Kru
                </button>
            </div>
        </header>

        <main class="flex-1 p-8 lg:p-10 space-y-6 overflow-y-auto">
            
            <!-- Filter Pencarian di Halaman Kru -->
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
                <form action="/anvo/public/jadwal/crew/<?= $jadwal['id_jadwal'] ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                    <div class="sm:col-span-5">
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Filter Tanggal Tugas</label>
                        <input type="date" name="tanggal_tugas" value="<?= $data['filter_tanggal'] ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div class="sm:col-span-5">
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Cari Nama / NIK / NIP</label>
                        <input type="text" name="keyword" value="<?= $data['filter_keyword'] ?>" placeholder="Ketik nama, NIK, atau NIP..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div class="sm:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 bg-[#8C6239] hover:bg-[#74502e] text-white py-2.5 rounded-xl font-semibold text-sm transition-all shadow-sm">
                            <i class="fa-solid fa-filter mr-1"></i> Cari
                        </button>
                        <a href="/anvo/public/jadwal/crew/<?= $jadwal['id_jadwal'] ?>" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition-all flex items-center justify-center" title="Reset Filter">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-extrabold text-[#0F172A]">Daftar Penugasan Kru Berdasarkan Tanggal Operasional</h2>
                        <p class="text-xs text-slate-400">Hubungi kru via WhatsApp atau kelola penugasan per tanggal perjalanan.</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Tanggal Tugas</th>
                                <th class="py-3.5 px-4">ID Pekerja / NIP</th>
                                <th class="py-3.5 px-4">NIK</th>
                                <th class="py-3.5 px-4">Nama Lengkap & Posisi</th>
                                <th class="py-3.5 px-4">No. Telp / WhatsApp</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['kru_assigned'])): ?>
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 font-medium text-xs">Belum ada kru yang ditugaskan pada jadwal ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($data['kru_assigned'] as $kr): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-4 px-4 font-semibold text-slate-700"><?= date('d M Y', strtotime($kr['tanggal_tugas'])) ?></td>
                                        <td class="py-4 px-4 font-bold text-[#0F172A]"><?= $kr['nip'] ?></td>
                                        <td class="py-4 px-4 text-slate-600"><?= $kr['nik'] ?></td>
                                        <td class="py-4 px-4">
                                            <span class="font-bold text-[#0F172A] block"><?= $kr['nama_lengkap'] ?></span>
                                            <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-bold"><?= $kr['posisi'] ?></span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $kr['no_telepon'])) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-500 hover:text-white text-emerald-600 rounded-xl text-xs font-semibold transition-all">
                                                <i class="fa-brands fa-whatsapp"></i> <?= $kr['no_telepon'] ?>
                                            </a>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <a href="/anvo/public/jadwal/hapus_crew/<?= $kr['id_penugasan'] ?>/<?= $jadwal['id_jadwal'] ?>" onclick="return confirm('Peringatan: Apakah Anda yakin ingin menghapus penugasan kru ini?')" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all inline-block" title="Hapus Penugasan">
                                                <i class="fa-solid fa-trash-can"></i> Hapus
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

    <!-- MODAL: TAMBAH PETUGAS KRU -->
    <div id="modal-tambah-crew" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-lg text-[#0F172A]">Pilih & Tambahkan Petugas Kru</h3>
                    <p class="text-xs text-slate-400">Centang kru yang akan ditugaskan pada tanggal tertentu.</p>
                </div>
                <button onclick="closeModal('modal-tambah-crew')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="/anvo/public/jadwal/tambah_crew" method="POST" class="space-y-4">
                <input type="hidden" name="id_jadwal" value="<?= $jadwal['id_jadwal'] ?>">

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Tanggal Tugas Operasional</label>
                    <input type="date" name="tanggal_tugas" min="<?= $jadwal['tanggal_mulai'] ?>" max="<?= $jadwal['tanggal_akhir'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    <span class="text-[10px] text-slate-400 mt-1 block">Rentang jadwal valid: <?= date('d M Y', strtotime($jadwal['tanggal_mulai'])) ?> s.d. <?= date('d M Y', strtotime($jadwal['tanggal_akhir'])) ?></span>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Cari / Pilih Daftar Kru Master</label>
                    <input type="text" id="search-kru-input" placeholder="Ketik nama atau NIP/NIK untuk memfilter..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium mb-3 focus:outline-none focus:border-[#8C6239]">
                    
                    <div id="list-kru-container" class="max-h-60 overflow-y-auto space-y-2 border border-slate-100 p-3 rounded-2xl bg-slate-50">
                        <?php foreach($data['master_kru'] as $mk): ?>
                            <label class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-slate-200/60 hover:border-[#8C6239] cursor-pointer transition-all kru-item">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="id_kru[]" value="<?= $mk['id_kru'] ?>" class="w-4 h-4 rounded text-[#8C6239] focus:ring-[#8C6239]">
                                    <div>
                                        <span class="font-bold text-xs text-[#0F172A] block kru-nama"><?= $mk['nama_lengkap'] ?></span>
                                        <span class="text-[10px] text-slate-400 kru-nip">NIP: <?= $mk['nip'] ?> | NIK: <?= $mk['nik'] ?></span>
                                    </div>
                                </div>
                                <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded font-bold"><?= $mk['posisi'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md mt-4">
                    Simpan Penugasan Kru
                </button>
            </form>
        </div>
    </div>

    <script>
        // Fitur Pencarian Instan Nama/NIP Kru di dalam Modal
        document.getElementById('search-kru-input').addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const items = document.querySelectorAll('.kru-item');
            
            items.forEach(item => {
                const nama = item.querySelector('.kru-nama').textContent.toLowerCase();
                const nip = item.querySelector('.kru-nip').textContent.toLowerCase();
                if(nama.includes(keyword) || nip.includes(keyword)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // AJAX Otomatis Checkbox Berdasarkan Tanggal Tugas yang Dipilih di Modal
        document.getElementById('input-tanggal-tugas').addEventListener('change', function() {
            const tanggal = this.value;
            const idJadwal = <?= $jadwal['id_jadwal'] ?>;
            
            // Reset semua checkbox terlebih dahulu
            document.querySelectorAll('#list-kru-container input[type="checkbox"]').forEach(cb => cb.checked = false);

            if (!tanggal) return;

            fetch(`/anvo/public/jadwal/get_kru_assigned_ajax/${idJadwal}?tanggal=${tanggal}`)
                .then(response => response.json())
                .then(assignedIds => {
                    assignedIds.forEach(idKru => {
                        const checkbox = document.querySelector(`#list-kru-container input[value="${idKru}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                })
                .catch(err => console.error('Gagal memuat data penugasan kru:', err));
        });
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>