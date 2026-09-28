<?php
// app/Controllers/JadwalController.php

class JadwalController extends Controller {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /anvo/public/auth/login');
            exit;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT role FROM users WHERE nik = :nik LIMIT 1");
        $stmt->execute([':nik' => $_SESSION['user_id']]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$currentUser || $currentUser['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak!';
            header('Location: /anvo/public/');
            exit;
        }
    }

    private function renderAdminView($viewName, $data = []) {
        $this->view('layouts/admin/header', $data);
        $this->view('layouts/admin/sidebar', $data);
        $this->view('admin/' . $viewName, $data);
    }
    
    public function index() {
        $adminModel = $this->model('AdminModel');
        $routeModel = $this->model('RouteModel'); 
        
        $tanggal = $_GET['tanggal'] ?? '';
        $kelas = $_GET['kelas'] ?? '';
        $asal = $_GET['asal'] ?? '';
        $tujuan = $_GET['tujuan'] ?? '';

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
        $data['active_menu'] = 'jadwal';
        $data['jadwal'] = $jadwal; 
        $data['kereta'] = $adminModel->getKeretaAktif();
        $data['stasiun'] = $adminModel->getAllStasiun();
        $data['koridor_list'] = $routeModel->getAllKoridor(); 
        $data['filter'] = compact('tanggal', 'kelas', 'asal', 'tujuan');

        $this->renderAdminView('jadwal', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            $transitVal = is_string($_POST['stasiun_transit'] ?? null) ? $_POST['stasiun_transit'] : json_encode($_POST['stasiun_transit'] ?? []);
            $_POST['stasiun_transit'] = $transitVal;
            
            if ($adminModel->tambahJadwal($_POST)) {
                $_SESSION['success'] = 'Jadwal operasional baru berhasil dibuat!';
            } else {
                $_SESSION['error'] = 'Gagal menyimpan jadwal operasional.';
            }
            header('Location: /anvo/public/admin/jadwal');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');
        if ($adminModel->hapusJadwal($id)) {
            $_SESSION['success'] = 'Jadwal berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus jadwal.';
        }
        header('Location: /anvo/public/admin/jadwal');
        exit;
    }

    public function edit_page($id_jadwal) {
        $adminModel = $this->model('AdminModel');
        $routeModel = $this->model('RouteModel');
        
        $data['judul'] = 'Edit Jadwal Operasional - ANVO Admin';
        $data['active_menu'] = 'jadwal';
        $data['jadwal_edit'] = $adminModel->getJadwalById($id_jadwal);
        $data['koridor_list'] = $routeModel->getAllKoridor();
        $data['stasiun'] = $adminModel->getAllStasiun();
        
        $this->renderAdminView('jadwal_edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            $transitVal = is_string($_POST['stasiun_transit'] ?? null) ? $_POST['stasiun_transit'] : json_encode($_POST['stasiun_transit'] ?? []);
            $_POST['stasiun_transit'] = $transitVal;
            
            if ($adminModel->updateJadwal($_POST)) {
                $_SESSION['success'] = 'Jadwal operasional berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui jadwal operasional.';
            }
            header('Location: /anvo/public/admin/jadwal');
            exit;
        }
    }

    public function tambah_page() {
        $routeModel = $this->model('RouteModel');
        $data['judul'] = 'Buat Jadwal Baru - ANVO Admin';
        $data['active_menu'] = 'jadwal';
        $data['koridor_list'] = $routeModel->getAllKoridor();
        
        $this->renderAdminView('jadwal_tambah', $data);
    }

    public function get_stasiun_ajax($id_koridor) {
        $routeModel = $this->model('RouteModel');
        header('Content-Type: application/json');
        echo json_encode($routeModel->getStasiunByKoridor($id_koridor));
        exit;
    }

    public function get_kereta_ajax($id_koridor) {
        $routeModel = $this->model('RouteModel');
        header('Content-Type: application/json');
        echo json_encode($routeModel->getKeretaByKoridor($id_koridor));
        exit;
    }

    public function crew($id_jadwal) {
        $adminModel = $this->model('AdminModel');
        $filterTanggal = $_GET['tanggal_tugas'] ?? '';
        $filterKeyword = $_GET['keyword'] ?? '';

        $data['judul'] = 'Penugasan Kru Onboard - ANVO Admin';
        $data['active_menu'] = 'jadwal';
        $data['jadwal'] = $adminModel->getJadwalById($id_jadwal);
        $data['kru_assigned'] = $adminModel->getKruByJadwalFiltered($id_jadwal, $filterTanggal, $filterKeyword);
        $data['master_kru'] = $adminModel->getAllMasterKru();
        $data['filter_tanggal'] = $filterTanggal;
        $data['filter_keyword'] = $filterKeyword;

        $this->renderAdminView('jadwal_crew', $data);
    }

    public function tambah_crew() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (!empty($_POST['tanggal_tugas'])) {
                if ($adminModel->syncPenugasanKru($_POST)) {
                    $_SESSION['success'] = 'Penugasan kru berhasil diperbarui!';
                } else {
                    $_SESSION['error'] = 'Gagal memperbarui penugasan kru.';
                }
            } else {
                $_SESSION['error'] = 'Tentukan tanggal tugas terlebih dahulu.';
            }
            header('Location: /anvo/public/admin/jadwal/crew/' . $_POST['id_jadwal']);
            exit;
        }
    }

    public function get_kru_assigned_ajax($id_jadwal) {
        $tanggal = $_GET['tanggal'] ?? '';
        $adminModel = $this->model('AdminModel');
        header('Content-Type: application/json');
        echo json_encode($adminModel->getKruAssignedByDate($id_jadwal, $tanggal));
        exit;
    }

    public function hapus_crew($id_penugasan, $id_jadwal) {
        $adminModel = $this->model('AdminModel');
        if ($adminModel->hapusPenugasanKru($id_penugasan)) {
            $_SESSION['success'] = 'Penugasan kru berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus penugasan kru.';
        }
        header('Location: /anvo/public/admin/jadwal/crew/' . $id_jadwal);
        exit;
    }
}