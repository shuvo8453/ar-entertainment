<?php

/**
 * AR Entertainment - 64 District Service Areas / Filming Location Guides
 * 
 * Listing, Search, Status Toggles, Division Filters, and Pagination.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Service Areas & District Guides';
$current_page = 'service-areas';

// -----------------------------------------------------------------------------
// Filters & Search
// -----------------------------------------------------------------------------
$search   = trim($_GET['q'] ?? '');
$status   = trim($_GET['status'] ?? '');
$page     = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 16;
$offset   = ($page - 1) * $per_page;

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(title LIKE ? OR city_name LIKE ? OR summary LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if (in_array($status, ['active', 'inactive'], true)) {
    $where[] = "status = ?";
    $params[] = $status;
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// -----------------------------------------------------------------------------
// Data Queries & Stats
// -----------------------------------------------------------------------------
try {
    $total_districts  = (int) db()->query("SELECT COUNT(*) FROM service_areas")->fetchColumn();
    $active_districts = (int) db()->query("SELECT COUNT(*) FROM service_areas WHERE status = 'active'")->fetchColumn();
    $inactive_count   = (int) db()->query("SELECT COUNT(*) FROM service_areas WHERE status = 'inactive'")->fetchColumn();

    // Total filtered records
    $count_stmt = db()->prepare("SELECT COUNT(*) FROM service_areas {$where_sql}");
    $count_stmt->execute($params);
    $total_filtered = (int) $count_stmt->fetchColumn();
    $total_pages = max(1, (int) ceil($total_filtered / $per_page));

    // Fetch paginated district guides
    $sql = "
        SELECT *
        FROM service_areas
        {$where_sql}
        ORDER BY sort_order ASC, city_name ASC
        LIMIT {$per_page} OFFSET {$offset}
    ";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $service_areas = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    $service_areas = [];
    $total_filtered = 0;
    $total_pages = 1;
}

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
                        <li class="breadcrumb-item active text-white" aria-current="page">Service Areas</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">64 District Filming Location Guides</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/service-areas/create') ?>" class="btn btn-ar-primary">
                    <i class="fa-solid fa-plus me-1"></i> Add District Guide
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <!-- Quick Stats Overview -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= number_format($total_districts) ?> <span class="fs-6 text-muted font-normal">/ 64</span></div>
                        <div class="stat-label">Districts Configured</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-success"><?= number_format($active_districts) ?></div>
                        <div class="stat-label">Active Guides</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-muted"><?= number_format($inactive_count) ?></div>
                        <div class="stat-label">Draft / Inactive</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="card-ar p-3 mb-4">
            <form method="GET" action="<?= site_url('admin/service-areas') ?>" class="row g-3 align-items-center">
                <div class="col-12 col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control bg-dark border-secondary text-white" placeholder="Search by district name, guide title or keywords...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select bg-dark border-secondary text-white">
                        <option value="">All Statuses</option>
                        <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active (Live)</option>
                        <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive (Hidden)</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-ar-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <?php if ($search !== '' || $status !== ''): ?>
                        <a href="<?= site_url('admin/service-areas') ?>" class="btn btn-ar-secondary" title="Clear Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Service Areas Table Card -->
        <div class="card-ar p-0 overflow-hidden">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--ar-border-color) !important;">
                <h6 class="fw-bold mb-0 text-white">
                    District Guides <span class="badge bg-secondary ms-2"><?= number_format($total_filtered) ?> Total</span>
                </h6>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-custom align-middle">
                    <thead>
                        <tr>
                            <th style="width: 140px;">District Name</th>
                            <th>Page Title &amp; SEO Slug</th>
                            <th>Summary Snippet</th>
                            <th class="text-center">Order</th>
                            <th class="text-center">Status</th>
                            <th class="text-end" style="width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($service_areas)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fa-solid fa-map-location-dot fs-1 opacity-25"></i>
                                    </div>
                                    <p class="mb-2">No district filming location guides found.</p>
                                    <a href="<?= site_url('admin/service-areas/create') ?>" class="btn btn-sm btn-ar-primary">
                                        <i class="fa-solid fa-plus me-1"></i> Create First District Guide
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($service_areas as $area): ?>
                                <tr>
                                    <!-- District Name -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-location-dot text-danger"></i>
                                            <strong class="text-white"><?= htmlspecialchars($area['city_name']) ?></strong>
                                        </div>
                                    </td>

                                    <!-- Title & Slug -->
                                    <td>
                                        <div class="fw-bold text-white mb-1">
                                            <a href="<?= site_url('admin/service-areas/edit?id=' . $area['id']) ?>" class="text-white text-decoration-none">
                                                <?= htmlspecialchars($area['title']) ?>
                                            </a>
                                        </div>
                                        <div class="small text-muted font-monospace" style="font-size: 11px;">
                                            /service-area/<?= htmlspecialchars($area['slug']) ?>
                                        </div>
                                    </td>

                                    <!-- Summary -->
                                    <td>
                                        <div class="small text-muted text-truncate" style="max-width: 280px;">
                                            <?= htmlspecialchars($area['summary'] ?: '—') ?>
                                        </div>
                                    </td>

                                    <!-- Sort Order -->
                                    <td class="text-center">
                                        <span class="badge bg-dark border border-secondary text-muted">
                                            <?= (int) $area['sort_order'] ?>
                                        </span>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td class="text-center">
                                        <form method="POST" action="<?= site_url('admin/service-areas/action') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?= $area['id'] ?>">
                                            <?php if ($area['status'] === 'active'): ?>
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
                                            <a href="<?= site_url('admin/service-areas/edit?id=' . $area['id']) ?>" class="btn btn-ar-secondary" title="Edit Guide">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?= site_url('service-area/' . urlencode($area['slug'])) ?>" target="_blank" class="btn btn-ar-secondary text-info" title="Preview Frontend">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-ar-secondary text-danger" title="Delete Guide" onclick="confirmDeleteArea(<?= $area['id'] ?>, '<?= htmlspecialchars(addslashes($area['city_name'])) ?>')">
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
                        Showing <?= $offset + 1 ?> to <?= min($offset + $per_page, $total_filtered) ?> of <?= number_format($total_filtered) ?> district guides
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link bg-dark border-secondary text-white" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                    &laquo;
                                </a>
                            </li>

                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <?php if ($p == 1 || $p == $total_pages || ($p >= $page - 2 && $p <= $page + 2)): ?>
                                    <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                                        <a class="page-link <?= ($p == $page) ? 'bg-danger border-danger text-white' : 'bg-dark border-secondary text-white' ?>" href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>">
                                            <?= $p ?>
                                        </a>
                                    </li>
                                <?php elseif ($p == $page - 3 || $p == $page + 3): ?>
                                    <li class="page-item disabled"><span class="page-link bg-dark border-secondary text-muted">...</span></li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                <a class="page-link bg-dark border-secondary text-white" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
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
    <div class="modal fade" id="deleteAreaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/service-areas/action') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteAreaId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-1 text-muted">Are you sure you want to delete this district filming guide?</p>
                        <p class="fw-bold text-white fs-6" id="deleteAreaTitle"></p>
                        <p class="small text-danger mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i> This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete Guide</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteArea(id, name) {
            document.getElementById('deleteAreaId').value = id;
            document.getElementById('deleteAreaTitle').textContent = '"' + name + ' District Guide"';
            new bootstrap.Modal(document.getElementById('deleteAreaModal')).show();
        }
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
