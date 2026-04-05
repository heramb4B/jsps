<?php
/**
 * admin/articles.php — Manage All Articles
 */

require_once __DIR__ . '/../bootstrap.php';
requireAdmin();

$db = Database::getInstance();

// Delete article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $db->execute("DELETE FROM articles WHERE id = ?", [(int)$_POST['delete_id']]);
    setFlash('success', 'Article deleted successfully.');
    redirect('/admin/articles.php');
}

// Toggle status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    $id      = (int)$_POST['toggle_id'];
    $current = $db->fetchOne("SELECT status FROM articles WHERE id = ?", [$id])['status'] ?? '';
    $new     = $current === 'published' ? 'draft' : 'published';
    $db->execute("UPDATE articles SET status = ? WHERE id = ?", [$new, $id]);
    setFlash('success', 'Article status updated.');
    redirect('/admin/articles.php');
}

$page  = max(1, (int)($_GET['page'] ?? 1));
$total = (int)$db->fetchOne("SELECT COUNT(*) AS c FROM articles")['c'];
$pager = paginate($total, 15, $page);

$articles = $db->fetchAll(
    "SELECT a.*, u.first_name, u.last_name
     FROM articles a JOIN users u ON a.user_id = u.id
     ORDER BY a.created_at DESC
     LIMIT ? OFFSET ?",
    [$pager['per_page'], $pager['offset']]
);

$pageTitle       = 'All Articles | Admin | ' . APP_NAME;
$activeAdminPage = 'articles';
$topbarTitle     = 'All Articles';

include __DIR__ . '/includes/panel_head.php';
?>

<div class="panel-card-header standalone">
    <h3>All Articles <span class="badge-pill badge-blue"><?= $total ?></span></h3>
    <a href="<?= APP_URL ?>/admin/article-form.php" class="btn-panel-primary">
        <i class="bi bi-plus-lg"></i> Add New Article
    </a>
</div>

<div class="panel-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                    <tr><td colspan="7" class="table-empty">No articles found.</td></tr>
                <?php else: ?>
                    <?php foreach ($articles as $a): ?>
                        <tr>
                            <td><?= $a['id'] ?></td>
                            <td>
                                <strong><?= e(substr($a['title'], 0, 55)) . (strlen($a['title']) > 55 ? '…' : '') ?></strong>
                            </td>
                            <td><?= e($a['first_name'] . ' ' . $a['last_name']) ?></td>
                            <td><span class="badge-pill badge-blue"><?= e($a['category']) ?></span></td>
                            <td>
                                <span class="badge-pill <?= $a['status'] === 'published' ? 'badge-green' : 'badge-gold' ?>">
                                    <?= ucfirst($a['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                            <td class="td-actions">
                                <a href="<?= APP_URL ?>/pages/blog-detail.php?slug=<?= e($a['slug']) ?>"
                                   target="_blank" class="btn-action btn-action-blue" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= APP_URL ?>/admin/article-form.php?id=<?= $a['id'] ?>"
                                   class="btn-action btn-action-gold" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="toggle_id" value="<?= $a['id'] ?>">
                                    <button type="submit" class="btn-action btn-action-green"
                                            title="Toggle Status">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                                <form method="POST" style="display:inline"
                                      onsubmit="return confirm('Permanently delete this article?')">
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
