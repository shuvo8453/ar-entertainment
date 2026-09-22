<?php

/**
 * AR Entertainment - Dynamic Homepage Template
 * 
 * Dynamic homepage integrating featured portfolio projects, core services,
 * client brand logos carousel, verified customer testimonials slider,
 * latest published blog articles, and authoritative brand credentials.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

// -----------------------------------------------------------------------------
// SEO & Meta Configuration
// -----------------------------------------------------------------------------
$page_title       = 'Video Production and Film Fixer Services in Bangladesh';
$page_description = 'AR Entertainment is a premier Dhaka-based video production house and film fixer for local and international productions. TVC, OVC, corporate AV, documentary, AI video content and full production support across Bangladesh.';
$page_keywords    = 'Video Production Bangladesh, TVC, Film Fixer in Bangladesh, AI Video Production Bangladesh, Corporate AV Production, OVC Production, Music Video Production, Film Production House Bangladesh';
$current_page     = 'home';
$canonical_url    = site_url();

// -----------------------------------------------------------------------------
// Dynamic Data Queries
// -----------------------------------------------------------------------------

// 1. Featured Services (Top 8 distinct active services)
try {
    $featured_services = db()->query("
        SELECT id, title, slug, icon, short_summary, pricing_note 
        FROM services 
        WHERE status = 'active' AND id != 1
        ORDER BY sort_order ASC, id ASC 
        LIMIT 8
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_services = [];
}

// 2. Featured Portfolio Video Projects
try {
    $featured_portfolio = db()->query("
        SELECT id, title, slug, category_name, client_name, year, thumbnail, video_url 
        FROM portfolio 
        WHERE status = 'active' AND is_featured = 1 
        ORDER BY sort_order ASC, id DESC 
        LIMIT 6
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_portfolio = [];
}

// 3. Client & Partner Brands for Carousel
try {
    $client_brands = db()->query("
        SELECT id, name, logo, website_url 
        FROM brands 
        WHERE status = 'active' 
        ORDER BY sort_order ASC, id ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $client_brands = [];
}

// 4. Verified Client Reviews
try {
    $verified_reviews = db()->query("
        SELECT id, client_name, client_company, client_designation, client_photo, review_text, rating, source 
        FROM reviews 
        WHERE status = 'active' 
        ORDER BY rating DESC, sort_order ASC, id ASC 
        LIMIT 6
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $verified_reviews = [];
}

// 5. Recent Published Blog Posts
try {
    $recent_blogs = db()->query("
        SELECT b.id, b.title, b.slug, b.summary, b.content, b.thumbnail, b.published_at, 
               c.name as category_name, c.slug as category_slug 
        FROM blogs b 
        LEFT JOIN categories c ON b.category_id = c.id 
        WHERE b.status = 'published' 
        ORDER BY b.published_at DESC 
        LIMIT 3
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recent_blogs = [];
}

// Helper to resolve service thumbnails cleanly
function get_homepage_service_image(array $service): string
{
    $slug = $service['slug'] ?? '';
    $candidates = [
        "uploads/services/ar-{$slug}-hero.avif",
        "uploads/services/ar-{$slug}-workflow.avif",
    ];

    if ($slug === 'tv-commercial' || $slug === 'tv-commercial-ovc-production') {
        $candidates[] = 'uploads/services/ar-tv-commercial-production-hero.avif';
        $candidates[] = 'images/featured/commercial-video-production.jpg';
    } elseif ($slug === 'online-video-commercial') {
        $candidates[] = 'uploads/services/ar-online-video-commercial-hero.avif';
        $candidates[] = 'images/featured/online-video-commercial.jpg';
    } elseif ($slug === 'line-production-film-fixing' || $slug === 'support-for-international-production') {
        $candidates[] = 'uploads/services/ar-intl-production-support-hero.avif';
    } elseif ($slug === 'corporate-av') {
        $candidates[] = 'uploads/services/ar-corporate-av-production-hero.avif';
        $candidates[] = 'images/featured/corporate-video-production.jpg';
    } elseif ($slug === 'documentary') {
        $candidates[] = 'uploads/services/ar-documentary-production-hero.avif';
        $candidates[] = 'images/featured/documentary.webp';
    } elseif ($slug === 'theme-song') {
        $candidates[] = 'uploads/services/ar-theme-song-production-hero.avif';
    } elseif ($slug === 'music-video') {
        $candidates[] = 'uploads/services/ar-music-video-production-hero.avif';
    }

    foreach ($candidates as $cand) {
        if (file_exists(ROOT_PATH . '/' . $cand)) {
            return upload_url($cand);
        }
    }
    return upload_url('images/featured/commercial-video-production.jpg');
}

// -----------------------------------------------------------------------------
// Rich Schema.org Structured Data (FAQ + WebPage)
// -----------------------------------------------------------------------------
$page_schema = [
    [
        '@type' => 'WebPage',
        '@id'   => site_url() . '#webpage',
        'url'   => site_url(),
        'name'  => 'AR Entertainment | Video Production and Film Fixer Services in Bangladesh',
        'isPartOf' => ['@id' => site_url() . '#website'],
        'about' => ['@id' => site_url() . '#organization'],
        'description' => $page_description,
        'inLanguage' => 'en'
    ],
    [
        '@type' => 'FAQPage',
        '@id'   => site_url() . '#faq',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name'  => 'What video production services does AR Entertainment provide in Bangladesh?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'AR Entertainment provides end-to-end video production services including Television Commercials (TVC), Online Video Commercials (OVC), Corporate Brand Films & AV, Documentary Films, Music Videos, 2D/3D Animation, AI Video Content Creation, and International Line Production & Film Fixer support.'
                ]
            ],
            [
                '@type' => 'Question',
                'name'  => 'How much does video production cost in Bangladesh?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'Online Video Commercials (OVC) generally start from BDT 80,000 to BDT 850,000. National Television Commercials (TVC) typically range from BDT 350,000 to BDT 2,500,000+ depending on concept, cast, filming locations, camera equipment (ARRI Alexa, RED), and VFX requirements.'
                ]
            ],
            [
                '@type' => 'Question',
                'name'  => 'Do you provide film fixer and shooting permits support for foreign crews in Bangladesh?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'Yes. AR Entertainment offers comprehensive fixer services for foreign production crews, including Ministry of Information filming permits, FF visa assistance, customs carnets, equipment rentals, English-speaking crew, and location scouting across Dhaka, Cox\'s Bazar, Sundarbans, Sylhet, and Chattogram.'
                ]
            ],
            [
                '@type' => 'Question',
                'name'  => 'Who leads AR Entertainment\'s creative and production direction?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'AR Entertainment is led by Founder and Film Director Azizul Hoque Shiplu, who has been directing commercial films, advertisements, and audiovisual projects since 2007, personally overseeing more than 100 successful productions.'
                ]
            ]
        ]
    ]
];

// Include Universal Header and Navigation Partials
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- ========================================================================= -->
<!-- 1. HERO VIDEO & BANNER SECTION                                            -->
<!-- ========================================================================= -->
<div class="video-section" itemscope itemtype="https://schema.org/VideoObject">
    <meta itemprop="name" content="AR Entertainment - Premium Video Production House & Film Fixer in Bangladesh">
    <meta itemprop="description" content="AR Entertainment is a premier Dhaka-based video production house and film fixer for TVC, OVC, corporate AV, documentary, and international shoots.">
    <meta itemprop="thumbnailUrl" content="<?= site_url('images/ar-hero-banner.webp') ?>">
    <meta itemprop="uploadDate" content="2024-08-18T08:00:00+06:00">
    <meta itemprop="isFamilyFriendly" content="true">

    <img src="<?= site_url('images/ar-hero-banner.webp') ?>" alt="AR Entertainment Video Production House in Bangladesh" width="1920" height="900" loading="eager" decoding="async" fetchpriority="high">
    
    <div class="slider-content">
        <div class="container">
            <h1 class="slider-title pl-3 pl-sm-5 mb-5" style="letter-spacing: -0.5px;">Video production and film fixer services in Bangladesh</h1>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. PRIMARY INTRODUCTION & VALUE PROPOSITION                               -->
<!-- ========================================================================= -->
<section class="common-sec">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <h2 class="common-heading homepage-speakable">Premier Video Production &amp; Film Fixer in Bangladesh</h2>
                <p>
                    <strong>AR Entertainment</strong> is a Dhaka-based full-service video production house delivering high-impact TV commercials (TVC), digital video campaigns (OVC), corporate brand films, documentaries, cutting-edge AI video content, and comprehensive film fixer support for local and international productions.
                </p>
                <p>
                    Founded in 2018 under the creative leadership of Founder and Film Director <strong>Azizul Hoque Shiplu</strong>, who has been working in commercial film production and advertising since 2007, our team has personally overseen more than 100 advertising and audiovisual projects across Bangladesh and supported international crews from the USA, UK, Belgium, Romania, Malaysia, UAE, France, and India.
                </p>
                <p>
                    From concept development, casting, and scriptwriting to camera rigs, drone clearances, and final post-production color grading, we ensure flawless execution that meets global broadcast and digital standards.
                </p>
                <div class="mt-4 pt-2">
                    <a class="btn common-btn mr-3" href="<?= site_url('contact-us') ?>">
                        Request for Quotation<span></span>
                    </a>
                    <a class="btn common-btn" href="<?= site_url('services/support-for-international-production') ?>">
                        Film Fixer Services<span></span>
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="img-box text-center">
                    <img src="<?= site_url('images/video-production-house-in-bangladesh.webp') ?>" alt="Video Production House in Bangladesh - AR Entertainment" width="690" height="460" loading="eager" class="img-fluid rounded shadow-lg" style="max-height: 420px; width: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 3. QUICK SIGNAL STRIP (KEY PERFORMANCE METRICS)                           -->
<!-- ========================================================================= -->
<section class="common-sec py-4" style="background: #111218; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div style="background: #1a1a1a; border-left: 4px solid #e50914; padding: 22px 18px; border-radius: 0 6px 6px 0; height: 100%;">
                    <div style="color: #e50914; font-size: 2.4rem; font-weight: 800; margin-bottom: 4px;">2018</div>
                    <div style="color: #cbd5e1; font-size: 14px; line-height: 1.4;">Founded in Dhaka, Bangladesh</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div style="background: #1a1a1a; border-left: 4px solid #e50914; padding: 22px 18px; border-radius: 0 6px 6px 0; height: 100%;">
                    <div style="color: #e50914; font-size: 2.4rem; font-weight: 800; margin-bottom: 4px;">100+</div>
                    <div style="color: #cbd5e1; font-size: 14px; line-height: 1.4;">TVCs, OVCs &amp; AV Projects Directed</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div style="background: #1a1a1a; border-left: 4px solid #e50914; padding: 22px 18px; border-radius: 0 6px 6px 0; height: 100%;">
                    <div style="color: #e50914; font-size: 2.4rem; font-weight: 800; margin-bottom: 4px;">10+</div>
                    <div style="color: #cbd5e1; font-size: 14px; line-height: 1.4;">Countries Supported Internationally</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div style="background: #1a1a1a; border-left: 4px solid #e50914; padding: 22px 18px; border-radius: 0 6px 6px 0; height: 100%;">
                    <div style="color: #e50914; font-size: 2.4rem; font-weight: 800; margin-bottom: 4px;">64</div>
                    <div style="color: #cbd5e1; font-size: 14px; line-height: 1.4;">Districts Covered Nationwide</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 4. MAJOR SERVICES SHOWCASE (DYNAMIC)                                      -->
<!-- ========================================================================= -->
<section class="common-sec">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4">
            <div>
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">What We Do</div>
                <h2 class="common-heading mb-0">Major Production Services</h2>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="<?= site_url('services') ?>" class="btn common-btn">View All 40+ Services<span></span></a>
            </div>
        </div>
        <p class="text-muted" style="max-width: 800px; font-size: 15px; line-height: 1.7;">
            As a full-spectrum production house in Dhaka, AR Entertainment specializes in comprehensive video production solutions tailored to communicate your narrative and achieve tangible audience engagement.
        </p>

        <div class="row mt-4">
            <?php foreach ($featured_services as $svc): ?>
                <?php 
                    $svc_img = get_homepage_service_image($svc);
                    $svc_url = site_url('services/' . $svc['slug']);
                ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div style="background: #1a1a1a; border-radius: 6px; overflow: hidden; height: 100%; display: flex; flex-direction: column; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="overflow: hidden; height: 180px; position: relative;">
                            <a href="<?= $svc_url ?>" style="display: block; width: 100%; height: 100%;">
                                <img src="<?= htmlspecialchars($svc_img) ?>" alt="<?= htmlspecialchars($svc['title']) ?> - AR Entertainment" width="400" height="180" loading="lazy" style="width: 100%; height: 180px; object-fit: cover; display: block; transition: transform 0.4s ease;">
                            </a>
                            <div style="position: absolute; bottom: 10px; right: 12px; background: rgba(15, 16, 22, 0.85); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.15);">
                                <i class="<?= !empty($svc['icon']) ? htmlspecialchars($svc['icon']) : 'fa-solid fa-film' ?>" style="color: #e50914; font-size: 15px;"></i>
                            </div>
                        </div>
                        <div style="padding: 20px; display: flex; flex-direction: column; flex: 1;">
                            <h3 style="font-size: 17px; line-height: 1.35; margin-bottom: 8px; font-weight: 700;">
                                <a href="<?= $svc_url ?>" style="color: #ffffff; text-decoration: none;">
                                    <?= htmlspecialchars($svc['title']) ?>
                                </a>
                            </h3>
                            <p style="color: #9ca3af; font-size: 13.5px; line-height: 1.55; margin-bottom: 16px; flex: 1;">
                                <?= htmlspecialchars(truncate_text($svc['short_summary'] ?? 'Professional high-quality video production services tailored to your campaign requirements in Bangladesh.', 110)) ?>
                            </p>
                            <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-top: auto;">
                                <div style="color: #e50914; font-size: 12.5px; font-weight: 600; margin-bottom: 6px;">
                                    <?= htmlspecialchars(truncate_text($svc['pricing_note'] ?? 'Custom package on brief', 40)) ?>
                                </div>
                                <a href="<?= $svc_url ?>" style="color: #cbd5e1; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
                                    Explore Service <i class="fa-solid fa-arrow-right ml-2" style="font-size: 11px; color: #e50914;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 5. FILM FIXER FOCUS SECTION (INTERNATIONAL PRODUCTIONS)                   -->
<!-- ========================================================================= -->
<section class="common-sec" style="background: #111218;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">International Support</div>
                <h2 class="common-heading">Film Fixer in Bangladesh: Seamless International Line Production</h2>
                <p>
                    Shooting in Bangladesh requires local expertise, regulatory coordination, and rapid logistical navigation. AR Entertainment acts as the primary on-ground fixer and co-producer for foreign media networks, documentary crews, commercial directors, and development agencies.
                </p>
                <div class="row mt-4">
                    <div class="col-sm-6 mb-3">
                        <div style="background: #1a1a1a; padding: 15px; border-radius: 6px; border-left: 3px solid #e50914;">
                            <h4 style="font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 4px;"><i class="fa-solid fa-passport mr-2" style="color: #e50914;"></i> Filming Permits</h4>
                            <p style="font-size: 13px; color: #9ca3af; margin-bottom: 0;">Ministry of Information permits, FF visas, and local municipal clearances.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div style="background: #1a1a1a; padding: 15px; border-radius: 6px; border-left: 3px solid #e50914;">
                            <h4 style="font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 4px;"><i class="fa-solid fa-camera mr-2" style="color: #e50914;"></i> Camera &amp; Gear</h4>
                            <p style="font-size: 13px; color: #9ca3af; margin-bottom: 0;">ARRI Alexa Mini, RED V-Raptor, Sony FX9/FX6, anamorphic lenses, and grips.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div style="background: #1a1a1a; padding: 15px; border-radius: 6px; border-left: 3px solid #e50914;">
                            <h4 style="font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 4px;"><i class="fa-solid fa-helicopter mr-2" style="color: #e50914;"></i> Drone Clearances</h4>
                            <p style="font-size: 13px; color: #9ca3af; margin-bottom: 0;">CAAB and defense permissions for lawful aerial filming across Bangladesh.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div style="background: #1a1a1a; padding: 15px; border-radius: 6px; border-left: 3px solid #e50914;">
                            <h4 style="font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 4px;"><i class="fa-solid fa-users mr-2" style="color: #e50914;"></i> Bilingual Crew</h4>
                            <p style="font-size: 13px; color: #9ca3af; margin-bottom: 0;">Fluent English-speaking assistant directors, fixers, sound recordists, and drivers.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?= site_url('services/support-for-international-production') ?>" class="btn common-btn mr-3">International Filming Guide<span></span></a>
                    <a href="<?= site_url('contact-us') ?>" class="btn common-btn">Consult a Local Fixer<span></span></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="img-box">
                    <img src="<?= upload_url('services/ar-intl-production-support-hero.avif') ?>" alt="Film Fixer in Bangladesh - AR Entertainment" width="600" height="420" loading="lazy" class="img-fluid rounded shadow-lg" style="width: 100%; object-fit: cover; max-height: 440px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 6. AI VIDEO INNOVATION SPOTLIGHT                                          -->
<!-- ========================================================================= -->
<section class="common-sec">
    <div class="container">
        <div class="row align-items-center flex-lg-row-reverse">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Next-Gen Production</div>
                <h2 class="common-heading">Enterprise AI Video Production, Dubbing &amp; Localisation</h2>
                <p>
                    AR Entertainment pioneers hybrid video production in Bangladesh by combining human film directing rigor with state-of-the-art AI synthesis. From photorealistic generative visuals to automated multi-language dubbing and lip-synchronization, we help enterprise brands accelerate content turnaround and lower production costs.
                </p>
                <ul class="list-unstyled" style="color: #cbd5e1; font-size: 14.5px; line-height: 1.8;">
                    <li><i class="fa-solid fa-circle-check mr-2" style="color: #e50914;"></i> <strong>Multilingual AI Dubbing:</strong> Translate English or Bangla video into Arabic, Spanish, French, German, and Hindi with authentic vocal tone and lip-sync.</li>
                    <li><i class="fa-solid fa-circle-check mr-2" style="color: #e50914;"></i> <strong>Rapid FMCG &amp; Performance Variations:</strong> Generate dozens of localized OVC hook variations for A/B testing on Meta and TikTok in hours, not weeks.</li>
                    <li><i class="fa-solid fa-circle-check mr-2" style="color: #e50914;"></i> <strong>Human-in-the-Loop Governance:</strong> Strict IP copyright review, talent consent adherence, and compliance with EU AI Act and global commercial standards.</li>
                </ul>
                <div class="mt-4">
                    <a href="<?= site_url('ai') ?>" class="btn common-btn mr-3">Explore AI Solutions<span></span></a>
                    <a href="<?= site_url('services/ai-video-localisation-dubbing') ?>" class="btn common-btn">AI Dubbing Service<span></span></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="img-box">
                    <img src="<?= upload_url('services/ar-ai-video-creation-hero.avif') ?>" alt="AI Video Production Bangladesh - AR Entertainment" width="600" height="420" loading="lazy" class="img-fluid rounded shadow-lg" style="width: 100%; object-fit: cover; max-height: 440px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 7. RMG & TEXTILE SECTOR SPOTLIGHT                                         -->
<!-- ========================================================================= -->
<section class="common-sec" style="background: #111218;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Apparel &amp; Manufacturing</div>
                <h2 class="common-heading">RMG &amp; Textile Corporate Video: Brand Films for Global Buyers</h2>
                <p>
                    Bangladesh is the second-largest garment exporter in the world. Today's international fashion retailers (Inditex, H&amp;M, Marks &amp; Spencer, Target) demand more than a dry walkthrough of sewing lines—they want authentic corporate films showcasing LEED-certified green factories, ethical labor practices, solar rooftops, and robotic cutting tech.
                </p>
                <p>
                    AR Entertainment creates high-production-value brand films for export-oriented apparel manufacturers in Gazipur, Narayanganj, Chattogram EPZ, and Savar that win international buying contracts and validate sustainability audits.
                </p>
                <div class="mt-4">
                    <a href="<?= site_url('services/corporate-video-for-garment-and-textile-industry-bangladesh') ?>" class="btn common-btn">RMG Video Guide<span></span></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="img-box">
                    <img src="<?= upload_url('services/ar-rmg-textile-video-hero.avif') ?>" alt="RMG & Textile Corporate Video - AR Entertainment" width="600" height="420" loading="lazy" class="img-fluid rounded shadow-lg" style="width: 100%; object-fit: cover; max-height: 440px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 8. FEATURED PORTFOLIO VIDEO SHOWCASE (DYNAMIC)                            -->
<!-- ========================================================================= -->
<section class="common-sec portfolio-sec">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4">
            <div>
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Selected Works</div>
                <h2 class="common-heading mb-0">Featured Portfolio</h2>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="<?= site_url('portfolio') ?>" class="btn common-btn">View All Projects<span></span></a>
            </div>
        </div>
        <p class="text-muted" style="max-width: 800px; font-size: 15px; line-height: 1.7;">
            From nationwide TV commercials for leading corporate conglomerates to international broadcast documentaries and high-energy music videos, explore our featured directorial and production work.
        </p>

        <div class="row mt-4">
            <?php foreach ($featured_portfolio as $item): ?>
                <?php
                    $thumb = !empty($item['thumbnail']) ? upload_url($item['thumbnail']) : '';
                    $video_parsed = parse_video_url($item['video_url']);
                    if (empty($thumb) && !empty($video_parsed['thumbnail_url'])) {
                        $thumb = $video_parsed['thumbnail_url'];
                    }
                    if (empty($thumb)) {
                        $thumb = site_url('images/ar-hero-banner.webp');
                    }
                    $watch_url = !empty($video_parsed['watch_url']) ? $video_parsed['watch_url'] : $item['video_url'];
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div style="background: #1a1a1a; border-radius: 6px; overflow: hidden; height: 100%; display: flex; flex-direction: column; border: 1px solid rgba(255,255,255,0.06); transition: transform 0.3s ease;">
                        <div style="position: relative; overflow: hidden; height: 220px;">
                            <a href="<?= htmlspecialchars($watch_url) ?>" data-fancybox="home-portfolio" data-caption="<?= htmlspecialchars($item['title']) ?> (<?= htmlspecialchars($item['client_name'] ?? 'AR Entertainment') ?>)" style="display: block; width: 100%; height: 100%;">
                                <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($item['title']) ?> - AR Entertainment" width="480" height="270" loading="lazy" style="width: 100%; height: 220px; object-fit: cover; display: block; transition: transform 0.4s ease;">
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 56px; height: 56px; background: rgba(229, 9, 20, 0.92); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(229, 9, 20, 0.5);">
                                    <div style="width: 0; height: 0; border-top: 10px solid transparent; border-bottom: 10px solid transparent; border-left: 17px solid #fff; margin-left: 4px;"></div>
                                </div>
                            </a>
                        </div>
                        <div style="padding: 18px 20px; display: flex; flex-direction: column; flex-grow: 1;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="color: #e50914; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;">
                                    <?= htmlspecialchars($item['category_name'] ?? 'Production') ?>
                                </span>
                                <?php if (!empty($item['year'])): ?>
                                    <span style="color: #64748b; font-size: 12px;"><?= htmlspecialchars($item['year']) ?></span>
                                <?php endif; ?>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 700; line-height: 1.4; margin-bottom: 6px;">
                                <a href="<?= htmlspecialchars($watch_url) ?>" data-fancybox="home-portfolio-text" style="color: #ffffff; text-decoration: none;">
                                    <?= htmlspecialchars($item['title']) ?>
                                </a>
                            </h3>
                            <?php if (!empty($item['client_name'])): ?>
                                <p style="color: #94a3b8; font-size: 13px; margin-bottom: 0;">
                                    <i class="fa-solid fa-building mr-1" style="font-size: 11px; color: #64748b;"></i> <?= htmlspecialchars($item['client_name']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 9. FOUNDER TRUST & EXECUTIVE OVERSIGHT                                    -->
<!-- ========================================================================= -->
<section class="common-sec" style="background: #111218;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <div class="founder-img-wrapper" style="position: relative; display: inline-block;">
                    <img src="<?= upload_url('team/azizul-hoque-shiplu.avif') ?>" alt="Azizul Hoque Shiplu - Founder & Film Director, AR Entertainment" width="320" height="380" loading="lazy" class="img-fluid rounded shadow-lg" style="max-height: 380px; width: auto; object-fit: cover; border: 2px solid rgba(229, 9, 20, 0.4);">
                </div>
            </div>
            <div class="col-lg-8 pl-lg-4">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Directorial Leadership</div>
                <h2 class="common-heading">Directed by Azizul Hoque Shiplu</h2>
                <h4 style="color: #9ca3af; font-size: 17px; margin-bottom: 18px; font-weight: 500;">Founder &amp; Film Director | Commercial Film Director Since 2007</h4>
                <p style="color: #cbd5e1; font-size: 15px; line-height: 1.7;">
                    Azizul Hoque Shiplu is an alumnus of the prestigious <strong>Zahir Raihan Film Institute</strong> and has directed commercial film productions and advertising campaigns across Bangladesh for nearly two decades. Having personally directed more than 100 commercials, corporate AVs, and international documentary shoots, he ensures that every project under AR Entertainment benefits from rigorous visual storytelling and technical command.
                </p>
                <div class="p-3 my-3 rounded" style="background: rgba(229, 9, 20, 0.08); border-left: 4px solid #e50914;">
                    <p class="mb-0 font-italic" style="color: #f1f5f9; font-size: 14px; line-height: 1.6;">
                        "Every frame we capture must tell a memorable story and respect the client's commercial objectives. Whether we are directing a high-profile television commercial in Dhaka or facilitating an international documentary film crew in the Sundarbans, creative integrity and seamless logistics are non-negotiable."
                    </p>
                </div>
                <div class="mt-4">
                    <a href="<?= site_url('meet-the-team') ?>" class="btn common-btn">Meet Our Full Team<span></span></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 10. WHY AR ENTERTAINMENT (CORE VALUE PILLARS)                             -->
<!-- ========================================================================= -->
<section class="common-sec">
    <div class="container">
        <div class="large-heading text-center text-uppercase mb-2">Why AR Entertainment</div>
        <p class="text-center text-muted mx-auto mb-5" style="max-width: 700px; font-size: 15px;">
            We combine high-end cinema equipment, visionary directing, disciplined production management, and transparent pricing.
        </p>

        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div style="background: #1a1a1a; padding: 28px 22px; border-radius: 6px; height: 100%; border: 1px solid rgba(255,255,255,0.06);">
                    <div style="width: 50px; height: 50px; background: rgba(229, 9, 20, 0.12); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                        <i class="fa-solid fa-clapperboard" style="color: #e50914; font-size: 22px;"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 10px;">Directorial Excellence</h3>
                    <p style="color: #9ca3af; font-size: 13.5px; line-height: 1.6; margin-bottom: 0;">
                        Led by seasoned director Azizul Hoque Shiplu with direct personal oversight on every storyboard, lighting setup, and edit.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div style="background: #1a1a1a; padding: 28px 22px; border-radius: 6px; height: 100%; border: 1px solid rgba(255,255,255,0.06);">
                    <div style="width: 50px; height: 50px; background: rgba(229, 9, 20, 0.12); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                        <i class="fa-solid fa-video" style="color: #e50914; font-size: 22px;"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 10px;">Broadcast-Grade Gear</h3>
                    <p style="color: #9ca3af; font-size: 13.5px; line-height: 1.6; margin-bottom: 0;">
                        Filming exclusively on ARRI Alexa, RED Digital Cinema, Sony FX, cinema primes, Ronin gimbals, and licensed drone setups.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div style="background: #1a1a1a; padding: 28px 22px; border-radius: 6px; height: 100%; border: 1px solid rgba(255,255,255,0.06);">
                    <div style="width: 50px; height: 50px; background: rgba(229, 9, 20, 0.12); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                        <i class="fa-solid fa-file-invoice-dollar" style="color: #e50914; font-size: 22px;"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 10px;">Transparent Budgeting</h3>
                    <p style="color: #9ca3af; font-size: 13.5px; line-height: 1.6; margin-bottom: 0;">
                        Itemized line-item quotes with clear shoot-day timelines, zero hidden surcharges, and structured milestone payments.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div style="background: #1a1a1a; padding: 28px 22px; border-radius: 6px; height: 100%; border: 1px solid rgba(255,255,255,0.06);">
                    <div style="width: 50px; height: 50px; background: rgba(229, 9, 20, 0.12); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                        <i class="fa-solid fa-shield-halved" style="color: #e50914; font-size: 22px;"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 10px;">100% Legal Clearance</h3>
                    <p style="color: #9ca3af; font-size: 13.5px; line-height: 1.6; margin-bottom: 0;">
                        Full copyright assignment, talent releases, music licensing, and government ministry permits for worry-free broadcasting.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 11. NATIONWIDE COVERAGE (64 DISTRICTS NETWORK)                            -->
<!-- ========================================================================= -->
<section class="common-sec" style="background: #111218;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Nationwide Reach</div>
                <h2 class="common-heading">Filming Support Across All 64 Districts</h2>
                <p>
                    While based in Dhaka, AR Entertainment maintains established logistics channels and local fixer networks across all 8 administrative divisions of Bangladesh:
                </p>
                <div class="row">
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Dhaka Division</strong></div>
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Chattogram</strong></div>
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Cox's Bazar</strong></div>
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Sylhet Division</strong></div>
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Sundarbans</strong></div>
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Rajshahi Division</strong></div>
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Khulna Division</strong></div>
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Barishal Division</strong></div>
                    <div class="col-6 col-sm-4 mb-2"><i class="fa-solid fa-location-dot mr-2" style="color: #e50914;"></i> <strong>Rangpur Division</strong></div>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div style="background: #1a1a1a; padding: 30px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                    <h3 style="font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 12px;">Planning a Shoot Outside Dhaka?</h3>
                    <p style="color: #9ca3af; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                        We arrange cross-country production vans, hotel blocks, remote power generators, and local police escort coordination wherever your script takes you.
                    </p>
                    <a href="<?= site_url('service-area') ?>" class="btn common-btn">View District Filming Guides<span></span></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 12. CLIENT BRANDS CAROUSEL (DYNAMIC)                                      -->
<!-- ========================================================================= -->
<?php if (!empty($client_brands)): ?>
<section class="common-sec certifications-sec">
    <div class="container-fluid">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Trusted Partnerships</div>
                <h3 class="large-heading mb-4 text-uppercase">Brands &amp; Clients We Work With</h3>
                <p style="color: #9ca3af; font-size: 15px; line-height: 1.7;">
                    From leading corporate conglomerates, FMCG brand managers, and commercial banks to international broadcasters and non-profit organizations, our clients trust AR Entertainment to bring their visual stories to life.
                </p>
            </div>
        </div>
        <div class="certifications-wrapper clients-slider owl-carousel owl-theme">
            <?php foreach ($client_brands as $brand): ?>
                <div class="item">
                    <a href="<?= !empty($brand['website_url']) ? htmlspecialchars($brand['website_url']) : site_url('brands') ?>" <?= !empty($brand['website_url']) ? 'target="_blank" rel="noopener noreferrer"' : '' ?> title="<?= htmlspecialchars($brand['name']) ?>" style="display: flex; align-items: center; justify-content: center; height: 90px; padding: 10px; background: rgba(255,255,255,0.03); border-radius: 6px; border: 1px solid rgba(255,255,255,0.06);">
                        <img loading="lazy" src="<?= upload_url($brand['logo']) ?>" alt="<?= htmlspecialchars($brand['name']) ?> Logo" width="250" height="125" style="max-height: 65px; width: auto; max-width: 160px; object-fit: contain; margin: 0 auto; filter: grayscale(10%) contrast(110%);">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========================================================================= -->
<!-- 13. VERIFIED CUSTOMER REVIEWS (DYNAMIC)                                   -->
<!-- ========================================================================= -->
<?php if (!empty($verified_reviews)): ?>
<section class="common-sec review-sec" style="background: #111218;">
    <div class="container">
        <div class="large-heading text-center text-uppercase">Client Reviews &amp; Testimonials</div>
        <p class="text-center text-muted mx-auto mb-5" style="max-width: 650px; font-size: 15px;">
            Read firsthand feedback from corporate executives, brand managers, and international producers who partnered with AR Entertainment.
        </p>

        <div class="row justify-content-center">
            <?php foreach ($verified_reviews as $rev): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="common-box h-100 d-flex flex-column justify-content-between" style="background: #1a1a1a; border-radius: 6px; padding: 24px; border: 1px solid rgba(255,255,255,0.06);">
                        <div>
                            <div class="review-img-box d-flex align-items-center mb-3">
                                <div class="user-image mr-3">
                                    <?php 
                                        $photo_url = !empty($rev['client_photo']) ? upload_url($rev['client_photo']) : site_url('images/team/azizul-hoque-shiplu.webp');
                                    ?>
                                    <div style="width: 52px; height: 52px; border-radius: 50%; overflow: hidden; background: #2a2a2a; display: flex; align-items: center; justify-content: center; border: 2px solid #e50914;">
                                        <i class="fa-solid fa-user" style="color: #cbd5e1; font-size: 22px;"></i>
                                    </div>
                                </div>
                                <div class="user-content">
                                    <h4 style="font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 2px;"><?= htmlspecialchars($rev['client_name']) ?></h4>
                                    <?php if (!empty($rev['client_company'])): ?>
                                        <span style="font-size: 12.5px; color: #94a3b8;"><?= htmlspecialchars($rev['client_company']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="content mb-3">
                                <p style="color: #cbd5e1; font-size: 13.5px; line-height: 1.6; margin-bottom: 0;">
                                    "<?= htmlspecialchars(truncate_text($rev['review_text'], 170)) ?>"
                                </p>
                            </div>
                        </div>
                        <div class="review-footer d-flex justify-content-between align-items-center pt-3 mt-2" style="border-top: 1px solid rgba(255,255,255,0.08);">
                            <div class="image-icon">
                                <?php if ($rev['source'] === 'google'): ?>
                                    <i class="fa-brands fa-google mr-1" style="color: #ea4335; font-size: 16px;"></i> <span style="font-size: 12px; color: #94a3b8;">Google Review</span>
                                <?php elseif ($rev['source'] === 'goodfirms'): ?>
                                    <i class="fa-solid fa-award mr-1" style="color: #0077b5; font-size: 16px;"></i> <span style="font-size: 12px; color: #94a3b8;">GoodFirms</span>
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-check mr-1" style="color: #22c55e; font-size: 16px;"></i> <span style="font-size: 12px; color: #94a3b8;">Verified Client</span>
                                <?php endif; ?>
                            </div>
                            <div class="review-star" style="color: #eab308; font-size: 13px;">
                                <?php 
                                    $r = (float)($rev['rating'] ?? 5);
                                    for ($i = 1; $i <= 5; $i++): 
                                ?>
                                    <i class="fa fa-star<?= ($i <= $r) ? '' : '-o' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= site_url('reviews') ?>" class="btn common-btn">View All Customer Reviews<span></span></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========================================================================= -->
<!-- 14. LATEST INSIGHTS & PRODUCTION ARTICLES (DYNAMIC)                       -->
<!-- ========================================================================= -->
<?php if (!empty($recent_blogs)): ?>
<section class="common-sec blog-sec">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4">
            <div>
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Studio Insights</div>
                <h2 class="common-heading mb-0">Latest Articles &amp; Production Guides</h2>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="<?= site_url('blog') ?>" class="btn common-btn">Explore All 85+ Articles<span></span></a>
            </div>
        </div>
        <p class="text-muted" style="max-width: 800px; font-size: 15px; line-height: 1.7;">
            In-depth guides on TV commercial budgeting, video ad duration optimization, film fixer regulations in Bangladesh, RMG marketing, and enterprise AI production workflows.
        </p>

        <div class="row mt-4">
            <?php foreach ($recent_blogs as $blog): ?>
                <?php
                    $thumb_src = !empty($blog['thumbnail']) ? upload_url($blog['thumbnail']) : site_url('images/ar-hero-banner.webp');
                    $blog_url = site_url('blog/' . $blog['slug']);
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div style="background: #1a1a1a; border-radius: 6px; overflow: hidden; height: 100%; display: flex; flex-direction: column; border: 1px solid rgba(255,255,255,0.06); transition: transform 0.3s ease;">
                        <div style="overflow: hidden; height: 210px; position: relative;">
                            <a href="<?= $blog_url ?>" style="display: block; width: 100%; height: 100%;">
                                <img src="<?= htmlspecialchars($thumb_src) ?>" alt="<?= htmlspecialchars($blog['title']) ?> - AR Entertainment" width="400" height="210" loading="lazy" style="width: 100%; height: 210px; object-fit: cover; display: block; transition: transform 0.5s ease;">
                            </a>
                            <?php if (!empty($blog['category_name'])): ?>
                                <span style="position: absolute; top: 12px; left: 12px; background: rgba(229, 9, 20, 0.92); color: #fff; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 4px 10px; border-radius: 4px; letter-spacing: 0.5px;">
                                    <?= htmlspecialchars($blog['category_name']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="padding: 22px; display: flex; flex-direction: column; flex: 1;">
                            <div style="font-size: 12px; color: #9ca3af; margin-bottom: 8px;">
                                <i class="fa-regular fa-calendar-days mr-1"></i> <?= format_date($blog['published_at']) ?>
                                <span class="mx-2">&bull;</span>
                                <i class="fa-regular fa-clock mr-1"></i> 5 min read
                            </div>
                            <h3 style="font-size: 17px; line-height: 1.4; margin-bottom: 12px; font-weight: 700;">
                                <a href="<?= $blog_url ?>" style="color: #ffffff; text-decoration: none;">
                                    <?= htmlspecialchars($blog['title']) ?>
                                </a>
                            </h3>
                            <p style="color: #9ca3af; font-size: 13.5px; line-height: 1.6; margin-bottom: 16px; flex: 1;">
                                <?= htmlspecialchars(truncate_text($blog['summary'] ?: strip_tags($blog['content']), 115)) ?>
                            </p>
                            <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-top: auto;">
                                <a href="<?= $blog_url ?>" style="color: #e50914; font-size: 13.5px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center;">
                                    Read Full Article <i class="fa-solid fa-arrow-right ml-2" style="font-size: 11px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========================================================================= -->
<!-- 15. AEO & VOICE-SEARCH FAQ ACCORDION SECTION                              -->
<!-- ========================================================================= -->
<section class="common-sec faq-sec" style="background: #111218;">
    <div class="container">
        <div class="large-heading text-center text-uppercase mb-2">Frequently Asked Questions</div>
        <p class="text-center text-muted mx-auto mb-5" style="max-width: 650px; font-size: 15px;">
            Answers to common questions about filming costs, timelines, fixer permits, and equipment in Bangladesh.
        </p>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion" id="homeFaqAccordion">
                    <!-- FAQ 1 -->
                    <div class="card mb-3" style="background: #1a1a1a; border: 1px solid rgba(255,255,255,0.08); border-radius: 6px;">
                        <div class="card-header p-0" id="faqHeading1" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3" type="button" data-toggle="collapse" data-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1" style="text-decoration: none; font-weight: 700; font-size: 16px;">
                                <span>How much does commercial video production cost in Bangladesh?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="faqCollapse1" class="collapse show" aria-labelledby="faqHeading1" data-parent="#homeFaqAccordion">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                Costs vary based on the format and scope. Digital Online Video Commercials (OVC) typically range from <strong>BDT 80,000 to BDT 850,000</strong>. National Television Commercials (TVC) generally range from <strong>BDT 350,000 to BDT 2,500,000+</strong> depending on casting, shoot days, set construction, ARRI/RED camera packages, and post-production VFX. We provide transparent line-item quotes based on your exact creative brief.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="card mb-3" style="background: #1a1a1a; border: 1px solid rgba(255,255,255,0.08); border-radius: 6px;">
                        <div class="card-header p-0" id="faqHeading2" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3 collapsed" type="button" data-toggle="collapse" data-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2" style="text-decoration: none; font-weight: 700; font-size: 16px;">
                                <span>How does AR Entertainment assist foreign film and documentary crews?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="faqCollapse2" class="collapse" aria-labelledby="faqHeading2" data-parent="#homeFaqAccordion">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                We provide full-service film fixing across Bangladesh, including <strong>Ministry of Information permits, FF journalist/filming visas, customs carnet clearance at Dhaka Airport (DAC), CAAB drone licenses</strong>, bilingual production crew, transport vans, and location scouting across Dhaka, Cox's Bazar, Sundarbans, and Sylhet.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="card mb-3" style="background: #1a1a1a; border: 1px solid rgba(255,255,255,0.08); border-radius: 6px;">
                        <div class="card-header p-0" id="faqHeading3" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3 collapsed" type="button" data-toggle="collapse" data-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3" style="text-decoration: none; font-weight: 700; font-size: 16px;">
                                <span>What is the typical production timeline from script to final delivery?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="faqCollapse3" class="collapse" aria-labelledby="faqHeading3" data-parent="#homeFaqAccordion">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                Standard digital video ad campaigns take <strong>7 to 15 business days</strong> from brief approval to final delivery. Elaborate TVCs and multi-location corporate documentaries generally require <strong>3 to 5 weeks</strong> to allow for comprehensive storyboarding, casting, shooting, color grading, sound mixing, and broadcast clearances.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="card mb-3" style="background: #1a1a1a; border: 1px solid rgba(255,255,255,0.08); border-radius: 6px;">
                        <div class="card-header p-0" id="faqHeading4" style="background: transparent;">
                            <button class="btn btn-link btn-block text-left text-white d-flex justify-content-between align-items-center p-3 collapsed" type="button" data-toggle="collapse" data-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4" style="text-decoration: none; font-weight: 700; font-size: 16px;">
                                <span>Can AR Entertainment handle AI dubbing and multi-language localisation?</span>
                                <i class="fa-solid fa-chevron-down" style="color: #e50914; font-size: 14px;"></i>
                            </button>
                        </div>
                        <div id="faqCollapse4" class="collapse" aria-labelledby="faqHeading4" data-parent="#homeFaqAccordion">
                            <div class="card-body pt-0 text-muted" style="line-height: 1.7; font-size: 14px;">
                                Yes. Our studio specializes in high-fidelity AI video dubbing with lip-synchronization and vocal clone matching. We seamlessly localize Bengali and English video assets into Arabic, Spanish, French, German, and Hindi for international corporate communication and global digital ad targeting.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 16. HIGH-CONVERSION INQUIRY & PROJECT CTA                                 -->
<!-- ========================================================================= -->
<section class="common-sec cta-sec">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div style="color: #e50914; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px;">Let's Create Together</div>
                <h2 class="display-4 font-weight-bold text-white mb-3" style="letter-spacing: -0.5px;">Ready to Produce Your Next Video Campaign?</h2>
                <p class="lead mb-4" style="color: #cbd5e1; font-size: 16px; line-height: 1.7;">
                    Whether you are planning a high-impact TV commercial, a digital OVC, a factory corporate film, or need international fixer support in Dhaka, reach out today for a consultation or quote.
                </p>
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                    <a href="<?= site_url('contact-us') ?>" class="btn common-btn m-2">
                        Get a Free Project Quote<span></span>
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
