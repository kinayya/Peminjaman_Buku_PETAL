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
    // AKSI: KONFIRMASI / PROSES PENGEMBALIAN
    // =====================================================

    if (isset($_GET['aksi']) && $_GET['aksi'] === 'konfirmasi') {

        $id_peminjaman = (int) ($_GET['id_peminjaman'] ?? $_GET['id'] ?? 0);
        $keterangan    = trim($_GET['keterangan'] ?? '');

        if ($id_peminjaman > 0) {

            $result = $peminjaman->konfirmasi_pengembalian($id_peminjaman, $keterangan);

            if ($result) {
                echo "<script>
                        alert('Pengembalian berhasil dikonfirmasi. Stok buku telah ditambahkan kembali.');
                        window.location='../views/pengembalianA.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Gagal mengonfirmasi pengembalian');
                        window.location='../views/pengembalianA.php';
                      </script>";
            }

        } else {
            echo "<script>
                    alert('ID peminjaman tidak valid');
                    window.location='../views/pengembalianA.php';
                  </script>";
        }

        exit;
    }


    // =====================================================
    // PAGINATION & TAMPIL DATA (DENGAN PENCARIAN)
    // =====================================================

    $limit   = 10;
    $halaman = isset($_GET['halaman']) ? max(1, (int) $_GET['halaman']) : 1;
    $start   = ($halaman - 1) * $limit;

    $keyword = trim($_GET['keyword'] ?? '');

    if (!empty($keyword)) {
        $total_data        = $peminjaman->hitung_total_pengembalian_cari($keyword);
        $total_halaman     = ceil($total_data / $limit);
        $data_pengembalian = $peminjaman->tampil_data_pengembalian_cari_page($keyword, $start, $limit);
    } else {
        $total_data        = $peminjaman->hitung_total_pengembalian();
        $total_halaman     = ceil($total_data / $limit);
        $data_pengembalian = $peminjaman->tampil_data_pengembalian_page($start, $limit);
    }

    // =====================================================
    // STATISTIK RINGKAS UNTUK KARTU DI ATAS TABEL
    // =====================================================

    $stat_menunggu_konfirmasi = $peminjaman->hitung_menunggu_konfirmasi();
    $stat_sedang_dipinjam     = $peminjaman->hitung_sedang_dipinjam_tanpa_pengajuan();
    $stat_total_dikembalikan  = $peminjaman->hitung_total_dikembalikan();

    $nama_admin = $_SESSION['data']['username'] ?? 'Admin';

} catch (Exception $e) {

    echo "Error: " . $e->getMessage();

}
?>
