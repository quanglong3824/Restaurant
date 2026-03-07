-- Update v1.3: Support Requests & Notifications
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Bảng Yêu cầu hỗ trợ (Support Requests)
CREATE TABLE IF NOT EXISTS `support_requests` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `table_id`   INT UNSIGNED NOT NULL,
    `type`       ENUM('support', 'payment') NOT NULL DEFAULT 'support',
    `status`     ENUM('pending', 'completed') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_support_table` FOREIGN KEY (`table_id`) REFERENCES `tables`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
