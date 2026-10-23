<?php

include_once __DIR__ . '/../controller/c_kategori.php';

$keyword = $_GET['keyword'] ?? '';

$kw_param = !empty($keyword)
    ? '&keyword=' . urlencode($keyword)
    : '';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar Kategori - Progresif Library</title>


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="../css/daftar_kategori.css"
    >


    <!-- FONT AWESOME -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar">

    <img
        src="../assets/logo.svg"
        class="logo"
        alt="Logo PETAL">

    <div class="nav-links">
        <a href="dashboard_admin.php">Dashboard</a>
        <a href="daftar_bukuA.php">Daftar Buku</a>
        <a href="daftar_user.php">Daftar Pengguna</a>
        <a href="daftar_kategori.php" class="active">Daftar Kategori</a>
        <a href="peminjamanA.php">Peminjaman</a>
        <a href="pengembalianA.php">Pengembalian</a>
        <a href="riwayat_peminjamanA.php">Riwayat Peminjaman</a>
    </div>

</nav>



<!-- =====================================================
     CONTENT
===================================================== -->

<div class="container">


    <!-- PAGE HEADER -->

    <div class="page-header">


        <!-- SEARCH -->

        <form
            method="GET"
            action="daftar_kategori.php"
            class="search-box"
        >

            <i class="fa-solid fa-magnifying-glass"></i>


            <input
                type="text"
                name="keyword"
                value="<?= htmlspecialchars(
                    $keyword,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                placeholder="Cari kategori lalu tekan Enter..."
            >

        </form>



        <!-- BUTTON TAMBAH -->

        <button
            type="button"
            class="btn-tambah"
            onclick="bukaModalTambah()"
        >

            <i class="fa-solid fa-plus"></i>

            Tambah Kategori

        </button>

    </div>



    <!-- =================================================
         TABLE
    ================================================= -->

    <div class="table-wrapper">

        <table id="tableKategori">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama Kategori</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>


                <?php if (!empty($data_kategori)): ?>


                    <?php foreach (
                        $data_kategori
                        as $index => $k
                    ): ?>


                        <tr>


                            <!-- NO -->

                            <td>

                                <?= ($start ?? 0)
                                    + $index
                                    + 1; ?>

                            </td>



                            <!-- NAMA KATEGORI -->

                            <td>

                                <span class="nama-kategori">

                                    <?= htmlspecialchars(
                                        $k->nama_kategori ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </span>

                            </td>



                            <!-- AKSI -->

                            <td class="aksi">


                                <!-- EDIT -->

                                <button
                                    type="button"
                                    class="btn-edit"
                                    title="Edit Kategori"

                                    data-id="<?= (int)
                                        $k->id_kategori; ?>"

                                    data-nama="<?= htmlspecialchars(
                                        $k->nama_kategori ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"

                                    onclick="bukaModalEditViaElement(this)"
                                >

                                    <i class="fa-solid fa-pen"></i>

                                </button>



                                <!-- HAPUS -->

                                <button
                                    type="button"
                                    class="btn-hapus"
                                    title="Hapus Kategori"

                                    onclick="hapusKategori(
                                        <?= (int)
                                        $k->id_kategori; ?>
                                    )">

                                    <i class="fa-solid fa-trash"></i>

                                </button>


                            </td>


                        </tr>


                    <?php endforeach; ?>


                <?php else: ?>


                    <!-- DATA KOSONG -->

                    <tr class="row-empty">

                        <td
                            colspan="3"
                            class="empty-data"
                        >

                            <i class="fa-solid fa-folder-open"></i>

                            <br>

                            Belum ada data kategori.

                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>

        </table>

    </div>



    <!-- =================================================
         PAGINATION
    ================================================= -->

    <?php
    if (
        isset($total_halaman)
        && $total_halaman > 1
    ):
    ?>

        <div class="pagination">


            <!-- PREVIOUS -->

            <?php if ($halaman > 1): ?>

                <a
                    href="?halaman=<?= ($halaman - 1)
                        . $kw_param; ?>"
                    title="Halaman Sebelumnya"
                >

                    <i class="fa-solid fa-chevron-left"></i>

                </a>

            <?php endif; ?>



            <!-- NOMOR HALAMAN -->

            <?php
            for (
                $i = 1;
                $i <= $total_halaman;
                $i++
            ):
            ?>

                <a
                    href="?halaman=<?= $i
                        . $kw_param; ?>"

                    class="<?= $i == $halaman
                        ? 'active'
                        : ''; ?>"
                >

                    <?= $i; ?>

                </a>

            <?php endfor; ?>



            <!-- NEXT -->

            <?php if (
                $halaman < $total_halaman
            ): ?>

                <a
                    href="?halaman=<?= ($halaman + 1)
                        . $kw_param; ?>"
                    title="Halaman Berikutnya"
                >

                    <i class="fa-solid fa-chevron-right"></i>

                </a>

            <?php endif; ?>


        </div>

    <?php endif; ?>


</div>



<!-- =====================================================
     MODAL TAMBAH
===================================================== -->

<div
    id="modalTambah"
    class="modal-overlay"
    onclick="klikLuarModal(
        event,
        'modalTambah'
    )"
>


    <div class="modal-box">


        <!-- CLOSE -->

        <button
            type="button"
            class="modal-close"
            onclick="tutupModal(
                'modalTambah'
            )"
        >

            &times;

        </button>



        <h2 class="modal-title">

            Tambah Kategori

        </h2>



        <!-- FORM -->

        <form
            action="../controller/c_kategori.php?aksi=tambah"
            method="POST"
        >


            <div class="form-group">

                <label>
                    Nama Kategori
                </label>


                <input
                    type="text"
                    name="nama_kategori"
                    placeholder="Masukkan nama kategori"
                    required
                >

            </div>



            <div class="modal-actions">


                <button
                    type="button"
                    class="btn-batal"
                    onclick="tutupModal(
                        'modalTambah'
                    )"
                >

                    Batal

                </button>


                <button
                    type="submit"
                    class="btn-simpan">
                    Kirim

                </button>


            </div>


        </form>


    </div>

</div>



<!-- =====================================================
     MODAL EDIT
===================================================== -->

<div
    id="modalEdit"
    class="modal-overlay"
    onclick="klikLuarModal(
        event,
        'modalEdit'
    )"
>


    <div class="modal-box">


        <!-- CLOSE -->

        <button
            type="button"
            class="modal-close"
            onclick="tutupModal(
                'modalEdit'
            )"
        >

            &times;

        </button>



        <h2 class="modal-title">

            Edit Kategori

        </h2>



        <!-- FORM -->

        <form
            action="../controller/c_kategori.php?aksi=edit"
            method="POST"
        >


            <input
                type="hidden"
                name="id_kategori"
                id="edit_id_kategori"
            >



            <div class="form-group">

                <label>
                    Nama Kategori
                </label>


                <input
                    type="text"
                    name="nama_kategori"
                    id="edit_nama_kategori"
                    placeholder="Masukkan nama kategori"
                    required
                >

            </div>



            <div class="modal-actions">


                <button
                    type="button"
                    class="btn-batal"
                    onclick="tutupModal(
                        'modalEdit'
                    )"
                >

                    Batal

                </button>


                <button
                    type="submit"
                    class="btn-simpan">
                    Simpan
                </button>


            </div>


        </form>


    </div>

</div>



<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>


// =====================================================
// MODAL TAMBAH
// =====================================================

function bukaModalTambah()
{
    document
        .getElementById('modalTambah')
        .classList
        .add('active');
}



// =====================================================
// MODAL EDIT
// =====================================================

function bukaModalEditViaElement(btn)
{

    const id =
        btn.dataset.id || '';


    const nama =
        btn.dataset.nama || '';


    document
        .getElementById('edit_id_kategori')
        .value = id;


    document
        .getElementById('edit_nama_kategori')
        .value = nama;


    document
        .getElementById('modalEdit')
        .classList
        .add('active');

}



// =====================================================
// TUTUP MODAL
// =====================================================

function tutupModal(idModal)
{

    document
        .getElementById(idModal)
        .classList
        .remove('active');

}



// =====================================================
// KLIK DI LUAR MODAL
// =====================================================

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



// =====================================================
// HAPUS KATEGORI
// =====================================================

function hapusKategori(id)
{

    if (
        confirm(
            'Yakin ingin menghapus kategori ini?'
        )
    )
    {

        window.location.href =
            '../controller/c_kategori.php?aksi=hapus&id='
            + id;

    }

}



// =====================================================
// ESC UNTUK MENUTUP MODAL
// =====================================================

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