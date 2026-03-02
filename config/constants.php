<?php
// ============================================================
// App Constants
// Aurora Restaurant — Digital Menu & Order System
// ============================================================

// App info
define('APP_NAME', 'Aurora Restaurant');
define('APP_VERSION', '1.0.0');
define('APP_LANG', 'vi');

// Paths
define('BASE_PATH', dirname(__DIR__));
// Tự động xác định BASE_URL (đường dẫn tương đối từ root tên miền)
// Ví dụ: nếu chạy ở http://localhost/project/index.php thì BASE_URL là /project
// Nếu chạy ở http://aurora.com/index.php thì BASE_URL là (rỗng)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', ($scriptDir === '/' || $scriptDir === '\\') ? '' : rtrim($scriptDir, '/'));

define('UPLOAD_PATH', BASE_PATH . '/public/uploads/');
define('UPLOAD_URL', BASE_URL . '/public/uploads/');

// Session
define('SESSION_NAME', 'aurora_restaurant_session');
define('SESSION_LIFETIME', 60 * 60 * 8); // 8 giờ

// Roles
define('ROLE_WAITER', 'waiter');
define('ROLE_ADMIN', 'admin');
define('ROLE_IT', 'it');

// Upload
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Table status
define('TABLE_AVAILABLE', 'available');
define('TABLE_OCCUPIED', 'occupied');

// Order status
define('ORDER_OPEN', 'open');
define('ORDER_CLOSED', 'closed');

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');
