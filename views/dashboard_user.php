<?php
session_start();

// Supaya tombol "Back" setelah logout tidak menampilkan dashboard dari cache
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Proteksi halaman: hanya pengguna yang sudah login dan memiliki role pengguna
if (
    !isset($_SESSION['data']['id_user']) ||
    !in_array(($_SESSION['data']['role'] ?? ''), ['siswa', 'staff', 'guru'], true)
) {
    echo "<script>
            alert('Silakan login terlebih dahulu.');
            window.location='../index.php';
          </script>";
    exit;
}

// Ambil daftar Most Popular berdasarkan transaksi peminjaman pada database.
require_once __DIR__ . '/../models/m_dashboard.php';
$model_dashboard_user = new m_dashboard();
$buku_populer = $model_dashboard_user->buku_populer_user(5);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progresif Library</title>
    <!-- Pastikan file CSS berada di folder yang sama atau sesuaikan jalurnya -->
    <link rel="stylesheet" href="../css/dbuser.css">
</head>

<body>

    <!-- ================= HERO ================= -->
    <header class="hero">

        <!-- LOGO -->
        <div class="logo">
            <img src="../assets/logo.svg" alt="Progresif Library">
        </div>

        <!-- HERO TEXT -->
        <div class="hero-text">
            <span>WELCOME TO</span>
            <h1>PETAL</h1>
            <p>Bawa pulang imajinasi tanpa batas dengan membaca di perpustakaan.</p>
        </div>

        <img src="../assets/bukusihir.svg" class="hero-book" alt="Buku Progresif Library">

    </header>


    <!-- ================= NAVBAR ================= -->
    <nav class="navbar">

    <!-- PROFIL -->
        <div class="navbar-menu">
            <a href="profilU.php">
                <img src="../assets/user.png" alt="Profil">
            </a>
            <p>Profil</p>
        </div>

        <!-- DAFTAR BUKU -->
        <div class="navbar-menu">
            <a href="daftar_bukuU.php">
                <img src="../assets/books.png" alt="Daftar Buku">
            </a>
            <p>Daftar Buku</p>
        </div>

        <!-- PEMINJAMAN -->
        <div class="navbar-menu">
            <a href="riwayat_peminjamanU.php">
                <img src="../assets/history.png" alt="Peminjaman">
            </a>
            <p>Riwayat Peminjaman</p>
        </div>
        
        <!-- LOGOUT -->
        <div class="navbar-menu">
            <a href="../controller/logout.php" onclick="return confirm('Yakin ingin logout?');">
                <img src="../assets/logout.png" alt="Logout">
            </a>
            <p>Logout</p>
        </div>

    </nav>


    <!-- ================= CONTENT ================= -->
    <main class="content">

        <!-- ================= QUOTE & OFFER SECTION ================= -->
        <section class="quote-container">

            <!-- QUOTE CARD 1 (Featured Quote / Dark Theme) -->
            <div class="quote-card featured-quote">

                <!-- Gambar Cover sebagai Background -->
                <img src="https://cdn.gramedia.com/uploads/items/9786020332956_Bumi-New-Cover.jpg" class="card-bg-image" alt="Cover Buku Bumi">

                <div class="quote-content">
                    <span>Tere Liye</span>
                    <blockquote>
                        "Apa pun yang hilang, tidak selalu lenyap seperti yang kita duga. Ada banyak sekali jawaban dari tempat-tempat yang hilang."
                    </blockquote>
                    <cite>- Bumi</cite>
                </div>

            </div>

            <!-- QUOTE CARD 2 (Special Offer / Cream Theme) -->
            <div class="quote-card special-offer">

                <!-- Gambar Cover sebagai Background -->
                <img src="https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1489732961i/1362193.jpg" class="card-bg-image" alt="Special Offer Cover">

                <div class="quote-content">
                    <span>Andrea Hirata</span>
                    <blockquote>
                       “Hiduplah Untuk Memberi yang Sebanyak-banyaknya, Bukan untuk Menerima yang Sebanyak-banyaknya. (Pak Harfan)”
                    </blockquote>
                    <cite>- Laskar Pelangi</cite>
                </div>

            </div>

        </section>


        <!-- ================= MOST POPULAR ================= -->
        <section class="popular-section">

            <div class="section-header">
                <h2>Most Popular</h2>
            </div>

            <div class="popular-books">
                <?php if (!empty($buku_populer)): ?>
                    <?php $nomor_populer = 1; foreach ($buku_populer as $buku): ?>
                        <div class="book-card">
                            <div class="book-cover">
                                <span class="popular-label">#<?= $nomor_populer++ ?> Popular</span>
                                <?php $cover = trim((string) ($buku['link_gambar'] ?? '')); ?>
                                <img
                                    src="<?= htmlspecialchars($cover !== '' ? $cover : '../assets/bukusihir.svg', ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Cover <?= htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8') ?>"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='../assets/bukusihir.svg';"
                                >
                            </div>
                            <div class="book-info">
                                <h3><?= htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p><?= htmlspecialchars($buku['pengarang'], ENT_QUOTES, 'UTF-8') ?></p>
                                <span class="borrow-count">
                                    <?= (int) $buku['total_dipinjam'] ?> kali dipinjam
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="popular-empty">
                        <p>Belum ada data peminjaman buku.</p>
                        <span>Buku populer akan muncul setelah ada peminjaman yang disetujui.</span>
                    </div>
                <?php endif; ?>
            </div>

        </section>

    </main>

</body>

</html>