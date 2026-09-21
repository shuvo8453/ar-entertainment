<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$pdo = Database::pdo();
$brands = $pdo->query("SELECT id, name, brand_type, logo, website_url, sort_order FROM brands ORDER BY sort_order ASC")->fetchAll();

echo "========================================================\n";
echo "🏆 BRANDS & CLIENTS IN DATABASE (" . count($brands) . " Records)\n";
echo "========================================================\n";
foreach ($brands as $b) {
    echo sprintf("#%-2d [%-8s] %-45s -> %s\n", $b['id'], $b['brand_type'], $b['name'], $b['logo']);
}
