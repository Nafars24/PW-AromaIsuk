-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 03, 2025 at 02:36 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aroma_isuk`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`, `profile_pic`) VALUES
(1, 'admin@gmail.com', '$2y$10$8XpbQrP3gX2A5nVzSDUZX.RiajxrPdNcn7t2ezQtUHeOwgelndWWW', '2025-01-15 08:19:14', NULL),
(2, 'admin', '$2y$10$GSCt01qSWwuNpx9SeJkE0OtMpTBuGTVlfidBlhcp4Ob4idE2cOYpS', '2025-01-15 08:20:18', 'uploads/about-icon-2.png');

-- --------------------------------------------------------

--
-- Table structure for table `hubungi`
--

CREATE TABLE `hubungi` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nomor_hp` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `tanggal_kirim` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `balasan` text,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hubungi`
--

INSERT INTO `hubungi` (`id`, `nama`, `email`, `nomor_hp`, `alamat`, `tanggal_kirim`, `balasan`, `user_id`) VALUES
(62, 'Naufal', 'cznaufal812@gmail.com', '081380108125', 'Udin O', '2025-01-16 12:02:18', 'sadwad', NULL),
(75, 'Asd', 'asdawd@sawdsa.com', '08138', 'Halo', '2025-01-21 12:44:32', NULL, NULL),
(76, 'a', 'admin@gmail.com', '12313', 'asdwas', '2025-02-02 15:45:58', NULL, NULL),
(77, 'a', 'admin@gmail.com', '131231', 'adwadaw', '2025-02-03 04:57:14', NULL, NULL),
(78, 'Fauzan Gondrong', 'admin@example.com', '123', '123', '2025-02-03 08:11:11', 'Hei Admin disini', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'Bangsawan Bliss', 'Kopi ringan dengan sentuhan vanilla dan karamel.', '40000.00', 'image/menu-1.png'),
(2, 'Caramel King', 'Kopi susu dengan sirup karamel yang manis dan lembut.', '42000.00', 'image/menu-3.png'),
(3, 'Pace Hazelnut', 'Kopi dengan rasa kacang hazelnut yang aromatik.', '45000.00', 'image/menu-4.png'),
(4, 'Sunset Gondrong', 'Espresso dengan rasa jeruk dan madu yang menyegarkan.', '37000.00', 'image/menu-5.png'),
(5, 'Tropical Mocha', 'Perpaduan cokelat dan kelapa untuk pengalaman tropis.', '48000.00', 'image/menu-6.png'),
(6, 'Red Vanilla', 'Perpaduan vanilla dan red velvet untuk kehidupan nyata.', '48000.00', 'image/menu-6.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `alamat` text NOT NULL,
  `pengiriman` varchar(50) NOT NULL,
  `kurir` varchar(50) DEFAULT NULL,
  `ongkir` int DEFAULT '0',
  `total_harga` int NOT NULL,
  `status` varchar(50) DEFAULT 'Berhasil',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `alamat`, `pengiriman`, `kurir`, `ongkir`, `total_harga`, `status`, `created_at`) VALUES
(1, 1, 'a', 'dikirim', 'Kurir Toko', 10000, 90000, 'Berhasil', '2025-01-17 01:12:45'),
(2, 1, 'asdawa', 'dikirim', 'Kurir Toko', 10000, 90000, 'Berhasil', '2025-01-17 01:30:36'),
(3, 1, 'Cisaat Sukaraja', 'dikirim', 'Kurir Toko', 10000, 217000, 'Berhasil', '2025-01-17 09:06:10'),
(4, 1, 'Jl. Merdeka No.10, Jakarta', 'JNE', 'REG', 10000, 50000, 'Berhasil', '2025-01-15 03:30:00'),
(5, 2, 'Jl. Sudirman No.20, Bandung', 'SiCepat', 'BEST', 12000, 75000, 'Berhasil', '2025-01-16 07:15:00'),
(6, 3, 'Jl. Ahmad Yani No.5, Surabaya', 'J&T', 'EZ', 8000, 60000, 'Berhasil', '2025-01-17 02:45:00'),
(7, 4, 'Jl. Diponegoro No.15, Yogyakarta', 'Pos Indonesia', 'Kilat', 5000, 90000, 'Berhasil', '2025-01-18 09:20:00'),
(8, 5, 'Jl. Gajah Mada No.30, Semarang', 'Grab Express', 'Same Day', 15000, 55000, 'Berhasil', '2025-01-19 01:00:00'),
(9, 6, 'Jl. Pemuda No.7, Medan', 'Gojek', 'Instant', 12000, 72000, 'Berhasil', '2025-01-20 04:10:00'),
(10, 7, 'Jl. Kartini No.25, Bali', 'Shopee Express', 'Standard', 9000, 81000, 'Berhasil', '2025-01-21 08:50:00'),
(11, 1, 'asdw', 'dikirim', 'Kurir Toko', 10000, 170000, 'Berhasil', '2025-01-18 01:57:23'),
(12, 1, 'awsadw', 'dikirim', 'Kurir Toko', 10000, 50000, 'Berhasil', '2025-01-20 00:03:30'),
(13, 1, 'asdawda', 'dikirim', 'JNE', 15000, 89000, 'Berhasil', '2025-01-20 00:04:27'),
(14, 1, 'asdwasdw', 'dikirim', 'Kurir Toko', 10000, 130000, 'Berhasil', '2025-01-22 03:36:31'),
(15, 1, 'Sukabumi', 'dikirim', 'Kurir Toko', 10000, 65000, 'Berhasil', '2025-02-03 06:09:54'),
(16, 1, 'asdwad', 'dikirim', 'Kurir Toko', 10000, 170000, 'Berhasil', '2025-02-03 07:32:43'),
(17, 1, 'Sukabumi', 'dikirim', 'Gosend', 15000, 255000, 'Berhasil', '2025-02-03 07:33:47'),
(18, 1, 'aw', 'dikirim', 'Kurir Toko', 10000, 10000, 'Berhasil', '2025-02-03 07:41:10'),
(19, 1, 'Cisaat', 'dikirim', 'Kurir Toko', 10000, 290000, 'Berhasil', '2025-02-03 08:00:14'),
(20, 1, 'Sukabumi', 'dikirim', 'Kurir Toko', 10000, 194000, 'Berhasil', '2025-02-03 08:17:01'),
(21, 1, 'as', 'dikirim', 'Kurir Toko', 10000, 130000, 'Berhasil', '2025-02-03 14:16:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `profile_image`) VALUES
(1, 'Zyfars', '1@gmail.com', '$2y$10$ZFNqTf4PbSbyPKcVuOO/2eQtfNnEZWfQbgNCcyV7mZkwDLUzuDgQS', '2025-01-13 12:28:30', 'hq720.jpg'),
(2, 'Ali', 'ali@gmail.com', '$2y$10$CjjHO6FejicIs3keit/5JeIv0zRRkj/8KrYgZetPFkT3OnVqNFim2', '2025-01-17 09:31:53', NULL),
(3, 'Naufal', 'naufal123@gmai.com', '$2y$10$cHFx/ZZLjACxtSS2FsBdQuh8B4HybWMJVywg2b7ItivLjMjxrBOvO', '2025-02-03 06:11:05', 'home-img-3.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `hubungi`
--
ALTER TABLE `hubungi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hubungi`
--
ALTER TABLE `hubungi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
