<?php
// File test nhanh - XÓA SAU KHI DÙNG
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/constants.php';
require_once 'config/database.php';

echo "<h1>Test Settings Table</h1>";

try {
    $db = getDB();
    
    // Check if table exists
    $result = $db->query("SHOW TABLES LIKE 'settings'");
    if ($result->rowCount() > 0) {
        echo "<p style='color:green'>✓ Bảng settings tồn tại</p>";
        
        // Get all settings
        $settings = $db->query("SELECT * FROM settings ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h2>Dữ liệu trong bảng:</h2>";
        echo "<pre>";
        print_r($settings);
        echo "</pre>";
        
        if (empty($settings)) {
            echo "<p style='color:orange'>⚠ Bảng rỗng! Chèn dữ liệu mẫu...</p>";
            
            $db->exec("INSERT INTO settings (setting_key, setting_value, description) VALUES
                ('dev_mode', '0', 'Chế độ phát triển - Tắt kiểm tra vị trí'),
                ('maintenance_mode', '0', 'Chế độ bảo trì'),
                ('allow_online_payment', '1', 'Cho phép thanh toán online'),
                ('auto_print_orders', '1', 'Tự động in đơn hàng')
            ");
            
            echo "<p style='color:green'>✓ Đã chèn dữ liệu mẫu</p>";
        }
    } else {
        echo "<p style='color:red'>✗ Bảng settings KHÔNG tồn tại!</p>";
        echo "<p>Đang tạo bảng...</p>";
        
        $db->exec("CREATE TABLE IF NOT EXISTS settings (
            id int(11) NOT NULL AUTO_INCREMENT,
            setting_key varchar(100) NOT NULL,
            setting_value text,
            description varchar(255) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_setting_key (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        echo "<p style='color:green'>✓ Đã tạo bảng settings</p>";
        
        $db->exec("INSERT INTO settings (setting_key, setting_value, description) VALUES
            ('dev_mode', '0', 'Chế độ phát triển - Tắt kiểm tra vị trí'),
            ('maintenance_mode', '0', 'Chế độ bảo trì'),
            ('allow_online_payment', '1', 'Cho phép thanh toán online'),
            ('auto_print_orders', '1', 'Tự động in đơn hàng')
        ");
        
        echo "<p style='color:green'>✓ Đã chèn dữ liệu mẫu</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Lỗi: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='it/settings'>→ Quay lại trang Settings</a></p>";
echo "<p style='color:red;font-weight:bold;'>⚠ XÓA FILE NÀY SAU KHI DÙNG!</p>";