<?php

/**
 * AR Entertainment - Dynamic Video Portfolio Showcase
 * 
 * Displays broadcast TV commercials, digital OVCs, corporate AV brand films,
 * international documentaries, and generative AI video productions.
 * Features real-time category filtering, instant keyword search, live video player modal,
 * and Schema.org ItemList + VideoObject JSON-LD.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

// -----------------------------------------------------------------------------
// 1. Data Retrieval: Categories & Portfolio Items
// -----------------------------------------------------------------------------
$db = db();

// Fetch portfolio categories
$categories_stmt = $db->query("
    SELECT id, name, slug, description, sort_order 
    FROM categories 
    WHERE type = 'portfolio' 
    ORDER BY sort_order ASC
");
$portfolio_categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Map category slugs and aliases
$category_map = [];
foreach ($portfolio_categories as $cat) {
    $category_map[$cat['slug']] = $cat;
}

// Category aliases for intuitive URLs
$category_aliases = [
    'theme-song'         => 'music-video-jingle',
    'music-video'        => 'music-video-jingle',
    'television-commercial' => 'tvc',
    'online-video-commercial' => 'ovc',
    'corporate-video'    => 'corporate-av',
    'ai'                 => 'ai-video',
    'ai-commercial'      => 'ai-video',
    'doc'                => 'documentary',
];

// Fetch all active portfolio items
$items_stmt = $db->query("
    SELECT 
        p.id, p.title, p.slug, p.category_id, p.category_name, 
        p.video_url, p.client_name, p.year, p.thumbnail, 
        p.description, p.is_featured, p.sort_order,
        c.name AS db_cat_name, c.slug AS db_cat_slug
    FROM portfolio p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
    ORDER BY p.is_featured DESC, p.sort_order ASC, p.id DESC
");
$all_portfolio_items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate item counts per category
$category_counts = ['all' => count($all_portfolio_items)];
foreach ($all_portfolio_items as $item) {
    $slug = $item['db_cat_slug'] ?? 'other';
    $category_counts[$slug] = ($category_counts[$slug] ?? 0) + 1;
}

// -----------------------------------------------------------------------------
// 2. Request Parameters & Filtering
// -----------------------------------------------------------------------------
$raw_category = trim($_GET['category'] ?? ($_GET['cat'] ?? ''));
$selected_category = $category_aliases[$raw_category] ?? $raw_category;
$search_query = trim($_GET['q'] ?? '');

// Validate selected category
if (!empty($selected_category) && !isset($category_map[$selected_category])) {
    $selected_category = '';
}

// Filter items for initial server-side render
$filtered_items = [];
foreach ($all_portfolio_items as $item) {
    $item_cat_slug = $item['db_cat_slug'] ?? '';
    
    // Category match
    if (!empty($selected_category) && $item_cat_slug !== $selected_category) {
        continue;
    }
    
    // Keyword search match
    if (!empty($search_query)) {
        $search_text = strtolower($item['title'] . ' ' . $item['client_name'] . ' ' . $item['description'] . ' ' . $item['year']);
        if (!str_contains($search_text, strtolower($search_query))) {
            continue;
        }
    }
    
    $filtered_items[] = $item;
}

// Helper to extract YouTube Video ID
function get_youtube_id(string $url): string {
    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $url, $matches);
    return $matches[1] ?? '';
}

// -----------------------------------------------------------------------------
// 3. SEO Meta Tags & Schema Configuration
// -----------------------------------------------------------------------------
$current_page     = 'portfolio';
$active_cat_name  = !empty($selected_category) && isset($category_map[$selected_category]) 
    ? $category_map[$selected_category]['name'] 
    : '';

$page_title       = !empty($active_cat_name) 
    ? "{$active_cat_name} Portfolio & Commercial Showcase" 
    : "Video Production Portfolio — TVC, OVC, Corporate AV & AI Films";

$page_description = !empty($active_cat_name)
    ? "Explore AR Entertainment's award-winning {$active_cat_name} portfolio in Bangladesh. Premium cinematography, 4K/8K cameras, and broadcast commercials."
    : "Browse AR Entertainment's video portfolio showcasing broadcast TV commercials, viral FMCG OVCs, corporate AV films, NGO documentaries, and AI video productions across Bangladesh.";

$canonical_url    = !empty($selected_category) 
    ? site_url("portfolio?category={$selected_category}") 
    : site_url('portfolio');

$og_image         = !empty($filtered_items[0]['thumbnail']) ? $filtered_items[0]['thumbnail'] : 'images/og.webp';

// Build Schema.org VideoObject list for rich snippets
$schema_items = [];
$position = 1;
foreach (array_slice($all_portfolio_items, 0, 15) as $item) {
    $yt_id = get_youtube_id($item['video_url']);
    $thumb_url = !empty($item['thumbnail']) ? upload_url($item['thumbnail']) : "https://img.youtube.com/vi/{$yt_id}/maxresdefault.jpg";
    
    $schema_items[] = [
        "@type" => "ListItem",
        "position" => $position++,
        "item" => [
            "@type" => "VideoObject",
            "name" => $item['title'],
            "description" => $item['description'],
            "thumbnailUrl" => [$thumb_url],
            "uploadDate" => "{$item['year']}-01-01T08:00:00+06:00",
            "contentUrl" => $item['video_url'],
            "embedUrl" => "https://www.youtube.com/embed/{$yt_id}",
            "publisher" => [
                "@type" => "Organization",
                "name" => SITE_NAME,
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => site_url('images/arentertainment-brand-logo.svg')
                ]
            ]
        ]
    ];
}

$schema_json = [
    "@context" => "https://schema.org",
    "@type" => "CollectionPage",
    "name" => $page_title,
    "description" => $page_description,
    "url" => $canonical_url,
    "mainEntity" => [
        "@type" => "ItemList",
        "itemListElement" => $schema_items
    ]
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Schema.org Structured Data -->
<script type="application/ld+json">
<?= json_encode($schema_json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<style>
/* ========================================================================= */
/* Portfolio Showcase Stylesheet                                            */
/* ========================================================================= */
.portfolio-hero {
    background: radial-gradient(circle at top center, #1f2430 0%, #0c0e14 70%);
    position: relative;
    overflow: hidden;
    padding: 100px 0 60px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.portfolio-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: 50%;
    transform: translateX(-50%);
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, rgba(229, 9, 20, 0.15) 0%, transparent 70%);
    pointer-events: none;
    z-index: 1;
}
.portfolio-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(229, 9, 20, 0.12);
    border: 1px solid rgba(229, 9, 20, 0.35);
    color: #ff333f;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 20px;
}
.filter-nav-wrapper {
    position: sticky;
    top: 75px;
    z-index: 90;
    background: rgba(12, 14, 20, 0.92);
    backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 16px 0;
    transition: all 0.3s ease;
}
.filter-btn {
    background: #181b24;
    color: #9ca3af;
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    text-decoration: none;
}
.filter-btn:hover {
    background: #232734;
    color: #fff;
    border-color: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}
.filter-btn.active {
    background: linear-gradient(135deg, #e50914 0%, #b20710 100%);
    color: #ffffff;
    border-color: #e50914;
    box-shadow: 0 4px 16px rgba(229, 9, 20, 0.4);
}
.filter-btn .count-badge {
    font-size: 11px;
    padding: 2px 7px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.3);
    color: inherit;
}
.portfolio-card {
    background: #151821;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}
.portfolio-card:hover {
    transform: translateY(-8px);
    border-color: rgba(229, 9, 20, 0.4);
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6), 0 0 20px rgba(229, 9, 20, 0.15);
}
.portfolio-thumb-wrapper {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
    background: #090a0f;
    overflow: hidden;
    cursor: pointer;
}
.portfolio-thumb-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.portfolio-card:hover .portfolio-thumb-wrapper img {
    transform: scale(1.08);
}
.portfolio-play-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.85;
    transition: all 0.3s ease;
}
.portfolio-card:hover .portfolio-play-overlay {
    opacity: 1;
    background: rgba(0, 0, 0, 0.3);
}
.play-btn-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(229, 9, 20, 0.95);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 8px 24px rgba(229, 9, 20, 0.5);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    transform: scale(0.9);
}
.portfolio-card:hover .play-btn-circle {
    transform: scale(1.1);
    background: #ff1e27;
}
.card-cat-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: rgba(12, 14, 20, 0.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #f3f4f6;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 4px 10px;
    border-radius: 6px;
    z-index: 2;
}
.card-year-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    background: rgba(229, 9, 20, 0.9);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 6px;
    z-index: 2;
}
.portfolio-body {
    padding: 22px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.portfolio-client {
    font-size: 12px;
    font-weight: 600;
    color: #e50914;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 6px;
}
.portfolio-title {
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.35;
    margin-bottom: 10px;
}
.portfolio-desc {
    font-size: 13.5px;
    color: #9ca3af;
    line-height: 1.55;
    margin-bottom: 16px;
    flex-grow: 1;
}
.portfolio-footer-cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    font-size: 13px;
    color: #d1d5db;
    font-weight: 600;
}
.portfolio-footer-cta i {
    color: #e50914;
    transition: transform 0.2s ease;
}
.portfolio-card:hover .portfolio-footer-cta i {
    transform: translateX(4px);
}

/* ========================================================================= */
/* Video Modal Customization                                                */
/* ========================================================================= */
#videoModal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(0, 0, 0, 0.92);
    backdrop-filter: blur(12px);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}
#videoModal.show {
    display: flex;
    opacity: 1;
}
.video-modal-dialog {
    width: 100%;
    max-width: 960px;
    background: #111319;
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.8), 0 0 40px rgba(229, 9, 20, 0.3);
    position: relative;
    transform: scale(0.92);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
#videoModal.show .video-modal-dialog {
    transform: scale(1);
}
.video-modal-header {
    padding: 16px 24px;
    background: #181b24;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.video-modal-title {
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 15px;
}
.video-modal-close {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #fff;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.2s ease;
}
.video-modal-close:hover {
    background: #e50914;
    transform: scale(1.1);
}
.video-modal-frame-wrapper {
    position: relative;
    padding-top: 56.25%; /* 16:9 */
    background: #000;
}
.video-modal-frame-wrapper iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
}
.video-modal-meta {
    padding: 16px 24px;
    background: #151821;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    font-size: 13.5px;
    color: #9ca3af;
}
</style>

<!-- ========================================================================= -->
<!-- 1. HERO SECTION & PAGE HEADER                                             -->
<!-- ========================================================================= -->
<section class="portfolio-hero text-center">
    <div class="container position-relative" style="z-index: 2;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
            <ol class="breadcrumb bg-transparent p-0 mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url() ?>" class="text-muted text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Portfolio</li>
                <?php if (!empty($active_cat_name)): ?>
                    <li class="breadcrumb-item active text-danger" aria-current="page"><?= htmlspecialchars($active_cat_name) ?></li>
                <?php endif; ?>
            </ol>
        </nav>

        <div class="portfolio-badge">
            <i class="fa-solid fa-clapperboard"></i> Broadcast &amp; Digital Showcase
        </div>

        <h1 class="display-4 font-weight-bold text-white mb-3" style="letter-spacing: -0.5px;">
            <?= !empty($active_cat_name) ? htmlspecialchars($active_cat_name) . ' Showcase' : 'Commercial &amp; Film Showcase' ?>
        </h1>
        
        <p class="lead text-muted mx-auto mb-4" style="max-width: 760px; font-size: 17px; line-height: 1.6;">
            Explore our curated reel of television commercials, digital video ads, corporate brand films, international documentaries, and generative AI productions created for market leaders in Bangladesh and global networks.
        </p>

        <!-- Quick Stats Banner -->
        <div class="d-flex flex-wrap justify-content-center align-items-center mt-4" style="gap: 20px;">
            <div class="d-flex align-items-center text-left px-3 py-2" style="background: rgba(255,255,255,0.04); border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                <i class="fa-solid fa-film text-danger mr-3" style="font-size: 24px;"></i>
                <div>
                    <div class="text-white font-weight-bold" style="font-size: 15px;">100+ Commercials</div>
                    <div class="text-muted" style="font-size: 12px;">Delivered Nationwide</div>
                </div>
            </div>
            <div class="d-flex align-items-center text-left px-3 py-2" style="background: rgba(255,255,255,0.04); border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                <i class="fa-solid fa-camera-movie text-danger mr-3" style="font-size: 24px;"></i>
                <div>
                    <div class="text-white font-weight-bold" style="font-size: 15px;">4K / 8K Cinema</div>
                    <div class="text-muted" style="font-size: 12px;">RED &amp; ARRI Pipeline</div>
                </div>
            </div>
            <div class="d-flex align-items-center text-left px-3 py-2" style="background: rgba(255,255,255,0.04); border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                <i class="fa-solid fa-award text-danger mr-3" style="font-size: 24px;"></i>
                <div>
                    <div class="text-white font-weight-bold" style="font-size: 15px;">Azizul Hoque Shiplu</div>
                    <div class="text-muted" style="font-size: 12px;">Director &amp; Line Producer</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 2. STICKY CATEGORY FILTER & SEARCH BAR                                    -->
<!-- ========================================================================= -->
<div class="filter-nav-wrapper">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between" style="gap: 16px;">
            <!-- Category Filter Pills -->
            <div class="d-flex flex-nowrap overflow-auto py-1 w-100 w-lg-auto" id="portfolioFilterNav" style="gap: 8px; scrollbar-width: none;">
                <!-- All Filter -->
                <button type="button" 
                        class="filter-btn <?= empty($selected_category) ? 'active' : '' ?>" 
                        data-filter="all">
                    <i class="fa-solid fa-layer-group"></i> All Work
                    <span class="count-badge"><?= $category_counts['all'] ?? 0 ?></span>
                </button>

                <?php foreach ($portfolio_categories as $cat): 
                    $cat_slug = $cat['slug'];
                    $cat_count = $category_counts[$cat_slug] ?? 0;
                    $is_active = ($selected_category === $cat_slug);
                ?>
                    <button type="button" 
                            class="filter-btn <?= $is_active ? 'active' : '' ?>" 
                            data-filter="<?= htmlspecialchars($cat_slug) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                        <span class="count-badge"><?= $cat_count ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Instant Search Input -->
            <div class="position-relative w-100 w-lg-auto" style="min-width: 260px;">
                <input type="text" 
                       id="portfolioSearchInput" 
                       class="form-control text-white" 
                       placeholder="Search by client, title, keyword..." 
                       value="<?= htmlspecialchars($search_query) ?>"
                       style="background: #181b24; border: 1px solid rgba(255,255,255,0.12); border-radius: 30px; padding-left: 38px; padding-right: 32px; font-size: 13.5px; height: 42px;">
                <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="left: 14px; top: 14px; font-size: 14px;"></i>
                <button type="button" id="clearSearchBtn" class="btn p-0 position-absolute text-muted" style="right: 14px; top: 11px; display: <?= !empty($search_query) ? 'block' : 'none' ?>; background: none; border: none;">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3. PORTFOLIO SHOWCASE GRID                                                -->
<!-- ========================================================================= -->
<section class="py-5" style="background: #0c0e14; min-height: 500px;">
    <div class="container">
        <!-- Result Stats & Active Filter Info -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
            <div class="text-muted" style="font-size: 14px;">
                Showing <span id="visibleCount" class="text-white font-weight-bold"><?= count($filtered_items) ?></span> of <span class="text-white font-weight-bold"><?= count($all_portfolio_items) ?></span> projects
                <?php if (!empty($selected_category)): ?>
                    in <span class="text-danger font-weight-bold"><?= htmlspecialchars($active_cat_name) ?></span>
                <?php endif; ?>
            </div>

            <!-- Reset Filter Link -->
            <button type="button" id="resetFiltersBtn" class="btn btn-sm text-danger p-0 font-weight-bold" style="background: none; border: none; font-size: 13px; display: <?= (!empty($selected_category) || !empty($search_query)) ? 'inline-block' : 'none' ?>;">
                <i class="fa-solid fa-rotate-left mr-1"></i> Reset Filters
            </button>
        </div>

        <!-- Video Grid Container -->
        <div class="row" id="portfolioGrid">
            <?php if (!empty($all_portfolio_items)): ?>
                <?php foreach ($all_portfolio_items as $item): 
                    $cat_slug = $item['db_cat_slug'] ?? 'other';
                    $cat_name = $item['category_name'] ?? ($item['db_cat_name'] ?? 'Production');
                    $yt_id    = get_youtube_id($item['video_url']);
                    
                    // Determine thumbnail URL
                    if (!empty($item['thumbnail'])) {
                        $thumb_url = upload_url($item['thumbnail']);
                    } elseif (!empty($yt_id)) {
                        $thumb_url = "https://img.youtube.com/vi/{$yt_id}/maxresdefault.jpg";
                    } else {
                        $thumb_url = asset_url('images/placeholder.webp');
                    }

                    // Initial display style based on active filter
                    $is_match = true;
                    if (!empty($selected_category) && $cat_slug !== $selected_category) {
                        $is_match = false;
                    }
                    if (!empty($search_query)) {
                        $search_target = strtolower($item['title'] . ' ' . $item['client_name'] . ' ' . $item['description'] . ' ' . $item['year']);
                        if (!str_contains($search_target, strtolower($search_query))) {
                            $is_match = false;
                        }
                    }
                ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4 portfolio-item" 
                         data-category="<?= htmlspecialchars($cat_slug) ?>"
                         data-title="<?= htmlspecialchars(strtolower($item['title'])) ?>"
                         data-client="<?= htmlspecialchars(strtolower($item['client_name'] ?? '')) ?>"
                         data-year="<?= htmlspecialchars($item['year'] ?? '') ?>"
                         data-search="<?= htmlspecialchars(strtolower($item['title'] . ' ' . ($item['client_name'] ?? '') . ' ' . $item['description'] . ' ' . ($item['year'] ?? ''))) ?>"
                         style="<?= $is_match ? '' : 'display: none;' ?>">
                        
                        <article class="portfolio-card">
                            <!-- Thumbnail Container with Play Overlay -->
                            <div class="portfolio-thumb-wrapper" 
                                 role="button"
                                 tabindex="0"
                                 aria-label="Play video: <?= htmlspecialchars($item['title']) ?>"
                                 data-video-url="<?= htmlspecialchars($item['video_url']) ?>"
                                 data-video-id="<?= htmlspecialchars($yt_id) ?>"
                                 data-video-title="<?= htmlspecialchars($item['title']) ?>"
                                 data-video-client="<?= htmlspecialchars($item['client_name'] ?? '') ?>"
                                 data-video-year="<?= htmlspecialchars($item['year'] ?? '') ?>"
                                 data-video-cat="<?= htmlspecialchars($cat_name) ?>"
                                 data-video-desc="<?= htmlspecialchars($item['description']) ?>">
                                
                                <span class="card-cat-badge"><?= htmlspecialchars($cat_name) ?></span>
                                <?php if (!empty($item['year'])): ?>
                                    <span class="card-year-badge"><?= htmlspecialchars($item['year']) ?></span>
                                <?php endif; ?>

                                <img src="<?= htmlspecialchars($thumb_url) ?>" 
                                     alt="<?= htmlspecialchars($item['title']) ?> - AR Entertainment Showcase" 
                                     loading="lazy"
                                     width="640" 
                                     height="360"
                                     onerror="this.src='https://img.youtube.com/vi/<?= htmlspecialchars($yt_id) ?>/hqdefault.jpg';">
                                
                                <div class="portfolio-play-overlay">
                                    <div class="play-btn-circle">
                                        <i class="fa-solid fa-play ml-1"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content Body -->
                            <div class="portfolio-body">
                                <?php if (!empty($item['client_name'])): ?>
                                    <div class="portfolio-client">
                                        <i class="fa-solid fa-briefcase mr-1"></i> <?= htmlspecialchars($item['client_name']) ?>
                                    </div>
                                <?php endif; ?>

                                <h2 class="portfolio-title">
                                    <?= htmlspecialchars($item['title']) ?>
                                </h2>

                                <p class="portfolio-desc">
                                    <?= htmlspecialchars($item['description']) ?>
                                </p>

                                <div class="portfolio-footer-cta" 
                                     role="button" 
                                     data-video-url="<?= htmlspecialchars($item['video_url']) ?>"
                                     data-video-id="<?= htmlspecialchars($yt_id) ?>"
                                     data-video-title="<?= htmlspecialchars($item['title']) ?>"
                                     data-video-client="<?= htmlspecialchars($item['client_name'] ?? '') ?>"
                                     data-video-year="<?= htmlspecialchars($item['year'] ?? '') ?>"
                                     data-video-cat="<?= htmlspecialchars($cat_name) ?>"
                                     data-video-desc="<?= htmlspecialchars($item['description']) ?>">
                                    <span>Watch Project Showcase</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Empty Results Message -->
        <div id="noPortfolioResults" class="text-center py-5" style="display: <?= empty($filtered_items) ? 'block' : 'none' ?>;">
            <div class="mb-3">
                <i class="fa-solid fa-film-slash text-muted" style="font-size: 54px; opacity: 0.3;"></i>
            </div>
            <h3 class="h4 text-white font-weight-bold mb-2">No Projects Match Your Filter</h3>
            <p class="text-muted mx-auto mb-4" style="max-width: 480px;">
                We couldn't find any showcase projects matching your current category or keyword criteria. Try clearing your search or switching categories.
            </p>
            <button type="button" class="btn common-btn" onclick="document.getElementById('resetFiltersBtn').click();">
                View All Projects<span></span>
            </button>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 4. PRODUCTION CAPABILITIES & GEAR MATRIX                                   -->
<!-- ========================================================================= -->
<section class="py-5" style="background: #111319; border-top: 1px solid rgba(255,255,255,0.08); border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <div class="text-center mb-5">
            <div class="portfolio-badge">
                <i class="fa-solid fa-video"></i> Technical Infrastructure
            </div>
            <h2 class="h2 text-white font-weight-bold mb-2">
                Why Brands &amp; Foreign Networks Choose AR Entertainment
            </h2>
            <p class="text-muted mx-auto" style="max-width: 650px; font-size: 15px;">
                State-of-the-art camera packages, calibrated post-production suites, and complete on-ground fixer logistics in Dhaka.
            </p>
        </div>

        <div class="row">
            <!-- Cinema Cameras -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="p-4 h-100" style="background: #181b24; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                    <div class="d-inline-flex p-3 rounded mb-3" style="background: rgba(229, 9, 20, 0.15); color: #ff2a35;">
                        <i class="fa-solid fa-camera-movie" style="font-size: 24px;"></i>
                    </div>
                    <h3 class="h5 text-white font-weight-bold mb-2">Cinema Cameras</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        RED V-Raptor 8K, ARRI Alexa Mini LF, Sony FX9/FX6, and master Cooke &amp; Zeiss Supreme prime lenses.
                    </p>
                </div>
            </div>

            <!-- Lighting & Grip -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="p-4 h-100" style="background: #181b24; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                    <div class="d-inline-flex p-3 rounded mb-3" style="background: rgba(229, 9, 20, 0.15); color: #ff2a35;">
                        <i class="fa-solid fa-lightbulb" style="font-size: 24px;"></i>
                    </div>
                    <h3 class="h5 text-white font-weight-bold mb-2">Lighting &amp; Grip</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Aputure Electro Storm 1200d, ARRI SkyPanel S60-C, Nanlite Pavotubes, motorized camera sliders and heavy cranes.
                    </p>
                </div>
            </div>

            <!-- Audio & Color Suite -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="p-4 h-100" style="background: #181b24; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                    <div class="d-inline-flex p-3 rounded mb-3" style="background: rgba(229, 9, 20, 0.15); color: #ff2a35;">
                        <i class="fa-solid fa-sliders" style="font-size: 24px;"></i>
                    </div>
                    <h3 class="h5 text-white font-weight-bold mb-2">DaVinci Color &amp; Atmos</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        ACES color-calibrated DaVinci Resolve Studio grading suites, 5.1/7.1 acoustic mixing, and Sennheiser shotgun audio.
                    </p>
                </div>
            </div>

            <!-- Turnkey Line Fixing -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="p-4 h-100" style="background: #181b24; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                    <div class="d-inline-flex p-3 rounded mb-3" style="background: rgba(229, 9, 20, 0.15); color: #ff2a35;">
                        <i class="fa-solid fa-passport" style="font-size: 24px;"></i>
                    </div>
                    <h3 class="h5 text-white font-weight-bold mb-2">Turnkey Line Fixing</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Ministry of Information 'FF' filming visas, CAAB drone clearances, NBR ATA Carnet customs, and 64-district safety.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 5. LIVE VIDEO PLAYER MODAL                                                -->
<!-- ========================================================================= -->
<div id="videoModal" role="dialog" aria-modal="true" aria-labelledby="modalVideoTitle">
    <div class="video-modal-dialog">
        <!-- Modal Header -->
        <div class="video-modal-header">
            <h2 id="modalVideoTitle" class="video-modal-title">Project Video Title</h2>
            <button type="button" class="video-modal-close" id="closeVideoModal" aria-label="Close Video Player">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- 16:9 Responsive Video Frame -->
        <div class="video-modal-frame-wrapper">
            <iframe id="videoIframe" 
                    src="" 
                    title="Video Player" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
            </iframe>
        </div>

        <!-- Modal Footer Meta -->
        <div class="video-modal-meta">
            <div>
                <span id="modalClient" class="font-weight-bold text-danger mr-3"></span>
                <span id="modalCategory" class="badge badge-dark mr-2" style="background: #232734; border: 1px solid rgba(255,255,255,0.1);"></span>
                <span id="modalYear" class="text-muted"></span>
            </div>
            <div>
                <a href="<?= site_url('contact-us') ?>" class="btn btn-sm btn-outline-danger font-weight-bold" style="border-radius: 20px; font-size: 12px; padding: 4px 14px;">
                    Enquire About Similar Project <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 6. JAVASCRIPT: FILTERING, SEARCH & MODAL ENGINE                          -->
<!-- ========================================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('#portfolioFilterNav .filter-btn');
    const portfolioItems = document.querySelectorAll('#portfolioGrid .portfolio-item');
    const searchInput = document.getElementById('portfolioSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');
    const visibleCountEl = document.getElementById('visibleCount');
    const noResultsEl = document.getElementById('noPortfolioResults');

    let activeFilter = '<?= htmlspecialchars($selected_category ?: 'all') ?>';
    let searchQuery = '<?= htmlspecialchars($search_query) ?>'.toLowerCase().trim();

    // -------------------------------------------------------------------------
    // Core Filter & Search Execution
    // -------------------------------------------------------------------------
    function applyFilters() {
        let visibleCount = 0;

        portfolioItems.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            const itemSearchText = item.getAttribute('data-search') || '';

            const matchesCategory = (activeFilter === 'all' || itemCategory === activeFilter);
            const matchesSearch = (!searchQuery || itemSearchText.includes(searchQuery));

            if (matchesCategory && matchesSearch) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Update counts and visibility
        if (visibleCountEl) visibleCountEl.textContent = visibleCount;
        if (noResultsEl) noResultsEl.style.display = (visibleCount === 0) ? 'block' : 'none';

        // Toggle search clear button
        if (clearSearchBtn) {
            clearSearchBtn.style.display = searchQuery ? 'block' : 'none';
        }

        // Toggle reset button
        if (resetFiltersBtn) {
            resetFiltersBtn.style.display = (activeFilter !== 'all' || searchQuery !== '') ? 'inline-block' : 'none';
        }

        // Update browser URL without reload
        const url = new URL(window.location);
        if (activeFilter !== 'all') {
            url.searchParams.set('category', activeFilter);
        } else {
            url.searchParams.delete('category');
            url.searchParams.delete('cat');
        }

        if (searchQuery) {
            url.searchParams.set('q', searchQuery);
        } else {
            url.searchParams.delete('q');
        }
        window.history.replaceState({}, '', url);
    }

    // -------------------------------------------------------------------------
    // Event: Category Pill Clicks
    // -------------------------------------------------------------------------
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            activeFilter = this.getAttribute('data-filter') || 'all';
            applyFilters();
        });
    });

    // -------------------------------------------------------------------------
    // Event: Live Search Input with Debounce
    // -------------------------------------------------------------------------
    let debounceTimer;
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                searchQuery = this.value.toLowerCase().trim();
                applyFilters();
            }, 180);
        });
    }

    // Clear search
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            searchQuery = '';
            applyFilters();
        });
    }

    // Reset all filters
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function () {
            activeFilter = 'all';
            searchQuery = '';
            if (searchInput) searchInput.value = '';

            filterButtons.forEach(b => {
                if (b.getAttribute('data-filter') === 'all') {
                    b.classList.add('active');
                } else {
                    b.classList.remove('active');
                }
            });

            applyFilters();
        });
    }

    // -------------------------------------------------------------------------
    // Video Modal Engine
    // -------------------------------------------------------------------------
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('videoIframe');
    const modalTitle = document.getElementById('modalVideoTitle');
    const modalClient = document.getElementById('modalClient');
    const modalCategory = document.getElementById('modalCategory');
    const modalYear = document.getElementById('modalYear');
    const closeModalBtn = document.getElementById('closeVideoModal');

    function openModal(triggerElement) {
        const videoId = triggerElement.getAttribute('data-video-id');
        const videoUrl = triggerElement.getAttribute('data-video-url');
        const title = triggerElement.getAttribute('data-video-title') || 'Project Showcase';
        const client = triggerElement.getAttribute('data-video-client') || '';
        const cat = triggerElement.getAttribute('data-video-cat') || '';
        const year = triggerElement.getAttribute('data-video-year') || '';

        // Extract ID if not directly provided
        let targetId = videoId;
        if (!targetId && videoUrl) {
            const match = videoUrl.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
            if (match) targetId = match[1];
        }

        if (!targetId) return;

        // Set content and autoplay embed
        if (modalTitle) modalTitle.textContent = title;
        if (modalClient) modalClient.textContent = client ? `Client: ${client}` : '';
        if (modalCategory) modalCategory.textContent = cat;
        if (modalYear) modalYear.textContent = year;

        if (iframe) {
            iframe.src = `https://www.youtube.com/embed/${targetId}?autoplay=1&rel=0&modestbranding=1`;
        }

        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            if (iframe) iframe.src = ''; // Tear down iframe to stop audio
            document.body.style.overflow = '';
        }, 300);
    }

    // Delegate click for video triggers
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-video-url]');
        if (trigger) {
            e.preventDefault();
            openModal(trigger);
        }
    });

    // Close on X button click
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    // Close on backdrop click
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    // Close on ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
            closeModal();
        }
    });
});
</script>

<?php 
require_once __DIR__ . '/includes/cta.php';
require_once __DIR__ . '/includes/footer.php';
?>
