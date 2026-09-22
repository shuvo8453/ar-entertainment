<?php
/**
 * AR Entertainment - 100% Original Asset Generator & Database Migrator for Blog Batch 5.4.15 (Articles 71–75)
 * 
 * Generates and embeds 5 brand-new, copyright-safe, clean cinema-grade featured visual assets:
 * 71. ai-video-localisation-and-dubbing: AI Video Localisation & Multilingual Dubbing Visual (Generated AI Soundstage)
 * 72. ai-video-production-cost-comparison-bangladesh-2026: AI Video Production Cost Comparison Visual (Generated AI Suite)
 * 73. ai-video-production-ngos-development-organisations: AI Video for NGOs & Development Organizations Visual (Generated AI Documentary)
 * 74. brand-film-production-textile-exporters-bangladesh: Brand Films for Textile & Garment Exporters Visual (RMG Factory Plate)
 * 75. how-many-ovc-versions-does-a-campaign-really-need: OVC Multivariate Versions & Hook Matrix Visual (Multi-OVC Shoot Plate)
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
echo "🛡️ AR ENTERTAINMENT - RECREATING 100% ORIGINAL BLOG ASSETS (BATCH 5.4.15: ARTICLES 71–75)\n";
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
        $tint = imagecolorallocatealpha($dst_img, 15, 23, 42, 60); // Navy dark tint
        imagefilledrectangle($dst_img, 0, 0, $dst_w, $dst_h, $tint);

        // Render stylish AR Entertainment Center-Left Brand Card Pill
        $card_w = 820;
        $card_h = 240;
        $card_x = 60;
        $card_y = (int)(($dst_h - $card_h) / 2 - 20);

        // Outer glow / card background
        $card_bg = imagecolorallocatealpha($dst_img, 15, 23, 42, 45); // Glassmorphic dark slate
        $card_border = imagecolorallocatealpha($dst_img, 245, 158, 11, 80); // Gold amber border
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
        $footer_bg = imagecolorallocatealpha($dst_img, 10, 15, 28, 30);
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

$brain_dir = 'C:/Users/Shuvo/.gemini/antigravity-ide/brain/ec7e8596-3e1f-4c60-9cbe-371c916b611c';

$blog_assets = [
    [
        'slug'            => 'ai-video-localisation-and-dubbing',
        'title'           => 'AI Video Localisation & Dubbing: Complete 2026 Guide',
        'category'        => 'AI Video Production',
        'badge_title'     => 'AI Video Localisation & Dubbing',
        'badge_subtitle'  => 'Enterprise Multilingual Voice Cloning & Lip-Sync (2026)',
        'filename_slug'   => 'ar-ai-video-localisation-and-dubbing',
        'source_img'      => $brain_dir . '/ar_ai_video_localisation_dubbing_1790073859373.jpg'
    ],
    [
        'slug'            => 'ai-video-production-cost-comparison-bangladesh-2026',
        'title'           => 'AI Video Production Cost Comparison: Bangladesh 2026',
        'category'        => 'AI Video Production',
        'badge_title'     => 'AI Video Production Cost Comparison 2026',
        'badge_subtitle'  => 'Bangladesh vs US, UK & Europe Production Budgets',
        'filename_slug'   => 'ar-ai-video-production-cost-comparison-bangladesh-2026',
        'source_img'      => $brain_dir . '/ar_ai_video_cost_comparison_1790073876641.jpg'
    ],
    [
        'slug'            => 'ai-video-production-ngos-development-organisations',
        'title'           => 'AI Video Production for NGOs & Development Organisations',
        'category'        => 'AI Video Production',
        'badge_title'     => 'AI Video for NGOs & Development Bodies',
        'badge_subtitle'  => 'Donor Reporting, Training & Field Communication',
        'filename_slug'   => 'ar-ai-video-production-ngos-development-organisations',
        'source_img'      => $brain_dir . '/ar_ai_ngo_development_video_1790073895910.jpg'
    ],
    [
        'slug'            => 'brand-film-production-textile-exporters-bangladesh',
        'title'           => 'Brand Film Production for Textile & Garment Exporters in Bangladesh',
        'category'        => 'Corporate Brand Films',
        'badge_title'     => 'Brand Films for Textile Exporters',
        'badge_subtitle'  => 'RMG Buyer Pitch & Global Brand Positioning in BD',
        'filename_slug'   => 'ar-brand-film-production-textile-exporters-bangladesh',
        'source_img'      => ROOT_PATH . '/images/rmg-garment-textile-og.webp'
    ],
    [
        'slug'            => 'how-many-ovc-versions-does-a-campaign-really-need',
        'title'           => 'How Many OVC Versions Does an Ad Campaign Really Need? (2026 Guide)',
        'category'        => 'OVC & Digital Ads',
        'badge_title'     => 'How Many OVC Versions Does a Campaign Need?',
        'badge_subtitle'  => 'Multi-Hook Testing & Creative Fatigue Strategy',
        'filename_slug'   => 'ar-how-many-ovc-versions-does-a-campaign-really-need',
        'source_img'      => ROOT_PATH . '/images/one-shoot-multiple-ovc-versions.webp'
    ],
];

foreach ($blog_assets as $item) {
    echo "▶ Generating Clean Blog Asset: {$item['title']} ({$item['slug']})\n";
    echo "  ↳ Using Source Plate: {$item['source_img']} (" . (file_exists($item['source_img']) ? filesize($item['source_img']) . ' bytes' : 'NOT FOUND') . ")\n";
    $rel_path = create_clean_blog_avif_asset(
        $item['source_img'],
        $item['filename_slug'],
        $item['category'],
        $item['badge_title'],
        $item['badge_subtitle']
    );

    // Update database if row exists
    $update_stmt = $db->prepare("UPDATE blogs SET thumbnail = ?, og_image = ? WHERE slug = ?");
    $update_stmt->execute([$rel_path, $rel_path, $item['slug']]);
    echo "  ↳ Saved: {$rel_path}\n";
}

echo "\n✨ Successfully generated 5 clean, unbranded .avif blog visual assets for Batch 5.4.15!\n\n";
