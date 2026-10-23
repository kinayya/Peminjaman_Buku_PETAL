<?php

include_once __DIR__ . '/../controller/c_bukuU.php';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Katalog Buku - Progresif Library</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Original+Surfer&display=swap');

        :root {
            --bg-cream: #FFF8EA;
            --card-cream: #FDF0D5;
            --primary-blue: #1D3557;
            --accent-blue: #457B9D;
            --soft-blue: #B4DBF7;
            --border-blue: #D8E3E6;
            --text-dark: #33475B;
        }

        /* =====================================================
           RESET
        ===================================================== */

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
                0 3px 9px rgba(29, 53, 87, 0.07);
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
            box-shadow:0 0 0 3px rgba(232, 229, 78, 0.26);
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
           CATALOG GRID
        ===================================================== */

        .catalog-grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 22px;
        }


        /* =====================================================
           BOOK CARD
        ===================================================== */

        .book-card {
            background-color: #fef9cb;
            border-radius: 18px;
            padding: 0 0 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            overflow: hidden;
            box-shadow:
                0 3px 10px
                rgba(29, 53, 87, 0.06);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 10px 22px
                rgba(29, 53, 87, 0.13);
        }

        /* =====================================================
           BOOK COVER
        ===================================================== */

        .book-cover-wrapper {
            width: 100%;
            height: 240px;

            background-color: #eee;

            overflow: hidden;
        }

        .book-cover {
            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;

            transition:
                transform 0.3s ease;
        }

        .book-card:hover .book-cover {
            transform: scale(1.03);
        }


        /* =====================================================
           BOOK DETAILS
        ===================================================== */

        .book-details {
            width: 100%;
            padding: 14px 15px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }


        /* =====================================================
           BOOK TITLE
        ===================================================== */

        .book-title {
            width: 100%;
            font-size: 0.90rem;
            color: #6b96da;
            margin-bottom: 3px;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        /* =====================================================
           BOOK AUTHOR
        ===================================================== */

        .book-author {
            font-family: "Original Surfer", sans-serif;
            font-weight: 600;
            font-style: normal;
            width: 100%;
            font-size: 0.85rem;
            color: #456cae;
            margin-bottom: 7px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        /* =====================================================
           STATUS STOK
        ===================================================== */

        .book-status {
            font-size: 0.90rem;
            font-weight: 700;
            color: #6b96da;
            margin-bottom: 12px;
        }

        .book-status.habis {
            color: #c1121f;
        }


        /* =====================================================
           BUTTON PINJAM
        ===================================================== */

        .btn-pinjam {
            background-color: white;
            color: #2c4c81;
            border: 1.5px solid #6b96da;
            padding: 7px 18px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.76rem;
            cursor: pointer;
            transition:
                all 0.2s ease;
        }

        .btn-pinjam:hover {
            background-color: #b4dbf7;
            color: #2c4c81;
            transform: translateY(-1px);
        }

        .btn-pinjam:disabled {
            opacity: 0.5;

            cursor: not-allowed;

            background-color: #eee;

            border-color: #aaa;

            color: #888;

            transform: none;
        }


        /* =====================================================
           NO DATA
        ===================================================== */

        .no-data {
            grid-column: 1 / -1;

            text-align: center;

            padding: 50px 20px;

            font-size: 0.9rem;

            font-weight: 600;

            color: var(--primary-blue);
        }

        .no-data i {
            font-size: 35px;

            margin-bottom: 10px;
        }


        /* =====================================================
           PAGINATION
        ===================================================== */

        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            margin-top: 35px;
        }

        .pagination-container a {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 5px 10px;
            font-size: 0.8rem;
            text-decoration: none;
            font-weight: 600;
            border-radius: 9px;
            color: #6b96da;
            border: 2px solid #6b96da;
            transition: all 0.2s ease;
        }

        .pagination-container a:hover {
            background-color: #fef9cb;
            border-color: #6b96da;
            color: #2c4c81;
        }

        .pagination-container a.active {
            background-color: #fef9cb;
            color: #2c4c81;
        }


        /* =====================================================
           MODAL PEMINJAMAN
        ===================================================== */

        .modal-overlay {
            display: none;

            position: fixed;

            inset: 0;

            width: 100%;
            height: 100%;

            background:
                rgba(29, 53, 87, 0.45);

            backdrop-filter: blur(4px);

            justify-content: center;
            align-items: center;

            padding: 20px;

            z-index: 9999;
        }

        .modal-overlay.active {
            display: flex;
        }


        /* =====================================================
           MODAL BOX
        ===================================================== */

        .modal-box {
            position: relative;

            width: 100%;
            max-width: 440px;

            max-height: 90vh;

            overflow-y: auto;

            background: #fff;

            border-radius: 20px;

            padding: 30px;

            box-shadow:
                0 25px 70px
                rgba(0, 0, 0, 0.2);

            animation:
                modalMasuk 0.25s ease;
        }


        /* =====================================================
           ANIMATION MODAL
        ===================================================== */

        @keyframes modalMasuk {

            from {
                opacity: 0;

                transform:
                    translateY(-30px)
                    scale(0.96);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }

        }


        /* =====================================================
           CLOSE MODAL
        ===================================================== */

        .modal-close {
            position: absolute;

            right: 18px;
            top: 18px;

            width: 38px;
            height: 38px;

            border: none;

            border-radius: 50%;

            background: #f0f4f5;

            color: var(--primary-blue);

            font-size: 24px;

            cursor: pointer;

            transition: 0.2s;
        }

        .modal-close:hover {
            background: #fef9cb;
            color: black;

            transform: rotate(90deg);
        }


        /* =====================================================
           MODAL TITLE
        ===================================================== */

        .modal-title {
            font-family: "Original Surfer", sans-serif;
            font-weight: 600;
            font-style: normal;

            font-size: 1.5rem;

            color: #6b96da;

            text-align: center;

            margin-bottom: 12px;
        }

        /* =====================================================
           BOOK PREVIEW
        ===================================================== */

        .pinjam-book-preview {
            display: flex;

            gap: 12px;

            align-items: center;

            background: #fef9cb;

            border-radius: 12px;

            padding: 10px 12px;

            margin-bottom: 20px;
        }

        .pinjam-book-preview img {
            width: 48px;
            height: 66px;

            object-fit: cover;

            border-radius: 6px;

            background: #eee;

            flex-shrink: 0;
        }

        .pinjam-book-preview .info {
            min-width: 0;
        }

        .pinjam-book-preview .info strong {
            display: block;

            font-size: 0.92rem;

            color: #6b96da;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }

        .pinjam-book-preview .info span {
            display: block;
            font-size: 0.78rem;
            color: #666;
            margin-top: 2px;
        }
        #pinjam_pengarang {
            display: block;
            font-family: "Original Surfer", sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            color: #456cae;
            margin-top: 2px;
        }

        /* =====================================================
           FORM
        ===================================================== */

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;

            margin-bottom: 6px;

            font-size: 0.82rem;

            font-weight: 600;

            color: var(--primary-blue);
        }

        .form-group input[type="date"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #6b96da;
            border-radius: 10px;
            outline: none;
            font-family: inherit;
            font-size: 0.85rem;
            color: var(--primary-blue);
            background: #fff;
        }

        .form-group input[type="date"]:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px #b4dbf7;
        }

        .form-hint {
            font-size: 0.75rem;

            color: #777;

            margin-top: 4px;
        }


        /* =====================================================
           MODAL ACTIONS
        ===================================================== */

        .modal-actions {
            display: flex;

            justify-content: flex-end;

            gap: 10px;

            margin-top: 22px;

            padding-top: 18px;

            border-top:
                1px solid #edf1f2;
        }

        .btn-batal,
        .btn-simpan {
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-batal {
            background: #f0f4f5;

            color: #555;
        }

        .btn-batal:hover {
            background: #fef9cb;
            color: black;
        }

        .btn-simpan {
            background: #feffd6;

            color: black;
        }

        .btn-simpan:hover {
            background: #fef9cb;
            color: black;
        }


        /* =====================================================
           TABLET
        ===================================================== */

        @media (max-width: 1000px) {

            .catalog-grid {
                grid-template-columns:
                    repeat(3, 1fr);
            }

        }


        /* =====================================================
           TABLET KECIL
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


            .catalog-grid {
                grid-template-columns:
                    repeat(2, 1fr);

                gap: 16px;
            }

            .book-cover-wrapper {
                height: 220px;
            }

        }


        /* =====================================================
           HP
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


            .catalog-grid {
                grid-template-columns:
                    repeat(2, 1fr);

                gap: 12px;
            }


            .book-cover-wrapper {
                height: 190px;
            }


            .book-details {
                padding:
                    12px 10px 0;
            }


            .book-title {
                font-size: 1.2rem;
            }


            .book-author {
                font-size: 0.7rem;
            }


            .book-status {
                font-size: 0.7rem;
            }


            .btn-pinjam {
                padding: 6px 12px;

                font-size: 0.68rem;
            }


            .modal-overlay {
                padding: 15px;
            }


            .modal-box {
                padding:
                    25px 20px;

                border-radius: 18px;
            }


            .modal-title {
                font-size: 1.7rem;
            }


            .modal-actions {
                flex-direction: column-reverse;
            }


            .btn-batal,
            .btn-simpan {
                width: 100%;
            }

        }


        /* =====================================================
           HP SANGAT KECIL
        ===================================================== */

        @media (max-width: 400px) {

            .catalog-grid {
                grid-template-columns: 1fr;
            }

            .book-cover-wrapper {
                height: 240px;
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
            <a href="dashboard_user.php">Dashboard</a>
            <a href="profilU.php">Profil</a>
            <a href="daftar_bukuU.php" class="active">Daftar Buku</a>
            <a href="riwayat_peminjamanU.php"> Riwayat Peminjaman</a>

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
                placeholder="Cari judul, pengarang, penerbit..."
            >

        </form>

    </div>


    <!-- =====================================================
         MAIN CONTAINER
    ===================================================== -->

    <div class="container">


        <!-- =================================================
             CATALOG GRID
        ================================================= -->

        <div class="catalog-grid">

            <?php if (!empty($books)): ?>

                <?php foreach ($books as $buku_item): ?>

                    <?php

                        $gambar = !empty($buku_item->link_gambar)
                            ? $buku_item->link_gambar
                            : 'https://via.placeholder.com/200x280?text=No+Cover';

                        $stok = (int) (
                            $buku_item->stok ?? 0
                        );

                    ?>


                    <!-- BOOK CARD -->

                    <div class="book-card">


                        <!-- COVER -->

                        <div class="book-cover-wrapper">

                            <img
                                src="<?= htmlspecialchars(
                                    $gambar,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                class="book-cover"
                                alt="Cover <?= htmlspecialchars(
                                    $buku_item->judul ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                            >

                        </div>


                        <!-- DETAILS -->

                        <div class="book-details">


                            <!-- TITLE -->

                            <h3
                                class="book-title"
                                title="<?= htmlspecialchars(
                                    $buku_item->judul ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                            >

                                <?= htmlspecialchars(
                                    $buku_item->judul ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </h3>


                            <!-- AUTHOR -->

                            <p
                                class="book-author"
                                title="<?= htmlspecialchars(
                                    $buku_item->pengarang ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                            >

                                Oleh:

                                <?= htmlspecialchars(
                                    $buku_item->pengarang ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </p>


                            <!-- STOCK -->

                            <div
                                class="book-status <?= $stok <= 0 ? 'habis' : ''; ?>"
                            >

                                <?= $stok > 0
                                    ? 'Tersedia: ' . $stok
                                    : 'Stok Habis';
                                ?>

                            </div>


                            <!-- BUTTON -->

                            <button
                                type="button"
                                class="btn-pinjam"

                                data-id="<?= (int)
                                    $buku_item->id_buku; ?>"

                                data-judul="<?= htmlspecialchars(
                                    $buku_item->judul ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"

                                data-pengarang="<?= htmlspecialchars(
                                    $buku_item->pengarang ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"

                                data-gambar="<?= htmlspecialchars(
                                    $gambar,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"

                                data-stok="<?= $stok; ?>"

                                onclick="bukaModalPinjam(this)"

                                <?= $stok <= 0
                                    ? 'disabled'
                                    : ''; ?>
                                >

                                Pinjam Sekarang

                            </button>

                        </div>

                    </div>


                <?php endforeach; ?>


            <?php else: ?>


                <!-- DATA KOSONG -->

                <div class="no-data">

                    <i class="fa-solid fa-book-open"></i>

                    <br>

                    Buku tidak ditemukan.

                </div>


            <?php endif; ?>

        </div>


        <!-- =================================================
             PAGINATION
        ================================================= -->

        <?php if (
            isset($totalPages)
            && $totalPages > 1
        ): ?>

            <div class="pagination-container">


                <!-- PREVIOUS -->

                <?php if ($page > 1): ?>

                    <a
                        href="?page=<?= $page - 1; ?>&keyword=<?= urlencode(
                            $keyword ?? ''
                        ); ?>"
                        title="Halaman Sebelumnya"
                    >

                        <i class="fa-solid fa-chevron-left"></i>

                    </a>

                <?php endif; ?>


                <!-- PAGE NUMBER -->

                <?php for (
                    $i = 1;
                    $i <= $totalPages;
                    $i++
                ): ?>

                    <a
                        href="?page=<?= $i; ?>&keyword=<?= urlencode(
                            $keyword ?? ''
                        ); ?>"

                        class="<?= ($i == $page)
                            ? 'active'
                            : ''; ?>"
                    >

                        <?= $i; ?>

                    </a>

                <?php endfor; ?>


                <!-- NEXT -->

                <?php if ($page < $totalPages): ?>

                    <a
                        href="?page=<?= $page + 1; ?>&keyword=<?= urlencode(
                            $keyword ?? ''
                        ); ?>"
                        title="Halaman Berikutnya"
                    >

                        <i class="fa-solid fa-chevron-right"></i>

                    </a>

                <?php endif; ?>


            </div>

        <?php endif; ?>

    </div>


    <!-- =====================================================
         MODAL PEMINJAMAN
    ===================================================== -->

    <div
        id="modalPinjam"
        class="modal-overlay"

        onclick="klikLuarModal(
            event,
            'modalPinjam'
        )">

        <div class="modal-box">


            <!-- CLOSE -->

            <button
                type="button"
                class="modal-close"

                onclick="tutupModal(
                    'modalPinjam'
                )">

                &times;

            </button>


            <!-- TITLE -->

            <h2 class="modal-title">
                Ajukan Peminjaman
            </h2>

            <!-- BOOK PREVIEW -->

            <div class="pinjam-book-preview">

                <img
                    id="pinjam_cover"
                    src=""
                    alt="Cover buku"
                >

                <div class="info">

                    <strong id="pinjam_judul"></strong>

                    <span id="pinjam_pengarang"></span>

                    <span id="pinjam_stok"></span>

                </div>

            </div>


            <!-- FORM -->

            <form
                action="../controller/c_peminjaman.php?aksi=tambah"
                method="POST"
            >


                <!-- ID BUKU -->

                <input
                    type="hidden"
                    name="id_buku"
                    id="pinjam_id_buku"
                >


                <!-- TANGGAL PINJAM -->

                <div class="form-group">

                    <label
                        for="pinjam_tanggal_pinjam"
                    >
                        Tanggal Pinjam
                    </label>


                    <input
                        type="date"
                        name="tanggal_pinjam"
                        id="pinjam_tanggal_pinjam"
                        required
                    >


                    <p class="form-hint">

                        Tanggal kembali otomatis dihitung
                        7 hari setelah tanggal ini.

                    </p>

                </div>


                <!-- ACTION -->

                <div class="modal-actions">


                    <button
                        type="button"
                        class="btn-batal"

                        onclick="tutupModal(
                            'modalPinjam'
                        )"
                    >

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn-simpan">

                        Pinjam

                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- =====================================================
         JAVASCRIPT
    ===================================================== -->

    <script>


        /* =====================================================
           LOGIN STATUS
        ===================================================== */

        const isLoggedIn =
            <?= isset($is_logged_in)
                && $is_logged_in
                ? 'true'
                : 'false'; ?>;


        /* =====================================================
           BUKA MODAL PEMINJAMAN
        ===================================================== */

        function bukaModalPinjam(btn)
        {

            if (!isLoggedIn)
            {

                alert(
                    'Silakan login terlebih dahulu untuk meminjam buku.'
                );

                window.location.href =
                    '../index.php';

                return;
            }


            /* DATA BUKU */

            const id =
                btn.dataset.id || '';

            const judul =
                btn.dataset.judul || '';

            const pengarang =
                btn.dataset.pengarang || '';

            const gambar =
                btn.dataset.gambar || '';

            const stok =
                btn.dataset.stok || '0';


            /* ISI DATA MODAL */

            document.getElementById(
                'pinjam_id_buku'
            ).value = id;


            document.getElementById(
                'pinjam_judul'
            ).textContent = judul;


            document.getElementById(
                'pinjam_pengarang'
            ).textContent =
                'Oleh: ' + pengarang;


            document.getElementById(
                'pinjam_stok'
            ).textContent =
                'Tersedia: ' + stok;


            document.getElementById(
                'pinjam_cover'
            ).src = gambar;


            /* ================================================
               TANGGAL HARI INI
            ================================================= */

            const today =
                new Date()
                    .toISOString()
                    .split('T')[0];


            const inputTanggal =
                document.getElementById(
                    'pinjam_tanggal_pinjam'
                );


            inputTanggal.min =
                today;

            inputTanggal.value =
                today;


            /* ================================================
               TAMPILKAN MODAL
            ================================================= */

            document
                .getElementById('modalPinjam')
                .classList
                .add('active');

        }


        /* =====================================================
           TUTUP MODAL
        ===================================================== */

        function tutupModal(idModal)
        {

            document
                .getElementById(idModal)
                .classList
                .remove('active');

        }


        /* =====================================================
           KLIK DI LUAR MODAL
        ===================================================== */

        function klikLuarModal(
            event,
            idModal
        )
        {

            if (
                event.target ===
                document.getElementById(idModal)
            )
            {

                tutupModal(idModal);

            }

        }


        /* =====================================================
           ESC UNTUK MENUTUP MODAL
        ===================================================== */

        document.addEventListener(
            'keydown',
            function(event)
            {

                if (event.key === 'Escape')
                {

                    document
                        .querySelectorAll(
                            '.modal-overlay'
                        )
                        .forEach(
                            function(modal)
                            {

                                modal.classList
                                    .remove('active');

                            }
                        );

                }

            }
        );

    </script>


</body>

</html>