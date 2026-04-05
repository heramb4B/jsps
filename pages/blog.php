<?php
/**
 * pages/blog.php — Blog Listing Page
 */

require_once __DIR__ . '/../bootstrap.php';

$pageTitle       = 'Blog & Insights | ' . APP_NAME;
$metaDescription = 'Expert articles on GST, Income Tax, Company Law, MSME, and financial planning from the CA team at JSPS Accounting Solutions.';
$activePage      = 'blog';
$breadcrumbs     = [
    ['label' => 'Home', 'href' => APP_URL . '/'],
    ['label' => 'Blog', 'href' => null],
];

$db = Database::getInstance();

// Filters
$category = sanitize($_GET['category'] ?? '');
$search   = sanitize($_GET['q'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));

// Build WHERE clause
$where  = ["a.status = 'published'"];
$params = [];

if ($category) {
    $where[]  = 'a.category = ?';
    $params[] = $category;
}
if ($search) {
    $where[]  = '(a.title LIKE ? OR a.content LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereSQL = implode(' AND ', $where);

// Total count for pagination
$total = (int)$db->fetchOne(
    "SELECT COUNT(*) AS cnt FROM articles a WHERE $whereSQL",
    $params
)['cnt'];

$pagination = paginate($total, POSTS_PER_PAGE, $page);

// Fetch articles
$articles = $db->fetchAll(
    "SELECT a.*, u.first_name, u.last_name
     FROM articles a
     JOIN users u ON a.user_id = u.id
     WHERE $whereSQL
     ORDER BY a.created_at DESC
     LIMIT ? OFFSET ?",
    array_merge($params, [$pagination['per_page'], $pagination['offset']])
);

// Categories for filter
$categories = $db->fetchAll(
    "SELECT DISTINCT category FROM articles WHERE status = 'published' ORDER BY category"
);

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
include __DIR__ . '/../includes/breadcrumb.php';
?>

<!-- ══ PAGE HERO ════════════════════════════════════════ -->
<section class="page-hero">
    <div class="container-xl text-center">
        <span class="section-tag">Expert Articles</span>
        <h1>Blog &amp; Insights</h1>
        <p>Stay informed about Indian taxation, compliance, and business regulations.</p>
    </div>
</section>

<!-- ══ FILTERS ══════════════════════════════════════════ -->
<section class="blog-filters-bar">
    <div class="container-xl">
        <form method="GET" class="blog-filter-form" role="search">
            <div class="filter-search">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Search articles..." value="<?= e($search) ?>" aria-label="Search articles">
            </div>
            <div class="filter-cats">
                <a href="<?= APP_URL ?>/pages/blog.php" class="filter-tag<?= !$category ? ' active' : '' ?>">All</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?= urlencode($cat['category']) ?>"
                       class="filter-tag<?= $category === $cat['category'] ? ' active' : '' ?>">
                        <?= e($cat['category']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php if ($search): ?>
                <button type="submit" class="btn-sm-primary">Search</button>
            <?php endif; ?>
        </form>
    </div>
</section>

<!-- ══ ARTICLES ════════════════════════════════════════ -->
<section class="section-pad">
    <div class="container-xl">
        <?php if (empty($articles)): ?>
            <div class="empty-state">
                <i class="bi bi-journal-x"></i>
                <h3>No articles found</h3>
                <p>Try adjusting your search or browse all articles.</p>
                <a href="<?= APP_URL ?>/pages/blog.php" class="btn-primary-cta mt-3 d-inline-block">View All</a>
            </div>
        <?php else: ?>
            <div class="blog-grid">
                <?php foreach ($articles as $article): ?>
                    <?php include __DIR__ . '/../includes/blog_card.php'; ?>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
                <nav class="pagination-wrap" aria-label="Blog pagination">
                    <?php if ($pagination['has_prev']): ?>
                        <a href="?page=<?= $page - 1 ?><?= $category ? '&category=' . urlencode($category) : '' ?>" class="page-btn">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                    <?php endif; ?>
                    <span class="page-info">Page <?= $page ?> of <?= $pagination['total_pages'] ?></span>
                    <?php if ($pagination['has_next']): ?>
                        <a href="?page=<?= $page + 1 ?><?= $category ? '&category=' . urlencode($category) : '' ?>" class="page-btn">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
