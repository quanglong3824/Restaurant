<?php
/**
 * AUTO SETUP ROOMS & DATABASE STRUCTURE (VERSION 2 - BULLETPROOF)
 * Aurora Hotel Plaza - Restaurant System
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', __DIR__);
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}

require_once BASE_PATH . '/config/database.php';

function columnExists($db, $table, $column) {
    $stmt = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    return (bool)$stmt->fetch();
}

try {
    $db = getDB();
    echo "<pre>--- Bắt đầu thiết lập hệ thống ---<br>";

    // BƯỚC 1: CẬP NHẬT CẤU TRÚC BẢNG
    echo "1. Kiểm tra cấu trúc bảng 'tables'...<br>";
    
    // Kiểm tra và thêm cột parent_id
    if (!columnExists($db, 'tables', 'parent_id')) {
        try {
            $db->exec("ALTER TABLE `tables` ADD `parent_id` int(10) unsigned DEFAULT NULL AFTER `id` ");
            echo "   - <span style='color:green;'>Đã thêm</span> cột 'parent_id'.<br>";
        } catch (PDOException $e) {
            echo "   - <span style='color:red;'>Lỗi</span> khi thêm 'parent_id': " . $e->getMessage() . "<br>";
        }
    } else {
        echo "   - Cột 'parent_id' <span style='color:blue;'>đã tồn tại</span>. Bỏ qua.<br>";
    }

    // Kiểm tra và thêm cột type
    if (!columnExists($db, 'tables', 'type')) {
        try {
            $db->exec("ALTER TABLE `tables` ADD `type` ENUM('table', 'room') NOT NULL DEFAULT 'table' AFTER `parent_id` ");
            echo "   - <span style='color:green;'>Đã thêm</span> cột 'type'.<br>";
        } catch (PDOException $e) {
            echo "   - <span style='color:red;'>Lỗi</span> khi thêm 'type': " . $e->getMessage() . "<br>";
        }
    } else {
        echo "   - Cột 'type' <span style='color:blue;'>đã tồn tại</span>. Bỏ qua.<br>";
    }

    // Kiểm tra và thêm Foreign Key cho parent_id
    try {
        // Kiểm tra xem FK đã tồn tại chưa (MySQL không có 'IF NOT EXISTS' cho FK trực tiếp)
        // Cách an toàn nhất là bọc trong try-catch
        $db->exec("ALTER TABLE `tables` ADD CONSTRAINT `fk_tables_parent_new` FOREIGN KEY (`parent_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL");
        echo "   - <span style='color:green;'>Đã thêm</span> ràng buộc khóa ngoại cho 'parent_id'.<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false || strpos($e->getMessage(), 'already exists') !== false) {
            echo "   - Ràng buộc khóa ngoại <span style='color:blue;'>đã tồn tại</span>. Bỏ qua.<br>";
        } else {
            echo "   - Thông tin khóa ngoại: " . $e->getMessage() . " (Có thể đã tồn tại hoặc bỏ qua).<br>";
        }
    }


    // BƯỚC 2: KHỞI TẠO DỮ LIỆU PHÒNG
    echo "<br>2. Kiểm tra dữ liệu phòng lưu trú (Tầng 7 - 12)...<br>";

    $floors = [
        7 => [701, 720],
        8 => [801, 819],
        9 => [901, 923],
        10 => [1001, 1023],
        11 => [1101, 1123],
        12 => [1201, 1220]
    ];

    $roomCount = 0;
    foreach ($floors as $floor => $range) {
        for ($roomNum = $range[0]; $roomNum <= $range[1]; $roomNum++) {
            if ($roomNum % 100 === 13) continue;

            $roomName = (string)$roomNum;
            $area = "Tầng $floor";
            
            $stmt = $db->prepare("SELECT id FROM tables WHERE name = ? AND type = 'room'");
            $stmt->execute([$roomName]);
            if ($stmt->fetch()) continue;

            $stmt = $db->prepare("INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES (?, ?, 3, 'room', ?, 1)");
            $stmt->execute([$roomName, $area, $roomNum]);
            $roomId = $db->lastInsertId();
            
            $token = bin2hex(random_bytes(8));
            $qrUrl = "/qr/menu?table_id=$roomId&token=$token";
            
            $stmt = $db->prepare("INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (?, ?, ?, 1, 0)");
            $stmt->execute([$roomId, $token, $qrUrl]);
            
            $roomCount++;
        }
    }

    if ($roomCount > 0) {
        echo "   - <span style='color:green;'>Thành công!</span> Đã thêm mới <b>$roomCount</b> phòng.<br>";
    } else {
        echo "   - Dữ liệu phòng <span style='color:blue;'>đã đầy đủ</span>. Không có phòng mới được thêm.<br>";
    }
    echo "<br>--- HOÀN TẤT THIẾT LẬP ---</pre>";

} catch (Exception $e) {
    die("<br><span style='color:red;'>LỖI NGHIÊM TRỌNG:</span> " . $e->getMessage());
}
