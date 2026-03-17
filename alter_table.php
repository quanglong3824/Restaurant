<?php
require 'config/database.php';
try {
    $db = getDB();
    $db->exec("ALTER TABLE `order_notifications` MODIFY `order_id` int(10) unsigned NULL;");
    echo "Successfully altered order_notifications table.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
