<?php

/**
 * AR Entertainment - Edit Team Member Profile
 * 
 * Edit staff profiles, designations, biographies, headshots, contact information,
 * and social profiles.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Edit Team Member';
$current_page = 'team_edit';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Invalid team member ID.');
    redirect('admin/team');
}

// Fetch existing record
try {
    $stmt = db()->prepare("SELECT * FROM team_members WHERE id = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch();

    if (!$member) {
        set_flash('error', 'Team member profile not found.');
        redirect('admin/team');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/team');
}

// Unpack defaults
$name        = $member['name'];
$slug        = $member['slug'];
$role_title  = $member['role_title'];
$bio         = $member['bio'] ?? '';
$email       = $member['email'] ?? '';
$phone       = $member['phone'] ?? '';
$sort_order  = (int) $member['sort_order'];
$status      = $member['status'];
$current_photo = $member['photo'] ?? '';

// Unpack social links JSON
$social_links = [
    'linkedin'  => '',
    'imdb'      => '',
    'facebook'  => '',
    'instagram' => '',
    'twitter'   => '',
    'website'   => ''
];

if (!empty($member['social_links_json'])) {
    $decoded = json_decode($member['social_links_json'], true);
    if (is_array($decoded)) {
        $social_links = array_merge($social_links, $decoded);
    }
}

$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security verification failed (CSRF token expired). Please try again.';
    } else {
        $name         = trim($_POST['name'] ?? '');
        $slug         = trim($_POST['slug'] ?? '');
        $role_title   = trim($_POST['role_title'] ?? '');
        $bio          = trim($_POST['bio'] ?? '');
        $email        = trim($_POST['email'] ?? '');
        $phone        = trim($_POST['phone'] ?? '');
        $sort_order   = (int) ($_POST['sort_order'] ?? 0);
        $status       = in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active';
        $remove_photo = isset($_POST['remove_photo']) && $_POST['remove_photo'] === '1';

        // Collect social links
        $social_links = [
            'linkedin'  => trim($_POST['social_linkedin'] ?? ''),
            'imdb'      => trim($_POST['social_imdb'] ?? ''),
            'facebook'  => trim($_POST['social_facebook'] ?? ''),
            'instagram' => trim($_POST['social_instagram'] ?? ''),
            'twitter'   => trim($_POST['social_twitter'] ?? ''),
            'website'   => trim($_POST['social_website'] ?? '')
        ];

        // Basic validation
        if ($name === '') {
            $errors[] = 'Member full name is required.';
        }

        if ($role_title === '') {
            $errors[] = 'Role title / designation is required.';
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please provide a valid email address.';
        }

        // Generate / sanitize slug
        if ($slug === '') {
            $slug = slugify($name);
        } else {
            $slug = slugify($slug);
        }

        // Ensure unique slug (excluding current record)
        try {
            $check_slug = db()->prepare("SELECT COUNT(*) FROM team_members WHERE slug = ? AND id != ?");
            $check_slug->execute([$slug, $id]);
            if ($check_slug->fetchColumn() > 0) {
                $slug = $slug . '-' . time();
            }
        } catch (PDOException $e) {
            $errors[] = 'Database error checking slug: ' . $e->getMessage();
        }

        $photo_path = $current_photo;

        // Handle Photo Replacement or Removal
        if (!empty($_FILES['photo']['tmp_name'])) {
            $upload_res = upload_image($_FILES['photo'], 'team');
            if ($upload_res['success']) {
                // Delete old file if present
                if (!empty($current_photo)) {
                    $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $current_photo);
                    if (file_exists($old_file)) {
                        @unlink($old_file);
                    }
                }
                $photo_path = $upload_res['path'];
            } else {
                $errors[] = 'Photo upload failed: ' . $upload_res['error'];
            }
        } elseif ($remove_photo) {
            if (!empty($current_photo)) {
                $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $current_photo);
                if (file_exists($old_file)) {
                    @unlink($old_file);
                }
            }
            $photo_path = null;
        }

        if (empty($errors)) {
            try {
                // Filter empty social links
                $filtered_social = array_filter($social_links, fn($val) => $val !== '');
                $social_json = !empty($filtered_social) ? json_encode($filtered_social, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;

                $sql = "
                    UPDATE team_members SET
                        name              = :name,
                        slug              = :slug,
                        role_title        = :role_title,
                        bio               = :bio,
                        photo             = :photo,
                        email             = :email,
                        phone             = :phone,
                        social_links_json = :social_links_json,
                        sort_order        = :sort_order,
                        status            = :status,
                        updated_at        = NOW()
                    WHERE id = :id
                ";
                $stmt = db()->prepare($sql);
                $stmt->execute([
                    ':name'              => $name,
                    ':slug'              => $slug,
                    ':role_title'        => $role_title,
                    ':bio'               => $bio ?: null,
                    ':photo'             => $photo_path ?: null,
                    ':email'             => $email ?: null,
                    ':phone'             => $phone ?: null,
                    ':social_links_json' => $social_json,
                    ':sort_order'        => $sort_order,
                    ':status'            => $status,
                    ':id'                => $id
                ]);

                set_flash('success', "Team member '<strong>" . htmlspecialchars($name) . "</strong>' updated successfully!");
                redirect('admin/team');
            } catch (PDOException $e) {
                $errors[] = 'Failed to update team member: ' . $e->getMessage();
            }
        }
    }
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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/team') ?>" class="text-muted text-decoration-none">Team Members</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Edit Member</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Edit Team Member: <?= htmlspecialchars($member['name']) ?></h3>
            </div>
            <div>
                <a href="<?= site_url('admin/team') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Team
                </a>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please fix the following errors:</div>
                <ul class="mb-0 ps-3">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= site_url('admin/team/edit?id=' . $id) ?>" enctype="multipart/form-data" id="teamForm">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Left Column (Main Information & Social Links) -->
                <div class="col-12 col-lg-8">
                    <!-- Basic Info Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-id-card text-danger me-2"></i> Member Details
                        </h5>

                        <div class="mb-3">
                            <label for="memberName" class="form-label text-white fw-semibold">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="memberName" class="form-control form-control-lg bg-dark border-secondary text-white" placeholder="e.g. Azizul Hoque Shiplu" value="<?= htmlspecialchars($name) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="memberSlug" class="form-label text-muted small fw-semibold">
                                Profile Slug <span class="text-muted">(Auto-generated permalink identifier)</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-dark border-secondary text-muted font-monospace"><?= SITE_DOMAIN ?>/team/</span>
                                <input type="text" name="slug" id="memberSlug" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="azizul-hoque-shiplu" value="<?= htmlspecialchars($slug) ?>">
                                <button class="btn btn-ar-secondary" type="button" id="btnUnlockSlug" title="Unlock custom slug">
                                    <i class="fa-solid fa-lock" id="slugLockIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="roleTitle" class="form-label text-white fw-semibold">
                                Role / Designation <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="role_title" id="roleTitle" list="rolesList" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Founder & Film Director" value="<?= htmlspecialchars($role_title) ?>" required>
                            <datalist id="rolesList">
                                <option value="Founder & Film Director">
                                <option value="Executive Producer">
                                <option value="Creative Director">
                                <option value="Director of Photography (DoP)">
                                <option value="Head of Production">
                                <option value="Head of Post-Production">
                                <option value="Senior Colorist">
                                <option value="Lead AI Video Specialist">
                                <option value="Sound Designer & Music Composer">
                                <option value="Line Producer / Film Fixer">
                                <option value="Senior Editor & Motion Designer">
                            </datalist>
                        </div>

                        <div class="mb-0">
                            <label for="memberBio" class="form-label text-white fw-semibold">
                                Biography &amp; Professional Background
                            </label>
                            <textarea name="bio" id="memberBio" rows="6" class="form-control bg-dark border-secondary text-white" placeholder="Write a short summary of their film background, notable projects, awards, or industry experience..."><?= htmlspecialchars($bio) ?></textarea>
                            <div class="form-text text-muted">A concise bio displayed on the Meet the Team and About Us pages.</div>
                        </div>
                    </div>

                    <!-- Social Profiles Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-share-nodes text-info me-2"></i> Professional &amp; Social Links
                        </h5>
                        <p class="text-muted small mb-3">Connect their official social media profiles, IMDb page, or personal portfolio.</p>

                        <div class="row g-3">
                            <!-- LinkedIn -->
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted small fw-semibold">
                                    <i class="fa-brands fa-linkedin text-primary me-1"></i> LinkedIn URL
                                </label>
                                <input type="url" name="social_linkedin" class="form-control bg-dark border-secondary text-white" placeholder="https://linkedin.com/in/username" value="<?= htmlspecialchars($social_links['linkedin']) ?>">
                            </div>

                            <!-- IMDb -->
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted small fw-semibold">
                                    <i class="fa-brands fa-imdb text-warning me-1"></i> IMDb Profile URL
                                </label>
                                <input type="url" name="social_imdb" class="form-control bg-dark border-secondary text-white" placeholder="https://www.imdb.com/name/nm..." value="<?= htmlspecialchars($social_links['imdb']) ?>">
                            </div>

                            <!-- Facebook -->
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted small fw-semibold">
                                    <i class="fa-brands fa-facebook text-info me-1"></i> Facebook Profile / Page
                                </label>
                                <input type="url" name="social_facebook" class="form-control bg-dark border-secondary text-white" placeholder="https://facebook.com/username" value="<?= htmlspecialchars($social_links['facebook']) ?>">
                            </div>

                            <!-- Instagram -->
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted small fw-semibold">
                                    <i class="fa-brands fa-instagram text-danger me-1"></i> Instagram URL
                                </label>
                                <input type="url" name="social_instagram" class="form-control bg-dark border-secondary text-white" placeholder="https://instagram.com/username" value="<?= htmlspecialchars($social_links['instagram']) ?>">
                            </div>

                            <!-- Twitter / X -->
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted small fw-semibold">
                                    <i class="fa-brands fa-x-twitter text-light me-1"></i> X (Twitter) URL
                                </label>
                                <input type="url" name="social_twitter" class="form-control bg-dark border-secondary text-white" placeholder="https://x.com/username" value="<?= htmlspecialchars($social_links['twitter']) ?>">
                            </div>

                            <!-- Personal Portfolio / Website -->
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted small fw-semibold">
                                    <i class="fa-solid fa-globe text-success me-1"></i> Personal Website / Reel
                                </label>
                                <input type="url" name="social_website" class="form-control bg-dark border-secondary text-white" placeholder="https://personalportfolio.com" value="<?= htmlspecialchars($social_links['website']) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Photo, Contact & Publishing Settings) -->
                <div class="col-12 col-lg-4">
                    <!-- Photo / Headshot Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-camera text-primary me-2"></i> Member Headshot
                        </h5>

                        <div class="text-center mb-3">
                            <div id="imagePreviewContainer" class="d-flex align-items-center justify-content-center mx-auto rounded-3 border border-secondary bg-dark overflow-hidden mb-3" style="width: 180px; height: 180px; position: relative;">
                                <?php if (!empty($current_photo)): ?>
                                    <img id="previewImg" src="<?= htmlspecialchars(upload_url($current_photo)) ?>" alt="Headshot Preview" class="w-100 h-100 object-fit-cover">
                                    <div id="previewPlaceholder" class="text-center p-3 text-muted d-none">
                                        <i class="fa-solid fa-user-tie fa-3x mb-2 text-secondary"></i>
                                        <div class="small">No image chosen</div>
                                    </div>
                                <?php else: ?>
                                    <img id="previewImg" src="" alt="Preview" class="w-100 h-100 object-fit-cover d-none">
                                    <div id="previewPlaceholder" class="text-center p-3 text-muted">
                                        <i class="fa-solid fa-user-tie fa-3x mb-2 text-secondary"></i>
                                        <div class="small">No image chosen</div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($current_photo)): ?>
                                <div class="form-check form-check-inline mb-2">
                                    <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remove_photo" id="removePhotoCheck" value="1">
                                    <label class="form-check-label text-danger small" for="removePhotoCheck">
                                        <i class="fa-solid fa-trash me-1"></i> Remove current photo
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="photoFile" class="form-label text-muted small fw-semibold">
                                <?= !empty($current_photo) ? 'Replace Photo File' : 'Upload Photo File' ?>
                            </label>
                            <input type="file" name="photo" id="photoFile" class="form-control bg-dark border-secondary text-white" accept=".jpg,.jpeg,.png,.webp">
                            <div class="form-text text-muted small">
                                Allowed: .jpg, .jpeg, .png, .webp (Max: 5MB). Auto-optimized on upload.
                            </div>
                        </div>
                    </div>

                    <!-- Direct Contact Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-address-book text-warning me-2"></i> Contact Info
                        </h5>

                        <div class="mb-3">
                            <label for="memberEmail" class="form-label text-muted small fw-semibold">
                                <i class="fa-solid fa-envelope me-1"></i> Email Address
                            </label>
                            <input type="email" name="email" id="memberEmail" class="form-control bg-dark border-secondary text-white" placeholder="name@arentertainment.bd" value="<?= htmlspecialchars($email) ?>">
                        </div>

                        <div class="mb-0">
                            <label for="memberPhone" class="form-label text-muted small fw-semibold">
                                <i class="fa-solid fa-phone me-1"></i> Phone / WhatsApp
                            </label>
                            <input type="text" name="phone" id="memberPhone" class="form-control bg-dark border-secondary text-white" placeholder="+880 1988 777444" value="<?= htmlspecialchars($phone) ?>">
                        </div>
                    </div>

                    <!-- Publishing & Sort Settings Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-sliders text-success me-2"></i> Display Settings
                        </h5>

                        <div class="mb-3">
                            <label for="memberStatus" class="form-label text-white fw-semibold">Status</label>
                            <select name="status" id="memberStatus" class="form-select bg-dark border-secondary text-white">
                                <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active (Visible on Website)</option>
                                <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive (Hidden / Draft)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="sortOrder" class="form-label text-white fw-semibold">
                                Sort Order Number
                            </label>
                            <input type="number" name="sort_order" id="sortOrder" class="form-control bg-dark border-secondary text-white" value="<?= (int) $sort_order ?>" min="0" step="1">
                            <div class="form-text text-muted small">
                                Lower numbers appear first on team showcases (e.g. 1 for Founder/Director, 2 for Executive Producer).
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-ar-primary btn-lg">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Update Team Member
                            </button>
                            <a href="<?= site_url('admin/team') ?>" class="btn btn-ar-secondary">
                                Cancel
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
    const memberNameInput = document.getElementById('memberName');
    const memberSlugInput = document.getElementById('memberSlug');
    const btnUnlockSlug   = document.getElementById('btnUnlockSlug');
    const slugLockIcon    = document.getElementById('slugLockIcon');
    let isSlugManual      = true; // In edit mode, default to locked/manual

    if (btnUnlockSlug && memberSlugInput) {
        btnUnlockSlug.addEventListener('click', function() {
            isSlugManual = !isSlugManual;
            if (!isSlugManual) {
                slugLockIcon.classList.remove('fa-lock');
                slugLockIcon.classList.add('fa-lock-open');
                // regenerate from name
                const generatedSlug = memberNameInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .trim()
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                memberSlugInput.value = generatedSlug;
            } else {
                slugLockIcon.classList.remove('fa-lock-open');
                slugLockIcon.classList.add('fa-lock');
            }
        });

        memberSlugInput.addEventListener('input', function() {
            isSlugManual = true;
            slugLockIcon.classList.remove('fa-lock');
            slugLockIcon.classList.add('fa-lock-open');
        });
    }

    // Live Photo Preview
    const photoFileInput   = document.getElementById('photoFile');
    const previewImg       = document.getElementById('previewImg');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    if (photoFileInput && previewImg) {
        photoFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
                const hasValidExt = validExtensions.some(ext => fileName.endsWith(ext));

                if (!hasValidExt) {
                    alert('Invalid file format. Only .jpg, .jpeg, .png, and .webp files are allowed. (' + file.name + ' is not allowed)');
                    this.value = '';
                    previewImg.src = '';
                    previewImg.classList.add('d-none');
                    if (previewPlaceholder) {
                        previewPlaceholder.classList.remove('d-none');
                    }
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('The selected image is ' + (file.size / (1024 * 1024)).toFixed(1) + 'MB. Maximum allowed size is 5MB. Please choose a smaller image.');
                    this.value = '';
                    previewImg.src = '';
                    previewImg.classList.add('d-none');
                    if (previewPlaceholder) {
                        previewPlaceholder.classList.remove('d-none');
                    }
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
