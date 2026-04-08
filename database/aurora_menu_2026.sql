-- ============================================================
-- Aurora Hotel Plaza Restaurant - Menu Data 2026
-- Extracted from actual menu images
-- Date: 2026-04-08
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- Clear data in correct order (children first, then parents)
-- ============================================================
DELETE FROM `menu_items`;
DELETE FROM `menu_categories`;
DELETE FROM `menu_types`;

-- ============================================================
-- 1. MENU TYPES
-- ============================================================
INSERT INTO `menu_types` (`name`, `name_en`, `type_key`, `description`, `color`, `icon`, `sort_order`, `is_active`) VALUES
('Món Á', 'Asian Cuisine', 'asia', 'Các món ăn truyền thống châu Á', '#0ea5e9', 'fa-bowl-rice', 1, 1),
('Món Âu', 'European Cuisine', 'europe', 'Các món ăn phong cách châu Âu', '#8b5cf6', 'fa-wine-glass', 2, 1),
('Alacarte', 'Alacarte', 'alacarte', 'Các món gọi riêng', '#f59e0b', 'fa-utensils', 3, 1),
('Đồ Uống', 'Beverages', 'other', 'Đồ uống các loại', '#10b981', 'fa-cocktail', 4, 1);

-- ============================================================
-- 2. MENU CATEGORIES
-- ============================================================
INSERT INTO `menu_categories` (`name`, `name_en`, `menu_type`, `icon`, `sort_order`, `is_active`) VALUES
-- Món Á (Appetizer, Soup, Salad, Rice, Noodle, Porridge)
('Khai Vị', 'Appetizer', 'asia', 'fa-utensils', 1, 1),
('Súp', 'Soup', 'asia', 'fa-bowl-food', 2, 1),
('Gỏi', 'Vietnamese Salad', 'asia', 'fa-leaf', 3, 1),
('Cơm & Xôi', 'Rice, Sticky Rice', 'asia', 'fa-bowl-rice', 4, 1),
('Mì & Bún', 'Noodle', 'asia', 'fa-utensils', 5, 1),
('Cháo', 'Rice Porridge', 'asia', 'fa-spoon', 6, 1),
-- Món Âu (Soup, Salad, Spaghetti, Sandwich, Main Course, Dessert)
('Súp Âu', 'Soup', 'europe', 'fa-bowl-food', 1, 1),
('Xà Lách', 'Salad', 'europe', 'fa-leaf', 2, 1),
('Mì Ý', 'Spaghetti', 'europe', 'fa-spaghety-monster', 3, 1),
('Sandwich', 'Sandwich', 'europe', 'fa-bread-slice', 4, 1),
('Món Chính', 'Main Course', 'europe', 'fa-drumstick-bite', 5, 1),
('Tráng Miệng', 'Dessert', 'europe', 'fa-ice-cream', 6, 1),
-- Side Dish
('Món Phụ', 'Sidedish', 'alacarte', 'fa-utensils', 1, 1),
-- Đồ Uống
('Cà Phê', 'Coffee', 'other', 'fa-mug-hot', 1, 1),
('Nước Ép', 'Fruit Juice', 'other', 'fa-glass-whiskey', 2, 1),
('Bia', 'Beer', 'other', 'fa-beer', 3, 1),
('Nước Ngọt', 'Soft Drink', 'other', 'fa-bottle-water', 4, 1),
('Nước Suối', 'Mineral Water', 'other', 'fa-wine-bottle', 5, 1),
('Trà', 'Tea Collection', 'other', 'fa-flask', 6, 1);

-- ============================================================
-- 3. MENU ITEMS - From Menu Images
-- ============================================================
INSERT INTO `menu_items` (`category_id`, `name`, `name_en`, `description`, `description_en`, `price`, `image`, `is_available`, `service_type`, `tags`, `note_options`, `note_options_en`) VALUES

-- ========== APPETIZER / KHAI VỊ (Category 1) ==========
(1, 'Phở Cuốn', 'Rice Pancake Rolls with Beef', 'Phở cuốn với bò', 'Rice pancake rolls with beef', 179000, NULL, 1, 'restaurant', '', '', ''),
(1, 'Bò Cuốn Lá Xanh', 'Mustard Leaf Rolls with Beef', 'Bò cuốn lá xanh mù tạt', 'Mustard leaf rolls with beef', 179000, NULL, 1, 'restaurant', 'recommended', '', ''),
(1, 'Gỏi Ngó Sen Đưa Tôm Thịt', 'Lotus Stems Salad with Shrimp & Pork', 'Gỏi ngó sen tôm thịt', 'Lotus stems salad with shrimp and pork', 180000, NULL, 1, 'restaurant', '', '', ''),
(1, 'Gỏi Củ Hủ Dừa Tôm Thịt', 'Coconut Palm Salad with Shrimp & Pork', 'Gỏi củ hủ dừa tôm thịt', 'Coconut palm salad with shrimp and pork', 180000, NULL, 1, 'restaurant', '', '', ''),
(1, 'Gỏi Cuốn Tôm Thịt', 'Fresh Spring Rolls with Shrimp & Pork', 'Gỏi cuốn tôm thịt tươi', 'Fresh spring rolls with shrimp and pork', 135000, NULL, 1, 'restaurant', 'bestseller', '', ''),
(1, 'Chả Giò Hải Sản Aurora', 'Aurora Deep Fried Seafood Spring Rolls', 'Chả giò hải sản Aurora', 'Aurora deep fried seafood spring rolls', 135000, NULL, 1, 'restaurant', 'bestseller', '', ''),

-- ========== SOUP / SÚP (Category 2) ==========
(2, 'Súp Bào Ngư Hải Sản', 'Abalone Seafood Soup', 'Súp bào ngư hải sản', 'Abalone seafood soup', 130000, NULL, 1, 'restaurant', '', '', ''),
(2, 'Súp Cua Hải Sản Tam Tố', 'Tam To Crab Meat & Seafood Soup', 'Súp cua hải sản tam tố', 'Crab meat and seafood soup', 130000, NULL, 1, 'restaurant', '', '', ''),
(2, 'Súp Kem Bí Đỏ', 'Pumpkin Soup', 'Súp kem bí đỏ', 'Pumpkin cream soup', 90000, NULL, 1, 'restaurant', '', '', ''),
(2, 'Súp Kem Nấm', 'Creamy Mushroom Soup', 'Súp kem nấm tươi', 'Creamy mushroom soup with assorted mushrooms', 95000, NULL, 1, 'restaurant', '', '', ''),
(2, 'Súp Khoai Tây Thịt Nguội', 'Ham & Potato Soup', 'Súp khoai tây thịt nguội', 'Potato ham chicken broth soup', 90000, NULL, 1, 'restaurant', '', '', ''),
(2, 'Súp Kem Nấm (Room Service)', 'Creamy Mushroom Soup', 'Súp kem nấm với sữa tươi, whipping cream', 'Creamy mushroom soup with fresh milk, whipping cream, chicken broth', 115000, NULL, 1, 'room_service', '', '', ''),
(2, 'Súp Khoai Tây Thịt Nguội (Room Service)', 'Ham & Potato Soup', 'Súp khoai tây thịt nguội', 'Potato ham chicken broth soup', 115000, NULL, 1, 'room_service', '', '', ''),

-- ========== GỎI / SALAD (Category 3) ==========
(3, 'Gỏi Xà Lách Trộn Kiểu Pháp', 'Caesar Salad', 'Xà lách Romaine, gà, bacon, phô mai Parmesan', 'Romaine lettuce, chicken, bacon, Parmesan cheese, croutons, dressing', 135000, NULL, 1, 'restaurant', '', '', ''),
(3, 'Xà Lách Cá Ngừ Kiểu Pháp', 'Nicoise Salad', 'Xà lách cá ngừ kiểu Pháp', 'Romaine lettuce, potato, olive, cherry tomato, tuna, egg, balsamic dressing', 135000, NULL, 1, 'restaurant', '', '', ''),
(3, 'Xà Lách Gà Nướng', 'Grilled Chicken Salad', 'Xà lách gà nướng', 'Assorted lettuce, tomato, cucumber, olive, green capsicum, grilled chicken, oil vinegar dressing', 110000, NULL, 1, 'restaurant', '', '', ''),
(3, 'Gỏi Xà Lách Trộn', 'Mixed Salad', 'Xà lách trộn dầu giấm', 'Mixed salad with vinegar dressing', 40000, NULL, 1, 'restaurant', '', '', ''),
(3, 'Gỏi Xà Lách Trứng Luộc', 'Mixed Salad & Boiled Egg', 'Xà lách trộn dầu giấm trứng luộc', 'Mixed salad with vinegar dressing and boiled egg', 55000, NULL, 1, 'restaurant', '', '', ''),
(3, 'Xà Lách Trộn (Room Service)', 'Caesar Salad', 'Xà lách trộn kiểu Pháp', 'Romaine lettuce, chicken, bacon, Parmesan cheese, croutons, dressing', 145000, NULL, 1, 'room_service', '', '', ''),
(3, 'Xà Lách Gà Nướng (Room Service)', 'Grilled Chicken Salad', 'Xà lách gà nướng', 'Assorted lettuce, tomato, cucumber, olive, green capsicum, grilled chicken', 135000, NULL, 1, 'room_service', '', '', ''),

-- ========== CƠM & XÔI (Category 4) ==========
(4, 'Xôi Xéo', 'Steamed Sticky Rice with Green Beans', 'Xôi xéo đậu xanh', 'Steamed sticky rice with mung beans', 45000, NULL, 1, 'restaurant', '', '', ''),
(4, 'Cơm Trắng', 'Steamed Rice', 'Cơm trắng', 'Steamed white rice', 20000, NULL, 1, 'restaurant', '', '', ''),
(4, 'Cơm Trắng / Thố', 'Steamed Rice / Big Bowl', 'Cơm trắng thố lớn', 'Steamed rice big bowl', 60000, NULL, 1, 'restaurant', '', '', ''),
(4, 'Cơm Chiên Cá Mặn Gà Xé', 'Fried Rice with Salted Fish & Shredded Chicken', 'Cơm chiên cá mặn gà xé', 'Fried rice with salted fish and shredded chicken', 105000, NULL, 1, 'restaurant', '', '', ''),
(4, 'Cơm Chiên Hải Sản Kim Sa', 'Seafood Fried Rice with Salted Egg', 'Cơm chiên hải sản kim sa', 'Seafood fried rice with salted egg', 105000, NULL, 1, 'restaurant', 'bestseller', '', ''),
(4, 'Cơm Chiên Thịt Xá Xíu Xốt XO', 'Fried Rice with Char Siu & XO Sauce', 'Cơm chiên thịt xá xíu xốt XO', 'Fried rice with char siu and XO sauce', 105000, NULL, 1, 'restaurant', '', '', ''),
(4, 'Cơm Trắng (Room Service - Chén)', 'Steamed Rice', 'Cơm trắng chén', 'Steamed rice small bowl', 25000, NULL, 1, 'room_service', '', '', ''),
(4, 'Cơm Chiên Cá Mặn Gà Xé (Room Service)', 'Fried Rice with Salted Fish & Shredded Chicken', 'Cơm chiên cá mặn gà xé', 'Fried rice with salted fish and shredded chicken', 110000, NULL, 1, 'room_service', '', '', ''),
(4, 'Cơm Chiên Hải Sản Kim Sa (Room Service)', 'Seafood Fried Rice with Salted Egg', 'Cơm chiên hải sản kim sa', 'Seafood fried rice with salted egg', 135000, NULL, 1, 'room_service', '', '', ''),
(4, 'Cơm Chiên Thịt Xá Xíu Xốt XO (Room Service)', 'Fried Rice with Char Siu & XO Sauce', 'Cơm chiên thịt xá xíu xốt XO', 'Fried rice with char siu and XO sauce', 135000, NULL, 1, 'room_service', '', '', ''),

-- ========== MÌ & BÚN (Category 5) ==========
(5, 'Bún Mọc Măng Dọc Mùng', 'Vietnamese Colocasia Gigantea Noodle Soup', 'Bún mọc măng dọc mùng', 'Vietnamese noodle soup with pork balls and bamboo shoot', 75000, NULL, 1, 'restaurant', '', '', ''),
(5, 'Bún Ốc Hà Nội', 'Vietnamese Noodle Soup with Snail', 'Bún ốc Hà Nội', 'Vietnamese noodle soup with snail Ha Noi style', 70000, NULL, 1, 'restaurant', '', '', ''),
(5, 'Bún Cá Hà Nội', 'Vietnamese Fish Noodle Soup', 'Bún cá Hà Nội', 'Vietnamese fish noodle soup Ha Noi style', 75000, NULL, 1, 'restaurant', '', '', ''),
(5, 'Bún Xào Singapore', 'Stir Fried Rice Noodle Singapore Style', 'Bún xào Singapore', 'Stir fried rice noodle Singapore style', 185000, NULL, 1, 'restaurant', '', '', ''),
(5, 'Miến Xào Hàn Quốc', 'Stir Fried Vermicelli with Beef & Vegetables', 'Miến xào Hàn Quốc', 'Stir fried vermicelli with beef and vegetables', 230000, NULL, 1, 'restaurant', '', '', ''),
(5, 'Mì Xào Tôm', 'Stir Fried Yellow Noodle with Shrimp', 'Mì xào tôm', 'Stir fried yellow noodle with shrimp', 140000, NULL, 1, 'restaurant', '', '', ''),
(5, 'Mì Xào Thịt Bò', 'Stir Fried Yellow Noodle with Beef', 'Mì xào thịt bò', 'Stir fried yellow noodle with beef', 190000, NULL, 1, 'restaurant', '', '', ''),
(5, 'Mì Xào Hải Sản', 'Stir Fried Yellow Noodle with Seafood', 'Mì xào hải sản', 'Stir fried yellow noodle with seafood', 165000, NULL, 1, 'restaurant', 'bestseller', '', ''),
(5, 'Bún Xào Singapore (Room Service)', 'Stir Fried Rice Noodle Singapore Style', 'Bún xào Singapore', 'Stir fried rice noodle Singapore style', 185000, NULL, 1, 'room_service', '', '', ''),
(5, 'Mì Xào Thịt Bò (Room Service)', 'Stir Fried Yellow Noodle with Beef', 'Mì xào thịt bò', 'Stir fried yellow noodle with beef', 190000, NULL, 1, 'room_service', '', '', ''),
(5, 'Mì Xào Tôm (Room Service)', 'Stir Fried Yellow Noodle with Shrimp', 'Mì xào tôm', 'Stir fried yellow noodle with shrimp', 160000, NULL, 1, 'room_service', '', '', ''),
(5, 'Mì Xào Hải Sản (Room Service)', 'Stir Fried Yellow Noodle with Seafood', 'Mì xào hải sản', 'Stir fried yellow noodle with seafood', 190000, NULL, 1, 'room_service', '', '', ''),

-- ========== CHÁO (Category 6) ==========
(6, 'Cháo Bò Bằm', 'Rice Porridge with Minced Beef', 'Cháo bò bằm', 'Rice porridge with minced beef', 115000, NULL, 1, 'restaurant', '', '', ''),
(6, 'Cháo Thịt Bằm', 'Rice Porridge with Minced Pork', 'Cháo thịt bằm', 'Rice porridge with minced pork', 65000, NULL, 1, 'restaurant', '', '', ''),
(6, 'Cháo Hải Sản', 'Rice Porridge with Seafood', 'Cháo hải sản', 'Rice porridge with seafood', 115000, NULL, 1, 'restaurant', '', '', ''),
(6, 'Cháo Bò Bằm (Room Service)', 'Rice Porridge with Minced Beef', 'Cháo bò bằm', 'Rice porridge with minced beef', 115000, NULL, 1, 'room_service', '', '', ''),
(6, 'Cháo Thịt Bằm (Room Service)', 'Rice Porridge with Minced Pork', 'Cháo thịt bằm', 'Rice porridge with minced pork', 90000, NULL, 1, 'room_service', '', '', ''),
(6, 'Cháo Hải Sản (Room Service)', 'Rice Porridge with Seafood', 'Cháo hải sản', 'Rice porridge with seafood', 155000, NULL, 1, 'room_service', '', '', ''),
(6, 'Cháo Lá Dứa Hột Vịt Muối', 'Rice Porridge with Salted Egg', 'Cháo lá dứa hột vịt muối', 'Rice porridge served with salted egg', 45000, NULL, 1, 'restaurant', '', '', ''),

-- ========== SÚP ÂU (Category 7) ==========
(7, 'Súp Kem Bí Đỏ', 'Pumpkin Soup', 'Súp kem bí đỏ với kem, phô mai, bánh mì', 'Pumpkin cream cheese soup served with crouton', 90000, NULL, 1, 'restaurant', '', '', ''),
(7, 'Súp Kem Nấm Tươi', 'Creamy Mushroom Soup', 'Súp kem nấm tươi', 'Assorted mushroom, fresh milk, whipping cream, chicken broth', 95000, NULL, 1, 'restaurant', '', '', ''),
(7, 'Súp Khoai Tây Thịt Nguội', 'Ham & Potato Soup', 'Súp khoai tây thịt nguội', 'Potato ham chicken broth soup', 90000, NULL, 1, 'restaurant', '', '', ''),

-- ========== XÀ LÁCH ÂU (Category 8) ==========
(8, 'Xà Lách Trộn Kiểu Pháp', 'Caesar Salad', 'Xà lách Romaine, gà, bacon, phô mai Parmesan', 'Romaine lettuce, chicken, bacon, Parmesan cheese, croutons, dressing', 135000, NULL, 1, 'restaurant', '', '', ''),
(8, 'Xà Lách Cá Ngừ Kiểu Pháp', 'Nicoise Salad', 'Xà lách cá ngừ kiểu Pháp', 'Romaine lettuce, potato, olive, cherry tomato, tuna, egg, balsamic dressing', 135000, NULL, 1, 'restaurant', '', '', ''),
(8, 'Xà Lách Gà Nướng', 'Grilled Chicken Salad', 'Xà lách gà nướng', 'Assorted lettuce, tomato, cucumber, olive, green capsicum, grilled chicken, oil vinegar dressing', 110000, NULL, 1, 'restaurant', '', '', ''),

-- ========== MÌ Ý (Category 9) ==========
(9, 'Spaghetti Bolognese', 'Spaghetti Bolognese', 'Mì Ý sốt bò bằm', 'Minced beef, tomato sauce, Parmesan cheese', 195000, NULL, 1, 'restaurant', 'bestseller', '', ''),
(9, 'Spaghetti Carbonara', 'Spaghetti Carbonara', 'Mì Ý sốt kem', 'Ham, mushroom, Parmesan cheese, cream sauce', 175000, NULL, 1, 'restaurant', '', '', ''),
(9, 'Spaghetti Marinara', 'Spaghetti Marinara', 'Mì Ý sốt hải sản', 'Seafood, tomato sauce, Parmesan cheese', 215000, NULL, 1, 'restaurant', '', '', ''),

-- ========== SANDWICH (Category 10) ==========
(10, 'Bánh Mì Sandwich Kẹp Phô Mai Thịt Nguội', 'Ham Cheese Sandwich', 'Bánh mì sandwich kẹp phô mai thịt nguội', 'Ham cheese sandwich', 165000, NULL, 1, 'restaurant', '', '', ''),
(10, 'Bánh Mì Sandwich Thập Cẩm', 'Club Sandwich', 'Bánh mì sandwich thập cẩm', 'Club sandwich', 210000, NULL, 1, 'restaurant', '', '', ''),
(10, 'Burger Bò', 'Beef Burger', 'Burger bò', 'Beef burger', 170000, NULL, 1, 'restaurant', '', '', ''),
(10, 'Club Sandwich (Room Service)', 'Club Sandwich', 'Bánh mì sandwich thập cẩm', 'Club sandwich', 210000, NULL, 1, 'room_service', '', '', ''),
(10, 'Beef Burger (Room Service)', 'Beef Burger', 'Burger bò', 'Beef burger', 215000, NULL, 1, 'room_service', '', '', ''),

-- ========== MÓN CHÍNH ÂU (Category 11) ==========
(11, 'Cá Hồi Nướng Xốt Nấm Hoặc Xốt Tiêu', 'Grilled Salmon with Mushroom Sauce or Pepper Sauce', 'Cá hồi nướng xốt nấm hoặc xốt tiêu', 'Served with potato, french fries & sauteed vegetables', 345000, NULL, 1, 'restaurant', 'recommended', '', ''),
(11, 'Ức Vịt Xông Khói Xốt Samba', 'Smoked Duck Breast with Samba Sauce', 'Ức vịt xông khói xốt samba', 'Served with gherkin, grilled vegetables', 185000, NULL, 1, 'restaurant', '', '', ''),
(11, 'Ức Gà Nướng Xốt Nấm', 'Grilled Chicken Breast with Mushroom Sauce', 'Ức gà nướng xốt nấm', 'Served with french fries & mixed salad', 130000, NULL, 1, 'restaurant', '', '', ''),
(11, 'Thăn Bò Áp Chảo Xốt Nấm Hoặc Xốt Tiêu', 'Roasted Beef Tenderloin with Mushroom Sauce or Pepper Sauce', 'Thăn bò áp chảo xốt nấm hoặc xốt tiêu', 'Served with mashed potato, french fries & sauteed vegetables', 330000, NULL, 1, 'restaurant', 'recommended', '', ''),
(11, 'Cá Hồi Nướng Xốt Nấm Hoặc Xốt Tiêu (Room Service)', 'Grilled Salmon with Mushroom Sauce or Pepper Sauce', 'Cá hồi nướng xốt nấm hoặc xốt tiêu', 'Served with potato, french fries & sauteed vegetables', 475000, NULL, 1, 'room_service', 'recommended', '', ''),
(11, 'Ức Vịt Xông Khói Xốt Samba (Room Service)', 'Smoked Duck Breast with Samba Sauce', 'Ức vịt xông khói xốt samba', 'Served with gherkin, grilled vegetables', 205000, NULL, 1, 'room_service', '', '', ''),
(11, 'Thăn Bò Áp Chảo Xốt Nấm Hoặc Xốt Tiêu (Room Service)', 'Roasted Beef Tenderloin with Mushroom Sauce or Pepper Sauce', 'Thăn bò áp chảo xốt nấm hoặc xốt tiêu', 'Served with mashed potato, french fries & sauteed vegetables', 355000, NULL, 1, 'room_service', 'recommended', '', ''),

-- ========== TRÁNG MIỆNG (Category 12) ==========
(12, 'Trái Cây Đốt Rượu', 'Fresh Fruit Flambee', 'Trái cây đốt rượu với bơ, nước cam, đường, rhum đen', 'Seasonal fresh fruit, butter, orange juice, sugar, dark rhum', 85000, NULL, 1, 'restaurant', '', 'Chọn loại trái: Xoài, Chuối, Thơm', 'Ask the waiter for your choice: Mango, Banana, Pineapple'),
(12, 'Xoài Đốt Rượu', 'Mango Flambe', 'Xoài đốt rượu', 'Mango flambe', 85000, NULL, 1, 'restaurant', '', '', ''),
(12, 'Thơm Đốt Rượu', 'Pineapple Flambe', 'Thơm đốt rượu', 'Pineapple flambe', 70000, NULL, 1, 'restaurant', '', '', ''),
(12, 'Chuối Đốt Rượu', 'Banana Flambe', 'Chuối đốt rượu', 'Banana flambe', 65000, NULL, 1, 'restaurant', '', '', ''),
(12, 'Trái Cây Theo Mùa', 'Seasonal Fresh Fruit', 'Trái cây theo mùa', 'Seasonal fresh fruit', 135000, NULL, 1, 'restaurant', '', '', ''),
(12, 'Trái Cây 3 Loại', '03 Kind of Seasonal Fresh Fruit', 'Trái cây 3 loại: Xoài, Thơm, Ổi', '3 kind of seasonal fresh fruits', 135000, NULL, 1, 'restaurant', '', '', ''),
(12, 'Trái Cây 4 Loại', '04 Kind of Seasonal Fresh Fruit', 'Trái cây 4 loại: Xoài, Thơm, Ổi, Dưa Hấu', '4 kind of seasonal fresh fruits', 175000, NULL, 1, 'restaurant', '', '', ''),
(12, 'Xoài Đốt Rượu (Room Service)', 'Mango Flambe', 'Xoài đốt rượu', 'Mango flambe', 95000, NULL, 1, 'room_service', '', '', ''),
(12, 'Trái Cây 3 Loại (Room Service)', '03 Kind of Seasonal Fresh Fruit', 'Trái cây 3 loại', '3 kind of seasonal fresh fruits', 150000, NULL, 1, 'room_service', '', '', ''),

-- ========== MÓN PHỤ / SIDEDISH (Category 13) ==========
(13, 'Xà Lách Trộn Dầu Giấm', 'Mixed Salad with Vinegar Dressing', 'Xà lách, cà chua, hành tây, dưa leo', 'Salad, tomato, onion, cucumber', 40000, NULL, 1, 'restaurant', '', '', ''),
(13, 'Rau Thập Cẩm Xào Tỏi', 'Stir Fried Vegetables with Garlic', 'Rau thập cẩm xào tỏi', 'Bông cải xanh, cà rốt, nấm rơm, nấm đông cô, bắp non, tỏi xay', 65000, NULL, 1, 'restaurant', '', '', ''),
(13, 'Bánh Mì Bơ Tỏi', 'Garlic Bread', 'Bánh mì bơ lát tỏi băm', 'Garlic bread with butter and minced garlic', 70000, NULL, 1, 'restaurant', '', '', ''),
(13, 'Khoai Tây Chiên', 'French Fries', 'Khoai tây chiên', 'French fries', 50000, NULL, 1, 'restaurant', 'bestseller', '', ''),
(13, 'Cơm Trắng Thố', 'Steamed Rice / Big Bowl', 'Cơm trắng thố', 'Steamed rice big bowl', 60000, NULL, 1, 'restaurant', '', '', ''),
(13, 'Cơm Trắng Chén', 'Steamed Rice / Small Bowl', 'Cơm trắng chén', 'Steamed rice small bowl', 20000, NULL, 1, 'restaurant', '', '', ''),
(13, 'Cơm Chiên Tỏi Trứng', 'Fried Rice with Garlic & Egg', 'Cơm chiên tỏi trứng', 'Fried rice with garlic and egg', 55000, NULL, 1, 'restaurant', '', '', ''),
(13, 'Cơm Chiên Tỏi', 'Fried Rice with Garlic', 'Cơm chiên tỏi', 'Fried rice with garlic', 40000, NULL, 1, 'restaurant', '', '', ''),

-- ========== CÀ PHÊ (Category 14) ==========
(14, 'Cà Phê Nóng Hoặc Đá', 'Hot or Iced Coffee', 'Cà phê nóng hoặc đá', 'Hot or iced coffee', 35000, NULL, 1, 'restaurant', '', '', ''),
(14, 'Cà Phê Sữa Nóng Hoặc Đá', 'Hot or Iced Coffee with Condensed Milk', 'Cà phê sữa nóng hoặc đá', 'Hot or iced coffee with condensed milk', 40000, NULL, 1, 'restaurant', 'bestseller', '', ''),
(14, 'Bạc Sỉu Nóng Hoặc Đá', 'Hot or Iced Fresh Milk with Coffee', 'Bạc sỉu nóng hoặc đá', 'Hot or iced fresh milk with coffee', 40000, NULL, 1, 'restaurant', '', '', ''),

-- ========== NƯỚC ÉP (Category 15) ==========
(15, 'Nước Cam Tươi', 'Orange Juice', 'Nước cam tươi', 'Fresh orange juice', 60000, NULL, 1, 'restaurant', 'bestseller', '', ''),
(15, 'Nước Xoài', 'Mango Juice', 'Nước xoài', 'Mango juice', 65000, NULL, 1, 'restaurant', '', '', ''),
(15, 'Nước Ép Thơm', 'Pineapple Juice', 'Nước ép thơm', 'Pineapple juice', 60000, NULL, 1, 'restaurant', '', '', ''),
(15, 'Nước Ép Dưa Hấu', 'Water Melon Juice', 'Nước ép dưa hấu', 'Water melon juice', 60000, NULL, 1, 'restaurant', '', '', ''),
(15, 'Nước Chanh Dây', 'Passion Fruit Juice', 'Nước chanh dây', 'Passion fruit juice', 60000, NULL, 1, 'restaurant', '', '', ''),

-- ========== BIA (Category 16) ==========
(16, 'Tiger Can Lon', 'Tiger', 'Bia Tiger can lon', 'Tiger beer can', 30000, NULL, 1, 'restaurant', '', '', ''),
(16, 'Tiger Silver Can Lon', 'Tiger Silver', 'Bia Tiger Silver can lon', 'Tiger Silver beer can', 32000, NULL, 1, 'restaurant', '', '', ''),
(16, 'Heineken Can Lon', 'Heineken', 'Bia Heineken can lon', 'Heineken beer can', 34000, NULL, 1, 'restaurant', '', ''),
(16, 'Heineken Silver Can Lon', 'Heineken Silver', 'Bia Heineken Silver can lon', 'Heineken Silver beer can', 36000, NULL, 1, 'restaurant', '', '', ''),

-- ========== NƯỚC NGỌT (Category 17) ==========
(17, 'Pepsi', 'Pepsi', 'Nước ngọt Pepsi', 'Pepsi soft drink', 25000, NULL, 1, 'restaurant', '', '', ''),
(17, '7 Up', '7 Up', 'Nước ngọt 7 Up', '7 Up soft drink', 25000, NULL, 1, 'restaurant', '', '', ''),
(17, 'Soda', 'Soda', 'Nước soda', 'Soda water', 25000, NULL, 1, 'restaurant', '', '', ''),

-- ========== NƯỚC SUỐI (Category 18) ==========
(18, 'Aquafina 500ml', 'Aquafina 500ml', 'Nước suối Aquafina 500ml', 'Aquafina mineral water 500ml', 20000, NULL, 1, 'restaurant', '', '', ''),
(18, 'Aquafina 1.5L', 'Aquafina 1.5L', 'Nước suối Aquafina 1.5L', 'Aquafina mineral water 1.5L', 45000, NULL, 1, 'restaurant', '', '', ''),
(18, 'Perrier', 'Perrier', 'Nước suối Perrier', 'Perrier sparkling water', 85000, NULL, 1, 'restaurant', '', '', ''),

-- ========== TRÀ (Category 19) ==========
(19, 'Trà Đen', 'Black Tea', 'Trà đen', 'Black tea', 50000, NULL, 1, 'restaurant', '', '', ''),
(19, 'Trà Lipton', 'Lipton Tea', 'Trà Lipton', 'Lipton tea', 50000, NULL, 1, 'restaurant', '', '', ''),
(19, 'Trà Sen', 'Lotus Tea', 'Trà sen', 'Lotus tea', 50000, NULL, 1, 'restaurant', '', '', ''),
(19, 'Trà Lài', 'Jasmine Tea', 'Trà lài', 'Jasmine tea', 50000, NULL, 1, 'restaurant', '', '', '');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Verification Query
-- ============================================================
-- SELECT 
--     mc.name AS category,
--     COUNT(mi.id) AS item_count,
--     GROUP_CONCAT(mi.name SEPARATOR ', ') AS items
-- FROM menu_items mi
-- JOIN menu_categories mc ON mi.category_id = mc.id
-- GROUP BY mc.name
-- ORDER BY mc.sort_order;