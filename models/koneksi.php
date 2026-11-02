<?php
// fungsinya untuk menghubungkan data ke database

class Koneksi {
    private $host = "localhost",
            $username = "root",
            $pass = "",
            $db = "Peminjaman_Buku_PETAL";

    public $koneksi;

    function __construct() {
        // PHP 8.1+ melempar exception saat koneksi gagal; tangkap supaya
        // halaman tidak kosong dan penyebabnya jelas.
        try {
            $this->koneksi = mysqli_connect(
                $this->host,
                $this->username,
                $this->pass,
                $this->db
            );
        } catch (mysqli_sql_exception $e) {
            $this->koneksi = false;
        }

        if (!$this->koneksi) {
            http_response_code(500);
            exit("Koneksi ke database gagal. Pastikan MySQL/MariaDB sudah aktif dan database '{$this->db}' sudah diimpor dari Peminjaman_Buku_PETAL.sql.");
        }
    }
}


$koneksi = new Koneksi();


$conn = $koneksi->koneksi;
