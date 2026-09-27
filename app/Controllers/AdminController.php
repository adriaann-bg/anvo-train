<?php
// app/Controllers/AdminController.php

class AdminController extends Controller {

    // Konstruktor untuk memproteksi seluruh method di AdminController dari akses user biasa
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        // Cek apakah user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /anvo/public/auth/login');
            exit;
        }

        // Verifikasi role langsung ke database
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT role FROM users WHERE nik = :nik LIMIT 1");
        $stmt->execute([':nik' => $_SESSION['user_id']]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika bukan admin, tendang keluar ke beranda
        if (!$currentUser || $currentUser['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak! Halaman ini khusus untuk Administrator.';
            header('Location: /anvo/public/');
            exit;
        }
    }

    // Helper privat untuk memuat layout dan view admin secara konsisten
    private function renderAdminView($viewName, $data = []) {
        $this->view('layouts/admin/header', $data);
        $this->view('layouts/admin/sidebar', $data);
        $this->view('admin/' . $viewName, $data);
    }
    
    // Halaman Utama Admin (Dashboard)
    public function index() {
        $adminModel = $this->model('AdminModel');
        
        // Parameter Filter Dashboard
        $start_date = $_GET['start_date'] ?? date('Y-m-d');
        $end_date = $_GET['end_date'] ?? date('Y-m-d');
        $kelas = $_GET['kelas'] ?? '';
        $asal = $_GET['asal'] ?? '';
        $tujuan = $_GET['tujuan'] ?? '';

        $baseSchedules = $adminModel->getSchedulesOverlap($start_date, $end_date, $kelas, $asal, $tujuan);
        
        $dailyOperations = [];
        $currentDate = strtotime($start_date);
        $endDateUnix = strtotime($end_date);
        
        while ($currentDate <= $endDateUnix) {
            $dateStr = date('Y-m-d', $currentDate);
            
            foreach ($baseSchedules as $j) {
                $mulai = strtotime($j['tanggal_mulai']);
                $akhir = empty($j['tanggal_akhir']) ? PHP_INT_MAX : strtotime($j['tanggal_akhir']);
                
                if ($currentDate >= $mulai && $currentDate <= $akhir) {
                    $op = $j;
                    $op['tanggal_operasional'] = $dateStr;
                    $op['kru_list'] = $adminModel->getKruByJadwalAndDate($j['id_jadwal'], $dateStr);
                    $op['penumpang_list'] = $adminModel->getPenumpangsByJadwalAndDate($j['id_jadwal'], $dateStr);
                    $dailyOperations[] = $op;
                }
            }
            $currentDate = strtotime('+1 day', $currentDate);
        }

        usort($dailyOperations, function($a, $b) {
            $timeA = strtotime($a['tanggal_operasional'] . ' ' . $a['jam_berangkat']);
            $timeB = strtotime($b['tanggal_operasional'] . ' ' . $b['jam_berangkat']);
            
            if ($timeA == $timeB) { return 0; }
            return ($timeA < $timeB) ? -1 : 1;
        });

        $data['judul'] = 'Pusat Kendali Operasional - ANVO Admin';
        $data['active_menu'] = 'dashboard';
        $data['daily_operations'] = $dailyOperations;
        $data['kereta'] = $adminModel->getAllKereta();
        $data['stasiun'] = $adminModel->getAllStasiun();
        $data['total_penumpang'] = $adminModel->getTotalPenumpangCount();
        $data['total_kru_aktif'] = $adminModel->getTotalKruAktif();
        
        $data['filter'] = compact('start_date', 'end_date', 'kelas', 'asal', 'tujuan');

        $this->renderAdminView('index', $data);
    }

    // Menu: Kelola Jadwal
    public function jadwal() {
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

    // Menu: Kelola Rute Koridor
    public function route() {
        $routeModel = $this->model('RouteModel');
        $data['judul'] = 'Kelola Rute Koridor - ANVO Admin';
        $data['active_menu'] = 'route';
        $data['koridor_list'] = $routeModel->getAllKoridor();

        $this->renderAdminView('route', $data);
    }

    // Menu: Armada Kereta
    public function armada() {
        $keretaModel = $this->model('Kereta');
        $data['judul'] = 'Armada Kereta - ANVO Admin';
        $data['active_menu'] = 'armada';
        $data['armada_list'] = $keretaModel->getAllKereta();

        $this->renderAdminView('armada', $data);
    }

    // Menu: Data Stasiun
    public function stasiun() {
        $adminModel = $this->model('AdminModel');
        $data['judul'] = 'Data Stasiun - ANVO Admin';
        $data['active_menu'] = 'stasiun';
        $data['stasiun_list'] = $adminModel->getAllStasiun();

        $this->renderAdminView('stasiun_master', $data);
    }

    // Menu: Master User
    public function user() {
        $adminModel = $this->model('AdminModel');
        $nama = $_GET['nama'] ?? '';
        $nik = $_GET['nik'] ?? '';
        $kontak = $_GET['kontak'] ?? '';
        $tanggal_lahir = $_GET['tanggal_lahir'] ?? '';

        $data['judul'] = 'Master User - ANVO Admin';
        $data['active_menu'] = 'user';
        $data['user_list'] = $adminModel->getAllUsers($nama, $nik, $kontak, $tanggal_lahir);
        $data['filter'] = compact('nama', 'nik', 'kontak', 'tanggal_lahir');

        $this->renderAdminView('user_master', $data);
    }

    // Menu: Master Kru
    public function kru() {
        $adminModel = $this->model('AdminModel');
        $data['judul'] = 'Master Kru - ANVO Admin';
        $data['active_menu'] = 'kru';
        $data['kru_list'] = $adminModel->getAllMasterKru();

        $this->renderAdminView('kru_master', $data);
    }

    // Proses Tambah Jadwal
    public function tambah_jadwal() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            
            if (isset($_POST['stasiun_transit']) && is_string($_POST['stasiun_transit'])) {
                $transitVal = $_POST['stasiun_transit'];
            } else {
                $transitArr = $_POST['stasiun_transit'] ?? [];
                $transitVal = json_encode($transitArr);
            }
            $_POST['stasiun_transit'] = $transitVal;
            
            if ($adminModel->tambahJadwal($_POST)) {
                $_SESSION['success'] = 'Jadwal kereta cepat berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan jadwal.';
            }
            header('Location: /anvo/public/admin/jadwal');
            exit;
        }
    }

    // Proses Hapus Jadwal
    public function hapus_jadwal($id) {
        $adminModel = $this->model('AdminModel');
        if ($adminModel->hapusJadwal($id)) {
            $_SESSION['success'] = 'Jadwal berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus jadwal.';
        }
        header('Location: /anvo/public/admin/jadwal');
        exit;
    }
}