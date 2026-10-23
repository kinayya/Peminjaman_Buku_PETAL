<?php

include_once __DIR__ . '/../controller/c_bukuA.php';

if (!isset($_GET['id_buku']) || empty($_GET['id_buku'])) {
    header("Location: daftar_bukuA.php");
    exit;
}

$id_buku = (int) $_GET['id_buku'];

$data_edit = $buku->tampil_satu_buku($id_buku);

$data_kategori = $buku->tampil_kategori();

if (!$data_edit) {
    echo "<script>
            alert('Data buku tidak ditemukan');
            window.location='daftar_bukuA.php';
          </script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Buku - PETAL</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5fafb;
            color: #33475B;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 850px;
            margin: auto;
        }

        .form-card {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(48, 126, 143, 0.12);
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            color: #307e8f;
            font-size: 30px;
            margin-bottom: 8px;
        }

        .header p {
            color: #718096;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            color: #33475B;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d7e2e6;
            border-radius: 12px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            transition: 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #307e8f;
            box-shadow: 0 0 0 3px rgba(48, 126, 143, 0.1);
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .button-area {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            text-decoration: none;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-back {
            background: #edf2f4;
            color: #536471;
        }

        .btn-back:hover {
            background: #dfe8eb;
        }

        .btn-save {
            background: #307e8f;
            color: white;
        }

        .btn-save:hover {
            background: #276b79;
            transform: translateY(-1px);
        }

        @media (max-width: 650px) {

            body {
                padding: 20px 12px;
            }

            .form-card {
                padding: 25px 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }

            .button-area {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }

    </style>

</head>

<body>

<div class="container">

    <div class="form-card">

        <div class="header">

            <h1>Edit Data Buku</h1>

            <p>
                Perbarui informasi buku yang sudah tersimpan.
            </p>

        </div>


        <form
            action="../controller/c_bukuA.php?aksi=edit"
            method="POST"
        >

            <!-- ID BUKU -->
            <input
                type="hidden"
                name="id_buku"
                value="<?= $data_edit->id_buku; ?>"
            >


            <div class="form-grid">

                <!-- JUDUL -->
                <div class="form-group full">

                    <label for="judul">
                        Judul Buku
                    </label>

                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        value="<?= htmlspecialchars($data_edit->judul); ?>"
                        required
                    >

                </div>


                <!-- PENGARANG -->
                <div class="form-group">

                    <label for="pengarang">
                        Pengarang
                    </label>

                    <input
                        type="text"
                        id="pengarang"
                        name="pengarang"
                        value="<?= htmlspecialchars($data_edit->pengarang); ?>"
                        required
                    >

                </div>


                <!-- PENERBIT -->
                <div class="form-group">

                    <label for="penerbit">
                        Penerbit
                    </label>

                    <input
                        type="text"
                        id="penerbit"
                        name="penerbit"
                        value="<?= htmlspecialchars($data_edit->penerbit); ?>"
                        required
                    >

                </div>


                <!-- TAHUN -->
                <div class="form-group">

                    <label for="tahun_terbit">
                        Tahun Terbit
                    </label>

                    <input
                        type="number"
                        id="tahun_terbit"
                        name="tahun_terbit"
                        value="<?= htmlspecialchars($data_edit->tahun_terbit); ?>"
                        min="1900"
                        max="2100"
                        required
                    >

                </div>


                <!-- STOK -->
                <div class="form-group">

                    <label for="stok">
                        Stok
                    </label>

                    <input
                        type="number"
                        id="stok"
                        name="stok"
                        value="<?= htmlspecialchars($data_edit->stok); ?>"
                        min="0"
                        required
                    >

                </div>


                <!-- KATEGORI -->
                <div class="form-group full">

                    <label for="id_kategori">
                        Kategori
                    </label>

                    <select
                        id="id_kategori"
                        name="id_kategori"
                        required
                    >

                        <option value="">
                            -- Pilih Kategori --
                        </option>

                        <?php foreach ($data_kategori as $kategori): ?>

                            <option
                                value="<?= $kategori->id_kategori; ?>"
                                <?= ($data_edit->id_kategori == $kategori->id_kategori)
                                    ? 'selected'
                                    : ''; ?>
                            >

                                <?= htmlspecialchars($kategori->nama_kategori); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- LINK GAMBAR -->
                <div class="form-group full">

                    <label for="link_gambar">
                        Link Gambar Sampul
                    </label>

                    <input
                        type="url"
                        id="link_gambar"
                        name="link_gambar"
                        value="<?= htmlspecialchars($data_edit->link_gambar ?? ''); ?>"
                        placeholder="https://contoh.com/gambar-buku.jpg"
                    >

                </div>


                <!-- SINOPSIS -->
                <div class="form-group full">

                    <label for="sinopsis">
                        Sinopsis
                    </label>

                    <textarea
                        id="sinopsis"
                        name="sinopsis"
                        placeholder="Masukkan sinopsis buku..."
                    ><?= htmlspecialchars($data_edit->sinopsis ?? ''); ?></textarea>

                </div>

            </div>


            <!-- BUTTON -->
            <div class="button-area">

                <a
                    href="daftar_bukuA.php"
                    class="btn btn-back"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-save"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>