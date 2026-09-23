<?php

/**
 * AR Entertainment - Dynamic Blog Listing Template
 * 
 * Lists published articles with real-time category filtering, keyword search,
 * and clean SEO-friendly pagination.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

// -----------------------------------------------------------------------------
// Request Parameters & Pagination Logic
// -----------------------------------------------------------------------------
$current_cat_slug = trim($_GET['category'] ?? '');
$search_query     = trim($_GET['q'] ?? '');
$current_page_num = max(1, (int)($_GET['page'] ?? 1));
$per_page         = 9;

// -----------------------------------------------------------------------------
// Fetch Active Blog Categories with Article Counts
// -----------------------------------------------------------------------------
try {
    $categories_stmt = db()->query("
        SELECT c.id, c.name, c.slug, COUNT(b.id) AS post_count
        FROM categories c
        JOIN blogs b ON b.category_id = c.id AND b.status = 'published'
        WHERE c.type = 'blog'
        GROUP BY c.id, c.name, c.slug
        ORDER BY c.sort_order ASC, c.name ASC
    ");
    $categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// -----------------------------------------------------------------------------
// Build Dynamic Query for Blog Articles
// -----------------------------------------------------------------------------
$where_clauses = ["b.status = 'published'"];
$params        = [];

// 1. Category Filter
$selected_category = null;
if (!empty($current_cat_slug)) {
    foreach ($categories as $cat) {
        if ($cat['slug'] === $current_cat_slug) {
            $selected_category = $cat;
            break;
        }
    }
    if ($selected_category) {
        $where_clauses[] = "c.slug = :category_slug";
        $params[':category_slug'] = $current_cat_slug;
    }
}

// 2. Keyword Search
if (!empty($search_query)) {
    $where_clauses[] = "(b.title LIKE :search OR b.summary LIKE :search OR b.tags LIKE :search)";
    $params[':search'] = '%' . $search_query . '%';
}

$where_sql = implode(' AND ', $where_clauses);

// 3. Count Total Matching Articles
try {
    $count_stmt = db()->prepare("
        SELECT COUNT(b.id) 
        FROM blogs b
        LEFT JOIN categories c ON b.category_id = c.id
        WHERE {$where_sql}
    ");
    foreach ($params as $key => $val) {
        $count_stmt->bindValue($key, $val);
    }
    $count_stmt->execute();
    $total_articles = (int)$count_stmt->fetchColumn();
} catch (PDOException $e) {
    $total_articles = 0;
}

// 4. Calculate Pagination Boundaries
$total_pages = max(1, (int)ceil($total_articles / $per_page));
if ($current_page_num > $total_pages) {
    $current_page_num = $total_pages;
}
$offset = ($current_page_num - 1) * $per_page;

// 5. Fetch Paginated Records
try {
    $articles_stmt = db()->prepare("
        SELECT b.id, b.title, b.slug, b.summary, b.content, b.thumbnail, 
               b.published_at, b.tags, b.views, b.author_name,
               c.name AS category_name, c.slug AS category_slug
        FROM blogs b
        LEFT JOIN categories c ON b.category_id = c.id
        WHERE {$where_sql}
        ORDER BY b.published_at DESC, b.id DESC
        LIMIT :limit OFFSET :offset
    ");
    foreach ($params as $key => $val) {
        $articles_stmt->bindValue($key, $val);
    }
    $articles_stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $articles_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $articles_stmt->execute();
    $articles = $articles_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $articles = [];
}

// -----------------------------------------------------------------------------
// SEO Meta Configuration
// -----------------------------------------------------------------------------
$site_name = get_setting('site_name', SITE_NAME);

if ($selected_category) {
    $page_title       = htmlspecialchars($selected_category['name']) . ' Insights & Production Guides';
    $page_description = "Explore specialized articles, case studies, and practical guides on {$selected_category['name']} in Bangladesh by AR Entertainment.";
} elseif (!empty($search_query)) {
    $page_title       = "Search Results for \"" . htmlspecialchars($search_query) . "\"";
    $page_description = "Search results for \"{$search_query}\" in AR Entertainment's production blog.";
} else {
    $page_title       = 'Video Production, TVC & Film Fixer Blog Bangladesh';
    $page_description = 'Industry insights, commercial filmmaking tips, TVC & OVC production breakdowns, international film fixer guidelines, and AI video strategies from AR Entertainment.';
}

$page_keywords = 'Video Production Blog Bangladesh, TVC Production Tips, Film Fixer Guide, OVC Video Marketing, Commercial Filmmaking Dhaka, AI Video Production';
$current_page  = 'blog';
$canonical_url = site_url('blog' . ($current_page_num > 1 ? '?page=' . $current_page_num : ''));

// Helper to build preserved pagination/filter URLs
function build_blog_query(array $overrides = []): string
{
    $params = [];
    if (!empty($_GET['category'])) {
        $params['category'] = $_GET['category'];
    }
    if (!empty($_GET['q'])) {
        $params['q'] = $_GET['q'];
    }
    if (!empty($_GET['page']) && (int)$_GET['page'] > 1) {
        $params['page'] = $_GET['page'];
    }

    foreach ($overrides as $k => $v) {
        if ($v === null || $v === '' || ($k === 'page' && (int)$v <= 1)) {
            unset($params[$k]);
        } else {
            $params[$k] = $v;
        }
    }

    $qs = http_build_query($params);
    return site_url('blog' . ($qs ? '?' . $qs : ''));
}

// Helper to estimate reading time in minutes
function estimate_reading_time(string $content): int
{
    $word_count = str_word_count(strip_tags($content));
    return max(2, (int)ceil($word_count / 200));
}

// Universal Header & Navigation Partials
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Scoped Category & Banner Refinements Stylesheet -->
<style>
    /* Equalized Hero Banner Height Overrides */
    .inner-banner-area .banner-content {
        height: auto !important;
        min-height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
    }
    
    /* Interactive Category Pill Buttons & Hover Effects */
    .category-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 25px;
        font-weight: 600;
        padding: 8px 18px;
        font-size: 13.5px;
        text-decoration: none !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        background: #181b24;
        border: 1px solid rgba(255,255,255,0.12);
        color: #d1d5db !important;
        cursor: pointer;
    }
    .category-pill:hover {
        background: #232838 !important;
        border-color: rgba(229, 9, 20, 0.7) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(229, 9, 20, 0.25);
    }
    .category-pill:hover .badge {
        background: rgba(229, 9, 20, 0.3) !important;
        color: #ff6b6b !important;
    }
    .category-pill.active {
        background: #e50914 !important;
        border-color: #e50914 !important;
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(229, 9, 20, 0.35);
    }
    .category-pill.active:hover {
        background: #ff1f2d !important;
        border-color: #ff1f2d !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(229, 9, 20, 0.5);
    }
    .category-pill .badge {
        border-radius: 12px;
        font-size: 11px;
        padding: 3px 8px;
        font-weight: 700;
        transition: all 0.25s ease;
    }
</style>

<!-- ========================================================================= -->
<!-- 1. HERO BANNER & BREADCRUMB                                               -->
<!-- ========================================================================= -->
<section class="inner-banner-area">
    <div class="inner-banner" style="background-image: url('<?= site_url('images/banner/blog.jpg') ?>'); background-size: cover; background-position: center; position: relative; padding-top: 155px !important; padding-bottom: 65px !important;">
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(10,12,18,0.75) 0%, rgba(10,12,18,0.94) 100%);"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="banner-content text-center">
                <nav class="breadcrumb-nav mb-3" aria-label="Breadcrumb">
                    <ul class="d-flex align-items-center justify-content-center list-unstyled mb-0" style="gap: 10px; font-size: 14px;">
                        <li><a href="<?= site_url() ?>" style="color: #9ca3af; text-decoration: none;"><i class="fa fa-home mr-1"></i> Home</a></li>
                        <li style="color: #6b7280; user-select: none;">/</li>
                        <?php if ($selected_category): ?>
                            <li><a href="<?= site_url('blog') ?>" style="color: #9ca3af; text-decoration: none;">Blog</a></li>
                            <li style="color: #6b7280; user-select: none;">/</li>
                            <li class="active" style="color: #e50914; font-weight: 700;"><?= htmlspecialchars($selected_category['name']) ?></li>
                        <?php elseif (!empty($search_query)): ?>
                            <li><a href="<?= site_url('blog') ?>" style="color: #9ca3af; text-decoration: none;">Blog</a></li>
                            <li style="color: #6b7280; user-select: none;">/</li>
                            <li class="active" style="color: #e50914; font-weight: 700;">Search: <?= htmlspecialchars($search_query) ?></li>
                        <?php else: ?>
                            <li class="active" style="color: #e50914; font-weight: 700;">Blog &amp; Insights</li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <h1 class="text-white font-weight-bold display-4 mb-3" style="letter-spacing: -0.5px;">
                    <?= ($selected_category) ? htmlspecialchars($selected_category['name']) : 'Filmmaking &amp; Video Insights' ?>
                </h1>
                <p class="lead text-light mx-auto mb-0" style="max-width: 750px; font-size: 17px; line-height: 1.6; color: #d1d5db;">
                    <?= ($selected_category) 
                        ? "Browse authoritative articles, practical production checklists, and guides on " . htmlspecialchars($selected_category['name']) . "." 
                        : "Authoritative guides, budget analysis, and strategic blueprints on commercial video production, international film fixing, and AI content in Bangladesh." 
                    ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 2. FILTER TABS & SEARCH BAR                                               -->
<!-- ========================================================================= -->
<section class="blog-filters-section" style="background: #0d0f17; border-bottom: 1px solid rgba(255,255,255,0.06); padding-top: 26px !important; padding-bottom: 26px !important;">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <!-- Category Pills Carousel / Wrap -->
            <div class="col-lg-8 col-12 mb-3 mb-lg-0">
                <div class="category-pills d-flex flex-wrap align-items-center" style="gap: 12px 10px;">
                    <?php $all_active = empty($current_cat_slug); ?>
                    <a href="<?= build_blog_query(['category' => null, 'page' => 1]) ?>" 
                       class="category-pill <?= $all_active ? 'active' : '' ?>">
                        All Topics 
                        <span class="badge ml-2" style="<?= $all_active ? 'background: rgba(255,255,255,0.25); color: #ffffff;' : 'background: #252836; color: #9ca3af;' ?>">
                            <?= $total_articles ?>
                        </span>
                    </a>
                    <?php foreach ($categories as $cat): 
                        $is_cat_active = ($current_cat_slug === $cat['slug']);
                    ?>
                        <a href="<?= build_blog_query(['category' => $cat['slug'], 'page' => 1]) ?>" 
                           class="category-pill <?= $is_cat_active ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat['name']) ?> 
                            <span class="badge ml-2" style="<?= $is_cat_active ? 'background: rgba(255,255,255,0.25); color: #ffffff;' : 'background: #252836; color: #9ca3af;' ?>">
                                <?= (int)$cat['post_count'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Search Bar Form -->
            <div class="col-lg-4 col-12">
                <form action="<?= site_url('blog') ?>" method="GET" class="d-flex align-items-center position-relative">
                    <?php if (!empty($current_cat_slug)): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($current_cat_slug) ?>">
                    <?php endif; ?>
                    <input type="text" 
                           name="q" 
                           value="<?= htmlspecialchars($search_query) ?>" 
                           placeholder="Search articles, TVC, AI..." 
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

        <!-- Active Filter Summary if Applied -->
        <?php if (!empty($search_query) || !empty($current_cat_slug)): ?>
            <div class="d-flex align-items-center mt-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.05); font-size: 13px; color: #9ca3af;">
                <span>Filtering by:</span>
                <?php if (!empty($current_cat_slug) && $selected_category): ?>
                    <span class="badge badge-info ml-2 px-2 py-1" style="background: rgba(229,9,20,0.15); color: #ff6b6b; border: 1px solid rgba(229,9,20,0.3);">
                        Category: <?= htmlspecialchars($selected_category['name']) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($search_query)): ?>
                    <span class="badge badge-info ml-2 px-2 py-1" style="background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3);">
                        Keyword: "<?= htmlspecialchars($search_query) ?>"
                    </span>
                <?php endif; ?>
                <a href="<?= site_url('blog') ?>" class="ml-3 text-danger font-weight-bold" style="text-decoration: underline;">
                    <i class="fa fa-times-circle mr-1"></i> Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 3. ARTICLE CARDS GRID & PAGINATION                                        -->
<!-- ========================================================================= -->
<section class="common-sec py-5" style="background: #0f1016; min-height: 500px;">
    <div class="container">
        <?php if (!empty($articles)): ?>
            <div class="row">
                <?php foreach ($articles as $post): ?>
                    <?php 
                    $thumb_url  = !empty($post['thumbnail']) ? upload_url($post['thumbnail']) : site_url('images/ar-hero-banner.webp');
                    $post_link  = site_url('blog/' . $post['slug']);
                    $read_time  = estimate_reading_time($post['content'] ?? '');
                    $post_date  = !empty($post['published_at']) ? date('M d, Y', strtotime($post['published_at'])) : date('M d, Y');
                    ?>
                    <div class="col-lg-4 col-md-6 col-12 mb-4 d-flex">
                        <article class="card w-100 d-flex flex-column" style="background: #151822; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; overflow: hidden; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                            <!-- Card Thumbnail -->
                            <div class="card-thumb position-relative" style="height: 220px; overflow: hidden;">
                                <a href="<?= $post_link ?>" aria-label="<?= htmlspecialchars($post['title']) ?>">
                                    <img src="<?= htmlspecialchars($thumb_url) ?>" 
                                         alt="<?= htmlspecialchars($post['title']) ?>" 
                                         loading="lazy" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover; transition: transform 0.4s ease;"
                                         onmouseover="this.style.transform='scale(1.05)'"
                                         onmouseout="this.style.transform='scale(1.0)'">
                                </a>
                                <?php if (!empty($post['category_name'])): ?>
                                    <span class="badge position-absolute" 
                                          style="top: 14px; left: 14px; background: rgba(10,12,18,0.85); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); font-weight: 700; font-size: 11px; padding: 5px 10px; border-radius: 6px; backdrop-filter: blur(8px);">
                                        <?= htmlspecialchars($post['category_name']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <!-- Meta: Date & Read Time -->
                                <div class="d-flex align-items-center justify-content-between mb-2" style="font-size: 12px; color: #9ca3af;">
                                    <span><i class="fa fa-calendar-alt mr-1 text-danger"></i> <?= $post_date ?></span>
                                    <span><i class="fa fa-clock mr-1"></i> <?= $read_time ?> min read</span>
                                </div>

                                <!-- Article Title -->
                                <h2 class="card-title h5 font-weight-bold mb-3" style="line-height: 1.4;">
                                    <a href="<?= $post_link ?>" style="color: #ffffff; text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#e50914'" onmouseout="this.style.color='#ffffff'">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h2>

                                <!-- Excerpt / Summary -->
                                <p class="card-text text-muted mb-4 flex-grow-1" style="font-size: 14px; line-height: 1.6; color: #9ca3af !important;">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($post['summary'] ?: $post['content']), 0, 130, '...')) ?>
                                </p>

                                <!-- Read More CTA Button -->
                                <div class="pt-2" style="border-top: 1px solid rgba(255,255,255,0.06);">
                                    <a href="<?= $post_link ?>" class="d-inline-flex align-items-center font-weight-bold" style="color: #e50914; font-size: 13.5px; text-decoration: none;">
                                        Read Full Article <i class="fa fa-arrow-right ml-2" style="font-size: 12px; transition: transform 0.2s ease;"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination Navigation -->
            <?php if ($total_pages > 1): ?>
                <nav class="d-flex justify-content-center mt-5" aria-label="Blog Article Pagination">
                    <ul class="pagination flex-wrap" style="gap: 6px;">
                        <!-- Prev Page -->
                        <?php if ($current_page_num > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= build_blog_query(['page' => $current_page_num - 1]) ?>" style="background: #181b24; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 8px 16px;">
                                    &larr; Prev
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Page Number Range with Ellipsis -->
                        <?php
                        $start_page = max(1, $current_page_num - 2);
                        $end_page   = min($total_pages, $current_page_num + 2);

                        if ($start_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="' . build_blog_query(['page' => 1]) . '" style="background: #181b24; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">1</a></li>';
                            if ($start_page > 2) {
                                echo '<li class="page-item disabled"><span class="page-link" style="background: transparent; color: #6b7280; border: none;">...</span></li>';
                            }
                        }

                        for ($p = $start_page; $p <= $end_page; $p++):
                            $is_active = ($p === $current_page_num);
                        ?>
                            <li class="page-item <?= $is_active ? 'active' : '' ?>">
                                <a class="page-link" 
                                   href="<?= build_blog_query(['page' => $p]) ?>" 
                                   style="<?= $is_active ? 'background: #e50914; border-color: #e50914; color: #fff; font-weight: 700;' : 'background: #181b24; color: #d1d5db; border: 1px solid rgba(255,255,255,0.1);' ?> border-radius: 8px; min-width: 40px; text-align: center;">
                                    <?= $p ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php
                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<li class="page-item disabled"><span class="page-link" style="background: transparent; color: #6b7280; border: none;">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="' . build_blog_query(['page' => $total_pages]) . '" style="background: #181b24; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">' . $total_pages . '</a></li>';
                        }
                        ?>

                        <!-- Next Page -->
                        <?php if ($current_page_num < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= build_blog_query(['page' => $current_page_num + 1]) ?>" style="background: #181b24; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 8px 16px;">
                                    Next &rarr;
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="text-center mt-3 text-muted" style="font-size: 13px;">
                    Showing page <?= $current_page_num ?> of <?= $total_pages ?> (<?= $total_articles ?> total articles)
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Empty Results State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fa fa-newspaper text-muted" style="font-size: 64px; opacity: 0.3;"></i>
                </div>
                <h2 class="h4 text-white font-weight-bold mb-2">No Articles Found</h2>
                <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
                    We couldn't find any published articles matching your current search or category filter. Try clearing your filters or searching for different keywords.
                </p>
                <a href="<?= site_url('blog') ?>" class="btn common-btn">
                    Reset &amp; View All Articles<span></span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 4. CALL TO ACTION BANNER & FOOTER                                         -->
<!-- ========================================================================= -->
<?php 
require_once __DIR__ . '/includes/cta.php';
require_once __DIR__ . '/includes/footer.php';
?>
