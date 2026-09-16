<?php

/**
 * AR Entertainment - Blog Actions Controller
 * 
 * Secure backend processor for Toggle Status, Toggle Featured, and Delete.
 */

require_once dirname(__DIR__) . '/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('error', 'Invalid request method.');
    redirect('admin/blogs');
}

if (!verify_csrf()) {
    set_flash('error', 'Security token expired (CSRF failure). Please try again.');
    redirect('admin/blogs');
}

$action = trim($_POST['action'] ?? '');
$id     = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Invalid article ID provided.');
    redirect('admin/blogs');
}

// Fetch target article
try {
    $stmt = db()->prepare("SELECT id, title, status, is_featured, thumbnail FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();

    if (!$article) {
        set_flash('error', 'Article not found.');
        redirect('admin/blogs');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/blogs');
}

switch ($action) {
    case 'toggle_status':
        $new_status = ($article['status'] === 'published') ? 'draft' : 'published';
        try {
            $update = db()->prepare("UPDATE blogs SET status = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_status, $id]);
            set_flash('success', "Article '<strong>" . htmlspecialchars($article['title']) . "</strong>' status updated to <strong>" . ucfirst($new_status) . "</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update status: ' . $e->getMessage());
        }
        break;

    case 'toggle_featured':
        $new_featured = $article['is_featured'] ? 0 : 1;
        try {
            $update = db()->prepare("UPDATE blogs SET is_featured = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_featured, $id]);
            $msg = $new_featured ? 'marked as Featured.' : 'removed from Featured.';
            set_flash('success', "Article '<strong>" . htmlspecialchars($article['title']) . "</strong>' {$msg}");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update featured flag: ' . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            // Delete record
            $del = db()->prepare("DELETE FROM blogs WHERE id = ?");
            $del->execute([$id]);

            // Attempt to clean up thumbnail if stored in uploads/blogs/
            if (!empty($article['thumbnail']) && str_starts_with($article['thumbnail'], 'blogs/')) {
                $file_path = UPLOADS_PATH . DIRECTORY_SEPARATOR . $article['thumbnail'];
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }

            set_flash('success', "Article '<strong>" . htmlspecialchars($article['title']) . "</strong>' was deleted successfully.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to delete article: ' . $e->getMessage());
        }
        break;

    default:
        set_flash('error', 'Unknown action specified.');
        break;
}

redirect('admin/blogs');
