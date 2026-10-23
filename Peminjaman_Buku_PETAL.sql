-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 08:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Peminjaman_Buku_PETAL`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `stok` int(35) NOT NULL DEFAULT 0,
  `sinopsis` varchar(1000) NOT NULL,
  `link_gambar` varchar(500) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `stok`, `sinopsis`, `link_gambar`, `id_kategori`) VALUES
(1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 8, 'Kisah perjuangan sepuluh anak Melayu Belitung dalam mengejar cita-cita di tengah keterbatasan fasilitas sekolah.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1489732961i/1362193.jpg', 1),
(2, 'Sang Pemimpi', 'Andrea Hirata', 'Bentang Pustaka', '2006', 6, 'Perjalanan remaja Ikal, Arai, dan Jimbron dalam mempertahankan mimpi dan mengejar pendidikan hingga ke Sorbonne.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1360741270i/1912836.jpg', 1),
(3, 'Bumi', 'Tere Liye', 'Gramedia Pustaka Utama', '2014', 10, 'Petualangan Raib, Seli, dan Ali yang menemukan dunia paralel Klan Bumi dan Klan Bulan.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1389852264i/20560249.jpg', 1),
(4, 'Bulan', 'Tere Liye', 'Gramedia Pustaka Utama', '2015', 7, 'Lanjutan petualangan tiga sekawan mengikuti festival pertarungan mematikan di Klan Matahari.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1423223028i/24855216.jpg', 1),
(5, 'Matahari', 'Tere Liye', 'Gramedia Pustaka Utama', '2016', 9, 'Rencana petualangan baru membawa Raib, Seli, dan Ali ke Klan Bintang yang misterius di perut bumi.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1466046757i/30612660.jpg', 1),
(6, 'Hujan', 'Tere Liye', 'Gramedia Pustaka Utama', '2016', 8, 'Kisah tentang persahabatan, perpisahan, teknologi masa depan, dan kenangan pahit yang coba dihapuskan.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1451996503i/28447817.jpg', 1),
(7, 'Laut Bercerita', 'Leila S. Chudori', 'Kepustakaan Populer Gramedia', '2017', 7, 'Kisah penderitaan para aktivis mahasiswa yang dihilangkan secara paksa pada era Orde Baru.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1508216126i/36428784.jpg', 1),
(8, 'Perahu Kertas', 'Dee Lestari', 'Bentang Pustaka', '2009', 5, 'Kugy dan Keenan yang saling memendam rasa dalam lika-liku persahabatan dan impian seni mereka.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1356225544i/6765740.jpg', 1),
(9, 'Aroma Karsa', 'Dee Lestari', 'Bentang Pustaka', '2018', 5, 'Pencarian tanaman langka Puspa Karsa yang memadukan misteri mitologi dan kekuatan indra penciuman.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1520697967i/39097780.jpg', 1),
(10, 'Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia Pustaka Utama', '2009', 9, 'Semangat Man Jadda Wajada dari kehidupan santri di Pondok Madani dalam meraih cita-cita dunia.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1247942661i/6698947.jpg', 1),
(11, 'Dilan: Dia adalah Dilanku Tahun 1990', 'Pidi Baiq', 'Pastel Books', '2014', 7, 'Romantisme dan keunikan gaya pacaran ala Dilan dan Milea di kota Bandung era 90-an.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1400234151i/22070100.jpg', 1),
(12, 'Mariposa', 'Luluk HF', 'Coconut Books', '2018', 6, 'Kisah Acha yang tak pantang menyerah mendekati Iqbal, seorang remaja laki-laki sedingin kupu-kupu mariposa.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1545041071i/43236021.jpg', 1),
(13, 'Geez & Ann', 'Rintik Sedu', 'GagasMedia', '2017', 5, 'Proses kedewasaan Ann dan Geez dalam menghadapi komitmen, perpisahan, dan pilihan hidup.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1508930438i/36483569.jpg', 1),
(14, 'Pulang', 'Tere Liye', 'Republika', '2015', 8, 'Perjalanan Bujang dalam dunia shadow economy hingga menemukan arti pulang yang sejati.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1442144888i/26500858.jpg', 1),
(15, 'Rindu', 'Tere Liye', 'Republika', '2014', 7, 'Kisah perjalanan panjang di atas kapal haji bertahun 1938 yang sarat makna dan kerinduan.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1412674996i/23326117.jpg', 1),
(16, 'A Brief History of Time', 'Stephen Hawking', 'Bantam Books', '1988', 5, 'Pembahasan asal-usul alam semesta, lubang hitam, dan teori relativitas untuk pembaca awam.', 'https://m.media-amazon.com/images/I/91+A2JmR+PL._AC_UF1000,1000_QL80_.jpg', 3),
(17, 'Cosmos', 'Carl Sagan', 'Random House', '1980', 6, 'Eksplorasi kosmologi, sejarah sains, serta tempat manusia di dalam luasnya alam semesta.', 'https://m.media-amazon.com/images/I/815LhQ+V2iL._AC_UF1000,1000_QL80_.jpg', 3),
(18, 'Astrophysics for People in a Hurry', 'Neil deGrasse Tyson', 'W. W. Norton & Company', '2017', 7, 'Rangkuman astrofisika modern yang dikemas secara singkat dan mudah dipahami.', 'https://m.media-amazon.com/images/I/71u9S+ZfInL._AC_UF1000,1000_QL80_.jpg', 3),
(19, 'The Selfish Gene', 'Richard Dawkins', 'Oxford University Press', '1976', 4, 'Perspektif evolusi yang berpusat pada gen sebagai unit utama seleksi alam.', 'https://m.media-amazon.com/images/I/71N-kIlyeEL._AC_UF1000,1000_QL80_.jpg', 3),
(20, 'The Hidden Life of Trees', 'Peter Wohlleben', 'Greystone Books', '2015', 5, 'Penjelasan menakjubkan tentang bagaimana pohon berkomunikasi dan saling merawat di hutan.', 'https://m.media-amazon.com/images/I/816T-Vp-e9L._AC_UF1000,1000_QL80_.jpg', 3),
(21, 'Sapiens: Riwayat Singkat Umat Manusia', 'Yuval Noah Harari', 'Kepustakaan Populer Gramedia', '2011', 8, 'Perjalanan evolusi Homo sapiens dari era kognitif hingga era teknologi canggih.', 'https://m.media-amazon.com/images/I/713jIoMO3UL._AC_UF1000,1000_QL80_.jpg', 4),
(22, 'Homo Deus: Masa Depan Umat Manusia', 'Yuval Noah Harari', 'Kepustakaan Populer Gramedia', '2015', 6, 'Prediksi masa depan kemanusiaan saat impian kesetaraan digantikan oleh kecerdasan buatan.', 'https://m.media-amazon.com/images/I/71239O-I2yL._AC_UF1000,1000_QL80_.jpg', 4),
(23, 'Guns, Germs, and Steel', 'Jared Diamond', 'W. W. Norton & Company', '1997', 5, 'Faktor geografis dan lingkungan yang membentuk perkembangan peradaban manusia.', 'https://m.media-amazon.com/images/I/81I2rYOnpUL._AC_UF1000,1000_QL80_.jpg', 4),
(24, 'Sejarah Indonesia Modern 1200-2008', 'M.C. Ricklefs', 'Serambi', '2008', 7, 'Rekaman rinci sejarah Nusantara sejak berkembangnya Islam hingga pasca-reformasi.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1247293888i/6666874.jpg', 4),
(25, 'The Silk Roads: Sejarah Baru Dunia', 'Peter Frankopan', 'Kompas', '2015', 4, 'Pandangan alternatif sejarah dunia dari sudut pandang Jalur Sutra Asia Tengah.', 'https://m.media-amazon.com/images/I/81l3rZK4koL._AC_UF1000,1000_QL80_.jpg', 4),
(26, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', 8, 'Panduan praktis penulisan kode program yang rapi, efisien, dan mudah dirawat.', 'https://m.media-amazon.com/images/I/41zoxjP9lcL._AC_UF1000,1000_QL80_.jpg', 5),
(27, 'The Pragmatic Programmer', 'Andrew Hunt, David Thomas', 'Addison-Wesley', '1999', 6, 'Tips dan teknik terbaik untuk menjadi pengembang perangkat lunak yang profesional.', 'https://m.media-amazon.com/images/I/71f743sOPoL._AC_UF1000,1000_QL80_.jpg', 5),
(28, 'Don\'t Make Me Think', 'Steve Krug', 'New Riders', '2000', 7, 'Prinsip utama desain antarmuka (UI/UX) yang intuitif dan berfokus pada kemudahan pengguna.', 'https://m.media-amazon.com/images/I/513yvOTo16L._AC_UF1000,1000_QL80_.jpg', 5),
(29, 'Eloquent JavaScript', 'Marijn Haverbeke', 'No Starch Press', '2011', 8, 'Buku panduan lengkap mendalami pemrograman JavaScript modern secara teknis dan praktis.', 'https://m.media-amazon.com/images/I/91asIC1f4pL._AC_UF1000,1000_QL80_.jpg', 5),
(30, 'Python Crash Course', 'Eric Matthes', 'No Starch Press', '2015', 9, 'Pengenalan cepat bahasa Python lengkap dengan pembuatan proyek aplikasi dan gim.', 'https://m.media-amazon.com/images/I/813RInM15AL._AC_UF1000,1000_QL80_.jpg', 5),
(31, 'Kamus Besar Bahasa Indonesia V', 'Tim Pustaka', 'Balai Pustaka', '2016', 10, 'Rujukan resmi kosakata dan leksikografi bahasa Indonesia standar.', 'https://m.media-amazon.com/images/I/61Nf+3YwO8L._AC_UF1000,1000_QL80_.jpg', 6),
(32, 'English Grammar in Use', 'Raymond Murphy', 'Cambridge University Press', '2019', 9, 'Buku acuan latihan tata bahasa Inggris paling populer untuk tingkat menengah.', 'https://m.media-amazon.com/images/I/7140IAn+XOL._AC_UF1000,1000_QL80_.jpg', 6),
(33, 'Tata Bahasa Baku Bahasa Indonesia', 'Hasan Alwi dkk.', 'Badan Bahasa', '2017', 5, 'Pedoman kaidah kebahasaan Indonesia yang komprehensif untuk akademisi.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1381283626i/18632612.jpg', 6),
(34, 'The Elements of Style', 'William Strunk Jr., E.B. White', 'Pearson', '1999', 6, 'Panduan ringkas penulisan bahasa Inggris yang efektif, jelas, dan lugas.', 'https://m.media-amazon.com/images/I/71I2b1S3EUL._AC_UF1000,1000_QL80_.jpg', 6),
(35, 'Word Power Made Easy', 'Norman Lewis', 'Anchor', '2014', 7, 'Metode interaktif dalam memperkaya kosakata bahasa Inggris secara cepat.', 'https://m.media-amazon.com/images/I/813uPMOnL8L._AC_UF1000,1000_QL80_.jpg', 6),
(36, 'Prisoners of Geography', 'Tim Marshall', 'Scribner', '2015', 6, 'Bagaimana faktor geografi fisik menentukan peta politik dan sejarah diplomasi dunia.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1432827094i/25135194.jpg', 7),
(37, 'The Power of Geography', 'Tim Marshall', 'Elliott & Thompson', '2021', 6, 'Lanjutan analisis geopolitik di sepuluh kawasan paling berpengaruh di masa depan.', 'https://m.media-amazon.com/images/I/81-0T+6KqGL._AC_UF1000,1000_QL80_.jpg', 7),
(38, 'Atlas Dunia untuk Pelajar', 'Tim Editor', 'Pustaka Baru Press', '2020', 8, 'Peta fisik dan politik dunia yang dilengkapi informasi geografi dasar.', 'https://m.media-amazon.com/images/I/91zYwK6E21L._AC_UF1000,1000_QL80_.jpg', 7),
(39, 'Geografi Lingkungan', 'Nursid Sumaatmadja', 'Alfabeta', '2018', 5, 'Kajian hubungan ekosistem, interaksi manusia, dan dinamika ruang kebumian.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1553580000i/44589000.jpg', 7),
(40, 'The Map That Changed the World', 'Simon Winchester', 'HarperCollins', '2001', 4, 'Kisah William Smith yang menciptakan peta geologi nasional pertama di Inggris.', 'https://m.media-amazon.com/images/I/81XmJzZfUdL._AC_UF1000,1000_QL80_.jpg', 7),
(41, 'Tafsir Al-Mishbah Vol 1', 'M. Quraish Shihab', 'Lentera Hati', '2002', 6, 'Pesan, kesan, dan keserasian Al-Qur\'an dengan gaya bahasa khas yang mencerahkan.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1286940858i/9398863.jpg', 8),
(42, 'Membumikan Al-Qur\'an', 'M. Quraish Shihab', 'Mizan', '1992', 7, 'Peran dan fungsi wahyu ilahi dalam kehidupan bermasyarakat modern.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1318817757i/3052828.jpg', 8),
(43, 'Ihya Ulumuddin', 'Imam Al-Ghazali', 'Republika', '2018', 4, 'Karya monumental sufistik yang membahas penyucian jiwa dan ibadah.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT94w3KDaX9sH34-UwCoOf9qIwuurAiB3unjw67y-NnYHfNrpwyOnHLUiV5Z4zdvrWC-Q8sfDO5F8pn5M7UWSGVSON-tN32wcpF3xg2jfk&s=10', 8),
(44, 'The Forty Rules of Love', 'Elif Shafak', 'Penguin Books', '2009', 8, 'Novel spiritual yang menceritakan ikatan persahabatan Jalaluddin Rumi dan Shams Tabrizi.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1442161289i/6642715.jpg', 8),
(45, 'The Power of Now', 'Eckhart Tolle', 'Namaste Publishing', '1997', 5, 'Panduan kesadaran spiritual untuk hidup sepenuhnya pada momen saat ini.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1689947880i/6708.jpg', 8),
(46, 'Steal Like an Artist', 'Austin Kleon', 'Workman Publishing', '2012', 8, '10 prinsip kreativitas era digital untuk mengeksplorasi ide-ide seni baru.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1404576602i/13099738.jpg', 9),
(47, 'Show Your Work!', 'Austin Kleon', 'Workman Publishing', '2014', 7, 'Cara membangun audiens dan membagikan proses kreatif seni secara konsisten.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1404580714i/18290401.jpg', 9),
(48, 'The Story of Art', 'E.H. Gombrich', 'Phaidon Press', '1950', 5, 'Buku sejarah seni rupa dunia terlengkap dan paling tepercaya sepanjang masa.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1311840836i/909524.jpg', 9),
(49, 'Understanding Comics', 'Scott McCloud', 'Harper Perennial', '1993', 6, 'Eksplorasi mendalam elemen visual, bahasa, dan storytelling dalam seni komik.', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1328408101i/102920.jpg', 9),
(50, 'The Artist\'s Way', 'Julia Cameron', 'TarcherPerigee', '1992', 8, 'Program pemulihan kreativitas jiwa dan mengatasi blokade seniman.', 'https://thumb.wikimedia.org/wikipedia/en/thumb/5/52/The_Artist%27s_Way.jpg/250px-The_Artist%27s_Way.jpg?utm_source=en.wikipedia.org&utm_campaign=parser&utm_content=thumbnail', 9);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Fiksi'),
(3, 'Sains'),
(4, 'Sejarah'),
(5, 'Teknologi'),
(6, 'Bahasa'),
(7, 'Geografi'),
(8, 'Agama'),
(9, 'Kesenian'),
(11, 'Sastra Inggris'),
(12, 'Psikologi'),
(13, 'Dongeng'),
(14, 'Horror');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `status` enum('pengajuan','disetujui','dipinjam','dikembalikan','ditolak') NOT NULL DEFAULT 'pengajuan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `id_buku`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(5, 15, 49, '2026-09-30', '2026-10-07', 'dikembalikan'),
(6, 15, 45, '2026-10-01', '2026-10-08', 'ditolak'),
(7, 15, 41, '2026-10-01', '2026-10-08', 'dikembalikan'),
(8, 15, 49, '2026-10-01', '2026-10-08', 'dikembalikan'),
(9, 21, 1, '2026-10-02', '2026-10-09', 'dikembalikan'),
(10, 22, 26, '2026-10-02', '2026-10-09', 'ditolak'),
(11, 15, 45, '2026-10-03', '2026-10-10', 'dipinjam'),
(12, 21, 8, '2026-10-12', '2026-10-19', 'dikembalikan'),
(13, 15, 8, '2026-10-12', '2026-10-19', 'dipinjam'),
(14, 21, 1, '2026-10-14', '2026-10-21', 'dikembalikan'),
(15, 24, 21, '2026-10-21', '2026-10-28', 'dikembalikan'),
(16, 22, 36, '2026-10-21', '2026-10-28', 'dipinjam'),
(17, 22, 43, '2026-10-21', '2026-10-28', 'dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`id_pengembalian`, `id_peminjaman`, `tanggal_pengembalian`, `keterangan`) VALUES
(2, 5, '2026-10-01', 'Dikonfirmasi oleh admin'),
(3, 7, '2026-10-01', 'Dikonfirmasi oleh admin'),
(4, 9, '2026-10-02', 'Dikonfirmasi oleh admin'),
(5, 8, '2026-10-21', 'Dikonfirmasi oleh admin'),
(6, 14, '2026-10-21', 'jangan lupa tanda tangannya!'),
(7, 12, '2026-10-21', 'Dikonfirmasi oleh admin'),
(8, 15, '2026-10-21', 'Dikonfirmasi oleh admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `Email` varchar(160) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','siswa','guru','staff') NOT NULL DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `Email`, `username`, `password`, `role`) VALUES
(15, 'kinarayun30@gmail.com', 'kinaayyu', '$2y$10$w1sxQ/VEiqE999xwgCGA1OWypwwnylDMuEKYkQF2Qrh8JPV01JBJ.', 'siswa'),
(16, 'naylasyifa@gmail.com', 'la.sagara', '$2y$10$ByoY8mngw/sfbpjAkUv6zuHR14ll.tJ6a7lgBqjDfdLjLHJlwZ82O', 'admin'),
(20, 'fahrifdlh@gmail.com', 'fhrfdllh', '$2y$10$nMaj9QU.Z4gwTOD1aZoi6u7MAGIzUGrvDUuw8XER/O6kKZlZYzzdO', 'admin'),
(21, 'affiyahra31@gmail.com', '4rin1', '$2y$10$wSGuII7f8LYmj44e/geL4ufDMwAlu8j3QKhiFw2WC9seX3wns2gQ.', 'siswa'),
(22, 'nazwaumayy@gmail.com', 'nazwaa', '$2y$10$C6F8rV.PCfUvXVr.2vyhf.Ly5j6wi9k4SVPxQkN1dLOv2skDmrppO', 'siswa'),
(23, 'Gugunwiguna@gmail.com', 'Sukanda Wiguna', '$2y$10$Wq3QiJjs32ibDl1hTg61IuedXcFe91/pdSq6K.hQeTaAs7TENcZhS', 'guru'),
(24, 'Ridvirizal@gmail.com', 'Ghazwan', '$2y$10$Op/oddc3CT8qm0WNr2CrUO7HO500Wmhz5LpL/MLXfhDe6SDMLQZrq', 'staff'),
(25, 'JKDAUSwdWAH@GMAIL.COM', 'jxjhszaZA', '$2y$10$Du/SORHATuB2NZMufNKdLOfkAb9JBk4umzssHlUqazzg8X/xfLqnG', 'admin'),
(26, 'GVSDXGSAwGS@gMAIL.COM', 'fhrfdllhJXZHSDHE', '$2y$10$BazbWZV/j4MjNlni2r4OkuZWbkAaXBiWyF323Z6i0VX5WJoG3KxI.', 'siswa'),
(27, 'vgvgggh@gmail.com', 'knj uy', '$2y$10$C1qrGMPqNKGROlv/uDV/iu/d4Jc7bxb11mxvUiq04oSWCmpFhNxdW', 'admin'),
(28, 'hjgbygygt@gmail.com', 'ccf  fv n', '$2y$10$/qA0WOf.I8X4jX0PPx1T6.cZhDeBYWZeaPDaHy/j7r1c1ylwOo.0.', 'guru');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `kategori_id` (`id_kategori`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `user_id` (`id_user`),
  ADD KEY `buku_id` (`id_buku`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `peminjaman_id` (`id_peminjaman`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
