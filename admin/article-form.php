<?php
/**
 * admin/article-form.php — Add / Edit Article (shared form)
 */

require_once __DIR__ . '/../bootstrap.php';
requireAdmin();

$db        = Database::getInstance();
$id        = isset($_GET['id']) ? (int)$_GET['id'] : null;
$isEdit    = $id !== null;
$article   = [];
$errors    = [];

// Load existing article for edit
if ($isEdit) {
    $article = $db->fetchOne("SELECT * FROM articles WHERE id = ?", [$id]);
    if (!$article) {
        setFlash('error', 'Article not found.');
        redirect('/admin/articles.php');
    }
}

// Handle submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = sanitize($_POST['title']    ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $excerpt  = sanitize($_POST['excerpt']  ?? '');
    $content  = trim($_POST['content']      ?? '');   // HTML allowed, sanitise on output
    $tags     = sanitize($_POST['tags']     ?? '');
    $emoji    = sanitize($_POST['emoji']    ?? '📰');
    $status   = in_array($_POST['status'] ?? '', ['published','draft']) ? $_POST['status'] : 'published';

    if (empty($title))    $errors[] = 'Title is required.';
    if (empty($category)) $errors[] = 'Category is required.';
    if (empty($excerpt))  $errors[] = 'Excerpt is required.';
    if (empty($content))  $errors[] = 'Content is required.';

    if (empty($errors)) {
        $slug     = slugify($title);
        $authorId = currentUser()['id'];

        if ($isEdit) {
            // Ensure slug is unique for other articles
            $existing = $db->fetchOne(
                "SELECT id FROM articles WHERE slug = ? AND id != ?", [$slug, $id]
            );
            if ($existing) $slug .= '-' . $id;

            $db->execute(
                "UPDATE articles SET title=?, slug=?, category=?, excerpt=?, content=?, tags=?, emoji=?, status=?, updated_at=NOW()
                 WHERE id=?",
                [$title, $slug, $category, $excerpt, $content, $tags, $emoji, $status, $id]
            );
            setFlash('success', 'Article updated successfully.');
        } else {
            // Unique slug
            $existing = $db->fetchOne("SELECT id FROM articles WHERE slug = ?", [$slug]);
            if ($existing) $slug .= '-' . time();

            $db->insert(
                "INSERT INTO articles (user_id, title, slug, category, excerpt, content, tags, emoji, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$authorId, $title, $slug, $category, $excerpt, $content, $tags, $emoji, $status]
            );
            setFlash('success', 'Article published successfully.');
        }
        redirect('/admin/articles.php');
    }
}

$categories = ['GST & Indirect Tax','Income Tax','Company Law','Accounting','Finance & Banking','MSME & Startups','TDS & Compliance','Other'];
$pageTitle       = ($isEdit ? 'Edit' : 'Add') . ' Article | Admin | ' . APP_NAME;
$activeAdminPage = 'articles';
$topbarTitle     = ($isEdit ? 'Edit' : 'Add New') . ' Article';

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
                       placeholder="Enter a descriptive title" required>
            </div>

            <div class="form-group">
                <label for="category">Category <span class="required">*</span></label>
                <select id="category" name="category" required>
                    <option value="">Select category...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= e($cat) ?>"
                            <?= (($article['category'] ?? '') === $cat || ($_POST['category'] ?? '') === $cat) ? 'selected' : '' ?>>
                            <?= e($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published" <?= (($article['status'] ?? 'published') === 'published') ? 'selected' : '' ?>>Published</option>
                    <option value="draft"     <?= (($article['status'] ?? '') === 'draft')     ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tags">Tags <span class="hint">(comma separated)</span></label>
                <input type="text" id="tags" name="tags"
                       value="<?= e($article['tags'] ?? ($_POST['tags'] ?? '')) ?>"
                       placeholder="GST, compliance, tax">
            </div>

            <div class="form-group">
                <label for="emoji">Card Emoji</label>
                <input type="text" id="emoji" name="emoji"
                       value="<?= e($article['emoji'] ?? ($_POST['emoji'] ?? '📰')) ?>"
                       placeholder="📰" maxlength="4">
            </div>

            <div class="form-group full-width">
                <label for="excerpt">Excerpt <span class="required">*</span></label>
                <textarea id="excerpt" name="excerpt" rows="3"
                          placeholder="Brief summary shown in blog listing..."><?= e($article['excerpt'] ?? ($_POST['excerpt'] ?? '')) ?></textarea>
            </div>

            <div class="form-group full-width">
                <label for="content">
                    Full Content <span class="required">*</span>
                    <span class="hint">(HTML supported: &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;blockquote&gt;, &lt;strong&gt;)</span>
                </label>
                <textarea id="content" name="content" rows="18"
                          placeholder="Write the full article content here. HTML tags are supported."><?= htmlspecialchars($article['content'] ?? ($_POST['content'] ?? ''), ENT_QUOTES) ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?= APP_URL ?>/admin/articles.php" class="btn-panel-secondary">Cancel</a>
            <button type="submit" class="btn-panel-primary">
                <i class="bi bi-check-lg me-1"></i>
                <?= $isEdit ? 'Update Article' : 'Publish Article' ?>
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/panel_footer.php'; ?>
