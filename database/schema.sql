-- ============================================================
-- AURORA RESTAURANT — Digital Menu & Order System
-- Database Schema v1.0
-- Created: 2026-03-01
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- ============================================================
-- TABLE: users
-- Nhân viên: waiter, admin, it
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(100) NOT NULL COMMENT 'Tên nhân viên',
    `username`   VARCHAR(50) NOT NULL UNIQUE COMMENT 'Tên đăng nhập',
    `pin`        CHAR(4) NOT NULL COMMENT 'PIN 4 số đăng nhập iPad',
    `role`       ENUM('waiter', 'admin', 'it') NOT NULL DEFAULT 'waiter',
    `avatar`     VARCHAR(255) DEFAULT NULL COMMENT 'URL ảnh đại diện',
    `is_active`  TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=hoạt động, 0=vô hiệu',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: tables
-- Bàn trong nhà hàng
-- ============================================================
CREATE TABLE IF NOT EXISTS `tables` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(50) NOT NULL COMMENT 'Tên bàn: Bàn 01, VIP 1...',
    `area`        VARCHAR(50) DEFAULT NULL COMMENT 'Khu vực: Trong, Ngoài, VIP...',
    `capacity`    TINYINT UNSIGNED NOT NULL DEFAULT 4 COMMENT 'Sức chứa (số ghế)',
    `status`      ENUM('available', 'occupied') NOT NULL DEFAULT 'available',
    `position_x`  SMALLINT UNSIGNED DEFAULT 0 COMMENT 'Toạ độ X trên sơ đồ',
    `position_y`  SMALLINT UNSIGNED DEFAULT 0 COMMENT 'Toạ độ Y trên sơ đồ',
    `sort_order`  SMALLINT UNSIGNED DEFAULT 0,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: menu_categories
-- Danh mục món ăn
-- ============================================================
CREATE TABLE IF NOT EXISTS `menu_categories` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(100) NOT NULL COMMENT 'Tên danh mục: Khai vị, Chính, Tráng miệng...',
    `name_en`     VARCHAR(100) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
    `icon`        VARCHAR(50) DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
    `sort_order`  SMALLINT UNSIGNED DEFAULT 0 COMMENT 'Thứ tự hiển thị',
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: menu_items
-- Món ăn
-- ============================================================
CREATE TABLE IF NOT EXISTS `menu_items` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id`   INT UNSIGNED NOT NULL,
    `name`          VARCHAR(150) NOT NULL COMMENT 'Tên món',
    `name_en`       VARCHAR(150) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
    `description`   TEXT DEFAULT NULL COMMENT 'Mô tả món',
    `price`         DECIMAL(10, 0) NOT NULL DEFAULT 0 COMMENT 'Giá (VND)',
    `image`         VARCHAR(255) DEFAULT NULL COMMENT 'Đường dẫn ảnh món',
    `is_available`  TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=còn hàng, 0=hết hàng',
    `is_active`     TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=hiển thị, 0=ẩn',
    `tags`          SET('bestseller','new','spicy','vegetarian','recommended') DEFAULT NULL,
    `sort_order`    SMALLINT UNSIGNED DEFAULT 0,
    `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`)
        REFERENCES `menu_categories`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: orders
-- Order theo bàn (một phiên khách ngồi)
-- ============================================================
CREATE TABLE IF NOT EXISTS `orders` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `table_id`     INT UNSIGNED NOT NULL,
    `waiter_id`    INT UNSIGNED NOT NULL COMMENT 'Phục vụ mở bàn',
    `guest_count`  TINYINT UNSIGNED DEFAULT 1 COMMENT 'Số khách',
    `note`         TEXT DEFAULT NULL COMMENT 'Ghi chú cho cả order',
    `status`       ENUM('open', 'closed') NOT NULL DEFAULT 'open'
                   COMMENT 'open=đang phục vụ, closed=khách ra',
    `opened_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Giờ mở bàn',
    `closed_at`    TIMESTAMP NULL DEFAULT NULL COMMENT 'Giờ đóng bàn',
    `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`)
        REFERENCES `tables`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_orders_waiter` FOREIGN KEY (`waiter_id`)
        REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: order_items
-- Chi tiết từng món trong order
-- ============================================================
CREATE TABLE IF NOT EXISTS `order_items` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`     INT UNSIGNED NOT NULL,
    `menu_item_id` INT UNSIGNED NOT NULL,
    `item_name`    VARCHAR(150) NOT NULL COMMENT 'Snapshot tên món tại thời điểm ghi',
    `item_price`   DECIMAL(10, 0) NOT NULL COMMENT 'Snapshot giá tại thời điểm ghi',
    `quantity`     TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `note`         VARCHAR(255) DEFAULT NULL COMMENT 'Ghi chú: không hành, ít cay...',
    `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`)
        REFERENCES `orders`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_order_items_menu` FOREIGN KEY (`menu_item_id`)
        REFERENCES `menu_items`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- INDEXES
-- ============================================================
CREATE INDEX `idx_orders_table`    ON `orders`(`table_id`);
CREATE INDEX `idx_orders_waiter`   ON `orders`(`waiter_id`);
CREATE INDEX `idx_orders_status`   ON `orders`(`status`);
CREATE INDEX `idx_orders_opened`   ON `orders`(`opened_at`);
CREATE INDEX `idx_items_category`  ON `menu_items`(`category_id`);
CREATE INDEX `idx_items_available` ON `menu_items`(`is_available`, `is_active`);

-- ============================================================
-- SEED DATA — Dữ liệu mẫu ban đầu
-- ============================================================

-- Users mặc định (PIN sẽ được hash trong production)
INSERT INTO `users` (`name`, `username`, `pin`, `role`) VALUES
('Admin Nhà Hàng', 'admin', '1234', 'admin'),
('IT System', 'it', '9999', 'it'),
('Nguyễn Văn A', 'waiter01', '1111', 'waiter'),
('Trần Thị B', 'waiter02', '2222', 'waiter');

-- Bàn mẫu (30 bàn tổng cộng)
INSERT INTO `tables` (`name`, `area`, `capacity`, `sort_order`) VALUES
-- A1 (6 bàn)
('A.01', 'A1', 4, 1),
('A.02', 'A1', 4, 2),
('A.03', 'A1', 4, 3),
('A.04', 'A1', 4, 4),
('A.05', 'A1', 4, 5),
('A.06', 'A1', 4, 6),

-- B1 (6 bàn)
('B.01', 'B1', 4, 7),
('B.02', 'B1', 4, 8),
('B.03', 'B1', 4, 9),
('B.04', 'B1', 4, 10),
('B.05', 'B1', 4, 11),
('B.06', 'B1', 4, 12),

-- C1 (6 bàn)
('C.01', 'C1', 4, 13),
('C.02', 'C1', 4, 14),
('C.03', 'C1', 4, 15),
('C.04', 'C1', 4, 16),
('C.05', 'C1', 4, 17),
('C.06', 'C1', 4, 18),

-- VIP 1 (2 bàn)
('VIP 1.1', 'VIP 1', 8, 19),
('VIP 1.2', 'VIP 1', 8, 20),

-- VIP 2 (2 bàn)
('VIP 2.1', 'VIP 2', 8, 21),
('VIP 2.2', 'VIP 2', 8, 22),

-- VIP 3 (2 bàn)
('VIP 3.1', 'VIP 3', 8, 23),
('VIP 3.2', 'VIP 3', 8, 24),

-- VIP 4 (2 bàn)
('VIP 4.1', 'VIP 4', 8, 25),
('VIP 4.2', 'VIP 4', 8, 26),

-- Âu (6 bàn)
('Âu 01', 'Âu', 4, 27),
('Âu 02', 'Âu', 4, 28),
('Âu 03', 'Âu', 4, 29),
('Âu 04', 'Âu', 4, 30),
('Âu 05', 'Âu', 4, 31),
('Âu 06', 'Âu', 4, 32);

-- Danh mục món
INSERT INTO `menu_categories` (`name`, `name_en`, `icon`, `sort_order`) VALUES
('Khai Vị', 'Appetizers', 'fa-leaf', 1),
('Món Chính', 'Main Course', 'fa-drumstick-bite', 2),
('Tráng Miệng', 'Desserts', 'fa-ice-cream', 3),
('Đồ Uống', 'Beverages', 'fa-glass-martini-alt', 4),
('Đặc Sản', 'Specialties', 'fa-star', 5);

-- Món mẫu (Khai vị)
INSERT INTO `menu_items` (`category_id`, `name`, `name_en`, `price`, `tags`, `sort_order`) VALUES
(1, 'Gỏi cuốn tôm thịt', 'Fresh Spring Rolls', 85000, 'bestseller', 1),
(1, 'Chả giò rế', 'Crispy Rolls', 75000, NULL, 2),
(1, 'Súp bào ngư vi cá', 'Abalone Soup', 150000, 'recommended', 3),

-- Món chính
(2, 'Cơm chiên hải sản', 'Seafood Fried Rice', 120000, 'bestseller', 1),
(2, 'Bò lúc lắc', 'Shaken Beef', 180000, 'recommended', 2),
(2, 'Cá chẽm hấp gừng', 'Steamed Seabass', 250000, NULL, 3),
(2, 'Tôm sú nướng muối ớt', 'Grilled Tiger Prawn', 220000, 'spicy', 4),

-- Tráng miệng
(3, 'Chè bưởi', 'Pomelo Dessert', 45000, NULL, 1),
(3, 'Kem dừa', 'Coconut Ice Cream', 55000, 'bestseller', 2),

-- Đồ uống
(4, 'Nước ép cam', 'Fresh Orange Juice', 65000, NULL, 1),
(4, 'Sinh tố bơ', 'Avocado Smoothie', 75000, 'bestseller', 2),
(4, 'Trà đào cam sả', 'Peach Tea', 55000, NULL, 3),
(4, 'Bia Tiger lon', 'Tiger Beer Can', 35000, NULL, 4),
(4, 'Nước suối', 'Water', 15000, NULL, 5);

-- ============================================================
-- TABLE: menu_sets (Set & Combo - À la carte)
-- ============================================================
CREATE TABLE IF NOT EXISTS `menu_sets` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(150) NOT NULL COMMENT 'Tên set',
    `name_en`     VARCHAR(150) DEFAULT NULL COMMENT 'Tên tiếng Anh',
    `description` TEXT DEFAULT NULL,
    `price`       DECIMAL(10, 0) NOT NULL DEFAULT 0,
    `image`       VARCHAR(255) DEFAULT NULL,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order`  SMALLINT UNSIGNED DEFAULT 0,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: menu_set_items (Món trong set)
-- ============================================================
CREATE TABLE IF NOT EXISTS `menu_set_items` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `set_id`       INT UNSIGNED NOT NULL,
    `menu_item_id` INT UNSIGNED NOT NULL,
    `quantity`     TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `is_required`  TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=bắt buộc, 0=tuỳ chọn',
    `sort_order`   SMALLINT UNSIGNED DEFAULT 0,
    `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_set_items_set` FOREIGN KEY (`set_id`) REFERENCES `menu_sets`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_set_items_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA: Menu Sets (Sample)
-- ============================================================
INSERT INTO `menu_sets` (`name`, `name_en`, `description`, `price`, `sort_order`) VALUES
('Set Ăn Sáng Á', 'Asian Breakfast Set', 'Bao gồm: Phở bò + Chả giò + Nước ép cam', 150000, 1),
('Set Ăn Sáng Âu', 'European Breakfast Set', 'Bao gồm: Bánh mì ốp la + Salad + Cà phê', 140000, 2),
('Set Trưa Văn Phòng', 'Office Lunch Set', 'Bao gồm: Cơm chiên + Gỏi cuốn + Nước suối', 120000, 3);

SET FOREIGN_KEY_CHECKS = 1;
