-- ============================================================
-- SEED DATA: Phân tách Menu Room Service
-- Aurora Hotel Plaza — Restaurant System
-- Chạy file này để thêm dữ liệu mô phỏng đầy đủ
-- ============================================================

-- ──────────────────────────────────────────────────────────
-- BƯỚC 1: Thêm cột service_type nếu chưa có
-- ──────────────────────────────────────────────────────────
ALTER TABLE `menu_items`
  ADD COLUMN IF NOT EXISTS `service_type` ENUM('restaurant','room_service','both') NOT NULL DEFAULT 'both' AFTER `is_active`;

ALTER TABLE `menu_items`
  ADD COLUMN IF NOT EXISTS `stock` int(11) NOT NULL DEFAULT '-1' AFTER `service_type`;

-- ──────────────────────────────────────────────────────────
-- BƯỚC 2: Thêm danh mục mới cho Room Service
-- ──────────────────────────────────────────────────────────
INSERT IGNORE INTO `menu_categories` (`id`, `name`, `name_en`, `menu_type`, `icon`, `sort_order`, `is_active`) VALUES
-- Nhà hàng (existing)
(1,  'Khai Vị',          'Appetizers',       'asia',     'fa-leaf',          1, 1),
(6,  'Gỏi - Nộm',        'Salads',           'alacarte', 'fa-leaf',          1, 1),
(11, 'Hải Sản',          'Seafood',          'asia',     'fa-fish',          6, 1),
(17, 'Cá & Hải Sản Âu',  'Fish & Seafood',   'europe',   'fa-fish',          6, 1),
(20, 'Set 4 Người',      'Set for 4',        'alacarte', 'fa-users',         2, 1),
-- Danh mục mới dùng chung / Room Service
(30, 'Đồ Uống',          'Beverages',        'other',    'fa-mug-hot',       1, 1),
(31, 'Ăn Sáng',          'Breakfast',        'other',    'fa-cloud-sun',     2, 1),
(32, 'Món Nhẹ & Snack',  'Light Meals',      'other',    'fa-burger',        3, 1),
(33, 'Tráng Miệng',      'Desserts',         'other',    'fa-ice-cream',     4, 1),
(34, 'Mì - Cháo - Súp',  'Noodles & Soups',  'asia',     'fa-bowl-food',     7, 1),
(35, 'Cơm & Món Chính',  'Rice & Mains',     'asia',     'fa-bowl-rice',     8, 1);

-- ──────────────────────────────────────────────────────────
-- BƯỚC 3: Cập nhật món hiện có — gán service_type rõ ràng
-- ──────────────────────────────────────────────────────────

-- Chả giò & Gỏi cuốn → Nhà hàng (nhẹ, khai vị, ok cả 2)
UPDATE `menu_items` SET `service_type` = 'both', `is_active` = 1, `is_available` = 1 WHERE `id` IN (2, 55);
-- Gỏi đu đủ → Nhà hàng
UPDATE `menu_items` SET `service_type` = 'restaurant', `is_active` = 1, `is_available` = 1 WHERE `id` = 62;
-- Hải sản → Chỉ nhà hàng
UPDATE `menu_items` SET `service_type` = 'restaurant', `is_active` = 1, `is_available` = 1 WHERE `id` IN (88, 89, 90, 149, 150);

-- ──────────────────────────────────────────────────────────
-- BƯỚC 4: Thêm món mô phỏng phân loại đầy đủ
-- Format: id, category_id, name, name_en, description, price,
--         image, is_available, is_active, service_type,
--         tags, note_options, note_options_en, sort_order
-- ──────────────────────────────────────────────────────────

INSERT INTO `menu_items`
  (`category_id`, `name`, `name_en`, `description`, `price`, `is_available`, `is_active`, `service_type`, `tags`, `note_options`, `note_options_en`, `sort_order`, `stock`)
VALUES

-- ══════════════════════════════════════════════
-- ĐỒ UỐNG (cat 30) — BOTH (phục vụ cả 2 nơi)
-- ══════════════════════════════════════════════
(30, 'Cà Phê Đen', 'Black Coffee',
  'Cà phê phin truyền thống, thơm đậm vị',
  35000, 1, 1, 'both', 'bestseller',
  'Đường riêng, Không đường, Đá riêng',
  'Sugar on side, No sugar, Ice on side', 1, -1),

(30, 'Cà Phê Sữa Đá', 'Iced Milk Coffee',
  'Cà phê phin pha sữa đặc, rót đá lạnh',
  45000, 1, 1, 'both', 'bestseller',
  'Ít ngọt, Bình thường, Nhiều sữa',
  'Less sweet, Normal, Extra milk', 2, -1),

(30, 'Trà Đào Cam Sả', 'Peach Lemongrass Tea',
  'Trà thảo mộc thơm vị đào và cam sả',
  55000, 1, 1, 'both', 'recommended',
  'Ít đá, Không đường, Đá riêng',
  'Less ice, No sugar, Ice on side', 3, -1),

(30, 'Nước Ép Cam Tươi', 'Fresh Orange Juice',
  'Cam vắt tươi 100%, không thêm đường',
  65000, 1, 1, 'both', 'recommended',
  'Không đường, Có đường, Ít đá',
  'No sugar, With sugar, Less ice', 4, -1),

(30, 'Trà Xanh Jasmine', 'Jasmine Green Tea',
  'Trà xanh ướp hoa lài, pha nóng hoặc đá',
  40000, 1, 1, 'both', NULL,
  'Pha nóng, Pha đá, Ít đường',
  'Hot, Iced, Less sugar', 5, -1),

(30, 'Bia Tiger Lon', 'Tiger Beer Can',
  'Bia Tiger 330ml lon lạnh',
  45000, 1, 1, 'restaurant', NULL,
  NULL, NULL, 6, -1),

(30, 'Rượu Vang Đỏ (ly)', 'Red Wine (glass)',
  'Vang đỏ Chile nhập khẩu, phục vụ theo ly',
  120000, 1, 1, 'restaurant', NULL,
  NULL, NULL, 7, -1),

(30, 'Nước Suối Aquafina', 'Mineral Water',
  'Nước suối Aquafina 500ml',
  20000, 1, 1, 'both', NULL,
  NULL, NULL, 8, -1),

(30, 'Sữa Tươi Vinamilk', 'Fresh Milk',
  'Sữa tươi không đường Vinamilk',
  30000, 1, 1, 'room_service', NULL,
  'Nóng, Lạnh', 'Hot, Cold', 9, -1),

(30, 'Sinh Tố Bơ', 'Avocado Smoothie',
  'Bơ Đắk Lắk, sữa tươi, đường cát',
  75000, 1, 1, 'both', 'recommended',
  'Ít đường, Không đường', 'Less sugar, No sugar', 10, -1),

-- ══════════════════════════════════════════════
-- ĂN SÁNG (cat 31) — ROOM SERVICE chủ yếu
-- ══════════════════════════════════════════════
(31, 'Phở Bò Tái Chín', 'Beef Pho',
  'Phở bò Nam Định truyền thống, nước dùng hầm 12h',
  95000, 1, 1, 'both', 'bestseller',
  'Tái, Chín, Hành trần, Không hành, Ít mì chính',
  'Rare, Well-done, Blanched onion, No onion, Less MSG', 1, -1),

(31, 'Bánh Mì Trứng Ốp La', 'Egg Sandwich',
  'Bánh mì giòn, 2 trứng ốp la, thêm pate và rau',
  55000, 1, 1, 'both', NULL,
  'Ít muối, Không pate, Thêm trứng',
  'Less salt, No pate, Extra egg', 2, -1),

(31, 'Cháo Trắng Sườn Non', 'Pork Rib Congee',
  'Cháo nấu từ gạo tám, sườn non mềm',
  75000, 1, 1, 'room_service', 'recommended',
  'Ít hành, Không tiêu, Loãng hơn',
  'Less onion, No pepper, Thinner', 3, -1),

(31, 'Trứng Chiên Xúc Xích', 'Egg & Sausage',
  'Trứng chiên + 2 xúc xích tiệt trùng, kèm bánh mì',
  65000, 1, 1, 'room_service', NULL,
  'Trứng ốp, Trứng đánh, Không bánh mì',
  'Sunny-side up, Scrambled, No bread', 4, -1),

(31, 'Sandwich Thịt Nguội', 'Cold Cut Sandwich',
  'Bánh sandwich với giăm bông, phô mai, rau xà lách',
  70000, 1, 1, 'room_service', NULL,
  'Toasted, Không toasted, Không phô mai',
  'Toasted, Not toasted, No cheese', 5, -1),

(31, 'Hủ Tiếu Nam Vang', 'Phnom Penh Noodles',
  'Hủ tiếu dai, thịt bằm, tôm tươi, nước trong',
  90000, 1, 1, 'both', 'recommended',
  'Khô, Nước, Ít hành, Không tỏi',
  'Dry, Soup, Less onion, No garlic', 6, -1),

(31, 'Granola & Sữa Chua', 'Granola Yogurt',
  'Granola hạt macca + sữa chua tươi + mứt dâu',
  80000, 1, 1, 'room_service', 'new',
  NULL, NULL, 7, -1),

-- ══════════════════════════════════════════════
-- MÓN NHẸ & SNACK (cat 32) — BOTH
-- ══════════════════════════════════════════════
(32, 'Khoai Tây Chiên Giòn', 'French Fries',
  'Khoai tây chiên ORIDA, muối thảo mộc',
  65000, 1, 1, 'both', 'bestseller',
  'Nhiều muối, Ít muối, Thêm tương cà',
  'Extra salt, Less salt, With ketchup', 1, -1),

(32, 'Gà Rán Giòn (4 miếng)', 'Fried Chicken (4pcs)',
  'Gà rán giòn sốt ngọt mật ong',
  145000, 1, 1, 'both', 'recommended',
  'Sốt cay, Sốt ngọt, Không sốt',
  'Spicy sauce, Sweet sauce, No sauce', 2, -1),

(32, 'Bánh Mì Nướng Bơ Tỏi', 'Garlic Butter Toast',
  'Bánh mì nướng bơ + tỏi thơm, phục vụ nóng',
  45000, 1, 1, 'room_service', NULL,
  NULL, NULL, 3, -1),

(32, 'Xúc Xích Que Nướng', 'Grilled Sausage Skewers',
  '4 que xúc xích BBQ nướng than, kèm mù tạt',
  95000, 1, 1, 'both', NULL,
  'Ít mù tạt, Thêm mù tạt, Kèm tương ớt',
  'Less mustard, Extra mustard, With chili sauce', 4, -1),

(32, 'Phô Mai Que Chiên', 'Mozzarella Sticks',
  'Phô mai mozzarella chiên giòn, chấm sốt cà chua',
  95000, 1, 1, 'room_service', 'new',
  NULL, NULL, 5, -1),

(32, 'Nachos & Salsa', 'Nachos & Salsa',
  'Bánh nachos bắp rang bơ, sốt salsa cà chua tươi',
  85000, 1, 1, 'restaurant', NULL,
  'Thêm jalapeño, Không jalapeño',
  'Extra jalapeño, No jalapeño', 6, -1),

-- ══════════════════════════════════════════════
-- MÌ - CHÁO - SÚP (cat 34) — BOTH
-- ══════════════════════════════════════════════
(34, 'Mì Tôm Trứng', 'Instant Noodles & Egg',
  'Mì tôm hảo hảo + 2 trứng + rau cải',
  55000, 1, 1, 'room_service', 'bestseller',
  'Nhiều nước, Ít cay, Thêm trứng',
  'More broth, Less spicy, Extra egg', 1, -1),

(34, 'Bún Bò Huế', 'Hue Style Beef Noodles',
  'Bún bò Huế cay nồng đặc trưng miền Trung',
  110000, 1, 1, 'both', 'recommended',
  'Ít cay, Không sả, Thêm chả',
  'Less spicy, No lemongrass, Extra sausage', 2, -1),

(34, 'Súp Khoai Tây Kem', 'Cream of Potato Soup',
  'Súp khoai tây kem kiểu Tây, thêm bacon',
  75000, 1, 1, 'both', NULL,
  'Không bacon, Thêm kem, Không tiêu',
  'No bacon, Extra cream, No pepper', 3, -1),

(34, 'Cháo Gà Nấm', 'Chicken Mushroom Congee',
  'Cháo gà ta nấu với nấm hương, ăn kèm gừng',
  85000, 1, 1, 'room_service', 'recommended',
  'Ít hành, Thêm gừng, Không tiêu',
  'Less onion, Extra ginger, No pepper', 4, -1),

(34, 'Súp Hải Sản Tom Yum', 'Tom Yum Seafood Soup',
  'Súp Tom Yum kiểu Thái, tôm mực nấm rơm',
  135000, 1, 1, 'restaurant', 'spicy',
  'Ít cay, Không cay, Thêm hải sản',
  'Less spicy, Not spicy, Extra seafood', 5, -1),

-- ══════════════════════════════════════════════
-- CƠM & MÓN CHÍNH (cat 35) — ROOM SERVICE chính
-- ══════════════════════════════════════════════
(35, 'Cơm Chiên Dương Châu', 'Yang Chow Fried Rice',
  'Cơm chiên trứng, tôm, lạp xưởng kiểu Hồng Kông',
  110000, 1, 1, 'both', 'bestseller',
  'Ít dầu, Không xúc xích, Thêm trứng',
  'Less oil, No sausage, Extra egg', 1, -1),

(35, 'Cơm Gà Xối Mỡ', 'Crispy Chicken Rice',
  'Cơm trắng + 1/4 gà xối mỡ giòn, nước mắm chanh',
  125000, 1, 1, 'both', 'recommended',
  'Không da, Thêm nước mắm, Ít ớt',
  'No skin, Extra fish sauce, Less chili', 2, -1),

(35, 'Bò Lúc Lắc', 'Shaken Beef',
  'Bò thăn xào lúc lắc tiêu xanh, kèm cơm trắng hoặc bánh mì',
  185000, 1, 1, 'both', 'bestseller',
  'Cơm trắng, Bánh mì, Tái, Chín hẳn',
  'Steamed rice, Bread, Medium rare, Well-done', 3, -1),

(35, 'Cá Hồi Áp Chảo', 'Pan-Seared Salmon',
  'Cá hồi Na Uy áp chảo bơ chanh, kèm khoai tây nghiền',
  285000, 1, 1, 'both', 'recommended',
  'Chín vừa, Chín kỹ, Không bơ',
  'Medium, Well-done, No butter', 4, -1),

(35, 'Pasta Carbonara', 'Spaghetti Carbonara',
  'Spaghetti kem trứng pancetta kiểu Ý',
  165000, 1, 1, 'both', NULL,
  'Thêm pho mai, Ít kem, Không thịt xông khói',
  'Extra cheese, Less cream, No bacon', 5, -1),

(35, 'Cơm Chiên Tôm', 'Shrimp Fried Rice',
  'Cơm tấm rang với tôm tươi và rau củ',
  130000, 1, 1, 'room_service', NULL,
  'Ít dầu, Không hành, Thêm tôm',
  'Less oil, No onion, Extra shrimp', 6, -1),

(35, 'Pizza Margherita (nhỏ)', 'Margherita Pizza (small)',
  'Pizza đế mỏng, sốt cà chua, mozzarella, húng',
  175000, 1, 1, 'room_service', 'new',
  'Thêm phô mai, Không húng, Thêm ớt khô',
  'Extra cheese, No basil, Extra chili flakes', 7, -1),

-- ══════════════════════════════════════════════
-- TRÁNG MIỆNG (cat 33) — BOTH
-- ══════════════════════════════════════════════
(33, 'Bánh Flan Caramel', 'Crème Caramel',
  'Bánh flan mềm mịn kiểu truyền thống, rưới caramel',
  55000, 1, 1, 'both', 'recommended',
  NULL, NULL, 1, -1),

(33, 'Chè Ba Màu', 'Three Color Dessert',
  'Đỗ xanh, đỗ đỏ, hạt lựu, nước cốt dừa và đá bào',
  65000, 1, 1, 'both', 'bestseller',
  'Ít đường, Không dừa, Thêm đá',
  'Less sugar, No coconut, Extra ice', 2, -1),

(33, 'Kem Dừa Tươi', 'Fresh Coconut Ice Cream',
  'Kem vani + thạch dừa phục vụ trong trái dừa tươi',
  85000, 1, 1, 'both', 'recommended',
  NULL, NULL, 3, -1),

(33, 'Lava Cake Socola', 'Chocolate Lava Cake',
  'Bánh socola nhân chảy nóng, kèm một quả kem vani',
  95000, 1, 1, 'both', 'new',
  NULL, NULL, 4, -1),

(33, 'Trái Cây Tươi Thái Sẵn', 'Fresh Cut Fruits',
  'Đĩa trái cây tươi theo mùa: dưa hấu, dứa, thanh long',
  75000, 1, 1, 'both', NULL,
  NULL, NULL, 5, -1),

-- ══════════════════════════════════════════════
-- KHi VỊ (cat 1) — thêm vài món RESTAURANT
-- ══════════════════════════════════════════════
(1, 'Súp Vi Cá', 'Shark Fin Soup',
  'Súp vi cá hầm lâu, nấm đông cô, trứng cút',
  185000, 1, 1, 'restaurant', NULL,
  'Ít tiêu, Không giấm, Thêm nấm',
  'Less pepper, No vinegar, Extra mushroom', 5, -1),

(1, 'Bạch Tuộc Nướng Muối Ớt', 'Grilled Octopus',
  'Bạch tuộc nướng than muối ớt, chấm tương hoisin',
  165000, 1, 1, 'restaurant', 'spicy',
  'Ít cay, Không cay, Thêm sốt',
  'Less spicy, Not spicy, Extra sauce', 6, -1),

(1, 'Tôm Khô Cải Chua', 'Dried Shrimp Pickle',
  'Tôm khô rim với củ cải chua, ăn kèm cơm',
  75000, 1, 1, 'both', NULL,
  'Ít cay, Ít ngọt', 'Less spicy, Less sweet', 7, -1);

-- ──────────────────────────────────────────────────────────
-- BƯỚC 5: Xác nhận kết quả
-- ──────────────────────────────────────────────────────────
SELECT
  CASE service_type
    WHEN 'restaurant'  THEN 'Nhà hàng'
    WHEN 'room_service' THEN 'Room Service'
    WHEN 'both'        THEN 'Cả hai'
  END  AS `Phục vụ cho`,
  COUNT(*) AS `Số món`
FROM `menu_items`
WHERE is_active = 1
GROUP BY service_type
ORDER BY service_type;

SELECT GROUP_CONCAT(DISTINCT name ORDER BY name SEPARATOR ', ') AS `Danh mục mới`
FROM `menu_categories`
WHERE id >= 30;
