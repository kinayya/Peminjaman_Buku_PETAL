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


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->


    <style>
        
        @import url('https://fonts.googleapis.com/css2?family=Original+Surfer&display=swap');


        /* ROOT */

        :root {

            --bg-cream: #FFF8EA;
            --card-cream: #FDF0D5;

            --primary-blue: #1D3557;
            --accent-blue: #457B9D;

            --soft-blue: #B4DBF7;
            --border-blue: #D8E3E6;

            --text-dark: #33475B;

        }


        /* RESET */

        * {

            margin: 0;
            padding: 0;

            box-sizing: border-box;

            font-family: 'Poppins', sans-serif;

        }


        /* =====================================================
           BODY
        ===================================================== */

        body {

            min-height: 100vh;

            display: flex;
            flex-direction: column;

            background-color: #fff;

            color: var(--text-dark);

        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .navbar {

            width: calc(100% - 60px);

            height: 60px;

            margin: 12px 30px 0;

            padding: 6px 22px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            background: linear-gradient(
            90deg,
            #feffd6 5%,     /* Kream di pinggir kiri */
            #b4dbf7 50%,    /* Biru di titik tengah */
            #feffd6 100%    /* Kream di pinggir kanan */
        );

            border-radius: 35px;

            box-shadow:
                0 3px 9px
                rgba(29, 53, 87, 0.07);

        }


        /* =====================================================
           LOGO
        ===================================================== */

        .logo-link {

            display: flex;

            align-items: center;

            text-decoration: none;

            flex-shrink: 0;

        }

        .navbar .logo {

            width: 85px;

            height: auto;

            display: block;

            margin-top: 6px;

        }


        /* =====================================================
           NAV LINKS
        ===================================================== */

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;

        }

        .nav-links a {

            position: relative;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 7px 13px;

            color: #1f535e;

            text-decoration: none;

            font-size: 0.85rem;

            font-weight: 600;

            border-radius: 20px;

            transition:
                color 0.25s ease,
                background-color 0.25s ease,
                transform 0.25s ease;

        }


        /* =====================================================
           GARIS AKTIF
        ===================================================== */

        .nav-links a::after {

            content: "";

            position: absolute;

            bottom: 3px;

            left: 50%;

            width: 0;

            height: 2px;

            background-color: #dfda4c;

            border-radius: 10px;

            transform: translateX(-50%);

            transition: width 0.25s ease;

        }


        /* =====================================================
           HOVER NAV
        ===================================================== */

        .nav-links a:hover {

            background-color: #fef9cb;

            transform: translateY(-1px);

        }

        .nav-links a:hover::after,
        .nav-links a.active::after {

            width: 28px;

        }

        .nav-links a.active {

            background-color: #fef9cb;

        }


        /* =====================================================
           SEARCH SECTION
        ===================================================== */

        .search-section {

            width: calc(100% - 60px);

            margin: 20px 30px 30px;

            display: flex;

            justify-content: flex-end;

        }


        /* =====================================================
           SEARCH BOX
        ===================================================== */

        .search-box {

            position: relative;

            width: 250px;

        }

        .search-box input {

            width: 100%;

            padding: 8px 13px 8px 35px;

            border: 2px solid #6b96da;

            border-radius: 20px;

            outline: none;

            font-size: 0.78rem;

            transition: 0.2s;

        }

        .search-box input:focus {

            border-color: #f5f263;

            box-shadow:
                0 0 0 3px
                rgba(232, 229, 78, 0.26);

        }

        .search-box input::placeholder {

            color: #718096;

        }

        .search-box i {

            position: absolute;

            left: 12px;

            top: 50%;

            transform: translateY(-50%);

            color: #456cae;

            font-size: 0.8rem;

            pointer-events: none;

        }


        /* =====================================================
           MAIN CONTAINER
        ===================================================== */

        .container {

            width: 100%;

            max-width: 1200px;

            margin: 0 auto;

            padding: 0 25px 40px;

            flex: 1;

        }


        /* =====================================================
           PAGE TITLE
        ===================================================== */

        .page-title {
            font-family: "Original Surfer", sans-serif;
            font-weight: 550;
            font-style: normal;
            font-size: 2.2rem;
            color: #6b96da;
            margin-bottom: 2px;
        }

        .page-subtitle {

            font-size: 0.78rem;

            color: #718096;

            margin-bottom: 20px;

        }


        /* =====================================================
           TABLE CARD
        ===================================================== */

        .table-card {

            width: 100%;

            background-color: #fef9cb;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 3px 10px
                rgba(29, 53, 87, 0.06);

        }


        /* =====================================================
           TABLE WRAPPER
        ===================================================== */

        .table-wrapper {

            width: 100%;

            overflow-x: auto;

        }


        /* =====================================================
           TABLE
        ===================================================== */

        table#tableRiwayat {

            width: 100%;

            min-width: 850px;

            border-collapse: collapse;

        }


        /* =====================================================
           TABLE HEADER
        ===================================================== */

        #tableRiwayat thead {

            background-color: #b4dbf7;

        }

        #tableRiwayat th {

            padding: 14px 18px;

            text-align: left;

            font-size: 0.76rem;

            font-weight: 700;

            color: #2c4c81;

            white-space: nowrap;

        }


        /* =====================================================
           TABLE BODY
        ===================================================== */

        #tableRiwayat td {
            font-family: 'veranda', sans-serif;
            padding: 13px 18px;

            border-bottom:
                1px solid
                rgba(62, 157, 178, 0.12);

            font-size: 0.76rem;

            vertical-align: middle;

            color: #555;

        }

        /* =====================================================
           BUKU CELL
        ===================================================== */

        .buku-cell {

            display: flex;

            align-items: center;

            gap: 12px;

            min-width: 240px;

        }


        .buku-cell img {

            width: 42px;

            height: 60px;

            object-fit: cover;

            border-radius: 7px;

            flex-shrink: 0;

            background: #eee;

            box-shadow:
                0 2px 5px
                rgba(29, 53, 87, 0.08);

        }


        .buku-cell .no-image {

            width: 42px;

            height: 60px;

            border-radius: 7px;

            background: #e9f1f3;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #9aa7ad;

            flex-shrink: 0;

        }


        .judul-buku {
            display: block;
            max-width: 230px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #6b96da;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;

        }


        .pengarang-buku {
            display: block;
            font-family: "Original Surfer", sans-serif;
            font-weight: 600;
            font-style: normal;
            max-width: 230px;
            margin-top: 3px;
            font-size: 0.70rem;
            color: #456cae;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;

        }


        /* =====================================================
           STATUS BADGE
        ===================================================== */

        .badge-status {

            display: inline-block;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 0.68rem;

            font-weight: 700;

            white-space: nowrap;

        }


        .badge-pengajuan {

            background-color: #fff3b5;

            color: #8a7000;

        }


        .badge-disetujui {

            background-color: #d8edff;

            color: #26739b;

        }


        .badge-dipinjam {

            background-color: #cfe8f5;

            color: #1f607a;

        }


        .badge-menunggu {

            background-color: #ffe9c7;

            color: #a15c00;

        }


        .badge-dikembalikan {

            background-color: #d9f2df;

            color: #27804b;

        }


        .badge-ditolak {

            background-color: #ffe0e0;

            color: #c1121f;

        }


        /* =====================================================
           KETERANGAN STATUS
        ===================================================== */

        .keterangan-kecil {

            display: block;

            font-size: 0.65rem;

            color: #777;

            margin-top: 4px;

        }


        /* =====================================================
           AKSI
        ===================================================== */

        .aksi {

            min-width: 180px;

        }


        .btn-kembalikan {

            background-color: #fff;

            color: #307e8f;

            border: 1.5px solid #307e8f;

            padding: 7px 14px;

            border-radius: 20px;

            font-weight: 600;

            font-size: 0.7rem;

            cursor: pointer;

            transition:
                all 0.2s ease;

            white-space: nowrap;

        }


        .btn-kembalikan:hover {

            background-color: #b4dbf7;

            color: #1f535e;

            transform: translateY(-1px);

        }


        .aksi-info {

            display: inline-block;

            font-size: 0.68rem;

            color: #777;

            font-style: italic;

        }


        /* =====================================================
           EMPTY DATA
        ===================================================== */

        .empty-data {

            text-align: center !important;

            padding: 55px 20px !important;

            color: var(--primary-blue) !important;

            font-size: 0.85rem !important;

            font-weight: 600;

        }


        .empty-data i {

            display: block;

            font-size: 35px;

            margin-bottom: 10px;

            color: #3e9db2;

        }


        .empty-data a {

            color: #307e8f;

            font-weight: 700;

            text-decoration: underline;

        }


        /* =====================================================
           RESPONSIVE TABLET
        ===================================================== */

        @media (max-width: 800px) {

            .navbar {

                width: calc(100% - 30px);

                margin: 10px 15px 0;

                min-height: 55px;

                height: auto;

                padding: 6px 15px;

            }


            .navbar .logo {

                width: 65px;

            }


            .nav-links {

                gap: 1px;

            }


            .nav-links a {

                padding: 6px 9px;

                font-size: 0.7rem;

            }


            .search-section {

                width: calc(100% - 30px);

                margin: 12px 15px 14px;

            }


            .search-box {

                width: 250px;

            }


            .container {

                padding:
                    0 18px 35px;

            }


            .table-card {

                border-radius: 15px;

            }

        }


        /* =====================================================
           RESPONSIVE HP
        ===================================================== */

        @media (max-width: 600px) {

            .navbar {

                width: calc(100% - 30px);

                border-radius: 22px;

                padding: 8px 12px;

                flex-direction: column;

                gap: 5px;

            }


            .navbar .logo {

                width: 65px;

            }


            .nav-links {

                width: 100%;

                justify-content: center;

                flex-wrap: wrap;

                gap: 1px;

            }


            .nav-links a {

                padding: 5px 8px;

                font-size: 0.68rem;

            }


            .search-section {

                width: calc(100% - 30px);

                margin: 12px 15px;

            }


            .search-box {

                width: 100%;

            }


            .container {

                padding:
                    0 15px 30px;

            }


            .page-title {

                font-size: 1.9rem;

            }


            .page-subtitle {

                font-size: 0.7rem;

                margin-bottom: 15px;

            }


            .table-card {

                border-radius: 14px;

            }


            #tableRiwayat th {

                padding: 12px 13px;

                font-size: 0.68rem;

            }


            #tableRiwayat td {

                padding: 11px 13px;

                font-size: 0.68rem;

            }


            .judul-buku {

                font-size: 0.82rem;

            }


            .pengarang-buku {

                font-size: 0.62rem;

            }


            .badge-status {

                font-size: 0.62rem;

                padding: 5px 9px;

            }


            .btn-kembalikan {

                padding: 6px 11px;

                font-size: 0.64rem;

            }

        }


        /* =====================================================
           HP SANGAT KECIL
        ===================================================== */

        @media (max-width: 400px) {

            .nav-links a {

                padding: 5px 6px;

                font-size: 0.63rem;

            }


            .page-title {

                font-size: 1.75rem;

            }

        }

    </style>

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
                placeholder="Cari judul buku / status...">

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


                        <?php if (!empty($riwayat)): ?>


                            <?php foreach ($riwayat as $r): ?>


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
