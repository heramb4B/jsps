<?php
/**
 * admin/dashboard.php — Admin Dashboard
 */

require_once __DIR__ . '/../bootstrap.php';
requireAdmin();

$pageTitle       = 'Dashboard | Admin | ' . APP_NAME;
$activeAdminPage = 'dashboard';
$topbarTitle     = 'Dashboard';

$db = Database::getInstance();

// Stats
$stats = [
    'users'    => (int)$db->fetchOne("SELECT COUNT(*) AS c FROM users")['c'],
    'articles' => (int)$db->fetchOne("SELECT COUNT(*) AS c FROM articles WHERE status = 'published'")['c'],
    'contacts' => (int)$db->fetchOne("SELECT COUNT(*) AS c FROM contact_submissions")['c'],
    'unread'   => (int)$db->fetchOne("SELECT COUNT(*) AS c FROM contact_submissions WHERE is_read = 0")['c'],
];

// Latest 5 contact submissions
$latestContacts = $db->fetchAll(
    "SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT 5"
);

// Latest 5 articles
$latestArticles = $db->fetchAll(
    "SELECT a.*, u.first_name, u.last_name
     FROM articles a JOIN users u ON a.user_id = u.id
     ORDER BY a.created_at DESC LIMIT 5"
);

include __DIR__ . '/includes/panel_head.php';
?>

<!-- ══ STAT CARDS ══════════════════════════════════════════ -->
<div class="panel-stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-blue"><i class="bi bi-people-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num"><?= $stats['users'] ?></div>
            <div class="stat-lbl">Total Users</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-green"><i class="bi bi-newspaper"></i></div>
        <div class="stat-body">
            <div class="stat-num"><?= $stats['articles'] ?></div>
            <div class="stat-lbl">Published Articles</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-gold"><i class="bi bi-inbox-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num"><?= $stats['contacts'] ?></div>
            <div class="stat-lbl">Total Enquiries</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-red"><i class="bi bi-bell-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num"><?= $stats['unread'] ?></div>
            <div class="stat-lbl">Unread Enquiries</div>
        </div>
    </div>
</div>

<!-- ══ TABLES ROW ══════════════════════════════════════════ -->
<div class="dashboard-tables-row">

    <!-- Latest Enquiries -->
    <div class="panel-card">
        <div class="panel-card-header">
            <h3>Recent Enquiries</h3>
            <a href="<?= APP_URL ?>/admin/contacts.php" class="card-header-link">View All</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($latestContacts)): ?>
                        <tr><td colspan="4" class="table-empty">No enquiries yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($latestContacts as $c): ?>
                            <tr>
                                <td><strong><?= e($c['full_name']) ?></strong><br>
                                    <small class="text-muted"><?= e($c['email']) ?></small></td>
                                <td><?= e($c['service'] ?: 'General') ?></td>
                                <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                                <td>
                                    <span class="badge-pill <?= $c['is_read'] ? 'badge-green' : 'badge-gold' ?>">
                                        <?= $c['is_read'] ? 'Read' : 'Unread' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Latest Articles -->
    <div class="panel-card">
        <div class="panel-card-header">
            <h3>Recent Articles</h3>
            <a href="<?= APP_URL ?>/admin/articles.php" class="card-header-link">View All</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($latestArticles)): ?>
                        <tr><td colspan="3" class="table-empty">No articles yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($latestArticles as $a): ?>
                            <tr>
                                <td><?= e(substr($a['title'], 0, 45)) . (strlen($a['title']) > 45 ? '…' : '') ?></td>
                                <td><?= e($a['first_name'] . ' ' . $a['last_name']) ?></td>
                                <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/panel_footer.php'; ?>
