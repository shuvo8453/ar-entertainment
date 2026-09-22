<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$tables = ['portfolio', 'services', 'brands', 'reviews', 'blogs'];
foreach ($tables as $t) {
    $stmt = db()->query("SHOW COLUMNS FROM `{$t}`");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "=== {$t} ===\n" . implode(', ', $cols) . "\n\n";
}
