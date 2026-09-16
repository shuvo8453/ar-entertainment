<?php

/**
 * AR Entertainment - Blog Categories Management
 * 
 * Create, Edit, List, and Delete Blog Categories with Live Article Counts.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Blog Categories';
$current_page = 'blogs_categories';

// Handle Add / Edit / Delete Category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'Security token expired. Please try again.');
        redirect('admin/blogs/categories.php');
    }

    $action = trim($_POST['action'] ?? '');

    if ($action === 'create') {
        $name        = trim($_POST['name'] ?? '');
        $slug        = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $sort_order  = (int) ($_POST['sort_order'] ?? 0);

        if ($name === '') {
            set_flash('error', 'Category name is required.');
        } else {
            $slug = $slug !== '' ? slugify($slug) : slugify($name);

            try {
                // Check if slug exists
                $check = db()->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND type = 'blog'");
                $check->execute([$slug]);
                if ($check->fetchColumn() > 0) {
                    $slug = $slug . '-' . time();
                }

                $stmt = db()->prepare("
                    INSERT INTO categories (name, slug, type, description, sort_order, created_at)
                    VALUES (?, ?, 'blog', ?, ?, NOW())
                ");
                $stmt->execute([$name, $slug, $description, $sort_order]);

                set_flash('success', "Category '<strong>" . htmlspecialchars($name) . "</strong>' created successfully!");
            } catch (PDOException $e) {
                set_flash('error', 'Failed to create category: ' . $e->getMessage());
            }
        }
        redirect('admin/blogs/categories.php');
    }

    if ($action === 'update') {
        $id          = (int) ($_POST['id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $slug        = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $sort_order  = (int) ($_POST['sort_order'] ?? 0);

        if ($id <= 0 || $name === '') {
            set_flash('error', 'Category name is required.');
        } else {
            $slug = $slug !== '' ? slugify($slug) : slugify($name);

            try {
                // Check if slug exists on another category
                $check = db()->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND id != ? AND type = 'blog'");
                $check->execute([$slug, $id]);
                if ($check->fetchColumn() > 0) {
                    $slug = $slug . '-' . time();
                }

                $stmt = db()->prepare("
                    UPDATE categories SET
                        name = ?,
                        slug = ?,
                        description = ?,
                        sort_order = ?
                    WHERE id = ? AND type = 'blog'
                ");
                $stmt->execute([$name, $slug, $description, $sort_order, $id]);

                set_flash('success', "Category '<strong>" . htmlspecialchars($name) . "</strong>' updated successfully!");
            } catch (PDOException $e) {
                set_flash('error', 'Failed to update category: ' . $e->getMessage());
            }
        }
        redirect('admin/blogs/categories.php');
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            try {
                // Remove category link from articles
                $unlink = db()->prepare("UPDATE blogs SET category_id = NULL WHERE category_id = ?");
                $unlink->execute([$id]);

                // Delete category
                $del = db()->prepare("DELETE FROM categories WHERE id = ? AND type = 'blog'");
                $del->execute([$id]);

                set_flash('success', 'Category deleted successfully.');
            } catch (PDOException $e) {
                set_flash('error', 'Failed to delete category: ' . $e->getMessage());
            }
        }
        redirect('admin/blogs/categories.php');
    }
}

// Fetch all blog categories with article counts
try {
    $sql = "
        SELECT 
            c.*,
            COUNT(b.id) AS articles_count
        FROM categories c
        LEFT JOIN blogs b ON c.id = b.category_id
        WHERE c.type = 'blog'
        GROUP BY c.id
        ORDER BY c.sort_order ASC, c.name ASC
    ";
    $categories = db()->query($sql)->fetchAll();
} catch (PDOException $e) {
    $categories = [];
    $error_message = $e->getMessage();
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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/blogs/index.php') ?>" class="text-muted text-decoration-none">Blog Articles</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Categories</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Blog Categories</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= site_url('admin/blogs/index.php') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Articles
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <div class="row g-4">
            <!-- Add New Category Form (Left) -->
            <div class="col-12 col-lg-4">
                <div class="card-ar">
                    <h5 class="fw-bold text-white border-bottom pb-3 mb-3" style="border-color: var(--ar-border-color) !important;">
                        <i class="fa-solid fa-plus text-danger me-2"></i> Add New Category
                    </h5>

                    <form method="POST" action="<?= site_url('admin/blogs/categories.php') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="create">

                        <div class="mb-3">
                            <label for="catName" class="form-label text-white fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="catName" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Video Production" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="catSlug" class="form-label text-white fw-semibold">URL Slug <span class="text-muted small">(Optional)</span></label>
                            <input type="text" name="slug" id="catSlug" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="video-production">
                        </div>

                        <div class="mb-3">
                            <label for="catDesc" class="form-label text-white fw-semibold">Description</label>
                            <textarea name="description" id="catDesc" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Brief description of this blog topic..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="catSort" class="form-label text-white fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" id="catSort" class="form-control bg-dark border-secondary text-white" value="0">
                            <div class="form-text text-muted small">Lower numbers appear first.</div>
                        </div>

                        <div class="d-grid pt-2">
                            <button type="submit" class="btn btn-ar-primary">
                                <i class="fa-solid fa-folder-plus me-1"></i> Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categories List Table (Right) -->
            <div class="col-12 col-lg-8">
                <div class="card-ar p-0 overflow-hidden">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--ar-border-color) !important;">
                        <h6 class="fw-bold mb-0 text-white">
                            Existing Categories <span class="badge bg-secondary ms-2"><?= count($categories) ?> Total</span>
                        </h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark-custom align-middle">
                            <thead>
                                <tr>
                                    <th>Name &amp; Slug</th>
                                    <th>Description</th>
                                    <th class="text-center">Order</th>
                                    <th class="text-center">Articles</th>
                                    <th class="text-end" style="width: 110px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No categories created yet. Use the form on the left to add one.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-white mb-1">
                                                    <?= htmlspecialchars($cat['name']) ?>
                                                </div>
                                                <div class="small text-muted font-monospace" style="font-size: 11px;">
                                                    /category/<?= htmlspecialchars($cat['slug']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small text-muted text-truncate" style="max-width: 200px;">
                                                    <?= htmlspecialchars($cat['description'] ?: '—') ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-dark border border-secondary text-muted">
                                                    <?= (int) $cat['sort_order'] ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= site_url('admin/blogs/index.php?category=' . $cat['id']) ?>" class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 text-decoration-none">
                                                    <?= number_format($cat['articles_count']) ?> articles
                                                </a>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-ar-secondary" title="Edit Category" onclick='editCategory(<?= json_encode($cat) ?>)'>
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-ar-secondary text-danger" title="Delete Category" onclick="confirmDeleteCat(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['name'])) ?>')">
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
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/blogs/categories.php') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="editCatId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-pen-to-square text-danger me-2"></i> Edit Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label for="editCatName" class="form-label text-white fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editCatName" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCatSlug" class="form-label text-white fw-semibold">URL Slug</label>
                            <input type="text" name="slug" id="editCatSlug" class="form-control bg-dark border-secondary text-white font-monospace">
                        </div>
                        <div class="mb-3">
                            <label for="editCatDesc" class="form-label text-white fw-semibold">Description</label>
                            <textarea name="description" id="editCatDesc" rows="3" class="form-control bg-dark border-secondary text-white"></textarea>
                        </div>
                        <div class="mb-0">
                            <label for="editCatSort" class="form-label text-white fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" id="editCatSort" class="form-control bg-dark border-secondary text-white">
                        </div>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-ar-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCatModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/blogs/categories.php') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteCatId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-1 text-muted">Are you sure you want to delete this category?</p>
                        <p class="fw-bold text-white fs-6" id="deleteCatTitle"></p>
                        <p class="small text-warning mb-0"><i class="fa-solid fa-info-circle me-1"></i> Articles in this category will remain safe but will be marked as Uncategorized.</p>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editCategory(cat) {
            document.getElementById('editCatId').value = cat.id;
            document.getElementById('editCatName').value = cat.name;
            document.getElementById('editCatSlug').value = cat.slug;
            document.getElementById('editCatDesc').value = cat.description || '';
            document.getElementById('editCatSort').value = cat.sort_order || 0;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function confirmDeleteCat(id, name) {
            document.getElementById('deleteCatId').value = id;
            document.getElementById('deleteCatTitle').textContent = '"' + name + '"';
            new bootstrap.Modal(document.getElementById('deleteCatModal')).show();
        }

        // Auto slug on create form
        document.getElementById('catName').addEventListener('input', function() {
            const slugInput = document.getElementById('catSlug');
            if (!slugInput.dataset.touched) {
                slugInput.value = this.value.toLowerCase().trim().replace(/[\s\W-]+/g, '-').replace(/^-+|-+$/g, '');
            }
        });
        document.getElementById('catSlug').addEventListener('input', function() {
            this.dataset.touched = 'true';
        });
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
