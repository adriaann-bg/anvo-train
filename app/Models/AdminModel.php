<?php
// app/Models/AdminModel.php

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllStasiun() {
        $stmt = $this->db->prepare("SELECT * FROM stasiun");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKeretaAktif() {
        $stmt = $this->db->prepare("SELECT * FROM kereta WHERE status_operasional = 'Aktif'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllKereta() {
        $stmt = $this->db->prepare("SELECT * FROM kereta");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllJadwal() {
        $sql = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis_kelas 
                FROM jadwal 
                JOIN kereta ON jadwal.id_kereta = kereta.id_kereta 
                ORDER BY jadwal.tanggal_mulai DESC, jadwal.jam_berangkat ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getFilteredJadwal($tanggal = '', $kelas = '', $asal = '', $tujuan = '') {
        $sql = "SELECT DISTINCT jadwal.*, kereta.nama_kereta, kereta.jenis_kelas 
                FROM jadwal 
                JOIN kereta ON jadwal.id_kereta = kereta.id_kereta 
                LEFT JOIN jadwal_penugasan_kru jp ON jadwal.id_jadwal = jp.id_jadwal
                LEFT JOIN master_kru mk ON jp.id_kru = mk.id_kru 
                WHERE 1=1";
        
        $params = [];

        if (!empty($tanggal)) {
            $sql .= " AND :tanggal BETWEEN jadwal.tanggal_mulai AND jadwal.tanggal_akhir";
            $params[':tanggal'] = $tanggal;
        }

        if (!empty($kelas)) {
            $sql .= " AND kereta.jenis_kelas LIKE :kelas";
            $params[':kelas'] = '%' . $kelas . '%';
        }

        $sql .= " ORDER BY jadwal.tanggal_mulai DESC, jadwal.jam_berangkat ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rawJadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filtered = [];
        foreach ($rawJadwal as $j) {
            // Normalisasi JSON yang mungkin double-encoded atau format lama
            $transit = json_decode($j['stasiun_transit'], true);
            if (is_string($transit)) {
                $transit = json_decode($transit, true); 
            }
            
            $transitNamas = [];
            if (is_array($transit)) {
                foreach ($transit as $t) {
                    $transitNamas[] = is_array($t) ? ($t['nama'] ?? '') : $t;
                }
            } else {
                $transitNamas = [$j['stasiun_asal'], $j['stasiun_tujuan']];
            }

            $match = true;

            // 1. Cek Stasiun Asal
            if (!empty($asal)) {
                $foundAsal = false;
                $asalIdx = -1;
                foreach ($transitNamas as $idx => $st) {
                    if (stripos($st, $asal) !== false) {
                        $foundAsal = true;
                        $asalIdx = $idx;
                        break;
                    }
                }
                if (!$foundAsal) { $match = false; }
            }

            // 2. Cek Stasiun Tujuan & Arah
            if (!empty($tujuan) && $match) {
                $foundTujuan = false;
                $tujuanIdx = -1;
                foreach ($transitNamas as $idx => $st) {
                    if (stripos($st, $tujuan) !== false) {
                        $foundTujuan = true;
                        $tujuanIdx = $idx;
                        break;
                    }
                }
                if (!$foundTujuan) {
                    $match = false;
                } else {
                    if (!empty($asal) && $asalIdx >= $tujuanIdx) {
                        $match = false;
                    }
                }
            }

            if ($match) {
                $filtered[] = $j;
            }
        }

        return $filtered;
    }

    public function tambahJadwal($data) {
        $sql = "INSERT INTO jadwal (id_kereta, id_koridor, stasiun_asal, stasiun_tujuan, stasiun_transit, jam_berangkat, jam_tiba, harga, tanggal_mulai, tanggal_akhir, jenis_jadwal) 
                VALUES (:id_kereta, :id_koridor, :stasiun_asal, :stasiun_tujuan, :stasiun_transit, :jam_berangkat, :jam_tiba, :harga, :tanggal_mulai, :tanggal_akhir, :jenis_jadwal)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_kereta' => $data['id_kereta'],
            ':id_koridor' => $data['id_koridor'],
            ':stasiun_asal' => $data['stasiun_asal'],
            ':stasiun_tujuan' => $data['stasiun_tujuan'],
            ':stasiun_transit' => $data['stasiun_transit'],
            ':jam_berangkat' => $data['jam_berangkat'],
            ':jam_tiba' => $data['jam_tiba'],
            ':harga' => $data['harga'],
            ':tanggal_mulai' => $data['tanggal_mulai'],
            ':tanggal_akhir' => $data['tanggal_akhir'],
            ':jenis_jadwal' => $data['jenis_jadwal']
        ]);
    }

    public function hapusJadwal($id) {
        $stmt = $this->db->prepare("DELETE FROM jadwal WHERE id_jadwal = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getJadwalById($id_jadwal) {
        $sql = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis_kelas 
                FROM jadwal 
                JOIN kereta ON jadwal.id_kereta = kereta.id_kereta 
                WHERE jadwal.id_jadwal = :id_jadwal";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_jadwal' => $id_jadwal]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getKruByJadwal($id_jadwal) {
        $sql = "SELECT jp.*, mk.* 
                FROM jadwal_penugasan_kru jp 
                JOIN master_kru mk ON jp.id_kru = mk.id_kru 
                WHERE jp.id_jadwal = :id_jadwal 
                ORDER BY jp.tanggal_tugas ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_jadwal' => $id_jadwal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKruByJadwalFiltered($id_jadwal, $tanggal = '', $keyword = '') {
        $sql = "SELECT jp.*, mk.* 
                FROM jadwal_penugasan_kru jp 
                JOIN master_kru mk ON jp.id_kru = mk.id_kru     
                WHERE jp.id_jadwal = :id_jadwal";
        
        $params = [':id_jadwal' => $id_jadwal];

        if (!empty($tanggal)) {
            $sql .= " AND jp.tanggal_tugas = :tanggal";
            $params[':tanggal'] = $tanggal;
        }

        if (!empty($keyword)) {
            $sql .= " AND (mk.nama_lengkap LIKE :keyword OR mk.nik LIKE :keyword OR mk.nip LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY jp.tanggal_tugas ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMasterKru() {
        $stmt = $this->db->prepare("SELECT * FROM master_kru ORDER BY nama_lengkap ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKruById($id_kru) {
        $stmt = $this->db->prepare("SELECT * FROM master_kru WHERE id_kru = :id");
        $stmt->execute([':id' => $id_kru]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateKru($id_kru, $data, $foto = null) {
        if ($foto) {
            $sql = "UPDATE master_kru SET nip = :nip, nik = :nik, nama_lengkap = :nama_lengkap, tanggal_lahir = :tanggal_lahir, agama = :agama, foto = :foto, email = :email, no_telepon = :no_telepon, pendidikan_terakhir = :pendidikan_terakhir, alamat_lengkap = :alamat_lengkap, status_pernikahan = :status_pernikahan, kontak_darurat = :kontak_darurat, riwayat_penyakit = :riwayat_penyakit, posisi = :posisi WHERE id_kru = :id";
        } else {
            $sql = "UPDATE master_kru SET nip = :nip, nik = :nik, nama_lengkap = :nama_lengkap, tanggal_lahir = :tanggal_lahir, agama = :agama, email = :email, no_telepon = :no_telepon, pendidikan_terakhir = :pendidikan_terakhir, alamat_lengkap = :alamat_lengkap, status_pernikahan = :status_pernikahan, kontak_darurat = :kontak_darurat, riwayat_penyakit = :riwayat_penyakit, posisi = :posisi WHERE id_kru = :id";
        }

        $params = [
            ':nip' => $data['nip'],
            ':nik' => $data['nik'],
            ':nama_lengkap' => $data['nama_lengkap'],
            ':tanggal_lahir' => $data['tanggal_lahir'],
            ':agama' => $data['agama'],
            ':email' => $data['email'],
            ':no_telepon' => $data['no_telepon'],
            ':pendidikan_terakhir' => $data['pendidikan_terakhir'],
            ':alamat_lengkap' => $data['alamat_lengkap'],
            ':status_pernikahan' => $data['status_pernikahan'],
            ':kontak_darurat' => $data['kontak_darurat'],
            ':riwayat_penyakit' => $data['riwayat_penyakit'],
            ':posisi' => $data['posisi'],
            ':id' => $id_kru
        ];

        if ($foto) { $params[':foto'] = $foto; }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function tambahKru($data, $foto = 'default-kru.png') {
        $sql = "INSERT INTO master_kru (nip, nik, nama_lengkap, tanggal_lahir, agama, foto, email, no_telepon, pendidikan_terakhir, alamat_lengkap, status_pernikahan, kontak_darurat, riwayat_penyakit, posisi, status_kru) 
                VALUES (:nip, :nik, :nama_lengkap, :tanggal_lahir, :agama, :foto, :email, :no_telepon, :pendidikan_terakhir, :alamat_lengkap, :status_pernikahan, :kontak_darurat, :riwayat_penyakit, :posisi, 'Aktif')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nip' => $data['nip'],
            ':nik' => $data['nik'],
            ':nama_lengkap' => $data['nama_lengkap'],
            ':tanggal_lahir' => $data['tanggal_lahir'],
            ':agama' => $data['agama'],
            ':foto' => $foto,
            ':email' => $data['email'],
            ':no_telepon' => $data['no_telepon'],
            ':pendidikan_terakhir' => $data['pendidikan_terakhir'],
            ':alamat_lengkap' => $data['alamat_lengkap'],
            ':status_pernikahan' => $data['status_pernikahan'],
            ':kontak_darurat' => $data['kontak_darurat'],
            ':riwayat_penyakit' => $data['riwayat_penyakit'],
            ':posisi' => $data['posisi']
        ]);
    }

    public function hapusKru($id_kru) {
        $stmt = $this->db->prepare("DELETE FROM master_kru WHERE id_kru = :id");
        return $stmt->execute([':id' => $id_kru]);
    }

    public function syncPenugasanKru($data) {
        $id_jadwal = $data['id_jadwal'];
        $tanggal_tugas = $data['tanggal_tugas'];
        $selectedKru = isset($data['id_kru']) ? $data['id_kru'] : [];

        $stmtDel = $this->db->prepare("DELETE FROM jadwal_penugasan_kru WHERE id_jadwal = :id_jadwal AND tanggal_tugas = :tanggal_tugas");
        $stmtDel->execute([':id_jadwal' => $id_jadwal, ':tanggal_tugas' => $tanggal_tugas]);

        if (!empty($selectedKru)) {
            $stmtIns = $this->db->prepare("INSERT INTO jadwal_penugasan_kru (id_jadwal, id_kru, tanggal_tugas) VALUES (:id_jadwal, :id_kru, :tanggal_tugas)");
            foreach ($selectedKru as $kruId) {
                $stmtIns->execute([
                    ':id_jadwal' => $id_jadwal,
                    ':id_kru' => $kruId,
                    ':tanggal_tugas' => $tanggal_tugas
                ]);
            }
        }
        return true;
    }

    public function getKruAssignedByDate($id_jadwal, $tanggal_tugas) {
        $stmt = $this->db->prepare("SELECT id_kru FROM jadwal_penugasan_kru WHERE id_jadwal = :id_jadwal AND tanggal_tugas = :tanggal");
        $stmt->execute([':id_jadwal' => $id_jadwal, ':tanggal' => $tanggal_tugas]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function hapusPenugasanKru($id_penugasan) {
        $stmt = $this->db->prepare("DELETE FROM jadwal_penugasan_kru WHERE id_penugasan = :id");
        return $stmt->execute([':id' => $id_penugasan]);
    }

    public function getAllUsers($nama = '', $nik = '', $kontak = '', $tanggal_lahir = '') {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if (!empty($nama)) {
            $sql .= " AND nama LIKE :nama";
            $params[':nama'] = '%' . $nama . '%';
        }
        if (!empty($nik)) {
            $sql .= " AND nik LIKE :nik";
            $params[':nik'] = '%' . $nik . '%';
        }
        if (!empty($kontak)) {
            // PERBAIKAN: Pisahkan parameter menjadi dua agar tidak bentrok di PDO
            $sql .= " AND (email LIKE :kontak_email OR no_hp LIKE :kontak_hp)";
            $params[':kontak_email'] = '%' . $kontak . '%';
            $params[':kontak_hp'] = '%' . $kontak . '%';
        }
        if (!empty($tanggal_lahir)) {
            $sql .= " AND tanggal_lahir = :tanggal_lahir";
            $params[':tanggal_lahir'] = $tanggal_lahir;
        }

        $sql .= " ORDER BY id_user DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hapusUser($id_user) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id_user = :id");
        return $stmt->execute([':id' => $id_user]);
    }

    public function getUserById($id_user) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id_user = :id LIMIT 1");
        $stmt->execute([':id' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahUser($data) {
        $sql = "INSERT INTO users (nama, nik, tanggal_lahir, email, no_hp, password, created_at) 
                VALUES (:nama, :nik, :tanggal_lahir, :email, :no_hp, :password, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nama' => $data['nama'],
            ':nik' => $data['nik'],
            ':tanggal_lahir' => $data['tanggal_lahir'],
            ':email' => $data['email'],
            ':no_hp' => $data['no_hp'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

    public function updateUser($id_user, $data) {
        if (!empty($data['password'])) {
            $sql = "UPDATE users SET nama = :nama, nik = :nik, tanggal_lahir = :tanggal_lahir, email = :email, no_hp = :no_hp, password = :password WHERE id_user = :id";
            $params = [
                ':nama' => $data['nama'],
                ':nik' => $data['nik'],
                ':tanggal_lahir' => $data['tanggal_lahir'],
                ':email' => $data['email'],
                ':no_hp' => $data['no_hp'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':id' => $id_user
            ];
        } else {
            $sql = "UPDATE users SET nama = :nama, nik = :nik, tanggal_lahir = :tanggal_lahir, email = :email, no_hp = :no_hp WHERE id_user = :id";
            $params = [
                ':nama' => $data['nama'],
                ':nik' => $data['nik'],
                ':tanggal_lahir' => $data['tanggal_lahir'],
                ':email' => $data['email'],
                ':no_hp' => $data['no_hp'],
                ':id' => $id_user
            ];
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateJadwal($data) {
        $sql = "UPDATE jadwal SET 
                id_kereta = :id_kereta, 
                id_koridor = :id_koridor, 
                stasiun_asal = :stasiun_asal, 
                stasiun_tujuan = :stasiun_tujuan, 
                stasiun_transit = :stasiun_transit, 
                jam_berangkat = :jam_berangkat, 
                jam_tiba = :jam_tiba, 
                harga = :harga, 
                tanggal_mulai = :tanggal_mulai, 
                tanggal_akhir = :tanggal_akhir, 
                jenis_jadwal = :jenis_jadwal 
                WHERE id_jadwal = :id_jadwal";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_kereta' => $data['id_kereta'],
            ':id_koridor' => $data['id_koridor'],
            ':stasiun_asal' => $data['stasiun_asal'],
            ':stasiun_tujuan' => $data['stasiun_tujuan'],
            ':stasiun_transit' => $data['stasiun_transit'],
            ':jam_berangkat' => $data['jam_berangkat'],
            ':jam_tiba' => $data['jam_tiba'],
            ':harga' => $data['harga'],
            ':tanggal_mulai' => $data['tanggal_mulai'],
            ':tanggal_akhir' => $data['tanggal_akhir'],
            ':jenis_jadwal' => $data['jenis_jadwal'],
            ':id_jadwal' => $data['id_jadwal']
        ]);
    }

    public function tambahStasiun($data) {
        $sql = "INSERT INTO stasiun (kode_stasiun, nama_stasiun, kota, julukan, image_url, is_top_destination) 
                VALUES (:kode, :nama, :kota, :julukan, :image, :is_top)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':kode' => $data['kode_stasiun'],
            ':nama' => $data['nama_stasiun'],
            ':kota' => $data['kota'],
            ':julukan' => $data['julukan'],
            ':image' => $data['image_url'],
            ':is_top' => $data['is_top_destination']
        ]);
    }

    public function updateStasiun($id, $data) {
        $sql = "UPDATE stasiun SET kode_stasiun = :kode, nama_stasiun = :nama, kota = :kota, julukan = :julukan, image_url = :image, is_top_destination = :is_top WHERE id_stasiun = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':kode' => $data['kode_stasiun'],
            ':nama' => $data['nama_stasiun'],
            ':kota' => $data['kota'],
            ':julukan' => $data['julukan'],
            ':image' => $data['image_url'],
            ':is_top' => $data['is_top_destination'],
            ':id' => $id
        ]);
    }

    public function hapusStasiun($id) {
        $stmt = $this->db->prepare("DELETE FROM stasiun WHERE id_stasiun = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getTotalPenumpangCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM penumpangs");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getTotalKruAktif() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM master_kru WHERE status_kru = 'Aktif'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getPenumpangsByJadwal($id_jadwal) {
        $sql = "SELECT p.*, r.tanggal_keberangkatan 
                FROM penumpangs p 
                JOIN reservasis r ON p.id_reservasi = r.id_reservasi 
                WHERE r.id_jadwal = :id_jadwal";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_jadwal' => $id_jadwal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}