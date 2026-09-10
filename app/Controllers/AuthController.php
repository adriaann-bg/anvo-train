<?php
// app/Controllers/AuthController.php

class AuthController extends Controller {
    
    public function index() {
        // Jika sudah login, tendang ke Beranda
        if (isset($_SESSION['user_id'])) {
            header('Location: /anvo/public/');
            exit;
        }
        header('Location: /anvo/public/auth/login');
        exit;
    }

    public function login() {
        // Jika sudah login, tendang ke Beranda
        if (isset($_SESSION['user_id'])) {
            header('Location: /anvo/public/');
            exit;
        }
        
        $data['judul'] = 'Lanjutkan Perjalanan - ANVO';
        $this->view('auth/login', $data);
    }

    public function register() {
        // Jika sudah login, tendang ke Beranda
        if (isset($_SESSION['user_id'])) {
            header('Location: /anvo/public/');
            exit;
        }

        $data['judul'] = 'Daftar - ANVO';
        $this->view('auth/register', $data);
    }

    // --- 1. PROSES PENDAFTARAN ---
    public function proses_register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama = trim($_POST['nama']);
            $nik = trim($_POST['nik']);
            $tanggal_lahir = trim($_POST['tanggal_lahir']);
            $email = trim($_POST['email']);
            $kode_negara = trim($_POST['kode_negara']); 
            $no_hp_mentah = trim($_POST['no_hp']);      
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            $no_hp_lengkap = $kode_negara . $no_hp_mentah;

            // VALIDASI PASSWORD COCOK
            if ($password !== $password_confirm) {
                $_SESSION['error'] = 'Konfirmasi password tidak cocok!';
                header('Location: /anvo/public/auth/register');
                exit;
            }

            // TAMBAHAN BARU: VALIDASI KEKUATAN PASSWORD
            // Penjelasan RegEx: (?=.*[a-z]) huruf kecil, (?=.*[A-Z]) huruf besar, (?=.*[^a-zA-Z0-9]) karakter khusus, .{8,} minimal 8 digit
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/', $password)) {
                $_SESSION['error'] = 'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan karakter khusus!';
                header('Location: /anvo/public/auth/register');
                exit;
            }

            // CEK DUPLIKAT NIK/EMAIL/NOHP (Biarkan kode di bawahnya tetap sama)
            $userModel = $this->model('User');
            // CEK DUPLIKAT NIK/EMAIL/NOHP
            $userModel = $this->model('User'); 
            if ($userModel->checkDuplicate($nik, $email, $no_hp_lengkap)) {
                $_SESSION['error'] = 'Pendaftaran gagal! NIK, Email, atau No. Telepon sudah terdaftar.';
                header('Location: /anvo/public/auth/register');
                exit;
            }

            // ENKRIPSI PASSWORD
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);

            $data = [
                'nama' => $nama,
                'nik' => $nik,
                'tanggal_lahir' => $tanggal_lahir,
                'email' => $email,
                'no_hp' => $no_hp_lengkap,
                'password' => $password_hashed
            ];

            // SIMPAN KE DATABASE
            if ($userModel->register($data)) {
                $_SESSION['success'] = 'Pendaftaran berhasil! Silakan Masuk untuk melanjutkan perjalanan.';
                header('Location: /anvo/public/auth/login');
                exit;
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                header('Location: /anvo/public/auth/register');
                exit;
            }
        } else {
            header('Location: /anvo/public/auth/register');
            exit;
        }
    }

    // --- 2. MESIN SMART LOGIN ---
    public function proses_login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $login_id = trim($_POST['login_id']); 
            $password = $_POST['password'];
            $ingat_saya = isset($_POST['ingat']) ? true : false; 

            $userModel = $this->model('User');

            // DETEKSI CERDAS: Email atau No. HP?
            if (filter_var($login_id, FILTER_VALIDATE_EMAIL)) {
                // Jika mengandung @ dan format email benar
                $user = $userModel->findUserByIdentifier('email', $login_id);
            } else {
                // Jika bukan email, bersihkan dari spasi/strip barangkali user copas
                $login_id = preg_replace('/[^0-9+]/', '', $login_id);
                
                // Standarisasi jika user mengetik angka 0 di depan (0812...) 
                // atau 62 di depan (62812...) menjadi format E.164 (+62812...)
                if (strpos($login_id, '0') === 0) {
                    $login_id = '+62' . substr($login_id, 1);
                } else if (strpos($login_id, '62') === 0) {
                    $login_id = '+' . $login_id;
                }

                $user = $userModel->findUserByIdentifier('no_hp', $login_id);
            }

            // VERIFIKASI USER & PASSWORD
            if ($user && password_verify($password, $user['password'])) {
                // BERHASIL MASUK
                $_SESSION['user_id'] = $user['nik'];
                $_SESSION['user_nama'] = $user['nama'];
                $_SESSION['user_email'] = $user['email'];

                // LOGIKA INGAT SAYA (COOKIE)
                if ($ingat_saya) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('anvo_remember', $token, time() + (86400 * 30), "/");
                }

                // Lempar ke Beranda Utama
                header('Location: /anvo/public/'); 
                exit;
            } else {
                // GAGAL MASUK
                $_SESSION['error'] = 'Email/No. Telepon atau Password salah!';
                header('Location: /anvo/public/auth/login');
                exit;
            }
        } else {
            header('Location: /anvo/public/auth/login');
            exit;
        }
    }

    // --- 3. FUNGSI LOGOUT ---
    public function logout() {
        // Hancurkan semua sesi
        session_unset();
        session_destroy();
        
        // Hapus cookie ingat saya jika ada
        if(isset($_COOKIE['anvo_remember'])) {
            setcookie('anvo_remember', '', time() - 3600, '/');
        }
        
        // Lempar kembali ke halaman login
        header('Location: /anvo/public/auth/login');
        exit;
    }
}
?>