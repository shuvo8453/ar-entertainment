<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$stmt = db()->query('DESCRIBE brands');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

$stmt2 = db()->query('SELECT * FROM brands LIMIT 10');
print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
