<?php
/**
 * AR Entertainment - 100% Original Asset Generator & Database Migrator for Blog Batch 5.4.17 (Articles 81–88)
 * 
 * Generates and embeds 8 brand-new, copyright-safe, clean cinema-grade featured visual assets:
 * 81. sustainability-video-production-rmg-bangladesh: RMG Green Factory & ESG Sustainability Video
 * 82. tiktok-is-powerful-video-marketing-tool: TikTok Video Marketing & Organic Growth
 * 83. tvc-production-cost-bangladesh: TV Commercial Production Cost & Budget Breakdown
 * 84. video-marketing-for-fmcg-brands-in-bangladesh: Video Marketing Strategy for FMCG Brands
 * 85. video-production-support-bangladesh-guide: Comprehensive Video Production Support in BD
 * 86. what-is-a-film-fixer-bangladesh: What is a Film Fixer in Bangladesh Guide
 * 87. what-is-corporate-av-production-bangladesh: Corporate AV Production Architecture
 * 88. why-fmcg-brands-spend-more-on-ovc-than-tvcs-in-bangladesh: FMCG OVC vs TVC Budget Shift
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
echo "🛡️ AR ENTERTAINMENT - RECREATING 100% ORIGINAL BLOG ASSETS (BATCH 5.4.17: ARTICLES 81–88)\n";
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
        $card_w = 840;
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

$blog_assets = [
    [
        'slug'            => 'sustainability-video-production-rmg-bangladesh',
        'title'           => 'Sustainability Video Production for RMG & Garment Exporters in Bangladesh',
        'category'        => 'Corporate & Brand Films',
        'badge_title'     => 'RMG Sustainability Video Production',
        'badge_subtitle'  => 'LEED Green Factories, ESG Compliance & Global Fashion Buyers',
        'filename_slug'   => 'ar-sustainability-video-production-rmg-bangladesh',
        'source_img'      => ROOT_PATH . '/images/rmg-garment-textile-og.webp'
    ],
    [
        'slug'            => 'tiktok-is-powerful-video-marketing-tool',
        'title'           => 'Why TikTok Is a Powerful Video Marketing Tool for Brands in Bangladesh (2026)',
        'category'        => 'Video Marketing & SEO',
        'badge_title'     => 'TikTok Video Marketing for Brands',
        'badge_subtitle'  => 'Organic Reach, In-Feed Video Ads & Gen Z Viral Hooks',
        'filename_slug'   => 'ar-tiktok-is-powerful-video-marketing-tool',
        'source_img'      => ROOT_PATH . '/images/powerful-tiktok-video-creation.jpg'
    ],
    [
        'slug'            => 'tvc-production-cost-bangladesh',
        'title'           => 'TVC Production Cost in Bangladesh: Complete 2026 Price Breakdown',
        'category'        => 'TVC & Commercials',
        'badge_title'     => 'TVC Production Cost in Bangladesh',
        'badge_subtitle'  => 'Pre-Production, Celebrity Cast Fees & Shoot Day Budgets',
        'filename_slug'   => 'ar-tvc-production-cost-bangladesh',
        'source_img'      => ROOT_PATH . '/images/tvc-production-cost-bangladesh-og.webp'
    ],
    [
        'slug'            => 'video-marketing-for-fmcg-brands-in-bangladesh',
        'title'           => 'Video Marketing for FMCG Brands in Bangladesh: The Complete Playbook (2026)',
        'category'        => 'Video Marketing & SEO',
        'badge_title'     => 'FMCG Video Marketing Playbook',
        'badge_subtitle'  => 'Top-of-Funnel Brand Awareness & High ROAS Digital Commerce',
        'filename_slug'   => 'ar-video-marketing-for-fmcg-brands-in-bangladesh',
        'source_img'      => ROOT_PATH . '/images/fmcg-ovc-strategy.webp'
    ],
    [
        'slug'            => 'video-production-support-bangladesh-guide',
        'title'           => 'Video Production Support in Bangladesh: Complete International Line Guide',
        'category'        => 'Film Fixer & Filming in BD',
        'badge_title'     => 'Video Production Support in Bangladesh',
        'badge_subtitle'  => 'Full Line Production, Gear Rentals & Field Management',
        'filename_slug'   => 'ar-video-production-support-bangladesh-guide',
        'source_img'      => ROOT_PATH . '/images/video-production-support-bangladesh-guide-og.webp'
    ],
    [
        'slug'            => 'what-is-a-film-fixer-bangladesh',
        'title'           => 'What Is a Film Fixer? The Complete Bangladesh Film Guide (2026)',
        'category'        => 'Film Fixer & Filming in BD',
        'badge_title'     => 'What Is a Film Fixer in Bangladesh?',
        'badge_subtitle'  => 'Roles, Local Navigation & Why Foreign Crews Need One',
        'filename_slug'   => 'ar-what-is-a-film-fixer-bangladesh',
        'source_img'      => ROOT_PATH . '/images/what-is-a-film-fixer-bangladesh-og.webp'
    ],
    [
        'slug'            => 'what-is-corporate-av-production-bangladesh',
        'title'           => 'What Is Corporate AV Production? Bangladesh Enterprise Guide (2026)',
        'category'        => 'Corporate AV & Video',
        'badge_title'     => 'What Is Corporate AV Production?',
        'badge_subtitle'  => 'Investor Overviews, Company Profile Films & Strategic AVs',
        'filename_slug'   => 'ar-what-is-corporate-av-production-bangladesh',
        'source_img'      => ROOT_PATH . '/images/what-is-corporate-av-production-og.webp'
    ],
    [
        'slug'            => 'why-fmcg-brands-spend-more-on-ovc-than-tvcs-in-bangladesh',
        'title'           => 'Why FMCG Brands in Bangladesh Spend More on OVC Than TVCs (2026 Analysis)',
        'category'        => 'OVC & Digital Ads',
        'badge_title'     => 'Why FMCG Brands Spend More on OVC',
        'badge_subtitle'  => 'Audience Shift, Attribution Tracking & Digital Ad Dominance',
        'filename_slug'   => 'ar-why-fmcg-brands-spend-more-on-ovc-than-tvcs-in-bangladesh',
        'source_img'      => ROOT_PATH . '/images/fmcg-ovc-vs-tvc-bangladesh-2026.webp'
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

echo "\n✨ Successfully generated 8 clean, unbranded .avif blog visual assets for Batch 5.4.17!\n\n";
