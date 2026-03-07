-- Update v1.2: Shifts & Advanced Auth
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Bảng Ca trực
CREATE TABLE IF NOT EXISTS `shifts` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(50) NOT NULL COMMENT 'Tên ca: Sáng, Chiều, Tối...',
    `start_time` TIME NOT NULL,
    `end_time`   TIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu ca mặc định
INSERT IGNORE INTO `shifts` (`id`, `name`, `start_time`, `end_time`) VALUES
(1, 'Ca Sáng', '06:00:00', '14:00:00'),
(2, 'Ca Chiều', '14:00:00', '22:00:00');

-- 2. Bảng Phân ca (Gán nhân viên vào ca trực theo ngày)
CREATE TABLE IF NOT EXISTS `user_shifts` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`    INT UNSIGNED NOT NULL,
    `shift_id`   INT UNSIGNED NOT NULL,
    `work_date`  DATE NOT NULL COMMENT 'Ngày làm việc',
    `note`       VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_user_shifts_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_shifts_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Cập nhật bảng Orders để ghi nhận ca trực khi tạo đơn
ALTER TABLE `orders` ADD COLUMN `shift_id` INT UNSIGNED DEFAULT NULL AFTER `waiter_id`;

SET FOREIGN_KEY_CHECKS = 1;
