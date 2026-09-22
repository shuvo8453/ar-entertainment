<?php
require_once __DIR__ . '/../config/helpers.php';

$seeded = db()->query('SELECT slug FROM blogs')->fetchAll(PDO::FETCH_COLUMN);
$files = array_filter(scandir(__DIR__ . '/../blog'), function($f) {
    return substr($f, -5) === '.html';
});

$unseeded = [];
foreach ($files as $f) {
    $slug = substr($f, 0, -5);
    if (!in_array($slug, $seeded, true)) {
        $unseeded[] = $slug;
    }
}

echo "Total HTML files in blog/: " . count($files) . PHP_EOL;
echo "Total Seeded in blogs table: " . count($seeded) . PHP_EOL;
echo "Remaining unseeded: " . count($unseeded) . PHP_EOL;
echo "Next batches preview:" . PHP_EOL;
print_r(array_chunk($unseeded, 5));
