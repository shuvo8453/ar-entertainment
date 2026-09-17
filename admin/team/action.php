<?php

/**
 * AR Entertainment - Team Member Actions Controller
 * 
 * Secure backend processor for Toggle Status and Delete Team Member.
 */

require_once dirname(__DIR__) . '/auth_check.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    set_flash('error', 'Invalid request method.');
    redirect('admin/team');
}

if (!verify_csrf()) {
    set_flash('error', 'Security token expired (CSRF failure). Please try again.');
    redirect('admin/team');
}

$action = trim($_POST['action'] ?? '');
$id     = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Invalid team member ID provided.');
    redirect('admin/team');
}

// Fetch target team member
try {
    $stmt = db()->prepare("SELECT id, name, photo, status FROM team_members WHERE id = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch();

    if (!$member) {
        set_flash('error', 'Team member not found.');
        redirect('admin/team');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/team');
}

switch ($action) {
    case 'toggle_status':
        $new_status = ($member['status'] === 'active') ? 'inactive' : 'active';
        try {
            $update = db()->prepare("UPDATE team_members SET status = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_status, $id]);
            set_flash('success', "Member '<strong>" . htmlspecialchars($member['name']) . "</strong>' status updated to <strong>" . ucfirst($new_status) . "</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update status: ' . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            // Remove uploaded photo file from storage if present
            if (!empty($member['photo'])) {
                $file_path = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $member['photo']);
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }

            $del = db()->prepare("DELETE FROM team_members WHERE id = ?");
            $del->execute([$id]);
            set_flash('success', "Team member '<strong>" . htmlspecialchars($member['name']) . "</strong>' was deleted successfully.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to delete team member: ' . $e->getMessage());
        }
        break;

    default:
        set_flash('error', 'Unknown action specified.');
        break;
}

redirect('admin/team');
