<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$districts = db()->query("
    SELECT id, city_name, slug, content 
    FROM service_areas 
    WHERE sort_order BETWEEN 22 AND 32 
    ORDER BY sort_order ASC
")->fetchAll(PDO::FETCH_ASSOC);

echo "========================================================\n";
echo "🔍 VERIFYING BATCH 5.3.3 ASSETS IN DATABASE\n";
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

$total_districts = (int)db()->query("SELECT COUNT(*) FROM service_areas WHERE status = 'active'")->fetchColumn();
echo "========================================================\n";
echo "Total Active District Guides in Database: {$total_districts}\n";
echo "========================================================\n";
