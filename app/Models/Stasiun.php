<?php
// app/Models/Stasiun.php

class Stasiun {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllStasiun() {
        // Mengambil semua stasiun dari tabel 'stasiun' diurutkan berdasarkan abjad
        $stmt = $this->db->prepare("SELECT * FROM stasiun ORDER BY nama_stasiun ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopDestinasi() {
        $stmt = $this->db->prepare("SELECT * FROM stasiun WHERE is_top_destination = TRUE LIMIT 6");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>