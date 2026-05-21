<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle = 'Add Service — Admin';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']            ?? '');
    $iconClass   = trim($_POST['icon_class']       ?? '');
    $shortDesc   = trim($_POST['short_description']?? '');
    $fullDesc    = trim($_POST['full_description'] ?? '');
    $features    = trim($_POST['features']         ?? '');
    $order       = (int)($_POST['display_order']   ?? 0);
    if (mb_strlen($title) < 3)     $errors[] = 'Title must be at least 3 characters.';
    if (empty($iconClass))          $errors[] = 'Icon class is required.';
    if (mb_strlen($shortDesc) < 10) $errors[] = 'Short description must be at least 10 characters.';
    if (mb_strlen($fullDesc) < 20)  $errors[] = 'Full description must be at least 20 characters.';
    if (empty($features))           $errors[] = 'Please enter at least one feature.';
    $uploadErr = uploadErrorMessage('image');
    if ($uploadErr) $errors[] = $uploadErr;

    if (empty($errors)) {
        $imgPath = '';
        if (!empty($_FILES['image']['name'])) {
            $imgPath = handleImageUpload('image','services');
            if ($imgPath===false) { $errors[]='Image upload failed.'; }
        }
        if (empty($errors)) {
            try {
                $pdo->prepare('INSERT INTO services (title,icon_class,image_path,short_description,full_description,features,display_order) VALUES (?,?,?,?,?,?,?)')->execute([$title,$iconClass,$imgPath,$shortDesc,$fullDesc,$features,$order]);
                setFlash('success','Service added successfully.');
                header('Location: services-list.php'); exit;
            } catch (PDOException $e) { $errors[]='Database error.'; }
        }
    }
}
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Service</h1>
        <a href="services-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?><div class="flash-alert alert-danger mb-3"><?= implode('<br>',array_map('h',$errors)) ?></div><?php endif; ?>
        <div class="admin-form-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Service Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= h($_POST['title']??'') ?>" placeholder="e.g. AI-Powered Virtual Assistant" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="<?= (int)($_POST['display_order']??0) ?>" min="0">
                        <div class="form-hint">Lower number appears first on the page.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bootstrap Icon Class <span class="text-danger">*</span></label>
                        <input type="text" name="icon_class" class="form-control" value="<?= h($_POST['icon_class']??'') ?>" placeholder="e.g. bi-robot">
                        <div class="form-hint">Find icon names at <a href="https://icons.getbootstrap.com" target="_blank">icons.getbootstrap.com</a></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Optional Service Image</label>
                        <input type="file" name="image" class="form-control file-preview-input" accept="image/*" data-preview="imgPreview">
                        <div class="form-hint">Max 5 MB. Leave blank to use icon only.</div>
                        <img id="imgPreview" src="" alt="" style="display:none;margin-top:.75rem;max-width:180px;border-radius:8px;border:2px solid #E2E8F0;">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Short Description <span class="text-danger">*</span></label>
                        <textarea name="short_description" class="form-control" rows="2" placeholder="Shown on service cards — 1–2 sentences." required><?= h($_POST['short_description']??'') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Full Description <span class="text-danger">*</span></label>
                        <textarea name="full_description" class="form-control" rows="5" placeholder="Detailed description shown on the Services page." required><?= h($_POST['full_description']??'') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Key Features <span class="text-danger">*</span></label>
                        <textarea name="features" class="form-control" rows="6" placeholder="Enter one feature per line:&#10;Natural Language Processing engine&#10;Multi-platform deployment&#10;24/7 availability" required><?= h($_POST['features']??'') ?></textarea>
                        <div class="form-hint">Each line becomes a bullet point on the Services page.</div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i>Save Service</button>
                    <a href="services-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
