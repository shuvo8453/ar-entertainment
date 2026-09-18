<?php
/**
 * AR Entertainment - View Single Lead / Inquiry
 * Phase 4.7: Management CRUD (admin/inquiries/view.php)
 */
require_once dirname(__DIR__) . '/auth_check.php';

$current_page = 'inquiries';
$page_title   = 'View Inquiry Details';

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    set_flash('error', 'Invalid inquiry ID.');
    redirect('admin/inquiries');
}

// Fetch inquiry
try {
    $stmt = db()->prepare("SELECT * FROM inquiries WHERE id = ?");
    $stmt->execute([$id]);
    $inquiry = $stmt->fetch();

    if (!$inquiry) {
        set_flash('error', 'Inquiry not found or may have been deleted.');
        redirect('admin/inquiries');
    }

    // Automatically mark as read if currently unread
    if ((int)$inquiry['is_read'] === 0) {
        $mark_read = db()->prepare("UPDATE inquiries SET is_read = 1 WHERE id = ?");
        $mark_read->execute([$id]);
        $inquiry['is_read'] = 1;
    }

    // Fetch Previous and Next inquiry IDs for quick navigation
    $prev_stmt = db()->prepare("SELECT id FROM inquiries WHERE id < ? ORDER BY id DESC LIMIT 1");
    $prev_stmt->execute([$id]);
    $prev_id = $prev_stmt->fetchColumn();

    $next_stmt = db()->prepare("SELECT id FROM inquiries WHERE id > ? ORDER BY id ASC LIMIT 1");
    $next_stmt->execute([$id]);
    $next_id = $next_stmt->fetchColumn();

} catch (PDOException $e) {
    set_flash('error', 'Database error: ' . $e->getMessage());
    redirect('admin/inquiries');
}

// Parse extra data JSON
$extra_data = [];
if (!empty($inquiry['extra_data_json'])) {
    $decoded = json_decode($inquiry['extra_data_json'], true);
    if (is_array($decoded)) {
        $extra_data = $decoded;
    }
}

// Form type badge styling
$type_configs = [
    'quote'   => ['label' => 'Quote Request', 'icon' => 'fa-calculator', 'class' => 'bg-success text-white'],
    'contact' => ['label' => 'Contact Inquiry', 'icon' => 'fa-envelope', 'class' => 'bg-primary text-white'],
    'careers' => ['label' => 'Career Application', 'icon' => 'fa-briefcase', 'class' => 'bg-info text-dark'],
    'survey'  => ['label' => 'Client Survey', 'icon' => 'fa-poll', 'class' => 'bg-warning text-dark']
];
$type_cfg = $type_configs[$inquiry['form_type']] ?? ['label' => ucfirst($inquiry['form_type']), 'icon' => 'fa-inbox', 'class' => 'bg-secondary text-white'];

// Sender Initials for Avatar
$name_parts = explode(' ', trim($inquiry['name']));
$initials = '';
foreach (array_slice($name_parts, 0, 2) as $part) {
    $initials .= strtoupper(mb_substr($part, 0, 1));
}
if (empty($initials)) {
    $initials = 'LE';
}

require_once ADMIN_PATH . '/includes/header.php';
require_once ADMIN_PATH . '/includes/sidebar.php';
?>

<div class="admin-main">
    <?php require_once ADMIN_PATH . '/includes/navbar.php'; ?>

    <!-- Content Body -->
    <main class="admin-content">
        <!-- Breadcrumb & Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/inquiries') ?>" class="text-muted text-decoration-none">Inquiries</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">#<?= $inquiry['id'] ?> - <?= htmlspecialchars($inquiry['name']) ?></li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                    <?= htmlspecialchars($inquiry['name']) ?>
                    <span class="badge <?= $type_cfg['class'] ?> rounded-pill fs-6 px-3 py-1">
                        <i class="fa-solid <?= $type_cfg['icon'] ?> me-1"></i> <?= $type_cfg['label'] ?>
                    </span>
                </h3>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= site_url('admin/inquiries') ?>" class="btn btn-ar-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Inbox
                </a>
                <a href="mailto:<?= htmlspecialchars($inquiry['email']) ?>?subject=<?= urlencode('Re: ' . ($inquiry['subject'] ?: 'Inquiry with AR Entertainment')) ?>" class="btn btn-ar-primary">
                    <i class="fa-solid fa-reply me-1"></i> Reply by Email
                </a>
            </div>
        </div>

        <?= render_flash() ?>

        <div class="row g-4">
            <!-- Left Column: Main Message & Extra Details -->
            <div class="col-12 col-lg-8">
                <!-- Sender & Subject Header Card -->
                <div class="card-ar mb-4">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, var(--ar-primary), #9c0008); color: #fff; font-size: 1.3rem; font-weight: 700; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(229,9,20,0.3);">
                                <?= htmlspecialchars($initials) ?>
                            </div>
                            <div>
                                <h5 class="fw-bold text-white mb-1"><?= htmlspecialchars($inquiry['name']) ?></h5>
                                <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size: 0.9rem;">
                                    <a href="mailto:<?= htmlspecialchars($inquiry['email']) ?>" class="text-info text-decoration-none">
                                        <i class="fa-solid fa-envelope me-1"></i> <?= htmlspecialchars($inquiry['email']) ?>
                                    </a>
                                    <?php if (!empty($inquiry['phone'])): ?>
                                        <a href="tel:<?= htmlspecialchars($inquiry['phone']) ?>" class="text-success text-decoration-none">
                                            <i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($inquiry['phone']) ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-lg-end text-muted" style="font-size: 0.85rem;">
                            <div><i class="fa-regular fa-clock me-1"></i> <?= date('M d, Y · h:i A', strtotime($inquiry['created_at'])) ?></div>
                            <small class="text-secondary">(<?= htmlspecialchars(format_date($inquiry['created_at'], 'l, F j, Y')) ?>)</small>
                        </div>
                    </div>

                    <!-- Subject Headline -->
                    <div class="mb-3">
                        <span class="text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Subject</span>
                        <h5 class="fw-bold text-white mt-1">
                            <?= !empty($inquiry['subject']) ? htmlspecialchars($inquiry['subject']) : '<em class="text-muted font-monospace">No Subject Specified</em>' ?>
                        </h5>
                    </div>

                    <!-- Message Body -->
                    <div class="mb-2">
                        <span class="text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Message Content</span>
                        <div class="p-3 mt-2 rounded-3 bg-dark bg-opacity-75 border border-secondary text-white" style="line-height: 1.7; font-size: 0.96rem; white-space: pre-wrap; word-break: break-word;">
<?= htmlspecialchars($inquiry['message']) ?>
                        </div>
                    </div>
                </div>

                <!-- Structured Additional Fields Card (if available) -->
                <?php if (!empty($extra_data)): ?>
                    <div class="card-ar mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                            <h6 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-sliders text-warning"></i> Additional Submission Data
                            </h6>
                            <span class="badge bg-secondary bg-opacity-25 text-info px-2 py-1" style="font-size: 0.75rem;">
                                <?= count($extra_data) ?> Field(s)
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-dark table-borderless table-striped align-middle mb-0" style="font-size: 0.92rem;">
                                <tbody>
                                    <?php foreach ($extra_data as $key => $val): ?>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="width: 32%; text-transform: capitalize;">
                                                <i class="fa-solid fa-angle-right text-secondary me-1" style="font-size: 0.75rem;"></i>
                                                <?= htmlspecialchars(str_replace(['_', '-'], ' ', $key)) ?>
                                            </td>
                                            <td class="text-white">
                                                <?php if (is_array($val)): ?>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <?php foreach ($val as $tag): ?>
                                                            <span class="badge bg-secondary bg-opacity-50 text-white fw-normal"><?= htmlspecialchars($tag) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php elseif (filter_var($val, FILTER_VALIDATE_URL)): ?>
                                                    <a href="<?= htmlspecialchars($val) ?>" target="_blank" class="text-info text-decoration-none">
                                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> <?= htmlspecialchars($val) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="fw-medium"><?= htmlspecialchars((string) $val) ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Meta & Actions -->
            <div class="col-12 col-lg-4">
                <!-- Status & Action Box -->
                <div class="card-ar mb-4">
                    <h6 class="fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Lead Management
                    </h6>

                    <div class="d-grid gap-2 mb-3">
                        <a href="mailto:<?= htmlspecialchars($inquiry['email']) ?>?subject=<?= urlencode('Re: ' . ($inquiry['subject'] ?: 'Inquiry with AR Entertainment')) ?>" class="btn btn-ar-primary py-2 fw-semibold text-center">
                            <i class="fa-solid fa-paper-plane me-2"></i> Reply by Email
                        </a>

                        <?php if (!empty($inquiry['phone'])): ?>
                            <a href="tel:<?= htmlspecialchars($inquiry['phone']) ?>" class="btn btn-outline-success py-2 text-center">
                                <i class="fa-solid fa-phone me-2"></i> Call <?= htmlspecialchars($inquiry['phone']) ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="border-top border-secondary border-opacity-25 pt-3 d-flex flex-column gap-2">
                        <!-- Toggle Read / Unread -->
                        <form method="POST" action="<?= site_url('admin/inquiries/action.php') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $inquiry['id'] ?>">
                            <input type="hidden" name="redirect_to" value="admin/inquiries/view.php?id=<?= $inquiry['id'] ?>">
                            <input type="hidden" name="action" value="mark_unread">
                            <button type="submit" class="btn btn-ar-secondary w-100 btn-sm text-warning">
                                <i class="fa-solid fa-envelope me-1"></i> Mark as Unread
                            </button>
                        </form>

                        <!-- Delete Button -->
                        <button type="button" class="btn btn-outline-danger w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#deleteInquiryModal">
                            <i class="fa-solid fa-trash me-1"></i> Delete Inquiry
                        </button>
                    </div>
                </div>

                <!-- Technical Telemetry Card -->
                <div class="card-ar mb-4">
                    <h6 class="fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                        <i class="fa-solid fa-server text-info me-1"></i> Technical Details
                    </h6>

                    <div class="d-flex flex-column gap-3" style="font-size: 0.88rem;">
                        <div>
                            <div class="text-muted small">Inquiry Reference ID</div>
                            <div class="text-white font-monospace fw-bold">#AR-INQ-<?= str_pad((string)$inquiry['id'], 5, '0', STR_PAD_LEFT) ?></div>
                        </div>

                        <div>
                            <div class="text-muted small">Form Type</div>
                            <div class="text-white fw-medium">
                                <span class="badge <?= $type_cfg['class'] ?>"><?= $type_cfg['label'] ?></span>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small">IP Address</div>
                            <div class="text-white font-monospace">
                                <?= !empty($inquiry['ip_address']) ? htmlspecialchars($inquiry['ip_address']) : '—' ?>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small">Client Device / User Agent</div>
                            <div class="text-secondary small font-monospace text-break">
                                <?= !empty($inquiry['user_agent']) ? htmlspecialchars($inquiry['user_agent']) : '—' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Controls (Prev / Next) -->
                <div class="card-ar">
                    <h6 class="fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                        <i class="fa-solid fa-compass text-secondary me-1"></i> Navigate Leads
                    </h6>
                    <div class="d-flex justify-content-between gap-2">
                        <?php if ($prev_id): ?>
                            <a href="<?= site_url('admin/inquiries/view.php?id=' . $prev_id) ?>" class="btn btn-sm btn-ar-secondary flex-grow-1">
                                <i class="fa-solid fa-chevron-left me-1"></i> Prev Lead
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-ar-secondary flex-grow-1 opacity-50" disabled>
                                <i class="fa-solid fa-chevron-left me-1"></i> Prev Lead
                            </button>
                        <?php endif; ?>

                        <?php if ($next_id): ?>
                            <a href="<?= site_url('admin/inquiries/view.php?id=' . $next_id) ?>" class="btn btn-sm btn-ar-secondary flex-grow-1">
                                Next Lead <i class="fa-solid fa-chevron-right ms-1"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-ar-secondary flex-grow-1 opacity-50" disabled>
                                Next Lead <i class="fa-solid fa-chevron-right ms-1"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once ADMIN_PATH . '/includes/footer.php'; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteInquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--ar-card-bg); border: 1px solid var(--ar-border-color); color: #fff;">
            <div class="modal-header border-bottom" style="border-color: var(--ar-border-color) !important;">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Delete Inquiry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1 text-muted">Are you sure you want to permanently delete this inquiry from <strong class="text-white"><?= htmlspecialchars($inquiry['name']) ?></strong>?</p>
                <p class="small text-danger mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-top" style="border-color: var(--ar-border-color) !important;">
                <button type="button" class="btn btn-ar-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= site_url('admin/inquiries/action.php') ?>" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $inquiry['id'] ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="redirect_to" value="admin/inquiries">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fa-solid fa-trash me-1"></i> Confirm Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
