<?php

include_once __DIR__ . '/koneksi.php';

// =====================================================
// MODEL DATA GRAFIK DASHBOARD ADMIN (hanya membaca data)
// =====================================================

class m_dashboard
{
    private $conn;

    public function __construct()
    {
        $db = new koneksi();
        $this->conn = $db->koneksi;
    }

    private function hitung($sql)
    {
        $query = mysqli_query($this->conn, $sql);

        if (!$query) {
            return 0;
        }

        $data = mysqli_fetch_assoc($query);

        return (int) ($data['total'] ?? 0);
    }


    // =====================================================
    // KARTU RINGKAS
    // =====================================================

    public function total_buku()
    {
        return $this->hitung("SELECT COUNT(*) AS total FROM buku");
    }

    public function total_user()
    {
        return $this->hitung("SELECT COUNT(*) AS total FROM users");
    }


    // =====================================================
    // GRAFIK 1: JUMLAH PEMINJAMAN PER BULAN
    // =====================================================

    public function peminjaman_per_bulan($jumlah_bulan = 6)
    {
        $jumlah_bulan = max(1, (int) $jumlah_bulan);

        $nama_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                       'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $awal = new DateTime('first day of this month');
        $awal->modify('-' . ($jumlah_bulan - 1) . ' months');

        $tgl_awal = $awal->format('Y-m-d');

        $sql = "SELECT DATE_FORMAT(tanggal_pinjam, '%Y-%m') AS bulan,
                       COUNT(*) AS total
                FROM peminjaman
                WHERE tanggal_pinjam >= ?
                GROUP BY bulan";

        $hasil = [];

        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $tgl_awal);
            $stmt->execute();

            $query = $stmt->get_result();

            while ($row = $query->fetch_assoc()) {
                $hasil[$row['bulan']] = (int) $row['total'];
            }

            $stmt->close();
        }

        $labels = [];
        $data   = [];

        $cur = clone $awal;

        for ($i = 0; $i < $jumlah_bulan; $i++) {
            $labels[] = $nama_bulan[(int) $cur->format('n') - 1] . ' ' . $cur->format('y');
            $data[]   = $hasil[$cur->format('Y-m')] ?? 0;

            $cur->modify('+1 month');
        }

        return ['labels' => $labels, 'data' => $data];
    }


    // =====================================================
    // GRAFIK 2: PEMINJAMAN BERDASARKAN STATUS
    // =====================================================

    public function peminjaman_per_status()
    {
        $urutan = [
            'pengajuan'    => 'Pengajuan',
            'disetujui'    => 'Disetujui',
            'dipinjam'     => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'ditolak'      => 'Ditolak',
        ];

        $jumlah = array_fill_keys(array_keys($urutan), 0);

        $query = mysqli_query(
            $this->conn,
            "SELECT status, COUNT(*) AS total FROM peminjaman GROUP BY status"
        );

        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                if (isset($jumlah[$row['status']])) {
                    $jumlah[$row['status']] = (int) $row['total'];
                }
            }
        }

        return [
            'labels' => array_values($urutan),
            'data'   => array_values($jumlah),
        ];
    }


    // =====================================================
    // GRAFIK 3: BUKU PALING BANYAK DIPINJAM
    // (hanya peminjaman yang benar-benar disetujui)
    // =====================================================

    public function buku_terpopuler($limit = 5)
    {
        $limit = max(1, (int) $limit);

        $sql = "SELECT buku.judul, COUNT(*) AS total
                FROM peminjaman
                INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
                WHERE peminjaman.status IN ('disetujui', 'dipinjam', 'dikembalikan')
                GROUP BY buku.id_buku, buku.judul
                ORDER BY total DESC, buku.judul ASC
                LIMIT $limit";

        $labels = [];
        $data   = [];

        $query = mysqli_query($this->conn, $sql);

        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                $labels[] = $row['judul'];
                $data[]   = (int) $row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }


    // =====================================================
    // MOST POPULAR DASHBOARD USER
    // Mengembalikan daftar baris lengkap (judul, pengarang, cover,
    // total_dipinjam) agar bisa ditampilkan sebagai card.
    // Kriteria sama dengan grafik admin: hanya peminjaman yang
    // benar-benar disetujui (disetujui, dipinjam, dikembalikan).
    // =====================================================

    public function buku_populer_user($limit = 5)
    {
        $limit = max(1, (int) $limit);

        $sql = "SELECT buku.id_buku,
                       buku.judul,
                       buku.pengarang,
                       buku.link_gambar,
                       COUNT(peminjaman.id_peminjaman) AS total_dipinjam
                FROM peminjaman
                INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
                WHERE peminjaman.status IN ('disetujui', 'dipinjam', 'dikembalikan')
                GROUP BY buku.id_buku, buku.judul, buku.pengarang, buku.link_gambar
                ORDER BY total_dipinjam DESC, buku.judul ASC
                LIMIT $limit";

        $hasil = [];

        $query = mysqli_query($this->conn, $sql);

        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                $row['total_dipinjam'] = (int) $row['total_dipinjam'];
                $hasil[] = $row;
            }
        }

        return $hasil;
    }


    // =====================================================
    // GRAFIK 4: JUMLAH JUDUL BUKU PER KATEGORI
    // =====================================================

    public function buku_per_kategori()
    {
        $sql = "SELECT COALESCE(kategori.nama_kategori, 'Tanpa Kategori') AS nama,
                       COUNT(buku.id_buku) AS total
                FROM buku
                LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori
                GROUP BY buku.id_kategori, kategori.nama_kategori
                ORDER BY total DESC, nama ASC";

        $labels = [];
        $data   = [];

        $query = mysqli_query($this->conn, $sql);

        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                $labels[] = $row['nama'];
                $data[]   = (int) $row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }
    // =====================================================
    // RIWAYAT PEMINJAMAN USER TERBARU UNTUK ADMIN
    // =====================================================
    public function riwayat_peminjaman_user_admin($limit = 8, $keyword = '')
    {
        $limit = max(1, (int) $limit);

        $base = "SELECT
                    peminjaman.id_peminjaman,
                    peminjaman.id_user,
                    peminjaman.tanggal_pinjam,
                    peminjaman.tanggal_kembali,
                    peminjaman.status,
                    users.username,
                    users.Email,
                    buku.judul,
                    buku.link_gambar
                FROM peminjaman
                LEFT JOIN users ON peminjaman.id_user = users.id_user
                LEFT JOIN buku ON peminjaman.id_buku = buku.id_buku";

        $hasil = [];

        if ($keyword !== '') {
            $sql = $base . " WHERE users.username LIKE ?
                              OR users.Email LIKE ?
                              OR buku.judul LIKE ?
                              OR peminjaman.status LIKE ?
                           ORDER BY peminjaman.id_peminjaman DESC
                           LIMIT $limit";

            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $like = '%' . $keyword . '%';
                $stmt->bind_param('ssss', $like, $like, $like, $like);
                $stmt->execute();
                $query = $stmt->get_result();
                while ($row = $query->fetch_object()) {
                    $hasil[] = $row;
                }
                $stmt->close();
            }
        } else {
            $query = mysqli_query(
                $this->conn,
                $base . " ORDER BY peminjaman.id_peminjaman DESC LIMIT $limit"
            );

            if ($query) {
                while ($row = mysqli_fetch_object($query)) {
                    $hasil[] = $row;
                }
            }
        }

        return $hasil;
    }

    public function hitung_total_riwayat_user_admin()
    {
        return $this->hitung("SELECT COUNT(*) AS total FROM peminjaman");
    }

}

?>