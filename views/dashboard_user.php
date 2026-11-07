<?php
require_once __DIR__ . '/../controller/auth.php';
auth_wajib_user();

// Ambil daftar Most Popular berdasarkan transaksi peminjaman pada database.
require_once __DIR__ . '/../models/m_dashboard.php';
$model_dashboard_user = new m_dashboard();
$buku_populer = $model_dashboard_user->buku_populer_user(5);

// Info buku untuk popup kartu quote (kalau bukunya tidak ada di database,
// kartu tetap tampil seperti biasa tapi tidak bisa diklik).
$buku_quote_1 = $model_dashboard_user->detail_buku_by_judul('Bumi');
$buku_quote_2 = $model_dashboard_user->detail_buku_by_judul('Laskar Pelangi');

// Atribut HTML agar kartu quote bisa diklik + membawa data bukunya
function atribut_kartu_buku($buku)
{
    if (!$buku) {
        return '';
    }

    $json = json_encode($buku, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);

    return ' role="button" tabindex="0" aria-haspopup="dialog"'
         . ' aria-label="Lihat info buku ' . htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8') . '"'
         . ' data-buku="' . htmlspecialchars($json, ENT_QUOTES, 'UTF-8') . '"';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progresif Library</title>
    <!-- Pastikan file CSS berada di folder yang sama atau sesuaikan jalurnya -->
    <link rel="stylesheet" href="../css/dbuser.css?v=<?= filemtime(__DIR__ . '/../css/dbuser.css') ?>">
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
            <div class="quote-card featured-quote<?= $buku_quote_1 ? ' dapat-diklik' : '' ?>"<?= atribut_kartu_buku($buku_quote_1) ?>>

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
            <div class="quote-card special-offer<?= $buku_quote_2 ? ' dapat-diklik' : '' ?>"<?= atribut_kartu_buku($buku_quote_2) ?>>

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

    <!-- ================= POPUP INFO BUKU (tampilan sama dengan kartu quote) ================= -->
    <div class="quote-modal-overlay" id="modalBuku" aria-hidden="true">

        <div class="quote-modal featured-quote" role="dialog" aria-modal="true" aria-labelledby="qmJudul">

            <img class="card-bg-image" id="qmBg" src="" alt="">

            <button type="button" class="quote-modal-close" id="qmTutup" aria-label="Tutup">&times;</button>

            <div class="quote-content qm-scroll">

                <div class="qm-top">
                    <img class="qm-cover" id="qmCover" src="" alt="Cover buku">

                    <div class="qm-head">
                        <span id="qmPengarang"></span>
                        <h3 class="qm-judul" id="qmJudul"></h3>

                        <dl class="qm-meta">
                            <div><dt>Penerbit</dt><dd id="qmPenerbit"></dd></div>
                            <div><dt>Tahun Terbit</dt><dd id="qmTahun"></dd></div>
                            <div><dt>Kategori</dt><dd id="qmKategori"></dd></div>
                            <div><dt>Stok</dt><dd id="qmStok"></dd></div>
                        </dl>
                    </div>
                </div>

                <div class="qm-sinopsis-wrap">
                    <dl class="qm-meta">
                        <div><dt>Sinopsis</dt></div>
                    </dl>
                    <p class="qm-sinopsis" id="qmSinopsis"></p>
                </div>

            </div>

        </div>

    </div>

    <script>
        (function () {
            var overlay = document.getElementById('modalBuku');
            var box     = overlay.querySelector('.quote-modal');
            var fallback = '../assets/bukusihir.svg';
            var terakhir = null;

            function isi(id, teks) {
                document.getElementById(id).textContent = teks;
            }

            function buka(kartu) {
                var data;
                try { data = JSON.parse(kartu.getAttribute('data-buku')); } catch (e) { return; }
                if (!data) { return; }

                // Tema popup mengikuti kartu yang diklik (gelap / krem)
                box.classList.remove('featured-quote', 'special-offer');
                box.classList.add(kartu.classList.contains('special-offer') ? 'special-offer' : 'featured-quote');

                var bg = kartu.querySelector('.card-bg-image').getAttribute('src');
                document.getElementById('qmBg').src = bg;

                var cover = document.getElementById('qmCover');
                cover.onerror = function () { cover.onerror = null; cover.src = fallback; };
                cover.src = data.link_gambar || bg;

                var stok = parseInt(data.stok, 10) || 0;

                isi('qmPengarang', data.pengarang || '');
                isi('qmJudul', data.judul || '');
                isi('qmPenerbit', data.penerbit || '-');
                isi('qmTahun', data.tahun_terbit || '-');
                isi('qmKategori', data.nama_kategori || '-');
                isi('qmStok', stok > 0 ? 'Tersedia: ' + stok : 'Stok habis');
                isi('qmSinopsis', (data.sinopsis || '').trim() || 'Sinopsis belum tersedia.');

                terakhir = kartu;
                overlay.classList.add('active');
                overlay.setAttribute('aria-hidden', 'false');
                document.body.classList.add('modal-open');
                overlay.querySelector('.qm-scroll').scrollTop = 0;
                document.getElementById('qmTutup').focus();
            }

            function tutup() {
                overlay.classList.remove('active');
                overlay.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
                if (terakhir) { terakhir.focus(); }
            }

            document.querySelectorAll('.quote-card.dapat-diklik').forEach(function (kartu) {
                kartu.addEventListener('click', function () { buka(kartu); });
                kartu.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        buka(kartu);
                    }
                });
            });

            document.getElementById('qmTutup').addEventListener('click', tutup);

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) { tutup(); }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && overlay.classList.contains('active')) { tutup(); }
            });
        })();
    </script>

</body>

</html>