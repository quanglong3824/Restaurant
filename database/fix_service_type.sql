-- ============================================================
-- Fix service_type for existing menu items
-- Run this on production database to fix empty service_type values
-- ============================================================

-- Items in room service categories (Cơm & Xôi có room service variants)
-- Set room_service for specific items that have "(Room Service)" in name
UPDATE menu_items SET service_type = 'room_service' 
WHERE name LIKE '%(Room Service)%' OR name LIKE '%(Room Service - Chén)%';

-- Set 'both' for items that should be available in both restaurant and room service
-- Based on the menu structure, most items should be 'both' unless specifically room_service only
UPDATE menu_items SET service_type = 'both' 
WHERE service_type = '' AND name NOT LIKE '%(Room Service)%';

-- Verify the fix
SELECT 
    service_type, 
    COUNT(*) as count 
FROM menu_items 
GROUP BY service_type;

-- Show all items with their service_type
SELECT id, name, category_id, service_type 
FROM menu_items 
ORDER BY category_id, id;