<?php
// app/Models/AdminModel.php

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllStasiun() {
        $stmt = $this->db->prepare("SELECT * FROM stasiun");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hanya ambil kereta yang aktif (tidak sedang maintenance) untuk form jadwal
    public function getKeretaAktif() {
        $stmt = $this->db->prepare("SELECT * FROM kereta WHERE status_operasional = 'Aktif'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllKereta() {
        $stmt = $this->db->prepare("SELECT * FROM kereta");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // TAMBAHKAN KEMBALI FUNGSI INI AGAR DASHBOARD TIDAK ERROR
    public function getAllJadwal() {
        $sql = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis_kelas 
                FROM jadwal 
                JOIN kereta ON jadwal.id_kereta = kereta.id_kereta 
                ORDER BY jadwal.tanggal DESC, jadwal.jam_berangkat ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Ambil jadwal dengan opsi Filter
    public function getFilteredJadwal($tanggal = '', $kelas = '', $rute = '') {
        $sql = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis_kelas 
                FROM jadwal 
                JOIN kereta ON jadwal.id_kereta = kereta.id_kereta WHERE 1=1";
        
        $params = [];

        if (!empty($tanggal)) {
            $sql .= " AND jadwal.tanggal = :tanggal";
            $params[':tanggal'] = $tanggal;
        }
        if (!empty($kelas)) {
            $sql .= " AND kereta.jenis_kelas = :kelas";
            $params[':kelas'] = $kelas;
        }
        if (!empty($rute)) {
            // Rute format: Asal-Tujuan (misal: Halim-Bandung)
            list($asal, $tujuan) = explode('-', $rute);
            $sql .= " AND jadwal.stasiun_asal = :asal AND jadwal.stasiun_tujuan = :tujuan";
            $params[':asal'] = trim($asal);
            $params[':tujuan'] = trim($tujuan);
        }

        $sql .= " ORDER BY jadwal.tanggal DESC, jadwal.jam_berangkat ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahJadwal($data) {
        $sql = "INSERT INTO jadwal (id_kereta, stasiun_asal, stasiun_tujuan, jam_berangkat, jam_tiba, harga, tanggal, jenis_jadwal) 
                VALUES (:id_kereta, :stasiun_asal, :stasiun_tujuan, :jam_berangkat, :jam_tiba, :harga, :tanggal, :jenis_jadwal)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_kereta' => $data['id_kereta'],
            ':stasiun_asal' => $data['stasiun_asal'],
            ':stasiun_tujuan' => $data['stasiun_tujuan'],
            ':jam_berangkat' => $data['jam_berangkat'],
            ':jam_tiba' => $data['jam_tiba'],
            ':harga' => $data['harga'],
            ':tanggal' => $data['tanggal'],
            ':jenis_jadwal' => $data['jenis_jadwal']
        ]);
    }

    public function hapusJadwal($id) {
        $stmt = $this->db->prepare("DELETE FROM jadwal WHERE id_jadwal = :id");
        return $stmt->execute([':id' => $id]);
    }
}