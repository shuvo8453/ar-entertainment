<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$tables = db()->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "Database tables: " . implode(', ', $tables) . "\n";

if (in_array('reviews', $tables)) {
    echo "=== Structure of reviews table ===\n";
    $stmt = db()->query("DESCRIBE reviews");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    $sample = db()->query("SELECT * FROM reviews LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    echo "Sample data:\n";
    print_r($sample);
}
