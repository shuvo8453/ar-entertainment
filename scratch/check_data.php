<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

echo "--- PORTFOLIO SAMPLE ---\n";
$pf = db()->query("SELECT id, title, slug, category_name, client_name, thumbnail, video_url, is_featured FROM portfolio LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
print_r($pf);

echo "--- SERVICES SAMPLE ---\n";
$sv = db()->query("SELECT id, title, slug, icon, pricing_note, status FROM services LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
print_r($sv);

echo "--- BRANDS SAMPLE ---\n";
$br = db()->query("SELECT id, name, logo, website_url, brand_type FROM brands LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
print_r($br);

echo "--- REVIEWS SAMPLE ---\n";
$rv = db()->query("SELECT id, client_name, client_company, rating, source, review_text FROM reviews LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
print_r($rv);

echo "--- BLOGS SAMPLE ---\n";
$bl = db()->query("SELECT id, title, slug, thumbnail, published_at FROM blogs WHERE status='published' ORDER BY published_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
print_r($bl);
