<?php
// public/index.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Jakarta');

// Panggil autoloader Composer
require_once '../vendor/autoload.php';

// Panggil file-file inti (Core) dan Konfigurasi
require_once '../app/Config/Database.php';
require_once '../app/Core/Controller.php';
require_once '../app/Core/App.php';

// TAMBAHKAN BARIS INI: Daftarkan Controller Admin manual agar terbaca mesin MVC
require_once '../app/Controllers/AdminController.php';

$app = new App();