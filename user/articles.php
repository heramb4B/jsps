<?php
/**
 * user/articles.php — My Articles
 */

require_once __DIR__ . '/../bootstrap.php';
requireLogin();
if (isAdmin()) redirect('/admin/articles.php');

$db     = Database::getInstance();
$userId = currentUser()['id'];

// Delete own article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delId = (int)$_POST['delete_id'];
    // Ensure user owns this article
    $owns = $db->fetchOne("SELECT id FROM articles WHERE id = ? AND user_id = ?", [$delId, $userId]);
    if ($owns) {
        $db->execute("DELETE FROM articles WHERE id = ?", [$delId]);
        setFlash('success', 'Article deleted.');
    }
    redirect('/user/articles.php');
}

$page  = max(1, (int)($_GET['page'] ?? 1));
$total = (int)$db->fetchOne("SELECT COUNT(*) AS c FROM articles WHERE user_id = ?", [$userId])['c'];
$pager = paginate($total, 15, $page);

$articles = $db->fetchAll(
    "SELECT * FROM articles WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
    [$userId, $pager['per_page'], $pager['offset']]
);

$pageTitle      = 'My Articles | ' . APP_NAME;
$activeUserPage = 'articles';
$topbarTitle    = 'My Articles';

include __DIR__ . '/includes/panel_head.php';
?>

<div class="panel-card-header standalone">
    <h3>My Articles <span class="badge-pill badge-blue"><?= $total ?></span></h3>
    <a href="<?= APP_URL ?>/user/article-form.php" class="btn-panel-primary">
        <i class="bi bi-plus-lg"></i> Write Article
    </a>
</div>

<div class="panel-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                    <tr><td colspan="6" class="table-empty">
                        You haven't written any articles yet.
                        <a href="<?= APP_URL ?>/user/article-form.php">Write your first one →</a>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($articles as $a): ?>
                        <tr>
                            <td><?= $a['id'] ?></td>
                            <td><strong><?= e(substr($a['title'],0,55)) . (strlen($a['title'])>55?'…':'') ?></strong></td>
                            <td><span class="badge-pill badge-blue"><?= e($a['category']) ?></span></td>
                            <td><span class="badge-pill <?= $a['status']==='published'?'badge-green':'badge-gold' ?>"><?= ucfirst($a['status']) ?></span></td>
                            <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                            <td class="td-actions">
                                <?php if ($a['status'] === 'published'): ?>
                                    <a href="<?= APP_URL ?>/pages/blog-detail.php?slug=<?= e($a['slug']) ?>"
                                       target="_blank" class="btn-action btn-action-blue" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= APP_URL ?>/user/article-form.php?id=<?= $a['id'] ?>"
                                   class="btn-action btn-action-gold" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete this article permanently?')">
                                    <input type="hidden" name="delete_id" value="<?= $a['id'] ?>">
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

    <?php if ($pager['total_pages'] > 1): ?>
        <div class="panel-pagination">
            <?php if ($pager['has_prev']): ?><a href="?page=<?= $page-1 ?>" class="page-btn">← Previous</a><?php endif; ?>
            <span>Page <?= $page ?> of <?= $pager['total_pages'] ?></span>
            <?php if ($pager['has_next']): ?><a href="?page=<?= $page+1 ?>" class="page-btn">Next →</a><?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/panel_footer.php'; ?>
