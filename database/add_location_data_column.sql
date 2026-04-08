-- Migration: Add location_data column to customer_sessions table
-- Date: 2026-04-08
-- Purpose: Store customer location data for persistent session tracking

ALTER TABLE `customer_sessions` 
ADD COLUMN `location_data` TEXT DEFAULT NULL COMMENT 'JSON location data for customer tracking' 
AFTER `user_agent`;