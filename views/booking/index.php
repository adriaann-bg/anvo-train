<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-20">
    <div class="bg-white rounded-[32px] shadow-xl p-8 border border-gray-100">
        <h2 class="text-2xl font-bold text-center text-[#0F172A] mb-8">Beli Tiket</h2>
        
        <!-- Form Pencarian Jadwal -->
        <form action="/anvo/public/booking/jadwal" method="GET" id="form-pencarian" onsubmit="return validateSearchForm()" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-6">
            
            <!-- Keberangkatan (Asal) -->
            <div class="relative custom-dropdown" data-target="asal">
                <label class="text-sm text-gray-500 mb-2 block">Keberangkatan <span class="text-red-500">*</span></label>
                <div class="border-b border-gray-300 py-2 flex justify-between items-center cursor-pointer hover:border-[#8C6239] transition-colors" onclick="toggleDropdown('dropdown-asal')">
                    <span id="label-asal" class="text-gray-400">Pilih Kota Asal</span>
                    <svg class="w-4 h-4 text-gray-400 dropdown-arrow transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <!-- Menggunakan data dari controller $data['stasiun_list'] -->
                <input type="hidden" name="asal" id="input-asal">
                
                <div id="dropdown-asal" class="hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                    <div class="p-3 bg-gray-50 border-b border-gray-100 sticky top-0 z-10">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" placeholder="Cari kota..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#2B9BFB] text-sm" onkeyup="filterDropdown(this, 'list-asal')">
                        </div>
                    </div>
                    <ul id="list-asal" class="max-h-48 overflow-y-auto">
                        <?php foreach($data['stasiun_list'] as $stasiun): ?>
                        <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer text-sm border-b border-gray-50 last:border-0" 
                            onclick="selectOption('asal', '<?= $stasiun['nama_stasiun'] ?>', '<?= $stasiun['nama_stasiun'] ?>')">
                            <?= $stasiun['nama_stasiun'] ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tujuan -->
            <div class="relative custom-dropdown" data-target="tujuan">
                <label class="text-sm text-gray-500 mb-2 block">Tujuan <span class="text-red-500">*</span></label>
                <div class="border-b border-gray-300 py-2 flex justify-between items-center cursor-pointer hover:border-[#8C6239] transition-colors" onclick="toggleDropdown('dropdown-tujuan')">
                    <span id="label-tujuan" class="text-gray-400">Pilih Kota Tujuan</span>
                    <svg class="w-4 h-4 text-gray-400 dropdown-arrow transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <input type="hidden" name="tujuan" id="input-tujuan">
                
                <div id="dropdown-tujuan" class="hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                    <div class="p-3 bg-gray-50 border-b border-gray-100 sticky top-0 z-10">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" placeholder="Cari kota..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#2B9BFB] text-sm" onkeyup="filterDropdown(this, 'list-tujuan')">
                        </div>
                    </div>
                    <ul id="list-tujuan" class="max-h-48 overflow-y-auto">
                        <?php foreach($data['stasiun_list'] as $stasiun): ?>
                        <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer text-sm border-b border-gray-50 last:border-0" 
                            onclick="selectOption('tujuan', '<?= $stasiun['nama_stasiun'] ?>', '<?= $stasiun['nama_stasiun'] ?>')">
                            <?= $stasiun['nama_stasiun'] ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tanggal Berangkat -->
            <div class="flex flex-col">
                <label class="text-sm text-gray-500 mb-2">Tanggal Berangkat <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" id="input-tanggal" value="<?= date('Y-m-d') ?>" class="border-b border-gray-300 py-2 focus:outline-none focus:border-[#8C6239] bg-transparent text-[#0F172A]">
            </div>
            
            <!-- Tanggal Kembali (Opsional via Switch) -->
            <div class="flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm text-gray-500">Tanggal Kembali</label>
                    <label class="inline-flex relative items-center cursor-pointer">
                        <input type="checkbox" id="toggle-pulang" class="sr-only peer">
                        <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#8C6239]"></div>
                    </label>
                </div>
                <input type="date" name="tanggal_pulang" id="input-pulang" disabled class="border-b border-gray-300 py-2 focus:outline-none focus:border-[#8C6239] bg-transparent opacity-50 cursor-not-allowed">
            </div>

            <!-- Penumpang -->
            <div class="relative custom-dropdown" data-target="penumpang">
                <label class="text-sm text-gray-500 mb-2 block">Penumpang <span class="text-red-500">*</span></label>
                <div class="border-b border-gray-300 py-2 flex justify-between items-center cursor-pointer hover:border-[#8C6239] transition-colors" onclick="toggleDropdown('dropdown-penumpang')">
                    <span id="label-penumpang" class="text-gray-400">Pilih Jumlah</span>
                    <svg class="w-4 h-4 text-gray-400 dropdown-arrow transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <input type="hidden" name="penumpang" id="input-penumpang">
                
                <div id="dropdown-penumpang" class="hidden absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                    <ul class="max-h-48 overflow-y-auto py-2">
                        <?php for($i=1; $i<=10; $i++): ?>
                        <li class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-sm" onclick="selectOption('penumpang', '<?= $i ?> Penumpang', '<?= $i ?>')"><?= $i ?> Penumpang</li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center md:col-span-2 lg:col-span-3 mt-4 border-t border-gray-100 pt-6 w-full">
                <p class="text-xs text-orange-500 flex items-center gap-2 mb-6 md:mb-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    Penumpang anak di atas 3 tahun wajib membeli tiket sendiri.
                </p>
                <button type="submit" class="w-full md:w-auto bg-transparent border border-[#0F172A] text-[#0F172A] px-10 py-3 rounded-full font-medium hover:border-transparent hover:text-white hover:bg-gradient-to-r hover:from-[#8C6239] hover:to-[#AF8B69] transition-all duration-300">
                    Cari Tiket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT LOGIKA FORM & VALIDASI ENTER (Sama Untuk Landing Page) -->
<script>
    // Inisialisasi: Cegah pemilihan tanggal masa lalu dan atur batasan max 30 hari
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const inputTanggalBerangkat = document.getElementById('input-tanggal');
        const inputTanggalPulang = document.getElementById('input-pulang');

        if (inputTanggalBerangkat) {
            inputTanggalBerangkat.min = today;
        }

        function updateReturnDateConstraints() {
            if (!inputTanggalBerangkat.value) return;
            
            const depDateStr = inputTanggalBerangkat.value;
            inputTanggalPulang.min = depDateStr;

            // Hitung batas maksimal 30 hari dari tanggal keberangkatan
            let maxDate = new Date(depDateStr);
            maxDate.setDate(maxDate.getDate() + 30);
            const maxDateStr = maxDate.toISOString().split('T')[0];
            inputTanggalPulang.max = maxDateStr;

            // Jika tanggal pulang melebihi batas 30 hari, sesuaikan otomatis
            if (inputTanggalPulang.value && inputTanggalPulang.value > maxDateStr) {
                inputTanggalPulang.value = maxDateStr;
            }
            // Jika tanggal pulang lebih kecil dari keberangkatan, sesuaikan otomatis
            if (inputTanggalPulang.value && inputTanggalPulang.value < depDateStr) {
                inputTanggalPulang.value = depDateStr;
            }
        }

        if (inputTanggalBerangkat) {
            inputTanggalBerangkat.addEventListener('change', updateReturnDateConstraints);
        }
    });

    // 1. Logika Switch Tanggal Pulang
    document.getElementById('toggle-pulang').addEventListener('change', function() {
        const inputPulang = document.getElementById('input-pulang');
        const inputBerangkat = document.getElementById('input-tanggal').value;
        const today = new Date().toISOString().split('T')[0];

        if(this.checked) {
            inputPulang.disabled = false;
            inputPulang.classList.remove('opacity-50', 'cursor-not-allowed');
            
            const minDate = (inputBerangkat && inputBerangkat > today) ? inputBerangkat : today;
            inputPulang.min = minDate;
            
            // Tentukan max date (30 hari dari tanggal berangkat)
            let maxDate = new Date(minDate);
            maxDate.setDate(maxDate.getDate() + 30);
            inputPulang.max = maxDate.toISOString().split('T')[0];

            inputPulang.value = minDate; 
        } else {
            inputPulang.disabled = true;
            inputPulang.classList.add('opacity-50', 'cursor-not-allowed');
            inputPulang.value = ''; 
        }
    });

    // 2. Validasi Sebelum Form Dikirim 
    function validateSearchForm() {
        const asal = document.getElementById('input-asal').value;
        const tujuan = document.getElementById('input-tujuan').value;
        const tanggal = document.getElementById('input-tanggal').value;
        const isPulangActive = document.getElementById('toggle-pulang').checked;
        const tanggalPulang = document.getElementById('input-pulang').value;
        const penumpang = document.getElementById('input-penumpang').value;

        if (!asal) {
            alert('Mohon pilih kota keberangkatan terlebih dahulu.');
            return false;
        }
        if (!tujuan) {
            alert('Mohon pilih kota tujuan terlebih dahulu.');
            return false;
        }
        if (asal === tujuan) {
            alert('Kota keberangkatan dan kota tujuan tidak boleh sama!');
            return false;
        }
        if (!tanggal) {
            alert('Mohon tentukan tanggal keberangkatan.');
            return false;
        }
        if (isPulangActive && !tanggalPulang) {
            alert('Mohon tentukan tanggal kembali karena opsi pulang aktif.');
            return false;
        }
        
        // Validasi Tanggal Kembali (Minimal sama dengan keberangkatan & Maksimal 30 hari)
        if (isPulangActive) {
            const depDate = new Date(tanggal);
            const retDate = new Date(tanggalPulang);
            
            if (retDate < depDate) {
                alert('Tanggal kembali tidak boleh lebih awal dari tanggal keberangkatan!');
                return false;
            }

            const maxAllowedDate = new Date(tanggal);
            maxAllowedDate.setDate(maxAllowedDate.getDate() + 30);
            
            if (retDate > maxAllowedDate) {
                alert('Maksimal pengambilan tanggal kembali adalah 30 hari dari tanggal keberangkatan!');
                return false;
            }
        }

        if (!penumpang) {
            alert('Mohon pilih jumlah penumpang.');
            return false;
        }
        return true;
    }

    // 3. Pencegahan Submit dengan "Enter" secara keseluruhan form
    document.getElementById('form-pencarian').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); 
        }
    });

    // 4. Logika Custom Dropdown
    function toggleDropdown(id) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            if(el.id !== id) {
                el.classList.add('hidden');
                el.parentElement.querySelector('.dropdown-arrow').classList.remove('rotate-180');
            }
        });
        
        const dropdown = document.getElementById(id);
        const arrow = dropdown.parentElement.querySelector('.dropdown-arrow');
        dropdown.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
        
        const searchInput = dropdown.querySelector('input[type="text"]');
        if(searchInput && !dropdown.classList.contains('hidden')) {
            searchInput.focus();
        }
    }

    function selectOption(target, labelText, value) {
        const label = document.getElementById('label-' + target);
        label.innerText = labelText;
        label.classList.remove('text-gray-400');
        label.classList.add('text-[#0F172A]', 'font-medium');
        
        document.getElementById('input-' + target).value = value;
        toggleDropdown('dropdown-' + target);
    }

    function filterDropdown(input, listId) {
        const filter = input.value.toUpperCase();
        const ul = document.getElementById(listId);
        const li = ul.getElementsByTagName('li');

        for (let i = 0; i < li.length; i++) {
            let txtValue = li[i].textContent || li[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    // Tutup dropdown jika klik di luar area
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.custom-dropdown')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                el.classList.add('hidden');
                el.parentElement.querySelector('.dropdown-arrow').classList.remove('rotate-180');
            });
        }
    });
</script>