<?php

/**
 * AR Entertainment - Blog Articles Management
 * 
 * Listing, Search, Category Filter, Status Filter, Pagination,
 * Quick Status & Featured Toggle, and Deletion.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Blog Articles';
$current_page = 'blogs';

// -----------------------------------------------------------------------------
// Filters & Search
// -----------------------------------------------------------------------------
$search   = trim($_GET['q'] ?? '');
$category = (int) ($_GET['category'] ?? 0);
$status   = trim($_GET['status'] ?? '');
$page     = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 12;
$offset   = ($page - 1) * $per_page;

// Build query conditions
$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(b.title LIKE ? OR b.tags LIKE ? OR b.summary LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if ($category > 0) {
    $where[] = "b.category_id = ?";
    $params[] = $category;
}

if (in_array($status, ['published', 'draft', 'archived'], true)) {
    $where[] = "b.status = ?";
    $params[] = $status;
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// -----------------------------------------------------------------------------
// Quick Counts & Stats
// -----------------------------------------------------------------------------
try {
    $total_articles   = (int) db()->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
    $published_count  = (int) db()->query("SELECT COUNT(*) FROM blogs WHERE status = 'published'")->fetchColumn();
    $draft_count      = (int) db()->query("SELECT COUNT(*) FROM blogs WHERE status = 'draft'")->fetchColumn();
    $total_views      = (int) db()->query("SELECT COALESCE(SUM(views), 0) FROM blogs")->fetchColumn();

    // Fetch blog categories for filter dropdown
    $categories = db()->query("SELECT id, name FROM categories WHERE type = 'blog' ORDER BY name ASC")->fetchAll();

    // Total filtered records for pagination
    $count_stmt = db()->prepare("SELECT COUNT(*) FROM blogs b {$where_sql}");
    $count_stmt->execute($params);
    $total_filtered = (int) $count_stmt->fetchColumn();
    $total_pages = max(1, (int) ceil($total_filtered / $per_page));

    // Fetch paginated articles
    $sql = "
        SELECT 
            b.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM blogs b
        LEFT JOIN categories c ON b.category_id = c.id
        {$where_sql}
        ORDER BY b.is_featured DESC, b.created_at DESC
        LIMIT {$per_page} OFFSET {$offset}
    ";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    $articles = [];
    $total_filtered = 0;
    $total_pages = 1;
    $categories = [];
}

require_once ADMIN_PATH . '/includes/header.php';
require_once ADMIN_PATH . '/includes/sidebar.php';
?>

<div class="admin-main">
    <?php require_once ADMIN_PATH . '/includes/navbar.php'; ?>

    <main class="admin-content">
        <!-- Breadcrumb & Actions Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/index.php') ?>" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Blog Articles</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Blog &amp; Article Manager</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= site_url('admin/blogs/categories.php') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-tags me-1"></i> Manage Categories
                </a>
                <a href="<?= site_url('admin/blogs/create.php') ?>" class="btn btn-ar-primary">
                    <i class="fa-solid fa-plus me-1"></i> Write New Article
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <!-- Quick Stats Overview -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= number_format($total_articles) ?></div>
                        <div class="stat-label">Total Articles</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-success"><?= number_format($published_count) ?></div>
                        <div class="stat-label">Published</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-warning"><?= number_format($draft_count) ?></div>
                        <div class="stat-label">Drafts</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-pen-ruler"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-danger"><?= number_format($total_views) ?></div>
                        <div class="stat-label">Article Views</div>
                    </div>
                    <div class="stat-icon icon-red">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="card-ar p-3 mb-4">
            <form method="GET" action="<?= site_url('admin/blogs/index.php') ?>" class="row g-3 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control bg-dark border-secondary text-white" placeholder="Search by title, tag or keyword...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="category" class="form-select bg-dark border-secondary text-white">
                        <option value="0">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($category === (int)$cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select bg-dark border-secondary text-white">
                        <option value="">All Statuses</option>
                        <option value="published" <?= ($status === 'published') ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= ($status === 'draft') ? 'selected' : '' ?>>Draft</option>
                        <option value="archived" <?= ($status === 'archived') ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-ar-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <?php if ($search !== '' || $category > 0 || $status !== ''): ?>
                        <a href="<?= site_url('admin/blogs/index.php') ?>" class="btn btn-ar-secondary" title="Clear Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Article Table Card -->
        <div class="card-ar p-0 overflow-hidden">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--ar-border-color) !important;">
                <h6 class="fw-bold mb-0 text-white">
                    Articles List <span class="badge bg-secondary ms-2"><?= number_format($total_filtered) ?> Total</span>
                </h6>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-custom align-middle">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Image</th>
                            <th>Article Title &amp; Slug</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th class="text-center">Views</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Featured</th>
                            <th>Date</th>
                            <th class="text-end" style="width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($articles)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fa-solid fa-newspaper fs-1 opacity-25"></i>
                                    </div>
                                    <p class="mb-2">No blog articles found matching your criteria.</p>
                                    <a href="<?= site_url('admin/blogs/create.php') ?>" class="btn btn-sm btn-ar-primary">
                                        <i class="fa-solid fa-plus me-1"></i> Write First Article
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($articles as $post): ?>
                                <tr>
                                    <!-- Thumbnail -->
                                    <td>
                                        <div style="width: 54px; height: 38px; border-radius: 6px; overflow: hidden; background-color: #242638;" class="d-flex align-items-center justify-content-center">
                                            <?php if (!empty($post['thumbnail'])): ?>
                                                <img src="<?= upload_url($post['thumbnail']) ?>" alt="thumb" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='<?= asset_url('images/placeholder.webp') ?>';">
                                            <?php else: ?>
                                                <i class="fa-solid fa-image text-muted"></i>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Title & Slug -->
                                    <td>
                                        <div class="fw-bold text-white mb-1">
                                            <a href="<?= site_url('admin/blogs/edit.php?id=' . $post['id']) ?>" class="text-white text-decoration-none">
                                                <?= htmlspecialchars($post['title']) ?>
                                            </a>
                                        </div>
                                        <div class="small text-muted font-monospace" style="font-size: 11px;">
                                            /blog/<?= htmlspecialchars($post['slug']) ?>
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td>
                                        <?php if (!empty($post['category_name'])): ?>
                                            <span class="badge bg-dark border border-secondary text-info">
                                                <?= htmlspecialchars($post['category_name']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-dark text-muted">Uncategorized</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Author -->
                                    <td>
                                        <span class="small text-muted">
                                            <i class="fa-solid fa-user-pen me-1"></i> <?= htmlspecialchars($post['author_name'] ?? 'Azizul Hoque Shiplu') ?>
                                        </span>
                                    </td>

                                    <!-- Views -->
                                    <td class="text-center">
                                        <span class="badge bg-dark border border-secondary text-white">
                                            <i class="fa-solid fa-eye me-1 text-muted"></i> <?= number_format($post['views']) ?>
                                        </span>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td class="text-center">
                                        <form method="POST" action="<?= site_url('admin/blogs/action.php') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                            <?php if ($post['status'] === 'published'): ?>
                                                <button type="submit" class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 text-decoration-none btn btn-sm py-1 px-2" title="Click to mark as Draft">
                                                    <i class="fa-solid fa-check me-1"></i> Published
                                                </button>
                                            <?php elseif ($post['status'] === 'draft'): ?>
                                                <button type="submit" class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 text-decoration-none btn btn-sm py-1 px-2" title="Click to Publish">
                                                    <i class="fa-solid fa-clock me-1"></i> Draft
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="badge bg-secondary bg-opacity-25 text-muted border border-secondary text-decoration-none btn btn-sm py-1 px-2">
                                                    Archived
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>

                                    <!-- Featured Toggle -->
                                    <td class="text-center">
                                        <form method="POST" action="<?= site_url('admin/blogs/action.php') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_featured">
                                            <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                            <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="<?= $post['is_featured'] ? 'Featured (Click to unset)' : 'Click to Feature' ?>">
                                                <?php if ($post['is_featured']): ?>
                                                    <i class="fa-solid fa-star text-warning fs-5"></i>
                                                <?php else: ?>
                                                    <i class="fa-regular fa-star text-muted fs-5"></i>
                                                <?php endif; ?>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Date -->
                                    <td>
                                        <div class="small text-white"><?= format_date($post['published_at'] ?? $post['created_at']) ?></div>
                                        <div class="text-muted" style="font-size: 11px;"><?= date('h:i A', strtotime($post['created_at'])) ?></div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('admin/blogs/edit.php?id=' . $post['id']) ?>" class="btn btn-ar-secondary" title="Edit Article">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?= site_url('blog-single.php?slug=' . urlencode($post['slug'])) ?>" target="_blank" class="btn btn-ar-secondary text-info" title="Preview Frontend">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-ar-secondary text-danger" title="Delete Article" onclick="confirmDelete(<?= $post['id'] ?>, '<?= htmlspecialchars(addslashes($post['title'])) ?>')">
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
                        Showing <?= $offset + 1 ?> to <?= min($offset + $per_page, $total_filtered) ?> of <?= number_format($total_filtered) ?> articles
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <!-- Prev -->
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

                            <!-- Next -->
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
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/blogs/action.php') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deletePostId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-1 text-muted">Are you sure you want to permanently delete this article?</p>
                        <p class="fw-bold text-white fs-6" id="deletePostTitle"></p>
                        <p class="small text-danger mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i> This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete Permanently</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, title) {
            document.getElementById('deletePostId').value = id;
            document.getElementById('deletePostTitle').textContent = '"' + title + '"';
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
