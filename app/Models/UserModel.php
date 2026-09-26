<?php
// app/Models/UserModel.php

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database(); 
    }

    public function checkDuplicate($nik, $email, $no_hp) {
        $this->db->query("SELECT id_user FROM users WHERE nik = :nik OR email = :email OR no_hp = :no_hp");
        $this->db->bind(':nik', $nik);
        $this->db->bind(':email', $email);
        $this->db->bind(':no_hp', $no_hp);
        
        $this->db->execute();
        
        if ($this->db->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function register($data) {
        // PERBAIKAN: Tambahkan created_at dan fungsi NOW()
        $this->db->query("INSERT INTO users (nama, nik, tanggal_lahir, email, no_hp, password, created_at) 
                          VALUES (:nama, :nik, :tanggal_lahir, :email, :no_hp, :password, NOW())");
        
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':nik', $data['nik']);
        $this->db->bind(':tanggal_lahir', $data['tanggal_lahir']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':no_hp', $data['no_hp']);
        $this->db->bind(':password', $data['password']);

        return $this->db->execute();
    }
}
?>