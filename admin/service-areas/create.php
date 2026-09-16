<?php

/**
 * AR Entertainment - Add District Filming Location Guide
 * 
 * 64 District presets, TinyMCE Guide Editor, SEO Suite & Google SERP Preview.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Add District Guide';
$current_page = 'service-areas_create';

// Form defaults
$city_name        = '';
$title            = '';
$slug             = '';
$summary          = '';
$content          = '';
$sort_order       = 0;
$status           = 'active';
$meta_title       = '';
$meta_description = '';

$errors = [];

// Bangladesh 64 Districts List
$bangladesh_districts = [
    'Bagerhat', 'Bandarban', 'Barguna', 'Barisal', 'Bhola', 'Bogra', 'Brahmanbaria', 'Chandpur',
    'Chapai Nawabganj', 'Chittagong', 'Chuadanga', 'Comilla', "Cox's Bazar", 'Dhaka', 'Dinajpur',
    'Faridpur', 'Feni', 'Gaibandha', 'Gazipur', 'Gopalganj', 'Habiganj', 'Jamalpur', 'Jessore',
    'Jhalokati', 'Jhenaidah', 'Joypurhat', 'Khagrachhari', 'Khulna', 'Kishoreganj', 'Kurigram',
    'Kushtia', 'Lakshmipur', 'Lalmonirhat', 'Madaripur', 'Magura', 'Manikganj', 'Meherpur',
    'Moulvibazar', 'Munshiganj', 'Mymensingh', 'Naogaon', 'Narail', 'Narayanganj', 'Narsingdi',
    'Natore', 'Netrokona', 'Nilphamari', 'Noakhali', 'Pabna', 'Panchagarh', 'Patuakhali',
    'Pirojpur', 'Rajbari', 'Rajshahi', 'Rangamati', 'Rangpur', 'Satkhira', 'Shariatpur',
    'Sherpur', 'Sirajganj', 'Sunamganj', 'Sylhet', 'Tangail', 'Thakurgaon'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security verification failed (CSRF token expired). Please try again.';
    } else {
        $city_name        = trim($_POST['city_name'] ?? '');
        $title            = trim($_POST['title'] ?? '');
        $slug             = trim($_POST['slug'] ?? '');
        $summary          = trim($_POST['summary'] ?? '');
        $content          = trim($_POST['content'] ?? '');
        $sort_order       = (int) ($_POST['sort_order'] ?? 0);
        $status           = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';
        $meta_title       = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');

        // Validation
        if ($city_name === '') {
            $errors[] = 'District / City name is required.';
        }

        if ($title === '') {
            $title = "Film Production & Location Fixer Services in {$city_name}";
        }

        if ($slug === '') {
            $slug = slugify($city_name);
        } else {
            $slug = slugify($slug);
        }

        // Ensure unique slug
        try {
            $check_slug = db()->prepare("SELECT COUNT(*) FROM service_areas WHERE slug = ?");
            $check_slug->execute([$slug]);
            if ($check_slug->fetchColumn() > 0) {
                $slug = $slug . '-' . time();
            }
        } catch (PDOException $e) {
            $errors[] = 'Database check failed: ' . $e->getMessage();
        }

        if ($meta_title === '') {
            $meta_title = "Film Production & Fixer in {$city_name}, Bangladesh — " . SITE_NAME;
        }

        if ($meta_description === '') {
            $meta_description = !empty($summary) ? $summary : truncate_text($content, 155);
        }

        if (empty($errors)) {
            try {
                $sql = "
                    INSERT INTO service_areas (
                        title, slug, city_name, summary, content,
                        sort_order, status, meta_title, meta_description,
                        created_at, updated_at
                    ) VALUES (
                        :title, :slug, :city_name, :summary, :content,
                        :sort_order, :status, :meta_title, :meta_description,
                        NOW(), NOW()
                    )
                ";
                $stmt = db()->prepare($sql);
                $stmt->execute([
                    ':title'            => $title,
                    ':slug'             => $slug,
                    ':city_name'        => $city_name,
                    ':summary'          => $summary,
                    ':content'          => $content,
                    ':sort_order'       => $sort_order,
                    ':status'           => $status,
                    ':meta_title'       => $meta_title,
                    ':meta_description' => $meta_description
                ]);

                set_flash('success', "District guide for '<strong>" . htmlspecialchars($city_name) . "</strong>' added successfully!");
                redirect('admin/service-areas');
            } catch (PDOException $e) {
                $errors[] = 'Failed to create district guide: ' . $e->getMessage();
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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/service-areas') ?>" class="text-muted text-decoration-none">Service Areas</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">New District Guide</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Create District Location Guide</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/service-areas') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Districts
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

        <form method="POST" action="<?= site_url('admin/service-areas/create') ?>" id="areaForm">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Main Content (Left) -->
                <div class="col-12 col-lg-8">
                    <!-- District & Title Card -->
                    <div class="card-ar mb-4">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-5">
                                <label for="cityName" class="form-label text-white fw-semibold">District Name <span class="text-danger">*</span></label>
                                <input type="text" name="city_name" id="cityName" list="districtList" class="form-control form-control-lg bg-dark border-secondary text-white" placeholder="e.g. Cox's Bazar" value="<?= htmlspecialchars($city_name) ?>" required autofocus>
                                <datalist id="districtList">
                                    <?php foreach ($bangladesh_districts as $d): ?>
                                        <option value="<?= htmlspecialchars($d) ?>"></option>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="col-12 col-md-7">
                                <label for="areaTitle" class="form-label text-white fw-semibold">Guide Headline / Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="areaTitle" class="form-control form-control-lg bg-dark border-secondary text-white" placeholder="e.g. Film Production &amp; Fixer Services in Cox's Bazar" value="<?= htmlspecialchars($title) ?>" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="areaSlug" class="form-label text-muted small fw-semibold">
                                Permalink URL Slug
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-dark border-secondary text-muted font-monospace"><?= SITE_DOMAIN ?>/service-area/</span>
                                <input type="text" name="slug" id="areaSlug" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="district-slug" value="<?= htmlspecialchars($slug) ?>">
                                <button class="btn btn-ar-secondary" type="button" id="btnUnlockSlug" title="Edit custom slug">
                                    <i class="fa-solid fa-lock" id="slugLockIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card-ar mb-4">
                        <label for="areaSummary" class="form-label text-white fw-semibold d-flex justify-content-between">
                            <span>Summary / District Highlights</span>
                            <span class="text-muted small" id="summaryCharCount">0 / 250</span>
                        </label>
                        <textarea name="summary" id="areaSummary" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Overview of filming in this district, scenic landmarks, landscape variety, and AR Entertainment's local capabilities..."><?= htmlspecialchars($summary) ?></textarea>
                    </div>

                    <!-- TinyMCE Rich Content Card -->
                    <div class="card-ar mb-4">
                        <label class="form-label text-white fw-semibold mb-2">Filming Location Guide &amp; Logistics Details</label>
                        <textarea name="content" id="editorContent" rows="16"><?= htmlspecialchars($content) ?></textarea>
                    </div>

                    <!-- SEO & SERP Meta Card -->
                    <div class="card-ar mb-4">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <h5 class="fw-bold mb-0 text-white">
                                <i class="fa-solid fa-chart-line text-danger me-2"></i> Search Engine Optimization (SEO)
                            </h5>
                            <span class="badge bg-dark border border-secondary text-muted">Google Preview</span>
                        </div>

                        <!-- Google Live SERP Preview -->
                        <div class="p-3 mb-4 rounded" style="background-color: #0c0d14; border: 1px dashed #2e3247;">
                            <div class="small text-muted mb-2 text-uppercase fw-bold" style="font-size: 11px;">Google Search Result Preview</div>
                            <div class="text-primary text-truncate fw-semibold fs-5" id="serpTitle" style="color: #8ab4f8 !important;">
                                <?= htmlspecialchars($meta_title ?: 'Film Production & Fixer in District — ' . SITE_NAME) ?>
                            </div>
                            <div class="text-success small font-monospace my-1" id="serpUrl" style="color: #81c995 !important;">
                                https://www.<?= SITE_DOMAIN ?> &rsaquo; service-area &rsaquo; <span id="serpSlug"><?= htmlspecialchars($slug ?: 'district-slug') ?></span>
                            </div>
                            <div class="text-muted small" id="serpDescription" style="color: #bdc1c6 !important; line-height: 1.4;">
                                <?= htmlspecialchars($meta_description ?: 'Comprehensive film fixing, location management, shooting permits, and production crew in Bangladesh.') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="metaTitle" class="form-label text-white fw-semibold d-flex justify-content-between">
                                <span>SEO Meta Title</span>
                                <span class="small text-muted" id="metaTitleCount">0 / 60 chars</span>
                            </label>
                            <input type="text" name="meta_title" id="metaTitle" class="form-control bg-dark border-secondary text-white" placeholder="Optimal: 50-60 characters" value="<?= htmlspecialchars($meta_title) ?>">
                        </div>

                        <div class="mb-0">
                            <label for="metaDescription" class="form-label text-white fw-semibold d-flex justify-content-between">
                                <span>SEO Meta Description</span>
                                <span class="small text-muted" id="metaDescCount">0 / 160 chars</span>
                            </label>
                            <textarea name="meta_description" id="metaDescription" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="Optimal: 140-160 characters summary for search engine snippet"><?= htmlspecialchars($meta_description) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Settings Sidebar (Right) -->
                <div class="col-12 col-lg-4">
                    <!-- Status & Order Card -->
                    <div class="card-ar mb-4">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-paper-plane text-danger me-2"></i> Guide Status
                        </h6>

                        <div class="mb-3">
                            <label for="areaStatus" class="form-label text-white fw-semibold">Status</label>
                            <select name="status" id="areaStatus" class="form-select bg-dark border-secondary text-white">
                                <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active (Visible on Map &amp; Search)</option>
                                <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive (Hidden)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sortOrder" class="form-label text-white fw-semibold">Sort Order Index</label>
                            <input type="number" name="sort_order" id="sortOrder" class="form-control bg-dark border-secondary text-white" value="<?= (int) $sort_order ?>">
                            <div class="form-text text-muted small">Alphabetical priority by default.</div>
                        </div>

                        <div class="d-grid gap-2 pt-2 border-top" style="border-color: var(--ar-border-color) !important;">
                            <button type="submit" class="btn btn-ar-primary btn-lg">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save District Guide
                            </button>
                            <a href="<?= site_url('admin/service-areas') ?>" class="btn btn-ar-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>

                    <!-- Location Guide Tips Card -->
                    <div class="card-ar mb-4" style="background-color: #12131d;">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-lightbulb text-warning me-2"></i> Content Best Practices
                        </h6>
                        <ul class="text-muted small ps-3 mb-0" style="line-height: 1.6;">
                            <li class="mb-2"><strong class="text-white">Scenic Spots:</strong> Detail key landscapes, heritage spots, rivers, and urban areas.</li>
                            <li class="mb-2"><strong class="text-white">Filming Permits:</strong> Highlight local authority coordination &amp; drone guidelines.</li>
                            <li class="mb-2"><strong class="text-white">Logistics:</strong> Proximity to airports, hotels, equipment transport, and local fixers.</li>
                            <li><strong class="text-white">Target SEO:</strong> Rank for <em>"Filming in [District]"</em> and <em>"Video Production in [District]"</em>.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- TinyMCE 6 Script -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
    <script>
        // Initialize TinyMCE 6 WYSIWYG
        tinymce.init({
            selector: '#editorContent',
            height: 440,
            skin: 'oxide-dark',
            content_css: 'dark',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat code fullscreen',
            images_upload_url: '<?= site_url('admin/blogs/upload_editor_image') ?>',
            automatic_uploads: true
        });

        // Auto-generation logic for District Name
        const cityInput = document.getElementById('cityName');
        const titleInput = document.getElementById('areaTitle');
        const slugInput = document.getElementById('areaSlug');
        const btnUnlock = document.getElementById('btnUnlockSlug');
        const lockIcon = document.getElementById('slugLockIcon');
        let slugIsLocked = true;
        let titleIsAuto = true;

        function slugifyJs(text) {
            return text.toString().toLowerCase()
                .trim()
                .replace(/[\s\W-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        cityInput.addEventListener('input', function() {
            const district = this.value.trim();
            if (district) {
                if (titleIsAuto) {
                    titleInput.value = `Film Production & Location Fixer Services in ${district}`;
                }
                if (slugIsLocked) {
                    slugInput.value = slugifyJs(district);
                }
            }
            updateSerp();
        });

        titleInput.addEventListener('input', function() {
            titleIsAuto = false;
            updateSerp();
        });

        btnUnlock.addEventListener('click', function() {
            slugIsLocked = !slugIsLocked;
            if (slugIsLocked) {
                lockIcon.className = 'fa-solid fa-lock';
                btnUnlock.classList.remove('btn-danger');
                btnUnlock.classList.add('btn-ar-secondary');
            } else {
                lockIcon.className = 'fa-solid fa-lock-open';
                btnUnlock.classList.add('btn-danger');
                btnUnlock.classList.remove('btn-ar-secondary');
                slugInput.focus();
            }
        });

        // Live SERP Preview & Counters
        const metaTitleInput = document.getElementById('metaTitle');
        const metaDescInput = document.getElementById('metaDescription');
        const areaSummaryInput = document.getElementById('areaSummary');
        const serpTitle = document.getElementById('serpTitle');
        const serpSlug = document.getElementById('serpSlug');
        const serpDesc = document.getElementById('serpDescription');
        const metaTitleCount = document.getElementById('metaTitleCount');
        const metaDescCount = document.getElementById('metaDescCount');
        const summaryCharCount = document.getElementById('summaryCharCount');

        function updateSerp() {
            const district = cityInput.value.trim();
            const currentTitle = metaTitleInput.value.trim() || titleInput.value.trim() || (district ? `Film Production in ${district} — AR Entertainment` : 'District Filming Guide — AR Entertainment');
            serpTitle.textContent = currentTitle;
            metaTitleCount.textContent = `${metaTitleInput.value.length} / 60 chars`;
            metaTitleCount.className = metaTitleInput.value.length > 60 ? 'small text-warning' : 'small text-muted';

            const currentSlug = slugInput.value.trim() || (district ? slugifyJs(district) : 'district-slug');
            serpSlug.textContent = currentSlug;

            const currentDesc = metaDescInput.value.trim() || areaSummaryInput.value.trim() || 'Comprehensive film fixing, location management, shooting permits, and production crew in Bangladesh.';
            serpDesc.textContent = currentDesc;
            metaDescCount.textContent = `${metaDescInput.value.length} / 160 chars`;
            metaDescCount.className = metaDescInput.value.length > 160 ? 'small text-warning' : 'small text-muted';

            summaryCharCount.textContent = `${areaSummaryInput.value.length} / 250`;
        }

        slugInput.addEventListener('input', updateSerp);
        metaTitleInput.addEventListener('input', updateSerp);
        metaDescInput.addEventListener('input', updateSerp);
        areaSummaryInput.addEventListener('input', updateSerp);

        // Initial update
        updateSerp();
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
