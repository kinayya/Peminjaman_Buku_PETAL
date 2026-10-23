<?php

// =====================================================
// DATA GRAFIK & KARTU RINGKAS DASHBOARD ADMIN
// (di-include setelah c_dashboard_admin.php yang sudah
//  memproteksi halaman & menyiapkan statistik peminjaman)
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Supaya tombol "Back" setelah logout tidak menampilkan dashboard dari cache
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

include_once __DIR__ . '/../models/m_dashboard.php';

$dashboard = new m_dashboard();

$riwayat_user_admin = $dashboard->riwayat_peminjaman_user_admin(8);
$total_riwayat_user_admin = $dashboard->hitung_total_riwayat_user_admin();

$stat_total_buku = $dashboard->total_buku();
$stat_total_user = $dashboard->total_user();

$grafik = [
    'bulanan'  => $dashboard->peminjaman_per_bulan(6),
    'status'   => $dashboard->peminjaman_per_status(),
    'populer'  => $dashboard->buku_terpopuler(5),
    'kategori' => $dashboard->buku_per_kategori(),
];

// Penanda ada/tidaknya data, untuk menampilkan pesan kosong bila belum ada
$ada_data = [];

foreach ($grafik as $kunci => $g) {
    $ada_data[$kunci] = array_sum($g['data']) > 0;
}
