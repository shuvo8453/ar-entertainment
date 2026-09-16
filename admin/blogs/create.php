<?php

/**
 * AR Entertainment - Create New Blog Article
 * 
 * Rich TinyMCE Editor, Live Slug Generator, Thumbnail Uploader,
 * SEO Meta Tag Suite & Google SERP Preview.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Write New Article';
$current_page = 'blogs_create';

// Fetch blog categories
$categories = [];
try {
    $categories = db()->query("SELECT id, name FROM categories WHERE type = 'blog' ORDER BY sort_order ASC, name ASC")->fetchAll();
} catch (PDOException $e) {
}

// Form Data defaults
$title = '';
$slug = '';
$summary = '';
$content = '';
$category_id = 0;
$author_name = current_user()['name'] ?? 'Azizul Hoque Shiplu';
$tags = '';
$status = 'published';
$is_featured = 0;
$meta_title = '';
$meta_description = '';
$meta_keywords = '';
$published_at = date('Y-m-d\TH:i');

$errors = [];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security verification failed (CSRF token expired). Please try again.';
    } else {
        $title            = trim($_POST['title'] ?? '');
        $slug             = trim($_POST['slug'] ?? '');
        $summary          = trim($_POST['summary'] ?? '');
        $content          = trim($_POST['content'] ?? '');
        $category_id      = (int) ($_POST['category_id'] ?? 0);
        $author_name      = trim($_POST['author_name'] ?? 'Azizul Hoque Shiplu');
        $tags             = trim($_POST['tags'] ?? '');
        $status           = in_array($_POST['status'] ?? '', ['published', 'draft', 'archived']) ? $_POST['status'] : 'published';
        $is_featured      = !empty($_POST['is_featured']) ? 1 : 0;
        $meta_title       = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $meta_keywords    = trim($_POST['meta_keywords'] ?? '');
        $published_at_raw = trim($_POST['published_at'] ?? '');
        $published_at     = !empty($published_at_raw) ? date('Y-m-d H:i:s', strtotime($published_at_raw)) : date('Y-m-d H:i:s');

        // Validation
        if ($title === '') {
            $errors[] = 'Article title is required.';
        }

        if ($content === '') {
            $errors[] = 'Article body content cannot be empty.';
        }

        // Auto-generate slug if empty
        if ($slug === '') {
            $slug = slugify($title);
        } else {
            $slug = slugify($slug);
        }

        // Ensure unique slug
        try {
            $check_slug = db()->prepare("SELECT COUNT(*) FROM blogs WHERE slug = ?");
            $check_slug->execute([$slug]);
            if ($check_slug->fetchColumn() > 0) {
                $slug = $slug . '-' . time();
            }
        } catch (PDOException $e) {
            $errors[] = 'Database check failed: ' . $e->getMessage();
        }

        // Handle Thumbnail Upload
        $thumbnail_path = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            $upload = upload_image($_FILES['thumbnail'], 'blogs');
            if ($upload['success']) {
                $thumbnail_path = $upload['path'];
            } else {
                $errors[] = 'Thumbnail upload error: ' . $upload['error'];
            }
        }

        // If no meta title, auto-fill from article title
        if ($meta_title === '') {
            $meta_title = $title;
        }

        // If no meta description, auto-fill from summary or truncated content
        if ($meta_description === '') {
            $meta_description = !empty($summary) ? $summary : truncate_text($content, 155);
        }

        // Insert into Database
        if (empty($errors)) {
            try {
                $sql = "
                    INSERT INTO blogs (
                        title, slug, summary, content, thumbnail, category_id,
                        author_name, tags, status, is_featured,
                        meta_title, meta_description, meta_keywords,
                        published_at, created_at, updated_at
                    ) VALUES (
                        :title, :slug, :summary, :content, :thumbnail, :category_id,
                        :author_name, :tags, :status, :is_featured,
                        :meta_title, :meta_description, :meta_keywords,
                        :published_at, NOW(), NOW()
                    )
                ";
                $stmt = db()->prepare($sql);
                $stmt->execute([
                    ':title'            => $title,
                    ':slug'             => $slug,
                    ':summary'          => $summary,
                    ':content'          => $content,
                    ':thumbnail'        => $thumbnail_path,
                    ':category_id'      => $category_id > 0 ? $category_id : null,
                    ':author_name'      => $author_name,
                    ':tags'             => $tags,
                    ':status'           => $status,
                    ':is_featured'      => $is_featured,
                    ':meta_title'       => $meta_title,
                    ':meta_description' => $meta_description,
                    ':meta_keywords'    => $meta_keywords,
                    ':published_at'     => $published_at
                ]);

                $new_id = db()->lastInsertId();
                set_flash('success', "Article '<strong>" . htmlspecialchars($title) . "</strong>' created successfully!");
                redirect('admin/blogs/index.php');
            } catch (PDOException $e) {
                $errors[] = 'Failed to save article: ' . $e->getMessage();
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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/index.php') ?>" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/blogs/index.php') ?>" class="text-muted text-decoration-none">Blog Articles</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">New Article</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Create New Blog Article</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/blogs/index.php') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Articles
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

        <form method="POST" action="<?= site_url('admin/blogs/create.php') ?>" enctype="multipart/form-data" id="articleForm">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Main Content Column (Left) -->
                <div class="col-12 col-lg-8">
                    <!-- Title & Slug Card -->
                    <div class="card-ar mb-4">
                        <div class="mb-3">
                            <label for="postTitle" class="form-label text-white fw-semibold">Article Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="postTitle" class="form-control form-control-lg bg-dark border-secondary text-white" placeholder="e.g. Top 10 Video Production Trends in Bangladesh 2026" value="<?= htmlspecialchars($title) ?>" required autofocus>
                        </div>

                        <div class="mb-0">
                            <label for="postSlug" class="form-label text-muted small fw-semibold">
                                Permalink URL Slug <span class="text-muted">(Auto-generated, editable)</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-dark border-secondary text-muted font-monospace"><?= SITE_DOMAIN ?>/blog/</span>
                                <input type="text" name="slug" id="postSlug" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="article-url-slug" value="<?= htmlspecialchars($slug) ?>">
                                <button class="btn btn-ar-secondary" type="button" id="btnUnlockSlug" title="Edit custom slug">
                                    <i class="fa-solid fa-lock" id="slugLockIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary / Excerpt Card -->
                    <div class="card-ar mb-4">
                        <label for="postSummary" class="form-label text-white fw-semibold d-flex justify-content-between">
                            <span>Summary / Excerpt</span>
                            <span class="text-muted small" id="summaryCharCount">0 / 250</span>
                        </label>
                        <textarea name="summary" id="postSummary" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="A brief, engaging 2-3 sentence overview of this article to appear on cards and search results..."><?= htmlspecialchars($summary) ?></textarea>
                    </div>

                    <!-- TinyMCE Rich Text Content Card -->
                    <div class="card-ar mb-4">
                        <label class="form-label text-white fw-semibold mb-2">Article Body Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="editorContent" rows="18"><?= htmlspecialchars($content) ?></textarea>
                    </div>

                    <!-- SEO & Social Meta Suite Card -->
                    <div class="card-ar mb-4">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <h5 class="fw-bold mb-0 text-white">
                                <i class="fa-solid fa-chart-line text-danger me-2"></i> Search Engine Optimization (SEO)
                            </h5>
                            <span class="badge bg-dark border border-secondary text-muted">Google &amp; Social Meta</span>
                        </div>

                        <!-- Google Live SERP Preview -->
                        <div class="p-3 mb-4 rounded" style="background-color: #0c0d14; border: 1px dashed #2e3247;">
                            <div class="small text-muted mb-2 text-uppercase fw-bold" style="font-size: 11px;">Google Search Result Preview</div>
                            <div class="text-primary text-truncate fw-semibold fs-5" id="serpTitle" style="color: #8ab4f8 !important; cursor: pointer;">
                                <?= htmlspecialchars($meta_title ?: ($title ?: 'Article Title &mdash; ' . SITE_NAME)) ?>
                            </div>
                            <div class="text-success small font-monospace my-1" id="serpUrl" style="color: #81c995 !important;">
                                https://www.<?= SITE_DOMAIN ?> &rsaquo; blog &rsaquo; <span id="serpSlug"><?= htmlspecialchars($slug ?: 'article-slug') ?></span>
                            </div>
                            <div class="text-muted small" id="serpDescription" style="color: #bdc1c6 !important; line-height: 1.4;">
                                <?= htmlspecialchars($meta_description ?: 'Comprehensive video production guide and film insights by AR Entertainment in Bangladesh.') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="metaTitle" class="form-label text-white fw-semibold d-flex justify-content-between">
                                <span>SEO Meta Title</span>
                                <span class="small text-muted" id="metaTitleCount">0 / 60 chars</span>
                            </label>
                            <input type="text" name="meta_title" id="metaTitle" class="form-control bg-dark border-secondary text-white" placeholder="Optimal: 50-60 characters" value="<?= htmlspecialchars($meta_title) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="metaDescription" class="form-label text-white fw-semibold d-flex justify-content-between">
                                <span>SEO Meta Description</span>
                                <span class="small text-muted" id="metaDescCount">0 / 160 chars</span>
                            </label>
                            <textarea name="meta_description" id="metaDescription" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="Optimal: 140-160 characters summary for search engines"><?= htmlspecialchars($meta_description) ?></textarea>
                        </div>

                        <div class="mb-0">
                            <label for="metaKeywords" class="form-label text-white fw-semibold">Focus Keywords</label>
                            <input type="text" name="meta_keywords" id="metaKeywords" class="form-control bg-dark border-secondary text-white" placeholder="video production dhaka, tv commercial bangladesh, documentary fixer" value="<?= htmlspecialchars($meta_keywords) ?>">
                        </div>
                    </div>
                </div>

                <!-- Sidebar Settings Column (Right) -->
                <div class="col-12 col-lg-4">
                    <!-- Publish Controls Card -->
                    <div class="card-ar mb-4">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-paper-plane text-danger me-2"></i> Publish Settings
                        </h6>

                        <div class="mb-3">
                            <label for="postStatus" class="form-label text-white fw-semibold">Status</label>
                            <select name="status" id="postStatus" class="form-select bg-dark border-secondary text-white">
                                <option value="published" <?= ($status === 'published') ? 'selected' : '' ?>>Published (Live)</option>
                                <option value="draft" <?= ($status === 'draft') ? 'selected' : '' ?>>Draft (Hidden)</option>
                                <option value="archived" <?= ($status === 'archived') ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="publishedAt" class="form-label text-white fw-semibold">Publish Date &amp; Time</label>
                            <input type="datetime-local" name="published_at" id="publishedAt" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($published_at))) ?>">
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" <?= $is_featured ? 'checked' : '' ?>>
                            <label class="form-check-label text-white" for="isFeatured">
                                <i class="fa-solid fa-star text-warning me-1"></i> Feature this Article on Homepage
                            </label>
                        </div>

                        <div class="d-grid gap-2 pt-2 border-top" style="border-color: var(--ar-border-color) !important;">
                            <button type="submit" class="btn btn-ar-primary btn-lg">
                                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Save &amp; Publish Article
                            </button>
                            <a href="<?= site_url('admin/blogs/index.php') ?>" class="btn btn-ar-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>

                    <!-- Category & Taxonomy Card -->
                    <div class="card-ar mb-4">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <h6 class="fw-bold text-white mb-0">
                                <i class="fa-solid fa-folder-open text-danger me-2"></i> Category
                            </h6>
                            <a href="<?= site_url('admin/blogs/categories.php') ?>" target="_blank" class="small text-info text-decoration-none">
                                + New Category
                            </a>
                        </div>

                        <div class="mb-3">
                            <select name="category_id" id="categoryId" class="form-select bg-dark border-secondary text-white">
                                <option value="0">-- Select Category --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($category_id === (int)$cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="authorName" class="form-label text-white fw-semibold">Author Display Name</label>
                            <input type="text" name="author_name" id="authorName" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($author_name) ?>">
                        </div>

                        <div class="mb-0">
                            <label for="postTags" class="form-label text-white fw-semibold">Tags</label>
                            <input type="text" name="tags" id="postTags" class="form-control bg-dark border-secondary text-white" placeholder="AI Video, TV Commercial, Film Fixing" value="<?= htmlspecialchars($tags) ?>">
                            <div class="form-text text-muted small">Separate tags with commas.</div>
                        </div>
                    </div>

                    <!-- Featured Image / Thumbnail Card -->
                    <div class="card-ar mb-4">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-image text-danger me-2"></i> Featured Thumbnail
                        </h6>

                        <div class="mb-3">
                            <div class="border border-secondary border-dashed rounded p-3 text-center position-relative" id="dropZone" style="background-color: #12131d; cursor: pointer;">
                                <input type="file" name="thumbnail" id="thumbnailInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/png,image/webp,image/gif" style="cursor: pointer;">
                                <div id="previewContainer" class="d-none mb-2">
                                    <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 180px; object-fit: cover;">
                                </div>
                                <div id="uploadPrompt">
                                    <i class="fa-solid fa-cloud-arrow-up fs-2 text-muted mb-2"></i>
                                    <p class="text-white small mb-1 fw-semibold">Click to browse or drag &amp; drop</p>
                                    <p class="text-muted" style="font-size: 11px;">WEBP, JPG, PNG up to 5MB (1200x630 recommended)</p>
                                </div>
                            </div>
                        </div>
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
            height: 480,
            skin: 'oxide-dark',
            content_css: 'dark',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat code fullscreen',
            images_upload_url: '<?= site_url('admin/blogs/upload_editor_image.php') ?>',
            automatic_uploads: true,
            images_reuse_filename: false,
            images_upload_handler: function(blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '<?= site_url('admin/blogs/upload_editor_image.php') ?>');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '<?= csrf_token() ?>');

                    xhr.upload.onprogress = (e) => {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = () => {
                        if (xhr.status === 403) {
                            reject({
                                message: 'HTTP Error: ' + xhr.status,
                                remove: true
                            });
                            return;
                        }
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }
                        const json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location != 'string') {
                            reject('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        resolve(json.location);
                    };

                    xhr.onerror = () => {
                        reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                    };

                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('csrf_token', '<?= csrf_token() ?>');
                    xhr.send(formData);
                });
            }
        });

        // Slug generator & synchronization
        const titleInput = document.getElementById('postTitle');
        const slugInput = document.getElementById('postSlug');
        const btnUnlock = document.getElementById('btnUnlockSlug');
        const lockIcon = document.getElementById('slugLockIcon');
        let slugIsLocked = true;

        function slugifyJs(text) {
            return text.toString().toLowerCase()
                .trim()
                .replace(/[\s\W-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        titleInput.addEventListener('input', function() {
            if (slugIsLocked) {
                const generated = slugifyJs(this.value);
                slugInput.value = generated;
                updateSerp();
            }
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

        // Thumbnail live preview
        const thumbnailInput = document.getElementById('thumbnailInput');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPrompt = document.getElementById('uploadPrompt');

        thumbnailInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                    uploadPrompt.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Live SERP Preview & Character Counters
        const metaTitleInput = document.getElementById('metaTitle');
        const metaDescInput = document.getElementById('metaDescription');
        const summaryInput = document.getElementById('postSummary');

        const serpTitle = document.getElementById('serpTitle');
        const serpSlug = document.getElementById('serpSlug');
        const serpDesc = document.getElementById('serpDescription');

        const metaTitleCount = document.getElementById('metaTitleCount');
        const metaDescCount = document.getElementById('metaDescCount');
        const summaryCharCount = document.getElementById('summaryCharCount');

        function updateSerp() {
            const currentTitle = metaTitleInput.value.trim() || titleInput.value.trim() || 'Article Title — AR Entertainment';
            serpTitle.textContent = currentTitle;
            metaTitleCount.textContent = `${metaTitleInput.value.length} / 60 chars`;
            metaTitleCount.className = metaTitleInput.value.length > 60 ? 'small text-warning' : 'small text-muted';

            const currentSlug = slugInput.value.trim() || 'article-slug';
            serpSlug.textContent = currentSlug;

            const currentDesc = metaDescInput.value.trim() || summaryInput.value.trim() || 'Comprehensive video production guide and film insights by AR Entertainment in Bangladesh.';
            serpDesc.textContent = currentDesc;
            metaDescCount.textContent = `${metaDescInput.value.length} / 160 chars`;
            metaDescCount.className = metaDescInput.value.length > 160 ? 'small text-warning' : 'small text-muted';

            summaryCharCount.textContent = `${summaryInput.value.length} / 250`;
        }

        titleInput.addEventListener('input', updateSerp);
        slugInput.addEventListener('input', updateSerp);
        metaTitleInput.addEventListener('input', updateSerp);
        metaDescInput.addEventListener('input', updateSerp);
        summaryInput.addEventListener('input', updateSerp);

        // Initial update
        updateSerp();
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
