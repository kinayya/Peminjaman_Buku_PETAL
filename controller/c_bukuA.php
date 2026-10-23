<?php

include_once __DIR__ . '/../models/m_bukuA.php';

$buku = new buku();

try {

    // =====================================================
    // CEK AKSI
    // =====================================================
    if (isset($_GET['aksi'])) {

        $aksi = $_GET['aksi'];

        // =================================================
        // TAMBAH DATA BUKU
        // =================================================
        if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $judul        = trim($_POST['judul'] ?? '');
            $pengarang    = trim($_POST['pengarang'] ?? '');
            $penerbit     = trim($_POST['penerbit'] ?? '');
            $tahun_terbit = $_POST['tahun_terbit'] ?? null;
            $stok         = $_POST['stok'] ?? 0;
            $sinopsis     = trim($_POST['sinopsis'] ?? '');
            $link_gambar  = trim($_POST['link_gambar'] ?? '');
            $id_kategori  = $_POST['id_kategori'] ?? null;

            // Validasi & Konversi Tipe Data
            $tahun_terbit = (empty($tahun_terbit)) ? 0 : (int) $tahun_terbit;
            $stok         = (int) $stok;
            $id_kategori  = (empty($id_kategori)) ? null : (int) $id_kategori;

            // Proses tambah
            $result = $buku->tambah_data_buku(
                $judul,
                $pengarang,
                $penerbit,
                $tahun_terbit,
                $stok,
                $sinopsis,
                $link_gambar,
                $id_kategori
            );

            if ($result) {
                echo "<script>
                        alert('Data Buku Berhasil Ditambahkan');
                        window.location='../views/daftar_bukuA.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Data Buku Gagal Ditambahkan');
                        window.history.back();
                      </script>";
            }

            exit;
        }

        // =================================================
        // EDIT DATA BUKU
        // =================================================
        elseif ($aksi === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_buku      = $_POST['id_buku'] ?? '';
            $judul        = trim($_POST['judul'] ?? '');
            $pengarang    = trim($_POST['pengarang'] ?? '');
            $penerbit     = trim($_POST['penerbit'] ?? '');
            $tahun_terbit = $_POST['tahun_terbit'] ?? null;
            $stok         = $_POST['stok'] ?? 0;
            $sinopsis     = trim($_POST['sinopsis'] ?? '');
            $link_gambar  = trim($_POST['link_gambar'] ?? '');
            $id_kategori  = $_POST['id_kategori'] ?? null;

            // Validasi ID Buku
            if (empty($id_buku)) {
                echo "<script>
                        alert('ID buku tidak ditemukan');
                        window.location='../views/daftar_bukuA.php';
                      </script>";
                exit;
            }

            // Validasi & Konversi Tipe Data
            $id_buku      = (int) $id_buku;
            $tahun_terbit = (empty($tahun_terbit)) ? 0 : (int) $tahun_terbit;
            $stok         = (int) $stok;
            $id_kategori  = (empty($id_kategori)) ? null : (int) $id_kategori;

            // Proses edit
            $result = $buku->edit_buku(
                $id_buku,
                $judul,
                $pengarang,
                $penerbit,
                $tahun_terbit,
                $stok,
                $sinopsis,
                $link_gambar,
                $id_kategori
            );

            if ($result) {
                echo "<script>
                        alert('Data Buku Berhasil Diubah');
                        window.location='../views/daftar_bukuA.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Data Buku Gagal Diubah');
                        window.history.back();
                      </script>";
            }

            exit;
        }

        // =================================================
        // HAPUS DATA BUKU
        // =================================================
        elseif ($aksi === 'hapus') {

            // Menangkap id_buku ATAU id dari URL
            $id_buku = $_GET['id_buku'] ?? $_GET['id'] ?? 0;
            $id_buku = (int) $id_buku;

            if ($id_buku > 0) {

                $result = $buku->hapus_data_buku($id_buku);

                if ($result) {
                    echo "<script>
                            alert('Data Buku Berhasil Dihapus');
                            window.location='../views/daftar_bukuA.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Data Buku Gagal Dihapus.');
                            window.location='../views/daftar_bukuA.php';
                          </script>";
                }

            } else {
                echo "<script>
                        alert('ID Buku tidak valid');
                        window.location='../views/daftar_bukuA.php';
                      </script>";
            }

            exit;
        }

        // =================================================
        // AMBIL DATA UNTUK FORM EDIT
        // =================================================
        elseif ($aksi === 'edit_form') {

            $id_buku = $_GET['id_buku'] ?? '';

            if (empty($id_buku)) {
                header("Location: ../views/daftar_bukuA.php");
                exit;
            }

            $data_edit = $buku->tampil_satu_buku((int) $id_buku);
            $data_kategori = $buku->tampil_kategori();

            if (!$data_edit) {
                echo "<script>
                        alert('Data buku tidak ditemukan');
                        window.location='../views/daftar_bukuA.php';
                      </script>";
                exit;
            }
        }
    }

    // =====================================================
    // PAGINATION & TAMPIL DATA (DENGAN PENCARIAN)
    // =====================================================
    if (!isset($_GET['aksi']) || $_GET['aksi'] !== 'edit_form') {

        $limit = 10;
        $halaman = isset($_GET['halaman']) ? max(1, (int) $_GET['halaman']) : 1;
        $start = ($halaman - 1) * $limit;

        // Tangkap kata kunci pencarian dari URL
        $keyword = trim($_GET['keyword'] ?? '');

        if (!empty($keyword)) {
            // Mengambil data terfilter jika kata kunci diisi
            $total_data = $buku->hitung_total_buku_cari($keyword);
            $total_halaman = ceil($total_data / $limit);
            $data_buku = $buku->tampil_data_buku_cari_page($keyword, $start, $limit);
        } else {
            // Mengambil seluruh data jika pencarian kosong
            $total_data = $buku->hitung_total_buku();
            $total_halaman = ceil($total_data / $limit);
            $data_buku = $buku->tampil_data_buku_page($start, $limit);
        }

        $data_kategori = $buku->tampil_kategori();
    }

} catch (Exception $e) {

    echo "Error: " . $e->getMessage();

}

?>