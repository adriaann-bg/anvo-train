<div class="max-w-3xl mx-auto py-10 px-6 space-y-6">
    <!-- Breadcrumb -->
    <div class="text-xs text-slate-400 font-medium flex items-center gap-2">
        <a href="/anvo/public/booking" class="hover:text-[#8C6239]">Beli Tiket</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <span class="text-[#0F172A] font-bold">Identitas Penumpang</span>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6 relative">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-extrabold text-[#0F172A]">Identitas Penumpang</h1>
            <button type="button" onclick="bukaModalTambahManual()" class="bg-[#8C6239] hover:bg-[#74502e] text-white px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                <i class="fa-solid fa-plus"></i> Tambah Penumpang
            </button>
        </div>

        <form action="" method="POST" id="form-identitas" class="space-y-4">
            <?php 
            $jumlahPenumpang =$data['search']['penumpang'] ?? 1;
            for($i = 0; $i < $jumlahPenumpang; $i++): 
                // Set default user login ke Penumpang 1
                $isDefault = ($i == 0);
                $defNama =$isDefault ? htmlspecialchars($data['current_user']['nama']) : '';$defNik = $isDefault ? htmlspecialchars($data['current_user']['nik']) : '';
            ?>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/60 flex justify-between items-center">
                    <div>
                        <span class="text-[10px] font-bold text-[#8C6239] uppercase tracking-wider block mb-1">Penumpang <?= $i + 1 ?> <?=$isDefault ? '(Pemesan)' : '' ?></span>
                        <h3 id="display-nama-<?= $i ?>" class="text-sm font-extrabold text-[#0F172A]"><?= $defNama ?: 'Pilih data penumpang' ?></h3>
                        <p id="display-nik-<?= $i ?>" class="text-xs text-slate-400 font-mono"><?= $defNik ? 'NIK: '.$defNik : '-' ?></p>
                    </div>
                    <input type="hidden" name="penumpang[<?= $i ?>][nama]" id="input-nama-<?= $i ?>" value="<?= $defNama ?>" required>
                    <input type="hidden" name="penumpang[<?= $i ?>][nik]" id="input-nik-<?= $i ?>" value="<?= $defNik ?>" required>
                    <button type="button" onclick="bukaModalPilih(<?= $i ?>)" class="px-4 py-2 bg-white border border-slate-200 hover:border-[#8C6239] text-slate-700 text-xs font-bold rounded-xl transition-all">
                        Ubah Penumpang <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                    </button>
                </div>
            <?php endfor; ?>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#8C6239] hover:bg-[#74502e] text-white py-4 rounded-2xl font-bold text-xs shadow-md">
                    Lanjut Pilih Kursi <i class="fa-solid fa-chevron-right ml-1"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PILIH / CARI PENUMPANG -->
<div id="modal-pilih-penumpang" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-[2rem] p-6 shadow-2xl space-y-5 animate-fade-in max-h-[85vh] flex flex-col">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="font-extrabold text-base text-[#0F172A]">Pilih Data Penumpang</h3>
            <button onclick="tutupModal('modal-pilih-penumpang')" class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Search Bar dalam Modal -->
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400 text-xs"></i>
            <input type="text" id="search-saved-passenger" onkeyup="filterSavedPassengers(this)" placeholder="Cari nama atau NIK penumpang..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-xs focus:outline-none focus:border-[#8C6239]">
        </div>

        <!-- Daftar Penumpang Tersimpan -->
        <div id="list-saved-passengers" class="space-y-2 overflow-y-auto max-h-60 custom-scrollbar pr-1">
            <?php if(empty($data['saved_passengers'])): ?>
                <div class="text-center py-8 text-slate-400 text-xs">Belum ada riwayat penumpang tersimpan. Silakan tambahkan baru.</div>
            <?php else: ?>
                <?php foreach($data['saved_passengers'] as$sp): ?>
                    <div onclick="pilihPenumpang('<?= htmlspecialchars($sp['nama']) ?>', '<?= htmlspecialchars($sp['nik']) ?>')" class="p-3 bg-slate-50 hover:bg-amber-50/50 border border-slate-200/80 hover:border-[#8C6239] rounded-xl cursor-pointer transition-all flex justify-between items-center">
                        <div>
                            <p class="text-xs font-bold text-[#0F172A]"><?= htmlspecialchars($sp['nama']) ?></p>
                            <p class="text-[11px] text-slate-400 font-mono">NIK: <?= htmlspecialchars($sp['nik']) ?></p>
                        </div>
                        <span class="text-[10px] font-bold text-[#8C6239] bg-amber-50 px-2.5 py-1 rounded-lg">Pilih</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="border-t border-slate-100 pt-3 text-center">
            <button type="button" onclick="alihKeTambahBaru()" class="text-xs font-bold text-[#8C6239] hover:underline">
                <i class="fa-solid fa-plus mr-1"></i> Atau tambah data penumpang baru
            </button>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PENUMPANG BARU MANUAL -->
<div id="modal-tambah-penumpang" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-[2rem] p-6 shadow-2xl space-y-5 animate-fade-in">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="font-extrabold text-base text-[#0F172A]">Tambah Penumpang Baru</h3>
            <button onclick="tutupModal('modal-tambah-penumpang')" class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="space-y-3">
            <div>
                <label class="text-[11px] font-bold text-slate-600 block mb-1">Nama Lengkap (Sesuai KTP)</label>
                <input type="text" id="new-nama" placeholder="Masukkan nama lengkap" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:border-[#8C6239]">
            </div>
            <div>
                <label class="text-[11px] font-bold text-slate-600 block mb-1">Nomor NIK / KTP</label>
                <input type="text" id="new-nik" maxlength="16" placeholder="16 digit NIK KTP" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:border-[#8C6239]">
            </div>
            <div>
                <label class="text-[11px] font-bold text-slate-600 block mb-1">Tanggal Lahir</label>
                <input type="date" id="new-tgllahir" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:border-[#8C6239]">
            </div>
        </div>

        <button type="button" onclick="simpanPenumpangBaru()" class="w-full bg-[#8C6239] hover:bg-[#74502e] text-white py-3 rounded-xl font-bold text-xs shadow-md transition-all">
            Simpan & Gunakan Penumpang
        </button>
    </div>
</div>

<script>
    let activeIndex = 0;

    // 1. Alert Pindah Halaman
    let isFormSubmitting = false;
    document.getElementById('form-identitas').addEventListener('submit', function(e) {
        isFormSubmitting = true; // Matikan alert jika submit form resmi
        const max = <?= $jumlahPenumpang ?>;
        for(let i=0; i<max; i++) {
            if(!document.getElementById(`input-nama-${i}`).value) {
                e.preventDefault(); isFormSubmitting = false;
                alert(`Data Penumpang ${i + 1} belum diisi!`);
                return;
            }
        }
    });
    window.addEventListener('beforeunload', function (e) {
        if (!isFormSubmitting) {
            e.preventDefault();
            e.returnValue = 'Data belum tersimpan. Yakin ingin meninggalkan halaman?';
        }
    });
    
    function bukaModalPilih(index) {
        activeIndex = index;
        document.getElementById('modal-pilih-penumpang').classList.remove('hidden');
        document.getElementById('modal-pilih-penumpang').classList.add('flex');
    }

    function bukaModalTambahManual() {
        // Jika ditambah via tombol atas, isikan ke slot kosong pertama
        const max = <?= $jumlahPenumpang ?>;
        for(let i=0; i<max; i++) {
            if(!document.getElementById(`input-nama-${i}`).value) {
                activeIndex = i;
                break;
            }
        }
        document.getElementById('modal-tambah-penumpang').classList.remove('hidden');
        document.getElementById('modal-tambah-penumpang').classList.add('flex');
    }

    function alihKeTambahBaru() {
        tutupModal('modal-pilih-penumpang');
        bukaModalTambahManual();
    }

    function tutupModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function pilihPenumpang(nama, nik) {
        document.getElementById(`display-nama-${activeIndex}`).innerText = nama;
        document.getElementById(`display-nik-${activeIndex}`).innerText = 'NIK: ' + nik;
        document.getElementById(`input-nama-${activeIndex}`).value = nama;
        document.getElementById(`input-nik-${activeIndex}`).value = nik;
        tutupModal('modal-pilih-penumpang');
    }

    function simpanPenumpangBaru() {
        const nama = document.getElementById('new-nama').value;
        const nik = document.getElementById('new-nik').value;
        const tgl = document.getElementById('new-tgllahir').value;

        if(!nama || !nik || !tgl) {
            alert('Semua kolom data penumpang wajib diisi!');
            return;
        }
        if(nik.length !== 16) {
            alert('Nomor NIK harus 16 digit!');
            return;
        }

        pilihPenumpang(nama, nik);
        tutupModal('modal-tambah-penumpang');
        // Reset form input modal
        document.getElementById('new-nama').value = '';
        document.getElementById('new-nik').value = '';
        document.getElementById('new-tgllahir').value = '';
    }

    function filterSavedPassengers(input) {
        const filter = input.value.toLowerCase();
        const items = document.querySelectorAll('#list-saved-passengers > div');
        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(filter) ? 'flex' : 'none';
        });
    }

    // Validasi sebelum submit form identitas
    document.getElementById('form-identitas').addEventListener('submit', function(e) {
        const max = <?= $jumlahPenumpang ?>;
        for(let i=0; i<max; i++) {
            const nama = document.getElementById(`input-nama-${i}`).value;
            if(!nama) {
                e.preventDefault();
                alert(`Data untuk Penumpang ${i + 1} belum dipilih atau diisi!`);
                bukaModalPilih(i);
                return;
            }
        }
    });
</script>