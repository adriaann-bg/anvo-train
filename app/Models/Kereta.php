<?php
// app/Models/Kereta.php

class Kereta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllKereta() {
        $stmt = $this->db->prepare("SELECT * FROM kereta ORDER BY id_kereta DESC");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as &$r) {
            // Cek apakah data di database berbentuk JSON atau teks biasa
            $decodedKelas = json_decode($r['jenis_kelas'], true);
            if (is_array($decodedKelas)) {
                $r['jenis_kelas_arr'] = $decodedKelas;
            } else {
                $r['jenis_kelas_arr'] = [$r['jenis_kelas']];
            }

            $decodedLayout = json_decode($r['layout_kursi'], true);
            if (is_array($decodedLayout)) {
                $r['layout_kursi_arr'] = $decodedLayout;
            } else {
                $r['layout_kursi_arr'] = [$r['layout_kursi']];
            }
        }
        return $results;
    }

    public function getKeretaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kereta WHERE id_kereta = :id");
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            $r['jenis_kelas_arr'] = json_decode($r['jenis_kelas'], true) ?? [$r['jenis_kelas']];
            $r['layout_kursi_arr'] = json_decode($r['layout_kursi'], true) ?? [$r['layout_kursi']];
        }
        return $r;
    }

    public function tambahKereta($post) {
        $kelasJson = json_encode($post['jenis_kelas'] ?? []);
        $layoutJson = json_encode($post['layout_kursi'] ?? []);

        $sql = "INSERT INTO kereta (nama_kereta, jenis_kelas, jenis_mesin, kecepatan_maksimal, status_operasional, layout_kursi, kapasitas_kursi) 
                VALUES (:nama, :kelas, :mesin, :kecepatan, :status, :layout, :kapasitas)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nama' => $post['nama_kereta'],
            ':kelas' => $kelasJson,
            ':mesin' => $post['jenis_mesin'],
            ':kecepatan' => $post['kecepatan_maksimal'],
            ':status' => $post['status_operasional'],
            ':layout' => $layoutJson,
            ':kapasitas' => $post['kapasitas_kursi'] ?? 100
        ]);
    }

    public function updateKereta($post) {
        $kelasJson = json_encode($post['jenis_kelas'] ?? []);
        $layoutJson = json_encode($post['layout_kursi'] ?? []);

        $sql = "UPDATE kereta SET nama_kereta = :nama, jenis_kelas = :kelas, jenis_mesin = :mesin, 
                kecepatan_maksimal = :kecepatan, status_operasional = :status, layout_kursi = :layout, kapasitas_kursi = :kapasitas 
                WHERE id_kereta = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $post['id_kereta'],
            ':nama' => $post['nama_kereta'],
            ':kelas' => $kelasJson,
            ':mesin' => $post['jenis_mesin'],
            ':kecepatan' => $post['kecepatan_maksimal'],
            ':status' => $post['status_operasional'],
            ':layout' => $layoutJson,
            ':kapasitas' => $post['kapasitas_kursi'] ?? 100
        ]);
    }

    public function hapusKereta($id) {
        $stmt = $this->db->prepare("DELETE FROM kereta WHERE id_kereta = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getKelasKereta() {
        $stmt = $this->db->prepare("SELECT jenis_kelas FROM kereta");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $allClasses = [];
        foreach ($results as $r) {
            $decoded = json_decode($r['jenis_kelas'], true);
            if (is_array($decoded)) {
                foreach ($decoded as $cls) {
                    $allClasses[] = trim($cls);
                }
            } else {
                $allClasses[] = trim($r['jenis_kelas']);
            }
        }
        
        // Hapus duplikat dan susun dalam bentuk array asosiatif berindeks 'kelas'
        $uniqueClasses = array_unique(array_filter($allClasses));
        $formatted = [];
        foreach ($uniqueClasses as $cls) {
            $formatted[] = ['kelas' => $cls];
        }
        return $formatted;
    }
}