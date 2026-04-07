-- ============================================================
-- Migration: Activity Logs Table
-- Aurora Restaurant - Nhật ký hoạt động hệ thống
-- ============================================================

-- Tạo bảng activity_logs
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL COMMENT 'Hành động thực hiện (login, create, update, delete...)',
  `entity` varchar(50) NOT NULL COMMENT 'Thực thể bị tác động (user, table, order, menu_item...)',
  `entity_id` int(10) unsigned DEFAULT NULL COMMENT 'ID của thực thể',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT 'ID người thực hiện (NULL = system)',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP address',
  `user_agent` text DEFAULT NULL COMMENT 'User agent string',
  `request_uri` varchar(500) DEFAULT NULL COMMENT 'URI yêu cầu',
  `request_method` varchar(10) DEFAULT 'GET' COMMENT 'HTTP method',
  `metadata` text DEFAULT NULL COMMENT 'Dữ liệu metadata (JSON)',
  `level` enum('info','notice','warning','error','critical') NOT NULL DEFAULT 'info' COMMENT 'Mức độ quan trọng',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời điểm ghi log',
  PRIMARY KEY (`id`),
  KEY `idx_action` (`action`),
  KEY `idx_entity` (`entity`, `entity_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_level` (`level`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhật ký hoạt động hệ thống';

-- Insert sample data (optional - for testing)
-- INSERT INTO `activity_logs` (`action`, `entity`, `user_id`, `metadata`, `level`, `created_at`) VALUES
-- ('system', 'activity_logs', NULL, '{"message": "Activity logs table created"}', 'info', NOW()),
-- ('login', 'user', 1, '{"success": true}', 'info', NOW()),
-- ('create', 'menu_item', 1, '{"name": "Sample Item"}', 'info', NOW()),
-- ('update', 'table', 1, '{"status": "occupied"}', 'notice', NOW()),
-- ('error', 'order', NULL, '{"message": "Payment failed"}', 'error', NOW());

-- Tạo view cho thống kê nhanh (optional)
DROP VIEW IF EXISTS `v_activity_stats_today`;
CREATE VIEW `v_activity_stats_today` AS
SELECT 
    COUNT(*) AS total_logs,
    SUM(CASE WHEN level = 'error' THEN 1 ELSE 0 END) AS error_count,
    SUM(CASE WHEN level = 'warning' THEN 1 ELSE 0 END) AS warning_count,
    SUM(CASE WHEN level = 'info' THEN 1 ELSE 0 END) AS info_count,
    COUNT(DISTINCT user_id) AS unique_users
FROM activity_logs
WHERE DATE(created_at) = CURDATE();

-- Tạo view cho hoạt động theo ngày (7 ngày gần nhất)
DROP VIEW IF EXISTS `v_activity_by_date`;
CREATE VIEW `v_activity_by_date` AS
SELECT 
    DATE(created_at) AS log_date,
    action,
    entity,
    level,
    COUNT(*) AS action_count
FROM activity_logs
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY DATE(created_at), action, entity, level
ORDER BY log_date DESC, action_count DESC;

-- Grant permissions (adjust as needed)
-- GRANT SELECT, INSERT ON activity_logs TO 'restaurant_user'@'localhost';
-- GRANT SELECT, INSERT, DELETE ON activity_logs TO 'restaurant_admin'@'localhost';