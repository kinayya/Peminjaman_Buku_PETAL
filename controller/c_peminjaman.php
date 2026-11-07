<?php

include_once __DIR__ . '/auth.php';
auth_wajib_user();

include_once __DIR__ . '/../models/m_peminjaman.php';

$peminjaman = new m_peminjaman();


// =====================================================
// PESAN GAGAL: tampilkan alert lalu kembali ke halaman
// sebelumnya (pilihan buku di form tetap utuh)
// =====================================================

function peminjaman_gagal($pesan)
{
    $js = json_encode(
        $pesan,
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    );

    echo "<script>
            alert($js);
            window.history.back();
          </script>";

    exit;
}


if (
    isset($_GET['aksi']) &&
    $_GET['aksi'] === 'tambah' &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {

    $id_user = (int) $_SESSION['data']['id_user'];


    // =====================================================
    // BUKU & JUMLAH EKSEMPLAR
    // Form mengirim satu id_buku dan jumlah (default 1).
    // =====================================================

    $id_buku = (int) ($_POST['id_buku'] ?? 0);
    $jumlah  = (int) ($_POST['jumlah'] ?? 1);

    if ($id_buku <= 0) {
        peminjaman_gagal('Buku tidak valid.');
    }

    if ($jumlah < 1) {
        peminjaman_gagal('Jumlah buku minimal 1.');
    }


    // =====================================================
    // VALIDASI BUKU
    // =====================================================

    $info = $peminjaman->info_buku($id_buku);

    if (!$info) {
        peminjaman_gagal('Buku tidak valid.');
    }

    $judul = $info['judul'];
    $stok  = (int) $info['stok'];

    if ($stok <= 0) {
        peminjaman_gagal(
            "Maaf, stok buku \"$judul\" sedang habis."
        );
    }

    if ($jumlah > $stok) {
        peminjaman_gagal(
            "Maaf, stok buku \"$judul\" hanya tersisa $stok."
        );
    }

    if ($peminjaman->cek_peminjaman_aktif($id_user, $id_buku)) {
        peminjaman_gagal(
            "Kamu masih memiliki peminjaman aktif untuk buku \"$judul\"."
        );
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
        peminjaman_gagal('Tanggal pinjam tidak boleh sebelum hari ini.');
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
        $jumlah,
        $tanggal_pinjam,
        $tanggal_kembali
    );

    if ($result) {

        $pesan = $jumlah > 1
            ? "$jumlah buku berhasil diajukan untuk dipinjam."
            : 'Buku Berhasil Dipinjam.';

        $js = json_encode($pesan);

        echo "<script>
                alert($js);
                window.location='../views/daftar_bukuU.php';
              </script>";

    } else {

        peminjaman_gagal('Buku Gagal Dipinjam.');
    }

    exit;
}
?>