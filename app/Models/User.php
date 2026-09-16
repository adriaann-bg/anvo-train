<?php
// app/Models/User.php

class User {
    private $db;

    public function __construct() {
        // Menggunakan koneksi PDO Singleton bawaan sistemmu
        $this->db = Database::getInstance()->getConnection();
    }

    // 1. Fungsi Cek Duplikat
    public function checkDuplicate($nik, $email, $no_hp) {
        // PERUBAHAN: Ganti "SELECT id" menjadi "SELECT *"
        $sql = "SELECT * FROM users WHERE nik = :nik OR email = :email OR no_hp = :no_hp LIMIT 1";
        $stmt = $this->db->prepare($sql);
        // ... (sisanya biarkan sama) ...
        
        $stmt->bindParam(':nik', $nik);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':no_hp', $no_hp);
        $stmt->execute();
        
        // Jika ada data yang cocok, kembalikan true (berarti duplikat)
        return $stmt->fetch() ? true : false;
    }

    // 2. Fungsi Mendaftarkan User Baru (Update dengan form baru)
    public function register($data) {
        $sql = "INSERT INTO users (nama, nik, tanggal_lahir, email, no_hp, password) 
                VALUES (:nama, :nik, :tanggal_lahir, :email, :no_hp, :password)";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':nik', $data['nik']);
        $stmt->bindParam(':tanggal_lahir', $data['tanggal_lahir']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':no_hp', $data['no_hp']);
        
        // Password sudah di-hash (enkripsi BCRYPT) dari AuthController
        $stmt->bindParam(':password', $data['password']); 
        
        return $stmt->execute();
    }

    // 3. Fungsi Mencari User untuk Smart Login (Email atau No. HP)
    public function findUserByIdentifier($field, $value) {
        // $field akan berisi string 'email' atau 'no_hp' yang dikirim dari Controller
        $sql = "SELECT * FROM users WHERE {$field} = :value LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Fungsi Validasi 3 Lapis untuk Lupa Akun
    public function verifyRecovery($nik, $nama, $tanggal_lahir) {
        // Pencarian harus cocok persis ketiga-tiganya untuk keamanan
        $sql = "SELECT * FROM users WHERE nik = :nik AND nama = :nama AND tanggal_lahir = :tanggal_lahir LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nik', $nik);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Simpan Kode OTP ke Database
    public function setOTP($nik, $otp, $expire) {
        $sql = "UPDATE users SET reset_token = :otp, token_expire = :expire WHERE nik = :nik";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':otp', $otp);
        $stmt->bindParam(':expire', $expire);
        $stmt->bindParam(':nik', $nik); 
        return $stmt->execute();
    }

    // 6. Validasi OTP (Cek kecocokan dan apakah belum kedaluwarsa)
    public function verifyOTP($nik, $otp) {
        // Cek token expire yang masih lebih besar dari waktu saat ini (NOW)
        $sql = "SELECT * FROM users WHERE nik = :nik AND reset_token = :otp AND token_expire > NOW() LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nik', $nik);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByNik($nik) {
        $sql = "SELECT * FROM users WHERE nik = :nik LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nik', $nik);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 7. Update Password Baru
    public function updatePassword($nik, $password_hashed) {
        $sql = "UPDATE users SET password = :password WHERE nik = :nik";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':password', $password_hashed);
        $stmt->bindParam(':nik', $nik);
        return $stmt->execute();
    }
}
?>
