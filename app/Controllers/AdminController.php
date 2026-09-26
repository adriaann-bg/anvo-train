<?php
// app/Controllers/AdminController.php

class AdminController extends Controller {

    // Halaman Utama Admin (Dashboard / Kelola Jadwal)
    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        $adminModel = $this->model('AdminModel');
        $data['judul'] = 'Pusat Kendali Operasional - ANVO Admin';
        $data['jadwal'] = $adminModel->getAllJadwal();
        $data['kereta'] = $adminModel->getAllKereta();
        $data['stasiun'] = $adminModel->getAllStasiun();
        $data['total_penumpang'] = $adminModel->getTotalPenumpangCount();
        $data['total_kru_aktif'] = $adminModel->getTotalKruAktif();

        $this->view('admin/index', $data);
    }

    // Proses Tambah Jadwal
    public function tambah_jadwal() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            
            if ($adminModel->tambahJadwal($_POST)) {
                if (session_status() == PHP_SESSION_NONE) { session_start(); }
                $_SESSION['success'] = 'Jadwal kereta cepat berhasil ditambahkan!';
                header('Location: /anvo/public/admin');
                exit;
            } else {
                if (session_status() == PHP_SESSION_NONE) { session_start(); }
                $_SESSION['error'] = 'Gagal menambahkan jadwal.';
                header('Location: /anvo/public/admin');
                exit;
            }
        }
    }

    // Proses Hapus Jadwal
    public function hapus_jadwal($id) {
        $adminModel = $this->model('AdminModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($adminModel->hapusJadwal($id)) {
            $_SESSION['success'] = 'Jadwal berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus jadwal.';
        }
        
        header('Location: /anvo/public/admin');
        exit;
    }

    public function tambahKru($data) {
        $sql = "INSERT INTO master_kru (nip, nik, nama_lengkap, tanggal_lahir, email, no_telepon, pendidikan_terakhir, alamat_lengkap, status_pernikahan, kontak_darurat, riwayat_penyakit, posisi, status_kru) 
                VALUES (:nip, :nik, :nama_lengkap, :tanggal_lahir, :email, :no_telepon, :pendidikan_terakhir, :alamat_lengkap, :status_pernikahan, :kontak_darurat, :riwayat_penyakit, :posisi, :status_kru)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nip' => $data['nip'],
            ':nik' => $data['nik'],
            ':nama_lengkap' => $data['nama_lengkap'],
            ':tanggal_lahir' => $data['tanggal_lahir'],
            ':email' => $data['email'],
            ':no_telepon' => $data['no_telepon'],
            ':pendidikan_terakhir' => $data['pendidikan_terakhir'],
            ':alamat_lengkap' => $data['alamat_lengkap'],
            ':status_pernikahan' => $data['status_pernikahan'],
            ':kontak_darurat' => $data['kontak_darurat'],
            ':riwayat_penyakit' => $data['riwayat_penyakit'],
            ':posisi' => $data['posisi'],
            ':status_kru' => $data['status_kru']
        ]);
    }

    public function hapusKru($id_kru) {
        $stmt = $this->db->prepare("DELETE FROM master_kru WHERE id_kru = :id");
        return $stmt->execute([':id' => $id_kru]);
    }

    public function getAllUsers() {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY id_user DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hapusUser($id_user) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id_user = :id");
        return $stmt->execute([':id' => $id_user]);
    }
}