<?php

include_once __DIR__ . '/../controller/c_pengembalianA.php';

$keyword  = $_GET['keyword'] ?? '';
$kw_param = !empty($keyword) ? '&keyword=' . urlencode($keyword) : '';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pengembalian - Progresif Library</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/peminjamanA.css">
    <link rel="stylesheet" href="../css/pengembalianA.css">

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
        <a href="pengembalianA.php" class="active">Pengembalian</a>
        <a href="riwayat_peminjamanA.php">Riwayat Peminjaman</a>
    </div>
</nav>


<!-- =====================================================
     CONTENT
===================================================== -->

<div class="container">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <!-- SEARCH FORM -->
        <form method="GET" action="pengembalianA.php" class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
                type="text"
                name="keyword"
                value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="Cari nama peminjam / judul buku..."
            >
        </form>
    </div>

    <!-- TABLE DATA PENGEMBALIAN -->
    <div class="table-wrapper">
        <table id="tablePeminjaman">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Buku</th>
                    <th>Peminjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Tenggat Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($data_pengembalian)): ?>
                    <?php foreach ($data_pengembalian as $index => $p): ?>
                        <?php
                            $status        = $p->status ?? 'dipinjam';
                            $sudahDiajukan = !empty($p->id_pengembalian);

                            if ($status === 'dipinjam' && $sudahDiajukan) {
                                $badgeClass = 'badge-menunggu';
                                $labelText  = 'Menunggu Konfirmasi';
                            } elseif ($status === 'dipinjam') {
                                $badgeClass = 'badge-dipinjam';
                                $labelText  = 'Sedang Dipinjam';
                            } else {
                                $badgeClass = 'badge-dikembalikan';
                                $labelText  = 'Sudah Dikembalikan';
                            }
                        ?>
                        <tr>
                            <!-- NO -->
                            <td><?= ($start ?? 0) + $index + 1; ?></td>

                            <!-- BUKU -->
                            <td>
                                <div class="buku-cell">
                                    <?php if (!empty($p->link_gambar)): ?>
                                        <img
                                            src="<?= htmlspecialchars($p->link_gambar, ENT_QUOTES, 'UTF-8'); ?>"
                                            class="book-image"
                                            alt="Cover Buku"
                                            onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';"
                                        >
                                        <span class="no-image" style="display:none;">
                                            <i class="fa-solid fa-book"></i>
                                        </span>
                                    <?php else: ?>
                                        <span class="no-image">
                                            <i class="fa-solid fa-book"></i>
                                        </span>
                                    <?php endif; ?>

                                    <span class="judul-buku">
                                        <?= htmlspecialchars($p->judul ?? '(buku telah dihapus)', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </div>
                            </td>

                            <!-- PEMINJAM -->
                            <td>
                                <span class="nama-peminjam">
                                    <?= htmlspecialchars($p->username ?? '(user dihapus)', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span class="email-peminjam">
                                    <?= htmlspecialchars($p->Email ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>

                            <!-- TANGGAL PINJAM -->
                            <td><?= htmlspecialchars($p->tanggal_pinjam ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                            <!-- TENGGAT KEMBALI -->
                            <td><?= htmlspecialchars($p->tanggal_kembali ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                            <!-- STATUS -->
                            <td>
                                <span class="badge-status <?= $badgeClass; ?>">
                                    <?= htmlspecialchars($labelText, ENT_QUOTES, 'UTF-8'); ?>
                                </span>

                                <?php if ($status === 'dikembalikan'): ?>
                                    <span class="keterangan-kecil">
                                        <?= htmlspecialchars($p->tanggal_pengembalian ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                        <?php if (!empty($p->keterangan_pengembalian)): ?>
                                            &middot; <?= htmlspecialchars($p->keterangan_pengembalian, ENT_QUOTES, 'UTF-8'); ?>
                                        <?php endif; ?>
                                    </span>
                                <?php elseif ($sudahDiajukan): ?>
                                    <span class="keterangan-kecil">Diajukan oleh siswa</span>
                                <?php endif; ?>
                            </td>

                            <!-- AKSI -->
                            <td class="aksi">
                                <?php if ($status === 'dipinjam' && $sudahDiajukan): ?>
                                    <button
                                        type="button"
                                        class="btn-konfirmasi btn-menunggu"
                                        title="Konfirmasi Pengembalian"
                                        onclick="konfirmasiPengembalian(<?= (int) $p->id_peminjaman; ?>)">
                                        <i class="fa-solid fa-check"></i> Konfirmasi Pengembalian
                                    </button>
                                <?php elseif ($status === 'dipinjam'): ?>
                                    <span class="aksi-selesai">Belum diajukan pengembalian</span>
                                <?php else: ?>
                                    <span class="aksi-selesai">Tidak ada aksi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- DATA KOSONG -->
                    <tr class="row-empty">
                        <td colspan="7" class="empty-data">
                            <i class="fa-solid fa-box-open"></i>
                            <br>
                            Belum ada data peminjaman yang perlu dikembalikan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <?php if (isset($total_halaman) && $total_halaman > 1): ?>
        <div class="pagination">
            <?php if ($halaman > 1): ?>
                <a href="?halaman=<?= ($halaman - 1) . $kw_param; ?>" title="Halaman Sebelumnya">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
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


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>
/* KONFIRMASI / PROSES PENGEMBALIAN */
function konfirmasiPengembalian(id) {
    if (!confirm('Konfirmasi buku ini sudah dikembalikan?')) {
        return;
    }

    var keterangan = prompt('Catatan kondisi buku (opsional, boleh dikosongkan):', '');

    if (keterangan === null) {
        return; // batal
    }

    window.location.href =
        '../controller/c_pengembalianA.php?aksi=konfirmasi&id_peminjaman=' + id +
        '&keterangan=' + encodeURIComponent(keterangan);
}
</script>

</body>
</html>
