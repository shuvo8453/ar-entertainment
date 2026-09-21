<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../database/seeds/generate_batch3_original_assets.php';

$rel = create_avif_asset('https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?auto=format&fit=crop&w=1200&q=80', 'ar-intl-production-support-hero');
$size = filesize(__DIR__ . '/../uploads/services/ar-intl-production-support-hero.avif');
echo "Updated ar-intl-production-support-hero.avif -> Size: {$size} bytes\n";
