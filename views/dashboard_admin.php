<?php
include_once __DIR__ . '/../controller/c_dashboard_admin.php';
include_once __DIR__ . '/../controller/c_grafik_admin.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Perpustakaan</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS External -->
    <link rel="stylesheet" href="../css/dbadmin.css">
</head>

<body>

<div class="layout">

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">

        <div class="logo-area">
            <img src="../assets/logo.svg" alt="Logo Perpustakaan">
        </div>

        <nav class="menu">

            <a href="daftar_bukuA.php" class="menu-item">
                <i class="fa-solid fa-book"></i>
                <span class="menu-text">Daftar Buku</span>
            </a>

            <a href="daftar_user.php" class="menu-item">
                <i class="fa-solid fa-user-group"></i>
                <span class="menu-text">Daftar Pengguna</span>
            </a>

            <a href="daftar_kategori.php" class="menu-item">
                <i class="fa-solid fa-table-list"></i>
                <span class="menu-text">Daftar Kategori</span>
            </a>

            <a href="peminjamanA.php" class="menu-item">
                 <i class="fa-solid fa-book-open-reader"></i>
                <span class="menu-text">Peminjaman</span>
            </a>

            <a href="pengembalianA.php" class="menu-item">
                <i class="fa-solid fa-book-open"></i>
                <span class="menu-text">Pengembalian</span>
            </a>

            <a href="riwayat_peminjamanA.php" class="menu-item">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span class="menu-text">Riwayat Peminjaman</span>
            </a>

        </nav>

        <!-- ================= LOGOUT ================= -->
        <div class="sidebar-bottom">
            <a href="../controller/logout.php" class="menu-item logout" onclick="return confirm('Yakin ingin logout?');">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="menu-text">Logout</span>
            </a>
        </div>

    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <section class="welcome">
            <h3>Welcome Admin!</h3>
        </section>

        <!-- ================= STATISTIK RINGKAS ================= -->
        <section class="statistics">

            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-book"></i></div>
                <div class="stat-content">
                    <span>Total Buku</span>
                    <strong><?= (int) $stat_total_buku ?></strong>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-user-group"></i></div>
                <div class="stat-content">
                    <span>Total User</span>
                    <strong><?= (int) $stat_total_user ?></strong>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange"><i class="fa-solid fa-book-open-reader"></i></div>
                <div class="stat-content">
                    <span>Sedang Dipinjam</span>
                    <strong><?= (int) $stat_sedang_dipinjam ?></strong>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple"><i class="fa-solid fa-hourglass-half"></i></div>
                <div class="stat-content">
                    <span>Menunggu Konfirmasi</span>
                    <strong><?= (int) $stat_menunggu_konfirmasi ?></strong>
                    <small>pengembalian</small>
                </div>
            </div>

        </section>

        <!-- ================= GRAFIK ================= -->
        <section class="transaction-grid chart-grid">

            <!-- TREN PEMINJAMAN -->
            <div class="transaction-card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="mini-icon blue-bg"><i class="fa-solid fa-chart-line"></i></div>
                        <div>
                            <h2>Tren Peminjaman</h2>
                            <p>Jumlah pengajuan per bulan (6 bulan terakhir)</p>
                        </div>
                    </div>
                </div>
                <div class="chart-box">
                    <?php if ($ada_data['bulanan']): ?>
                        <canvas id="grafikBulanan"></canvas>
                    <?php else: ?>
                        <p class="chart-empty">Belum ada data peminjaman.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- STATUS PEMINJAMAN -->
            <div class="transaction-card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="mini-icon green-bg"><i class="fa-solid fa-chart-pie"></i></div>
                        <div>
                            <h2>Status Peminjaman</h2>
                            <p>Perbandingan seluruh peminjaman berdasarkan status</p>
                        </div>
                    </div>
                </div>
                <div class="chart-box">
                    <?php if ($ada_data['status']): ?>
                        <canvas id="grafikStatus"></canvas>
                    <?php else: ?>
                        <p class="chart-empty">Belum ada data peminjaman.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- BUKU TERPOPULER -->
            <div class="transaction-card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="mini-icon orange-bg"><i class="fa-solid fa-ranking-star"></i></div>
                        <div>
                            <h2>Buku Terpopuler</h2>
                            <p>5 buku yang paling sering dipinjam</p>
                        </div>
                    </div>
                </div>
                <div class="chart-box">
                    <?php if ($ada_data['populer']): ?>
                        <canvas id="grafikPopuler"></canvas>
                    <?php else: ?>
                        <p class="chart-empty">Belum ada buku yang dipinjam.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- BUKU PER KATEGORI -->
            <div class="transaction-card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="mini-icon purple-bg"><i class="fa-solid fa-table-list"></i></div>
                        <div>
                            <h2>Buku per Kategori</h2>
                            <p>Jumlah judul buku di setiap kategori</p>
                        </div>
                    </div>
                </div>
                <div class="chart-box">
                    <?php if ($ada_data['kategori']): ?>
                        <canvas id="grafikKategori"></canvas>
                    <?php else: ?>
                        <p class="chart-empty">Belum ada data buku.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </main>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {

    var d = <?= json_encode($grafik, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    var canvases = document.querySelectorAll('.chart-box canvas');

    // Jika Chart.js gagal dimuat (mis. tidak ada internet), tampilkan pesan
    if (typeof Chart === 'undefined') {
        canvases.forEach(function (c) {
            var p = document.createElement('p');
            p.className = 'chart-empty';
            p.textContent = 'Grafik tidak dapat dimuat. Periksa koneksi internet.';
            c.parentNode.replaceChild(p, c);
        });
        return;
    }

    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#667080';

    var warna = ['#398ee8', '#37bd5a', '#ffa515', '#8553ed', '#e63c3c',
                 '#2fb7c4', '#f06fa8', '#8aa4b8', '#b9a000', '#5c6bc0'];

    function potong(teks, maks) {
        return teks.length > maks ? teks.substring(0, maks - 1) + '\u2026' : teks;
    }

    var elBulanan = document.getElementById('grafikBulanan');
    if (elBulanan) {
        new Chart(elBulanan, {
            type: 'line',
            data: {
                labels: d.bulanan.labels,
                datasets: [{
                    label: 'Peminjaman',
                    data: d.bulanan.data,
                    borderColor: '#398ee8',
                    backgroundColor: 'rgba(57, 142, 232, 0.15)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#398ee8'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#edf0f3' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    var elStatus = document.getElementById('grafikStatus');
    if (elStatus) {
        new Chart(elStatus, {
            type: 'doughnut',
            data: {
                labels: d.status.labels,
                datasets: [{
                    data: d.status.data,
                    backgroundColor: ['#ffa515', '#398ee8', '#8553ed', '#37bd5a', '#e63c3c'],
                    borderColor: '#ffffff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } } }
            }
        });
    }

    var elPopuler = document.getElementById('grafikPopuler');
    if (elPopuler) {
        new Chart(elPopuler, {
            type: 'bar',
            data: {
                labels: d.populer.labels.map(function (t) { return potong(t, 28); }),
                datasets: [{
                    label: 'Kali dipinjam',
                    data: d.populer.data,
                    backgroundColor: '#ffa515',
                    borderRadius: 8,
                    maxBarThickness: 26
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function (items) { return d.populer.labels[items[0].dataIndex]; }
                        }
                    }
                },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#edf0f3' } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    var elKategori = document.getElementById('grafikKategori');
    if (elKategori) {
        new Chart(elKategori, {
            type: 'bar',
            data: {
                labels: d.kategori.labels.map(function (t) { return potong(t, 14); }),
                datasets: [{
                    label: 'Jumlah buku',
                    data: d.kategori.data,
                    backgroundColor: d.kategori.labels.map(function (_, i) { return warna[i % warna.length]; }),
                    borderRadius: 8,
                    maxBarThickness: 38
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function (items) { return d.kategori.labels[items[0].dataIndex]; }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#edf0f3' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

})();
</script>

</body>
</html>
