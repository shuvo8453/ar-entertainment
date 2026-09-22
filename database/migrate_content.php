<?php
/**
 * AR Entertainment - Master Database Migration & Seeding Runner
 * 
 * Runs all seeders sequentially in a single command.
 * CLI: php database/migrate_content.php
 * Web: http://localhost/ar-entertainment/database/migrate_content.php
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$is_cli = (php_sapi_name() === 'cli' || defined('STDIN'));

if (!$is_cli) {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>AR Entertainment - Master Seeder</title>';
    echo '<style>body{max-width:900px;margin:30px auto;padding:0 20px;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;line-height:1.5;background:#0f172a;color:#e2e8f0;}';
    echo '.card{background:#1e293b;border-radius:10px;padding:30px;box-shadow:0 10px 25px rgba(0,0,0,0.5);border:1px solid #334155;}';
    echo 'h2{color:#38bdf8;margin-top:0;} pre{background:#090d16;padding:15px;border-radius:6px;overflow-x:auto;color:#a5f3fc;font-size:13px;}</style></head><body><div class="card">';
    echo '<h2>🎬 AR Entertainment — Master Database Seeder</h2><hr style="border:0;border-top:1px solid #334155;margin-bottom:20px;"><pre>';
}

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT — MASTER SEEDER RUNNER\n";
echo "Database: " . DB_NAME . " (" . DB_HOST . ":" . DB_PORT . ")\n";
echo "========================================================\n\n";

$seed_scripts = [
    'Setup & Base Schema'     => __DIR__ . '/setup.php',
    'Brand Identity & Media'  => __DIR__ . '/seeds/seed_brand_identity.php',
    'Services Batch 1 (1–8)'  => __DIR__ . '/seeds/seed_services_batch1.php',
    'Services Batch 2 (9–16)' => __DIR__ . '/seeds/seed_services_batch2.php',
    'Services Batch 3 (17–24)'=> __DIR__ . '/seeds/seed_services_batch3.php',
    'Services Batch 4 (25–32)'=> __DIR__ . '/seeds/seed_services_batch4.php',
    'Services Batch 5 (33–40)'=> __DIR__ . '/seeds/seed_services_batch5.php',
    'Districts Batch 1 (1–10)'=> __DIR__ . '/seeds/seed_service_areas_batch1.php',
    'Districts Batch 2 (11–21)'=> __DIR__ . '/seeds/seed_service_areas_batch2.php',
    'Districts Batch 3 (22–32)'=> __DIR__ . '/seeds/seed_service_areas_batch3.php',
    'Districts Batch 4 (33–40)'=> __DIR__ . '/seeds/seed_service_areas_batch4.php',
    'Districts Batch 5 (41–48)'=> __DIR__ . '/seeds/seed_service_areas_batch5.php',
    'Districts Batch 6 (49–58)'=> __DIR__ . '/seeds/seed_service_areas_batch6.php',
    'Districts Batch 7 (59–64)'=> __DIR__ . '/seeds/seed_service_areas_batch7.php',
    'Blog Articles Batch 1 (1–5)' => __DIR__ . '/seeds/seed_blogs_batch1.php',
    'Blog Articles Batch 2 (6–10)' => __DIR__ . '/seeds/seed_blogs_batch2.php',
    'Blog Articles Batch 3 (11–15)' => __DIR__ . '/seeds/seed_blogs_batch3.php',
    'Blog Articles Batch 4 (16–20)' => __DIR__ . '/seeds/seed_blogs_batch4.php',
    'Blog Articles Batch 5 (21–25)' => __DIR__ . '/seeds/seed_blogs_batch5.php',
    'Blog Articles Batch 6 (26–30)' => __DIR__ . '/seeds/seed_blogs_batch6.php',
    'Blog Articles Batch 7 (31–35)' => __DIR__ . '/seeds/seed_blogs_batch7.php',
    'Blog Articles Batch 8 (36–40)' => __DIR__ . '/seeds/seed_blogs_batch8.php',
    'Blog Articles Batch 9 (41–45)' => __DIR__ . '/seeds/seed_blogs_batch9.php',
    'Blog Articles Batch 10 (46–50)' => __DIR__ . '/seeds/seed_blogs_batch10.php',
    'Blog Articles Batch 11 (51–55)' => __DIR__ . '/seeds/seed_blogs_batch11.php',
    'Blog Articles Batch 12 (56–60)' => __DIR__ . '/seeds/seed_blogs_batch12.php',
    'Blog Articles Batch 13 (61–65)' => __DIR__ . '/seeds/seed_blogs_batch13.php',
    'Blog Articles Batch 14 (66–70)' => __DIR__ . '/seeds/seed_blogs_batch14.php',
    'Sample Inquiries & Leads' => __DIR__ . '/../scratch/seed_inquiries.php',
];

$success_count = 0;
$start_time = microtime(true);

foreach ($seed_scripts as $label => $file_path) {
    if (!file_exists($file_path)) {
        echo "⚠️ Skipping [{$label}]: File not found at {$file_path}\n";
        continue;
    }

    echo "\n>>> Running: {$label} ...\n";
    
    // Execute script via output buffering isolation
    try {
        ob_start();
        include $file_path;
        $output = ob_get_clean();
        echo $output . "\n";
        $success_count++;
    } catch (Throwable $e) {
        if (ob_get_level() > 0) ob_end_clean();
        echo "❌ Error in [{$label}]: " . $e->getMessage() . "\n";
    }
}

$elapsed = round(microtime(true) - $start_time, 2);

echo "\n========================================================\n";
echo "🎉 ALL SEEDERS FINISHED in {$elapsed}s! ({$success_count}/" . count($seed_scripts) . " completed)\n";
echo "========================================================\n";

if (!$is_cli) {
    echo '</pre><div style="margin-top:20px;padding:15px;background:#0369a1;color:#fff;border-radius:6px;">';
    echo '<strong>Seeding Complete!</strong> You can now login to <a href="' . ADMIN_URL . '/login.php" style="color:#fef08a;text-decoration:underline;">Admin Panel</a> with <code>admin@arentertainment.bd</code> / <code>Admin@AREnt2026!</code>';
    echo '</div></div></body></html>';
}
