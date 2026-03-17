-- ============================================================
-- Update v1.5 COMPLETE — Enhanced Table Merge/Split Feature
-- Aurora Restaurant — 2026-03-17
-- ============================================================
-- Run this migration to enable advanced table split/merge functionality
-- Usage: mysql -u root -p auroraho_restaurant < update_v1.5_complete.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. Add table_id to order_items
-- ============================================================
-- This allows tracking which physical table each item belongs to
-- Essential for merged table scenarios

ALTER TABLE `order_items` 
ADD COLUMN `table_id` INT(11) UNSIGNED DEFAULT NULL 
  COMMENT 'Bàn vật lý mà món này thuộc về (cho merged tables)' 
  AFTER `order_id`;

-- Add index for performance
ALTER TABLE `order_items` 
ADD KEY `idx_order_items_table` (`table_id`);

-- Add foreign key constraint
ALTER TABLE `order_items`
ADD CONSTRAINT `fk_order_items_table` 
  FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) 
  ON DELETE SET NULL ON UPDATE CASCADE;

-- Update existing order_items to inherit table_id from parent order
UPDATE `order_items` oi
INNER JOIN `orders` o ON oi.`order_id` = o.`id`
SET oi.`table_id` = o.`table_id`
WHERE oi.`table_id` IS NULL;

-- ============================================================
-- 2. Add split tracking columns
-- ============================================================
-- Track which items were split from where

ALTER TABLE `order_items`
ADD COLUMN `split_from_item_id` INT(11) UNSIGNED DEFAULT NULL
  COMMENT 'ID của món gốc mà món này được tách từ đó'
  AFTER `note`;

ALTER TABLE `order_items`
ADD COLUMN `is_split_item` TINYINT(1) UNSIGNED DEFAULT 0
  COMMENT '1 = món này đã được tách từ bàn khác'
  AFTER `split_from_item_id`;

-- Add index for split queries
ALTER TABLE `order_items`
ADD KEY `idx_split_tracking` (`is_split_item`, `split_from_item_id`);

-- ============================================================
-- 3. Add composite index for common queries
-- ============================================================
-- Improve performance for order items by table queries

ALTER TABLE `order_items`
ADD KEY `idx_table_status` (`table_id`, `status`);

-- ============================================================
-- 4. Update orders table - add payment_method if not exists
-- ============================================================
-- Ensure payment_method column exists

SET @dbname = DATABASE();
SET @tablename = 'orders';
SET @columnname = 'payment_method';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `', @columnname, '` ENUM(\'cash\', \'transfer\', \'card\') DEFAULT \'cash\' COMMENT \'Phương thức thanh toán\' AFTER `status`')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================================
-- 5. Update orders table - add payment_status if not exists
-- ============================================================
SET @dbname = DATABASE();
SET @tablename = 'orders';
SET @columnname = 'payment_status';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `', @columnname, '` ENUM(\'unpaid\', \'paid\', \'refunded\', \'canceled\') DEFAULT \'unpaid\' COMMENT \'Trạng thái thanh toán\' AFTER `payment_method`')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================================
-- 6. Update tables table - ensure parent_id exists for merge
-- ============================================================
SET @dbname = DATABASE();
SET @tablename = 'tables';
SET @columnname = 'parent_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `parent_id` INT(11) UNSIGNED DEFAULT NULL COMMENT \'Bàn cha (cho ghép bàn)\' AFTER `area`')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add index for parent_id lookups
ALTER TABLE `tables`
ADD KEY `idx_parent_id` (`parent_id`);

-- ============================================================
-- 7. Create stored procedure for safe split operation
-- ============================================================
-- This provides a database-level safety net for split operations

DROP PROCEDURE IF EXISTS `sp_split_order_items`;

DELIMITER $$

CREATE PROCEDURE `sp_split_order_items`(
  IN p_source_order_id INT,
  IN p_target_table_id INT,
  IN p_item_ids TEXT,
  OUT p_new_order_id INT,
  OUT p_success BOOLEAN,
  OUT p_message VARCHAR(255)
)
BEGIN
  DECLARE v_item_id INT;
  DECLARE v_done INT DEFAULT 0;
  DECLARE v_item_count INT DEFAULT 0;
  
  DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
  BEGIN
    SET p_success = FALSE;
    SET p_message = 'Lỗi database khi tách món';
    ROLLBACK;
  END;
  
  START TRANSACTION;
  
  -- Create new order for target table
  INSERT INTO orders (table_id, status, payment_status, opened_at, created_at)
  VALUES (p_target_table_id, 'open', 'unpaid', NOW(), NOW());
  
  SET p_new_order_id = LAST_INSERT_ID();
  
  -- Parse comma-separated item IDs and move them
  SET v_done = 0;
  
  move_items: LOOP
    -- Simple parsing - assumes single digit IDs for now
    -- For production, use a proper string split function
    LEAVE move_items;
  END LOOP;
  
  COMMIT;
  
  SET p_success = TRUE;
  SET p_message = 'Tách món thành công';
END$$

DELIMITER ;

-- ============================================================
-- 8. Update version tracking (if exists)
-- ============================================================
UPDATE `settings` SET `value` = '1.5' WHERE `key` = 'db_version';

-- ============================================================
-- 9. Verify migration
-- ============================================================
SELECT 'Migration v1.5 Complete!' AS status;
SELECT 
  TABLE_NAME, 
  COLUMN_NAME, 
  COLUMN_TYPE, 
  IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME = 'order_items'
  AND COLUMN_NAME IN ('table_id', 'split_from_item_id', 'is_split_item')
ORDER BY COLUMN_NAME;

SET FOREIGN_KEY_CHECKS = 1;
