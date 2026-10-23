<?php

include_once __DIR__ . '/../models/m_kategori.php';

$kategori = new kategori();

try {

    // =====================================================
    // CEK AKSI
    // =====================================================

    if (isset($_GET['aksi'])) {

        $aksi = $_GET['aksi'];


        // =================================================
        // TAMBAH KATEGORI
        // =================================================

        if (
            $aksi === 'tambah' &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {

            $nama_kategori = trim(
                $_POST['nama_kategori'] ?? ''
            );


            if (empty($nama_kategori)) {

                echo "<script>
                        alert('Nama kategori tidak boleh kosong');
                        window.history.back();
                      </script>";

                exit;
            }


            $result =
                $kategori->tambah_data_kategori(
                    $nama_kategori
                );


            if ($result) {

                echo "<script>
                        alert('Kategori Berhasil Ditambahkan');
                        window.location='../views/daftar_kategori.php';
                      </script>";

            } else {

                echo "<script>
                        alert('Kategori Gagal Ditambahkan');
                        window.history.back();
                      </script>";
            }

            exit;
        }


        // =================================================
        // EDIT KATEGORI
        // =================================================

        elseif (
            $aksi === 'edit' &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {

            $id_kategori =
                $_POST['id_kategori'] ?? '';

            $nama_kategori =
                trim($_POST['nama_kategori'] ?? '');


            if (empty($id_kategori)) {

                echo "<script>
                        alert('ID kategori tidak ditemukan');
                        window.location='../views/daftar_kategori.php';
                      </script>";

                exit;
            }


            if (empty($nama_kategori)) {

                echo "<script>
                        alert('Nama kategori tidak boleh kosong');
                        window.history.back();
                      </script>";

                exit;
            }


            $id_kategori = (int) $id_kategori;


            $result =
                $kategori->edit_kategori(
                    $id_kategori,
                    $nama_kategori
                );


            if ($result) {

                echo "<script>
                        alert('Kategori Berhasil Diubah');
                        window.location='../views/daftar_kategori.php';
                      </script>";

            } else {

                echo "<script>
                        alert('Kategori Gagal Diubah');
                        window.history.back();
                      </script>";
            }

            exit;
        }


        // =================================================
        // HAPUS KATEGORI
        // =================================================

        elseif ($aksi === 'hapus') {

            $id_kategori =
                $_GET['id_kategori']
                ?? $_GET['id']
                ?? 0;

            $id_kategori = (int) $id_kategori;


            if ($id_kategori > 0) {

                $result =
                    $kategori->hapus_data_kategori(
                        $id_kategori
                    );


                if ($result) {

                    echo "<script>
                            alert('Kategori Berhasil Dihapus');
                            window.location='../views/daftar_kategori.php';
                          </script>";

                } else {

                    echo "<script>
                            alert('Kategori Gagal Dihapus');
                            window.location='../views/daftar_kategori.php';
                          </script>";
                }

            } else {

                echo "<script>
                        alert('ID kategori tidak valid');
                        window.location='../views/daftar_kategori.php';
                      </script>";
            }

            exit;
        }
    }


    // =====================================================
    // PAGINATION + SEARCH
    // =====================================================

    $limit = 10;


    $halaman =
        isset($_GET['halaman'])
        ? max(1, (int) $_GET['halaman'])
        : 1;


    $start =
        ($halaman - 1) * $limit;


    $keyword =
        trim($_GET['keyword'] ?? '');


    // =====================================================
    // JIKA ADA PENCARIAN
    // =====================================================

    if (!empty($keyword)) {

        $total_data =
            $kategori->hitung_total_kategori_cari(
                $keyword
            );


        $total_halaman =
            ceil($total_data / $limit);


        $data_kategori =
            $kategori->tampil_data_kategori_cari_page(
                $keyword,
                $start,
                $limit
            );

    }


    // =====================================================
    // JIKA TIDAK ADA PENCARIAN
    // =====================================================

    else {

        $total_data =
            $kategori->hitung_total_kategori();


        $total_halaman =
            ceil($total_data / $limit);


        $data_kategori =
            $kategori->tampil_data_kategori_page(
                $start,
                $limit
            );
    }


} catch (Exception $e) {

    echo "Error: " . $e->getMessage();

}

?>