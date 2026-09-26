<?php
class StasiunController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $adminModel = $this->model('AdminModel');

        $data['judul'] = 'Master Stasiun - ANVO Admin';
        $data['stasiun_list'] = $adminModel->getAllStasiun();

        $this->view('admin/stasiun_master', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if ($adminModel->tambahStasiun($_POST)) {
                $_SESSION['success'] = 'Data stasiun baru berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan stasiun.';
            }
            header('Location: /anvo/public/stasiun');
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model('AdminModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if ($adminModel->updateStasiun($id, $_POST)) {
                $_SESSION['success'] = 'Data stasiun berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui stasiun.';
            }
            header('Location: /anvo/public/stasiun');
            exit;
        }
    }

    public function hapus($id) {
        $adminModel = $this->model('AdminModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($adminModel->hapusStasiun($id)) {
            $_SESSION['success'] = 'Data stasiun berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus stasiun.';
        }
        header('Location: /anvo/public/stasiun');
        exit;
    }
}