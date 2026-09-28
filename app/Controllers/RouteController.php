<?php
// app/Controllers/RouteController.php

class RouteController extends Controller {

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
        $routeModel = $this->model('RouteModel');
        $adminModel = $this->model('AdminModel');

        $data['judul'] = 'Kelola Rute Koridor - ANVO Admin';
        $data['active_menu'] = 'route';
        $data['koridor'] = $routeModel->getAllKoridor();
        $data['stasiun_list'] = $adminModel->getAllStasiun();

        $this->renderAdminView('route', $data);
    }

    public function detail($id_koridor) {
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

    public function tambah_koridor() {
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

    // FUNGSI BARU: Edit Nama & Deskripsi Koridor
    public function edit_koridor($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
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

    // FUNGSI BARU: Hapus Koridor
    public function hapus_koridor($id_koridor) {
        $db = Database::getInstance()->getConnection();
        // Hapus relasi terkait jika ada, atau hapus langsung koridornya
        $stmt = $db->prepare("DELETE FROM koridor WHERE id_koridor = :id");
        if ($stmt->execute([':id' => $id_koridor])) {
            $_SESSION['success'] = 'Koridor berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus koridor.';
        }
        header('Location: /anvo/public/admin/route');
        exit;
    }

    public function tambah_stasiun_koridor($id_koridor) {
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

    public function hapus_stasiun($id_koridor, $id_koridor_stasiun) {
        $routeModel = $this->model('RouteModel');
        if ($routeModel->hapusStasiunKoridor($id_koridor_stasiun)) {
            $_SESSION['success'] = 'Stasiun dihapus dari jalur.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus stasiun.';
        }
        header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
        exit;
    }

    public function edit_stasiun($id_koridor) {
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

    public function simpan_urutan() {
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

    public function toggle_status($id_koridor) {
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

    public function tambah_kereta_koridor($id_koridor) {
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

    public function lepas_kereta_koridor($id_koridor, $id_kereta) {
        $routeModel = $this->model('RouteModel');
        if ($routeModel->lepasKereta($id_kereta)) {
            $_SESSION['success'] = 'Armada dikembalikan ke pool.';
        } else {
            $_SESSION['error'] = 'Gagal melepas armada.';
        }
        header('Location: /anvo/public/admin/route/detail/' . $id_koridor);
        exit;
    }
}