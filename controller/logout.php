<?php

// =====================================================
// PROSES LOGOUT (dipakai admin & user)
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kosongkan semua data session
$_SESSION = [];

// Hapus cookie session di browser
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $p['path'],
        $p['domain'],
        $p['secure'],
        $p['httponly']
    );
}

// Hancurkan session di server
session_destroy();

// Kembali ke halaman login
header('Location: ../index.php');
exit;
