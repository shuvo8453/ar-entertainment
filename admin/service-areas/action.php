<?php

/**
 * AR Entertainment - Service Areas Actions Controller
 * 
 * Secure backend processor for Toggle Status and Delete District Guide.
 */

require_once dirname(__DIR__) . '/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('error', 'Invalid request method.');
    redirect('admin/service-areas');
}

if (!verify_csrf()) {
    set_flash('error', 'Security token expired (CSRF failure). Please try again.');
    redirect('admin/service-areas');
}

$action = trim($_POST['action'] ?? '');
$id     = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Invalid district guide ID provided.');
    redirect('admin/service-areas');
}

// Fetch target service area
try {
    $stmt = db()->prepare("SELECT id, city_name, title, status FROM service_areas WHERE id = ?");
    $stmt->execute([$id]);
    $area = $stmt->fetch();

    if (!$area) {
        set_flash('error', 'District filming guide not found.');
        redirect('admin/service-areas');
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/service-areas');
}

switch ($action) {
    case 'toggle_status':
        $new_status = ($area['status'] === 'active') ? 'inactive' : 'active';
        try {
            $update = db()->prepare("UPDATE service_areas SET status = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$new_status, $id]);
            set_flash('success', "District guide for '<strong>" . htmlspecialchars($area['city_name']) . "</strong>' status updated to <strong>" . ucfirst($new_status) . "</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update status: ' . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            $del = db()->prepare("DELETE FROM service_areas WHERE id = ?");
            $del->execute([$id]);
            set_flash('success', "District guide for '<strong>" . htmlspecialchars($area['city_name']) . "</strong>' was deleted successfully.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to delete district guide: ' . $e->getMessage());
        }
        break;

    default:
        set_flash('error', 'Unknown action specified.');
        break;
}

redirect('admin/service-areas');
