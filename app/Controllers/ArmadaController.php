<?php
// app/Controllers/ArmadaController.php

class ArmadaController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $keretaModel = $this->model('Kereta');

        $data['judul'] = 'Manajemen Armada Kereta - ANVO Admin';
        $data['armada_list'] = $keretaModel->getAllKereta();

        $this->view('admin/armada', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $keretaModel = $this->model('Kereta');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if ($keretaModel->tambahKereta($_POST)) {
                $_SESSION['success'] = 'Armada kereta baru berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan armada kereta.';
            }
            header('Location: /anvo/public/armada');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $keretaModel = $this->model('Kereta');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if ($keretaModel->updateKereta($_POST)) {
                $_SESSION['success'] = 'Data armada kereta berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui armada.';
            }
            header('Location: /anvo/public/armada');
            exit;
        }
    }

    public function hapus($id) {
        $keretaModel = $this->model('Kereta');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($keretaModel->hapusKereta($id)) {
            $_SESSION['success'] = 'Armada kereta berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus armada.';
        }
        header('Location: /anvo/public/armada');
        exit;
    }
}