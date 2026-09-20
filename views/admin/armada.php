<?php 
$data['active_menu'] = 'armada';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>

    <div class="flex-1 flex flex-col min-w-0 relative">

        <!-- TOAST NOTIFICATION -->
        <div id="custom-toast" class="fixed bottom-8 right-8 z-50 transform translate-y-20 opacity-0 transition-all duration-300 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-semibold">
            <i id="toast-icon" class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
            <span id="toast-message">Notifikasi berhasil.</span>
        </div>

        <!-- TOP BAR -->
        <header class="h-20 bg-white border-b border-slate-200/60 px-6 sm:px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div>
                <h1 class="text-base sm:text-lg font-extrabold text-[#0F172A] tracking-tight">Manajemen Armada Kereta (Rolling Stock)</h1>
                <p class="text-xs text-slate-400 font-medium">Kelola spesifikasi teknis, mesin, kecepatan, dan status operasional armada.</p>
            </div>

            <div>
                <button onclick="openModal('modal-tambah-armada')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Armada Baru
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

            <!-- TABEL DAFTAR ARMADA -->
            <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div>
                    <h2 class="text-base sm:text-lg font-extrabold text-[#0F172A] tracking-tight">Daftar Rangkaian Kereta Cepat</h2>
                    <p class="text-xs text-slate-400">Seluruh unit rolling stock yang terdaftar dalam sistem operasional ANVO.</p>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Nama & Seri Kereta</th>
                                <th class="py-3.5 px-4">Kelas & Layout Kursi</th>
                                <th class="py-3.5 px-4">Spesifikasi Mesin & Kecepatan</th>
                                <th class="py-3.5 px-4">Status Operasional</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['armada_list'])): ?>
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 font-medium text-xs">Belum ada armada kereta yang terdaftar.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($data['armada_list'] as $arm): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-4 px-4 font-bold text-[#0F172A]"><?= htmlspecialchars($arm['nama_kereta']) ?></td>
                                        <td class="py-4 px-4">
                                            <div class="flex flex-wrap gap-1">
                                                <?php 
                                                $rawKelas = $arm['jenis_kelas'] ?? '';
                                                $kelasArr = [];
                                                if (!empty($arm['jenis_kelas_arr']) && is_array($arm['jenis_kelas_arr'])) {
                                                    $kelasArr = $arm['jenis_kelas_arr'];
                                                } else {
                                                    $decoded = json_decode($rawKelas, true);
                                                    $kelasArr = is_array($decoded) ? $decoded : (!empty($rawKelas) ? explode(',', $rawKelas) : ['Standar']);
                                                }
                                                foreach($kelasArr as $kls): 
                                                ?>
                                                    <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded-md font-bold"><?= htmlspecialchars(trim($kls)) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <span class="text-[10px] text-slate-400 font-medium mt-1 block">Multi-Layout Gerbong</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-semibold text-slate-700 block text-xs"><?= htmlspecialchars($arm['jenis_mesin'] ?? 'EMU-Gen4') ?></span>
                                            <span class="text-[11px] text-[#8C6239] font-bold"><i class="fa-solid fa-gauge-high mr-1"></i><?= $arm['kecepatan_maksimal'] ?? 350 ?> km/h</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <?php 
                                                $status = $arm['status_operasional'] ?? 'Aktif';
                                                $badgeColor = 'bg-emerald-50 text-emerald-600';
                                                if($status == 'Maintenance') $badgeColor = 'bg-amber-50 text-amber-600';
                                                if($status == 'Standby') $badgeColor = 'bg-sky-50 text-[#2B9BFB]';
                                                if($status == 'Rusak' || $status == 'Arsip/Gudang') $badgeColor = 'bg-rose-50 text-rose-500';
                                            ?>
                                            <span class="text-[10px] px-3 py-1 rounded-full font-bold <?= $badgeColor ?>">
                                                <?= $status ?>
                                            </span>
                                        </td>
                                        <!-- Kolom Aksi -->
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- Tombol Detail Lengkap -->
                                                <button onclick="bukaModalDetail(<?= htmlspecialchars(json_encode($arm)) ?>)" class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-[#0F172A] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Detail">
                                                    <i class="fa-solid fa-eye"></i> Detail
                                                </button>
                                                <!-- Tombol Edit -->
                                                <button onclick="bukaModalEdit(<?= htmlspecialchars(json_encode($arm)) ?>)" class="px-3 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </button>
                                                <!-- Tombol Hapus -->
                                                <a href="/anvo/public/armada/hapus/<?= $arm['id_kereta'] ?>" onclick="return confirm('Hapus armada kereta ini?')" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
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

    <!-- MODAL TAMBAH ARMADA -->
    <div id="modal-tambah-armada" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-lg text-[#0F172A]">Tambah Armada Kereta Baru</h3>
                    <p class="text-xs text-slate-400">Konfigurasi spesifikasi rangkaian rolling stock.</p>
                </div>
                <button onclick="closeModal('modal-tambah-armada')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="/anvo/public/armada/tambah" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Nama & Seri Kereta</label>
                    <input type="text" name="nama_kereta" placeholder="Contoh: G101 Antasena Evo" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                </div>

                <!-- Bagian Input Dinamis Multi-Kelas Tambah -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-500">Konfigurasi Kelas & Gerbong (Maks. 3)</label>
                        <button type="button" onclick="tambahBarisKelas()" class="text-xs font-bold text-[#8C6239] hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-plus"></i> Tambah Kelas Gerbong
                        </button>
                    </div>
                    <div id="container-kelas-gerbong" class="space-y-3">
                        <div class="kelas-row grid grid-cols-1 sm:grid-cols-12 gap-2 p-3 bg-slate-50 rounded-2xl border border-slate-200/60 items-center">
                            <div class="sm:col-span-5">
                                <label class="text-[10px] font-bold text-slate-400 block mb-1">Kelas Kereta</label>
                                <select name="jenis_kelas[]" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[#8C6239]">
                                    <option value="Executive Prime">Executive Prime</option>
                                    <option value="Luminary Capsule">Luminary Capsule</option>
                                    <option value="VVIP Skybox Suite">VVIP Skybox Suite</option>
                                </select>
                            </div>
                            <div class="sm:col-span-5">
                                <label class="text-[10px] font-bold text-slate-400 block mb-1">Layout Kursi</label>
                                <select name="layout_kursi[]" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[#8C6239]">
                                    <option value="Executive Prime (2-2)">Executive Prime (2-2)</option>
                                    <option value="Capsule Unit (1-1)">Capsule Unit (1-1)</option>
                                    <option value="Private Suite (Lounge)">Private Suite (Lounge)</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2 text-right">
                                <label class="text-[10px] font-bold text-transparent block mb-1">-</label>
                                <button type="button" onclick="hapusBarisKelas(this)" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Jenis Mesin</label>
                        <input type="text" name="jenis_mesin" value="Electric Multiple Unit (EMU-Gen4)" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Kecepatan Maksimal (km/h)</label>
                        <input type="number" name="kecepatan_maksimal" value="350" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Status Operasional</label>
                        <select name="status_operasional" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="Aktif">Aktif</option>
                            <option value="Standby">Standby</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Arsip/Gudang">Arsip / Gudang</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Kapasitas Kursi</label>
                        <input type="number" name="kapasitas_kursi" value="100" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md mt-4">Simpan Armada Baru</button>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT ARMADA -->
    <div id="modal-edit-armada" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-lg text-[#0F172A]">Edit Spesifikasi Armada</h3>
                    <p class="text-xs text-slate-400">Pembaruan data teknis rangkaian kereta.</p>
                </div>
                <button onclick="closeModal('modal-edit-armada')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="/anvo/public/armada/update" method="POST" class="space-y-4">
                <input type="hidden" name="id_kereta" id="edit-id-kereta">
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Nama & Seri Kereta</label>
                    <input type="text" name="nama_kereta" id="edit-nama-kereta" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                </div>

                <!-- Bagian Input Dinamis Multi-Kelas Edit -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-500">Konfigurasi Kelas & Gerbong (Maks. 3)</label>
                        <button type="button" onclick="tambahBarisKelasEdit()" class="text-xs font-bold text-[#8C6239] hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-plus"></i> Tambah Kelas Gerbong
                        </button>
                    </div>
                    <div id="edit-container-kelas-gerbong" class="space-y-3">
                        <!-- Baris dirender via JS -->
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Jenis Mesin</label>
                        <input type="text" name="jenis_mesin" id="edit-jenis-mesin" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Kecepatan Maksimal (km/h)</label>
                        <input type="number" name="kecepatan_maksimal" id="edit-kecepatan" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Status Operasional</label>
                        <select name="status_operasional" id="edit-status" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="Aktif">Aktif</option>
                            <option value="Standby">Standby</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Arsip/Gudang">Arsip / Gudang</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Kapasitas Kursi</label>
                        <input type="number" name="kapasitas_kursi" id="edit-kapasitas" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md mt-4">Simpan Perubahan Armada</button>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL ARMADA LENGKAP -->
    <div id="modal-detail-armada" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-lg text-[#0F172A]">Detail Spesifikasi Armada</h3>
                    <p class="text-xs text-slate-400">Informasi menyeluruh unit rolling stock.</p>
                </div>
                <button onclick="closeModal('modal-detail-armada')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="space-y-4 text-sm">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Nama & Seri Kereta</span>
                    <p id="detail-nama" class="font-extrabold text-[#0F172A] text-base">-</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Jenis Mesin</span>
                        <p id="detail-mesin" class="font-bold text-slate-700 text-xs">-</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kecepatan Maksimal</span>
                        <p id="detail-kecepatan" class="font-bold text-[#8C6239] text-xs">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Status Operasional</span>
                        <p id="detail-status" class="font-bold text-emerald-600 text-xs">-</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kapasitas Kursi</span>
                        <p id="detail-kapasitas" class="font-bold text-slate-700 text-xs">-</p>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Daftar Kelas & Layout Gerbong</span>
                    <div id="detail-container-kelas" class="space-y-2">
                        <!-- Render list kelas via JS -->
                    </div>
                </div>

                <!-- BAGIAN BARU: PETA VISUAL KORIDOR ARMADA -->
                <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100 space-y-3 mt-4">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block border-b border-slate-200 pb-2">Rute Operasional Armada</span>
                    
                    <div id="detail-rute-armada" class="flex items-center overflow-x-auto custom-scrollbar py-2">
                        <!-- Tampil via JS -->
                    </div>
                </div>
            </div>

            <button onclick="closeModal('modal-detail-armada')" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3 rounded-2xl font-semibold transition-all shadow-md">Tutup Detail</button>
        </div>
    </div>

    <!-- Script Global & Modal Editor -->
    <script>
        setTimeout(() => { const el = document.getElementById('flash-alert'); if(el) el.remove(); }, 3000);

        function tambahBarisKelas() {
            const container = document.getElementById('container-kelas-gerbong');
            if (container.querySelectorAll('.kelas-row').length >= 3) {
                showToast('Maksimal 3 kelas per rangkaian kereta.', true);
                return;
            }

            const newRow = document.createElement('div');
            newRow.className = 'kelas-row grid grid-cols-1 sm:grid-cols-12 gap-2 p-3 bg-slate-50 rounded-2xl border border-slate-200/60 items-center animate-fade-in';
            newRow.innerHTML = `
                <div class="sm:col-span-5">
                    <label class="text-[10px] font-bold text-slate-400 block mb-1">Kelas Kereta</label>
                    <select name="jenis_kelas[]" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[#8C6239]">
                        <option value="Executive Prime">Executive Prime</option>
                        <option value="Luminary Capsule">Luminary Capsule</option>
                        <option value="VVIP Skybox Suite">VVIP Skybox Suite</option>
                    </select>
                </div>
                <div class="sm:col-span-5">
                    <label class="text-[10px] font-bold text-slate-400 block mb-1">Layout Kursi</label>
                    <select name="layout_kursi[]" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[#8C6239]">
                        <option value="Executive Prime (2-2)">Executive Prime (2-2)</option>
                        <option value="Capsule Unit (1-1)">Capsule Unit (1-1)</option>
                        <option value="Private Suite (Lounge)">Private Suite (Lounge)</option>
                    </select>
                </div>
                <div class="sm:col-span-2 text-right">
                    <label class="text-[10px] font-bold text-transparent block mb-1">-</label>
                    <button type="button" onclick="hapusBarisKelas(this)" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
        }

        function tambahBarisKelasEdit() {
            const container = document.getElementById('edit-container-kelas-gerbong');
            if (container.querySelectorAll('.kelas-row').length >= 3) {
                showToast('Maksimal 3 kelas per rangkaian kereta.', true);
                return;
            }

            const newRow = document.createElement('div');
            newRow.className = 'kelas-row grid grid-cols-1 sm:grid-cols-12 gap-2 p-3 bg-slate-50 rounded-2xl border border-slate-200/60 items-center animate-fade-in';
            newRow.innerHTML = `
                <div class="sm:col-span-5">
                    <label class="text-[10px] font-bold text-slate-400 block mb-1">Kelas Kereta</label>
                    <select name="jenis_kelas[]" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[#8C6239]">
                        <option value="Executive Prime">Executive Prime</option>
                        <option value="Luminary Capsule">Luminary Capsule</option>
                        <option value="VVIP Skybox Suite">VVIP Skybox Suite</option>
                    </select>
                </div>
                <div class="sm:col-span-5">
                    <label class="text-[10px] font-bold text-slate-400 block mb-1">Layout Kursi</label>
                    <select name="layout_kursi[]" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[#8C6239]">
                        <option value="Executive Prime (2-2)">Executive Prime (2-2)</option>
                        <option value="Capsule Unit (1-1)">Capsule Unit (1-1)</option>
                        <option value="Private Suite (Lounge)">Private Suite (Lounge)</option>
                    </select>
                </div>
                <div class="sm:col-span-2 text-right">
                    <label class="text-[10px] font-bold text-transparent block mb-1">-</label>
                    <button type="button" onclick="hapusBarisKelas(this)" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
        }

        function hapusBarisKelas(button) {
            const container = button.closest('#container-kelas-gerbong, #edit-container-kelas-gerbong');
            if (container.querySelectorAll('.kelas-row').length > 1) {
                button.closest('.kelas-row').remove();
            } else {
                showToast('Kereta minimal harus memiliki 1 kelas.', true);
            }
        }

        function bukaModalEdit(armada) {
            document.getElementById('edit-id-kereta').value = armada.id_kereta;
            document.getElementById('edit-nama-kereta').value = armada.nama_kereta;
            document.getElementById('edit-jenis-mesin').value = armada.jenis_mesin || 'Electric Multiple Unit (EMU-Gen4)';
            document.getElementById('edit-kecepatan').value = armada.kecepatan_maksimal || 350;
            document.getElementById('edit-status').value = armada.status_operasional || 'Aktif';
            document.getElementById('edit-kapasitas').value = armada.kapasitas_kursi || 100;

            const container = document.getElementById('edit-container-kelas-gerbong');
            container.innerHTML = '';

            let kelasArr = armada.jenis_kelas_arr || JSON.parse(armada.jenis_kelas || '["Executive Prime"]');
            let layoutArr = armada.layout_kursi_arr || JSON.parse(armada.layout_kursi || '["Executive Prime (2-2)"]');

            kelasArr.forEach((kls, index) => {
                let lay = layoutArr[index] || 'Executive Prime (2-2)';
                const row = document.createElement('div');
                row.className = 'kelas-row grid grid-cols-1 sm:grid-cols-12 gap-2 p-3 bg-slate-50 rounded-2xl border border-slate-200/60 items-center';
                row.innerHTML = `
                    <div class="sm:col-span-5">
                        <label class="text-[10px] font-bold text-slate-400 block mb-1">Kelas Kereta</label>
                        <select name="jenis_kelas[]" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="Executive Prime" ${kls.trim() === 'Executive Prime' ? 'selected' : ''}>Executive Prime</option>
                            <option value="Luminary Capsule" ${kls.trim() === 'Luminary Capsule' ? 'selected' : ''}>Luminary Capsule</option>
                            <option value="VVIP Skybox Suite" ${kls.trim() === 'VVIP Skybox Suite' ? 'selected' : ''}>VVIP Skybox Suite</option>
                        </select>
                    </div>
                    <div class="sm:col-span-5">
                        <label class="text-[10px] font-bold text-slate-400 block mb-1">Layout Kursi</label>
                        <select name="layout_kursi[]" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[#8C6239]">
                            <option value="Executive Prime (2-2)" ${lay.trim() === 'Executive Prime (2-2)' ? 'selected' : ''}>Executive Prime (2-2)</option>
                            <option value="Capsule Unit (1-1)" ${lay.trim() === 'Capsule Unit (1-1)' ? 'selected' : ''}>Capsule Unit (1-1)</option>
                            <option value="Private Suite (Lounge)" ${lay.trim() === 'Private Suite (Lounge)' ? 'selected' : ''}>Private Suite (Lounge)</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 text-right">
                        <label class="text-[10px] font-bold text-transparent block mb-1">-</label>
                        <button type="button" onclick="hapusBarisKelas(this)" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                `;
                container.appendChild(row);
            });

            openModal('modal-edit-armada');
        }

        function bukaModalDetail(armada) {
            document.getElementById('detail-nama').innerText = armada.nama_kereta;
            document.getElementById('detail-mesin').innerText = armada.jenis_mesin || 'Electric Multiple Unit (EMU-Gen4)';
            document.getElementById('detail-kecepatan').innerText = (armada.kecepatan_maksimal || 350) + ' km/h';
            document.getElementById('detail-status').innerText = armada.status_operasional || 'Aktif';
            document.getElementById('detail-kapasitas').innerText = (armada.kapasitas_kursi || 100) + ' Kursi';

            // Render Kelas Gerbong
            const containerKelas = document.getElementById('detail-container-kelas');
            containerKelas.innerHTML = '';
            let kelasArr = armada.jenis_kelas_arr || JSON.parse(armada.jenis_kelas || '["Executive Prime"]');
            kelasArr.forEach((kls) => {
                const item = document.createElement('div');
                item.className = 'bg-white px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold text-[#2B9BFB] inline-block mr-2 mb-2';
                item.innerHTML = `<i class="fa-solid fa-train-subway mr-1.5"></i>${kls.trim()}`;
                containerKelas.appendChild(item);
            });

            // Fetch & Render Peta Rute Armada
            const ruteContainer = document.getElementById('detail-rute-armada');
            ruteContainer.innerHTML = '<span class="text-xs text-slate-400 italic">Memuat rute...</span>';
            
            if (!armada.id_koridor) {
                ruteContainer.innerHTML = '<span class="text-xs font-bold text-rose-500 bg-rose-50 px-3 py-1.5 rounded-lg"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Armada ini belum ditugaskan di koridor manapun (Berada di Pool).</span>';
            } else {
                fetch('/anvo/public/jadwal/get_stasiun_ajax/' + armada.id_koridor)
                    .then(r => r.json())
                    .then(data => {
                        ruteContainer.innerHTML = '';
                        data.forEach((st, index) => {
                            const isNode = document.createElement('div');
                            isNode.className = 'flex items-center min-w-max';
                            
                            // Visual: Lingkaran Hijau (Dilewati) -> Garis -> Lingkaran Hijau
                            isNode.innerHTML = `
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full border-[3px] border-emerald-500 bg-white z-10"></div>
                                    <span class="text-[10px] font-bold text-[#0F172A] mt-1">${st.nama_stasiun.split(' - ')[0]}</span>
                                </div>
                                ${index < data.length - 1 ? '<div class="w-10 sm:w-16 h-1 bg-emerald-500/30 -mt-4"></div>' : ''}
                            `;
                            ruteContainer.appendChild(isNode);
                        });
                    });
            }

            openModal('modal-detail-armada');
        }
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>