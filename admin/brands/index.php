<?php
/**
 * AR Entertainment - Brands, Clients & Awards Manager
 * Phase 4.5: Management CRUD (admin/brands/index.php)
 */
require_once dirname(__DIR__) . '/auth_check.php';

$page_title   = 'Brands & Clients';
$current_page = 'brands';

// Filter inputs
$type_filter   = trim($_GET['type'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$search        = trim($_GET['q'] ?? '');
$page          = max(1, (int) ($_GET['page'] ?? 1));
$per_page      = 15;

// Allowed brand types
$valid_types = ['client', 'partner', 'award', 'affiliation'];
if (!in_array($type_filter, $valid_types, true)) {
    $type_filter = '';
}

// Allowed statuses
$valid_statuses = ['active', 'inactive'];
if (!in_array($status_filter, $valid_statuses, true)) {
    $status_filter = '';
}

// Build query
$where  = [];
$params = [];

if ($type_filter !== '') {
    $where[]  = "brand_type = :brand_type";
    $params[':brand_type'] = $type_filter;
}

if ($status_filter !== '') {
    $where[]  = "status = :status";
    $params[':status'] = $status_filter;
}

if ($search !== '') {
    $where[] = "(name LIKE :q OR website_url LIKE :q2)";
    $params[':q']  = "%{$search}%";
    $params[':q2'] = "%{$search}%";
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Count total
try {
    $count_stmt = db()->prepare("SELECT COUNT(*) FROM brands {$where_sql}");
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

// Fetch brands
$brands = [];
try {
    $sql = "
        SELECT * FROM brands
        {$where_sql}
        ORDER BY sort_order ASC, name ASC
        LIMIT :limit OFFSET :offset
    ";
    $stmt = db()->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $brands = $stmt->fetchAll();
} catch (PDOException $e) {
    set_flash('error', 'Failed to retrieve brands: ' . $e->getMessage());
}

// Fetch stats
$stats = [
    'total'       => 0,
    'clients'     => 0,
    'partners'    => 0,
    'awards'      => 0,
    'affiliations'=> 0
];
try {
    $stats_stmt = db()->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN brand_type = 'client' THEN 1 ELSE 0 END) as clients,
            SUM(CASE WHEN brand_type = 'partner' THEN 1 ELSE 0 END) as partners,
            SUM(CASE WHEN brand_type = 'award' THEN 1 ELSE 0 END) as awards,
            SUM(CASE WHEN brand_type = 'affiliation' THEN 1 ELSE 0 END) as affiliations
        FROM brands
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
                        <li class="breadcrumb-item active text-white" aria-current="page">Brands &amp; Clients</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Brands, Clients &amp; Awards</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/brands/create.php') ?>" class="btn btn-ar-primary">
                    <i class="fa-solid fa-plus me-1"></i> Add Brand / Client
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= (int) ($stats['clients'] ?? 0) ?></div>
                        <div class="stat-label">Clients</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-info"><?= (int) ($stats['partners'] ?? 0) ?></div>
                        <div class="stat-label">Partners</div>
                    </div>
                    <div class="stat-icon icon-purple">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-warning"><?= (int) ($stats['awards'] ?? 0) ?></div>
                        <div class="stat-label">Awards</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-success"><?= (int) ($stats['affiliations'] ?? 0) ?></div>
                        <div class="stat-label">Affiliations</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="card-ar p-3 mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <!-- Type Tabs -->
                <ul class="nav nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link <?= empty($type_filter) ? 'active' : '' ?>" href="<?= site_url('admin/brands/?' . http_build_query(array_merge($_GET, ['type' => '', 'page' => 1]))) ?>">
                            All Types <span class="badge bg-secondary ms-1"><?= (int) ($stats['total'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type_filter === 'client') ? 'active' : '' ?>" href="<?= site_url('admin/brands/?' . http_build_query(array_merge($_GET, ['type' => 'client', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-briefcase me-1"></i> Clients <span class="badge bg-primary ms-1"><?= (int) ($stats['clients'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type_filter === 'partner') ? 'active' : '' ?>" href="<?= site_url('admin/brands/?' . http_build_query(array_merge($_GET, ['type' => 'partner', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-handshake me-1"></i> Partners <span class="badge bg-info text-dark ms-1"><?= (int) ($stats['partners'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type_filter === 'award') ? 'active' : '' ?>" href="<?= site_url('admin/brands/?' . http_build_query(array_merge($_GET, ['type' => 'award', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-trophy me-1"></i> Awards <span class="badge bg-warning text-dark ms-1"><?= (int) ($stats['awards'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type_filter === 'affiliation') ? 'active' : '' ?>" href="<?= site_url('admin/brands/?' . http_build_query(array_merge($_GET, ['type' => 'affiliation', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-certificate me-1"></i> Affiliations <span class="badge bg-secondary ms-1"><?= (int) ($stats['affiliations'] ?? 0) ?></span>
                        </a>
                    </li>
                </ul>

                <div>
                    <a href="<?= site_url('admin/brands/create.php') ?>" class="btn btn-sm btn-ar-primary">
                        <i class="fa-solid fa-plus me-1"></i> Add Brand / Client
                    </a>
                </div>
            </div>

            <!-- Search & Status Row -->
            <form method="GET" action="<?= site_url('admin/brands') ?>" class="row g-2 align-items-center">
                <?php if (!empty($type_filter)): ?>
                    <input type="hidden" name="type" value="<?= htmlspecialchars($type_filter) ?>">
                <?php endif; ?>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="q" class="form-control bg-dark border-secondary text-white" placeholder="Search brand name or website..." value="<?= htmlspecialchars($search) ?>">
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
                    <?php if ($search !== '' || $status_filter !== '' || $type_filter !== ''): ?>
                        <a href="<?= site_url('admin/brands') ?>" class="btn btn-ar-secondary" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Brands Table Card -->
        <div class="card-ar p-0 overflow-hidden">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--ar-border-color) !important;">
                <h6 class="fw-bold mb-0 text-white">
                    Brand List <span class="badge bg-secondary ms-2"><?= number_format($total_items) ?> Total</span>
                </h6>
                <span class="text-muted small">Ordered by Sort Priority</span>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">Sort</th>
                            <th style="width: 120px;">Logo</th>
                            <th>Brand / Company Name</th>
                            <th>Category / Type</th>
                            <th>Website Link</th>
                            <th class="text-center">Status</th>
                            <th style="width: 130px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($brands)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fa-solid fa-award fs-1 opacity-25"></i>
                                    </div>
                                    <h5 class="text-white fw-bold">No brands or partners found</h5>
                                    <p class="text-muted small mb-2">Try adjusting your search criteria or add your first brand.</p>
                                    <a href="<?= site_url('admin/brands/create.php') ?>" class="btn btn-sm btn-ar-primary">
                                        <i class="fa-solid fa-plus me-1"></i> Add Brand / Client
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($brands as $item): ?>
                                <tr>
                                    <!-- Sort Order -->
                                    <td class="text-center">
                                        <span class="badge bg-dark border border-secondary text-muted">
                                            #<?= (int) $item['sort_order'] ?>
                                        </span>
                                    </td>

                                    <!-- Logo Thumbnail -->
                                    <td>
                                        <div class="rounded-3 border border-secondary p-1 d-flex align-items-center justify-content-center" style="width: 90px; height: 45px; background-color: #1a1a24 !important;">
                                            <?php if (!empty($item['logo'])): ?>
                                                <img src="<?= htmlspecialchars(upload_url($item['logo'])) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="max-width: 80px; max-height: 35px; object-fit: contain;">
                                            <?php else: ?>
                                                <i class="fa-solid fa-image text-muted"></i>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Brand Name -->
                                    <td>
                                        <div class="fw-bold text-white mb-0"><?= htmlspecialchars($item['name']) ?></div>
                                        <small class="text-muted font-monospace" style="font-size: 11px;">ID: #<?= (int) $item['id'] ?></small>
                                    </td>

                                    <!-- Brand Type -->
                                    <td>
                                        <?php if ($item['brand_type'] === 'client'): ?>
                                            <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50">
                                                <i class="fa-solid fa-briefcase me-1"></i> Client
                                            </span>
                                        <?php elseif ($item['brand_type'] === 'partner'): ?>
                                            <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50">
                                                <i class="fa-solid fa-handshake me-1"></i> Partner
                                            </span>
                                        <?php elseif ($item['brand_type'] === 'award'): ?>
                                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">
                                                <i class="fa-solid fa-trophy me-1"></i> Award
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary">
                                                <i class="fa-solid fa-certificate me-1"></i> Affiliation
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Website URL -->
                                    <td>
                                        <?php if (!empty($item['website_url'])): ?>
                                            <a href="<?= htmlspecialchars($item['website_url']) ?>" target="_blank" rel="noopener noreferrer" class="text-info small text-decoration-none d-inline-flex align-items-center gap-1">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                <?= htmlspecialchars(parse_url($item['website_url'], PHP_URL_HOST) ?: $item['website_url']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td class="text-center">
                                        <form method="POST" action="<?= site_url('admin/brands/action.php') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                            <?php if ($item['status'] === 'active'): ?>
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
                                            <a href="<?= site_url('admin/brands/edit.php?id=' . (int) $item['id']) ?>" class="btn btn-ar-secondary" title="Edit Brand">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-ar-secondary text-danger" onclick="confirmDeleteBrand(<?= (int) $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['name'])) ?>')" title="Delete Brand">
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
                        Showing <?= $offset + 1 ?> to <?= min($offset + $per_page, $total_items) ?> of <?= number_format($total_items) ?> brands
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link bg-dark border-secondary text-white" href="<?= site_url('admin/brands/?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>">
                                    &laquo;
                                </a>
                            </li>
                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                                    <a class="page-link <?= ($p === $page) ? 'bg-danger border-danger text-white' : 'bg-dark border-secondary text-white' ?>" href="<?= site_url('admin/brands/?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
                                        <?= $p ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                <a class="page-link bg-dark border-secondary text-white" href="<?= site_url('admin/brands/?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>">
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
    <div class="modal fade" id="deleteBrandModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/brands/action.php') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteBrandId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Delete
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-1 text-muted">Are you sure you want to permanently delete this brand?</p>
                        <p class="fw-bold text-white fs-6" id="deleteBrandName"></p>
                        <p class="small text-danger mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i> This will also remove the logo from file storage.</p>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteBrand(id, name) {
            document.getElementById('deleteBrandId').value = id;
            document.getElementById('deleteBrandName').textContent = '"' + name + '"';
            new bootstrap.Modal(document.getElementById('deleteBrandModal')).show();
        }
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
