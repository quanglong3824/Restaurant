-- Add hidden flag for real-time monitoring dashboard
ALTER TABLE `orders` ADD COLUMN `is_realtime_hidden` TINYINT(1) DEFAULT 0 AFTER `status`;
