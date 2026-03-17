-- ============================================================
-- Update v1.5 SAFE — Enhanced Table Merge/Split Feature
-- Aurora Restaurant — 2026-03-17
-- ============================================================
-- Safe migration - only adds columns if they don't exist
-- Usage: Run in phpMyAdmin or: mysql -u root -p auroraho_restaurant < update_v1.5_safe.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. Add table_id to order_items (if not exists)
-- ============================================================
SET @dbname = DATABASE();
SET @tablename = 'order_items';
SET @columnname = 'table_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT \'Column table_id already exists - skipping\' AS message',
  'ALTER TABLE `order_items` ADD COLUMN `table_id` INT(11) UNSIGNED DEFAULT NULL COMMENT \'Bàn vật lý mà món này thuộc về (cho merged tables)\' AFTER `order_id`'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add index for table_id (if not exists)
SET @indexname = 'idx_order_items_table';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE table_schema = DATABASE()
      AND table_name = 'order_items'
      AND index_name = @indexname
  ) > 0,
  'SELECT \'Index idx_order_items_table already exists - skipping\' AS message',
  'ALTER TABLE `order_items` ADD KEY `idx_order_items_table` (`table_id`)'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add foreign key for table_id (if not exists)
SET @fkname = 'fk_order_items_table';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND CONSTRAINT_NAME = @fkname
      AND TABLE_NAME = 'order_items'
  ) > 0,
  'SELECT \'Foreign key fk_order_items_table already exists - skipping\' AS message',
  'ALTER TABLE `order_items` ADD CONSTRAINT `fk_order_items_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL ON UPDATE CASCADE'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Update existing order_items to inherit table_id from parent order
UPDATE `order_items` oi
INNER JOIN `orders` o ON oi.`order_id` = o.`id`
SET oi.`table_id` = o.`table_id`
WHERE oi.`table_id` IS NULL;

-- ============================================================
-- 2. Add split_from_item_id (if not exists)
-- ============================================================
SET @columnname = 'split_from_item_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT \'Column split_from_item_id already exists - skipping\' AS message',
  'ALTER TABLE `order_items` ADD COLUMN `split_from_item_id` INT(11) UNSIGNED DEFAULT NULL COMMENT \'ID của món gốc mà món này được tách từ đó\' AFTER `note`'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================================
-- 3. Add is_split_item (if not exists)
-- ============================================================
SET @columnname = 'is_split_item';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT \'Column is_split_item already exists - skipping\' AS message',
  'ALTER TABLE `order_items` ADD COLUMN `is_split_item` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT \'1 = món này đã được tách từ bàn khác\' AFTER `split_from_item_id`'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add composite index for split tracking
SET @indexname = 'idx_split_tracking';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE table_schema = DATABASE()
      AND table_name = 'order_items'
      AND index_name = @indexname
  ) > 0,
  'SELECT \'Index idx_split_tracking already exists - skipping\' AS message',
  'ALTER TABLE `order_items` ADD KEY `idx_split_tracking` (`is_split_item`, `split_from_item_id`)'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add composite index for table_status
SET @indexname = 'idx_table_status';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE table_schema = DATABASE()
      AND table_name = 'order_items'
      AND index_name = @indexname
  ) > 0,
  'SELECT \'Index idx_table_status already exists - skipping\' AS message',
  'ALTER TABLE `order_items` ADD KEY `idx_table_status` (`table_id`, `status`)'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================================
-- 4. Ensure parent_id exists in tables (for merge)
-- ============================================================
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
  'SELECT \'Column parent_id already exists in tables - skipping\' AS message',
  'ALTER TABLE `tables` ADD COLUMN `parent_id` INT(11) UNSIGNED DEFAULT NULL COMMENT \'Bàn cha (cho ghép bàn)\' AFTER `area`'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add index for parent_id
SET @indexname = 'idx_parent_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE table_schema = DATABASE()
      AND table_name = 'tables'
      AND index_name = @indexname
  ) > 0,
  'SELECT \'Index idx_parent_id already exists - skipping\' AS message',
  'ALTER TABLE `tables` ADD KEY `idx_parent_id` (`parent_id`)'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================================
-- 5. Verify migration
-- ============================================================
SELECT '=== Migration v1.5 SAFE Complete! ===' AS status;
SELECT 'Checking order_items columns...' AS step;

SELECT 
  COLUMN_NAME, 
  COLUMN_TYPE,
  IS_NULLABLE,
  COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME = 'order_items'
  AND COLUMN_NAME IN ('table_id', 'split_from_item_id', 'is_split_item')
ORDER BY ORDINAL_POSITION;

SELECT 'Checking indexes...' AS step;

SELECT 
  INDEX_NAME,
  COLUMN_NAME,
  SEQ_IN_INDEX
FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'order_items'
  AND INDEX_NAME IN ('idx_order_items_table', 'idx_split_tracking', 'idx_table_status')
ORDER BY INDEX_NAME, SEQ_IN_INDEX;

SET FOREIGN_KEY_CHECKS = 1;

SELECT '=== Done! You can now use split/merge features ===' AS message;
