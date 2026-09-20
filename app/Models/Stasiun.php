<?php
// app/Models/Stasiun.php

class Stasiun {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllStasiun() {
        $stmt = $this->db->prepare("SELECT * FROM stasiun ORDER BY nama_stasiun ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopDestinasi() {
        // Ubah LIMIT 6 menjadi LIMIT 15 agar semua top destinasi muncul di carousel
        $stmt = $this->db->prepare("SELECT * FROM stasiun WHERE is_top_destination = TRUE LIMIT 15");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>