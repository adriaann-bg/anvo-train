<?php
// app/Config/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // 1. Membaca kredensial dengan aman dari file .ini
        $config = parse_ini_file(__DIR__ . '/database.ini');
        
        if (!$config) {
            error_log("KRITIKAL: Gagal membaca file database.ini");
            throw new Exception("Terjadi kesalahan sistem. Kode: DB_CFG");
        }

        $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
            PDO::ATTR_EMULATE_PREPARES   => false, 
            PDO::ATTR_PERSISTENT         => true
        ];

        try {
            // 2. Membuka koneksi menggunakan data dari file .ini
            $this->pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
        } catch (PDOException $e) {
            // 3. Log pesan error asli ke server (error.log), BUKAN ke browser!
            error_log("Koneksi Database PDO Gagal: " . $e->getMessage());
            
            // 4. Lempar Exception generik agar struktur tabel/server tidak terekspos ke pengguna
            throw new Exception("Sistem sedang mengalami gangguan. Silakan coba beberapa saat lagi.");
        }
    }

    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>