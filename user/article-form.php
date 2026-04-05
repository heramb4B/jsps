<?php
/**
 * user/article-form.php — User: Add / Edit Own Article
 */

require_once __DIR__ . '/../bootstrap.php';
requireLogin();
if (isAdmin()) redirect('/admin/article-form.php');

$db     = Database::getInstance();
$userId = currentUser()['id'];
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;
$isEdit = $id !== null;
$article = [];
$errors  = [];

if ($isEdit) {
    $article = $db->fetchOne(
        "SELECT * FROM articles WHERE id = ? AND user_id = ?", [$id, $userId]
    );
    if (!$article) {
        setFlash('error', 'Article not found or you do not have permission to edit it.');
        redirect('/user/articles.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = sanitize($_POST['title']   ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $excerpt  = sanitize($_POST['excerpt']  ?? '');
    $content  = trim($_POST['content']      ?? '');
    $tags     = sanitize($_POST['tags']     ?? '');
    $emoji    = sanitize($_POST['emoji']    ?? '📰');
    $status   = in_array($_POST['status'] ?? '', ['published','draft']) ? $_POST['status'] : 'draft';

    if (empty($title))    $errors[] = 'Title is required.';
    if (empty($category)) $errors[] = 'Category is required.';
    if (empty($excerpt))  $errors[] = 'Excerpt is required.';
    if (empty($content))  $errors[] = 'Content is required.';

    if (empty($errors)) {
        $slug = slugify($title);

        if ($isEdit) {
            $exists = $db->fetchOne("SELECT id FROM articles WHERE slug = ? AND id != ?", [$slug, $id]);
            if ($exists) $slug .= '-' . $id;
            $db->execute(
                "UPDATE articles SET title=?,slug=?,category=?,excerpt=?,content=?,tags=?,emoji=?,status=?,updated_at=NOW() WHERE id=? AND user_id=?",
                [$title,$slug,$category,$excerpt,$content,$tags,$emoji,$status,$id,$userId]
            );
            setFlash('success', 'Article updated.');
        } else {
            $exists = $db->fetchOne("SELECT id FROM articles WHERE slug = ?", [$slug]);
            if ($exists) $slug .= '-' . time();
            $db->insert(
                "INSERT INTO articles (user_id,title,slug,category,excerpt,content,tags,emoji,status) VALUES (?,?,?,?,?,?,?,?,?)",
                [$userId,$title,$slug,$category,$excerpt,$content,$tags,$emoji,$status]
            );
            setFlash('success', 'Article saved.');
        }
        redirect('/user/articles.php');
    }
}

$categories = ['GST & Indirect Tax','Income Tax','Company Law','Accounting','Finance & Banking','MSME & Startups','TDS & Compliance','Other'];
$pageTitle      = ($isEdit ? 'Edit' : 'Write') . ' Article | ' . APP_NAME;
$activeUserPage = 'articles';
$topbarTitle    = ($isEdit ? 'Edit Article' : 'Write New Article');

include __DIR__ . '/includes/panel_head.php';
?>

<div class="panel-card form-card">

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid-2">
            <div class="form-group full-width">
                <label for="title">Article Title <span class="required">*</span></label>
                <input type="text" id="title" name="title"
                       value="<?= e($article['title'] ?? ($_POST['title'] ?? '')) ?>"
                       placeholder="Enter an informative title" required>
            </div>
            <div class="form-group">
                <label for="category">Category <span class="required">*</span></label>
                <select id="category" name="category" required>
                    <option value="">Select...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= e($cat) ?>"
                            <?= (($article['category'] ?? '') === $cat) ? 'selected' : '' ?>>
                            <?= e($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Save as</label>
                <select id="status" name="status">
                    <option value="draft"     <?= (($article['status'] ?? 'draft') === 'draft')     ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= (($article['status'] ?? '') === 'published') ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" id="tags" name="tags"
                       value="<?= e($article['tags'] ?? '') ?>" placeholder="e.g. GST, tax, compliance">
            </div>
            <div class="form-group">
                <label for="emoji">Card Emoji</label>
                <input type="text" id="emoji" name="emoji"
                       value="<?= e($article['emoji'] ?? '📰') ?>" maxlength="4">
            </div>
            <div class="form-group full-width">
                <label for="excerpt">Excerpt <span class="required">*</span></label>
                <textarea id="excerpt" name="excerpt" rows="3" placeholder="Brief summary..."><?= e($article['excerpt'] ?? '') ?></textarea>
            </div>
            <div class="form-group full-width">
                <label for="content">
                    Content <span class="required">*</span>
                    <span class="hint">(HTML supported: &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;blockquote&gt;, &lt;strong&gt;)</span>
                </label>
                <textarea id="content" name="content" rows="16"><?= htmlspecialchars($article['content'] ?? '', ENT_QUOTES) ?></textarea>
            </div>
        </div>
        <div class="form-actions">
            <a href="<?= APP_URL ?>/user/articles.php" class="btn-panel-secondary">Cancel</a>
            <button type="submit" class="btn-panel-primary">
                <i class="bi bi-check-lg me-1"></i> <?= $isEdit ? 'Update Article' : 'Save Article' ?>
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/panel_footer.php'; ?>
