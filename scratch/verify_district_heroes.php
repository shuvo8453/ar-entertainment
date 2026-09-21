<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$districts = db()->query("SELECT city_name, slug, content FROM service_areas WHERE status = 'active' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

echo "========================================================\n";
echo "🔍 VERIFYING DISTRICT HERO & INFOGRAPHIC ASSETS IN DATABASE\n";
echo "========================================================\n\n";

foreach ($districts as $d) {
    preg_match_all('/<img src="([^"]+)"/i', $d['content'], $matches);
    $hero = $matches[1][0] ?? 'MISSING';
    $infographic = $matches[1][1] ?? 'MISSING';
    
    $hero_exists = file_exists(__DIR__ . '/../' . $hero) ? 'EXISTS (' . round(filesize(__DIR__ . '/../' . $hero)/1024, 1) . ' KB)' : 'FILE NOT FOUND';
    $info_exists = file_exists(__DIR__ . '/../' . $infographic) ? 'EXISTS (' . round(filesize(__DIR__ . '/../' . $infographic)/1024, 1) . ' KB)' : 'FILE NOT FOUND';

    echo sprintf("%-15s | Hero: %-48s [%s]\n                | Info: %-48s [%s]\n", 
        $d['city_name'], 
        $hero, 
        $hero_exists, 
        $infographic, 
        $info_exists
    );
}

echo "\n========================================================\n";
echo "Total Active Districts in Database: " . count($districts) . "\n";
echo "========================================================\n";
