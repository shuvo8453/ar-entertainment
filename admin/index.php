<?php

/**
 * AR Entertainment - Admin Dashboard Overview
 */

$page_title = 'Dashboard Overview';
$current_page = 'dashboard';

require_once __DIR__ . '/auth_check.php';

// Fetch stats safely
try {
    $db = db();
    $total_blogs = (int) $db->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
    $total_services = (int) $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $total_portfolio = (int) $db->query("SELECT COUNT(*) FROM portfolio")->fetchColumn();
    $unread_inquiries = (int) $db->query("SELECT COUNT(*) FROM inquiries WHERE is_read = 0")->fetchColumn();
    $total_team = (int) $db->query("SELECT COUNT(*) FROM team_members")->fetchColumn();
    $total_brands = (int) $db->query("SELECT COUNT(*) FROM brands")->fetchColumn();
    $total_reviews = (int) $db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();

    // Recent Inquiries
    $recent_inquiries = $db->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5")->fetchAll();

    // Recent Blogs
    $recent_blogs = $db->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 5")->fetchAll();
} catch (PDOException $e) {
    $total_blogs = $total_services = $total_portfolio = $unread_inquiries = 0;
    $recent_inquiries = [];
    $recent_blogs = [];
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="admin-main">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <main class="admin-content">
        <!-- Breadcrumb & Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>" class="text-muted text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Dashboard Overview</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= site_url('admin/blogs/create') ?>" class="btn btn-ar-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> New Article
                </a>
                <a href="<?= site_url('admin/portfolio/create') ?>" class="btn btn-ar-secondary btn-sm">
                    <i class="fa-solid fa-video me-1"></i> Add Work
                </a>
            </div>
        </div>

        <!-- Flash notifications -->
        <?= render_flash() ?>

        <!-- Welcome Banner -->
        <div class="card-ar mb-4" style="background: linear-gradient(135deg, #1c1e2f 0%, #141522 100%); border-left: 4px solid var(--ar-primary);">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="fw-bold text-white mb-1">Welcome back, <?= htmlspecialchars(current_user()['name'] ?? 'Admin') ?>! 👋</h4>
                    <p class="text-muted mb-0 small">
                        Here is an overview of what's happening on <strong class="text-light"><?= SITE_NAME ?></strong>.
                    </p>
                </div>
                <div class="text-muted small">
                    <i class="fa-regular fa-calendar-days me-1 text-danger"></i> <?= date('l, F j, Y') ?>
                </div>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= $total_blogs ?></div>
                        <div class="stat-label">Total Articles</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= $total_portfolio ?></div>
                        <div class="stat-label">Portfolio Works</div>
                    </div>
                    <div class="stat-icon icon-red">
                        <i class="fa-solid fa-film"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= $total_services ?></div>
                        <div class="stat-label">Services Offered</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-clapperboard"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-warning"><?= $unread_inquiries ?></div>
                        <div class="stat-label">New Inquiries</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row: Recent Inquiries & Quick Actions -->
        <div class="row g-4 mb-4">
            <!-- Recent Inquiries Table -->
            <div class="col-lg-7">
                <div class="card-ar h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-white mb-0">
                            <i class="fa-solid fa-envelope-open-text text-warning me-2"></i> Recent Inquiries &amp; Leads
                        </h6>
                        <a href="<?= site_url('admin/inquiries') ?>" class="btn btn-ar-secondary btn-sm py-1 px-2 small">
                            View All <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <?php if (empty($recent_inquiries)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-inbox fa-3x mb-3 text-secondary opacity-50"></i>
                            <p class="mb-0">No inquiries received yet. Incoming submissions will appear here.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark-custom">
                                <thead>
                                    <tr>
                                        <th>Name / Contact</th>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_inquiries as $inq): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-white"><?= htmlspecialchars($inq['name']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($inq['email']) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary badge-ar"><?= strtoupper($inq['form_type']) ?></span>
                                            </td>
                                            <td class="text-truncate" style="max-width: 160px;">
                                                <?= htmlspecialchars($inq['subject'] ?: $inq['message']) ?>
                                            </td>
                                            <td class="small text-muted">
                                                <?= format_date($inq['created_at'], 'M d, H:i') ?>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('admin/inquiries/view?id=' . $inq['id']) ?>" class="btn btn-outline-light btn-sm py-0 px-2">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Overview & Fast Actions -->
            <div class="col-lg-5">
                <div class="card-ar mb-4">
                    <h6 class="fw-bold text-white mb-3">
                        <i class="fa-solid fa-bolt text-danger me-2"></i> Quick Management
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('admin/blogs/create') ?>" class="btn btn-ar-secondary text-start d-flex align-items-center justify-content-between">
                            <span><i class="fa-solid fa-file-circle-plus text-primary me-2"></i> Write New Blog Post</span>
                            <i class="fa-solid fa-chevron-right text-muted small"></i>
                        </a>
                        <a href="<?= site_url('admin/portfolio/create') ?>" class="btn btn-ar-secondary text-start d-flex align-items-center justify-content-between">
                            <span><i class="fa-solid fa-circle-play text-danger me-2"></i> Upload Portfolio Video</span>
                            <i class="fa-solid fa-chevron-right text-muted small"></i>
                        </a>
                        <a href="<?= site_url('admin/services/create') ?>" class="btn btn-ar-secondary text-start d-flex align-items-center justify-content-between">
                            <span><i class="fa-solid fa-plus-circle text-success me-2"></i> Add New Service</span>
                            <i class="fa-solid fa-chevron-right text-muted small"></i>
                        </a>
                        <a href="<?= site_url('admin/settings') ?>" class="btn btn-ar-secondary text-start d-flex align-items-center justify-content-between">
                            <span><i class="fa-solid fa-sliders text-warning me-2"></i> Edit Site Settings &amp; SEO</span>
                            <i class="fa-solid fa-chevron-right text-muted small"></i>
                        </a>
                    </div>
                </div>

                <div class="card-ar">
                    <h6 class="fw-bold text-white mb-3">
                        <i class="fa-solid fa-server text-info me-2"></i> System &amp; Brand Status
                    </h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="d-flex justify-content-between py-2 border-bottom border-dark">
                            <span>Brand Identity:</span>
                            <strong class="text-white"><?= SITE_NAME ?></strong>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-dark">
                            <span>Production Domain:</span>
                            <code class="text-info"><?= SITE_DOMAIN ?></code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-dark">
                            <span>Database:</span>
                            <code class="text-success"><?= DB_NAME ?> (MySQL)</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-dark">
                            <span>PHP Version:</span>
                            <span class="text-white"><?= phpversion() ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span>Timezone:</span>
                            <span class="text-white"><?= date_default_timezone_get() ?> (<?= date('H:i:s') ?>)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>