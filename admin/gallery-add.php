<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle = 'Add Gallery Image — Admin';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']    ?? '');
    $category = trim($_POST['category'] ?? '');
    if (mb_strlen($title) < 2)  $errors[] = 'Title must be at least 2 characters.';
    if (empty($category))        $errors[] = 'Please select a category.';
    $uploadErr = uploadErrorMessage('image');
    if ($uploadErr) $errors[] = $uploadErr;
    if (empty($_FILES['image']['name'])) $errors[] = 'Please select an image to upload.';

    if (empty($errors)) {
        $imagePath = handleImageUpload('image', 'gallery');
        if ($imagePath === false) { $errors[] = 'Image upload failed. Please try again.'; }
        else {
            try {
                $pdo->prepare('INSERT INTO gallery_images (title,category,image_path) VALUES (?,?,?)')->execute([$title,$category,$imagePath]);
                setFlash('success','Gallery image added successfully.');
                header('Location: gallery-list.php'); exit;
            } catch (PDOException $e) { $errors[] = 'Database error. Please try again.'; }
        }
    }
}
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Gallery Image</h1>
        <a href="gallery-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?>
        <div class="flash-alert alert-danger mb-3"><?= implode('<br>', array_map('h', $errors)) ?></div>
        <?php endif; ?>
        <div class="admin-form-card" style="max-width:600px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= h($_POST['title']??'') ?>" placeholder="e.g. AI Summit 2026 Opening" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">Select category</option>
                        <option value="promotional" <?= ($_POST['category']??'')==='promotional'?'selected':'' ?>>Promotional Events</option>
                        <option value="team"        <?= ($_POST['category']??'')==='team'?'selected':'' ?>>Team Photos</option>
                        <option value="partner"     <?= ($_POST['category']??'')==='partner'?'selected':'' ?>>Partner Events</option>
                        <option value="product"     <?= ($_POST['category']??'')==='product'?'selected':'' ?>>Product Launches</option>
                        <option value="general"     <?= ($_POST['category']??'')==='general'?'selected':'' ?>>General</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control file-preview-input" accept="image/*" data-preview="imgPreview" required>
                    <div class="form-hint">JPG, PNG, WebP or GIF — max 5 MB</div>
                    <img id="imgPreview" src="" alt="" style="display:none;margin-top:.75rem;max-width:200px;border-radius:8px;border:2px solid #E2E8F0;">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-upload me-1"></i>Upload & Save</button>
                    <a href="gallery-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
