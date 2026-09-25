<?php
// app/Controllers/UserController.php

class UserController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $adminModel = $this->model('AdminModel');

        $nama = $_GET['nama'] ?? '';
        $nik = $_GET['nik'] ?? '';
        $kontak = $_GET['kontak'] ?? '';
        $tanggal_lahir = $_GET['tanggal_lahir'] ?? '';

        $data['judul'] = 'Master User - ANVO Admin';
        $data['user_list'] = $adminModel->getAllUsers($nama, $nik, $kontak, $tanggal_lahir);
        
        $data['filter'] = [
            'nama' => $nama,
            'nik' => $nik,
            'kontak' => $kontak,
            'tanggal_lahir' => $tanggal_lahir
        ];

        $this->view('admin/user_master', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if ($adminModel->tambahUser($_POST)) {
                $_SESSION['success'] = 'Akun pengguna baru berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan pengguna.';
            }
            header('Location: /anvo/public/user');
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if ($adminModel->updateUser($id, $_POST)) {
                $_SESSION['success'] = 'Data pengguna berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui data pengguna.';
            }
            header('Location: /anvo/public/user');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($adminModel->hapusUser($id)) {
            $_SESSION['success'] = 'Akun pengguna berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pengguna.';
        }
        header('Location: /anvo/public/user');
        exit;
    }
}