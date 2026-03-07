<?php
require_once __DIR__ . '/config/database.php';
try {
    $db = getDB();
    $stmt = $db->query("DESC orders");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo $e->getMessage();
}
