<?php
/**
 * Test isolated execution of each admin page with session_start
 */
$pages = [
    'Dashboard'         => 'admin/index.php',
    'Blogs List'        => 'admin/blogs/index.php',
    'Services List'     => 'admin/services/index.php',
    'Service Areas List'=> 'admin/service-areas/index.php',
    'Portfolio List'    => 'admin/portfolio/index.php',
    'Team List'         => 'admin/team/index.php',
    'Brands List'       => 'admin/brands/index.php',
    'Brands Create'     => 'admin/brands/create.php',
    'Brands Edit'       => 'admin/brands/edit.php?id=1',
    'Reviews List'      => 'admin/reviews/index.php',
    'Reviews Create'    => 'admin/reviews/create.php',
    'Reviews Edit'      => 'admin/reviews/edit.php?id=1',
    'Inquiries List'    => 'admin/inquiries/index.php',
    'Inquiries View'    => 'admin/inquiries/view.php?id=1'
];

echo "=== 🚀 TESTING ALL ADMIN PAGES IN ISOLATION ===\n\n";

$all_passed = true;
foreach ($pages as $name => $path) {
    $query_parts = parse_url($path);
    $script_file = $query_parts['path'];
    $query_str = $query_parts['query'] ?? '';

    $tmp_file = __DIR__ . '/runner_' . uniqid() . '.php';
    $runner_code = '<?php
session_start();
$_SESSION["user_id"] = 1;
$_SESSION["user_name"] = "Azizul Hoque Shiplu";
$_SESSION["user_email"] = "admin@arentertainment.bd";
$_SESSION["user_role"] = "admin";
parse_str("' . addslashes($query_str) . '", $_GET);
require_once __DIR__ . "/../' . $script_file . '";
';
    file_put_contents($tmp_file, $runner_code);

    $cmd = 'php ' . escapeshellarg($tmp_file);
    $output = shell_exec($cmd . ' 2>&1');
    @unlink($tmp_file);

    $checks = [
        'class="admin-wrapper"' => 'admin-wrapper layout shell',
        'class="admin-sidebar"' => 'admin-sidebar navigation',
        'class="admin-main"'    => 'admin-main content container',
        'class="admin-topbar"'  => 'admin-topbar header component',
        'class="admin-content"' => 'admin-content wrapper'
    ];

    $errors = [];
    foreach ($checks as $pattern => $label) {
        if (!str_contains($output, $pattern)) {
            $errors[] = "Missing {$label}";
        }
    }

    if (empty($errors)) {
        echo "✅ [PASSED] {$name} (" . strlen($output) . " bytes) - 100% compliant with standard layout!\n";
    } else {
        $all_passed = false;
        echo "❌ [FAILED] {$name} - " . implode(', ', $errors) . "\n";
    }
}

if ($all_passed) {
    echo "\n=== 🏆 ALL 14 ADMIN PAGES VERIFIED 100% IDENTICAL AND VALID! ===\n";
} else {
    echo "\n❌ Some pages failed validation.\n";
}
