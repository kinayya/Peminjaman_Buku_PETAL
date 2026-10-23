<?php

include_once __DIR__ . '/koneksi.php';

// =====================================================
// MODEL PROFIL USER (siswa)
// =====================================================

class m_profil
{
    private $conn;

    public function __construct()
    {
        $db = new koneksi();
        $this->conn = $db->koneksi;
    }


    // =====================================================
    // AMBIL DATA PROFIL (tanpa password)
    // =====================================================

    public function ambil($id_user)
    {
        $id_user = (int) $id_user;

        $sql = "SELECT id_user, Email, username, role
                FROM users
                WHERE id_user = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_user);
        $stmt->execute();

        $query = $stmt->get_result();
        $data  = $query ? $query->fetch_assoc() : null;

        $stmt->close();

        return $data ?: null;
    }


    // =====================================================
    // AMBIL HASH PASSWORD (untuk verifikasi password lama)
    // =====================================================

    public function ambil_hash_password($id_user)
    {
        $id_user = (int) $id_user;

        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id_user = ?");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_user);
        $stmt->execute();

        $query = $stmt->get_result();
        $data  = $query ? $query->fetch_assoc() : null;

        $stmt->close();

        return $data['password'] ?? null;
    }


    // =====================================================
    // CEK USERNAME / EMAIL SUDAH DIPAKAI USER LAIN
    // (login mencocokkan username ATAU email, jadi keduanya dicek)
    // =====================================================

    public function sudah_dipakai($nilai, $id_user_sendiri)
    {
        $id_user_sendiri = (int) $id_user_sendiri;

        $sql = "SELECT id_user
                FROM users
                WHERE (username = ? OR Email = ?)
                  AND id_user <> ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return true;
        }

        $stmt->bind_param("ssi", $nilai, $nilai, $id_user_sendiri);
        $stmt->execute();

        $query = $stmt->get_result();
        $ada   = $query && $query->num_rows > 0;

        $stmt->close();

        return $ada;
    }


    // =====================================================
    // UBAH USERNAME & EMAIL (role tidak bisa diubah dari sini)
    // =====================================================

    public function ubah_profil($id_user, $username, $email)
    {
        $id_user = (int) $id_user;

        $sql = "UPDATE users
                SET username = ?, Email = ?
                WHERE id_user = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssi", $username, $email, $id_user);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // UBAH PASSWORD (parameter sudah berupa hash)
    // =====================================================

    public function ubah_password($id_user, $password_hash)
    {
        $id_user = (int) $id_user;

        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id_user = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("si", $password_hash, $id_user);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // STATISTIK PEMINJAMAN MILIK SATU USER
    // =====================================================

    public function statistik_peminjaman($id_user)
    {
        $id_user = (int) $id_user;

        $stat = [
            'total'        => 0,
            'menunggu'     => 0, // pengajuan + disetujui
            'dipinjam'     => 0,
            'dikembalikan' => 0,
        ];

        $stmt = $this->conn->prepare(
            "SELECT status, COUNT(*) AS total
             FROM peminjaman
             WHERE id_user = ?
             GROUP BY status"
        );

        if (!$stmt) {
            return $stat;
        }

        $stmt->bind_param("i", $id_user);
        $stmt->execute();

        $query = $stmt->get_result();

        while ($row = $query->fetch_assoc()) {

            $jumlah = (int) $row['total'];

            $stat['total'] += $jumlah;

            if ($row['status'] === 'pengajuan' || $row['status'] === 'disetujui') {
                $stat['menunggu'] += $jumlah;
            } elseif ($row['status'] === 'dipinjam') {
                $stat['dipinjam'] += $jumlah;
            } elseif ($row['status'] === 'dikembalikan') {
                $stat['dikembalikan'] += $jumlah;
            }
        }

        $stmt->close();

        return $stat;
    }
}

?>
