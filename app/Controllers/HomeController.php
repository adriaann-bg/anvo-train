<?php
// app/Controllers/HomeController.php

class HomeController extends Controller {
    public function index() {
        $data['judul'] = 'ANVO - Menghubungkan Jarak, Memanjakan Perjalanan';
        
        // 1. Instansiasi Model
        $stasiunModel = $this->model('Stasiun');
        $keretaModel = $this->model('Kereta');
        
        // 2. Ambil data dari database
        $data['stasiuns'] = $stasiunModel->getAllStasiun();
        $data['kelas_kereta']  = $keretaModel->getKelasKereta();
        $data['top_destinasi'] = $stasiunModel->getTopDestinasi(); // Data baru untuk Carousel
        
        // 3. Kirim data ke View
        $this->view('layouts/header', $data);
        $this->view('home/index', $data);
        $this->view('layouts/footer');
    }
}
?>