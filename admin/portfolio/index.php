<?php

/**
 * AR Entertainment - Portfolio & Video Projects Manager
 * 
 * Listing, Search, Category Filter, Status & Featured Toggles, and Pagination.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Portfolio & Video Projects';
$current_page = 'portfolio';

// -----------------------------------------------------------------------------
// Filters & Search
// -----------------------------------------------------------------------------
$search      = trim($_GET['q'] ?? '');
$category_id = (int) ($_GET['category_id'] ?? 0);
$status      = trim($_GET['status'] ?? '');
$featured    = trim($_GET['featured'] ?? '');
$page        = max(1, (int) ($_GET['page'] ?? 1));
$per_page    = 12;
$offset      = ($page - 1) * $per_page;

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(p.title LIKE ? OR p.client_name LIKE ? OR p.description LIKE ? OR p.category_name LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if ($category_id > 0) {
    $where[] = "p.category_id = ?";
    $params[] = $category_id;
}

if (in_array($status, ['active', 'inactive'], true)) {
    $where[] = "p.status = ?";
    $params[] = $status;
}

if ($featured === '1') {
    $where[] = "p.is_featured = 1";
} elseif ($featured === '0') {
    $where[] = "p.is_featured = 0";
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// -----------------------------------------------------------------------------
// Data Queries & Stats
// -----------------------------------------------------------------------------
try {
    $total_projects    = (int) db()->query("SELECT COUNT(*) FROM portfolio")->fetchColumn();
    $active_projects   = (int) db()->query("SELECT COUNT(*) FROM portfolio WHERE status = 'active'")->fetchColumn();
    $featured_projects = (int) db()->query("SELECT COUNT(*) FROM portfolio WHERE is_featured = 1")->fetchColumn();

    // Fetch portfolio categories for filter dropdown
    $cat_stmt = db()->query("
        SELECT c.*, COUNT(p.id) as project_count
        FROM categories c
        LEFT JOIN portfolio p ON c.id = p.category_id
        WHERE c.type = 'portfolio'
        GROUP BY c.id
        ORDER BY c.sort_order ASC, c.name ASC
    ");
    $categories = $cat_stmt->fetchAll();

    // Total filtered records
    $count_stmt = db()->prepare("SELECT COUNT(*) FROM portfolio p {$where_sql}");
    $count_stmt->execute($params);
    $total_filtered = (int) $count_stmt->fetchColumn();
    $total_pages = max(1, (int) ceil($total_filtered / $per_page));

    // Fetch paginated projects
    $sql = "
        SELECT p.*, c.name AS category_display_name
        FROM portfolio p
        LEFT JOIN categories c ON p.category_id = c.id
        {$where_sql}
        ORDER BY p.sort_order ASC, p.id DESC
        LIMIT {$per_page} OFFSET {$offset}
    ";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $projects = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    $projects = [];
    $categories = [];
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
                        <li class="breadcrumb-item active text-white" aria-current="page">Portfolio</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Portfolio &amp; Video Showcase</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= site_url('admin/portfolio/categories') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-tags me-1"></i> Video Categories
                </a>
                <a href="<?= site_url('admin/portfolio/create') ?>" class="btn btn-ar-primary">
                    <i class="fa-solid fa-plus me-1"></i> Add Video Project
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <!-- Quick Stats Overview -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= number_format($total_projects) ?></div>
                        <div class="stat-label">Total Video Works</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-video"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-warning"><?= number_format($featured_projects) ?></div>
                        <div class="stat-label">Featured on Homepage</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-success"><?= number_format($active_projects) ?></div>
                        <div class="stat-label">Active / Published</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="card-ar p-3 mb-4">
            <form method="GET" action="<?= site_url('admin/portfolio') ?>" class="row g-3 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control bg-dark border-secondary text-white" placeholder="Search title, client, or keywords...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="category_id" class="form-select bg-dark border-secondary text-white">
                        <option value="0">All Video Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?> (<?= $cat['project_count'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select bg-dark border-secondary text-white">
                        <option value="">All Statuses</option>
                        <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="featured" class="form-select bg-dark border-secondary text-white">
                        <option value="">All Featured</option>
                        <option value="1" <?= ($featured === '1') ? 'selected' : '' ?>>Featured Only ⭐</option>
                        <option value="0" <?= ($featured === '0') ? 'selected' : '' ?>>Standard Only</option>
                    </select>
                </div>
                <div class="col-6 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-ar-primary w-100" title="Apply Filter">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <?php if ($search !== '' || $category_id > 0 || $status !== '' || $featured !== ''): ?>
                        <a href="<?= site_url('admin/portfolio') ?>" class="btn btn-ar-secondary" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Portfolio Table Card -->
        <div class="card-ar p-0 overflow-hidden">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--ar-border-color) !important;">
                <h6 class="fw-bold mb-0 text-white">
                    Video Projects <span class="badge bg-secondary ms-2"><?= number_format($total_filtered) ?> Total</span>
                </h6>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-custom align-middle">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Preview</th>
                            <th>Project Title &amp; Slug</th>
                            <th>Category</th>
                            <th>Client &amp; Year</th>
                            <th class="text-center">Featured</th>
                            <th class="text-center">Status</th>
                            <th class="text-end" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projects)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fa-solid fa-video-slash fs-1 opacity-25"></i>
                                    </div>
                                    <p class="mb-2">No video projects found matching your criteria.</p>
                                    <a href="<?= site_url('admin/portfolio/create') ?>" class="btn btn-sm btn-ar-primary">
                                        <i class="fa-solid fa-plus me-1"></i> Add First Video Project
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($projects as $p): ?>
                                <?php
                                $parsed_video = parse_video_url($p['video_url']);
                                $thumb_img = !empty($p['thumbnail']) ? upload_url($p['thumbnail']) : ($parsed_video['thumbnail_url'] ?: asset_url('images/placeholder.webp'));
                                ?>
                                <tr>
                                    <!-- Video Thumbnail Preview -->
                                    <td>
                                        <div class="position-relative rounded overflow-hidden" style="width: 90px; height: 52px; background: #000;">
                                            <img src="<?= htmlspecialchars($thumb_img) ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="w-100 h-100 object-fit-cover" onerror="this.src='https://img.youtube.com/vi/<?= htmlspecialchars($parsed_video['video_id']) ?>/hqdefault.jpg'">
                                            <a href="javascript:void(0)" class="position-absolute top-50 start-50 translate-middle text-white bg-dark bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px;" onclick="previewVideoModal('<?= htmlspecialchars(addslashes($p['title'])) ?>', '<?= htmlspecialchars(addslashes($parsed_video['embed_url'])) ?>')">
                                                <i class="fa-solid fa-play" style="font-size: 10px; margin-left: 2px;"></i>
                                            </a>
                                        </div>
                                    </td>

                                    <!-- Title & Slug -->
                                    <td>
                                        <div class="fw-bold text-white mb-1">
                                            <a href="<?= site_url('admin/portfolio/edit?id=' . $p['id']) ?>" class="text-white text-decoration-none">
                                                <?= htmlspecialchars($p['title']) ?>
                                            </a>
                                        </div>
                                        <div class="small text-muted font-monospace" style="font-size: 11px;">
                                            /portfolio/<?= htmlspecialchars($p['slug']) ?>
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td>
                                        <span class="badge bg-dark border border-secondary text-info">
                                            <?= htmlspecialchars($p['category_display_name'] ?: $p['category_name']) ?>
                                        </span>
                                    </td>

                                    <!-- Client & Year -->
                                    <td>
                                        <div class="text-white small fw-semibold">
                                            <?= htmlspecialchars($p['client_name'] ?: 'AR Entertainment') ?>
                                        </div>
                                        <div class="text-muted small">
                                            <?= htmlspecialchars($p['year'] ?: date('Y')) ?>
                                        </div>
                                    </td>

                                    <!-- Featured Toggle -->
                                    <td class="text-center">
                                        <form method="POST" action="<?= site_url('admin/portfolio/action') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_featured">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <?php if ($p['is_featured']): ?>
                                                <button type="submit" class="btn btn-sm btn-link text-warning p-0 text-decoration-none" title="Click to Unfeature">
                                                    <i class="fa-solid fa-star fs-5"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-link text-muted p-0 text-decoration-none opacity-50 hover-opacity-100" title="Click to Feature on Homepage">
                                                    <i class="fa-regular fa-star fs-5"></i>
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td class="text-center">
                                        <form method="POST" action="<?= site_url('admin/portfolio/action') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <?php if ($p['status'] === 'active'): ?>
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
                                            <a href="<?= site_url('admin/portfolio/edit?id=' . $p['id']) ?>" class="btn btn-ar-secondary" title="Edit Video Project">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?= site_url('portfolio/' . urlencode($p['slug'])) ?>" target="_blank" class="btn btn-ar-secondary text-info" title="Preview Frontend">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-ar-secondary text-danger" title="Delete Project" onclick="confirmDeleteProject(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['title'])) ?>')">
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
                        Showing <?= $offset + 1 ?> to <?= min($offset + $per_page, $total_filtered) ?> of <?= number_format($total_filtered) ?> projects
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link bg-dark border-secondary text-white" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                    &laquo;
                                </a>
                            </li>

                            <?php for ($p_num = 1; $p_num <= $total_pages; $p_num++): ?>
                                <?php if ($p_num == 1 || $p_num == $total_pages || ($p_num >= $page - 2 && $p_num <= $page + 2)): ?>
                                    <li class="page-item <?= ($p_num == $page) ? 'active' : '' ?>">
                                        <a class="page-link <?= ($p_num == $page) ? 'bg-danger border-danger text-white' : 'bg-dark border-secondary text-white' ?>" href="?<?= http_build_query(array_merge($_GET, ['page' => $p_num])) ?>">
                                            <?= $p_num ?>
                                        </a>
                                    </li>
                                <?php elseif ($p_num == $page - 3 || $p_num == $page + 3): ?>
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

    <!-- Interactive Video Preview Modal -->
    <div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                    <h5 class="modal-title fw-bold text-white" id="videoPreviewTitle">Video Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeVideoModal()"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9" style="background: #000;">
                        <iframe id="videoPreviewIframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/portfolio/action') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteProjectId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-1 text-muted">Are you sure you want to delete this video project?</p>
                        <p class="fw-bold text-white fs-6" id="deleteProjectTitle"></p>
                        <p class="small text-danger mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i> This will also remove any uploaded project thumbnail from storage.</p>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete Video</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewVideoModal(title, embedUrl) {
            document.getElementById('videoPreviewTitle').textContent = title;
            document.getElementById('videoPreviewIframe').src = embedUrl;
            new bootstrap.Modal(document.getElementById('videoPreviewModal')).show();
        }

        function closeVideoModal() {
            document.getElementById('videoPreviewIframe').src = '';
        }

        document.getElementById('videoPreviewModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('videoPreviewIframe').src = '';
        });

        function confirmDeleteProject(id, title) {
            document.getElementById('deleteProjectId').value = id;
            document.getElementById('deleteProjectTitle').textContent = '"' + title + '"';
            new bootstrap.Modal(document.getElementById('deleteProjectModal')).show();
        }
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
