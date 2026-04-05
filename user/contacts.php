<?php
/**
 * user/contacts.php — My Contact Submissions
 */

require_once __DIR__ . '/../bootstrap.php';
requireLogin();
if (isAdmin()) redirect('/admin/contacts.php');

$db     = Database::getInstance();
$userId = currentUser()['id'];

$page  = max(1, (int)($_GET['page'] ?? 1));
$total = (int)$db->fetchOne(
    "SELECT COUNT(*) AS c FROM contact_submissions WHERE user_id = ?", [$userId]
)['c'];
$pager = paginate($total, 15, $page);

$contacts = $db->fetchAll(
    "SELECT * FROM contact_submissions WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
    [$userId, $pager['per_page'], $pager['offset']]
);

$pageTitle      = 'My Submissions | ' . APP_NAME;
$activeUserPage = 'contacts';
$topbarTitle    = 'My Contact Submissions';

include __DIR__ . '/includes/panel_head.php';
?>

<div class="panel-card-header standalone">
    <h3>My Enquiries <span class="badge-pill badge-blue"><?= $total ?></span></h3>
    <a href="<?= APP_URL ?>/pages/contact.php" class="btn-panel-primary">
        <i class="bi bi-plus-lg"></i> New Enquiry
    </a>
</div>

<div class="panel-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service Requested</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="5" class="table-empty">
                            You haven't submitted any enquiries yet.
                            <a href="<?= APP_URL ?>/pages/contact.php">Submit an enquiry →</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contacts as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><span class="badge-pill badge-blue"><?= e($c['service'] ?: 'General') ?></span></td>
                            <td><?= e(substr($c['message'], 0, 80)) . (strlen($c['message']) > 80 ? '…' : '') ?></td>
                            <td><?= date('d M Y, g:i A', strtotime($c['created_at'])) ?></td>
                            <td>
                                <span class="badge-pill badge-green">Received</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager['total_pages'] > 1): ?>
        <div class="panel-pagination">
            <?php if ($pager['has_prev']): ?><a href="?page=<?= $page-1 ?>" class="page-btn">← Previous</a><?php endif; ?>
            <span>Page <?= $page ?> of <?= $pager['total_pages'] ?></span>
            <?php if ($pager['has_next']): ?><a href="?page=<?= $page+1 ?>" class="page-btn">Next →</a><?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/panel_footer.php'; ?>
