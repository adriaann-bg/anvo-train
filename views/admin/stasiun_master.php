<?php 
$data['active_menu'] = 'stasiun'; // Pastikan sidebar.php Anda memiliki indikator 'stasiun'
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>
    <!-- DataTables Library -->
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
                <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Master Data Stasiun</h1>
                <p class="text-xs text-slate-400 font-medium">Kelola infrastruktur dan stasiun kereta cepat ANVO.</p>
            </div>
            <div>
                <button onclick="openModal('modal-tambah-stasiun')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Stasiun
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

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="overflow-x-auto custom-scrollbar">
                    <table id="dataTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Gambar</th>
                                <th class="py-3.5 px-4">Kode & Nama Stasiun</th>
                                <th class="py-3.5 px-4">Kota & Julukan</th>
                                <th class="py-3.5 px-4">Label</th>
                                <th class="py-3.5 px-4 text-center">Aksi (Detail, Edit, Hapus)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(!empty($data['stasiun_list'])): ?>
                                <?php $no = 1; foreach($data['stasiun_list'] as $s): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-4 px-4">
                                            <img src="<?= htmlspecialchars($s['image_url']) ?>" class="w-14 h-10 rounded-lg object-cover shadow-sm border border-slate-200" onerror="this.src='/anvo/public/img/stasiun-default.jpg'">
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-bold text-[#0F172A] block"><?= $s['nama_stasiun'] ?></span>
                                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-bold">KODE: <?= $s['kode_stasiun'] ?></span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-semibold text-slate-700 block"><?= $s['kota'] ?></span>
                                            <span class="text-xs text-slate-400 italic">"<?= $s['julukan'] ?>"</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <?php if($s['is_top_destination'] == 1): ?>
                                                <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full font-bold"><i class="fa-solid fa-star mr-1"></i> Top Destinasi</span>
                                            <?php else: ?>
                                                <span class="text-[10px] bg-slate-50 text-slate-500 px-2.5 py-1 rounded-full font-bold">Reguler</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button onclick="bukaModalDetail(<?= htmlspecialchars(json_encode($s)) ?>)" class="px-2.5 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all">
                                                    <i class="fa-solid fa-circle-info"></i>
                                                </button>
                                                <button onclick="bukaModalEdit(<?= htmlspecialchars(json_encode($s)) ?>)" class="px-2.5 py-1.5 bg-amber-50 text-[#8C6239] hover:bg-[#8C6239] hover:text-white rounded-xl text-xs font-semibold transition-all">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <a href="/anvo/public/stasiun/hapus/<?= $s['id_stasiun'] ?>" onclick="return confirm('Hapus stasiun ini?')" class="p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all">
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

    <!-- MODAL TAMBAH STASIUN -->
    <div id="modal-tambah-stasiun" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-5 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="font-extrabold text-lg text-[#0F172A]">Tambah Stasiun Baru</h3>
                <button onclick="closeModal('modal-tambah-stasiun')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="/anvo/public/stasiun/tambah" method="POST" class="space-y-4">
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-1">
                        <label class="text-xs font-bold text-slate-500 block mb-1">Kode (Max 3)</label>
                        <input type="text" name="kode_stasiun" maxlength="3" required placeholder="HLM" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm uppercase">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-slate-500 block mb-1">Nama Lengkap Stasiun</label>
                        <input type="text" name="nama_stasiun" required placeholder="Halim (HLM) - Jakarta" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Kota</label>
                        <input type="text" name="kota" required placeholder="Jakarta" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Julukan Kota</label>
                        <input type="text" name="julukan" required placeholder="Kota Metropolitan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">URL Gambar Stasiun</label>
                    <input type="url" name="image_url" required placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Kategori Destinasi</label>
                    <select name="is_top_destination" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                        <option value="0">Stasiun Reguler</option>
                        <option value="1">Top Destinasi Wisata</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3 rounded-xl font-bold text-sm shadow-md mt-2">Simpan Stasiun</button>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT STASIUN -->
    <div id="modal-edit-stasiun" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl space-y-5 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h3 class="font-extrabold text-lg text-[#0F172A]">Edit Stasiun</h3>
                <button onclick="closeModal('modal-edit-stasiun')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="form-edit-stasiun" method="POST" class="space-y-4">
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-1">
                        <label class="text-xs font-bold text-slate-500 block mb-1">Kode (Max 3)</label>
                        <input type="text" name="kode_stasiun" id="edit-kode" maxlength="3" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm uppercase">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-slate-500 block mb-1">Nama Lengkap Stasiun</label>
                        <input type="text" name="nama_stasiun" id="edit-nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Kota</label>
                        <input type="text" name="kota" id="edit-kota" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">Julukan Kota</label>
                        <input type="text" name="julukan" id="edit-julukan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">URL Gambar Stasiun</label>
                    <input type="url" name="image_url" id="edit-image" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 block mb-1">Kategori Destinasi</label>
                    <select name="is_top_destination" id="edit-top" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm">
                        <option value="0">Stasiun Reguler</option>
                        <option value="1">Top Destinasi Wisata</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#0F172A] hover:bg-[#8C6239] text-white py-3 rounded-xl font-bold text-sm shadow-md mt-2">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL STASIUN -->
    <div id="modal-detail-stasiun" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-0 shadow-2xl animate-fade-in overflow-hidden">
            <div class="relative h-48 w-full bg-slate-200">
                <img id="detail-img" src="" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 to-transparent flex items-end p-6">
                    <div>
                        <span id="detail-kode" class="text-[10px] bg-[#8C6239] text-white px-2 py-0.5 rounded font-bold uppercase tracking-wider mb-2 inline-block"></span>
                        <h3 id="detail-judul-nama" class="font-extrabold text-2xl text-white"></h3>
                    </div>
                </div>
                <button onclick="closeModal('modal-detail-stasiun')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white hover:bg-white hover:text-slate-900 flex items-center justify-center transition-all"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-sky-50 text-[#2B9BFB] flex items-center justify-center text-lg"><i class="fa-solid fa-city"></i></div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kota & Julukan</span>
                        <strong id="detail-kota-julukan" class="text-[#0F172A]"></strong>
                    </div>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kategori</span>
                        <strong id="detail-kategori" class="text-[#0F172A]"></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ada stasiun yang ditemukan",
                    "info": "Halaman _PAGE_ dari _PAGES_",
                    "search": "Cari Stasiun:",
                    "paginate": { "first": "Awal", "last": "Akhir", "next": "Lanjut", "previous": "Kembali" }
                },
                "columnDefs": [ { "orderable": false, "targets": [0, 1, 5] } ]
            });
        });

        function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }

        function bukaModalEdit(stasiun) {
            document.getElementById('form-edit-stasiun').action = '/anvo/public/stasiun/update/' + stasiun.id_stasiun;
            document.getElementById('edit-kode').value = stasiun.kode_stasiun;
            document.getElementById('edit-nama').value = stasiun.nama_stasiun;
            document.getElementById('edit-kota').value = stasiun.kota;
            document.getElementById('edit-julukan').value = stasiun.julukan;
            document.getElementById('edit-image').value = stasiun.image_url;
            document.getElementById('edit-top').value = stasiun.is_top_destination;
            openModal('modal-edit-stasiun');
        }

        function bukaModalDetail(stasiun) {
            document.getElementById('detail-img').src = stasiun.image_url;
            document.getElementById('detail-kode').innerText = 'KODE: ' + stasiun.kode_stasiun;
            document.getElementById('detail-judul-nama').innerText = stasiun.nama_stasiun;
            document.getElementById('detail-kota-julukan').innerText = stasiun.kota + ' ("' + stasiun.julukan + '")';
            document.getElementById('detail-kategori').innerText = stasiun.is_top_destination == 1 ? 'Top Destinasi Wisata' : 'Stasiun Reguler';
            openModal('modal-detail-stasiun');
        }

        setTimeout(function() {
            const alertBox = document.getElementById('flash-alert');
            if (alertBox) { alertBox.style.opacity = '0'; setTimeout(() => alertBox.remove(), 500); }
        }, 3000);
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>