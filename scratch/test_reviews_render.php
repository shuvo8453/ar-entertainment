<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

// Simulate logged in session
$_SESSION['user_id']   = 1;
$_SESSION['user_name'] = 'Super Admin';
$_SESSION['user_email']= 'admin@arentertainment.bd';
$_SESSION['user_role'] = 'super_admin';

// Test rendering index.php
ob_start();
require __DIR__ . '/../admin/reviews/index.php';
$html_index = ob_get_clean();
echo "admin/reviews/index.php rendered: " . strlen($html_index) . " bytes\n";
echo "Contains 'Client Reviews': " . (strpos($html_index, 'Client Reviews') !== false ? 'YES' : 'NO') . "\n";
echo "Contains 'Ehtesham Ahmed': " . (strpos($html_index, 'Ehtesham Ahmed') !== false ? 'YES' : 'NO') . "\n";

// Test rendering create.php
ob_start();
require __DIR__ . '/../admin/reviews/create.php';
$html_create = ob_get_clean();
echo "admin/reviews/create.php rendered: " . strlen($html_create) . " bytes\n";
echo "Contains 'Add Client Review': " . (strpos($html_create, 'Add Client Review') !== false ? 'YES' : 'NO') . "\n";

// Test rendering edit.php
$_GET['id'] = 1;
ob_start();
require __DIR__ . '/../admin/reviews/edit.php';
$html_edit = ob_get_clean();
echo "admin/reviews/edit.php rendered: " . strlen($html_edit) . " bytes\n";
echo "Contains 'Edit Client Review': " . (strpos($html_edit, 'Edit Client Review') !== false ? 'YES' : 'NO') . "\n";

echo "=== All Admin Reviews Pages Rendered Successfully ===\n";
