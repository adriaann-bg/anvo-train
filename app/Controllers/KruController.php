<?php
// app/Controllers/KruController.php

class KruController extends Controller {

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

        $data['judul'] = 'Master Kru - ANVO Admin';
        $data['active_menu'] = 'kru';
        $data['kru_list'] = $adminModel->getAllMasterKru();

        $this->renderAdminView('kru_master', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');

            $fotoName = 'default-kru.png';
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = $_FILES['foto']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = 'kru_' . time() . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../../public/img/kru/';
                    if (!is_dir($uploadFileDir)) { mkdir($uploadFileDir, 0755, true); }
                    
                    if(move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                        $fotoName = $newFileName;
                    }
                }
            }

            if ($adminModel->tambahKru($_POST, $fotoName)) {
                $_SESSION['success'] = 'Data kru baru berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan kru (NIP/NIK sudah terdaftar).';
            }
            header('Location: /anvo/public/admin/kru');
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');

            $fotoName = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = $_FILES['foto']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = 'kru_' . time() . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../../public/img/kru/';
                    if (!is_dir($uploadFileDir)) { mkdir($uploadFileDir, 0755, true); }
                    
                    if(move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                        $fotoName = $newFileName;
                    }
                }
            }

            if ($adminModel->updateKru($id, $_POST, $fotoName)) {
                $_SESSION['success'] = 'Data kru berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui data kru.';
            }
            // Diperbarui agar mengarah ke jalur admin
            header('Location: /anvo/public/admin/kru');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');

        if ($adminModel->hapusKru($id)) {
            $_SESSION['success'] = 'Data kru berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus kru.';
        }
        // Diperbarui agar mengarah ke jalur admin
        header('Location: /anvo/public/admin/kru');
        exit;
    }
}