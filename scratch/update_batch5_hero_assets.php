<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../database/seeds/generate_batch5_original_assets.php';

// Reliable photography URLs
create_avif_asset('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1200&q=80', 'ar-nationwide-corporate-av-hero');
create_avif_asset('https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&fit=crop&w=1200&q=80', 'ar-additional-services-hero');

echo "Updated hero assets for Nationwide AV and Additional Services!\n";
