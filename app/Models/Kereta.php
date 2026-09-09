<?php
// app/Models/Kereta.php

class Kereta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getKelasKereta() {
        // Mengambil data kelas unik (tidak duplikat)
        $stmt = $this->db->prepare("SELECT DISTINCT kelas FROM keretas ORDER BY kelas ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>