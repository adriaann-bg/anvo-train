<!-- Styling khusus denah kursi (Diadopsi dari Admin Blueprint) -->
<style>
    .seat-map-container { display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 1rem; }
    .car-container { min-width: 260px; border: 2px solid #e2e8f0; border-radius: 1.5rem; background: #f8fafc; padding: 1rem; display: flex; flex-direction: column; gap: 0.5rem; }
    .car-header { text-align: center; font-weight: 900; font-size: 11px; color: #64748b; border-bottom: 2px dashed #cbd5e1; padding-bottom: 0.5rem; mb: 0.5rem; }
    .grid-exec { display: grid; grid-template-columns: 1fr 1fr 0.5fr 1fr 1fr; gap: 6px; }
    .grid-caps { display: grid; grid-template-columns: 1.2fr 0.8fr 1.2fr; gap: 8px; }
    .seat-btn { border-radius: 6px; height: 28px; width: 100%; display: flex; justify-content: center; align-items: center; font-size: 9px; font-weight: bold; cursor: pointer; transition: all 0.2s; }
    .seat-available { background: #e2e8f0; color: #475569; border: 1px solid #cbd5e1; }
    .seat-available:hover { background: #8C6239; color: white; border-color: #8C6239; }
    .seat-occupied { background: #f1f5f9; color: #cbd5e1; cursor: not-allowed; text-decoration: line-through; }
    .seat-selected { background: #2B9BFB; color: white; border: 1px solid #1e3a8a; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .aisle-space { background: transparent; }
</style>

<div class="max-w-4xl mx-auto py-10 px-6 space-y-6">
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6">
        <div>
            <h1 class="text-xl font-extrabold text-[#0F172A]">Pilih Kursi Penumpang</h1>
            <p class="text-xs text-slate-400"><?= htmlspecialchars($data['jadwal']['nama_kereta']) ?> &bull; <?= date('d M Y', strtotime($_SESSION['booking_search']['tanggal'])) ?></p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i><span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="form-kursi" class="space-y-4">
            <?php foreach($data['penumpang'] as $idx =>$p): ?>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/60 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-extrabold text-[#0F172A] block"><?= htmlspecialchars($p['nama']) ?></span>
                        <span class="text-[11px] text-slate-400 font-mono">NIK: <?= htmlspecialchars($p['nik']) ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="badge-kursi-<?= $idx ?>" class="text-xs font-bold text-slate-400 bg-white px-3 py-1.5 rounded-xl border border-slate-200">Belum Dipilih</span>
                        <input type="hidden" name="kursi[<?= $idx ?>]" id="input-kursi-<?= $idx ?>" required>
                        <button type="button" onclick="bukaModalKursi(<?= $idx ?>)" class="px-3 py-1.5 bg-[#0F172A] hover:bg-[#8C6239] text-white text-[10px] font-bold rounded-lg transition-all">
                            Pilih Denah
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="w-full bg-[#8C6239] hover:bg-[#74502e] text-white py-3.5 rounded-xl font-bold text-xs shadow-md mt-4">
                Konfirmasi Pembayaran <i class="fa-solid fa-chevron-right ml-1"></i>
            </button>
        </form>
    </div>
</div>

<!-- MODAL DENAH KURSI -->
<div id="modal-denah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-5xl rounded-[2.5rem] p-8 shadow-2xl space-y-4 animate-fade-in flex flex-col max-h-[90vh]">
        <div class="flex justify-between items-center border-b border-slate-100 pb-4 shrink-0">
            <div>
                <h3 class="font-extrabold text-lg text-[#0F172A]">Denah Gerbong & Kursi</h3>
                <p class="text-xs text-[#8C6239] font-bold">Pilih kursi untuk Penumpang <span id="label-penumpang-aktif"></span></p>
            </div>
            <button onclick="tutupModalKursi()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="flex gap-4 text-[10px] font-bold text-slate-500 justify-center pb-2 shrink-0">
            <span class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#e2e8f0] border border-slate-300 rounded-sm"></div> Kosong</span>
            <span class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#2B9BFB] rounded-sm"></div> Dipilih</span>
            <span class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#f1f5f9] border border-slate-200 rounded-sm"></div> Terjual</span>
        </div>
        
        <div id="seat-map-render" class="seat-map-container flex-1 custom-scrollbar bg-slate-100/50 p-4 rounded-2xl border border-slate-200">
            <!-- Render via JS -->
        </div>
    </div>
</div>

<script>
    const layoutGerbong = <?= json_encode($data['layout_gerbong']) ?>;
    const occupiedSeats = <?= json_encode($data['occupied_seats']) ?>;
    let selectedSeats = {}; // Menyimpan kursi yang dipilih sementara { idPenumpang: 'G1-1A' }
    let activePenumpangIdx = null;

    const templateKelas = {
        'Executive Prime': { tipe: 'grid', cols: ['A', 'B', 'Lorong', 'C', 'D'] },
        'Luminary Capsule': { tipe: 'staggered', cols: ['A', 'Lorong', 'B'] },
        'VVIP Skybox Suite': { tipe: 'grid', cols: ['Kabin'] } // Disederhanakan untuk pemesanan
    };

    function renderSeatMap() {
        const container = document.getElementById('seat-map-render');
        container.innerHTML = '';

        layoutGerbong.forEach((g, gIndex) => {
            const numGerbong = gIndex + 1;
            const template = templateKelas[g.tipe_kelas];
            
            const carDiv = document.createElement('div');
            carDiv.className = 'car-container';
            carDiv.innerHTML = `<div class="car-header">GERBONG ${numGerbong} &bull; ${g.tipe_kelas}</div>`;

            const gridDiv = document.createElement('div');
            gridDiv.className = template.tipe === 'grid' ? 'grid-exec' : 'grid-caps';
            
            for(let b = 1; b <= g.baris; b++) {
                template.cols.forEach(colStr => {
                    const cell = document.createElement('div');
                    if(colStr === 'Lorong') {
                        cell.className = 'aisle-space';
                    } else {
                        const seatName = colStr === 'Kabin' ? `VVIP-${b}` : `${b}${colStr}`;
                        const fullId = `G${numGerbong}-${seatName}`;
                        
                        // Cek Status Kursi
                        let statusClass = 'seat-available';
                        let isClickable = true;

                        if (occupiedSeats.includes(fullId)) {
                            statusClass = 'seat-occupied';
                            isClickable = false;
                        } else if (Object.values(selectedSeats).includes(fullId)) {
                            // Jika kursi ini sudah dipilih (baik oleh penumpang aktif atau penumpang lain)
                            if (selectedSeats[activePenumpangIdx] === fullId) {
                                statusClass = 'seat-selected';
                            } else {
                                statusClass = 'seat-occupied'; // Terkunci oleh penumpang lain dalam 1 booking
                                isClickable = false;
                            }
                        }

                        cell.className = `seat-btn ${statusClass}`;
                        cell.innerText = seatName;

                        if (isClickable) {
                            cell.onclick = () => pilihKursi(fullId);
                        }
                    }
                    gridDiv.appendChild(cell);
                });
            }
            carDiv.appendChild(gridDiv);
            container.appendChild(carDiv);
        });
    }

    function bukaModalKursi(idx) {
        activePenumpangIdx = idx;
        document.getElementById('label-penumpang-aktif').innerText = (idx + 1);
        renderSeatMap();
        document.getElementById('modal-denah').classList.remove('hidden');
        document.getElementById('modal-denah').classList.add('flex');
    }

    function tutupModalKursi() {
        document.getElementById('modal-denah').classList.add('hidden');
        document.getElementById('modal-denah').classList.remove('flex');
    }

    function pilihKursi(seatId) {
        // Simpan ke memory
        selectedSeats[activePenumpangIdx] = seatId;
        // Update UI Badge
        const badge = document.getElementById(`badge-kursi-${activePenumpangIdx}`);
        badge.innerText = `Kursi: ${seatId}`;
        badge.className = 'text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1.5 rounded-xl border border-purple-100';
        // Update Hidden Input Form
        document.getElementById(`input-kursi-${activePenumpangIdx}`).value = seatId;
        tutupModalKursi();
    }

    // Alert Sebelum Pindah (Sama seperti Identitas)
    let isSubmitting = false;
    document.getElementById('form-kursi').addEventListener('submit', (e) => {
        isSubmitting = true;
    });
    window.addEventListener('beforeunload', function (e) {
        if (!isSubmitting) {
            e.preventDefault(); e.returnValue = '';
        }
    });
</script>