-- ============================================================
-- TẠO BẢNG SETTINGS - AURORA RESTAURANT
-- Chạy file này để tạo bảng cài đặt hệ thống
-- ============================================================

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('dev_mode', '0', 'Chế độ phát triển - Tắt kiểm tra vị trí'),
('maintenance_mode', '0', 'Chế độ bảo trì'),
('allow_online_payment', '1', 'Cho phép thanh toán online'),
('auto_print_orders', '1', 'Tự động in đơn hàng')
ON DUPLICATE KEY UPDATE `setting_key` = VALUES(`setting_key`);
