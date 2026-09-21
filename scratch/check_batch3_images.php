<?php
$b3 = [
    'sylhet' => 'images/service-area/sylhet-video-production.webp',
    'maulvibazar' => 'images/service-area/maulvibazar-video-production.webp',
    'sunamganj' => 'images/service-area/sunamganj-video-production.webp',
    'habiganj' => 'images/service-area/habiganj-video-production.webp',
    'mymensingh' => 'images/service-area/mymensingh-video-production.webp',
    'jamalpur' => 'images/service-area/jamalpur-video-production.webp',
    'netrokona' => 'images/service-area/netrokona-video-production.webp',
    'sherpur' => 'images/service-area/sherpur-video-production.webp',
    'kishoreganj' => 'images/service-area/kishoreganj-video-production.webp',
    'rajbari' => 'images/service-area/rajbari-video-production.webp',
    'shariatpur' => 'images/service-area/shariatpur-video-production.webp'
];

echo "--- BATCH 5.3.3 IMAGE FILES CHECK ---\n";
foreach ($b3 as $k => $p) {
    echo sprintf("%-15s: %s (size: %s bytes)\n", $k, file_exists($p) ? "EXISTS" : "MISSING", file_exists($p) ? filesize($p) : 0);
}
