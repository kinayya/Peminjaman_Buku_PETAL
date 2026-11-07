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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/daftar_bukuU.css">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">


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
            class="search-form">

            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    name="keyword"
                    value="<?= htmlspecialchars(
                        $keyword ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>"
                    placeholder="Cari judul, pengarang, penerbit, stok..."
                >

            </div>

            <select name="kategori" class="filter-kategori" onchange="this.form.submit()">

                <option value="0">Semua Kategori</option>

                <?php foreach (($data_kategori ?? []) as $kat): ?>
                    <option
                        value="<?= (int) $kat->id_kategori; ?>"
                        <?= ((int) $id_kategori === (int) $kat->id_kategori) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($kat->nama_kategori, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>

            </select>

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

                                data-sinopsis="<?= htmlspecialchars(
                                    $buku_item->sinopsis ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"

                                data-stok="<?= $stok; ?>"

                                onclick="bukaModalPinjam(this)"

                                <?= $stok <= 0
                                    ? 'disabled'
                                    : ''; ?>
                                >

                                <span class="btn-pinjam-label">Pinjam Sekarang</span>
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
                        ); ?>&kategori=<?= (int) ($id_kategori ?? 0); ?>"
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
                        ); ?>&kategori=<?= (int) ($id_kategori ?? 0); ?>"

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
                        ); ?>&kategori=<?= (int) ($id_kategori ?? 0); ?>"
                        title="Halaman Berikutnya"
                    >

                        <i class="fa-solid fa-chevron-right"></i>

                    </a>

                <?php endif; ?>


            </div>

        <?php endif; ?>

    </div>


    <!-- =====================================================
         MODAL PEMINJAMAN (satu buku, jumlah bisa lebih dari satu)
    ===================================================== -->

    <div
        id="modalPinjam"
        class="modal-overlay"

        onclick="klikLuarModal(
            event,
            'modalPinjam'
        )">

        <div
            class="modal-box"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modalPinjamJudul">


            <!-- CLOSE -->

            <button
                type="button"
                class="modal-close"
                aria-label="Tutup"

                onclick="tutupModal(
                    'modalPinjam'
                )">

                &times;

            </button>


            <!-- TITLE -->

            <h2 class="modal-title" id="modalPinjamJudul">
                Ajukan Peminjaman
            </h2>


            <!-- FORM -->

            <form
                id="formPinjam"
                action="../controller/c_peminjaman.php?aksi=tambah"
                method="POST"
                onsubmit="rapikanJumlah(true)"
            >

                <!-- ID BUKU -->

                <input type="hidden" name="id_buku" id="pinjamIdBuku">


                <!-- BUKU YANG DIPINJAM (diisi lewat JavaScript) -->

                <div class="pinjam-book">

                    <img id="pinjamCover" src="" alt="">

                    <div class="pinjam-book-info">
                        <strong id="pinjamJudul"></strong>
                        <span class="penulis" id="pinjamPenulis"></span>
                        <span class="stok" id="pinjamStok"></span>
                    </div>

                </div>


                <!-- SINOPSIS -->

                <div class="pinjam-sinopsis">

                    <span class="pinjam-sinopsis-label">Sinopsis</span>

                    <div class="pinjam-sinopsis-text" id="pinjamSinopsis"></div>

                </div>


                <!-- JUMLAH EKSEMPLAR -->

                <div class="form-group">

                    <label for="pinjam_jumlah">
                        Jumlah
                    </label>

                    <div class="jumlah-stepper">

                        <button
                            type="button"
                            id="btnKurang"
                            aria-label="Kurangi jumlah"
                            onclick="ubahJumlah(-1)"
                        >&minus;</button>

                        <input
                            type="number"
                            name="jumlah"
                            id="pinjam_jumlah"
                            value="1"
                            min="1"
                            max="1"
                            inputmode="numeric"
                            required
                            oninput="rapikanJumlah(false)"
                            onblur="rapikanJumlah(true)"
                        >

                        <button
                            type="button"
                            id="btnTambah"
                            aria-label="Tambah jumlah"
                            onclick="ubahJumlah(1)"
                        >+</button>

                    </div>

                    <p class="form-hint" id="pinjamHintJumlah"></p>

                </div>


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

                        Tanggal kembali otomatis dihitung 7 hari
                        setelah tanggal ini.

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
                        class="btn-simpan"
                        id="btnPinjamSubmit">

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
           LOGIN STATUS & PENGATURAN
        ===================================================== */

        const isLoggedIn =
            <?= isset($is_logged_in)
                && $is_logged_in
                ? 'true'
                : 'false'; ?>;

        // Jumlah maksimal untuk buku yang sedang dibuka di modal
        // (sama dengan stok yang tersedia)
        let jumlahMaks = 1;


        /* =====================================================
           JUMLAH EKSEMPLAR
        ===================================================== */

        function jumlahSekarang()
        {

            const nilai = parseInt(
                document.getElementById('pinjam_jumlah').value,
                10
            );

            return isNaN(nilai) ? 0 : nilai;

        }

        function perbaruiJumlah()
        {

            const jumlah = jumlahSekarang();

            document.getElementById('btnKurang').disabled =
                jumlah <= 1;

            document.getElementById('btnTambah').disabled =
                jumlah >= jumlahMaks;

        }

        // akhir = true: nilai kosong/tidak valid dikembalikan ke 1
        function rapikanJumlah(akhir)
        {

            const input = document.getElementById('pinjam_jumlah');

            let jumlah = jumlahSekarang();

            if (jumlah > jumlahMaks)
            {
                jumlah = jumlahMaks;
            }

            if (jumlah < 1)
            {
                jumlah = akhir ? 1 : 0;
            }

            if (input.value !== '' || akhir)
            {
                input.value = jumlah > 0 ? jumlah : '';
            }

            perbaruiJumlah();

        }

        function ubahJumlah(selisih)
        {

            const input = document.getElementById('pinjam_jumlah');

            input.value = Math.min(
                jumlahMaks,
                Math.max(1, jumlahSekarang() + selisih)
            );

            perbaruiJumlah();

        }


        /* =====================================================
           BUKA MODAL PEMINJAMAN (klik "Pinjam Sekarang")
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

            const stok = parseInt(btn.dataset.stok, 10) || 0;

            if (stok <= 0)
            {
                return;
            }

            jumlahMaks = stok;


            /* DATA BUKU */

            const sinopsis = (btn.dataset.sinopsis || '').trim();

            document.getElementById('pinjamIdBuku').value =
                btn.dataset.id || '';

            const cover = document.getElementById('pinjamCover');
            cover.src = btn.dataset.gambar || '';
            cover.alt = 'Cover ' + (btn.dataset.judul || '');

            document.getElementById('pinjamJudul').textContent =
                btn.dataset.judul || '';

            document.getElementById('pinjamPenulis').textContent =
                'Oleh: ' + (btn.dataset.pengarang || '');

            document.getElementById('pinjamStok').textContent =
                'Tersedia: ' + stok;

            const teksSinopsis =
                document.getElementById('pinjamSinopsis');

            teksSinopsis.textContent =
                sinopsis !== '' ? sinopsis : 'Sinopsis belum tersedia.';

            teksSinopsis.classList.toggle('kosong', sinopsis === '');


            /* JUMLAH EKSEMPLAR */

            const inputJumlah =
                document.getElementById('pinjam_jumlah');

            inputJumlah.max   = jumlahMaks;
            inputJumlah.value = 1;

            document.getElementById('pinjamHintJumlah').textContent =
                'Maksimal ' + jumlahMaks + ' untuk buku ini.';

            perbaruiJumlah();


            /* TANGGAL HARI INI (waktu lokal perangkat) */

            const sekarang = new Date();

            const today =
                new Date(
                    sekarang.getTime()
                    - sekarang.getTimezoneOffset() * 60000
                )
                    .toISOString()
                    .split('T')[0];


            const inputTanggal = document.getElementById(
                    'pinjam_tanggal_pinjam'
                );

            inputTanggal.min =
                today;

            if (!inputTanggal.value || inputTanggal.value < today)
            {
                inputTanggal.value =
                    today;
            }


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
