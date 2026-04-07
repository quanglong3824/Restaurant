<?php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

try {
    $db = getDB();
    // Add column service_type
    $db->exec("ALTER TABLE `menu_items` ADD COLUMN `service_type` ENUM('restaurant', 'room_service', 'both') NOT NULL DEFAULT 'both' AFTER `is_active`");
    echo "<h1>Cập nhật cơ sở dữ liệu thành công!</h1>";
    echo "<p>Cột 'service_type' đã được thêm vào bảng 'menu_items'.</p>";
    echo "<p>Vui lòng xóa file này (`migrate_room_service.php`) để bảo mật.</p>";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "<h1>Cột service_type đã tồn tại!</h1>";
        echo "<p>Cơ sở dữ liệu đã được cập nhật trước đó.</p>";
        echo "<p>Vui lòng xóa file này (`migrate_room_service.php`) để bảo mật.</p>";
    } else {
        echo "<h1>Lỗi:</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
