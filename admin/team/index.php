<?php

/**
 * AR Entertainment - Team Members Management
 * 
 * Listing, Search, Status Filter, Quick Status Toggle, and Staff Profiles CRUD.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Team Members';
$current_page = 'team';

// -----------------------------------------------------------------------------
// Filters & Search
// -----------------------------------------------------------------------------
$search   = trim($_GET['q'] ?? '');
$status   = trim($_GET['status'] ?? '');
$page     = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 15;
$offset   = ($page - 1) * $per_page;

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(name LIKE ? OR role_title LIKE ? OR bio LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
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
    $total_members  = (int) db()->query("SELECT COUNT(*) FROM team_members")->fetchColumn();
    $active_members = (int) db()->query("SELECT COUNT(*) FROM team_members WHERE status = 'active'")->fetchColumn();
    $inactive_count = (int) db()->query("SELECT COUNT(*) FROM team_members WHERE status = 'inactive'")->fetchColumn();

    // Total filtered records for pagination
    $count_stmt = db()->prepare("SELECT COUNT(*) FROM team_members {$where_sql}");
    $count_stmt->execute($params);
    $total_filtered = (int) $count_stmt->fetchColumn();
    $total_pages = max(1, (int) ceil($total_filtered / $per_page));

    // Fetch paginated team members
    $sql = "
        SELECT *
        FROM team_members
        {$where_sql}
        ORDER BY sort_order ASC, id ASC
        LIMIT {$per_page} OFFSET {$offset}
    ";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $team_members = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    $team_members = [];
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
                        <li class="breadcrumb-item active text-white" aria-current="page">Team Members</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Team Members &amp; Crew</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/team/create') ?>" class="btn btn-ar-primary">
                    <i class="fa-solid fa-plus me-1"></i> Add New Member
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Quick Stats Overview -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= number_format($total_members) ?></div>
                        <div class="stat-label">Total Staff</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-success"><?= number_format($active_members) ?></div>
                        <div class="stat-label">Active Profiles</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-secondary"><?= number_format($inactive_count) ?></div>
                        <div class="stat-label">Hidden / Inactive</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="card-ar mb-4">
            <form method="GET" action="<?= site_url('admin/team') ?>" class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-lg-7">
                    <label class="form-label text-muted small fw-semibold">Search Members</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="q" class="form-control bg-dark border-secondary text-white" placeholder="Search by name, role, email, phone, bio..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-3">
                    <label class="form-label text-muted small fw-semibold">Status</label>
                    <select name="status" class="form-select bg-dark border-secondary text-white">
                        <option value="">All Statuses</option>
                        <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active Only</option>
                        <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive Only</option>
                    </select>
                </div>

                <div class="col-6 col-md-3 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-ar-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <?php if ($search !== '' || $status !== ''): ?>
                        <a href="<?= site_url('admin/team') ?>" class="btn btn-ar-secondary" title="Clear Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Team Members Table Card -->
        <div class="card-ar p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Photo</th>
                            <th>Name &amp; Designation</th>
                            <th>Contact Info</th>
                            <th>Social Profiles</th>
                            <th class="text-center" style="width: 100px;">Sort Order</th>
                            <th class="text-center" style="width: 110px;">Status</th>
                            <th class="text-end" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($team_members)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="fa-solid fa-user-group fa-3x mb-3 text-secondary" style="opacity: 0.5;"></i>
                                        <h5 class="text-white fw-bold">No Team Members Found</h5>
                                        <p class="text-muted small mb-3">
                                            <?= ($search !== '' || $status !== '') ? 'No results matched your search criteria.' : 'Start introducing your directors, producers, and creative crew.' ?>
                                        </p>
                                        <?php if ($search !== '' || $status !== ''): ?>
                                            <a href="<?= site_url('admin/team') ?>" class="btn btn-ar-secondary btn-sm">Clear Filter</a>
                                        <?php else: ?>
                                            <a href="<?= site_url('admin/team/create') ?>" class="btn btn-ar-primary btn-sm">
                                                <i class="fa-solid fa-plus me-1"></i> Add First Team Member
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($team_members as $m): ?>
                                <?php
                                $social_links = [];
                                if (!empty($m['social_links_json'])) {
                                    $social_links = json_decode($m['social_links_json'], true) ?: [];
                                }
                                ?>
                                <tr>
                                    <!-- Photo Avatar -->
                                    <td>
                                        <?php if (!empty($m['photo'])): ?>
                                            <img src="<?= htmlspecialchars(upload_url($m['photo'])) ?>" alt="<?= htmlspecialchars($m['name']) ?>" class="rounded-circle object-fit-cover shadow-sm border border-secondary" style="width: 48px; height: 48px;">
                                        <?php else: ?>
                                            <?php
                                            $initials = '';
                                            $words = explode(' ', trim($m['name']));
                                            foreach (array_slice($words, 0, 2) as $w) {
                                                $initials .= mb_substr($w, 0, 1);
                                            }
                                            $initials = strtoupper($initials) ?: 'AR';
                                            ?>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold border border-secondary" style="width: 48px; height: 48px; background: linear-gradient(135deg, #2a2d42, #181926); color: var(--ar-primary); font-size: 15px;">
                                                <?= htmlspecialchars($initials) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Name & Role -->
                                    <td>
                                        <div class="fw-bold text-white fs-6 mb-1">
                                            <a href="<?= site_url('admin/team/edit?id=' . $m['id']) ?>" class="text-white text-decoration-none hover-primary">
                                                <?= htmlspecialchars($m['name']) ?>
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-dark border border-secondary text-info small fw-normal">
                                                <?= htmlspecialchars($m['role_title']) ?>
                                            </span>
                                            <span class="text-muted small font-monospace">/<?= htmlspecialchars($m['slug']) ?></span>
                                        </div>
                                    </td>

                                    <!-- Contact Info -->
                                    <td>
                                        <?php if (!empty($m['email'])): ?>
                                            <div class="small text-muted mb-1">
                                                <i class="fa-solid fa-envelope me-1 text-secondary"></i>
                                                <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="text-muted text-decoration-none hover-white">
                                                    <?= htmlspecialchars($m['email']) ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($m['phone'])): ?>
                                            <div class="small text-muted">
                                                <i class="fa-solid fa-phone me-1 text-secondary"></i>
                                                <a href="tel:<?= htmlspecialchars($m['phone']) ?>" class="text-muted text-decoration-none hover-white">
                                                    <?= htmlspecialchars($m['phone']) ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (empty($m['email']) && empty($m['phone'])): ?>
                                            <span class="text-muted small">&mdash;</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Social Profiles -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <?php if (!empty($social_links['linkedin'])): ?>
                                                <a href="<?= htmlspecialchars($social_links['linkedin']) ?>" target="_blank" class="btn btn-sm btn-dark p-1 rounded-circle border border-secondary text-primary" title="LinkedIn" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fa-brands fa-linkedin-in small"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($social_links['imdb'])): ?>
                                                <a href="<?= htmlspecialchars($social_links['imdb']) ?>" target="_blank" class="btn btn-sm btn-dark p-1 rounded-circle border border-secondary text-warning" title="IMDb" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fa-brands fa-imdb small"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($social_links['facebook'])): ?>
                                                <a href="<?= htmlspecialchars($social_links['facebook']) ?>" target="_blank" class="btn btn-sm btn-dark p-1 rounded-circle border border-secondary text-info" title="Facebook" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fa-brands fa-facebook-f small"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($social_links['instagram'])): ?>
                                                <a href="<?= htmlspecialchars($social_links['instagram']) ?>" target="_blank" class="btn btn-sm btn-dark p-1 rounded-circle border border-secondary text-danger" title="Instagram" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fa-brands fa-instagram small"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($social_links['twitter'])): ?>
                                                <a href="<?= htmlspecialchars($social_links['twitter']) ?>" target="_blank" class="btn btn-sm btn-dark p-1 rounded-circle border border-secondary text-light" title="X / Twitter" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fa-brands fa-x-twitter small"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($social_links['website'])): ?>
                                                <a href="<?= htmlspecialchars($social_links['website']) ?>" target="_blank" class="btn btn-sm btn-dark p-1 rounded-circle border border-secondary text-success" title="Website" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid fa-globe small"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (empty(array_filter($social_links))): ?>
                                                <span class="text-muted small">&mdash;</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Sort Order -->
                                    <td class="text-center">
                                        <span class="badge bg-dark border border-secondary text-muted font-monospace">
                                            #<?= (int) $m['sort_order'] ?>
                                        </span>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td class="text-center">
                                        <form method="POST" action="<?= site_url('admin/team/action') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                            <?php if ($m['status'] === 'active'): ?>
                                                <button type="submit" class="btn btn-sm badge bg-success text-white border-0 px-2 py-1" title="Click to hide member">
                                                    <i class="fa-solid fa-check me-1"></i> Active
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm badge bg-secondary text-white border-0 px-2 py-1" title="Click to activate member">
                                                    <i class="fa-solid fa-eye-slash me-1"></i> Inactive
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="<?= site_url('admin/team/edit?id=' . $m['id']) ?>" class="btn btn-sm btn-ar-secondary" title="Edit Profile">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $m['id'] ?>" title="Delete Member">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal<?= $m['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content card-ar border-danger text-start">
                                                    <div class="modal-header border-secondary">
                                                        <h5 class="modal-title text-danger fw-bold">
                                                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Deletion
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-white">
                                                        <p class="mb-2">Are you sure you want to permanently delete team member <strong><?= htmlspecialchars($m['name']) ?></strong> (<em><?= htmlspecialchars($m['role_title']) ?></em>)?</p>
                                                        <p class="text-muted small mb-0">This will remove their profile and headshot from the website. This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer border-secondary">
                                                        <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form method="POST" action="<?= site_url('admin/team/action') ?>">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fa-solid fa-trash me-1"></i> Delete Member
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <?php if ($total_pages > 1): ?>
                <div class="p-3 border-top border-secondary d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small">
                        Showing <?= min($offset + 1, $total_filtered) ?> to <?= min($offset + $per_page, $total_filtered) ?> of <?= $total_filtered ?> members
                    </div>
                    <nav aria-label="Team Pagination">
                        <ul class="pagination pagination-sm mb-0">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link bg-dark border-secondary text-white" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                                    <a class="page-link <?= ($p === $page) ? 'bg-danger border-danger text-white' : 'bg-dark border-secondary text-white' ?>" href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>">
                                        <?= $p ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link bg-dark border-secondary text-white" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
