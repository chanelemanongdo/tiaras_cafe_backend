-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2026 at 04:19 AM
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
-- Database: `imdf_finals`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@tiarascafe.com', '$2y$10$Hv9gqR9L2qvLDxt7IJBYPOXPhY7m5bdNhwHynuie8FYKuj88yrbDG', '2026-11-14 08:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(10) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_desc`) VALUES
(1, 'Coffee', 'Enjoy the rich aroma and bold flavors of freshly brewed coffee, perfect for your daily boost or a relaxing break.'),
(2, 'Non-Coffee Blends', 'A delightful mix of creamy, flavorful, and refreshing blends made for those who want something different from coffee.'),
(3, 'Non-Coffee', 'Treat yourself to delicious, milk-based beverages with smooth, creamy textures and irresistible flavors in every sip.'),
(4, 'Fruit Soda', 'A fizzy and refreshing combination of fruity flavors and sparkling soda, perfect for cooling down and satisfying your thirst.'),
(5, 'Snacks', 'Enjoy tasty and satisfying bites, perfect for light cravings, quick breaks, or pairing with your favorite drink.'),
(6, 'Pasta', 'Savor delicious pasta dishes prepared with flavorful sauces and satisfying ingredients for a comforting meal.'),
(7, 'Noodles', 'Warm, flavorful, and satisfying noodle dishes made to comfort your cravings and keep you coming back for more.');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_item_id` int(10) NOT NULL,
  `menu_variant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_item_id`, `menu_variant_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 3),
(15, 4),
(16, 3),
(16, 4),
(17, 3),
(17, 4),
(18, 3),
(18, 4),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 6),
(24, 7),
(25, 6),
(25, 7),
(26, 6),
(26, 7),
(27, 6),
(27, 7),
(28, 8),
(29, 10),
(30, 10),
(31, 10),
(32, 10),
(33, 9),
(34, 9),
(35, 9);

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `menu_item_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `Item_name` varchar(100) NOT NULL,
  `item_desc` text NOT NULL,
  `is_available` enum('available','unavailable') NOT NULL DEFAULT 'available',
  `image_url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`menu_item_id`, `category_id`, `Item_name`, `item_desc`, `is_available`, `image_url`) VALUES
(1, 1, 'Americano Caramel', 'A smooth coffee with a sweet caramel flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Americano Caramel.jpg'),
(2, 1, 'Americano Hazelnut', 'A rich coffee with a nutty flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Americano Hazelnut.jpg'),
(3, 1, 'Americano', 'A classic coffee with a bold, rich taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Americano.jpg'),
(4, 1, 'Americano Vanilla', 'A smooth coffee with a sweet vanilla flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Americano Vanilla.jpg'),
(5, 1, 'Ube Coffee', 'A unique coffee with a creamy, sweet ube flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Ube Coffee.jpg'),
(6, 1, 'Caramel Coffee', 'A creamy coffee with a rich caramel taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Caramel Coffee.jpg'),
(7, 1, 'Salted Caramel', 'A balanced blend of sweet and salty flavors with coffee.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Salted Caramel Coffee.jpg'),
(8, 1, 'Vanilla Coffee', 'A creamy coffee with a smooth vanilla taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Vanilla Coffee.jpg'),
(9, 1, 'Mocha Coffee', 'A rich coffee with a sweet chocolate flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Mocha Coffee.jpg'),
(10, 1, 'Barista Drink (coffee latte)', 'A smooth, creamy coffee perfect for everyday enjoyment.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Barista Drink.jpg'),
(11, 1, 'Dirty Matcha', 'A flavorful blend of coffee and matcha with a creamy taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Dirty Matcha.jpg'),
(12, 1, 'Spanish Latte\r\n', 'A rich and creamy coffee with a sweet, milky taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Spanish Latte.jpg'),
(13, 1, 'Choco Hazelnut Coffee', 'A rich and creamy coffee with a smooth chocolate taste and a nutty hazelnut flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Choco Hazelnut Coffee.jpg'),
(14, 2, 'Strawberry Mocha', 'A creamy drink with sweet strawberry and chocolate flavors. ', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Strawberry Mocha.jpg'),
(15, 2, 'Strawberry Matcha', 'A refreshing blend of sweet strawberry and earthy matcha flavors.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Strawberry Matcha.jpg'),
(16, 2, 'Matcha Caramel', 'A creamy matcha drink with a sweet caramel taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Matcha Caramel.jpg'),
(17, 2, 'Blueberry Matcha', 'A creamy matcha drink with a sweet blueberry flavor.\r\n', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Blueberry Matcha.jpg'),
(18, 3, 'Choco Hazelnut Milk', 'A creamy chocolate drink with a rich hazelnut flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Choco Hazelnut Milk.jpg'),
(19, 3, 'Ube Milk', 'A smooth, creamy drink with a sweet ube flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Ube Milk.jpg'),
(20, 3, 'Mocha Milk', 'A creamy milk drink with a rich chocolate flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Mocha Milk.jpg'),
(21, 3, 'Matcha Milk', 'A creamy drink with the distinct taste of matcha.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Matcha Milk.jpg'),
(22, 3, 'Blueberry Milk', 'A sweet and creamy drink with a fruity blueberry flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Blueberry Milk.jpg'),
(23, 3, 'Strawberry Milk', 'A creamy milk drink with a sweet strawberry taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Strawberry Milk.jpg'),
(24, 4, 'Lychee', 'A bubbly, refreshing drink with a sweet and fruity taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Lychee.jpg'),
(25, 4, 'Blueberry', 'A fizzy drink with a sweet blueberry flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Blueberry.jpg'),
(26, 4, 'Strawberry', 'A refreshing soda with a sweet strawberry taste.', 'available', ''),
(27, 4, 'Green Apple', 'A bubbly drink with a crisp and tangy green apple flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Green Apple.jpg'),
(28, 5, 'Pork Siomai', 'Savory pork dumplings, perfect for a quick snack.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Pork Siomai.jpg'),
(29, 5, 'Japanese Siomai', 'Delicious Japanese-style dumplings with a savory taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Japanese Siomai.jpg\r\n'),
(30, 5, 'Gyoza ', 'Japanese-style dumplings with a satisfying savory flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Gyoza.jpg'),
(31, 6, 'Carbonara', 'A creamy and savory pasta dish perfect for a satisfying meal.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Carbonara.jpg'),
(32, 6, 'Spagetti ', 'A classic pasta dish with a delicious, savory flavor.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Spagetti.jpg'),
(33, 7, 'Pancit Canton Original', 'Classic stir-fried noodles with a savory taste.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Pancit Canton Original.jpg'),
(34, 7, 'Pancit Canton Hot & Spicy', 'Flavorful noodles with a bold and spicy kick.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Pancit Canton Hot & Spicy.jpg'),
(35, 7, 'Pancit Canton Sweet & Spicy', 'Delicious noodles with a balance of sweet and spicy flavors.', 'available', 'C:\\xampp\\htdocs\\tiaras_cafe\\public\\images\\coffee\\Pancit Canton Sweet & Spicy.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `menu_variant`
--

CREATE TABLE `menu_variant` (
  `menu_variant_id` int(11) NOT NULL,
  `size` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_variant`
--

INSERT INTO `menu_variant` (`menu_variant_id`, `size`, `price`) VALUES
(1, '16oz', 60.00),
(2, '22oz', 70.00),
(3, '16oz', 65.00),
(4, '22oz', 75.00),
(6, '16oz', 50.00),
(7, '22oz', 60.00),
(8, '', 25.00),
(9, '', 30.00),
(10, '', 35.00);

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `pakage_id` int(11) NOT NULL,
  `pakage_name` varchar(100) NOT NULL,
  `pakage_desc` varchar(255) NOT NULL,
  `pakage_price` decimal(10,2) NOT NULL,
  `pakage_inclusions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`pakage_id`, `pakage_name`, `pakage_desc`, `pakage_price`, `pakage_inclusions`) VALUES
(1, 'Package 1', 'A perfect option for small gatherings and intimate events. This package includes 50 cups, making it ideal for smaller gatherings.\r\n\r\n', 5600.00, '50 CUPS\r\n\r\n- FULL SET-UP COFFEE BAR\r\n- SERVICE WITHIN 4 HOURS\r\n- 5 COFFEE DRINKS, 2 NON-COFFEE DRINKS, AND 3 ITALIAN SODAS\r\n- ALL DRINKS ARE ICED (16 oz)\r\n- 2 TO 3 BARISTAS '),
(2, 'Package 2', 'Ideal for medium-sized gatherings and celebrations. With 100 cups included, you can serve more guests with a convenient, affordable package.\r\n', 10500.00, '100 CUPS\r\n\r\n- FULL SET-UP COFFEE BAR\r\n- SERVICE WITHIN 4 HOURS\r\n- 5 COFFEE DRINKS, 2 NON-COFFEE DRINKS, AND 3 ITALIAN SODAS\r\n- ALL DRINKS ARE ICED (16 oz)\r\n- 2 TO 3 BARISTAS '),
(3, 'Package 3', 'Best for large gatherings, parties, and special events. This package includes 150 cups, providing more servings for events with a larger number of guests.\r\n', 14500.00, '150 CUPS\r\n\r\n- FULL SET-UP COFFEE BAR\r\n- SERVICE WITHIN 4 HOURS\r\n- 5 COFFEE DRINKS, 2 NON-COFFEE DRINKS, AND 3 ITALIAN SODAS\r\n- ALL DRINKS ARE ICED (16 oz)\r\n- 2 TO 3 BARISTAS ');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `booking_location` varchar(255) NOT NULL,
  `package_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `cancelled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(10) UNSIGNED NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `email_address`, `rating`, `comment`, `created_at`) VALUES
(1, 'maria@email.com	', 5, 'Delicious food and refreshing drinks. Great place to hang out!', '2026-11-14 10:15:00'),
(2, 'john@email.com', 5, 'I love the variety of drinks and snacks. Everything tastes great!', '2026-11-14 11:20:00'),
(3, 'anna@email.com', 4, 'Nice cafe with affordable food and many drink options.', '2026-04-14 12:30:00'),
(4, 'mark@email.com', 5, 'Great food, tasty drinks, and a comfortable atmosphere!', '2026-11-14 10:06:53');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(10) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `service_name`, `service_desc`) VALUES
(1, 'Weddings', 'Make your special day more memorable with our in-service café booth, offering delicious coffee and refreshments for you and your wedding guests.'),
(2, 'Anniversaries', 'Celebrate love and milestones with our coffee and beverage services, adding a cozy and delightful touch to your anniversary celebration.'),
(3, 'Birthdays / Debut', 'Make every birthday and debut extra special with freshly prepared drinks and café favorites that your guests can enjoy throughout the celebration.'),
(4, 'School Events', 'Keep students, teachers, and guests refreshed with our convenient café booth, perfect for school celebrations, programs, fairs, and other campus activities.'),
(5, 'Office Events', 'Bring a café experience to your workplace with our coffee and beverage services, perfect for corporate gatherings, meetings, team celebrations, and company occasions.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_item_id`,`menu_variant_id`),
  ADD KEY `menu_item_` (`menu_item_id`),
  ADD KEY `menu_variant` (`menu_variant_id`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`menu_item_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `menu_variant`
--
ALTER TABLE `menu_variant`
  ADD PRIMARY KEY (`menu_variant_id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`pakage_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `reservation_package` (`package_id`),
  ADD KEY `reservation_service` (`service_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `menu_variant`
--
ALTER TABLE `menu_variant`
  MODIFY `menu_variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `pakage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_item_` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`menu_item_id`),
  ADD CONSTRAINT `menu_variant` FOREIGN KEY (`menu_variant_id`) REFERENCES `menu_variant` (`menu_variant_id`);

--
-- Constraints for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_package` FOREIGN KEY (`package_id`) REFERENCES `package` (`pakage_id`),
  ADD CONSTRAINT `reservation_service` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
