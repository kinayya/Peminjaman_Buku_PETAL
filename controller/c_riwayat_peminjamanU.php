<?php

session_start();

include_once __DIR__ . '/../models/m_peminjaman.php';

$peminjaman = new m_peminjaman();


// =====================================================
// PROTEKSI HALAMAN: HARUS LOGIN
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


// =====================================================
// AKSI: AJUKAN PENGEMBALIAN
// =====================================================

if (isset($_GET['aksi']) && $_GET['aksi'] === 'ajukan_pengembalian') {

    $id_peminjaman = (int) ($_GET['id'] ?? 0);

    if ($id_peminjaman > 0) {

        $result = $peminjaman->ajukan_pengembalian_user($id_peminjaman, $id_user);

        if ($result) {
            echo "<script>
                    alert('Pengembalian berhasil diajukan. Silakan serahkan buku ke petugas perpustakaan.');
                    window.location='../views/riwayat_peminjamanU.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal mengajukan pengembalian.');
                    window.location='../views/riwayat_peminjamanU.php';
                  </script>";
        }

    } else {
        echo "<script>
                alert('Data peminjaman tidak valid.');
                window.location='../views/riwayat_peminjamanU.php';
              </script>";
    }

    exit;
}


// =====================================================
// TAMPIL RIWAYAT + PENCARIAN SEDERHANA
// =====================================================

$keyword       = trim($_GET['keyword'] ?? '');
$semua_riwayat = $peminjaman->riwayat_peminjaman_user($id_user);

if (!empty($keyword)) {

    $kw = mb_strtolower($keyword);

    $riwayat = array_values(array_filter($semua_riwayat, function ($r) use ($kw) {
        return str_contains(mb_strtolower($r->judul ?? ''), $kw)
            || str_contains(mb_strtolower($r->status ?? ''), $kw);
    }));

} else {
    $riwayat = $semua_riwayat;
}
?>
