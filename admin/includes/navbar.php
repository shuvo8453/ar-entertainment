<?php

/**
 * AR Entertainment - Admin Top Navbar Partial
 */
$page_title = $page_title ?? 'Dashboard';
$current_user = current_user();
?>
<header class="admin-topbar">
    <div class="d-flex align-items-center gap-3">
        <!-- Mobile Sidebar Toggle -->
        <button class="btn btn-ar-secondary d-lg-none py-1 px-2" id="sidebarToggle" type="button">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h5 class="mb-0 fw-bold text-white"><?= htmlspecialchars($page_title) ?></h5>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Domain indicator badge -->
        <span class="badge bg-dark border border-secondary text-muted d-none d-md-inline-block px-3 py-2">
            <i class="fa-solid fa-globe text-success me-1"></i> <?= SITE_DOMAIN ?>
        </span>

        <!-- User Dropdown -->
        <div class="dropdown">
            <button class="btn btn-ar-secondary dropdown-toggle d-flex align-items-center gap-2 py-1 px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 12px;">
                    <?= strtoupper(substr($current_user['name'] ?? 'A', 0, 1)) ?>
                </div>
                <span class="small fw-semibold"><?= htmlspecialchars($current_user['name'] ?? 'Admin') ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" style="background-color: var(--ar-sidebar-bg); border-color: var(--ar-border-color);">
                <li>
                    <h6 class="dropdown-header text-muted"><?= htmlspecialchars($current_user['email'] ?? '') ?> (<?= strtoupper($current_user['role'] ?? 'admin') ?>)</h6>
                </li>
                <li>
                    <hr class="dropdown-divider" style="border-color: var(--ar-border-color);">
                </li>
                <li><a class="dropdown-item" href="<?= site_url('admin/settings') ?>"><i class="fa-solid fa-sliders me-2"></i> Settings</a></li>
                <li><a class="dropdown-item" href="<?= site_url('') ?>" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square me-2"></i> View Site</a></li>
                <li>
                    <hr class="dropdown-divider" style="border-color: var(--ar-border-color);">
                </li>
                <li><a class="dropdown-item text-danger" href="<?= site_url('admin/logout') ?>"><i class="fa-solid fa-power-off me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>