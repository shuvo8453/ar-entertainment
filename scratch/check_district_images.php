<?php
$batch1 = [
    'dhaka' => 'images/dhaka-shooting-locations.webp',
    'gazipur' => 'images/service-area/gazipur-video-production.webp',
    'narayanganj' => 'images/service-area/narayanganj-video-production.webp',
    'tangail' => 'images/service-area/tangail-video-production.webp',
    'manikganj' => 'images/service-area/manikganj-video-production.webp',
    'munshiganj' => 'images/service-area/munshiganj-video-production.webp',
    'narsingdi' => 'images/service-area/narsingdi-video-production.webp',
    'faridpur' => 'images/service-area/faridpur-video-production.webp',
    'gopalganj' => 'images/service-area/gopalganj-video-production.webp',
    'madaripur' => 'images/service-area/madaripur-video-production.webp'
];

$batch2 = [
    'chattogram' => 'images/film-fixer-chittagong-og.webp',
    'coxsbazar' => 'images/coxsbazar-sea-beach.webp',
    'bandarban' => 'images/service-area/bandarban-video-production.webp',
    'rangamati' => 'images/service-area/rangamati-video-production.webp',
    'khagrachari' => 'images/service-area/khagrachari-video-production.webp',
    'feni' => 'images/service-area/feni-video-production.webp',
    'noakhali' => 'images/service-area/noakhali-video-production.webp',
    'lakshmipur' => 'images/service-area/lakshmipur-video-production.webp',
    'chandpur' => 'images/service-area/chandpur-video-production.webp',
    'cumilla' => 'images/service-area/cumilla-video-production.jpg',
    'brahmanbaria' => 'images/service-area/brahmanbaria-video-production.webp'
];

echo "--- BATCH 1 CHECK ---\n";
foreach ($batch1 as $k => $p) {
    echo sprintf("%-15s: %s (size: %s bytes)\n", $k, file_exists($p) ? "EXISTS" : "MISSING", file_exists($p) ? filesize($p) : 0);
}

echo "\n--- BATCH 2 CHECK ---\n";
foreach ($batch2 as $k => $p) {
    echo sprintf("%-15s: %s (size: %s bytes)\n", $k, file_exists($p) ? "EXISTS" : "MISSING", file_exists($p) ? filesize($p) : 0);
}
