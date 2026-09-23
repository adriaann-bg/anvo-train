<?php
// public/index.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Jakarta');

// Fungsi universal untuk merender halaman error sesuai wireframe kasar Anda
function renderErrorPage($errorMessage) {
    if (!headers_sent()) {
        http_response_code(500);
    }
    
    $errorViewPath = dirname(__DIR__) . '/app/views/errors/error_page.php';
    
    if (file_exists($errorViewPath)) {
        require_once $errorViewPath;
    } else {
        // Fallback jika file view error tidak ditemukan
        echo "<div style='font-family: monospace; background: #0f172a; color: #f8fafc; padding: 20px; border-radius: 12px;'>";
        echo "<h3>Critical Error: Error View Not Found</h3>";
        echo "<pre>" . htmlspecialchars($errorMessage) . "</pre>";
        echo "</div>";
    }
    exit;
}

// 1. Tangkap semua Error standar PHP (Notice, Warning, dll) dan ubah menjadi ErrorException agar tertangkap
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// 2. Tangkap semua Uncaught Exception (seperti PDOException, dll)
set_exception_handler(function($e) {
    $errorMessage = "Type: " . get_class($e) . "\n";
    $errorMessage .= "Message: " . $e->getMessage() . "\n";
    $errorMessage .= "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n\n";
    $errorMessage .= "Stack Trace:\n" . $e->getTraceAsString();
    
    renderErrorPage($errorMessage);
});

// 3. Tangkap Fatal Error (seperti memory limit habis atau fungsi tidak ditemukan) saat skrip berhenti mendadak
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        $errorMessage = "Type: Fatal Error (" . $error['type'] . ")\n";
        $errorMessage .= "Message: " . $error['message'] . "\n";
        $errorMessage .= "File: " . $error['file'] . " (Line: " . $error['line'] . ")";
        
        renderErrorPage($errorMessage);
    }
});

// Panggil autoloader Composer
require_once '../vendor/autoload.php';

// Panggil file-file inti (Core) dan Konfigurasi
require_once '../app/Config/Database.php';
require_once '../app/Core/Controller.php';
require_once '../app/Core/App.php';

// Daftarkan Controller Admin manual agar terbaca mesin MVC
require_once '../app/Controllers/AdminController.php';

// Jalankan Aplikasi MVC
$app = new App();