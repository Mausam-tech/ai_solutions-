<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle = 'Edit Gallery Image — Admin';

$id = validId($_GET['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid ID.'); header('Location: gallery-list.php'); exit; }

try { $stmt = $pdo->prepare('SELECT * FROM gallery_images WHERE id=?'); $stmt->execute([$id]); $item = $stmt->fetch(); }
catch (PDOException $e) { $item = null; }
if (!$item) { setFlash('danger','Image not found.'); header('Location: gallery-list.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']    ?? '');
    $category = trim($_POST['category'] ?? '');
    if (mb_strlen($title) < 2) $errors[] = 'Title must be at least 2 characters.';
    if (empty($category))       $errors[] = 'Please select a category.';
    $uploadErr = uploadErrorMessage('image');
    if ($uploadErr) $errors[] = $uploadErr;

    if (empty($errors)) {
        $imagePath = $item['image_path'];
        if (!empty($_FILES['image']['name'])) {
            $newPath = handleImageUpload('image', 'gallery', $item['image_path']);
            if ($newPath === false) $errors[] = 'Image upload failed.';
            else $imagePath = $newPath;
        }
        if (empty($errors)) {
            try {
                $pdo->prepare('UPDATE gallery_images SET title=?,category=?,image_path=? WHERE id=?')->execute([$title,$category,$imagePath,$id]);
                setFlash('success','Gallery image updated.');
                header('Location: gallery-list.php'); exit;
            } catch (PDOException $e) { $errors[] = 'Database error.'; }
        }
    }
}
$title    = $_POST['title']    ?? $item['title'];
$category = $_POST['category'] ?? $item['category'];

include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-pencil me-2 text-primary"></i>Edit Gallery Image</h1>
        <a href="gallery-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?>
        <div class="flash-alert alert-danger mb-3"><?= implode('<br>', array_map('h',$errors)) ?></div>
        <?php endif; ?>
        <div class="admin-form-card" style="max-width:600px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= h($title) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <?php foreach (['promotional'=>'Promotional Events','team'=>'Team Photos','partner'=>'Partner Events','product'=>'Product Launches','general'=>'General'] as $val=>$lab): ?>
                        <option value="<?= $val ?>" <?= $category===$val?'selected':'' ?>><?= $lab ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">Replace Image <span class="text-muted">(optional)</span></label>
                    <?php if (!empty($item['image_path'])): ?>
                    <div class="mb-2">
                        <div class="current-img-label">Current image:</div>
                        <img src="<?= h(imgUrl($item['image_path'])) ?>" style="width:120px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #E2E8F0;" alt="">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control file-preview-input" accept="image/*" data-preview="imgPreview">
                    <div class="form-hint">Leave blank to keep the current image. Max 5 MB.</div>
                    <img id="imgPreview" src="" alt="" style="display:none;margin-top:.75rem;max-width:200px;border-radius:8px;border:2px solid #E2E8F0;">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i>Save Changes</button>
                    <a href="gallery-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
