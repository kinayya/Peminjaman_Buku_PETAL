<?php

// =====================================================
// DATA GRAFIK & KARTU RINGKAS DASHBOARD ADMIN
// (di-include setelah c_dashboard_admin.php yang sudah
//  memproteksi halaman & menyiapkan statistik peminjaman)
// =====================================================

include_once __DIR__ . '/auth.php';
auth_wajib_admin();

include_once __DIR__ . '/../models/m_dashboard.php';

$dashboard = new m_dashboard();

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
