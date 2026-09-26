<?php 
// Tetapkan status menu aktif untuk sidebar
$data['active_menu'] = 'dashboard';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 

// Instansiasi model untuk mengambil data relasi kru & penumpang per jadwal di dalam view
$adminModel = new AdminModel();
?>

<!-- DataTables CSS & JS CDN -->
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

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50/50">

        <!-- TOP BAR -->
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Pusat Kendali Operasional Kereta Cepat</h1>
                <p class="text-xs text-slate-400 font-medium">Monitoring real-time perjalanan, manifes penumpang, dan kesiapan kru.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200/60 text-xs font-semibold text-slate-600">
                    <i class="fa-regular fa-calendar-days text-[#8C6239]"></i>
                    <span><?= date('d M Y') ?></span>
                </div>
            </div>
        </header>

        <!-- DASHBOARD BODY -->
        <main class="flex-1 p-8 lg:p-10 space-y-8 overflow-y-auto">

            <!-- Flash Toast Notification -->
            <?php if (isset($_SESSION['success'])): ?>
                <div id="flash-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm flex items-center justify-between transition-all duration-500">
                    <span class="text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i> <?= $_SESSION['success'] ?>
                    </span>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- TOP METRICS (NYATA DARI DATABASE) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jadwal Aktif</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#8C6239] flex items-center justify-center font-bold">
                            <i class="fa-solid fa-train-subway"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= count($data['jadwal']) ?></h3>
                        <p class="text-[11px] text-emerald-500 font-semibold mt-1"><i class="fa-solid fa-arrow-trend-up"></i> Beroperasi Normal</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Armada Kereta</span>
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-gauge-high"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= count($data['kereta']) ?></h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">Series Eksekutif & Luminary</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Penumpang</span>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= $data['total_penumpang'] ?></h3>
                        <p class="text-[11px] text-purple-500 font-semibold mt-1">Manifes Keseluruhan</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kesiapan Kru</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#0F172A]"><?= $data['total_kru_aktif'] ?></h3>
                        <p class="text-[11px] text-emerald-500 font-semibold mt-1">Kru Status Aktif</p>
                    </div>
                </div>
            </div>

            <!-- TABEL UTAMA OPERASIONAL KERETA (DENGAN DATATABLES PAGINATION) -->
            <div id="tabel-kereta" class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Monitor Operasional Kereta & Kru</h2>
                        <p class="text-xs text-slate-400">Daftar perjalanan kereta cepat, manifes penumpang, serta kontak kru aktif.</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table id="dataTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">No.</th>
                                <th class="py-3.5 px-4">Nama Kereta</th>
                                <th class="py-3.5 px-4">Series</th>
                                <th class="py-3.5 px-4">Stasiun Berangkat</th>
                                <th class="py-3.5 px-4">Stasiun Tujuan</th>
                                <th class="py-3.5 px-4">Manifes</th>
                                <th class="py-3.5 px-4">Kru</th>
                                <th class="py-3.5 px-4 text-center">Aksi Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(!empty($data['jadwal'])): ?>
                                <?php 
                                $no = 1; 
                                foreach($data['jadwal'] as$j): 
                                    $kruList =$adminModel->getKruByJadwal($j['id_jadwal']);$penumpangList = $adminModel->getPenumpangsByJadwal($j['id_jadwal']);
                                    $totalKru = count($kruList);
                                    $totalPenumpang = count($penumpangList);
                                ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-slate-500"><?= $no++ ?></td>
                                        <td class="py-4 px-4 font-bold text-[#0F172A]"><?= htmlspecialchars($j['nama_kereta']) ?></td>
                                        <td class="py-4 px-4">
                                            <span class="text-xs bg-sky-50 text-[#2B9BFB] px-2.5 py-1 rounded-lg font-bold"><?= htmlspecialchars($j['jenis_kelas']) ?></span>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-slate-700"><?= htmlspecialchars($j['stasiun_asal']) ?> <span class="text-xs text-slate-400 block font-normal"><?= $j['jam_berangkat'] ?></span></td>
                                        <td class="py-4 px-4 font-semibold text-slate-700"><?= htmlspecialchars($j['stasiun_tujuan']) ?> <span class="text-xs text-slate-400 block font-normal"><?= $j['jam_tiba'] ?></span></td>
                                        <td class="py-4 px-4 font-bold text-purple-600"><?= $totalPenumpang ?> Penumpang</td>
                                        <td class="py-4 px-4 font-bold text-emerald-600"><?= $totalKru ?> Kru</td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button onclick="openModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="px-3 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5" title="Dokumen Resmi A4">
                                                    <i class="fa-solid fa-file-lines"></i> Detail Dokumen
                                                </button>
                                                <a href="/anvo/public/admin/hapus_jadwal/<?= $j['id_jadwal'] ?>" onclick="return confirm('Hapus jadwal operasional ini?')" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all" title="Hapus Jadwal">
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

    <!-- SEMUA MODAL DETAIL DOKUMEN RESMI A4 DIPERBAIKI (DIPINDAHKAN KELUAR TABEL) -->
    <?php if(!empty($data['jadwal'])): ?>
        <?php foreach($data['jadwal'] as$j): 
            $kruList =$adminModel->getKruByJadwal($j['id_jadwal']);$penumpangList = $adminModel->getPenumpangsByJadwal($j['id_jadwal']);
        ?>
            <div id="modal-detail-<?= $j['id_jadwal'] ?>" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                
                <!-- Container Kertas A4 -->
                <div id="dokumen-a4-<?= $j['id_jadwal'] ?>" class="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl animate-fade-in relative rounded-xl text-left">
                    
                    <!-- Aksen Header Atas Dokumen -->
                    <div class="h-2 w-full bg-gradient-to-r from-[#8C6239] to-[#AF8B69] no-print"></div>
                    
                    <div class="p-8 sm:p-12 space-y-8">
                        
                        <!-- Kop Surat / Header Dokumen -->
                        <div class="flex flex-col sm:flex-row justify-between items-start border-b-2 border-slate-800 pb-6">
                            <div>
                                <h2 class="text-lg sm:text-xl font-black text-[#0F172A] tracking-widest uppercase">Manifes & Operasional Perjalanan</h2>
                                <p class="text-xs text-slate-500 font-mono mt-1">DOKUMEN NO: ANV-OPR-<?= str_pad($j['id_jadwal'], 4, '0', STR_PAD_LEFT) ?></p>
                            </div>
                            <div class="mt-4 sm:mt-0 text-left sm:text-right text-[11px] text-slate-500 font-mono space-y-1">
                                <p>Waktu Akses Data:</p>
                                <p class="font-bold text-[#0F172A]"><?= date('d F Y - H:i:s') ?> WIB</p>
                                <p class="pt-2 text-[10px] italic">Dicetak secara otomatis oleh sistem</p>
                            </div>
                        </div>

                        <div class="space-y-6 text-sm">
                            <!-- Bagian 1: Informasi Jadwal & Kereta -->
                            <div>
                                <h3 class="font-bold text-[#8C6239] uppercase tracking-wider text-xs mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-train-subway"></i> I. Informasi Kereta & Jadwal Rute
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 bg-slate-50/50 p-5 border border-slate-200 rounded-lg">
                                    <div>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Nama Kereta & Series</span>
                                        <span class="block font-bold text-[#0F172A] text-base mt-0.5"><?= htmlspecialchars($j['nama_kereta']) ?></span>
                                        <span class="text-xs font-semibold text-[#8C6239]"><?= htmlspecialchars($j['jenis_kelas']) ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Jenis & Harga Tiket</span>
                                        <span class="block font-bold text-[#0F172A] mt-0.5"><?= htmlspecialchars($j['jenis_jadwal']) ?> - Rp <?= number_format($j['harga'], 0, ',', '.') ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Stasiun Keberangkatan (Asal)</span>
                                        <span class="block font-semibold text-slate-700 mt-0.5"><?= htmlspecialchars($j['stasiun_asal']) ?></span>
                                        <span class="text-xs font-mono text-slate-500 font-bold">Pukul: <?= $j['jam_berangkat'] ?> WIB</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Stasiun Kedatangan (Tujuan)</span>
                                        <span class="block font-semibold text-slate-700 mt-0.5"><?= htmlspecialchars($j['stasiun_tujuan']) ?></span>
                                        <span class="text-xs font-mono text-slate-500 font-bold">Pukul: <?= $j['jam_tiba'] ?> WIB</span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Periode Operasional Tanggal</span>
                                        <span class="block font-semibold text-slate-700 mt-0.5"><?= date('d M Y', strtotime($j['tanggal_mulai'])) ?> s.d <?= !empty($j['tanggal_akhir']) ? date('d M Y', strtotime($j['tanggal_akhir'])) : 'Seterusnya' ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian 2: Data Keseluruhan Kru Bertugas ([NIK]/[NIP]) -->
                            <div>
                                <h3 class="font-bold text-[#8C6239] uppercase tracking-wider text-xs mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-user-shield"></i> II. Daftar Kru Bertugas (Sesuai Jadwal Penugasan)
                                </h3>
                                <div class="border border-slate-200 rounded-lg overflow-hidden">
                                    <table class="w-full text-left border-collapse text-xs">
                                        <thead>
                                            <tr class="bg-slate-100 text-slate-600 font-bold border-b border-slate-200">
                                                <th class="py-2.5 px-3">No.</th>
                                                <th class="py-2.5 px-3">Nama Lengkap</th>
                                                <th class="py-2.5 px-3">Posisi Kru</th>
                                                <th class="py-2.5 px-3">Identitas [NIK]/[NIP]</th>
                                                <th class="py-2.5 px-3">Kontak WA</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <?php if(empty($kruList)): ?>
                                                <tr>
                                                    <td colspan="5" class="py-4 text-center text-slate-400 italic">Belum ada kru yang ditugaskan pada jadwal ini.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $kNo = 1; foreach($kruList as$kru): ?>
                                                    <tr>
                                                        <td class="py-2.5 px-3 font-bold text-slate-500"><?= $kNo++ ?></td>
                                                        <td class="py-2.5 px-3 font-bold text-[#0F172A]"><?= htmlspecialchars($kru['nama_lengkap']) ?></td>
                                                        <td class="py-2.5 px-3"><span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-bold"><?= htmlspecialchars($kru['posisi']) ?></span></td>
                                                        <td class="py-2.5 px-3 font-mono text-slate-600">[<?= htmlspecialchars($kru['nik']) ?>]/[<?= htmlspecialchars($kru['nip']) ?>]</td>
                                                        <td class="py-2.5 px-3 font-mono text-slate-600"><?= htmlspecialchars($kru['no_telepon']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Bagian 3: Manifes Penumpang -->
                            <div>
                                <h3 class="font-bold text-[#8C6239] uppercase tracking-wider text-xs mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-users"></i> III. Manifes Penumpang Terdaftar
                                </h3>
                                <div class="border border-slate-200 rounded-lg overflow-hidden max-h-60 overflow-y-auto custom-scrollbar">
                                    <table class="w-full text-left border-collapse text-xs">
                                        <thead>
                                            <tr class="bg-slate-100 text-slate-600 font-bold border-b border-slate-200 sticky top-0">
                                                <th class="py-2.5 px-3">No.</th>
                                                <th class="py-2.5 px-3">Nama Penumpang</th>
                                                <th class="py-2.5 px-3">NIK (KTP)</th>
                                                <th class="py-2.5 px-3">Nomor Kursi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <?php if(empty($penumpangList)): ?>
                                                <tr>
                                                    <td colspan="4" class="py-4 text-center text-slate-400 italic">Belum ada data penumpang terdaftar pada jadwal ini.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $pNo = 1; foreach($penumpangList as$p): ?>
                                                    <tr>
                                                        <td class="py-2.5 px-3 font-bold text-slate-500"><?= $pNo++ ?></td>
                                                        <td class="py-2.5 px-3 font-bold text-[#0F172A]"><?= htmlspecialchars($p['nama']) ?></td>
                                                        <td class="py-2.5 px-3 font-mono text-slate-600"><?= htmlspecialchars($p['nik']) ?></td>
                                                        <td class="py-2.5 px-3 font-bold text-purple-600"><?= htmlspecialchars($p['nomor_kursi']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Validasi Cap Digital & Tombol Aksi -->
                            <div class="mt-8 pt-8 border-t border-slate-200 flex justify-between items-end">
                                <!-- Tombol Aksi (Hanya di layar, otomatis hilang saat diprint) -->
                                <div class="flex gap-2 no-print">
                                    <button onclick="closeModal('modal-detail-<?= $j['id_jadwal'] ?>')" class="px-5 py-2.5 bg-slate-100 hover:bg-[#0F172A] hover:text-white text-slate-600 rounded-lg font-bold text-xs transition-colors shadow-sm">
                                        Tutup
                                    </button>
                                    <button onclick="cetakDokumenJadwal(<?= $j['id_jadwal'] ?>)" class="px-5 py-2.5 bg-[#8C6239] hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] text-white rounded-lg font-bold text-xs transition-colors shadow-sm flex items-center gap-2">
                                        <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
                                    </button>
                                </div>
                                
                                <!-- Cap Digital Resmi -->
                                <div class="text-center relative mr-2">
                                    <span class="block text-[9px] text-slate-400 mb-6 uppercase tracking-widest">Divalidasi Oleh</span>
                                    <span class="block text-[10px] font-extrabold text-[#0F172A] mt-2 border-b-2 border-slate-800 pb-1 relative z-10">PT KERETA CEPAT ANVO</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- SCRIPT INISIALISASI DATATABLES & PRINT PDF -->
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
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data operasional jadwal kereta yang ditemukan.",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Data kosong",
                    "search": "Cari Cepat:",
                    "paginate": { "first": "Awal", "last": "Akhir", "next": "Lanjut", "previous": "Kembali" }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [7] } 
                ]
            });
        });

        // Fungsi Cetak / Simpan PDF Dokumen A4 Jadwal Operasional
        function cetakDokumenJadwal(id_jadwal) {
            const konten = document.getElementById('dokumen-a4-' + id_jadwal).innerHTML;
            
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
                        <title>Dokumen Manifes & Operasional - ID ${id_jadwal}</title>
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

<?php 
require_once __DIR__ . '/../layouts/admin/footer.php'; 
?>