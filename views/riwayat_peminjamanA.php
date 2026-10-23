<?php

include_once __DIR__ . '/../controller/c_grafik_admin.php';

$keyword  = trim($_GET['keyword'] ?? '');
$kw_param = $keyword !== '' ? '&keyword=' . urlencode($keyword) : '';

// Ambil seluruh riwayat (mengikuti hasil pencarian).
// Tabel di layar dipotong per halaman, sedangkan laporan cetak memakai semuanya.
$semua_riwayat = $dashboard->riwayat_peminjaman_user_admin(5000, $keyword);

$label_status = [
    'pengajuan'    => 'Menunggu Persetujuan',
    'disetujui'    => 'Disetujui',
    'dipinjam'     => 'Sedang Dipinjam',
    'dikembalikan' => 'Dikembalikan',
    'ditolak'      => 'Ditolak'
];

// =====================================================
// PAGINATION
// =====================================================
$per_halaman    = 10;
$jumlah_riwayat = count($semua_riwayat);
$total_halaman  = max(1, (int) ceil($jumlah_riwayat / $per_halaman));

$halaman = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$halaman = min(max(1, $halaman), $total_halaman);

$start            = ($halaman - 1) * $per_halaman;
$riwayat_halaman  = array_slice($semua_riwayat, $start, $per_halaman);

// Hanya 2 nomor halaman yang tampil sekaligus: (1 2), (3 4), (5 6), dst.
$blok_awal  = (int) floor(($halaman - 1) / 2) * 2 + 1;
$blok_akhir = min($blok_awal + 1, $total_halaman);

// =====================================================
// BARIS TABEL (dipakai tabel layar & tabel cetak)
// =====================================================
function baris_riwayat($r, $no, $label_status)
{
    $status = $r->status ?? 'pengajuan';
    $label  = $label_status[$status] ?? ucfirst($status);
    ?>
    <tr>
        <td><?= (int) $no; ?></td>
        <td>
            <span class="nama-user">
                <?= htmlspecialchars($r->username ?? '(user dihapus)', ENT_QUOTES, 'UTF-8'); ?>
            </span>
        </td>
        <td><?= htmlspecialchars($r->Email ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
        <td>
            <span class="judul-buku">
                <?= htmlspecialchars($r->judul ?? '(buku dihapus)', ENT_QUOTES, 'UTF-8'); ?>
            </span>
        </td>
        <td><?= htmlspecialchars($r->tanggal_pinjam ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?= htmlspecialchars($r->tanggal_kembali ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
        <td>
            <span class="status-badge status-<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>">
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
            </span>
        </td>
    </tr>
    <?php
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat User - Progresif Library</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/riwayat_peminjamanA.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- =====================================================
     NAVBAR
===================================================== -->
<nav class="navbar">
    <img src="../assets/logo.svg" class="logo" alt="Logo PETAL">

    <div class="nav-links">
        <a href="dashboard_admin.php">Dashboard</a>
        <a href="daftar_bukuA.php">Daftar Buku</a>
        <a href="daftar_user.php">Daftar Pengguna</a>
        <a href="daftar_kategori.php">Daftar Kategori</a>
        <a href="peminjamanA.php">Peminjaman</a>
        <a href="pengembalianA.php">Pengembalian</a>
        <a href="riwayat_peminjamanA.php" class="active">Riwayat Peminjaman</a>
    </div>
</nav>

<!-- =====================================================
     CONTENT
===================================================== -->
<div class="container">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <!-- SEARCH FORM -->
        <form method="GET" action="riwayat_peminjamanA.php" class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
                type="text"
                name="keyword"
                value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="Cari riwayat lalu tekan Enter..."
            >
        </form>

        <!-- BUTTON CETAK -->
        <button type="button" class="btn-cetak" onclick="cetakLaporan()">
            <i class="fa-solid fa-print"></i> Cetak Laporan
        </button>
    </div>

    <?php if ($keyword !== ''): ?>
        <a href="riwayat_peminjamanA.php" class="reset-search">
            <i class="fa-solid fa-xmark"></i> Hapus pencarian
        </a>
    <?php endif; ?>

    <!-- =================================================
         TABEL DI LAYAR (PER HALAMAN)
    ================================================= -->
    <div class="screen-only">
        <div class="table-wrapper">
            <table id="tableRiwayat">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($riwayat_halaman)): ?>
                    <?php foreach ($riwayat_halaman as $i => $r): ?>
                        <?php baris_riwayat($r, $start + $i + 1, $label_status); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- DATA KOSONG -->
                    <tr class="row-empty">
                        <td colspan="7" class="empty-data">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <br>
                            <?= $keyword !== '' ? 'Riwayat tidak ditemukan.' : 'Belum ada riwayat peminjaman.'; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION: hanya 2 nomor halaman per tampilan -->
        <?php if ($total_halaman > 1): ?>
            <div class="pagination">
                <?php if ($halaman > 1): ?>
                    <a href="?halaman=<?= ($halaman - 1) . $kw_param; ?>" title="Halaman Sebelumnya">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php for ($i = $blok_awal; $i <= $blok_akhir; $i++): ?>
                    <a href="?halaman=<?= $i . $kw_param; ?>" class="<?= $i == $halaman ? 'active' : ''; ?>">
                        <?= $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($halaman < $total_halaman): ?>
                    <a href="?halaman=<?= ($halaman + 1) . $kw_param; ?>" title="Halaman Berikutnya">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- =================================================
         AREA LAPORAN (HANYA TAMPIL SAAT DICETAK)
         Berisi seluruh riwayat, bukan hanya halaman aktif.
    ================================================= -->
    <section class="report-area" id="areaLaporan">
        <div class="report-heading">
            <div class="report-logo-title">
                <img src="../assets/logo.svg" alt="Logo PETAL">
                <div>
                    <h2>PROGRESIF LIBRARY</h2>
                    <p>Laporan Riwayat Peminjaman User</p>
                </div>
            </div>
            <div class="report-date">
                Dicetak: <?= date('d/m/Y H:i'); ?>
            </div>
        </div>

        <div class="table-wrapper">
            <table id="tableRiwayatCetak">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($semua_riwayat)): ?>
                    <?php foreach ($semua_riwayat as $i => $r): ?>
                        <?php baris_riwayat($r, $i + 1, $label_status); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-data">Belum ada riwayat peminjaman.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

<script>
function cetakLaporan() {
    window.print();
}
</script>

</body>
</html>
