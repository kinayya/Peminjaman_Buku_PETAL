<?php

include_once __DIR__ . '/koneksi.php';

class buku {

    private $conn;

    public function __construct() {
        $db = new Koneksi();
        $this->conn = $db->koneksi;
    }


    // =====================================================
    // TAMPIL SEMUA DATA BUKU
    // =====================================================
    public function tampil_data_buku() {

        $sql = "SELECT buku.*, kategori.nama_kategori
                FROM buku
                LEFT JOIN kategori 
                ON buku.id_kategori = kategori.id_kategori
                ORDER BY buku.id_buku DESC";

        $query = mysqli_query($this->conn, $sql);

        $result = [];

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }

        return $result;
    }


    // =====================================================
    // TAMPIL DATA KATEGORI
    // =====================================================
    public function tampil_kategori() {

        $sql = "SELECT * FROM kategori
                ORDER BY nama_kategori ASC";

        $query = mysqli_query($this->conn, $sql);

        $result = [];

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }

        return $result;
    }


    // =====================================================
    // TAMBAH DATA BUKU
    // =====================================================
    public function tambah_data_buku(
        $judul,
        $pengarang,
        $penerbit,
        $tahun_terbit,
        $stok,
        $sinopsis,
        $link_gambar,
        $id_kategori
    ) {

        $sql = "INSERT INTO buku
                (
                    judul,
                    pengarang,
                    penerbit,
                    tahun_terbit,
                    stok,
                    sinopsis,
                    link_gambar,
                    id_kategori
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sssisssi",
            $judul,
            $pengarang,
            $penerbit,
            $tahun_terbit,
            $stok,
            $sinopsis,
            $link_gambar,
            $id_kategori
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // AMBIL SATU DATA BUKU BERDASARKAN ID
    // =====================================================
    public function tampil_satu_buku($id_buku) {

        $sql = "SELECT buku.*, kategori.nama_kategori
                FROM buku
                LEFT JOIN kategori
                ON buku.id_kategori = kategori.id_kategori
                WHERE buku.id_buku = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_buku);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_object();

        $stmt->close();

        return $data;
    }


    // =====================================================
    // EDIT DATA BUKU
    // =====================================================
    public function edit_buku(
        $id_buku,
        $judul,
        $pengarang,
        $penerbit,
        $tahun_terbit,
        $stok,
        $sinopsis,
        $link_gambar,
        $id_kategori
    ) {

        $sql = "UPDATE buku SET
                    judul = ?,
                    pengarang = ?,
                    penerbit = ?,
                    tahun_terbit = ?,
                    stok = ?,
                    sinopsis = ?,
                    link_gambar = ?,
                    id_kategori = ?
                WHERE id_buku = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sssisssii",
            $judul,
            $pengarang,
            $penerbit,
            $tahun_terbit,
            $stok,
            $sinopsis,
            $link_gambar,
            $id_kategori,
            $id_buku
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // HAPUS DATA BUKU
    // =====================================================
    public function hapus_data_buku($id_buku) {

        $sql = "DELETE FROM buku WHERE id_buku = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id_buku);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // HITUNG TOTAL BUKU
    // =====================================================
    public function hitung_total_buku() {

        $sql = "SELECT COUNT(*) AS total FROM buku";

        $query = mysqli_query($this->conn, $sql);

        $data = mysqli_fetch_assoc($query);

        return $data['total'];
    }


    // =====================================================
    // TAMPIL BUKU DENGAN PAGINATION
    // =====================================================
    public function tampil_data_buku_page($start, $limit) {

        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT buku.*, kategori.nama_kategori
                FROM buku
                LEFT JOIN kategori
                ON buku.id_kategori = kategori.id_kategori
                ORDER BY buku.id_buku DESC
                LIMIT $start, $limit";

        $query = mysqli_query($this->conn, $sql);

        $result = [];

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }

        return $result;
    }


    // =====================================================
    // HITUNG TOTAL BUKU BERDASARKAN KATA KUNCI (SEARCH)
    // =====================================================
    public function hitung_total_buku_cari($keyword) {

        $param_keyword = "%$keyword%";

        $sql = "SELECT COUNT(*) AS total 
                FROM buku 
                WHERE judul LIKE ? OR pengarang LIKE ? OR penerbit LIKE ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("sss", $param_keyword, $param_keyword, $param_keyword);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $result['total'] ?? 0;
    }


    // =====================================================
    // TAMPIL BUKU DENGAN PENCARIAN & PAGINATION
    // =====================================================
    public function tampil_data_buku_cari_page($keyword, $start, $limit) {

        $param_keyword = "%$keyword%";
        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT buku.*, kategori.nama_kategori 
                FROM buku 
                LEFT JOIN kategori 
                ON buku.id_kategori = kategori.id_kategori 
                WHERE buku.judul LIKE ? OR buku.pengarang LIKE ? OR buku.penerbit LIKE ? 
                ORDER BY buku.id_buku DESC 
                LIMIT ?, ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("sssii", $param_keyword, $param_keyword, $param_keyword, $start, $limit);
        $stmt->execute();

        $result_set = $stmt->get_result();

        $result = [];

        while ($data = $result_set->fetch_object()) {
            $result[] = $data;
        }

        $stmt->close();

        return $result;
    }

}

?>