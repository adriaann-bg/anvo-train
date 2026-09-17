<?php
// app/Controllers/RouteController.php

class RouteController extends Controller {

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $routeModel = $this->model('RouteModel');
        $adminModel = $this->model('AdminModel');

        $data['judul'] = 'Kelola Rute Koridor - ANVO Admin';
        $data['koridor'] = $routeModel->getAllKoridor();
        $data['stasiun_list'] = $adminModel->getAllStasiun();

        $this->view('admin/route', $data);
    }

    public function detail($id_koridor) {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $routeModel = $this->model('RouteModel');

        $koridorList = $routeModel->getAllKoridor();
        $currentKoridor = null;
        foreach($koridorList as $k) {
            if ($k['id_koridor'] == $id_koridor) { $currentKoridor = $k; break; }
        }

        if (!$currentKoridor) {
            header('Location: /anvo/public/route');
            exit;
        }

        $data['judul'] = 'Detail Koridor: ' . $currentKoridor['nama_koridor'];
        $data['koridor'] = $currentKoridor;
        $data['stasiun_terdaftar'] = $routeModel->getStasiunByKoridor($id_koridor);
        // Ambil stasiun yang belum diinput saja
        $data['stasiun_tersedia'] = $routeModel->getStasiunTersedia($id_koridor);

        $this->view('admin/route_detail', $data);
    }

    public function tambah_koridor() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if ($routeModel->tambahKoridor($_POST['nama_koridor'], $_POST['keterangan'])) {
                $_SESSION['success'] = 'Koridor berhasil dibuat!';
            } else {
                $_SESSION['error'] = 'Gagal membuat koridor.';
            }
            header('Location: /anvo/public/route');
            exit;
        }
    }

    public function tambah_stasiun_koridor($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            if ($routeModel->tambahStasiunKoridorOtomatis($id_koridor, $_POST['nama_stasiun'])) {
                $_SESSION['success'] = 'Stasiun berhasil ditambahkan ke jalur!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan stasiun.';
            }
            header('Location: /anvo/public/route/detail/' . $id_koridor);
            exit;
        }
    }

    public function hapus_stasiun($id_koridor, $id_koridor_stasiun) {
        $routeModel = $this->model('RouteModel');
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($routeModel->hapusStasiunKoridor($id_koridor_stasiun)) {
            $_SESSION['success'] = 'Stasiun dihapus dari jalur.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus stasiun.';
        }
        header('Location: /anvo/public/route/detail/' . $id_koridor);
        exit;
    }

    public function edit_stasiun($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            $id_koridor_stasiun = $_POST['id_koridor_stasiun'];
            $nama_stasiun_baru = $_POST['nama_stasiun'];

            if ($routeModel->editStasiunKoridor($id_koridor_stasiun, $nama_stasiun_baru)) {
                $_SESSION['success'] = 'Urutan stasiun berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui stasiun.';
            }
            header('Location: /anvo/public/route/detail/' . $id_koridor);
            exit;
        }
    }

    public function simpan_urutan() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!empty($input['urutan'])) {
                $routeModel->updateUrutanKoridor($input['urutan']);
                echo json_encode(['status' => 'success', 'message' => 'Urutan jalur berhasil disimpan permanen!']);
                exit;
            }
            echo json_encode(['status' => 'error', 'message' => 'Data urutan kosong.']);
            exit;
        }
    }

    public function toggle_status($id_koridor) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeModel = $this->model('RouteModel');
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            $status_baru = $_POST['status_koridor'];
            if ($routeModel->toggleStatusKoridor($id_koridor, $status_baru)) {
                $_SESSION['success'] = 'Status koridor berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui status koridor.';
            }
            header('Location: /anvo/public/route/detail/' . $id_koridor);
            exit;
        }
    }
}