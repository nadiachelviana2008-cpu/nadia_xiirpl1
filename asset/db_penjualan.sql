-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Sep 2026 pada 08.54
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjualan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Level` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`AdminID`, `Username`, `Password`, `Level`) VALUES
(1, 'nadia', 'nadia123', 'admin'),
(2, 'kasir', '12345', 'kasir');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detailpenjualan`
--

CREATE TABLE `detailpenjualan` (
  `DetailID` int(11) NOT NULL,
  `PenjualanID` int(11) DEFAULT NULL,
  `ProdukID` int(11) DEFAULT NULL,
  `JumlahProduk` int(11) DEFAULT NULL,
  `Subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `detailpenjualan`
--

INSERT INTO `detailpenjualan` (`DetailID`, `PenjualanID`, `ProdukID`, `JumlahProduk`, `Subtotal`) VALUES
(1, 1, 6, 4, 28000.00),
(2, 2, 2, 3, 9000.00),
(3, 2, 1, 2, 10000.00),
(4, 3, 6, 2, 14000.00),
(5, 3, 2, 3, 9000.00),
(7, 5, 2, 4, 12000.00),
(8, 5, 1, 2, 10000.00),
(9, 6, 1, 2, 10000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `PelangganID` int(11) NOT NULL,
  `NamaPelanggan` varchar(255) DEFAULT NULL,
  `Alamat` text DEFAULT NULL,
  `NomorTelepon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`PelangganID`, `NamaPelanggan`, `Alamat`, `NomorTelepon`) VALUES
(1, 'syifa', 'campurejo', '08664356790'),
(5, 'Nadia', 'Semarang', '0835264858'),
(6, 'Thalita', 'Medini', '0869462674');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `PenjualanID` int(11) NOT NULL,
  `TanggalPenjualan` date DEFAULT NULL,
  `TotalHarga` decimal(10,2) DEFAULT NULL,
  `PelangganID` int(11) DEFAULT NULL,
  `UangTunai` int(11) DEFAULT NULL,
  `Kembalian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`PenjualanID`, `TanggalPenjualan`, `TotalHarga`, `PelangganID`, `UangTunai`, `Kembalian`) VALUES
(1, '2026-09-16', 28000.00, 1, NULL, NULL),
(2, '2026-09-16', 19000.00, 1, NULL, NULL),
(3, '2026-09-23', 23000.00, 1, NULL, NULL),
(5, '2026-09-23', 22000.00, 5, 50000, 28000),
(6, '2026-09-23', 10000.00, 6, 10000, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `ProdukID` int(11) NOT NULL,
  `NamaProduk` varchar(255) DEFAULT NULL,
  `Harga` decimal(10,2) DEFAULT NULL,
  `Stok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`ProdukID`, `NamaProduk`, `Harga`, `Stok`) VALUES
(1, 'Buku Tulis', 5000.00, 44),
(2, 'Pulpen', 3000.00, 86),
(3, 'Pensil', 2500.00, 80),
(4, 'Penghapus', 2000.00, 60),
(5, 'Penggaris', 4000.00, 40),
(6, 'Buku Gambar', 7000.00, 24),
(7, 'Spidol', 6000.00, 25),
(8, 'Tas Sekolah', 75000.00, 15),
(9, 'Corrector', 10000.00, 50);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indeks untuk tabel `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  ADD PRIMARY KEY (`DetailID`),
  ADD KEY `PenjualanID` (`PenjualanID`),
  ADD KEY `ProdukID` (`ProdukID`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`PelangganID`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`PenjualanID`),
  ADD KEY `PelangganID` (`PelangganID`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`ProdukID`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  MODIFY `DetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `PelangganID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `PenjualanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `ProdukID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detailpenjualan`
--
ALTER TABLE `detailpenjualan`
  ADD CONSTRAINT `detailpenjualan_ibfk_1` FOREIGN KEY (`PenjualanID`) REFERENCES `penjualan` (`PenjualanID`),
  ADD CONSTRAINT `detailpenjualan_ibfk_2` FOREIGN KEY (`ProdukID`) REFERENCES `produk` (`ProdukID`);

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`PelangganID`) REFERENCES `pelanggan` (`PelangganID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
