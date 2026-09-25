<?php 
$data['active_menu'] = 'user';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Master Data Pengguna (User)</h1>
                <p class="text-xs text-slate-400 font-medium">Daftar akun penumpang yang terdaftar pada sistem.</p>
            </div>
            <div>
                <button onclick="openModal('modal-tambah-user')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Tambah User Baru
                </button>
            </div>
        </header>

        <main class="flex-1 p-8 lg:p-10 space-y-6 overflow-y-auto">
            
            <!-- Flash Toast Notification dengan ID agar otomatis hilang -->
            <?php if (isset($_SESSION['success'])): ?>
                <div id="flash-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold flex items-center justify-between transition-all duration-500 shadow-sm">
                    <span><i class="fa-solid fa-circle-check mr-2"></i> <?= $_SESSION['success'] ?></span>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- KOTAK FILTER PENCARIAN MULTI-KOLOM -->
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
                <form action="/anvo/public/user" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= $data['filter']['nama'] ?? '' ?>" placeholder="Cari nama..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">NIK (KTP)</label>
                        <input type="text" name="nik" value="<?= $data['filter']['nik'] ?? '' ?>" placeholder="Cari NIK..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Email & No. HP</label>
                        <input type="text" name="kontak" value="<?= $data['filter']['kontak'] ?? '' ?>" placeholder="Cari email / no hp..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="<?= $data['filter']['tanggal_lahir'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-[#8C6239] hover:bg-[#74502e] text-white py-2.5 rounded-xl font-semibold text-sm transition-all shadow-sm">
                            <i class="fa-solid fa-filter mr-1"></i> Cari
                        </button>
                        <a href="/anvo/public/user" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition-all flex items-center justify-center" title="Reset Filter">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- TABEL USER -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Nama Lengkap</th>
                                <th class="py-3.5 px-4">NIK (KTP)</th>
                                <th class="py-3.5 px-4">Email & No. HP</th>
                                <th class="py-3.5 px-4">Tanggal Lahir</th>
                                <th class="py-3.5 px-4">Waktu Pendaftaran</th>
                                <th class="py-3.5 px-4 text-center">Aksi (Detail, Edit, Hapus)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['user_list'])): ?>
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 font-medium text-xs">Tidak ada data pengguna yang ditemukan.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($data['user_list'] as $u): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-4 px-4 font-bold text-[#0F172A]"><?= $u['nama'] ?></td>
                                        <td class="py-4 px-4 text-slate-600"><?= $u['nik'] ?? '-' ?></td>
                                        <td class="py-4 px-4">
                                            <span class="font-semibold text-slate-700 block"><?= $u['email'] ?></span>
                                            <span class="text-xs text-slate-400"><?= $u['no_hp'] ?></span>
                                        </td>
                                        <td class="py-4 px-4 text-slate-600"><?= $u['tanggal_lahir'] ? date('d M Y', strtotime($u['tanggal_lahir'])) : '-' ?></td>
                                        <td class="py-4 px-4 text-xs font-mono text-slate-500">
                                            <?= !empty($u['created_at']) ? date('d M Y, H:i', strtotime($u['created_at'])) : '-' ?>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button onclick="openModal('modal-detail-<?= $u['id_user'] ?>')" class="px-2.5 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Detail">
                                                    <i class="fa-solid fa-circle-info"></i> Detail
                                                </button>
                                                <button onclick="openModal('modal-edit-<?= $u['id_user'] ?>')" class="px-2.5 py-1.5 bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white rounded-xl text-xs font-semibold transition-all" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </button>
                                                <a href="/anvo/public/user/hapus/<?= $u['id_user'] ?>" onclick="return confirm('Hapus akun user ini?')" class="p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL DETAIL USER -->
                                    <div id="modal-detail-<?= $u['id_user'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                                        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 shadow-2xl space-y-4 animate-fade-in">
                                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-base text-[#0F172A]"><i class="fa-solid fa-circle-info text-[#2B9BFB] mr-2"></i> Detail Pengguna</h3>
                                                <button onclick="closeModal('modal-detail-<?= $u['id_user'] ?>')" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                                            </div>
                                            <div class="space-y-2 text-sm text-slate-600">
                                                <div class="bg-slate-50 p-3 rounded-xl flex justify-between"><span class="font-medium">Nama Lengkap:</span> <strong class="text-[#0F172A]"><?= $u['nama'] ?></strong></div>
                                                <div class="bg-slate-50 p-3 rounded-xl flex justify-between"><span class="font-medium">NIK:</span> <strong class="text-[#0F172A]"><?= $u['nik'] ?></strong></div>
                                                <div class="bg-slate-50 p-3 rounded-xl flex justify-between"><span class="font-medium">Email:</span> <strong class="text-[#0F172A]"><?= $u['email'] ?></strong></div>
                                                <div class="bg-slate-50 p-3 rounded-xl flex justify-between"><span class="font-medium">No. Telepon:</span> <strong class="text-[#0F172A]"><?= $u['no_hp'] ?></strong></div>
                                                <div class="bg-slate-50 p-3 rounded-xl flex justify-between"><span class="font-medium">Tanggal Lahir:</span> <strong class="text-[#0F172A]"><?= $u['tanggal_lahir'] ?></strong></div>
                                                <div class="bg-slate-50 p-3 rounded-xl flex justify-between"><span class="font-medium">Waktu Pendaftaran:</span> <strong class="text-[#0F172A]"><?= $u['created_at'] ?></strong></div>
                                            </div>
                                            <button onclick="closeModal('modal-detail-<?= $u['id_user'] ?>')" class="w-full bg-[#0F172A] text-white py-2.5 rounded-xl font-bold text-xs">Tutup</button>
                                        </div>
                                    </div>

                                    <!-- MODAL EDIT USER -->
                                    <div id="modal-edit-<?= $u['id_user'] ?>" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
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
                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 block mb-1">Waktu Pendaftaran Sistem (Timestamp)</label>
                                                    <input type="text" value="<?= $u['created_at'] ?? 'Otomatis Sistem' ?>" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 cursor-not-allowed">
                                                </div>
                                                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3 rounded-xl font-bold text-xs transition-all shadow-md mt-2">
                                                    Simpan Perubahan
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

    <!-- MODAL TAMBAH USER BARU -->
    <div id="modal-tambah-user" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
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
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Waktu Pendaftaran Sistem (Timestamp)</label>
                    <input type="text" value="Dibuat otomatis oleh sistem saat disimpan" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 cursor-not-allowed">
                </div>
                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3 rounded-xl font-bold text-xs transition-all shadow-md mt-2">
                    Simpan Akun Pengguna
                </button>
            </form>
        </div>
    </div>

    <!-- SCRIPT OTOMATIS HILANGKAN ALERT SETELAH 3 DETIK -->
    <script>
        setTimeout(function() {
            const alertBox = document.getElementById('flash-alert');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>