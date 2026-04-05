<?php
/**
 * admin/contacts.php — View & Manage Contact Submissions
 */

require_once __DIR__ . '/../bootstrap.php';
requireAdmin();

$db = Database::getInstance();

// Mark as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    $id = (int)$_POST['mark_read'];
    $db->execute("UPDATE contact_submissions SET is_read = 1 WHERE id = ?", [$id]);
    setFlash('success', 'Submission marked as read.');
    redirect('/admin/contacts.php');
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $db->execute("DELETE FROM contact_submissions WHERE id = ?", [$id]);
    setFlash('success', 'Submission deleted.');
    redirect('/admin/contacts.php');
}

// Pagination
$page    = max(1, (int)($_GET['page'] ?? 1));
$total   = (int)$db->fetchOne("SELECT COUNT(*) AS c FROM contact_submissions")['c'];
$pager   = paginate($total, 15, $page);

$contacts = $db->fetchAll(
    "SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT ? OFFSET ?",
    [$pager['per_page'], $pager['offset']]
);

$pageTitle       = 'Contact Submissions | Admin | ' . APP_NAME;
$activeAdminPage = 'contacts';
$topbarTitle     = 'Contact Submissions';

include __DIR__ . '/includes/panel_head.php';
?>

<div class="panel-card">
    <div class="panel-card-header">
        <h3>All Enquiries <span class="badge-pill badge-blue"><?= $total ?></span></h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr><td colspan="9" class="table-empty">No contact submissions yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($contacts as $c): ?>
                        <tr class="<?= !$c['is_read'] ? 'row-unread' : '' ?>">
                            <td><?= $c['id'] ?></td>
                            <td><strong><?= e($c['full_name']) ?></strong></td>
                            <td><a href="mailto:<?= e($c['email']) ?>"><?= e($c['email']) ?></a></td>
                            <td><?= e($c['phone'] ?: '—') ?></td>
                            <td><span class="badge-pill badge-blue"><?= e($c['service'] ?: 'General') ?></span></td>
                            <td class="td-message" title="<?= e($c['message']) ?>">
                                <?= e(substr($c['message'], 0, 60)) . (strlen($c['message']) > 60 ? '…' : '') ?>
                            </td>
                            <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                            <td>
                                <span class="badge-pill <?= $c['is_read'] ? 'badge-green' : 'badge-gold' ?>">
                                    <?= $c['is_read'] ? 'Read' : 'Unread' ?>
                                </span>
                            </td>
                            <td class="td-actions">
                                <?php if (!$c['is_read']): ?>
                                    <form method="POST" style="display:inline">
                                        <input type="hidden" name="mark_read" value="<?= $c['id'] ?>">
                                        <button type="submit" class="btn-action btn-action-green" title="Mark as Read">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete this submission?')">
                                    <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
                                    <button type="submit" class="btn-action btn-action-red" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pager['total_pages'] > 1): ?>
        <div class="panel-pagination">
            <?php if ($pager['has_prev']): ?>
                <a href="?page=<?= $page - 1 ?>" class="page-btn">← Previous</a>
            <?php endif; ?>
            <span>Page <?= $page ?> of <?= $pager['total_pages'] ?></span>
            <?php if ($pager['has_next']): ?>
                <a href="?page=<?= $page + 1 ?>" class="page-btn">Next →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/panel_footer.php'; ?>
