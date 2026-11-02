<?php

include_once __DIR__ . '/auth.php';
auth_wajib_user();

include_once __DIR__ . '/../models/m_peminjaman.php';

$peminjaman = new m_peminjaman();



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

if ($keyword !== '') {

    $kw     = mb_strtolower($keyword);
    $kw_tgl = str_replace('/', '-', $kw);   // 29/10/2026 -> 29-10-2026

    // Format tanggal yang bisa diketik: 2026-10-29 atau 29-10-2026
    $format_tgl = function ($tgl) {
        if (empty($tgl) || strtotime($tgl) === false) {
            return '';
        }
        return $tgl . ' ' . date('d-m-Y', strtotime($tgl));
    };

    // Label status persis seperti yang tampil di tabel
    $label_cari = [
        'pengajuan'    => 'Menunggu Persetujuan',
        'disetujui'    => 'Disetujui, Ambil di Perpustakaan',
        'dipinjam'     => 'Sedang Dipinjam',
        'dikembalikan' => 'Sudah Dikembalikan',
        'ditolak'      => 'Ditolak',
    ];

    $riwayat = array_values(array_filter($semua_riwayat, function ($r) use ($kw, $kw_tgl, $format_tgl, $label_cari) {

        $status_r    = $r->status ?? '';
        $teks_status = mb_strtolower($status_r . ' ' . ($label_cari[$status_r] ?? ''));

        if ($status_r === 'dipinjam' && !empty($r->id_pengembalian)) {
            $teks_status .= ' menunggu konfirmasi admin';
        }

        $teks_tanggal = $format_tgl($r->tanggal_pinjam ?? '')
            . ' ' . $format_tgl($r->tanggal_kembali ?? '');

        if (($r->status ?? '') === 'dikembalikan') {
            $teks_tanggal .= ' ' . $format_tgl($r->tanggal_pengembalian ?? '');
        }

        return str_contains(mb_strtolower($r->judul ?? ''), $kw)
            || str_contains($teks_status, $kw)
            || str_contains($teks_tanggal, $kw_tgl);
    }));

} else {
    $riwayat = $semua_riwayat;
}
?>
