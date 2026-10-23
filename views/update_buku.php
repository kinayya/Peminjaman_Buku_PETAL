<?php

// ===============================
// KONEKSI DATABASE
// ===============================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "Peminjaman_Buku_PETAL";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");


// ===============================
// AMBIL SEMUA DATA BUKU
// ===============================
$sql = "SELECT id_buku, judul, pengarang FROM buku";
$result = $conn->query($sql);

if (!$result) {
    die("Gagal mengambil data buku: " . $conn->error);
}


// ===============================
// FUNGSI AMBIL DATA GOOGLE BOOKS
// ===============================
function getBookData($judul, $pengarang)
{
    $query = urlencode('intitle:"' . $judul . '" inauthor:"' . $pengarang . '"');

    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&maxResults=1";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => "Mozilla/5.0"
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (
        !$data ||
        !isset($data['items']) ||
        count($data['items']) == 0
    ) {
        return null;
    }

    $book = $data['items'][0]['volumeInfo'];

    // ===============================
    // SINOPSIS
    // ===============================
    $sinopsis = null;

    if (!empty($book['description'])) {
        $sinopsis = strip_tags($book['description']);
    }

    // ===============================
    // COVER
    // ===============================
    $cover = null;

    if (!empty($book['imageLinks']['thumbnail'])) {
        $cover = $book['imageLinks']['thumbnail'];

        // Mengubah http menjadi https
        $cover = str_replace("http://", "https://", $cover);
    }

    return [
        'sinopsis' => $sinopsis,
        'cover'    => $cover
    ];
}


// ===============================
// PROSES SEMUA BUKU
// ===============================
$total = $result->num_rows;
$berhasil = 0;
$gagal = 0;

echo "<h2>Update Data Buku</h2>";
echo "<p>Total buku: <b>$total</b></p>";

echo "<hr>";

while ($row = $result->fetch_assoc()) {

    $id       = $row['id_buku'];
    $judul    = $row['judul'];
    $pengarang = $row['pengarang'];

    echo "<p>";
    echo "<b>Memproses:</b> " . htmlspecialchars($judul);
    echo "<br>Pengarang: " . htmlspecialchars($pengarang);

    // Ambil data Google Books
    $data = getBookData($judul, $pengarang);

    if ($data === null) {

        echo "<br><span style='color:red;'>❌ Data tidak ditemukan</span>";
        echo "</p>";

        $gagal++;

        // Jeda agar tidak terlalu cepat
        sleep(1);

        continue;
    }


    // ===============================
    // UPDATE DATABASE
    // ===============================
    $stmt = $conn->prepare("
        UPDATE buku
        SET sinopsis = ?, link_gambar = ?
        WHERE id_buku = ?
    ");

    $stmt->bind_param(
        "ssi",
        $data['sinopsis'],
        $data['cover'],
        $id
    );

    if ($stmt->execute()) {

        echo "<br><span style='color:green;'>✅ Berhasil</span>";

        if ($data['sinopsis']) {
            echo "<br>📖 Sinopsis ditemukan";
        } else {
            echo "<br>⚠️ Sinopsis tidak tersedia";
        }

        if ($data['cover']) {
            echo "<br>🖼️ Cover ditemukan";
        } else {
            echo "<br>⚠️ Cover tidak tersedia";
        }

        $berhasil++;

    } else {

        echo "<br><span style='color:red;'>❌ Gagal update database</span>";

        $gagal++;
    }

    $stmt->close();

    echo "</p>";
    echo "<hr>";

    // Jeda antar-request
    sleep(1);
}


// ===============================
// HASIL AKHIR
// ===============================
echo "<h3>Selesai!</h3>";

echo "Total buku: <b>$total</b><br>";
echo "Berhasil: <b style='color:green;'>$berhasil</b><br>";
echo "Gagal/tidak ditemukan: <b style='color:red;'>$gagal</b><br>";

$conn->close();

?>