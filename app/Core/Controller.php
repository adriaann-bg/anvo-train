<?php
// app/Core/Controller.php

class Controller {
    // Fungsi untuk memanggil tampilan (UI)
    public function view($view, $data = []) {
        // Ekstrak array data menjadi variabel individu agar mudah dibaca di HTML
        extract($data); 
        require_once '../views/' . $view . '.php';
    }

    // Fungsi untuk memanggil kueri database
    public function model($model) {
        require_once '../app/Models/' . $model . '.php';
        return new $model;
    }
}
?>