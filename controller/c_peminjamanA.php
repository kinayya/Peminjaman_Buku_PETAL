<?php

session_start();

include_once __DIR__ . '/../models/m_peminjaman.php';

$peminjaman = new m_peminjaman();

// =====================================================
// PROTEKSI HALAMAN: HANYA ADMIN
// =====================================================
if (
    !isset($_SESSION['data']) ||
    !isset($_SESSION['data']['role']) ||
    $_SESSION['data']['role'] !== 'admin'
) {
    echo "<script>
            alert('Silakan login sebagai admin terlebih dahulu.');
            window.location='../index.php';
          </script>";
    exit;
}

try {

    // =====================================================
    // CEK AKSI
    // =====================================================
    if (isset($_GET['aksi'])) {

        $aksi           = $_GET['aksi'];
        $id_peminjaman  = (int) ($_GET['id_peminjaman'] ?? $_GET['id'] ?? 0);

        // =================================================
        // SETUJUI PEMINJAMAN
        // =================================================
        if ($aksi === 'setujui') {

            if ($id_peminjaman > 0) {

                $result = $peminjaman->setujui_peminjaman($id_peminjaman);

                if ($result) {
                    echo "<script>
                            alert('Peminjaman berhasil disetujui. Stok buku telah dikurangi.');
                            window.location='../views/peminjamanA.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Gagal menyetujui peminjaman.');
                            window.location='../views/peminjamanA.php';
                          </script>";
                }

            } else {
                echo "<script>
                        alert('ID peminjaman tidak valid');
                        window.location='../views/peminjamanA.php';
                      </script>";
            }

            exit;
        }

        // =================================================
        // TOLAK PEMINJAMAN
        // =================================================
        elseif ($aksi === 'tolak') {

            if ($id_peminjaman > 0) {

                $result = $peminjaman->tolak_peminjaman($id_peminjaman);

                if ($result) {
                    echo "<script>
                            alert('Peminjaman berhasil ditolak.');
                            window.location='../views/peminjamanA.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Gagal menolak peminjaman.');
                            window.location='../views/peminjamanA.php';
                          </script>";
                }

            } else {
                echo "<script>
                        alert('ID peminjaman tidak valid');
                        window.location='../views/peminjamanA.php';
                      </script>";
            }

            exit;
        }

        // =================================================
        // TANDAI BUKU SUDAH DIAMBIL (DIPINJAM)
        // =================================================
        elseif ($aksi === 'dipinjam') {

            if ($id_peminjaman > 0) {

                $result = $peminjaman->tandai_dipinjam($id_peminjaman);

                if ($result) {
                    echo "<script>
                            alert('Peminjaman ditandai sebagai sedang dipinjam.');
                            window.location='../views/peminjamanA.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Gagal memperbarui status. Bisa jadi status sudah berubah.');
                            window.location='../views/peminjamanA.php';
                          </script>";
                }

            } else {
                echo "<script>
                        alert('ID peminjaman tidak valid');
                        window.location='../views/peminjamanA.php';
                      </script>";
            }

            exit;
        }
    }

    // =====================================================
    // PAGINATION & TAMPIL DATA (DENGAN PENCARIAN)
    // =====================================================
    $limit   = 10;
    $halaman = isset($_GET['halaman']) ? max(1, (int) $_GET['halaman']) : 1;
    $start   = ($halaman - 1) * $limit;

    $keyword = trim($_GET['keyword'] ?? '');

    if (!empty($keyword)) {
        $total_data     = $peminjaman->hitung_total_peminjaman_cari($keyword);
        $total_halaman  = ceil($total_data / $limit);
        $data_peminjaman = $peminjaman->tampil_data_peminjaman_cari_page($keyword, $start, $limit);
    } else {
        $total_data     = $peminjaman->hitung_total_peminjaman();
        $total_halaman  = ceil($total_data / $limit);
        $data_peminjaman = $peminjaman->tampil_data_peminjaman_page($start, $limit);
    }

} catch (Exception $e) {

    echo "Error: " . $e->getMessage();

}

?>
