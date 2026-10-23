<?php

include_once __DIR__ . '/../models/m_user.php';

$user = new m_user();

try {

    // =====================================================
    // CEK AKSI
    // =====================================================

    if (isset($_GET['aksi'])) {

        $aksi = $_GET['aksi'];


        // =================================================
        // TAMBAH USER
        // =================================================

        if (
            $aksi === 'tambah' &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {

            $username = trim(
                $_POST['username'] ?? ''
            );

            $Email = trim(
                $_POST['Email'] ?? ''
            );

            $password = $_POST['password'] ?? '';

            $role = trim(
                $_POST['role'] ?? ''
            );


            // VALIDASI

            if (
                empty($username) ||
                empty($Email) ||
                empty($password) ||
                empty($role)
            ) {

                echo "<script>
                        alert('Semua data pengguna wajib diisi.');
                        window.history.back();
                      </script>";

                exit;
            }


            // VALIDASI ROLE

            $allowed_roles = [
                'admin',
                'siswa',
                'staff',
                'guru'
            ];

            if (
                !in_array(
                    $role,
                    $allowed_roles,
                    true
                )
            ) {

                echo "<script>
                        alert('Role tidak valid.');
                        window.history.back();
                      </script>";

                exit;
            }


            // CEK USERNAME / EMAIL

            $cekUsername =
                $user->login($username);

            $cekEmail =
                $user->login($Email);


            if (
                $cekUsername ||
                $cekEmail
            ) {

                echo "<script>
                        alert('Username atau Email sudah digunakan.');
                        window.history.back();
                      </script>";

                exit;
            }


            // HASH PASSWORD

            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


            // SIMPAN

            $result =
                $user->tambah_data(
                    $username,
                    $Email,
                    $hashed_password,
                    $role
                );


            if ($result) {

                echo "<script>
                        alert('Pengguna Berhasil Ditambahkan.');
                        window.location='../views/daftar_user.php';
                      </script>";

            } else {

                echo "<script>
                        alert('Pengguna Gagal Ditambahkan.');
                        window.history.back();
                      </script>";
            }

            exit;
        }


        // =================================================
        // EDIT USER
        // =================================================

        elseif (
            $aksi === 'edit' &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {

            $id_user =
                (int) (
                    $_POST['id_user'] ?? 0
                );

            $username = trim(
                $_POST['username'] ?? ''
            );

            $Email = trim(
                $_POST['Email'] ?? ''
            );

            $role = trim(
                $_POST['role'] ?? ''
            );

            $password =
                $_POST['password'] ?? '';


            if ($id_user <= 0) {

                echo "<script>
                        alert('ID user tidak valid.');
                        window.history.back();
                      </script>";

                exit;
            }


            if (
                empty($username) ||
                empty($Email) ||
                empty($role)
            ) {

                echo "<script>
                        alert('Username, Email, dan Role wajib diisi.');
                        window.history.back();
                      </script>";

                exit;
            }


            $allowed_roles = [
                'admin',
                'siswa',
                'staff',
                'guru'
            ];


            if (
                !in_array(
                    $role,
                    $allowed_roles,
                    true
                )
            ) {

                echo "<script>
                        alert('Role tidak valid.');
                        window.history.back();
                      </script>";

                exit;
            }


            // UPDATE DATA USER

            $result =
                $user->edit(
                    $id_user,
                    $username,
                    $Email,
                    $role
                );


            // JIKA PASSWORD DIISI,
            // PASSWORD JUGA DIUBAH

            if (
                $result &&
                !empty($password)
            ) {

                $hashed_password =
                    password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );

                $resultPassword =
                    $user->edit_password(
                        $id_user,
                        $hashed_password
                    );

                $result =
                    $resultPassword;
            }


            if ($result) {

                echo "<script>
                        alert('Data Pengguna Berhasil Diubah.');
                        window.location='../views/daftar_user.php';
                      </script>";

            } else {

                echo "<script>
                        alert('Data Pengguna Gagal Diubah.');
                        window.history.back();
                      </script>";
            }

            exit;
        }


        // =================================================
        // HAPUS USER
        // =================================================

        elseif ($aksi === 'hapus') {

            $id_user =
                (int) (
                    $_GET['id_user']
                    ?? $_GET['id']
                    ?? 0
                );


            if ($id_user <= 0) {

                echo "<script>
                        alert('ID user tidak valid.');
                        window.location='../views/daftar_user.php';
                      </script>";

                exit;
            }


            $result =
                $user->hapus_data(
                    $id_user
                );


            if ($result) {

                echo "<script>
                        alert('Pengguna berhasil dihapus.');
                        window.location='../views/daftar_user.php';
                      </script>";

            } else {

                echo "<script>
                        alert('Pengguna gagal dihapus.');
                        window.location='../views/daftar_user.php';
                      </script>";
            }

            exit;
        }
    }


    // =====================================================
    // PAGINATION
    // =====================================================

    $limit = 10;


    $halaman =
        isset($_GET['halaman'])
        ? max(
            1,
            (int) $_GET['halaman']
        )
        : 1;


    $start =
        ($halaman - 1) * $limit;


    $keyword =
        trim(
            $_GET['keyword'] ?? ''
        );


    // =====================================================
    // SEARCH
    // =====================================================

    if (!empty($keyword)) {

        $total_data =
            $user->hitung_total_cari(
                $keyword
            );


        $total_halaman =
            ceil(
                $total_data / $limit
            );


        $data_user =
            $user->tampil_data_cari_page(
                $keyword,
                $start,
                $limit
            );

    }


    // =====================================================
    // SEMUA DATA
    // =====================================================

    else {

        $total_data =
            $user->hitung_total();


        $total_halaman =
            ceil(
                $total_data / $limit
            );


        $data_user =
            $user->tampil_data_page(
                $start,
                $limit
            );
    }


} catch (Exception $e) {

    echo "Error: "
        . $e->getMessage();

}

?>