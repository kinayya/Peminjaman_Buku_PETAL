<?php

include_once __DIR__ . '/koneksi.php';

class kategori
{
    private $conn;

    public function __construct()
    {
        $db = new Koneksi();
        $this->conn = $db->koneksi;
    }


    // =====================================================
    // TAMPIL SEMUA DATA KATEGORI
    // =====================================================
    public function tampil_data_kategori()
    {
        $sql = "SELECT *
                FROM kategori
                ORDER BY id_kategori DESC";

        $query = mysqli_query($this->conn, $sql);

        $result = [];

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }

        return $result;
    }


    // =====================================================
    // TAMPIL KATEGORI DENGAN PAGINATION
    // =====================================================
    public function tampil_data_kategori_page($start, $limit)
    {
        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT *
                FROM kategori
                ORDER BY id_kategori DESC
                LIMIT $start, $limit";

        $query = mysqli_query($this->conn, $sql);

        $result = [];

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }

        return $result;
    }


    // =====================================================
    // TAMBAH KATEGORI
    // =====================================================
    public function tambah_data_kategori($nama_kategori)
    {
        $sql = "INSERT INTO kategori (nama_kategori)
                VALUES (?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "s",
            $nama_kategori
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // AMBIL SATU KATEGORI
    // =====================================================
    public function tampil_satu_kategori($id_kategori)
    {
        $sql = "SELECT *
                FROM kategori
                WHERE id_kategori = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param(
            "i",
            $id_kategori
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_object();

        $stmt->close();

        return $data;
    }


    // =====================================================
    // EDIT KATEGORI
    // =====================================================
    public function edit_kategori(
        $id_kategori,
        $nama_kategori
    )
    {
        $sql = "UPDATE kategori
                SET nama_kategori = ?
                WHERE id_kategori = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "si",
            $nama_kategori,
            $id_kategori
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // HAPUS KATEGORI
    // =====================================================
    public function hapus_data_kategori($id_kategori)
    {
        $sql = "DELETE FROM kategori
                WHERE id_kategori = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "i",
            $id_kategori
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // HITUNG TOTAL KATEGORI
    // =====================================================
    public function hitung_total_kategori()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM kategori";

        $query = mysqli_query($this->conn, $sql);

        if (!$query) {
            return 0;
        }

        $data = mysqli_fetch_assoc($query);

        return (int) ($data['total'] ?? 0);
    }


    // =====================================================
    // HITUNG TOTAL HASIL PENCARIAN
    // =====================================================
    public function hitung_total_kategori_cari($keyword)
    {
        $keyword = "%{$keyword}%";

        $sql = "SELECT COUNT(*) AS total
                FROM kategori
                WHERE nama_kategori LIKE ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param(
            "s",
            $keyword
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_assoc();

        $stmt->close();

        return (int) ($data['total'] ?? 0);
    }


    // =====================================================
    // SEARCH + PAGINATION
    // =====================================================
    public function tampil_data_kategori_cari_page(
        $keyword,
        $start,
        $limit
    )
    {
        $keyword = "%{$keyword}%";

        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT *
                FROM kategori
                WHERE nama_kategori LIKE ?
                ORDER BY id_kategori DESC
                LIMIT ?, ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param(
            "sii",
            $keyword,
            $start,
            $limit
        );

        $stmt->execute();

        $query = $stmt->get_result();

        $result = [];

        while ($data = $query->fetch_object()) {
            $result[] = $data;
        }

        $stmt->close();

        return $result;
    }
}

?>