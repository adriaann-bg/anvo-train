<?php 
$data['active_menu'] = 'armada';
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
    
    .seat-map-container { display: flex; gap: 1rem; padding-bottom: 1rem; }
    .car-container { min-width: 260px; border: 2px solid #e2e8f0; border-radius: 1.5rem; background: #f8fafc; padding: 1rem; display: flex; flex-direction: column; gap: 0.5rem; position: relative; }
    .car-header { text-align: center; font-weight: 900; font-size: 11px; text-transform: uppercase; color: #64748b; margin-bottom: 0.5rem; border-bottom: 2px dashed #cbd5e1; padding-bottom: 0.5rem; }
    
    .grid-exec { display: grid; grid-template-columns: 1fr 1fr 0.5fr 1fr 1fr; gap: 4px; }
    .grid-caps { display: grid; grid-template-columns: 1.2fr 0.8fr 1.2fr; gap: 6px; }
    .grid-room { display: grid; grid-template-columns: 1fr; gap: 8px; }

    .seat-box { background: #0F172A; border-radius: 4px; height: 16px; width: 100%; display: flex; justify-content: center; align-items: center; color: white; font-size: 6px; font-weight: bold; }
    .seat-capsule { background: #8C6239; border-radius: 8px; height: 24px; width: 100%; display: flex; justify-content: center; align-items: center; color: white; font-size: 7px; font-weight: bold; }
    .seat-room { background: #2B9BFB; border-radius: 12px; height: 36px; width: 100%; display: flex; justify-content: center; align-items: center; color: white; font-size: 9px; font-weight: bold; }
    .aisle-space { background: transparent; height: 100%; width: 100%; }
</style>

    <div class="flex-1 flex flex-col min-w-0 relative">

        <div id="custom-toast" class="fixed bottom-8 right-8 z-50 transform translate-y-20 opacity-0 transition-all duration-300 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-semibold">
            <i id="toast-icon" class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
            <span id="toast-message">Notifikasi berhasil.</span>
        </div>

        <header class="h-20 bg-white border-b border-slate-200/60 px-6 sm:px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div>
                <h1 class="text-base sm:text-lg font-extrabold text-[#0F172A] tracking-tight">Manajemen Armada Kereta (Rolling Stock)</h1>
                <p class="text-xs text-slate-400 font-medium">Desain komposisi gerbong, blueprint layout kursi, dan spesifikasi armada.</p>
            </div>
            <div>
                <button onclick="bukaModalBuilder('tambah')" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-5 py-3 rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Armada Baru
                </button>
            </div>
        </header>

        <main class="flex-1 p-6 sm:p-10 space-y-8 overflow-y-auto">
            <?php if (isset($_SESSION['success'])): ?>
                <div id="flash-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm flex items-center justify-between">
                    <span class="text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i> <?= $_SESSION['success'] ?>
                    </span>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm space-y-6">
                <div>
                    <h2 class="text-base sm:text-lg font-extrabold text-[#0F172A] tracking-tight">Daftar Rangkaian Kereta Cepat</h2>
                    <p class="text-xs text-slate-400">Armada beserta matriks layout dan konfigurasi gerbong otomatis.</p>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table id="dataTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] uppercase text-slate-400 font-bold tracking-wider">
                                <th class="py-3.5 px-4">Seri Kereta</th>
                                <th class="py-3.5 px-4">Komposisi Kelas</th>
                                <th class="py-3.5 px-4">Spesifikasi</th>
                                <th class="py-3.5 px-4 text-center">Formasi</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-50">
                            <?php if(empty($data['armada_list'])): ?>
                                <tr><td colspan="6" class="py-12 text-center text-slate-400 font-medium text-xs">Belum ada armada kereta yang terdaftar.</td></tr>
                            <?php else: ?>
                                <?php foreach($data['armada_list'] as $arm): ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-4 font-bold text-[#0F172A]"><?= htmlspecialchars($arm['nama_kereta']) ?></td>
                                        <td class="py-4 px-4">
                                            <div class="flex flex-wrap gap-1 mb-1">
                                                <?php foreach($arm['jenis_kelas_arr'] ?? [] as $kls): ?>
                                                    <span class="text-[9px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded border border-sky-100 font-bold"><?= htmlspecialchars(trim($kls)) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-semibold text-slate-700 block text-[11px]"><?= htmlspecialchars($arm['jenis_mesin']) ?></span>
                                            <span class="text-[10px] text-[#8C6239] font-bold"><?= htmlspecialchars($arm['kecepatan_maksimal']) ?> km/h</span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="inline-flex flex-col items-center bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
                                                <span class="text-sm font-extrabold text-[#0F172A]"><?= htmlspecialchars($arm['kapasitas_kursi']) ?></span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase">Total Kursi</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <?php 
                                                $status = $arm['status_operasional'];
                                                $badgeColor = ($status == 'Aktif') ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500';
                                            ?>
                                            <span class="text-[10px] px-3 py-1 rounded-full font-bold <?= $badgeColor ?>"><?= htmlspecialchars($status) ?></span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button onclick="bukaModalDetail(<?= htmlspecialchars(json_encode($arm)) ?>)" class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-[#0F172A] hover:text-white rounded-xl text-xs font-semibold transition-all"><i class="fa-solid fa-eye"></i> Layout</button>
                                                <button onclick="bukaModalBuilder('edit', <?= htmlspecialchars(json_encode($arm)) ?>)" class="px-3 py-1.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-semibold transition-all"><i class="fa-solid fa-pen-to-square"></i></button>
                                                <a href="/anvo/public/admin/armada/hapus/<?= $arm['id_kereta'] ?>" onclick="return confirm('Hapus armada kereta beserta layoutnya?')" class="p-2.5 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-xs transition-all"><i class="fa-solid fa-trash-can"></i></a>
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

    <!-- MODAL BUILDER GERBONG & ARMADA -->
    <div id="modal-builder-armada" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 id="builder-title" class="font-extrabold text-lg text-[#0F172A]">Blueprint Formasi Armada</h3>
                    <p class="text-xs text-slate-400">Susun tata letak gerbong dan kapasitas otomatis.</p>
                </div>
                <button onclick="closeModal('modal-builder-armada')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form id="form-builder" action="/anvo/public/admin/armada/tambah" method="POST" class="space-y-5">
                <input type="hidden" name="id_kereta" id="builder-id">
                <input type="hidden" name="komposisi_gerbong" id="builder-komposisi-json">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <label class="text-[11px] font-bold text-slate-500 block mb-1.5">Nama Seri Armada</label>
                        <input type="text" name="nama_kereta" id="builder-nama" placeholder="Cth: G101 Antasena Evo" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-500 block mb-1.5">Jenis Mesin (Traksi)</label>
                        <input type="text" name="jenis_mesin" id="builder-mesin" value="EMU-Gen4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-500 block mb-1.5">Top Speed (km/h)</label>
                        <input type="number" name="kecepatan_maksimal" id="builder-kecepatan" value="350" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#8C6239]">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-500 block mb-1.5">Status Awal</label>
                        <select name="status_operasional" id="builder-status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#8C6239]">
                            <option value="Aktif">Aktif Operasional</option>
                            <option value="Standby">Standby (Pool)</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-3">
                        <div>
                            <h4 class="text-sm font-extrabold text-[#0F172A]">Formasi Gerbong (Carriages)</h4>
                            <p class="text-[10px] text-slate-400">Atur urutan gerbong dari depan (G1) ke belakang.</p>
                        </div>
                        <button type="button" onclick="tambahGerbongBuilder()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-[#0F172A] text-[10px] font-bold rounded-lg transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-plus"></i> Gerbong Baru
                        </button>
                    </div>

                    <div id="gerbong-container" class="space-y-2">
                    </div>

                    <div class="mt-4 p-4 bg-[#0F172A] rounded-xl flex justify-between items-center text-white">
                        <span class="text-xs font-medium text-slate-300">Total Kalkulasi Kapasitas Sistem:</span>
                        <div class="text-right">
                            <span id="label-total-kapasitas" class="text-2xl font-extrabold text-[#2B9BFB]">0</span>
                            <span class="text-xs font-bold text-slate-400 ml-1">Kursi</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#8C6239] hover:bg-[#74502e] text-white py-3.5 rounded-xl font-bold transition-all shadow-md mt-2">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Formasi Armada
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL & SEAT MAP PREVIEW -->
    <div id="modal-detail-armada" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-5xl rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in max-h-[95vh] flex flex-col">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 shrink-0">
                <div>
                    <h3 class="font-extrabold text-xl text-[#0F172A]">Visualisasi Layout & Denah Armada</h3>
                    <p id="map-nama-armada" class="text-sm font-bold text-[#8C6239]">-</p>
                </div>
                <button onclick="closeModal('modal-detail-armada')" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="flex-1 overflow-x-auto overflow-y-hidden custom-scrollbar bg-slate-100 p-6 rounded-3xl border border-slate-200/60 shadow-inner relative">
                <div class="absolute left-2 top-1/2 transform -translate-y-1/2 w-16 h-32 bg-slate-300 rounded-l-[3rem] opacity-50 flex items-center justify-center">
                    <i class="fa-solid fa-train text-slate-400 text-xl"></i>
                </div>

                <div id="seat-map-render" class="seat-map-container ml-16 mr-16 h-full flex items-center">
                </div>

                <div class="absolute right-2 top-1/2 transform -translate-y-1/2 w-16 h-32 bg-slate-300 rounded-r-[3rem] opacity-50 flex items-center justify-center">
                    <i class="fa-solid fa-train text-slate-400 text-xl"></i>
                </div>
            </div>

            <div class="shrink-0 flex justify-between items-center pt-2">
                <div class="flex gap-4">
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500"><div class="w-3 h-3 bg-[#0F172A] rounded-sm"></div> Executive Prime (2-2)</span>
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500"><div class="w-3 h-3 bg-[#8C6239] rounded-sm"></div> Luminary Capsule (1-1)</span>
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500"><div class="w-3 h-3 bg-[#2B9BFB] rounded-sm"></div> VVIP Skybox (Private)</span>
                </div>
                <button onclick="closeModal('modal-detail-armada')" class="px-6 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-2xl font-bold text-xs transition-all">Tutup Pratinjau</button>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => { const el = document.getElementById('flash-alert'); if(el) el.remove(); }, 3000);
        function showToast(message, isError = false) {
            const toast = document.getElementById('custom-toast');
            document.getElementById('toast-message').innerText = message;
            document.getElementById('toast-icon').className = isError ? 'fa-solid fa-triangle-exclamation text-rose-400 text-sm' : 'fa-solid fa-circle-check text-emerald-400 text-sm';
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 3500);
        }

        const templateKelas = {
            'Executive Prime': { formasi: '2-2', multiplier: 4, tipe: 'grid', cols: ['A', 'B', 'Lorong', 'C', 'D'] },
            'Luminary Capsule': { formasi: '1-1', multiplier: 2, tipe: 'staggered', cols: ['A', 'Lorong', 'B'] },
            'VVIP Skybox Suite': { formasi: 'Private', multiplier: 1, tipe: 'room', cols: ['Kabin'] }
        };

        let gerbongState = [];

        function bukaModalBuilder(mode, data = null) {
            gerbongState = []; 
            const form = document.getElementById('form-builder');
            const title = document.getElementById('builder-title');

            if (mode === 'tambah') {
                title.innerText = 'Buat Formasi Armada Baru';
                form.action = '/anvo/public/admin/armada/tambah';
                form.reset();
                document.getElementById('builder-id').value = '';
                tambahGerbongState('Executive Prime', 15);
            } else {
                title.innerText = 'Edit Formasi & Spesifikasi Armada';
                form.action = '/anvo/public/admin/armada/update';
                document.getElementById('builder-id').value = data.id_kereta;
                document.getElementById('builder-nama').value = data.nama_kereta;
                document.getElementById('builder-mesin').value = data.jenis_mesin;
                document.getElementById('builder-kecepatan').value = data.kecepatan_maksimal;
                document.getElementById('builder-status').value = data.status_operasional;

                let savedData = [];
                try {
                    savedData = typeof data.layout_kursi === 'string' ? JSON.parse(data.layout_kursi) : data.layout_kursi_arr;
                } catch(e) {}
                
                if(savedData && savedData.length > 0 && typeof savedData[0] === 'object') {
                    gerbongState = savedData;
                } else {
                    tambahGerbongState('Executive Prime', 15);
                }
            }
            renderBuilderUI();
            openModal('modal-builder-armada');
        }

        function tambahGerbongState(tipe_kelas, baris) {
            gerbongState.push({
                tipe_kelas: tipe_kelas,
                baris: baris,
                kapasitas: baris * templateKelas[tipe_kelas].multiplier
            });
        }

        function tambahGerbongBuilder() {
            if (gerbongState.length >= 12) {
                showToast('Maksimal 12 gerbong dalam satu rangkaian kereta.', true); return;
            }
            tambahGerbongState('Executive Prime', 15);
            renderBuilderUI();
        }

        function ubahKelasGerbong(index, selectEl) {
            const tipe = selectEl.value;
            let defaultBaris = 15;
            if(tipe === 'Luminary Capsule') defaultBaris = 8;
            if(tipe === 'VVIP Skybox Suite') defaultBaris = 3;

            gerbongState[index].tipe_kelas = tipe;
            gerbongState[index].baris = defaultBaris;
            gerbongState[index].kapasitas = defaultBaris * templateKelas[tipe].multiplier;
            renderBuilderUI();
        }

        function ubahBarisGerbong(index, inputEl) {
            let val = parseInt(inputEl.value) || 1;
            if(val > 25) val = 25; 
            const tipe = gerbongState[index].tipe_kelas;
            gerbongState[index].baris = val;
            gerbongState[index].kapasitas = val * templateKelas[tipe].multiplier;
            updateKapasitasLabel();
        }

        function hapusGerbong(index) {
            if (gerbongState.length <= 1) { showToast('Minimal 1 gerbong!', true); return; }
            gerbongState.splice(index, 1);
            renderBuilderUI();
        }

        function updateKapasitasLabel() {
            let total = gerbongState.reduce((sum, g) => sum + g.kapasitas, 0);
            document.getElementById('label-total-kapasitas').innerText = total;
            document.getElementById('builder-komposisi-json').value = JSON.stringify(gerbongState);
        }

        function renderBuilderUI() {
            const container = document.getElementById('gerbong-container');
            container.innerHTML = '';

            gerbongState.forEach((g, index) => {
                const num = index + 1;
                const template = templateKelas[g.tipe_kelas];
                
                const row = document.createElement('div');
                row.className = 'grid grid-cols-12 gap-3 p-3 bg-white border border-slate-200 rounded-xl items-center relative';
                row.innerHTML = `
                    <div class="col-span-2 sm:col-span-1 text-center">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 font-extrabold flex items-center justify-center text-xs border border-slate-200">G${num}</div>
                    </div>
                    <div class="col-span-10 sm:col-span-5">
                        <select onchange="ubahKelasGerbong(${index}, this)" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-xs font-bold text-[#0F172A] focus:outline-none focus:border-[#8C6239]">
                            <option value="Executive Prime" ${g.tipe_kelas === 'Executive Prime' ? 'selected' : ''}>Executive Prime (Grid 2-2)</option>
                            <option value="Luminary Capsule" ${g.tipe_kelas === 'Luminary Capsule' ? 'selected' : ''}>Luminary Capsule (Capsule 1-1)</option>
                            <option value="VVIP Skybox Suite" ${g.tipe_kelas === 'VVIP Skybox Suite' ? 'selected' : ''}>VVIP Skybox Suite (Private Room)</option>
                        </select>
                    </div>
                    <div class="col-span-5 sm:col-span-3">
                        <div class="flex items-center gap-2">
                            <input type="number" min="1" max="25" value="${g.baris}" oninput="ubahBarisGerbong(${index}, this)" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-center text-xs font-bold focus:outline-none focus:border-[#8C6239]">
                            <span class="text-[10px] font-bold text-slate-400 leading-tight">Baris<br>(x${template.multiplier} kursi)</span>
                        </div>
                    </div>
                    <div class="col-span-5 sm:col-span-2 text-right">
                        <span class="font-extrabold text-[#2B9BFB] text-sm">${g.kapasitas} <span class="text-[9px] text-slate-400">Seat</span></span>
                    </div>
                    <div class="col-span-2 sm:col-span-1 text-right">
                        <button type="button" onclick="hapusGerbong(${index})" class="text-rose-400 hover:text-rose-600 text-sm p-2"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                `;
                container.appendChild(row);
            });
            updateKapasitasLabel();
        }

        function bukaModalDetail(data) {
            document.getElementById('map-nama-armada').innerText = data.nama_kereta + ' (' + data.kapasitas_kursi + ' Kursi)';
            const container = document.getElementById('seat-map-render');
            container.innerHTML = '';

            let gerbongArr = [];
            try {
                gerbongArr = typeof data.layout_kursi === 'string' ? JSON.parse(data.layout_kursi) : data.layout_kursi_arr;
            } catch(e) {}

            if(!gerbongArr || gerbongArr.length === 0 || typeof gerbongArr[0] !== 'object') {
                container.innerHTML = `<span class="text-slate-400 font-bold text-sm mx-auto">Format data gerbong lama, tidak dapat dirender secara visual. Silakan Edit Formasi.</span>`;
                openModal('modal-detail-armada');
                return;
            }

            gerbongArr.forEach((g, index) => {
                const template = templateKelas[g.tipe_kelas];
                const carDiv = document.createElement('div');
                carDiv.className = 'car-container';

                const header = document.createElement('div');
                header.className = 'car-header';
                header.innerHTML = `Gerbong ${index + 1} &bull; ${g.tipe_kelas} &bull; ${g.kapasitas} Seat`;
                carDiv.appendChild(header);

                const gridDiv = document.createElement('div');
                if (template.tipe === 'grid') gridDiv.className = 'grid-exec';
                else if (template.tipe === 'staggered') gridDiv.className = 'grid-caps';
                else if (template.tipe === 'room') gridDiv.className = 'grid-room';
                
                for(let b = 1; b <= g.baris; b++) {
                    template.cols.forEach((colStr) => {
                        const cell = document.createElement('div');
                        if(colStr === 'Lorong') {
                            cell.className = 'aisle-space';
                        } else {
                            if(template.tipe === 'grid') {
                                cell.className = 'seat-box shadow-sm';
                                cell.innerText = `${b}${colStr}`;
                            } else if(template.tipe === 'staggered') {
                                cell.className = 'seat-capsule shadow-sm';
                                cell.innerText = `${b}${colStr}`;
                            } else if(template.tipe === 'room') {
                                cell.className = 'seat-room shadow-md';
                                cell.innerHTML = `<i class="fa-solid fa-couch mr-1"></i> VVIP-${b}`;
                            }
                        }
                        gridDiv.appendChild(cell);
                    });
                }
                
                carDiv.appendChild(gridDiv);
                container.appendChild(carDiv);
            });

            openModal('modal-detail-armada');
        }

        $(document).ready(function() {$('#dataTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_", "zeroRecords": "Tidak ada armada", "info": "Hal. _PAGE_ dari _PAGES_",
                    "infoEmpty": "Kosong", "search": "Cari Armada:", "paginate": { "next": ">", "previous": "<" }
                },
                "columnDefs": [ { "orderable": false, "targets": [3, 5] } ]
            });
        });
        
        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
        }
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        }
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>