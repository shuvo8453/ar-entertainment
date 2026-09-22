<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';
db()->query("UPDATE blogs SET thumbnail='uploads/blog/ar-corporate-video-production-in-bangladesh-the-ultimate-guide.avif' WHERE id=1");
db()->query("UPDATE blogs SET thumbnail='uploads/blog/ar-international-production-support-bangladesh-guide.avif' WHERE id=2");
$cnt = db()->query("SELECT COUNT(*) FROM blogs WHERE status='published' AND (thumbnail = '' OR thumbnail IS NULL)")->fetchColumn();
echo "Remaining published blogs without thumbnail: {$cnt}\n";
