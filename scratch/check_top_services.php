<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$sv = db()->query("SELECT id, title, slug, icon, pricing_note, short_summary FROM services WHERE status = 'active' ORDER BY sort_order ASC, id ASC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
foreach ($sv as $s) {
    echo "ID {$s['id']} | {$s['title']} | Slug: {$s['slug']} | Icon: {$s['icon']} | Price: {$s['pricing_note']}\n";
}
