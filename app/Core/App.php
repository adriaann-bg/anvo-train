<?php
// app/Core/App.php

class App {
    protected $controller = 'HomeController'; // Controller bawaan (Landing Page)
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();

        // 1. Cek apakah file Controller ada di folder app/Controllers/
        if (isset($url[0]) && file_exists('../app/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once '../app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Cek apakah Method (fungsi) ada di dalam Controller tersebut
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Ambil sisa URL sebagai parameter (misal ID jadwal)
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // 4. Eksekusi Controller dan Method beserta parameternya
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
?>