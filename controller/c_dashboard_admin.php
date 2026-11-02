<?php

include_once __DIR__ . '/auth.php';
auth_wajib_admin();

include_once __DIR__ . '/../models/m_peminjaman.php';



$peminjaman = new m_peminjaman();

// =====================================================
// KARTU STATISTIK PEMINJAMAN
// =====================================================

$stat_menunggu_konfirmasi = $peminjaman->hitung_menunggu_konfirmasi();
$stat_sedang_dipinjam     = $peminjaman->hitung_sedang_dipinjam_tanpa_pengajuan();
?>
