-- Update v1.1: Menu Classification & Table Merging
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Phân loại Menu
ALTER TABLE `menu_categories` 
ADD COLUMN `menu_type` ENUM('asia', 'europe', 'alacarte', 'other') DEFAULT 'asia' AFTER `name_en`;

-- Cập nhật dữ liệu mẫu cho Menu Type
UPDATE `menu_categories` SET `menu_type` = 'asia' WHERE `name` LIKE '%Á%';
UPDATE `menu_categories` SET `menu_type` = 'europe' WHERE `name` LIKE '%Âu%';
UPDATE `menu_categories` SET `menu_type` = 'alacarte' WHERE `name` NOT LIKE '%Á%' AND `name` NOT LIKE '%Âu%';

-- 2. Ghép bàn & Ghép phòng VIP
ALTER TABLE `tables` 
ADD COLUMN `parent_id` INT UNSIGNED DEFAULT NULL AFTER `id`,
ADD CONSTRAINT `fk_tables_parent` FOREIGN KEY (`parent_id`) REFERENCES `tables`(`id`) ON DELETE SET NULL;

SET FOREIGN_KEY_CHECKS = 1;
