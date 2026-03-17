<?php
// Bypass config/database.php logic for CLI
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'auroraho_longdev');
define('DB_PASS', '@longdev3824');
define('DB_NAME', 'auroraho_restaurant');
define('DB_CHARSET', 'utf8mb4');

function getDB(): PDO
{
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
    return new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}

try {
    $db = getDB();
    
    echo "Updating order_items status enum...\n";
    $db->exec("ALTER TABLE order_items MODIFY COLUMN status ENUM('draft', 'pending', 'confirmed', 'cooking', 'served', 'cancelled') DEFAULT 'draft'");
    echo "[OK] order_items status updated.\n";

    echo "Updating orders table source and status...\n";
    try {
        $db->exec("ALTER TABLE orders MODIFY COLUMN order_source ENUM('waiter', 'customer_qr') DEFAULT 'waiter'");
        echo "[OK] Updated order_source enum.\n";
    } catch (Exception $e) {
        // Maybe column doesn't exist
        $db->exec("ALTER TABLE orders ADD COLUMN order_source ENUM('waiter', 'customer_qr') DEFAULT 'waiter' AFTER status");
        echo "[OK] Added order_source column.\n";
    }

    echo "Done.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
