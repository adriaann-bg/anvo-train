<?php
// app/Models/BookingModel.php

class BookingModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function searchJadwal($asal, $tujuan, $tanggal, $kelas) {
        $sql = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis_kelas, kereta.layout_kursi, kereta.kapasitas_kursi 
                FROM jadwal 
                JOIN kereta ON jadwal.id_kereta = kereta.id_kereta 
                WHERE :tanggal BETWEEN jadwal.tanggal_mulai AND jadwal.tanggal_akhir";
        
        $params = [':tanggal' => $tanggal];

        if (!empty($kelas)) {
            $sql .= " AND kereta.jenis_kelas LIKE :kelas";
            $params[':kelas'] = '%' . $kelas . '%';
        }

        $sql .= " ORDER BY jadwal.jam_berangkat ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rawJadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filtered = [];
        foreach ($rawJadwal as $j) {
            $transit = json_decode($j['stasiun_transit'], true);
            if (is_string($transit)) { $transit = json_decode($transit, true); }
            
            $validStoppingStations = [];
            if (is_array($transit)) {
                foreach ($transit as $t) {
                    $namaStasiun = is_array($t) ? ($t['nama'] ?? '') : $t;
                    $statusStasiun = is_array($t) ? strtolower($t['status'] ?? 'transit') : 'transit';
                    if (strpos($statusStasiun, 'dilewati') === false && strpos($statusStasiun, 'langsung') === false) {
                        $validStoppingStations[] = $namaStasiun;
                    }
                }
            } else {
                $validStoppingStations = [$j['stasiun_asal'], $j['stasiun_tujuan']];
            }

            $match = true;
            $asalIdx = -1; $tujuanIdx = -1;

            if (!empty($asal)) {
                $foundAsal = false;
                foreach ($validStoppingStations as $idx => $st) {
                    if (stripos($st, $asal) !== false) { $foundAsal = true; $asalIdx = $idx; break; }
                }
                if (!$foundAsal) $match = false;
            }

            if (!empty($tujuan) && $match) {
                $foundTujuan = false;
                foreach ($validStoppingStations as $idx => $st) {
                    if (stripos($st, $tujuan) !== false) { $foundTujuan = true; $tujuanIdx = $idx; break; }
                }
                if (!$foundTujuan || ($asalIdx >= $tujuanIdx)) $match = false;
            }

            if ($match) { $filtered[] = $j; }
        }
        return $filtered;
    }

    public function getJadwalById($id_jadwal) {
        $sql = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis_kelas, kereta.layout_kursi, kereta.kapasitas_kursi 
                FROM jadwal JOIN kereta ON jadwal.id_kereta = kereta.id_kereta WHERE jadwal.id_jadwal = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_jadwal]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOccupiedSeats($id_jadwal, $tanggal) {
        $sql = "SELECT p.nomor_kursi FROM penumpangs p 
                JOIN reservasis r ON p.id_reservasi = r.id_reservasi 
                JOIN pembayarans pay ON r.id_reservasi = pay.id_reservasi
                WHERE r.id_jadwal = :id_jadwal AND r.tanggal_keberangkatan = :tanggal AND pay.status_bayar = 'lunas'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_jadwal' => $id_jadwal, ':tanggal' => $tanggal]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createReservation($data) {
        try {
            $this->db->beginTransaction();

            // Validasi id_user agar sesuai dengan database
            // PENGAMAN: Cari id_user yang valid di tabel users
            $stmtUser = $this->db->prepare("SELECT id_user FROM users WHERE id_user = :val1 OR nik = :val2 LIMIT 1");
            $stmtUser->execute([':val1' => $data['id_user'], ':val2' => $data['id_user']]);
            $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
            $realUserId = $userData ? $userData['id_user'] : $data['id_user'];

            // 1. Insert Reservasi
            $sqlRes = "INSERT INTO reservasis (id_user, id_jadwal, id_stasiun_asal, id_stasiun_tujuan, tanggal_keberangkatan, jumlah_tiket) 
                       VALUES (:id_user, :id_jadwal, :id_asal, :id_tujuan, :tanggal, :jumlah)";
            $stmtRes = $this->db->prepare($sqlRes);
            $stmtRes->execute([
                ':id_user'   => $realUserId,
                ':id_jadwal' => $data['id_jadwal'],
                ':id_asal'   => $data['id_stasiun_asal'],
                ':id_tujuan' => $data['id_stasiun_tujuan'],
                ':tanggal'   => $data['tanggal_keberangkatan'],
                ':jumlah'    => count($data['penumpang'])
            ]);
            $id_reservasi = $this->db->lastInsertId();

            // 2. Insert Penumpang & Kursi
            $sqlPen = "INSERT INTO penumpangs (id_reservasi, nama, nik, nomor_kursi) VALUES (:id_res, :nama, :nik, :kursi)";
            $stmtPen = $this->db->prepare($sqlPen);
            foreach ($data['penumpang'] as $p) {
                $stmtPen->execute([
                    ':id_res'  => $id_reservasi,
                    ':nama'    => $p['nama'],
                    ':nik'     => $p['nik'],
                    ':kursi'   => $p['kursi']
                ]);
            }

            // 3. Insert Pembayaran (Pending)
            $sqlPay = "INSERT INTO pembayarans (id_reservasi, jumlah_tagihan, status_bayar) VALUES (:id_res, :tagihan, 'pending')";
            $stmtPay = $this->db->prepare($sqlPay);
            $stmtPay->execute([
                ':id_res'     => $id_reservasi,
                ':tagihan'    => $data['total_tagihan']
            ]);

            $this->db->commit();
            return $id_reservasi;
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['db_error'] = $e->getMessage();
            return false;
        }
    }

    public function getReservasiById($id_reservasi) {
        $sql = "SELECT r.*, j.*, k.nama_kereta, k.jenis_kelas, pay.jumlah_tagihan, pay.status_bayar, pay.id_pembayaran,
                sa.nama_stasiun as nama_asal, st.nama_stasiun as nama_tujuan, u.nama as nama_user
                FROM reservasis r
                JOIN jadwal j ON r.id_jadwal = j.id_jadwal
                JOIN kereta k ON j.id_kereta = k.id_kereta
                JOIN pembayarans pay ON r.id_reservasi = pay.id_reservasi
                JOIN stasiun sa ON r.id_stasiun_asal = sa.id_stasiun
                JOIN stasiun st ON r.id_stasiun_tujuan = st.id_stasiun
                JOIN users u ON r.id_user = u.id_user
                WHERE r.id_reservasi = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_reservasi]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            $stmtP = $this->db->prepare("SELECT * FROM penumpangs WHERE id_reservasi = :id");
            $stmtP->execute([':id' => $id_reservasi]);
            $res['penumpang_list'] = $stmtP->fetchAll(PDO::FETCH_ASSOC);
        }
        return $res;
    }

    public function getUserTickets($id_user) {
        $sql = "SELECT r.*, j.*, k.nama_kereta, k.jenis_kelas, pay.jumlah_tagihan, pay.status_bayar, pay.id_pembayaran,
                sa.nama_stasiun as nama_asal, st.nama_stasiun as nama_tujuan
                FROM reservasis r
                JOIN jadwal j ON r.id_jadwal = j.id_jadwal
                JOIN kereta k ON j.id_kereta = k.id_kereta
                JOIN pembayarans pay ON r.id_reservasi = pay.id_reservasi
                JOIN stasiun sa ON r.id_stasiun_asal = sa.id_stasiun
                JOIN stasiun st ON r.id_stasiun_tujuan = st.id_stasiun
                WHERE r.id_user = :id_user AND pay.status_bayar = 'lunas'
                ORDER BY r.tanggal_keberangkatan DESC, j.jam_berangkat DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_user' => $id_user]);
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tickets as &$t) {
            $stmtP = $this->db->prepare("SELECT * FROM penumpangs WHERE id_reservasi = :id");
            $stmtP->execute([':id' => $t['id_reservasi']]);
            $t['penumpang_list'] = $stmtP->fetchAll(PDO::FETCH_ASSOC);
        }
        return $tickets;
    }

    public function updateStatusPembayaran($id_pembayaran, $metode) {
        $sql = "UPDATE pembayarans SET status_bayar = 'lunas', waktu_bayar = NOW(), metode_pembayaran = :metode WHERE id_pembayaran = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':metode' => $metode, ':id' => $id_pembayaran]);
    }

    // Tambahkan di dalam class BookingModel
    public function getSavedPassengers($id_user) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.nama, p.nik 
            FROM penumpangs p
            JOIN reservasis r ON p.id_reservasi = r.id_reservasi
            WHERE r.id_user = :id_user
        ");
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}