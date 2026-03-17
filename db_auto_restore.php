<?php
/**
 * Database Restore Script — Aurora Restaurant
 * Description: Automatically restores the database from the specified SQL backup file.
 */

// 1. Load configuration
require_once __DIR__ . '/config/database.php';

// 2. Configuration - NEWEST BACKUP
$backup_file = __DIR__ . '/database/backup_auroraho_restaurant_20260317_144346.sql';

// Check if file exists
if (!file_exists($backup_file)) {
    die("Error: Backup file not found at $backup_file");
}

echo "<h1>Database Auto-Restore Tool</h1>";
echo "<p>Restoring from: <code>" . basename($backup_file) . "</code></p>";
echo "<hr>";

try {
    $db = getDB();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read the SQL file
    $sql = file_get_contents($backup_file);

    // Disable foreign key checks for clean restore
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
    echo "<p style='color: blue;'>[OK] Disabled foreign key checks.</p>";

    // Split SQL into individual queries
    // Note: Simple split by semicolon.
    $queries = explode(';', $sql);
    
    $success_count = 0;
    $error_count = 0;

    foreach ($queries as $query) {
        $query = trim($query);
        if (empty($query)) continue;

        try {
            $db->exec($query);
            $success_count++;
        } catch (PDOException $e) {
            $error_count++;
            echo "<p style='color: red;'>[Error] Query failed: " . htmlspecialchars(substr($query, 0, 100)) . "... <br>Message: " . $e->getMessage() . "</p>";
        }
    }

    // Re-enable foreign key checks
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "<p style='color: blue;'>[OK] Re-enabled foreign key checks.</p>";

    echo "<hr>";
    echo "<h3>Restore Completed!</h3>";
    echo "<p>Successful queries: <strong>$success_count</strong></p>";
    if ($error_count > 0) {
        echo "<p style='color: orange;'>Queries with errors: <strong>$error_count</strong> (See details above if any)</p>";
    } else {
        echo "<p style='color: green;'>All queries executed successfully.</p>";
    }
    
    echo "<p><a href='index.php'>Go back to Home</a></p>";

} catch (Exception $e) {
    die("<h2 style='color: red;'>Fatal Error: " . $e->getMessage() . "</h2>");
}
