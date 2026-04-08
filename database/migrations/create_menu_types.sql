-- ============================================================
-- Migration: Create menu_types table
-- Aurora Restaurant - Dynamic Menu Type Management
-- Date: 2026-04-08
-- ============================================================

-- Tạo bảng menu_types để quản lý động các loại menu
CREATE TABLE IF NOT EXISTS `menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên tiếng Việt',
  `name_en` varchar(100) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `type_key` varchar(50) NOT NULL COMMENT 'Mã định danh (vd: asia, europe, alacarte)',
  `description` text DEFAULT NULL COMMENT 'Mô tả ngắn',
  `color` varchar(20) DEFAULT '#0ea5e9' COMMENT 'Màu sắc đại diện (hex)',
  `icon` varchar(50) DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự sắp xếp',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: Hiện, 0: Ẩn',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_type_key` (`type_key`),
  KEY `idx_types_active` (`is_active`),
  KEY `idx_types_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân loại menu (Á, Âu, Alacarte, Khác)';

-- Insert dữ liệu mặc định (4 loại menu cơ bản)
INSERT INTO `menu_types` (`name`, `name_en`, `type_key`, `description`, `color`, `icon`, `sort_order`, `is_active`) VALUES
('Món Á', 'Asian Cuisine', 'asia', 'Các món ăn truyền thống châu Á', '#0ea5e9', 'fa-bowl-rice', 1, 1),
('Món Âu', 'European Cuisine', 'europe', 'Các món ăn châu Âu', '#8b5cf6', 'fa-utensils', 2, 1),
('Ala Carte', 'Ala Carte', 'alacarte', 'Các món gọi lẻ theo thực đơn', '#f59e0b', 'fa-clipboard-list', 3, 1),
('Khác', 'Other', 'other', 'Các loại món khác (đồ uống, tráng miệng...)', '#16a34a', 'fa-utensils', 4, 1);

-- ============================================================
-- Cập nhật bảng menu_categories: Xóa ENUM, chuyển sang VARCHAR
-- ============================================================

-- 1. Tạo bảng tạm để backup dữ liệu hiện tại
CREATE TABLE IF NOT EXISTS `menu_categories_backup` AS SELECT * FROM `menu_categories`;

-- 2. Xóa constraint FK nếu có (trước khi ALTER)
SET FOREIGN_KEY_CHECKS = 0;

-- 3. ALTER column menu_type từ ENUM sang VARCHAR(50)
ALTER TABLE `menu_categories` 
  MODIFY COLUMN `menu_type` varchar(50) DEFAULT 'asia' COMMENT 'Tham chiếu đến type_key trong menu_types';

-- 4. Bật lại FK check
SET FOREIGN_KEY_CHECKS = 1;

-- Ghi chú: 
-- Bây giờ menu_type là VARCHAR, có thể thêm/xóa loại menu động từ bảng menu_types
-- Dữ liệu cũ vẫn được giữ nguyên trong menu_categories_backup
