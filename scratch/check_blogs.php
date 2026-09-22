<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$total = db()->query("SELECT COUNT(*) FROM blogs WHERE status='published'")->fetchColumn();
$withThumb = db()->query("SELECT COUNT(*) FROM blogs WHERE status='published' AND thumbnail != '' AND thumbnail IS NOT NULL")->fetchColumn();
echo "Total published: {$total}, with thumbnail: {$withThumb}\n";

$noThumb = db()->query("SELECT id, title, slug FROM blogs WHERE status='published' AND (thumbnail = '' OR thumbnail IS NULL)")->fetchAll(PDO::FETCH_ASSOC);
echo "Without thumbnail (" . count($noThumb) . "):\n";
foreach ($noThumb as $nt) {
    echo "ID {$nt['id']}: {$nt['slug']}\n";
}
