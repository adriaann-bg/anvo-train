<?php
// app/Controllers/JadwalController.php

class JadwalController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        $adminModel = $this->model('AdminModel');
        $routeModel = $this->model('RouteModel'); // Panggil RouteModel untuk data koridor
        
        $tanggal = $_GET['tanggal'] ?? '';
        $kelas = $_GET['kelas'] ?? '';
        $rute = $_GET['rute'] ?? '';

        $data['judul'] = 'Kelola Jadwal - ANVO Admin';
        $data['jadwal'] = $adminModel->getFilteredJadwal($tanggal, $kelas, $rute);
        $data['kereta'] = $adminModel->getKeretaAktif();
        $data['stasiun'] = $adminModel->getAllStasiun();
        $data['koridor_list'] = $routeModel->getAllKoridor(); // Ambil daftar koridor
        
        $data['filter'] = ['tanggal' => $tanggal, 'kelas' => $kelas, 'rute' => $rute];

        $this->view('admin/jadwal', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }
            
            if ($adminModel->tambahJadwal($_POST)) {
                $_SESSION['success'] = 'Jadwal kereta berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan jadwal.';
            }
            header('Location: /anvo/public/jadwal');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($adminModel->hapusJadwal($id)) {
            $_SESSION['success'] = 'Jadwal berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus jadwal.';
        }
        
        header('Location: /anvo/public/jadwal');
        exit;
    }
}