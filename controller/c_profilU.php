<?php

// =====================================================
// CONTROLLER HALAMAN PROFIL USER
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Supaya tombol "Back" setelah logout tidak menampilkan halaman dari cache
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

include_once __DIR__ . '/../models/m_profil.php';


// =====================================================
// PROTEKSI HALAMAN: HARUS LOGIN
// =====================================================

if (
    !isset($_SESSION['data']) ||
    !isset($_SESSION['data']['id_user'])
) {
    echo "<script>
            alert('Silakan login terlebih dahulu.');
            window.location='../index.php';
          </script>";
    exit;
}

$id_user = (int) $_SESSION['data']['id_user'];

$profil = new m_profil();

$data_profil = $profil->ambil($id_user);

// Akun sudah tidak ada di database -> paksa keluar
if (!$data_profil) {
    header('Location: ../controller/logout.php');
    exit;
}


// =====================================================
// PESAN (flash dari proses sebelumnya / error form ini)
// =====================================================

$pesan_profil   = null;
$pesan_password = null;
$scroll_ke      = '';

if (isset($_SESSION['flash_profil'])) {

    $flash = $_SESSION['flash_profil'];
    unset($_SESSION['flash_profil']);

    if ($flash['target'] === 'password') {
        $pesan_password = $flash;
    } else {
        $pesan_profil = $flash;
    }
}

$input_username = $data_profil['username'] ?? '';
$input_email    = $data_profil['Email'] ?? '';


// =====================================================
// PROSES FORM
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $aksi = $_POST['aksi'] ?? '';


    // ---------- UBAH USERNAME & EMAIL ----------

    if ($aksi === 'ubah_profil') {

        $input_username = trim($_POST['username'] ?? '');
        $input_email    = trim($_POST['email'] ?? '');

        $error = '';

        if ($input_username === '' || $input_email === '') {
            $error = 'Username dan email wajib diisi.';
        } elseif (strlen($input_username) < 3 || strlen($input_username) > 50) {
            $error = 'Username harus 3 sampai 50 karakter.';
        } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL) || strlen($input_email) > 159) {
            $error = 'Format email tidak valid.';
        } elseif (
            $profil->sudah_dipakai($input_username, $id_user) ||
            $profil->sudah_dipakai($input_email, $id_user)
        ) {
            $error = 'Username atau email sudah dipakai user lain.';
        }

        if ($error === '') {

            if ($profil->ubah_profil($id_user, $input_username, $input_email)) {

                $_SESSION['data']['username'] = $input_username;
                $_SESSION['data']['Email']    = $input_email;

                $_SESSION['flash_profil'] = [
                    'target' => 'profil',
                    'tipe'   => 'sukses',
                    'pesan'  => 'Profil berhasil diperbarui.',
                ];

                header('Location: profilU.php#panel-profil');
                exit;
            }

            $error = 'Gagal memperbarui profil, silakan coba lagi.';
        }

        $pesan_profil = ['tipe' => 'error', 'pesan' => $error];
        $scroll_ke    = 'panel-profil';
    }


    // ---------- UBAH PASSWORD ----------

    if ($aksi === 'ubah_password') {

        $password_lama  = $_POST['password_lama'] ?? '';
        $password_baru  = $_POST['password_baru'] ?? '';
        $password_ulang = $_POST['password_ulang'] ?? '';

        $error = '';

        $hash_lama = $profil->ambil_hash_password($id_user);

        if ($password_lama === '' || $password_baru === '' || $password_ulang === '') {
            $error = 'Semua kolom password wajib diisi.';
        } elseif (!$hash_lama || !password_verify($password_lama, $hash_lama)) {
            $error = 'Password lama salah.';
        } elseif (strlen($password_baru) < 6) {
            $error = 'Password baru minimal 6 karakter.';
        } elseif ($password_baru !== $password_ulang) {
            $error = 'Konfirmasi password baru tidak sama.';
        }

        if ($error === '') {

            $hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

            if ($profil->ubah_password($id_user, $hash_baru)) {

                $_SESSION['data']['password'] = $hash_baru;

                $_SESSION['flash_profil'] = [
                    'target' => 'password',
                    'tipe'   => 'sukses',
                    'pesan'  => 'Password berhasil diperbarui.',
                ];

                header('Location: profilU.php#panel-password');
                exit;
            }

            $error = 'Gagal mengubah password, silakan coba lagi.';
        }

        $pesan_password = ['tipe' => 'error', 'pesan' => $error];
        $scroll_ke      = 'panel-password';
    }
}


// =====================================================
// DATA UNTUK TAMPILAN
// =====================================================

$stat = $profil->statistik_peminjaman($id_user);

$huruf_pertama = preg_match('/^./us', $data_profil['username'] ?? '', $cocok) ? $cocok[0] : '?';

$inisial = strtoupper($huruf_pertama);

$label_role = [
    'admin' => 'Admin',
    'siswa' => 'Siswa',
    'staff' => 'Staff',
    'guru' => 'Guru'
][$data_profil['role']] ?? ucfirst($data_profil['role']);
