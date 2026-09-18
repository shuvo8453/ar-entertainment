<?php
/**
 * AR Entertainment - Leads & Inquiries Actions
 * Phase 4.7: Management CRUD (admin/inquiries/action.php)
 * Handles mark read, mark unread, single delete, and bulk operations.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    redirect('admin/inquiries');
}

if (!verify_csrf()) {
    set_flash('error', 'Security verification failed (CSRF token expired).');
    redirect('admin/inquiries');
}

$action     = trim($_POST['action'] ?? '');
$redirect_to = trim($_POST['redirect_to'] ?? 'admin/inquiries');

// Handle Bulk Actions
if (in_array($action, ['bulk_read', 'bulk_unread', 'bulk_delete'], true)) {
    $ids = $_POST['ids'] ?? [];
    if (!is_array($ids) || empty($ids)) {
        set_flash('error', 'No inquiries were selected for bulk action.');
        redirect($redirect_to);
    }

    $sanitized_ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);
    if (empty($sanitized_ids)) {
        set_flash('error', 'Invalid inquiry IDs provided.');
        redirect($redirect_to);
    }

    $count = count($sanitized_ids);
    $placeholders = implode(',', array_fill(0, $count, '?'));

    try {
        if ($action === 'bulk_read') {
            $stmt = db()->prepare("UPDATE inquiries SET is_read = 1 WHERE id IN ($placeholders)");
            $stmt->execute($sanitized_ids);
            set_flash('success', "Marked <strong>{$count}</strong> inquiry(ies) as <strong>Read</strong>.");
        } elseif ($action === 'bulk_unread') {
            $stmt = db()->prepare("UPDATE inquiries SET is_read = 0 WHERE id IN ($placeholders)");
            $stmt->execute($sanitized_ids);
            set_flash('success', "Marked <strong>{$count}</strong> inquiry(ies) as <strong>Unread</strong>.");
        } elseif ($action === 'bulk_delete') {
            $stmt = db()->prepare("DELETE FROM inquiries WHERE id IN ($placeholders)");
            $stmt->execute($sanitized_ids);
            set_flash('success', "Deleted <strong>{$count}</strong> inquiry(ies) successfully.");
        }
    } catch (PDOException $e) {
        set_flash('error', 'Database error during bulk action: ' . $e->getMessage());
    }

    redirect($redirect_to);
}

// Handle Single Item Actions
$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    set_flash('error', 'Invalid inquiry ID.');
    redirect($redirect_to);
}

// Fetch record
try {
    $stmt = db()->prepare("SELECT id, name, subject, form_type, is_read FROM inquiries WHERE id = ?");
    $stmt->execute([$id]);
    $inquiry = $stmt->fetch();

    if (!$inquiry) {
        set_flash('error', 'Inquiry not found.');
        redirect($redirect_to);
    }
} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect($redirect_to);
}

switch ($action) {
    case 'mark_read':
        try {
            $update = db()->prepare("UPDATE inquiries SET is_read = 1 WHERE id = ?");
            $update->execute([$id]);
            set_flash('success', "Inquiry from '<strong>" . htmlspecialchars($inquiry['name']) . "</strong>' marked as <strong>Read</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update read status: ' . $e->getMessage());
        }
        break;

    case 'mark_unread':
        try {
            $update = db()->prepare("UPDATE inquiries SET is_read = 0 WHERE id = ?");
            $update->execute([$id]);
            set_flash('success', "Inquiry from '<strong>" . htmlspecialchars($inquiry['name']) . "</strong>' marked as <strong>Unread</strong>.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to update read status: ' . $e->getMessage());
        }
        break;

    case 'delete':
        try {
            $delete = db()->prepare("DELETE FROM inquiries WHERE id = ?");
            $delete->execute([$id]);
            set_flash('success', "Inquiry from '<strong>" . htmlspecialchars($inquiry['name']) . "</strong>' deleted successfully.");
        } catch (PDOException $e) {
            set_flash('error', 'Failed to delete inquiry: ' . $e->getMessage());
        }
        break;

    default:
        set_flash('error', 'Unrecognized action.');
        break;
}

redirect($redirect_to);
