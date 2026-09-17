<?php
/**
 * AR Entertainment - Brand / Client Actions (Toggle Status, Delete)
 * Phase 4.5: Management CRUD (admin/brands/action.php)
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    redirect('admin/brands');
}

if (!verify_csrf()) {
    set_flash('error', 'Security verification failed (CSRF token expired).');
    redirect('admin/brands');
}

$id     = (int) ($_POST['id'] ?? 0);
$action = trim($_POST['action'] ?? '');

if ($id <= 0) {
    set_flash('error', 'Invalid brand ID.');
    redirect('admin/brands');
}

// Fetch record
try {
    $stmt = db()->prepare("SELECT id, name, logo, status FROM brands WHERE id = ?");
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

switch ($action) {
    case 'toggle_status':
        $new_status = ($brand['status'] === 'active') ? 'inactive' : 'active';
        try {
            $update = db()->prepare("UPDATE brands SET status = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_status, $id]);
            set_flash('success', "Brand '<strong>" . htmlspecialchars($brand['name']) . "</strong>' status updated to <strong>" . ucfirst($new_status) . "</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update status: ' . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            // Remove uploaded logo file from storage if present in uploads/
            if (!empty($brand['logo']) && !str_starts_with($brand['logo'], 'images/')) {
                $file_path = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $brand['logo']);
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }

            $delete = db()->prepare("DELETE FROM brands WHERE id = ?");
            $delete->execute([$id]);
            set_flash('success', "Brand '<strong>" . htmlspecialchars($brand['name']) . "</strong>' deleted successfully.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to delete brand: ' . $e->getMessage());
        }
        break;

    default:
        set_flash('error', 'Unrecognized action.');
        break;
}

redirect('admin/brands');
