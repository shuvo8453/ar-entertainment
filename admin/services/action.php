<?php

/**
 * AR Entertainment - Services Actions Controller
 * 
 * Secure backend processor for Toggle Status and Delete Service.
 */

require_once dirname(__DIR__) . '/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('error', 'Invalid request method.');
    redirect('admin/services');
}

if (!verify_csrf()) {
    set_flash('error', 'Security token expired (CSRF failure). Please try again.');
    redirect('admin/services');
}

$action = trim($_POST['action'] ?? '');
$id     = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Invalid service ID provided.');
    redirect('admin/services');
}

// Fetch target service
try {
    $stmt = db()->prepare("SELECT id, title, status FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();

    if (!$service) {
        set_flash('error', 'Service not found.');
        redirect('admin/services');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/services');
}

switch ($action) {
    case 'toggle_status':
        $new_status = ($service['status'] === 'active') ? 'inactive' : 'active';
        try {
            $update = db()->prepare("UPDATE services SET status = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_status, $id]);
            set_flash('success', "Service '<strong>" . htmlspecialchars($service['title']) . "</strong>' status updated to <strong>" . ucfirst($new_status) . "</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update status: ' . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            $del = db()->prepare("DELETE FROM services WHERE id = ?");
            $del->execute([$id]);
            set_flash('success', "Service '<strong>" . htmlspecialchars($service['title']) . "</strong>' was deleted successfully.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to delete service: ' . $e->getMessage());
        }
        break;

    default:
        set_flash('error', 'Unknown action specified.');
        break;
}

redirect('admin/services');
