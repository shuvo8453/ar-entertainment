<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

db()->query("UPDATE portfolio SET is_featured = 0, status = 'inactive' WHERE id IN (1, 2)");
$count = db()->query("SELECT COUNT(*) FROM portfolio WHERE status = 'active' AND is_featured = 1")->fetchColumn();
echo "Active featured portfolio count: {$count}\n";
