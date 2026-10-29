<?php
include_once __DIR__ . '/../controller/c_bukuA.php';

$keyword = $_GET['keyword'] ?? '';
$kategori_aktif = (int) ($_GET['kategori'] ?? 0);
$kw_param = ($keyword !== '' ? '&keyword=' . urlencode($keyword) : '')
          . ($kategori_aktif > 0 ? '&kategori=' . $kategori_aktif : '');

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Buku - Progresif Library</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/daftar_bu.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar">
    <img src="../assets/logo.svg" class="logo" alt="Logo PETAL">

    <div class="nav-links">
        <a href="dashboard_admin.php">Dashboard</a>
        <a href="daftar_bukuA.php" class="active">Daftar Buku</a>
        <a href="daftar_user.php">Daftar Pengguna</a>
        <a href="daftar_kategori.php">Daftar Kategori</a>
        <a href="peminjamanA.php">Peminjaman</a>
        <a href="pengembalianA.php">Pengembalian</a>
        <a href="riwayat_peminjamanA.php">Riwayat Peminjaman</a>
    </div>
</nav>


<!-- =====================================================
     CONTENT
===================================================== -->

<div class="container">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <!-- SEARCH FORM -->
        <form method="GET" action="daftar_bukuA.php" class="search-form">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input 
                    type="text" 
                    name="keyword" 
                    value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" 
                    placeholder="Cari judul, pengarang, penerbit, stok..."
                >
            </div>

            <select name="kategori" class="filter-kategori" onchange="this.form.submit()">
                <option value="0">Semua Kategori</option>
                <?php foreach ($data_kategori as $kat): ?>
                    <option value="<?= (int) $kat->id_kategori; ?>"
                        <?= $kategori_aktif === (int) $kat->id_kategori ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($kat->nama_kategori, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- BUTTON TAMBAH -->
        <button type="button" class="btn-tambah" onclick="bukaModalTambah()">
            <i class="fa-solid fa-plus"></i> Tambah Buku
        </button>
    </div>

    <!-- TABLE DATA BUKU -->
    <table id="tableBuku">
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Judul</th>
                <th class="pengarang">Pengarang</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($data_buku)): ?>
                <?php foreach ($data_buku as $index => $b): ?>
                    <tr>
                        <td><?= ($start ?? 0) + $index + 1; ?></td>

                        <td>
                            <?php if (!empty($b->link_gambar)): ?>
                                <img 
                                    src="<?= htmlspecialchars($b->link_gambar, ENT_QUOTES, 'UTF-8'); ?>" 
                                    class="book-image" 
                                    alt="Cover Buku"
                                    onerror="
                                        this.style.display='none';
                                        if(this.nextElementSibling) this.nextElementSibling.style.display='flex';
                                    "
                                >
                                <span class="no-image" style="display:none;">
                                    <i class="fa-solid fa-book"></i>
                                </span>
                            <?php else: ?>
                                <span class="no-image">
                                    <i class="fa-solid fa-book"></i>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="judul-buku">
                                <?= htmlspecialchars($b->judul ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>

                        <td><?= htmlspecialchars($b->pengarang ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= htmlspecialchars($b->penerbit ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= htmlspecialchars($b->tahun_terbit ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                        <td>
                            <?= !empty($b->nama_kategori) 
                                ? htmlspecialchars($b->nama_kategori, ENT_QUOTES, 'UTF-8') 
                                : '-'; 
                            ?>
                        </td>

                        <td>
                            <span class="stok">
                                <?= htmlspecialchars($b->stok ?? 0, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>

                        <td class="aksi">
                            <!-- EDIT BUTTON MENGGUNAKAN DATA ATTRIBUTES -->
                            <button 
                                type="button" 
                                class="btn-edit" 
                                title="Edit Buku"
                                data-id="<?= (int)$b->id_buku; ?>"
                                data-judul="<?= htmlspecialchars($b->judul ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                data-pengarang="<?= htmlspecialchars($b->pengarang ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                data-penerbit="<?= htmlspecialchars($b->penerbit ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                data-tahun="<?= (int)($b->tahun_terbit ?? 0); ?>"
                                data-stok="<?= (int)($b->stok ?? 0); ?>"
                                data-sinopsis="<?= htmlspecialchars($b->sinopsis ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                data-gambar="<?= htmlspecialchars($b->link_gambar ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                data-kategori="<?= isset($b->id_kategori) ? (int)$b->id_kategori : ''; ?>"
                                onclick="bukaModalEditViaElement(this)">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <!-- HAPUS -->
                            <button type="button" class="btn-hapus" title="Hapus Buku" onclick="hapusBuku(<?= (int)$b->id_buku; ?>)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- DATA KOSONG -->
                <tr class="row-empty">
                    <td colspan="9" class="empty-data">
                        <i class="fa-solid fa-book-open"></i>
                        <br>
                        Belum ada data buku.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <?php if (isset($total_halaman) && $total_halaman > 1): ?>
        <div class="pagination">
            <?php if ($halaman > 1): ?>
                <a href="?halaman=<?= ($halaman - 1) . $kw_param; ?>" title="Halaman Sebelumnya">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                <a href="?halaman=<?= $i . $kw_param; ?>" class="<?= $i == $halaman ? 'active' : ''; ?>">
                    <?= $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($halaman < $total_halaman): ?>
                <a href="?halaman=<?= ($halaman + 1) . $kw_param; ?>" title="Halaman Berikutnya">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>


<!-- =====================================================
     MODAL TAMBAH BUKU
===================================================== -->

<div id="modalTambah" class="modal-overlay" onclick="klikLuarModal(event, 'modalTambah')">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="tutupModal('modalTambah')">&times;</button>
        <h2 class="modal-title">Tambah Buku</h2>

        <form action="../controller/c_bukuA.php?aksi=tambah" method="POST">
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" placeholder="Masukkan judul buku" required>
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" placeholder="Masukkan nama pengarang" required>
            </div>

            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" placeholder="Masukkan nama penerbit" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" placeholder="2026" min="1000" max="9999" required>
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" placeholder="0" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php if (!empty($data_kategori)): ?>
                        <?php foreach ($data_kategori as $kategori): ?>
                            <option value="<?= (int)$kategori->id_kategori; ?>">
                                <?= htmlspecialchars($kategori->nama_kategori, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Link Gambar</label>
                <input type="text" name="link_gambar" placeholder="https://...">
            </div>

            <div class="form-group">
                <label>Sinopsis</label>
                <textarea name="sinopsis" placeholder="Masukkan sinopsis buku..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-batal" onclick="tutupModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-simpan">Kirim</button>
            </div>
        </form>
    </div>
</div>


<!-- MODAL EDIT BUKU -->

<div id="modalEdit" class="modal-overlay" onclick="klikLuarModal(event, 'modalEdit')">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="tutupModal('modalEdit')">&times;</button>
        <h2 class="modal-title">Edit Buku</h2>

        <form action="../controller/c_bukuA.php?aksi=edit" method="POST">
            <input type="hidden" name="id_buku" id="edit_id_buku">

            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" id="edit_judul" required>
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" id="edit_pengarang" required>
            </div>

            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" id="edit_penerbit" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" id="edit_tahun_terbit" min="1000" max="9999" required>
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" id="edit_stok" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" id="edit_id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php if (!empty($data_kategori)): ?>
                        <?php foreach ($data_kategori as $kategori): ?>
                            <option value="<?= (int)$kategori->id_kategori; ?>">
                                <?= htmlspecialchars($kategori->nama_kategori, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Link Gambar</label>
                <input type="text" name="link_gambar" id="edit_link_gambar">
            </div>

            <div class="form-group">
                <label>Sinopsis</label>
                <textarea name="sinopsis" id="edit_sinopsis"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-batal" onclick="tutupModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-simpan">
                 Simpan
                </button>
            </div>
        </form>
    </div>
</div>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>
/* MODAL TAMBAH */
function bukaModalTambah() {
    document.getElementById('modalTambah').classList.add('active');
}

/* MODAL EDIT MENGGUNAKAN DATA ATTRIBUTES */
function bukaModalEditViaElement(btn) {
    const id = btn.dataset.id || '';
    const judul = btn.dataset.judul || '';
    const pengarang = btn.dataset.pengarang || '';
    const penerbit = btn.dataset.penerbit || '';
    const tahun = btn.dataset.tahun || '';
    const stok = btn.dataset.stok || '';
    const sinopsis = btn.dataset.sinopsis || '';
    const gambar = btn.dataset.gambar || '';
    const kategori = btn.dataset.kategori || '';

    document.getElementById('edit_id_buku').value = id;
    document.getElementById('edit_judul').value = judul;
    document.getElementById('edit_pengarang').value = pengarang;
    document.getElementById('edit_penerbit').value = penerbit;
    document.getElementById('edit_tahun_terbit').value = tahun;
    document.getElementById('edit_stok').value = stok;
    document.getElementById('edit_sinopsis').value = sinopsis;
    document.getElementById('edit_link_gambar').value = gambar;

    // Menyesuaikan dropdown kategori
    const selectKategori = document.getElementById('edit_id_kategori');
    if (selectKategori) {
        selectKategori.value = kategori;
    }

    document.getElementById('modalEdit').classList.add('active');
}

/* TUTUP MODAL */
function tutupModal(idModal) {
    document.getElementById(idModal).classList.remove('active');
}

/* KLIK DI LUAR MODAL */
function klikLuarModal(event, idModal) {
    if (event.target === document.getElementById(idModal)) {
        tutupModal(idModal);
    }
}

/* HAPUS BUKU */
function hapusBuku(id) {
    if (confirm('Yakin ingin menghapus buku ini?')) {
        window.location.href = '../controller/c_bukuA.php?aksi=hapus&id=' + id;
    }
}

/* TOMBOL ESC UNTUK MENUTUP MODAL */
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(function(modal) {
            modal.classList.remove('active');
        });
    }
});
</script>

</body>
</html>