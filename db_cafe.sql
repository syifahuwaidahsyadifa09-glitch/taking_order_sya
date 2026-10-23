-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 01:15 PM
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
-- Database: `db_cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$URbm5qJg52hq3qmEHtrreOk.sWKjRPk6LaS1rjZnW4exG7N.6Ss8u', 'Administrator', '2026-10-23 07:09:56', '2026-10-23 09:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') NOT NULL DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `stok`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'matcha latte', 'Non-Kopi', 12000.00, 'minuman berbasis teh yang dibuat dengan mencampurkan bubuk teh hijau khusus dari Jepang (matcha) dengan air panas, lalu dipadukan dengan susu atau alternatif susu.', 21, 'menu_20261023170705_617efcb5.jpg', 'tersedia', '2026-10-23 10:07:05', '2026-10-23 10:45:24'),
(2, 'Strawberry Matcha', 'Non-Kopi', 15000.00, 'perpaduan matcha dan stroberi', 10, 'menu_20261023171433_b9de7720.jpg', 'tersedia', '2026-10-23 10:14:33', '2026-10-23 10:14:33'),
(3, 'Matcha Cloud', 'Non-Kopi', 22000.00, 'matcha dengan foam susu lembut', 15, 'menu_20261023171800_a0187c0f.jpg', 'tersedia', '2026-10-23 10:18:00', '2026-10-23 10:18:00'),
(4, 'Brown Sugar Matcha', 'Non-Kopi', 20000.00, 'Matcha dengan gula aren', 20, 'menu_20261023171946_6b078ce1.jpg', 'tersedia', '2026-10-23 10:19:46', '2026-10-23 10:19:46'),
(5, 'Choco Milk', 'Non-Kopi', 15000.00, 'Cokelat susu dingin', 11, 'menu_20261023172503_604dc52e.jpg', 'tersedia', '2026-10-23 10:25:03', '2026-10-23 10:25:03'),
(6, 'Strawberry Milk', 'Non-Kopi', 15000.00, 'susu stroberi manis', 22, 'menu_20261023172641_391542e6.jpg', 'tersedia', '2026-10-23 10:26:41', '2026-10-23 10:26:41'),
(7, 'Taro Latte', 'Non-Kopi', 17000.00, 'minuman taro creamy', 12, 'menu_20261023172930_822dfd9a.jpg', 'tersedia', '2026-10-23 10:28:04', '2026-10-23 10:29:30'),
(8, 'Chiken Katsu Bites', 'Makanan', 20000.00, 'Potongan ayam crispy', 15, 'menu_20261023173122_1d635634.jpg', 'tersedia', '2026-10-23 10:31:22', '2026-10-23 10:31:22'),
(9, 'Mini Sausage Roll', 'Makanan', 15000.00, 'Roti panggang dengan keju', 14, 'menu_20261023173255_d12c640e.jpg', 'tersedia', '2026-10-23 10:32:55', '2026-10-23 10:32:55'),
(10, 'Beef Teriyaki Rice Bowl', 'Makanan', 25000.00, 'Nasi dengan daging sapi saus teriyaki', 12, 'menu_20261023173525_af33baa9.jpg', 'tersedia', '2026-10-23 10:35:25', '2026-10-23 10:45:24');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_order` varchar(30) NOT NULL,
  `pelanggan_id` int(10) UNSIGNED NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `total_harga` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `waktu_order` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `kode_order`, `pelanggan_id`, `no_meja`, `total_harga`, `status`, `catatan`, `waktu_order`, `updated_at`) VALUES
(1, 'ORD-20261023174524-349', 1, '01', 37000.00, 'selesai', '', '2026-10-23 17:45:24', '2026-10-23 10:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `menu_id`, `nama_menu`, `harga`, `jumlah`, `subtotal`) VALUES
(1, 1, 1, 'matcha latte', 12000.00, 1, 12000.00),
(2, 1, 10, 'Beef Teriyaki Rice Bowl', 25000.00, 1, 25000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `waktu_datang` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `no_meja`, `no_hp`, `waktu_datang`) VALUES
(1, 'sifa', '01', '', '2026-10-23 17:45:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_admin_username` (`username`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_kategori` (`kategori`),
  ADD KEY `idx_menu_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_order_kode` (`kode_order`),
  ADD KEY `idx_order_pelanggan` (`pelanggan_id`),
  ADD KEY `idx_order_status` (`status`),
  ADD KEY `idx_order_waktu` (`waktu_order`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_detail_order` (`order_id`),
  ADD KEY `idx_detail_menu` (`menu_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pelanggan_nama` (`nama`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_detail_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
