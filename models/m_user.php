<?php

include_once __DIR__ . '/koneksi.php';

class m_user
{
    private $conn;

    public function __construct()
    {
        $db = new koneksi();
        $this->conn = $db->koneksi;
    }


    // =====================================================
    // LOGIN
    // =====================================================

    public function login($username)
    {
        $sql = "SELECT *
                FROM users
                WHERE username = ?
                   OR Email = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param(
            "ss",
            $username,
            $username
        );

        $stmt->execute();

        $query = $stmt->get_result();

        $data = null;

        if ($query && $query->num_rows > 0) {
            $data = $query->fetch_assoc();
        }

        $stmt->close();

        return $data;
    }


    // =====================================================
    // TAMPIL SEMUA USER
    // =====================================================

    public function tampil_data()
{
    $sql = "SELECT id_user, Email, username, password, role
            FROM users
            ORDER BY id_user DESC";

    $query = mysqli_query($this->conn, $sql);

    $result = [];

    if ($query && mysqli_num_rows($query) > 0) {

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }
    }

    return $result;
}


    // =====================================================
    // TAMPIL USER + PAGINATION
    // =====================================================

    public function tampil_data_page($start, $limit)
{
    $start = (int) $start;
    $limit = (int) $limit;

    $sql = "SELECT id_user, Email, username, password, role
            FROM users
            ORDER BY id_user DESC
            LIMIT $start, $limit";

    $query = mysqli_query($this->conn, $sql);

    $result = [];

    if ($query) {

        while ($data = mysqli_fetch_object($query)) {
            $result[] = $data;
        }
    }

    return $result;
}
    // =====================================================
    // TAMBAH USER
    // =====================================================

    public function tambah_data(
        $username,
        $Email,
        $password,
        $role
    )
    {
        $sql = "INSERT INTO users
                (username, Email, password, role)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssss",
            $username,
            $Email,
            $password,
            $role
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // AMBIL SATU USER
    // =====================================================

    public function tampil_satu(
        $id_user
    )
    {
        $id_user = (int) $id_user;

        $sql = "SELECT id_user, Email, username, role
                FROM users
                WHERE id_user = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param(
            "i",
            $id_user
        );

        $stmt->execute();

        $query = $stmt->get_result();

        $data = null;

        if ($query) {
            $data = $query->fetch_object();
        }

        $stmt->close();

        return $data;
    }


    // =====================================================
    // EDIT USER
    // =====================================================

    public function edit(
        $id_user,
        $username,
        $Email,
        $role
    )
    {
        $id_user = (int) $id_user;

        $sql = "UPDATE users
                SET username = ?,
                    Email = ?,
                    role = ?
                WHERE id_user = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sssi",
            $username,
            $Email,
            $role,
            $id_user
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // EDIT PASSWORD
    // =====================================================

    public function edit_password(
        $id_user,
        $password
    )
    {
        $id_user = (int) $id_user;

        $sql = "UPDATE users
                SET password = ?
                WHERE id_user = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "si",
            $password,
            $id_user
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // HAPUS USER
    // =====================================================

    public function hapus_data(
        $id_user
    )
    {
        $id_user = (int) $id_user;

        $sql = "DELETE FROM users
                WHERE id_user = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "i",
            $id_user
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }


    // =====================================================
    // HITUNG TOTAL USER
    // =====================================================

    public function hitung_total()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM users";

        $query = mysqli_query(
            $this->conn,
            $sql
        );

        if (!$query) {
            return 0;
        }

        $data = mysqli_fetch_assoc($query);

        return (int) (
            $data['total'] ?? 0
        );
    }


    // =====================================================
    // SEARCH USER
    // =====================================================

    public function tampil_data_cari_page(
    $keyword,
    $start,
    $limit
)
{
    $keyword = "%{$keyword}%";

    $start = (int) $start;
    $limit = (int) $limit;

    $sql = "SELECT id_user, Email, username, password, role
            FROM users
            WHERE username LIKE ?
               OR Email LIKE ?
               OR role LIKE ?
            ORDER BY id_user DESC
            LIMIT ?, ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param(
        "sssii",
        $keyword,
        $keyword,
        $keyword,
        $start,
        $limit
    );

    $stmt->execute();

    $query = $stmt->get_result();

    $result = [];

    while ($data = $query->fetch_object()) {
        $result[] = $data;
    }

    $stmt->close();

    return $result;
}


    // =====================================================
    // HITUNG HASIL SEARCH
    // =====================================================

    public function hitung_total_cari(
        $keyword
    )
    {
        $keyword = "%{$keyword}%";

        $sql = "SELECT COUNT(*) AS total
                FROM users
                WHERE username LIKE ?
                   OR Email LIKE ?
                   OR role LIKE ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param(
            "sss",
            $keyword,
            $keyword,
            $keyword
        );

        $stmt->execute();

        $query = $stmt->get_result();

        $data = $query->fetch_assoc();

        $stmt->close();

        return (int) (
            $data['total'] ?? 0
        );
    }
}

?>