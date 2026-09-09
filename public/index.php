<?php
// public/index.php

// Memulai session untuk sistem login dan keranjang pemesanan nanti
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil file-file inti (Core) dan Konfigurasi
require_once '../app/Config/Database.php';
require_once '../app/Core/Controller.php';
require_once '../app/Core/App.php';

// Menjalankan mesin MVC
$app = new App();
?>