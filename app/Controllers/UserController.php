<?php
// app/Controllers/UserController.php

class UserController extends Controller {

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

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');

            if ($adminModel->tambahUser($_POST)) {
                $_SESSION['success'] = 'Akun pengguna baru berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan pengguna.';
            }
            header('Location: /anvo/public/admin/user');
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');

            if ($adminModel->updateUser($id, $_POST)) {
                $_SESSION['success'] = 'Data pengguna berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui data pengguna.';
            }
            // Diperbarui agar mengarah ke jalur admin
            header('Location: /anvo/public/admin/user');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');

        if ($adminModel->hapusUser($id)) {
            $_SESSION['success'] = 'Akun pengguna berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pengguna.';
        }
        // Diperbarui agar mengarah ke jalur admin
        header('Location: /anvo/public/admin/user');
        exit;
    }
}