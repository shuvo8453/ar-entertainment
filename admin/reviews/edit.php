<?php
/**
 * AR Entertainment - Edit Client Review / Testimonial
 * Phase 4.6: Management CRUD (admin/reviews/edit.php)
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

require_login();

$current_page = 'reviews';
$page_title   = 'Edit Client Review';

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    set_flash('error', 'Invalid review ID specified.');
    redirect('admin/reviews');
}

// Fetch existing review
$review = null;
try {
    $stmt = db()->prepare("SELECT * FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    $review = $stmt->fetch();

    if (!$review) {
        set_flash('error', 'Review not found.');
        redirect('admin/reviews');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/reviews');
}

$client_name        = $review['client_name'];
$client_company     = $review['client_company'] ?? '';
$client_designation = $review['client_designation'] ?? '';
$rating             = (string) $review['rating'];
$review_text        = $review['review_text'];
$project_name       = $review['project_name'] ?? '';
$source             = $review['source'];
$sort_order         = (int) $review['sort_order'];
$status             = $review['status'];
$current_photo      = $review['client_photo'] ?? '';
$errors             = [];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security verification failed (CSRF token expired). Please try again.';
    } else {
        $client_name        = trim($_POST['client_name'] ?? '');
        $client_company     = trim($_POST['client_company'] ?? '');
        $client_designation = trim($_POST['client_designation'] ?? '');
        $rating             = (float) ($_POST['rating'] ?? 5.0);
        $review_text        = trim($_POST['review_text'] ?? '');
        $project_name       = trim($_POST['project_name'] ?? '');
        $source             = in_array($_POST['source'] ?? '', ['google', 'goodfirms', 'clutch', 'direct'], true) ? $_POST['source'] : 'google';
        $sort_order         = (int) ($_POST['sort_order'] ?? 0);
        $status             = in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active';
        $remove_photo       = isset($_POST['remove_photo']) && $_POST['remove_photo'] === '1';

        // Validation
        if ($client_name === '') {
            $errors[] = 'Client / Reviewer name is required.';
        }

        if ($review_text === '') {
            $errors[] = 'Review / Testimonial text is required.';
        }

        if ($rating < 1.0 || $rating > 5.0) {
            $errors[] = 'Rating must be between 1.0 and 5.0 stars.';
        }

        $photo_path = $current_photo;

        // Handle Photo Replacement or Removal
        if (!empty($_FILES['client_photo']['tmp_name'])) {
            $upload_res = upload_image(
                $_FILES['client_photo'],
                'reviews',
                ['image/jpeg', 'image/png', 'image/webp'],
                5242880,
                600,
                82
            );
            if ($upload_res['success']) {
                // Delete old photo if it was uploaded to uploads/
                if (!empty($current_photo) && !str_starts_with($current_photo, 'images/')) {
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
            if (!empty($current_photo) && !str_starts_with($current_photo, 'images/')) {
                $old_file = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $current_photo);
                if (file_exists($old_file)) {
                    @unlink($old_file);
                }
            }
            $photo_path = null;
        }

        // Save changes
        if (empty($errors)) {
            try {
                $sql = "
                    UPDATE reviews SET
                        client_name        = :client_name,
                        client_company     = :client_company,
                        client_designation = :client_designation,
                        client_photo       = :client_photo,
                        review_text        = :review_text,
                        rating             = :rating,
                        project_name       = :project_name,
                        source             = :source,
                        sort_order         = :sort_order,
                        status             = :status,
                        updated_at         = NOW()
                    WHERE id = :id
                ";
                $stmt = db()->prepare($sql);
                $stmt->execute([
                    ':client_name'        => $client_name,
                    ':client_company'     => $client_company ?: null,
                    ':client_designation' => $client_designation ?: null,
                    ':client_photo'       => $photo_path,
                    ':review_text'        => $review_text,
                    ':rating'             => $rating,
                    ':project_name'       => $project_name ?: null,
                    ':source'             => $source,
                    ':sort_order'         => $sort_order,
                    ':status'             => $status,
                    ':id'                 => $id
                ]);

                set_flash('success', "Review from '<strong>" . htmlspecialchars($client_name) . "</strong>' updated successfully.");
                redirect('admin/reviews');
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
                <h4 class="fw-bold mb-0 text-white">Edit Client Review</h4>
                <small class="text-muted">Editing feedback from <?= htmlspecialchars($client_name) ?></small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('admin/reviews') ?>" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Reviews
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

        <form method="POST" action="<?= site_url('admin/reviews/edit.php?id=' . $id) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Left Column: Testimonial & Review Content -->
                <div class="col-12 col-lg-8">
                    <!-- Client Details Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-user-check text-primary me-2"></i> Reviewer Information
                        </h5>

                        <div class="row g-3">
                            <!-- Client Name -->
                            <div class="col-12 col-md-6">
                                <label for="clientName" class="form-label text-white fw-semibold">
                                    Client / Reviewer Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="client_name" id="clientName" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Ehtesham Ahmed" value="<?= htmlspecialchars($client_name) ?>" required>
                            </div>

                            <!-- Company Name -->
                            <div class="col-12 col-md-6">
                                <label for="clientCompany" class="form-label text-white fw-semibold">
                                    Company / Organization <small class="text-muted fw-normal">(Optional)</small>
                                </label>
                                <input type="text" name="client_company" id="clientCompany" class="form-control bg-dark border-secondary text-white" placeholder="e.g. PRAN-RFL Group or Unilever" value="<?= htmlspecialchars($client_company) ?>">
                            </div>

                            <!-- Designation -->
                            <div class="col-12 col-md-6">
                                <label for="clientDesignation" class="form-label text-muted small fw-semibold">
                                    Job Title / Role <small class="text-muted fw-normal">(Optional)</small>
                                </label>
                                <input type="text" name="client_designation" id="clientDesignation" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Head of Marketing & Communications" value="<?= htmlspecialchars($client_designation) ?>">
                            </div>

                            <!-- Project Name -->
                            <div class="col-12 col-md-6">
                                <label for="projectName" class="form-label text-muted small fw-semibold">
                                    Project / Campaign Name <small class="text-muted fw-normal">(Optional)</small>
                                </label>
                                <input type="text" name="project_name" id="projectName" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 4K Brand Film & TVC Campaign" value="<?= htmlspecialchars($project_name) ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial Content Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-quote-left text-warning me-2"></i> Testimonial &amp; Rating
                        </h5>

                        <!-- Star Rating Selector -->
                        <div class="mb-4">
                            <label for="ratingInput" class="form-label text-white fw-semibold d-block">
                                Star Rating <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <select name="rating" id="ratingInput" class="form-select bg-dark border-secondary text-warning fw-bold" style="width: 150px;">
                                    <option value="5.0" <?= (number_format((float)$rating, 1) === '5.0') ? 'selected' : '' ?>>★★★★★ 5.0 (Perfect)</option>
                                    <option value="4.5" <?= (number_format((float)$rating, 1) === '4.5') ? 'selected' : '' ?>>★★★★½ 4.5 (Excellent)</option>
                                    <option value="4.0" <?= (number_format((float)$rating, 1) === '4.0') ? 'selected' : '' ?>>★★★★☆ 4.0 (Great)</option>
                                    <option value="3.5" <?= (number_format((float)$rating, 1) === '3.5') ? 'selected' : '' ?>>★★★½☆ 3.5 (Good)</option>
                                    <option value="3.0" <?= (number_format((float)$rating, 1) === '3.0') ? 'selected' : '' ?>>★★★☆☆ 3.0 (Average)</option>
                                </select>
                                <span class="text-muted small">Select the official rating awarded by the client.</span>
                            </div>
                        </div>

                        <!-- Review Text -->
                        <div class="mb-0">
                            <label for="reviewText" class="form-label text-white fw-semibold">
                                Review / Testimonial Text <span class="text-danger">*</span>
                            </label>
                            <textarea name="review_text" id="reviewText" rows="6" class="form-control bg-dark border-secondary text-white" placeholder="Write or paste the exact client feedback here..." required><?= htmlspecialchars($review_text) ?></textarea>
                            <div class="form-text text-muted small">This text will be showcased in client testimonials carousels and reviews pages.</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Source, Avatar & Publishing -->
                <div class="col-12 col-lg-4">
                    <!-- Platform Source Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-share-nodes text-info me-2"></i> Review Source
                        </h5>

                        <label class="form-label text-muted small fw-semibold">Platform / Channel</label>
                        <select name="source" class="form-select bg-dark border-secondary text-white mb-2">
                            <option value="google" <?= ($source === 'google') ? 'selected' : '' ?>>Google Business Reviews</option>
                            <option value="goodfirms" <?= ($source === 'goodfirms') ? 'selected' : '' ?>>GoodFirms Verified</option>
                            <option value="clutch" <?= ($source === 'clutch') ? 'selected' : '' ?>>Clutch.co Verified</option>
                            <option value="direct" <?= ($source === 'direct') ? 'selected' : '' ?>>Direct Client Feedback / Email</option>
                        </select>
                        <div class="form-text text-muted small">Displays the source platform icon alongside the review.</div>
                    </div>

                    <!-- Client Photo Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-image text-warning me-2"></i> Client Avatar / Photo
                        </h5>

                        <div class="text-center mb-3">
                            <div id="photoPreviewContainer" class="d-flex align-items-center justify-content-center mx-auto rounded-circle border border-secondary bg-dark overflow-hidden mb-2" style="width: 120px; height: 120px; position: relative;">
                                <?php if (!empty($current_photo)): ?>
                                    <img id="previewImg" src="<?= htmlspecialchars(upload_url($current_photo)) ?>" alt="Client Photo" class="w-100 h-100 object-fit-cover">
                                    <div id="previewPlaceholder" class="text-center p-2 text-muted d-none">
                                        <i class="fa-solid fa-user fa-2x mb-1 text-secondary"></i>
                                        <div style="font-size: 0.72rem;">No photo</div>
                                    </div>
                                <?php else: ?>
                                    <img id="previewImg" src="" alt="Client Photo" class="w-100 h-100 object-fit-cover d-none">
                                    <div id="previewPlaceholder" class="text-center p-2 text-muted">
                                        <i class="fa-solid fa-user fa-2x mb-1 text-secondary"></i>
                                        <div style="font-size: 0.72rem;">No photo</div>
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
                            <label for="clientPhotoFile" class="form-label text-muted small fw-semibold">
                                <?= !empty($current_photo) ? 'Replace Photo File' : 'Upload Client Photo' ?>
                            </label>
                            <input type="file" name="client_photo" id="clientPhotoFile" class="form-control bg-dark border-secondary text-white" accept=".jpg,.jpeg,.png,.webp">
                            <div class="form-text text-muted small">
                                Allowed: .jpg, .jpeg, .png, .webp (Max: 5MB). Leave empty to keep current photo.
                            </div>
                        </div>
                    </div>

                    <!-- Publishing & Sort Order Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-sliders text-success me-2"></i> Display Settings
                        </h5>

                        <div class="mb-3">
                            <label for="reviewStatus" class="form-label text-white fw-semibold">Status</label>
                            <select name="status" id="reviewStatus" class="form-select bg-dark border-secondary text-white">
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
                                Lower numbers appear first on featured carousels.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <i class="fa-solid fa-check-circle me-2"></i> Update Review
                            </button>
                            <a href="<?= site_url('admin/reviews') ?>" class="btn btn-outline-secondary py-2">
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
    const photoFileInput    = document.getElementById('clientPhotoFile');
    const previewImg        = document.getElementById('previewImg');
    const previewPlaceholder= document.getElementById('previewPlaceholder');

    if (photoFileInput && previewImg) {
        photoFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
                const hasValidExt = validExtensions.some(ext => fileName.endsWith(ext));

                if (!hasValidExt) {
                    alert('Invalid file format. Only .jpg, .jpeg, .png, and .webp files are allowed.');
                    this.value = '';
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('The selected photo is ' + (file.size / (1024 * 1024)).toFixed(1) + 'MB. Maximum allowed size is 5MB.');
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
