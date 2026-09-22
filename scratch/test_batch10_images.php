<?php
$files = [
    'creative-testing-fmcg-facebook-ads-bangladesh.webp',
    'scale-winners-rotate-creatives-fmcg.webp',
    'film-fixer-bangladesh.webp',
    'film-fixer-on-ground-support.webp',
    'film-crew-bangladesh.webp',
    'facebook-vs-youtube-ovc.webp',
    'facebook-youtube-viewing-behaviour-bangladesh.webp',
    'film-fixer-services.webp',
    'local-crew-equipment-bangladesh.webp',
    'filming-permits-bangladesh.webp',
    'filming-permit-process-bangladesh-international-crew.webp',
    'location-permits-police-support-filming-bangladesh.webp'
];
foreach ($files as $f) {
    $p = __DIR__ . '/../images/' . $f;
    echo $f . ': ' . (file_exists($p) ? filesize($p) . ' bytes' : 'NOT FOUND') . PHP_EOL;
}
