<?php 
$data['active_menu'] = 'jadwal';
require_once __DIR__ . '/../layouts/admin/header.php'; 
require_once __DIR__ . '/../layouts/admin/sidebar.php'; 

$jdwl =$data['jadwal_edit'];
$transitRaw = json_decode($jdwl['stasiun_transit'], true) ?? [];

$transitMap = [];
foreach($transitRaw as$item) {
    if(is_array($item)) {$transitMap[$item['nama']] =$item['waktu'] ?? '';
    } else {
        $transitMap[$item] = '';
    }
}
?>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex justify-between items-center sticky top-0 z-25 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="/anvo/public/jadwal" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">Edit Jadwal #<?= $jdwl['id_jadwal'] ?></h1>
                    <p class="text-xs text-slate-400 font-medium">Perbarui informasi jadwal, armada, dan jam singgah stasiun.</p>
                </div>
            </div>
        </header>

        <main class="flex-1 p-8 lg:p-10 overflow-y-auto">
            <form id="form-jadwal-edit" action="/anvo/public/jadwal/update" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <input type="hidden" name="id_jadwal" value="<?= $jdwl['id_jadwal'] ?>">
                
                <input type="hidden" name="stasiun_transit" id="input-transit-json-edit">
                
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm space-y-4">
                        <h2 class="font-bold text-[#0F172A] border-b border-slate-100 pb-3">Informasi Dasar</h2>
                        
                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Jenis Jadwal</label>
                            <select name="jenis_jadwal" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                                <option value="Harian" <?= ($jdwl['jenis_jadwal'] == 'Harian') ? 'selected' : '' ?>>Harian (Reguler)</option>
                                <option value="Khusus" <?= ($jdwl['jenis_jadwal'] == 'Khusus') ? 'selected' : '' ?>>Khusus (Event/Ekstra)</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Koridor Jalur</label>
                            <select id="select-koridor-edit" name="id_koridor" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                                <option value="">-- Pilih Koridor --</option>
                                <?php foreach($data['koridor_list'] ?? [] as$kor): ?>
                                    <option value="<?= $kor['id_koridor'] ?>" <?= ($jdwl['id_koridor'] ==$kor['id_koridor']) ? 'selected' : '' ?>>
                                        <?= $kor['nama_koridor'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Pilih Armada Kereta</label>
                            <select id="select-armada-edit" name="id_kereta" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm disabled:opacity-50 focus:outline-none focus:border-[#8C6239]">
                                <option value="<?= $jdwl['id_kereta'] ?>" data-status="Aktif" selected><?= $jdwl['nama_kereta'] ?> (Terpilih)</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm space-y-4">
                        <h2 class="font-bold text-[#0F172A] border-b border-slate-100 pb-3">Waktu Utama & Harga</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Jam Berangkat</label>
                                <input type="time" id="main-jam-berangkat-edit" name="jam_berangkat" value="<?= date('H:i', strtotime($jdwl['jam_berangkat'])) ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Jam Tiba</label>
                                <input type="time" id="main-jam-tiba-edit" name="jam_tiba" value="<?= date('H:i', strtotime($jdwl['jam_tiba'])) ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="<?= $jdwl['tanggal_mulai'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 block mb-1.5">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" value="<?= $jdwl['tanggal_akhir'] ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 text-sm font-medium focus:outline-none focus:border-[#8C6239]">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 block mb-1.5">Harga Tiket (Rp)</label>
                            <input type="number" name="harga" value="<?= floor($jdwl['harga']) ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm">
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-sm min-h-[400px]">
                        <div class="flex justify-between items-end mb-8">
                            <div>
                                <h2 class="text-xl font-extrabold text-[#0F172A]">Konfigurasi Stasiun & Jam Singgah</h2>
                                <p class="text-xs text-slate-400 mt-1">Sesuaikan kembali jam kedatangan dan keberangkatan tiap stasiun.</p>
                            </div>
                            
                            <button type="button" id="btn-reverse-route" class="inline-flex px-4 py-2.5 bg-sky-50 text-[#2B9BFB] hover:bg-[#2B9BFB] hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm items-center gap-2" title="Tukar Keberangkatan dan Tujuan">
                                <i class="fa-solid fa-arrow-right-arrow-left"></i> Putar Arah (Z - A)
                            </button>
                        </div>

                        <div id="transit-map-container">
                            <div class="w-full overflow-x-auto custom-scrollbar pb-12 pt-4">
                                <div id="transit-line" class="flex items-center min-w-max px-4"></div>
                            </div>
                            
                            <input type="hidden" name="stasiun_asal" id="input-asal-edit">
                            <input type="hidden" name="stasiun_tujuan" id="input-tujuan-edit">
                        </div>

                        <div id="empty-state" class="hidden py-20 text-center text-slate-400 border-2 border-dashed border-slate-100 rounded-3xl mt-4">
                            <i class="fa-solid fa-map-location-dot text-4xl mb-3 text-slate-300 block"></i>
                            Pilih Koridor Jalur terlebih dahulu untuk memuat peta stasiun.
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="/anvo/public/jadwal" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-semibold text-sm transition-all">Batal Edit</a>
                        <button type="submit" class="px-8 py-3.5 bg-[#8C6239] hover:bg-[#74502e] text-white rounded-2xl font-semibold text-sm transition-all shadow-md">
                            Simpan Perubahan Jadwal
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        const stasiunAsalLama = "<?= $jdwl['stasiun_asal'] ?>";
        const transitMapLama = <?= json_encode($transitMap) ?>;
        let currentStations = []; 

        function renderTransitMap(data) {
            const transitLine = document.getElementById('transit-line');
            transitLine.innerHTML = '';
            if(data.length < 2) return;

            document.getElementById('input-asal-edit').value = data[0].nama_stasiun;
            document.getElementById('input-tujuan-edit').value = data[data.length-1].nama_stasiun;

            data.forEach((st, index) => {
                const isFirstOrLast = (index === 0 || index === data.length - 1);
                let isChecked = (transitMapLama.hasOwnProperty(st.nama_stasiun) || isFirstOrLast) ? 'checked' : '';
                let savedTime = transitMapLama[st.nama_stasiun] || '';

                const node = document.createElement('div');
                node.className = 'flex items-center transit-node-item';
                node.innerHTML = `
                    <div class="relative flex flex-col items-center group">
                        <label class="cursor-pointer flex flex-col items-center ${isFirstOrLast ? 'pointer-events-none' : ''}">
                            <input type="checkbox" value="${st.nama_stasiun}" ${isChecked} class="transit-checkbox peer hidden">
                            <div class="w-6 h-6 rounded-full border-[5px] bg-white transition-all duration-300 z-10 ${isFirstOrLast ? 'border-amber-500' : 'border-slate-300 peer-checked:border-[#2B9BFB] group-hover:scale-110'}"></div>
                            <span class="text-xs mt-2 font-bold ${isFirstOrLast ? 'text-amber-700' : 'text-slate-700'}">
                                ${st.nama_stasiun.split(' - ')[0]}
                            </span>
                        </label>
                        <div class="mt-2">
                            <input type="time" value="${savedTime}" class="transit-time w-28 bg-slate-50 border border-slate-200 rounded-xl px-2 py-1.5 text-xs font-semibold text-center focus:outline-none focus:border-[#2B9BFB]" required title="Jam Singgah">
                        </div>
                    </div>
                    ${index < data.length - 1 ? '<div class="w-20 sm:w-28 h-1.5 bg-slate-200 mx-2 mb-10"></div>' : ''}
                `;
                transitLine.appendChild(node);
            });

            document.getElementById('empty-state').classList.add('hidden');
            document.getElementById('transit-map-container').classList.remove('hidden');
        }

        window.addEventListener('DOMContentLoaded', () => {
            const idKoridorAwal = document.getElementById('select-koridor-edit').value;
            if(idKoridorAwal) {
                fetch('/anvo/public/jadwal/get_stasiun_ajax/' + idKoridorAwal)
                .then(r => r.json())
                .then(data => {
                    currentStations = data;
                    if (data.length > 1 && data[0].nama_stasiun !== stasiunAsalLama) {
                        currentStations.reverse();
                    }
                    renderTransitMap(currentStations);
                });
            }
        });

        document.getElementById('select-koridor-edit').addEventListener('change', function() {
            const idKoridor = this.value;
            const selectArmada = document.getElementById('select-armada-edit');

            if (!idKoridor) {
                selectArmada.innerHTML = '<option value="">-- Menunggu Koridor --</option>';
                selectArmada.disabled = true;
                document.getElementById('transit-map-container').classList.add('hidden');
                document.getElementById('empty-state').classList.remove('hidden');
                currentStations = [];
                return;
            }

            fetch('/anvo/public/jadwal/get_kereta_ajax/' + idKoridor)
                .then(r => r.json())
                .then(data => {
                    selectArmada.innerHTML = '<option value="">-- Pilih Armada Kereta --</option>';
                    data.forEach(k => {
                        const opt = document.createElement('option');
                        opt.value = k.id_kereta;
                        opt.setAttribute('data-status', k.status_operasional);
                        opt.textContent = `${k.nama_kereta} - Status: ${k.status_operasional}`;
                        selectArmada.appendChild(opt);
                    });
                    selectArmada.disabled = false;
                });

            fetch('/anvo/public/jadwal/get_stasiun_ajax/' + idKoridor)
                .then(r => r.json())
                .then(data => {
                    currentStations = data; 
                    renderTransitMap(currentStations); 
                });
        });

        document.getElementById('btn-reverse-route').addEventListener('click', function() {
            if (currentStations.length > 0) {
                currentStations.reverse(); 
                renderTransitMap(currentStations); 
                const icon = this.querySelector('i');
                icon.classList.add('fa-spin');
                setTimeout(() => icon.classList.remove('fa-spin'), 300);
            }
        });
        
        document.getElementById('form-jadwal-edit').addEventListener('submit', function(e) {
            const selectArmada = document.getElementById('select-armada-edit');
            const selectedOption = selectArmada.options[selectArmada.selectedIndex];
            
            if (selectedOption) {
                const status = selectedOption.getAttribute('data-status');
                if (status && status !== 'Aktif') {
                    e.preventDefault(); 
                    alert(`TIDAK DAPAT DISIMPAN!\n\nArmada kereta sedang dalam status: [ ${status} ].\nSilakan pilih armada yang berstatus Aktif.`);
                    return;
                }
            }

            const nodes = document.querySelectorAll('.transit-node-item');
            const transitArray = [];
            
            nodes.forEach(node => {
                const checkbox = node.querySelector('.transit-checkbox');
                const timeInput = node.querySelector('.transit-time');
                if (checkbox && checkbox.checked) {
                    transitArray.push({
                        nama: checkbox.value,
                        waktu: timeInput ? timeInput.value : ''
                    });
                }
            });

            document.getElementById('input-transit-json-edit').value = JSON.stringify(transitArray);
            if(transitArray.length > 0) {
                document.getElementById('main-jam-berangkat-edit').value = transitArray[0].waktu;
                document.getElementById('main-jam-tiba-edit').value = transitArray[transitArray.length - 1].waktu;
            }
        });
    </script>

<?php require_once __DIR__ . '/../layouts/admin/footer.php'; ?>