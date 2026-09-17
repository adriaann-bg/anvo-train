<?php
// app/Controllers/AdminController.php

class AdminController extends Controller {

    // Halaman Utama Admin (Dashboard / Kelola Jadwal)
    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        $adminModel = $this->model('AdminModel');
        $data['judul'] = 'Panel Admin - Kelola Jadwal ANVO';
        $data['jadwal'] = $adminModel->getAllJadwal();
        $data['kereta'] = $adminModel->getAllKereta();
        $data['stasiun'] = $adminModel->getAllStasiun();

        $this->view('admin/index', $data);
    }

    // Proses Tambah Jadwal
    public function tambah_jadwal() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            
            if ($adminModel->tambahJadwal($_POST)) {
                if (session_status() == PHP_SESSION_NONE) { session_start(); }
                $_SESSION['success'] = 'Jadwal kereta cepat berhasil ditambahkan!';
                header('Location: /anvo/public/admin');
                exit;
            } else {
                if (session_status() == PHP_SESSION_NONE) { session_start(); }
                $_SESSION['error'] = 'Gagal menambahkan jadwal.';
                header('Location: /anvo/public/admin');
                exit;
            }
        }
    }

    // Proses Hapus Jadwal
    public function hapus_jadwal($id) {
        $adminModel = $this->model('AdminModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($adminModel->hapusJadwal($id)) {
            $_SESSION['success'] = 'Jadwal berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus jadwal.';
        }
        
        header('Location: /anvo/public/admin');
        exit;
    }
}