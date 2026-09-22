<?php
$files = [
    'rmg-garment-textile-og.webp',
    'garments-factory.webp',
    'one-shoot-multiple-ovc-versions.webp',
    'multiple-ovc-versions.webp',
    'fmcg-one-shoot-multiple-ovc-ads-bangladesh.webp',
    'ai-image-tools-2026.webp',
    'ai-voiceover-bangla-service-libanza-films.webp',
    'benefits-of-creating-training-videos.webp',
    'video-ad-performance.jpg',
    'the-future-of-programming-with-artificial-intelligence.jpg'
];
foreach($files as $f) {
    $path = __DIR__ . '/../images/' . $f;
    echo $f . ': ' . (file_exists($path) ? filesize($path) . ' bytes' : 'MISSING') . PHP_EOL;
}
