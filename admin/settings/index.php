<?php
/**
 * AR Entertainment - Global Site Settings & Brand Configuration
 * Phase 4.8: Management Module (admin/settings/index.php)
 * 
 * Manages General Brand Identity, Logo Uploader with AVIF/WebP Auto-Conversion,
 * Contact, Social Media, Analytics & Custom Code settings.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title   = 'Site Settings';
$current_page = 'settings';

$active_tab = trim($_GET['tab'] ?? 'general');
$valid_tabs = ['general', 'contact', 'social', 'analytics', 'custom_code'];
if (!in_array($active_tab, $valid_tabs, true)) {
    $active_tab = 'general';
}

$errors = [];

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security verification failed (CSRF token expired). Please try again.';
    } else {
        $tab = trim($_POST['_tab'] ?? 'general');

        if ($tab === 'general') {
            $fields = [
                'site_name'        => ['group' => 'general', 'val' => trim($_POST['site_name'] ?? '')],
                'site_tagline'     => ['group' => 'general', 'val' => trim($_POST['site_tagline'] ?? '')],
                'site_description' => ['group' => 'general', 'val' => trim($_POST['site_description'] ?? '')],
                'site_keywords'    => ['group' => 'general', 'val' => trim($_POST['site_keywords'] ?? '')],
                'footer_copyright' => ['group' => 'general', 'val' => trim($_POST['footer_copyright'] ?? '')],
            ];
            foreach ($fields as $key => $data) {
                update_setting($key, $data['val'], $data['group']);
            }

            // 1. Primary Website Logo Upload (Auto-converts to AVIF / WebP via helper)
            if (!empty($_FILES['site_logo']['tmp_name'])) {
                $upload = upload_image(
                    $_FILES['site_logo'],
                    'settings',
                    ['image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/svg+xml'],
                    5242880,
                    1200,
                    90
                );
                if ($upload['success']) {
                    $old_logo = get_setting('site_logo');
                    if (!empty($old_logo) && !str_starts_with($old_logo, 'images/')) {
                        $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $old_logo);
                        if (file_exists($old_file)) {
                            @unlink($old_file);
                        }
                    }
                    update_setting('site_logo', $upload['path'], 'general');
                } else {
                    $errors[] = 'Primary Logo error: ' . $upload['error'];
                }
            } elseif (!empty($_POST['remove_site_logo']) && $_POST['remove_site_logo'] === '1') {
                $old_logo = get_setting('site_logo');
                if (!empty($old_logo) && !str_starts_with($old_logo, 'images/')) {
                    $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $old_logo);
                    if (file_exists($old_file)) {
                        @unlink($old_file);
                    }
                }
                update_setting('site_logo', '', 'general');
            }

            // 2. Footer / Dark Background Logo Upload
            if (!empty($_FILES['site_logo_dark']['tmp_name'])) {
                $upload = upload_image(
                    $_FILES['site_logo_dark'],
                    'settings',
                    ['image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/svg+xml'],
                    5242880,
                    1200,
                    90
                );
                if ($upload['success']) {
                    $old_logo = get_setting('site_logo_dark');
                    if (!empty($old_logo) && !str_starts_with($old_logo, 'images/')) {
                        $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $old_logo);
                        if (file_exists($old_file)) {
                            @unlink($old_file);
                        }
                    }
                    update_setting('site_logo_dark', $upload['path'], 'general');
                } else {
                    $errors[] = 'Footer Logo error: ' . $upload['error'];
                }
            } elseif (!empty($_POST['remove_site_logo_dark']) && $_POST['remove_site_logo_dark'] === '1') {
                $old_logo = get_setting('site_logo_dark');
                if (!empty($old_logo) && !str_starts_with($old_logo, 'images/')) {
                    $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $old_logo);
                    if (file_exists($old_file)) {
                        @unlink($old_file);
                    }
                }
                update_setting('site_logo_dark', '', 'general');
            }

            // 3. Favicon Upload
            if (!empty($_FILES['site_favicon']['tmp_name'])) {
                $upload = upload_image(
                    $_FILES['site_favicon'],
                    'settings',
                    ['image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'],
                    2097152,
                    512,
                    90
                );
                if ($upload['success']) {
                    $old_fav = get_setting('site_favicon');
                    if (!empty($old_fav) && !str_starts_with($old_fav, 'images/')) {
                        $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $old_fav);
                        if (file_exists($old_file)) {
                            @unlink($old_file);
                        }
                    }
                    update_setting('site_favicon', $upload['path'], 'general');
                } else {
                    $errors[] = 'Favicon upload error: ' . $upload['error'];
                }
            } elseif (!empty($_POST['remove_site_favicon']) && $_POST['remove_site_favicon'] === '1') {
                $old_fav = get_setting('site_favicon');
                if (!empty($old_fav) && !str_starts_with($old_fav, 'images/')) {
                    $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $old_fav);
                    if (file_exists($old_file)) {
                        @unlink($old_file);
                    }
                }
                update_setting('site_favicon', '', 'general');
            }

            if (empty($errors)) {
                set_flash('success', 'General site identity and logo settings saved successfully.');
                redirect('admin/settings/?tab=general');
            }
        }

        if ($tab === 'contact') {
            $fields = [
                'company_email'        => ['group' => 'contact', 'val' => trim($_POST['company_email'] ?? '')],
                'company_phone_1'      => ['group' => 'contact', 'val' => trim($_POST['company_phone_1'] ?? '')],
                'company_phone_2'      => ['group' => 'contact', 'val' => trim($_POST['company_phone_2'] ?? '')],
                'company_whatsapp'     => ['group' => 'contact', 'val' => trim($_POST['company_whatsapp'] ?? '')],
                'company_address'      => ['group' => 'contact', 'val' => trim($_POST['company_address'] ?? '')],
                'company_city'         => ['group' => 'contact', 'val' => trim($_POST['company_city'] ?? '')],
                'company_hours'        => ['group' => 'contact', 'val' => trim($_POST['company_hours'] ?? '')],
                'google_maps_embed'    => ['group' => 'contact', 'val' => trim($_POST['google_maps_embed'] ?? '')],
                'lead_notify_email'    => ['group' => 'contact', 'val' => trim($_POST['lead_notify_email'] ?? '')],
            ];
            foreach ($fields as $key => $data) {
                update_setting($key, $data['val'], $data['group']);
            }
            set_flash('success', 'Contact and studio location settings saved successfully.');
            redirect('admin/settings/?tab=contact');
        }

        if ($tab === 'social') {
            $fields = [
                'social_facebook'  => ['group' => 'social', 'val' => trim($_POST['social_facebook'] ?? '')],
                'social_youtube'   => ['group' => 'social', 'val' => trim($_POST['social_youtube'] ?? '')],
                'social_vimeo'     => ['group' => 'social', 'val' => trim($_POST['social_vimeo'] ?? '')],
                'social_instagram' => ['group' => 'social', 'val' => trim($_POST['social_instagram'] ?? '')],
                'social_linkedin'  => ['group' => 'social', 'val' => trim($_POST['social_linkedin'] ?? '')],
                'social_imdb'      => ['group' => 'social', 'val' => trim($_POST['social_imdb'] ?? '')],
                'social_twitter'   => ['group' => 'social', 'val' => trim($_POST['social_twitter'] ?? '')],
                'social_tiktok'    => ['group' => 'social', 'val' => trim($_POST['social_tiktok'] ?? '')],
            ];
            foreach ($fields as $key => $data) {
                update_setting($key, $data['val'], $data['group']);
            }
            set_flash('success', 'Social media and video streaming profiles saved successfully.');
            redirect('admin/settings/?tab=social');
        }

        if ($tab === 'analytics') {
            $fields = [
                'ga4_measurement_id'     => ['group' => 'analytics', 'val' => trim($_POST['ga4_measurement_id'] ?? '')],
                'meta_pixel_id'          => ['group' => 'analytics', 'val' => trim($_POST['meta_pixel_id'] ?? '')],
                'google_site_verify'     => ['group' => 'analytics', 'val' => trim($_POST['google_site_verify'] ?? '')],
                'recaptcha_site_key'     => ['group' => 'analytics', 'val' => trim($_POST['recaptcha_site_key'] ?? '')],
                'recaptcha_secret_key'   => ['group' => 'analytics', 'val' => trim($_POST['recaptcha_secret_key'] ?? '')],
            ];
            foreach ($fields as $key => $data) {
                update_setting($key, $data['val'], $data['group']);
            }
            set_flash('success', 'Analytics, Pixel and API tracking keys saved successfully.');
            redirect('admin/settings/?tab=analytics');
        }

        if ($tab === 'custom_code') {
            $fields = [
                'custom_header_code' => ['group' => 'custom_code', 'val' => trim($_POST['custom_header_code'] ?? '')],
                'custom_footer_code' => ['group' => 'custom_code', 'val' => trim($_POST['custom_footer_code'] ?? '')],
                'custom_css'         => ['group' => 'custom_code', 'val' => trim($_POST['custom_css'] ?? '')],
            ];
            foreach ($fields as $key => $data) {
                update_setting($key, $data['val'], $data['group']);
            }
            set_flash('success', 'Custom scripts and CSS overrides saved successfully.');
            redirect('admin/settings/?tab=custom_code');
        }
    }
}

// Load current settings
$settings = load_settings();

// Logo Paths
$current_logo      = $settings['site_logo'] ?? '';
$current_logo_dark = $settings['site_logo_dark'] ?? '';
$current_favicon   = $settings['site_favicon'] ?? '';

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
                        <li class="breadcrumb-item active text-white" aria-current="page">Site Settings</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Global Site Settings &amp; Configuration</h3>
            </div>
            <div>
                <a href="<?= site_url('') ?>" target="_blank" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Preview Live Website
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-2"></i>Please fix the following issues:</div>
                <ul class="mb-0 ps-3">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-danger">PHP 8.3</div>
                        <div class="stat-label">Environment (AVIF/GD)</div>
                    </div>
                    <div class="stat-icon icon-red">
                        <i class="fa-brands fa-php"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-success">Active</div>
                        <div class="stat-label">Database Connected</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-database"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-info">HTTPS</div>
                        <div class="stat-label">Security &amp; SSL</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-warning"><?= count($settings) ?></div>
                        <div class="stat-label">Total Parameters</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Tabs Navigation Bar -->
        <div class="card-ar p-3 mb-4">
            <ul class="nav nav-pills gap-1 flex-wrap">
                <li class="nav-item">
                    <a class="nav-link <?= ($active_tab === 'general') ? 'active' : '' ?>" href="<?= site_url('admin/settings/?tab=general') ?>">
                        <i class="fa-solid fa-globe me-2"></i> General Identity &amp; Logos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active_tab === 'contact') ? 'active' : '' ?>" href="<?= site_url('admin/settings/?tab=contact') ?>">
                        <i class="fa-solid fa-location-dot me-2"></i> Contact &amp; Studio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active_tab === 'social') ? 'active' : '' ?>" href="<?= site_url('admin/settings/?tab=social') ?>">
                        <i class="fa-brands fa-youtube me-2"></i> Social &amp; Video Channels
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active_tab === 'analytics') ? 'active' : '' ?>" href="<?= site_url('admin/settings/?tab=analytics') ?>">
                        <i class="fa-solid fa-chart-line me-2"></i> Analytics &amp; Tracking
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active_tab === 'custom_code') ? 'active' : '' ?>" href="<?= site_url('admin/settings/?tab=custom_code') ?>">
                        <i class="fa-solid fa-code me-2"></i> Custom Scripts &amp; CSS
                    </a>
                </li>
            </ul>
        </div>

        <!-- Active Tab Content Area -->
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <!-- TAB 1: General Identity & Logos -->
                <?php if ($active_tab === 'general'): ?>
                    <form method="POST" action="<?= site_url('admin/settings/?tab=general') ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_tab" value="general">
                        <input type="hidden" name="remove_site_logo" id="removeLogoInput" value="0">
                        <input type="hidden" name="remove_site_logo_dark" id="removeLogoDarkInput" value="0">
                        <input type="hidden" name="remove_site_favicon" id="removeFaviconInput" value="0">

                        <!-- Brand Identity Text Card -->
                        <div class="card-ar mb-4">
                            <h5 class="fw-bold text-white mb-3">
                                <i class="fa-solid fa-building text-primary me-2"></i> Brand &amp; Site Identity
                            </h5>

                            <div class="mb-3">
                                <label for="siteName" class="form-label text-white fw-semibold">Website Title / Brand Name <span class="text-danger">*</span></label>
                                <input type="text" name="site_name" id="siteName" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['site_name'] ?? SITE_NAME) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="siteTagline" class="form-label text-white fw-semibold">Brand Tagline / Slogan</label>
                                <input type="text" name="site_tagline" id="siteTagline" class="form-control bg-dark border-secondary text-white" placeholder="Leading Film Production & Media Company in Bangladesh" value="<?= htmlspecialchars($settings['site_tagline'] ?? 'Leading Film Production & Media Company in Bangladesh') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="siteDescription" class="form-label text-white fw-semibold">Global Meta Description</label>
                                <textarea name="site_description" id="siteDescription" rows="3" class="form-control bg-dark border-secondary text-white"><?= htmlspecialchars($settings['site_description'] ?? 'AR Entertainment is Bangladesh’s premier video production house and line production fixer specializing in TV commercials, online video ads, documentaries, and AI cinema.') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="siteKeywords" class="form-label text-white fw-semibold">Global Meta Focus Keywords</label>
                                <input type="text" name="site_keywords" id="siteKeywords" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['site_keywords'] ?? 'video production bangladesh, tv commercial dhaka, film fixer bangladesh, ovc production, line producer') ?>">
                            </div>

                            <div class="mb-0">
                                <label for="footerCopyright" class="form-label text-white fw-semibold">Footer Copyright Text</label>
                                <input type="text" name="footer_copyright" id="footerCopyright" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['footer_copyright'] ?? '© ' . date('Y') . ' AR Entertainment. All Rights Reserved.') ?>">
                            </div>
                        </div>

                        <!-- Brand Logos & Graphics Card (with Live AVIF Converter & Preview) -->
                        <div class="card-ar mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                                <h5 class="fw-bold text-white mb-0">
                                    <i class="fa-solid fa-image text-warning me-2"></i> Brand Logos &amp; Favicon
                                </h5>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">
                                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Auto-Converts to AVIF
                                </span>
                            </div>

                            <!-- 1. Main Header Logo -->
                            <div class="mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                                <label class="form-label text-white fw-semibold d-block mb-2">
                                    Main Header Logo <small class="text-muted fw-normal">(Primary Navigation Bar)</small>
                                </label>
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-md-5">
                                        <div class="rounded-3 border border-secondary p-2 d-flex align-items-center justify-content-center position-relative" style="height: 110px; background: repeating-conic-gradient(#222330 0% 25%, #181926 0% 50%) 50% / 16px 16px;">
                                            <?php if (!empty($current_logo)): ?>
                                                <img id="logoMainPreview" src="<?= htmlspecialchars(upload_url($current_logo)) ?>" alt="Header Logo" style="max-width: 90%; max-height: 80px; object-fit: contain;">
                                                <div id="logoMainPlaceholder" class="text-muted small text-center d-none">
                                                    <i class="fa-solid fa-image fa-2x mb-1 text-secondary"></i>
                                                    <div>No Logo Set</div>
                                                </div>
                                            <?php else: ?>
                                                <img id="logoMainPreview" src="" alt="Header Logo" class="d-none" style="max-width: 90%; max-height: 80px; object-fit: contain;">
                                                <div id="logoMainPlaceholder" class="text-muted small text-center">
                                                    <i class="fa-solid fa-image fa-2x mb-1 text-secondary"></i>
                                                    <div>Default Brand Header Logo</div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="file" name="site_logo" id="siteLogoInput" class="form-control bg-dark border-secondary text-white mb-2" accept=".png,.webp,.avif,.jpg,.jpeg,.svg">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <span class="text-muted" style="font-size: 11px;">PNG, WebP, AVIF, SVG (Auto-compressed to AVIF)</span>
                                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2 <?= empty($current_logo) ? 'd-none' : '' ?>" id="btnRemoveLogoMain">
                                                <i class="fa-solid fa-trash me-1"></i> Remove Logo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Dark Background / Footer Logo -->
                            <div class="mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                                <label class="form-label text-white fw-semibold d-block mb-2">
                                    Footer / Dark Alternate Logo <small class="text-muted fw-normal">(Optional)</small>
                                </label>
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-md-5">
                                        <div class="rounded-3 border border-secondary p-2 d-flex align-items-center justify-content-center" style="height: 110px; background: repeating-conic-gradient(#222330 0% 25%, #181926 0% 50%) 50% / 16px 16px;">
                                            <?php if (!empty($current_logo_dark)): ?>
                                                <img id="logoDarkPreview" src="<?= htmlspecialchars(upload_url($current_logo_dark)) ?>" alt="Dark Logo" style="max-width: 90%; max-height: 80px; object-fit: contain;">
                                                <div id="logoDarkPlaceholder" class="text-muted small text-center d-none">
                                                    <i class="fa-solid fa-image fa-2x mb-1 text-secondary"></i>
                                                    <div>No Dark Logo</div>
                                                </div>
                                            <?php else: ?>
                                                <img id="logoDarkPreview" src="" alt="Dark Logo" class="d-none" style="max-width: 90%; max-height: 80px; object-fit: contain;">
                                                <div id="logoDarkPlaceholder" class="text-muted small text-center">
                                                    <i class="fa-solid fa-image fa-2x mb-1 text-secondary"></i>
                                                    <div>Optional Alternate Logo</div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="file" name="site_logo_dark" id="siteLogoDarkInput" class="form-control bg-dark border-secondary text-white mb-2" accept=".png,.webp,.avif,.jpg,.jpeg,.svg">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <span class="text-muted" style="font-size: 11px;">Recommended: High-contrast light PNG / SVG for dark footers</span>
                                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2 <?= empty($current_logo_dark) ? 'd-none' : '' ?>" id="btnRemoveLogoDark">
                                                <i class="fa-solid fa-trash me-1"></i> Remove Logo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Favicon & App Icon -->
                            <div class="mb-3">
                                <label class="form-label text-white fw-semibold d-block mb-2">
                                    Browser Favicon / Web Icon <small class="text-muted fw-normal">(Tabs &amp; Bookmarks)</small>
                                </label>
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-md-5">
                                        <div class="rounded-3 border border-secondary p-2 d-flex align-items-center justify-content-center" style="height: 80px; background-color: #12131d;">
                                            <?php if (!empty($current_favicon)): ?>
                                                <img id="faviconPreview" src="<?= htmlspecialchars(upload_url($current_favicon)) ?>" alt="Favicon" style="width: 36px; height: 36px; object-fit: contain;">
                                                <div id="faviconPlaceholder" class="text-muted small text-center d-none">
                                                    <i class="fa-solid fa-icons fa-2x text-secondary"></i>
                                                </div>
                                            <?php else: ?>
                                                <img id="faviconPreview" src="" alt="Favicon" class="d-none" style="width: 36px; height: 36px; object-fit: contain;">
                                                <div id="faviconPlaceholder" class="text-muted small text-center">
                                                    <i class="fa-solid fa-icons fa-2x text-secondary mb-1"></i>
                                                    <div style="font-size: 10px;">Default Favicon</div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="file" name="site_favicon" id="siteFaviconInput" class="form-control bg-dark border-secondary text-white mb-2" accept=".ico,.png,.svg,.webp,.avif">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <span class="text-muted" style="font-size: 11px;">Square 32x32 / 64x64 (.png, .ico, .svg, .avif)</span>
                                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2 <?= empty($current_favicon) ? 'd-none' : '' ?>" id="btnRemoveFavicon">
                                                <i class="fa-solid fa-trash me-1"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-3 border-top border-secondary border-opacity-25">
                                <button type="submit" class="btn btn-ar-primary px-4 py-2 fw-bold">
                                    <i class="fa-solid fa-check-circle me-2"></i> Save General &amp; Logo Settings
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

                <!-- TAB 2: Contact & Studio -->
                <?php if ($active_tab === 'contact'): ?>
                    <form method="POST" action="<?= site_url('admin/settings/?tab=contact') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_tab" value="contact">

                        <div class="card-ar mb-4">
                            <h5 class="fw-bold text-white mb-3">
                                <i class="fa-solid fa-address-book text-success me-2"></i> Official Contact &amp; Studio Info
                            </h5>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="companyEmail" class="form-label text-white fw-semibold">Official Inquiries Email <span class="text-danger">*</span></label>
                                    <input type="email" name="company_email" id="companyEmail" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['company_email'] ?? 'info@arentertainment.bd') ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="leadNotifyEmail" class="form-label text-white fw-semibold">Lead Notification Email</label>
                                    <input type="email" name="lead_notify_email" id="leadNotifyEmail" class="form-control bg-dark border-secondary text-white" placeholder="admin@arentertainment.bd" value="<?= htmlspecialchars($settings['lead_notify_email'] ?? 'admin@arentertainment.bd') ?>">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label for="companyPhone1" class="form-label text-white fw-semibold">Primary Hotline</label>
                                    <input type="text" name="company_phone_1" id="companyPhone1" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['company_phone_1'] ?? '+880 1711 000000') ?>">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="companyPhone2" class="form-label text-white fw-semibold">Secondary Phone</label>
                                    <input type="text" name="company_phone_2" id="companyPhone2" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['company_phone_2'] ?? '+880 1811 000000') ?>">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="companyWhatsapp" class="form-label text-white fw-semibold">WhatsApp Business</label>
                                    <input type="text" name="company_whatsapp" id="companyWhatsapp" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['company_whatsapp'] ?? '+880 1711 000000') ?>">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-8">
                                    <label for="companyAddress" class="form-label text-white fw-semibold">Studio / Office Address</label>
                                    <input type="text" name="company_address" id="companyAddress" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['company_address'] ?? 'House #12, Road #4, Banani DOHS, Dhaka 1206') ?>">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="companyCity" class="form-label text-white fw-semibold">City / Country</label>
                                    <input type="text" name="company_city" id="companyCity" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['company_city'] ?? 'Dhaka, Bangladesh') ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="companyHours" class="form-label text-white fw-semibold">Working Hours</label>
                                <input type="text" name="company_hours" id="companyHours" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($settings['company_hours'] ?? 'Saturday - Thursday: 10:00 AM - 7:00 PM (Friday Closed)') ?>">
                            </div>

                            <div class="mb-4">
                                <label for="googleMapsEmbed" class="form-label text-white fw-semibold">Google Maps Embed URL</label>
                                <input type="text" name="google_maps_embed" id="googleMapsEmbed" class="form-control bg-dark border-secondary text-white" placeholder="https://www.google.com/maps/embed?pb=..." value="<?= htmlspecialchars($settings['google_maps_embed'] ?? '') ?>">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-ar-primary px-4 py-2">
                                    <i class="fa-solid fa-check-circle me-2"></i> Save Contact Settings
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

                <!-- TAB 3: Social Media & Streaming -->
                <?php if ($active_tab === 'social'): ?>
                    <form method="POST" action="<?= site_url('admin/settings/?tab=social') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_tab" value="social">

                        <div class="card-ar mb-4">
                            <h5 class="fw-bold text-white mb-3">
                                <i class="fa-brands fa-youtube text-danger me-2"></i> Social Channels &amp; Video Showcases
                            </h5>

                            <div class="mb-3">
                                <label for="socialYoutube" class="form-label text-white fw-semibold">
                                    <i class="fa-brands fa-youtube text-danger me-1"></i> YouTube Official Channel URL
                                </label>
                                <input type="url" name="social_youtube" id="socialYoutube" class="form-control bg-dark border-secondary text-white" placeholder="https://youtube.com/@arentertainment" value="<?= htmlspecialchars($settings['social_youtube'] ?? 'https://youtube.com/@arentertainment') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="socialVimeo" class="form-label text-white fw-semibold">
                                    <i class="fa-brands fa-vimeo text-info me-1"></i> Vimeo Portfolio Channel URL
                                </label>
                                <input type="url" name="social_vimeo" id="socialVimeo" class="form-control bg-dark border-secondary text-white" placeholder="https://vimeo.com/arentertainment" value="<?= htmlspecialchars($settings['social_vimeo'] ?? 'https://vimeo.com/arentertainment') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="socialFacebook" class="form-label text-white fw-semibold">
                                    <i class="fa-brands fa-facebook text-primary me-1"></i> Facebook Official Page URL
                                </label>
                                <input type="url" name="social_facebook" id="socialFacebook" class="form-control bg-dark border-secondary text-white" placeholder="https://facebook.com/arentertainment.bd" value="<?= htmlspecialchars($settings['social_facebook'] ?? 'https://facebook.com/arentertainment.bd') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="socialInstagram" class="form-label text-white fw-semibold">
                                    <i class="fa-brands fa-instagram text-danger me-1"></i> Instagram Profile URL
                                </label>
                                <input type="url" name="social_instagram" id="socialInstagram" class="form-control bg-dark border-secondary text-white" placeholder="https://instagram.com/arentertainment.bd" value="<?= htmlspecialchars($settings['social_instagram'] ?? 'https://instagram.com/arentertainment.bd') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="socialLinkedin" class="form-label text-white fw-semibold">
                                    <i class="fa-brands fa-linkedin text-info me-1"></i> LinkedIn Company Page URL
                                </label>
                                <input type="url" name="social_linkedin" id="socialLinkedin" class="form-control bg-dark border-secondary text-white" placeholder="https://linkedin.com/company/ar-entertainment-bd" value="<?= htmlspecialchars($settings['social_linkedin'] ?? 'https://linkedin.com/company/ar-entertainment-bd') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="socialImdb" class="form-label text-white fw-semibold">
                                    <i class="fa-brands fa-imdb text-warning me-1"></i> IMDb Company / Director Profile URL
                                </label>
                                <input type="url" name="social_imdb" id="socialImdb" class="form-control bg-dark border-secondary text-white" placeholder="https://www.imdb.com/name/nm..." value="<?= htmlspecialchars($settings['social_imdb'] ?? '') ?>">
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <label for="socialTwitter" class="form-label text-white fw-semibold">
                                        <i class="fa-brands fa-x-twitter text-light me-1"></i> X / Twitter Profile URL
                                    </label>
                                    <input type="url" name="social_twitter" id="socialTwitter" class="form-control bg-dark border-secondary text-white" placeholder="https://x.com/arentertainment" value="<?= htmlspecialchars($settings['social_twitter'] ?? '') ?>">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="socialTiktok" class="form-label text-white fw-semibold">
                                        <i class="fa-brands fa-tiktok text-light me-1"></i> TikTok Profile URL
                                    </label>
                                    <input type="url" name="social_tiktok" id="socialTiktok" class="form-control bg-dark border-secondary text-white" placeholder="https://tiktok.com/@arentertainment" value="<?= htmlspecialchars($settings['social_tiktok'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-ar-primary px-4 py-2">
                                    <i class="fa-solid fa-check-circle me-2"></i> Save Social Media Profiles
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

                <!-- TAB 4: Analytics & Tracking -->
                <?php if ($active_tab === 'analytics'): ?>
                    <form method="POST" action="<?= site_url('admin/settings/?tab=analytics') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_tab" value="analytics">

                        <div class="card-ar mb-4">
                            <h5 class="fw-bold text-white mb-3">
                                <i class="fa-solid fa-chart-line text-warning me-2"></i> Analytics, Pixel &amp; Verification Keys
                            </h5>

                            <div class="mb-3">
                                <label for="ga4MeasurementId" class="form-label text-white fw-semibold">Google Analytics 4 Measurement ID</label>
                                <input type="text" name="ga4_measurement_id" id="ga4MeasurementId" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="G-XXXXXXXXXX" value="<?= htmlspecialchars($settings['ga4_measurement_id'] ?? '') ?>">
                                <div class="form-text text-muted small">Enter your GA4 Measurement ID (e.g. G-ABC123XYZ) to enable auto tracking across all pages.</div>
                            </div>

                            <div class="mb-3">
                                <label for="metaPixelId" class="form-label text-white fw-semibold">Meta (Facebook) Pixel ID</label>
                                <input type="text" name="meta_pixel_id" id="metaPixelId" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="123456789012345" value="<?= htmlspecialchars($settings['meta_pixel_id'] ?? '') ?>">
                                <div class="form-text text-muted small">Enter your numeric Meta Pixel ID for ad campaign conversion tracking.</div>
                            </div>

                            <div class="mb-3">
                                <label for="googleSiteVerify" class="form-label text-white fw-semibold">Google Search Console Verification Tag</label>
                                <input type="text" name="google_site_verify" id="googleSiteVerify" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="google-site-verification=abcdefg..." value="<?= htmlspecialchars($settings['google_site_verify'] ?? '') ?>">
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <label for="recaptchaSiteKey" class="form-label text-white fw-semibold">reCAPTCHA v3 Site Key</label>
                                    <input type="text" name="recaptcha_site_key" id="recaptchaSiteKey" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="6L..." value="<?= htmlspecialchars($settings['recaptcha_site_key'] ?? '') ?>">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="recaptchaSecretKey" class="form-label text-white fw-semibold">reCAPTCHA v3 Secret Key</label>
                                    <input type="password" name="recaptcha_secret_key" id="recaptchaSecretKey" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="••••••••••••" value="<?= htmlspecialchars($settings['recaptcha_secret_key'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-ar-primary px-4 py-2">
                                    <i class="fa-solid fa-check-circle me-2"></i> Save Analytics Keys
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

                <!-- TAB 5: Custom Code & Scripts -->
                <?php if ($active_tab === 'custom_code'): ?>
                    <form method="POST" action="<?= site_url('admin/settings/?tab=custom_code') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_tab" value="custom_code">

                        <div class="card-ar mb-4">
                            <h5 class="fw-bold text-white mb-3">
                                <i class="fa-solid fa-code text-info me-2"></i> Custom Header, Footer &amp; CSS Injection
                            </h5>

                            <div class="mb-3">
                                <label for="customHeaderCode" class="form-label text-white fw-semibold">Custom &lt;head&gt; Code Injection</label>
                                <textarea name="custom_header_code" id="customHeaderCode" rows="5" class="form-control bg-dark border-secondary text-white font-monospace" style="font-size: 0.85rem;" placeholder="<!-- Google Tag Manager or external meta tags -->"><?= htmlspecialchars($settings['custom_header_code'] ?? '') ?></textarea>
                                <div class="form-text text-muted small">Scripts or tags placed here will be loaded inside &lt;head&gt; on every public frontend page.</div>
                            </div>

                            <div class="mb-3">
                                <label for="customFooterCode" class="form-label text-white fw-semibold">Custom Footer &lt;/body&gt; Scripts</label>
                                <textarea name="custom_footer_code" id="customFooterCode" rows="5" class="form-control bg-dark border-secondary text-white font-monospace" style="font-size: 0.85rem;" placeholder="<!-- Live chat widgets, tracking beacons -->"><?= htmlspecialchars($settings['custom_footer_code'] ?? '') ?></textarea>
                                <div class="form-text text-muted small">Scripts placed here will be loaded right before &lt;/body&gt; closing tag.</div>
                            </div>

                            <div class="mb-4">
                                <label for="customCss" class="form-label text-white fw-semibold">Custom CSS Overrides</label>
                                <textarea name="custom_css" id="customCss" rows="5" class="form-control bg-dark border-secondary text-white font-monospace" style="font-size: 0.85rem;" placeholder="/* Custom CSS rules */"><?= htmlspecialchars($settings['custom_css'] ?? '') ?></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-ar-primary px-4 py-2">
                                    <i class="fa-solid fa-check-circle me-2"></i> Save Custom Code
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Right Column: System Information & Quick Reference -->
            <div class="col-12 col-lg-4">
                <div class="card-ar mb-4">
                    <h6 class="fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                        <i class="fa-solid fa-circle-info text-info me-2"></i> Settings &amp; Logo Helpers
                    </h6>
                    <p class="text-muted small mb-3">
                        These configuration values and logos are globally accessible anywhere across the codebase via helper functions:
                    </p>
                    <div class="p-2 rounded bg-dark border border-secondary font-monospace text-warning small mb-2">
                        &lt;?= get_setting('site_name') ?&gt;
                    </div>
                    <div class="p-2 rounded bg-dark border border-secondary font-monospace text-info small mb-3">
                        &lt;?= upload_url(get_setting('site_logo')) ?&gt;
                    </div>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="d-flex justify-content-between py-2 border-bottom border-dark">
                            <span>Active Domain:</span>
                            <span class="text-white font-monospace"><?= SITE_DOMAIN ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-dark">
                            <span>Base URL:</span>
                            <span class="text-white font-monospace"><?= BASE_URL ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-dark">
                            <span>Uploads Directory:</span>
                            <span class="text-success font-monospace">/uploads/settings/</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span>Image Format Engine:</span>
                            <span class="text-warning">AVIF / WebP Auto-Optimize</span>
                        </li>
                    </ul>
                </div>

                <div class="card-ar">
                    <h6 class="fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                        <i class="fa-solid fa-lightbulb text-warning me-2"></i> AVIF Optimization Tip
                    </h6>
                    <p class="text-muted small mb-0">
                        Uploaded PNG and JPEG logos are automatically converted to next-generation AVIF/WebP formats with alpha transparency preserved, providing 50%+ faster page load times.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupImagePreview(inputId, previewId, placeholderId, removeBtnId, removeFlagInputId) {
        const fileInput    = document.getElementById(inputId);
        const previewImg   = document.getElementById(previewId);
        const placeholder  = document.getElementById(placeholderId);
        const removeBtn    = document.getElementById(removeBtnId);
        const removeFlag   = document.getElementById(removeFlagInputId);

        if (!fileInput || !previewImg) return;

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Selected image exceeds 5MB limit.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImg.src = evt.target.result;
                    previewImg.classList.remove('d-none');
                    if (placeholder) placeholder.classList.add('d-none');
                    if (removeBtn) removeBtn.classList.remove('d-none');
                    if (removeFlag) removeFlag.value = '0';
                };
                reader.readAsDataURL(file);
            }
        });

        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                fileInput.value = '';
                previewImg.src = '';
                previewImg.classList.add('d-none');
                if (placeholder) placeholder.classList.remove('d-none');
                removeBtn.classList.add('d-none');
                if (removeFlag) removeFlag.value = '1';
            });
        }
    }

    setupImagePreview('siteLogoInput', 'logoMainPreview', 'logoMainPlaceholder', 'btnRemoveLogoMain', 'removeLogoInput');
    setupImagePreview('siteLogoDarkInput', 'logoDarkPreview', 'logoDarkPlaceholder', 'btnRemoveLogoDark', 'removeLogoDarkInput');
    setupImagePreview('siteFaviconInput', 'faviconPreview', 'faviconPlaceholder', 'btnRemoveFavicon', 'removeFaviconInput');
});
</script>
