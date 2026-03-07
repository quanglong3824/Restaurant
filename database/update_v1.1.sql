-- Update v1.1: Menu Classification & Table Merging (Safe version)
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Phân loại Menu
-- Lưu ý: Nếu cột menu_type đã tồn tại, hãy bỏ qua khối ALTER này
DROP PROCEDURE IF EXISTS AddMenuTypeColumn;
DELIMITER //
CREATE PROCEDURE AddMenuTypeColumn()
BEGIN
    IF NOT EXISTS (
        SELECT * FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'menu_categories' 
        AND COLUMN_NAME = 'menu_type'
    ) THEN
        ALTER TABLE `menu_categories` 
        ADD COLUMN `menu_type` ENUM('asia', 'europe', 'alacarte', 'other') DEFAULT 'asia' AFTER `name_en`;
    END IF;
END //
DELIMITER ;
CALL AddMenuTypeColumn();
DROP PROCEDURE AddMenuTypeColumn;

-- Cập nhật dữ liệu mẫu cho Menu Type
UPDATE `menu_categories` SET `menu_type` = 'asia' WHERE `name` LIKE '%Á%';
UPDATE `menu_categories` SET `menu_type` = 'europe' WHERE `name` LIKE '%Âu%';
UPDATE `menu_categories` SET `menu_type` = 'alacarte' WHERE `name` NOT LIKE '%Á%' AND `name` NOT LIKE '%Âu%';

-- 2. Ghép bàn & Ghép phòng VIP
DROP PROCEDURE IF EXISTS AddTableParentColumn;
DELIMITER //
CREATE PROCEDURE AddTableParentColumn()
BEGIN
    IF NOT EXISTS (
        SELECT * FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'tables' 
        AND COLUMN_NAME = 'parent_id'
    ) THEN
        ALTER TABLE `tables` 
        ADD COLUMN `parent_id` INT UNSIGNED DEFAULT NULL AFTER `id`,
        ADD CONSTRAINT `fk_tables_parent` FOREIGN KEY (`parent_id`) REFERENCES `tables`(`id`) ON DELETE SET NULL;
    END IF;
END //
DELIMITER ;
CALL AddTableParentColumn();
DROP PROCEDURE AddTableParentColumn;

SET FOREIGN_KEY_CHECKS = 1;
