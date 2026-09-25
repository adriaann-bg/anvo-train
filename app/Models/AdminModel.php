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

        // Filter Tanggal
        if (!empty($tanggal)) {
            $sql .= " AND :tanggal BETWEEN jadwal.tanggal_mulai AND jadwal.tanggal_akhir";
            $params[':tanggal'] = $tanggal;
        }

        // Filter Kelas Kereta
        if (!empty($kelas)) {
            $sql .= " AND kereta.jenis_kelas LIKE :kelas";
            $params[':kelas'] = '%' . $kelas . '%';
        }

        // Filter Rute 2 Arah (Asal & Tujuan)
        if (!empty($asal) && !empty($tujuan)) {
            $sql .= " AND (
                        (jadwal.stasiun_asal LIKE :asal AND jadwal.stasiun_tujuan LIKE :tujuan) 
                        OR 
                        (jadwal.stasiun_asal LIKE :tujuan_rev AND jadwal.stasiun_tujuan LIKE :asal_rev)
                      )";
            $params[':asal'] = '%' . $asal . '%';
            $params[':tujuan'] = '%' . $tujuan . '%';
            $params[':tujuan_rev'] = '%' . $tujuan . '%';
            $params[':asal_rev'] = '%' . $asal . '%';
        } elseif (!empty($asal)) {
            // PERBAIKAN: Gunakan parameter unik (:asal1, :asal2, :asal3)
            $sql .= " AND (jadwal.stasiun_asal LIKE :asal1 OR jadwal.stasiun_tujuan LIKE :asal2 OR jadwal.stasiun_transit LIKE :asal3)";
            $params[':asal1'] = '%' . $asal . '%';
            $params[':asal2'] = '%' . $asal . '%';
            $params[':asal3'] = '%' . $asal . '%';
        } elseif (!empty($tujuan)) {
            // PERBAIKAN: Gunakan parameter unik (:tujuan1, :tujuan2, :tujuan3)
            $sql .= " AND (jadwal.stasiun_asal LIKE :tujuan1 OR jadwal.stasiun_tujuan LIKE :tujuan2 OR jadwal.stasiun_transit LIKE :tujuan3)";
            $params[':tujuan1'] = '%' . $tujuan . '%';
            $params[':tujuan2'] = '%' . $tujuan . '%';
            $params[':tujuan3'] = '%' . $tujuan . '%';
        }

        $sql .= " ORDER BY jadwal.tanggal_mulai DESC, jadwal.jam_berangkat ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    // Ambil data kru berdasarkan ID
    public function getKruById($id_kru) {
        $stmt = $this->db->prepare("SELECT * FROM master_kru WHERE id_kru = :id");
        $stmt->execute([':id' => $id_kru]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update data kru
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

        if ($foto) {
            $params[':foto'] = $foto;
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Update fungsi tambahKru agar support agama & foto
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

    // Sinkronisasi penugasan kru (Check = tambah, Uncheck = hapus otomatis, cegah duplikasi)
    public function syncPenugasanKru($data) {
        $id_jadwal = $data['id_jadwal'];
        $tanggal_tugas = $data['tanggal_tugas'];
        $selectedKru = isset($data['id_kru']) ? $data['id_kru'] : [];

        // 1. Hapus penugasan lama pada tanggal tersebut untuk jadwal ini
        $stmtDel = $this->db->prepare("DELETE FROM jadwal_penugasan_kru WHERE id_jadwal = :id_jadwal AND tanggal_tugas = :tanggal_tugas");
        $stmtDel->execute([':id_jadwal' => $id_jadwal, ':tanggal_tugas' => $tanggal_tugas]);

        // 2. Masukkan ulang kru yang dicentang
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

    // Ambil ID kru yang sudah bertugas pada tanggal tertentu
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
            $sql .= " AND (email LIKE :kontak OR no_hp LIKE :kontak)";
            $params[':kontak'] = '%' . $kontak . '%';
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

    // Ambil user berdasarkan ID
    public function getUserById($id_user) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id_user = :id LIMIT 1");
        $stmt->execute([':id' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah user baru oleh admin
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

    // Update user oleh admin
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
}