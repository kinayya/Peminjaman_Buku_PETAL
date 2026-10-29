<?php
// =====================================================
// CONTROLLER KATALOG BUKU UNTUK USER (SISWA)
// Menangani: pencarian, pagination, dan status login
// Semua akses data lewat model 'buku' (models/m_bukuA.php)
// =====================================================

session_start();

include_once __DIR__ . '/../models/m_bukuA.php';

$buku = new buku();

// =====================================================
// STATUS LOGIN USER (dipakai untuk validasi tombol pinjam)
// =====================================================
$is_logged_in = isset($_SESSION['data']['id_user']);
$id_user      = $is_logged_in ? (int) $_SESSION['data']['id_user'] : 0;

// =====================================================
// PENCARIAN & PAGINATION
// =====================================================
$limit = 8;
$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$start = ($page - 1) * $limit;

$keyword = trim($_GET['keyword'] ?? '');

// Filter kategori (0 = semua kategori) + daftar kategori untuk dropdown
$id_kategori   = (int) ($_GET['kategori'] ?? 0);
$data_kategori = $buku->tampil_kategori();

// Catatan: pakai !== '' supaya kata kunci "0" (stok 0) tetap bisa dicari
if ($keyword !== '' || $id_kategori > 0) {
    // Mengambil data terfilter jika kata kunci / kategori dipilih
    $total_data = $buku->hitung_total_buku_cari($keyword, $id_kategori);
    $books      = $buku->tampil_data_buku_cari_page($keyword, $start, $limit, $id_kategori);
} else {
    // Mengambil seluruh data jika pencarian kosong
    $total_data = $buku->hitung_total_buku();
    $books      = $buku->tampil_data_buku_page($start, $limit);
}

$totalPages = (int) ceil($total_data / $limit);
if ($totalPages < 1) {
    $totalPages = 1;
}
