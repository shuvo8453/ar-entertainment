<?php
/**
 * AR Entertainment - Leads & Inquiries CSV Exporter
 * Phase 4.7: Management CRUD (admin/inquiries/export.php)
 * Generates an Excel-compatible UTF-8 CSV download for filtered or all inquiries.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';

require_login();

// Filter inputs
$type_filter   = trim($_GET['type'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$search        = trim($_GET['q'] ?? '');

$valid_types = ['contact', 'quote', 'careers', 'survey'];
if (!in_array($type_filter, $valid_types, true)) {
    $type_filter = '';
}

$valid_statuses = ['unread', 'read'];
if (!in_array($status_filter, $valid_statuses, true)) {
    $status_filter = '';
}

// Build query
$where  = [];
$params = [];

if ($type_filter !== '') {
    $where[] = "form_type = :type";
    $params[':type'] = $type_filter;
}

if ($status_filter !== '') {
    $where[] = "is_read = :is_read";
    $params[':is_read'] = ($status_filter === 'read') ? 1 : 0;
}

if ($search !== '') {
    $where[] = "(name LIKE :q OR email LIKE :q2 OR phone LIKE :q3 OR subject LIKE :q4 OR message LIKE :q5)";
    $params[':q']  = "%{$search}%";
    $params[':q2'] = "%{$search}%";
    $params[':q3'] = "%{$search}%";
    $params[':q4'] = "%{$search}%";
    $params[':q5'] = "%{$search}%";
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

try {
    $stmt = db()->prepare("SELECT * FROM inquiries {$where_sql} ORDER BY created_at DESC");
    $stmt->execute($params);
    $inquiries = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Export failed: " . htmlspecialchars($e->getMessage()));
}

$filename = 'ar_entertainment_leads_' . date('Y-m-d_His') . '.csv';

// Output CSV headers
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Open php://output
$output = fopen('php://output', 'w');

// Add UTF-8 BOM so Microsoft Excel parses special characters & formatting properly
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// CSV Header Row
fputcsv($output, [
    'ID',
    'Form Type',
    'Sender Name',
    'Email Address',
    'Phone Number',
    'Subject',
    'Message Content',
    'Additional Fields / Metadata',
    'Read Status',
    'IP Address',
    'User Agent',
    'Date Submitted'
]);

foreach ($inquiries as $row) {
    // Format form type label
    $type_labels = [
        'contact' => 'Contact Message',
        'quote'   => 'Quote Request',
        'careers' => 'Career Application',
        'survey'  => 'Survey Feedback'
    ];
    $form_type_str = $type_labels[$row['form_type']] ?? ucfirst($row['form_type']);

    // Format extra data JSON into readable text
    $extra_details_str = '';
    if (!empty($row['extra_data_json'])) {
        $extra = json_decode($row['extra_data_json'], true);
        if (is_array($extra)) {
            $formatted_pairs = [];
            foreach ($extra as $key => $val) {
                $clean_key = ucwords(str_replace(['_', '-'], ' ', $key));
                if (is_array($val)) {
                    $val = implode(', ', $val);
                }
                $formatted_pairs[] = "{$clean_key}: {$val}";
            }
            $extra_details_str = implode(" | ", $formatted_pairs);
        } else {
            $extra_details_str = $row['extra_data_json'];
        }
    }

    fputcsv($output, [
        $row['id'],
        $form_type_str,
        $row['name'],
        $row['email'],
        $row['phone'] ?? '',
        $row['subject'] ?? '',
        $row['message'],
        $extra_details_str,
        ($row['is_read'] == 1) ? 'Read' : 'Unread',
        $row['ip_address'] ?? '',
        $row['user_agent'] ?? '',
        date('Y-m-d H:i:s', strtotime($row['created_at']))
    ]);
}

fclose($output);
exit;
