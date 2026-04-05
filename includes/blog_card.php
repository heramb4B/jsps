<?php
/**
 * includes/blog_card.php
 * Reusable blog article card.
 *
 * Variable expected:
 *   $article — associative array with keys:
 *              id, title, slug, category, excerpt, emoji,
 *              first_name, last_name, created_at
 */
if (empty($article)) return;
$authorInitials = strtoupper(
    substr($article['first_name'] ?? '', 0, 1) .
    substr($article['last_name']  ?? '', 0, 1)
);
?>
<article class="blog-card" itemscope itemtype="https://schema.org/BlogPosting">
    <a href="<?= APP_URL ?>/pages/blog-detail.php?slug=<?= e($article['slug']) ?>" class="blog-card-link">
        <div class="blog-thumb" aria-hidden="true">
            <span class="blog-emoji"><?= e($article['emoji'] ?? '📰') ?></span>
        </div>
        <div class="blog-body">
            <span class="blog-cat"><?= e($article['category']) ?></span>
            <h3 class="blog-title" itemprop="headline"><?= e($article['title']) ?></h3>
            <p class="blog-excerpt" itemprop="description"><?= e($article['excerpt']) ?></p>
            <div class="blog-meta">
                <span class="blog-avatar" aria-hidden="true"><?= e($authorInitials) ?></span>
                <span itemprop="author"><?= e($article['first_name'] . ' ' . $article['last_name']) ?></span>
                <span class="sep">·</span>
                <time itemprop="datePublished" datetime="<?= e($article['created_at']) ?>">
                    <?= date('d M Y', strtotime($article['created_at'])) ?>
                </time>
            </div>
        </div>
    </a>
</article>
