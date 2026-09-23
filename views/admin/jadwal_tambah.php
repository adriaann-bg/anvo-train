<?php 
$data['active_menu'] = 'jadwal';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 
?>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="/anvo/public/jadwal" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Buat Jadwal Operasional</h1>
                    <p class="text-xs text-slate-400 font-medium">Konfigurasi jadwal, armada, dan titik transit stasiun.</p>
                </div>
            </div>
        </header>

        <main class="flex-1 p-8 lg:p-10 overflow-y-auto">
            <form id="form-jadwal" action="/anvo/public/jadwal/tambah" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- KOLOM KIRI: Form Dasar -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm space-y-4">
                        <h2 class="font-bold text-[#0F172A] border-b border-slate-100 pb-3">Informasi Dasar</h2>
                        
                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Jenis Jadwal</label>
                            <select name="jenis_jadwal" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                                <option value="Harian">Harian (Reguler)</option>
                                <option value="Khusus">Khusus (Event/Ekstra)</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Koridor Jalur</label>
                            <select id="select-koridor" name="id_koridor" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                                <option value="">-- Pilih Koridor --</option>
                                <?php foreach($data['koridor_list'] ?? [] as$kor): ?>
                                    <option value="<?= $kor['id_koridor'] ?>"><?= $kor['nama_koridor'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Armada Kereta</label>
                            <select id="select-armada" name="id_kereta" required disabled class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm disabled:opacity-50 focus:outline-none focus:border-[#8C6239]">
                                <option value="">-- Menunggu Koridor --</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm space-y-4">
                        <h2 class="font-bold text-[#0F172A] border-b border-slate-100 pb-3">Waktu & Harga</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Jam Berangkat</label>
                                <input type="time" name="jam_berangkat" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Jam Tiba</label>
                                <input type="time" name="jam_tiba" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm">
                            </div>
                        </div>
                        <!-- Ganti bagian input tanggal lama dengan ini -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Tanggal Mulai Operasi</label>
                                <input type="date" name="tanggal_mulai" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Tanggal Akhir Operasi</label>
                                <input type="date" name="tanggal_akhir" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Harga Tiket (Rp)</label>
                            <input type="number" name="harga" required placeholder="Contoh: 250000" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm">
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: UI Peta Transit Interaktif -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm min-h-[400px]">
                        <div class="flex justify-between items-end mb-8">
                            <div>
                                <h2 class="text-xl font-extrabold text-[#0F172A]">Konfigurasi Stasiun Transit</h2>
                                <p class="text-xs text-slate-400 mt-1">Klik titik stasiun untuk menonaktifkan pemberhentian (kereta akan lewat langsung).</p>
                            </div>
                        </div>

                        <!-- Area Peta Interaktif -->
                        <div id="transit-map-container" class="hidden">
                            <div class="w-full overflow-x-auto custom-scrollbar pb-6">
                                <div id="transit-line" class="flex items-center min-w-max px-4 pt-4">
                                    <!-- Stasiun akan di-render via JavaScript -->
                                </div>
                            </div>
                            
                            <!-- Stasiun Asal & Tujuan Utama (Auto Set) -->
                            <div class="mt-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex justify-between items-center text-sm">
                                <div>
                                    <span class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Stasiun Keberangkatan Awal</span>
                                    <span id="label-asal" class="font-bold text-[#0F172A]">-</span>
                                    <input type="hidden" name="stasiun_asal" id="input-asal">
                                </div>
                                <i class="fa-solid fa-arrow-right text-emerald-300"></i>
                                <div class="text-right">
                                    <span class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Stasiun Tujuan Akhir</span>
                                    <span id="label-tujuan" class="font-bold text-[#0F172A]">-</span>
                                    <input type="hidden" name="stasiun_tujuan" id="input-tujuan">
                                </div>
                            </div>
                        </div>
                        
                        <!-- State Kosong -->
                        <div id="empty-state" class="py-20 text-center text-slate-400 border-2 border-dashed border-slate-100 rounded-3xl mt-4">
                            <i class="fa-solid fa-map-location-dot text-4xl mb-3 text-slate-300 block"></i>
                            Pilih Koridor Jalur terlebih dahulu untuk memuat peta stasiun.
                        </div>

                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="/anvo/public/jadwal" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-semibold text-sm transition-all">Batal</a>
                        <button type="submit" class="px-8 py-3.5 bg-[#0F172A] hover:bg-[#8C6239] text-white rounded-2xl font-semibold text-sm transition-all shadow-md">
                            Simpan Jadwal Operasional
                        </button>
                    </div>
                </div>

            </form>
        </main>
    </div>

    <script>
        document.getElementById('select-koridor').addEventListener('change', function() {
            const idKoridor = this.value;
            const selectArmada = document.getElementById('select-armada');
            const transitContainer = document.getElementById('transit-map-container');
            const emptyState = document.getElementById('empty-state');
            const transitLine = document.getElementById('transit-line');

            if (!idKoridor) {
                selectArmada.innerHTML = '<option value="">-- Menunggu Koridor --</option>';
                selectArmada.disabled = true;
                transitContainer.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            // 1. Fetch Armada (Logika Validasi Status)
            selectArmada.innerHTML = '<option value="">Memuat armada...</option>';
            fetch('/anvo/public/jadwal/get_kereta_ajax/' + idKoridor)
                .then(r => r.json())
                .then(data => {
                    selectArmada.innerHTML = '<option value="">-- Pilih Armada Kereta --</option>';
                    if(data.length === 0) selectArmada.innerHTML = '<option value="">-- Tidak ada armada terdaftar --</option>';
                    data.forEach(k => {
                        const opt = document.createElement('option');
                        opt.value = k.id_kereta;
                        opt.setAttribute('data-status', k.status_operasional);
                        opt.textContent = `${k.nama_kereta} - Status: ${k.status_operasional}`;
                        selectArmada.appendChild(opt);
                    });
                    selectArmada.disabled = false;
                });

            // 2. Fetch Stasiun & Render Interactive Map
            fetch('/anvo/public/jadwal/get_stasiun_ajax/' + idKoridor)
                .then(r => r.json())
                .then(data => {
                    transitLine.innerHTML = '';
                    if(data.length < 2) return;

                    // Set Stasiun Awal & Akhir Otomatis
                    document.getElementById('label-asal').innerText = data[0].nama_stasiun;
                    document.getElementById('input-asal').value = data[0].nama_stasiun;
                    document.getElementById('label-tujuan').innerText = data[data.length-1].nama_stasiun;
                    document.getElementById('input-tujuan').value = data[data.length-1].nama_stasiun;

                    // Render UI Peta Interaktif
                    data.forEach((st, index) => {
                        const isFirstOrLast = (index === 0 || index === data.length - 1);
                        
                        const node = document.createElement('div');
                        node.className = 'flex items-center';
                        node.innerHTML = `
                            <label class="relative flex flex-col items-center cursor-pointer group ${isFirstOrLast ? 'pointer-events-none' : ''}">
                                <input type="checkbox" name="stasiun_transit[]" value="${st.nama_stasiun}" checked class="peer hidden">
                                
                                <!-- Lingkaran Node -->
                                <div class="w-6 h-6 rounded-full border-[5px] bg-white transition-all duration-300 z-10
                                    ${isFirstOrLast ? 'border-emerald-500' : 'border-slate-300 peer-checked:border-[#2B9BFB] group-hover:scale-110'}">
                                </div>
                                
                                <!-- Tooltip / Label Nama Stasiun -->
                                <div class="absolute top-8 w-max text-center">
                                    <span class="text-xs transition-colors duration-300 
                                        ${isFirstOrLast ? 'font-bold text-emerald-600' : 'text-slate-400 peer-checked:text-[#0F172A] peer-checked:font-bold'}">
                                        ${st.nama_stasiun.split(' - ')[0]}
                                    </span>
                                    ${!isFirstOrLast ? '<span class="text-[9px] block text-slate-400 peer-checked:text-[#2B9BFB] mt-0.5 peer-checked:opacity-100 opacity-0 transition-opacity">Berhenti</span>' : ''}
                                </div>
                            </label>
                            ${index < data.length - 1 ? '<div class="w-16 sm:w-24 h-1.5 bg-slate-200 transition-colors"></div>' : ''}
                        `;

                        // Efek mengubah warna garis jika node di-klik
                        if(!isFirstOrLast) {
                            const checkbox = node.querySelector('input');
                            const lineBefore = node.previousElementSibling ? node.previousElementSibling.querySelector('.h-1\\.5') : null;
                            const lineAfter = node.querySelector('.h-1\\.5');
                            
                            // Set warna default garis biru
                            if(lineBefore) lineBefore.classList.add('bg-[#2B9BFB]/30');
                            if(lineAfter) lineAfter.classList.add('bg-[#2B9BFB]/30');

                            checkbox.addEventListener('change', function() {
                                if(this.checked) {
                                    node.querySelector('.w-6').classList.replace('border-slate-300', 'border-[#2B9BFB]');
                                } else {
                                    node.querySelector('.w-6').classList.replace('border-[#2B9BFB]', 'border-slate-300');
                                }
                            });
                        }
                        
                        transitLine.appendChild(node);
                    });

                    emptyState.classList.add('hidden');
                    transitContainer.classList.remove('hidden');
                });
        });

        // Validasi Pencegahan Simpan Jika Kereta Tidak Aktif
        document.getElementById('form-jadwal').addEventListener('submit', function(e) {
            const selectArmada = document.getElementById('select-armada');
            const selectedOption = selectArmada.options[selectArmada.selectedIndex];
            
            if (selectedOption) {
                const status = selectedOption.getAttribute('data-status');
                if (status && status !== 'Aktif') {
                    e.preventDefault(); 
                    alert(`TIDAK DAPAT DISIMPAN!\n\nArmada kereta sedang dalam status: [ ${status} ].\nSilakan pilih armada yang berstatus Aktif.`);
                }
            }
        });
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>