<?php
/**
 * includes/breadcrumb.php
 * Reusable breadcrumb bar.
 *
 * Variable expected:
 *   $breadcrumbs — array of ['label' => string, 'href' => string|null]
 *                  Last item has href = null (current page).
 */
$breadcrumbs = $breadcrumbs ?? [];
if (empty($breadcrumbs)) return;
?>
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
    <div class="container-xl">
        <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $i => $crumb): ?>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <?php if ($crumb['href']): ?>
                        <a href="<?= e($crumb['href']) ?>" itemprop="item">
                            <span itemprop="name"><?= e($crumb['label']) ?></span>
                        </a>
                    <?php else: ?>
                        <span itemprop="name"><?= e($crumb['label']) ?></span>
                    <?php endif; ?>
                    <meta itemprop="position" content="<?= $i + 1 ?>">
                    <?php if ($crumb['href']): ?><span class="sep">›</span><?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>
