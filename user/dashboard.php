<?php
/**
 * user/dashboard.php — User Dashboard
 */

require_once __DIR__ . '/../bootstrap.php';
requireLogin();

// Redirect admins to admin panel
if (isAdmin()) redirect('/admin/dashboard.php');

$db     = Database::getInstance();
$userId = currentUser()['id'];

$stats = [
    'articles' => (int)$db->fetchOne("SELECT COUNT(*) AS c FROM articles WHERE user_id = ?", [$userId])['c'],
    'contacts' => (int)$db->fetchOne("SELECT COUNT(*) AS c FROM contact_submissions WHERE user_id = ?", [$userId])['c'],
];

$recentArticles = $db->fetchAll(
    "SELECT * FROM articles WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
    [$userId]
);

$recentContacts = $db->fetchAll(
    "SELECT * FROM contact_submissions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
    [$userId]
);

$pageTitle      = 'My Dashboard | ' . APP_NAME;
$activeUserPage = 'dashboard';
$topbarTitle    = 'My Dashboard';

include __DIR__ . '/includes/panel_head.php';
?>

<!-- Stat Cards -->
<div class="panel-stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-green"><i class="bi bi-newspaper"></i></div>
        <div class="stat-body">
            <div class="stat-num"><?= $stats['articles'] ?></div>
            <div class="stat-lbl">My Articles</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-blue"><i class="bi bi-inbox-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num"><?= $stats['contacts'] ?></div>
            <div class="stat-lbl">My Enquiries</div>
        </div>
    </div>
</div>

<div class="dashboard-tables-row">

    <!-- Recent Articles -->
    <div class="panel-card">
        <div class="panel-card-header">
            <h3>My Recent Articles</h3>
            <a href="<?= APP_URL ?>/user/articles.php" class="card-header-link">View All</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>Title</th><th>Category</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    <?php if (empty($recentArticles)): ?>
                        <tr><td colspan="4" class="table-empty">
                            No articles yet.
                            <a href="<?= APP_URL ?>/user/article-form.php">Write your first article →</a>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($recentArticles as $a): ?>
                            <tr>
                                <td><?= e(substr($a['title'],0,50)) . (strlen($a['title'])>50?'…':'') ?></td>
                                <td><span class="badge-pill badge-blue"><?= e($a['category']) ?></span></td>
                                <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                                <td><span class="badge-pill <?= $a['status']==='published'?'badge-green':'badge-gold' ?>"><?= ucfirst($a['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Enquiries -->
    <div class="panel-card">
        <div class="panel-card-header">
            <h3>My Recent Enquiries</h3>
            <a href="<?= APP_URL ?>/user/contacts.php" class="card-header-link">View All</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>Service</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    <?php if (empty($recentContacts)): ?>
                        <tr><td colspan="3" class="table-empty">
                            No enquiries yet.
                            <a href="<?= APP_URL ?>/pages/contact.php">Contact us →</a>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($recentContacts as $c): ?>
                            <tr>
                                <td><?= e($c['service'] ?: 'General') ?></td>
                                <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                                <td><span class="badge-pill badge-green">Received</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/panel_footer.php'; ?>
