<?php
// app/Controllers/UserController.php

class UserController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $adminModel = $this->model('AdminModel');

        $data['judul'] = 'Master User - ANVO Admin';
        $data['user_list'] = $adminModel->getAllUsers();

        $this->view('admin/user_master', $data);
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