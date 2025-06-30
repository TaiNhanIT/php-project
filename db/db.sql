-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Jun 04, 2025 at 08:51 AM
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
-- Database: `samsung`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
                              `id` int(11) NOT NULL,
                              `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
                                            (1, 'Điện thoại'),
                                            (2, 'Máy tính bảng'),
                                            (3, 'Phụ kiện');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
                             `id` int(11) NOT NULL,
                             `first_name` varchar(100) NOT NULL,
                             `last_name` varchar(100) NOT NULL,
                             `email` varchar(255) NOT NULL,
                             `phone_number` varchar(20) DEFAULT NULL,
                             `password` varchar(255) NOT NULL,
                             `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `reset_token`) VALUES
                                                                                                                  (1, 'Nguyen', 'Van A', 'vana@example.com', '0901234567', 'password123', NULL),
                                                                                                                  (2, 'Tran', 'Thi B', 'tib@example.com', '0912345678', 'password456', NULL),
                                                                                                                  (3, 'Nguyễn', 'Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '$2y$10$e42JoGq37B7QE.7jH.J8juNt4Devx9b8HWYiSYJkhKfEubSjHAQxK', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE `customer_address` (
                                    `id` int(11) NOT NULL,
                                    `customer_id` int(11) NOT NULL,
                                    `street` varchar(255) NOT NULL,
                                    `city` varchar(100) NOT NULL,
                                    `country_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_address`
--

INSERT INTO `customer_address` (`id`, `customer_id`, `street`, `city`, `country_code`) VALUES
                                                                                           (1, 1, '123 Le Loi', 'Hanoi', 'VN'),
                                                                                           (2, 2, '456 Nguyen Trai', 'Ho Chi Minh', 'VN'),
                                                                                           (3, 3, 'us', 'sài gòn', 'Việt Nam');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
                          `id` int(11) NOT NULL,
                          `customer_id` int(11) NOT NULL,
                          `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
                               `id` int(11) NOT NULL,
                               `order_id` int(11) NOT NULL,
                               `product_id` int(11) NOT NULL,
                               `quantity` int(11) NOT NULL,
                               `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
                            `id` int(11) NOT NULL,
                            `product_name` varchar(255) NOT NULL,
                            `price` decimal(10,2) NOT NULL,
                            `description` text DEFAULT NULL,
                            `stock_quantity` int(11) NOT NULL,
                            `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `description`, `stock_quantity`, `image`) VALUES
                                                                                                     (1, 'Samsung Galaxy S21', 20000000.00, 'Smartphone cao cấp', 50, '683eab70e27e3_R (17).jpg'),
                                                                                                     (2, 'Samsung Tab S7', 15000000.00, 'Máy tính bảng', 30, '683eab4a20389_OIP (40).jpg'),
                                                                                                     (3, 'Tai nghe Samsung', 2000000.00, 'Phụ kiện chính hãng', 100, '683eab035d0c1_OIP (39).jpg'),
                                                                                                     (4, 'Điện thoại Samsung Galaxy A54 5G 8GB/256GB Xanh', 22000000.00, 'Điện thoại Samsung thiết kế sang trọng, siêu mỏng nhẹ chỉ 202 gam\r\nMàn hình Super AMOLED, phân giải Full HD+, hình ảnh hiển thị sống động\r\nChip Exynos 1380 mạnh mẽ, nghe gọi, xem phim, lướt web mượt mà\r\nBộ 3 camera sau 50MP, 12MP, 5MP ghi lại mọi khoảnh khắc đẹp sắc nét\r\nCamera trước 32MP, chụp ảnh chân dung đẹp tự nhiên và ấn tượng\r\nTính năng Quay chụp chống rung chuyên nghiệp OIS, hạn chế mờ nhòe\r\nViên pin dung lượng 5000mAh bền bỉ, làm việc và giải trí suốt cả ngày\r\nĐạt tiêu chuẩn kháng nước IP67, sử dụng bất chấp thời tiết nắng mưa\r\nTrang bị cảm biến vân tay trong màn hình, mở khóa nhanh với 1 chạm', 10, '683ea4d26b547_10055113-dien-thoai-samsung-galaxy-a54-5g-8gb-256gb-xanh-1_a9qc-qi.webp'),
                                                                                                     (5, 'Máy tính bảng Samsung Galaxy Tab A8', 3799000.00, '', 20, '683eac41e1e79_52582_may_tinh_bang_samsung_galaxy_tab_a8_t295_32gb_8_inch_wifi_4g_android_9_0_den_2019__2_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
                                      `product_id` int(11) NOT NULL,
                                      `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
                                                                   (1, 1),
                                                                   (2, 2),
                                                                   (3, 3),
                                                                   (4, 1),
                                                                   (5, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
    ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
    ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
    ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
    ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_address`
--
ALTER TABLE `customer_address`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_address`
--
ALTER TABLE `customer_address`
    ADD CONSTRAINT `customer_address_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
    ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
    ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
    ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
