<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

function test_service_img($slug) {
    $candidates = [
        "uploads/services/ar-{$slug}-hero.avif",
        "uploads/services/ar-{$slug}-workflow.avif",
    ];
    if ($slug === 'tv-commercial' || $slug === 'tv-commercial-ovc-production') {
        $candidates[] = 'uploads/services/ar-tv-commercial-production-hero.avif';
    }
    if ($slug === 'online-video-commercial') {
        $candidates[] = 'uploads/services/ar-online-video-commercial-hero.avif';
    }
    if ($slug === 'line-production-film-fixing' || $slug === 'support-for-international-production') {
        $candidates[] = 'uploads/services/ar-intl-production-support-hero.avif';
    }
    if ($slug === 'corporate-av') {
        $candidates[] = 'uploads/services/ar-corporate-av-production-hero.avif';
    }
    if ($slug === 'documentary') {
        $candidates[] = 'uploads/services/ar-documentary-production-hero.avif';
    }
    if ($slug === 'theme-song') {
        $candidates[] = 'uploads/services/ar-theme-song-production-hero.avif';
    }
    if ($slug === 'music-video') {
        $candidates[] = 'uploads/services/ar-music-video-production-hero.avif';
    }

    foreach ($candidates as $cand) {
        if (file_exists(ROOT_PATH . '/' . $cand)) {
            return $cand;
        }
    }
    return 'none';
}

$sv = db()->query("SELECT slug FROM services WHERE status = 'active' ORDER BY sort_order ASC, id ASC LIMIT 8")->fetchAll(PDO::FETCH_COLUMN);
foreach ($sv as $s) {
    echo "Slug: {$s} => " . test_service_img($s) . "\n";
}
