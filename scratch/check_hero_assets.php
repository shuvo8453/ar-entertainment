<?php
$candidates = [
    'images/arentertainment-hero.webp',
    'images/hero-banner.webp',
    'images/video-production-house-in-bangladesh.webp',
    'images/libanzafilms-video-thumbnail.webp',
    'videos/ar-video.mp4',
    'videos/libanza-video.mp4'
];
foreach ($candidates as $c) {
    echo $c . ': ' . (file_exists(__DIR__ . '/../' . $c) ? 'EXISTS' : 'NOT FOUND') . "\n";
}
