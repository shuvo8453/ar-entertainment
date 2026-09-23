<?php

/**
 * AR Entertainment - Dynamic Single Blog Post Template
 * 
 * Fetches and displays published articles by slug with automatic view tracking,
 * author attribution, dynamic Schema.org JSON-LD (BlogPosting & BreadcrumbList),
 * high-contrast typography, social sharing, and related article recommendations.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

// -----------------------------------------------------------------------------
// 1. Resolve Article by Slug
// -----------------------------------------------------------------------------
$slug = trim($_GET['slug'] ?? '');

if (empty($slug)) {
    header("Location: " . site_url('blog'), true, 301);
    exit;
}

try {
    $article_stmt = db()->prepare("
        SELECT b.*, c.name AS category_name, c.slug AS category_slug
        FROM blogs b
        LEFT JOIN categories c ON b.category_id = c.id
        WHERE b.slug = :slug AND b.status = 'published'
        LIMIT 1
    ");
    $article_stmt->execute([':slug' => $slug]);
    $article = $article_stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $article = null;
}

// -----------------------------------------------------------------------------
// 2. Handle 404 If Article Not Found
// -----------------------------------------------------------------------------
if (!$article) {
    http_response_code(404);
    $page_title       = 'Article Not Found | ' . SITE_NAME;
    $page_description = 'The requested film or video production article could not be found. Browse our latest insights and production guides.';
    $current_page     = 'blog';

    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/navbar.php';
    ?>
    <section class="py-5 text-center text-white" style="background: #0b0d14; min-height: 65vh; display: flex; align-items: center;">
        <div class="container py-5">
            <div class="mb-4">
                <span style="font-size: 72px; color: #e50914;"><i class="fa-solid fa-film"></i></span>
            </div>
            <h1 class="display-4 font-weight-bold mb-3">Article Not Found</h1>
            <p class="lead text-muted mx-auto mb-4" style="max-width: 580px; color: #9ca3af !important;">
                The production guide or article you are looking for has been relocated or archived. Explore our full library of filmmaking insights and TVC case studies below.
            </p>
            <div class="d-flex justify-content-center flex-wrap" style="gap: 12px;">
                <a href="<?= site_url('blog') ?>" class="btn btn-danger btn-lg px-4 py-3 font-weight-bold" style="background: #e50914; border-radius: 8px;">
                    <i class="fa fa-arrow-left mr-2"></i> Browse All Articles
                </a>
                <a href="<?= site_url() ?>" class="btn btn-outline-light btn-lg px-4 py-3 font-weight-bold" style="border-radius: 8px; border-color: rgba(255,255,255,0.2);">
                    Return to Home
                </a>
            </div>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/cta.php';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// -----------------------------------------------------------------------------
// 3. Session-Guarded View Counter Increment
// -----------------------------------------------------------------------------
$view_session_key = 'viewed_blog_' . $article['id'];
if (empty($_SESSION[$view_session_key])) {
    $_SESSION[$view_session_key] = true;
    try {
        db()->prepare("UPDATE blogs SET views = views + 1 WHERE id = ?")->execute([$article['id']]);
        $article['views'] = (int)$article['views'] + 1;
    } catch (PDOException $e) {
        // Silently skip if DB write fails
    }
}

// -----------------------------------------------------------------------------
// 4. Fetch Author Profile from Team Members
// -----------------------------------------------------------------------------
$author_name = $article['author_name'] ?: 'Azizul Hoque Shiplu';
try {
    $author_stmt = db()->prepare("
        SELECT id, name, slug, role_title, photo, bio 
        FROM team_members 
        WHERE (name = :name OR slug = :slug) AND status = 'active'
        LIMIT 1
    ");
    $author_stmt->execute([':name' => $author_name, ':slug' => slugify($author_name)]);
    $author = $author_stmt->fetch(PDO::FETCH_ASSOC);

    // Fallback to Founder if no specific team member match
    if (!$author) {
        $founder_stmt = db()->query("SELECT id, name, slug, role_title, photo, bio FROM team_members WHERE id = 1 LIMIT 1");
        $author = $founder_stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $author = null;
}

$author_display_name = $author['name'] ?? $author_name;
$author_role         = $author['role_title'] ?? 'Founder & Film Director';
$author_bio          = $author['bio'] ?? 'Celebrated Bangladeshi filmmaker and commercial director with over 19 years of industry experience. Leading TV commercial, documentary, and international fixer productions at AR Entertainment.';
$author_photo_url    = !empty($author['photo']) ? upload_url($author['photo']) : site_url('images/ar-team-avatar.png');

// -----------------------------------------------------------------------------
// 5. Fetch Related Articles (Same Category or Recent)
// -----------------------------------------------------------------------------
try {
    $related_stmt = db()->prepare("
        SELECT b.id, b.title, b.slug, b.thumbnail, b.published_at, b.content, b.summary,
               c.name AS category_name, c.slug AS category_slug
        FROM blogs b
        LEFT JOIN categories c ON b.category_id = c.id
        WHERE b.status = 'published' AND b.id != :current_id
        ORDER BY (b.category_id = :cat_id) DESC, b.published_at DESC
        LIMIT 3
    ");
    $related_stmt->execute([
        ':current_id' => $article['id'],
        ':cat_id'     => (int)($article['category_id'] ?? 0)
    ]);
    $related_articles = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $related_articles = [];
}

// -----------------------------------------------------------------------------
// 6. Fetch Popular Articles & Categories for Sidebar
// -----------------------------------------------------------------------------
try {
    $popular_stmt = db()->query("
        SELECT id, title, slug, thumbnail, views, published_at
        FROM blogs
        WHERE status = 'published'
        ORDER BY views DESC, published_at DESC
        LIMIT 5
    ");
    $popular_articles = $popular_stmt->fetchAll(PDO::FETCH_ASSOC);

    $sidebar_cat_stmt = db()->query("
        SELECT c.id, c.name, c.slug, COUNT(b.id) AS post_count
        FROM categories c
        JOIN blogs b ON b.category_id = c.id AND b.status = 'published'
        WHERE c.type = 'blog'
        GROUP BY c.id, c.name, c.slug
        ORDER BY c.sort_order ASC, c.name ASC
    ");
    $sidebar_categories = $sidebar_cat_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $popular_articles   = [];
    $sidebar_categories = [];
}

// -----------------------------------------------------------------------------
// 7. Previous & Next Articles
// -----------------------------------------------------------------------------
$prev_article = null;
$next_article = null;
try {
    $prev_stmt = db()->prepare("
        SELECT title, slug FROM blogs 
        WHERE status = 'published' AND published_at < :pub_at 
        ORDER BY published_at DESC LIMIT 1
    ");
    $prev_stmt->execute([':pub_at' => $article['published_at'] ?: date('Y-m-d H:i:s')]);
    $prev_article = $prev_stmt->fetch(PDO::FETCH_ASSOC);

    $next_stmt = db()->prepare("
        SELECT title, slug FROM blogs 
        WHERE status = 'published' AND published_at > :pub_at 
        ORDER BY published_at ASC LIMIT 1
    ");
    $next_stmt->execute([':pub_at' => $article['published_at'] ?: date('Y-m-d H:i:s')]);
    $next_article = $next_stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Optional navigation fallback
}

// -----------------------------------------------------------------------------
// 8. Dynamic SEO & Schema.org JSON-LD Configuration
// -----------------------------------------------------------------------------
$page_title       = !empty($article['meta_title']) ? $article['meta_title'] : $article['title'];
$page_description = !empty($article['meta_description']) 
    ? $article['meta_description'] 
    : ($article['summary'] ?: mb_strimwidth(strip_tags($article['content']), 0, 160, '...'));
$page_keywords    = !empty($article['meta_keywords']) ? $article['meta_keywords'] : ($article['tags'] ?: 'AR Entertainment, Video Production Bangladesh, Film Fixer Dhaka');
$canonical_url    = site_url('blog/' . $article['slug']);
$og_image         = !empty($article['og_image']) ? $article['og_image'] : ($article['thumbnail'] ?: 'images/ar-hero-banner.webp');
$og_type          = 'article';
$current_page     = 'blog';

// Estimated reading time & dates
$word_count       = str_word_count(strip_tags($article['content']));
$read_time        = max(2, (int)ceil($word_count / 200));
$pub_iso_date     = date('c', strtotime($article['published_at'] ?: $article['created_at']));
$mod_iso_date     = date('c', strtotime($article['updated_at'] ?: $article['created_at']));
$formatted_date   = date('F j, Y', strtotime($article['published_at'] ?: $article['created_at']));

// Rich Schema.org Graph for this article
$page_schema = [
    [
        '@type'            => 'BlogPosting',
        '@id'              => $canonical_url . '#article',
        'isPartOf'         => ['@id' => site_url() . '#website'],
        'headline'         => $article['title'],
        'description'      => $page_description,
        'image'            => upload_url($og_image),
        'datePublished'    => $pub_iso_date,
        'dateModified'     => $mod_iso_date,
        'mainEntityOfPage' => $canonical_url,
        'articleSection'   => $article['category_name'] ?? 'Production',
        'keywords'         => $article['tags'] ?? '',
        'wordCount'        => $word_count,
        'inLanguage'       => 'en',
        'author'           => [
            '@type'    => 'Person',
            'name'     => $author_display_name,
            'jobTitle' => $author_role,
            'url'      => site_url('meet-the-team')
        ],
        'publisher'        => ['@id' => site_url() . '#organization']
    ],
    [
        '@type'            => 'BreadcrumbList',
        '@id'              => $canonical_url . '#breadcrumbs',
        'itemListElement'  => [
            [
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => 'Home',
                'item'     => site_url()
            ],
            [
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => 'Blog',
                'item'     => site_url('blog')
            ],
            [
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => $article['category_name'] ?? 'Production',
                'item'     => site_url('blog?category=' . urlencode($article['category_slug'] ?? ''))
            ],
            [
                '@type'    => 'ListItem',
                'position' => 4,
                'name'     => $article['title'],
                'item'     => $canonical_url
            ]
        ]
    ]
];

// Custom Typography & Article Layout Stylesheet
$extra_head = <<<HTML
<style>
    /* High-contrast Article Content Typography */
    .blog-article-content {
        color: #d1d5db;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 1.075rem;
        line-height: 1.85;
    }
    .blog-article-content p {
        margin-bottom: 1.6rem;
    }
    .blog-article-content h2 {
        color: #ffffff;
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.3px;
        margin-top: 2.5rem;
        margin-bottom: 1.1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .blog-article-content h3 {
        color: #f3f4f6;
        font-size: 1.35rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 0.9rem;
    }
    .blog-article-content h4 {
        color: #e5e7eb;
        font-size: 1.15rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.8rem;
    }
    .blog-article-content ul, .blog-article-content ol {
        margin-bottom: 1.6rem;
        padding-left: 1.75rem;
    }
    .blog-article-content li {
        margin-bottom: 0.6rem;
    }
    .blog-article-content strong {
        color: #ffffff;
        font-weight: 700;
    }
    .blog-article-content a {
        color: #e50914;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .blog-article-content a:hover {
        color: #ff3b47;
    }
    .blog-article-content blockquote {
        margin: 2rem 0;
        padding: 1.25rem 1.75rem;
        background: rgba(229, 9, 20, 0.05);
        border-left: 4px solid #e50914;
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: #f3f4f6;
    }
    .blog-article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 1.8rem 0;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    }
    .blog-article-content table {
        width: 100%;
        margin: 1.8rem 0;
        border-collapse: collapse;
        color: #d1d5db;
        background: #151822;
        border-radius: 8px;
        overflow: hidden;
    }
    .blog-article-content th, .blog-article-content td {
        padding: 12px 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .blog-article-content th {
        background: #1c202d;
        color: #ffffff;
        font-weight: 700;
    }
    /* Sticky Sidebar Widget */
    .article-sidebar-sticky {
        position: sticky;
        top: 100px;
    }
    /* Social Share Buttons */
    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: #fff !important;
        font-size: 15px;
        transition: transform 0.2s ease, opacity 0.2s ease;
        text-decoration: none !important;
    }
    .share-btn:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
    .share-btn-wa { background: #25D366; }
    .share-btn-li { background: #0A66C2; }
    .share-btn-fb { background: #1877F2; }
    .share-btn-x  { background: #111111; border: 1px solid rgba(255,255,255,0.2); }
    .share-btn-cp { background: #374151; }
    /* Toast Notification for Link Copy */
    #copy-toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #10b981;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        z-index: 99999;
        display: none;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Popular Insights Card Refinements */
    .popular-post-card {
        padding: 10px 10px;
        border-radius: 8px;
        transition: all 0.25s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        text-decoration: none !important;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .popular-post-card:last-child {
        border-bottom: none;
    }
    .popular-post-card:hover {
        background: rgba(255, 255, 255, 0.04);
        transform: translateX(4px);
    }
    .popular-post-card .pop-thumb-wrap {
        flex-shrink: 0;
        width: 74px;
        height: 56px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        position: relative;
    }
    .popular-post-card .pop-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .popular-post-card:hover .pop-thumb-wrap img {
        transform: scale(1.08);
    }
    .popular-post-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        color: #f3f4f6;
        font-weight: 600;
        font-size: 13.5px;
        line-height: 1.4;
        transition: color 0.2s ease;
    }
    .popular-post-card:hover .popular-post-title {
        color: #e50914 !important;
    }
</style>
HTML;

// Universal Partials
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Prepare Thumbnail Image
$featured_img_url = !empty($article['thumbnail']) ? upload_url($article['thumbnail']) : site_url('images/ar-hero-banner.webp');
?>

<!-- ========================================================================= -->
<!-- 1. BREADCRUMBS & ARTICLE HEADER                                           -->
<!-- ========================================================================= -->
<section class="single-blog-header py-5" style="background: linear-gradient(180deg, #0b0d14 0%, #11141e 100%); border-bottom: 1px solid rgba(255,255,255,0.06); padding-top: 130px !important;">
    <div class="container">
        <!-- Breadcrumb Links -->
        <nav class="breadcrumb-nav mb-4" aria-label="Breadcrumb">
            <ul class="d-flex align-items-center flex-wrap list-unstyled mb-0" style="gap: 8px; font-size: 13.5px;">
                <li><a href="<?= site_url() ?>" style="color: #9ca3af; text-decoration: none;"><i class="fa fa-home mr-1"></i> Home</a></li>
                <li style="color: #6b7280;">/</li>
                <li><a href="<?= site_url('blog') ?>" style="color: #9ca3af; text-decoration: none;">Blog</a></li>
                <?php if (!empty($article['category_name'])): ?>
                    <li style="color: #6b7280;">/</li>
                    <li><a href="<?= site_url('blog?category=' . urlencode($article['category_slug'])) ?>" style="color: #9ca3af; text-decoration: none;"><?= htmlspecialchars($article['category_name']) ?></a></li>
                <?php endif; ?>
                <li style="color: #6b7280;">/</li>
                <li class="active text-truncate" style="color: #e50914; font-weight: 600; max-width: 320px;"><?= htmlspecialchars($article['title']) ?></li>
            </ul>
        </nav>

        <!-- Category Badge -->
        <?php if (!empty($article['category_name'])): ?>
            <div class="mb-3">
                <a href="<?= site_url('blog?category=' . urlencode($article['category_slug'])) ?>" 
                   class="badge badge-danger text-uppercase px-3 py-2" 
                   style="background: #e50914; font-weight: 700; font-size: 12px; letter-spacing: 0.5px; border-radius: 4px; text-decoration: none;">
                    <?= htmlspecialchars($article['category_name']) ?>
                </a>
            </div>
        <?php endif; ?>

        <!-- Article H1 Headline -->
        <h1 class="text-white font-weight-bold mb-4" style="font-size: clamp(2rem, 3.5vw, 3rem); line-height: 1.25; letter-spacing: -0.5px;">
            <?= htmlspecialchars($article['title']) ?>
        </h1>

        <!-- Author & Meta Toolbar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between pt-3" style="border-top: 1px solid rgba(255,255,255,0.08); gap: 16px;">
            <!-- Author Block -->
            <div class="d-flex align-items-center" style="gap: 12px;">
                <img src="<?= htmlspecialchars($author_photo_url) ?>" 
                     alt="<?= htmlspecialchars($author_display_name) ?>" 
                     style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e50914;">
                <div>
                    <div class="text-white font-weight-bold" style="font-size: 15px; line-height: 1.2;">
                        <a href="<?= site_url('meet-the-team') ?>" style="color: #fff; text-decoration: none;">
                            <?= htmlspecialchars($author_display_name) ?>
                        </a>
                    </div>
                    <div style="font-size: 12.5px; color: #9ca3af;"><?= htmlspecialchars($author_role) ?></div>
                </div>
            </div>

            <!-- Meta Details: Date, Read Time, Views -->
            <div class="d-flex align-items-center flex-wrap" style="gap: 18px; font-size: 13.5px; color: #9ca3af;">
                <span><i class="fa-regular fa-calendar text-danger mr-1"></i> <?= $formatted_date ?></span>
                <span><i class="fa-regular fa-clock mr-1"></i> <?= $read_time ?> min read</span>
                <span><i class="fa-regular fa-eye mr-1"></i> <?= number_format($article['views']) ?> views</span>
            </div>

            <!-- Social Share Quick Buttons -->
            <div class="d-flex align-items-center" style="gap: 8px;">
                <span class="d-none d-md-inline text-muted mr-1" style="font-size: 13px;">Share:</span>
                <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' ' . $canonical_url) ?>" 
                   target="_blank" rel="noopener noreferrer" class="share-btn share-btn-wa" title="Share on WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($canonical_url) ?>" 
                   target="_blank" rel="noopener noreferrer" class="share-btn share-btn-li" title="Share on LinkedIn">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonical_url) ?>" 
                   target="_blank" rel="noopener noreferrer" class="share-btn share-btn-fb" title="Share on Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode($canonical_url) ?>&text=<?= urlencode($article['title']) ?>" 
                   target="_blank" rel="noopener noreferrer" class="share-btn share-btn-x" title="Share on X">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
                <button type="button" class="share-btn share-btn-cp border-0" onclick="copyArticleUrl()" title="Copy Link">
                    <i class="fa-solid fa-link"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 2. ARTICLE BODY & SIDEBAR                                                 -->
<!-- ========================================================================= -->
<section class="py-5" style="background: #0f1016;">
    <div class="container">
        <div class="row">
            <!-- Main Article Column -->
            <div class="col-lg-8 col-12 mb-5 mb-lg-0">
                <article class="p-4 p-md-5" style="background: #141722; border: 1px solid rgba(255,255,255,0.06); border-radius: 14px;">
                    <!-- Featured Article Image -->
                    <?php if (!empty($article['thumbnail'])): ?>
                        <div class="mb-4" style="border-radius: 12px; overflow: hidden; max-height: 480px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                            <img src="<?= htmlspecialchars($featured_img_url) ?>" 
                                 alt="<?= htmlspecialchars($article['title']) ?>" 
                                 class="w-100" 
                                 style="object-fit: cover; max-height: 480px; display: block;">
                        </div>
                    <?php endif; ?>

                    <!-- Article HTML Body -->
                    <div class="blog-article-content">
                        <?= $article['content'] ?>
                    </div>

                    <!-- Tags & Topics Section -->
                    <?php if (!empty($article['tags'])): 
                        $tags_list = array_filter(array_map('trim', explode(',', $article['tags'])));
                    ?>
                        <div class="mt-5 pt-4" style="border-top: 1px solid rgba(255,255,255,0.08);">
                            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                <span class="text-white font-weight-bold mr-2" style="font-size: 14px;">
                                    <i class="fa fa-tags text-danger mr-1"></i> Topics:
                                </span>
                                <?php foreach ($tags_list as $t): ?>
                                    <a href="<?= site_url('blog?q=' . urlencode($t)) ?>" 
                                       class="badge" 
                                       style="background: #1c202d; color: #d1d5db; border: 1px solid rgba(255,255,255,0.1); padding: 7px 14px; font-size: 12.5px; border-radius: 20px; text-decoration: none; transition: all 0.2s;"
                                       onmouseover="this.style.background='#e50914'; this.style.color='#fff';"
                                       onmouseout="this.style.background='#1c202d'; this.style.color='#d1d5db';">
                                        <?= htmlspecialchars($t) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Bottom Social Sharing Bar -->
                    <div class="mt-4 pt-4 d-flex flex-wrap align-items-center justify-content-between" style="border-top: 1px solid rgba(255,255,255,0.08); gap: 14px;">
                        <span class="text-white font-weight-bold" style="font-size: 15px;">
                            Enjoyed this guide? Share with your team:
                        </span>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' ' . $canonical_url) ?>" 
                               target="_blank" rel="noopener noreferrer" class="share-btn share-btn-wa" title="Share on WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($canonical_url) ?>" 
                               target="_blank" rel="noopener noreferrer" class="share-btn share-btn-li" title="Share on LinkedIn">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonical_url) ?>" 
                               target="_blank" rel="noopener noreferrer" class="share-btn share-btn-fb" title="Share on Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($canonical_url) ?>&text=<?= urlencode($article['title']) ?>" 
                               target="_blank" rel="noopener noreferrer" class="share-btn share-btn-x" title="Share on X">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <button type="button" class="share-btn share-btn-cp border-0" onclick="copyArticleUrl()" title="Copy Link">
                                <i class="fa-solid fa-link"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Author Profile Box -->
                    <div class="mt-5 p-4 rounded" style="background: #1a1e2b; border: 1px solid rgba(255,255,255,0.08);">
                        <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start text-center text-sm-left" style="gap: 20px;">
                            <img src="<?= htmlspecialchars($author_photo_url) ?>" 
                                 alt="<?= htmlspecialchars($author_display_name) ?>" 
                                 style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #e50914; flex-shrink: 0;">
                            <div>
                                <div class="d-flex align-items-center justify-content-center justify-content-sm-start flex-wrap mb-1" style="gap: 8px;">
                                    <h3 class="h5 text-white font-weight-bold mb-0"><?= htmlspecialchars($author_display_name) ?></h3>
                                    <span class="badge" style="background: rgba(229,9,20,0.15); color: #ff6b6b; font-size: 11px; padding: 4px 8px; border-radius: 4px;"><?= htmlspecialchars($author_role) ?></span>
                                </div>
                                <p class="text-muted mb-3" style="font-size: 13.5px; line-height: 1.6; color: #9ca3af !important;">
                                    <?= htmlspecialchars($author_bio) ?>
                                </p>
                                <a href="<?= site_url('meet-the-team') ?>" class="text-danger font-weight-bold" style="font-size: 13px; text-decoration: underline;">
                                    Meet the Production Team <i class="fa fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Previous & Next Article Navigation -->
                    <?php if ($prev_article || $next_article): ?>
                        <div class="row mt-5 pt-4" style="border-top: 1px solid rgba(255,255,255,0.08);">
                            <div class="col-sm-6 col-12 mb-3 mb-sm-0">
                                <?php if ($prev_article): ?>
                                    <a href="<?= site_url('blog/' . $prev_article['slug']) ?>" 
                                       class="d-block p-3 rounded h-100 text-decoration-none" 
                                       style="background: #181b24; border: 1px solid rgba(255,255,255,0.05); transition: border-color 0.2s;"
                                       onmouseover="this.style.borderColor='#e50914'"
                                       onmouseout="this.style.borderColor='rgba(255,255,255,0.05)'">
                                        <div class="text-muted" style="font-size: 12px;"><i class="fa fa-arrow-left mr-1"></i> Previous Article</div>
                                        <div class="text-white font-weight-bold text-truncate mt-1" style="font-size: 14px;"><?= htmlspecialchars($prev_article['title']) ?></div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-6 col-12 text-sm-right">
                                <?php if ($next_article): ?>
                                    <a href="<?= site_url('blog/' . $next_article['slug']) ?>" 
                                       class="d-block p-3 rounded h-100 text-decoration-none" 
                                       style="background: #181b24; border: 1px solid rgba(255,255,255,0.05); transition: border-color 0.2s;"
                                       onmouseover="this.style.borderColor='#e50914'"
                                       onmouseout="this.style.borderColor='rgba(255,255,255,0.05)'">
                                        <div class="text-muted" style="font-size: 12px;">Next Article <i class="fa fa-arrow-right ml-1"></i></div>
                                        <div class="text-white font-weight-bold text-truncate mt-1" style="font-size: 14px;"><?= htmlspecialchars($next_article['title']) ?></div>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </article>
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4 col-12">
                <aside class="article-sidebar-sticky">
                    <!-- 1. Search Box Widget -->
                    <div class="p-4 rounded mb-4" style="background: #141722; border: 1px solid rgba(255,255,255,0.06);">
                        <h4 class="text-white font-weight-bold h6 mb-3 text-uppercase" style="letter-spacing: 0.5px;">Search Blog</h4>
                        <form action="<?= site_url('blog') ?>" method="GET" class="position-relative">
                            <input type="text" 
                                   name="q" 
                                   placeholder="Search production guides..." 
                                   class="form-control" 
                                   style="background: #1c202d; border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; color: #fff; padding-right: 48px; font-size: 14px; height: 44px; outline: none; box-shadow: none;">
                            <button type="submit" 
                                    style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; border-radius: 6px; background: #e50914; border: none; color: #ffffff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s ease, transform 0.2s ease; box-shadow: 0 2px 8px rgba(229,9,20,0.4);" 
                                    onmouseover="this.style.background='#ff2b37'; this.style.transform='translateY(-50%) scale(1.05)';" 
                                    onmouseout="this.style.background='#e50914'; this.style.transform='translateY(-50%) scale(1.0)';"
                                    aria-label="Search">
                                <i class="fa-solid fa-magnifying-glass" style="font-size: 13px;"></i>
                            </button>
                        </form>
                    </div>

                    <!-- 2. Categories Widget -->
                    <?php if (!empty($sidebar_categories)): ?>
                        <div class="p-4 rounded mb-4" style="background: #141722; border: 1px solid rgba(255,255,255,0.06);">
                            <h4 class="text-white font-weight-bold h6 mb-3 text-uppercase" style="letter-spacing: 0.5px;">Production Topics</h4>
                            <ul class="list-unstyled mb-0" style="gap: 8px; display: flex; flex-direction: column;">
                                <?php foreach ($sidebar_categories as $scat): 
                                    $is_current_cat = ($article['category_id'] == $scat['id']);
                                ?>
                                    <li>
                                        <a href="<?= site_url('blog?category=' . urlencode($scat['slug'])) ?>" 
                                           class="d-flex align-items-center justify-content-between p-2 rounded text-decoration-none"
                                           style="color: <?= $is_current_cat ? '#e50914' : '#d1d5db' ?>; background: <?= $is_current_cat ? 'rgba(229,9,20,0.1)' : 'transparent' ?>; font-size: 14px; font-weight: <?= $is_current_cat ? '700' : '500' ?>; transition: all 0.2s;"
                                           onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#e50914';"
                                           onmouseout="this.style.background='<?= $is_current_cat ? 'rgba(229,9,20,0.1)' : 'transparent' ?>'; this.style.color='<?= $is_current_cat ? '#e50914' : '#d1d5db' ?>';">
                                            <span><i class="fa fa-angle-right mr-2 text-danger"></i> <?= htmlspecialchars($scat['name']) ?></span>
                                            <span class="badge badge-secondary" style="background: #1c202d; font-size: 11px;"><?= (int)$scat['post_count'] ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- 3. Trending / Popular Articles Widget -->
                    <?php if (!empty($popular_articles)): ?>
                        <div class="p-4 rounded mb-4" style="background: #141722; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="d-flex align-items-center mb-3" style="gap: 8px;">
                                <i class="fa-solid fa-fire text-danger" style="font-size: 15px;"></i>
                                <h4 class="text-white font-weight-bold h6 mb-0 text-uppercase" style="letter-spacing: 0.5px;">Popular Insights</h4>
                            </div>
                            <div class="d-flex flex-column" style="gap: 4px;">
                                <?php foreach ($popular_articles as $pop): 
                                    $pop_thumb = !empty($pop['thumbnail']) ? upload_url($pop['thumbnail']) : site_url('images/ar-hero-banner.webp');
                                    $pop_date  = !empty($pop['published_at']) ? date('M j, Y', strtotime($pop['published_at'])) : '';
                                ?>
                                    <a href="<?= site_url('blog/' . $pop['slug']) ?>" class="popular-post-card" title="<?= htmlspecialchars($pop['title']) ?>">
                                        <div class="pop-thumb-wrap">
                                            <img src="<?= htmlspecialchars($pop_thumb) ?>" alt="<?= htmlspecialchars($pop['title']) ?>" loading="lazy">
                                        </div>
                                        <div class="overflow-hidden flex-grow-1">
                                            <div class="popular-post-title">
                                                <?= htmlspecialchars($pop['title']) ?>
                                            </div>
                                            <div class="d-flex align-items-center mt-1" style="font-size: 11.5px; color: #9ca3af; gap: 8px;">
                                                <span><i class="fa-regular fa-eye text-danger mr-1"></i> <?= number_format($pop['views']) ?></span>
                                                <?php if ($pop_date): ?>
                                                    <span style="color: #6b7280;">•</span>
                                                    <span><?= $pop_date ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 4. Quick Production Inquiry Card -->
                    <div class="p-4 rounded text-center" style="background: linear-gradient(145deg, #1e1115 0%, #151822 100%); border: 1px solid rgba(229,9,20,0.3);">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; background: rgba(229,9,20,0.15); color: #e50914; font-size: 22px;">
                                <i class="fa-solid fa-clapperboard"></i>
                            </span>
                        </div>
                        <h4 class="text-white font-weight-bold h6 mb-2">Plan Your Next Shoot</h4>
                        <p class="text-muted mb-4" style="font-size: 13px; line-height: 1.5; color: #d1d5db !important;">
                            Commercial TVCs, corporate documentaries, or foreign film fixing in Dhaka — AR Entertainment delivers end-to-end execution.
                        </p>
                        <a href="<?= site_url('contact-us') ?>" class="btn btn-danger btn-block font-weight-bold py-2" style="background: #e50914; border-radius: 6px; font-size: 13.5px;">
                            Request a Quote <i class="fa fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 3. RELATED ARTICLES SECTION                                               -->
<!-- ========================================================================= -->
<?php if (!empty($related_articles)): ?>
    <section class="py-5" style="background: #0b0d14; border-top: 1px solid rgba(255,255,255,0.06);">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <span class="text-danger font-weight-bold text-uppercase" style="font-size: 12px; letter-spacing: 1px;">Keep Reading</span>
                    <h2 class="text-white font-weight-bold h3 mb-0">Related Production Guides</h2>
                </div>
                <a href="<?= site_url('blog') ?>" class="text-danger font-weight-bold d-none d-sm-inline" style="font-size: 14px; text-decoration: underline;">
                    View All Articles <i class="fa fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="row">
                <?php foreach ($related_articles as $rel): 
                    $rel_thumb = !empty($rel['thumbnail']) ? upload_url($rel['thumbnail']) : site_url('images/ar-hero-banner.webp');
                    $rel_link  = site_url('blog/' . $rel['slug']);
                    $rel_date  = !empty($rel['published_at']) ? date('M j, Y', strtotime($rel['published_at'])) : date('M j, Y');
                    $rel_read  = max(2, (int)ceil(str_word_count(strip_tags($rel['content'])) / 200));
                ?>
                    <div class="col-lg-4 col-md-6 col-12 mb-4 d-flex">
                        <article class="card w-100 d-flex flex-column" style="background: #141722; border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; overflow: hidden; transition: transform 0.2s ease;">
                            <div class="position-relative" style="height: 190px; overflow: hidden;">
                                <a href="<?= $rel_link ?>">
                                    <img src="<?= htmlspecialchars($rel_thumb) ?>" 
                                         alt="<?= htmlspecialchars($rel['title']) ?>" 
                                         loading="lazy" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover;">
                                </a>
                                <?php if (!empty($rel['category_name'])): ?>
                                    <span class="badge position-absolute" 
                                          style="top: 12px; left: 12px; background: rgba(10,12,18,0.85); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                                        <?= htmlspecialchars($rel['category_name']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-2" style="font-size: 12px; color: #9ca3af;">
                                    <span><?= $rel_date ?></span>
                                    <span><?= $rel_read ?> min read</span>
                                </div>
                                <h3 class="h6 font-weight-bold mb-3" style="line-height: 1.4;">
                                    <a href="<?= $rel_link ?>" style="color: #fff; text-decoration: none;">
                                        <?= htmlspecialchars($rel['title']) ?>
                                    </a>
                                </h3>
                                <p class="text-muted mb-3 flex-grow-1" style="font-size: 13.5px; line-height: 1.5; color: #9ca3af !important;">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($rel['summary'] ?: $rel['content']), 0, 100, '...')) ?>
                                </p>
                                <a href="<?= $rel_link ?>" class="text-danger font-weight-bold" style="font-size: 13px; text-decoration: none;">
                                    Read Article <i class="fa fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ========================================================================= -->
<!-- 4. COPY LINK TOAST & SHARE SCRIPT                                         -->
<!-- ========================================================================= -->
<div id="copy-toast">
    <i class="fa fa-check-circle mr-2"></i> Article link copied to clipboard!
</div>

<script>
function copyArticleUrl() {
    const url = window.location.href;
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(showToast);
    } else {
        const tempInput = document.createElement('input');
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showToast();
    }
}

function showToast() {
    const toast = document.getElementById('copy-toast');
    if (toast) {
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }
}
</script>

<!-- ========================================================================= -->
<!-- 5. UNIVERSAL CTA & FOOTER                                                 -->
<!-- ========================================================================= -->
<?php
require_once __DIR__ . '/includes/cta.php';
require_once __DIR__ . '/includes/footer.php';
?>
