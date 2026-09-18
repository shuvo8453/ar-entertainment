<?php
/**
 * AR Entertainment - Client Reviews & Testimonials Manager
 * Phase 4.6: Management CRUD (admin/reviews/index.php)
 */
require_once dirname(__DIR__) . '/auth_check.php';

$page_title   = 'Client Reviews';
$current_page = 'reviews';

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
    <?php require_once ADMIN_PATH . '/includes/navbar.php'; ?>

    <main class="admin-content">
        <!-- Breadcrumb & Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Client Reviews</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Client Reviews &amp; Testimonials</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/reviews/create.php') ?>" class="btn btn-ar-primary">
                    <i class="fa-solid fa-plus me-1"></i> Add Review
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-warning"><?= number_format((float) ($stats['avg_rating'] ?? 5.0), 1) ?> <span style="font-size: 0.9rem; color: #f59e0b;">★</span></div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= (int) ($stats['total'] ?? 0) ?></div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-danger"><?= (int) ($stats['google'] ?? 0) ?></div>
                        <div class="stat-label">Google Reviews</div>
                    </div>
                    <div class="stat-icon icon-red">
                        <i class="fa-brands fa-google"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-success"><?= (int) ($stats['active'] ?? 0) ?></div>
                        <div class="stat-label">Published Active</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="card-ar p-3 mb-4">
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

                <div>
                    <a href="<?= site_url('admin/reviews/create.php') ?>" class="btn btn-sm btn-ar-primary">
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
                    <button type="submit" class="btn btn-ar-primary flex-grow-1">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <?php if ($search !== '' || $status_filter !== '' || $source_filter !== ''): ?>
                        <a href="<?= site_url('admin/reviews') ?>" class="btn btn-ar-secondary" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Reviews Table Card -->
        <div class="card-ar p-0 overflow-hidden">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--ar-border-color) !important;">
                <h6 class="fw-bold mb-0 text-white">
                    Client Testimonials <span class="badge bg-secondary ms-2"><?= number_format($total_items) ?> Total</span>
                </h6>
                <span class="text-muted small">Sorted by Display Priority</span>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;" class="text-center">Sort</th>
                            <th style="width: 60px;">Avatar</th>
                            <th>Client &amp; Company</th>
                            <th style="width: 120px;" class="text-center">Rating</th>
                            <th>Review Snippet</th>
                            <th>Source</th>
                            <th class="text-center">Status</th>
                            <th style="width: 130px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reviews)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fa-solid fa-star-half-stroke fs-1 opacity-25"></i>
                                    </div>
                                    <h5 class="text-white fw-bold">No client reviews found</h5>
                                    <p class="text-muted small mb-2">Try adjusting your filters or add verified client testimonials.</p>
                                    <a href="<?= site_url('admin/reviews/create.php') ?>" class="btn btn-sm btn-ar-primary">
                                        <i class="fa-solid fa-plus me-1"></i> Add First Review
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reviews as $rev): ?>
                                <?php
                                $rating = (float) $rev['rating'];
                                $full_stars = (int) floor($rating);
                                $has_half   = ($rating - $full_stars) >= 0.3;
                                ?>
                                <tr>
                                    <!-- Sort Order -->
                                    <td class="text-center">
                                        <span class="badge bg-dark border border-secondary text-muted">
                                            #<?= (int) $rev['sort_order'] ?>
                                        </span>
                                    </td>

                                    <!-- Client Avatar -->
                                    <td>
                                        <div class="rounded-circle border border-secondary d-flex align-items-center justify-content-center overflow-hidden" style="width: 44px; height: 44px; background-color: #1a1a24;">
                                            <?php if (!empty($rev['client_photo'])): ?>
                                                <img src="<?= htmlspecialchars(upload_url($rev['client_photo'])) ?>" alt="<?= htmlspecialchars($rev['client_name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <span class="fw-bold text-muted small">
                                                    <?= strtoupper(mb_substr($rev['client_name'], 0, 1)) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Client Name & Company -->
                                    <td>
                                        <div class="fw-bold text-white mb-0"><?= htmlspecialchars($rev['client_name']) ?></div>
                                        <div class="small text-muted">
                                            <?= htmlspecialchars($rev['client_designation'] ? $rev['client_designation'] . ', ' : '') ?><?= htmlspecialchars($rev['client_company'] ?: '') ?>
                                        </div>
                                        <?php if (!empty($rev['project_name'])): ?>
                                            <div class="small text-info mt-1" style="font-size: 11px;">
                                                <i class="fa-solid fa-clapperboard me-1"></i> <?= htmlspecialchars($rev['project_name']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Rating Stars -->
                                    <td class="text-center text-nowrap">
                                        <div class="text-warning small mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $full_stars): ?>
                                                    <i class="fa-solid fa-star"></i>
                                                <?php elseif ($i == $full_stars + 1 && $has_half): ?>
                                                    <i class="fa-solid fa-star-half-stroke"></i>
                                                <?php else: ?>
                                                    <i class="fa-regular fa-star text-secondary"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="badge bg-dark border border-secondary text-warning small font-monospace">
                                            <?= number_format($rating, 1) ?>
                                        </span>
                                    </td>

                                    <!-- Review Text Snippet -->
                                    <td>
                                        <div class="small text-light" style="max-width: 320px; line-height: 1.4;">
                                            "<?= htmlspecialchars(truncate_text($rev['review_text'], 90)) ?>"
                                        </div>
                                    </td>

                                    <!-- Platform Source Badge -->
                                    <td>
                                        <?php if ($rev['source'] === 'google'): ?>
                                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50">
                                                <i class="fa-brands fa-google me-1"></i> Google
                                            </span>
                                        <?php elseif ($rev['source'] === 'goodfirms'): ?>
                                            <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50">
                                                <i class="fa-solid fa-shield-halved me-1"></i> GoodFirms
                                            </span>
                                        <?php elseif ($rev['source'] === 'clutch'): ?>
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
                                    <td class="text-center">
                                        <form method="POST" action="<?= site_url('admin/reviews/action.php') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?= (int) $rev['id'] ?>">
                                            <?php if ($rev['status'] === 'active'): ?>
                                                <button type="submit" class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 text-decoration-none btn btn-sm py-1 px-2" title="Click to Deactivate">
                                                    <i class="fa-solid fa-check me-1"></i> Active
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="badge bg-secondary bg-opacity-25 text-muted border border-secondary text-decoration-none btn btn-sm py-1 px-2" title="Click to Activate">
                                                    Inactive
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('admin/reviews/edit.php?id=' . (int) $rev['id']) ?>" class="btn btn-ar-secondary" title="Edit Review">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-ar-secondary text-danger" onclick="confirmDeleteReview(<?= (int) $rev['id'] ?>, '<?= htmlspecialchars(addslashes($rev['client_name'])) ?>')" title="Delete Review">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <?php if ($total_pages > 1): ?>
                <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-color: var(--ar-border-color) !important;">
                    <div class="small text-muted">
                        Showing <?= $offset + 1 ?> to <?= min($offset + $per_page, $total_items) ?> of <?= number_format($total_items) ?> reviews
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link bg-dark border-secondary text-white" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>">
                                    &laquo;
                                </a>
                            </li>
                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                                    <a class="page-link <?= ($p === $page) ? 'bg-danger border-danger text-white' : 'bg-dark border-secondary text-white' ?>" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
                                        <?= $p ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                <a class="page-link bg-dark border-secondary text-white" href="<?= site_url('admin/reviews/?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>">
                                    &raquo;
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/reviews/action.php') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteReviewId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Delete
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-1 text-muted">Are you sure you want to permanently delete review from:</p>
                        <p class="fw-bold text-white fs-6" id="deleteReviewName"></p>
                        <p class="small text-danger mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i> This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteReview(id, name) {
            document.getElementById('deleteReviewId').value = id;
            document.getElementById('deleteReviewName').textContent = '"' + name + '"';
            new bootstrap.Modal(document.getElementById('deleteReviewModal')).show();
        }
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
