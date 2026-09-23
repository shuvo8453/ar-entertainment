<?php

/**
 * AR Entertainment - Dynamic Single Service Template
 * 
 * Renders full production specifications, workflow diagrams, rich editorial content,
 * technical equipment capabilities, interactive FAQ accordions, and a sticky project
 * inquiry sidebar with direct phone/WhatsApp triggers.
 * Includes automated Schema.org Service, BreadcrumbList, and FAQPage JSON-LD.
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
        'slugs' => [
            'tv-commercial-ovc-production', 'tv-commercial', 'online-video-commercial',
            'promo-video', 'social-media-video', 'brand-anthem-video',
            'music-video', 'animated-music-video'
        ]
    ],
    'corporate-av' => [
        'name'  => 'Corporate AV & Brand Films',
        'icon'  => 'fa-solid fa-building',
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
        'slugs' => [
            'line-production-film-fixing', 'support-for-international-production',
            'filming-permits-and-visa-guidance-bangladesh', 'drone-video',
            'video-documentation', 'documentary'
        ]
    ],
    'ai-production' => [
        'name'  => 'AI Video Solutions',
        'icon'  => 'fa-solid fa-robot',
        'slugs' => [
            'ai-video-content-creation', 'ai-video-localisation-dubbing',
            'ai-training-avatar-video-production', 'ai-music-jingle-brand-anthem-development'
        ]
    ],
    'animation-audio' => [
        'name'  => 'Animation, Audio & Post',
        'icon'  => 'fa-solid fa-wand-magic-sparkles',
        'slugs' => [
            '2d-and-3d-animation', 'animated-explainer-video', 'explainer-video',
            'jingle-production', 'theme-song', 'lyrics-development',
            'service-excellence', 'additional-services'
        ]
    ],
    'strategy-marketing' => [
        'name'  => 'Strategy & Video Marketing',
        'icon'  => 'fa-solid fa-chart-line',
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
// Request Validation & Database Retrieval
// -----------------------------------------------------------------------------
$slug = trim($_GET['slug'] ?? '');
if (empty($slug) && isset($_SERVER['REQUEST_URI'])) {
    $uri_parts = explode('?', $_SERVER['REQUEST_URI'])[0];
    if (preg_match('#/services/([a-zA-Z0-9_-]+)/?$#', $uri_parts, $matches)) {
        $slug = $matches[1];
    }
}

// Clean slug
$slug = preg_replace('/[^a-zA-Z0-9_-]/', '', $slug);

$service = null;
if (!empty($slug)) {
    try {
        $stmt = db()->prepare("
            SELECT * 
            FROM services 
            WHERE slug = :slug AND status = 'active' 
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $service = null;
    }
}

// -----------------------------------------------------------------------------
// 404 Error Handling
// -----------------------------------------------------------------------------
if (!$service) {
    http_response_code(404);
    $page_title       = 'Service Not Found | AR Entertainment';
    $page_description = 'The production service you requested could not be located in our catalogue.';
    $canonical_url    = site_url('services');

    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/navbar.php';
    ?>
    <section class="common-sec text-center py-5" style="background: #0d0f14; min-height: 550px; display: flex; align-items: center;">
        <div class="container">
            <div style="font-size: 64px; color: #e50914; margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h1 class="text-white font-weight-bold mb-3">Service Not Found</h1>
            <p class="text-muted mx-auto mb-4" style="max-width: 520px; font-size: 16px; line-height: 1.6;">
                The production service you are looking for may have been moved, renamed, or is currently inactive. Please explore our full directory of 42+ active services.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?= site_url('services') ?>" class="btn common-btn">Browse All Services<span></span></a>
                <a href="<?= site_url('contact-us') ?>" class="btn common-btn" style="background: transparent; border: 1px solid #e50914;">Contact Production Desk<span></span></a>
            </div>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// -----------------------------------------------------------------------------
// Resolve Media & Department
// -----------------------------------------------------------------------------
$dept_key = $slug_to_dept[$service['slug']] ?? 'tvc-commercials';
$department = $departments[$dept_key] ?? [
    'name' => 'Video Production',
    'icon' => 'fa-solid fa-film'
];

$media_map = [
    'tv-commercial-ovc-production' => ['hero' => 'ar-tv-commercial-production-hero.avif', 'wf' => 'ar-tv-commercial-production-workflow.avif'],
    'line-production-film-fixing'  => ['hero' => 'ar-intl-production-support-hero.avif', 'wf' => 'ar-intl-production-support-workflow.avif'],
    'tv-commercial'                => ['hero' => 'ar-tv-commercial-production-hero.avif', 'wf' => 'ar-tv-commercial-production-workflow.avif'],
    'online-video-commercial'       => ['hero' => 'ar-online-video-commercial-hero.avif', 'wf' => 'ar-online-video-commercial-workflow.avif'],
    'corporate-av'                 => ['hero' => 'ar-corporate-av-production-hero.avif', 'wf' => 'ar-corporate-av-production-workflow.avif'],
    'documentary'                  => ['hero' => 'ar-documentary-production-hero.avif', 'wf' => 'ar-documentary-production-workflow.avif'],
    'theme-song'                   => ['hero' => 'ar-theme-song-production-hero.avif', 'wf' => 'ar-theme-song-production-workflow.avif'],
    'music-video'                  => ['hero' => 'ar-music-video-production-hero.avif', 'wf' => 'ar-music-video-production-workflow.avif'],
    '2d-and-3d-animation'          => ['hero' => 'ar-animation-vfx-production-hero.avif', 'wf' => 'ar-animation-vfx-production-workflow.avif'],
    'explainer-video'              => ['hero' => 'ar-explainer-video-production-hero.avif', 'wf' => 'ar-explainer-video-production-workflow.avif'],
    'ai-video-content-creation'    => ['hero' => 'ar-ai-video-creation-hero.avif', 'wf' => 'ar-ai-video-creation-workflow.avif'],
    'ai-video-localisation-dubbing'=> ['hero' => 'ar-ai-localisation-dubbing-hero.avif', 'wf' => 'ar-ai-localisation-dubbing-workflow.avif'],
    'ai-training-avatar-video-production' => ['hero' => 'ar-ai-training-avatar-hero.avif', 'wf' => 'ar-ai-training-avatar-workflow.avif'],
    'ai-music-jingle-brand-anthem-development' => ['hero' => 'ar-ai-music-jingle-hero.avif', 'wf' => 'ar-ai-music-jingle-workflow.avif'],
    'jingle-production'            => ['hero' => 'ar-jingle-production-hero.avif', 'wf' => 'ar-jingle-production-workflow.avif'],
    'lyrics-development'           => ['hero' => 'ar-lyrics-development-hero.avif', 'wf' => 'ar-lyrics-development-workflow.avif'],
    'brand-anthem-video'           => ['hero' => 'ar-brand-anthem-video-hero.avif', 'wf' => 'ar-brand-anthem-video-workflow.avif'],
    'animated-music-video'         => ['hero' => 'ar-animated-music-video-hero.avif', 'wf' => 'ar-animated-music-video-workflow.avif'],
    'support-for-international-production' => ['hero' => 'ar-intl-production-support-hero.avif', 'wf' => 'ar-intl-production-support-workflow.avif'],
    'filming-permits-and-visa-guidance-bangladesh' => ['hero' => 'ar-filming-permits-visa-hero.avif', 'wf' => 'ar-filming-permits-visa-workflow.avif'],
    'drone-video'                  => ['hero' => 'ar-drone-aerial-cinematography-hero.avif', 'wf' => 'ar-drone-aerial-cinematography-workflow.avif'],
    'corporate-video-for-garment-and-textile-industry-bangladesh' => ['hero' => 'ar-rmg-textile-video-hero.avif', 'wf' => 'ar-rmg-textile-video-workflow.avif'],
    'event-video-production'       => ['hero' => 'ar-event-video-production-hero.avif', 'wf' => 'ar-event-video-production-workflow.avif'],
    'real-estate-video'            => ['hero' => 'ar-real-estate-video-hero.avif', 'wf' => 'ar-real-estate-video-workflow.avif'],
    'interviews'                   => ['hero' => 'ar-executive-interviews-hero.avif', 'wf' => 'ar-executive-interviews-workflow.avif'],
    'video-documentation'          => ['hero' => 'ar-video-documentation-hero.avif', 'wf' => 'ar-video-documentation-workflow.avif'],
    'promo-video'                  => ['hero' => 'ar-promo-video-hero.avif', 'wf' => 'ar-promo-video-workflow.avif'],
    'training-video'               => ['hero' => 'ar-training-video-hero.avif', 'wf' => 'ar-training-video-workflow.avif'],
    'milestone-celebration-video'  => ['hero' => 'ar-milestone-celebration-video-hero.avif', 'wf' => 'ar-milestone-celebration-video-workflow.avif'],
    'social-media-video'           => ['hero' => 'ar-social-media-video-hero.avif', 'wf' => 'ar-social-media-video-workflow.avif'],
    'concept-development'          => ['hero' => 'ar-concept-development-hero.avif', 'wf' => 'ar-concept-development-workflow.avif'],
    'script-development'           => ['hero' => 'ar-script-development-hero.avif', 'wf' => 'ar-script-development-workflow.avif'],
    'storyboard-development'       => ['hero' => 'ar-storyboard-development-hero.avif', 'wf' => 'ar-storyboard-development-workflow.avif'],
    'strategic-planning'           => ['hero' => 'ar-strategic-planning-hero.avif', 'wf' => 'ar-strategic-planning-workflow.avif'],
    'video-tutorials'              => ['hero' => 'ar-video-tutorials-hero.avif', 'wf' => 'ar-video-tutorials-workflow.avif'],
    'video-marketing'              => ['hero' => 'ar-video-marketing-hero.avif', 'wf' => 'ar-video-marketing-workflow.avif'],
    'video-description-service-bangladesh' => ['hero' => 'ar-video-description-seo-hero.avif', 'wf' => 'ar-video-description-seo-workflow.avif'],
    'corporate-av-production-company-in-dhaka' => ['hero' => 'ar-dhaka-corporate-av-hero.avif', 'wf' => 'ar-dhaka-corporate-av-workflow.avif'],
    'corporate-av-production-in-bangladesh' => ['hero' => 'ar-nationwide-corporate-av-hero.avif', 'wf' => 'ar-nationwide-corporate-av-workflow.avif'],
    'service-excellence'           => ['hero' => 'ar-service-excellence-hero.avif', 'wf' => 'ar-service-excellence-workflow.avif'],
    'additional-services'          => ['hero' => 'ar-additional-services-hero.avif', 'wf' => 'ar-additional-services-workflow.avif'],
    'animated-explainer-video'     => ['hero' => 'ar-technical-explainer-video-hero.avif', 'wf' => 'ar-technical-explainer-video-workflow.avif']
];

$hero_file = $media_map[$service['slug']]['hero'] ?? 'ar-tv-commercial-production-hero.avif';
$wf_file   = $media_map[$service['slug']]['wf'] ?? null;

$hero_img = file_exists(ROOT_PATH . '/uploads/services/' . $hero_file)
    ? upload_url('services/' . $hero_file)
    : upload_url('images/featured/commercial-video-production.jpg');

$wf_img = ($wf_file && file_exists(ROOT_PATH . '/uploads/services/' . $wf_file))
    ? upload_url('services/' . $wf_file)
    : null;

// Parse FAQs
$faqs = [];
if (!empty($service['faqs_json'])) {
    $decoded = json_decode($service['faqs_json'], true);
    if (is_array($decoded)) {
        $faqs = $decoded;
    }
}

// Fetch Related Services in the Same Department
try {
    $related_stmt = db()->prepare("
        SELECT id, title, slug, icon, short_summary 
        FROM services 
        WHERE status = 'active' AND slug != :slug AND slug IN (" . implode(',', array_fill(0, count($department['slugs']), '?')) . ")
        ORDER BY sort_order ASC, id ASC 
        LIMIT 4
    ");
    $rel_params = array_merge([$service['slug']], $department['slugs']);
    $related_stmt->execute($rel_params);
    $related_services = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $related_services = [];
}

// -----------------------------------------------------------------------------
// SEO Meta Information & Schema.org Graph
// -----------------------------------------------------------------------------
$page_title       = !empty($service['meta_title']) ? $service['meta_title'] : ($service['title'] . ' | AR Entertainment Bangladesh');
$page_description = !empty($service['meta_description']) ? $service['meta_description'] : strip_tags($service['short_summary'] ?? '');
$page_keywords    = 'Video Production Bangladesh, ' . $service['title'] . ', Film Fixer Dhaka, Commercial Video Production, AR Entertainment';
$current_page     = 'services';
$canonical_url    = site_url('services/' . $service['slug']);
$og_image         = $hero_img;

// Build Schema Graph
$schema_service = [
    '@type'       => 'Service',
    '@id'         => $canonical_url . '#service',
    'name'        => $service['title'],
    'description' => strip_tags($service['short_summary'] ?? ''),
    'serviceType' => $department['name'],
    'provider'    => [
        '@type' => 'Organization',
        '@id'   => site_url() . '#organization',
        'name'  => 'AR Entertainment',
        'url'   => site_url(),
        'telephone' => '+8801988-777444'
    ],
    'areaServed'  => [
        ['@type' => 'Country', 'name' => 'Bangladesh'],
        ['@type' => 'City', 'name' => 'Dhaka']
    ]
];

if (!empty($service['pricing_note'])) {
    $schema_service['offers'] = [
        '@type' => 'Offer',
        'description' => $service['pricing_note']
    ];
}

$schema_breadcrumbs = [
    '@type' => 'BreadcrumbList',
    '@id'   => $canonical_url . '#breadcrumb',
    'itemListElement' => [
        [
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => 'Home',
            'item'     => site_url()
        ],
        [
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => 'Services',
            'item'     => site_url('services')
        ],
        [
            '@type'    => 'ListItem',
            'position' => 3,
            'name'     => $service['title'],
            'item'     => $canonical_url
        ]
    ]
];

$page_schema = [
    [
        '@type' => 'WebPage',
        '@id'   => $canonical_url . '#webpage',
        'url'   => $canonical_url,
        'name'  => $page_title,
        'description' => $page_description,
        'isPartOf' => ['@id' => site_url() . '#website'],
        'about' => ['@id' => $canonical_url . '#service'],
        'breadcrumb' => ['@id' => $canonical_url . '#breadcrumb']
    ],
    $schema_service,
    $schema_breadcrumbs
];

// Add FAQPage Schema if FAQs exist
if (!empty($faqs)) {
    $faq_entities = [];
    foreach ($faqs as $f) {
        if (!empty($f['q']) && !empty($f['a'])) {
            $faq_entities[] = [
                '@type' => 'Question',
                'name'  => $f['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $f['a']
                ]
            ];
        }
    }
    if (!empty($faq_entities)) {
        $page_schema[] = [
            '@type'      => 'FAQPage',
            '@id'        => $canonical_url . '#faq',
            'mainEntity' => $faq_entities
        ];
    }
}

// Universal Header & Navigation Partials
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Scoped Single Service Stylesheet -->
<style>
    /* Balanced Hero Banner Overrides */
    .inner-banner-area .banner-content {
        height: auto !important;
        min-height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
    }

    /* Content Styling & Typography */
    .service-content-body {
        color: #cbd5e1;
        font-size: 15.5px;
        line-height: 1.8;
    }
    .service-content-body h2 {
        color: #ffffff;
        font-size: 26px;
        font-weight: 700;
        margin-top: 36px;
        margin-bottom: 18px;
        letter-spacing: -0.3px;
        position: relative;
        padding-bottom: 10px;
    }
    .service-content-body h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 45px;
        height: 3px;
        background: #e50914;
        border-radius: 2px;
    }
    .service-content-body h3 {
        color: #ffffff;
        font-size: 20px;
        font-weight: 700;
        margin-top: 28px;
        margin-bottom: 14px;
    }
    .service-content-body p {
        margin-bottom: 18px;
    }
    .service-content-body ul {
        margin-bottom: 24px;
        padding-left: 20px;
    }
    .service-content-body ul li {
        margin-bottom: 10px;
        position: relative;
    }
    .service-content-body ul.checked {
        list-style: none;
        padding-left: 0;
    }
    .service-content-body ul.checked li {
        position: relative;
        padding-left: 30px;
        margin-bottom: 14px;
    }
    .service-content-body ul.checked li::before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 2px;
        color: #e50914;
        font-size: 14px;
    }
    .service-content-body strong {
        color: #ffffff;
    }

    /* Tech Specs Grid Box */
    .specs-card {
        background: #161922;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 20px;
        height: 100%;
        transition: all 0.3s ease;
    }
    .specs-card:hover {
        border-color: rgba(229, 9, 20, 0.4);
        transform: translateY(-3px);
    }

    /* Sticky Sidebar */
    .service-sidebar-sticky {
        position: sticky;
        top: 90px;
    }
    .sidebar-widget-box {
        background: #161922;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .sidebar-widget-title {
        color: #ffffff;
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sidebar-widget-title i {
        color: #e50914;
    }

    /* Related Services Mini Cards */
    .related-svc-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px;
        background: #1a1e29;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        text-decoration: none !important;
        margin-bottom: 10px;
        transition: all 0.25s ease;
    }
    .related-svc-item:hover {
        background: #222736;
        border-color: rgba(229, 9, 20, 0.5);
        transform: translateX(4px);
    }
    .related-svc-icon {
        width: 38px;
        height: 38px;
        border-radius: 6px;
        background: rgba(229, 9, 20, 0.12);
        color: #e50914;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .related-svc-title {
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- ========================================================================= -->
<!-- 1. HERO BANNER & BREADCRUMB                                               -->
<!-- ========================================================================= -->
<section class="inner-banner-area">
    <div class="inner-banner" style="background-image: url('<?= $hero_img ?>'); background-size: cover; background-position: center; position: relative; padding-top: 155px !important; padding-bottom: 65px !important;">
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(10,12,18,0.82) 0%, rgba(10,12,18,0.96) 100%);"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="banner-content text-center">
                <!-- Breadcrumbs -->
                <nav class="breadcrumb-nav mb-3" aria-label="Breadcrumb">
                    <ul class="d-flex align-items-center justify-content-center list-unstyled mb-0" style="gap: 10px; font-size: 14px;">
                        <li><a href="<?= site_url() ?>" style="color: #9ca3af; text-decoration: none;"><i class="fa fa-home mr-1"></i> Home</a></li>
                        <li style="color: #6b7280; user-select: none;">/</li>
                        <li><a href="<?= site_url('services') ?>" style="color: #9ca3af; text-decoration: none;">Services</a></li>
                        <li style="color: #6b7280; user-select: none;">/</li>
                        <li class="active" style="color: #e50914; font-weight: 700;"><?= htmlspecialchars($service['title']) ?></li>
                    </ul>
                </nav>

                <!-- Department Tag -->
                <div class="mb-2">
                    <span style="background: rgba(229, 9, 20, 0.15); border: 1px solid rgba(229, 9, 20, 0.4); color: #ff6b6b; padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;">
                        <i class="<?= $department['icon'] ?> mr-1"></i> <?= htmlspecialchars($department['name']) ?>
                    </span>
                </div>

                <!-- Page Heading -->
                <h1 class="text-white font-weight-bold display-4 mb-3" style="letter-spacing: -0.5px;">
                    <?= htmlspecialchars($service['title']) ?>
                </h1>

                <!-- Short Summary Subtitle -->
                <p class="lead text-light mx-auto mb-4" style="max-width: 820px; font-size: 16.5px; line-height: 1.6; color: #d1d5db;">
                    <?= htmlspecialchars(strip_tags($service['short_summary'] ?? '')) ?>
                </p>

                <!-- Action Buttons -->
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                    <a href="#quoteSidebar" class="btn common-btn m-2">
                        Request Project Proposal<span></span>
                    </a>
                    <a href="tel:<?= preg_replace('/[^\d+]/', '', get_setting('contact_phone', CONTACT_PHONE)) ?>" class="btn common-btn m-2" style="background: transparent; border: 1px solid #e50914;">
                        <i class="fa-solid fa-phone mr-2" style="color: #e50914;"></i> Call Production Desk<span></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 2. MAIN TWO-COLUMN SERVICE VIEW                                            -->
<!-- ========================================================================= -->
<section class="common-sec" style="background: #0d0f14;">
    <div class="container">
        <div class="row">
            <!-- ------------------------------------------------------------- -->
            <!-- LEFT COLUMN: Primary Production Overview & Specs (8 cols)      -->
            <!-- ------------------------------------------------------------- -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <!-- Cinematic Hero Visual Feature -->
                <div class="mb-5 position-relative" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 12px 30px rgba(0,0,0,0.5);">
                    <img src="<?= $hero_img ?>" alt="<?= htmlspecialchars($service['title']) ?>" class="img-fluid w-100" style="max-height: 480px; object-fit: cover;">
                </div>

                <!-- Production Workflow Visual Guide (if available) -->
                <?php if ($wf_img): ?>
                    <div class="mb-5 p-4" style="background: #161922; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa-solid fa-diagram-project text-danger mr-2" style="font-size: 20px;"></i>
                            <h3 class="text-white font-weight-bold mb-0" style="font-size: 19px;">Our Production Methodology &amp; Workflow</h3>
                        </div>
                        <p class="text-muted" style="font-size: 14px; line-height: 1.6;">
                            Every phase of our production is structured through rigid institutional milestones—ensuring client vision aligns with technical execution and broadcast delivery.
                        </p>
                        <div class="text-center mt-3" style="border-radius: 8px; overflow: hidden; background: #0f1118; border: 1px solid rgba(255,255,255,0.05);">
                            <img src="<?= $wf_img ?>" alt="<?= htmlspecialchars($service['title']) ?> Workflow Process" class="img-fluid" style="width: 100%; max-height: 400px; object-fit: contain;">
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Formatted HTML Body Content from Database -->
                <div class="service-content-body mb-5">
                    <?php if (!empty($service['content'])): ?>
                        <?= $service['content'] ?>
                    <?php else: ?>
                        <p>
                            AR Entertainment delivers world-class <strong><?= htmlspecialchars($service['title']) ?></strong> in Bangladesh under the directorship of Founder Azizul Hoque Shiplu. Our Dhaka production studio offers end-to-end creative ideation, precision technical shooting, and broadcast-grade post-production finishing.
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Technical Capabilities & Cinema Equipment Specs Strip -->
                <div class="mb-5">
                    <h2 class="text-white font-weight-bold mb-4" style="font-size: 22px; position: relative; padding-bottom: 8px; border-bottom: 2px solid #e50914; display: inline-block;">
                        Deployed Technical Infrastructure
                    </h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="specs-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-video text-danger mr-2" style="font-size: 18px;"></i>
                                    <h4 class="text-white font-weight-bold mb-0" style="font-size: 15px;">Cinema Camera Rigs</h4>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6;">
                                    RED V-Raptor 8K VV, ARRI Alexa Mini LF, Sony FX9/FX3 cinema packages with Zeiss &amp; Cooke prime lenses.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="specs-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-lightbulb text-danger mr-2" style="font-size: 18px;"></i>
                                    <h4 class="text-white font-weight-bold mb-0" style="font-size: 15px;">Lighting &amp; Grip</h4>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6;">
                                    Aputure Electro Storm 1200d, Nanlux Evoke 1200B, motorized sliders, Dana Dollys, and Matthews C-Stands.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="specs-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-helicopter text-danger mr-2" style="font-size: 18px;"></i>
                                    <h4 class="text-white font-weight-bold mb-0" style="font-size: 15px;">CAAB Heavy-Lift Drones</h4>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6;">
                                    DJI Inspire 3 (8K RAW) and heavy-lift cinema hexacopters with full CAAB civil aviation flight insurance.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="specs-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-sliders text-danger mr-2" style="font-size: 18px;"></i>
                                    <h4 class="text-white font-weight-bold mb-0" style="font-size: 15px;">Post-Production Color</h4>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6;">
                                    DaVinci Resolve Studio with ACES color management, Dolby Atmos sound design, and EBU R128 broadcast mix.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Database-Driven FAQ Accordions -->
                <?php if (!empty($faqs)): ?>
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa-solid fa-circle-question text-danger mr-2" style="font-size: 22px;"></i>
                            <h2 class="text-white font-weight-bold mb-0" style="font-size: 22px;">Frequently Asked Questions</h2>
                        </div>
                        <p class="text-muted mb-4" style="font-size: 14px;">
                            Common questions regarding deliverables, shooting timelines, and logistics for <?= htmlspecialchars($service['title']) ?>.
                        </p>

                        <div class="accordion" id="serviceFaqAccordion">
                            <?php foreach ($faqs as $f_idx => $faq_item): ?>
                                <?php
                                    $q_text = htmlspecialchars($faq_item['q'] ?? '');
                                    $a_text = htmlspecialchars($faq_item['a'] ?? '');
                                    $is_first = ($f_idx === 0);
                                ?>
                                <div class="card mb-3" style="background: #161922; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden;">
                                    <div class="card-header p-0" id="sFaqHeading<?= $f_idx ?>" style="background: transparent;">
                                        <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3 <?= $is_first ? '' : 'collapsed' ?>" type="button" data-toggle="collapse" data-target="#sFaqCollapse<?= $f_idx ?>" aria-expanded="<?= $is_first ? 'true' : 'false' ?>" aria-controls="sFaqCollapse<?= $f_idx ?>" style="text-decoration: none; font-weight: 700; font-size: 15px;">
                                            <span><?= $q_text ?></span>
                                            <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 13px;"></i>
                                        </button>
                                    </div>
                                    <div id="sFaqCollapse<?= $f_idx ?>" class="collapse <?= $is_first ? 'show' : '' ?>" aria-labelledby="sFaqHeading<?= $f_idx ?>" data-parent="#serviceFaqAccordion">
                                        <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                            <?= $a_text ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Production Deliverables Guarantee Checklist -->
                <div class="p-4" style="background: #161922; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                    <h3 class="text-white font-weight-bold mb-3" style="font-size: 18px;">Standard Deliverables Guarantee</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="checked mb-0" style="list-style: none; padding-left: 0;">
                                <li style="position: relative; padding-left: 28px; margin-bottom: 12px; font-size: 13.5px; color: #cbd5e1;">
                                    <i class="fa-solid fa-check text-danger" style="position: absolute; left: 0; top: 3px;"></i>
                                    Master 4K/UHD ProRes 422 HQ broadcast exports
                                </li>
                                <li style="position: relative; padding-left: 28px; margin-bottom: 12px; font-size: 13.5px; color: #cbd5e1;">
                                    <i class="fa-solid fa-check text-danger" style="position: absolute; left: 0; top: 3px;"></i>
                                    Multi-format cutdowns: 16:9, 9:16 vertical reels &amp; 1:1
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="checked mb-0" style="list-style: none; padding-left: 0;">
                                <li style="position: relative; padding-left: 28px; margin-bottom: 12px; font-size: 13.5px; color: #cbd5e1;">
                                    <i class="fa-solid fa-check text-danger" style="position: absolute; left: 0; top: 3px;"></i>
                                    Uncompressed audio stems (Voice, Foley, Music, Mix)
                                </li>
                                <li style="position: relative; padding-left: 28px; margin-bottom: 12px; font-size: 13.5px; color: #cbd5e1;">
                                    <i class="fa-solid fa-check text-danger" style="position: absolute; left: 0; top: 3px;"></i>
                                    Secure cloud archiving &amp; RAW camera backups
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ------------------------------------------------------------- -->
            <!-- RIGHT SIDEBAR: Sticky Proposal Trigger & Direct Links (4 cols) -->
            <!-- ------------------------------------------------------------- -->
            <div class="col-lg-4" id="quoteSidebar">
                <div class="service-sidebar-sticky">
                    <!-- 1. Quick Proposal Form Card -->
                    <div class="sidebar-widget-box" style="border-top: 3px solid #e50914;">
                        <h3 class="sidebar-widget-title">
                            <i class="fa-solid fa-file-signature"></i> Request Production Quote
                        </h3>
                        <p class="text-muted" style="font-size: 13px; line-height: 1.5; margin-bottom: 18px;">
                            Tell us about your upcoming project and receive a custom estimate within 24 hours.
                        </p>

                        <form action="<?= site_url('contact-us') ?>" method="GET">
                            <div class="form-group mb-3">
                                <label class="text-white" style="font-size: 12.5px; font-weight: 600;">Selected Service</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($service['title']) ?>" readonly style="background: #11131a; border: 1px solid rgba(255,255,255,0.1); color: #e50914; font-size: 13px; font-weight: 700;">
                                <input type="hidden" name="service" value="<?= htmlspecialchars($service['slug']) ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-white" style="font-size: 12.5px; font-weight: 600;">Your Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Tanvir Ahmed" required style="background: #181b24; border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 13px;">
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-white" style="font-size: 12.5px; font-weight: 600;">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="name@company.com" required style="background: #181b24; border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 13px;">
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-white" style="font-size: 12.5px; font-weight: 600;">Phone / WhatsApp</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+880 1..." required style="background: #181b24; border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 13px;">
                            </div>
                            <button type="submit" class="btn common-btn btn-block mt-3" style="width: 100%;">
                                Submit Project Inquiry<span></span>
                            </button>
                        </form>
                    </div>

                    <!-- 2. Transparent Pricing Guidance Note -->
                    <?php if (!empty($service['pricing_note'])): ?>
                        <div class="sidebar-widget-box" style="background: rgba(229, 9, 20, 0.04); border-color: rgba(229, 9, 20, 0.2);">
                            <h3 class="sidebar-widget-title" style="color: #ff6b6b; font-size: 15px;">
                                <i class="fa-solid fa-tag"></i> Budget &amp; Pricing Guidance
                            </h3>
                            <p class="text-light mb-0" style="font-size: 13px; line-height: 1.6;">
                                <?= htmlspecialchars($service['pricing_note']) ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- 3. Direct Production Desk Triggers -->
                    <div class="sidebar-widget-box text-center">
                        <div style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #e50914; margin-bottom: 6px; letter-spacing: 0.5px;">Need Immediate Assistance?</div>
                        <h4 class="text-white font-weight-bold mb-3" style="font-size: 16px;">Speak Directly with Our Producer</h4>
                        
                        <a href="tel:<?= preg_replace('/[^\d+]/', '', get_setting('contact_phone', CONTACT_PHONE)) ?>" class="btn btn-block mb-2 text-white font-weight-bold" style="background: #1e2330; border: 1px solid rgba(255,255,255,0.1); padding: 10px; border-radius: 6px; text-decoration: none;">
                            <i class="fa-solid fa-phone mr-2 text-danger"></i> <?= htmlspecialchars(get_setting('contact_phone', CONTACT_PHONE)) ?>
                        </a>

                        <a href="https://wa.me/8801988777444?text=<?= urlencode('Hello AR Entertainment, I am inquiring about ' . $service['title']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-block font-weight-bold text-white" style="background: #25d366; padding: 10px; border-radius: 6px; text-decoration: none;">
                            <i class="fa-brands fa-whatsapp mr-2"></i> Chat on WhatsApp
                        </a>
                        <div class="mt-2 text-muted" style="font-size: 11.5px;">Mon – Sat (9am – 8pm BST) | 24/7 International Fixer Desk</div>
                    </div>

                    <!-- 4. Related Services in Same Department -->
                    <?php if (!empty($related_services)): ?>
                        <div class="sidebar-widget-box">
                            <h3 class="sidebar-widget-title">
                                <i class="fa-solid fa-layer-group"></i> Related <?= htmlspecialchars($department['name']) ?>
                            </h3>
                            <?php foreach ($related_services as $rel): ?>
                                <a href="<?= site_url('services/' . $rel['slug']) ?>" class="related-svc-item">
                                    <div class="related-svc-icon">
                                        <i class="<?= htmlspecialchars($rel['icon'] ?? 'fa-solid fa-video') ?>"></i>
                                    </div>
                                    <div>
                                        <div class="related-svc-title"><?= htmlspecialchars($rel['title']) ?></div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- 5. Studio Credentials -->
                    <div class="sidebar-widget-box">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa-solid fa-award text-danger mr-3" style="font-size: 26px;"></i>
                            <div>
                                <div class="text-white font-weight-bold" style="font-size: 14px;">18+ Years Commercial Film Experience</div>
                                <div class="text-muted" style="font-size: 12px;">Under Director Azizul Hoque Shiplu</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa-solid fa-earth-americas text-danger mr-3" style="font-size: 26px;"></i>
                            <div>
                                <div class="text-white font-weight-bold" style="font-size: 14px;">10+ Countries Supported</div>
                                <div class="text-muted" style="font-size: 12px;">USA, UK, France, UAE, Malaysia, India</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-map-location-dot text-danger mr-3" style="font-size: 26px;"></i>
                            <div>
                                <div class="text-white font-weight-bold" style="font-size: 14px;">64 Districts Covered</div>
                                <div class="text-muted" style="font-size: 12px;">Nationwide logistics &amp; field fixers</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 3. BOTTOM HIGH-CONVERSION CTA STRIP                                        -->
<!-- ========================================================================= -->
<section class="common-sec cta-sec" style="background: #11131a; border-top: 1px solid rgba(255,255,255,0.06);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px;">Start Your Production</div>
                <h2 class="display-4 font-weight-bold text-white mb-3" style="letter-spacing: -0.5px;">Ready to Book <?= htmlspecialchars($service['title']) ?>?</h2>
                <p class="lead mb-4" style="color: #cbd5e1; font-size: 16px; line-height: 1.7;">
                    From concept brainstorm to final broadcast master, our production team handles every logistical and creative detail. Reach out today for an itemized estimate.
                </p>
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                    <a href="<?= site_url('contact-us?service=' . urlencode($service['slug'])) ?>" class="btn common-btn m-2">
                        Get a Detailed Proposal<span></span>
                    </a>
                    <a href="tel:<?= preg_replace('/[^\d+]/', '', get_setting('contact_phone', CONTACT_PHONE)) ?>" class="btn common-btn m-2" style="background: transparent; border: 1px solid #e50914;">
                        <i class="fa-solid fa-phone mr-2" style="color: #e50914;"></i> Call: <?= htmlspecialchars(get_setting('contact_phone', CONTACT_PHONE)) ?><span></span>
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
