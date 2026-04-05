<?php
/**
 * admin/users.php — Manage Users
 */

require_once __DIR__ . '/../bootstrap.php';
requireAdmin();

$db      = Database::getInstance();
$myId    = currentUser()['id'];
$errors  = [];

// Delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delId = (int)$_POST['delete_id'];
    if ($delId === (int)$myId) {
        setFlash('error', 'You cannot delete your own account.');
    } else {
        $db->execute("DELETE FROM users WHERE id = ?", [$delId]);
        setFlash('success', 'User deleted successfully.');
    }
    redirect('/admin/users.php');
}

// Change role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_role_id'])) {
    $uid  = (int)$_POST['change_role_id'];
    $role = in_array($_POST['new_role'] ?? '', ['admin','user']) ? $_POST['new_role'] : 'user';
    if ($uid === (int)$myId) {
        setFlash('error', 'You cannot change your own role.');
    } else {
        $db->execute("UPDATE users SET role = ? WHERE id = ?", [$role, $uid]);
        setFlash('success', 'User role updated.');
    }
    redirect('/admin/users.php');
}

$page  = max(1, (int)($_GET['page'] ?? 1));
$total = (int)$db->fetchOne("SELECT COUNT(*) AS c FROM users")['c'];
$pager = paginate($total, 20, $page);

$users = $db->fetchAll(
    "SELECT u.*, (SELECT COUNT(*) FROM articles WHERE user_id = u.id) AS article_count,
            (SELECT COUNT(*) FROM contact_submissions WHERE user_id = u.id) AS contact_count
     FROM users u ORDER BY u.created_at DESC LIMIT ? OFFSET ?",
    [$pager['per_page'], $pager['offset']]
);

$pageTitle       = 'Manage Users | Admin | ' . APP_NAME;
$activeAdminPage = 'users';
$topbarTitle     = 'Manage Users';

include __DIR__ . '/includes/panel_head.php';
?>

<div class="panel-card-header standalone">
    <h3>Registered Users <span class="badge-pill badge-blue"><?= $total ?></span></h3>
</div>

<div class="panel-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Articles</th>
                    <th>Enquiries</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="9" class="table-empty">No users found.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <?php $isSelf = ((int)$u['id'] === (int)$myId); ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-cell-avatar">
                                        <?= strtoupper(substr($u['first_name'],0,1) . substr($u['last_name'],0,1)) ?>
                                    </div>
                                    <div>
                                        <strong><?= e($u['first_name'] . ' ' . $u['last_name']) ?></strong>
                                        <?php if ($isSelf): ?><span class="hint"> (You)</span><?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= e($u['email']) ?></td>
                            <td>
                                <?php if (!$isSelf): ?>
                                    <form method="POST" style="display:inline">
                                        <input type="hidden" name="change_role_id" value="<?= $u['id'] ?>">
                                        <select name="new_role" onchange="this.form.submit()"
                                                class="role-select">
                                            <option value="user"  <?= $u['role'] === 'user'  ? 'selected' : '' ?>>User</option>
                                            <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </form>
                                <?php else: ?>
                                    <span class="badge-pill badge-blue">ADMIN</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $u['article_count'] ?></td>
                            <td><?= $u['contact_count'] ?></td>
                            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <span class="badge-pill <?= $u['is_active'] ? 'badge-green' : 'badge-red' ?>">
                                    <?= $u['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="td-actions">
                                <?php if (!$isSelf): ?>
                                    <form method="POST" style="display:inline"
                                          onsubmit="return confirm('Delete user <?= e(addslashes($u['first_name'])) ?>? This also deletes all their articles.')">
                                        <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn-action btn-action-red" title="Delete User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="hint">Protected</span>
                                <?php endif; ?>
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
