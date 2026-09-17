<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

echo "GD extension loaded: " . (extension_loaded('gd') ? 'YES' : 'NO') . "\n";
echo "imagewebp exists: " . (function_exists('imagewebp') ? 'YES' : 'NO') . "\n";
echo "imageavif exists: " . (function_exists('imageavif') ? 'YES' : 'NO') . "\n";
