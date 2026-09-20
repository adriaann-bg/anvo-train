<?php
// app/Controllers/JadwalController.php

class JadwalController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        $adminModel = $this->model('AdminModel');
        $routeModel = $this->model('RouteModel'); 
        
        $tanggal = $_GET['tanggal'] ?? '';
        $kelas = $_GET['kelas'] ?? '';
        $rute = $_GET['rute'] ?? '';

        // 1. Ambil jadwal dari database
        $jadwal = $adminModel->getFilteredJadwal($tanggal, $kelas, $rute);
        
        // 2. TAMBAHAN: Ambil data stasiun full per koridor untuk mencari stasiun yang di-skip
        foreach ($jadwal as &$j) {
            if (!empty($j['id_koridor'])) {
                $koridor_stasiun = $routeModel->getStasiunByKoridor($j['id_koridor']);
                // Ekstrak hanya nama stasiunnya menjadi array flat
                $j['full_stasiun'] = array_column($koridor_stasiun, 'nama_stasiun');
            } else {
                $j['full_stasiun'] = [];
            }
        }

        $data['judul'] = 'Kelola Jadwal - ANVO Admin';
        $data['jadwal'] = $jadwal; // Masukkan jadwal yang sudah di-inject full_stasiun
        $data['kereta'] = $adminModel->getKeretaAktif();
        $data['stasiun'] = $adminModel->getAllStasiun();
        $data['koridor_list'] = $routeModel->getAllKoridor(); 
        
        $data['filter'] = ['tanggal' => $tanggal, 'kelas' => $kelas, 'rute' => $rute];

        $this->view('admin/jadwal', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }
            
            // Tangkap array stasiun_transit dari checkbox (peta interaktif)
            $transitArr = isset($_POST['stasiun_transit']) ? $_POST['stasiun_transit'] : [];
            
            // Masukkan data transit (dalam bentuk JSON) ke dalam array POST utama
            $_POST['stasiun_transit'] = json_encode($transitArr);
            
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
}