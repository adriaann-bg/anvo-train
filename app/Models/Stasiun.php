<?php
// app/Models/Stasiun.php

class Stasiun {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllStasiun() {
        // Mengambil semua stasiun diurutkan berdasarkan abjad
        $stmt = $this->db->prepare("SELECT * FROM stasiuns ORDER BY nama_stasiun ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tambahkan di bawah fungsi getAllStasiun()
    public function getTopDestinasi() {
        $stmt = $this->db->prepare("SELECT * FROM stasiuns WHERE is_top_destination = TRUE LIMIT 6");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>