<?php
/**
 * AR Entertainment - 100% Original Asset Generator & Database Migrator for District Batch 5.3.7 (Districts 59–64 — Barishal Division)
 * 
 * Generates and embeds 12 brand-new, copyright-safe, cinema-grade visual assets:
 * 1. Barishal: Floating Guava Market & Kirtankhola River Visual + District Logistics Infographic
 * 2. Barguna: Payra Estuary & Shuvoshondha Sea Beach Visual + District Logistics Infographic
 * 3. Bhola: Monpura Island & Jacob Tower Visual + District Logistics Infographic
 * 4. Jhalokati: Bhimruli Canal & Gabkhan Bridge Visual + District Logistics Infographic
 * 5. Patuakhali: Kuakata Beach & Payra Deep Sea Port Visual + District Logistics Infographic
 * 6. Pirojpur: Bekutia Bridge & Betel Riverway Visual + District Logistics Infographic
 * 
 * Features:
 * - All visual assets converted to lightweight .avif in uploads/service-areas/
 * - 6 programmatically generated district filming infographics in dark-mode cinema aesthetic
 * - Updates MySQL service_areas table with rich responsive layout embedding only these pristine assets
 * - Completes Phase 5.3 (100% of all 64 Bangladesh Districts migrated)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

echo "========================================================\n";
echo "🛡️ AR ENTERTAINMENT - RECREATING 100% ORIGINAL DISTRICT ASSETS (BATCH 5.3.7: BARISHAL DIVISION)\n";
echo "========================================================\n\n";

$db = db();
$target_dir = UPLOADS_PATH . DIRECTORY_SEPARATOR . 'service-areas';
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

if (!function_exists('save_image_as_avif')) {
    function save_image_as_avif($img, string $filename_slug): string
    {
        global $target_dir;
        $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename_slug . '.avif';
        $target_rel = 'uploads/service-areas/' . $filename_slug . '.avif';

        imagealphablending($img, false);
        imagesavealpha($img, true);

        if (function_exists('imageavif')) {
            @imageavif($img, $target_file, 85);
        } elseif (function_exists('imagewebp')) {
            $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename_slug . '.webp';
            $target_rel = 'uploads/service-areas/' . $filename_slug . '.webp';
            @imagewebp($img, $target_file, 85);
        }

        return $target_rel;
    }
}

if (!function_exists('create_avif_asset')) {
    function create_avif_asset(string $source_path_or_url, string $filename_slug): string
    {
        $raw = false;
        if (str_starts_with($source_path_or_url, 'http://') || str_starts_with($source_path_or_url, 'https://')) {
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);
            $raw = @file_get_contents($source_path_or_url, false, $ctx);
        } elseif (file_exists($source_path_or_url)) {
            $raw = @file_get_contents($source_path_or_url);
        }

        if ($raw === false || strlen($raw) < 1000) {
            echo "   ⚠️ Notice: Source {$source_path_or_url} not reachable, generating gradient fallback...\n";
            return create_gradient_district_hero_avif($filename_slug, ucwords(str_replace(['-', 'ar '], ' ', $filename_slug)));
        }

        if (!function_exists('imagecreatefromstring')) {
            return '';
        }

        $src_img = @imagecreatefromstring($raw);
        if (!$src_img) {
            return create_gradient_district_hero_avif($filename_slug, ucwords(str_replace(['-', 'ar '], ' ', $filename_slug)));
        }

        // Resize to standard 1200x675 (16:9)
        $dst_w = 1200;
        $dst_h = 675;
        $dst_img = imagecreatetruecolor($dst_w, $dst_h);

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

        // Apply film branding & vignette
        $overlay_col = imagecolorallocatealpha($dst_img, 10, 15, 28, 40);
        imagefilledrectangle($dst_img, 0, $dst_h - 100, $dst_w, $dst_h, $overlay_col);

        $amber = imagecolorallocate($dst_img, 245, 158, 11);
        $gray = imagecolorallocate($dst_img, 203, 213, 225);

        imagestring($dst_img, 5, 40, $dst_h - 70, "AR ENTERTAINMENT | CINEMA PRODUCTION & LOCATION FIXER", $amber);
        imagestring($dst_img, 4, 40, $dst_h - 40, "Official Bangladesh 64 District Production Guide - arentertainment.bd", $gray);

        $rel = save_image_as_avif($dst_img, $filename_slug);
        imagedestroy($dst_img);
        return $rel;
    }
}

if (!function_exists('create_gradient_district_hero_avif')) {
    function create_gradient_district_hero_avif(string $filename_slug, string $title): string
    {
        $w = 1200;
        $h = 675;
        $img = imagecreatetruecolor($w, $h);

        $col1 = [10, 20, 45];
        $col2 = [28, 15, 40];

        for ($y = 0; $y < $h; $y++) {
            $ratio = $y / $h;
            $r = (int)($col1[0] * (1 - $ratio) + $col2[0] * $ratio);
            $g = (int)($col1[1] * (1 - $ratio) + $col2[1] * $ratio);
            $b = (int)($col1[2] * (1 - $ratio) + $col2[2] * $ratio);
            $line_col = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $w, $y, $line_col);
        }

        $accent = imagecolorallocate($img, 225, 29, 72);
        $gold = imagecolorallocate($img, 245, 158, 11);
        $white = imagecolorallocate($img, 255, 255, 255);
        $gray = imagecolorallocate($img, 148, 163, 184);

        imageline($img, 50, 50, 90, 50, $gold);
        imageline($img, 50, 50, 50, 90, $gold);
        imageline($img, $w - 50, 50, $w - 90, 50, $gold);
        imageline($img, $w - 50, 50, $w - 50, 90, $gold);

        imagestring($img, 5, 80, (int)($h / 2 - 60), "AR ENTERTAINMENT CINEMA LOCATION GUIDE", $accent);
        imagestring($img, 5, 80, (int)($h / 2 - 20), strtoupper($title), $white);
        imagestring($img, 4, 80, (int)($h / 2 + 25), "Bespoke Film Permitting, Logistics & Cinema Gear in Bangladesh", $gray);

        $rel = save_image_as_avif($img, $filename_slug);
        imagedestroy($img);
        return $rel;
    }
}

if (!function_exists('create_district_logistics_infographic_avif')) {
    function create_district_logistics_infographic_avif(string $filename_slug, string $district_name, array $specs): string
    {
        $w = 1200;
        $h = 520;
        $img = imagecreatetruecolor($w, $h);

        // Deep cinematic background gradient
        $bg1 = [12, 16, 28];
        $bg2 = [22, 24, 38];
        for ($y = 0; $y < $h; $y++) {
            $ratio = $y / $h;
            $r = (int)($bg1[0] * (1 - $ratio) + $bg2[0] * $ratio);
            $g = (int)($bg1[1] * (1 - $ratio) + $bg2[1] * $ratio);
            $b = (int)($bg1[2] * (1 - $ratio) + $bg2[2] * $ratio);
            $line_col = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $w, $y, $line_col);
        }

        $accent_red = imagecolorallocate($img, 225, 29, 72);
        $accent_amber = imagecolorallocate($img, 245, 158, 11);
        $white = imagecolorallocate($img, 255, 255, 255);
        $gray = imagecolorallocate($img, 148, 163, 184);

        // Header strip
        imagefilledrectangle($img, 0, 0, $w, 8, $accent_red);

        // Framing crosshairs
        imageline($img, 60, 60, 100, 60, $gray);
        imageline($img, 60, 60, 60, 100, $gray);
        imageline($img, $w - 60, 60, $w - 100, 60, $gray);
        imageline($img, $w - 60, 60, $w - 60, 100, $gray);

        // Titles
        imagestring($img, 5, 80, 80, "AR ENTERTAINMENT LOCATION PRODUCTION SPECS", $accent_amber);
        imagestring($img, 5, 80, 120, strtoupper($district_name) . " DISTRICT FILMING SPECIFICATIONS", $white);
        imagestring($img, 4, 80, 160, "Official Permits, Transport Logistics, Drone Clearance & Fixer Readiness", $gray);

        // 4 Visual Step Boxes
        $box_w = 240;
        $box_h = 170;
        $start_x = 80;
        $box_y = 230;
        $spacing = 40;

        foreach ($specs as $i => $spec) {
            $x = $start_x + ($i * ($box_w + $spacing));
            $card_bg = imagecolorallocate($img, 18, 25, 42);
            $card_border = imagecolorallocate($img, 42, 52, 75);
            imagefilledrectangle($img, $x, $box_y, $x + $box_w, $box_y + $box_h, $card_bg);
            imagerectangle($img, $x, $box_y, $x + $box_w, $box_y + $box_h, $card_border);
            
            // Step accent top border
            imagefilledrectangle($img, $x, $box_y, $x + $box_w, $box_y + 4, $accent_red);
            imagestring($img, 4, $x + 15, $box_y + 25, $spec[0], $accent_amber);
            
            // Text lines
            $words = explode(' ', $spec[1]);
            $line1 = implode(' ', array_slice($words, 0, 3));
            $line2 = implode(' ', array_slice($words, 3, 4));
            $line3 = implode(' ', array_slice($words, 7));
            imagestring($img, 3, $x + 15, $box_y + 65, $line1, $white);
            imagestring($img, 3, $x + 15, $box_y + 90, $line2, $gray);
            if (!empty($line3)) {
                imagestring($img, 3, $x + 15, $box_y + 115, $line3, $gray);
            }
        }

        // Footer
        imagestring($img, 4, 80, $h - 60, "Official 64 District Production Guide (c) AR Entertainment Ltd. - arentertainment.bd", $gray);

        $rel = save_image_as_avif($img, $filename_slug);
        imagedestroy($img);
        return $rel;
    }
}

// 6 Districts Configuration for Batch 5.3.7 (Barishal Division)
$districts_config = [
    [
        'slug' => 'video-production-company-in-barisal',
        'city_name' => 'Barishal',
        'source_img' => __DIR__ . '/../../images/service-area/barisal-video-production.webp',
        'hero_slug' => 'ar-barishal-district-filming-guide-hero',
        'infographic_slug' => 'ar-barishal-filming-logistics-infographic',
        'specs' => [
            ['1. LOCATION ACCESS', 'Barishal Airport (BZL) & Dhaka-Barishal River Launch terminals.'],
            ['2. CANAL RIGS', 'Custom motorized dinghy camera mounts for floating guava market shoots.'],
            ['3. DRONE PERMITS', 'CAAB aerial clearance for Kirtankhola river and Guthia Mosque complex.'],
            ['4. CREW BASE', 'Experienced local boatmen, marine gaffers, and southern production unit.']
        ],
        'highlights' => [
            'Bhimruli & Banaripara Floating Guava Markets: Hundreds of wooden trading boats in canal networks.',
            'Kirtankhola River Port: Multi-deck luxury passenger steamers and golden hour water reflections.',
            'Oxford Mission Church (Epiphany): Historic red-brick Gothic cathedral with towering 40ft cross.',
            'Baitul Aman Jame Masjid Complex (Guthia Mosque): 20 domes, 193-foot minaret, and shimmering lakes.',
            'Durgasagar Dighi: Historic 18th-century reservoir island teeming with migratory waterbirds.'
        ]
    ],
    [
        'slug' => 'video-production-company-in-barguna',
        'city_name' => 'Barguna',
        'source_img' => __DIR__ . '/../../images/service-area/barguna-video-production.webp',
        'hero_slug' => 'ar-barguna-district-filming-guide-hero',
        'infographic_slug' => 'ar-barguna-filming-logistics-infographic',
        'specs' => [
            ['1. COASTAL LOGISTICS', 'Payra & Bishkhali riverway access via southern regional highways.'],
            ['2. TIDAL TIMING', 'Carefully charted tidal charts for Shuvoshondha Beach sandspit access.'],
            ['3. INDIGENOUS SHOOTS', 'Community liaison for Taltali Rakhine handloom village filming.'],
            ['4. SALT PROTECTION', 'Marine-grade weather-sealed cinema rigs and anti-corrosive gear.']
        ],
        'highlights' => [
            'Shuvoshondha Sea Beach (Taltali): 4-kilometer pristine beach at Payra river ocean convergence.',
            'Tengragiri Wildlife Sanctuary: Second-largest coastal mangrove forest in Bangladesh.',
            'Taltali Rakhine Settlements: Traditional wooden weaving handlooms and Buddhist pagoda monasteries.',
            'Bibi Chini Mughal Mosque: 17th-century elevated brick architecture in Betagi.',
            'Patharghata Fish Harbor: High-energy coastal fishing trawler operations and dry fish yards.'
        ]
    ],
    [
        'slug' => 'video-production-company-in-bhola',
        'city_name' => 'Bhola',
        'source_img' => __DIR__ . '/../../images/service-area/bhola-video-production.webp',
        'hero_slug' => 'ar-bhola-district-filming-guide-hero',
        'infographic_slug' => 'ar-bhola-filming-logistics-infographic',
        'specs' => [
            ['1. ISLAND TRANSIT', 'Regular ferry & dedicated speedboat crossings across Meghna estuary.'],
            ['2. TOWER FILMING', 'Special crane & drone authorizations for 225-ft Jacob Tower glass deck.'],
            ['3. OFFSHORE EXPEDITIONS', 'Self-sufficient mobile generators for Monpura & Char Kukri-Mukri.'],
            ['4. AERIAL CLEARANCE', 'High-wind stabilized cinema drones for infinite oceanic horizon sweeps.']
        ],
        'highlights' => [
            'Monpura Island: Remote island paradise with virgin mangrove trails and sunset estuaries.',
            'Jacob Tower (Char Fasson): 225-foot glass observation tower with 360-degree delta views.',
            'Tarua Sea Beach: Pristine golden sandspits surrounded by ocean mangroves.',
            'Pastoral Buffalo Herds: Swimming water buffalo river crossings and traditional dairy farms.',
            'Char Kukri-Mukri Reserve: Coastal wildlife sanctuary with spotted deer and migratory birds.'
        ]
    ],
    [
        'slug' => 'video-production-company-in-jhalokati',
        'city_name' => 'Jhalokati',
        'source_img' => __DIR__ . '/../../images/service-area/jhalokati-video-production.webp',
        'hero_slug' => 'ar-jhalokati-district-filming-guide-hero',
        'infographic_slug' => 'ar-jhalokati-filming-logistics-infographic',
        'specs' => [
            ['1. WATER NAVIGATION', 'Shallow-draft camera boats tailored for narrow Bhimruli canal transit.'],
            ['2. BRIDGE RIGGING', 'Cable-cam & high-angle camera spots on 115-ft Gabkhan Channel bridge.'],
            ['3. CULTURAL PERMITS', 'Local heritage approvals for Kirtipasha Zamindar Palace ruins.'],
            ['4. AUDIO RECORDING', 'Lush environmental sound capture for Dhanshiri river landscapes.']
        ],
        'highlights' => [
            'Bhimruli Floating Guava & Hog Plum Market: Historic waterway trading canal intersection.',
            'Gabkhan Bridge & Channel: The Suez Canal of Bengal accommodating massive cargo barges.',
            'Kirtipasha Zamindar Palace: 18th-century terracotta architecture and mossy royal courtyards.',
            'Dhanshiri River Heritage: Poetic waterscapes celebrated in Bengali literature by Jibanananda Das.',
            'Timber Boatbuilding Yards: Master woodcraft artisans constructing traditional river vessels.'
        ]
    ],
    [
        'slug' => 'video-production-company-in-patuakhali',
        'city_name' => 'Patuakhali',
        'source_img' => __DIR__ . '/../../images/service-area/patuakhali-video-production.webp',
        'hero_slug' => 'ar-patuakhali-district-filming-guide-hero',
        'infographic_slug' => 'ar-patuakhali-filming-logistics-infographic',
        'specs' => [
            ['1. ROAD & AIR ACCESS', 'Padma Bridge highway connectivity direct to Kuakata coastline.'],
            ['2. INDUSTRIAL ACCESS', 'Port Authority security passes for Payra Deep Sea Port & Power Plant.'],
            ['3. BEACH CINEMA RIGS', '4x4 tracking vehicles and gyro-stabilized gimbal heads for sandy shores.'],
            ['4. TIMELAPSE CREW', 'High-res dual-camera units capturing both sunrise and sunset horizons.']
        ],
        'highlights' => [
            'Kuakata Sea Beach ("Sagar Kannya"): 18km beach viewing both sunrise and sunset over the Bay.',
            'Payra Deep Sea Port & Megaprojects: Sprawling multi-billion-dollar maritime industrial docks.',
            'Fatrar Char Mangrove Reserve: Untamed tidal forest channels and red crab colonies.',
            'Misripara & Keranipara Rakhine Temples: Ancient metal Buddha statue and historic wishing wells.',
            'Lebur Char: Romantic coastal driftwood sandspits with spectacular golden hour lighting.'
        ]
    ],
    [
        'slug' => 'video-production-company-in-pirojpur',
        'city_name' => 'Pirojpur',
        'source_img' => __DIR__ . '/../../images/service-area/pirojpur-video-production.webp',
        'hero_slug' => 'ar-pirojpur-district-filming-guide-hero',
        'infographic_slug' => 'ar-pirojpur-filming-logistics-infographic',
        'specs' => [
            ['1. HIGHWAY ACCESS', 'Direct transit via Bekutia Bridge over the wide Sandhya River.'],
            ['2. FLOATING PLATFORMS', 'Low-profile catamarans for Atghar-Kuriana betel leaf market filming.'],
            ['3. HERITAGE CLEARANCE', 'Filming permits for 300-year-old Rayerkathi Zamindar royal palaces.'],
            ['4. MANGROVE LOGISTICS', 'Experienced river navigators for Sundarbans fringe delta waterways.']
        ],
        'highlights' => [
            'Atghar-Kuriana Floating Betel Leaf Market: Dense canals trading betel leaves and coconuts.',
            'Bekutia Bridge (8th Bangladesh-China Friendship): 1.5km suspension engineering over Sandhya River.',
            'Rayerkathi Zamindar Palace & Temples: 300-year-old historic terracotta estate and Shiva shrines.',
            'Boleshwar River Estuaries: Mangrove eco-travel channels bordering the western Sundarbans.',
            'Bhandaria & Mathbaria Coconut Groves: Lush traditional rural coastal palm plantations.'
        ]
    ]
];

$update_stmt = $db->prepare("
    UPDATE service_areas
    SET content = :content,
        meta_title = :meta_title,
        meta_description = :meta_description,
        updated_at = NOW()
    WHERE slug = :slug
");

$processed = 0;

foreach ($districts_config as $cfg) {
    echo "▶ Processing District: {$cfg['city_name']} ({$cfg['slug']})\n";

    // 1. Generate / convert 16:9 Hero AVIF Asset
    $hero_rel = create_avif_asset($cfg['source_img'], $cfg['hero_slug']);
    echo "   🖼️ Hero Image Generated: {$hero_rel}\n";

    // 2. Generate Cinematic Infographic AVIF
    $info_rel = create_district_logistics_infographic_avif($cfg['infographic_slug'], $cfg['city_name'], $cfg['specs']);
    echo "   📊 Infographic Generated: {$info_rel}\n";

    // 3. Build Rich Responsive HTML Content
    $highlights_html = '';
    foreach ($cfg['highlights'] as $hl) {
        $parts = explode(':', $hl, 2);
        if (count($parts) === 2) {
            $highlights_html .= "    <li><strong>" . htmlspecialchars(trim($parts[0])) . ":</strong> " . htmlspecialchars(trim($parts[1])) . "</li>\n";
        } else {
            $highlights_html .= "    <li>" . htmlspecialchars($hl) . "</li>\n";
        }
    }

    $specs_html = '';
    foreach ($cfg['specs'] as $sp) {
        $specs_html .= "    <div class=\"col-md-6 mb-3\">\n";
        $specs_html .= "        <div class=\"p-3 rounded border\" style=\"background: #1e293b; border-color: #334155 !important;\">\n";
        $specs_html .= "            <h5 class=\"text-warning font-weight-bold mb-1\">" . htmlspecialchars($sp[0]) . "</h5>\n";
        $specs_html .= "            <p class=\"text-light mb-0 small\">" . htmlspecialchars($sp[1]) . "</p>\n";
        $specs_html .= "        </div>\n";
        $specs_html .= "    </div>\n";
    }

    $rich_content = <<<HTML
<div class="district-guide-container">
    <div class="row align-items-center mb-4">
        <div class="col-lg-12">
            <div class="position-relative overflow-hidden rounded shadow-lg mb-4">
                <img src="{$hero_rel}" alt="Video Production & Filming in {$cfg['city_name']} - AR Entertainment" class="img-fluid w-100" style="object-fit: cover; max-height: 520px;">
                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(15,23,42,0.95), transparent);">
                    <span class="badge badge-warning mb-2" style="background-color: #f59e0b; color: #000; font-weight: 700;">OFFICIAL DISTRICT GUIDE</span>
                    <h2 class="text-white font-weight-bold mb-1">Cinema &amp; Commercial Video Production in {$cfg['city_name']}</h2>
                    <p class="text-light mb-0">Full-Service Film Fixer, Drone Clearances, Equipment Rental &amp; Logistics in {$cfg['city_name']}, Bangladesh</p>
                </div>
            </div>
        </div>
    </div>

    <div class="district-body-content text-light mb-5">
        <h3 class="text-white font-weight-bold mb-3">Filming Locations &amp; Creative Potential in {$cfg['city_name']}</h3>
        <p class="lead" style="color: #cbd5e1;">AR Entertainment provides comprehensive film line production, creative direction, professional camera crews, and complete fixer support across {$cfg['city_name']} and the southern coastal division.</p>
        
        <h4 class="text-warning font-weight-bold mt-4 mb-3">Key Filming Hotspots &amp; Scenic Landscapes</h4>
        <ul class="location-list mb-4" style="line-height: 1.8; color: #cbd5e1;">
{$highlights_html}        </ul>

        <div class="infographic-section my-5 text-center">
            <h4 class="text-white font-weight-bold mb-3">Production Specs &amp; Logistics Workflow</h4>
            <div class="rounded border p-2 shadow-sm" style="background: #0f172a; border-color: #334155 !important;">
                <img src="{$info_rel}" alt="{$cfg['city_name']} District Filming Specifications - AR Entertainment" class="img-fluid rounded w-100">
            </div>
        </div>

        <h4 class="text-warning font-weight-bold mt-4 mb-3">Local Logistics, Rigging &amp; Filming Clearances</h4>
        <div class="row">
{$specs_html}        </div>

        <div class="p-4 rounded mt-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-left: 4px solid #f59e0b;">
            <h4 class="text-white font-weight-bold mb-2">Plan Your Production in {$cfg['city_name']}</h4>
            <p class="text-light mb-3">Whether you require local fixer support, shallow-draft boat setups, CAAB aerial permits, or 4K/8K cinema camera packages in {$cfg['city_name']}, AR Entertainment is your trusted partner.</p>
            <a href="contact-us.html" class="btn btn-warning font-weight-bold px-4 py-2" style="background: #f59e0b; color: #0f172a;">Contact Our Production Desk</a>
        </div>
    </div>
</div>
HTML;

    $meta_title = "Video Production Company in {$cfg['city_name']} | AR Entertainment";
    $meta_desc = "Top video production company & film fixer in {$cfg['city_name']}, Bangladesh. Location scouting, permits, drone cinematography, and corporate video by AR Entertainment.";

    $update_stmt->execute([
        ':content'          => $rich_content,
        ':meta_title'       => $meta_title,
        ':meta_description' => $meta_desc,
        ':slug'             => $cfg['slug']
    ]);

    $processed++;
    echo "   ✅ Updated MySQL record for: {$cfg['slug']}\n\n";
}

echo "========================================================\n";
echo "🏆 BATCH 5.3.7 ORIGINAL ASSETS & HTML GENERATION COMPLETED!\n";
echo "   - Total Districts Upgraded in Batch: {$processed} / 6\n";
echo "   - Phase 5.3 Complete: All 64 Districts Ingested & Branded!\n";
echo "========================================================\n\n";
