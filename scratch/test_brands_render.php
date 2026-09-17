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
require __DIR__ . '/../admin/brands/index.php';
$html_index = ob_get_clean();
echo "admin/brands/index.php rendered: " . strlen($html_index) . " bytes\n";
echo "Contains 'Brands, Clients & Awards': " . (strpos($html_index, 'Brands, Clients & Awards') !== false ? 'YES' : 'NO') . "\n";
echo "Contains 'PRAN-RFL Group': " . (strpos($html_index, 'PRAN-RFL Group') !== false ? 'YES' : 'NO') . "\n";

// Test rendering create.php
ob_start();
require __DIR__ . '/../admin/brands/create.php';
$html_create = ob_get_clean();
echo "admin/brands/create.php rendered: " . strlen($html_create) . " bytes\n";
echo "Contains 'Add Brand / Client': " . (strpos($html_create, 'Add Brand / Client') !== false ? 'YES' : 'NO') . "\n";

// Test rendering edit.php
$_GET['id'] = 1;
ob_start();
require __DIR__ . '/../admin/brands/edit.php';
$html_edit = ob_get_clean();
echo "admin/brands/edit.php rendered: " . strlen($html_edit) . " bytes\n";
echo "Contains 'Edit Brand / Client': " . (strpos($html_edit, 'Edit Brand / Client') !== false ? 'YES' : 'NO') . "\n";

echo "=== All Admin Brands Pages Rendered Successfully ===\n";
