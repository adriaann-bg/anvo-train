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
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Nama Lengkap</th>
                                <th class="py-3.5 px-4">NIK (KTP)</th>
                                <th class="py-3.5 px-4">Email & No. HP</th>
                                <th class="py-3.5 px-4">Tanggal Lahir</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['user_list'])): ?>
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 font-medium text-xs">Belum ada pengguna terdaftar.</td>
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
                                        <td class="py-4 px-4 text-center">
                                            <a href="/anvo/public/user/hapus/<?= $u['id_user'] ?>" onclick="return confirm('Hapus akun user ini?')" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all inline-block" title="Hapus User">
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

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>