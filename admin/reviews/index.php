<?php
/**
 * AR Entertainment - Client Reviews & Testimonials Manager
 * Phase 4.6: Management CRUD (admin/reviews/index.php)
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

require_login();

$current_page = 'reviews';
$page_title   = 'Client Reviews & Testimonials';

// Filter inputs
$source_filter = trim($_GET['source'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$search        = trim($_GET['q'] ?? '');
$page          = max(1, (int) ($_GET['page'] ?? 1));
$per_page      = 15;

// Allowed sources
$valid_sources = ['google', 'goodfirms', 'clutch', 'direct'];
if (!in_array($source_filter, $valid_sources, true)) {
    $source_filter = '';
}

// Allowed statuses
$valid_statuses = ['active', 'inactive'];
if (!in_array($status_filter, $valid_statuses, true)) {
    $status_filter = '';
}

// Build query
$where  = [];
$params = [];

if ($source_filter !== '') {
    $where[] = "source = :source";
    $params[':source'] = $source_filter;
}

if ($status_filter !== '') {
    $where[] = "status = :status";
    $params[':status'] = $status_filter;
}

if ($search !== '') {
    $where[] = "(client_name LIKE :q OR client_company LIKE :q2 OR project_name LIKE :q3 OR review_text LIKE :q4)";
    $params[':q']  = "%{$search}%";
    $params[':q2'] = "%{$search}%";
    $params[':q3'] = "%{$search}%";
    $params[':q4'] = "%{$search}%";
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Count total
try {
    $count_stmt = db()->prepare("SELECT COUNT(*) FROM reviews {$where_sql}");
    $count_stmt->execute($params);
    $total_items = (int) $count_stmt->fetchColumn();
} catch (PDOException $e) {
    $total_items = 0;
    set_flash('error', 'Database error: ' . $e->getMessage());
}

$total_pages = max(1, (int) ceil($total_items / $per_page));
if ($page > $total_pages) {
    $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

// Fetch reviews
$reviews = [];
try {
    $sql = "
        SELECT * FROM reviews
        {$where_sql}
        ORDER BY sort_order ASC, created_at DESC
        LIMIT :limit OFFSET :offset
    ";
    $stmt = db()->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll();
} catch (PDOException $e) {
    set_flash('error', 'Failed to retrieve reviews: ' . $e->getMessage());
}

// Fetch stats
$stats = [
    'total'      => 0,
    'avg_rating' => 5.0,
    'google'     => 0,
    'goodfirms'  => 0,
    'clutch'     => 0,
    'direct'     => 0,
    'active'     => 0
];
try {
    $stats_stmt = db()->query("
        SELECT 
            COUNT(*) as total,
            COALESCE(AVG(rating), 5.0) as avg_rating,
            SUM(CASE WHEN source = 'google' THEN 1 ELSE 0 END) as google,
            SUM(CASE WHEN source = 'goodfirms' THEN 1 ELSE 0 END) as goodfirms,
            SUM(CASE WHEN source = 'clutch' THEN 1 ELSE 0 END) as clutch,
            SUM(CASE WHEN source = 'direct' THEN 1 ELSE 0 END) as direct,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active
        FROM reviews
    ");
    $stats = $stats_stmt->fetch() ?: $stats;
} catch (Exception $e) {}

require_once ADMIN_PATH . '/includes/header.php';
require_once ADMIN_PATH . '/includes/sidebar.php';
?>

<div class="admin-main">
    <div class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary d-lg-none" id="sidebarToggleBtn">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div>
                <h4 class="fw-bold mb-0 text-white">Client Reviews &amp; Testimonials</h4>
                <small class="text-muted">Manage verified client testimonials, star ratings, and review platforms</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('admin/reviews/create.php') ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Add Review
            </a>
        </div>
    </div>

    <main class="content-body">
        <?= render_flash() ?>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= number_format((float) ($stats['avg_rating'] ?? 5.0), 1) ?> <span style="font-size: 0.9rem; color: #f59e0b;">★</span></div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= (int) ($stats['total'] ?? 0) ?></div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="fa-brands fa-google"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= (int) ($stats['google'] ?? 0) ?></div>
                        <div class="stat-label">Google Reviews</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= (int) ($stats['active'] ?? 0) ?></div>
                        <div class="stat-label">Published Active</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="card-ar mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <!-- Source Tabs -->
                <ul class="nav nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link <?= empty($source_filter) ? 'active' : '' ?>" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['source' => '', 'page' => 1]))) ?>">
                            All Sources <span class="badge bg-secondary ms-1"><?= (int) ($stats['total'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($source_filter === 'google') ? 'active' : '' ?>" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['source' => 'google', 'page' => 1]))) ?>">
                            <i class="fa-brands fa-google me-1 text-danger"></i> Google <span class="badge bg-danger ms-1"><?= (int) ($stats['google'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($source_filter === 'goodfirms') ? 'active' : '' ?>" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['source' => 'goodfirms', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-shield-halved me-1 text-info"></i> GoodFirms <span class="badge bg-info text-dark ms-1"><?= (int) ($stats['goodfirms'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($source_filter === 'clutch') ? 'active' : '' ?>" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['source' => 'clutch', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-award me-1 text-warning"></i> Clutch <span class="badge bg-warning text-dark ms-1"><?= (int) ($stats['clutch'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($source_filter === 'direct') ? 'active' : '' ?>" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['source' => 'direct', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-user-check me-1 text-success"></i> Direct Client <span class="badge bg-secondary ms-1"><?= (int) ($stats['direct'] ?? 0) ?></span>
                        </a>
                    </li>
                </ul>

                <!-- Add Button Quick Link -->
                <div>
                    <a href="<?= site_url('admin/reviews/create.php') ?>" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus me-1"></i> Add Review
                    </a>
                </div>
            </div>

            <!-- Search & Status Row -->
            <form method="GET" action="<?= site_url('admin/reviews') ?>" class="row g-2 align-items-center">
                <?php if (!empty($source_filter)): ?>
                    <input type="hidden" name="source" value="<?= htmlspecialchars($source_filter) ?>">
                <?php endif; ?>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="q" class="form-control bg-dark border-secondary text-white" placeholder="Search client name, company, or feedback text..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <select name="status" class="form-select bg-dark border-secondary text-white">
                        <option value="">All Statuses</option>
                        <option value="active" <?= ($status_filter === 'active') ? 'selected' : '' ?>>Active Only</option>
                        <option value="inactive" <?= ($status_filter === 'inactive') ? 'selected' : '' ?>>Inactive Only</option>
                    </select>
                </div>

                <div class="col-6 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary flex-grow-1">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <?php if ($search !== '' || $status_filter !== '' || $source_filter !== ''): ?>
                        <a href="<?= site_url('admin/reviews') ?>" class="btn btn-outline-secondary" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Reviews Table Card -->
        <div class="card-ar">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-white mb-0">
                    <i class="fa-solid fa-comments me-2 text-primary"></i> Testimonials &amp; Feedback List
                    <span class="badge bg-secondary ms-1"><?= $total_items ?></span>
                </h5>
                <span class="text-muted small">Ordered by Sort Priority</span>
            </div>

            <?php if (empty($reviews)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fa-solid fa-star-half-stroke fa-3x text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-white fw-bold">No client reviews found</h5>
                    <p class="text-muted small">Add verified testimonials from Google, Clutch, or direct clients.</p>
                    <a href="<?= site_url('admin/reviews/create.php') ?>" class="btn btn-primary mt-2">
                        <i class="fa-solid fa-plus me-1"></i> Add Review
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small border-secondary">
                                <th style="width: 50px;">Sort</th>
                                <th style="width: 60px;">Client</th>
                                <th>Client &amp; Company</th>
                                <th>Rating</th>
                                <th style="width: 38%;">Testimonial Snippet</th>
                                <th>Source / Platform</th>
                                <th>Status</th>
                                <th style="width: 130px;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $item): ?>
                                <tr>
                                    <!-- Sort Order -->
                                    <td>
                                        <span class="badge bg-dark border border-secondary text-muted">
                                            #<?= (int) $item['sort_order'] ?>
                                        </span>
                                    </td>

                                    <!-- Client Avatar -->
                                    <td>
                                        <?php if (!empty($item['client_photo'])): ?>
                                            <img src="<?= htmlspecialchars(upload_url($item['client_photo'])) ?>" alt="<?= htmlspecialchars($item['client_name']) ?>" class="rounded-circle border border-secondary" style="width: 44px; height: 44px; object-fit: cover;">
                                        <?php else: ?>
                                            <?php
                                            $initials = '';
                                            $parts = explode(' ', trim($item['client_name']));
                                            foreach (array_slice($parts, 0, 2) as $p) {
                                                $initials .= mb_substr($p, 0, 1);
                                            }
                                            $initials = strtoupper($initials ?: 'C');
                                            ?>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold border border-secondary" style="width: 44px; height: 44px; background: linear-gradient(135deg, #2a2a3c, #1f1f2e); color: #f59e0b; font-size: 0.85rem;">
                                                <?= htmlspecialchars($initials) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Client Name & Company -->
                                    <td>
                                        <div class="fw-bold text-white"><?= htmlspecialchars($item['client_name']) ?></div>
                                        <?php if (!empty($item['client_designation']) || !empty($item['client_company'])): ?>
                                            <small class="text-muted d-block">
                                                <?= htmlspecialchars(implode(', ', array_filter([$item['client_designation'], $item['client_company']]))) ?>
                                            </small>
                                        <?php endif; ?>
                                        <?php if (!empty($item['project_name'])): ?>
                                            <span class="badge bg-dark border border-secondary text-info mt-1" style="font-size: 0.72rem;">
                                                <i class="fa-solid fa-clapperboard me-1"></i> <?= htmlspecialchars($item['project_name']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Rating -->
                                    <td>
                                        <div class="text-warning fw-bold small d-flex align-items-center gap-1">
                                            <?php
                                            $r = (float) $item['rating'];
                                            for ($s = 1; $s <= 5; $s++) {
                                                if ($s <= $r) {
                                                    echo '<i class="fa-solid fa-star"></i>';
                                                } elseif ($s - 0.5 <= $r) {
                                                    echo '<i class="fa-solid fa-star-half-stroke"></i>';
                                                } else {
                                                    echo '<i class="fa-regular fa-star text-muted opacity-50"></i>';
                                                }
                                            }
                                            ?>
                                            <span class="ms-1 text-white"><?= number_format($r, 1) ?></span>
                                        </div>
                                    </td>

                                    <!-- Review Text -->
                                    <td>
                                        <div class="text-light small" style="line-height: 1.45;">
                                            “<?= htmlspecialchars(truncate_text($item['review_text'], 130)) ?>”
                                        </div>
                                    </td>

                                    <!-- Platform Source -->
                                    <td>
                                        <?php if ($item['source'] === 'google'): ?>
                                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50">
                                                <i class="fa-brands fa-google me-1"></i> Google
                                            </span>
                                        <?php elseif ($item['source'] === 'goodfirms'): ?>
                                            <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50">
                                                <i class="fa-solid fa-shield-halved me-1"></i> GoodFirms
                                            </span>
                                        <?php elseif ($item['source'] === 'clutch'): ?>
                                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">
                                                <i class="fa-solid fa-award me-1"></i> Clutch
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary">
                                                <i class="fa-solid fa-user-check me-1"></i> Direct
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td>
                                        <form method="POST" action="<?= site_url('admin/reviews/action.php') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                            <?php if ($item['status'] === 'active'): ?>
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2 small" title="Click to Deactivate">
                                                    <i class="fa-solid fa-circle-check me-1"></i> Active
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2 small" title="Click to Activate">
                                                    <i class="fa-solid fa-circle-pause me-1"></i> Inactive
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('admin/reviews/edit.php?id=' . (int) $item['id']) ?>" class="btn btn-outline-info" title="Edit Review">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= (int) $item['id'] ?>" title="Delete Review">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal<?= (int) $item['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content bg-dark border-secondary text-white text-start">
                                                    <div class="modal-header border-secondary">
                                                        <h5 class="modal-title fw-bold">
                                                            <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i> Confirm Delete
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-4">
                                                        <p class="mb-2">Are you sure you want to delete review from <strong><?= htmlspecialchars($item['client_name']) ?></strong>?</p>
                                                        <p class="text-muted small mb-0">This testimonial will be permanently removed from all website showcase sections.</p>
                                                    </div>
                                                    <div class="modal-footer border-secondary">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form method="POST" action="<?= site_url('admin/reviews/action.php') ?>" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fa-solid fa-trash me-1"></i> Delete Review
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <small class="text-muted">
                            Showing <?= $offset + 1 ?> to <?= min($offset + $per_page, $total_items) ?> of <?= $total_items ?> entries
                        </small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link bg-dark border-secondary text-white" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>">
                                        Previous
                                    </a>
                                </li>
                                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                    <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                                        <a class="page-link <?= ($p === $page) ? 'bg-primary border-primary text-white' : 'bg-dark border-secondary text-white' ?>" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
                                            <?= $p ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link bg-dark border-secondary text-white" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>">
                                        Next
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
