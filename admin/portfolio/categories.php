<?php

/**
 * AR Entertainment - Portfolio Categories Management
 * 
 * Create, Edit, List, and Delete Video Categories with Live Project Counts.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Video Categories';
$current_page = 'portfolio_categories';

// Handle Add / Edit / Delete Category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'Security token expired. Please try again.');
        redirect('admin/portfolio/categories');
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
                $check = db()->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND type = 'portfolio'");
                $check->execute([$slug]);
                if ($check->fetchColumn() > 0) {
                    $slug = $slug . '-' . time();
                }

                $stmt = db()->prepare("
                    INSERT INTO categories (name, slug, type, description, sort_order, created_at)
                    VALUES (?, ?, 'portfolio', ?, ?, NOW())
                ");
                $stmt->execute([$name, $slug, $description, $sort_order]);

                set_flash('success', "Category '<strong>" . htmlspecialchars($name) . "</strong>' created successfully!");
            } catch (PDOException $e) {
                set_flash('error', 'Failed to create category: ' . $e->getMessage());
            }
        }
        redirect('admin/portfolio/categories');
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
                $check = db()->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND id != ? AND type = 'portfolio'");
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
                    WHERE id = ? AND type = 'portfolio'
                ");
                $stmt->execute([$name, $slug, $description, $sort_order, $id]);

                // Also update cached category_name on portfolio table
                $up_port = db()->prepare("UPDATE portfolio SET category_name = ? WHERE category_id = ?");
                $up_port->execute([$name, $id]);

                set_flash('success', "Category '<strong>" . htmlspecialchars($name) . "</strong>' updated successfully!");
            } catch (PDOException $e) {
                set_flash('error', 'Failed to update category: ' . $e->getMessage());
            }
        }
        redirect('admin/portfolio/categories');
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            try {
                // Remove category link from portfolio items
                $unlink = db()->prepare("UPDATE portfolio SET category_id = NULL WHERE category_id = ?");
                $unlink->execute([$id]);

                // Delete category
                $del = db()->prepare("DELETE FROM categories WHERE id = ? AND type = 'portfolio'");
                $del->execute([$id]);

                set_flash('success', 'Category deleted successfully.');
            } catch (PDOException $e) {
                set_flash('error', 'Failed to delete category: ' . $e->getMessage());
            }
        }
        redirect('admin/portfolio/categories');
    }
}

// Fetch all portfolio categories with project counts
try {
    $sql = "
        SELECT 
            c.*,
            COUNT(p.id) as project_count
        FROM categories c
        LEFT JOIN portfolio p ON c.id = p.category_id
        WHERE c.type = 'portfolio'
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
        <!-- Breadcrumb & Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/portfolio') ?>" class="text-muted text-decoration-none">Portfolio</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Categories</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Video Project Categories</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/portfolio') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Portfolio
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <div class="row g-4">
            <!-- Left: Add New Category Form -->
            <div class="col-12 col-lg-4">
                <div class="card-ar">
                    <h5 class="fw-bold mb-3 text-white border-bottom pb-2" style="border-color: var(--ar-border-color) !important;">
                        <i class="fa-solid fa-folder-plus text-danger me-2"></i> Add Video Category
                    </h5>

                    <form method="POST" action="<?= site_url('admin/portfolio/categories') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="create">

                        <div class="mb-3">
                            <label for="catName" class="form-label text-white fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="catName" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 3D Animation &amp; VFX" required>
                        </div>

                        <div class="mb-3">
                            <label for="catSlug" class="form-label text-muted small fw-semibold">URL Slug</label>
                            <input type="text" name="slug" id="catSlug" class="form-control form-control-sm bg-dark border-secondary text-white font-monospace" placeholder="3d-animation-vfx">
                            <div class="form-text text-muted small">Auto-generated from name if left empty.</div>
                        </div>

                        <div class="mb-3">
                            <label for="catDescription" class="form-label text-white fw-semibold">Description</label>
                            <textarea name="description" id="catDescription" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Short description of this production genre..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="catSortOrder" class="form-label text-white fw-semibold">Sort Order Index</label>
                            <input type="number" name="sort_order" id="catSortOrder" class="form-control bg-dark border-secondary text-white" value="0">
                        </div>

                        <button type="submit" class="btn btn-ar-primary w-100">
                            <i class="fa-solid fa-plus me-1"></i> Create Category
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Categories List Table -->
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
                                    <th>Category Name</th>
                                    <th>Slug</th>
                                    <th class="text-center">Projects</th>
                                    <th class="text-center">Order</th>
                                    <th class="text-end" style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No video categories found. Create one using the form.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-white"><?= htmlspecialchars($cat['name']) ?></div>
                                                <?php if (!empty($cat['description'])): ?>
                                                    <div class="small text-muted text-truncate" style="max-width: 250px;">
                                                        <?= htmlspecialchars($cat['description']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="font-monospace small text-muted">
                                                <?= htmlspecialchars($cat['slug']) ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= site_url('admin/portfolio?category_id=' . $cat['id']) ?>" class="badge bg-dark border border-secondary text-info text-decoration-none">
                                                    <?= (int) $cat['project_count'] ?> Works
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-dark border border-secondary text-muted">
                                                    <?= (int) $cat['sort_order'] ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-ar-secondary" title="Edit Category" onclick="openEditModal(<?= htmlspecialchars(json_encode($cat)) ?>)">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-ar-secondary text-danger" title="Delete Category" onclick="confirmDelete(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['name'])) ?>', <?= (int) $cat['project_count'] ?>)">
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
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/portfolio/categories') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="editCatId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-pen-to-square text-danger me-2"></i> Edit Video Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editCatName" class="form-label text-white fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editCatName" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCatSlug" class="form-label text-muted small fw-semibold">URL Slug</label>
                            <input type="text" name="slug" id="editCatSlug" class="form-control form-control-sm bg-dark border-secondary text-white font-monospace">
                        </div>
                        <div class="mb-3">
                            <label for="editCatDesc" class="form-label text-white fw-semibold">Description</label>
                            <textarea name="description" id="editCatDesc" rows="3" class="form-control bg-dark border-secondary text-white"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editCatSort" class="form-label text-white fw-semibold">Sort Order Index</label>
                            <input type="number" name="sort_order" id="editCatSort" class="form-control bg-dark border-secondary text-white" value="0">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
                <form method="POST" action="<?= site_url('admin/portfolio/categories') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteCatId" value="">

                    <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                        <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-1 text-muted">Are you sure you want to delete this video category?</p>
                        <p class="fw-bold text-white fs-5" id="deleteCatName"></p>
                        <div id="deleteWarning" class="alert alert-warning small mb-0 py-2 d-none">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> <span id="deleteCatCount"></span> video projects currently use this category. Deleting will unlink them (set category to Unassigned).
                        </div>
                    </div>
                    <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(cat) {
            document.getElementById('editCatId').value = cat.id;
            document.getElementById('editCatName').value = cat.name;
            document.getElementById('editCatSlug').value = cat.slug;
            document.getElementById('editCatDesc').value = cat.description || '';
            document.getElementById('editCatSort').value = cat.sort_order || 0;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }

        function confirmDelete(id, name, count) {
            document.getElementById('deleteCatId').value = id;
            document.getElementById('deleteCatName').textContent = '"' + name + '"';
            const warningEl = document.getElementById('deleteWarning');
            if (count > 0) {
                document.getElementById('deleteCatCount').textContent = count;
                warningEl.classList.remove('d-none');
            } else {
                warningEl.classList.add('d-none');
            }
            new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
        }

        // Live slug auto-fill for new category
        const catName = document.getElementById('catName');
        const catSlug = document.getElementById('catSlug');
        catName.addEventListener('input', function() {
            catSlug.value = this.value.toLowerCase().replace(/[\s\W-]+/g, '-').replace(/^-+|-+$/g, '');
        });
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
