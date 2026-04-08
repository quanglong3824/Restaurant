<?php
/**
 * Migration Script - Aurora Menu 2026
 * Run this file from browser: https://aurorahotelplaza.com/restaurant/database/migrate_menu_2026.php
 * 
 * IMPORTANT: Delete this file after running for security!
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only allow local access or authenticated admin
$allowed_ips = ['127.0.0.1', '::1', 'localhost'];
$is_local = in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowed_ips);

// Load config
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

if (!$is_local && session_status() === PHP_SESSION_NONE) {
    session_start();
    $is_admin = isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin';
    if (!$is_admin) {
        die('Access denied. Please login as admin or run from localhost.');
    }
}

try {
    $pdo = getDB();
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
$errors = [];
$success = [];

try {
    $pdo->beginTransaction();
    
    // Step 1: Delete existing data (in correct order)
    $success[] = "Step 1: Deleting existing menu items...";
    $pdo->exec("DELETE FROM menu_items");
    
    $success[] = "Step 2: Deleting existing menu categories...";
    $pdo->exec("DELETE FROM menu_categories");
    
    $success[] = "Step 3: Deleting existing menu types...";
    $pdo->exec("DELETE FROM menu_types");
    
    // Step 4: Insert Menu Types
    $menuTypes = [
        ['Món Á', 'Asian Cuisine', 'asia', 'Các món ăn truyền thống châu Á', '#0ea5e9', 'fa-bowl-rice', 1],
        ['Món Âu', 'European Cuisine', 'europe', 'Các món ăn phong cách châu Âu', '#8b5cf6', 'fa-wine-glass', 2],
        ['Alacarte', 'Alacarte', 'alacarte', 'Các món gọi riêng', '#f59e0b', 'fa-utensils', 3],
        ['Đồ Uống', 'Beverages', 'other', 'Đồ uống các loại', '#10b981', 'fa-cocktail', 4],
    ];
    
    $stmt = $pdo->prepare("INSERT INTO menu_types (name, name_en, type_key, description, color, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
    foreach ($menuTypes as $type) {
        $stmt->execute($type);
    }
    $success[] = "Step 4: Inserted " . count($menuTypes) . " menu types.";
    
    // Step 5: Insert Menu Categories
    $categories = [
        // Món Á
        ['Khai Vị', 'Appetizer', 'asia', 'fa-utensils', 1],
        ['Súp', 'Soup', 'asia', 'fa-bowl-food', 2],
        ['Gỏi', 'Vietnamese Salad', 'asia', 'fa-leaf', 3],
        ['Cơm & Xôi', 'Rice, Sticky Rice', 'asia', 'fa-bowl-rice', 4],
        ['Mì & Bún', 'Noodle', 'asia', 'fa-utensils', 5],
        ['Cháo', 'Rice Porridge', 'asia', 'fa-spoon', 6],
        // Món Âu
        ['Súp Âu', 'Soup', 'europe', 'fa-bowl-food', 1],
        ['Xà Lách', 'Salad', 'europe', 'fa-leaf', 2],
['Mì Ý', 'Spaghetti', 'europe', 'fa-utensils', 3],
        ['Sandwich', 'Sandwich', 'europe', 'fa-bread-slice', 4],
        ['Món Chính', 'Main Course', 'europe', 'fa-drumstick-bite', 5],
        ['Tráng Miệng', 'Dessert', 'europe', 'fa-ice-cream', 6],
        // Alacarte
        ['Món Phụ', 'Sidedish', 'alacarte', 'fa-utensils', 1],
        // Đồ Uống
        ['Cà Phê', 'Coffee', 'other', 'fa-mug-hot', 1],
        ['Nước Ép', 'Fruit Juice', 'other', 'fa-glass-whiskey', 2],
        ['Bia', 'Beer', 'other', 'fa-beer', 3],
        ['Nước Ngọt', 'Soft Drink', 'other', 'fa-bottle-water', 4],
        ['Nước Suối', 'Mineral Water', 'other', 'fa-wine-bottle', 5],
        ['Trà', 'Tea Collection', 'other', 'fa-flask', 6],
    ];
    
    $stmt = $pdo->prepare("INSERT INTO menu_categories (name, name_en, menu_type, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    foreach ($categories as $cat) {
        $stmt->execute($cat);
    }
    $success[] = "Step 5: Inserted " . count($categories) . " menu categories.";
    
    // Get category IDs by name
    $catIds = [];
    $stmt = $pdo->query("SELECT id, name FROM menu_categories");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $catIds[$row['name']] = $row['id'];
    }
    
    // Step 6: Insert Menu Items
    $items = [
        // KHAI VỊ (Category 1)
        ['Khai Vị', 'Phở Cuốn', 'Rice Pancake Rolls with Beef', 'Phở cuốn với bò', 'Rice pancake rolls with beef', 179000, '', '', '', ''],
        ['Khai Vị', 'Bò Cuốn Lá Xanh', 'Mustard Leaf Rolls with Beef', 'Bò cuốn lá xanh mù tạt', 'Mustard leaf rolls with beef', 179000, 'recommended', '', '', ''],
        ['Khai Vị', 'Gỏi Ngó Sen Đưa Tôm Thịt', 'Lotus Stems Salad with Shrimp & Pork', 'Gỏi ngó sen tôm thịt', 'Lotus stems salad with shrimp and pork', 180000, '', '', '', ''],
        ['Khai Vị', 'Gỏi Củ Hủ Dừa Tôm Thịt', 'Coconut Palm Salad with Shrimp & Pork', 'Gỏi củ hủ dừa tôm thịt', 'Coconut palm salad with shrimp and pork', 180000, '', '', '', ''],
        ['Khai Vị', 'Gỏi Cuốn Tôm Thịt', 'Fresh Spring Rolls with Shrimp & Pork', 'Gỏi cuốn tôm thịt tươi', 'Fresh spring rolls with shrimp and pork', 135000, 'bestseller', '', '', ''],
        ['Khai Vị', 'Chả Giò Hải Sản Aurora', 'Aurora Deep Fried Seafood Spring Rolls', 'Chả giò hải sản Aurora', 'Aurora deep fried seafood spring rolls', 135000, 'bestseller', '', '', ''],
        
        // SÚP (Category 2)
        ['Súp', 'Súp Bào Ngư Hải Sản', 'Abalone Seafood Soup', 'Súp bào ngư hải sản', 'Abalone seafood soup', 130000, '', '', '', ''],
        ['Súp', 'Súp Cua Hải Sản Tam Tố', 'Tam To Crab Meat & Seafood Soup', 'Súp cua hải sản tam tố', 'Crab meat and seafood soup', 130000, '', '', '', ''],
        ['Súp', 'Súp Kem Bí Đỏ', 'Pumpkin Soup', 'Súp kem bí đỏ', 'Pumpkin cream soup', 90000, '', '', '', ''],
        ['Súp', 'Súp Kem Nấm', 'Creamy Mushroom Soup', 'Súp kem nấm tươi', 'Creamy mushroom soup with assorted mushrooms', 95000, '', '', '', ''],
        ['Súp', 'Súp Khoai Tây Thịt Nguội', 'Ham & Potato Soup', 'Súp khoai tây thịt nguội', 'Potato ham chicken broth soup', 90000, '', '', '', ''],
        ['Súp', 'Súp Kem Nấm (Room Service)', 'Creamy Mushroom Soup', 'Súp kem nấm với sữa tươi, whipping cream', 'Creamy mushroom soup with fresh milk, whipping cream, chicken broth', 115000, '', 'room_service', '', ''],
        ['Súp', 'Súp Khoai Tây Thịt Nguội (Room Service)', 'Ham & Potato Soup', 'Súp khoai tây thịt nguội', 'Potato ham chicken broth soup', 115000, '', 'room_service', '', ''],
        
        // GỎI (Category 3)
        ['Gỏi', 'Gỏi Xà Lách Trộn Kiểu Pháp', 'Caesar Salad', 'Xà lách Romaine, gà, bacon, phô mai Parmesan', 'Romaine lettuce, chicken, bacon, Parmesan cheese, croutons, dressing', 135000, '', '', '', ''],
        ['Gỏi', 'Xà Lách Cá Ngừ Kiểu Pháp', 'Nicoise Salad', 'Xà lách cá ngừ kiểu Pháp', 'Romaine lettuce, potato, olive, cherry tomato, tuna, egg, balsamic dressing', 135000, '', '', '', ''],
        ['Gỏi', 'Xà Lách Gà Nướng', 'Grilled Chicken Salad', 'Xà lách gà nướng', 'Assorted lettuce, tomato, cucumber, olive, green capsicum, grilled chicken, oil vinegar dressing', 110000, '', '', '', ''],
        ['Gỏi', 'Gỏi Xà Lách Trộn', 'Mixed Salad', 'Xà lách trộn dầu giấm', 'Mixed salad with vinegar dressing', 40000, '', '', '', ''],
        ['Gỏi', 'Gỏi Xà Lách Trứng Luộc', 'Mixed Salad & Boiled Egg', 'Xà lách trộn dầu giấm trứng luộc', 'Mixed salad with vinegar dressing and boiled egg', 55000, '', '', '', ''],
        ['Gỏi', 'Xà Lách Trộn (Room Service)', 'Caesar Salad', 'Xà lách trộn kiểu Pháp', 'Romaine lettuce, chicken, bacon, Parmesan cheese, croutons, dressing', 145000, '', 'room_service', '', ''],
        ['Gỏi', 'Xà Lách Gà Nướng (Room Service)', 'Grilled Chicken Salad', 'Xà lách gà nướng', 'Assorted lettuce, tomato, cucumber, olive, green capsicum, grilled chicken', 135000, '', 'room_service', '', ''],
        
        // CƠM & XÔI (Category 4)
        ['Cơm & Xôi', 'Xôi Xéo', 'Steamed Sticky Rice with Green Beans', 'Xôi xéo đậu xanh', 'Steamed sticky rice with mung beans', 45000, '', '', '', ''],
        ['Cơm & Xôi', 'Cơm Trắng', 'Steamed Rice', 'Cơm trắng', 'Steamed white rice', 20000, '', '', '', ''],
        ['Cơm & Xôi', 'Cơm Trắng / Thố', 'Steamed Rice / Big Bowl', 'Cơm trắng thố lớn', 'Steamed rice big bowl', 60000, '', '', '', ''],
        ['Cơm & Xôi', 'Cơm Chiên Cá Mặn Gà Xé', 'Fried Rice with Salted Fish & Shredded Chicken', 'Cơm chiên cá mặn gà xé', 'Fried rice with salted fish and shredded chicken', 105000, '', '', '', ''],
        ['Cơm & Xôi', 'Cơm Chiên Hải Sản Kim Sa', 'Seafood Fried Rice with Salted Egg', 'Cơm chiên hải sản kim sa', 'Seafood fried rice with salted egg', 105000, 'bestseller', '', '', ''],
        ['Cơm & Xôi', 'Cơm Chiên Thịt Xá Xíu Xốt XO', 'Fried Rice with Char Siu & XO Sauce', 'Cơm chiên thịt xá xíu xốt XO', 'Fried rice with char siu and XO sauce', 105000, '', '', '', ''],
        ['Cơm & Xôi', 'Cơm Trắng (Room Service - Chén)', 'Steamed Rice', 'Cơm trắng chén', 'Steamed rice small bowl', 25000, '', 'room_service', '', ''],
        ['Cơm & Xôi', 'Cơm Chiên Cá Mặn Gà Xé (Room Service)', 'Fried Rice with Salted Fish & Shredded Chicken', 'Cơm chiên cá mặn gà xé', 'Fried rice with salted fish and shredded chicken', 110000, '', 'room_service', '', ''],
        ['Cơm & Xôi', 'Cơm Chiên Hải Sản Kim Sa (Room Service)', 'Seafood Fried Rice with Salted Egg', 'Cơm chiên hải sản kim sa', 'Seafood fried rice with salted egg', 135000, '', 'room_service', '', ''],
        ['Cơm & Xôi', 'Cơm Chiên Thịt Xá Xíu Xốt XO (Room Service)', 'Fried Rice with Char Siu & XO Sauce', 'Cơm chiên thịt xá xíu xốt XO', 'Fried rice with char siu and XO sauce', 135000, '', 'room_service', '', ''],
        
        // MÌ & BÚN (Category 5)
        ['Mì & Bún', 'Bún Mọc Măng Dọc Mùng', 'Vietnamese Colocasia Gigantea Noodle Soup', 'Bún mọc măng dọc mùng', 'Vietnamese noodle soup with pork balls and bamboo shoot', 75000, '', '', '', ''],
        ['Mì & Bún', 'Bún Ốc Hà Nội', 'Vietnamese Noodle Soup with Snail', 'Bún ốc Hà Nội', 'Vietnamese noodle soup with snail Ha Noi style', 70000, '', '', '', ''],
        ['Mì & Bún', 'Bún Cá Hà Nội', 'Vietnamese Fish Noodle Soup', 'Bún cá Hà Nội', 'Vietnamese fish noodle soup Ha Noi style', 75000, '', '', '', ''],
        ['Mì & Bún', 'Bún Xào Singapore', 'Stir Fried Rice Noodle Singapore Style', 'Bún xào Singapore', 'Stir fried rice noodle Singapore style', 185000, '', '', '', ''],
        ['Mì & Bún', 'Miến Xào Hàn Quốc', 'Stir Fried Vermicelli with Beef & Vegetables', 'Miến xào Hàn Quốc', 'Stir fried vermicelli with beef and vegetables', 230000, '', '', '', ''],
        ['Mì & Bún', 'Mì Xào Tôm', 'Stir Fried Yellow Noodle with Shrimp', 'Mì xào tôm', 'Stir fried yellow noodle with shrimp', 140000, '', '', '', ''],
        ['Mì & Bún', 'Mì Xào Thịt Bò', 'Stir Fried Yellow Noodle with Beef', 'Mì xào thịt bò', 'Stir fried yellow noodle with beef', 190000, '', '', '', ''],
        ['Mì & Bún', 'Mì Xào Hải Sản', 'Stir Fried Yellow Noodle with Seafood', 'Mì xào hải sản', 'Stir fried yellow noodle with seafood', 165000, 'bestseller', '', '', ''],
        ['Mì & Bún', 'Bún Xào Singapore (Room Service)', 'Stir Fried Rice Noodle Singapore Style', 'Bún xào Singapore', 'Stir fried rice noodle Singapore style', 185000, '', 'room_service', '', ''],
        ['Mì & Bún', 'Mì Xào Thịt Bò (Room Service)', 'Stir Fried Yellow Noodle with Beef', 'Mì xào thịt bò', 'Stir fried yellow noodle with beef', 190000, '', 'room_service', '', ''],
        ['Mì & Bún', 'Mì Xào Tôm (Room Service)', 'Stir Fried Yellow Noodle with Shrimp', 'Mì xào tôm', 'Stir fried yellow noodle with shrimp', 160000, '', 'room_service', '', ''],
        ['Mì & Bún', 'Mì Xào Hải Sản (Room Service)', 'Stir Fried Yellow Noodle with Seafood', 'Mì xào hải sản', 'Stir fried yellow noodle with seafood', 190000, '', 'room_service', '', ''],
        
        // CHÁO (Category 6)
        ['Cháo', 'Cháo Bò Bằm', 'Rice Porridge with Minced Beef', 'Cháo bò bằm', 'Rice porridge with minced beef', 115000, '', '', '', ''],
        ['Cháo', 'Cháo Thịt Bằm', 'Rice Porridge with Minced Pork', 'Cháo thịt bằm', 'Rice porridge with minced pork', 65000, '', '', '', ''],
        ['Cháo', 'Cháo Hải Sản', 'Rice Porridge with Seafood', 'Cháo hải sản', 'Rice porridge with seafood', 115000, '', '', '', ''],
        ['Cháo', 'Cháo Bò Bằm (Room Service)', 'Rice Porridge with Minced Beef', 'Cháo bò bằm', 'Rice porridge with minced beef', 115000, '', 'room_service', '', ''],
        ['Cháo', 'Cháo Thịt Bằm (Room Service)', 'Rice Porridge with Minced Pork', 'Cháo thịt bằm', 'Rice porridge with minced pork', 90000, '', 'room_service', '', ''],
        ['Cháo', 'Cháo Hải Sản (Room Service)', 'Rice Porridge with Seafood', 'Cháo hải sản', 'Rice porridge with seafood', 155000, '', 'room_service', '', ''],
        ['Cháo', 'Cháo Lá Dứa Hột Vịt Muối', 'Rice Porridge with Salted Egg', 'Cháo lá dứa hột vịt muối', 'Rice porridge served with salted egg', 45000, '', '', '', ''],
        
        // SÚP ÂU (Category 7)
        ['Súp Âu', 'Súp Kem Bí Đỏ', 'Pumpkin Soup', 'Súp kem bí đỏ với kem, phô mai, bánh mì', 'Pumpkin cream cheese soup served with crouton', 90000, '', '', '', ''],
        ['Súp Âu', 'Súp Kem Nấm Tươi', 'Creamy Mushroom Soup', 'Súp kem nấm tươi', 'Assorted mushroom, fresh milk, whipping cream, chicken broth', 95000, '', '', '', ''],
        ['Súp Âu', 'Súp Khoai Tây Thịt Nguội', 'Ham & Potato Soup', 'Súp khoai tây thịt nguội', 'Potato ham chicken broth soup', 90000, '', '', '', ''],
        
        // XÀ LÁCH ÂU (Category 8)
        ['Xà Lách', 'Xà Lách Trộn Kiểu Pháp', 'Caesar Salad', 'Xà lách Romaine, gà, bacon, phô mai Parmesan', 'Romaine lettuce, chicken, bacon, Parmesan cheese, croutons, dressing', 135000, '', '', '', ''],
        ['Xà Lách', 'Xà Lách Cá Ngừ Kiểu Pháp', 'Nicoise Salad', 'Xà lách cá ngừ kiểu Pháp', 'Romaine lettuce, potato, olive, cherry tomato, tuna, egg, balsamic dressing', 135000, '', '', '', ''],
        ['Xà Lách', 'Xà Lách Gà Nướng', 'Grilled Chicken Salad', 'Xà lách gà nướng', 'Assorted lettuce, tomato, cucumber, olive, green capsicum, grilled chicken, oil vinegar dressing', 110000, '', '', '', ''],
        
        // MÌ Ý (Category 9)
        ['Mì Ý', 'Spaghetti Bolognese', 'Spaghetti Bolognese', 'Mì Ý sốt bò bằm', 'Minced beef, tomato sauce, Parmesan cheese', 195000, 'bestseller', '', '', ''],
        ['Mì Ý', 'Spaghetti Carbonara', 'Spaghetti Carbonara', 'Mì Ý sốt kem', 'Ham, mushroom, Parmesan cheese, cream sauce', 175000, '', '', '', ''],
        ['Mì Ý', 'Spaghetti Marinara', 'Spaghetti Marinara', 'Mì Ý sốt hải sản', 'Seafood, tomato sauce, Parmesan cheese', 215000, '', '', '', ''],
        
        // SANDWICH (Category 10)
        ['Sandwich', 'Bánh Mì Sandwich Kẹp Phô Mai Thịt Nguội', 'Ham Cheese Sandwich', 'Bánh mì sandwich kẹp phô mai thịt nguội', 'Ham cheese sandwich', 165000, '', '', '', ''],
        ['Sandwich', 'Bánh Mì Sandwich Thập Cẩm', 'Club Sandwich', 'Bánh mì sandwich thập cẩm', 'Club sandwich', 210000, '', '', '', ''],
        ['Sandwich', 'Burger Bò', 'Beef Burger', 'Burger bò', 'Beef burger', 170000, '', '', '', ''],
        ['Sandwich', 'Club Sandwich (Room Service)', 'Club Sandwich', 'Bánh mì sandwich thập cẩm', 'Club sandwich', 210000, '', 'room_service', '', ''],
        ['Sandwich', 'Beef Burger (Room Service)', 'Beef Burger', 'Burger bò', 'Beef burger', 215000, '', 'room_service', '', ''],
        
        // MÓN CHÍNH ÂU (Category 11)
        ['Món Chính', 'Cá Hồi Nướng Xốt Nấm Hoặc Xốt Tiêu', 'Grilled Salmon with Mushroom Sauce or Pepper Sauce', 'Cá hồi nướng xốt nấm hoặc xốt tiêu', 'Served with potato, french fries & sauteed vegetables', 345000, 'recommended', '', '', ''],
        ['Món Chính', 'Ức Vịt Xông Khói Xốt Samba', 'Smoked Duck Breast with Samba Sauce', 'Ức vịt xông khói xốt samba', 'Served with gherkin, grilled vegetables', 185000, '', '', '', ''],
        ['Món Chính', 'Ức Gà Nướng Xốt Nấm', 'Grilled Chicken Breast with Mushroom Sauce', 'Ức gà nướng xốt nấm', 'Served with french fries & mixed salad', 130000, '', '', '', ''],
        ['Món Chính', 'Thăn Bò Áp Chảo Xốt Nấm Hoặc Xốt Tiêu', 'Roasted Beef Tenderloin with Mushroom Sauce or Pepper Sauce', 'Thăn bò áp chảo xốt nấm hoặc xốt tiêu', 'Served with mashed potato, french fries & sauteed vegetables', 330000, 'recommended', '', '', ''],
        ['Món Chính', 'Cá Hồi Nướng Xốt Nấm Hoặc Xốt Tiêu (Room Service)', 'Grilled Salmon with Mushroom Sauce or Pepper Sauce', 'Cá hồi nướng xốt nấm hoặc xốt tiêu', 'Served with potato, french fries & sauteed vegetables', 475000, 'recommended', 'room_service', '', ''],
        ['Món Chính', 'Ức Vịt Xông Khói Xốt Samba (Room Service)', 'Smoked Duck Breast with Samba Sauce', 'Ức vịt xông khói xốt samba', 'Served with gherkin, grilled vegetables', 205000, '', 'room_service', '', ''],
        ['Món Chính', 'Thăn Bò Áp Chảo Xốt Nấm Hoặc Xốt Tiêu (Room Service)', 'Roasted Beef Tenderloin with Mushroom Sauce or Pepper Sauce', 'Thăn bò áp chảo xốt nấm hoặc xốt tiêu', 'Served with mashed potato, french fries & sauteed vegetables', 355000, 'recommended', 'room_service', '', ''],
        
        // TRÁNG MIỆNG (Category 12)
        ['Tráng Miệng', 'Trái Cây Đốt Rượu', 'Fresh Fruit Flambee', 'Trái cây đốt rượu với bơ, nước cam, đường, rhum đen', 'Seasonal fresh fruit, butter, orange juice, sugar, dark rhum', 85000, '', '', 'Chọn loại trái: Xoài, Chuối, Thơm', 'Ask the waiter for your choice: Mango, Banana, Pineapple'],
        ['Tráng Miệng', 'Xoài Đốt Rượu', 'Mango Flambe', 'Xoài đốt rượu', 'Mango flambe', 85000, '', '', '', ''],
        ['Tráng Miệng', 'Thơm Đốt Rượu', 'Pineapple Flambe', 'Thơm đốt rượu', 'Pineapple flambe', 70000, '', '', '', ''],
        ['Tráng Miệng', 'Chuối Đốt Rượu', 'Banana Flambe', 'Chuối đốt rượu', 'Banana flambe', 65000, '', '', '', ''],
        ['Tráng Miệng', 'Trái Cây Theo Mùa', 'Seasonal Fresh Fruit', 'Trái cây theo mùa', 'Seasonal fresh fruit', 135000, '', '', '', ''],
        ['Tráng Miệng', 'Trái Cây 3 Loại', '03 Kind of Seasonal Fresh Fruit', 'Trái cây 3 loại: Xoài, Thơm, Ổi', '3 kind of seasonal fresh fruits', 135000, '', '', '', ''],
        ['Tráng Miệng', 'Trái Cây 4 Loại', '04 Kind of Seasonal Fresh Fruit', 'Trái cây 4 loại: Xoài, Thơm, Ổi, Dưa Hấu', '4 kind of seasonal fresh fruits', 175000, '', '', '', ''],
        ['Tráng Miệng', 'Xoài Đốt Rượu (Room Service)', 'Mango Flambe', 'Xoài đốt rượu', 'Mango flambe', 95000, '', 'room_service', '', ''],
        ['Tráng Miệng', 'Trái Cây 3 Loại (Room Service)', '03 Kind of Seasonal Fresh Fruit', 'Trái cây 3 loại', '3 kind of seasonal fresh fruits', 150000, '', 'room_service', '', ''],
        
        // MÓN PHỤ (Category 13)
        ['Món Phụ', 'Xà Lách Trộn Dầu Giấm', 'Mixed Salad with Vinegar Dressing', 'Xà lách, cà chua, hành tây, dưa leo', 'Salad, tomato, onion, cucumber', 40000, '', '', '', ''],
        ['Món Phụ', 'Rau Thập Cẩm Xào Tỏi', 'Stir Fried Vegetables with Garlic', 'Rau thập cẩm xào tỏi', 'Bông cải xanh, cà rốt, nấm rơm, nấm đông cô, bắp non, tỏi xay', 65000, '', '', '', ''],
        ['Món Phụ', 'Bánh Mì Bơ Tỏi', 'Garlic Bread', 'Bánh mì bơ lát tỏi băm', 'Garlic bread with butter and minced garlic', 70000, '', '', '', ''],
        ['Món Phụ', 'Khoai Tây Chiên', 'French Fries', 'Khoai tây chiên', 'French fries', 50000, 'bestseller', '', '', ''],
        ['Món Phụ', 'Cơm Trắng Thố', 'Steamed Rice / Big Bowl', 'Cơm trắng thố', 'Steamed rice big bowl', 60000, '', '', '', ''],
        ['Món Phụ', 'Cơm Trắng Chén', 'Steamed Rice / Small Bowl', 'Cơm trắng chén', 'Steamed rice small bowl', 20000, '', '', '', ''],
        ['Món Phụ', 'Cơm Chiên Tỏi Trứng', 'Fried Rice with Garlic & Egg', 'Cơm chiên tỏi trứng', 'Fried rice with garlic and egg', 55000, '', '', '', ''],
        ['Món Phụ', 'Cơm Chiên Tỏi', 'Fried Rice with Garlic', 'Cơm chiên tỏi', 'Fried rice with garlic', 40000, '', '', '', ''],
        
        // CÀ PHÊ (Category 14)
        ['Cà Phê', 'Cà Phê Nóng Hoặc Đá', 'Hot or Iced Coffee', 'Cà phê nóng hoặc đá', 'Hot or iced coffee', 35000, '', '', '', ''],
        ['Cà Phê', 'Cà Phê Sữa Nóng Hoặc Đá', 'Hot or Iced Coffee with Condensed Milk', 'Cà phê sữa nóng hoặc đá', 'Hot or iced coffee with condensed milk', 40000, 'bestseller', '', '', ''],
        ['Cà Phê', 'Bạc Sỉu Nóng Hoặc Đá', 'Hot or Iced Fresh Milk with Coffee', 'Bạc sỉu nóng hoặc đá', 'Hot or iced fresh milk with coffee', 40000, '', '', '', ''],
        
        // NƯỚC ÉP (Category 15)
        ['Nước Ép', 'Nước Cam Tươi', 'Orange Juice', 'Nước cam tươi', 'Fresh orange juice', 60000, 'bestseller', '', '', ''],
        ['Nước Ép', 'Nước Xoài', 'Mango Juice', 'Nước xoài', 'Mango juice', 65000, '', '', '', ''],
        ['Nước Ép', 'Nước Ép Thơm', 'Pineapple Juice', 'Nước ép thơm', 'Pineapple juice', 60000, '', '', '', ''],
        ['Nước Ép', 'Nước Ép Dưa Hấu', 'Water Melon Juice', 'Nước ép dưa hấu', 'Water melon juice', 60000, '', '', '', ''],
        ['Nước Ép', 'Nước Chanh Dây', 'Passion Fruit Juice', 'Nước chanh dây', 'Passion fruit juice', 60000, '', '', '', ''],
        
        // BIA (Category 16)
        ['Bia', 'Tiger Can Lon', 'Tiger', 'Bia Tiger can lon', 'Tiger beer can', 30000, '', '', '', ''],
        ['Bia', 'Tiger Silver Can Lon', 'Tiger Silver', 'Bia Tiger Silver can lon', 'Tiger Silver beer can', 32000, '', '', '', ''],
        ['Bia', 'Heineken Can Lon', 'Heineken', 'Bia Heineken can lon', 'Heineken beer can', 34000, '', '', '', ''],
        ['Bia', 'Heineken Silver Can Lon', 'Heineken Silver', 'Bia Heineken Silver can lon', 'Heineken Silver beer can', 36000, '', '', '', ''],
        
        // NƯỚC NGỌT (Category 17)
        ['Nước Ngọt', 'Pepsi', 'Pepsi', 'Nước ngọt Pepsi', 'Pepsi soft drink', 25000, '', '', '', ''],
        ['Nước Ngọt', '7 Up', '7 Up', 'Nước ngọt 7 Up', '7 Up soft drink', 25000, '', '', '', ''],
        ['Nước Ngọt', 'Soda', 'Soda', 'Nước soda', 'Soda water', 25000, '', '', '', ''],
        
        // NƯỚC SUỐI (Category 18)
        ['Nước Suối', 'Aquafina 500ml', 'Aquafina 500ml', 'Nước suối Aquafina 500ml', 'Aquafina mineral water 500ml', 20000, '', '', '', ''],
        ['Nước Suối', 'Aquafina 1.5L', 'Aquafina 1.5L', 'Nước suối Aquafina 1.5L', 'Aquafina mineral water 1.5L', 45000, '', '', '', ''],
        ['Nước Suối', 'Perrier', 'Perrier', 'Nước suối Perrier', 'Perrier sparkling water', 85000, '', '', '', ''],
        
        // TRÀ (Category 19)
        ['Trà', 'Trà Đen', 'Black Tea', 'Trà đen', 'Black tea', 50000, '', '', '', ''],
        ['Trà', 'Trà Lipton', 'Lipton Tea', 'Trà Lipton', 'Lipton tea', 50000, '', '', '', ''],
        ['Trà', 'Trà Sen', 'Lotus Tea', 'Trà sen', 'Lotus tea', 50000, '', '', '', ''],
        ['Trà', 'Trà Lài', 'Jasmine Tea', 'Trà lài', 'Jasmine tea', 50000, '', '', '', ''],
    ];
    
    $stmt = $pdo->prepare("INSERT INTO menu_items (category_id, name, name_en, description, description_en, price, image, is_available, service_type, tags, note_options, note_options_en) VALUES (?, ?, ?, ?, ?, ?, NULL, 1, ?, ?, ?, ?)");
    
    $inserted = 0;
    foreach ($items as $item) {
        $categoryId = $catIds[$item[0]] ?? null;
        if ($categoryId) {
            $stmt->execute([
                $categoryId,
                $item[1], // name
                $item[2], // name_en
                $item[3], // description
                $item[4], // description_en
                $item[5], // price
                $item[6] !== '' ? $item[6] : '', // tags
                $item[7] !== '' ? $item[7] : 'restaurant', // service_type
                $item[8] ?? '', // note_options
                $item[9] ?? ''  // note_options_en
            ]);
            $inserted++;
        }
    }
    $success[] = "Step 6: Inserted $inserted menu items.";
    
    $pdo->commit();
    
    $success[] = "<strong>✅ MIGRATION COMPLETED SUCCESSFULLY!</strong>";
    $success[] = "Total: 4 menu types, 19 categories, $inserted menu items.";
    
} catch (Exception $e) {
    $pdo->rollBack();
    $errors[] = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Migration 2026 - Aurora Restaurant</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #f59e0b; border-bottom: 3px solid #f59e0b; padding-bottom: 10px; }
        .success { background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107; }
        .btn { display: inline-block; padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #c82333; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍽️ Aurora Menu Migration 2026</h1>
        
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $msg): ?>
                <div class="error">❌ <?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <?php foreach ($success as $msg): ?>
                <div class="success">✅ <?php echo $msg; ?></div>
            <?php endforeach; ?>
            
            <div class="warning">
                <strong>⚠️ SECURITY WARNING:</strong><br>
                Please delete this file after running migration to prevent unauthorized access:<br>
                <pre>rm <?php echo __FILE__; ?></pre>
            </div>
            
            <a href="../admin/menu" class="btn">Go to Menu Admin</a>
        <?php else: ?>
            <p>Running migration...</p>
        <?php endif; ?>
    </div>
</body>
</html>