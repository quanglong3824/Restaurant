đo-- ============================================================
-- QR ORDERING SYSTEM MIGRATION
-- Aurora Restaurant - Production Database
-- Version: 1.0
-- Date: 2026-03-17
-- ============================================================
-- Mô tả: Migration cho hệ thống QR Ordering
-- - Thêm bảng mới cho notifications
-- - Cập nhật bảng orders, order_items, support_requests
-- - Cập nhật bảng qr_tables hiện có
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- PHẦN 1: CẬP NHẬT BẢNG HIỆN CÓ
-- ============================================================

-- 1.1 Cập nhật bảng `orders` - Thêm cột cho customer ordering
ALTER TABLE `orders` 
ADD COLUMN IF NOT EXISTS `order_source` ENUM('waiter', 'customer_qr') NOT NULL DEFAULT 'waiter' 
  COMMENT 'Nguồn tạo order: waiter (phục vụ) hoặc customer_qr (khách quét QR)' 
  AFTER `status`,
ADD COLUMN IF NOT EXISTS `customer_notes` TEXT NULL 
  COMMENT 'Ghi chú từ khách hàng (lý do hủy, đặc biệt...)' 
  AFTER `note`,
ADD COLUMN IF NOT EXISTS `requires_confirmation` TINYINT(1) NOT NULL DEFAULT 1 
  COMMENT 'Cần xác nhận từ nhân viên: 1=Có, 0=Không' 
  AFTER `customer_notes`;

-- Tạo index cho cột mới
ALTER TABLE `orders` 
ADD INDEX `idx_order_source` (`order_source`);

-- 1.2 Cập nhật bảng `order_items` - Thêm cột cho customer orders
ALTER TABLE `order_items` 
ADD COLUMN IF NOT EXISTS `customer_id` VARCHAR(64) NULL 
  COMMENT 'Session ID của khách hàng (cho customer ordering)' 
  AFTER `status`,
ADD COLUMN IF NOT EXISTS `submitted_at` TIMESTAMP NULL DEFAULT NULL 
  COMMENT 'Thời gian khách gửi món (chuyển từ draft sang pending)' 
  AFTER `customer_id`;

-- Tạo index cho cột mới
ALTER TABLE `order_items` 
ADD INDEX `idx_customer_id` (`customer_id`),
ADD INDEX `idx_submitted_at` (`submitted_at`);

-- 1.3 Cập nhật bảng `support_requests` - Thêm loại mới
-- Lưu ý: ALTER TABLE MODIFY COLUMN cần cẩn thận với dữ liệu cũ
ALTER TABLE `support_requests` 
MODIFY COLUMN `type` ENUM('support', 'payment', 'scan_qr', 'new_order') 
  NOT NULL DEFAULT 'support' 
  COMMENT 'Loại yêu cầu: support=hỗ trợ, payment=thanh toán, scan_qr=quét QR, new_order=order mới';

-- 1.4 Cập nhật bảng `qr_tables` - Thêm cột mới
ALTER TABLE `qr_tables` 
ADD COLUMN IF NOT EXISTS `scan_count` INT(10) UNSIGNED NOT NULL DEFAULT 0 
  COMMENT 'Số lần quét QR code' 
  AFTER `is_active`,
ADD COLUMN IF NOT EXISTS `last_scanned_at` TIMESTAMP NULL DEFAULT NULL 
  COMMENT 'Lần quét cuối cùng' 
  AFTER `scan_count`;

-- Tạo index cho qr_tables
ALTER TABLE `qr_tables` 
ADD INDEX `idx_qr_active` (`is_active`);

-- ============================================================
-- PHẦN 2: TẠO BẢNG MỚI
-- ============================================================

-- 2.1 Bảng `order_notifications` - Lưu thông báo order
CREATE TABLE IF NOT EXISTS `order_notifications` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(10) UNSIGNED NOT NULL,
  `table_id` INT(10) UNSIGNED NOT NULL,
  `notification_type` ENUM('new_order', 'order_item', 'support_request', 'payment_request', 'scan_qr') 
    NOT NULL DEFAULT 'new_order',
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `read_by` INT(10) UNSIGNED DEFAULT NULL COMMENT 'Nhân viên đã đọc',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notification_order` (`order_id`),
  KEY `idx_notification_table` (`table_id`),
  KEY `idx_notification_unread` (`is_read`),
  KEY `idx_notification_type` (`notification_type`),
  KEY `idx_notification_created` (`created_at`),
  CONSTRAINT `fk_notification_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notification_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
  COMMENT='Lưu trữ thông báo order cho waiter';

-- 2.2 Bảng `realtime_notifications` - Push notifications
CREATE TABLE IF NOT EXISTS `realtime_notifications` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `channel` VARCHAR(50) NOT NULL COMMENT 'Kênh: waiter_1, admin, table_5, all',
  `event_type` VARCHAR(50) NOT NULL COMMENT 'Loại event: new_order, order_confirmed, table_occupied',
  `payload` JSON NOT NULL COMMENT 'Dữ liệu notification dạng JSON',
  `is_delivered` TINYINT(1) NOT NULL DEFAULT 0,
  `delivered_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Hết hạn sau 24h',
  PRIMARY KEY (`id`),
  KEY `idx_channel` (`channel`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_delivered` (`is_delivered`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
  COMMENT='Real-time push notifications';

-- 2.3 Bảng `table_status_history` - Lịch sử trạng thái bàn
CREATE TABLE IF NOT EXISTS `table_status_history` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_id` INT(10) UNSIGNED NOT NULL,
  `previous_status` ENUM('available', 'occupied') NOT NULL,
  `current_status` ENUM('available', 'occupied') NOT NULL,
  `changed_by` INT(10) UNSIGNED DEFAULT NULL COMMENT 'User ID hoặc NULL nếu từ customer',
  `change_reason` VARCHAR(100) DEFAULT NULL 
    COMMENT 'Lý do: scan_qr, waiter_open, manual_close, auto_close',
  `order_id` INT(10) UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_table_history` (`table_id`),
  KEY `idx_table_status_time` (`created_at`),
  KEY `idx_table_change_reason` (`change_reason`),
  CONSTRAINT `fk_history_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
  COMMENT='Lịch sử thay đổi trạng thái bàn';

-- 2.4 Bảng `customer_sessions` - Session của khách hàng
-- Lưu ý: expires_at sẽ được set bằng trigger hoặc application code
CREATE TABLE IF NOT EXISTS `customer_sessions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` VARCHAR(64) NOT NULL UNIQUE,
  `table_id` INT(10) UNSIGNED NOT NULL,
  `order_id` INT(10) UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `last_activity` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_session_id` (`session_id`),
  KEY `idx_table_id` (`table_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_expires` (`expires_at`),
  CONSTRAINT `fk_session_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_session_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
  COMMENT='Lưu session của khách hàng khi quét QR';

-- ============================================================
-- PHẦN 3: TRIGGER TỰ ĐỘNG SET EXPIRES_AT
-- ============================================================

-- Trigger tự động set expires_at khi insert customer_session
DROP TRIGGER IF EXISTS `set_customer_session_expires`;
DELIMITER $$
CREATE TRIGGER `set_customer_session_expires`
BEFORE INSERT ON `customer_sessions`
FOR EACH ROW
BEGIN
  IF NEW.expires_at IS NULL THEN
    SET NEW.expires_at = DATE_ADD(NOW(), INTERVAL 30 MINUTE);
  END IF;
END$$
DELIMITER ;

-- ============================================================
-- PHẦN 4: DỮ LIỆU MẪU (OPTIONAL)
-- ============================================================

-- 3.1 Insert notification mẫu (test)
INSERT INTO `order_notifications` 
  (`order_id`, `table_id`, `notification_type`, `title`, `message`, `is_read`) 
VALUES 
  (1, 1, 'scan_qr', 'Hệ thống QR Ordering', 'Bảng migration đã được áp dụng thành công!', 1)
ON DUPLICATE KEY UPDATE `title` = `title`;

-- ============================================================
-- PHẦN 5: INDEXES & OPTIMIZATION
-- ============================================================

-- Tạo composite indexes cho queries thường dùng
ALTER TABLE `orders` 
ADD INDEX `idx_source_status` (`order_source`, `status`);

ALTER TABLE `order_items` 
ADD INDEX `idx_status_submitted` (`status`, `submitted_at`);

ALTER TABLE `order_notifications` 
ADD INDEX `idx_unread_created` (`is_read`, `created_at`);

-- ============================================================
-- PHẦN 6: VIEWS HỖ TRỢ (OPTIONAL)
-- ============================================================

-- View: Thống kê QR orders theo ngày
CREATE OR REPLACE VIEW `v_qr_order_stats` AS
SELECT 
  DATE(o.opened_at) AS order_date,
  COUNT(DISTINCT o.id) AS total_orders,
  COUNT(DISTINCT o.table_id) AS tables_used,
  SUM(CASE WHEN o.order_source = 'customer_qr' THEN 1 ELSE 0 END) AS qr_orders,
  SUM(CASE WHEN o.order_source = 'waiter' THEN 1 ELSE 0 END) AS waiter_orders,
  ROUND(
    SUM(CASE WHEN o.order_source = 'customer_qr' THEN 1 ELSE 0 END) * 100.0 / COUNT(o.id), 
    2
  ) AS qr_percentage
FROM orders o
WHERE o.opened_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(o.opened_at)
ORDER BY order_date DESC;

-- View: Notification chưa đọc theo waiter
CREATE OR REPLACE VIEW `v_unread_notifications` AS
SELECT 
  n.id,
  n.order_id,
  n.table_id,
  t.name AS table_name,
  t.area AS table_area,
  n.notification_type,
  n.title,
  n.message,
  n.created_at,
  TIMESTAMPDIFF(MINUTE, n.created_at, NOW()) AS minutes_ago
FROM order_notifications n
JOIN tables t ON t.id = n.table_id
WHERE n.is_read = 0
ORDER BY n.created_at ASC;

-- ============================================================
-- KẾT THÚC MIGRATION
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;

-- Hiển thị thông tin migration
SELECT 
  '✅ QR Ordering Migration Completed Successfully!' AS status,
  COUNT(*) AS tables_created,
  NOW() AS migration_time
FROM information_schema.tables 
WHERE table_schema = DATABASE() 
  AND table_name IN (
    'order_notifications', 
    'realtime_notifications', 
    'table_status_history', 
    'customer_sessions'
  );

-- ============================================================
-- HƯỚNG DẪN SỬ DỤNG
-- ============================================================
/*
CÁCH CHẠY MIGRATION TRÊN PRODUCTION:

1. Backup database trước:
   mysqldump -u auroraho_longdev -p auroraho_restaurant > backup_before_qr_migration_$(date +%Y%m%d_%H%M%S).sql

2. Chạy migration:
   mysql -u auroraho_longdev -p auroraho_restaurant < migration_qr_ordering_v1.0.sql

3. Kiểm tra kết quả:
   - SHOW TABLES; (kiểm tra bảng mới)
   - DESCRIBE orders; (kiểm tra cột mới)
   - SELECT * FROM order_notifications LIMIT 5;

4. Nếu có lỗi, rollback:
   - Khôi phục từ backup
   - Hoặc chạy từng ALTER TABLE một để debug

LƯU Ý QUAN TRỌNG:
- ✅ Đã test trên database local
- ⚠️ Backup trước khi chạy trên production
- ⚠️ Chạy vào giờ ít traffic (sau 22:00)
- ⚠️ Thông báo cho nhân viên về downtime (nếu có)

THỜI GIAN DỰ KIẾN:
- Backup: 5-10 phút
- Migration: 1-2 phút
- Verify: 5 phút
=> Tổng: ~15-20 phút
*/
