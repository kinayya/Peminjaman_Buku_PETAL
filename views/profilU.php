<?php
include_once __DIR__ . '/../controller/c_profilU.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Progresif Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/profilU.css">
</head>
<body>

<nav class="navbar">
    <a href="dashboard_user.php" class="logo-link">
        <img src="../assets/logo.svg" class="logo" alt="Progresif Library">
    </a>

    <div class="nav-links">
        <a href="dashboard_user.php">Dashboard</a>
        <a href="profilU.php" class="active">Profil</a>
        <a href="daftar_bukuU.php">Daftar Buku</a>
        <a href="riwayat_peminjamanU.php">Riwayat Peminjaman</a>
    </div>
</nav>

<main class="profil-content">

    <section class="profil-card">
        <div class="profil-avatar"><?= htmlspecialchars($inisial) ?></div>
        <div class="identity">
            <h2><?= htmlspecialchars($data_profil['username'] ?? '') ?></h2>
            <p><?= htmlspecialchars($data_profil['Email'] ?? '-') ?></p>
            <span class="profil-role"><?= htmlspecialchars($label_role) ?></span>
        </div>
        <div class="profil-stats">
            <div><strong><?= (int) $stat['total'] ?></strong><span>Total Peminjaman</span></div>
            <div><strong><?= (int) $stat['menunggu'] ?></strong><span>Menunggu</span></div>
            <div><strong><?= (int) $stat['dipinjam'] ?></strong><span>Sedang Dipinjam</span></div>
            <div><strong><?= (int) $stat['dikembalikan'] ?></strong><span>Dikembalikan</span></div>
        </div>
    </section>

    <section class="profil-panel" id="panel-profil">
        <div class="panel-title">
            <div class="panel-icon"><i class="fa-solid fa-user-pen"></i></div>
            <div><h2>Informasi Akun</h2><p>Perbarui username dan email akun kamu.</p></div>
        </div>

        <?php if ($pesan_profil): ?>
            <div class="alert alert-<?= $pesan_profil['tipe'] === 'sukses' ? 'sukses' : 'error' ?>">
                <?= htmlspecialchars($pesan_profil['pesan']) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="profilU.php#panel-profil" autocomplete="off">
            <input type="hidden" name="aksi" value="ubah_profil">

            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="username" name="username" maxlength="50" required value="<?= htmlspecialchars($input_username) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" maxlength="159" required value="<?= htmlspecialchars($input_email) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-simpan"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
        </form>
    </section>

    <section class="profil-panel" id="panel-password">
        <div class="panel-title">
            <div class="panel-icon"><i class="fa-solid fa-lock"></i></div>
            <div><h2>Keamanan Akun</h2><p>Ubah password untuk menjaga keamanan akun.</p></div>
        </div>

        <?php if ($pesan_password): ?>
            <div class="alert alert-<?= $pesan_password['tipe'] === 'sukses' ? 'sukses' : 'error' ?>">
                <?= htmlspecialchars($pesan_password['pesan']) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="profilU.php#panel-password" autocomplete="off">
            <input type="hidden" name="aksi" value="ubah_password">

            <div class="form-group">
                <label for="password_lama">Password Lama</label>
                <div class="input-wrap password-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password_lama" name="password_lama" autocomplete="current-password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_lama', this)" aria-label="Lihat password"><i class="fa-solid fa-eye"></i></button>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password_baru">Password Baru</label>
                    <div class="input-wrap password-wrap">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" id="password_baru" name="password_baru" minlength="6" autocomplete="new-password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password_baru', this)" aria-label="Lihat password"><i class="fa-solid fa-eye"></i></button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_ulang">Ulangi Password Baru</label>
                    <div class="input-wrap password-wrap">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" id="password_ulang" name="password_ulang" minlength="6" autocomplete="new-password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password_ulang', this)" aria-label="Lihat password"><i class="fa-solid fa-eye"></i></button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-simpan"><i class="fa-solid fa-shield-halved"></i> Ubah Password</button>
        </form>
    </section>

    <section class="logout-panel">
        <div>
            <strong>Keluar dari akun?</strong>
        </div>
        <a href="../controller/logout.php" onclick="return confirm('Yakin ingin logout?');"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </section>

</main>

<script>
function togglePassword(id, button) {
    const input = document.getElementById(id);
    const icon = button.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php if ($scroll_ke !== ''): ?>
<script>
document.getElementById('<?= $scroll_ke ?>').scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>
<?php endif; ?>
</body>
</html>
