<?php

// =====================================================
// PENJAGA AKSES (dipakai semua controller & halaman)
//
//   auth_wajib_admin()          -> hanya admin
//   auth_wajib_user()           -> hanya user (siswa/guru/staff)
//   auth_wajib_user(true)       -> user, tamu (belum login) juga boleh
//
// Aturan:
//   - Belum login                   -> diarahkan ke halaman login.
//   - User membuka halaman admin    -> diam-diam kembali ke dashboard user
//                                      (tanpa pesan, tanpa ke login).
//   - Admin membuka halaman user    -> kembali ke dashboard admin.
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const AUTH_ROLE_USER = ['siswa', 'guru', 'staff'];


// Peran sesi saat ini: 'admin', 'user', atau 'tamu'
function auth_peran()
{
    $role = $_SESSION['data']['role'] ?? '';

    if ($role === 'admin') {
        return 'admin';
    }

    if (
        isset($_SESSION['data']['id_user'])
        && in_array($role, AUTH_ROLE_USER, true)
    ) {
        return 'user';
    }

    return 'tamu';
}


// Supaya tombol "Back" tidak menampilkan halaman dari cache
function auth_tanpa_cache()
{
    if (!headers_sent()) {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
    }
}


function auth_arahkan($tujuan)
{
    header('Location: ' . $tujuan);
    exit;
}


function auth_ke_login($pesan)
{
    echo "<script>
            alert('" . $pesan . "');
            window.location='../index.php';
          </script>";
    exit;
}


function auth_wajib_admin()
{
    auth_tanpa_cache();

    switch (auth_peran()) {
        case 'admin':
            return;

        case 'user':
            // Tetap di area user, tidak perlu login ulang
            auth_arahkan('../views/dashboard_user.php');

        default:
            auth_ke_login('Silakan login sebagai admin terlebih dahulu.');
    }
}


function auth_wajib_user($boleh_tamu = false)
{
    auth_tanpa_cache();

    switch (auth_peran()) {
        case 'user':
            return;

        case 'admin':
            auth_arahkan('../views/dashboard_admin.php');

        default:
            if ($boleh_tamu) {
                return;
            }

            auth_ke_login('Silakan login terlebih dahulu.');
    }
}
