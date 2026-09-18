<?php

/**
 * AR Entertainment - Admin Sidebar Partial
 */
$current_page = $current_page ?? 'dashboard';

// Fetch unread inquiries count for badge
$unread_leads_count = 0;
try {
    $unread_leads_count = (int) db()->query("SELECT COUNT(*) FROM inquiries WHERE is_read = 0")->fetchColumn();
} catch (Exception $e) {
}
?>
<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand Logo -->
    <a href="<?= site_url('admin') ?>" class="sidebar-brand">
        <div class="logo-icon">
            <i class="fa-solid fa-clapperboard"></i>
        </div>
        <div>
            <div class="brand-title">AR <span>ENT</span></div>
            <div class="brand-sub">Admin Panel</div>
        </div>
    </a>

    <!-- Navigation Links -->
    <div class="sidebar-nav">
        <div class="nav-section-title">Main</div>

        <a href="<?= site_url('admin') ?>" class="sidebar-link <?= ($current_page === 'dashboard') ? 'active' : '' ?>">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Dashboard</span>
        </a>

        <div class="nav-section-title">Content & Media</div>

        <a href="<?= site_url('admin/blogs') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'blogs')) ? 'active' : '' ?>">
            <i class="fa-solid fa-newspaper"></i>
            <span>Blogs & Articles</span>
        </a>

        <a href="<?= site_url('admin/services') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'services')) ? 'active' : '' ?>">
            <i class="fa-solid fa-film"></i>
            <span>Services</span>
        </a>

        <a href="<?= site_url('admin/service-areas') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'service-areas')) ? 'active' : '' ?>">
            <i class="fa-solid fa-location-dot"></i>
            <span>Service Areas</span>
        </a>

        <a href="<?= site_url('admin/portfolio') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'portfolio')) ? 'active' : '' ?>">
            <i class="fa-solid fa-video"></i>
            <span>Portfolio / Works</span>
        </a>

        <div class="nav-section-title">Company & Social</div>

        <a href="<?= site_url('admin/team') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'team')) ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i>
            <span>Team Members</span>
        </a>

        <a href="<?= site_url('admin/brands') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'brands')) ? 'active' : '' ?>">
            <i class="fa-solid fa-award"></i>
            <span>Brands & Clients</span>
        </a>

        <a href="<?= site_url('admin/reviews') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'reviews')) ? 'active' : '' ?>">
            <i class="fa-solid fa-star"></i>
            <span>Client Reviews</span>
        </a>

        <div class="nav-section-title">Leads & Inquiries</div>

        <a href="<?= site_url('admin/inquiries') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'inquiries')) ? 'active' : '' ?>">
            <i class="fa-solid fa-inbox"></i>
            <span class="flex-grow-1">Inquiries / Leads</span>
            <?php if ($unread_leads_count > 0): ?>
                <span class="badge bg-danger rounded-pill"><?= $unread_leads_count ?></span>
            <?php endif; ?>
        </a>

        <div class="nav-section-title">Administration</div>

        <a href="<?= site_url('admin/settings') ?>" class="sidebar-link <?= (str_starts_with($current_page, 'settings')) ? 'active' : '' ?>">
            <i class="fa-solid fa-sliders"></i>
            <span>Site Settings</span>
        </a>

        <hr style="border-color: var(--ar-border-color); margin: 15px 0;">

        <a href="<?= site_url('') ?>" target="_blank" class="sidebar-link text-info">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>View Live Website</span>
        </a>

        <a href="<?= site_url('admin/logout') ?>" class="sidebar-link text-danger">
            <i class="fa-solid fa-power-off"></i>
            <span>Sign Out</span>
        </a>
    </div>
</aside>