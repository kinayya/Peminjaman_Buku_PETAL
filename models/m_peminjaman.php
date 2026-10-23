<?php

include_once __DIR__ . '/koneksi.php';

class m_peminjaman
{
    private $conn;

    public function __construct()
    {
        $db = new koneksi();
        $this->conn = $db->koneksi;
    }

    // =====================================================
    // TAMBAH PEMINJAMAN
    // =====================================================

    public function tambah_peminjaman(
        $id_user,
        $id_buku,
        $tanggal_pinjam,
        $tanggal_kembali
    ) {
        $sql = "INSERT INTO peminjaman
                (
                    id_user,
                    id_buku,
                    tanggal_pinjam,
                    tanggal_kembali,
                    status
                )
                VALUES (?, ?, ?, ?, 'pengajuan')";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "iiss",
            $id_user,
            $id_buku,
            $tanggal_pinjam,
            $tanggal_kembali
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // CEK APAKAH USER MASIH MEMILIKI PEMINJAMAN AKTIF
    // =====================================================

    public function cek_peminjaman_aktif(
        $id_user,
        $id_buku
    ) {
        $sql = "SELECT id_peminjaman
                FROM peminjaman
                WHERE id_user = ?
                AND id_buku = ?
                AND status IN ('pengajuan', 'disetujui', 'dipinjam')
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ii",
            $id_user,
            $id_buku
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $ada = $result->num_rows > 0;

        $stmt->close();

        return $ada;
    }


    // =====================================================
    // CEK STOK BUKU
    // =====================================================

    public function cek_stok($id_buku)
    {
        $sql = "SELECT stok
                FROM buku
                WHERE id_buku = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $id_buku);

        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_assoc();

        $stmt->close();

        return $data ? (int) $data['stok'] : 0;
    }


    // =====================================================
    // (ADMIN) TAMPIL SEMUA PEMINJAMAN + PAGINATION
    // =====================================================

    public function tampil_data_peminjaman_page($start, $limit)
    {
        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT
                    peminjaman.*,
                    users.username,
                    users.Email,
                    buku.judul,
                    buku.link_gambar
                FROM peminjaman
                LEFT JOIN users ON peminjaman.id_user = users.id_user
                LEFT JOIN buku  ON peminjaman.id_buku = buku.id_buku
                ORDER BY
                    FIELD(peminjaman.status, 'pengajuan', 'disetujui', 'dipinjam', 'dikembalikan', 'ditolak'),
                    peminjaman.id_peminjaman DESC
                LIMIT $start, $limit";

        $query = mysqli_query($this->conn, $sql);

        $result = [];

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }

        return $result;
    }


    // =====================================================
    // (ADMIN) HITUNG TOTAL PEMINJAMAN
    // =====================================================

    public function hitung_total_peminjaman()
    {
        $sql = "SELECT COUNT(*) AS total FROM peminjaman";

        $query = mysqli_query($this->conn, $sql);

        if (!$query) {
            return 0;
        }

        $data = mysqli_fetch_assoc($query);

        return (int) ($data['total'] ?? 0);
    }


    // =====================================================
    // (ADMIN) PENCARIAN PEMINJAMAN (username, judul, status) + PAGINATION
    // =====================================================

    public function tampil_data_peminjaman_cari_page($keyword, $start, $limit)
    {
        $param_keyword = "%$keyword%";
        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT
                    peminjaman.*,
                    users.username,
                    users.Email,
                    buku.judul,
                    buku.link_gambar
                FROM peminjaman
                LEFT JOIN users ON peminjaman.id_user = users.id_user
                LEFT JOIN buku  ON peminjaman.id_buku = buku.id_buku
                WHERE users.username LIKE ?
                   OR buku.judul LIKE ?
                   OR peminjaman.status LIKE ?
                ORDER BY
                    FIELD(peminjaman.status, 'pengajuan', 'disetujui', 'dipinjam', 'dikembalikan', 'ditolak'),
                    peminjaman.id_peminjaman DESC
                LIMIT ?, ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param(
            "sssii",
            $param_keyword,
            $param_keyword,
            $param_keyword,
            $start,
            $limit
        );

        $stmt->execute();

        $result_set = $stmt->get_result();

        $result = [];

        while ($data = $result_set->fetch_object()) {
            $result[] = $data;
        }

        $stmt->close();

        return $result;
    }


    // =====================================================
    // (ADMIN) HITUNG TOTAL HASIL PENCARIAN PEMINJAMAN
    // =====================================================

    public function hitung_total_peminjaman_cari($keyword)
    {
        $param_keyword = "%$keyword%";

        $sql = "SELECT COUNT(*) AS total
                FROM peminjaman
                LEFT JOIN users ON peminjaman.id_user = users.id_user
                LEFT JOIN buku  ON peminjaman.id_buku = buku.id_buku
                WHERE users.username LIKE ?
                   OR buku.judul LIKE ?
                   OR peminjaman.status LIKE ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param(
            "sss",
            $param_keyword,
            $param_keyword,
            $param_keyword
        );

        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return (int) ($result['total'] ?? 0);
    }


    // =====================================================
    // (ADMIN) AMBIL SATU PEMINJAMAN (untuk validasi sebelum ubah status)
    // =====================================================

    public function tampil_satu_peminjaman($id_peminjaman)
    {
        $sql = "SELECT * FROM peminjaman WHERE id_peminjaman = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_peminjaman);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_object();

        $stmt->close();

        return $data;
    }


    // =====================================================
    // (ADMIN) SETUJUI PEMINJAMAN
    // Hanya bisa dari status 'pengajuan', dan stok buku dikurangi 1
    // =====================================================

    public function setujui_peminjaman($id_peminjaman)
    {
        $peminjaman = $this->tampil_satu_peminjaman($id_peminjaman);

        if (!$peminjaman || $peminjaman->status !== 'pengajuan') {
            return false;
        }

        $stok = $this->cek_stok($peminjaman->id_buku);

        if ($stok <= 0) {
            return false;
        }

        $this->conn->begin_transaction();

        try {
            $sqlUpdateStatus = "UPDATE peminjaman
                                 SET status = 'disetujui'
                                 WHERE id_peminjaman = ? AND status = 'pengajuan'";

            $stmt1 = $this->conn->prepare($sqlUpdateStatus);
            $stmt1->bind_param("i", $id_peminjaman);
            $stmt1->execute();
            $updated = $stmt1->affected_rows > 0;
            $stmt1->close();

            if (!$updated) {
                $this->conn->rollback();
                return false;
            }

            $sqlKurangiStok = "UPDATE buku
                                SET stok = stok - 1
                                WHERE id_buku = ? AND stok > 0";

            $stmt2 = $this->conn->prepare($sqlKurangiStok);
            $stmt2->bind_param("i", $peminjaman->id_buku);
            $stmt2->execute();
            $stmt2->close();

            $this->conn->commit();

            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }


    // =====================================================
    // (ADMIN) TOLAK PEMINJAMAN
    // Hanya bisa dari status 'pengajuan'
    // =====================================================

    public function tolak_peminjaman($id_peminjaman)
    {
        $sql = "UPDATE peminjaman
                SET status = 'ditolak'
                WHERE id_peminjaman = ? AND status = 'pengajuan'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id_peminjaman);
        $stmt->execute();

        $result = $stmt->affected_rows > 0;

        $stmt->close();

        return $result;
    }


    // =====================================================
    // (ADMIN) TANDAI BUKU SUDAH DIAMBIL (DIPINJAM)
    // Hanya bisa dari status 'disetujui'
    // =====================================================

    public function tandai_dipinjam($id_peminjaman)
    {
        $sql = "UPDATE peminjaman
                SET status = 'dipinjam'
                WHERE id_peminjaman = ? AND status = 'disetujui'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id_peminjaman);
        $stmt->execute();

        $result = $stmt->affected_rows > 0;

        $stmt->close();

        return $result;
    }


    // =====================================================
    // (USER) RIWAYAT PEMINJAMAN MILIK SATU USER
    // =====================================================

    public function riwayat_peminjaman_user($id_user)
    {
        $sql = "SELECT
                    peminjaman.*,
                    buku.judul,
                    buku.pengarang,
                    buku.link_gambar,
                    pengembalian.id_pengembalian,
                    pengembalian.tanggal_pengembalian,
                    pengembalian.keterangan AS keterangan_pengembalian
                FROM peminjaman
                LEFT JOIN buku ON peminjaman.id_buku = buku.id_buku
                LEFT JOIN pengembalian ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
                WHERE peminjaman.id_user = ?
                ORDER BY
                    FIELD(peminjaman.status, 'dipinjam', 'disetujui', 'pengajuan', 'dikembalikan', 'ditolak'),
                    peminjaman.id_peminjaman DESC";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $id_user);
        $stmt->execute();

        $result_set = $stmt->get_result();

        $result = [];

        while ($data = $result_set->fetch_object()) {
            $result[] = $data;
        }

        $stmt->close();

        return $result;
    }


    // =====================================================
    // CEK APAKAH PENGEMBALIAN SUDAH PERNAH DIAJUKAN/DIPROSES
    // =====================================================

    public function sudah_diajukan_pengembalian($id_peminjaman)
    {
        $sql = "SELECT id_pengembalian FROM pengembalian WHERE id_peminjaman = ? LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id_peminjaman);
        $stmt->execute();

        $ada = $stmt->get_result()->num_rows > 0;

        $stmt->close();

        return $ada;
    }


    // =====================================================
    // (USER) AJUKAN PENGEMBALIAN
    // Hanya bisa dari status 'dipinjam', milik user ybs,
    // dan belum pernah diajukan sebelumnya
    // =====================================================

    public function ajukan_pengembalian_user($id_peminjaman, $id_user)
    {
        $sql = "SELECT status, id_user FROM peminjaman WHERE id_peminjaman = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id_peminjaman);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_object();

        $stmt->close();

        if (!$data || (int) $data->id_user !== (int) $id_user || $data->status !== 'dipinjam') {
            return false;
        }

        if ($this->sudah_diajukan_pengembalian($id_peminjaman)) {
            return false;
        }

        $sqlInsert = "INSERT INTO pengembalian
                        (id_peminjaman, tanggal_pengembalian, keterangan)
                      VALUES (?, CURDATE(), 'Menunggu konfirmasi admin')";

        $stmt2 = $this->conn->prepare($sqlInsert);

        if (!$stmt2) {
            return false;
        }

        $stmt2->bind_param("i", $id_peminjaman);

        $result = $stmt2->execute();

        $stmt2->close();

        return $result;
    }


    // =====================================================
    // (ADMIN) TAMPIL DATA PENGEMBALIAN (status dipinjam/dikembalikan) + PAGINATION
    // =====================================================

    public function tampil_data_pengembalian_page($start, $limit)
    {
        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT
                    peminjaman.*,
                    users.username,
                    users.Email,
                    buku.judul,
                    buku.link_gambar,
                    pengembalian.id_pengembalian,
                    pengembalian.tanggal_pengembalian,
                    pengembalian.keterangan AS keterangan_pengembalian
                FROM peminjaman
                LEFT JOIN users ON peminjaman.id_user = users.id_user
                LEFT JOIN buku  ON peminjaman.id_buku = buku.id_buku
                LEFT JOIN pengembalian ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
                WHERE peminjaman.status IN ('dipinjam', 'dikembalikan')
                ORDER BY
                    FIELD(peminjaman.status, 'dipinjam', 'dikembalikan'),
                    (pengembalian.id_pengembalian IS NULL) DESC,
                    peminjaman.id_peminjaman DESC
                LIMIT $start, $limit";

        $query = mysqli_query($this->conn, $sql);

        $result = [];

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }

        return $result;
    }


    // =====================================================
    // (ADMIN) HITUNG TOTAL DATA PENGEMBALIAN
    // =====================================================

    public function hitung_total_pengembalian()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM peminjaman
                WHERE status IN ('dipinjam', 'dikembalikan')";

        $query = mysqli_query($this->conn, $sql);

        $data = mysqli_fetch_assoc($query);

        return (int) ($data['total'] ?? 0);
    }


    // =====================================================
    // (ADMIN) PENCARIAN DATA PENGEMBALIAN (username, judul) + PAGINATION
    // =====================================================

    public function tampil_data_pengembalian_cari_page($keyword, $start, $limit)
    {
        $param_keyword = "%$keyword%";
        $start = (int) $start;
        $limit = (int) $limit;

        $sql = "SELECT
                    peminjaman.*,
                    users.username,
                    users.Email,
                    buku.judul,
                    buku.link_gambar,
                    pengembalian.id_pengembalian,
                    pengembalian.tanggal_pengembalian,
                    pengembalian.keterangan AS keterangan_pengembalian
                FROM peminjaman
                LEFT JOIN users ON peminjaman.id_user = users.id_user
                LEFT JOIN buku  ON peminjaman.id_buku = buku.id_buku
                LEFT JOIN pengembalian ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
                WHERE peminjaman.status IN ('dipinjam', 'dikembalikan')
                  AND (users.username LIKE ? OR buku.judul LIKE ?)
                ORDER BY
                    FIELD(peminjaman.status, 'dipinjam', 'dikembalikan'),
                    (pengembalian.id_pengembalian IS NULL) DESC,
                    peminjaman.id_peminjaman DESC
                LIMIT ?, ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param(
            "ssii",
            $param_keyword,
            $param_keyword,
            $start,
            $limit
        );

        $stmt->execute();

        $result_set = $stmt->get_result();

        $result = [];

        while ($data = $result_set->fetch_object()) {
            $result[] = $data;
        }

        $stmt->close();

        return $result;
    }


    // =====================================================
    // (ADMIN) HITUNG TOTAL HASIL PENCARIAN PENGEMBALIAN
    // =====================================================

    public function hitung_total_pengembalian_cari($keyword)
    {
        $param_keyword = "%$keyword%";

        $sql = "SELECT COUNT(*) AS total
                FROM peminjaman
                LEFT JOIN users ON peminjaman.id_user = users.id_user
                LEFT JOIN buku  ON peminjaman.id_buku = buku.id_buku
                WHERE peminjaman.status IN ('dipinjam', 'dikembalikan')
                  AND (users.username LIKE ? OR buku.judul LIKE ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("ss", $param_keyword, $param_keyword);

        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return (int) ($result['total'] ?? 0);
    }


    // =====================================================
    // (ADMIN) KONFIRMASI / PROSES PENGEMBALIAN
    // Hanya bisa dari status 'dipinjam'.
    // Menambah baris ke tabel pengembalian (atau memperbarui
    // jika user sudah mengajukan lebih dulu) & menambah stok buku +1
    // =====================================================

    public function konfirmasi_pengembalian($id_peminjaman, $keterangan = null)
    {
        $peminjamanData = $this->tampil_satu_peminjaman($id_peminjaman);

        if (!$peminjamanData || $peminjamanData->status !== 'dipinjam') {
            return false;
        }

        // Admin hanya boleh mengonfirmasi jika user sudah mengajukan
        // pengembalian terlebih dahulu. Selama buku masih dalam status
        // dipinjam & belum diajukan, tidak ada proses pengembalian.
        if (!$this->sudah_diajukan_pengembalian($id_peminjaman)) {
            return false;
        }

        $keterangan = ($keterangan !== null && trim($keterangan) !== '')
            ? trim($keterangan)
            : 'Dikonfirmasi oleh admin';

        $this->conn->begin_transaction();

        try {
            if ($this->sudah_diajukan_pengembalian($id_peminjaman)) {

                $sqlUpdatePengembalian = "UPDATE pengembalian
                                           SET tanggal_pengembalian = CURDATE(),
                                               keterangan = ?
                                           WHERE id_peminjaman = ?";

                $stmt1 = $this->conn->prepare($sqlUpdatePengembalian);
                $stmt1->bind_param("si", $keterangan, $id_peminjaman);
                $stmt1->execute();
                $stmt1->close();

            } else {

                $sqlInsertPengembalian = "INSERT INTO pengembalian
                                            (id_peminjaman, tanggal_pengembalian, keterangan)
                                           VALUES (?, CURDATE(), ?)";

                $stmt1 = $this->conn->prepare($sqlInsertPengembalian);
                $stmt1->bind_param("is", $id_peminjaman, $keterangan);
                $stmt1->execute();
                $stmt1->close();
            }

            $sqlUpdateStatus = "UPDATE peminjaman
                                 SET status = 'dikembalikan'
                                 WHERE id_peminjaman = ? AND status = 'dipinjam'";

            $stmt2 = $this->conn->prepare($sqlUpdateStatus);
            $stmt2->bind_param("i", $id_peminjaman);
            $stmt2->execute();
            $updated = $stmt2->affected_rows > 0;
            $stmt2->close();

            if (!$updated) {
                $this->conn->rollback();
                return false;
            }

            $sqlTambahStok = "UPDATE buku SET stok = stok + 1 WHERE id_buku = ?";

            $stmt3 = $this->conn->prepare($sqlTambahStok);
            $stmt3->bind_param("i", $peminjamanData->id_buku);
            $stmt3->execute();
            $stmt3->close();

            $this->conn->commit();

            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }


    // =====================================================
    // (ADMIN/DASHBOARD) HITUNG: MENUNGGU KONFIRMASI PENGEMBALIAN
    // (status 'dipinjam' & sudah diajukan pengembalian oleh siswa)
    // =====================================================

    public function hitung_menunggu_konfirmasi()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM peminjaman
                INNER JOIN pengembalian
                    ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
                WHERE peminjaman.status = 'dipinjam'";

        $query = mysqli_query($this->conn, $sql);
        $data  = mysqli_fetch_assoc($query);

        return (int) ($data['total'] ?? 0);
    }


    // =====================================================
    // (ADMIN/DASHBOARD) HITUNG: SEDANG DIPINJAM, BELUM DIAJUKAN KEMBALI
    // =====================================================

    public function hitung_sedang_dipinjam_tanpa_pengajuan()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM peminjaman
                LEFT JOIN pengembalian
                    ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
                WHERE peminjaman.status = 'dipinjam'
                  AND pengembalian.id_pengembalian IS NULL";

        $query = mysqli_query($this->conn, $sql);
        $data  = mysqli_fetch_assoc($query);

        return (int) ($data['total'] ?? 0);
    }


    // =====================================================
    // (ADMIN/DASHBOARD) HITUNG: TOTAL SUDAH DIKEMBALIKAN
    // =====================================================

    public function hitung_total_dikembalikan()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM peminjaman
                WHERE status = 'dikembalikan'";

        $query = mysqli_query($this->conn, $sql);
        $data  = mysqli_fetch_assoc($query);

        return (int) ($data['total'] ?? 0);
    }


    // =====================================================
    // (ADMIN/DASHBOARD) DAFTAR RINGKAS YANG MENUNGGU KONFIRMASI
    // Dipakai untuk widget di halaman dashboard_admin.php
    // =====================================================

    public function riwayat_menunggu_konfirmasi($limit = 5)
    {
        $limit = (int) $limit;

        $sql = "SELECT
                    peminjaman.id_peminjaman,
                    peminjaman.tanggal_pinjam,
                    peminjaman.tanggal_kembali,
                    users.username,
                    buku.judul,
                    buku.link_gambar,
                    pengembalian.tanggal_pengembalian
                FROM peminjaman
                INNER JOIN pengembalian
                    ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
                LEFT JOIN users ON peminjaman.id_user = users.id_user
                LEFT JOIN buku  ON peminjaman.id_buku = buku.id_buku
                WHERE peminjaman.status = 'dipinjam'
                ORDER BY pengembalian.tanggal_pengembalian ASC
                LIMIT $limit";

        $query = mysqli_query($this->conn, $sql);

        $result = [];

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }

        return $result;
    }
}
?>