<?php
// app/Controllers/BookingController.php

class BookingController extends Controller {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /anvo/public/auth/login');
            exit;
        }
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $stasiunStmt = $db->query("SELECT * FROM stasiun ORDER BY nama_stasiun ASC");
        
        $data['judul'] = 'Pesan Tiket Kereta Cepat - ANVO';
        $data['active_menu'] = 'beli_tiket';
        $data['stasiun_list'] = $stasiunStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('layouts/header', $data); // Disesuaikan
        $this->view('booking/index', $data);
        $this->view('layouts/footer');        // Disesuaikan
    }

    public function jadwal() {
        $bookingModel = $this->model('BookingModel');
        $asal = $_GET['asal'] ?? '';
        $tujuan = $_GET['tujuan'] ?? '';
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $tanggal_pulang = $_GET['tanggal_pulang'] ?? ''; // Ditangkap jika ada
        $kelas = $_GET['kelas'] ?? '';
        $penumpang = $_GET['penumpang'] ?? 1;

        // Simpan ke session untuk step selanjutnya termasuk tanggal pulang
        $_SESSION['booking_search'] = compact('asal', 'tujuan', 'tanggal', 'tanggal_pulang', 'kelas', 'penumpang');

        $data['judul'] = 'Pilih Jadwal Keberangkatan - ANVO';
        $data['active_menu'] = 'beli_tiket';
        $data['jadwal_list'] = $bookingModel->searchJadwal($asal, $tujuan, $tanggal, $kelas);
        $data['search'] = $_SESSION['booking_search'];

        $this->view('layouts/header', $data);
        $this->view('booking/jadwal', $data);
        $this->view('layouts/footer');
    }

    public function identitas($id_jadwal) {
        $bookingModel = $this->model('BookingModel');
        $db = Database::getInstance()->getConnection();

        $jadwal = $bookingModel->getJadwalById($id_jadwal);
        if (!isset($_SESSION['booking_search'])) {
            header('Location: /anvo/public/booking');
            exit;
        }

        // Simpan ID jadwal ke session untuk step berikutnya
        $_SESSION['booking_jadwal_id'] = $id_jadwal;

        // Jika form identitas penumpang di-submit (POST dari halaman identitas)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['penumpang'])) {
            $_SESSION['booking_penumpang'] = $_POST['penumpang'];
            header('Location: /anvo/public/booking/kursi');
            exit;
        }

        // Ambil daftar penumpang yang pernah disimpan user sebelumnya
        $savedPassengers = $bookingModel->getSavedPassengers($_SESSION['user_id']);

        // AMBIL DATA USER SAAT INI UNTUK DEFAULT PENUMPANG 1
        $stmtUser = $db->prepare("SELECT nama, nik FROM users WHERE id_user = :id");
        $stmtUser->execute([':id' => $_SESSION['user_id']]);
        $data['current_user'] = $stmtUser->fetch(PDO::FETCH_ASSOC);

        $data['judul'] = 'Identitas Penumpang - ANVO';
        $data['active_menu'] = 'beli_tiket';
        $data['jadwal'] = $jadwal;
        $data['search'] = $_SESSION['booking_search'];
        $data['saved_passengers'] = $savedPassengers;

        $this->view('layouts/header', $data);
        $this->view('booking/identitas', $data);
        $this->view('layouts/footer');
    }

    public function kursi() {
        if (!isset($_SESSION['booking_jadwal_id']) || !isset($_SESSION['booking_penumpang'])) {
            header('Location: /anvo/public/booking');
            exit;
        }

        $bookingModel = $this->model('BookingModel');
        $jadwalId = $_SESSION['booking_jadwal_id'];
        $jadwal = $bookingModel->getJadwalById($jadwalId);
        $tanggal = $_SESSION['booking_search']['tanggal'];
        $occupiedSeats = $bookingModel->getOccupiedSeats($jadwalId, $tanggal);

        // Parsing layout gerbong
        $layoutGerbong = json_decode($jadwal['layout_kursi'], true) ?? [];

        // Generate default kursi secara acak untuk setiap penumpang jika belum ada
        if (!isset($_SESSION['booking_kursi_assigned'])) {
            $assigned = [];
            $allAvailableSeats = [];

            foreach ($layoutGerbong as $gIndex => $gerbong) {
                $tipe = $gerbong['tipe_kelas'];
                $baris = $gerbong['baris'];
                $cols = ($tipe == 'Executive Prime') ? ['A','B','C','D'] : (($tipe == 'Luminary Capsule') ? ['A','B'] : ['Kabin']);
                
                for ($b = 1; $b <= $baris; $b++) {
                    foreach ($cols as $c) {
                        $seatName = ($c == 'Kabin') ? "VVIP-{$b}" : "{$b}{$c}";
                        $fullSeat = "G" . ($gIndex + 1) . "-{$seatName}";
                        if (!in_array($fullSeat, $occupiedSeats) && !in_array($fullSeat, $assigned)) {
                            $allAvailableSeats[] = $fullSeat;
                        }
                    }
                }
            }

            shuffle($allAvailableSeats);
            foreach ($_SESSION['booking_penumpang'] as $idx => $p) {
                $_SESSION['booking_penumpang'][$idx]['kursi'] = array_shift($allAvailableSeats) ?? 'G1-1A';
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Simpan pilihan kursi dari form
            foreach ($_POST['kursi'] as $idx => $kursiPilihan) {
                $_SESSION['booking_penumpang'][$idx]['kursi'] = $kursiPilihan;
            }

            // Hitung total harga
            $jumlahPenumpang = count($_SESSION['booking_penumpang']);
            $totalHarga = $jadwal['harga'] * $jumlahPenumpang;

            // Dapatkan ID Stasiun Asal & Tujuan
            $db = Database::getInstance()->getConnection();
            $stmtSt = $db->prepare("SELECT id_stasiun FROM stasiun WHERE nama_stasiun LIKE :nama LIMIT 1");
            
            $stmtSt->execute([':nama' => '%' . $_SESSION['booking_search']['asal'] . '%']);
            $asalSt = $stmtSt->fetch(PDO::FETCH_ASSOC);

            $stmtSt->execute([':nama' => '%' . $_SESSION['booking_search']['tujuan'] . '%']);
            $tujuanSt = $stmtSt->fetch(PDO::FETCH_ASSOC);

            $reservationData = [
                'id_user' => $_SESSION['user_id'],
                'id_jadwal' => $jadwalId,
                'id_stasiun_asal' => $asalSt['id_stasiun'] ?? 1,
                'id_stasiun_tujuan' => $tujuanSt['id_stasiun'] ?? 2,
                'tanggal_keberangkatan' => $tanggal,
                'penumpang' => $_SESSION['booking_penumpang'],
                'total_tagihan' => $totalHarga
            ];

            $id_reservasi = $bookingModel->createReservation($reservationData);
            if ($id_reservasi) {
                header('Location: /anvo/public/booking/pembayaran/' . $id_reservasi);
                exit;
            } else {
                $_SESSION['error'] = 'Gagal memproses reservasi: ' . ($_SESSION['db_error'] ?? 'Kesalahan database');
            }
        }

        $data['judul'] = 'Pilih Kursi Penumpang - ANVO';
        $data['active_menu'] = 'beli_tiket';
        $data['jadwal'] = $jadwal;
        $data['layout_gerbong'] = $layoutGerbong;
        $data['occupied_seats'] = $occupiedSeats;
        $data['penumpang'] = $_SESSION['booking_penumpang'];

        $this->view('layouts/header', $data); // Disesuaikan
        $this->view('booking/kursi', $data);
        $this->view('layouts/footer');        // Disesuaikan
    }

   public function pembayaran($id_reservasi = null) {
        if (empty($id_reservasi)) {
            $_SESSION['error'] = 'ID Reservasi tidak valid.';
            header('Location: /anvo/public/booking');
            exit;
        }

        $bookingModel = $this->model('BookingModel');
        $reservasi = $bookingModel->getReservasiById($id_reservasi);

        if (!$reservasi) {
            $_SESSION['error'] = 'Data reservasi tidak ditemukan.';
            header('Location: /anvo/public/booking');
            exit;
        }

        $data['judul'] = 'Pembayaran Tiket - ANVO';
        $data['active_menu'] = 'beli_tiket';
        $data['reservasi'] = $reservasi;

        $this->view('layouts/header', $data);
        $this->view('booking/pembayaran', $data);
        $this->view('layouts/footer');
    }

    public function proses_bayar_ajax() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $pin = $input['pin'] ?? '';
        $id_pembayaran = $input['id_pembayaran'] ?? '';
        $id_reservasi = $input['id_reservasi'] ?? '';

        if (!isset($_SESSION['pin_attempts'])) {
            $_SESSION['pin_attempts'] = 0;
            $_SESSION['pin_lock_time'] = 0;
        }

        // Cek apakah sedang dalam masa lockout (1-5 menit)
        if ($_SESSION['pin_attempts'] >= 5) {
            $lockDuration = 120; // 2 menit
            if (time() - $_SESSION['pin_lock_time'] < $lockDuration) {
                $sisa = $lockDuration - (time() - $_SESSION['pin_lock_time']);
                echo json_encode(['status' => 'locked', 'message' => "Terlalu banyak percobaan PIN salah. Mohon tunggu " . ceil($sisa/60) . " menit."]);
                exit;
            } else {
                $_SESSION['pin_attempts'] = 0;
            }
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT pin FROM users WHERE id_user = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || empty($user['pin'])) {
            echo json_encode(['status' => 'error', 'message' => 'PIN Anda belum diatur. Atur PIN melalui profil akun.']);
            exit;
        }

        if (password_verify($pin, $user['pin'])) {
            $_SESSION['pin_attempts'] = 0;
            $bookingModel = $this->model('BookingModel');
            $bookingModel->updateStatusPembayaran($id_pembayaran, 'Virtual Account / Saldo ANVO');
            
            echo json_encode(['status' => 'success', 'redirect' => '/anvo/public/booking/sukses/' . $id_reservasi]);
            exit;
        } else {
            $_SESSION['pin_attempts']++;
            $sisaKesempatan = 5 - $_SESSION['pin_attempts'];
            if ($_SESSION['pin_attempts'] >= 5) {
                $_SESSION['pin_lock_time'] = time();
                echo json_encode(['status' => 'locked', 'message' => 'PIN salah 5 kali. Akses pembayaran dikunci sementara selama 2 menit.']);
            } else {
                echo json_encode(['status' => 'wrong_pin', 'message' => "PIN salah! Sisa kesempatan: {$sisaKesempatan} kali."]);
            }
            exit;
        }
    }

    public function sukses($id_reservasi) {
        $bookingModel = $this->model('BookingModel');
        $reservasi = $bookingModel->getReservasiById($id_reservasi);

        $data['judul'] = 'E-Ticket Berhasil - ANVO';
        $data['active_menu'] = 'tiket_saya';
        $data['reservasi'] = $reservasi;

        $this->view('layouts/header', $data); // Disesuaikan
        $this->view('booking/sukses', $data);
        $this->view('layouts/footer');        // Disesuaikan
    }

    public function tiket_saya() {
        $bookingModel = $this->model('BookingModel');
        $tickets = $bookingModel->getUserTickets($_SESSION['user_id']);

        $currentTime = date('Y-m-d H:i:s');
        $activeTickets = [];
        $historyTickets = [];

        foreach ($tickets as $t) {
            $arrivalDateTime = $t['tanggal_keberangkatan'] . ' ' . $t['jam_tiba'];
            if ($currentTime <= $arrivalDateTime) {
                $activeTickets[] = $t;
            } else {
                $historyTickets[] = $t;
            }
        }

        $data['judul'] = 'Histori & Tiket Saya - ANVO';
        $data['active_menu'] = 'tiket_saya';
        $data['active_tickets'] = $activeTickets;
        $data['history_tickets'] = $historyTickets;

        $this->view('layouts/header', $data); // Disesuaikan
        $this->view('user/tickets', $data);
        $this->view('layouts/footer');        // Disesuaikan
    }
}