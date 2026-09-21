<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$pdo = Database::pdo();
$stmt = $pdo->query("SELECT id, title, slug, sort_order, pricing_note, LENGTH(content) as content_len, LENGTH(faqs_json) as faqs_len FROM services ORDER BY sort_order ASC");
$rows = $stmt->fetchAll();

echo "========================================================\n";
echo "📊 SERVICES TABLE STATUS (" . count($rows) . " Records)\n";
echo "========================================================\n";
foreach ($rows as $r) {
    echo sprintf(
        "#%-2d [Order: %2d] %-55s (%-36s) | Body: %5d B | FAQs: %4d B\n",
        $r['id'],
        $r['sort_order'],
        $r['title'],
        $r['slug'],
        $r['content_len'],
        $r['faqs_len']
    );
}
echo "========================================================\n";
