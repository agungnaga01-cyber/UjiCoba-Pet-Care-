-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Okt 2026 pada 08.00
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penitipan_peliharaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hewan`
--

CREATE TABLE `hewan` (
  `id_hewan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `ras` varchar(50) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hewan`
--

INSERT INTO `hewan` (`id_hewan`, `id_user`, `nama_hewan`, `jenis`, `ras`, `umur`, `jenis_kelamin`, `keterangan`) VALUES
(3, 3, 'nuni', 'Kelinci', 'Holland Lop', 4, 'Jantan', 'jangan terlalu di kasih banyak makan'),
(4, 2, 'Gio', 'Anjing', 'German Shepherd', 7, 'Jantan', 'Tolong biarkan di kandang yang tidak ramai atau ribut'),
(5, 3, 'Kiwi', 'Burung', 'Lovebird', 2, 'Jantan', 'Burung lovebird berwarna cerah'),
(6, 3, 'Nemo', 'Hewan Air', 'Ikan Cupang', 1, 'Jantan', 'Ikan cupang dalam akuarium'),
(7, 2, 'Iggy', 'Reptil', 'Iguana', 3, 'Betina', 'Iguana peliharaan dengan habitat khusus'),
(8, 2, 'Piko', 'Hamster', 'Syrian', 1, 'Jantan', 'Hamster peliharaan yang aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kandang`
--

CREATE TABLE `kandang` (
  `id_kandang` int(11) NOT NULL,
  `nomor_kandang` varchar(20) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `ukuran` varchar(50) DEFAULT NULL,
  `harga_per_hari` decimal(12,2) NOT NULL,
  `status` enum('tersedia','terisi','maintenance') NOT NULL DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kandang`
--

INSERT INTO `kandang` (`id_kandang`, `nomor_kandang`, `jenis`, `ukuran`, `harga_per_hari`, `status`) VALUES
(4, 'ANJ-01', 'Anjing', 'Medium', 60000.00, 'tersedia'),
(5, 'ANJ-02', 'Anjing', 'Large', 70000.00, 'tersedia'),
(6, 'KUC-03', 'Kucing', 'Medium', 35000.00, 'tersedia'),
(7, 'KUC-04', 'Kucing', 'Large', 50000.00, 'tersedia'),
(8, 'BRG-01', 'Burung', 'Sangkar besar', 25000.00, 'tersedia'),
(9, 'BRG-02', 'Burung', 'Aviary', 40000.00, 'tersedia'),
(10, 'RPT-01', 'Reptil', 'Terrarium kecil', 30000.00, 'tersedia'),
(11, 'RPT-02', 'Reptil', 'Terrarium besar', 45000.00, 'tersedia'),
(12, 'AIR-01', 'Hewan Air', 'Akuarium 20 liter', 25000.00, 'tersedia'),
(13, 'AIR-02', 'Hewan Air', 'Akuarium 50 liter', 40000.00, 'tersedia'),
(14, 'KEL-01', 'Kelinci', 'Medium', 30000.00, 'tersedia'),
(15, 'KEL-02', 'Kelinci', 'Large', 45000.00, 'tersedia'),
(16, 'HAM-01', 'Hamster', 'Habitat kecil', 20000.00, 'tersedia'),
(17, 'HAM-02', 'Hamster', 'Habitat besar', 30000.00, 'tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(11) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `harga`, `deskripsi`, `status`) VALUES
(4, 'Grooming Anjing', 65000.00, 'Mandi dan perawatan bulu anjing', 'aktif'),
(5, 'Potong Kuku Anjing', 20000.00, 'Perawatan kuku anjing', 'aktif'),
(6, 'Grooming Kucing', 50000.00, 'Mandi dan perawatan bulu kucing', 'aktif'),
(7, 'Mandi Kucing', 35000.00, 'Memandikan kucing dengan perlengkapan sesuai', 'aktif'),
(8, 'Mandi Burung', 20000.00, 'Perawatan kebersihan burung', 'aktif'),
(9, 'Pembersihan Sangkar Burung', 25000.00, 'Membersihkan sangkar dan tempat makan burung', 'aktif'),
(10, 'Pembersihan Terrarium Reptil', 35000.00, 'Membersihkan terrarium reptil', 'aktif'),
(11, 'Penataan Habitat Reptil', 30000.00, 'Menata habitat sesuai kebutuhan jenis reptil', 'aktif'),
(12, 'Pembersihan Akuarium', 40000.00, 'Membersihkan akuarium dan dekorasi', 'aktif'),
(13, 'Pengecekan Kondisi Air', 25000.00, 'Memeriksa kondisi dasar air akuarium', 'aktif'),
(14, 'Sisir Bulu Kelinci', 25000.00, 'Menyisir bulu kelinci dengan lembut', 'aktif'),
(15, 'Pembersihan Kandang Kelinci', 30000.00, 'Membersihkan kandang dan tempat makan kelinci', 'aktif'),
(16, 'Pembersihan Habitat Hamster', 20000.00, 'Membersihkan habitat hamster', 'aktif'),
(17, 'Aktivitas Hamster', 15000.00, 'Penyediaan aktivitas dan pengayaan lingkungan', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_bayar` int(11) NOT NULL,
  `id_titip` int(11) NOT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `metode` enum('cash','transfer','qris') NOT NULL,
  `status` enum('pending','lunas','gagal') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penitipan`
--

CREATE TABLE `penitipan` (
  `id_titip` int(11) NOT NULL,
  `id_hewan` int(11) NOT NULL,
  `id_kandang` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `lama_menginap` int(11) NOT NULL,
  `total_biaya` decimal(12,2) NOT NULL,
  `status` enum('menunggu','disetujui','berlangsung','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`, `no_telp`, `alamat`) VALUES
(1, 'Admin PetCare', 'admin', '$2y$10$odT6gGJ4l7RBR1Jgdv.UCO4.9ByjTL8AHtLuNUB/9hSmcWEOZC4HC', 'admin', '081234567890', 'Banjarmasin'),
(2, 'Budi Santoso', 'budi', 'budi123', 'user', '081234567891', 'Banjarmasin'),
(3, 'Andi Pratama', 'andi', 'andi123', 'user', '081234567892', 'Banjarmasin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `hewan`
--
ALTER TABLE `hewan`
  ADD PRIMARY KEY (`id_hewan`),
  ADD KEY `fk_hewan_user` (`id_user`);

--
-- Indeks untuk tabel `kandang`
--
ALTER TABLE `kandang`
  ADD PRIMARY KEY (`id_kandang`),
  ADD UNIQUE KEY `nomor_kandang` (`nomor_kandang`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_bayar`),
  ADD UNIQUE KEY `id_titip` (`id_titip`);

--
-- Indeks untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  ADD PRIMARY KEY (`id_titip`),
  ADD KEY `fk_penitipan_hewan` (`id_hewan`),
  ADD KEY `fk_penitipan_kandang` (`id_kandang`),
  ADD KEY `fk_penitipan_layanan` (`id_layanan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `hewan`
--
ALTER TABLE `hewan`
  MODIFY `id_hewan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kandang`
--
ALTER TABLE `kandang`
  MODIFY `id_kandang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_bayar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  MODIFY `id_titip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hewan`
--
ALTER TABLE `hewan`
  ADD CONSTRAINT `fk_hewan_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_penitipan` FOREIGN KEY (`id_titip`) REFERENCES `penitipan` (`id_titip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  ADD CONSTRAINT `fk_penitipan_hewan` FOREIGN KEY (`id_hewan`) REFERENCES `hewan` (`id_hewan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penitipan_kandang` FOREIGN KEY (`id_kandang`) REFERENCES `kandang` (`id_kandang`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penitipan_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
