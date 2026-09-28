<div class="max-w-4xl mx-auto py-10 px-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-extrabold text-[#0F172A]">Tiket & Histori Perjalanan Saya</h1>
        <div class="flex gap-2 bg-slate-100 p-1.5 rounded-2xl">
            <button onclick="switchTab('aktif')" id="btnTabAktif" class="px-4 py-2 bg-white text-[#0F172A] font-bold text-xs rounded-xl shadow-sm transition-all">Tiket Aktif</button>
            <button onclick="switchTab('riwayat')" id="btnTabRiwayat" class="px-4 py-2 text-slate-500 font-bold text-xs rounded-xl transition-all">Riwayat Tiket</button>
        </div>
    </div>

    <!-- TAB AKTIF -->
    <div id="tabAktif" class="space-y-4">
        <?php if(empty($data['active_tickets'])): ?>
            <div class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-xs">Tidak ada tiket yang sedang aktif saat ini.</div>
        <?php else: ?>
            <?php foreach($data['active_tickets'] as $t): ?>
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center">
                    <div>
                        <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full font-bold">Aktif Berlaku s.d <?= $t['jam_tiba'] ?></span>
                        <h3 class="font-extrabold text-base text-[#0F172A] mt-2"><?= htmlspecialchars($t['nama_kereta']) ?></h3>
                        <p class="text-xs text-slate-500"><?= htmlspecialchars($t['nama_asal']) ?> &rarr; <?= htmlspecialchars($t['nama_tujuan']) ?> | <?= $t['tanggal_keberangkatan'] ?></p>
                    </div>
                    <a href="/anvo/public/booking/sukses/<?= $t['id_reservasi'] ?>" class="px-4 py-2.5 bg-[#8C6239] text-white text-xs font-bold rounded-xl">Buka E-Ticket</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- TAB RIWAYAT -->
    <div id="tabRiwayat" class="space-y-4 hidden">
        <?php if(empty($data['history_tickets'])): ?>
            <div class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-xs">Belum ada riwayat perjalanan sebelumnya.</div>
        <?php else: ?>
            <?php foreach($data['history_tickets'] as $t): ?>
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center opacity-75">
                    <div>
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full font-bold">Selesai / Kedaluwarsa</span>
                        <h3 class="font-extrabold text-base text-[#0F172A] mt-2"><?= htmlspecialchars($t['nama_kereta']) ?></h3>
                        <p class="text-xs text-slate-500"><?= htmlspecialchars($t['nama_asal']) ?> &rarr; <?= htmlspecialchars($t['nama_tujuan']) ?> | <?= $t['tanggal_keberangkatan'] ?></p>
                    </div>
                    <a href="/anvo/public/booking/sukses/<?= $t['id_reservasi'] ?>" class="px-4 py-2.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Lihat Detail</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    function switchTab(tab) {
        const aktif = document.getElementById('tabAktif');
        const riwayat = document.getElementById('tabRiwayat');
        const btnAktif = document.getElementById('btnTabAktif');
        const btnRiwayat = document.getElementById('btnTabRiwayat');

        if(tab === 'aktif') {
            aktif.classList.remove('hidden'); riwayat.classList.add('hidden');
            btnAktif.className = "px-4 py-2 bg-white text-[#0F172A] font-bold text-xs rounded-xl shadow-sm transition-all";
            btnRiwayat.className = "px-4 py-2 text-slate-500 font-bold text-xs rounded-xl transition-all";
        } else {
            riwayat.classList.remove('hidden'); aktif.classList.add('hidden');
            btnRiwayat.className = "px-4 py-2 bg-white text-[#0F172A] font-bold text-xs rounded-xl shadow-sm transition-all";
            btnAktif.className = "px-4 py-2 text-slate-500 font-bold text-xs rounded-xl transition-all";
        }
    }
</script>