<?php

include_once __DIR__ . '/../controller/c_user.php';

$keyword =
    $_GET['keyword'] ?? '';

$kw_param =
    !empty($keyword)
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

    <title>
        Daftar User - Progresif Library
    </title>


    <!-- CSS SAMA SEPERTI DAFTAR BUKU -->

    <link
        rel="stylesheet"
        href="../css/daftar_bu.css"
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
        <a href="daftar_user.php" class="active">Daftar Pengguna</a>
        <a href="daftar_kategori.php">Daftar Kategori</a>
        <a href="peminjamanA.php">Peminjaman</a>
        <a href="pengembalianA.php">Pengembalian</a>
        <a href="riwayat_peminjamanA.php">Riwayat Peminjaman</a>
    </div>

</nav>



<!-- =====================================================
     CONTENT
===================================================== -->

<div class="container">


    <!-- =================================================
         HEADER
    ================================================= -->

    <div class="page-header">


        <!-- SEARCH -->

        <form
            method="GET"
            action="daftar_user.php"
            class="search-box"
        >

            <i
                class="fa-solid fa-magnifying-glass"
            ></i>


            <input
                type="text"
                name="keyword"

                value="<?= htmlspecialchars(
                    $keyword,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"

                placeholder="Cari username, email, role..."
            >

        </form>



        <!-- TAMBAH USER -->

        <button
            type="button"
            class="btn-tambah"
            onclick="bukaModalTambah()"
        >

            <i
                class="fa-solid fa-plus"
            ></i>

            Tambah Pengguna

        </button>


    </div>



    <!-- =================================================
         TABLE USER
    ================================================= -->
<div class="table-wrapper">

    <table id="tableBuku">

        <thead>

            <tr>

                <th>
                    No
                </th>

                <th>
                    Email
                </th>

                <th>
                    Username
                </th>

                <th>
                    Password
                </th>

                <th>
                    Role
                </th>

                <th>
                    Aksi
                </th>

            </tr>

        </thead>


        <tbody>

            <?php if (!empty($data_user)): ?>

                <?php foreach (
                    $data_user as $index => $u
                ): ?>

                    <tr>

                        <!-- NO -->

                        <td>

                            <?= ($start ?? 0)
                                + $index
                                + 1; ?>

                        </td>


                        <!-- EMAIL -->

                        <td>

                            <?= htmlspecialchars(
                                $u->Email ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </td>


                        <!-- USERNAME -->

                        <td>

                            <span class="judul-buku">

                                <?= htmlspecialchars(
                                    $u->username ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </span>

                        </td>


                        <!-- PASSWORD -->

                        <td>

                            <span class="password-user">

                                <?= htmlspecialchars(
                                    $u->password ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </span>

                        </td>


                        <!-- ROLE -->

                        <td>

                            <span class="stok">

                                <?= htmlspecialchars(
                                    ucfirst(
                                        $u->role ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </span>

                        </td>


                        <!-- AKSI -->

                        <td class="aksi">

                            <button
                                type="button"
                                class="btn-edit"

                                title="Edit User"

                                data-id="<?= (int)
                                    $u->id_user; ?>"

                                data-username="<?= htmlspecialchars(
                                    $u->username ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"

                                data-email="<?= htmlspecialchars(
                                    $u->Email ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"

                                data-role="<?= htmlspecialchars(
                                    $u->role ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"

                                onclick="bukaModalEdit(this)"
                            >

                                <i
                                    class="fa-solid fa-pen"
                                ></i>

                            </button>


                            <button
                                type="button"
                                class="btn-hapus"

                                title="Hapus User"

                                onclick="hapusUser(
                                    <?= (int)
                                    $u->id_user; ?>
                                )"
                            >

                                <i
                                    class="fa-solid fa-trash"
                                ></i>

                            </button>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td
                        colspan="6"
                        class="empty-data"
                    >

                        <i
                            class="fa-solid fa-users"
                        ></i>

                        <br>

                        Belum ada data user.

                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>


    <!-- =================================================
         PAGINATION
    ================================================= -->

    <?php if (
        isset($total_halaman)
        &&
        $total_halaman > 1
    ): ?>


        <div class="pagination">


            <!-- PREVIOUS -->

            <?php if (
                $halaman > 1
            ): ?>


                <a
                    href="?halaman=<?= (
                        $halaman - 1
                    ) . $kw_param; ?>"
                >

                    <i
                        class="fa-solid fa-chevron-left"
                    ></i>

                </a>


            <?php endif; ?>



            <!-- NOMOR -->

            <?php for (
                $i = 1;
                $i <= $total_halaman;
                $i++
            ): ?>


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
                    href="?halaman=<?= (
                        $halaman + 1
                    ) . $kw_param; ?>"
                >

                    <i
                        class="fa-solid fa-chevron-right"
                    ></i>

                </a>


            <?php endif; ?>


        </div>


    <?php endif; ?>


</div>



<!-- =====================================================
     MODAL TAMBAH USER
===================================================== -->

<div
    id="modalTambah"
    class="modal-overlay"

    onclick="
        klikLuarModal(
            event,
            'modalTambah'
        )
    "
>


    <div class="modal-box">


        <!-- CLOSE -->

        <button
            type="button"
            class="modal-close"

            onclick="
                tutupModal(
                    'modalTambah'
                )
            "
        >

            &times;

        </button>



        <h2 class="modal-title">

            Tambah Pengguna

        </h2>



        <form
            action="../controller/c_user.php?aksi=tambah"
            method="POST"
        >


            <!-- USERNAME -->

            <div class="form-group">

                <label>
                    Username
                </label>

                <input
                    type="text"
                    name="username"

                    placeholder="Masukkan username"

                    required
                >

            </div>



            <!-- EMAIL -->

            <div class="form-group">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="Email"

                    placeholder="Masukkan email"

                    required
                >

            </div>



            <!-- PASSWORD -->
<div class="form-group">
    <label>
        Password
    </label>

    <div class="password-wrapper">
        <input
            type="password"
            name="password"
            id="tambah_password"
            placeholder="Masukkan password"
            required
        >

        <button
            type="button"
            class="toggle-password"
            onclick="togglePassword('tambah_password', this)"
            title="Lihat password"
        >
            <i class="fa-solid fa-eye"></i>
        </button>
    </div>
</div>



            <!-- ROLE -->

            <div class="form-group">

                <label>
                    Role
                </label>

                <select
                    name="role"
                    required
                >

                    <option value="">
                        -- Pilih Role --
                    </option>


                    <option value="siswa">
                        Siswa
                    </option>


                    <option value="admin">
                        Admin
                    </option>
                    <option value="staff">
                        Staff
                    </option>
                    <option value="guru">
                        Guru
                    </option>

                </select>

            </div>



            <!-- BUTTON -->

            <div class="modal-actions">


                <button
                    type="button"
                    class="btn-batal"

                    onclick="
                        tutupModal(
                            'modalTambah'
                        )
                    "
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
     MODAL EDIT USER
===================================================== -->

<div
    id="modalEdit"
    class="modal-overlay"

    onclick="
        klikLuarModal(
            event,
            'modalEdit'
        )
    "
>


    <div class="modal-box">


        <!-- CLOSE -->

        <button
            type="button"
            class="modal-close"

            onclick="
                tutupModal(
                    'modalEdit'
                )
            "
        >

            &times;

        </button>



        <h2 class="modal-title">

            Edit Pengguna

        </h2>



        <form
            action="../controller/c_user.php?aksi=edit"
            method="POST"
        >


            <!-- ID -->

            <input
                type="hidden"
                name="id_user"
                id="edit_id_user"
            >



            <!-- USERNAME -->

            <div class="form-group">

                <label>
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    id="edit_username"

                    required
                >

            </div>



            <!-- EMAIL -->

            <div class="form-group">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="Email"
                    id="edit_email"

                    required
                >

            </div>



            <!-- PASSWORD -->
            <div class="form-group">
                <label>
                    Password Baru
                </label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        name="password"
                        id="edit_password"
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('edit_password', this)"
                        title="Lihat password"
                    >
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>



            <!-- ROLE -->

            <div class="form-group">

                <label>
                    Role
                </label>

                <select
                    name="role"
                    id="edit_role"

                    required
                >

                    <option value="siswa">
                        Siswa
                    </option>


                    <option value="admin">
                        Admin
                    </option>
                    <option value="staff">
                        Staff
                    </option>
                    <option value="guru">
                        Guru
                    </option>

                </select>

            </div>



            <!-- BUTTON -->

            <div class="modal-actions">


                <button
                    type="button"
                    class="btn-batal"

                    onclick="
                        tutupModal(
                            'modalEdit'
                        )
                    "
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
// BUKA MODAL TAMBAH
// =====================================================

function bukaModalTambah()
{

    document
        .getElementById(
            'modalTambah'
        )
        .classList
        .add('active');

}



// =====================================================
// BUKA MODAL EDIT
// =====================================================

function bukaModalEdit(button)
{

    const id =
        button.dataset.id || '';


    const username =
        button.dataset.username || '';


    const email =
        button.dataset.email || '';


    const role =
        button.dataset.role || '';


    document
        .getElementById(
            'edit_id_user'
        )
        .value = id;


    document
        .getElementById(
            'edit_username'
        )
        .value = username;


    document
        .getElementById(
            'edit_email'
        )
        .value = email;


    document
        .getElementById(
            'edit_role'
        )
        .value = role;


    document
        .getElementById(
            'edit_password'
        )
        .value = '';


    document
        .getElementById(
            'modalEdit'
        )
        .classList
        .add('active');

}



// =====================================================
// TUTUP MODAL
// =====================================================

function tutupModal(id)
{

    document
        .getElementById(id)
        .classList
        .remove('active');

}



// =====================================================
// KLIK DI LUAR MODAL
// =====================================================

function klikLuarModal(
    event,
    id
)
{

    if (
        event.target ===
        document.getElementById(id)
    )
    {

        tutupModal(id);

    }

}



// =====================================================
// HAPUS USER
// =====================================================

function hapusUser(id)
{

    const yakin =
        confirm(
            'Yakin ingin menghapus pengguna ini?'
        );


    if (yakin)
    {

        window.location.href =
            '../controller/c_user.php'
            + '?aksi=hapus&id='
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

        if (
            event.key === 'Escape'
        )
        {

            document
                .querySelectorAll(
                    '.modal-overlay'
                )
                .forEach(
                    function(modal)
                    {

                        modal
                            .classList
                            .remove(
                                'active'
                            );

                    }
                );

        }

    }
);

// =====================================================
// TOGGLE LIHAT PASSWORD
// =====================================================

function togglePassword(inputId, button) {

    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');

        button.title = 'Sembunyikan password';

    } else {

        input.type = 'password';

        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');

        button.title = 'Lihat password';
    }
}

</script>


</body>

</html>