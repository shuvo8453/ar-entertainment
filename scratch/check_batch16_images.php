<?php
$candidates = [
    'film-fixer-cost-bangladesh.webp',
    'film-fixer-cost-bangladesh-og.webp',
    'fmcg-one-shoot-multiple-ovc-ads-bangladesh.webp',
    'fmcg-multi-ovc-shoot-og.webp',
    'how-to-brief-tvc-production-company-bangladesh.webp',
    'how-to-brief-tvc-production-company-bangladesh-og.webp',
    'how-to-hire-film-fixer-bangladesh-og.webp',
    'film-fixer-bangladesh.webp',
    'film-fixer-bangladesh-guide-og.webp',
    'international-shoot-in-bangladesh-2026-guide.webp',
    'international-shoot-in-bangladesh-og.webp',
    'international-production-support-bangladesh.webp'
];

foreach ($candidates as $c) {
    $path = __DIR__ . '/../images/' . $c;
    echo $c . ': ' . (file_exists($path) ? filesize($path) . ' bytes' : 'MISSING') . PHP_EOL;
}
