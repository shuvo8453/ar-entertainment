<?php
/**
 * AR Entertainment - Leads & Inquiries Inbox
 * Phase 4.7: Management CRUD (admin/inquiries/index.php)
 */
require_once dirname(__DIR__) . '/auth_check.php';

$page_title   = 'Leads & Inquiries';
$current_page = 'inquiries';

// Filter inputs
$type_filter   = trim($_GET['type'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$search        = trim($_GET['q'] ?? '');
$page          = max(1, (int) ($_GET['page'] ?? 1));
$per_page      = 15;

// Allowed form types
$valid_types = ['contact', 'quote', 'careers', 'survey'];
if (!in_array($type_filter, $valid_types, true)) {
    $type_filter = '';
}

// Allowed read statuses
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

// Count total matching items
try {
    $count_stmt = db()->prepare("SELECT COUNT(*) FROM inquiries {$where_sql}");
    $count_stmt->execute($params);
    $total_items = (int) $count_stmt->fetchColumn();
} catch (PDOException $e) {
    $total_items = 0;
    set_flash('error', 'Database error: ' . $e->getMessage());
}

$total_pages = max(1, (int) ceil($total_items / $per_page));
if ($page > $total_pages) {
    $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

// Fetch inquiries
$inquiries = [];
try {
    $sql = "
        SELECT * FROM inquiries
        {$where_sql}
        ORDER BY is_read ASC, created_at DESC
        LIMIT :limit OFFSET :offset
    ";
    $stmt = db()->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $inquiries = $stmt->fetchAll();
} catch (PDOException $e) {
    set_flash('error', 'Failed to retrieve inquiries: ' . $e->getMessage());
}

// Fetch stats counts
$stats = [
    'total'   => 0,
    'unread'  => 0,
    'read'    => 0,
    'quote'   => 0,
    'contact' => 0,
    'careers' => 0,
    'survey'  => 0
];
try {
    $stats_stmt = db()->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread,
            SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as `read`,
            SUM(CASE WHEN form_type = 'quote' THEN 1 ELSE 0 END) as `quote`,
            SUM(CASE WHEN form_type = 'contact' THEN 1 ELSE 0 END) as `contact`,
            SUM(CASE WHEN form_type = 'careers' THEN 1 ELSE 0 END) as `careers`,
            SUM(CASE WHEN form_type = 'survey' THEN 1 ELSE 0 END) as `survey`
        FROM inquiries
    ");
    $stats = $stats_stmt->fetch() ?: $stats;
} catch (Exception $e) {}

// Form type badge configurations
$type_configs = [
    'quote'   => ['label' => 'Quote Request', 'icon' => 'fa-calculator', 'class' => 'bg-success text-white'],
    'contact' => ['label' => 'Contact Inquiry', 'icon' => 'fa-envelope', 'class' => 'bg-primary text-white'],
    'careers' => ['label' => 'Career Application', 'icon' => 'fa-briefcase', 'class' => 'bg-info text-dark'],
    'survey'  => ['label' => 'Client Survey', 'icon' => 'fa-poll', 'class' => 'bg-warning text-dark']
];

$export_query = http_build_query([
    'type'   => $type_filter,
    'status' => $status_filter,
    'q'      => $search
]);

require_once ADMIN_PATH . '/includes/header.php';
require_once ADMIN_PATH . '/includes/sidebar.php';
?>

<div class="admin-main">
    <?php require_once ADMIN_PATH . '/includes/navbar.php'; ?>

    <!-- Main Content Body -->
    <main class="admin-content">
        <!-- Breadcrumb & Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Leads &amp; Inquiries</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    Leads &amp; Inquiries Inbox
                    <?php if (($stats['unread'] ?? 0) > 0): ?>
                        <span class="badge bg-danger rounded-pill fs-6 px-2 py-1"><?= (int)$stats['unread'] ?> Unread</span>
                    <?php endif; ?>
                </h3>
            </div>
            <div>
                <a href="<?= site_url('admin/inquiries/export.php?' . $export_query) ?>" class="btn btn-ar-secondary text-success">
                    <i class="fa-solid fa-file-excel me-1"></i> Export CSV
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-danger"><?= (int) ($stats['unread'] ?? 0) ?></div>
                        <div class="stat-label">Unread Leads</div>
                    </div>
                    <div class="stat-icon icon-red">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-number"><?= (int) ($stats['total'] ?? 0) ?></div>
                        <div class="stat-label">Total Inquiries</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-success"><?= (int) ($stats['quote'] ?? 0) ?></div>
                        <div class="stat-label">Quote Requests</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-info"><?= (int) ($stats['contact'] ?? 0) ?></div>
                        <div class="stat-label">Messages</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-number text-warning"><?= (int) ($stats['careers'] ?? 0) ?></div>
                        <div class="stat-label">Careers</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-number" style="color: #c084fc;"><?= (int) ($stats['survey'] ?? 0) ?></div>
                        <div class="stat-label">Surveys</div>
                    </div>
                    <div class="stat-icon icon-purple">
                        <i class="fa-solid fa-poll"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="card-ar p-3 mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <!-- Form Type Tabs -->
                <ul class="nav nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link <?= empty($type_filter) ? 'active' : '' ?>" href="<?= site_url('admin/inquiries/?' . http_build_query(array_merge($_GET, ['type' => '', 'page' => 1]))) ?>">
                            All Types <span class="badge bg-secondary ms-1"><?= (int) ($stats['total'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type_filter === 'quote') ? 'active' : '' ?>" href="<?= site_url('admin/inquiries/?' . http_build_query(array_merge($_GET, ['type' => 'quote', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-calculator me-1 text-success"></i> Quotes <span class="badge bg-success ms-1"><?= (int) ($stats['quote'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type_filter === 'contact') ? 'active' : '' ?>" href="<?= site_url('admin/inquiries/?' . http_build_query(array_merge($_GET, ['type' => 'contact', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-envelope me-1 text-primary"></i> Contact <span class="badge bg-primary ms-1"><?= (int) ($stats['contact'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type_filter === 'careers') ? 'active' : '' ?>" href="<?= site_url('admin/inquiries/?' . http_build_query(array_merge($_GET, ['type' => 'careers', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-briefcase me-1 text-info"></i> Careers <span class="badge bg-info text-dark ms-1"><?= (int) ($stats['careers'] ?? 0) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($type_filter === 'survey') ? 'active' : '' ?>" href="<?= site_url('admin/inquiries/?' . http_build_query(array_merge($_GET, ['type' => 'survey', 'page' => 1]))) ?>">
                            <i class="fa-solid fa-poll me-1 text-warning"></i> Surveys <span class="badge bg-warning text-dark ms-1"><?= (int) ($stats['survey'] ?? 0) ?></span>
                        </a>
                    </li>
                </ul>

                <div>
                    <a href="<?= site_url('admin/inquiries/export.php?' . $export_query) ?>" class="btn btn-sm btn-ar-secondary text-success">
                        <i class="fa-solid fa-download me-1"></i> Download CSV
                    </a>
                </div>
            </div>

            <!-- Search & Status Row -->
            <form method="GET" action="<?= site_url('admin/inquiries') ?>" class="row g-2 align-items-center">
                <?php if (!empty($type_filter)): ?>
                    <input type="hidden" name="type" value="<?= htmlspecialchars($type_filter) ?>">
                <?php endif; ?>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="q" class="form-control bg-dark border-secondary text-white" placeholder="Search sender name, email, phone, or message text..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <select name="status" class="form-select bg-dark border-secondary text-white">
                        <option value="">All Statuses (<?= (int)$stats['total'] ?>)</option>
                        <option value="unread" <?= ($status_filter === 'unread') ? 'selected' : '' ?>>Unread Only (<?= (int)$stats['unread'] ?>)</option>
                        <option value="read" <?= ($status_filter === 'read') ? 'selected' : '' ?>>Read Only (<?= (int)$stats['read'] ?>)</option>
                    </select>
                </div>

                <div class="col-6 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-ar-primary flex-grow-1">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <?php if ($search !== '' || $status_filter !== '' || $type_filter !== ''): ?>
                        <a href="<?= site_url('admin/inquiries') ?>" class="btn btn-ar-secondary" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Bulk Action Form & Master Table -->
        <form method="POST" action="<?= site_url('admin/inquiries/action.php') ?>" id="bulkInquiriesForm">
            <?= csrf_field() ?>
            <input type="hidden" name="action" id="bulkActionInput" value="">
            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'admin/inquiries') ?>">

            <!-- Floating / Inline Bulk Controls Bar -->
            <div id="bulkControlsBar" class="alert alert-dark border-secondary d-none align-items-center justify-content-between p-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-check-double text-info"></i>
                    <span class="text-white fw-semibold" id="selectedCountText">0 items selected</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-ar-secondary" onclick="submitBulkAction('bulk_read')">
                        <i class="fa-solid fa-envelope-open me-1 text-success"></i> Mark as Read
                    </button>
                    <button type="button" class="btn btn-sm btn-ar-secondary" onclick="submitBulkAction('bulk_unread')">
                        <i class="fa-solid fa-envelope me-1 text-warning"></i> Mark as Unread
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmBulkDelete()">
                        <i class="fa-solid fa-trash me-1"></i> Delete Selected
                    </button>
                </div>
            </div>

            <!-- Inquiries List Table Card -->
            <div class="card-ar p-0 overflow-hidden">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--ar-border-color) !important;">
                    <h6 class="fw-bold mb-0 text-white">
                        Inquiries Feed <span class="badge bg-secondary ms-2"><?= number_format($total_items) ?> Total</span>
                    </h6>
                    <span class="text-muted small">Realtime Customer Leads</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark-custom align-middle mb-0" id="inquiriesTable">
                        <thead>
                            <tr>
                                <th style="width: 40px;" class="text-center">
                                    <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                                </th>
                                <th style="width: 45px;" class="text-center">Status</th>
                                <th style="width: 140px;">Type</th>
                                <th style="width: 220px;">Sender</th>
                                <th>Subject &amp; Message Snippet</th>
                                <th style="width: 140px;">Received</th>
                                <th style="width: 130px;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inquiries)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="mb-3">
                                            <i class="fa-solid fa-inbox fs-1 opacity-25"></i>
                                        </div>
                                        <h5 class="fw-bold text-white mb-1">No Inquiries Found</h5>
                                        <p class="mb-0 text-muted small">
                                            <?= ($search !== '' || $type_filter !== '' || $status_filter !== '') ? 'No inquiries matched your active search or filter criteria.' : 'Your inbox is currently clean. Form submissions will appear here live.' ?>
                                        </p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inquiries as $inq): ?>
                                    <?php
                                    $is_unread = ((int)$inq['is_read'] === 0);
                                    $cfg = $type_configs[$inq['form_type']] ?? ['label' => ucfirst($inq['form_type']), 'icon' => 'fa-inbox', 'class' => 'bg-secondary text-white'];

                                    // Initials
                                    $np = explode(' ', trim($inq['name']));
                                    $inq_initials = '';
                                    foreach (array_slice($np, 0, 2) as $p) {
                                        $inq_initials .= strtoupper(mb_substr($p, 0, 1));
                                    }
                                    if (empty($inq_initials)) {
                                        $inq_initials = 'LE';
                                    }

                                    // Extra data indicator
                                    $extra_count = 0;
                                    if (!empty($inq['extra_data_json'])) {
                                        $dec = json_decode($inq['extra_data_json'], true);
                                        if (is_array($dec)) {
                                            $extra_count = count($dec);
                                        }
                                    }
                                    ?>
                                    <tr class="<?= $is_unread ? 'fw-semibold' : '' ?>" style="<?= $is_unread ? 'background-color: rgba(229, 9, 20, 0.04);' : '' ?>">
                                        <!-- Checkbox -->
                                        <td class="text-center">
                                            <input type="checkbox" name="ids[]" value="<?= $inq['id'] ?>" class="form-check-input item-checkbox">
                                        </td>

                                        <!-- Status Indicator -->
                                        <td class="text-center">
                                            <?php if ($is_unread): ?>
                                                <span class="d-inline-block rounded-circle bg-danger" style="width: 10px; height: 10px; box-shadow: 0 0 8px rgba(239, 68, 68, 0.8);" title="Unread Lead"></span>
                                            <?php else: ?>
                                                <span class="d-inline-block rounded-circle bg-secondary bg-opacity-50" style="width: 8px; height: 8px;" title="Read"></span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Type -->
                                        <td>
                                            <span class="badge <?= $cfg['class'] ?> rounded-pill px-2 py-1" style="font-size: 0.76rem;">
                                                <i class="fa-solid <?= $cfg['icon'] ?> me-1"></i> <?= $cfg['label'] ?>
                                            </span>
                                        </td>

                                        <!-- Sender Info -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width: 34px; height: 34px; border-radius: 50%; background: <?= $is_unread ? 'linear-gradient(135deg, var(--ar-primary), #9c0008)' : '#242638' ?>; color: #fff; font-size: 0.82rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <?= htmlspecialchars($inq_initials) ?>
                                                </div>
                                                <div class="text-truncate">
                                                    <a href="<?= site_url('admin/inquiries/view.php?id=' . $inq['id']) ?>" class="text-decoration-none <?= $is_unread ? 'text-white fw-bold' : 'text-light' ?>">
                                                        <?= htmlspecialchars($inq['name']) ?>
                                                    </a>
                                                    <div class="small text-muted text-truncate" style="font-size: 0.8rem;">
                                                        <?= htmlspecialchars($inq['email']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Subject & Message Preview -->
                                        <td>
                                            <a href="<?= site_url('admin/inquiries/view.php?id=' . $inq['id']) ?>" class="text-decoration-none d-block">
                                                <div class="<?= $is_unread ? 'text-white fw-bold' : 'text-light' ?> text-truncate" style="max-width: 460px;">
                                                    <?= !empty($inq['subject']) ? htmlspecialchars($inq['subject']) : '<em class="text-muted">(No subject)</em>' ?>
                                                </div>
                                                <div class="text-muted small text-truncate" style="max-width: 460px; font-size: 0.82rem;">
                                                    <?= htmlspecialchars(truncate_text($inq['message'], 100)) ?>
                                                </div>
                                            </a>
                                            <?php if ($extra_count > 0): ?>
                                                <div class="mt-1">
                                                    <span class="badge bg-secondary bg-opacity-25 text-info" style="font-size: 0.72rem;">
                                                        <i class="fa-solid fa-paperclip me-1"></i> <?= $extra_count ?> custom field(s)
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Date -->
                                        <td class="text-nowrap" style="font-size: 0.84rem;">
                                            <div class="<?= $is_unread ? 'text-white' : 'text-muted' ?>">
                                                <?= date('M d, Y', strtotime($inq['created_at'])) ?>
                                            </div>
                                            <div class="text-muted small" style="font-size: 0.76rem;">
                                                <?= date('h:i A', strtotime($inq['created_at'])) ?>
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-end text-nowrap">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= site_url('admin/inquiries/view.php?id=' . $inq['id']) ?>" class="btn btn-ar-secondary text-info" title="View Lead Details">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <button type="button" class="btn btn-ar-secondary" onclick="toggleSingleStatus(<?= $inq['id'] ?>, '<?= $is_unread ? 'mark_read' : 'mark_unread' ?>')" title="<?= $is_unread ? 'Mark as Read' : 'Mark as Unread' ?>">
                                                    <i class="fa-solid <?= $is_unread ? 'fa-envelope-open text-success' : 'fa-envelope text-warning' ?>"></i>
                                                </button>

                                                <button type="button" class="btn btn-ar-secondary text-danger" onclick="confirmSingleDelete(<?= $inq['id'] ?>, '<?= htmlspecialchars(addslashes($inq['name'])) ?>')" title="Delete Inquiry">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <?php if ($total_pages > 1): ?>
                    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-color: var(--ar-border-color) !important;">
                        <div class="small text-muted">
                            Showing <?= min($offset + 1, $total_items) ?> to <?= min($offset + $per_page, $total_items) ?> of <?= number_format($total_items) ?> inquiries
                        </div>
                        <nav aria-label="Inbox pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link bg-dark border-secondary text-white" href="<?= site_url('admin/inquiries/?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>">
                                        &laquo;
                                    </a>
                                </li>
                                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                    <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                                        <a class="page-link <?= ($p === $page) ? 'bg-danger border-danger text-white' : 'bg-dark border-secondary text-white' ?>" href="<?= site_url('admin/inquiries/?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>">
                                            <?= $p ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link bg-dark border-secondary text-white" href="<?= site_url('admin/inquiries/?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>">
                                        &raquo;
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </main>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>

<!-- Single Action Standalone Hidden Form -->
<form method="POST" action="<?= site_url('admin/inquiries/action.php') ?>" id="singleActionForm" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="singleActionId" value="">
    <input type="hidden" name="action" id="singleActionType" value="">
    <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'admin/inquiries') ?>">
</form>

<!-- Single Delete Modal -->
<div class="modal fade" id="deleteSingleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
            <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Delete Inquiry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1 text-muted">Are you sure you want to delete the inquiry from <strong class="text-white" id="deleteTargetName">this sender</strong>?</p>
                <p class="small text-danger mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger px-4" id="confirmSingleDeleteBtn">
                    <i class="fa-solid fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="deleteBulkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
            <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Bulk Delete Inquiries
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1 text-muted">Are you sure you want to delete all <strong class="text-white" id="bulkDeleteCount">0</strong> selected inquiries?</p>
                <p class="small text-danger mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i> All selected leads will be permanently removed.</p>
            </div>
            <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger px-4" onclick="executeBulkDelete()">
                    <i class="fa-solid fa-trash me-1"></i> Confirm Bulk Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkControlsBar = document.getElementById('bulkControlsBar');
    const selectedCountText = document.getElementById('selectedCountText');

    function updateBulkBar() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkControlsBar.classList.remove('d-none');
            bulkControlsBar.classList.add('d-flex');
            selectedCountText.textContent = count + ' inquiry(ies) selected';
        } else {
            bulkControlsBar.classList.add('d-none');
            bulkControlsBar.classList.remove('d-flex');
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (count > 0 && count === itemCheckboxes.length);
            selectAllCheckbox.indeterminate = (count > 0 && count < itemCheckboxes.length);
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            updateBulkBar();
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });
});

function submitBulkAction(actionType) {
    document.getElementById('bulkActionInput').value = actionType;
    document.getElementById('bulkInquiriesForm').submit();
}

function confirmBulkDelete() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    document.getElementById('bulkDeleteCount').textContent = checkedBoxes.length;
    const modal = new bootstrap.Modal(document.getElementById('deleteBulkModal'));
    modal.show();
}

function executeBulkDelete() {
    document.getElementById('bulkActionInput').value = 'bulk_delete';
    document.getElementById('bulkInquiriesForm').submit();
}

function toggleSingleStatus(id, actionType) {
    document.getElementById('singleActionId').value = id;
    document.getElementById('singleActionType').value = actionType;
    document.getElementById('singleActionForm').submit();
}

let activeDeleteTargetId = 0;
function confirmSingleDelete(id, name) {
    activeDeleteTargetId = id;
    document.getElementById('deleteTargetName').textContent = name;
    const modal = new bootstrap.Modal(document.getElementById('deleteSingleModal'));
    modal.show();
}

document.getElementById('confirmSingleDeleteBtn')?.addEventListener('click', function () {
    if (activeDeleteTargetId > 0) {
        document.getElementById('singleActionId').value = activeDeleteTargetId;
        document.getElementById('singleActionType').value = 'delete';
        document.getElementById('singleActionForm').submit();
    }
});
</script>
