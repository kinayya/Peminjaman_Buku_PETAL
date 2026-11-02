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
//   - User membuka halaman admin    -> diam-diam kembali ke halaman user
//                                      yang terakhir dibuka (tanpa pesan,
//                                      tanpa ke login).
//   - Admin membuka halaman user    -> kembali ke halaman admin yang
//                                      terakhir dibuka.
//   - Jika belum ada halaman terakhir (mis. baru login lalu langsung ganti
//     URL), barulah diarahkan ke dashboard masing-masing.
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


// Parameter URL yang aman disimpan untuk "halaman terakhir".
// Sengaja whitelist, supaya parameter aksi (hapus, konfirmasi, dll.)
// tidak ikut tersimpan dan terjalankan ulang saat redirect.
const AUTH_QUERY_AMAN = ['keyword', 'kategori', 'halaman', 'page', 'id_buku'];


// Catat halaman (di folder views) yang sedang dibuka dengan akses sah
function auth_catat_halaman($peran)
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
        return;
    }

    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
        return;
    }

    $skrip = $_SERVER['SCRIPT_NAME'] ?? '';

    // Hanya halaman tampilan, bukan endpoint controller
    if (basename(dirname($skrip)) !== 'views') {
        return;
    }

    $query = array_filter(
        array_intersect_key($_GET, array_flip(AUTH_QUERY_AMAN)),
        'is_scalar'
    );

    $_SESSION['halaman_terakhir'] = [
        'peran' => $peran,
        'id'    => $_SESSION['data']['id_user'] ?? null,
        'url'   => basename($skrip) . ($query ? '?' . http_build_query($query) : ''),
    ];
}


// Kembalikan ke halaman terakhir (kalau ada & masih sesuai peran),
// kalau tidak ada baru ke dashboard
function auth_kembali($peran, $dashboard)
{
    $h = $_SESSION['halaman_terakhir'] ?? null;

    if (
        is_array($h)
        && ($h['peran'] ?? '') === $peran
        && ($h['id'] ?? null) === ($_SESSION['data']['id_user'] ?? null)
        && preg_match('/^[A-Za-z0-9_]+\.php(\?[A-Za-z0-9_=&%.+\-]*)?$/', $h['url'] ?? '')
    ) {
        auth_arahkan('../views/' . $h['url']);
    }

    auth_arahkan('../views/' . $dashboard);
}


function auth_wajib_admin()
{
    auth_tanpa_cache();

    switch (auth_peran()) {
        case 'admin':
            auth_catat_halaman('admin');
            return;

        case 'user':
            // Tetap di halaman user yang sedang dibuka
            auth_kembali('user', 'dashboard_user.php');

        default:
            auth_ke_login('Silakan login sebagai admin terlebih dahulu.');
    }
}


function auth_wajib_user($boleh_tamu = false)
{
    auth_tanpa_cache();

    switch (auth_peran()) {
        case 'user':
            auth_catat_halaman('user');
            return;

        case 'admin':
            // Tetap di halaman admin yang sedang dibuka
            auth_kembali('admin', 'dashboard_admin.php');

        default:
            if ($boleh_tamu) {
                return;
            }

            auth_ke_login('Silakan login terlebih dahulu.');
    }
}
