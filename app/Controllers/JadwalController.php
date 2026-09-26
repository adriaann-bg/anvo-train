<?php
// app/Controllers/JadwalController.php

class JadwalController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        $adminModel = $this->model('AdminModel');
        $routeModel = $this->model('RouteModel'); 
        
        $tanggal = $_GET['tanggal'] ?? '';
        $kelas = $_GET['kelas'] ?? '';
        $asal = $_GET['asal'] ?? '';
        $tujuan = $_GET['tujuan'] ?? '';

        // Panggil model dengan parameter baru
        $jadwal = $adminModel->getFilteredJadwal($tanggal, $kelas, $asal, $tujuan);
        
        foreach ($jadwal as &$j) {
            if (!empty($j['id_koridor'])) {
                $koridor_stasiun = $routeModel->getStasiunByKoridor($j['id_koridor']);
                $j['full_stasiun'] = array_column($koridor_stasiun, 'nama_stasiun');
            } else {
                $j['full_stasiun'] = [];
            }
        }

        $data['judul'] = 'Kelola Jadwal - ANVO Admin';
        $data['jadwal'] = $jadwal; 
        $data['kereta'] = $adminModel->getKeretaAktif();
        $data['stasiun'] = $adminModel->getAllStasiun();
        $data['koridor_list'] = $routeModel->getAllKoridor(); 
        
        $data['filter'] = [
            'tanggal' => $tanggal, 
            'kelas' => $kelas, 
            'asal' => $asal, 
            'tujuan' => $tujuan
        ];

        $this->view('admin/jadwal', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }
            
            // Cek apakah stasiun_transit sudah berupa JSON string dari JS atau array biasa
            if (isset($_POST['stasiun_transit']) && is_string($_POST['stasiun_transit'])) {
                $transitVal = $_POST['stasiun_transit'];
            } else {
                $transitArr = isset($_POST['stasiun_transit']) ? $_POST['stasiun_transit'] : [];
                $transitVal = json_encode($transitArr);
            }
            $_POST['stasiun_transit'] = $transitVal;
            
            if ($adminModel->tambahJadwal($_POST)) {
                $_SESSION['success'] = 'Jadwal operasional baru berhasil dibuat!';
            } else {
                $_SESSION['error'] = 'Gagal menyimpan jadwal operasional.';
            }
            header('Location: /anvo/public/jadwal');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($adminModel->hapusJadwal($id)) {
            $_SESSION['success'] = 'Jadwal berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus jadwal.';
        }
        
        header('Location: /anvo/public/jadwal');
        exit;
    }

    // Membuka halaman khusus Edit Jadwal
    public function edit_page($id_jadwal) {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $adminModel = $this->model('AdminModel');
        $routeModel = $this->model('RouteModel');
        
        $data['judul'] = 'Edit Jadwal Operasional - ANVO Admin';
        $data['jadwal_edit'] = $adminModel->getJadwalById($id_jadwal);
        $data['koridor_list'] = $routeModel->getAllKoridor();
        $data['stasiun'] = $adminModel->getAllStasiun();
        
        // Memanggil file view jadwal_edit.php (Pastikan Anda membuat file ini nanti)
        $this->view('admin/jadwal_edit', $data);
    }

    // Proses Simpan Pembaruan Jadwal
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }
            
            if (isset($_POST['stasiun_transit']) && is_string($_POST['stasiun_transit'])) {
                $transitVal = $_POST['stasiun_transit'];
            } else {
                $transitArr = isset($_POST['stasiun_transit']) ? $_POST['stasiun_transit'] : [];
                $transitVal = json_encode($transitArr);
            }
            $_POST['stasiun_transit'] = $transitVal;
            
            if ($adminModel->updateJadwal($_POST)) {
                $_SESSION['success'] = 'Jadwal operasional berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui jadwal operasional.';
            }
            header('Location: /anvo/public/jadwal');
            exit;
        }
    }

    // Membuka halaman khusus Tambah Jadwal
    public function tambah_page() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $routeModel = $this->model('RouteModel');
        
        $data['judul'] = 'Buat Jadwal Baru - ANVO Admin';
        $data['koridor_list'] = $routeModel->getAllKoridor();
        
        $this->view('admin/jadwal_tambah', $data);
    }

    // Fungsi AJAX untuk mengambil urutan stasiun berdasarkan koridor
    public function get_stasiun_ajax($id_koridor) {
        $routeModel = $this->model('RouteModel');
        $stasiun = $routeModel->getStasiunByKoridor($id_koridor);
        
        header('Content-Type: application/json');
        echo json_encode($stasiun);
        exit;
    }

    // Fungsi khusus AJAX untuk mengambil kereta berdasarkan koridor
    public function get_kereta_ajax($id_koridor) {
        $routeModel = $this->model('RouteModel');
        $kereta = $routeModel->getKeretaByKoridor($id_koridor);
        
        // Kembalikan data dalam format JSON untuk JavaScript
        header('Content-Type: application/json');
        echo json_encode($kereta);
        exit;
    }

    // Membuka Halaman Manajemen Kru Berdasarkan Jadwal
    public function crew($id_jadwal) {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $adminModel = $this->model('AdminModel');

        $filterTanggal = $_GET['tanggal_tugas'] ?? '';
        $filterKeyword = $_GET['keyword'] ?? '';

        $data['judul'] = 'Penugasan Kru Onboard - ANVO Admin';
        $data['jadwal'] = $adminModel->getJadwalById($id_jadwal);
        $data['kru_assigned'] = $adminModel->getKruByJadwalFiltered($id_jadwal, $filterTanggal, $filterKeyword);
        $data['master_kru'] = $adminModel->getAllMasterKru();
        $data['filter_tanggal'] = $filterTanggal;
        $data['filter_keyword'] = $filterKeyword;

        $this->view('admin/jadwal_crew', $data);
    }

    public function tambah_crew() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if (!empty($_POST['tanggal_tugas'])) {
                if ($adminModel->syncPenugasanKru($_POST)) {
                    $_SESSION['success'] = 'Penugasan kru berhasil diperbarui!';
                } else {
                    $_SESSION['error'] = 'Gagal memperbarui penugasan kru.';
                }
            } else {
                $_SESSION['error'] = 'Tentukan tanggal tugas terlebih dahulu.';
            }
            header('Location: /anvo/public/jadwal/crew/' . $_POST['id_jadwal']);
            exit;
        }
    }

    // Endpoint AJAX untuk mencentang otomatis kru yang sudah bertugas pada tanggal tersebut
    public function get_kru_assigned_ajax($id_jadwal) {
        $tanggal = $_GET['tanggal'] ?? '';
        $adminModel = $this->model('AdminModel');
        $assignedIds = $adminModel->getKruAssignedByDate($id_jadwal, $tanggal);
        
        header('Content-Type: application/json');
        echo json_encode($assignedIds);
        exit;
    }

    public function hapus_crew($id_penugasan, $id_jadwal) {
        $adminModel = $this->model('AdminModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($adminModel->hapusPenugasanKru($id_penugasan)) {
            $_SESSION['success'] = 'Penugasan kru berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus penugasan kru.';
        }
        header('Location: /anvo/public/jadwal/crew/' . $id_jadwal);
        exit;
    }
}