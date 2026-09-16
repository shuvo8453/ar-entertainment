<?php

/**
 * AR Entertainment - Core Helper Functions
 * 
 * Sanitization, slug generator, CSRF security, settings management,
 * flash messaging, and URL helpers.
 */

require_once __DIR__ . '/db.php';

// In-memory cache for site settings
$GLOBALS['_AR_SITE_SETTINGS_CACHE'] = null;

/**
 * Quick access to PDO database instance.
 */
function db(): PDO
{
    return Database::pdo();
}

/**
 * Load all site settings into static memory cache.
 */
function load_settings(): array
{
    if ($GLOBALS['_AR_SITE_SETTINGS_CACHE'] !== null) {
        return $GLOBALS['_AR_SITE_SETTINGS_CACHE'];
    }

    $GLOBALS['_AR_SITE_SETTINGS_CACHE'] = [];
    try {
        $stmt = db()->query("SELECT setting_key, setting_value FROM site_settings");
        while ($row = $stmt->fetch()) {
            $GLOBALS['_AR_SITE_SETTINGS_CACHE'][$row['setting_key']] = $row['setting_value'];
        }
    } catch (PDOException $e) {
        // In case table does not exist yet during installation
    }
    return $GLOBALS['_AR_SITE_SETTINGS_CACHE'];
}

/**
 * Get a specific site setting by key.
 */
function get_setting(string $key, string $default = ''): string
{
    $settings = load_settings();
    return $settings[$key] ?? $default;
}

/**
 * Update or insert a site setting.
 */
function update_setting(string $key, string $value, string $group = 'general'): bool
{
    try {
        $stmt = db()->prepare("
            INSERT INTO site_settings (setting_key, setting_value, setting_group, updated_at)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()
        ");
        $success = $stmt->execute([$key, $value, $group]);
        if ($success && $GLOBALS['_AR_SITE_SETTINGS_CACHE'] !== null) {
            $GLOBALS['_AR_SITE_SETTINGS_CACHE'][$key] = $value;
        }
        return $success;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Sanitize string or array input against XSS.
 */
function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a clean, SEO-friendly URL slug.
 */
function slugify(string $text): string
{
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    if (function_exists('iconv')) {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);

    return empty($text) ? 'n-a' : $text;
}

/**
 * Build asset URL.
 */
function asset_url(string $path = ''): string
{
    return ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Build uploads URL.
 */
function upload_url(string $path = ''): string
{
    if (empty($path)) {
        return ASSETS_URL . '/images/placeholder.webp';
    }
    // If it's already an absolute URL or starts with images/
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    if (str_starts_with($path, 'images/')) {
        return BASE_URL . '/' . $path;
    }
    return UPLOADS_URL . '/' . ltrim($path, '/');
}

/**
 * Build site URL.
 */
function site_url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Generate or get CSRF token.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output hidden CSRF HTML input.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

/**
 * Verify submitted CSRF token.
 */
function verify_csrf(?string $token = null): bool
{
    if ($token === null) {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    }
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Set a session flash message.
 */
function set_flash(string $type, string $message): void
{
    $_SESSION['flash_messages'][$type][] = $message;
}

/**
 * Get and clear session flash messages.
 */
function get_flashes(): array
{
    $flashes = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $flashes;
}

/**
 * Display rendered flash alerts (Bootstrap formatted).
 */
function render_flash(): string
{
    $flashes = get_flashes();
    if (empty($flashes)) {
        return '';
    }

    $html = '';
    foreach ($flashes as $type => $messages) {
        $alert_type = ($type === 'error') ? 'danger' : $type;
        $html .= '<div class="alert alert-' . htmlspecialchars($alert_type) . ' alert-dismissible fade show" role="alert">';
        foreach ($messages as $msg) {
            $html .= '<div>' . htmlspecialchars($msg) . '</div>';
        }
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        $html .= '</div>';
    }
    return $html;
}

/**
 * Redirect to a given URL and exit.
 */
function redirect(string $url): void
{
    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
        $url = site_url($url);
    }
    header("Location: " . $url);
    exit;
}

/**
 * Check if admin is currently logged in.
 */
function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']) && !empty($_SESSION['user_role']);
}

/**
 * Require login or redirect to admin login page.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('error', 'Please login to access the administration panel.');
        redirect('admin/login.php');
    }
}

/**
 * Get current logged in user session data.
 */
function current_user(): ?array
{
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id'    => $_SESSION['user_id'] ?? null,
        'name'  => $_SESSION['user_name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role'  => $_SESSION['user_role'] ?? 'editor'
    ];
}

/**
 * Format datetime for display.
 */
function format_date(?string $datetime, string $format = 'M d, Y'): string
{
    if (empty($datetime)) {
        return '—';
    }
    $ts = strtotime($datetime);
    return ($ts !== false) ? date($format, $ts) : '—';
}

/**
 * Truncate long text cleanly at word boundary.
 */
function truncate_text(string $text, int $limit = 150, string $ellipsis = '...'): string
{
    $clean = strip_tags($text);
    if (mb_strlen($clean) <= $limit) {
        return $clean;
    }
    $cut = mb_substr($clean, 0, $limit);
    $last_space = mb_strrpos($cut, ' ');
    if ($last_space !== false) {
        $cut = mb_substr($cut, 0, $last_space);
    }
    return $cut . $ellipsis;
}

/**
 * Upload an image file securely with MIME and size validation.
 *
 * @param array $file $_FILES['key']
 * @param string $folder Destination folder inside /uploads/ (e.g. 'blogs', 'team', 'portfolio')
 * @param array $allowed_types Allowed MIME types
 * @param int $max_size Maximum file size in bytes (default 5MB)
 * @return array ['success' => bool, 'path' => string, 'filename' => string, 'error' => string]
 */
function upload_image(array $file, string $folder = 'blogs', array $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'], int $max_size = 5242880): array
{
    if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'No file was uploaded or upload failed.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'File upload error code: ' . $file['error']];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'File size exceeds maximum limit (' . round($max_size / 1048576, 1) . 'MB).'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_types, true)) {
        return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'Invalid image format. Allowed: JPG, PNG, WEBP, GIF, SVG.'];
    }

    $ext_map = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        'image/svg+xml' => 'svg'
    ];
    $ext = $ext_map[$mime] ?? pathinfo($file['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);

    $target_dir = UPLOADS_PATH . DIRECTORY_SEPARATOR . trim($folder, '/\\');
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $clean_orig_name = slugify(pathinfo($file['name'], PATHINFO_FILENAME));
    if (empty($clean_orig_name)) {
        $clean_orig_name = 'image';
    }
    $filename = $clean_orig_name . '-' . uniqid() . '.' . $ext;
    $target_file = $target_dir . DIRECTORY_SEPARATOR . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $relative_path = $folder . '/' . $filename;
        return ['success' => true, 'path' => $relative_path, 'filename' => $filename, 'error' => ''];
    }

    return ['success' => false, 'path' => '', 'filename' => '', 'error' => 'Failed to save uploaded file to storage directory.'];
}
