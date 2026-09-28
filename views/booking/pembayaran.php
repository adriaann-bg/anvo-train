<div class="max-w-xl mx-auto py-12 px-6">
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6">
        <div class="text-center space-y-2">
            <h1 class="text-xl font-extrabold text-[#0F172A]">Konfirmasi & Pembayaran Tiket</h1>
            <p class="text-xs text-slate-400">Selesaikan pembayaran reservasi kereta cepat Anda.</p>
        </div>

        <!-- Ringkasan Tagihan -->
        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200/60 space-y-4">
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-500 font-medium">ID Reservasi:</span>
                <span class="font-bold text-[#0F172A]">#ANVO-<?= $data['reservasi']['id_reservasi'] ?></span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-500 font-medium">Kereta / Armada:</span>
                <span class="font-bold text-[#0F172A]"><?= htmlspecialchars($data['reservasi']['nama_kereta']) ?></span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-500 font-medium">Rute Perjalanan:</span>
                <span class="font-bold text-[#0F172A]"><?= htmlspecialchars($data['reservasi']['nama_asal']) ?> &rarr; <?= htmlspecialchars($data['reservasi']['nama_tujuan']) ?></span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-500 font-medium">Tanggal Keberangkatan:</span>
                <span class="font-bold text-[#0F172A]"><?= date('d M Y', strtotime($data['reservasi']['tanggal_keberangkatan'])) ?></span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-500 font-medium">Jumlah Tiket:</span>
                <span class="font-bold text-[#0F172A]"><?= $data['reservasi']['jumlah_tiket'] ?> Orang</span>
            </div>
            <hr class="border-slate-200">
            <div class="flex justify-between items-center">
                <span class="text-sm font-bold text-slate-700">Total Tagihan:</span>
                <span class="text-lg font-black text-[#8C6239]">Rp <?= number_format($data['reservasi']['jumlah_tagihan'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Tombol Bayar Sekarang -->
        <button type="button" onclick="bukaModalPin()" class="w-full bg-[#8C6239] hover:bg-[#74502e] text-white py-4 rounded-2xl font-bold text-xs shadow-md transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-lock"></i> Bayar Sekarang
        </button>
    </div>
</div>

<!-- POP-UP INPUT PIN KEAMANAN -->
<div id="modal-pin" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl space-y-6 animate-fade-in text-center">
        <div>
            <div class="w-12 h-12 bg-amber-50 text-[#8C6239] rounded-2xl flex items-center justify-center mx-auto mb-3 text-lg">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 class="text-lg font-extrabold text-[#0F172A]">Masukkan PIN Keamanan</h3>
            <p class="text-xs text-slate-400 mt-1">Masukkan 6 digit PIN akun Anda untuk mengesahkan transaksi.</p>
        </div>

        <div class="space-y-4">
            <input type="password" id="input-pin-transaksi" maxlength="6" pattern="\d{6}" placeholder="••••••" class="w-full border border-slate-200 rounded-2xl py-3.5 px-4 focus:ring-2 focus:ring-[#8C6239] focus:outline-none text-center text-2xl tracking-[0.5em] bg-slate-50 font-bold" required>
            <div id="pin-error-msg" class="text-xs text-rose-500 font-semibold hidden"></div>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="tutupModalPin()" class="w-1/2 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3.5 rounded-2xl font-bold text-xs transition-all">Batal</button>
            <button type="button" onclick="prosesBayar()" class="w-1/2 bg-[#8C6239] hover:bg-[#74502e] text-white py-3.5 rounded-2xl font-bold text-xs shadow-md transition-all">Konfirmasi Bayar</button>
        </div>
    </div>
</div>

<script>
    const idPembayaran = <?= $data['reservasi']['id_pembayaran'] ?>;
    const idReservasi = <?= $data['reservasi']['id_reservasi'] ?>;

    function bukaModalPin() {
        document.getElementById('modal-pin').classList.remove('hidden');
        document.getElementById('modal-pin').classList.add('flex');
        document.getElementById('input-pin-transaksi').focus();
    }

    function tutupModalPin() {
        document.getElementById('modal-pin').classList.add('hidden');
        document.getElementById('modal-pin').classList.remove('flex');
        document.getElementById('input-pin-transaksi').value = '';
        document.getElementById('pin-error-msg').classList.add('hidden');
    }

    function prosesBayar() {
        const pin = document.getElementById('input-pin-transaksi').value;
        const errDiv = document.getElementById('pin-error-msg');

        if(pin.length < 6) {
            errDiv.innerText = 'PIN harus 6 digit angka.';
            errDiv.classList.remove('hidden');
            return;
        }

        // Kirim permintaan AJAX ke BookingController::proses_bayar_ajax()
        fetch('/anvo/public/booking/proses_bayar_ajax', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                pin: pin,
                id_pembayaran: idPembayaran,
                id_reservasi: idReservasi
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                window.location.href = data.redirect; // Mengarah ke halaman sukses / e-ticket
            } else {
                errDiv.innerText = data.message;
                errDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errDiv.innerText = 'Terjadi kesalahan sistem.';
            errDiv.classList.remove('hidden');
        });
    }
</script>