<?php
// app/Controllers/KruController.php

class KruController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $adminModel = $this->model('AdminModel');

        $data['judul'] = 'Master Kru - ANVO Admin';
        $data['kru_list'] = $adminModel->getAllMasterKru();

        $this->view('admin/kru_master', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            // Handle Upload Foto
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
            header('Location: /anvo/public/kru');
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

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
            header('Location: /anvo/public/kru');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($adminModel->hapusKru($id)) {
            $_SESSION['success'] = 'Data kru berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus kru.';
        }
        header('Location: /anvo/public/kru');
        exit;
    }
}