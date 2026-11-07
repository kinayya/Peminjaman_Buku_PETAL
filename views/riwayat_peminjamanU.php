<?php

include_once __DIR__ . '/../controller/c_riwayat_peminjamanU.php';

/* =====================================================
   LABEL STATUS
===================================================== */

$label_status = [
    'pengajuan'    => 'Menunggu Persetujuan',
    'disetujui'    => 'Disetujui, Ambil di Perpustakaan',
    'dipinjam'     => 'Sedang Dipinjam',
    'dikembalikan' => 'Sudah Dikembalikan',
    'ditolak'      => 'Ditolak',
];


/* =====================================================
   PAGINATION
===================================================== */

$kw_param = ($keyword ?? '') !== ''
    ? '&keyword=' . urlencode($keyword)
    : '';

$per_halaman    = 10;
$jumlah_riwayat = count($riwayat);
$total_halaman  = max(1, (int) ceil($jumlah_riwayat / $per_halaman));

$halaman = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$halaman = min(max(1, $halaman), $total_halaman);

$riwayat_halaman = array_slice(
    $riwayat,
    ($halaman - 1) * $per_halaman,
    $per_halaman
);

// Hanya 2 nomor halaman yang tampil sekaligus: (1 2), (3 4), (5 6), dst.
$blok_awal  = (int) floor(($halaman - 1) / 2) * 2 + 1;
$blok_akhir = min($blok_awal + 1, $total_halaman);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0" >

    <title>Status Pinjaman - Progresif Library</title>


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/riwayat_peminjamanU.css">

</head>


<body>


    <!-- =====================================================
         NAVBAR
    ===================================================== -->

    <nav class="navbar">


        <!-- LOGO -->

        <a
            href="dashboard_user.php"
            class="logo-link">

            <img
                src="../assets/logo.svg"
                class="logo"
                alt="Logo PETAL">

        </a>


        <!-- NAVIGATION -->

        <div class="nav-links">
            <a href="dashboard_user.php"> Dashboard </a>
            <a href="profilU.php">Profil</a>
            <a href="daftar_bukuU.php"> Daftar Buku</a>
            <a href="riwayat_peminjamanU.php" class="active">Riwayat Peminjaman</a>
        </div>

    </nav>


    <!-- =====================================================
         SEARCH
    ===================================================== -->

    <div class="search-section">

        <form
            action=""
            method="GET"
            class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                name="keyword"
                value="<?= htmlspecialchars(
                    $keyword ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                placeholder="Cari Riwayat Transaksi">

        </form>

    </div>


    <!-- =====================================================
         MAIN CONTAINER
    ===================================================== -->

    <div class="container">


        <!-- PAGE TITLE -->

        <h1 class="page-title">

            Status Pinjaman

        </h1>


        <p class="page-subtitle">

            Lihat status pengajuan, peminjaman, dan pengembalian bukumu.

        </p>


        <!-- =================================================
             TABLE
        ================================================= -->

        <div class="table-card">

            <div class="table-wrapper">

                <table id="tableRiwayat">


                    <!-- TABLE HEADER -->

                    <thead>

                        <tr>

                            <th>
                                Buku
                            </th>

                            <th>
                                Tanggal Pinjam
                            </th>

                            <th>
                                Tanggal Kembali
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <!-- TABLE BODY -->

                    <tbody>


                        <?php if (!empty($riwayat_halaman)): ?>


                            <?php foreach ($riwayat_halaman as $r): ?>


                                <?php

                                $status =
                                    $r->status ?? 'pengajuan';


                                $sudahDiajukan =
                                    !empty(
                                        $r->id_pengembalian
                                    );


                                if (
                                    $status === 'dipinjam'
                                    && $sudahDiajukan
                                ) {

                                    $badgeClass =
                                        'badge-menunggu';

                                    $labelText =
                                        'Menunggu Konfirmasi Admin';

                                } else {

                                    $badgeClass =
                                        'badge-' . $status;

                                    $labelText =
                                        $label_status[$status]
                                        ?? ucfirst($status);

                                }

                                ?>


                                <tr>


                                    <!-- BUKU -->

                                    <td>

                                        <div class="buku-cell">


                                            <?php if (
                                                !empty(
                                                    $r->link_gambar
                                                )
                                            ): ?>


                                                <img
                                                    src="<?= htmlspecialchars(
                                                        $r->link_gambar,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ); ?>"
                                                    alt="Cover Buku"
                                                    onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';"
                                                >


                                                <span
                                                    class="no-image"
                                                    style="display:none;"
                                                >

                                                    <i class="fa-solid fa-book"></i>

                                                </span>


                                            <?php else: ?>


                                                <span class="no-image">

                                                    <i class="fa-solid fa-book"></i>

                                                </span>


                                            <?php endif; ?>


                                            <span>


                                                <span class="judul-buku">

                                                    <?= htmlspecialchars(
                                                        $r->judul
                                                        ?? '(buku telah dihapus)',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ); ?>

                                                </span>


                                                <?php if (
                                                    !empty(
                                                        $r->pengarang
                                                    )
                                                ): ?>


                                                    <span class="pengarang-buku">

                                                        Oleh:

                                                        <?= htmlspecialchars(
                                                            $r->pengarang,
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ); ?>

                                                    </span>


                                                <?php endif; ?>


                                            </span>


                                        </div>

                                    </td>


                                    <!-- TANGGAL PINJAM -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $r->tanggal_pinjam
                                            ?? '',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </td>


                                    <!-- TENGGAT -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $r->tanggal_kembali
                                            ?? '',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </td>


                                    <!-- STATUS -->

                                    <td>


                                        <span
                                            class="badge-status <?= htmlspecialchars(
                                                $badgeClass,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>"
                                        >

                                            <?= htmlspecialchars(
                                                $labelText,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>

                                        </span>


                                        <?php if (
                                            $status === 'dikembalikan'
                                            && !empty(
                                                $r->tanggal_pengembalian
                                            )
                                        ): ?>


                                            <span class="keterangan-kecil">

                                                Dikembalikan:

                                                <?= htmlspecialchars(
                                                    $r->tanggal_pengembalian,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>

                                            </span>


                                        <?php endif; ?>


                                    </td>


                                    <!-- AKSI -->

                                    <td class="aksi">


                                        <?php if (
                                            $status === 'dipinjam'
                                            && !$sudahDiajukan
                                        ): ?>


                                            <button
                                                type="button"
                                                class="btn-kembalikan"
                                                onclick="ajukanPengembalian(<?= (int) $r->id_peminjaman; ?>)"
                                            >

                                                <i class="fa-solid fa-book-open-reader"></i>

                                                Ajukan Pengembalian

                                            </button>


                                        <?php elseif (
                                            $status === 'dipinjam'
                                            && $sudahDiajukan
                                        ): ?>


                                            <span class="aksi-info">

                                                Menunggu diproses admin

                                            </span>


                                        <?php else: ?>


                                            <span class="aksi-info">

                                                Tidak ada aksi

                                            </span>


                                            <?php
                                                $catatan_admin = trim((string) ($r->keterangan_pengembalian ?? ''));
                                                $ada_pesan = $status === 'dikembalikan'
                                                    && $catatan_admin !== ''
                                                    && $catatan_admin !== 'Dikonfirmasi oleh admin';
                                            ?>

                                            <?php if ($ada_pesan): ?>

                                                <span class="pesan-admin">

                                                    <strong>
                                                        <i class="fa-solid fa-envelope-circle-check"></i>
                                                        Pesan dari admin
                                                    </strong>

                                                    <?= htmlspecialchars($catatan_admin, ENT_QUOTES, 'UTF-8'); ?>

                                                </span>

                                            <?php endif; ?>



                                        <?php endif; ?>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        <?php else: ?>


                            <!-- DATA KOSONG -->

                            <tr>

                                <td
                                    colspan="5"
                                    class="empty-data">

                                    <i class="fa-solid fa-book-open"></i>

                                    Belum ada riwayat peminjaman.

                                    <br>

                                    Yuk mulai pinjam buku dari

                                    <a href="daftar_bukuU.php">

                                        katalog buku

                                    </a>.

                                </td>

                            </tr>


                        <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>


        <!-- =================================================
             PAGINATION (2 nomor halaman per tampilan)
        ================================================= -->

        <?php if ($total_halaman > 1): ?>

            <div class="pagination">

                <?php if ($halaman > 1): ?>
                    <a
                        href="?halaman=<?= ($halaman - 1) . $kw_param; ?>"
                        title="Halaman Sebelumnya">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php for ($i = $blok_awal; $i <= $blok_akhir; $i++): ?>
                    <a
                        href="?halaman=<?= $i . $kw_param; ?>"
                        class="<?= $i == $halaman ? 'active' : ''; ?>">
                        <?= $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($halaman < $total_halaman): ?>
                    <a
                        href="?halaman=<?= ($halaman + 1) . $kw_param; ?>"
                        title="Halaman Berikutnya">
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


        /* =====================================================
           AJUKAN PENGEMBALIAN
        ===================================================== */

        function ajukanPengembalian(id)
        {

            if (
                confirm(
                    'Yakin ingin mengembalikan buku?'
                )
            )
            {

                window.location.href =
                    '../controller/c_riwayat_peminjamanU.php?aksi=ajukan_pengembalian&id='
                    + id;

            }

        }

    </script>


</body>

</html>
