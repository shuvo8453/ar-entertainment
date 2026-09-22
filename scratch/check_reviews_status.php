<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$rows = db()->query("SELECT id, client_name, status, rating, sort_order FROM reviews LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
