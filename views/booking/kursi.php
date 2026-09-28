<div class="max-w-4xl mx-auto py-10 px-6 space-y-6">
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6">
        <div>
            <h1 class="text-xl font-extrabold text-[#0F172A]">Pilih Kursi Kereta</h1>
            <p class="text-xs text-slate-400"><?= htmlspecialchars($data['jadwal']['nama_kereta']) ?> &bull; <?= date('d M Y', strtotime($_SESSION['booking_search']['tanggal'])) ?></p>
        </div>

        <!-- PENGAMAN: Tampilkan error jika gagal menyimpan reservasi -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <?php foreach($data['penumpang'] as $idx => $p): ?>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/60 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-extrabold text-[#0F172A] block"><?= htmlspecialchars($p['nama']) ?></span>
                        <span class="text-[11px] text-slate-400 font-mono">NIK: <?= htmlspecialchars($p['nik']) ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1.5 rounded-xl border border-purple-100">Kursi: <?= htmlspecialchars($p['kursi']) ?></span>
                        <input type="hidden" name="kursi[<?= $idx ?>]" value="<?= htmlspecialchars($p['kursi']) ?>">
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-800">
                <i class="fa-solid fa-circle-info mr-1"></i> Sistem telah memilihkan kursi kosong secara otomatis dan acak. Anda dapat melanjutkan langsung ke pembayaran.
            </div>

            <button type="submit" class="w-full bg-[#8C6239] hover:bg-[#74502e] text-white py-3.5 rounded-xl font-bold text-xs shadow-md transition-all">
                Konfirmasi Kursi & Lanjut Pembayaran <i class="fa-solid fa-chevron-right ml-1"></i>
            </button>
        </form>
    </div>
</div>