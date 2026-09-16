<?php
// public/index.php

// Memulai session untuk sistem login dan keranjang pemesanan nanti
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// PERBAIKAN ZONA WAKTU: Pastikan PHP menggunakan waktu Indonesia (WIB)
date_default_timezone_set('Asia/Jakarta');

// 1. PANGGIL AUTOLOADER COMPOSER DI SINI (Wajib di atas file core)
require_once '../vendor/autoload.php';

// Memanggil file-file inti (Core) dan Konfigurasi bawaanmu
require_once '../app/Config/Database.php';
require_once '../app/Core/Controller.php';
require_once '../app/Core/App.php';

// Menjalankan mesin MVC
$app = new App();
?>