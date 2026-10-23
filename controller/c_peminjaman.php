<?php

session_start();

include_once __DIR__ . '/../models/m_peminjaman.php';

$peminjaman = new m_peminjaman();

if (
    isset($_GET['aksi']) &&
    $_GET['aksi'] === 'tambah' &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {

    // =====================================================
    // CEK LOGIN
    // =====================================================

    if (
        !isset($_SESSION['data']) ||
        !isset($_SESSION['data']['id_user'])
    ) {

        echo "<script>
                alert('Silakan login terlebih dahulu.');
                window.location='../index.php';
              </script>";

        exit;
    }


    $id_user = (int) $_SESSION['data']['id_user'];

    $id_buku = (int) (
        $_POST['id_buku'] ?? 0
    );


    // =====================================================
    // VALIDASI ID BUKU
    // =====================================================

    if ($id_buku <= 0) {

        echo "<script>
                alert('Buku tidak valid.');
                window.history.back();
              </script>";

        exit;
    }


    // =====================================================
    // CEK STOK
    // =====================================================

    $stok = $peminjaman->cek_stok($id_buku);

    if ($stok <= 0) {

        echo "<script>
                alert('Maaf, stok buku sedang habis.');
                window.history.back();
              </script>";

        exit;
    }


    // =====================================================
    // CEK PEMINJAMAN AKTIF
    // =====================================================

    if (
        $peminjaman->cek_peminjaman_aktif(
            $id_user,
            $id_buku
        )
    ) {

        echo "<script>
                alert('Kamu masih memiliki peminjaman aktif untuk buku ini.');
                window.history.back();
              </script>";

        exit;
    }


    // =====================================================
    // TANGGAL
    // =====================================================

    $tanggal_pinjam_input = trim($_POST['tanggal_pinjam'] ?? '');
    $hari_ini             = date('Y-m-d');

    // Validasi format & pastikan tidak memilih tanggal yang sudah lewat
    $format_valid = (bool) DateTime::createFromFormat('Y-m-d', $tanggal_pinjam_input);

    if (empty($tanggal_pinjam_input) || !$format_valid) {
        $tanggal_pinjam = $hari_ini;
    } elseif ($tanggal_pinjam_input < $hari_ini) {
        echo "<script>
                alert('Tanggal pinjam tidak boleh sebelum hari ini.');
                window.history.back();
              </script>";
        exit;
    } else {
        $tanggal_pinjam = $tanggal_pinjam_input;
    }

    // Batas pengembalian 7 hari setelah tanggal pinjam
    $tanggal_kembali = date(
        'Y-m-d',
        strtotime($tanggal_pinjam . ' +7 days')
    );


    // =====================================================
    // SIMPAN
    // =====================================================

    $result = $peminjaman->tambah_peminjaman(
        $id_user,
        $id_buku,
        $tanggal_pinjam,
        $tanggal_kembali
    );


    if ($result) {

        echo "<script>
                alert('Buku Berhasil Dipinjam.');
                window.location='../views/daftar_bukuU.php';
              </script>";

    } else {

        echo "<script>
                alert('Buku Gagal Dipinjam.');
                window.history.back();
              </script>";
    }

    exit;
}
?>