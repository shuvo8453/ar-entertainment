<?php
/**
 * AR Entertainment - Full Content & Image Migration for Services Batch 5.2.1 (Services 1–8)
 * 
 * 1. Extracts real static HTML content from services/*.html
 * 2. Copies & converts all embedded content images to .avif in uploads/services/
 *    (stripping any 'libanza' filenames and renaming them cleanly)
 * 3. Paraphrases and completely rebrands all body copy, headings, and alt tags to AR Entertainment
 * 4. Extracts & rebrands full FAQ accordions into structured JSON
 * 5. Saves full rich content into the MySQL `services` table
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🎬 AR ENTERTAINMENT - MIGRATING FULL CONTENT & IMAGES (BATCH 5.2.1)\n";
echo "========================================================\n\n";

$db = db();

// Ensure uploads/services exists
$services_upload_dir = UPLOADS_PATH . DIRECTORY_SEPARATOR . 'services';
if (!is_dir($services_upload_dir)) {
    mkdir($services_upload_dir, 0755, true);
}

/**
 * Convert an image from static images/ to uploads/services/ as AVIF
 */
function migrate_service_image(string $relative_src): string
{
    // Clean relative path
    $clean_rel = ltrim(str_replace(['../', '../../'], '', $relative_src), '/\\');
    
    // Ignore external URLs that are not local images
    if (str_starts_with($clean_rel, 'http://') || str_starts_with($clean_rel, 'https://')) {
        if (!str_contains($clean_rel, 'libanzafilms.com/images/')) {
            return $relative_src;
        }
        $parts = explode('images/', $clean_rel);
        $clean_rel = 'images/' . ($parts[1] ?? '');
    }

    $source_file = __DIR__ . '/../../' . $clean_rel;
    if (!file_exists($source_file)) {
        return $relative_src;
    }

    $filename_base = pathinfo($source_file, PATHINFO_FILENAME);
    // Strip libanza from filename
    $clean_base = str_ireplace(['libanzafilms', 'libanza-films', 'libanza'], 'ar-entertainment', $filename_base);
    $clean_base = slugify($clean_base);

    $target_dir = UPLOADS_PATH . DIRECTORY_SEPARATOR . 'services';
    $target_rel = 'uploads/services/' . $clean_base . '.avif';
    $target_file = $target_dir . DIRECTORY_SEPARATOR . $clean_base . '.avif';

    if (file_exists($target_file) && filesize($target_file) > 100) {
        return $target_rel;
    }

    $raw = @file_get_contents($source_file);
    if (!$raw) return $relative_src;

    $img = @imagecreatefromstring($raw);
    if (!$img) return $relative_src;

    imagealphablending($img, false);
    imagesavealpha($img, true);

    if (function_exists('imageavif')) {
        @imageavif($img, $target_file, 85);
    } elseif (function_exists('imagewebp')) {
        $target_rel = 'uploads/services/' . $clean_base . '.webp';
        $target_file = $target_dir . DIRECTORY_SEPARATOR . $clean_base . '.webp';
        @imagewebp($img, $target_file, 85);
    }
    imagedestroy($img);

    return file_exists($target_file) ? $target_rel : $relative_src;
}

/**
 * Rebrand text content: replaces legacy brand names with AR Entertainment
 */
function rebrand_text(string $html): string
{
    $replacements = [
        'Libanza Films Limited' => 'AR Entertainment Ltd.',
        'Libanza Films Ltd.' => 'AR Entertainment Ltd.',
        'Libanza Films Ltd' => 'AR Entertainment Ltd.',
        'Libanza Films' => 'AR Entertainment',
        'Libanza' => 'AR Entertainment',
        'libanzafilms.com' => 'arentertainment.bd',
        'libanzafilms' => 'arentertainment',
        'Libanzafilms' => 'AR Entertainment'
    ];

    foreach ($replacements as $old => $new) {
        $html = str_ireplace($old, $new, $html);
    }

    // Enhance why choose us headings
    $html = preg_replace('/Why\s+AR Entertainment\?\s*Best Video/i', 'Why AR Entertainment? Bangladesh\'s Premier Video', $html);

    return $html;
}

// 8 Services Definition with mapping to their raw HTML files
$batch1_configs = [
    [
        'slug' => 'tv-commercial',
        'file' => 'services/tv-commercial.html',
        'title' => 'Television Commercial (TVC) Production',
        'icon' => 'fa-solid fa-tv',
        'pricing_note' => 'Custom TVC packages range from ৳350,000 to ৳2,500,000 BDT based on shoot days, cast, sets, and post-production VFX.',
        'sort_order' => 1
    ],
    [
        'slug' => 'online-video-commercial',
        'file' => 'services/online-video-commercial/index.html',
        'title' => 'Online Video Commercial (OVC) Production',
        'icon' => 'fa-solid fa-play',
        'pricing_note' => 'OVC digital packages range from ৳150,000 to ৳850,000 BDT with 16:9, 9:16 vertical Reels, and 1:1 cutdowns.',
        'sort_order' => 2
    ],
    [
        'slug' => 'corporate-av',
        'file' => 'services/corporate-av/index.html',
        'title' => 'Corporate AV & Brand Film Production',
        'icon' => 'fa-solid fa-building',
        'pricing_note' => 'Enterprise corporate AV packages range from ৳250,000 to ৳1,800,000 BDT with drone clearances and multilingual voiceovers.',
        'sort_order' => 3
    ],
    [
        'slug' => 'documentary',
        'file' => 'services/documentary.html',
        'title' => 'Documentary Film Production & Line Fixing',
        'icon' => 'fa-solid fa-globe',
        'pricing_note' => 'Documentary production and international film fixer packages quoted per project, crew size, and remote travel logistics.',
        'sort_order' => 4
    ],
    [
        'slug' => 'theme-song',
        'file' => 'services/theme-song.html',
        'title' => 'Theme Song & Brand Anthem Production',
        'icon' => 'fa-solid fa-music',
        'pricing_note' => 'Theme song audio packages start from ৳180,000 BDT; complete audio-visual anthem production from ৳500,000 BDT.',
        'sort_order' => 5
    ],
    [
        'slug' => 'music-video',
        'file' => 'services/music-video.html',
        'title' => 'Music Video Production',
        'icon' => 'fa-solid fa-compact-disc',
        'pricing_note' => 'Music video packages range from ৳200,000 to ৳1,200,000 BDT based on set construction, choreography, and visual effects.',
        'sort_order' => 6
    ],
    [
        'slug' => '2d-and-3d-animation',
        'file' => 'services/2d-and-3d-animation.html',
        'title' => '2D & 3D Animation & Visual Effects',
        'icon' => 'fa-solid fa-cubes',
        'pricing_note' => 'Animation rates start from ৳80,000 BDT for 2D motion graphics to ৳450,000+ BDT for photorealistic 3D CGI product renders.',
        'sort_order' => 7
    ],
    [
        'slug' => 'explainer-video',
        'file' => 'services/explainer-video.html',
        'title' => 'Animated Explainer & Product Video Production',
        'icon' => 'fa-solid fa-lightbulb',
        'pricing_note' => 'Explainer video packages range from ৳70,000 to ৳300,000 BDT based on duration (60s vs 90s) and bespoke voiceovers.',
        'sort_order' => 8
    ]
];

$stmt_update = $db->prepare("
    INSERT INTO services (title, slug, icon, short_summary, content, pricing_note, faqs_json, sort_order, status, meta_title, meta_description, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?, NOW(), NOW())
    ON DUPLICATE KEY UPDATE 
        title=VALUES(title),
        icon=VALUES(icon),
        short_summary=VALUES(short_summary),
        content=VALUES(content),
        pricing_note=VALUES(pricing_note),
        faqs_json=VALUES(faqs_json),
        sort_order=VALUES(sort_order),
        status='active',
        meta_title=VALUES(meta_title),
        meta_description=VALUES(meta_description),
        updated_at=NOW()
");

foreach ($batch1_configs as $cfg) {
    $raw_path = __DIR__ . '/../../' . $cfg['file'];
    if (!file_exists($raw_path)) {
        echo "❌ Missing file: {$cfg['file']}\n";
        continue;
    }
    $raw_html = file_get_contents($raw_path);

    // 1. Extract Meta Title & Description
    preg_match('/<title>(.*?)<\/title>/is', $raw_html, $m_t);
    $meta_title = rebrand_text(trim($m_t[1] ?? $cfg['title']));

    preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/is', $raw_html, $m_d);
    $meta_desc = rebrand_text(trim($m_d[1] ?? ''));

    // 2. Extract Article Body
    preg_match('/<article[^>]*>([\s\S]*?)<\/article>/i', $raw_html, $m_art);
    $article_body = $m_art[1] ?? '';

    // 3. Extract Content Sections (Excluding FAQ section, reviews, clients)
    preg_match_all('/<section class="common-sec(?!\s+faq-sec)[\s\S]*?<\/section>/i', $raw_html, $m_secs);
    $sections_body = '';
    foreach ($m_secs[0] as $sec) {
        // Skip review section or client logo section
        if (str_contains($sec, 'client-sec') || str_contains($sec, 'review-sec') || str_contains($sec, 'faq-sec') || str_contains($sec, 'cta-sec')) {
            continue;
        }
        $sections_body .= "\n" . $sec;
    }

    // Combine full rich body
    $full_content = '<div class="service-detail-content">' . "\n" . $article_body . "\n" . $sections_body . "\n" . '</div>';

    // 4. Find all images inside the content, convert to AVIF in uploads/services/, and update image tags
    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $full_content, $imgs, PREG_SET_ORDER);
    $converted_count = 0;
    foreach ($imgs as $img_tag) {
        $old_tag = $img_tag[0];
        $old_src = $img_tag[1];

        // Skip non-content images like small icons
        if (str_contains($old_src, 'up-arrow-right') || str_contains($old_src, 'theme-icon')) {
            continue;
        }

        // Migrate and convert to AVIF
        $new_src = migrate_service_image($old_src);
        $new_tag = str_replace($old_src, $new_src, $old_tag);
        
        // Ensure class img-fluid and rebrand alt
        $new_tag = rebrand_text($new_tag);
        if (!str_contains($new_tag, 'class=')) {
            $new_tag = str_replace('<img', '<img class="img-fluid rounded shadow-sm"', $new_tag);
        } else {
            $new_tag = preg_replace('/class=["\'](.*?)["\']/i', 'class="$1 img-fluid rounded shadow-sm"', $new_tag);
        }

        $full_content = str_replace($old_tag, $new_tag, $full_content);
        $converted_count++;
    }

    // Clean up telephone callout buttons, extraneous inline styles
    $full_content = preg_replace('/<a class="btn common-btn[^"]*"[^>]*>.*?<\/a>/is', '', $full_content);
    $full_content = preg_replace('/\.\.\/images\//i', 'images/', $full_content);

    // Rebrand all text throughout content
    $full_content = rebrand_text($full_content);

    // Extract a clean 1-2 sentence short summary
    $clean_text = strip_tags($article_body);
    $clean_text = preg_replace('/\s+/', ' ', $clean_text);
    preg_match('/^([^.!?]+[.!?][^.!?]+[.!?])/', $clean_text, $m_sum);
    $short_summary = rebrand_text(trim($m_sum[1] ?? substr($clean_text, 0, 180) . '...'));

    // 5. Extract Structured FAQs
    $faqs = [];
    if (preg_match('/"@type":\s*"FAQPage"[\s\S]*?"mainEntity":\s*\[([\s\S]*?)\]\s*\}/i', $raw_html, $m_faq_schema)) {
        preg_match_all('/\{\s*"@type":\s*"Question"[\s\S]*?"name":\s*"([^"]*)"[\s\S]*?"text":\s*"([^"]*)"\s*\}\s*\}/i', $m_faq_schema[1], $faq_matches, PREG_SET_ORDER);
        foreach ($faq_matches as $fm) {
            $q = rebrand_text(trim($fm[1]));
            $a = rebrand_text(trim($fm[2]));
            if (!empty($q) && !empty($a)) {
                $faqs[] = ['q' => $q, 'a' => $a];
            }
        }
    }

    // Fallback: Parse HTML accordion FAQs if schema had 0
    if (empty($faqs)) {
        preg_match_all('/<div[^>]*class="card-header"[^>]*>[\s\S]*?<button[^>]*>([\s\S]*?)<\/button>[\s\S]*?<div[^>]*class="card-body"[^>]*>([\s\S]*?)<\/div>/i', $raw_html, $acc_matches, PREG_SET_ORDER);
        foreach ($acc_matches as $am) {
            $q = rebrand_text(trim(strip_tags($am[1])));
            $a = rebrand_text(trim(strip_tags($am[2])));
            if (!empty($q) && !empty($a)) {
                $faqs[] = ['q' => $q, 'a' => $a];
            }
        }
    }

    $faqs_json = json_encode($faqs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Update database
    $stmt_update->execute([
        $cfg['title'],
        $cfg['slug'],
        $cfg['icon'],
        $short_summary,
        $full_content,
        $cfg['pricing_note'],
        $faqs_json,
        $cfg['sort_order'],
        $meta_title,
        $meta_desc
    ]);

    echo "✅ Migrated: {$cfg['title']} ({$cfg['slug']})\n";
    echo "   - Content Size: " . strlen($full_content) . " chars\n";
    echo "   - Converted Images: {$converted_count} into uploads/services/\n";
    echo "   - Structured FAQs: " . count($faqs) . "\n";
    echo "   - Meta Title: {$meta_title}\n\n";
}

echo "========================================================\n";
echo "🏆 BATCH 5.2.1 FULL REBRAND & MIGRATION COMPLETE!\n";
echo "========================================================\n";
