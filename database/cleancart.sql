-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 06:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cleancart`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `first_name`, `last_name`, `email`, `phone`, `message`, `created_at`) VALUES
(2, 'Almas', 'Faiz', 'almas@gmail.com', '0302-7691575', 'Hi, can you please share the prices and availability of your kitchen and bathroom cleaning products', '2025-11-15 13:37:35'),
(3, 'Zimran', 'Sohail', 'zimran@gmail.com', '0302-7857575', 'I am interested in buying your cleaning products in bulk. Do you offer any discounts for large orders?', '2025-11-15 13:50:40'),
(4, 'Suleman', 'Zubair', 'suleman@gmail.com', '0301-7757577', 'I am looking to purchase cleaning products in bulk for my store. Kindly provide your product catalog, wholesale prices, and delivery information.', '2025-11-17 19:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `handwash_products`
--

CREATE TABLE `handwash_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `price` int(11) NOT NULL,
  `ml` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `handwash_products`
--

INSERT INTO `handwash_products` (`id`, `product_name`, `product_description`, `price`, `ml`, `image`, `created_at`) VALUES
(1, 'Anti-Bacterial Handwash', 'Provides strong germ protection with neem and aloe extracts. Suitable for daily use.', 210, '200 ml', 'product_1763217419_6918900be2cfc.png', '2025-11-15 14:36:59'),
(2, 'Handwash – Gentle Care', 'Mild formula with moisturizing agents; suitable for sensitive skin.', 250, '200 ml', 'product_1763232430_6918caaeb702b.png', '2025-11-15 18:47:10'),
(3, 'Handwash – Anti-Bacterial Shield', 'Kills 99.9% germs; ideal for hygiene protection.', 150, '70 ml', 'product_1763232474_6918cadabdd2f.png', '2025-11-15 18:47:54'),
(4, 'Handwash – Aloe Soft Touch', 'Infused with aloe vera for smooth and hydrated hands.', 280, '150 ml', 'product_1763232508_6918cafc8463f.png', '2025-11-15 18:48:28'),
(5, 'Handwash – Lemon Fresh', 'Refreshing lemon scent; removes strong odors and oils.', 340, '500 ml', 'product_1763232558_6918cb2e8d214.png', '2025-11-15 18:49:18'),
(6, 'Handwash – Rose Bloom', 'Floral fragrance with gentle cleansing formula.', 270, '300 ml', 'product_1763232597_6918cb55b0bc5.png', '2025-11-15 18:49:57'),
(7, 'Handwash – Tea Tree Protect', 'Tea tree extract helps fight bacteria and keeps hands fresh.', 300, '200 ml', 'product_1763232633_6918cb79137a1.png', '2025-11-15 18:50:33'),
(9, 'Dettol Cool Liquid Hand Wash', 'It’s an antibacterial hand wash from Dettol with a cool mint / menthol scent.', 210, '250 ml', 'product_1763494673_691ccb1187d0c.png', '2025-11-18 19:37:53'),
(10, 'Dettol Hand Wash Pomegranate', 'Keep your hands fresh and protected with Dettol Pomegranate Hand Wash & enriched with a fruity scent and trusted germ-kill formula.', 395, '250 ml', 'product_1764697809_692f26d1628fe.png', '2025-12-02 17:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `home_products`
--

CREATE TABLE `home_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `price` int(11) NOT NULL,
  `ml` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_products`
--

INSERT INTO `home_products` (`id`, `product_name`, `product_description`, `price`, `ml`, `image`, `created_at`) VALUES
(1, 'Floor Cleaner', 'A powerful liquid floor cleaner that removes tough stains and leaves a long-lasting lemon fragrance. Suitable for tiles, marble, and wooden floors.', 210, '100 ml', 'product_1763217189_69188f255f051.png', '2025-11-15 14:33:09'),
(2, 'Multi-Surface Cleaner', 'Powerful all-purpose cleaner suitable for tables, shelves, wood, plastic, and metal surfaces.', 350, '100 ml', 'product_1763229721_6918c01943129.png', '2025-11-15 18:02:01'),
(3, 'Floor Cleaner', 'Liquid floor cleaner for all types of flooring. Removes dust, grease, and stains while adding a long-lasting rose fragrance.', 280, '150 ml', 'product_1763229765_6918c045b58a2.png', '2025-11-15 18:02:45'),
(4, 'Glass Cleaner', 'Streak-free formula ideal for windows, mirrors, and glass tables. Provides clear shine with fast drying.', 250, '120 ml', 'product_1763229826_6918c0829c48a.png', '2025-11-15 18:03:46'),
(5, 'Furniture Polish', 'Protects wooden furniture from dust and scratches. Nourishes wood and restores its natural shine.', 450, '250 ml', 'product_1763229869_6918c0ad686a1.png', '2025-11-15 18:04:29'),
(6, 'Carpet & Sofa Cleaner', 'Deep-cleaning foam that removes stains, dust, and odor from carpets and sofas. Safe for fabrics.', 500, '350 ml', 'product_1763229915_6918c0db6b17a.png', '2025-11-15 18:05:15'),
(8, 'Liquid Laundry Detergent', 'High-efficiency laundry liquid designed to remove tough stains while protecting fabric color.', 390, '300 ml', 'product_1763230136_6918c1b8be619.png', '2025-11-15 18:08:56'),
(9, 'CleanIt – Floor Disinfectant and Cleaner', 'CleanIt- Floor Disinfectant & Cleaner has built-in dirt repelling technology to leave a protective coating after cleaning, preventing further staining after use.', 750, '500 ml', 'product_1764698802_692f2ab21d939.png', '2025-12-02 18:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_products`
--

CREATE TABLE `kitchen_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `price` int(11) NOT NULL,
  `ml` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kitchen_products`
--

INSERT INTO `kitchen_products` (`id`, `product_name`, `product_description`, `price`, `ml`, `image`, `created_at`) VALUES
(2, 'Kitchen Degreaser Spray', 'A heavy-duty spray that quickly dissolves oil, grease, and burnt food from stoves, countertops, and exhaust hoods.', 380, '150 ml', 'product_1763221179_69189ebba5d72.png', '2025-11-15 15:39:39'),
(3, 'Dishwashing Liquid', 'Removes grease instantly with rich foam. Gentle on hands and leaves dishes sparkling clean.', 240, '120 ml', 'product_1763230633_6918c3a950979.png', '2025-11-15 18:17:13'),
(4, 'Kitchen Degreaser', 'Heavy-duty spray that dissolves stubborn grease, oil stains, and burnt residue from stoves and counters.', 380, '180 ml', 'product_1763230662_6918c3c616c11.png', '2025-11-15 18:17:42'),
(5, 'Stainless Steel Cleane', 'Cleans fridges, ovens, sinks, and steel cookware. Removes fingerprints and restores metallic shine.', 450, '250 ml', 'product_1763230699_6918c3eb1931e.png', '2025-11-15 18:18:19'),
(6, 'Oven & Grill Cleaner', 'Strong formula that removes burnt grease and grime from ovens, microwaves, grills, and BBQ racks.', 550, '330 ml', 'product_1763230736_6918c410e0fa4.png', '2025-11-15 18:18:56'),
(7, 'Dishwashing Bar', 'Solid dishwashing bar with extra lemon power for tough grease removal.', 120, '50 ml', 'product_1763230782_6918c43eb17c2.png', '2025-11-15 18:19:42'),
(8, 'Sink & Drain Cleaner', 'Powerful drain unblocker that clears food waste, grease buildup, and bad odors from kitchen sinks.', 300, '150 ml', 'product_1763230811_6918c45b4ad11.png', '2025-11-15 18:20:11'),
(10, 'Kitchen Surface Sanitizer', 'Food-safe sanitizer spray for countertops, cutting boards, and preparation areas. Kills 99.9% bacteria.', 380, '350 ml', 'product_1763230967_6918c4f73ff51.png', '2025-11-15 18:22:47'),
(11, 'Multipurpose Kitchen Cleaner Spray', 'A powerful multipurpose kitchen cleaner designed to remove oil, grease, stains, and food spills from countertops, stoves, cabinets, and tiles. Safe for daily use and leaves a fresh, clean fragrance.', 370, '150 ml', 'product_1763310431_6919fb5fba811.png', '2025-11-16 16:27:11'),
(12, 'Nova Liquid Dishwash', 'Nova Dishwash Clean dishes and utensils are vital in maintaining the hygiene of the family. Liquid starts to cut down the dirt particles even before you begin to wipe & Eliminates all kinds of fingerprints, smudges and grime on the surface of the dishes.', 275, '400 ml', 'product_1764698947_692f2b439e770.png', '2025-12-02 18:09:07'),
(13, 'Lemon Fresh Dishwashing Liquid', 'Thick formula that cuts grease quickly and gives sparkling clean dishes. Gentle on hands, strong on dirt.', 280, '750 ml', 'product_1764873452_6931d4ec4b320.png', '2025-12-04 18:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`id`, `email`) VALUES
(3, 'sabestean@gmail.com'),
(4, 'suleman@gmail.com'),
(5, 'zimran@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `delivery_address` text NOT NULL,
  `cart_items` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_name`, `phone`, `delivery_address`, `cart_items`, `subtotal`, `shipping_fee`, `tax`, `total`, `order_date`, `status`) VALUES
(1, 'Sabestean', '03027847575', 'C Block Model Town, Lahore', '[{\"name\":\"Kitchen Surface Sanitizer\",\"category\":\"Kitchen Product\",\"price\":370,\"quantity\":1,\"subtotal\":370},{\"name\":\"Mold & Mildew Remover\",\"category\":\"Washroom Product\",\"price\":400,\"quantity\":2,\"subtotal\":800},{\"name\":\"Handwash \\u2013 Gentle Care\",\"category\":\"Hand Wash Product\",\"price\":250,\"quantity\":3,\"subtotal\":750}]', 1920.00, 200.00, 192.00, 2312.00, '2025-11-17 07:37:58', 'Completed'),
(3, 'Zimran', '03017757577', 'Gulberg 3 near hafiz center,Lahore.', '[{\"name\":\"Sink & Drain Cleaner\",\"category\":\"Kitchen Product\",\"price\":300,\"quantity\":1,\"subtotal\":300},{\"name\":\"Mold & Mildew Remover\",\"category\":\"Washroom Product\",\"price\":400,\"quantity\":2,\"subtotal\":800}]', 1100.00, 200.00, 110.00, 1410.00, '2025-11-17 07:59:57', 'Processing'),
(7, 'Sabestean', '0303-7822570', 'DHA phase 3 Lahore Cantt.', '[{\"name\":\"Tile & Floor Cleaner\",\"category\":\"Washroom Product\",\"price\":\"350\",\"quantity\":3,\"ml\":\"200 ml\",\"subtotal\":1050},{\"name\":\"Dettol Hand Wash Pomegranate\",\"category\":\"Hand Wash Product\",\"price\":\"395\",\"quantity\":2,\"ml\":\"250 ml\",\"subtotal\":790},{\"name\":\"Floor Cleaner\",\"category\":\"Home Product\",\"price\":\"280\",\"quantity\":1,\"ml\":\"150 ml\",\"subtotal\":280}]', 2120.00, 200.00, 212.00, 2532.00, '2025-12-03 13:58:09', 'Completed'),
(8, 'Sagar', '0302-7150545', 'DHA phase 7 Lahore Cantt.', '[{\"name\":\"Dettol Cool Liquid Hand Wash\",\"category\":\"Hand Wash Product\",\"price\":\"210\",\"quantity\":4,\"ml\":\"250 ml\",\"subtotal\":840}]', 840.00, 200.00, 84.00, 1124.00, '2025-12-04 08:28:47', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin1234', 'admin'),
(3, 'sabestean', 'sabestean@gmail.com', 'sabestean17', 'user'),
(4, 'suleman', 'suleman@gmail.com', 'suleman123', 'user'),
(5, 'uneeb', 'uneeb@gmail.com', 'uneeb123', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `washroom_products`
--

CREATE TABLE `washroom_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `price` int(11) NOT NULL,
  `ml` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `washroom_products`
--

INSERT INTO `washroom_products` (`id`, `product_name`, `product_description`, `price`, `ml`, `image`, `created_at`) VALUES
(1, 'Toilet Cleaner', 'Thick liquid toilet cleaner that removes stains, kills germs, and leaves a fresh fragrance.', 557, '750 ml', 'product_1763217533_6918907d852c7.png', '2025-11-15 14:38:53'),
(2, 'Tile & Floor Cleaner', 'Removes tough stains, soap scum, and hard-water marks from tiles and floors. Leaves a glossy finish.', 350, '200 ml', 'product_1763231533_6918c72d791a1.png', '2025-11-15 18:32:13'),
(3, 'Toilet Bowl Cleaner', 'Thick formula that clings to the bowl, removes limescale, kills 99.9% germs, and leaves a fresh scent.', 280, '180 ml', 'product_1763231580_6918c75c9277a.png', '2025-11-15 18:33:00'),
(4, 'Glass & Mirror Cleaner', 'Streak-free cleaning for mirrors, shower glass, and chrome fixtures. Fast drying.', 170, NULL, 'product_1763231631_6918c78f24226.png', '2025-11-15 18:33:51'),
(5, 'Bathroom Surface Spray', 'All-purpose disinfectant spray for sinks, countertops, taps, and shower areas.', 300, '100 ml', 'product_1763231678_6918c7be6c5b9.png', '2025-11-15 18:34:38'),
(6, 'Drain Cleaner', 'Clears clogged drains and dissolves hair, grease, and soap buildup.', 320, '150 ml', 'product_1763231726_6918c7ee97368.png', '2025-11-15 18:35:26'),
(7, 'Mold & Mildew Remover', 'Fast-acting formula that removes mold spots from tiles, grout, and damp corners.', 400, '250 ml', 'product_1763231804_6918c83c4fa62.png', '2025-11-15 18:36:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `handwash_products`
--
ALTER TABLE `handwash_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_products`
--
ALTER TABLE `home_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kitchen_products`
--
ALTER TABLE `kitchen_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_order_date` (`order_date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `washroom_products`
--
ALTER TABLE `washroom_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `handwash_products`
--
ALTER TABLE `handwash_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `home_products`
--
ALTER TABLE `home_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kitchen_products`
--
ALTER TABLE `kitchen_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `washroom_products`
--
ALTER TABLE `washroom_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
