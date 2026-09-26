<?php

/**
 * AR Entertainment - Dynamic Service Areas & 64 District Filming Guides
 * 
 * Provides:
 * 1. Nationwide 64-District Filming Directory Hub with Division Filters & Live Keyword Search.
 * 2. Dedicated Single District Filming Guide (/service-area/{district_slug}) with local logistics,
 *    filming hotspots, permit guidance, nearby districts, and district-specific FAQs.
 * 3. Dynamic Schema.org JSON-LD (CollectionPage + ItemList + Service + Place + FAQPage).
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

// -----------------------------------------------------------------------------
// 1. Administrative Division Mapping for 64 Districts
// -----------------------------------------------------------------------------
$division_districts_map = [
    'Dhaka' => ['Dhaka', 'Gazipur', 'Narayanganj', 'Tangail', 'Manikganj', 'Munshiganj', 'Narsingdi', 'Faridpur', 'Gopalganj', 'Madaripur', 'Rajbari', 'Shariatpur', 'Kishoreganj'],
    'Chattogram' => ['Chattogram', "Cox's Bazar", 'Cumilla', 'Brahmanbaria', 'Chandpur', 'Feni', 'Lakshmipur', 'Noakhali', 'Khagrachari', 'Rangamati', 'Bandarban'],
    'Sylhet' => ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
    'Rajshahi' => ['Rajshahi', 'Bogura', 'Joypurhat', 'Naogaon', 'Natore', 'Chapai Nawabganj', 'Pabna', 'Sirajganj'],
    'Khulna' => ['Khulna', 'Bagerhat', 'Chuadanga', 'Jashore', 'Jhenaidah', 'Kushtia', 'Magura', 'Meherpur', 'Narail', 'Satkhira'],
    'Barishal' => ['Barishal', 'Barguna', 'Bhola', 'Jhalokati', 'Patuakhali', 'Pirojpur'],
    'Rangpur' => ['Rangpur', 'Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Thakurgaon'],
    'Mymensingh' => ['Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur']
];

// Helper to determine division from city name
function get_district_division(string $cityName, array $divisionMap): string
{
    foreach ($divisionMap as $division => $districts) {
        if (in_array($cityName, $districts, true)) {
            return $division;
        }
    }
    return 'National';
}

// -----------------------------------------------------------------------------
// 2. Resolve Route Mode (Single District View vs. Directory Hub)
// -----------------------------------------------------------------------------
$slug = trim($_GET['slug'] ?? '');
$is_single_view = !empty($slug);
$district = null;
$division = 'National';

if ($is_single_view) {
    try {
        $stmt = db()->prepare("SELECT * FROM service_areas WHERE (slug = ? OR slug = ? OR city_name = ?) AND status = 'active' LIMIT 1");
        $stmt->execute([$slug, 'video-production-company-in-' . $slug, $slug]);
        $district = $stmt->fetch();
    } catch (PDOException $e) {
        $district = null;
    }

    if ($district) {
        $division = get_district_division($district['city_name'], $division_districts_map);
    }
}

// -----------------------------------------------------------------------------
// 3. Fetch All 64 Districts for Hub or Directory Navigation
// -----------------------------------------------------------------------------
$all_districts = [];
try {
    $stmt = db()->query("SELECT id, title, slug, city_name, summary, sort_order, status FROM service_areas WHERE status = 'active' ORDER BY sort_order ASC, city_name ASC");
    $all_districts = $stmt->fetchAll();
} catch (PDOException $e) {
    $all_districts = [];
}

// Group districts by division
$grouped_by_division = [];
foreach ($division_districts_map as $divName => $divDistricts) {
    $grouped_by_division[$divName] = [];
}
foreach ($all_districts as $d) {
    $div = get_district_division($d['city_name'], $division_districts_map);
    if (!isset($grouped_by_division[$div])) {
        $grouped_by_division[$div] = [];
    }
    $grouped_by_division[$div][] = $d;
}

// -----------------------------------------------------------------------------
// 4. District Image Resolver
// -----------------------------------------------------------------------------
function get_district_image_url(string $cityName, string $slug): string
{
    $clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $cityName));
    $clean_slug = str_replace('video-production-company-in-', '', $slug);
    
    $possible_files = [
        "images/service-area/{$clean_slug}-video-production.webp",
        "images/service-area/{$clean_slug}-video-production.jpg",
        "images/service-area/{$clean_name}-video-production.webp",
        "images/service-area/{$clean_name}-video-production.jpg",
    ];

    foreach ($possible_files as $file) {
        if (file_exists(ROOT_PATH . '/' . $file)) {
            return site_url($file);
        }
    }

    return site_url('images/banner/service-area.jpg');
}

// -----------------------------------------------------------------------------
// 5. Dynamic SEO Meta & Schema Setup
// -----------------------------------------------------------------------------
if ($is_single_view && $district) {
    $page_title       = !empty($district['meta_title']) ? $district['meta_title'] : "Video Production & Film Fixer in {$district['city_name']} | AR Entertainment";
    $page_description = !empty($district['meta_description']) ? $district['meta_description'] : "Top video production company and film fixer in {$district['city_name']}, {$division} Division, Bangladesh. Commercials, corporate AVs, documentaries, and cinema drone filming.";
    $canonical_url    = site_url('service-area/' . $district['slug']);
    $og_type          = 'article';
    $og_image         = get_district_image_url($district['city_name'], $district['slug']);
    $current_page     = 'service-area';
} elseif ($is_single_view && !$district) {
    // 404 handler for invalid district
    http_response_code(404);
    $page_title       = "District Filming Guide Not Found | AR Entertainment";
    $page_description = "The requested district filming guide could not be located. Explore our 64 district video production coverage across Bangladesh.";
    $canonical_url    = site_url('service-area');
    $current_page     = 'service-area';
} else {
    // Main 64-District Hub
    $page_title       = "Video Production & Film Fixer Service Areas in Bangladesh (64 Districts) | AR Entertainment";
    $page_description = "AR Entertainment provides nationwide video production and film fixer services across all 64 districts in Bangladesh. In-house ARRI/RED camera packages, heavy-lift drones, bilingual fixers, and local filming permits.";
    $page_keywords    = "Video Production Bangladesh, 64 Districts Video Production, Film Fixer Bangladesh, Dhaka Film Fixer, Chittagong Video Production, Sylhet Filming, Coxs Bazar Video Production";
    $canonical_url    = site_url('service-area');
    $og_type          = 'website';
    $og_image         = site_url('images/banner/service-area.jpg');
    $current_page     = 'service-area';
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="MainSiteContent" id="main-content">

<?php if ($is_single_view && !$district): ?>
    <!-- ===================================================================== -->
    <!-- 404 NOT FOUND VIEW: Invalid District Slug                            -->
    <!-- ===================================================================== -->
    <section class="inner-banner-area position-relative">
        <div class="inner-banner" style="background-image: linear-gradient(rgba(11, 12, 18, 0.88), rgba(11, 12, 18, 0.95)), url('<?= site_url('images/banner/service-area.jpg') ?>'); background-size: cover; background-position: center; padding: 100px 0 60px;">
            <div class="container text-center">
                <span class="badge badge-danger text-uppercase px-3 py-2 mb-3" style="font-size: 11px; letter-spacing: 1.5px; border-radius: 4px;">404 Error</span>
                <h1 class="text-white font-weight-bold display-4 mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">District Guide Not Found</h1>
                <p class="text-muted lead mx-auto" style="max-width: 650px;">The district filming guide you are looking for may have been moved or updated. Browse all 64 districts below.</p>
                <div class="mt-4">
                    <a href="<?= site_url('service-area') ?>" class="btn btn-danger px-4 py-3 font-weight-bold" style="border-radius: 6px; box-shadow: 0 4px 15px rgba(229,9,20,0.4);">
                        <i class="fa fa-map-marker-alt mr-2"></i> View All 64 Districts Directory
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php elseif ($is_single_view && $district): ?>
    <!-- ===================================================================== -->
    <!-- SINGLE DISTRICT FILMING GUIDE VIEW                                    -->
    <!-- ===================================================================== -->
    <?php
    $district_img = get_district_image_url($district['city_name'], $district['slug']);
    
    // Clean and modernize district content
    $raw_content = $district['content'] ?? '';
    $clean_content = str_replace(['Libanza Films', 'Libanza'], ['AR Entertainment', 'AR Entertainment'], $raw_content);
    // Replace legacy static html links with clean php dynamic routes
    $clean_content = preg_replace('/href=["\'](?:(?:\.\.\/)*)service-area\/video-production-company-in-([a-zA-Z0-9_-]+)\.html["\']/i', 'href="' . site_url('service-area/video-production-company-in-$1') . '"', $clean_content);
    $clean_content = preg_replace('/href=["\'](?:(?:\.\.\/)*)services\/([a-zA-Z0-9_\-\/]+)\.html["\']/i', 'href="' . site_url('services/$1') . '"', $clean_content);
    $clean_content = preg_replace('/href=["\'](?:(?:\.\.\/)*)about-us\/contact-us\.html["\']/i', 'href="' . site_url('contact-us') . '"', $clean_content);
    
    // Surrounding / Neighboring districts in the same division
    $neighboring = [];
    if (isset($grouped_by_division[$division])) {
        foreach ($grouped_by_division[$division] as $other) {
            if ($other['id'] !== $district['id']) {
                $neighboring[] = $other;
            }
        }
    }
    // If division has fewer than 3 neighbors, supplement from all districts
    if (count($neighboring) < 3) {
        foreach ($all_districts as $other) {
            if ($other['id'] !== $district['id'] && !in_array($other, $neighboring, true)) {
                $neighboring[] = $other;
                if (count($neighboring) >= 4) break;
            }
        }
    }
    ?>

    <!-- District Hero Header -->
    <section class="inner-banner-area position-relative">
        <div class="inner-banner" style="background-image: linear-gradient(180deg, rgba(8, 9, 14, 0.85) 0%, rgba(8, 9, 14, 0.95) 100%), url('<?= htmlspecialchars($district_img) ?>'); background-size: cover; background-position: center; padding: 120px 0 70px;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <!-- Breadcrumbs -->
                        <nav aria-label="breadcrumb" class="mb-3">
                            <ol class="breadcrumb bg-transparent p-0 m-0" style="font-size: 13px;">
                                <li class="breadcrumb-item"><a href="<?= site_url() ?>" class="text-white-50"><i class="fa fa-home mr-1"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= site_url('service-area') ?>" class="text-white-50">Service Area</a></li>
                                <li class="breadcrumb-item active text-danger font-weight-bold" aria-current="page"><?= htmlspecialchars($district['city_name']) ?></li>
                            </ol>
                        </nav>

                        <div class="d-inline-flex align-items-center mb-3">
                            <span class="badge badge-danger text-uppercase px-3 py-1 mr-2" style="font-size: 11px; letter-spacing: 1px; border-radius: 4px; background-color: #e50914;">
                                <?= htmlspecialchars($division) ?> Division
                            </span>
                            <span class="badge badge-dark text-uppercase px-3 py-1" style="font-size: 11px; letter-spacing: 1px; border-radius: 4px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                                <i class="fa fa-check-circle text-success mr-1"></i> In-House Crew Active
                            </span>
                        </div>

                        <h1 class="text-white font-weight-bold mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(28px, 4vw, 44px); line-height: 1.2;">
                            Video Production Company in <?= htmlspecialchars($district['city_name']) ?>
                        </h1>
                        <p class="text-white-50 lead mb-4" style="font-size: 16px; max-width: 720px; line-height: 1.6;">
                            <?= htmlspecialchars($district['summary'] ?: "Professional commercial, corporate video, documentary filming, and local fixer services across {$district['city_name']} and neighboring upazilas by AR Entertainment.") ?>
                        </p>

                        <div class="d-flex flex-wrap align-items-center" style="gap: 12px;">
                            <a href="<?= site_url('contact-us') ?>" class="btn btn-danger px-4 py-2 font-weight-bold" style="border-radius: 6px; box-shadow: 0 4px 18px rgba(229,9,20,0.45); font-size: 14px;">
                                <i class="fa fa-paper-plane mr-2"></i> Get a Quote for <?= htmlspecialchars($district['city_name']) ?>
                            </a>
                            <a href="tel:<?= htmlspecialchars($clean_phone) ?>" class="btn btn-outline-light px-4 py-2 font-weight-bold" style="border-radius: 6px; font-size: 14px; border-color: rgba(255,255,255,0.3);">
                                <i class="fa fa-phone mr-2 text-danger"></i> <?= htmlspecialchars($contact_phone) ?>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4 d-none d-lg-block">
                        <div class="card p-3" style="background: rgba(18, 20, 29, 0.85); backdrop-filter: blur(14px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                            <div class="position-relative overflow-hidden rounded mb-3" style="height: 190px;">
                                <img src="<?= htmlspecialchars($district_img) ?>" alt="Filming in <?= htmlspecialchars($district['city_name']) ?>" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                                <div class="position-absolute" style="bottom: 8px; left: 8px; background: rgba(0,0,0,0.75); padding: 3px 8px; border-radius: 4px; font-size: 11px; color: #fff;">
                                    <i class="fa fa-camera mr-1 text-danger"></i> <?= htmlspecialchars($district['city_name']) ?> Filming Hub
                                </div>
                            </div>
                            <div class="text-white-50" style="font-size: 12.5px; line-height: 1.5;">
                                <div class="d-flex justify-content-between py-1 border-bottom border-secondary" style="border-color: rgba(255,255,255,0.08) !important;">
                                    <span>Division:</span>
                                    <strong class="text-white"><?= htmlspecialchars($division) ?></strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom border-secondary" style="border-color: rgba(255,255,255,0.08) !important;">
                                    <span>Deployment:</span>
                                    <strong class="text-success">Same-Day Dispatch</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1" style="border-color: rgba(255,255,255,0.08) !important;">
                                    <span>Permit Support:</span>
                                    <strong class="text-white">Police & Local Govt</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content & Logistics Details -->
    <section class="py-5" style="background-color: #0b0c12; color: #e0e0e0;">
        <div class="container">
            <div class="row">
                <!-- Left Main Content Body -->
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="bg-dark p-4 p-md-5 rounded" style="background: rgba(18, 20, 29, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
                        
                        <div class="district-html-content" style="font-size: 15px; line-height: 1.75; color: #d0d2db;">
                            <?= $clean_content ?>
                        </div>

                        <!-- Technical Capabilities Callout -->
                        <div class="mt-5 p-4 rounded" style="background: linear-gradient(135deg, rgba(229,9,20,0.12) 0%, rgba(18,20,29,0.9) 100%); border: 1px solid rgba(229,9,20,0.3); border-radius: 10px;">
                            <h4 class="text-white font-weight-bold mb-3" style="font-size: 18px;">
                                <i class="fa fa-film text-danger mr-2"></i> Cinema Equipment & Gear Packages for <?= htmlspecialchars($district['city_name']) ?>
                            </h4>
                            <p class="mb-3" style="font-size: 14px; color: #c4c7d4;">
                                AR Entertainment equips projects in <strong><?= htmlspecialchars($district['city_name']) ?></strong> with certified broadcast and cinema equipment dispatched from our Dhaka logistics depot:
                            </p>
                            <div class="row" style="font-size: 13px;">
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center text-white"><i class="fa fa-check text-danger mr-2"></i> ARRI Alexa Mini LF & RED V-Raptor 8K</div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center text-white"><i class="fa fa-check text-danger mr-2"></i> DJI Inspire 3 & Mavic 3 Cine Drones</div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center text-white"><i class="fa fa-check text-danger mr-2"></i> Cooke, Zeiss & Angénieux Cinema Primes</div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center text-white"><i class="fa fa-check text-danger mr-2"></i> Sound Devices 833 & Wireless Audio Kits</div>
                                </div>
                            </div>
                        </div>

                        <!-- District Filming FAQs Accordion -->
                        <div class="mt-5 pt-4 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                            <h3 class="text-white font-weight-bold mb-4" style="font-size: 22px;">
                                <i class="fa fa-question-circle text-danger mr-2"></i> Frequently Asked Questions for <?= htmlspecialchars($district['city_name']) ?> Filming
                            </h3>
                            <div class="accordion" id="districtFaqAccordion">
                                <div class="card mb-3" style="background: rgba(26, 28, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; overflow: hidden;">
                                    <div class="card-header p-0" id="headingOne" style="background: transparent; border: none;">
                                        <button class="btn btn-link text-white text-left font-weight-bold w-100 p-3 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#faqOne" aria-expanded="true" aria-controls="faqOne" style="text-decoration: none; font-size: 15px;">
                                            <span>What video production services does AR Entertainment provide in <?= htmlspecialchars($district['city_name']) ?>?</span>
                                            <i class="fa fa-chevron-down text-danger" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                    <div id="faqOne" class="collapse show" aria-labelledby="headingOne" data-parent="#districtFaqAccordion">
                                        <div class="card-body pt-0 px-3 pb-3" style="font-size: 14px; color: #b0b4c3;">
                                            We offer end-to-end commercial video production in <?= htmlspecialchars($district['city_name']) ?>, including TV Commercials (TVCs), Online Video Commercials (OVCs), corporate brand films, factory compliance profiles, NGO documentaries, music videos, and aerial drone cinematography.
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3" style="background: rgba(26, 28, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; overflow: hidden;">
                                    <div class="card-header p-0" id="headingTwo" style="background: transparent; border: none;">
                                        <button class="btn btn-link text-white text-left font-weight-bold w-100 p-3 d-flex justify-content-between align-items-center collapsed" type="button" data-toggle="collapse" data-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo" style="text-decoration: none; font-size: 15px;">
                                            <span>How are local filming permits and administrative clearances arranged in <?= htmlspecialchars($district['city_name']) ?>?</span>
                                            <i class="fa fa-chevron-down text-danger" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                    <div id="faqTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#districtFaqAccordion">
                                        <div class="card-body pt-0 px-3 pb-3" style="font-size: 14px; color: #b0b4c3;">
                                            Our bilingual location managers coordinate all necessary permissions with the local Deputy Commissioner (DC) office, Superintendent of Police (SP), District Information Office, and local municipal authorities to guarantee smooth, unhindered shooting.
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3" style="background: rgba(26, 28, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; overflow: hidden;">
                                    <div class="card-header p-0" id="headingThree" style="background: transparent; border: none;">
                                        <button class="btn btn-link text-white text-left font-weight-bold w-100 p-3 d-flex justify-content-between align-items-center collapsed" type="button" data-toggle="collapse" data-target="#faqThree" aria-expanded="false" aria-controls="faqThree" style="text-decoration: none; font-size: 15px;">
                                            <span>Can foreign film crews and international broadcasters hire fixer support in <?= htmlspecialchars($district['city_name']) ?>?</span>
                                            <i class="fa fa-chevron-down text-danger" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                    <div id="faqThree" class="collapse" aria-labelledby="headingThree" data-parent="#districtFaqAccordion">
                                        <div class="card-body pt-0 px-3 pb-3" style="font-size: 14px; color: #b0b4c3;">
                                            Yes. AR Entertainment frequently serves as the trusted local line producer and fixer for foreign production companies, providing FF Visa recommendation letters, customs equipment clearance (carnet facilitation), bilingual assistant directors, location scouting, transport logistics, and security.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Sidebar: Quick Actions, Services & Surrounding Districts -->
                <div class="col-lg-4">
                    
                    <!-- Quick Quote Action Box -->
                    <div class="card mb-4 text-center p-4" style="background: linear-gradient(180deg, #181a24 0%, #12141d 100%); border: 1px solid rgba(229,9,20,0.3); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: rgba(229,9,20,0.15); color: #e50914; font-size: 22px;">
                            <i class="fa fa-video"></i>
                        </div>
                        <h4 class="text-white font-weight-bold mb-2" style="font-size: 18px;">Filming in <?= htmlspecialchars($district['city_name']) ?>?</h4>
                        <p class="text-white-50 mb-3" style="font-size: 13px;">Tell us your script requirements and get a detailed location, crew, and gear estimate within 24 hours.</p>
                        <a href="<?= site_url('contact-us') ?>" class="btn btn-danger btn-block font-weight-bold py-2 mb-2" style="border-radius: 6px;">
                            <i class="fa fa-calculator mr-1"></i> Request a Quote
                        </a>
                        <a href="tel:<?= htmlspecialchars($clean_phone) ?>" class="btn btn-outline-light btn-block font-weight-bold py-2" style="border-radius: 6px; font-size: 13px; border-color: rgba(255,255,255,0.2);">
                            <i class="fa fa-phone-alt mr-1 text-danger"></i> Direct Call: <?= htmlspecialchars($contact_phone) ?>
                        </a>
                    </div>

                    <!-- Core Production Services -->
                    <div class="card mb-4 p-4" style="background: #141622; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                        <h5 class="text-white font-weight-bold mb-3 border-bottom pb-2" style="border-color: rgba(255,255,255,0.08) !important; font-size: 16px;">
                            <i class="fa fa-star text-danger mr-2"></i> Core Services Available
                        </h5>
                        <ul class="list-unstyled mb-0" style="font-size: 13.5px;">
                            <li class="py-2 border-bottom d-flex justify-content-between align-items-center" style="border-color: rgba(255,255,255,0.05) !important;">
                                <a href="<?= site_url('services/tv-commercial') ?>" class="text-white-50 text-decoration-none hover-danger">TV Commercial (TVC)</a>
                                <i class="fa fa-angle-right text-danger"></i>
                            </li>
                            <li class="py-2 border-bottom d-flex justify-content-between align-items-center" style="border-color: rgba(255,255,255,0.05) !important;">
                                <a href="<?= site_url('services/online-video-commercial') ?>" class="text-white-50 text-decoration-none hover-danger">Online Video Commercial (OVC)</a>
                                <i class="fa fa-angle-right text-danger"></i>
                            </li>
                            <li class="py-2 border-bottom d-flex justify-content-between align-items-center" style="border-color: rgba(255,255,255,0.05) !important;">
                                <a href="<?= site_url('services/corporate-av') ?>" class="text-white-50 text-decoration-none hover-danger">Corporate AV & Brand Films</a>
                                <i class="fa fa-angle-right text-danger"></i>
                            </li>
                            <li class="py-2 border-bottom d-flex justify-content-between align-items-center" style="border-color: rgba(255,255,255,0.05) !important;">
                                <a href="<?= site_url('services/documentary') ?>" class="text-white-50 text-decoration-none hover-danger">Documentary & NGO Films</a>
                                <i class="fa fa-angle-right text-danger"></i>
                            </li>
                            <li class="py-2 d-flex justify-content-between align-items-center">
                                <a href="<?= site_url('services/support-for-international-production') ?>" class="text-white-50 text-decoration-none hover-danger">Film Fixer in Bangladesh</a>
                                <i class="fa fa-angle-right text-danger"></i>
                            </li>
                        </ul>
                    </div>

                    <!-- Neighboring & Nearby Districts in Division -->
                    <div class="card p-4" style="background: #141622; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                        <h5 class="text-white font-weight-bold mb-3 border-bottom pb-2" style="border-color: rgba(255,255,255,0.08) !important; font-size: 16px;">
                            <i class="fa fa-compass text-danger mr-2"></i> Nearby Districts in <?= htmlspecialchars($division) ?>
                        </h5>
                        <div class="d-flex flex-wrap" style="gap: 8px;">
                            <?php foreach ($neighboring as $neighbor): ?>
                                <a href="<?= site_url('service-area/' . $neighbor['slug']) ?>" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; font-size: 12px;">
                                    <i class="fa fa-map-marker-alt text-danger mr-1"></i> <?= htmlspecialchars($neighbor['city_name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3 pt-3 border-top text-center" style="border-color: rgba(255,255,255,0.08) !important;">
                            <a href="<?= site_url('service-area') ?>" class="text-danger font-weight-bold" style="font-size: 12.5px;">
                                <i class="fa fa-globe mr-1"></i> View All 64 Districts in Bangladesh ➔
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

<?php else: ?>
    <!-- ===================================================================== -->
    <!-- MAIN 64-DISTRICT SERVICE AREA DIRECTORY HUB VIEW                      -->
    <!-- ===================================================================== -->

    <!-- Directory Hero Banner -->
    <section class="inner-banner-area position-relative">
        <div class="inner-banner" style="background-image: linear-gradient(180deg, rgba(8, 9, 14, 0.90) 0%, rgba(8, 9, 14, 0.97) 100%), url('<?= site_url('images/banner/service-area.jpg') ?>'); background-size: cover; background-position: center; padding: 120px 0 70px;">
            <div class="container text-center">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb bg-transparent p-0 m-0 justify-content-center" style="font-size: 13px;">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>" class="text-white-50"><i class="fa fa-home mr-1"></i> Home</a></li>
                        <li class="breadcrumb-item active text-danger font-weight-bold" aria-current="page">Service Area</li>
                    </ol>
                </nav>
                <span class="badge badge-danger text-uppercase px-3 py-2 mb-3" style="font-size: 11px; letter-spacing: 2px; border-radius: 4px; background: #e50914;">
                    Nationwide Production Coverage
                </span>
                <h1 class="text-white font-weight-bold display-4 mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(30px, 4.5vw, 48px); line-height: 1.15;">
                    Video Production & Film Fixer in All 64 Districts
                </h1>
                <p class="text-white-50 lead mx-auto mb-4" style="max-width: 820px; font-size: 16px; line-height: 1.6;">
                    From the corporate skyscrapers of Dhaka and tea estates of Sylhet to the coastal stretches of Cox's Bazar and heritage zamindar palaces of Rajshahi, AR Entertainment provides in-house cinema gear, licensed drone operators, and bilingual fixers nationwide.
                </p>

                <!-- Overview Stats Bar -->
                <div class="row justify-content-center mt-4">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                            <div class="font-weight-bold text-danger" style="font-size: 28px;">64</div>
                            <div class="text-white-50" style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Districts Covered</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                            <div class="font-weight-bold text-white" style="font-size: 28px;">8</div>
                            <div class="text-white-50" style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Divisions Active</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                            <div class="font-weight-bold text-danger" style="font-size: 28px;">100%</div>
                            <div class="text-white-50" style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">In-House Gear</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                            <div class="font-weight-bold text-white" style="font-size: 28px;">24/7</div>
                            <div class="text-white-50" style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Fixer Response</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Directory: Filter Pills & Instant Keyword Search -->
    <section class="py-5" style="background-color: #0c0d14; min-height: 600px;">
        <div class="container">
            
            <!-- Filter Controls Bar -->
            <div class="card p-3 p-md-4 mb-5" style="background: rgba(18, 20, 29, 0.95); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-3 mb-lg-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0 text-white-50" style="border-color: rgba(255,255,255,0.15);">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                            <input type="text" id="districtSearchInput" class="form-control text-white border-left-0" placeholder="Search any district (e.g. Dhaka, Chittagong, Sylhet, Bogura)..." style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.15); font-size: 14px;" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <!-- Division Filter Pills -->
                        <div class="d-flex flex-wrap align-items-center justify-content-lg-end" style="gap: 6px;" id="divisionPillsContainer">
                            <button class="btn btn-sm btn-danger active division-pill" data-division="all" style="border-radius: 20px; font-size: 12px; padding: 5px 12px;">
                                All Districts (64)
                            </button>
                            <?php foreach ($grouped_by_division as $divName => $distList): ?>
                                <button class="btn btn-sm btn-outline-light division-pill" data-division="<?= htmlspecialchars($divName) ?>" style="border-radius: 20px; font-size: 12px; padding: 5px 12px; border-color: rgba(255,255,255,0.2);">
                                    <?= htmlspecialchars($divName) ?> (<?= count($distList) ?>)
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Districts Grid -->
            <div id="districtsGridContainer" class="row">
                <?php foreach ($all_districts as $d): ?>
                    <?php 
                    $div = get_district_division($d['city_name'], $division_districts_map);
                    $img = get_district_image_url($d['city_name'], $d['slug']);
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4 district-card-item" data-division="<?= htmlspecialchars($div) ?>" data-city="<?= strtolower($d['city_name']) ?>" data-summary="<?= strtolower($d['summary']) ?>">
                        <div class="card h-100 position-relative overflow-hidden text-white" style="background: rgba(18, 20, 29, 0.85); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;">
                            
                            <!-- District Image Thumbnail -->
                            <div class="position-relative overflow-hidden" style="height: 160px;">
                                <img src="<?= htmlspecialchars($img) ?>" alt="Video Production in <?= htmlspecialchars($d['city_name']) ?>" class="w-100 h-100 object-fit-cover" loading="lazy" style="object-fit: cover; transition: transform 0.4s ease;">
                                <div class="position-absolute" style="top: 10px; right: 10px;">
                                    <span class="badge badge-dark px-2 py-1" style="background: rgba(0,0,0,0.75); border: 1px solid rgba(255,255,255,0.2); font-size: 11px; border-radius: 4px;">
                                        <?= htmlspecialchars($div) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h3 class="font-weight-bold mb-2 text-white" style="font-size: 18px; font-family: 'Plus Jakarta Sans', sans-serif;">
                                        <?= htmlspecialchars($d['city_name']) ?>
                                    </h3>
                                    <p class="text-white-50 mb-3" style="font-size: 13px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?= htmlspecialchars($d['summary'] ?: "Cinema video production, TVCs, corporate AVs, and local fixer services in {$d['city_name']}, Bangladesh.") ?>
                                    </p>
                                </div>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(255,255,255,0.08) !important;">
                                    <a href="<?= site_url('service-area/' . $d['slug']) ?>" class="btn btn-sm btn-outline-danger font-weight-bold px-3 py-1" style="border-radius: 4px; font-size: 12px;">
                                        Filming Specs <i class="fa fa-arrow-right ml-1"></i>
                                    </a>
                                    <span class="text-white-50" style="font-size: 11px;">
                                        <i class="fa fa-circle text-success mr-1" style="font-size: 8px;"></i> Available
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Zero Results Fallback -->
            <div id="noDistrictsFound" class="text-center py-5 d-none">
                <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(255,255,255,0.05); color: #e50914; font-size: 24px;">
                    <i class="fa fa-search"></i>
                </div>
                <h4 class="text-white font-weight-bold">No District Found</h4>
                <p class="text-muted">No district matched your keyword search. Try searching for another district or division.</p>
                <button id="resetSearchBtn" class="btn btn-sm btn-danger px-3 py-2 font-weight-bold" style="border-radius: 4px;">
                    Reset Search Filter
                </button>
            </div>

        </div>
    </section>

    <!-- Nationwide Logistics & In-House Production Matrix -->
    <section class="py-5" style="background-color: #08090e; color: #fff;">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-danger text-uppercase font-weight-bold" style="font-size: 12px; letter-spacing: 2px;">Nationwide Capabilities</span>
                <h2 class="font-weight-bold mt-2" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(24px, 3.5vw, 36px);">
                    Why Productions Rely on AR Entertainment Across Bangladesh
                </h2>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="p-4 rounded h-100 text-center" style="background: rgba(18, 20, 29, 0.8); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                        <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 52px; height: 52px; background: rgba(229,9,20,0.15); color: #e50914; font-size: 20px;">
                            <i class="fa fa-camera-retro"></i>
                        </div>
                        <h4 class="font-weight-bold text-white mb-2" style="font-size: 16px;">In-House Cinema Packages</h4>
                        <p class="text-white-50" style="font-size: 13px; line-height: 1.5;">ARRI Alexa Mini LF, RED V-Raptor 8K, Cooke/Zeiss primes, and cinema lighting dispatched directly to any district.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="p-4 rounded h-100 text-center" style="background: rgba(18, 20, 29, 0.8); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                        <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 52px; height: 52px; background: rgba(229,9,20,0.15); color: #e50914; font-size: 20px;">
                            <i class="fa fa-id-card"></i>
                        </div>
                        <h4 class="font-weight-bold text-white mb-2" style="font-size: 16px;">Permits & Police Clearance</h4>
                        <p class="text-white-50" style="font-size: 13px; line-height: 1.5;">Direct coordination with Ministry of Information, Civil Aviation (CAAB drone permits), and local police administrations.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="p-4 rounded h-100 text-center" style="background: rgba(18, 20, 29, 0.8); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                        <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 52px; height: 52px; background: rgba(229,9,20,0.15); color: #e50914; font-size: 20px;">
                            <i class="fa fa-users"></i>
                        </div>
                        <h4 class="font-weight-bold text-white mb-2" style="font-size: 16px;">Bilingual Line Producers</h4>
                        <p class="text-white-50" style="font-size: 13px; line-height: 1.5;">Experienced fixers and ADs fluent in English and regional dialects, ensuring flawless communication on remote shoots.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="p-4 rounded h-100 text-center" style="background: rgba(18, 20, 29, 0.8); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
                        <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 52px; height: 52px; background: rgba(229,9,20,0.15); color: #e50914; font-size: 20px;">
                            <i class="fa fa-helicopter"></i>
                        </div>
                        <h4 class="font-weight-bold text-white mb-2" style="font-size: 16px;">Expedition & Transport</h4>
                        <p class="text-white-50" style="font-size: 13px; line-height: 1.5;">Dedicated 4WD production vehicles, river speedboats, generator trucks, and remote crew accommodation logistics.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nationwide Filming FAQs -->
    <section class="py-5" style="background-color: #0b0c12; color: #fff;">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-danger text-uppercase font-weight-bold" style="font-size: 12px; letter-spacing: 2px;">FAQ Guide</span>
                <h2 class="font-weight-bold mt-2" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(24px, 3.5vw, 36px);">
                    Frequently Asked Questions About Filming in Bangladesh
                </h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion" id="nationwideFaqAccordion">
                        
                        <div class="card mb-3" style="background: rgba(26, 28, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; overflow: hidden;">
                            <div class="card-header p-0" id="nwHead1" style="background: transparent; border: none;">
                                <button class="btn btn-link text-white text-left font-weight-bold w-100 p-3 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#nwFaq1" aria-expanded="true" aria-controls="nwFaq1" style="text-decoration: none; font-size: 15px;">
                                    <span>Does AR Entertainment have crews capable of filming in all 64 districts?</span>
                                    <i class="fa fa-chevron-down text-danger" style="font-size: 12px;"></i>
                                </button>
                            </div>
                            <div id="nwFaq1" class="collapse show" aria-labelledby="nwHead1" data-parent="#nationwideFaqAccordion">
                                <div class="card-body pt-0 px-3 pb-3" style="font-size: 14px; color: #b0b4c3;">
                                    Yes. Headquartered in Dhaka, AR Entertainment operates a nationwide mobile production infrastructure. We routinely deploy cinema crews, drones, and lighting trucks to all 64 administrative districts across Bangladesh for commercials, documentaries, and international broadcast shoots.
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3" style="background: rgba(26, 28, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; overflow: hidden;">
                            <div class="card-header p-0" id="nwHead2" style="background: transparent; border: none;">
                                <button class="btn btn-link text-white text-left font-weight-bold w-100 p-3 d-flex justify-content-between align-items-center collapsed" type="button" data-toggle="collapse" data-target="#nwFaq2" aria-expanded="false" aria-controls="nwFaq2" style="text-decoration: none; font-size: 15px;">
                                    <span>How far in advance should we contact AR Entertainment for remote district shoots?</span>
                                    <i class="fa fa-chevron-down text-danger" style="font-size: 12px;"></i>
                                </button>
                            </div>
                            <div id="nwFaq2" class="collapse" aria-labelledby="nwHead2" data-parent="#nationwideFaqAccordion">
                                <div class="card-body pt-0 px-3 pb-3" style="font-size: 14px; color: #b0b4c3;">
                                    For standard local shoots (Dhaka, Gazipur, Narayanganj), we can deploy within 24 to 48 hours. For remote districts requiring specialized drone permits, Forest Department permissions (Sundarbans), or Hill Tracts clearances (Bandarban, Rangamati), we recommend contacting us 2 to 3 weeks in advance.
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3" style="background: rgba(26, 28, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; overflow: hidden;">
                            <div class="card-header p-0" id="nwHead3" style="background: transparent; border: none;">
                                <button class="btn btn-link text-white text-left font-weight-bold w-100 p-3 d-flex justify-content-between align-items-center collapsed" type="button" data-toggle="collapse" data-target="#nwFaq3" aria-expanded="false" aria-controls="nwFaq3" style="text-decoration: none; font-size: 15px;">
                                    <span>What equipment packages can be transported across districts?</span>
                                    <i class="fa fa-chevron-down text-danger" style="font-size: 12px;"></i>
                                </button>
                            </div>
                            <div id="nwFaq3" class="collapse" aria-labelledby="nwHead3" data-parent="#nationwideFaqAccordion">
                                <div class="card-body pt-0 px-3 pb-3" style="font-size: 14px; color: #b0b4c3;">
                                    We transport complete ARRI Alexa Mini LF, RED V-Raptor 8K, Sony FX9/FX6 packages, full anamorphic and spherical prime sets, wireless video transmitters, motorized gimbals, sound mixer kits, and Aputure/Nanlux LED lighting trucks to any location accessible by road or waterway.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client-side Interactive Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('districtSearchInput');
            const divisionPills = document.querySelectorAll('.division-pill');
            const cardItems = document.querySelectorAll('.district-card-item');
            const noResults = document.getElementById('noDistrictsFound');
            const resetBtn = document.getElementById('resetSearchBtn');

            let currentDivision = 'all';
            let currentQuery = '';

            function filterDistricts() {
                let visibleCount = 0;

                cardItems.forEach(function(card) {
                    const cardDiv = card.getAttribute('data-division');
                    const cardCity = card.getAttribute('data-city') || '';
                    const cardSummary = card.getAttribute('data-summary') || '';

                    const matchesDivision = (currentDivision === 'all' || cardDiv.toLowerCase() === currentDivision.toLowerCase());
                    const matchesQuery = (currentQuery === '' || cardCity.includes(currentQuery) || cardSummary.includes(currentQuery));

                    if (matchesDivision && matchesQuery) {
                        card.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        card.classList.add('d-none');
                    }
                });

                if (visibleCount === 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            }

            // Keyword Search Debounce
            let debounceTimer;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        currentQuery = searchInput.value.trim().toLowerCase();
                        filterDistricts();
                    }, 120);
                });
            }

            // Division Filter Pill Click
            divisionPills.forEach(function(pill) {
                pill.addEventListener('click', function() {
                    divisionPills.forEach(p => {
                        p.classList.remove('btn-danger', 'active');
                        p.classList.add('btn-outline-light');
                    });
                    this.classList.add('btn-danger', 'active');
                    this.classList.remove('btn-outline-light');

                    currentDivision = this.getAttribute('data-division');
                    filterDistricts();
                });
            });

            // Reset Search Button
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    currentQuery = '';
                    currentDivision = 'all';
                    divisionPills.forEach(p => {
                        if (p.getAttribute('data-division') === 'all') {
                            p.classList.add('btn-danger', 'active');
                            p.classList.remove('btn-outline-light');
                        } else {
                            p.classList.remove('btn-danger', 'active');
                            p.classList.add('btn-outline-light');
                        }
                    });
                    filterDistricts();
                });
            }
        });
    </script>
<?php endif; ?>

</main>

<!-- ========================================================================= -->
<!-- SCHEMA.ORG STRUCTURED DATA (JSON-LD)                                      -->
<!-- ========================================================================= -->
<?php if ($is_single_view && $district): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Video Production Company in <?= addslashes($district['city_name']) ?>",
  "provider": {
    "@type": "LocalBusiness",
    "name": "AR Entertainment",
    "telephone": "<?= addslashes($contact_phone) ?>",
    "url": "<?= site_url() ?>"
  },
  "areaServed": {
    "@type": "AdministrativeArea",
    "name": "<?= addslashes($district['city_name']) ?>",
    "containedInPlace": {
      "@type": "Country",
      "name": "Bangladesh"
    }
  },
  "description": "<?= addslashes($district['summary'] ?: $page_description) ?>",
  "url": "<?= htmlspecialchars($canonical_url) ?>"
}
</script>
<?php else: ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "Video Production & Film Fixer Service Areas in Bangladesh (64 Districts)",
  "description": "<?= addslashes($page_description) ?>",
  "url": "<?= site_url('service-area') ?>",
  "mainEntity": {
    "@type": "ItemList",
    "itemListElement": [
      <?php 
      $schema_items = [];
      $idx = 1;
      foreach (array_slice($all_districts, 0, 30) as $d) {
          $schema_items[] = json_encode([
              "@type" => "ListItem",
              "position" => $idx++,
              "name" => "Video Production in " . $d['city_name'],
              "url" => site_url('service-area/' . $d['slug'])
          ], JSON_UNESCAPED_SLASHES);
      }
      echo implode(",\n      ", $schema_items);
      ?>
    ]
  }
}
</script>
<?php endif; ?>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
