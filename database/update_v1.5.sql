-- ============================================================
-- Update v1.5 — Enhanced Table Merge/Split Feature
-- Aurora Restaurant — 2026-03-17
-- ============================================================

-- Add table_id to order_items to track which table each item belongs to
ALTER TABLE `order_items` 
ADD COLUMN `table_id` INT(11) DEFAULT NULL AFTER `order_id`,
ADD KEY `fk_order_items_table` (`table_id`),
ADD CONSTRAINT `fk_order_items_table` 
  FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) 
  ON DELETE SET NULL ON UPDATE CASCADE;

-- Update existing order_items to have table_id from orders table
UPDATE `order_items` oi
JOIN `orders` o ON oi.`order_id` = o.`id`
SET oi.`table_id` = o.`table_id`
WHERE oi.`table_id` IS NULL;

-- Add note for split/transfer tracking
ALTER TABLE `order_items`
ADD COLUMN `split_from_item_id` INT(11) DEFAULT NULL AFTER `note`,
ADD COLUMN `is_split_item` TINYINT(1) DEFAULT 0 AFTER `split_from_item_id`;

-- Add index for better query performance
ALTER TABLE `order_items`
ADD KEY `idx_table_status` (`table_id`, `status`);
