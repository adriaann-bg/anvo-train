<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-xl space-y-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A]">Cari & Pesan Tiket Kereta Cepat</h1>
            <p class="text-xs text-slate-400">Pilih rute perjalanan dan kelas armada impian Anda.</p>
        </div>

        <form action="/anvo/public/booking/jadwal" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1.5">Stasiun Asal</label>
                <select name="asal" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    <option value="">Pilih Stasiun Asal</option>
                    <?php foreach($data['stasiun_list'] as $s): ?>
                        <option value="<?= htmlspecialchars($s['nama_stasiun']) ?>"><?= htmlspecialchars($s['nama_stasiun']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1.5">Stasiun Tujuan</label>
                <select name="tujuan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    <option value="">Pilih Stasiun Tujuan</option>
                    <?php foreach($data['stasiun_list'] as $s): ?>
                        <option value="<?= htmlspecialchars($s['nama_stasiun']) ?>"><?= htmlspecialchars($s['nama_stasiun']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1.5">Tanggal Keberangkatan</label>
                <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1.5">Kelas Kereta</label>
                <select name="kelas" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
                    <option value="">Semua Kelas</option>
                    <option value="Executive Prime">Executive Prime</option>
                    <option value="Luminary Capsule">Luminary Capsule</option>
                    <option value="VVIP Skybox Suite">VVIP Skybox Suite</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs font-bold text-slate-600 block mb-1.5">Jumlah Penumpang</label>
                <input type="number" name="penumpang" min="1" max="6" value="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#8C6239]">
            </div>
            <div class="md:col-span-2 pt-2">
                <button type="submit" class="w-full bg-[#8C6239] hover:bg-[#74502e] text-white py-3.5 rounded-xl font-bold text-sm transition-all shadow-md">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari Jadwal Kereta
                </button>
            </div>
        </form>
    </div>
</div>