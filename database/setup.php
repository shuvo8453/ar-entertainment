<?php

/**
 * AR Entertainment - Automated Database Setup & Migration Runner
 * 
 * Runs schema.sql and seed.sql to initialize or update the database tables.
 * Can be executed via CLI (php database/setup.php) or web browser.
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/helpers.php';

$is_cli = (php_sapi_name() === 'cli' || defined('STDIN'));

function output_msg(string $msg, string $type = 'info'): void
{
    global $is_cli;
    if ($is_cli) {
        $prefix = match ($type) {
            'success' => "\033[32m[SUCCESS]\033[0m ",
            'error'   => "\033[31m[ERROR]\033[0m ",
            'warning' => "\033[33m[WARNING]\033[0m ",
            default   => "\033[36m[INFO]\033[0m "
        };
        echo $prefix . $msg . PHP_EOL;
    } else {
        $color = match ($type) {
            'success' => '#155724;background:#d4edda;border-color:#c3e6cb;',
            'error'   => '#721c24;background:#f8d7da;border-color:#f5c6cb;',
            'warning' => '#856404;background:#fff3cd;border-color:#ffeeba;',
            default   => '#0c5460;background:#d1ecf1;border-color:#bee5eb;'
        };
        echo '<div style="padding:10px 15px;margin:5px 0;border:1px solid;border-radius:4px;font-family:sans-serif;color:' . $color . '">' . htmlspecialchars($msg) . '</div>';
    }
}

if (!$is_cli) {
    echo '<!DOCTYPE html><html><head><title>AR Entertainment - Database Setup</title>';
    echo '<style>body{max-width:800px;margin:40px auto;padding:0 20px;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;line-height:1.5;background:#f8f9fa;}';
    echo '.card{background:#fff;border-radius:8px;padding:30px;box-shadow:0 4px 12px rgba(0,0,0,0.08);}</style></head><body><div class="card">';
    echo '<h2>🎬 AR Entertainment — Database Setup</h2><hr style="border:0;border-top:1px solid #eee;margin-bottom:20px;">';
}

output_msg("Starting database setup for AR Entertainment on database [" . DB_NAME . "]...");

try {
    $pdo = db();
    output_msg("Connected to MySQL server (" . DB_HOST . ":" . DB_PORT . ") successfully.", 'success');

    // 1. Run schema.sql
    $schema_file = __DIR__ . '/schema.sql';
    if (!file_exists($schema_file)) {
        throw new Exception("Schema file not found at " . $schema_file);
    }
    output_msg("Executing schema.sql...");
    $schema_sql = file_get_contents($schema_file);
    $pdo->exec($schema_sql);
    output_msg("Database schema created / updated successfully (11 tables ready).", 'success');

    // 2. Run seed.sql
    $seed_file = __DIR__ . '/seed.sql';
    if (!file_exists($seed_file)) {
        throw new Exception("Seed file not found at " . $seed_file);
    }
    output_msg("Executing seed.sql...");
    $seed_sql = file_get_contents($seed_file);
    $pdo->exec($seed_sql);
    output_msg("Seed data populated successfully (Default Admin & Site Settings ready).", 'success');

    // 3. Verify Table Counts
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    output_msg("Found " . count($tables) . " tables in database [" . DB_NAME . "]: " . implode(', ', $tables), 'info');

    // 4. Verify Admin Account
    $admin_stmt = $pdo->query("SELECT id, name, email, role FROM users WHERE email = 'admin@arentertainment.bd'");
    $admin = $admin_stmt->fetch();
    if ($admin) {
        output_msg("Superadmin verified: " . $admin['email'] . " (Role: " . $admin['role'] . ")", 'success');
    }

    // 5. Verify Settings
    $settings_count = $pdo->query("SELECT COUNT(*) FROM site_settings")->fetchColumn();
    output_msg("Loaded {$settings_count} global site settings for AR Entertainment.", 'success');

    output_msg("🎉 Phase 1 Database Setup Complete! Ready for Phase 2 (Frontend Templating & Routing).", 'success');

    if (!$is_cli) {
        echo '<div style="margin-top:20px;padding:15px;background:#e8f4fd;border-left:4px solid #007bff;border-radius:4px;">';
        echo '<strong>Admin Credentials:</strong><br>';
        echo 'Email: <code>admin@arentertainment.bd</code><br>';
        echo 'Password: <code>Admin@AREnt2026!</code>';
        echo '</div>';
    }
} catch (Exception $e) {
    output_msg("Setup Failed: " . $e->getMessage(), 'error');
}

if (!$is_cli) {
    echo '</div></body></html>';
}
