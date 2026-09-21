<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$db = db();

// Remove old dummy placeholder entries if any (where slug doesn't start with video-production-company-in-)
$db->exec("DELETE FROM service_areas WHERE slug NOT LIKE 'video-production-company-in-%'");

// Re-seed cleanly
require_once __DIR__ . '/../database/seeds/seed_service_areas_batch1.php';
require_once __DIR__ . '/../database/seeds/seed_service_areas_batch2.php';
require_once __DIR__ . '/../database/seeds/generate_district_batch1_assets.php';
require_once __DIR__ . '/../database/seeds/generate_district_batch2_assets.php';

$districts = $db->query("SELECT id, city_name, slug, content FROM service_areas WHERE status = 'active' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

echo "\n========================================================\n";
echo "🔍 VERIFYING CLEAN RE-SEEDED DISTRICT GUIDES (BATCH 5.3.1 & 5.3.2)\n";
echo "========================================================\n\n";

foreach ($districts as $d) {
    preg_match_all('/<img src="([^"]+)"/i', $d['content'], $matches);
    $hero = $matches[1][0] ?? 'MISSING';
    $infographic = $matches[1][1] ?? 'MISSING';
    
    $hero_file = __DIR__ . '/../' . $hero;
    $info_file = __DIR__ . '/../' . $infographic;

    $hero_exists = file_exists($hero_file) ? 'EXISTS (' . round(filesize($hero_file)/1024, 1) . ' KB)' : 'FILE NOT FOUND';
    $info_exists = file_exists($info_file) ? 'EXISTS (' . round(filesize($info_file)/1024, 1) . ' KB)' : 'FILE NOT FOUND';

    echo sprintf("[%02d] %-15s | Hero: %-48s [%s]\n     %-15s | Info: %-48s [%s]\n\n", 
        $d['id'],
        $d['city_name'], 
        $hero, 
        $hero_exists, 
        '',
        $infographic, 
        $info_exists
    );
}

echo "========================================================\n";
echo "Total Clean Active District Guides in Database: " . count($districts) . "\n";
echo "========================================================\n";
