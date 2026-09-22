<?php
$html = file_get_contents(__DIR__ . '/test_index_utf8.html');
preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches);
$imgs = array_unique($matches[1]);
echo "Total unique images found: " . count($imgs) . "\n";
$missing = [];
foreach ($imgs as $img) {
    if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
        // Strip localhost prefix if present
        $local = str_replace('http://localhost/ar-entertainment/', '', $img);
        if ($local !== $img) {
            $path = __DIR__ . '/../' . ltrim($local, '/');
            if (!file_exists($path)) {
                $missing[] = "Local file missing: $img ($path)";
            }
        }
    } else {
        $path = __DIR__ . '/../' . ltrim($img, '/');
        if (!file_exists($path)) {
            $missing[] = "Relative file missing: $img";
        }
    }
}
if (empty($missing)) {
    echo "SUCCESS: 100% of all local images exist on disk!\n";
} else {
    echo "MISSING IMAGES (" . count($missing) . "):\n";
    foreach ($missing as $m) {
        echo " - $m\n";
    }
}
