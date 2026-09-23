<?php

/**
 * AR Entertainment - Dynamic Services Catalogue
 * 
 * Displays 42+ specialized production services categorized into 6 major departments:
 * TVC & Commercials, Corporate AV & Brand Films, Film Fixing & Line Production,
 * AI Video Solutions, Animation & Audio Post, and Strategy & Video Marketing.
 * Features real-time filtering, keyword search, dark cinema aesthetics, and Schema.org ItemList.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

// -----------------------------------------------------------------------------
// Department Definitions & Classification Map
// -----------------------------------------------------------------------------
$departments = [
    'tvc-commercials' => [
        'name'  => 'TVC & Commercials',
        'icon'  => 'fa-solid fa-film',
        'desc'  => 'High-impact television and digital video commercials crafted for broadcast and digital streaming.',
        'slugs' => [
            'tv-commercial-ovc-production', 'tv-commercial', 'online-video-commercial',
            'promo-video', 'social-media-video', 'brand-anthem-video',
            'music-video', 'animated-music-video'
        ]
    ],
    'corporate-av' => [
        'name'  => 'Corporate AV & Brand Films',
        'icon'  => 'fa-solid fa-building',
        'desc'  => 'Executive brand films, industrial RMG documentaries, factory tours, and corporate jubilee celebrations.',
        'slugs' => [
            'corporate-av', 'corporate-av-production-company-in-dhaka',
            'corporate-av-production-in-bangladesh', 'corporate-video-for-garment-and-textile-industry-bangladesh',
            'event-video-production', 'interviews', 'training-video',
            'milestone-celebration-video', 'real-estate-video'
        ]
    ],
    'film-fixing' => [
        'name'  => 'Film Fixing & Line Production',
        'icon'  => 'fa-solid fa-globe',
        'desc'  => 'Turnkey line production, Ministry of Information filming permits, NBR customs carnet, and fixer support across Bangladesh.',
        'slugs' => [
            'line-production-film-fixing', 'support-for-international-production',
            'filming-permits-and-visa-guidance-bangladesh', 'drone-video',
            'video-documentation', 'documentary'
        ]
    ],
    'ai-production' => [
        'name'  => 'AI Video Solutions',
        'icon'  => 'fa-solid fa-robot',
        'desc'  => 'Generative AI content creation, neural voice dubbing, lip-sync localization, and corporate digital avatars.',
        'slugs' => [
            'ai-video-content-creation', 'ai-video-localisation-dubbing',
            'ai-training-avatar-video-production', 'ai-music-jingle-brand-anthem-development'
        ]
    ],
    'animation-audio' => [
        'name'  => 'Animation, Audio & Post',
        'icon'  => 'fa-solid fa-wand-magic-sparkles',
        'desc'  => '2D/3D motion graphics, commercial jingle audio, DaVinci ACES color grading, and acoustic mixing.',
        'slugs' => [
            '2d-and-3d-animation', 'animated-explainer-video', 'explainer-video',
            'jingle-production', 'theme-song', 'lyrics-development',
            'service-excellence', 'additional-services'
        ]
    ],
    'strategy-marketing' => [
        'name'  => 'Strategy & Video Marketing',
        'icon'  => 'fa-solid fa-chart-line',
        'desc'  => 'Concept ideation, 2-column AV scripts, visual animatics, YouTube SEO, and performance video marketing.',
        'slugs' => [
            'concept-development', 'script-development', 'storyboard-development',
            'strategic-planning', 'video-tutorials', 'video-marketing',
            'video-description-service-bangladesh'
        ]
    ]
];

// Inverted lookup map: slug -> dept_key
$slug_to_dept = [];
foreach ($departments as $dept_key => $dept_info) {
    foreach ($dept_info['slugs'] as $slug) {
        $slug_to_dept[$slug] = $dept_key;
    }
}

// -----------------------------------------------------------------------------
// Request Parameters & Filtering
// -----------------------------------------------------------------------------
$selected_dept  = trim($_GET['dept'] ?? '');
$search_query   = trim($_GET['q'] ?? '');

// Validate selected department
if (!empty($selected_dept) && !isset($departments[$selected_dept])) {
    $selected_dept = '';
}

// -----------------------------------------------------------------------------
// Fetch All Active Services from Database
// -----------------------------------------------------------------------------
try {
    $services_stmt = db()->query("
        SELECT id, title, slug, icon, short_summary, pricing_note, sort_order 
        FROM services 
        WHERE status = 'active' 
        ORDER BY sort_order ASC, id ASC
    ");
    $all_services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $all_services = [];
}

// Calculate Department Service Counts
$dept_counts = [
    'all' => count($all_services)
];
foreach (array_keys($departments) as $k) {
    $dept_counts[$k] = 0;
}
foreach ($all_services as $svc) {
    $dept_k = $slug_to_dept[$svc['slug']] ?? null;
    if ($dept_k && isset($dept_counts[$dept_k])) {
        $dept_counts[$dept_k]++;
    }
}

// Filter Services based on Department & Search Query
$filtered_services = [];
foreach ($all_services as $svc) {
    $svc_dept = $slug_to_dept[$svc['slug']] ?? '';
    
    // Department Filter
    if (!empty($selected_dept) && $svc_dept !== $selected_dept) {
        continue;
    }
    
    // Search Query Filter
    if (!empty($search_query)) {
        $haystack = strtolower($svc['title'] . ' ' . $svc['short_summary'] . ' ' . $svc['pricing_note']);
        if (strpos($haystack, strtolower($search_query)) === false) {
            continue;
        }
    }
    
    $filtered_services[] = $svc;
}

// -----------------------------------------------------------------------------
// Service Media Resolution Helper
// -----------------------------------------------------------------------------
function get_service_card_image(string $slug): string
{
    $map = [
        'tv-commercial-ovc-production' => 'ar-tv-commercial-production-hero.avif',
        'line-production-film-fixing'  => 'ar-intl-production-support-hero.avif',
        'tv-commercial'                => 'ar-tv-commercial-production-hero.avif',
        'online-video-commercial'       => 'ar-online-video-commercial-hero.avif',
        'corporate-av'                 => 'ar-corporate-av-production-hero.avif',
        'documentary'                  => 'ar-documentary-production-hero.avif',
        'theme-song'                   => 'ar-theme-song-production-hero.avif',
        'music-video'                  => 'ar-music-video-production-hero.avif',
        '2d-and-3d-animation'          => 'ar-animation-vfx-production-hero.avif',
        'explainer-video'              => 'ar-explainer-video-production-hero.avif',
        'ai-video-content-creation'    => 'ar-ai-video-creation-hero.avif',
        'ai-video-localisation-dubbing'=> 'ar-ai-localisation-dubbing-hero.avif',
        'ai-training-avatar-video-production' => 'ar-ai-training-avatar-hero.avif',
        'ai-music-jingle-brand-anthem-development' => 'ar-ai-music-jingle-hero.avif',
        'jingle-production'            => 'ar-jingle-production-hero.avif',
        'lyrics-development'           => 'ar-lyrics-development-hero.avif',
        'brand-anthem-video'           => 'ar-brand-anthem-video-hero.avif',
        'animated-music-video'         => 'ar-animated-music-video-hero.avif',
        'support-for-international-production' => 'ar-intl-production-support-hero.avif',
        'filming-permits-and-visa-guidance-bangladesh' => 'ar-filming-permits-visa-hero.avif',
        'drone-video'                  => 'ar-drone-aerial-cinematography-hero.avif',
        'corporate-video-for-garment-and-textile-industry-bangladesh' => 'ar-rmg-textile-video-hero.avif',
        'event-video-production'       => 'ar-event-video-production-hero.avif',
        'real-estate-video'            => 'ar-real-estate-video-hero.avif',
        'interviews'                   => 'ar-executive-interviews-hero.avif',
        'video-documentation'          => 'ar-video-documentation-hero.avif',
        'promo-video'                  => 'ar-promo-video-hero.avif',
        'training-video'               => 'ar-training-video-hero.avif',
        'milestone-celebration-video'  => 'ar-milestone-celebration-video-hero.avif',
        'social-media-video'           => 'ar-social-media-video-hero.avif',
        'concept-development'          => 'ar-concept-development-hero.avif',
        'script-development'           => 'ar-script-development-hero.avif',
        'storyboard-development'       => 'ar-storyboard-development-hero.avif',
        'strategic-planning'           => 'ar-strategic-planning-hero.avif',
        'video-tutorials'              => 'ar-video-tutorials-hero.avif',
        'video-marketing'              => 'ar-video-marketing-hero.avif',
        'video-description-service-bangladesh' => 'ar-video-description-seo-hero.avif',
        'corporate-av-production-company-in-dhaka' => 'ar-dhaka-corporate-av-hero.avif',
        'corporate-av-production-in-bangladesh' => 'ar-nationwide-corporate-av-hero.avif',
        'service-excellence'           => 'ar-service-excellence-hero.avif',
        'additional-services'          => 'ar-additional-services-hero.avif',
        'animated-explainer-video'     => 'ar-technical-explainer-video-hero.avif'
    ];

    $filename = $map[$slug] ?? 'ar-tv-commercial-production-hero.avif';
    $path = ROOT_PATH . '/uploads/services/' . $filename;
    if (file_exists($path)) {
        return upload_url('services/' . $filename);
    }
    return upload_url('images/featured/commercial-video-production.jpg');
}

// -----------------------------------------------------------------------------
// SEO Meta Information
// -----------------------------------------------------------------------------
if (!empty($selected_dept)) {
    $dept_name = $departments[$selected_dept]['name'];
    $page_title       = "{$dept_name} Services Bangladesh | AR Entertainment";
    $page_description = "Explore specialized {$dept_name} services in Bangladesh by AR Entertainment. Professional film production, line fixing, cinema equipment and broadcast delivery.";
} elseif (!empty($search_query)) {
    $page_title       = "Production Services Search: \"" . htmlspecialchars($search_query) . "\" | AR Entertainment";
    $page_description = "Search results for \"{$search_query}\" across AR Entertainment's full catalogue of 42+ video production and film fixer services in Bangladesh.";
} else {
    $page_title       = 'Video Production & Film Fixer Services Bangladesh | AR Entertainment';
    $page_description = 'Explore 42+ specialized video production and film fixer services in Bangladesh. TV commercials, corporate films, international line production, AI video, and post-production.';
}

$page_keywords = 'Video Production Services Bangladesh, TV Commercial Production, Corporate AV Dhaka, Film Fixer Bangladesh, International Line Production, AI Video Content, Drone Cinematography CAAB';
$current_page  = 'services';
$canonical_url = site_url('services' . (!empty($selected_dept) ? '?dept=' . urlencode($selected_dept) : ''));

// Helper to preserve active query parameters in filter links
function build_services_url(array $overrides = []): string
{
    $params = [];
    if (!empty($_GET['dept'])) {
        $params['dept'] = $_GET['dept'];
    }
    if (!empty($_GET['q'])) {
        $params['q'] = $_GET['q'];
    }

    foreach ($overrides as $k => $v) {
        if ($v === null || $v === '') {
            unset($params[$k]);
        } else {
            $params[$k] = $v;
        }
    }

    $qs = http_build_query($params);
    return site_url('services' . ($qs ? '?' . $qs : ''));
}

// Schema.org Structured Data (WebPage + BreadcrumbList + ItemList)
$item_list_elements = [];
$pos = 1;
foreach ($filtered_services as $s) {
    $item_list_elements[] = [
        '@type'    => 'ListItem',
        'position' => $pos++,
        'name'     => $s['title'],
        'url'      => site_url('services/' . $s['slug'])
    ];
}

$page_schema = [
    [
        '@type' => 'WebPage',
        '@id'   => site_url('services') . '#webpage',
        'url'   => site_url('services'),
        'name'  => $page_title,
        'description' => $page_description,
        'isPartOf' => ['@id' => site_url() . '#website'],
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => site_url()
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Services',
                    'item' => site_url('services')
                ]
            ]
        ]
    ],
    [
        '@type' => 'ItemList',
        'name'  => 'AR Entertainment Production Services Catalogue',
        'itemListElement' => $item_list_elements
    ]
];

// Universal Header & Navigation Partials
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Scoped Services Catalogue Stylesheet -->
<style>
    /* Balanced Hero Banner Overrides */
    .inner-banner-area,
    .inner-banner-area .inner-banner,
    .inner-banner-area .banner-content {
        height: auto !important;
        min-height: 0 !important;
        max-height: none !important;
        display: block !important;
    }
    .inner-banner-area .banner-content {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Department Filter Pills (Matching blog.php) */
    .dept-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 25px;
        font-weight: 600;
        padding: 8px 18px;
        font-size: 13.5px;
        text-decoration: none !important;
        transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
        background: #181b24;
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #d1d5db !important;
    }
    .dept-pill:hover {
        background: #232838 !important;
        border-color: rgba(229, 9, 20, 0.7) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(229, 9, 20, 0.25);
    }
    .dept-pill:hover .badge {
        background: rgba(229, 9, 20, 0.3) !important;
        color: #ff6b6b !important;
    }
    .dept-pill.active {
        background: #e50914 !important;
        border-color: #e50914 !important;
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(229, 9, 20, 0.35);
    }
    .dept-pill.active:hover {
        background: #ff1f2d !important;
        border-color: #ff1f2d !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(229, 9, 20, 0.5);
    }
    .dept-pill .badge {
        border-radius: 12px;
        font-size: 11px;
        padding: 3px 8px;
        font-weight: 700;
        transition: all 0.25s ease;
    }

    /* Cinema Service Card */
    .service-cinema-card {
        background: #161922;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.35s ease;
    }
    .service-cinema-card:hover {
        transform: translateY(-6px);
        border-color: rgba(229, 9, 20, 0.5);
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.6), 0 0 20px rgba(229, 9, 20, 0.15);
    }
    .service-card-thumb-wrap {
        height: 210px;
        position: relative;
        overflow: hidden;
        background: #0d0f14;
    }
    .service-card-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .service-cinema-card:hover .service-card-thumb-wrap img {
        transform: scale(1.06);
    }
    .service-dept-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        background: rgba(10, 12, 18, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f3f4f6;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 10px;
        border-radius: 4px;
        z-index: 2;
    }
    .service-card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .service-card-title {
        font-size: 19px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 12px;
        color: #ffffff;
    }
    .service-card-title a {
        color: #ffffff;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .service-card-title a:hover {
        color: #e50914;
    }
    .service-card-text {
        font-size: 14px;
        line-height: 1.65;
        color: #94a3b8;
        margin-bottom: 18px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }
    .service-pricing-pill {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 12.5px;
        color: #cbd5e1;
        line-height: 1.4;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .service-pricing-pill strong {
        color: #e50914;
    }
    .service-card-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        padding-top: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .service-explore-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }
    .service-explore-btn i {
        color: #e50914;
        font-size: 13px;
        transition: transform 0.25s ease;
    }
    .service-cinema-card:hover .service-explore-btn {
        color: #e50914;
    }
    .service-cinema-card:hover .service-explore-btn i {
        transform: translateX(4px);
    }
    .service-card-icon-tag {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(229, 9, 20, 0.12);
        color: #e50914;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
</style>

<!-- ========================================================================= -->
<!-- 1. HERO BANNER & BREADCRUMB                                               -->
<!-- ========================================================================= -->
<section class="inner-banner-area">
    <div class="inner-banner" style="background-image: url('<?= site_url('images/banner/services.jpg') ?>'); background-size: cover; background-position: center; position: relative; padding-top: 155px !important; padding-bottom: 65px !important;">
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(10,12,18,0.78) 0%, rgba(10,12,18,0.95) 100%);"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="banner-content text-center">
                <nav class="breadcrumb-nav mb-3" aria-label="Breadcrumb">
                    <ul class="d-flex align-items-center justify-content-center list-unstyled mb-0" style="gap: 10px; font-size: 14px;">
                        <li><a href="<?= site_url() ?>" style="color: #9ca3af; text-decoration: none;"><i class="fa fa-home mr-1"></i> Home</a></li>
                        <li style="color: #6b7280; user-select: none;">/</li>
                        <?php if ($selected_dept): ?>
                            <li><a href="<?= site_url('services') ?>" style="color: #9ca3af; text-decoration: none;">Services</a></li>
                            <li style="color: #6b7280; user-select: none;">/</li>
                            <li class="active" style="color: #e50914; font-weight: 700;"><?= htmlspecialchars($departments[$selected_dept]['name']) ?></li>
                        <?php elseif (!empty($search_query)): ?>
                            <li><a href="<?= site_url('services') ?>" style="color: #9ca3af; text-decoration: none;">Services</a></li>
                            <li style="color: #6b7280; user-select: none;">/</li>
                            <li class="active" style="color: #e50914; font-weight: 700;">Search: <?= htmlspecialchars($search_query) ?></li>
                        <?php else: ?>
                            <li class="active" style="color: #e50914; font-weight: 700;">Production Services</li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <h1 class="text-white font-weight-bold display-4 mb-3" style="letter-spacing: -0.5px;">
                    <?= ($selected_dept) ? htmlspecialchars($departments[$selected_dept]['name']) : 'Full-Spectrum Video Production &amp; Film Fixing' ?>
                </h1>
                <p class="lead text-light mx-auto mb-0" style="max-width: 820px; font-size: 17px; line-height: 1.6; color: #d1d5db;">
                    <?= ($selected_dept) 
                        ? htmlspecialchars($departments[$selected_dept]['desc']) 
                        : 'Explore 42+ production services spanning TV commercials, industrial RMG corporate videos, international film fixing, generative AI video, and high-end DaVinci color grading across Bangladesh.' 
                    ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 2. FILTER & SEARCH CONTROL BAR                                            -->
<!-- ========================================================================= -->
<section class="services-filters-section" style="background: #0d0f17; border-bottom: 1px solid rgba(255,255,255,0.06); padding-top: 26px !important; padding-bottom: 26px !important;">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <!-- Department Navigation Pills (Flex Wrap) -->
            <div class="col-lg-8 col-12 mb-3 mb-lg-0">
                <div class="category-pills d-flex flex-wrap align-items-center" style="gap: 12px 10px;">
                    <!-- All Services Pill -->
                    <?php $all_active = empty($selected_dept); ?>
                    <a href="<?= build_services_url(['dept' => '']) ?>" class="dept-pill <?= $all_active ? 'active' : '' ?>">
                        <i class="fa-solid fa-layer-group mr-2"></i> All Services
                        <span class="badge ml-2" style="<?= $all_active ? 'background: rgba(255,255,255,0.25); color: #ffffff;' : 'background: #252836; color: #9ca3af;' ?>"><?= $dept_counts['all'] ?></span>
                    </a>

                    <!-- Specific Department Pills -->
                    <?php foreach ($departments as $dept_k => $d_info): 
                        $is_active = ($selected_dept === $dept_k);
                    ?>
                        <a href="<?= build_services_url(['dept' => $dept_k]) ?>" class="dept-pill <?= $is_active ? 'active' : '' ?>">
                            <i class="<?= $d_info['icon'] ?> mr-2"></i> <?= htmlspecialchars($d_info['name']) ?>
                            <span class="badge ml-2" style="<?= $is_active ? 'background: rgba(255,255,255,0.25); color: #ffffff;' : 'background: #252836; color: #9ca3af;' ?>"><?= $dept_counts[$dept_k] ?? 0 ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Keyword Search Form -->
            <div class="col-lg-4 col-12">
                <form action="<?= site_url('services') ?>" method="GET" class="d-flex align-items-center position-relative">
                    <?php if (!empty($selected_dept)): ?>
                        <input type="hidden" name="dept" value="<?= htmlspecialchars($selected_dept) ?>">
                    <?php endif; ?>
                    <input type="text" 
                           name="q" 
                           value="<?= htmlspecialchars($search_query) ?>" 
                           placeholder="Search production services..." 
                           class="form-control" 
                           style="background: #181b24; border: 1px solid rgba(255,255,255,0.18); border-radius: 25px; color: #fff; padding-left: 20px; padding-right: 48px; font-size: 14px; height: 44px; outline: none; box-shadow: none;">
                    <button type="submit" 
                            style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; border-radius: 50%; background: #e50914; border: none; color: #ffffff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s ease, transform 0.2s ease; box-shadow: 0 2px 8px rgba(229,9,20,0.4);" 
                            onmouseover="this.style.background='#ff2b37'; this.style.transform='translateY(-50%) scale(1.05)';" 
                            onmouseout="this.style.background='#e50914'; this.style.transform='translateY(-50%) scale(1.0)';"
                            aria-label="Submit Search">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 13px;"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 3. SERVICES CATALOGUE GRID                                                -->
<!-- ========================================================================= -->
<section class="common-sec" style="background: #0d0f14; min-height: 600px;">
    <div class="container">
        <!-- Results Counter & Reset Trigger -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.06) !important;">
            <div>
                <span class="text-white font-weight-bold" style="font-size: 18px;">
                    Showing <?= count($filtered_services) ?> Production Services
                </span>
                <?php if ($selected_dept || !empty($search_query)): ?>
                    <span class="text-muted ml-2" style="font-size: 14px;">
                        (Filtered<?php if ($selected_dept): ?> by <strong><?= htmlspecialchars($departments[$selected_dept]['name']) ?></strong><?php endif; ?><?php if (!empty($search_query)): ?> matching "<strong><?= htmlspecialchars($search_query) ?></strong>"<?php endif; ?>)
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($selected_dept || !empty($search_query)): ?>
                <div>
                    <a href="<?= site_url('services') ?>" class="text-danger font-weight-bold" style="font-size: 13.5px; text-decoration: none;">
                        <i class="fa fa-refresh mr-1"></i> Reset Filters
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($filtered_services)): ?>
            <div class="row">
                <?php foreach ($filtered_services as $svc): ?>
                    <?php
                        $dept_k   = $slug_to_dept[$svc['slug']] ?? 'tvc-commercials';
                        $dept_obj = $departments[$dept_k] ?? null;
                        $svc_img  = get_service_card_image($svc['slug']);
                        $svc_url  = site_url('services/' . $svc['slug']);
                    ?>
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="service-cinema-card">
                            <!-- Card Thumbnail with Aspect Ratio -->
                            <div class="service-card-thumb-wrap">
                                <span class="service-dept-badge">
                                    <i class="<?= $dept_obj['icon'] ?? 'fa-solid fa-video' ?> mr-1" style="color: #e50914;"></i>
                                    <?= htmlspecialchars($dept_obj['name'] ?? 'Production') ?>
                                </span>
                                <a href="<?= $svc_url ?>">
                                    <img src="<?= $svc_img ?>" alt="<?= htmlspecialchars($svc['title']) ?>" loading="lazy">
                                </a>
                            </div>

                            <!-- Card Body -->
                            <div class="service-card-body">
                                <h3 class="service-card-title">
                                    <a href="<?= $svc_url ?>"><?= htmlspecialchars($svc['title']) ?></a>
                                </h3>

                                <p class="service-card-text">
                                    <?= htmlspecialchars(strip_tags($svc['short_summary'] ?? '')) ?>
                                </p>

                                <?php if (!empty($svc['pricing_note'])): ?>
                                    <div class="service-pricing-pill" title="<?= htmlspecialchars($svc['pricing_note']) ?>">
                                        <i class="fa-solid fa-tag mr-1 text-danger"></i>
                                        <strong>Guide:</strong> <?= htmlspecialchars($svc['pricing_note']) ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Card Footer -->
                                <div class="service-card-footer mt-auto">
                                    <a href="<?= $svc_url ?>" class="service-explore-btn">
                                        Explore Service <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                    <div class="service-card-icon-tag" title="<?= htmlspecialchars($svc['title']) ?>">
                                        <i class="<?= htmlspecialchars($svc['icon'] ?? 'fa-solid fa-video') ?>"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Empty Search Results State -->
            <div class="text-center py-5" style="background: #161922; border-radius: 12px; border: 1px dashed rgba(255,255,255,0.1); margin: 30px 0;">
                <div style="font-size: 48px; color: #e50914; margin-bottom: 16px;">
                    <i class="fa-solid fa-film"></i>
                </div>
                <h3 class="text-white font-weight-bold mb-2">No Matching Services Found</h3>
                <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
                    We could not find any services matching your criteria. Try browsing across other departments or reset your search query.
                </p>
                <a href="<?= site_url('services') ?>" class="btn common-btn">View All 42+ Services<span></span></a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 4. DIRECTOR AUTHORITY & PRODUCTION INFRASTRUCTURE                         -->
<!-- ========================================================================= -->
<section class="common-sec" style="background: #11131a; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Production Infrastructure</div>
                <h2 class="common-heading text-white mb-3">Cinema-Grade Execution Across 64 Districts</h2>
                <p class="text-muted" style="line-height: 1.7; font-size: 15px;">
                    Founded in 2018 under the creative leadership of Founder and Film Director <strong>Azizul Hoque Shiplu</strong> (working in advertising and commercial filmmaking since 2007), AR Entertainment operates as a full-tier audio-visual powerhouse in Dhaka.
                </p>
                <p class="text-muted" style="line-height: 1.7; font-size: 15px;">
                    Having personally directed over 100 TVCs, OVCs, and corporate documentaries, our team combines elite creative storytelling with institutional production discipline. We have proudly facilitated international crews from the <strong>United States, United Kingdom, France, UAE, Romania, Malaysia, Belgium, and India</strong>.
                </p>
                <div class="row mt-4">
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-camera text-danger mr-3" style="font-size: 24px;"></i>
                            <div>
                                <h4 class="text-white font-weight-bold mb-0" style="font-size: 15px;">RED &amp; ARRI Rigs</h4>
                                <span class="text-muted" style="font-size: 12.5px;">6K &amp; 8K RAW cinema suites</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-helicopter text-danger mr-3" style="font-size: 24px;"></i>
                            <div>
                                <h4 class="text-white font-weight-bold mb-0" style="font-size: 15px;">CAAB Drone Permits</h4>
                                <span class="text-muted" style="font-size: 12.5px;">Licensed heavy-lift aerials</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-passport text-danger mr-3" style="font-size: 24px;"></i>
                            <div>
                                <h4 class="text-white font-weight-bold mb-0" style="font-size: 15px;">Ministry Clearances</h4>
                                <span class="text-muted" style="font-size: 12.5px;">FF visas &amp; ATA carnet support</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-sliders text-danger mr-3" style="font-size: 24px;"></i>
                            <div>
                                <h4 class="text-white font-weight-bold mb-0" style="font-size: 15px;">DaVinci ACES Grading</h4>
                                <span class="text-muted" style="font-size: 12.5px;">Hollywood broadcast finishing</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative text-center">
                    <img src="<?= site_url('images/video-production-house-in-bangladesh.webp') ?>" alt="AR Entertainment Video Production House Dhaka" class="img-fluid rounded shadow-lg" style="border: 1px solid rgba(255,255,255,0.1); max-height: 440px; width: 100%; object-fit: cover;">
                    <div class="p-3" style="position: absolute; bottom: 20px; right: 20px; background: rgba(10,12,18,0.9); backdrop-filter: blur(10px); border: 1px solid rgba(229,9,20,0.5); border-radius: 8px; text-align: left;">
                        <div style="font-size: 18px; font-weight: 800; color: #e50914;">100+ Commercials</div>
                        <div style="font-size: 12px; color: #cbd5e1;">Directed by Azizul Hoque Shiplu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 5. UNIVERSAL PRODUCTION FAQS ACCORDION                                     -->
<!-- ========================================================================= -->
<section class="common-sec" style="background: #0d0f14;">
    <div class="container">
        <div class="text-center mb-5">
            <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px;">Clear Answers</div>
            <h2 class="common-heading text-white">Frequently Asked Questions</h2>
            <p class="text-muted mx-auto" style="max-width: 650px; font-size: 15px;">
                Transparent guidance on production timelines, pricing structures, equipment standards, and international line production in Bangladesh.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion" id="servicesGlobalFaq">
                    <!-- FAQ 1 -->
                    <div class="card mb-3" style="background: #161922; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden;">
                        <div class="card-header p-0" id="gfaqHeading1" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3" type="button" data-toggle="collapse" data-target="#gfaqCollapse1" aria-expanded="true" aria-controls="gfaqCollapse1" style="text-decoration: none; font-weight: 700; font-size: 15.5px;">
                                <span>What is the standard production timeline for a commercial or corporate video?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="gfaqCollapse1" class="collapse show" aria-labelledby="gfaqHeading1" data-parent="#servicesGlobalFaq">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                Standard digital video ad campaigns and corporate interviews typically take <strong>7 to 14 business days</strong> from brief approval to final delivery. Elaborate broadcast TVCs, factory RMG brand films, and multi-location documentaries generally require <strong>3 to 5 weeks</strong> to allow for comprehensive storyboarding, casting, shooting, color grading, sound mixing, and broadcast clearances.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="card mb-3" style="background: #161922; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden;">
                        <div class="card-header p-0" id="gfaqHeading2" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3 collapsed" type="button" data-toggle="collapse" data-target="#gfaqCollapse2" aria-expanded="false" aria-controls="gfaqCollapse2" style="text-decoration: none; font-weight: 700; font-size: 15.5px;">
                                <span>Do you provide international line production and film fixer support for foreign crews?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="gfaqCollapse2" class="collapse" aria-labelledby="gfaqHeading2" data-parent="#servicesGlobalFaq">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                Yes. AR Entertainment is Bangladesh's premier fixer partner for international broadcasters and commercial agencies. We manage <strong>Ministry of Information filming permits, FF journalist visas, NBR customs carnet clearance at Dhaka Airport (DAC), CAAB drone licenses</strong>, bilingual crew, secure production transport, and remote district location scouting.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="card mb-3" style="background: #161922; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden;">
                        <div class="card-header p-0" id="gfaqHeading3" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3 collapsed" type="button" data-toggle="collapse" data-target="#gfaqCollapse3" aria-expanded="false" aria-controls="gfaqCollapse3" style="text-decoration: none; font-weight: 700; font-size: 15.5px;">
                                <span>How do you structure production budgets and pricing?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="gfaqCollapse3" class="collapse" aria-labelledby="gfaqHeading3" data-parent="#servicesGlobalFaq">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                We provide transparent, itemized milestone-based quotes with zero hidden surcharges. Pricing is customized based on camera package tier (RED/ARRI vs Sony Cinema FX), lighting rigs, shooting days, location logistics, talent casting fees, and animation/VFX complexity.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="card mb-3" style="background: #161922; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden;">
                        <div class="card-header p-0" id="gfaqHeading4" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3 collapsed" type="button" data-toggle="collapse" data-target="#gfaqCollapse4" aria-expanded="false" aria-controls="gfaqCollapse4" style="text-decoration: none; font-weight: 700; font-size: 15.5px;">
                                <span>What cinema cameras, lighting, and audio equipment do you deploy?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="gfaqCollapse4" class="collapse" aria-labelledby="gfaqHeading4" data-parent="#servicesGlobalFaq">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                Our in-house equipment inventory includes <strong>RED V-Raptor / Komodo, ARRI Alexa Mini LF, Sony FX9/FX6/FX3</strong>, Cooke &amp; Zeiss cinema primes, DJI Inspire 3 heavy-lift aerial drones, Aputure Electro Storm &amp; Nanlux LED lighting, and Sennheiser MKH 416 boom audio setups.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="card mb-3" style="background: #161922; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden;">
                        <div class="card-header p-0" id="gfaqHeading5" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3 collapsed" type="button" data-toggle="collapse" data-target="#gfaqCollapse5" aria-expanded="false" aria-controls="gfaqCollapse5" style="text-decoration: none; font-weight: 700; font-size: 15.5px;">
                                <span>Can you handle AI video dubbing, lip-syncing, and international localization?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="gfaqCollapse5" class="collapse" aria-labelledby="gfaqHeading5" data-parent="#servicesGlobalFaq">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                Absolutely. Our AI production lab specializes in human-in-the-loop neural voice cloning, automated phoneme lip-sync matching, and multilingual audio dubbing into English, Arabic, Spanish, French, German, and Hindi for global multi-market advertising campaigns.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 6. HIGH-CONVERSION INQUIRY CTA                                            -->
<!-- ========================================================================= -->
<section class="common-sec cta-sec" style="background: #11131a; border-top: 1px solid rgba(255,255,255,0.06);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px;">Let's Produce Together</div>
                <h2 class="display-4 font-weight-bold text-white mb-3" style="letter-spacing: -0.5px;">Ready to Bring Your Vision to Life?</h2>
                <p class="lead mb-4" style="color: #cbd5e1; font-size: 16px; line-height: 1.7;">
                    Whether you require a nationwide TV commercial campaign, a corporate documentary, or specialized international film fixer support in Dhaka, our executive production desk is ready to advise.
                </p>
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                    <a href="<?= site_url('contact-us') ?>" class="btn common-btn m-2">
                        Request a Production Quote<span></span>
                    </a>
                    <a href="tel:<?= preg_replace('/[^\d+]/', '', get_setting('contact_phone', CONTACT_PHONE)) ?>" class="btn common-btn m-2" style="background: transparent; border: 1px solid #e50914;">
                        <i class="fa-solid fa-phone mr-2" style="color: #e50914;"></i> Call Production Desk<span></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include Universal Footer Partial
require_once __DIR__ . '/includes/footer.php';
?>
