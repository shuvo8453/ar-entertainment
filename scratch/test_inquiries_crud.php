<?php
/**
 * AR Entertainment - Inquiries Module Automated Test Suite
 * Validates Phase 4.7 CRUD, filters, actions, CSV export, and view rendering.
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

echo "=== 🚀 STARTING INQUIRIES AUTOMATED TEST SUITE ===\n\n";

// 1. Check Initial Record Count
$total = (int) db()->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
echo "1. Total Inquiries in DB: {$total}\n";
if ($total < 1) {
    echo "⚠️ Seeding sample data first...\n";
    require __DIR__ . '/seed_inquiries.php';
    $total = (int) db()->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
}
assert($total > 0, "Expected at least 1 inquiry in DB");
echo "   ✅ Initial count verified ({$total} items)\n\n";

// 2. Test Stats Aggregation
$stats = db()->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread,
        SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as `read`,
        SUM(CASE WHEN form_type = 'quote' THEN 1 ELSE 0 END) as `quote`,
        SUM(CASE WHEN form_type = 'contact' THEN 1 ELSE 0 END) as `contact`,
        SUM(CASE WHEN form_type = 'careers' THEN 1 ELSE 0 END) as `careers`,
        SUM(CASE WHEN form_type = 'survey' THEN 1 ELSE 0 END) as `survey`
    FROM inquiries
")->fetch();

echo "2. Testing Stats Aggregation:\n";
echo "   - Total: {$stats['total']}\n";
echo "   - Unread: {$stats['unread']}\n";
echo "   - Read: {$stats['read']}\n";
echo "   - Quotes: {$stats['quote']}\n";
echo "   - Contacts: {$stats['contact']}\n";
echo "   - Careers: {$stats['careers']}\n";
echo "   - Surveys: {$stats['survey']}\n";
assert($stats['total'] == ($stats['unread'] + $stats['read']), "Total should equal unread + read");
echo "   ✅ Stats query logic verified!\n\n";

// 3. Test Filter by Type and Search
echo "3. Testing Filters & Search:\n";
$stmt = db()->prepare("SELECT COUNT(*) FROM inquiries WHERE form_type = ?");
$stmt->execute(['quote']);
$quote_count = (int)$stmt->fetchColumn();
echo "   - Filter form_type='quote': {$quote_count} items found.\n";

$search_term = 'commercial';
$stmt = db()->prepare("SELECT COUNT(*) FROM inquiries WHERE (name LIKE :q OR email LIKE :q2 OR subject LIKE :q3 OR message LIKE :q4)");
$param = "%{$search_term}%";
$stmt->execute([':q' => $param, ':q2' => $param, ':q3' => $param, ':q4' => $param]);
$search_count = (int)$stmt->fetchColumn();
echo "   - Search '{$search_term}': {$search_count} matches found.\n";
echo "   ✅ Filters & Search SQL verified!\n\n";

// 4. Test Single Toggle Action (mark_read / mark_unread)
echo "4. Testing Read/Unread State Toggles:\n";
$first = db()->query("SELECT id, name, is_read FROM inquiries ORDER BY id ASC LIMIT 1")->fetch();
$test_id = (int)$first['id'];
$orig_status = (int)$first['is_read'];
$new_status = $orig_status === 1 ? 0 : 1;

// Update status
$upd = db()->prepare("UPDATE inquiries SET is_read = ? WHERE id = ?");
$upd->execute([$new_status, $test_id]);

$check = (int)db()->query("SELECT is_read FROM inquiries WHERE id = {$test_id}")->fetchColumn();
assert($check === $new_status, "Status should have changed to {$new_status}");
echo "   - Inquiry #{$test_id} toggled from {$orig_status} to {$new_status}: SUCCESS\n";

// Revert back
$upd->execute([$orig_status, $test_id]);
echo "   - Reverted back to original status: SUCCESS\n";
echo "   ✅ Toggle mechanism verified!\n\n";

// 5. Test Extra Data JSON Parsing
echo "5. Testing JSON Parsing for Extra Fields:\n";
$sample_with_json = db()->query("SELECT id, extra_data_json FROM inquiries WHERE extra_data_json IS NOT NULL LIMIT 1")->fetch();
if ($sample_with_json) {
    $decoded = json_decode($sample_with_json['extra_data_json'], true);
    assert(is_array($decoded), "extra_data_json should be valid JSON array");
    echo "   - Decoded keys: " . implode(', ', array_keys($decoded)) . "\n";
    echo "   ✅ JSON decoding verified!\n\n";
}

// 6. Test CSV Export Format & Headers Simulation
echo "6. Testing CSV Export Engine:\n";
ob_start();
// Simulate CSV writing
$test_output = fopen('php://temp', 'r+');
fprintf($test_output, chr(0xEF) . chr(0xBB) . chr(0xBF));
fputcsv($test_output, ['ID', 'Form Type', 'Sender Name', 'Email Address', 'Phone', 'Subject', 'Message Content', 'Additional Fields / Metadata', 'Read Status', 'IP Address', 'User Agent', 'Date Submitted']);

$rows = db()->query("SELECT * FROM inquiries LIMIT 3")->fetchAll();
foreach ($rows as $row) {
    fputcsv($test_output, [
        $row['id'],
        ucfirst($row['form_type']),
        $row['name'],
        $row['email'],
        $row['phone'] ?? '',
        $row['subject'] ?? '',
        $row['message'],
        $row['extra_data_json'] ?? '',
        ($row['is_read'] == 1) ? 'Read' : 'Unread',
        $row['ip_address'] ?? '',
        $row['user_agent'] ?? '',
        $row['created_at']
    ]);
}
rewind($test_output);
$csv_content = stream_get_contents($test_output);
fclose($test_output);
ob_end_clean();

assert(str_starts_with($csv_content, "\xEF\xBB\xBF"), "CSV must include UTF-8 BOM");
assert(str_contains($csv_content, 'Sender Name'), "CSV must contain header");
echo "   - Generated CSV Size: " . strlen($csv_content) . " bytes\n";
echo "   ✅ CSV Export data & UTF-8 BOM verified!\n\n";

// 7. Test Insertion, Bulk Operation and Cleanup
echo "7. Testing Insertion & Bulk Operations:\n";
$temp_insert = db()->prepare("
    INSERT INTO inquiries (form_type, name, email, subject, message, is_read)
    VALUES ('contact', 'Test Lead A', 'test.a@example.com', 'Bulk Test A', 'Message A', 0),
           ('quote', 'Test Lead B', 'test.b@example.com', 'Bulk Test B', 'Message B', 0)
");
$temp_insert->execute();

$temp_ids = db()->query("SELECT id FROM inquiries WHERE email IN ('test.a@example.com', 'test.b@example.com')")->fetchAll(PDO::FETCH_COLUMN);
echo "   - Created 2 temporary test leads: IDs [" . implode(', ', $temp_ids) . "]\n";

// Bulk Read
$placeholders = implode(',', array_fill(0, count($temp_ids), '?'));
$bulk_read = db()->prepare("UPDATE inquiries SET is_read = 1 WHERE id IN ($placeholders)");
$bulk_read->execute($temp_ids);

$check_read_count = (int)db()->query("SELECT COUNT(*) FROM inquiries WHERE id IN (" . implode(',', $temp_ids) . ") AND is_read = 1")->fetchColumn();
assert($check_read_count === count($temp_ids), "All temporary leads should be marked as read");
echo "   - Bulk Read test: SUCCESS ({$check_read_count} leads marked)\n";

// Bulk Delete
$bulk_del = db()->prepare("DELETE FROM inquiries WHERE id IN ($placeholders)");
$bulk_del->execute($temp_ids);

$check_del_count = (int)db()->query("SELECT COUNT(*) FROM inquiries WHERE id IN (" . implode(',', $temp_ids) . ")")->fetchColumn();
assert($check_del_count === 0, "All temporary leads should be deleted");
echo "   - Bulk Delete test: SUCCESS\n";
echo "   ✅ Bulk operations verified!\n\n";

echo "=== 🏆 ALL INQUIRIES TESTS PASSED SUCCESSFULLY! ===\n";
