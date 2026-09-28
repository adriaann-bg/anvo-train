<div class="max-w-2xl mx-auto py-12 px-6 space-y-6">
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden text-left relative">
        <div class="bg-[#0F172A] text-white p-8 flex justify-between items-center">
            <div>
                <span class="text-[10px] text-amber-400 font-bold tracking-widest uppercase block">E-Ticket Kereta Cepat</span>
                <h2 class="text-xl font-black mt-1"><?= htmlspecialchars($data['reservasi']['nama_kereta']) ?></h2>
            </div>
            <div class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-xs font-bold border border-emerald-500/30">LUNAS</div>
        </div>

        <div class="p-8 space-y-6">
            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl">
                <div>
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Keberangkatan</span>
                    <span class="text-sm font-extrabold text-[#0F172A]"><?= htmlspecialchars($data['reservasi']['nama_asal']) ?></span>
                    <span class="text-xs font-mono text-slate-600 block"><?= $data['reservasi']['tanggal_keberangkatan'] ?> &bull; <?= $data['reservasi']['jam_berangkat'] ?></span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Tujuan & Batas Berlaku</span>
                    <span class="text-sm font-extrabold text-[#0F172A]"><?= htmlspecialchars($data['reservasi']['nama_tujuan']) ?></span>
                    <span class="text-xs font-mono text-rose-600 font-bold block">Pukul: <?= $data['reservasi']['jam_tiba'] ?></span>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-3">Daftar Penumpang & Kursi</h4>
                <div class="space-y-2">
                    <?php foreach($data['reservasi']['penumpang_list'] as $p): ?>
                        <div class="flex justify-between items-center p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                            <div>
                                <span class="font-bold text-[#0F172A] block"><?= htmlspecialchars($p['nama']) ?></span>
                                <span class="text-[10px] text-slate-400 font-mono">NIK: <?= htmlspecialchars($p['nik']) ?></span>
                            </div>
                            <span class="font-extrabold text-[#8C6239] bg-white px-3 py-1.5 rounded-lg border border-slate-200"><?= htmlspecialchars($p['nomor_kursi']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="pt-4 border-t border-dashed border-slate-200 flex justify-between items-center">
                <div class="font-mono text-[10px] text-slate-400">
                    BARCODE-ANV-<?= str_pad($data['reservasi']['id_reservasi'], 6, '0', STR_PAD_LEFT) ?>
                </div>
                <a href="/anvo/public/booking/tiket_saya" class="px-5 py-2.5 bg-[#8C6239] text-white text-xs font-bold rounded-xl shadow-md">Lihat Tiket Saya</a>
            </div>
        </div>
    </div>
</div>