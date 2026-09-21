<?php

/**
 * AR Entertainment - Edit Service Offering
 * 
 * Edit existing service, Icon Selector, TinyMCE Editor,
 * Dynamic Structured FAQ Builder, and SEO Suite.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$service_id = (int) ($_GET['id'] ?? 0);

if ($service_id <= 0) {
    set_flash('error', 'Invalid service ID provided.');
    redirect('admin/services');
}

// Fetch existing service
try {
    $stmt = db()->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();

    if (!$service) {
        set_flash('error', 'Service not found or has been deleted.');
        redirect('admin/services');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database query failed: ' . $e->getMessage());
    redirect('admin/services');
}

$page_title = 'Edit: ' . truncate_text($service['title'], 35);
$current_page = 'services_edit';

// Form Data defaults
$title            = $service['title'];
$slug             = $service['slug'];
$icon             = $service['icon'] ?: 'fa-solid fa-video';
$short_summary    = $service['short_summary'] ?? '';
$content          = $service['content'] ?? '';
$pricing_note     = $service['pricing_note'] ?? '';
$sort_order       = (int) ($service['sort_order'] ?? 0);
$status           = $service['status'] ?? 'active';
$meta_title       = $service['meta_title'] ?? '';
$meta_description = $service['meta_description'] ?? '';
$faqs_json        = $service['faqs_json'] ?? '[]';

// Decode existing FAQs
$existing_faqs = [];
if (!empty($faqs_json)) {
    $decoded = json_decode($faqs_json, true);
    if (is_array($decoded)) {
        $existing_faqs = $decoded;
    }
}

$errors = [];

// Preset Popular Icons
$popular_icons = [
    'fa-solid fa-video'                 => 'Video Camera',
    'fa-solid fa-film'                  => 'Film Strip',
    'fa-solid fa-clapperboard'          => 'Clapperboard',
    'fa-solid fa-wand-magic-sparkles'   => 'AI VFX / Magic',
    'fa-solid fa-robot'                 => 'AI / Automation',
    'fa-solid fa-tv'                    => 'Television / TVC',
    'fa-solid fa-bullhorn'              => 'Marketing / Promo',
    'fa-solid fa-microphone-lines'      => 'Audio / Jingle',
    'fa-solid fa-location-dot'          => 'Film Fixer / Location',
    'fa-solid fa-camera'                => 'Photography',
    'fa-solid fa-chart-line'            => 'Strategy / Planning',
    'fa-solid fa-building'              => 'Corporate AV'
];

// Handle Update Submission
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security verification failed (CSRF token expired). Please try again.';
    } else {
        $title            = trim($_POST['title'] ?? '');
        $slug             = trim($_POST['slug'] ?? '');
        $icon             = trim($_POST['icon'] ?? 'fa-solid fa-video');
        $short_summary    = trim($_POST['short_summary'] ?? '');
        $content          = trim($_POST['content'] ?? '');
        $pricing_note     = trim($_POST['pricing_note'] ?? '');
        $sort_order       = (int) ($_POST['sort_order'] ?? 0);
        $status           = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';
        $meta_title       = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');

        // Parse FAQs
        $faq_questions = $_POST['faq_q'] ?? [];
        $faq_answers   = $_POST['faq_a'] ?? [];
        $faqs_array    = [];

        if (is_array($faq_questions) && is_array($faq_answers)) {
            for ($i = 0; $i < count($faq_questions); $i++) {
                $q = trim($faq_questions[$i] ?? '');
                $a = trim($faq_answers[$i] ?? '');
                if ($q !== '' && $a !== '') {
                    $faqs_array[] = [
                        'q' => $q,
                        'a' => $a
                    ];
                }
            }
        }
        $faqs_json = !empty($faqs_array) ? json_encode($faqs_array, JSON_UNESCAPED_UNICODE) : null;
        $existing_faqs = $faqs_array;

        if ($title === '') {
            $errors[] = 'Service title is required.';
        }

        if ($slug === '') {
            $slug = slugify($title);
        } else {
            $slug = slugify($slug);
        }

        // Ensure unique slug (excluding current)
        try {
            $check_slug = db()->prepare("SELECT COUNT(*) FROM services WHERE slug = ? AND id != ?");
            $check_slug->execute([$slug, $service_id]);
            if ($check_slug->fetchColumn() > 0) {
                $slug = $slug . '-' . time();
            }
        } catch (PDOException $e) {
            $errors[] = 'Database check failed: ' . $e->getMessage();
        }

        if ($meta_title === '') {
            $meta_title = $title . ' — ' . SITE_NAME;
        }

        if ($meta_description === '') {
            $meta_description = !empty($short_summary) ? $short_summary : truncate_text($content, 155);
        }

        if (empty($errors)) {
            try {
                $sql = "
                    UPDATE services SET
                        title            = :title,
                        slug             = :slug,
                        icon             = :icon,
                        short_summary    = :short_summary,
                        content          = :content,
                        pricing_note     = :pricing_note,
                        faqs_json        = :faqs_json,
                        sort_order       = :sort_order,
                        status           = :status,
                        meta_title       = :meta_title,
                        meta_description = :meta_description,
                        updated_at       = NOW()
                    WHERE id = :id
                ";
                $stmt = db()->prepare($sql);
                $stmt->execute([
                    ':title'            => $title,
                    ':slug'             => $slug,
                    ':icon'             => $icon,
                    ':short_summary'    => $short_summary,
                    ':content'          => $content,
                    ':pricing_note'     => $pricing_note,
                    ':faqs_json'        => $faqs_json,
                    ':sort_order'       => $sort_order,
                    ':status'           => $status,
                    ':meta_title'       => $meta_title,
                    ':meta_description' => $meta_description,
                    ':id'               => $service_id
                ]);

                set_flash('success', "Service '<strong>" . htmlspecialchars($title) . "</strong>' updated successfully!");
                redirect('admin/services');
            } catch (PDOException $e) {
                $errors[] = 'Failed to update service: ' . $e->getMessage();
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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/services') ?>" class="text-muted text-decoration-none">Services</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Edit #<?= $service_id ?></li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Edit Service</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= site_url('services/' . urlencode($slug)) ?>" target="_blank" class="btn btn-ar-secondary text-info">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Preview Live
                </a>
                <a href="<?= site_url('admin/services') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Services
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

        <form method="POST" action="<?= site_url('admin/services/edit?id=' . $service_id) ?>" id="serviceForm">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Main Content (Left) -->
                <div class="col-12 col-lg-8">
                    <!-- Title & Slug Card -->
                    <div class="card-ar mb-4">
                        <div class="mb-3">
                            <label for="serviceTitle" class="form-label text-white fw-semibold">Service Name <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="serviceTitle" class="form-control form-control-lg bg-dark border-secondary text-white" placeholder="e.g. TV Commercial Production" value="<?= htmlspecialchars($title) ?>" required autofocus>
                        </div>

                        <div class="mb-0">
                            <label for="serviceSlug" class="form-label text-muted small fw-semibold">
                                Permalink URL Slug
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-dark border-secondary text-muted font-monospace"><?= SITE_DOMAIN ?>/services/</span>
                                <input type="text" name="slug" id="serviceSlug" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="service-slug" value="<?= htmlspecialchars($slug) ?>">
                                <button class="btn btn-ar-secondary" type="button" id="btnUnlockSlug" title="Edit custom slug">
                                    <i class="fa-solid fa-lock" id="slugLockIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Short Summary Card -->
                    <div class="card-ar mb-4">
                        <label for="shortSummary" class="form-label text-white fw-semibold d-flex justify-content-between">
                            <span>Short Summary (For Catalogue Cards)</span>
                            <span class="text-muted small" id="summaryCharCount">0 / 250</span>
                        </label>
                        <textarea name="short_summary" id="shortSummary" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="A concise 2-sentence description of this service to appear on service cards..."><?= htmlspecialchars($short_summary) ?></textarea>
                    </div>

                    <!-- TinyMCE Rich Content Card -->
                    <div class="card-ar mb-4">
                        <label class="form-label text-white fw-semibold mb-2">Service Description &amp; Scope of Work</label>
                        <textarea name="content" id="editorContent" rows="15"><?= htmlspecialchars($content) ?></textarea>
                    </div>

                    <!-- Structured FAQs Builder Card -->
                    <div class="card-ar mb-4">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <div>
                                <h5 class="fw-bold mb-0 text-white">
                                    <i class="fa-solid fa-circle-question text-warning me-2"></i> Structured FAQ Builder
                                </h5>
                                <p class="text-muted small mb-0">Add frequently asked questions. These power interactive accordions and Google FAQ Schema!</p>
                            </div>
                            <button type="button" class="btn btn-ar-secondary btn-sm" id="btnAddFaq">
                                <i class="fa-solid fa-plus me-1"></i> Add FAQ
                            </button>
                        </div>

                        <div id="faqContainer">
                            <!-- Dynamic FAQ Items -->
                        </div>

                        <div id="emptyFaqNotice" class="text-center py-4 text-muted <?= !empty($existing_faqs) ? 'd-none' : '' ?>">
                            <i class="fa-regular fa-comments fs-2 text-secondary opacity-50 mb-2"></i>
                            <p class="mb-0 small">No FAQs added yet. Click <strong>"Add FAQ"</strong> above to attach structured questions &amp; answers.</p>
                        </div>
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
                                <?= htmlspecialchars($meta_title ?: ($title ?: 'Service Title — ' . SITE_NAME)) ?>
                            </div>
                            <div class="text-success small font-monospace my-1" id="serpUrl" style="color: #81c995 !important;">
                                https://www.<?= SITE_DOMAIN ?> &rsaquo; services &rsaquo; <span id="serpSlug"><?= htmlspecialchars($slug ?: 'service-slug') ?></span>
                            </div>
                            <div class="text-muted small" id="serpDescription" style="color: #bdc1c6 !important; line-height: 1.4;">
                                <?= htmlspecialchars($meta_description ?: 'Professional video production, film fixing, and commercial maker services in Bangladesh.') ?>
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
                    <!-- Publish Controls Card -->
                    <div class="card-ar mb-4">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-paper-plane text-danger me-2"></i> Update Status
                        </h6>

                        <div class="mb-3">
                            <label for="serviceStatus" class="form-label text-white fw-semibold">Visibility Status</label>
                            <select name="status" id="serviceStatus" class="form-select bg-dark border-secondary text-white">
                                <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active (Visible on Website)</option>
                                <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive (Hidden)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="pricingNote" class="form-label text-white fw-semibold">Pricing / Budget Note</label>
                            <input type="text" name="pricing_note" id="pricingNote" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Starting from $800 or Custom Quote" value="<?= htmlspecialchars($pricing_note) ?>">
                            <div class="form-text text-muted small">Optional pricing badge displayed on service header.</div>
                        </div>

                        <div class="mb-3">
                            <label for="sortOrder" class="form-label text-white fw-semibold">Sort Order Index</label>
                            <input type="number" name="sort_order" id="sortOrder" class="form-control bg-dark border-secondary text-white" value="<?= (int) $sort_order ?>">
                            <div class="form-text text-muted small">Lower numbers appear first on the services page.</div>
                        </div>

                        <div class="d-grid gap-2 pt-2 border-top" style="border-color: var(--ar-border-color) !important;">
                            <button type="submit" class="btn btn-ar-primary btn-lg">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                            </button>
                            <a href="<?= site_url('admin/services') ?>" class="btn btn-ar-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>

                    <!-- Icon Selector Card -->
                    <div class="card-ar mb-4">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-icons text-danger me-2"></i> Service Icon
                        </h6>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Choose a Pre-set Icon:</label>
                            <div class="row g-2" id="iconGrid">
                                <?php foreach ($popular_icons as $ico => $label): ?>
                                    <div class="col-4 text-center">
                                        <div class="icon-choice p-2 rounded border border-secondary text-center cursor-pointer <?= ($icon === $ico) ? 'active-icon' : '' ?>" data-icon="<?= $ico ?>" style="cursor: pointer; background: #14151f;">
                                            <i class="<?= $ico ?> fs-4 mb-1"></i>
                                            <div style="font-size: 10px;" class="text-truncate text-muted"><?= $label ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="customIcon" class="form-label text-white fw-semibold">Selected Icon Class:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-white" id="iconPreview">
                                    <i class="<?= htmlspecialchars($icon) ?>"></i>
                                </span>
                                <input type="text" name="icon" id="customIcon" class="form-control bg-dark border-secondary text-white font-monospace" value="<?= htmlspecialchars($icon) ?>" placeholder="fa-solid fa-video">
                            </div>
                            <div class="form-text text-muted small">You can paste any FontAwesome 6 icon class.</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <style>
        .icon-choice:hover {
            border-color: var(--ar-primary) !important;
            color: #fff;
        }

        .icon-choice.active-icon {
            border-color: var(--ar-primary) !important;
            background: rgba(229, 9, 20, 0.15) !important;
            color: #fff;
        }

        .faq-item-card {
            background-color: #12131d;
            border: 1px solid var(--ar-border-color);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            transition: border-color 0.2s;
        }

        .faq-item-card:hover {
            border-color: #3b3e58;
        }
    </style>

    <!-- TinyMCE 6 Script -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
    <script>
        // Initialize TinyMCE 6 WYSIWYG
        tinymce.init({
            selector: '#editorContent',
            height: 420,
            skin: 'oxide-dark',
            content_css: 'dark',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat code fullscreen',
            images_upload_url: '<?= site_url('admin/blogs/upload_editor_image') ?>',
            automatic_uploads: true
        });

        // Slug lock controls
        const slugInput = document.getElementById('serviceSlug');
        const btnUnlock = document.getElementById('btnUnlockSlug');
        const lockIcon = document.getElementById('slugLockIcon');
        let slugIsLocked = true;

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

        // Icon Selector Grid
        const customIconInput = document.getElementById('customIcon');
        const iconPreview = document.getElementById('iconPreview');
        const iconChoices = document.querySelectorAll('.icon-choice');

        iconChoices.forEach(choice => {
            choice.addEventListener('click', function() {
                iconChoices.forEach(c => c.classList.remove('active-icon'));
                this.classList.add('active-icon');
                const selectedIcon = this.getAttribute('data-icon');
                customIconInput.value = selectedIcon;
                iconPreview.innerHTML = `<i class="${selectedIcon}"></i>`;
            });
        });

        customIconInput.addEventListener('input', function() {
            iconPreview.innerHTML = `<i class="${this.value}"></i>`;
        });

        // Dynamic FAQ Builder & Initial Loading
        const faqContainer = document.getElementById('faqContainer');
        const emptyFaqNotice = document.getElementById('emptyFaqNotice');
        const btnAddFaq = document.getElementById('btnAddFaq');

        function checkEmptyNotice() {
            if (faqContainer.children.length === 0) {
                emptyFaqNotice.classList.remove('d-none');
            } else {
                emptyFaqNotice.classList.add('d-none');
            }
        }

        function createFaqItem(question = '', answer = '') {
            const div = document.createElement('div');
            div.className = 'faq-item-card';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-dark border border-secondary text-warning fw-bold">FAQ Item</span>
                    <button type="button" class="btn btn-sm text-danger p-0 border-0 btn-remove-faq" title="Remove FAQ">
                        <i class="fa-solid fa-trash-can"></i> Remove
                    </button>
                </div>
                <div class="mb-2">
                    <input type="text" name="faq_q[]" class="form-control form-control-sm bg-dark border-secondary text-white fw-semibold" placeholder="e.g. What is the typical turnaround time?" value="${question.replace(/"/g, '&quot;')}" required>
                </div>
                <div>
                    <textarea name="faq_a[]" rows="2" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="Answer to this question..." required>${answer}</textarea>
                </div>
            `;

            div.querySelector('.btn-remove-faq').addEventListener('click', function() {
                div.remove();
                checkEmptyNotice();
            });

            faqContainer.appendChild(div);
            checkEmptyNotice();
        }

        btnAddFaq.addEventListener('click', () => createFaqItem());

        // Pre-populate existing FAQs
        const existingFaqs = <?= json_encode($existing_faqs) ?>;
        if (Array.isArray(existingFaqs) && existingFaqs.length > 0) {
            existingFaqs.forEach(faq => {
                createFaqItem(faq.q || '', faq.a || '');
            });
        }

        // Live SERP Preview & Counters
        const titleInput = document.getElementById('serviceTitle');
        const metaTitleInput = document.getElementById('metaTitle');
        const metaDescInput = document.getElementById('metaDescription');
        const shortSummaryInput = document.getElementById('shortSummary');
        const serpTitle = document.getElementById('serpTitle');
        const serpSlug = document.getElementById('serpSlug');
        const serpDesc = document.getElementById('serpDescription');
        const metaTitleCount = document.getElementById('metaTitleCount');
        const metaDescCount = document.getElementById('metaDescCount');
        const summaryCharCount = document.getElementById('summaryCharCount');

        function updateSerp() {
            const currentTitle = metaTitleInput.value.trim() || titleInput.value.trim() || 'Service Title — AR Entertainment';
            serpTitle.textContent = currentTitle;
            metaTitleCount.textContent = `${metaTitleInput.value.length} / 60 chars`;
            metaTitleCount.className = metaTitleInput.value.length > 60 ? 'small text-warning' : 'small text-muted';

            const currentSlug = slugInput.value.trim() || 'service-slug';
            serpSlug.textContent = currentSlug;

            const currentDesc = metaDescInput.value.trim() || shortSummaryInput.value.trim() || 'Professional video production, film fixing, and commercial maker services in Bangladesh.';
            serpDesc.textContent = currentDesc;
            metaDescCount.textContent = `${metaDescInput.value.length} / 160 chars`;
            metaDescCount.className = metaDescInput.value.length > 160 ? 'small text-warning' : 'small text-muted';

            summaryCharCount.textContent = `${shortSummaryInput.value.length} / 250`;
        }

        titleInput.addEventListener('input', updateSerp);
        slugInput.addEventListener('input', updateSerp);
        metaTitleInput.addEventListener('input', updateSerp);
        metaDescInput.addEventListener('input', updateSerp);
        shortSummaryInput.addEventListener('input', updateSerp);

        // Initial update
        updateSerp();
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
