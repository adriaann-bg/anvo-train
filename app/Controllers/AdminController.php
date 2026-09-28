<?php
// app/Controllers/AdminController.php

class AdminController extends Controller {

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
            $_SESSION['error'] = 'Akses ditolak! Halaman ini khusus untuk Administrator.';
            header('Location: /anvo/public/');
            exit;
        }
    }

    private function renderAdminView($viewName, $data = []) {
        $this->view('layouts/admin/header', $data);
        $this->view('layouts/admin/sidebar', $data);
        $this->view('admin/' . $viewName, $data);
    }
    
    // Dashboard Admin
    public function index() {
        $adminModel = $this->model('AdminModel');
        $routeModel = $this->model('RouteModel');
        
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
                    
                    // Ambil urutan stasiun dari koridor
                    $op['koridor_stasiun'] = !empty($j['id_koridor']) ? $routeModel->getStasiunByKoridor($j['id_koridor']) : [];
                    
                    // Parse stasiun transit untuk memisahkan stasiun berhenti vs dilewati beserta jam-nya
                    $transit = json_decode($j['stasiun_transit'], true);
                    if (is_string($transit)) { $transit = json_decode($transit, true); }
                    
                    $stoppingStations = [];
                    $passedStations = [];
                    
                    if (is_array($transit)) {
                        foreach ($transit as $t) {
                            $namaSt = is_array($t) ? ($t['nama'] ?? '') : $t;
                            $statusSt = is_array($t) ? ($t['status'] ?? 'Transit') : 'Transit';
                            $jamSt = is_array($t) ? ($t['jam'] ?? $t['waktu'] ?? $t['jam_datang'] ?? $t['jam_berangkat'] ?? '-') : '-';
                            
                            $statusLower = strtolower($statusSt);
                            if (strpos($statusLower, 'dilewati') !== false || strpos($statusLower, 'langsung') !== false) {
                                $passedStations[] = [
                                    'nama' => $namaSt,
                                    'status' => $statusSt,
                                    'jam' => $jamSt
                                ];
                            } else {
                                $stoppingStations[] = [
                                    'nama' => $namaSt,
                                    'status' => $statusSt,
                                    'jam' => $jamSt
                                ];
                            }
                        }
                    } else {
                        $stoppingStations = [
                            ['nama' => $j['stasiun_asal'], 'status' => 'Berangkat', 'jam' => $j['jam_berangkat']],
                            ['nama' => $j['stasiun_tujuan'], 'status' => 'Tiba', 'jam' => $j['jam_tiba']]
                        ];
                    }
                    $op['stopping_stations'] = $stoppingStations;
                    $op['passed_stations'] = $passedStations;
                    
                    // Kalkulasi kapasitas per kelas dari layout gerbong kereta
                    $layoutArr = json_decode($j['layout_kursi'] ?? '[]', true);
                    $kelasKapasitas = [];
                    if (is_array($layoutArr)) {
                        foreach ($layoutArr as $gerbong) {
                            $tipe = $gerbong['tipe_kelas'] ?? 'Standard';
                            $kap = (int)($gerbong['kapasitas'] ?? 0);
                            if (!isset($kelasKapasitas[$tipe])) { $kelasKapasitas[$tipe] = 0; }
                            $kelasKapasitas[$tipe] += $kap;
                        }
                    }
                    $op['kelas_kapasitas'] = $kelasKapasitas;

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
        $data['stasiun'] = $adminModel->getAllStasiun() ?? [];
        $data['total_penumpang'] = $adminModel->getTotalPenumpangCount();
        $data['total_kru_aktif'] = $adminModel->getTotalKruAktif();
        $data['filter'] = compact('start_date', 'end_date', 'kelas', 'asal', 'tujuan');

        $this->renderAdminView('index', $data);
    }

    // ========================================================
    // MENU KELOLA JADWAL & SUB-ROUTING
    // ========================================================
    public function jadwal($action = '', $param1 = '', $param2 = '') {
        if ($action === 'tambah_page') return $this->tambah_page();
        if ($action === 'tambah') return $this->tambah();
        if ($action === 'edit_page') return $this->edit_page($param1);
        if ($action === 'update') return $this->update();
        if ($action === 'hapus') return $this->hapus($param1);
        if ($action === 'crew') return $this->crew($param1);
        if ($action === 'tambah_crew') return $this->tambah_crew();
        if ($action === 'hapus_crew') return $this->hapus_crew($param1, $param2);
        if ($action === 'get_stasiun_ajax') return $this->get_stasiun_ajax($param1);
        if ($action === 'get_kereta_ajax') return $this->get_kereta_ajax($param1);
        if ($action === 'get_kru_assigned_ajax') return $this->get_kru_assigned_ajax($param1);

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

    private function tambah() {
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

    private function hapus($id) {
        $adminModel = $this->model('AdminModel');
        if ($adminModel->hapusJadwal($id)) {
            $_SESSION['success'] = 'Jadwal berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus jadwal.';
        }
        header('Location: /anvo/public/admin/jadwal');
        exit;
    }

    private function edit_page($id_jadwal) {
        $adminModel = $this->model('AdminModel');
        $routeModel = $this->model('RouteModel');
        
        $data['judul'] = 'Edit Jadwal Operasional - ANVO Admin';
        $data['active_menu'] = 'jadwal';
        $data['jadwal_edit'] = $adminModel->getJadwalById($id_jadwal);
        $data['koridor_list'] = $routeModel->getAllKoridor();
        $data['stasiun'] = $adminModel->getAllStasiun();
        
        $this->renderAdminView('jadwal_edit', $data);
    }

    private function update() {
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

    private function tambah_page() {
        $routeModel = $this->model('RouteModel');
        $data['judul'] = 'Buat Jadwal Baru - ANVO Admin';
        $data['active_menu'] = 'jadwal';
        $data['koridor_list'] = $routeModel->getAllKoridor();
        
        $this->renderAdminView('jadwal_tambah', $data);
    }

    private function get_stasiun_ajax($id_koridor) {
        $routeModel = $this->model('RouteModel');
        header('Content-Type: application/json');
        echo json_encode($routeModel->getStasiunByKoridor($id_koridor));
        exit;
    }

    private function get_kereta_ajax($id_koridor) {
        $routeModel = $this->model('RouteModel');
        header('Content-Type: application/json');
        echo json_encode($routeModel->getKeretaByKoridor($id_koridor));
        exit;
    }

    private function crew($id_jadwal) {
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

    private function tambah_crew() {
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

    private function get_kru_assigned_ajax($id_jadwal) {
        $tanggal = $_GET['tanggal'] ?? '';
        $adminModel = $this->model('AdminModel');
        header('Content-Type: application/json');
        echo json_encode($adminModel->getKruAssignedByDate($id_jadwal, $tanggal));
        exit;
    }

    private function hapus_crew($id_penugasan, $id_jadwal) {
        $adminModel = $this->model('AdminModel');
        if ($adminModel->hapusPenugasanKru($id_penugasan)) {
            $_SESSION['success'] = 'Penugasan kru berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus penugasan kru.';
        }
        header('Location: /anvo/public/admin/jadwal/crew/' . $id_jadwal);
        exit;
    }
    // ========================================================
    // END KELOLA JADWAL
    // ========================================================


    // ========================================================
    // MENU KELOLA RUTE KORIDOR & SUB-ROUTING (Pusat Perbaikan)
    // ========================================================
    public function route($action = '', $param1 = '', $param2 = '') {
        if ($action === 'detail') return $this->route_detail($param1);
        if ($action === 'tambah_koridor') return $this->route_tambah_koridor();
        if ($action === 'edit_koridor') return $this->route_edit_koridor($param1);
        if ($action === 'hapus_koridor') return $this->route_hapus_koridor($param1);
        if ($action === 'tambah_stasiun_koridor') return $this->route_tambah_stasiun_koridor($param1);
        if ($action === 'hapus_stasiun') return $this->route_hapus_stasiun($param1, $param2);
        if ($action === 'edit_stasiun') return $this->route_edit_stasiun($param1);
        if ($action === 'simpan_urutan') return $this->route_simpan_urutan();
        if ($action === 'toggle_status') return $this->route_toggle_status($param1);
        if ($action === 'tambah_kereta_koridor') return $this->route_tambah_kereta_koridor($param1);
        if ($action === 'lepas_kereta_koridor') return $this->route_lepas_kereta_koridor($param1, $param2);

        $routeModel = $this->model('RouteModel');
        $adminModel = $this->model('AdminModel');
        $data['judul'] = 'Kelola Rute Koridor - ANVO Admin';
        $data['active_menu'] = 'route';
        $data['koridor'] = $routeModel->getAllKoridor();
        $data['stasiun_list'] = $adminModel->getAllStasiun();
        $this->renderAdminView('route', $data);
    }

    private function route_detail($id_koridor) {
        $routeModel = $this->model('RouteModel');
        $koridorList = $routeModel->getAllKoridor();
        $currentKoridor = null;
        
        foreach($koridorList as $k) {
            if ($k['id_koridor'] == $id_koridor) { $currentKoridor = $k; break; }
        }

        if (!$currentKoridor) {
            header('Location: /anvo/public/admin/route');
            exit;
        }

        $data['judul'] = 'Detail Koridor: ' . $currentKoridor['nama_koridor'];
        $data['active_menu'] = 'route';
        $data['koridor'] = $currentKoridor;
        $data['stasiun_terdaftar'] = $routeModel->getStasiunByKoridor($id_koridor);
        $data['stasiun_tersedia'] = $routeModel->getStasiunTersedia($id_koridor);
        $data['kereta_berdinas'] = $routeModel->getKeretaByKoridor($id_koridor);
        $data['kereta_tersedia'] = $routeModel->getKeretaTersedia();

        $this->renderAdminView('route_detail', $data);
    }

    private function route_tambah_koridor() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            if ($routeModel->tambahKoridor($_POST['nama_koridor'], $_POST['keterangan'])) {
                $_SESSION['success'] = 'Koridor berhasil dibuat!';
            } else {
                $_SESSION['error'] = 'Gagal membuat koridor.';
            }
            header('Location: /anvo/public/admin/route');
            exit;
        }
    }

    private function route_edit_koridor($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE koridor SET nama_koridor = :nama, keterangan = :ket WHERE id_koridor = :id");
            if ($stmt->execute([':nama' => $_POST['nama_koridor'], ':ket' => $_POST['keterangan'], ':id' => $id_koridor])) {
                $_SESSION['success'] = 'Informasi koridor berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui koridor.';
            }
            header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
            exit;
        }
    }

    private function route_hapus_koridor($id_koridor) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM koridor WHERE id_koridor = :id");
        if ($stmt->execute([':id' => $id_koridor])) {
            $_SESSION['success'] = 'Koridor berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus koridor.';
        }
        header('Location: /anvo/public/admin/route');
        exit;
    }

    private function route_tambah_stasiun_koridor($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            if ($routeModel->tambahStasiunKoridorOtomatis($id_koridor, $_POST['nama_stasiun'])) {
                $_SESSION['success'] = 'Stasiun berhasil ditambahkan ke jalur!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan stasiun.';
            }
            header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
            exit;
        }
    }

    private function route_hapus_stasiun($id_koridor, $id_koridor_stasiun) {
        $routeModel = $this->model('RouteModel');
        if ($routeModel->hapusStasiunKoridor($id_koridor_stasiun)) {
            $_SESSION['success'] = 'Stasiun dihapus dari jalur.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus stasiun.';
        }
        header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
        exit;
    }

    private function route_edit_stasiun($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            if ($routeModel->editStasiunKoridor($_POST['id_koridor_stasiun'], $_POST['nama_stasiun'])) {
                $_SESSION['success'] = 'Urutan stasiun berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui stasiun.';
            }
            header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
            exit;
        }
    }

    private function route_simpan_urutan() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            $input = json_decode(file_get_contents('php://input'), true);
            if (!empty($input['urutan'])) {
                $routeModel->updateUrutanKoridor($input['urutan']);
                echo json_encode(['status' => 'success', 'message' => 'Urutan jalur berhasil disimpan permanen!']);
                exit;
            }
            echo json_encode(['status' => 'error', 'message' => 'Data urutan kosong.']);
            exit;
        }
    }

    private function route_toggle_status($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            if ($routeModel->toggleStatusKoridor($id_koridor, $_POST['status_koridor'])) {
                $_SESSION['success'] = 'Status koridor berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui status koridor.';
            }
            header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
            exit;
        }
    }

    private function route_tambah_kereta_koridor($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id_kereta'])) {
            $routeModel = $this->model('RouteModel');
            if ($routeModel->tugaskanKereta($id_koridor, $_POST['id_kereta'])) {
                $_SESSION['success'] = 'Armada berhasil ditugaskan ke koridor!';
            } else {
                $_SESSION['error'] = 'Gagal menugaskan armada.';
            }
            header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
            exit;
        }
    }

    private function route_lepas_kereta_koridor($id_koridor, $id_kereta) {
        $routeModel = $this->model('RouteModel');
        if ($routeModel->lepasKereta($id_kereta)) {
            $_SESSION['success'] = 'Armada dikembalikan ke pool.';
        } else {
            $_SESSION['error'] = 'Gagal melepas armada.';
        }
        header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
        exit;
    }
    // ========================================================
    // END KELOLA RUTE KORIDOR
    // ========================================================


    // Menu lain (Armada, Stasiun, User, Kru)
    // ========================================================
    // MENU KELOLA ARMADA KERETA & SUB-ROUTING
    // ========================================================
    public function armada($action = '', $param1 = '') {
        if ($action === 'tambah') return $this->armada_tambah();
        if ($action === 'update') return $this->armada_update();
        if ($action === 'hapus') return $this->armada_hapus($param1);

        $keretaModel = $this->model('Kereta');
        $data['judul'] = 'Manajemen Armada Kereta - ANVO Admin';
        $data['active_menu'] = 'armada';
        $data['armada_list'] = $keretaModel->getAllKereta();
        $this->renderAdminView('armada', $data);
    }

    // Fungsi Helper: Memproses JSON Blueprint dari Frontend
    private function processKomposisiPayload($postData) {
        $komposisi = json_decode($postData['komposisi_gerbong'] ?? '[]', true);
        $jenis_kelas = [];
        $total_kapasitas = 0;

        if (is_array($komposisi)) {
            foreach ($komposisi as $gerbong) {
                if (!in_array($gerbong['tipe_kelas'], $jenis_kelas)) {
                    $jenis_kelas[] = $gerbong['tipe_kelas'];
                }
                $total_kapasitas += (int)$gerbong['kapasitas'];
            }
        }

        $postData['jenis_kelas'] = $jenis_kelas;
        $postData['layout_kursi'] = $komposisi; // Akan di-json_encode oleh Model Kereta
        $postData['kapasitas_kursi'] = $total_kapasitas;

        return $postData;
    }

    private function armada_tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $keretaModel = $this->model('Kereta');
            $processedData = $this->processKomposisiPayload($_POST);
            
            if ($keretaModel->tambahKereta($processedData)) {
                $_SESSION['success'] = 'Blueprint formasi armada baru berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan armada kereta.';
            }
            header('Location: /anvo/public/admin/armada');
            exit;
        }
    }

    private function armada_update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $keretaModel = $this->model('Kereta');
            $processedData = $this->processKomposisiPayload($_POST);
            
            if ($keretaModel->updateKereta($processedData)) {
                $_SESSION['success'] = 'Spesifikasi & formasi armada berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui armada.';
            }
            header('Location: /anvo/public/admin/armada');
            exit;
        }
    }

    private function armada_hapus($id) {
        $keretaModel = $this->model('Kereta');
        
        if ($keretaModel->hapusKereta($id)) {
            $_SESSION['success'] = 'Armada kereta beserta blueprint layoutnya berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus armada.';
        }
        header('Location: /anvo/public/admin/armada');
        exit;
    }
    // ========================================================
    // END KELOLA ARMADA
    // ========================================================

    public function stasiun() {
        $adminModel = $this->model('AdminModel');
        $data['judul'] = 'Data Stasiun - ANVO Admin';
        $data['active_menu'] = 'stasiun';
        $data['stasiun_list'] = $adminModel->getAllStasiun();
        $this->renderAdminView('stasiun_master', $data);
    }

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

    public function kru() {
        $adminModel = $this->model('AdminModel');
        $data['judul'] = 'Master Kru - ANVO Admin';
        $data['active_menu'] = 'kru';
        $data['kru_list'] = $adminModel->getAllMasterKru();
        $this->renderAdminView('kru_master', $data);
    }
}