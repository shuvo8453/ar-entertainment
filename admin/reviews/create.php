<?php
/**
 * AR Entertainment - Add Client Review / Testimonial
 * Phase 4.6: Management CRUD (admin/reviews/create.php)
 */
require_once dirname(__DIR__) . '/auth_check.php';

$current_page = 'reviews_create';
$page_title   = 'Add Client Review';

$client_name        = '';
$client_company     = '';
$client_designation = '';
$rating             = '5.0';
$review_text        = '';
$project_name       = '';
$source             = 'google';
$sort_order         = 0;
$status             = 'active';
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

        // Photo upload handling (optional)
        $photo_path = null;
        if (!empty($_FILES['client_photo']['tmp_name'])) {
            $upload_res = upload_image(
                $_FILES['client_photo'],
                'reviews',
                ['image/jpeg', 'image/pjpeg', 'image/jfif', 'image/png', 'image/webp', 'image/avif'],
                5242880,
                600,
                82
            );
            if ($upload_res['success']) {
                $photo_path = $upload_res['path'];
            } else {
                $errors[] = 'Photo upload failed: ' . $upload_res['error'];
            }
        }

        // Insert into Database
        if (empty($errors)) {
            try {
                $sql = "
                    INSERT INTO reviews (
                        client_name,
                        client_company,
                        client_designation,
                        client_photo,
                        review_text,
                        rating,
                        project_name,
                        source,
                        sort_order,
                        status,
                        created_at,
                        updated_at
                    ) VALUES (
                        :client_name,
                        :client_company,
                        :client_designation,
                        :client_photo,
                        :review_text,
                        :rating,
                        :project_name,
                        :source,
                        :sort_order,
                        :status,
                        NOW(),
                        NOW()
                    )
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
                    ':status'             => $status
                ]);

                set_flash('success', "Review from '<strong>" . htmlspecialchars($client_name) . "</strong>' added successfully.");
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
    <?php require_once ADMIN_PATH . '/includes/navbar.php'; ?>

    <main class="admin-content">
        <!-- Breadcrumb & Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/reviews') ?>" class="text-muted text-decoration-none">Client Reviews</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Add Review</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Add Client Review</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/reviews') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Reviews
                </a>
            </div>
        </div>

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

        <form method="POST" action="<?= site_url('admin/reviews/create.php') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Left Column: Primary Feedback Content -->
                <div class="col-12 col-lg-8">
                    <!-- Feedback Text Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-comment-dots text-primary me-2"></i> Client Testimonial
                        </h5>

                        <!-- Review Text -->
                        <div class="mb-4">
                            <label for="reviewText" class="form-label text-white fw-semibold">
                                Review / Testimonial Text <span class="text-danger">*</span>
                            </label>
                            <textarea name="review_text" id="reviewText" rows="5" class="form-control bg-dark border-secondary text-white" placeholder="Write or paste client feedback..." required><?= htmlspecialchars($review_text) ?></textarea>
                        </div>

                        <!-- Rating Selector -->
                        <div class="mb-4">
                            <label class="form-label text-white fw-semibold d-block">
                                Star Rating <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <select name="rating" id="ratingSelect" class="form-select bg-dark border-secondary text-white" style="max-width: 140px;">
                                    <option value="5.0" <?= ($rating == 5.0) ? 'selected' : '' ?>>5.0 Stars ★★★★★</option>
                                    <option value="4.5" <?= ($rating == 4.5) ? 'selected' : '' ?>>4.5 Stars ★★★★½</option>
                                    <option value="4.0" <?= ($rating == 4.0) ? 'selected' : '' ?>>4.0 Stars ★★★★☆</option>
                                    <option value="3.5" <?= ($rating == 3.5) ? 'selected' : '' ?>>3.5 Stars ★★★½☆</option>
                                    <option value="3.0" <?= ($rating == 3.0) ? 'selected' : '' ?>>3.0 Stars ★★★☆☆</option>
                                </select>
                                <span class="text-warning fs-5" id="starsLivePreview">★★★★★</span>
                            </div>
                        </div>

                        <!-- Associated Project Name -->
                        <div class="mb-0">
                            <label for="projectName" class="form-label text-white fw-semibold">
                                Associated Video / Project Title <small class="text-muted fw-normal">(Optional)</small>
                            </label>
                            <input type="text" name="project_name" id="projectName" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Apex Eid TV Commercial or BBC Line Production" value="<?= htmlspecialchars($project_name) ?>">
                            <div class="form-text text-muted small">Associating a project creates an authentic context badge.</div>
                        </div>
                    </div>

                    <!-- Client Details Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-user-tie text-info me-2"></i> Client Information
                        </h5>

                        <div class="row g-3">
                            <!-- Client Name -->
                            <div class="col-12 col-md-6">
                                <label for="clientName" class="form-label text-white fw-semibold">
                                    Client / Reviewer Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="client_name" id="clientName" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Tanvir Ahmed" value="<?= htmlspecialchars($client_name) ?>" required>
                            </div>

                            <!-- Company Name -->
                            <div class="col-12 col-md-6">
                                <label for="clientCompany" class="form-label text-white fw-semibold">
                                    Company / Organization
                                </label>
                                <input type="text" name="client_company" id="clientCompany" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Apex Footwear Ltd." value="<?= htmlspecialchars($client_company) ?>">
                            </div>

                            <!-- Designation / Title -->
                            <div class="col-12">
                                <label for="clientDesignation" class="form-label text-white fw-semibold">
                                    Designation / Job Title
                                </label>
                                <input type="text" name="client_designation" id="clientDesignation" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Head of Marketing & Communications" value="<?= htmlspecialchars($client_designation) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Avatar Uploader & Source Settings -->
                <div class="col-12 col-lg-4">
                    <!-- Client Photo Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-image text-warning me-2"></i> Client Avatar / Photo <small class="text-muted fw-normal">(Optional)</small>
                        </h5>

                        <div class="text-center mb-3">
                            <div id="avatarPreviewContainer" class="d-flex align-items-center justify-content-center mx-auto rounded-circle border border-secondary p-1 mb-2 overflow-hidden" style="width: 100px; height: 100px; background-color: #1a1a24;">
                                <img id="previewImg" src="" alt="Avatar Preview" class="d-none w-100 h-100 object-fit-cover">
                                <div id="previewPlaceholder" class="text-center text-muted">
                                    <i class="fa-solid fa-user fa-2x text-secondary"></i>
                                </div>
                            </div>
                            <button type="button" id="btnRemoveImage" class="btn btn-outline-danger btn-sm d-none">
                                <i class="fa-solid fa-trash me-1"></i> Remove Photo
                            </button>
                        </div>

                        <div>
                            <label for="photoFile" class="form-label text-muted small fw-semibold">Upload Photo</label>
                            <input type="file" name="client_photo" id="photoFile" class="form-control bg-dark border-secondary text-white" accept=".png,.webp,.avif,.jpg,.jpeg,.jfif">
                            <div class="form-text text-muted small">
                                PNG, WebP, JPG, JPEG, JFIF, AVIF. Square crop recommended.
                            </div>
                        </div>
                    </div>

                    <!-- Source Platform & Settings Card -->
                    <div class="card-ar mb-4">
                        <h5 class="fw-bold text-white mb-3">
                            <i class="fa-solid fa-sliders text-success me-2"></i> Source &amp; Visibility
                        </h5>

                        <!-- Review Source -->
                        <div class="mb-3">
                            <label for="reviewSource" class="form-label text-white fw-semibold">Verification Platform</label>
                            <select name="source" id="reviewSource" class="form-select bg-dark border-secondary text-white">
                                <option value="google" <?= ($source === 'google') ? 'selected' : '' ?>>Google Reviews</option>
                                <option value="goodfirms" <?= ($source === 'goodfirms') ? 'selected' : '' ?>>GoodFirms</option>
                                <option value="clutch" <?= ($source === 'clutch') ? 'selected' : '' ?>>Clutch.co</option>
                                <option value="direct" <?= ($source === 'direct') ? 'selected' : '' ?>>Direct Client Feedback</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="reviewStatus" class="form-label text-white fw-semibold">Status</label>
                            <select name="status" id="reviewStatus" class="form-select bg-dark border-secondary text-white">
                                <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active (Published)</option>
                                <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive (Hidden / Draft)</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="mb-4">
                            <label for="sortOrder" class="form-label text-white fw-semibold">Sort Order Priority</label>
                            <input type="number" name="sort_order" id="sortOrder" class="form-control bg-dark border-secondary text-white" value="<?= (int) $sort_order ?>" min="0" step="1">
                            <div class="form-text text-muted small">
                                Lower numbers appear first on testimonials carousels.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-ar-primary py-2 fw-bold">
                                <i class="fa-solid fa-check-circle me-2"></i> Save Client Review
                            </button>
                            <a href="<?= site_url('admin/reviews') ?>" class="btn btn-ar-secondary py-2 text-center">
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
    const ratingSelect      = document.getElementById('ratingSelect');
    const starsLivePreview  = document.getElementById('starsLivePreview');
    const photoFileInput    = document.getElementById('photoFile');
    const previewImg        = document.getElementById('previewImg');
    const previewPlaceholder= document.getElementById('previewPlaceholder');
    const btnRemoveImage    = document.getElementById('btnRemoveImage');

    if (ratingSelect && starsLivePreview) {
        const starMap = {
            '5.0': '★★★★★',
            '4.5': '★★★★½',
            '4.0': '★★★★☆',
            '3.5': '★★★½☆',
            '3.0': '★★★☆☆'
        };
        ratingSelect.addEventListener('change', function() {
            starsLivePreview.textContent = starMap[this.value] || '★★★★★';
        });
    }

    if (photoFileInput && previewImg) {
        photoFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtensions = ['.png', '.webp', '.jpg', '.jpeg'];
                const hasValidExt = validExtensions.some(ext => fileName.endsWith(ext));

                if (!hasValidExt) {
                    alert('Invalid file format. Allowed formats: .png, .webp, .jpg, .jpeg');
                    this.value = '';
                    previewImg.src = '';
                    previewImg.classList.add('d-none');
                    previewPlaceholder.classList.remove('d-none');
                    btnRemoveImage.classList.add('d-none');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('File size exceeds 5MB limit.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImg.src = evt.target.result;
                    previewImg.classList.remove('d-none');
                    previewPlaceholder.classList.add('d-none');
                    btnRemoveImage.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        btnRemoveImage.addEventListener('click', function() {
            photoFileInput.value = '';
            previewImg.src = '';
            previewImg.classList.add('d-none');
            previewPlaceholder.classList.remove('d-none');
            btnRemoveImage.classList.add('d-none');
        });
    }
});
</script>
