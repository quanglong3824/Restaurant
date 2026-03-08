<?php
// ============================================================
// Database Configuration
// Aurora Restaurant — Digital Menu & Order System
// ============================================================

// Tự động chuyển đổi cấu hình Database theo môi trường
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // Thông số host người dùng cung cấp
    define('DB_HOST', 'localhost'); // Cổng 3306 là mặc định nên không cần ghi :3306 trừ khi khác
    define('DB_USER', 'auroraho_longdev');
    define('DB_PASS', '@longdev3824');
}
define('DB_NAME', 'auroraho_restaurant');
define('DB_CHARSET', 'utf8mb4');


function getDB(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Database connection failed.']));
        }
    }
    return $pdo;
}
