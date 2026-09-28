<?php
// app/Controllers/ArmadaController.php

class ArmadaController extends Controller {

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
        $keretaModel = $this->model('Kereta');

        $data['judul'] = 'Manajemen Armada Kereta - ANVO Admin';
        $data['active_menu'] = 'armada';
        $data['armada_list'] = $keretaModel->getAllKereta();

        $this->renderAdminView('armada', $data);
    }

    // FUNGSI HELPER: Memproses JSON Blueprint dari Frontend
    private function processKomposisiPayload($postData) {
        $komposisi = json_decode($postData['komposisi_gerbong'] ?? '[]', true);
        $jenis_kelas = [];
        $total_kapasitas = 0;

        if (is_array($komposisi)) {
            foreach ($komposisi as $gerbong) {
                if (!in_array($gerbong['tipe_kelas'], $jenis_kelas)) {
                    $jenis_kelas[] = $gerbong['tipe_kelas'];
                }
                $total_kapasitas += (int)$gerbong['kapasitas'];
            }
        }

        $postData['jenis_kelas'] = $jenis_kelas;
        $postData['layout_kursi'] = $komposisi; // Akan di-json_encode lagi oleh model
        $postData['kapasitas_kursi'] = $total_kapasitas;

        return $postData;
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $keretaModel = $this->model('Kereta');
            $processedData = $this->processKomposisiPayload($_POST);
            
            if ($keretaModel->tambahKereta($processedData)) {
                $_SESSION['success'] = 'Armada kereta baru berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan armada kereta.';
            }
            header('Location: /anvo/public/admin/armada');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $keretaModel = $this->model('Kereta');
            $processedData = $this->processKomposisiPayload($_POST);
            
            if ($keretaModel->updateKereta($processedData)) {
                $_SESSION['success'] = 'Data armada kereta berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui armada.';
            }
            header('Location: /anvo/public/admin/armada');
            exit;
        }
    }

    public function hapus($id) {
        $keretaModel = $this->model('Kereta');
        
        if ($keretaModel->hapusKereta($id)) {
            $_SESSION['success'] = 'Armada kereta berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus armada.';
        }
        header('Location: /anvo/public/admin/armada');
        exit;
    }
}