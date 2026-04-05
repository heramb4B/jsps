<?php
/**
 * pages/blog-detail.php — Single Article Page
 */

require_once __DIR__ . '/../bootstrap.php';

$db   = Database::getInstance();
$slug = sanitize($_GET['slug'] ?? '');

if (!$slug) {
    header('Location: ' . APP_URL . '/pages/blog.php');
    exit;
}

// Fetch article
$article = $db->fetchOne(
    "SELECT a.*, u.first_name, u.last_name, u.email AS author_email
     FROM articles a
     JOIN users u ON a.user_id = u.id
     WHERE a.slug = ? AND a.status = 'published'",
    [$slug]
);

if (!$article) {
    http_response_code(404);
    $pageTitle = '404 — Article Not Found | ' . APP_NAME;
    include __DIR__ . '/../includes/head.php';
    include __DIR__ . '/../includes/navbar.php';
    echo '<div class="container-xl section-pad text-center"><h1>Article Not Found</h1><p>The article you are looking for does not exist or has been removed.</p><a href="' . APP_URL . '/pages/blog.php" class="btn-primary-cta mt-3 d-inline-block">Back to Blog</a></div>';
    include __DIR__ . '/../includes/footer.php';
    exit;
}

// Fetch related articles (same category, exclude current)
$related = $db->fetchAll(
    "SELECT a.*, u.first_name, u.last_name
     FROM articles a
     JOIN users u ON a.user_id = u.id
     WHERE a.category = ? AND a.slug != ? AND a.status = 'published'
     ORDER BY a.created_at DESC
     LIMIT 3",
    [$article['category'], $slug]
);

$authorInitials = strtoupper(substr($article['first_name'], 0, 1) . substr($article['last_name'], 0, 1));
$authorName     = e($article['first_name'] . ' ' . $article['last_name']);
$publishDate    = date('d F Y', strtotime($article['created_at']));
$pageTitle       = e($article['title']) . ' | ' . APP_NAME;
$metaDescription = e(substr(strip_tags($article['excerpt']), 0, 160));
$activePage      = 'blog';
$breadcrumbs     = [
    ['label' => 'Home',     'href' => APP_URL . '/'],
    ['label' => 'Blog',     'href' => APP_URL . '/pages/blog.php'],
    ['label' => substr($article['title'], 0, 50) . (strlen($article['title']) > 50 ? '…' : ''), 'href' => null],
];

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
include __DIR__ . '/../includes/breadcrumb.php';
?>

<!-- ══ ARTICLE ════════════════════════════════════════════ -->
<main class="article-wrap container-xl" itemscope itemtype="https://schema.org/BlogPosting">
    <meta itemprop="url" content="<?= APP_URL . '/pages/blog-detail.php?slug=' . e($slug) ?>">

    <header class="article-header">
        <span class="blog-cat" itemprop="articleSection"><?= e($article['category']) ?></span>
        <h1 class="article-title" itemprop="headline"><?= e($article['title']) ?></h1>
        <div class="article-meta-bar">
            <span class="art-meta-item">
                <span class="blog-avatar" aria-hidden="true"><?= $authorInitials ?></span>
                <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <span itemprop="name"><?= $authorName ?></span>
                </span>
            </span>
            <span class="art-meta-item">
                <i class="bi bi-calendar3" aria-hidden="true"></i>
                <time itemprop="datePublished" datetime="<?= e($article['created_at']) ?>"><?= $publishDate ?></time>
            </span>
            <span class="art-meta-item">
                <i class="bi bi-clock" aria-hidden="true"></i>
                <?= max(1, (int)(str_word_count(strip_tags($article['content'])) / 200)) ?> min read
            </span>
            <?php if (!empty($article['tags'])): ?>
                <span class="art-meta-item">
                    <i class="bi bi-tags" aria-hidden="true"></i>
                    <?php foreach (explode(',', $article['tags']) as $tag): ?>
                        <span class="tag-pill"><?= e(trim($tag)) ?></span>
                    <?php endforeach; ?>
                </span>
            <?php endif; ?>
        </div>
    </header>

    <div class="article-body" itemprop="articleBody">
        <?= $article['content'] /* Content stored as sanitised HTML */ ?>
    </div>

    <!-- Author Box -->
    <div class="author-box">
        <div class="blog-avatar author-avatar" aria-hidden="true"><?= $authorInitials ?></div>
        <div class="author-details">
            <h4>Written by <?= $authorName ?></h4>
            <p>Expert CA &amp; Financial Advisor at JSPS Accounting Solutions. Helping businesses navigate compliance with clarity.</p>
        </div>
    </div>
</main>

<!-- ══ RELATED ARTICLES ══════════════════════════════════ -->
<?php if (!empty($related)): ?>
<section class="section-pad section-alt">
    <div class="container-xl">
        <div class="section-header">
            <h2>Related Articles</h2>
        </div>
        <div class="blog-grid">
            <?php foreach ($related as $article): ?>
                <?php include __DIR__ . '/../includes/blog_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
