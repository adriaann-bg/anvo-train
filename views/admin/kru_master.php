<?php 
$data['active_menu'] = 'kru';
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

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Master Data Kru Operasional</h1>
                <p class="text-xs text-slate-400 font-medium">Kelola data pegawai, masinis, kondektur, dan kru onboard.</p>
            </div>
            <div>
                <button onclick="openModal('modal-tambah-kru')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Tambah Kru Baru
                </button>
            </div>
        </header>

        <main class="flex-1 p-8 lg:p-10 space-y-6 overflow-y-auto">
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold flex items-center justify-between">
                    <span><i class="fa-solid fa-circle-check mr-2"></i> <?= $_SESSION['success'] ?></span>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="overflow-x-auto custom-scrollbar">
                    <table id="dataTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Foto & NIP / NIK</th>
                                <th class="py-3.5 px-4">Nama Lengkap & Posisi</th>
                                <th class="py-3.5 px-4">Kontak (WhatsApp)</th>
                                <th class="py-3.5 px-4">Pendidikan & Agama</th>
                                <th class="py-3.5 px-4 text-center">Aksi (Detail, Edit, Hapus)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['kru_list'])): ?>
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 font-medium text-xs">Belum ada data master kru yang terdaftar.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($data['kru_list'] as $k): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-4 px-4 flex items-center gap-3">
                                            <img src="/anvo/public/img/kru/<?= !empty($k['foto']) ? $k['foto'] : 'default-kru.png' ?>" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-sm" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($k['nama_lengkap']) ?>&background=0F172A&color=fff'">
                                            <div>
                                                <span class="font-bold text-[#0F172A] block"><?= $k['nip'] ?></span>
                                                <span class="text-xs text-slate-400">NIK: <?= $k['nik'] ?></span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-bold text-[#0F172A] block"><?= $k['nama_lengkap'] ?></span>
                                            <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded font-bold"><?= $k['posisi'] ?></span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-semibold text-slate-700 block"><?= $k['no_telepon'] ?></span>
                                            <span class="text-xs text-slate-400"><?= $k['email'] ?></span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-xs font-bold text-slate-700 block"><?= $k['pendidikan_terakhir'] ?></span>
                                            <span class="text-[10px] text-slate-400"><?= $k['agama'] ?? '-' ?> | <?= $k['status_pernikahan'] ?></span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- Tombol Detail (ID Card) -->
                                                <button onclick="openModal('modal-detail-<?= $k['id_kru'] ?>')" class="px-2.5 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Lihat ID Card">
                                                    <i class="fa-solid fa-id-card"></i> Detail
                                                </button>
                                                <!-- Tombol Edit -->
                                                <button onclick="openModal('modal-edit-<?= $k['id_kru'] ?>')" class="px-2.5 py-1.5 bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Edit Kru">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </button>
                                                <!-- Tombol Hapus -->
                                                <a href="/anvo/public/kru/hapus/<?= $k['id_kru'] ?>" onclick="return confirm('Hapus data kru ini?')" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all inline-block" title="Hapus">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL DETAIL: OFFICIAL EMPLOYEE ID CARD (Rasio 8.56cm x 5.5cm) -->
                                    <div id="modal-detail-<?= $k['id_kru'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in text-center">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-extrabold text-base text-[#0F172A]"><i class="fa-solid fa-id-badge text-[#8C6239] mr-2"></i> Official ID Card Pegawai</h3>
                                                <button onclick="closeModal('modal-detail-<?= $k['id_kru'] ?>')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            
                                            <!-- KARTU ID (Rasio Kredensial 8.56 x 5.5 cm) -->
                                            <div class="mx-auto w-[360px] h-[230px] bg-[#0F172A] rounded-[1.5rem] p-5 shadow-xl text-white relative overflow-hidden flex flex-col justify-between border border-[#8C6239]/40 text-left">
                                                <!-- Background Accent Glow -->
                                                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#8C6239]/20 rounded-full blur-2xl"></div>

                                                <!-- Header ID Card -->
                                                <div class="flex justify-between items-center z-10">
                                                    <img src="/anvo/public/img/logo-anvo-hp.svg" alt="ANVO" class="h-6 object-contain">
                                                    <span class="text-[9px] uppercase tracking-widest bg-[#8C6239] text-white px-2 py-0.5 rounded font-bold">Onboard Crew</span>
                                                </div>

                                                <!-- Body ID Card -->
                                                <div class="flex items-center gap-4 z-10">
                                                    <img src="/anvo/public/img/kru/<?= !empty($k['foto']) ? $k['foto'] : 'default-kru.png' ?>" class="w-20 h-24 rounded-xl object-cover border-2 border-[#8C6239] shadow-md" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($k['nama_lengkap']) ?>&background=8C6239&color=fff'">
                                                    <div class="space-y-1">
                                                        <div>
                                                            <span class="text-[9px] uppercase text-slate-400 font-bold block">Nama Lengkap</span>
                                                            <span class="text-sm font-extrabold text-white block leading-snug"><?= $k['nama_lengkap'] ?></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-[9px] uppercase text-slate-400 font-bold block">ID Pekerja / NIP</span>
                                                            <span class="text-xs font-mono font-bold text-[#8C6239] bg-white/10 px-2 py-0.5 rounded"><?= $k['nip'] ?></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-[9px] uppercase text-slate-400 font-bold block">Role / Posisi</span>
                                                            <span class="text-xs font-bold text-sky-400"><?= $k['posisi'] ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Footer ID Card -->
                                                <div class="flex justify-between items-end border-t border-white/10 pt-2 z-10 text-[9px] text-slate-400">
                                                    <div>
                                                        <span class="block">PT Kereta Cepat ANVO Indonesia</span>
                                                        <span class="text-[8px] text-slate-500">Authorized Personnel ID</span>
                                                    </div>
                                                    <!-- Mockup QR Code -->
                                                    <div class="bg-white p-1 rounded-md">
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=35x35&data=NIP-<?= $k['nip'] ?>" class="w-8 h-8 object-contain">
                                                    </div>
                                                </div>
                                            </div>

                                            <button onclick="closeModal('modal-detail-<?= $k['id_kru'] ?>')" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3 rounded-xl font-bold text-sm transition-all shadow-md">Tutup ID Card</button>
                                        </div>
                                    </div>

                                    <!-- MODAL EDIT KRU -->
                                    <div id="modal-edit-<?= $k['id_kru'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-xl rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar text-left">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                                                <h3 class="font-extrabold text-lg text-[#0F172A]">Edit Data Kru: <?= $k['nama_lengkap'] ?></h3>
                                                <button onclick="closeModal('modal-edit-<?= $k['id_kru'] ?>')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            
                                            <form action="/anvo/public/kru/update/<?= $k['id_kru'] ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">NIP (ID Pekerja)</label>
                                                        <input type="text" name="nip" value="<?= $k['nip'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">NIK (KTP)</label>
                                                        <input type="text" name="nik" value="<?= $k['nik'] ?>" required maxlength="16" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 block mb-1">Nama Lengkap</label>
                                                    <input type="text" name="nama_lengkap" value="<?= $k['nama_lengkap'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                </div>

                                                <div class="grid grid-cols-3 gap-3">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">Posisi</label>
                                                        <select name="posisi" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                            <option value="Masinis" <?= ($k['posisi']=='Masinis')?'selected':'' ?>>Masinis</option>
                                                            <option value="Kondektur" <?= ($k['posisi']=='Kondektur')?'selected':'' ?>>Kondektur</option>
                                                            <option value="Teknisi" <?= ($k['posisi']=='Teknisi')?'selected':'' ?>>Teknisi</option>
                                                            <option value="Pramugari/a" <?= ($k['posisi']=='Pramugari/a')?'selected':'' ?>>Pramugari/a</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">Agama</label>
                                                        <select name="agama" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                            <option value="Islam" <?= ($k['agama']=='Islam')?'selected':'' ?>>Islam</option>
                                                            <option value="Kristen" <?= ($k['agama']=='Kristen')?'selected':'' ?>>Kristen</option>
                                                            <option value="Katolik" <?= ($k['agama']=='Katolik')?'selected':'' ?>>Katolik</option>
                                                            <option value="Hindu" <?= ($k['agama']=='Hindu')?'selected':'' ?>>Hindu</option>
                                                            <option value="Buddha" <?= ($k['agama']=='Buddha')?'selected':'' ?>>Buddha</option>
                                                            <option value="Konghucu" <?= ($k['agama']=='Konghucu')?'selected':'' ?>>Konghucu</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">Tanggal Lahir</label>
                                                        <input type="date" name="tanggal_lahir" value="<?= $k['tanggal_lahir'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">Email</label>
                                                        <input type="email" name="email" value="<?= $k['email'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">No. Telepon / WhatsApp</label>
                                                        <input type="text" name="no_telepon" value="<?= $k['no_telepon'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">Pendidikan Terakhir</label>
                                                        <input type="text" name="pendidikan_terakhir" value="<?= $k['pendidikan_terakhir'] ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">Status Pernikahan</label>
                                                        <select name="status_pernikahan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                            <option value="Belum Menikah" <?= ($k['status_pernikahan']=='Belum Menikah')?'selected':'' ?>>Belum Menikah</option>
                                                            <option value="Menikah" <?= ($k['status_pernikahan']=='Menikah')?'selected':'' ?>>Menikah</option>
                                                            <option value="Cerai" <?= ($k['status_pernikahan']=='Cerai')?'selected':'' ?>>Cerai</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 block mb-1">Ganti Foto Profil (Opsional)</label>
                                                    <input type="file" name="foto" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs">
                                                </div>

                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 block mb-1">Alamat Lengkap</label>
                                                    <textarea name="alamat_lengkap" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm"><?= $k['alamat_lengkap'] ?></textarea>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">Kontak Darurat</label>
                                                        <input type="text" name="kontak_darurat" value="<?= $k['kontak_darurat'] ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1">Riwayat Penyakit Khusus</label>
                                                        <input type="text" name="riwayat_penyakit" value="<?= $k['riwayat_penyakit'] ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                                                    </div>
                                                </div>

                                                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md mt-4">
                                                    Simpan Perubahan Data Kru
                                                </button>
                                            </form>
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

    <!-- MODAL: TAMBAH KRU BARU -->
    <div id="modal-tambah-kru" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-xl rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar text-left">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="font-extrabold text-lg text-[#0F172A]">Form Pendaftaran Master Kru</h3>
                <button onclick="closeModal('modal-tambah-kru')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="/anvo/public/kru/tambah" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">NIP (ID Pekerja)</label>
                        <input type="text" name="nip" required placeholder="Contoh: K-10928" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">NIK (KTP)</label>
                        <input type="text" name="nik" required maxlength="16" placeholder="16 Digit NIK" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required placeholder="Nama lengkap beserta gelar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Posisi / Jabatan</label>
                        <select name="posisi" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                            <option value="Masinis">Masinis</option>
                            <option value="Kondektur">Kondektur</option>
                            <option value="Teknisi">Teknisi</option>
                            <option value="Pramugari/a">Pramugari/a</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Agama</label>
                        <select name="agama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Email</label>
                        <input type="email" name="email" required placeholder="email@anvo.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">No. Telepon / WhatsApp</label>
                        <input type="text" name="no_telepon" required placeholder="08xxxxxxxxxx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" placeholder="Contoh: D3 Perkeretaapian" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block preview mb-1">Status Pernikahan</label>
                        <select name="status_pernikahan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-sm">
                            <option value="Belum Menikah">Belum Menikah</option>
                            <option value="Menikah">Menikah</option>
                            <option value="Cerai">Cerai</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Foto Profil Pegawai</label>
                    <input type="file" name="foto" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" rows="2" placeholder="Alamat domisili saat ini..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Kontak Darurat</label>
                        <input type="text" name="kontak_darurat" placeholder="Nama & No HP Keluarga" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Riwayat Penyakit Khusus</label>
                        <input type="text" name="riwayat_penyakit" placeholder="Kosongkan jika sehat" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md mt-4">
                    Simpan Data Kru Baru
                </button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data",
                    "search": "Cari:",
                    "paginate": { "first": "Awal", "last": "Akhir", "next": "Lanjut", "previous": "Kembali" }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [0, -1] } // Nonaktifkan sortir untuk kolom No (0) dan Aksi (terakhir)
                ]
            });
        });
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>