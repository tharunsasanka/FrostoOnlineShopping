-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 11, 2025 at 01:11 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u931839000_stosrho`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image_path`) VALUES
(1, 'Clothings', 'categories/clothing1.png'),
(2, 'Foods', 'categories/lays.png'),
(3, 'Study Materials', 'categories/stud.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `image_path`, `stock`) VALUES
(6, 'Rose Flowers', 'Clothings', 'Add a touch of elegance to any occasion with our fresh, hand-picked roses! Whether you\'re celebrating love, appreciation, or beauty, our premium roses make the perfect gift. Choose from a variety of stunning colors and arrangements to brighten someone\'s day. Order now and let your emotions bloom!', 4500.00, 'flower5_1754729632.webp', 10),
(7, 'Key tag', 'Foods', 'Keep your keys organized and easily identifiable with our durable key tags. Made from high-quality materials, these tags are perfect for labeling keys at home, work, or even as a promotional item. Available in various colors and designs, you can add a personalized touch or simply stay organized with ease. Lightweight, practical, and built to last, these key tags are the perfect accessory for everyday use.', 200.00, 'ktag_1754729908.png', 100),
(8, 'Note Book', 'Study Materials', 'Capture your thoughts, ideas, and creativity with our premium notebook. Featuring smooth, high-quality pages and a sturdy cover, this notebook is perfect for writing, sketching, or journaling. Whether you\'re a student, professional, or artist, its sleek design and practical layout make it an essential companion for work, study, or personal reflection. Available in various sizes and colors, it’s the perfect space to organize your thoughts and bring your ideas to life.', 300.00, 'noteb_1754794832.png', 50),
(9, 'Calculator fx100', 'Study Materials', 'Fx100 Scientific Calculator', 1450.00, 'calc1_1754896800.png', 20),
(10, 'Calculator mx100', 'Study Materials', 'mx100 Scientific Calculator', 2000.00, 'calc2_1754896860.png', 15),
(11, 'Snackers', 'Foods', 'Snackers packs', 50.00, 'snack_1754896918.png', 100);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `verification_token` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `verification_token`, `is_verified`) VALUES
(3, 'admin', 'cpcreations190@gmail.com', '$2y$10$uVq4eMO56o2JlZSIujyeJOBvUhfQNWlyzdtr5ad7CDmWso.sqO1mW', 'adminuser', 'admin', 'e385d9d4e9189e1ed0d568149d7b20a350ea9e555b828d526a91aaa8c34915a8', 1),
(10, 'padmal', 'chathurapadmal3@gmail.com', '$2y$10$PfqhVXjdS/RUfLURTX0QeORLJAn2Brks8q2CBnj.YLbstuAaLgGt.', 'chathura', 'user', NULL, 1),
(11, 'Marsh', 'aselmarsh06@gmail.com', '$2y$10$FeR8DDoZiAno8yAxUS3Qn.BuAn7RfNHOmd6gz2fFv8xcmCUZA9Xpu', 'Marsh Zenin', 'user', NULL, 1),
(12, 'danuja', 'thashmikadanuja3@gmail.com', '$2y$10$XTLpJNSDEp.3lPExMInc7Oe.jOnrkjq.lZGNe/1RPwQWnU.48/hEy', 'Danuja Thashmika', 'user', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
