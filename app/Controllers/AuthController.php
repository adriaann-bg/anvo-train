<?php
// app/Controllers/AuthController.php

// Panggil PHPMailer di paling atas
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    public function lupa() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        
        // Jika user sudah login, tendang ke Beranda
        if (isset($_SESSION['user_id'])) {
            header('Location: /anvo/public/');
            exit;
        }

        // HAPUS unset($_SESSION['recovery_data']); DARI SINI 
        // Biarkan data tetap ada jika user sedang berada di tahap "Akun Ditemukan"

        $data['judul'] = 'Pemulihan Akun - ANVO';
        $this->view('auth/lupa', $data);
    }

    // --- FUNGSI RESET / BATAL PEMULIHAN AKUN ---
    public function batal_lupa() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        
        // Hapus sesi data pemulihan
        unset($_SESSION['recovery_data']);
        unset($_SESSION['otp_verified']); // Bersihkan juga jaga-jaga jika sudah sampai tahap OTP
        
        // Arahkan kembali ke halaman login sesuai keinginanmu!
        header('Location: /anvo/public/auth/login');
        exit;
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
                $_SESSION['old'] = $_POST; // <-- SIMPAN DATA LAMA
                header('Location: /anvo/public/auth/register');
                exit;
            }

            // TAMBAHAN BARU: VALIDASI KEKUATAN PASSWORD
            // Penjelasan RegEx: (?=.*[a-z]) huruf kecil, (?=.*[A-Z]) huruf besar, (?=.*[^a-zA-Z0-9]) karakter khusus, .{8,} minimal 8 digit
            if (!preg_match(...)) {
                $_SESSION['error'] = 'Password harus minimal 8 karakter...';
                $_SESSION['old'] = $_POST; // <-- SIMPAN DATA LAMA
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

                // PERBAIKAN 4: Set pesan sukses
                $_SESSION['success'] = 'Selamat datang kembali, ' . $user['nama'] . '!';

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

    // --- 4. PROSES PENCARIAN AKUN (LUPA AKUN) ---
    public function proses_lupa() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nik = trim($_POST['nik']);
            $nama = trim($_POST['nama']);
            $tanggal_lahir = trim($_POST['tanggal_lahir']);

            $userModel = $this->model('User');
            $user = $userModel->verifyRecovery($nik, $nama, $tanggal_lahir);

            if ($user) {
                // DATA DITEMUKAN! Lakukan Masking (Penyamaran Data)
                
                // 1. Masking Email (syahputraadrian5011@g... menjadi syah****@g...)
                $email_parts = explode('@', $user['email']);
                $name_part = $email_parts[0];
                $domain = $email_parts[1];
                $show_chars = min(4, strlen($name_part)); // Tampilkan 4 huruf awal
                $masked_email = substr($name_part, 0, $show_chars) . str_repeat('*', strlen($name_part) - $show_chars) . '@' . $domain;

                // 2. Masking No HP (+6285176923011 menjadi +62851****3011)
                $phone = $user['no_hp'];
                $masked_phone = substr($phone, 0, 6) . str_repeat('*', strlen($phone) - 10) . substr($phone, -4);

                // Simpan data yang sudah disensor ke Sesi sementara
                session_start();
                $_SESSION['recovery_data'] = [
                    'nik' => $user['nik'], // NIK disimpan untuk proses reset password nanti
                    'nama' => $user['nama'],
                    'email' => $masked_email,
                    'no_hp' => $masked_phone
                ];

                header('Location: /anvo/public/auth/lupa');
                exit;
            } else {
                // DATA TIDAK COCOK
                session_start();
                $_SESSION['error'] = 'Data tidak ditemukan atau kombinasi salah. Pastikan NIK, Nama, dan Tanggal Lahir sesuai KTP.';
                header('Location: /anvo/public/auth/lupa');
                exit;
            }
        } else {
            header('Location: /anvo/public/auth/lupa');
            exit;
        }
    }

    // --- 5. REQUEST OTP & KIRIM EMAIL ---
    public function reset_password() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Pastikan user sudah melewati tahap pencarian akun (Lupa.php)
        if (!isset($_SESSION['recovery_data'])) {
            header('Location: /anvo/public/auth/lupa');
            exit;
        }

        $nik = $_SESSION['recovery_data']['nik'];
        
        // PERBAIKAN: Gunakan Model untuk mengambil data, BUKAN direct Database!
        $userModel = $this->model('User');
        $userData = $userModel->getUserByNik($nik);

        // Jika entah bagaimana datanya tidak ada, kembalikan dengan error
        if (!$userData) {
            $_SESSION['error'] = 'Gagal memuat data pengguna. Silakan coba lagi.';
            header('Location: /anvo/public/auth/lupa');
            exit;
        }

        $email_asli = $userData['email'];

        // 1. Generate 6 Angka Acak & Waktu Kedaluwarsa (5 Menit)
        $otp = rand(100000, 999999);
        $expire = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // 2. Simpan OTP ke Database
        $userModel->setOTP($nik, $otp, $expire);

        // 3. Kirim Email menggunakan PHPMailer & Brevo SMTP
        $mail = new PHPMailer(true);

        try {
            // Konfigurasi SMTP Brevo
            $mail->isSMTP();
            // Isi dengan server SMTP Brevo yang valid

            // Pengirim & Penerima
            // Gunakan email resmi ANVO untuk pengiriman
            $mail->addAddress($email_asli, $userData['nama']);

            // Konten Email
            $mail->isHTML(true);
            $mail->Subject = 'Kode Rahasia Pemulihan Akun ANVO Anda';
            $mail->Body    = "
                <h3>Halo, {$userData['nama']}</h3>
                <p>Kami menerima permintaan untuk mereset kata sandi akun ANVO Anda.</p>
                <p>Berikut adalah kode OTP 6-digit Anda (Berlaku selama 5 menit):</p>
                <h2 style='background: #f4f4f4; padding: 10px; font-size: 24px; letter-spacing: 5px; text-align: center; color: #8C6239;'>{$otp}</h2>
                <p><b>JANGAN berikan kode ini kepada siapa pun</b>, termasuk staf ANVO.</p>
            ";

            $mail->send();
            
            // Lanjut ke Halaman Input OTP
            $data['judul'] = 'Verifikasi OTP - ANVO';
            $this->view('auth/otp', $data);

        } catch (Exception $e) {
            $_SESSION['error'] = "Gagal mengirim email OTP. Silakan coba lagi. Mailer Error: {$mail->ErrorInfo}";
            header('Location: /anvo/public/auth/lupa');
            exit;
        }
    }

    // --- 6. PROSES VERIFIKASI OTP ---
    public function proses_otp() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['otp'])) {
            $otp_input = implode('', $_POST['otp']); 
            $nik = $_SESSION['recovery_data']['nik']; 
            $userModel = $this->model('User');
            
            if ($userModel->verifyOTP($nik, $otp_input)) {
                // OTP VALID!
                $_SESSION['otp_verified'] = true;
                $userModel->setOTP($nik, NULL, NULL);
                
                // TAMBAHAN: Set pesan sukses
                $_SESSION['success'] = 'Kode OTP valid! Mengarahkan ke pembuatan sandi baru...';
                
                // Tetap lempar ke otp.php DULU agar animasinya jalan, baru otp.php yang akan melempar ke password_baru
                header('Location: /anvo/public/auth/otp_sukses'); 
                exit;
            } else {
                // OTP SALAH
                $_SESSION['error'] = 'Kode OTP salah atau sudah kedaluwarsa.';
                $data['judul'] = 'Verifikasi OTP - ANVO';
                $this->view('auth/otp', $data);
                exit;
            }
        }
    }

    // Tambahkan 1 fungsi kecil ini tepat di bawah proses_otp()
    public function otp_sukses() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        $data['judul'] = 'Verifikasi Berhasil - ANVO';
        $this->view('auth/otp', $data); // Kita pakai view yang sama, tapi nanti ditangkap oleh JavaScript
    }

    // --- 7. TAMPILKAN HALAMAN BUAT PASSWORD BARU ---
    public function password_baru() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Auth Guard: Pastikan user sudah melewati verifikasi OTP!
        if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
            header('Location: /anvo/public/auth/lupa');
            exit;
        }

        $data['judul'] = 'Buat Password Baru - ANVO';
        $this->view('auth/password_baru', $data);
    }

    // --- 8. SIMPAN PASSWORD BARU ---
    public function simpan_password_baru() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        // Pastikan user tidak bypass langsung ke URL ini
        if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
            header('Location: /anvo/public/auth/lupa');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            // 1. Validasi Kecocokan
            if ($password !== $password_confirm) {
                $_SESSION['error'] = 'Konfirmasi password tidak cocok!';
                header('Location: /anvo/public/auth/password_baru');
                exit;
            }

            // 2. VALIDASI KEKUATAN PASSWORD (Pecah agar 100% Akurat)
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $specialChars = preg_match('/[^a-zA-Z0-9]/', $password); // Cek apakah ada selain huruf dan angka

            if(!$uppercase || !$lowercase || !$specialChars || strlen($password) < 8) {
                $_SESSION['error'] = 'Password gagal disimpan! Harus minimal 8 karakter, mengandung huruf besar, kecil, dan karakter khusus.';
                header('Location: /anvo/public/auth/password_baru');
                exit;
            }

            // 3. Enkripsi dan Simpan
            $nik = $_SESSION['recovery_data']['nik'];
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);

            $userModel = $this->model('User');
            
            if ($userModel->updatePassword($nik, $password_hashed)) {
                // BERHASIL! Bersihkan semua sesi recovery
                unset($_SESSION['recovery_data']);
                unset($_SESSION['otp_verified']);
                
                $_SESSION['success'] = 'Password berhasil diubah! Silakan masuk dengan sandi baru Anda.';
                header('Location: /anvo/public/auth/login');
                exit;
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan sistem saat menyimpan password.';
                header('Location: /anvo/public/auth/password_baru');
                exit;
            }
        }
    }
}
?>