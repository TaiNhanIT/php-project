-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: db
-- Thời gian đã tạo: Th7 09, 2025 lúc 03:58 PM
-- Phiên bản máy phục vụ: 8.0.42
-- Phiên bản PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `php-project`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_session` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Điện thoại'),
(2, 'Máy tính bảng'),
(3, 'Phụ kiện');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `reset_token`) VALUES
(1, 'Nguyen', 'Van A', 'vana@example.com', '0901234567', 'password123', NULL),
(2, 'Tran', 'Thi B', 'tib@example.com', '0912345678', 'password456', NULL),
(3, 'Nguyễn', 'Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '$2y$10$e42JoGq37B7QE.7jH.J8juNt4Devx9b8HWYiSYJkhKfEubSjHAQxK', NULL),
(4, 'Nguyễn Hữu Tài', 'Nhân', 'nguyenhuutainhan21003@gmail.com', '', '$2y$10$vVj8G5ta1HeFFdUmUkyrA.8Karp4UMFTwqIrhNQRFlL6fhGINK95G', NULL),
(5, 'Lê Văn', 'Hải', 'hai@gmail.com', '', '$2y$10$Pa7ckfnITibongDWYup1puQQY9YRGUVy66GP13xACOBGGYfjOZjRS', NULL),
(6, 'Nguyễn Hữu Tài', 'Nhân', 'nguyenhuutainhan03@gmail.com', '', '$2y$10$2rqjtQWjrJjkXWqQpeP2kOYHLVb3KLFfTQiS5ua.0kVybQ8aSdQ1e', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_address`
--

CREATE TABLE `customer_address` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `country_code` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `detail` varchar(255) COLLATE utf8mb4_general_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_address`
--

INSERT INTO `customer_address` (`id`, `customer_id`, `street`, `city`, `country_code`, `name`, `phone`, `detail`) VALUES
(1, 1, '123 Le Loi', 'Hanoi', 'VN', '', '', ''),
(2, 2, '456 Nguyen Trai', 'Ho Chi Minh', 'VN', '', '', ''),
(15, 4, 'uk', 'Đà Nẵng', 'Việt Nam', 'anh hoang', '0888121123', ''),
(19, 3, 'Quốc Lộ 24B', 'Quảng Ngãi', 'Việt Nam', 'Nhân', '0397150061', ''),
(21, 3, 'Hai Bà Trưng', 'Đà Nẵng', 'Việt Nam', 'Nguyễn Hữu Tài Nhân', '0397150061', ''),
(25, 5, 'Tịnh Hà', 'Quảng Ngãi', 'Việt Nam', 'Lê VĂN', '099999', 'đối diện ủy ban Tịnh Hà');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` json NOT NULL,
  `shipping_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `customer_name`, `customer_email`, `customer_phone`, `address`, `shipping_method`, `payment_method`, `total`, `status`, `created_at`) VALUES
(1, 4, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 2000000.00, 1, '2025-07-05 10:48:51'),
(2, 4, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 2000000.00, 1, '2025-07-05 10:50:03'),
(3, 4, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 2000000.00, 1, '2025-07-05 10:50:06'),
(4, 4, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 2000000.00, 1, '2025-07-05 10:52:58'),
(5, 4, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 2000000.00, 1, '2025-07-05 10:56:08'),
(6, 4, 'anh hoang', 'nguyenhuutainhan21003@gmail.com', '0888121123', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"uk\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 30000000.00, 1, '2025-07-05 11:01:10'),
(7, 4, 'anh hoang', 'nguyenhuutainhan21003@gmail.com', '0888121123', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"uk\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 3799000.00, 1, '2025-07-05 12:03:09'),
(8, 4, 'anh hoang', 'nguyenhuutainhan21003@gmail.com', '0888121123', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"uk\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-05 12:04:02'),
(9, 4, 'anh hoang', 'nguyenhuutainhan21003@gmail.com', '0888121123', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"uk\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 15000000.00, 1, '2025-07-05 12:06:05'),
(10, 4, 'anh hoang', 'nguyenhuutainhan21003@gmail.com', '0888121123', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"uk\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-05 12:08:28'),
(11, 4, 'anh hoang', 'nguyenhuutainhan21003@gmail.com', '0888121123', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"uk\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 3799000.00, 1, '2025-07-05 12:08:36'),
(12, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 3799000.00, 4, '2025-07-07 10:24:08'),
(13, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 3799000.00, 4, '2025-07-07 11:17:04'),
(14, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-07 12:42:36'),
(15, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"24/6 Hai Bà Trưng\", \"street\": \"Hai Bà Trưng\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 3799000.00, 1, '2025-07-07 13:04:01'),
(16, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"24/6 Hai Bà Trưng\", \"street\": \"Hai Bà Trưng\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-07 13:08:34'),
(17, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"24/6 Hai Bà Trưng\", \"street\": \"Hai Bà Trưng\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 11397000.00, 1, '2025-07-07 13:11:36'),
(18, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-07 14:24:28'),
(19, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 15000000.00, 1, '2025-07-07 14:25:43'),
(20, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 264000000.00, 1, '2025-07-07 14:29:39'),
(21, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-09 07:12:52'),
(22, 3, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhanit@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-09 09:16:08'),
(23, NULL, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 37000000.00, 4, '2025-07-09 13:38:31'),
(24, NULL, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-09 13:43:36'),
(25, NULL, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 22000000.00, 1, '2025-07-09 13:44:38'),
(26, NULL, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 15000000.00, 1, '2025-07-09 13:50:38'),
(27, NULL, 'Nguyễn Hữu Tài Nhân', 'sibi13@gmail.com', '0397150061', '{\"city\": \"Đà nẵng\", \"detail\": \"\", \"street\": \"Trần Cao Vân\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 3799000.00, 1, '2025-07-09 14:57:29'),
(28, NULL, 'anh hoang', 'vana@example.com', '0888121123', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"uk\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 30000000.00, 1, '2025-07-09 15:01:00'),
(29, NULL, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 80000000.00, 1, '2025-07-09 15:03:52'),
(30, NULL, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 8000000.00, 1, '2025-07-09 15:07:27'),
(31, NULL, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan21003@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 15000000.00, 1, '2025-07-09 15:13:44'),
(32, NULL, 'Nguyễn Hữu Tài Nhân', 'nguyenhuutainhan2@gmail.com', '0397150061', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 40000000.00, 1, '2025-07-09 15:21:45'),
(33, 5, 'hai le van', 'hai@gmail.com', '112334444', '{\"city\": \"Quảng Ngãi\", \"detail\": \"\", \"street\": \"vn\", \"country_code\": \"Việt Nam\"}', 'express', 'visa', 20000000.00, 1, '2025-07-09 15:34:06'),
(34, 5, 'Lê VĂN', 'hai@gmail.com', '099999', '{\"city\": \"Quảng Ngãi\", \"detail\": \"đối diện ủy ban Tịnh Hà\", \"street\": \"Tịnh Hà\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 3799000.00, 4, '2025-07-09 15:40:48'),
(35, 6, 'anh hoang', 'nguyenhuutainhan03@gmail.com', '0888121123', '{\"city\": \"Đà Nẵng\", \"detail\": \"\", \"street\": \"uk\", \"country_code\": \"Việt Nam\"}', 'standard', 'cod', 15000000.00, 1, '2025-07-09 15:50:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 2, 3, 1, 2000000.00),
(2, 3, 3, 1, 2000000.00),
(3, 4, 3, 1, 2000000.00),
(4, 5, 3, 1, 2000000.00),
(5, 6, 2, 2, 15000000.00),
(6, 7, 5, 1, 3799000.00),
(7, 8, 4, 1, 22000000.00),
(8, 9, 2, 1, 15000000.00),
(9, 10, 4, 1, 22000000.00),
(10, 11, 5, 1, 3799000.00),
(11, 12, 5, 1, 3799000.00),
(12, 13, 5, 1, 3799000.00),
(13, 14, 4, 1, 22000000.00),
(14, 15, 5, 1, 3799000.00),
(15, 16, 4, 1, 22000000.00),
(16, 17, 5, 3, 3799000.00),
(17, 18, 4, 1, 22000000.00),
(18, 19, 2, 1, 15000000.00),
(19, 20, 4, 12, 22000000.00),
(20, 21, 4, 1, 22000000.00),
(21, 22, 4, 1, 22000000.00),
(22, 23, 2, 1, 15000000.00),
(23, 23, 4, 1, 22000000.00),
(24, 24, 4, 1, 22000000.00),
(25, 25, 4, 1, 22000000.00),
(26, 26, 2, 1, 15000000.00),
(27, 27, 5, 1, 3799000.00),
(28, 28, 2, 2, 15000000.00),
(29, 29, 1, 4, 20000000.00),
(30, 30, 3, 4, 2000000.00),
(31, 31, 2, 1, 15000000.00),
(32, 32, 1, 2, 20000000.00),
(33, 33, 1, 1, 20000000.00),
(34, 34, 5, 1, 3799000.00),
(35, 35, 2, 1, 15000000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_status`
--

CREATE TABLE `order_status` (
  `id` int NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `order_status`
--

INSERT INTO `order_status` (`id`, `label`) VALUES
(1, 'pending'),
(2, 'processing'),
(3, 'closed'),
(4, 'cancel');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `stock_quantity` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `description`, `stock_quantity`, `image`) VALUES
(1, 'Samsung Galaxy S21', 20000000.00, 'Smartphone cao cấp', 43, 'OIP.webp'),
(2, 'Samsung Tab S7', 15000000.00, 'Máy tính bảng', 20, 'OIP (1).webp'),
(3, 'Tai nghe Samsung', 2000000.00, 'Phụ kiện chính hãng', 95, '44194951e8a5310d0386a342bf0c24e7.jpg'),
(4, 'Điện thoại Samsung Galaxy A54 5G 8GB/256GB Xanh', 22000000.00, 'Điện thoại Samsung thiết kế sang trọng, siêu mỏng nhẹ chỉ 202 gam\r\nMàn hình Super AMOLED, phân giải Full HD+, hình ảnh hiển thị sống động\r\nChip Exynos 1380 mạnh mẽ, nghe gọi, xem phim, lướt web mượt mà\r\nBộ 3 camera sau 50MP, 12MP, 5MP ghi lại mọi khoảnh khắc đẹp sắc nét\r\nCamera trước 32MP, chụp ảnh chân dung đẹp tự nhiên và ấn tượng\r\nTính năng Quay chụp chống rung chuyên nghiệp OIS, hạn chế mờ nhòe\r\nViên pin dung lượng 5000mAh bền bỉ, làm việc và giải trí suốt cả ngày\r\nĐạt tiêu chuẩn kháng nước IP67, sử dụng bất chấp thời tiết nắng mưa\r\nTrang bị cảm biến vân tay trong màn hình, mở khóa nhanh với 1 chạm', 0, 'J-Switch-to-A24-Product-Photo-e1695159319345.jpeg'),
(5, 'Máy tính bảng Samsung Galaxy Tab A8', 3799000.00, '', 10, 'R.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(1, 1),
(4, 1),
(2, 2),
(5, 2),
(3, 3);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_guest_cart` (`customer_session`,`product_id`),
  ADD UNIQUE KEY `unique_cart_item` (`customer_id`,`customer_session`,`product_id`),
  ADD UNIQUE KEY `unique_user_cart` (`customer_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `status` (`status`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Chỉ mục cho bảng `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `customer_address`
--
ALTER TABLE `customer_address`
  ADD CONSTRAINT `customer_address_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`status`) REFERENCES `order_status` (`id`) ON DELETE RESTRICT;

--
-- Ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT;

--
-- Ràng buộc cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
