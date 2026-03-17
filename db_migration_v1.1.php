<?php
require_once __DIR__ . '/config/database.php';

echo "<h1>Database Migration Tool v1.1</h1>";

try {
    $db = getDB();
    
    echo "<p>Updating <code>order_items</code> status enum...</p>";
    $db->exec("ALTER TABLE order_items MODIFY COLUMN status ENUM('draft', 'pending', 'confirmed', 'cooking', 'served', 'cancelled') DEFAULT 'draft'");
    echo "<p style='color: green;'>[OK] order_items status updated.</p>";

    echo "<p>Updating <code>orders</code> table source...</p>";
    try {
        $db->exec("ALTER TABLE orders MODIFY COLUMN order_source ENUM('waiter', 'customer_qr') DEFAULT 'waiter'");
        echo "<p style='color: green;'>[OK] Updated order_source enum.</p>";
    } catch (Exception $e) {
        $db->exec("ALTER TABLE orders ADD COLUMN order_source ENUM('waiter', 'customer_qr') DEFAULT 'waiter' AFTER status");
        echo "<p style='color: green;'>[OK] Added order_source column.</p>";
    }

    echo "<h3>Migration Done!</h3>";
    echo "<p><a href='index.php'>Go back to Home</a></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}
