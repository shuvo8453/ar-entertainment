<?php
$paths = [
    'uploads/team/azizul-hoque-shiplu.avif',
    'uploads/team/ar-azizul-hoque-shiplu.avif',
    'images/team/azizul-hoque-shiplu.webp',
    'images/azizul-hoque-shiplu.webp'
];
foreach ($paths as $p) {
    echo $p . ': ' . (file_exists(__DIR__ . '/../' . $p) ? 'EXISTS' : 'NOT FOUND') . "\n";
}
