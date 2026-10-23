<?php

include_once __DIR__ . '/../controller/c_bukuA.php';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Buku - PETAL</title>

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
            <h1>Tambah Data Buku</h1>
            <p>Tambahkan informasi buku baru ke dalam koleksi PETAL.</p>
        </div>


        <form action="../controller/c_bukuA.php?aksi=tambah"
              method="POST">

            <div class="form-grid">

                <!-- JUDUL -->
                <div class="form-group full">
                    <label for="judul">Judul Buku</label>

                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        placeholder="Masukkan judul buku"
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
                        placeholder="Nama pengarang"
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
                        placeholder="Nama penerbit"
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
                        placeholder="Contoh: 2024"
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
                        placeholder="Jumlah stok"
                        min="0"
                        value="0"
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

                            <option value="<?= $kategori->id_kategori; ?>">
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
                    ></textarea>

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
                    Simpan Buku
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>