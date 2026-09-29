<div class="max-w-4xl mx-auto py-10 px-6 space-y-6">
    <!-- Header Informasi Pencarian -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="font-extrabold text-lg text-[#0F172A]">Jadwal Keberangkatan</h1>
            <p class="text-xs text-slate-500 mt-1">
                <span class="font-bold text-[#0F172A]"><?= htmlspecialchars($data['search']['asal']) ?></span> &rarr; <span class="font-bold text-[#0F172A]"><?= htmlspecialchars($data['search']['tujuan']) ?></span>
            </p>
            <p class="text-xs text-slate-400 mt-0.5">
                Berangkat: <span class="font-semibold text-[#8C6239]"><?= date('d M Y', strtotime($data['search']['tanggal'])) ?></span>
                <?php if(!empty($data['search']['tanggal_pulang'])): ?>
                    | Pulang: <span class="font-semibold text-[#8C6239]"><?= date('d M Y', strtotime($data['search']['tanggal_pulang'])) ?></span>
                <?php endif; ?>
                | Penumpang: <?= $data['search']['penumpang'] ?> Orang
            </p>
        </div>
        <a href="/anvo/public/booking" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">Ubah Pencarian</a>
    </div>

    <!-- Tambahkan kode ini di bawah div Header Informasi Pencarian di jadwal.php -->
    <?php $kls_aktif = $_GET['kelas'] ?? ''; ?>
    <div class="flex gap-3 overflow-x-auto custom-scrollbar pb-2 pt-2 border-b border-slate-100">
        <a href="/anvo/public/booking/jadwal?asal=<?= urlencode($data['search']['asal']) ?>&tujuan=<?= urlencode($data['search']['tujuan']) ?>&tanggal=<?= urlencode($data['search']['tanggal']) ?>&penumpang=<?= $data['search']['penumpang'] ?>&kelas=" 
           class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all <?= empty($kls_aktif) ? 'bg-[#0F172A] text-white shadow-md' : 'bg-white border border-slate-200 text-slate-500 hover:border-[#8C6239] hover:text-[#8C6239]' ?>">
           Semua Kelas
        </a>
        <a href="/anvo/public/booking/jadwal?asal=<?= urlencode($data['search']['asal']) ?>&tujuan=<?= urlencode($data['search']['tujuan']) ?>&tanggal=<?= urlencode($data['search']['tanggal']) ?>&penumpang=<?= $data['search']['penumpang'] ?>&kelas=Executive+Prime" 
           class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all <?= $kls_aktif == 'Executive Prime' ? 'bg-[#0F172A] text-white shadow-md' : 'bg-white border border-slate-200 text-slate-500 hover:border-[#8C6239] hover:text-[#8C6239]' ?>">
           Executive Prime
        </a>
        <a href="/anvo/public/booking/jadwal?asal=<?= urlencode($data['search']['asal']) ?>&tujuan=<?= urlencode($data['search']['tujuan']) ?>&tanggal=<?= urlencode($data['search']['tanggal']) ?>&penumpang=<?= $data['search']['penumpang'] ?>&kelas=Luminary+Capsule" 
           class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all <?= $kls_aktif == 'Luminary Capsule' ? 'bg-[#0F172A] text-white shadow-md' : 'bg-white border border-slate-200 text-slate-500 hover:border-[#8C6239] hover:text-[#8C6239]' ?>">
           Luminary Capsule
        </a>
        <a href="/anvo/public/booking/jadwal?asal=<?= urlencode($data['search']['asal']) ?>&tujuan=<?= urlencode($data['search']['tujuan']) ?>&tanggal=<?= urlencode($data['search']['tanggal']) ?>&penumpang=<?= $data['search']['penumpang'] ?>&kelas=VVIP+Skybox" 
           class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all <?= $kls_aktif == 'VVIP Skybox' ? 'bg-[#0F172A] text-white shadow-md' : 'bg-white border border-slate-200 text-slate-500 hover:border-[#8C6239] hover:text-[#8C6239]' ?>">
           VVIP Skybox
        </a>
        <!-- Tambahkan hal yang sama untuk Luminary Capsule dan VVIP Skybox -->
    </div>

    <!-- Daftar Jadwal Kereta -->
    <div class="space-y-4">
        <?php if(empty($data['jadwal_list'])): ?>
            <div class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
                Tidak ada jadwal kereta yang sesuai pada tanggal tersebut.
            </div>
        <?php else: ?>
            <?php foreach($data['jadwal_list'] as $j): 
                // Kalkulasi Durasi Riil
                $start = strtotime($j['jam_berangkat']);
                $end = strtotime($j['jam_tiba']);
                $diff = $end - $start;
                if ($diff < 0) { $diff += 24 * 3600; } // Antisipasi lewat tengah malam
                $jam = floor($diff / 3600);
                $menit = floor(($diff % 3600) / 60);
                $durasiText = "{$jam} jam {$menit} mnt";

                // Hitung sisa kursi (Kapasitas total dikurangi kursi yang sudah dibooking pada tanggal tersebut)
                $db = Database::getInstance()->getConnection();
                $stmtCount = $db->prepare("SELECT COUNT(*) as terpesan FROM penumpangs p JOIN reservasis r ON p.id_reservasi = r.id_reservasi WHERE r.id_jadwal = :id_jadwal AND r.tanggal_keberangkatan = :tanggal");
                $stmtCount->execute([':id_jadwal' => $j['id_jadwal'], ':tanggal' => $data['search']['tanggal']]);
                $terpesan = $stmtCount->fetch(PDO::FETCH_ASSOC)['terpesan'] ?? 0;
                $sisaKursi = max(0, $j['kapasitas_kursi'] - $terpesan);
            ?>
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4 hover:border-[#8C6239] transition-all">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-extrabold text-base text-[#0F172A]"><?= htmlspecialchars($j['nama_kereta']) ?></h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] bg-sky-50 text-[#2B9BFB] px-2 py-0.5 rounded font-bold"><?= htmlspecialchars($j['jenis_kelas']) ?></span>
                                <span class="text-[10px] font-semibold <?= $sisaKursi > 5 ? 'text-emerald-600 bg-emerald-50' : 'text-amber-600 bg-amber-50' ?> px-2 py-0.5 rounded">
                                    Sisa Kursi: <?= $sisaKursi ?>
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-black text-[#8C6239]">Rp <?= number_format($j['harga'], 0, ',', '.') ?></span>
                            <span class="text-[10px] text-slate-400 block font-semibold">Per Orang</span>
                        </div>
                    </div>

                    <!-- Waktu Berangkat, Durasi Riil, dan Tiba -->
                    <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl">
                        <div>
                            <span class="text-xs font-extrabold text-[#0F172A] block"><?= date('H:i', strtotime($j['jam_berangkat'])) ?></span>
                            <span class="text-[10px] text-slate-500 font-bold"><?= htmlspecialchars($j['stasiun_asal']) ?></span>
                        </div>
                        <div class="text-center">
                            <span class="text-[10px] text-slate-400 font-bold block"><?= $durasiText ?></span>
                            <i class="fa-solid fa-arrow-right-long text-[#8C6239] my-1"></i>
                            <span class="text-[9px] text-slate-400 block">Langsung</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-extrabold text-[#0F172A] block"><?= date('H:i', strtotime($j['jam_tiba'])) ?></span>
                            <span class="text-[10px] text-slate-500 font-bold"><?= htmlspecialchars($j['stasiun_tujuan']) ?></span>
                        </div>
                    </div>

                    <!-- Tombol Pilih -->
                    <div class="flex justify-end pt-2">
                        <a href="/anvo/public/booking/identitas/<?= $j['id_jadwal'] ?>" class="px-6 py-2.5 bg-[#8C6239] hover:bg-[#74502e] text-white text-xs font-bold rounded-xl shadow-md transition-all inline-flex items-center">
                            Pilih Sekarang <i class="fa-solid fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>