<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require 'config/database.php';
require 'core/Model.php';
require 'models/Table.php';

$tableModel = new Table();

// 1. Create a parent and child setup
$db = getDB();
$db->exec("UPDATE tables SET status='occupied' WHERE id=1");
$db->exec("UPDATE tables SET parent_id=1, status='occupied' WHERE id=2");

// Verify
$before = $db->query("SELECT id, parent_id, status FROM tables WHERE id IN (1,2)")->fetchAll();
print_r(array("BEFORE_CLOSE" => $before));

// 2. Close table 1
$tableModel->close(1);

// Verify
$after = $db->query("SELECT id, parent_id, status FROM tables WHERE id IN (1,2)")->fetchAll();
print_r(array("AFTER_CLOSE" => $after));
