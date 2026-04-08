<?php
/**
 * Test script to verify menu items are showing correctly
 * Run from browser: https://aurorahotelplaza.com/restaurant/database/test_menu_items.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define BASE_PATH if not already defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/..');
}

// Simple database config
$db_host = 'localhost';
$db_user = 'auroraho_longdev';
$db_pass = '@longdev3824';
$db_name = 'auroraho_restaurant';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Menu Items - Aurora Restaurant</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #f59e0b; border-bottom: 3px solid #f59e0b; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #dee2e6; font-size: 0.85rem; }
        th { background: #f8f9fa; font-weight: 600; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-both { background: #d4edda; color: #155724; }
        .badge-restaurant { background: #cce5ff; color: #004085; }
        .badge-room_service { background: #fff3cd; color: #856404; }
        .badge-empty { background: #f8d7da; color: #721c24; }
        .stats { display: flex; gap: 20px; flex-wrap: wrap; margin: 20px 0; }
        .stat-card { background: #f8f9fa; padding: 15px; border-radius: 8px; flex: 1; min-width: 150px; }
        .stat-card h3 { margin: 0; font-size: 2rem; color: #f59e0b; }
        .stat-card p { margin: 5px 0 0; color: #6c757d; font-size: 0.85rem; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test Menu Items Display</h1>
        
        <?php
        // Test 1: Count by service_type
        echo "<h2>1. Service Type Distribution</h2>";
        $stmt = $pdo->query("SELECT service_type, COUNT(*) as count FROM menu_items GROUP BY service_type");
        $distribution = $stmt->fetchAll();
        
        echo "<div class='stats'>";
        foreach ($distribution as $row) {
            $type = $row['service_type'] ?: '(empty)';
            echo "<div class='stat-card'><h3>{$row['count']}</h3><p>{$type}</p></div>";
        }
        echo "</div>";
        
        // Test 2: Simulate what customer QR sees
        echo "<h2>2. Customer QR Simulation</h2>";
        
        // Restaurant customers
        echo "<h3>Restaurant Customers (service_type IN ('restaurant', 'both') OR service_type = '')</h3>";
        $stmt = $pdo->query("
            SELECT COUNT(*) as count FROM menu_items i
            LEFT JOIN menu_categories c ON c.id = i.category_id
            WHERE i.is_active = 1 
            AND (i.service_type IN ('restaurant', 'both') OR i.service_type = '')
        ");
        $restaurantCount = $stmt->fetch()['count'];
        echo "<p><strong>Items visible:</strong> {$restaurantCount}</p>";
        
        // Room service customers
        echo "<h3>Room Service Customers (service_type IN ('room_service', 'both') OR service_type = '')</h3>";
        $stmt = $pdo->query("
            SELECT COUNT(*) as count FROM menu_items i
            LEFT JOIN menu_categories c ON c.id = i.category_id
            WHERE i.is_active = 1 
            AND (i.service_type IN ('room_service', 'both') OR i.service_type = '')
        ");
        $roomCount = $stmt->fetch()['count'];
        echo "<p><strong>Items visible:</strong> {$roomCount}</p>";
        
        // Test 3: Show sample items
        echo "<h2>3. Sample Items (first 30)</h2>";
        $stmt = $pdo->query("
            SELECT i.id, i.name, i.service_type, i.is_active, c.name as category_name, c.menu_type
            FROM menu_items i
            LEFT JOIN menu_categories c ON c.id = i.category_id
            ORDER BY c.sort_order, i.sort_order, i.name
            LIMIT 30
        ");
        $items = $stmt->fetchAll();
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Menu Type</th><th>Service Type</th><th>Active</th></tr>";
        foreach ($items as $item) {
            $stBadge = $item['service_type'] 
                ? "badge-{$item['service_type']}" 
                : 'badge-empty';
            $stLabel = $item['service_type'] ?: '(empty)';
            echo "<tr>";
            echo "<td>{$item['id']}</td>";
            echo "<td>" . htmlspecialchars($item['name']) . "</td>";
            echo "<td>" . htmlspecialchars($item['category_name'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($item['menu_type'] ?? 'N/A') . "</td>";
            echo "<td><span class='badge {$stBadge}'>{$stLabel}</span></td>";
            echo "<td>" . ($item['is_active'] ? '✅' : '❌') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test 4: Check MenuItem model method
        echo "<h2>4. Model Test (getAllActive)</h2>";
        require_once BASE_PATH . '/models/Model.php';
        require_once BASE_PATH . '/models/MenuItem.php';
        
        $menuModel = new MenuItem();
        
        // Test with 'restaurant' service type
        $restaurantItems = $menuModel->getAllActive('restaurant');
        echo "<p><strong>getAllActive('restaurant'):</strong> " . count($restaurantItems) . " items</p>";
        
        // Test with 'room_service' service type
        $roomItems = $menuModel->getAllActive('room_service');
        echo "<p><strong>getAllActive('room_service'):</strong> " . count($roomItems) . " items</p>";
        
        // Test with empty (all items)
        $allItems = $menuModel->getAllActive('');
        echo "<p><strong>getAllActive(''):</strong> " . count($allItems) . " items</p>";
        
        ?>
        
        <h2>5. Conclusion</h2>
        <?php
        if (count($restaurantItems) >= 100) {
            echo "<p style='color: #28a745; font-weight: bold;'>✅ OK: Customer QR should see all menu items!</p>";
        } else {
            echo "<p style='color: #dc3545; font-weight: bold;'>❌ ISSUE: Customer QR only sees " . count($restaurantItems) . " items</p>";
            echo "<p><strong>Fix:</strong> Run the fix_service_type.php script to update service_type values.</p>";
        }
        ?>
        
        <p style="margin-top: 20px;">
            <a href="fix_service_type.php" style="color: #f59e0b;">→ Run Fix Service Type Script</a>
        </p>
    </div>
</body>
</html>