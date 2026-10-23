<!-- proses pendaftaran pengguna baru -->
<?php
include_once '../models/m_user.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = new m_user();

    $input_username = trim($_POST['username']);
    $input_email    = trim($_POST['email']);
    $input_password = $_POST['password'];
    $input_role     = trim($_POST['role'] ?? '');

    // Validasi sederhana: field wajib diisi
    if (empty($input_username) || empty($input_email) || empty($input_password) || empty($input_role)) {
        echo "<script>alert('Semua field wajib diisi.'); window.location='../views/register.php'</script>";
        exit;
    }

    // Whitelist role yang diperbolehkan, supaya tidak bisa dimanipulasi jadi nilai sembarang
    $allowed_roles = ['admin', 'siswa', 'staff', 'guru'];
    if (!in_array($input_role, $allowed_roles, true)) {
        echo "<script>alert('Role tidak valid.'); window.location='../views/register.php'</script>";
        exit;
    }

    // Cek apakah username/email sudah dipakai
    $cek = $user->login($input_username); // fungsi login() mencari by username ATAU email
    if (!$cek) {
        $cek = $user->login($input_email);
    }

    if ($cek) {
        echo "<script>alert('Username atau Email sudah terdaftar.'); window.location='../views/register.php'</script>";
        exit;
    }

    // Hash password sebelum disimpan (WAJIB, karena login pakai password_verify)
    $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);

    $result = $user->tambah_data($input_username, $input_email, $hashed_password, $input_role);

    if ($result) {
        echo "<script>alert('Registrasi berhasil, silakan login.'); window.location='../index.php'</script>";
        exit;
    } else {
        echo "<script>alert('Registrasi gagal, silahkan coba lagi.'); window.location='../views/register.php'</script>";
        exit;
    }

} else {
    header("location: ../views/register.php");
    exit;
}
?>
