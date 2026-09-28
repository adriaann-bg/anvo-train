<?php
// app/Controllers/StasiunController.php

class StasiunController extends Controller {

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

        $data['judul'] = 'Master Stasiun - ANVO Admin';
        $data['active_menu'] = 'stasiun';
        $data['stasiun_list'] = $adminModel->getAllStasiun();

        $this->renderAdminView('stasiun_master', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');

            if ($adminModel->tambahStasiun($_POST)) {
                $_SESSION['success'] = 'Data stasiun baru berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan stasiun.';
            }
            header('Location: /anvo/public/admin/stasiun');
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');

            if ($adminModel->updateStasiun($id, $_POST)) {
                $_SESSION['success'] = 'Data stasiun berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui stasiun.';
            }
            // Diperbarui agar mengarah ke jalur admin
            header('Location: /anvo/public/admin/stasiun');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');

        if ($adminModel->hapusStasiun($id)) {
            $_SESSION['success'] = 'Data stasiun berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus stasiun.';
        }
        // Diperbarui agar mengarah ke jalur admin
        header('Location: /anvo/public/admin/stasiun');
        exit;
    }
}