<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$blogs = db()->query("SELECT id, slug, thumbnail FROM blogs")->fetchAll(PDO::FETCH_ASSOC);
$updated = 0;
foreach ($blogs as $b) {
    $expectedPath = 'uploads/blog/ar-' . $b['slug'] . '.avif';
    if (file_exists(__DIR__ . '/../' . $expectedPath)) {
        if ($b['thumbnail'] !== $expectedPath) {
            $stmt = db()->prepare("UPDATE blogs SET thumbnail = :thumb WHERE id = :id");
            $stmt->execute([':thumb' => $expectedPath, ':id' => $b['id']]);
            $updated++;
        }
    }
}
echo "Updated {$updated} blogs with their matching avif thumbnail!\n";
$remaining = db()->query("SELECT COUNT(*) FROM blogs WHERE thumbnail = '' OR thumbnail IS NULL")->fetchColumn();
echo "Remaining blogs without thumbnail: {$remaining}\n";
