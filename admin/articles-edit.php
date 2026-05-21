<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle = 'Edit Article — Admin';
$id = validId($_GET['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid ID.'); header('Location: articles-list.php'); exit; }
try { $stmt=$pdo->prepare('SELECT * FROM articles WHERE id=?'); $stmt->execute([$id]); $item=$stmt->fetch(); }
catch (PDOException $e) { $item=null; }
if (!$item) { setFlash('danger','Article not found.'); header('Location: articles-list.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']   ?? '');
    $category = trim($_POST['category']?? '');
    $excerpt  = trim($_POST['excerpt'] ?? '');
    $content  = trim($_POST['content'] ?? '');
    if (mb_strlen($title) < 5)   $errors[] = 'Title must be at least 5 characters.';
    if (empty($category))         $errors[] = 'Category is required.';
    if (mb_strlen($excerpt) < 10) $errors[] = 'Excerpt must be at least 10 characters.';
    if (mb_strlen($content) < 20) $errors[] = 'Content must be at least 20 characters.';
    $uploadErr = uploadErrorMessage('thumbnail');
    if ($uploadErr) $errors[] = $uploadErr;

    if (empty($errors)) {
        $thumbPath = $item['thumbnail_path'];
        if (!empty($_FILES['thumbnail']['name'])) {
            $new = handleImageUpload('thumbnail','articles',$item['thumbnail_path']);
            if ($new===false) $errors[]='Thumbnail upload failed.';
            else $thumbPath=$new;
        }
        if (empty($errors)) {
            try {
                $pdo->prepare('UPDATE articles SET title=?,category=?,excerpt=?,content=?,thumbnail_path=? WHERE id=?')->execute([$title,$category,$excerpt,$content,$thumbPath,$id]);
                setFlash('success','Article updated successfully.');
                header('Location: articles-list.php'); exit;
            } catch (PDOException $e) { $errors[]='Database error.'; }
        }
    }
}
$title    = $_POST['title']    ?? $item['title'];
$category = $_POST['category'] ?? $item['category'];
$excerpt  = $_POST['excerpt']  ?? $item['excerpt'];
$content  = $_POST['content']  ?? $item['content'];
$categories = ['AI Innovation','Digital Workplace','Industry Spotlight','Company News','Tech Deep Dive'];
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-pencil me-2 text-primary"></i>Edit Article</h1>
        <a href="articles-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?><div class="flash-alert alert-danger mb-3"><?= implode('<br>',array_map('h',$errors)) ?></div><?php endif; ?>
        <div class="admin-form-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= h($title) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= h($cat) ?>" <?= $category===$cat?'selected':'' ?>><?= h($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Excerpt <span class="text-danger">*</span></label>
                        <textarea name="excerpt" class="form-control" rows="3" required><?= h($excerpt) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Full Article Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="14" required><?= h($content) ?></textarea>
                        <div class="form-hint">HTML formatting is supported.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Replace Thumbnail <span class="text-muted">(optional)</span></label>
                        <?php if (!empty($item['thumbnail_path'])): ?>
                        <div class="mb-2"><div class="current-img-label">Current thumbnail:</div>
                        <img src="<?= h(imgUrl($item['thumbnail_path'])) ?>" style="width:120px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #E2E8F0;" alt=""></div>
                        <?php endif; ?>
                        <input type="file" name="thumbnail" class="form-control file-preview-input" accept="image/*" data-preview="thumbPreview">
                        <div class="form-hint">Leave blank to keep current image. Max 5 MB.</div>
                        <img id="thumbPreview" src="" alt="" style="display:none;margin-top:.75rem;max-width:200px;border-radius:8px;border:2px solid #E2E8F0;">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i>Save Changes</button>
                    <a href="articles-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
