<?php
$slugs = [
    'ar-ai-content-disclosure-best-practices.avif',
    'ar-ai-image-generation-guide-and-best-tools.avif',
    'ar-ai-video-dubbing-bangla-english-arabic.avif',
    'ar-ai-video-for-government-training.avif',
    'ar-ai-video-for-performance-marketing.avif',
    'ar-ai-video-localisation-and-dubbing.avif',
    'ar-ai-video-production-cost-comparison-bangladesh-2026.avif',
    'ar-ai-video-production-ngos-development-organisations.avif',
    'ar-brand-film-production-textile-exporters-bangladesh.avif',
    'ar-how-many-ovc-versions-does-a-campaign-really-need.avif'
];
foreach($slugs as $s) {
    $path = __DIR__ . '/../uploads/blog/' . $s;
    echo $s . ' -> ' . (file_exists($path) ? filesize($path) . ' bytes' : 'NOT FOUND') . PHP_EOL;
}
