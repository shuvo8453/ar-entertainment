<?php
header('Content-Type: application/json');
$gd_loaded = extension_loaded('gd');
$info = $gd_loaded ? gd_info() : [];
echo json_encode([
    'gd_loaded' => $gd_loaded,
    'imagewebp' => function_exists('imagewebp'),
    'imageavif' => function_exists('imageavif'),
    'gd_info' => $info
], JSON_PRETTY_PRINT);
