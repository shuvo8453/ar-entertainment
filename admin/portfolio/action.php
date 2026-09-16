<?php

/**
 * AR Entertainment - Portfolio Actions Controller
 * 
 * Secure backend processor for Toggle Status, Toggle Featured, and Delete Video Project.
 */

require_once dirname(__DIR__) . '/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('error', 'Invalid request method.');
    redirect('admin/portfolio');
}

if (!verify_csrf()) {
    set_flash('error', 'Security token expired (CSRF failure). Please try again.');
    redirect('admin/portfolio');
}

$action = trim($_POST['action'] ?? '');
$id     = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Invalid video project ID provided.');
    redirect('admin/portfolio');
}

// Fetch target video project
try {
    $stmt = db()->prepare("SELECT id, title, thumbnail, status, is_featured FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();

    if (!$project) {
        set_flash('error', 'Video project not found.');
        redirect('admin/portfolio');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/portfolio');
}

switch ($action) {
    case 'toggle_status':
        $new_status = ($project['status'] === 'active') ? 'inactive' : 'active';
        try {
            $update = db()->prepare("UPDATE portfolio SET status = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_status, $id]);
            set_flash('success', "Project '<strong>" . htmlspecialchars($project['title']) . "</strong>' status updated to <strong>" . ucfirst($new_status) . "</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update status: ' . $e->getMessage());
        }
        break;

    case 'toggle_featured':
        $new_featured = ($project['is_featured'] == 1) ? 0 : 1;
        try {
            $update = db()->prepare("UPDATE portfolio SET is_featured = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_featured, $id]);
            $msg = ($new_featured == 1) ? "is now <strong>Featured ⭐</strong> on the homepage." : "has been removed from homepage featured reel.";
            set_flash('success', "Project '<strong>" . htmlspecialchars($project['title']) . "</strong>' {$msg}");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to toggle featured status: ' . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            // Remove uploaded thumbnail file from storage if present
            if (!empty($project['thumbnail'])) {
                $file_path = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $project['thumbnail']);
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }

            $del = db()->prepare("DELETE FROM portfolio WHERE id = ?");
            $del->execute([$id]);
            set_flash('success', "Video project '<strong>" . htmlspecialchars($project['title']) . "</strong>' was deleted successfully.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to delete video project: ' . $e->getMessage());
        }
        break;

    default:
        set_flash('error', 'Unknown action specified.');
        break;
}

redirect('admin/portfolio');
