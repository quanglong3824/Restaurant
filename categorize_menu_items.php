<?php
/**
 * Categorize Menu Items by Service Type
 * Based on actual menu data analysis
 * 
 * Run from browser: https://aurorahotelplaza.com/restaurant/database/categorize_menu_items.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

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

// Check if already categorized
$stmt = $pdo->query("SELECT COUNT(*) FROM menu_items WHERE service_type = '' OR service_type IS NULL");
$emptyCount = (int) $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorize Menu Items - Aurora Restaurant</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #f59e0b; border-bottom: 3px solid #f59e0b; padding-bottom: 10px; }
        .success { color: #28a745; background: #d4edda; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6; font-size: 0.85rem; }
        th { background: #f8f9fa; font-weight: 600; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
        .badge-restaurant { background: #cce5ff; color: #004085; }
        .badge-room_service { background: #fff3cd; color: #856404; }
        .stats { display: flex; gap: 20px; flex-wrap: wrap; margin: 20px 0; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 8px; flex: 1; min-width: 150px; border-left: 4px solid #f59e0b; }
        .stat-card h3 { margin: 0; font-size: 2rem; color: #f59e0b; }
        .stat-card p { margin: 5px 0 0; color: #6c757d; font-size: 0.9rem; }
        .btn { display: inline-block; padding: 10px 20px; background: #f59e0b; color: white; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; font-size: 0.9rem; }
        .btn:hover { background: #d4af37; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍽️ Categorize Menu Items by Service Type</h1>
        
        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'run') {
            // Run categorization
            try {
                $pdo->beginTransaction();
                
                // Step 1: Set all empty service_type to 'restaurant'
                $stmt = $pdo->prepare("UPDATE menu_items SET service_type = 'restaurant' WHERE service_type = '' OR service_type IS NULL");
                $stmt->execute();
                $restaurantCount = $stmt->rowCount();
                
                // Step 2: Set room_service items (those with "(Room Service)" in name)
                $stmt = $pdo->prepare("UPDATE menu_items SET service_type = 'room_service' WHERE name LIKE '%(Room Service)%'");
                $stmt->execute();
                $roomServiceCount = $stmt->rowCount();
                
                $pdo->commit();
                
                echo "<div class='success'>";
                echo "<strong>✅ Categorization Complete!</strong><br>";
                echo "• Set {$restaurantCount} items to 'restaurant'<br>";
                echo "• Set {$roomServiceCount} items to 'room_service' (items with '(Room Service)' in name)";
                echo "</div>";
                
                // Show summary
                $stmt = $pdo->query("SELECT service_type, COUNT(*) as count FROM menu_items GROUP BY service_type ORDER BY count DESC");
                $summary = $stmt->fetchAll();
                
                echo "<h2>📊 Summary</h2>";
                echo "<div class='stats'>";
                foreach ($summary as $row) {
                    $type = $row['service_type'] ?: '(empty)';
                    $icon = $type === 'restaurant' ? '🍴' : ($type === 'room_service' ? '🛏️' : '❓');
                    echo "<div class='stat-card'><h3>{$row['count']}</h3><p>{$icon} {$type}</p></div>";
                }
                echo "</div>";
                
                // Show room service items
                $stmt = $pdo->query("SELECT id, name, price, service_type FROM menu_items WHERE service_type = 'room_service' ORDER BY name");
                $roomItems = $stmt->fetchAll();
                
                echo "<h2>🛏️ Room Service Items (" . count($roomItems) . ")</h2>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th><th>Price</th></tr>";
                foreach ($roomItems as $item) {
                    echo "<tr>";
                    echo "<td>{$item['id']}</td>";
                    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                    echo "<td>" . number_format($item['price'], 0, ',', '.') . " đ</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
            } catch (PDOException $e) {
                $pdo->rollBack();
                echo "<div class='warning'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            
        } else {
            // Show current state
            $stmt = $pdo->query("SELECT service_type, COUNT(*) as count FROM menu_items GROUP BY service_type ORDER BY count DESC");
            $summary = $stmt->fetchAll();
            
            echo "<h2>📊 Current Distribution</h2>";
            echo "<div class='stats'>";
            foreach ($summary as $row) {
                $type = $row['service_type'] ?: '(empty)';
                echo "<div class='stat-card'><h3>{$row['count']}</h3><p>{$type}</p></div>";
            }
            echo "</div>";
            
            if ($emptyCount > 0) {
                echo "<div class='warning'>";
                echo "⚠️ <strong>Warning:</strong> {$emptyCount} items have empty service_type.<br>";
                echo "Click the button below to categorize them automatically.";
                echo "</div>";
                
                echo "<div class='info'>";
                echo "<strong>📋 Categorization Rules:</strong><br>";
                echo "1. All items with empty service_type → <strong>'restaurant'</strong><br>";
                echo "2. Items with '(Room Service)' in name → <strong>'room_service'</strong><br>";
                echo "</div>";
                
                echo "<a href='?action=run' class='btn' onclick=\"return confirm('Run categorization? This will update all menu items.')\">▶️ Run Categorization</a>";
            } else {
                echo "<div class='success'>✅ All items are already categorized!</div>";
            }
            
            // Show all items
            $stmt = $pdo->query("SELECT id, name, service_type, price FROM menu_items ORDER BY service_type, name LIMIT 50");
            $items = $stmt->fetchAll();
            
            echo "<h2>📋 Sample Items (first 50)</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Service Type</th><th>Price</th></tr>";
            foreach ($items as $item) {
                $badgeClass = 'badge-' . ($item['service_type'] ?: 'both');
                echo "<tr>";
                echo "<td>{$item['id']}</td>";
                echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                echo "<td><span class='badge {$badgeClass}'>" . ($item['service_type'] ?: 'empty') . "</span></td>";
                echo "<td>" . number_format($item['price'], 0, ',', '.') . " đ</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
        
        <p style="margin-top: 20px;">
            <a href="<?= BASE_URL ?>/admin/menu" class="btn">← Back to Admin Menu</a>
        </p>
    </div>
</body>
</html>