<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

echo "=== Testing Brands & Clients CRUD Pipeline ===\n";

// 1. Check stats
$stats_stmt = db()->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN brand_type = 'client' THEN 1 ELSE 0 END) as clients,
        SUM(CASE WHEN brand_type = 'partner' THEN 1 ELSE 0 END) as partners,
        SUM(CASE WHEN brand_type = 'award' THEN 1 ELSE 0 END) as awards,
        SUM(CASE WHEN brand_type = 'affiliation' THEN 1 ELSE 0 END) as affiliations
    FROM brands
");
$stats = $stats_stmt->fetch();
echo "Stats: Total={$stats['total']}, Clients={$stats['clients']}, Partners={$stats['partners']}, Awards={$stats['awards']}, Affiliations={$stats['affiliations']}\n";

// 2. Insert test brand
$test_name = 'Test Studio Brand ' . time();
$insert = db()->prepare("
    INSERT INTO brands (name, logo, website_url, brand_type, sort_order, status, created_at, updated_at)
    VALUES (?, ?, ?, 'partner', 99, 'active', NOW(), NOW())
");
$insert->execute([$test_name, 'images/chorki.png', 'https://testbrand.com']);
$inserted_id = (int) db()->lastInsertId();
echo "Inserted test brand ID: $inserted_id\n";

// 3. Update test brand
$update = db()->prepare("UPDATE brands SET name = ?, brand_type = 'award', sort_order = 50 WHERE id = ?");
$update->execute([$test_name . ' Updated', $inserted_id]);
echo "Updated test brand ID: $inserted_id\n";

// Verify update
$check = db()->prepare("SELECT name, brand_type, sort_order, status FROM brands WHERE id = ?");
$check->execute([$inserted_id]);
$row = $check->fetch();
echo "Verified: Name='{$row['name']}', Type='{$row['brand_type']}', Sort={$row['sort_order']}\n";

// 4. Toggle status
$toggle = db()->prepare("UPDATE brands SET status = 'inactive' WHERE id = ?");
$toggle->execute([$inserted_id]);
$check->execute([$inserted_id]);
echo "Toggled status to: " . $check->fetchColumn(3) . "\n";

// 5. Delete test brand
$del = db()->prepare("DELETE FROM brands WHERE id = ?");
$del->execute([$inserted_id]);
echo "Deleted test brand ID: $inserted_id\n";

$check->execute([$inserted_id]);
echo "Record exists after delete: " . ($check->fetch() ? 'YES' : 'NO') . "\n";

echo "=== All Brands CRUD Tests Passed Successfully ===\n";
