<?php 
$data['active_menu'] = 'route';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>

    <div class="flex-1 flex flex-col min-w-0">

        <!-- TOP BAR -->
        <header class="h-20 bg-white border-b border-slate-200/60 px-6 sm:px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div>
                <h1 class="text-lg sm:text-xl font-extrabold text-[#0F172A] tracking-tight">Manajemen Rute Koridor</h1>
                <p class="text-xs text-slate-400 font-medium">Atur urutan stasiun linier dan koridor perjalanan kereta.</p>
            </div>

            <div>
                <button onclick="openModal('modal-tambah-koridor')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Buat Koridor Baru</span><span class="sm:hidden">Tambah</span>
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

            <!-- KARTU KORIDOR -->
            <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-[#0F172A] tracking-tight">Daftar Koridor Jalur Utama</h2>
                    <p class="text-xs text-slate-400">Pilih koridor untuk mengatur urutan stasiun linier dan peta rute visual.</p>
                </div>

                <?php if(empty($data['koridor'])): ?>
                    <div class="py-12 text-center text-slate-400 text-sm border-2 border-dashed border-slate-100 rounded-3xl">
                        <i class="fa-solid fa-route text-4xl mb-3 text-slate-300 block"></i>
                        Belum ada koridor rute yang dikonfigurasi.
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach($data['koridor'] as $k): ?>
                            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200/60 space-y-4 hover:border-[#8C6239]/50 transition-all">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-base text-[#0F172A]"><?= htmlspecialchars($k['nama_koridor']) ?></h3>
                                        <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($k['keterangan'] ?? 'Tanpa keterangan koridor.') ?></p>
                                    </div>
                                    <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full font-bold">Aktif</span>
                                </div>
                                <div class="pt-3 border-t border-slate-200/60 flex justify-between items-center text-xs">
                                    <span class="text-slate-500 font-medium"><i class="fa-solid fa-train-subway mr-1 text-[#8C6239]"></i> Jalur Linier Siap</span>
                                    <a href="/anvo/public/route/detail/<?= $k['id_koridor'] ?>" class="px-4 py-2 bg-[#0F172A] hover:bg-[#8C6239] text-white rounded-xl font-semibold transition-all">
                                        Kelola Jalur →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <!-- MODAL TAMBAH KORIDOR -->
    <div id="modal-tambah-koridor" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="font-extrabold text-lg text-[#0F172A]">Buat Koridor Jalur Baru</h3>
                <button onclick="closeModal('modal-tambah-koridor')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="/anvo/public/route/tambah_koridor" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Nama Koridor</label>
                    <input type="text" name="nama_koridor" placeholder="Contoh: Koridor Utama Jakarta - Bandung" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Deskripsi singkat jalur..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]"></textarea>
                </div>
                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3.5 rounded-2xl font-semibold transition-all shadow-md">Simpan Koridor</button>
            </form>
        </div>
    </div>

    <script>
        setTimeout(() => { const el = document.getElementById('flash-alert'); if(el) el.remove(); }, 3000);

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