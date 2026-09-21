<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$pdo = Database::pdo();
$seeded_slugs = $pdo->query("SELECT slug FROM services")->fetchAll(PDO::FETCH_COLUMN);

$all_files = glob(__DIR__ . '/../services/*.html');
$remaining = [];
foreach ($all_files as $f) {
    $slug = basename($f, '.html');
    if (!in_array($slug, $seeded_slugs)) {
        $remaining[] = $slug;
    }
}

echo "Total static service files: " . count($all_files) . "\n";
echo "Already seeded slugs: " . count($seeded_slugs) . "\n";
echo "Remaining static service files: " . count($remaining) . "\n\n";

echo "--- Remaining Files ---\n";
foreach ($remaining as $i => $r) {
    echo ($i + 1) . ". " . $r . "\n";
}
