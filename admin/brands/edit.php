<?php
/**
 * AR Entertainment - Edit Brand / Client / Award
 * Phase 4.5: Management CRUD (admin/brands/edit.php)
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

require_login();

$current_page = 'brands';
$page_title   = 'Edit Brand / Client';

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    set_flash('error', 'Invalid brand ID specified.');
    redirect('admin/brands');
}

// Fetch existing brand
$brand = null;
try {
    $stmt = db()->prepare("SELECT * FROM brands WHERE id = ?");
    $stmt->execute([$id]);
    $brand = $stmt->fetch();

    if (!$brand) {
        set_flash('error', 'Brand or partner not found.');
        redirect('admin/brands');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/brands');
}

$name         = $brand['name'];
$brand_type   = $brand['brand_type'];
$website_url  = $brand['website_url'] ?? '';
$sort_order   = (int) $brand['sort_order'];
$status       = $brand['status'];
$current_logo = $brand['logo'] ?? '';
$errors       = [];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security verification failed (CSRF token expired). Please try again.';
    } else {
        $name        = trim($_POST['name'] ?? '');
        $brand_type  = in_array($_POST['brand_type'] ?? '', ['client', 'partner', 'award', 'affiliation'], true) ? $_POST['brand_type'] : 'client';
        $website_url = trim($_POST['website_url'] ?? '');
        $sort_order  = (int) ($_POST['sort_order'] ?? 0);
        $status      = in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active';
        $remove_logo = isset($_POST['remove_logo']) && $_POST['remove_logo'] === '1';

        // Validation
        if ($name === '') {
            $errors[] = 'Brand / Company name is required.';
        }

        if ($website_url !== '' && !filter_var($website_url, FILTER_VALIDATE_URL)) {
            $errors[] = 'Please provide a valid website URL (starting with http:// or https://).';
        }

        $logo_path = $current_logo;

        // Handle Logo Replacement or Removal
        if (!empty($_FILES['logo']['tmp_name'])) {
            $upload_res = upload_image(
                $_FILES['logo'],
                'brands',
                ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'],
                5242880,
                800,
                85
            );
            if ($upload_res['success']) {
                // Delete old logo file if it was uploaded to uploads/
                if (!empty($current_logo) && !str_starts_with($current_logo, 'images/')) {
                    $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $current_logo);
                    if (file_exists($old_file)) {
                        @unlink($old_file);
                    }
                }
                $logo_path = $upload_res['path'];
            } else {
                $errors[] = 'Logo upload failed: ' . $upload_res['error'];
            }
        } elseif ($remove_logo) {
            if (!empty($current_logo) && !str_starts_with($current_logo, 'images/')) {
                $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $current_logo);
                if (file_exists($old_file)) {
                    @unlink($old_file);
                }
            }
            $logo_path = '';
        }

        // Save changes
        if (empty($errors)) {
            try {
                $sql = "
                    UPDATE brands SET
                        name        = :name,
                        logo        = :logo,
                        website_url = :website_url,
                        brand_type  = :brand_type,
                        sort_order  = :sort_order,
                        status      = :status,
                        updated_at  = NOW()
                    WHERE id = :id
                ";
                $stmt = db()->prepare($sql);
                $stmt->execute([
                    ':name'        => $name,
                    ':logo'        => $logo_path,
                    ':website_url' => $website_url ?: null,
                    ':brand_type'  => $brand_type,
                    ':sort_order'  => $sort_order,
                    ':status'      => $status,
                    ':id'          => $id
                ]);

                set_flash('success', "Brand '<strong>" . htmlspecialchars($name) . "</strong>' updated successfully.");
                redirect('admin/brands');
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

require_once ADMIN_PATH . '/includes/header.php';
require_once ADMIN_PATH . '/includes/sidebar.php';
?>

<div class="admin-main">
    <div class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary d-lg-none" id="sidebarToggleBtn">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div>
                <h4 class="fw-bold mb-0 text-white">Edit Brand / Client</h4>
                <small class="text-muted">Editing <?= htmlspecialchars($name) ?></small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('admin/brands') ?>" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Brands
            </a>
        </div>
    </div>

    <main class="content-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-2"></i>Please resolve the following issues:</div>
                <ul class="mb-0 ps-3">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= site_url('admin/brands/edit.php?id=' . $id) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Left Column: Primary Details -->
                <div class="col-12 col-lg-8">
                    <!-- Basic Information Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-building text-primary me-2"></i> Brand / Partner Details
                        </h5>

                        <!-- Brand Name -->
                        <div class="mb-4">
                            <label for="brandName" class="form-label text-white fw-semibold">
                                Brand / Company / Organization Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="brandName" class="form-control bg-dark border-secondary text-white" placeholder="e.g. PRAN-RFL Group or Chorki OTT" value="<?= htmlspecialchars($name) ?>" required>
                        </div>

                        <!-- Brand Type Selection -->
                        <div class="mb-4">
                            <label class="form-label text-white fw-semibold d-block">
                                Category / Type <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="brand_type" id="typeClient" value="client" <?= ($brand_type === 'client') ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary w-100 py-2 text-start d-flex align-items-center gap-2" for="typeClient">
                                        <i class="fa-solid fa-briefcase"></i>
                                        <div>
                                            <div class="fw-bold small">Client</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Customer Logo</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="brand_type" id="typePartner" value="partner" <?= ($brand_type === 'partner') ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-info w-100 py-2 text-start d-flex align-items-center gap-2" for="typePartner">
                                        <i class="fa-solid fa-handshake"></i>
                                        <div>
                                            <div class="fw-bold small">Partner</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">OTT / Platform</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="brand_type" id="typeAward" value="award" <?= ($brand_type === 'award') ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-warning w-100 py-2 text-start d-flex align-items-center gap-2" for="typeAward">
                                        <i class="fa-solid fa-trophy"></i>
                                        <div>
                                            <div class="fw-bold small">Award</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Trophy / Honor</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="brand_type" id="typeAffiliation" value="affiliation" <?= ($brand_type === 'affiliation') ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-secondary w-100 py-2 text-start d-flex align-items-center gap-2" for="typeAffiliation">
                                        <i class="fa-solid fa-certificate"></i>
                                        <div>
                                            <div class="fw-bold small">Affiliation</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Guild / Council</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Website URL -->
                        <div class="mb-0">
                            <label for="websiteUrl" class="form-label text-white fw-semibold">
                                Official Website URL <small class="text-muted fw-normal">(Optional)</small>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-muted">
                                    <i class="fa-solid fa-globe"></i>
                                </span>
                                <input type="url" name="website_url" id="websiteUrl" class="form-control bg-dark border-secondary text-white" placeholder="https://www.example.com" value="<?= htmlspecialchars($website_url) ?>">
                            </div>
                            <div class="form-text text-muted small">Visitors clicking this logo can be linked to their official website.</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Logo Uploader & Publishing Settings -->
                <div class="col-12 col-lg-4">
                    <!-- Logo Upload Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-image text-warning me-2"></i> Brand Logo Image
                        </h5>

                        <div class="text-center mb-3">
                            <div id="logoPreviewContainer" class="d-flex align-items-center justify-content-center mx-auto rounded-3 border border-secondary p-3 mb-2" style="width: 100%; height: 140px; background: repeating-conic-gradient(#20202e 0% 25%, #161622 0% 50%) 50% / 20px 20px;">
                                <?php if (!empty($current_logo)): ?>
                                    <img id="previewImg" src="<?= htmlspecialchars(upload_url($current_logo)) ?>" alt="Logo Preview" style="max-width: 100%; max-height: 110px; object-fit: contain;">
                                    <div id="previewPlaceholder" class="text-center p-3 text-muted d-none">
                                        <i class="fa-solid fa-cloud-arrow-up fa-3x mb-2 text-secondary"></i>
                                        <div class="small">No logo selected</div>
                                    </div>
                                <?php else: ?>
                                    <img id="previewImg" src="" alt="Logo Preview" class="d-none" style="max-width: 100%; max-height: 110px; object-fit: contain;">
                                    <div id="previewPlaceholder" class="text-center p-3 text-muted">
                                        <i class="fa-solid fa-cloud-arrow-up fa-3x mb-2 text-secondary"></i>
                                        <div class="small">No logo selected</div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($current_logo)): ?>
                                <div class="form-check form-check-inline mb-2">
                                    <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remove_logo" id="removeLogoCheck" value="1">
                                    <label class="form-check-label text-danger small" for="removeLogoCheck">
                                        <i class="fa-solid fa-trash me-1"></i> Remove current logo
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="logoFile" class="form-label text-muted small fw-semibold">
                                <?= !empty($current_logo) ? 'Replace Logo File' : 'Upload Logo File' ?>
                            </label>
                            <input type="file" name="logo" id="logoFile" class="form-control bg-dark border-secondary text-white" accept=".jpg,.jpeg,.png,.webp,.svg">
                            <div class="form-text text-muted small">
                                Allowed: .svg, .png, .webp, .jpg (Max 5MB). Leave blank to keep current logo.
                            </div>
                        </div>
                    </div>

                    <!-- Publishing & Sort Order Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-sliders text-success me-2"></i> Display Settings
                        </h5>

                        <div class="mb-3">
                            <label for="brandStatus" class="form-label text-white fw-semibold">Status</label>
                            <select name="status" id="brandStatus" class="form-select bg-dark border-secondary text-white">
                                <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active (Visible on Website)</option>
                                <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive (Hidden / Draft)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="sortOrder" class="form-label text-white fw-semibold">
                                Sort Order Priority
                            </label>
                            <input type="number" name="sort_order" id="sortOrder" class="form-control bg-dark border-secondary text-white" value="<?= (int) $sort_order ?>" min="0" step="1">
                            <div class="form-text text-muted small">
                                Lower numbers appear first on client carousels and showcase grids.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <i class="fa-solid fa-check-circle me-2"></i> Update Brand / Client
                            </button>
                            <a href="<?= site_url('admin/brands') ?>" class="btn btn-outline-secondary py-2">
                                <i class="fa-solid fa-arrow-left me-1"></i> Cancel &amp; Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoFileInput     = document.getElementById('logoFile');
    const previewImg         = document.getElementById('previewImg');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    if (logoFileInput && previewImg) {
        logoFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtensions = ['.svg', '.png', '.webp', '.jpg', '.jpeg'];
                const hasValidExt = validExtensions.some(ext => fileName.endsWith(ext));

                if (!hasValidExt) {
                    alert('Invalid file format. Allowed formats: .svg, .png, .webp, .jpg, .jpeg (' + file.name + ' is not supported)');
                    this.value = '';
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('The selected logo is ' + (file.size / (1024 * 1024)).toFixed(1) + 'MB. Maximum allowed size is 5MB.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImg.src = evt.target.result;
                    previewImg.classList.remove('d-none');
                    if (previewPlaceholder) {
                        previewPlaceholder.classList.add('d-none');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
