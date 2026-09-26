<?php 
$data['active_menu'] = 'user';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>

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

    <div class="flex-1 flex flex-col min-w-0 bg-slate-50/50">
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Master Data Pengguna (User)</h1>
                <p class="text-xs text-slate-400 font-medium">Daftar akun penumpang yang terdaftar pada sistem ANVO.</p>
            </div>
            <div>
                <button onclick="openModal('modal-tambah-user')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Tambah User Baru
                </button>
            </div>
        </header>

        <main class="flex-1 p-8 lg:p-10 space-y-6 overflow-y-auto">
            
            <?php if (isset($_SESSION['success'])): ?>
                <div id="flash-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold flex items-center justify-between transition-all duration-500 shadow-sm">
                    <span><i class="fa-solid fa-circle-check mr-2"></i> <?= $_SESSION['success'] ?></span>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- TABEL USER (Menggunakan DataTables murni tanpa filter form tambahan) -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="overflow-x-auto custom-scrollbar">
                    <table id="dataTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Nama Lengkap</th>
                                <th class="py-3.5 px-4">NIK (KTP)</th>
                                <th class="py-3.5 px-4">Email & No. HP</th>
                                <th class="py-3.5 px-4">Tanggal Lahir</th>
                                <th class="py-3.5 px-4">Waktu Pendaftaran</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php $no = 1; foreach($data['user_list'] as $u): ?>
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                    <td class="py-4 px-4 font-bold text-[#0F172A]"><?= htmlspecialchars($u['nama']) ?></td>
                                    <td class="py-4 px-4 text-slate-600 font-mono"><?= htmlspecialchars($u['nik'] ?? '-') ?></td>
                                    <td class="py-4 px-4">
                                        <span class="font-semibold text-slate-700 block"><?= htmlspecialchars($u['email']) ?></span>
                                        <span class="text-xs text-slate-400"><?= htmlspecialchars($u['no_hp']) ?></span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600"><?= !empty($u['tanggal_lahir']) ? date('d M Y', strtotime($u['tanggal_lahir'])) : '-' ?></td>
                                    <td class="py-4 px-4 text-xs font-mono text-slate-500">
                                        <?= !empty($u['created_at']) ? date('d M Y, H:i', strtotime($u['created_at'])) : '-' ?>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button onclick="openModal('modal-detail-<?= $u['id_user'] ?>')" class="px-2.5 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Detail Dokumen">
                                                <i class="fa-solid fa-file-lines"></i>
                                            </button>
                                            <button onclick="openModal('modal-edit-<?= $u['id_user'] ?>')" class="px-2.5 py-1.5 bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <a href="/anvo/public/user/hapus/<?= $u['id_user'] ?>" onclick="return confirm('Hapus akun user ini secara permanen?')" class="p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <?php 
                                    // LOGIKA PERHITUNGAN UMUR AKUN (PHP)
                                    $waktuDaftar = !empty($u['created_at']) ? $u['created_at'] : null;
                                    $teksLamaBuat = '-';
                                    
                                    if ($waktuDaftar) {
                                        $created = new DateTime($waktuDaftar);
                                        $now = new DateTime();
                                        $diff = $now->diff($created);
                                        $parts = [];
                                        
                                        if ($diff->y > 0) $parts[] = $diff->y . ' Tahun';
                                        if ($diff->m > 0) $parts[] = $diff->m . ' Bulan';
                                        if ($diff->d > 0) $parts[] = $diff->d . ' Hari';
                                        
                                        if (empty($parts)) {
                                            $teksLamaBuat = 'Baru Hari Ini';
                                        } else {
                                            $teksLamaBuat = implode(', ', $parts);
                                        }
                                    }
                                ?>

                                <!-- MODAL DETAIL USER (DESAIN KERTAS A4 DOKUMEN RESMI) -->
                                <!-- INI ADALAH WRAPPER MODAL YANG SEBELUMNYA HILANG -->
                                <div id="modal-detail-<?= $u['id_user'] ?>" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                                    
                                    <!-- INI ADALAH KONTEN KERTAS A4 (Yang akan di print) -->
                                    <div id="dokumen-a4-<?= $u['id_user'] ?>" class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl animate-fade-in relative rounded-xl">
                                        
                                        <!-- Aksen Header Atas Dokumen -->
                                        <div class="h-2 w-full bg-gradient-to-r from-[#8C6239] to-[#AF8B69] no-print"></div>
                                        
                                        <div class="p-8 sm:p-12 space-y-8">
                                            <!-- Kop Surat / Header Dokumen -->
                                            <div class="flex flex-col sm:flex-row justify-between items-start border-b-2 border-slate-800 pb-6">
                                                <div>
                                                    <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO" class="h-8 mb-3">
                                                    <h2 class="text-lg sm:text-xl font-black text-[#0F172A] tracking-widest uppercase">Formulir Identitas Akun</h2>
                                                    <p class="text-xs text-slate-500 font-mono mt-1">DOKUMEN NO: ANV-USR-<?= str_pad($u['id_user'], 4, '0', STR_PAD_LEFT) ?></p>
                                                </div>
                                                <div class="mt-4 sm:mt-0 text-left sm:text-right text-[11px] text-slate-500 font-mono space-y-1">
                                                    <p>Waktu Akses Data:</p>
                                                    <p class="font-bold text-[#0F172A]"><?= date('d F Y - H:i:s') ?> WIB</p>
                                                    <p class="pt-2 text-[10px] italic">Dicetak secara otomatis oleh sistem</p>
                                                </div>
                                            </div>

                                            <div class="space-y-6 text-sm">
                                                <!-- Bagian 1: Identitas -->
                                                <div>
                                                    <h3 class="font-bold text-[#8C6239] uppercase tracking-wider text-xs mb-3 flex items-center gap-2">
                                                        <i class="fa-solid fa-user-check"></i> I. Identitas Pasien / Pengguna
                                                    </h3>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 bg-slate-50/50 p-5 border border-slate-200 rounded-lg">
                                                        <div>
                                                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Nama Lengkap (Sesuai KTP)</span>
                                                            <span class="block font-bold text-[#0F172A] text-base mt-0.5"><?= htmlspecialchars($u['nama']) ?></span>
                                                        </div>
                                                        <div>
                                                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Nomor Induk Kependudukan (NIK)</span>
                                                            <span class="block font-mono font-bold text-[#0F172A] mt-0.5"><?= htmlspecialchars($u['nik'] ?? 'BELUM DIUPDATE') ?></span>
                                                        </div>
                                                        <div>
                                                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Alamat Email</span>
                                                            <span class="block font-semibold text-slate-700 mt-0.5"><?= htmlspecialchars($u['email']) ?></span>
                                                        </div>
                                                        <div>
                                                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Nomor Handphone / WA</span>
                                                            <span class="block font-semibold text-slate-700 mt-0.5"><?= htmlspecialchars($u['no_hp']) ?></span>
                                                        </div>
                                                        <div class="sm:col-span-2">
                                                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Tanggal Lahir</span>
                                                            <span class="block font-semibold text-slate-700 mt-0.5"><?= !empty($u['tanggal_lahir']) ? date('d F Y', strtotime($u['tanggal_lahir'])) : '-' ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Bagian 2: Riwayat Sistem -->
                                                <div>
                                                    <h3 class="font-bold text-[#8C6239] uppercase tracking-wider text-xs mb-3 flex items-center gap-2">
                                                        <i class="fa-solid fa-server"></i> II. Rekam Jejak Sistem
                                                    </h3>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                        <div class="border border-slate-200 p-4 rounded-lg bg-white">
                                                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Waktu Pendaftaran Awal</span>
                                                            <span class="block font-mono font-bold text-[#0F172A] mt-1 text-sm">
                                                                <?= $waktuDaftar ? date('d M Y, H:i:s', strtotime($waktuDaftar)) . ' WIB' : '<span class="text-rose-500">Data Tidak Tersedia</span>' ?>
                                                            </span>
                                                        </div>
                                                        <div class="border border-slate-200 p-4 rounded-lg bg-[#0F172A] text-white">
                                                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Total Lama Pembuatan Akun</span>
                                                            <span class="block font-bold text-[#8C6239] mt-1 text-sm"><?= $teksLamaBuat ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Bagian 3: Syarat dan Ketentuan -->
                                                <div class="pt-4">
                                                    <h3 class="font-bold text-slate-800 uppercase tracking-wider text-[10px] mb-2">III. Syarat & Ketentuan Sistem E-Ticketing ANVO</h3>
                                                    <div class="text-[10px] text-slate-500 text-justify space-y-2 leading-relaxed bg-slate-50 p-4 rounded-lg border border-slate-200">
                                                        <p>1. Data kepemilikan akun ini dilindungi penuh oleh kebijakan privasi perusahaan. Segala bentuk penyalahgunaan atau pencurian data akan diproses sesuai hukum keamanan *cyber* yang berlaku di Indonesia.</p>
                                                        <p>2. Pengguna setuju bahwa tiket yang dipesan menggunakan data NIK ini bersifat mengikat dan tidak dapat dipindahtangankan (non-transferable) tanpa prosedur birokrasi resmi di loket stasiun.</p>
                                                        <p>3. Pihak Manajemen PT Kereta Cepat ANVO berhak penuh membekukan aktivitas akun secara sepihak apabila sistem mendeteksi adanya transaksi anomali, penipuan, pencaloan, atau eksploitasi sistem e-ticketing.</p>
                                                    </div>
                                                </div>

                                                <!-- Validasi Cap Digital & Tombol Aksi -->
                                                <div class="mt-8 pt-8 border-t border-slate-200 flex justify-between items-end">
                                                    
                                                    <!-- TOMBOL HANYA MUNCUL DI LAYAR (DISEMBUNYIKAN SAAT PRINT) -->
                                                    <div class="flex gap-2 no-print">
                                                        <button onclick="closeModal('modal-detail-<?= $u['id_user'] ?>')" class="px-5 py-2.5 bg-slate-100 hover:bg-[#0F172A] hover:text-white text-slate-600 rounded-lg font-bold text-xs transition-colors shadow-sm">
                                                            Tutup
                                                        </button>
                                                        <button onclick="cetakDokumen(<?= $u['id_user'] ?>)" class="px-5 py-2.5 bg-[#8C6239] hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] text-white rounded-lg font-bold text-xs transition-colors shadow-sm flex items-center gap-2">
                                                            <i class="fa-solid fa-print"></i> Cetak PDF
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- CAP DIGITAL (Selalu tampil termasuk saat diprint) -->
                                                    <div class="text-center relative mr-2">
                                                        <span class="block text-[9px] text-slate-400 mb-6 uppercase tracking-widest">Divalidasi Oleh</span>
                                                        <!-- Stamp Logo Bayangan -->
                                                        <img src="/anvo/public/img/logo-anvo-hp.svg" class="h-8 mx-auto opacity-10 invert absolute top-4 left-0 right-0 z-0">
                                                        <span class="block text-[10px] font-extrabold text-[#0F172A] mt-2 border-b-2 border-slate-800 pb-1 relative z-10">PT KERETA CEPAT ANVO</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END MODAL DETAIL -->

                                <!-- MODAL EDIT USER -->
                                <div id="modal-edit-<?= $u['id_user'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                                    <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-5 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
                                        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                                            <h3 class="font-extrabold text-lg text-[#0F172A]">Edit Data Pengguna</h3>
                                            <button onclick="closeModal('modal-edit-<?= $u['id_user'] ?>')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                                        </div>
                                        <form action="/anvo/public/user/update/<?= $u['id_user'] ?>" method="POST" class="space-y-4 text-left">
                                            <div>
                                                <label class="text-xs font-bold text-slate-500 block mb-1">Nama Lengkap</label>
                                                <input type="text" name="nama" value="<?= $u['nama'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-slate-500 block mb-1">NIK (KTP)</label>
                                                <input type="text" name="nik" value="<?= $u['nik'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 block mb-1">Tanggal Lahir</label>
                                                    <input type="date" name="tanggal_lahir" value="<?= $u['tanggal_lahir'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                                </div>
                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 block mb-1">No. Telepon</label>
                                                    <input type="text" name="no_hp" value="<?= $u['no_hp'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-slate-500 block mb-1">Email</label>
                                                <input type="email" name="email" value="<?= $u['email'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-slate-500 block mb-1">Password Baru <span class="text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span></label>
                                                <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                                            </div>
                                            <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3 rounded-xl font-bold text-xs transition-all shadow-md mt-2">
                                                Simpan Perubahan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL TAMBAH USER BARU -->
    <div id="modal-tambah-user" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-5 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="font-extrabold text-lg text-[#0F172A]">Tambah Akun Pengguna Baru</h3>
                <button onclick="closeModal('modal-tambah-user')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="/anvo/public/user/tambah" method="POST" class="space-y-4 text-left">
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan nama sesuai KTP..." required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">NIK (Sesuai KTP)</label>
                    <input type="text" name="nik" placeholder="Masukkan 16 digit NIK..." required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">No. Telepon</label>
                        <input type="text" name="no_hp" placeholder="Contoh: 08123456789" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Email</label>
                    <input type="email" name="email" placeholder="Masukkan email aktif..." required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Password</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter..." required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                </div>
                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3 rounded-xl font-bold text-xs transition-all shadow-md mt-2">
                    Simpan Akun Pengguna
                </button>
            </form>
        </div>
    </div>

    <!-- SCRIPT OTOMATIS HILANGKAN ALERT, MODAL, & DATATABLE -->
    <script>
        setTimeout(function() {
            const alertBox = document.getElementById('flash-alert');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);

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

        $(document).ready(function() {$('#dataTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ada data pengguna yang ditemukan.",
                    "info": "Halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Data kosong",
                    "search": "Cari Cepat:",
                    "paginate": { "first": "Awal", "last": "Akhir", "next": "Lanjut", "previous": "Kembali" }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6] } 
                ]
            });
        });

        // Script Pintar untuk Cetak PDF
        function cetakDokumen(id_user) {
            const konten = document.getElementById('dokumen-a4-' + id_user).innerHTML;
            
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
                        <title>Dokumen ANVO - ${id_user}</title>
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
                        <div class="max-w-3xl mx-auto rounded-xl overflow-hidden relative font-sans text-sm p-4">
                            ${konten}
                        </div>
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