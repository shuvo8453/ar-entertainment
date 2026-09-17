<?php
echo "GD loaded: " . (extension_loaded('gd') ? 'Yes' : 'No') . "\n";
echo "imageavif function exists: " . (function_exists('imageavif') ? 'Yes' : 'No') . "\n";
echo "imagewebp function exists: " . (function_exists('imagewebp') ? 'Yes' : 'No') . "\n";
if (extension_loaded('gd')) {
    $info = gd_info();
    echo "AVIF Support in GD: " . (!empty($info['AVIF Support']) ? 'Yes' : 'No') . "\n";
    echo "WebP Support in GD: " . (!empty($info['WebP Support']) ? 'Yes' : 'No') . "\n";
}
echo "Imagick loaded: " . (extension_loaded('imagick') ? 'Yes' : 'No') . "\n";
