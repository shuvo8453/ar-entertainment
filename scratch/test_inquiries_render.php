<?php
/**
 * Test HTML rendering for admin/inquiries/index.php and view.php
 */
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Azizul Hoque Shiplu';
$_SESSION['user_email'] = 'admin@arentertainment.bd';
$_SESSION['user_role'] = 'admin';

echo "1. Testing admin/inquiries/index.php rendering...\n";
$_GET = [];
ob_start();
require __DIR__ . '/../admin/inquiries/index.php';
$html = ob_get_clean();
assert(strlen($html) > 1000, "Rendered HTML should not be empty");
assert(str_contains($html, 'Leads &amp; Inquiries Inbox'), "HTML should contain page title");
assert(str_contains($html, 'stat-card'), "HTML should contain stat cards");
assert(str_contains($html, 'bulkInquiriesForm'), "HTML should contain bulk actions form");
echo "   ✅ index.php rendered successfully (" . strlen($html) . " bytes)\n\n";

echo "2. Testing admin/inquiries/view.php rendering for ID=1...\n";
$_GET = ['id' => 1];
ob_start();
require __DIR__ . '/../admin/inquiries/view.php';
$view_html = ob_get_clean();
assert(strlen($view_html) > 1000, "Rendered view HTML should not be empty");
assert(str_contains($view_html, 'Tanvir Ahmed'), "View HTML should contain lead name");
assert(str_contains($view_html, 'Apex Footwear'), "View HTML should contain extra data");
assert(str_contains($view_html, 'Reply by Email'), "View HTML should contain email action");
echo "   ✅ view.php rendered successfully (" . strlen($view_html) . " bytes)\n\n";

echo "=== 🎉 HTML RENDER TESTS PASSED! ===\n";
