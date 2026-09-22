<?php
/**
 * AR Entertainment - 100% Original Asset Generator & Database Migrator for Blog Batch 5.4.13 (Articles 61–65)
 * 
 * Generates and embeds 5 brand-new, copyright-safe, clean cinema-grade featured visual assets:
 * 61. music-video-production-bangladesh-cost: Music Video Cinematography & Cost Breakdown Visual
 * 62. outsource-ai-video-production-bangladesh: Remote AI Video Production Outsourcing Visual
 * 63. ovc-vs-tvc-in-bangladesh: OVC vs TVC Performance & ROI Comparison Visual
 * 64. ai-content-and-eu-ai-act: EU AI Act Risk Categories & Compliance Visual
 * 65. ai-content-and-uk-advertising-standards: UK ASA & CAP Code Advertising Compliance Visual
 * 
 * Features:
 * - 100% Clean AR Entertainment Branding & 0% Legacy Logos
 * - Converted to high-quality .avif in uploads/blog/
 * - Updates MySQL blogs table with thumbnail & og_image paths
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🛡️ AR ENTERTAINMENT - RECREATING 100% ORIGINAL BLOG ASSETS (BATCH 5.4.13: ARTICLES 61–65)\n";
echo "========================================================\n\n";

$db = db();
$target_dir = UPLOADS_PATH . DIRECTORY_SEPARATOR . 'blog';
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

if (!function_exists('save_blog_image_as_avif')) {
    function save_blog_image_as_avif($img, string $filename_slug): string
    {
        global $target_dir;
        $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename_slug . '.avif';
        $target_rel = 'uploads/blog/' . $filename_slug . '.avif';

        imagealphablending($img, false);
        imagesavealpha($img, true);

        if (function_exists('imageavif')) {
            @imageavif($img, $target_file, 85);
        } elseif (function_exists('imagewebp')) {
            $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename_slug . '.webp';
            $target_rel = 'uploads/blog/' . $filename_slug . '.webp';
            @imagewebp($img, $target_file, 85);
        }

        return $target_rel;
    }
}

if (!function_exists('create_clean_blog_avif_asset')) {
    function create_clean_blog_avif_asset(string $source_path, string $filename_slug, string $category_name, string $badge_title, string $badge_subtitle): string
    {
        $raw = false;
        if (file_exists($source_path)) {
            $raw = @file_get_contents($source_path);
        }

        $dst_w = 1200;
        $dst_h = 630;
        $dst_img = imagecreatetruecolor($dst_w, $dst_h);

        if ($raw !== false && strlen($raw) > 1000 && function_exists('imagecreatefromstring')) {
            $src_img = @imagecreatefromstring($raw);
            if ($src_img) {
                $src_w = imagesx($src_img);
                $src_h = imagesy($src_img);

                // Cover crop calculation
                $src_ratio = $src_w / $src_h;
                $dst_ratio = $dst_w / $dst_h;

                if ($src_ratio > $dst_ratio) {
                    $temp_w = (int)($src_h * $dst_ratio);
                    $temp_h = $src_h;
                    $src_x = (int)(($src_w - $temp_w) / 2);
                    $src_y = 0;
                } else {
                    $temp_w = $src_w;
                    $temp_h = (int)($src_w / $dst_ratio);
                    $src_x = 0;
                    $src_y = (int)(($src_h - $temp_h) / 2);
                }

                imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $temp_w, $temp_h);
                imagedestroy($src_img);
            }
        }

        // Apply cinematic dark tint overlay across the image for rich contrast
        imagealphablending($dst_img, true);
        $tint = imagecolorallocatealpha($dst_img, 15, 23, 42, 50); // Navy dark tint
        imagefilledrectangle($dst_img, 0, 0, $dst_w, $dst_h, $tint);

        // Render stylish AR Entertainment Center-Left Brand Card Pill
        $card_w = 780;
        $card_h = 230;
        $card_x = 60;
        $card_y = (int)(($dst_h - $card_h) / 2 - 30);

        // Outer glow / card background
        $card_bg = imagecolorallocatealpha($dst_img, 15, 23, 42, 35); // Glassmorphic dark slate
        $card_border = imagecolorallocatealpha($dst_img, 245, 158, 11, 60); // Gold amber border
        imagefilledrectangle($dst_img, $card_x, $card_y, $card_x + $card_w, $card_y + $card_h, $card_bg);
        imagerectangle($dst_img, $card_x, $card_y, $card_x + $card_w, $card_y + $card_h, $card_border);

        // Gold decorative accent bar on left of card
        $gold = imagecolorallocate($dst_img, 245, 158, 11);
        imagefilledrectangle($dst_img, $card_x, $card_y, $card_x + 8, $card_y + $card_h, $gold);

        // Text inside card
        $white = imagecolorallocate($dst_img, 255, 255, 255);
        $light_gold = imagecolorallocate($dst_img, 253, 230, 138);
        $light_slate = imagecolorallocate($dst_img, 203, 213, 225);

        // Category Tag
        imagestring($dst_img, 5, $card_x + 35, $card_y + 35, "AR ENTERTAINMENT | " . strtoupper($category_name), $light_gold);

        // Main Title (Wrapped nicely if long)
        $title_line1 = substr($badge_title, 0, 48);
        $title_line2 = strlen($badge_title) > 48 ? substr($badge_title, 48, 48) : '';

        imagestring($dst_img, 5, $card_x + 35, $card_y + 80, strtoupper($title_line1), $white);
        if ($title_line2 !== '') {
            imagestring($dst_img, 5, $card_x + 35, $card_y + 115, strtoupper($title_line2), $white);
            imagestring($dst_img, 4, $card_x + 35, $card_y + 165, $badge_subtitle, $light_slate);
        } else {
            imagestring($dst_img, 5, $card_x + 35, $card_y + 125, $badge_subtitle, $light_slate);
            imagestring($dst_img, 4, $card_x + 35, $card_y + 165, "Professional Production House in Bangladesh - arentertainment.bd", $light_gold);
        }

        // Apply dark cinema lower third footer branding strip
        $footer_bg = imagecolorallocatealpha($dst_img, 10, 15, 28, 25);
        imagefilledrectangle($dst_img, 0, $dst_h - 85, $dst_w, $dst_h, $footer_bg);

        $amber = imagecolorallocate($dst_img, 245, 158, 11);
        $gray = imagecolorallocate($dst_img, 203, 213, 225);

        imagestring($dst_img, 5, 40, $dst_h - 60, "AR ENTERTAINMENT | " . strtoupper($category_name), $amber);
        imagestring($dst_img, 4, 40, $dst_h - 32, "Official Production & Video Marketing Insights - arentertainment.bd", $gray);

        $rel = save_blog_image_as_avif($dst_img, $filename_slug);
        imagedestroy($dst_img);
        return $rel;
    }
}

// 5 Blog Articles Configuration for Batch 5.4.13 with CLEAN, LOGO-FREE Source Images
$blogs_config_batch13 = [
    [
        'slug' => 'music-video-production-bangladesh-cost',
        'title' => 'Music Video Production in Bangladesh: Cost & Process Guide',
        'category_name' => 'TVC & COMMERCIALS',
        'source_img' => __DIR__ . '/../../images/banner/music-video-production-bangladesh-cost.jpg',
        'target_slug' => 'ar-music-video-production-bangladesh-cost',
        'badge_title' => 'Music Video Production in Bangladesh',
        'badge_subtitle' => 'Budget Tiers, Cinematography, Studio Sets & Post-Production'
    ],
    [
        'slug' => 'outsource-ai-video-production-bangladesh',
        'title' => 'How to Outsource AI Video Production to Bangladesh (2026)',
        'category_name' => 'AI VIDEO PRODUCTION',
        'source_img' => __DIR__ . '/../../images/banner/outsource-ai-video-production-bangladesh.jpg',
        'target_slug' => 'ar-outsource-ai-video-production-bangladesh',
        'badge_title' => 'Outsource AI Video Production to BD',
        'badge_subtitle' => 'Global Workflows, Cost Advantage & Remote Delivery'
    ],
    [
        'slug' => 'ovc-vs-tvc-in-bangladesh',
        'title' => 'OVC vs TVC in Bangladesh: Cost, Performance & ROI (2026)',
        'category_name' => 'OVC & DIGITAL ADS',
        'source_img' => __DIR__ . '/../../images/banner/ovc-vs-tvc-in-bangladesh.jpg',
        'target_slug' => 'ar-ovc-vs-tvc-in-bangladesh',
        'badge_title' => 'OVC vs TVC in Bangladesh Comparison',
        'badge_subtitle' => 'Media Buying, Production Cost, Aspect Ratios & Ad ROI'
    ],
    [
        'slug' => 'ai-content-and-eu-ai-act',
        'title' => 'AI Content and the EU AI Act: 2026 Compliance Guide',
        'category_name' => 'AI VIDEO PRODUCTION',
        'source_img' => __DIR__ . '/../../images/banner/ai-content-and-eu-ai-act.jpg',
        'target_slug' => 'ar-ai-content-and-eu-ai-act',
        'badge_title' => 'AI Content and the EU AI Act Guide',
        'badge_subtitle' => 'Risk Categorisation, Transparency & Enterprise Duties'
    ],
    [
        'slug' => 'ai-content-and-uk-advertising-standards',
        'title' => 'AI Content & UK Advertising Standards: ASA & CAP Guide',
        'category_name' => 'AI VIDEO PRODUCTION',
        'source_img' => __DIR__ . '/../../images/banner/ai-content-and-uk-advertising-standards.jpg',
        'target_slug' => 'ar-ai-content-and-uk-advertising-standards',
        'badge_title' => 'AI Content & UK Advertising Standards',
        'badge_subtitle' => 'ASA Rules, CAP Code Compliance & Disclosure Guidelines'
    ]
];

$update_stmt = $db->prepare("
    UPDATE blogs
    SET thumbnail = :thumbnail,
        og_image = :og_image,
        updated_at = NOW()
    WHERE slug = :slug
");

$processed = 0;

foreach ($blogs_config_batch13 as $cfg) {
    echo "▶ Generating Clean Blog Asset: {$cfg['title']} ({$cfg['slug']})\n";

    // Generate clean 1200x630 AVIF Asset with 100% AR Entertainment styling
    $avif_rel = create_clean_blog_avif_asset(
        $cfg['source_img'],
        $cfg['target_slug'],
        $cfg['category_name'],
        $cfg['badge_title'],
        $cfg['badge_subtitle']
    );

    echo "  ↳ Saved: {$avif_rel}\n";

    // Update database record if article exists
    $update_stmt->execute([
        ':thumbnail' => $avif_rel,
        ':og_image' => $avif_rel,
        ':slug' => $cfg['slug']
    ]);

    $processed++;
}

echo "\n✨ Successfully generated {$processed} clean, unbranded .avif blog visual assets for Batch 5.4.13!\n";
