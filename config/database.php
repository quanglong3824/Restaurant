<?php
// ============================================================
// Database Configuration
// Aurora Restaurant — Digital Menu & Order System
// ============================================================

/**
 * Load .env file from a specific path
 */
function loadEnv($path)
{
    if (!file_exists($path)) return false;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) continue;
        
        $name = trim($parts[0]);
        $value = trim($parts[1]);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
    return true;
}

// Đường dẫn tới file .env nằm ngoài public_html (ngang cấp public_html trong thư mục config)
// Giả sử cấu trúc: /home/user/public_html/restaurant và /home/user/config/.env
// Hoặc đơn giản là lùi lại 2 cấp từ project root
$envPath = dirname(BASE_PATH, 2) . '/config/.env';
if (!loadEnv($envPath)) {
    // Thử thêm 1 phương án dự phòng nếu project nằm trực tiếp trong public_html
    $envPath = dirname(BASE_PATH, 1) . '/config/.env';
    loadEnv($envPath);
}

// Ưu tiên lấy từ biến môi trường (.env), nếu không có mới dùng mặc định
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: (($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') ? 'root' : 'auroraho_longdev'));
define('DB_PASS', getenv('DB_PASS') ?: (($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') ? '' : '@longdev3824'));
define('DB_NAME', getenv('DB_NAME') ?: 'auroraho_restaurant');
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
