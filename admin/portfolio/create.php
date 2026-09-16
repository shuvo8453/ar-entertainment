<?php

/**
 * AR Entertainment - Add Video Project to Portfolio
 * 
 * Embed parser for YouTube/Vimeo, interactive video preview player,
 * custom thumbnail uploader, category picker, and featured toggle.
 */

require_once dirname(__DIR__) . '/auth_check.php';

$page_title = 'Add Video Project';
$current_page = 'portfolio_create';

// Fetch portfolio categories
try {
    $cat_stmt = db()->query("
        SELECT id, name, slug 
        FROM categories 
        WHERE type = 'portfolio' 
        ORDER BY sort_order ASC, name ASC
    ");
    $categories = $cat_stmt->fetchAll();
} catch (PDOException $e) {
    $categories = [];
}

// Form defaults
$title         = '';
$slug          = '';
$category_id   = 0;
$category_name = 'TV Commercial';
$video_url     = '';
$client_name   = '';
$year          = date('Y');
$thumbnail     = '';
$description   = '';
$is_featured   = 0;
$sort_order    = 0;
$status        = 'active';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security verification failed (CSRF token expired). Please try again.';
    } else {
        $title         = trim($_POST['title'] ?? '');
        $slug          = trim($_POST['slug'] ?? '');
        $category_id   = (int) ($_POST['category_id'] ?? 0);
        $video_url     = trim($_POST['video_url'] ?? '');
        $client_name   = trim($_POST['client_name'] ?? '');
        $year          = trim($_POST['year'] ?? date('Y'));
        $description   = trim($_POST['description'] ?? '');
        $is_featured   = isset($_POST['is_featured']) ? 1 : 0;
        $sort_order    = (int) ($_POST['sort_order'] ?? 0);
        $status        = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

        // Resolve category name
        if ($category_id > 0) {
            foreach ($categories as $c) {
                if ($c['id'] == $category_id) {
                    $category_name = $c['name'];
                    break;
                }
            }
        } else {
            $category_name = trim($_POST['custom_category_name'] ?? 'TV Commercial');
            $category_id = null;
        }

        // Validation
        if ($title === '') {
            $errors[] = 'Project title is required.';
        }

        if ($video_url === '') {
            $errors[] = 'Video URL or Embed code is required.';
        }

        if ($slug === '') {
            $slug = slugify($title);
        } else {
            $slug = slugify($slug);
        }

        // Ensure unique slug
        try {
            $check_slug = db()->prepare("SELECT COUNT(*) FROM portfolio WHERE slug = ?");
            $check_slug->execute([$slug]);
            if ($check_slug->fetchColumn() > 0) {
                $slug = $slug . '-' . time();
            }
        } catch (PDOException $e) {
            $errors[] = 'Database check failed: ' . $e->getMessage();
        }

        // Handle Custom Thumbnail Upload if provided
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            $upload_res = upload_image($_FILES['thumbnail'], 'portfolio');
            if ($upload_res['success']) {
                $thumbnail = $upload_res['path'];
            } else {
                $errors[] = 'Thumbnail upload failed: ' . $upload_res['error'];
            }
        }

        if (empty($errors)) {
            try {
                $sql = "
                    INSERT INTO portfolio (
                        title, slug, category_id, category_name, video_url,
                        client_name, year, thumbnail, description,
                        is_featured, sort_order, status,
                        created_at, updated_at
                    ) VALUES (
                        :title, :slug, :category_id, :category_name, :video_url,
                        :client_name, :year, :thumbnail, :description,
                        :is_featured, :sort_order, :status,
                        NOW(), NOW()
                    )
                ";
                $stmt = db()->prepare($sql);
                $stmt->execute([
                    ':title'         => $title,
                    ':slug'          => $slug,
                    ':category_id'   => $category_id ?: null,
                    ':category_name' => $category_name,
                    ':video_url'     => $video_url,
                    ':client_name'   => $client_name,
                    ':year'          => $year,
                    ':thumbnail'     => $thumbnail ?: null,
                    ':description'   => $description,
                    ':is_featured'   => $is_featured,
                    ':sort_order'    => $sort_order,
                    ':status'        => $status
                ]);

                set_flash('success', "Video project '<strong>" . htmlspecialchars($title) . "</strong>' added successfully to portfolio!");
                redirect('admin/portfolio');
            } catch (PDOException $e) {
                $errors[] = 'Failed to create video project: ' . $e->getMessage();
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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/portfolio') ?>" class="text-muted text-decoration-none">Portfolio</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Add Project</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white">Add Video Project</h3>
            </div>
            <div>
                <a href="<?= site_url('admin/portfolio') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Portfolio
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

        <form method="POST" action="<?= site_url('admin/portfolio/create') ?>" enctype="multipart/form-data" id="projectForm">
            <?= csrf_field() ?>

            <div class="row g-4">
                <!-- Main Content (Left) -->
                <div class="col-12 col-lg-8">
                    <!-- Title & Slug Card -->
                    <div class="card-ar mb-4">
                        <div class="mb-3">
                            <label for="projectTitle" class="form-label text-white fw-semibold">Project Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="projectTitle" class="form-control form-control-lg bg-dark border-secondary text-white" placeholder="e.g. Grameenphone 5G TV Commercial — Future Ready" value="<?= htmlspecialchars($title) ?>" required autofocus>
                        </div>

                        <div class="mb-0">
                            <label for="projectSlug" class="form-label text-muted small fw-semibold">
                                Permalink URL Slug
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-dark border-secondary text-muted font-monospace"><?= SITE_DOMAIN ?>/portfolio/</span>
                                <input type="text" name="slug" id="projectSlug" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="project-slug" value="<?= htmlspecialchars($slug) ?>">
                                <button class="btn btn-ar-secondary" type="button" id="btnUnlockSlug" title="Edit custom slug">
                                    <i class="fa-solid fa-lock" id="slugLockIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Video URL & Live Interactive Player Card -->
                    <div class="card-ar mb-4">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <h5 class="fw-bold mb-0 text-white">
                                <i class="fa-solid fa-play text-danger me-2"></i> Video Source &amp; Live Embed Preview
                            </h5>
                            <span class="badge bg-dark border border-secondary text-info">YouTube &amp; Vimeo Supported</span>
                        </div>

                        <div class="mb-3">
                            <label for="videoUrlInput" class="form-label text-white fw-semibold">
                                Video URL or Embed Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="video_url" id="videoUrlInput" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="e.g. https://www.youtube.com/watch?v=kYJv... OR https://youtu.be/... OR embed iframe" value="<?= htmlspecialchars($video_url) ?>" required>
                            <div class="form-text text-muted small">
                                Paste any regular YouTube link, Vimeo URL, or copied <code>&lt;iframe&gt;</code> embed code. The player and video ID will parse automatically.
                            </div>
                        </div>

                        <!-- Live Interactive Video Player Box -->
                        <div id="videoPreviewBox" class="rounded overflow-hidden border border-secondary mt-3" style="background: #000;">
                            <div class="ratio ratio-16x9">
                                <iframe id="videoPreviewIframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                            <div id="noVideoNotice" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-film fs-1 opacity-25 mb-2"></i>
                                <p class="mb-0 small">Enter a YouTube or Vimeo link above to preview video playback here.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Project Brief & Production Credits Card -->
                    <div class="card-ar mb-4">
                        <label for="projectDescription" class="form-label text-white fw-semibold mb-2">
                            Project Description &amp; Credits
                        </label>
                        <textarea name="description" id="projectDescription" rows="6" class="form-control bg-dark border-secondary text-white" placeholder="Production background, director's note, creative concept, camera package used, location, sound design credits..."><?= htmlspecialchars($description) ?></textarea>
                    </div>
                </div>

                <!-- Settings Sidebar (Right) -->
                <div class="col-12 col-lg-4">
                    <!-- Category & Client Card -->
                    <div class="card-ar mb-4">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-tag text-danger me-2"></i> Project Metadata
                        </h6>

                        <div class="mb-3">
                            <label for="categoryId" class="form-label text-white fw-semibold">Video Category</label>
                            <select name="category_id" id="categoryId" class="form-select bg-dark border-secondary text-white">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="clientName" class="form-label text-white fw-semibold">Client / Brand Name</label>
                            <input type="text" name="client_name" id="clientName" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Unilever, bKash, British Council" value="<?= htmlspecialchars($client_name) ?>">
                        </div>

                        <div class="mb-0">
                            <label for="projectYear" class="form-label text-white fw-semibold">Production Year</label>
                            <input type="text" name="year" id="projectYear" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 2026" value="<?= htmlspecialchars($year) ?>">
                        </div>
                    </div>

                    <!-- Custom Thumbnail Upload Card -->
                    <div class="card-ar mb-4">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-image text-danger me-2"></i> Custom Video Poster / Thumbnail
                        </h6>

                        <div class="mb-3">
                            <input type="file" name="thumbnail" id="thumbnailInput" class="form-control bg-dark border-secondary text-white" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text text-muted small">
                                Optional. If left empty, AR Entertainment automatically retrieves HD poster from YouTube / Vimeo.
                            </div>
                        </div>

                        <div id="thumbPreviewWrapper" class="text-center rounded p-2" style="background: #090a0f; border: 1px dashed #2a2d40; display: none;">
                            <img id="thumbPreviewImg" src="" alt="Thumbnail preview" class="img-fluid rounded" style="max-height: 140px;">
                            <div class="small text-muted mt-1" id="thumbPreviewLabel">Uploaded Thumbnail</div>
                        </div>
                    </div>

                    <!-- Publishing Controls Card -->
                    <div class="card-ar mb-4">
                        <h6 class="fw-bold text-white border-bottom pb-2 mb-3" style="border-color: var(--ar-border-color) !important;">
                            <i class="fa-solid fa-sliders text-danger me-2"></i> Showcase Visibility
                        </h6>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_featured" id="isFeatured" value="1" <?= $is_featured ? 'checked' : '' ?>>
                            <label class="form-check-label text-white fw-semibold" for="isFeatured">
                                <i class="fa-solid fa-star text-warning me-1"></i> Feature on Homepage Reel
                            </label>
                            <div class="form-text text-muted small">Displays video prominently in top hero showreel &amp; featured portfolio slider.</div>
                        </div>

                        <div class="mb-3">
                            <label for="projectStatus" class="form-label text-white fw-semibold">Status</label>
                            <select name="status" id="projectStatus" class="form-select bg-dark border-secondary text-white">
                                <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Active (Visible on Showcase)</option>
                                <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Inactive (Hidden)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sortOrder" class="form-label text-white fw-semibold">Sort Order Index</label>
                            <input type="number" name="sort_order" id="sortOrder" class="form-control bg-dark border-secondary text-white" value="<?= (int) $sort_order ?>">
                            <div class="form-text text-muted small">Lower numbers appear first (0 = default).</div>
                        </div>

                        <div class="d-grid gap-2 pt-2 border-top" style="border-color: var(--ar-border-color) !important;">
                            <button type="submit" class="btn btn-ar-primary btn-lg">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Video Project
                            </button>
                            <a href="<?= site_url('admin/portfolio') ?>" class="btn btn-ar-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        // Slug generation
        const titleInput = document.getElementById('projectTitle');
        const slugInput = document.getElementById('projectSlug');
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
                slugInput.value = slugifyJs(this.value);
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

        // Live Interactive Video Embed Parser & Previewer
        const videoInput = document.getElementById('videoUrlInput');
        const previewIframe = document.getElementById('videoPreviewIframe');
        const noVideoNotice = document.getElementById('noVideoNotice');

        function parseVideoJs(input) {
            input = input.trim();
            if (!input) return null;

            // Extract src from iframe
            const iframeMatch = input.match(/src=["']([^"']+)["']/i);
            if (iframeMatch) {
                input = iframeMatch[1];
            }

            // YouTube Match
            const ytMatch = input.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
            if (ytMatch) {
                return {
                    platform: 'youtube',
                    id: ytMatch[1],
                    embedUrl: `https://www.youtube-nocookie.com/embed/${ytMatch[1]}?rel=0`,
                    thumbnailUrl: `https://img.youtube.com/vi/${ytMatch[1]}/maxresdefault.jpg`
                };
            }

            // Vimeo Match
            const vimeoMatch = input.match(/(?:vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/[^\/]*\/videos\/|video\/|)|player\.vimeo\.com\/video\/)(\d+)/i);
            if (vimeoMatch) {
                return {
                    platform: 'vimeo',
                    id: vimeoMatch[1],
                    embedUrl: `https://player.vimeo.com/video/${vimeoMatch[1]}`,
                    thumbnailUrl: `https://vumbnail.com/${vimeoMatch[1]}.jpg`
                };
            }

            return null;
        }

        function updateVideoPreview() {
            const parsed = parseVideoJs(videoInput.value);
            if (parsed && parsed.embedUrl) {
                previewIframe.src = parsed.embedUrl;
                previewIframe.style.display = 'block';
                noVideoNotice.style.display = 'none';
            } else {
                previewIframe.src = '';
                previewIframe.style.display = 'none';
                noVideoNotice.style.display = 'block';
            }
        }

        videoInput.addEventListener('input', updateVideoPreview);
        videoInput.addEventListener('paste', function() {
            setTimeout(updateVideoPreview, 100);
        });

        // Initialize preview if URL pre-filled
        updateVideoPreview();

        // Local Thumbnail File Preview
        const thumbInput = document.getElementById('thumbnailInput');
        const thumbWrapper = document.getElementById('thumbPreviewWrapper');
        const thumbImg = document.getElementById('thumbPreviewImg');

        thumbInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    thumbImg.src = evt.target.result;
                    thumbWrapper.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>
