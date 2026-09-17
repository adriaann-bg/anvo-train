<?php
// app/Models/RouteModel.php

class RouteModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllKoridor() {
        $stmt = $this->db->prepare("SELECT * FROM koridor");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStasiunByKoridor($id_koridor) {
        $stmt = $this->db->prepare("SELECT * FROM koridor_stasiun WHERE id_koridor = :id_koridor ORDER BY urutan ASC");
        $stmt->execute([':id_koridor' => $id_koridor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil hanya stasiun master yang BELUM masuk ke koridor ini (untuk filtering dropdown)
    public function getStasiunTersedia($id_koridor) {
        $sql = "SELECT * FROM stasiun WHERE nama_stasiun NOT IN 
                (SELECT nama_stasiun FROM koridor_stasiun WHERE id_koridor = :id_koridor)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_koridor' => $id_koridor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahKoridor($nama_koridor, $keterangan) {
        $stmt = $this->db->prepare("INSERT INTO koridor (nama_koridor, keterangan) VALUES (:nama, :ket)");
        return $stmt->execute([':nama' => $nama_koridor, ':ket' => $keterangan]);
    }

    // Tambah stasiun dengan otomatis menghitung nomor urut berikutnya
    public function tambahStasiunKoridorOtomatis($id_koridor, $nama_stasiun) {
        // Cari urutan terakhir
        $stmt = $this->db->prepare("SELECT MAX(urutan) as max_urut FROM koridor_stasiun WHERE id_koridor = :id");
        $stmt->execute([':id' => $id_koridor]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $urutan_berikutnya = ($res['max_urut'] ?? 0) + 1;

        $stmtIns = $this->db->prepare("INSERT INTO koridor_stasiun (id_koridor, nama_stasiun, urutan) VALUES (:id_koridor, :stasiun, :urutan)");
        return $stmtIns->execute([
            ':id_koridor' => $id_koridor,
            ':stasiun' => $nama_stasiun,
            ':urutan' => $urutan_berikutnya
        ]);
    }

    public function hapusStasiunKoridor($id_koridor_stasiun) {
        $stmt = $this->db->prepare("DELETE FROM koridor_stasiun WHERE id_koridor_stasiun = :id");
        return $stmt->execute([':id' => $id_koridor_stasiun]);
    }

    public function editStasiunKoridor($id_koridor_stasiun, $nama_stasiun_baru) {
        $stmt = $this->db->prepare("UPDATE koridor_stasiun SET nama_stasiun = :stasiun WHERE id_koridor_stasiun = :id");
        return $stmt->execute([
            ':stasiun' => $nama_stasiun_baru,
            ':id' => $id_koridor_stasiun
        ]);
    }

    // Memperbarui urutan stasiun secara massal
    public function updateUrutanKoridor($data_urutan) {
        $stmt = $this->db->prepare("UPDATE koridor_stasiun SET urutan = :urutan WHERE id_koridor_stasiun = :id");
        foreach ($data_urutan as $item) {
            $stmt->execute([
                ':urutan' => $item['urutan'],
                ':id' => $item['id']
            ]);
        }
        return true;
    }

    // Ambil data kereta yang berdinas berdasarkan rute/stasiun di koridor ini
    public function getKeretaByKoridor($id_koridor) {
        // Kita ambil kereta yang memiliki jadwal yang melewati stasiun dalam koridor ini
        $sql = "SELECT DISTINCT kereta.* FROM kereta 
                JOIN jadwal ON kereta.id_kereta = jadwal.id_kereta
                JOIN koridor_stasiun ks1 ON jadwal.stasiun_asal = ks1.nama_stasiun
                WHERE ks1.id_koridor = :id_koridor";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_koridor' => $id_koridor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ubah status aktif/non-aktif koridor
    public function toggleStatusKoridor($id_koridor, $status_baru) {
        $stmt = $this->db->prepare("UPDATE koridor SET status_koridor = :status WHERE id_koridor = :id");
        return $stmt->execute([':status' => $status_baru, ':id' => $id_koridor]);
    }
}