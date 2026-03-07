<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require 'config/database.php';
require 'core/Model.php';
require 'models/Table.php';
require 'models/Order.php';

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES); }
define('BASE_PATH', __DIR__);
define('BASE_URL', '');
function formatPrice($p) { return $p; }

$order = ['id' => 1, 'guest_count' => 1, 'opened_at' => '2025-01-01', 'waiter_name' => 'W'];
$items = [];
$total = 0;
$table = ['id' => 1, 'name' => 'Table', 'parent_id' => null];
$table_display_name = 'Table';
$grouped = [];

ob_start();
try {
    require 'views/orders/index.php';
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
$out = ob_get_clean();
echo "Render string len: " . strlen($out) . "\n";
