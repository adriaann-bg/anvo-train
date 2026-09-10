<?php
// app/Models/UserModel.php

class UserModel {
    private $db;

    public function __construct() {
        // Asumsi kamu sudah punya class Database pembungkus PDO bawaan MVC-mu
        $this->db = new Database(); 
    }

    // 1. Fungsi Cek Duplikat (NIK, Email, No HP)
    public function checkDuplicate($nik, $email, $no_hp) {
        $this->db->query("SELECT id FROM users WHERE nik = :nik OR email = :email OR no_hp = :no_hp");
        $this->db->bind(':nik', $nik);
        $this->db->bind(':email', $email);
        $this->db->bind(':no_hp', $no_hp);
        
        $this->db->execute();
        
        // Jika ada baris yang ditemukan, berarti duplikat (Return True)
        if ($this->db->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // 2. Fungsi Simpan User Baru
    public function register($data) {
        $this->db->query("INSERT INTO users (nama, nik, tanggal_lahir, email, no_hp, password) 
                          VALUES (:nama, :nik, :tanggal_lahir, :email, :no_hp, :password)");
        
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':nik', $data['nik']);
        $this->db->bind(':tanggal_lahir', $data['tanggal_lahir']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':no_hp', $data['no_hp']);
        $this->db->bind(':password', $data['password']); // Password ini sudah di-hash dari Controller

        return $this->db->execute();
    }
}
?>