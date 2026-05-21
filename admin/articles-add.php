<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle = 'Add Article — Admin';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']    ?? '');
    $category = trim($_POST['category'] ?? '');
    $excerpt  = trim($_POST['excerpt']  ?? '');
    $content  = trim($_POST['content']  ?? '');
    if (mb_strlen($title) < 5)    $errors[] = 'Title must be at least 5 characters.';
    if (empty($category))          $errors[] = 'Category is required.';
    if (mb_strlen($excerpt) < 10)  $errors[] = 'Excerpt must be at least 10 characters.';
    if (mb_strlen($content) < 20)  $errors[] = 'Content must be at least 20 characters.';
    $uploadErr = uploadErrorMessage('thumbnail');
    if ($uploadErr) $errors[] = $uploadErr;

    if (empty($errors)) {
        $thumbPath = '';
        if (!empty($_FILES['thumbnail']['name'])) {
            $thumbPath = handleImageUpload('thumbnail', 'articles');
            if ($thumbPath === false) { $errors[] = 'Thumbnail upload failed.'; }
        }
        if (empty($errors)) {
            try {
                $pdo->prepare('INSERT INTO articles (title,category,excerpt,content,thumbnail_path) VALUES (?,?,?,?,?)')->execute([$title,$category,$excerpt,$content,$thumbPath]);
                setFlash('success','Article published successfully.');
                header('Location: articles-list.php'); exit;
            } catch (PDOException $e) { $errors[] = 'Database error. Please try again.'; }
        }
    }
}
$categories = ['AI Innovation','Digital Workplace','Industry Spotlight','Company News','Tech Deep Dive'];
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Article</h1>
        <a href="articles-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?><div class="flash-alert alert-danger mb-3"><?= implode('<br>', array_map('h',$errors)) ?></div><?php endif; ?>
        <div class="admin-form-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= h($_POST['title']??'') ?>" placeholder="e.g. How AI is Transforming Healthcare" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= h($cat) ?>" <?= ($_POST['category']??'')===$cat?'selected':'' ?>><?= h($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Excerpt <span class="text-danger">*</span></label>
                        <textarea name="excerpt" class="form-control" rows="3" placeholder="Short preview shown on article cards (2–3 sentences)…" required><?= h($_POST['excerpt']??'') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Full Article Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="14" placeholder="Write the full article here. You can use HTML tags for formatting (e.g. &lt;p&gt;, &lt;strong&gt;, &lt;h3&gt;)…" required><?= h($_POST['content']??'') ?></textarea>
                        <div class="form-hint">HTML formatting is supported. Use &lt;p&gt;, &lt;strong&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;li&gt; etc.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Thumbnail Image <span class="text-muted">(optional)</span></label>
                        <input type="file" name="thumbnail" class="form-control file-preview-input" accept="image/*" data-preview="thumbPreview">
                        <div class="form-hint">JPG, PNG, WebP — max 5 MB. Recommended size: 800×500px.</div>
                        <img id="thumbPreview" src="" alt="" style="display:none;margin-top:.75rem;max-width:200px;border-radius:8px;border:2px solid #E2E8F0;">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-send me-1"></i>Publish Article</button>
                    <a href="articles-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
