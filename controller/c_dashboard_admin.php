<?php

session_start();

include_once __DIR__ . '/../models/m_peminjaman.php';


// =====================================================
// PROTEKSI HALAMAN: HANYA ADMIN
// =====================================================

if (
    !isset($_SESSION['data']) ||
    !isset($_SESSION['data']['role']) ||
    $_SESSION['data']['role'] !== 'admin'
) {
    echo "<script>
            alert('Silakan login sebagai admin terlebih dahulu.');
            window.location='../index.php';
          </script>";
    exit;
}

$peminjaman = new m_peminjaman();

$nama_admin = $_SESSION['data']['username'] ?? 'Admin';

// =====================================================
// WIDGET: PENGEMBALIAN YANG MENUNGGU KONFIRMASI ADMIN
// =====================================================

$stat_menunggu_konfirmasi = $peminjaman->hitung_menunggu_konfirmasi();
$stat_sedang_dipinjam     = $peminjaman->hitung_sedang_dipinjam_tanpa_pengajuan();
$stat_total_dikembalikan  = $peminjaman->hitung_total_dikembalikan();

$daftar_menunggu_konfirmasi = $peminjaman->riwayat_menunggu_konfirmasi(5);
?>
