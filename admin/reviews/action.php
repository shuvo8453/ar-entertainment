<?php
/**
 * AR Entertainment - Client Review Actions (Toggle Status, Delete)
 * Phase 4.6: Management CRUD (admin/reviews/action.php)
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    redirect('admin/reviews');
}

if (!verify_csrf()) {
    set_flash('error', 'Security verification failed (CSRF token expired).');
    redirect('admin/reviews');
}

$id     = (int) ($_POST['id'] ?? 0);
$action = trim($_POST['action'] ?? '');

if ($id <= 0) {
    set_flash('error', 'Invalid review ID.');
    redirect('admin/reviews');
}

// Fetch record
try {
    $stmt = db()->prepare("SELECT id, client_name, client_photo, status FROM reviews WHERE id = ?");
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

switch ($action) {
    case 'toggle_status':
        $new_status = ($review['status'] === 'active') ? 'inactive' : 'active';
        try {
            $update = db()->prepare("UPDATE reviews SET status = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_status, $id]);
            set_flash('success', "Review from '<strong>" . htmlspecialchars($review['client_name']) . "</strong>' status updated to <strong>" . ucfirst($new_status) . "</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update status: ' . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            // Remove uploaded client photo file from storage if present in uploads/
            if (!empty($review['client_photo']) && !str_starts_with($review['client_photo'], 'images/')) {
                $file_path = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $review['client_photo']);
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }

            $delete = db()->prepare("DELETE FROM reviews WHERE id = ?");
            $delete->execute([$id]);
            set_flash('success', "Review from '<strong>" . htmlspecialchars($review['client_name']) . "</strong>' deleted successfully.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to delete review: ' . $e->getMessage());
        }
        break;

    default:
        set_flash('error', 'Unrecognized action.');
        break;
}

redirect('admin/reviews');
