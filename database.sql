-- =======================================================
-- CƠ SỞ DỮ LIỆU TECHSTORE / HỆ THỐNG ĐIỆN MÁY XANH
-- Phục vụ website thương mại điện tử chuẩn MVC
-- =======================================================

CREATE DATABASE IF NOT EXISTS `techstore_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `techstore_db`;

-- 1. BẢNG NGƯỜI DÙNG (USERS)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NULL,
    `password` VARCHAR(255) NOT NULL,
    `address` TEXT NULL,
    `avatar` VARCHAR(255) DEFAULT 'default-avatar.png',
    `role` ENUM('user', 'admin') DEFAULT 'user',
    `status` TINYINT(1) DEFAULT 1 COMMENT '1: Hoạt động, 0: Bị khóa',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. BẢNG DANH MỤC SẢN PHẨM (CATEGORIES)
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `icon` VARCHAR(100) NULL COMMENT 'Icon fontawesome hoặc đường dẫn ảnh',
    `badge` VARCHAR(50) NULL COMMENT 'Nhãn như HOT, 3.790k, -50%',
    `description` TEXT NULL,
    `status` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. BẢNG SẢN PHẨM (PRODUCTS)
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `sku` VARCHAR(50) NULL,
    `image` VARCHAR(255) NOT NULL,
    `gallery` TEXT NULL COMMENT 'JSON lưu danh sách nhiều ảnh',
    `price` DECIMAL(15, 0) NOT NULL COMMENT 'Giá gốc',
    `sale_price` DECIMAL(15, 0) NULL COMMENT 'Giá khuyến mãi / Flash sale',
    `discount_percent` INT DEFAULT 0,
    `quantity` INT DEFAULT 100,
    `sold_count` INT DEFAULT 0,
    `flash_sale` TINYINT(1) DEFAULT 0 COMMENT '1: Thuộc đợt Flash sale',
    `flash_sale_quota` INT DEFAULT 10 COMMENT 'Số lượng suất mở bán',
    `flash_sale_sold` INT DEFAULT 10 COMMENT 'Số suất đã bán',
    `rating` DECIMAL(2, 1) DEFAULT 5.0,
    `review_count` INT DEFAULT 1,
    `specs` TEXT NULL COMMENT 'JSON thông số kỹ thuật (Dung tích, Công suất, Chế độ nấu...)',
    `gift_info` TEXT NULL COMMENT 'Thông tin quà tặng đính kèm',
    `short_description` VARCHAR(500) NULL,
    `description` LONGTEXT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `status` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BẢNG MÃ GIẢM GIÁ (VOUCHERS)
CREATE TABLE IF NOT EXISTS `vouchers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `discount_type` ENUM('fixed', 'percent') DEFAULT 'fixed',
    `discount_value` DECIMAL(15, 0) NOT NULL,
    `min_order_value` DECIMAL(15, 0) DEFAULT 0,
    `max_discount` DECIMAL(15, 0) NULL,
    `start_date` DATETIME NULL,
    `end_date` DATETIME NULL,
    `usage_limit` INT DEFAULT 100,
    `used_count` INT DEFAULT 0,
    `status` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. BẢNG ĐƠN HÀNG (ORDERS)
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_code` VARCHAR(30) NOT NULL UNIQUE,
    `user_id` INT NULL COMMENT 'NULL nếu khách mua không đăng nhập',
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,
    `customer_email` VARCHAR(150) NULL,
    `shipping_address` TEXT NOT NULL,
    `shipping_method` VARCHAR(50) DEFAULT 'standard' COMMENT 'standard: Giao tiêu chuẩn, express: Giao siêu nhanh',
    `shipping_fee` DECIMAL(15, 0) DEFAULT 0,
    `payment_method` VARCHAR(50) DEFAULT 'bank_transfer' COMMENT 'bank_transfer, paylater, cod, credit_card',
    `payment_type` ENUM('full', 'deposit') DEFAULT 'full' COMMENT 'full: 100%, deposit: giữ hàng 10%',
    `payment_status` ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    `subtotal` DECIMAL(15, 0) NOT NULL,
    `discount_amount` DECIMAL(15, 0) DEFAULT 0,
    `total_amount` DECIMAL(15, 0) NOT NULL,
    `note` TEXT NULL,
    `status` ENUM('pending', 'processing', 'shipping', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. BẢNG CHI TIẾT ĐƠN HÀNG (ORDER_ITEMS)
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_image` VARCHAR(255) NOT NULL,
    `price` DECIMAL(15, 0) NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `total` DECIMAL(15, 0) NOT NULL,
    `gift_info` VARCHAR(255) NULL,
    CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. BẢNG LIÊN HỆ / PHẢN HỒI (CONTACTS)
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- NẠP DỮ LIỆU MẪU CHUẨN ĐIỆN MÁY XANH
-- =======================================================

-- Tài khoản quản trị mặc định: admin@dienmayxanh.com / 123456
-- Mật khẩu mã hóa bcrypt cho '123456' là: $2y$10$hJOHuSkT0mLOMQ4rDVgOfOSrbkSVhovyiktemoaR0tfq9ZJ1YUIHC
INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `password`, `address`, `role`, `status`) VALUES
(1, 'Quản Trị Viên DMX', 'admin@dienmayxanh.com', '18001061', '$2y$10$hJOHuSkT0mLOMQ4rDVgOfOSrbkSVhovyiktemoaR0tfq9ZJ1YUIHC', 'Tòa nhà MWG, Quận 9, TP. Hồ Chí Minh', 'admin', 1),
(2, 'Anh Ngô Quang Minh', 'minh.ngo@example.com', '0342586038', '$2y$10$hJOHuSkT0mLOMQ4rDVgOfOSrbkSVhovyiktemoaR0tfq9ZJ1YUIHC', 'ngõ 114, Phường Phan Đình Phùng, Tỉnh Thái Nguyên', 'user', 1)
ON DUPLICATE KEY UPDATE `password`=VALUES(`password`);

-- Danh mục sản phẩm (Theo đúng giao diện ảnh 1)
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `badge`, `sort_order`) VALUES
(1, 'Máy giặt', 'may-giat', 'fa-solid fa-soap', '3.790k', 1),
(2, 'Tủ lạnh', 'tu-lanh', 'fa-solid fa-temperature-arrow-down', 'HOT', 2),
(3, 'Máy nước nóng', 'may-nuoc-nong', 'fa-solid fa-shower', NULL, 3),
(4, 'Tivi', 'tivi', 'fa-solid fa-tv', '4.190k', 4),
(5, 'Gia dụng', 'gia-dung', 'fa-solid fa-blender', '-50%', 5),
(6, 'Máy lạnh', 'may-lanh', 'fa-solid fa-wind', '5.290k', 6),
(7, 'Máy lọc nước', 'may-loc-nuoc', 'fa-solid fa-faucet-drip', NULL, 7),
(8, 'Máy sấy quần áo', 'may-say-quan-ao', 'fa-solid fa-shirt', '4.990k', 8),
(9, 'Tủ đông mát', 'tu-dong-mat', 'fa-solid fa-snowflake', '2.990k', 9),
(10, 'Robot hút bụi', 'robot-hut-bui', 'fa-solid fa-robot', NULL, 10),
(11, 'Quạt', 'quat', 'fa-solid fa-fan', NULL, 11),
(12, 'Nồi cơm điện', 'noi-com-dien', 'fa-solid fa-bowl-rice', '790k', 12),
(13, 'Loa', 'loa', 'fa-solid fa-volume-high', NULL, 13),
(14, 'Sức khỏe & Làm đẹp', 'suc-khoe-lam-dep', 'fa-solid fa-heart-pulse', NULL, 14),
(15, 'Máy lọc không khí', 'may-loc-khong-khi', 'fa-solid fa-shield-virus', NULL, 15),
(16, 'Tất cả danh mục', 'tat-ca-danh-muc', 'fa-solid fa-bars', NULL, 16)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Sản phẩm (Khớp chính xác các sản phẩm trong ảnh 2, 3, 4)
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `sku`, `image`, `price`, `sale_price`, `discount_percent`, `quantity`, `sold_count`, `flash_sale`, `flash_sale_quota`, `flash_sale_sold`, `rating`, `review_count`, `specs`, `gift_info`, `short_description`, `is_featured`, `status`) VALUES
(1, 12, 'Nồi cơm điện tử Kangaroo 1.8 lít KGRC18D3G', 'noi-com-dien-tu-kangaroo-1-8-lit-kgrc18d3g', 'KGRC18D3G', 'kangaroo-kgrc18d3g.png', 2690000, 790000, 70, 50, 1100, 1, 10, 10, 4.9, 1100, '{"dung_tich":"1.8 Lít","cong_suat":"860W","che_do":"7 chế độ nấu","hen_gio":"Có hẹn giờ"}', 'Phiếu mua hàng mua Pin tiểu (đơn hàng từ 80.000đ trở lên) trị giá 20.000đ', 'Nồi cơm điện tử Kangaroo dung tích 1.8L phù hợp gia đình 4-6 người, lòng nồi chống dính cao cấp.', 1, 1),

(2, 12, 'Nồi cơm điện tử BlueStone 1.5 lít RCB-5908', 'noi-com-dien-tu-bluestone-1-5-lit-rcb-5908', 'RCB5908', 'bluestone-rcb5908.png', 2450000, 790000, 67, 45, 193, 1, 10, 10, 4.6, 193, '{"dung_tich":"1.5 Lít","cong_suat":"860W","che_do":"8 chế độ nấu thông minh","long_noi":"Chống dính ceramic"}', 'Tặng vá xới cơm cao cấp và xửng hấp', 'Thiết kế sang trọng, bảng điều khiển cảm ứng tiếng Việt dễ sử dụng.', 1, 1),

(3, 5, 'Nồi chiên không dầu Rapido 8 lít RAF-8.0M3', 'noi-chien-khong-dau-rapido-8-lit-raf-8-0m3', 'RAF80M3', 'rapido-raf-8m3.png', 2990000, 1290000, 56, 30, 77, 1, 10, 10, 5.0, 77, '{"dung_tich":"8 Lít","cong_suat":"1800W","cong_nghe":"Rapid Air giảm 80% dầu mỡ","kinh_quan_sat":"Kính chịu nhiệt trong suốt"}', 'Tặng bình xịt dầu ăn cao cấp', 'Dung tích siêu lớn 8 lít nướng nguyên con gà, mặt kính quan sát dễ dàng.', 1, 1),

(4, 12, 'Nồi cơm điện tử Sunhouse 1.8 lít SHD8909', 'noi-com-dien-tu-sunhouse-1-8-lit-shd8909', 'SHD8909', 'sunhouse-shd8909.png', 2110000, 790000, 62, 60, 1000, 1, 10, 10, 4.9, 1000, '{"dung_tich":"1.8 Lít","cong_suat":"860W","che_do":"21 chế độ nấu đa năng","long_noi":"Dày 1.6 mm"}', 'Tặng kèm muỗng canh & cốc đong gạo', 'Lòng nồi dày giữ nhiệt tốt, nấu cơm dẻo thơm từng hạt.', 1, 1),

(5, 5, 'Camera IP 360 Độ 3MP EZVIZ H6C G1', 'camera-ip-360-do-3mp-ezviz-h6c-g1', 'EZVIZ-H6C', 'ezviz-h6c.png', 890000, 430000, 51, 80, 240, 1, 10, 10, 4.9, 240, '{"do_phan_giai":"3.0 Megapixel (2K)","goc_quay":"Xoay 360 độ","dam_thoai":"2 chiều","hong_ngoai":"10m"}', 'Miễn phí công lắp đặt tại nhà', 'Camera thông minh theo dõi chuyển động, đàm thoại 2 chiều sắc nét.', 1, 1),

(6, 5, 'Lò chiên không dầu Ferroli 12 lít FAF-12D', 'lo-chien-khong-dau-ferroli-12-lit-faf-12d', 'FAF12D', 'ferroli-faf12d.png', 2490000, 1190000, 52, 40, 57, 1, 10, 10, 4.7, 57, '{"dung_tich":"12 Lít","cong_suat":"1700W","dieu_khien":"Cảm ứng điện tử","hen_gio":"Tối đa 90 phút"}', 'Tặng bộ 5 phụ kiện inox không gỉ', 'Lò chiên kết hợp nướng 12 lít tiện lợi cho bữa ăn gia đình lớn.', 1, 1),

(7, 4, 'Smart Tivi Samsung 4K 55 inch Crystal UHD', 'smart-tivi-samsung-4k-55-inch-crystal-uhd', 'UA55AU7002', 'samsung-55-crystal.png', 14900000, 8990000, 40, 25, 340, 0, 10, 8, 4.8, 340, '{"man_hinh":"55 inch 4K","he_dieu_hanh":"Tizen OS","am_thanh":"Q-Symphony 20W","ket_noi":"Bluetooth, Wifi, HDMI, USB"}', 'Tặng gói xem phim VieON & Galaxy Play 12 tháng', 'Hình ảnh sắc nét sống động với công nghệ PurColor chuẩn 4K.', 1, 1),

(8, 2, 'Tủ lạnh Panasonic Inverter 322 lít NR-BC360QKVN', 'tu-lanh-panasonic-inverter-322-lit', 'NRBC360', 'panasonic-322l.png', 15990000, 11490000, 28, 15, 120, 0, 10, 6, 4.9, 120, '{"dung_tich":"322 Lít","ngan_dong_mem":"Prime Fresh+ -3 độ C","khang_khuan":"Ag Clean","tiet_kiem_dien":"Econavi Inverter"}', 'Tặng chảo chống dính Sunhouse 26cm', 'Ngăn đông mềm chuẩn -3 độ C giữ thịt cá tươi ngon không cần rã đông.', 1, 1)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Vouchers khuyến mãi
INSERT INTO `vouchers` (`id`, `code`, `discount_type`, `discount_value`, `min_order_value`, `max_discount`, `status`) VALUES
(1, 'GIAM500K', 'fixed', 500000, 5000000, 500000, 1),
(2, 'GIAM1TR2', 'fixed', 1200000, 15000000, 1200000, 1),
(3, 'DMX50K', 'fixed', 50000, 500000, 50000, 1)
ON DUPLICATE KEY UPDATE `id`=`id`;
