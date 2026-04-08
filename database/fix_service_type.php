<?php
/**
 * Fix service_type for existing menu items
 * Run this file from browser: https://aurorahotelplaza.com/restaurant/database/fix_service_type.php
 * 
 * IMPORTANT: Delete this file after running for security!
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only allow local access or authenticated admin
$allowed_ips = ['127.0.0.1', '::1', 'localhost'];
$is_local = in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowed_ips);

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

if (!$is_local && session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
$success = [];
$stats = [];

try {
    $pdo->beginTransaction();
    
    // Step 1: Count items with empty service_type
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM menu_items WHERE service_type = ''");
    $emptyCount = $stmt->fetch()['count'];
    $stats['empty_service_type'] = $emptyCount;
    
    // Step 2: Count total items
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM menu_items");
    $totalCount = $stmt->fetch()['count'];
    $stats['total_items'] = $totalCount;
    
    // Step 3: Update room_service items (those with "(Room Service)" in name)
    $stmt = $pdo->prepare("UPDATE menu_items SET service_type = 'room_service' 
                           WHERE service_type = '' AND (name LIKE '%(Room Service)%' OR name LIKE '%(Room Service - Chén)%')");
    $stmt->execute();
    $roomServiceCount = $stmt->rowCount();
    $stats['set_to_room_service'] = $roomServiceCount;
    
    // Step 4: Update remaining empty service_type to 'both'
    $stmt = $pdo->prepare("UPDATE menu_items SET service_type = 'both' 
                           WHERE service_type = ''");
    $stmt->execute();
    $bothCount = $stmt->rowCount();
    $stats['set_to_both'] = $bothCount;
    
    // Step 5: Get final stats
    $stmt = $pdo->query("SELECT service_type, COUNT(*) as count FROM menu_items GROUP BY service_type");
    $finalStats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    $stats['final_distribution'] = $finalStats;
    
    $pdo->commit();
    
    $success[] = "<strong>✅ FIX COMPLETED SUCCESSFULLY!</strong>";
    
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
    <title>Fix Service Type - Aurora Restaurant</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #f59e0b; border-bottom: 3px solid #f59e0b; padding-bottom: 10px; }
        .success { background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107; }
        .stats { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .stats table { width: 100%; border-collapse: collapse; }
        .stats td, .stats th { padding: 8px; text-align: left; border-bottom: 1px solid #dee2e6; }
        .btn { display: inline-block; padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #c82333; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Service Type for Menu Items</h1>
        
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $msg): ?>
                <div class="error">❌ <?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <?php foreach ($success as $msg): ?>
                <div class="success">✅ <?php echo $msg; ?></div>
            <?php endforeach; ?>
            
            <div class="stats">
                <h3>📊 Statistics:</h3>
                <table>
                    <tr><th>Metric</th><th>Value</th></tr>
                    <tr><td>Total menu items</td><td><?php echo $stats['total_items']; ?></td></tr>
                    <tr><td>Items with empty service_type (before fix)</td><td><?php echo $stats['empty_service_type']; ?></td></tr>
                    <tr><td>Items set to 'room_service'</td><td><?php echo $stats['set_to_room_service']; ?></td></tr>
                    <tr><td>Items set to 'both'</td><td><?php echo $stats['set_to_both']; ?></td></tr>
                </table>
                
                <h4>Final Distribution:</h4>
                <table>
                    <tr><th>service_type</th><th>Count</th></tr>
                    <?php foreach ($stats['final_distribution'] as $type => $count): ?>
                        <tr><td><?php echo htmlspecialchars($type); ?></td><td><?php echo $count; ?></td></tr>
                    <?php endforeach; ?>
                </table>
            </div>
            
            <div class="warning">
                <strong>⚠️ SECURITY WARNING:</strong><br>
                Please delete this file after running to prevent unauthorized access:<br>
                <pre>rm <?php echo __FILE__; ?></pre>
            </div>
            
            <a href="../admin/menu" class="btn">Go to Menu Admin</a>
        <?php else: ?>
            <p>Running fix...</p>
        <?php endif; ?>
    </div>
</body>
</html>