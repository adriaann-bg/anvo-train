<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - ANVO Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#F1F5F9] min-h-screen flex flex-col justify-between p-8 font-sans antialiased text-slate-800">

    <!-- LOGO DI KIRI ATAS -->
    <div>
        <img src="/anvo/public/img/logo-anvo-berwarna.svg" alt="ANVO" class="h-8 object-contain" onerror="this.outerHTML='<span class=\'font-black text-lg text-[#0F172A]\'>ANVO</span>'">
    </div>

    <!-- KONTEN TENGAH (Sesuai Wireframe) -->
    <div class="w-full max-w-3xl mx-auto flex flex-col items-center space-y-6 my-auto">
        
        <!-- LINGKARAN BESAR TENGAH (MASKOT PANDA) -->
        <div class="w-36 h-36 bg-slate-200 rounded-full flex items-center justify-center shadow-lg relative overflow-hidden border-4 border-white">
            <div class="relative w-full h-full flex flex-col items-center justify-center">
                <!-- Telinga Panda -->
                <div class="absolute top-4 left-6 w-9 h-9 bg-slate-800 rounded-full"></div>
                <div class="absolute top-4 right-6 w-9 h-9 bg-slate-800 rounded-full"></div>
                <!-- Wajah Panda -->
                <div class="w-20 h-20 bg-white rounded-full flex flex-col items-center justify-center relative shadow-inner mt-3">
                    <!-- Mata Sedih -->
                    <div class="flex justify-between w-11 px-1 mt-2">
                        <div class="w-3.5 h-4.5 bg-slate-900 rounded-full rotate-12"></div>
                        <div class="w-3.5 h-4.5 bg-slate-900 rounded-full -rotate-12"></div>
                    </div>
                    <!-- Mulut Sedih -->
                    <div class="w-4 h-2 border-b-2 border-slate-900 rounded-full mt-1"></div>
                    <!-- Tetes Air Mata -->
                    <div class="absolute bottom-2 right-2.5 w-1.5 h-3 bg-[#8C6239] rounded-full animate-bounce"></div>
                </div>
            </div>
        </div>

        <!-- TEKS ERROR! -->
        <h1 class="text-3xl font-black tracking-widest text-slate-900">ERROR!</h1>

        <!-- CARD KODE BLOCK (Gaya Sesuai Wireframe) -->
        <div class="w-full bg-slate-200/80 p-5 rounded-[2.5rem] shadow-xl border border-slate-300/60">
            <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-inner">
                <!-- Header Code Block: 3 Titik di Kiri, Tombol Copy di Kanan -->
                <div class="flex items-center justify-between px-5 py-3.5 bg-slate-800 border-b border-slate-700">
                    <!-- 3 Titik Kontrol macOS -->
                    <div class="flex items-center gap-2">
                        <div class="w-3.5 h-3.5 rounded-full bg-rose-500"></div>
                        <div class="w-3.5 h-3.5 rounded-full bg-amber-400"></div>
                        <div class="w-3.5 h-3.5 rounded-full bg-emerald-500"></div>
                    </div>
                    <!-- Tombol Copy di Kanan Atas Head Code Block -->
                    <button onclick="copyErrorLog()" id="copy-btn" class="text-xs font-semibold text-slate-200 hover:text-white flex items-center gap-1.5 bg-slate-700/80 hover:bg-slate-700 px-3.5 py-1.5 rounded-xl transition-all shadow-sm cursor-pointer">
                        <i class="fa-solid fa-copy" id="copy-icon"></i> <span id="copy-text">Copy</span>
                    </button>
                </div>
                <!-- Isi Plain Text Log Error -->
                <pre id="error-log-text" class="p-5 text-slate-100 text-xs font-mono overflow-x-auto max-h-48 leading-relaxed selection:bg-[#8C6239] selection:text-white"><?= htmlspecialchars($errorMessage ?? 'Unknown error occurred.') ?></pre>
            </div>
        </div>

        <!-- TOMBOL KEMBALI KE DASHBOARD -->
        <div class="flex gap-3 pt-2">
            <a href="/anvo/public/admin" class="bg-[#0F172A] hover:bg-[#8C6239] text-white px-6 py-3.5 rounded-2xl font-bold text-xs transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-house"></i> Kembali ke Dashboard
            </a>
        </div>

    </div>

    <div></div> <!-- Spacer Bawah -->

    <!-- SCRIPT JAVASCRIPT UNTUK TOMBOL COPY -->
    <script>
        function copyErrorLog() {
            const textElement = document.getElementById('error-log-text');
            const textToCopy = textElement.innerText || textElement.textContent;
            
            navigator.clipboard.writeText(textToCopy).then(() => {
                const btnText = document.getElementById('copy-text');
                const icon = document.getElementById('copy-icon');
                
                btnText.innerText = 'Copied!';
                icon.className = 'fa-solid fa-check text-emerald-400';
                
                setTimeout(() => {
                    btnText.innerText = 'Copy';
                    icon.className = 'fa-solid fa-copy';
                }, 2000);
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        }
    </script>
</body>
</html>