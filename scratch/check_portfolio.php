<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';
$items = db()->query("SELECT id, title, slug, thumbnail, video_url, is_featured FROM portfolio")->fetchAll(PDO::FETCH_ASSOC);
foreach ($items as $item) {
    echo "ID {$item['id']} | Slug: {$item['slug']} | Featured: {$item['is_featured']} | Thumb: {$item['thumbnail']} | URL: {$item['video_url']}\n";
}
