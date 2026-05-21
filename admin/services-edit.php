<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle = 'Edit Service — Admin';
$id = validId($_GET['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid ID.'); header('Location: services-list.php'); exit; }
try { $stmt=$pdo->prepare('SELECT * FROM services WHERE id=?'); $stmt->execute([$id]); $item=$stmt->fetch(); }
catch (PDOException $e) { $item=null; }
if (!$item) { setFlash('danger','Service not found.'); header('Location: services-list.php'); exit; }

$errors=[];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title     = trim($_POST['title']            ?? '');
    $iconClass = trim($_POST['icon_class']       ?? '');
    $shortDesc = trim($_POST['short_description']?? '');
    $fullDesc  = trim($_POST['full_description'] ?? '');
    $features  = trim($_POST['features']         ?? '');
    $order     = (int)($_POST['display_order']   ?? 0);
    if (mb_strlen($title)<3)     $errors[]='Title must be at least 3 characters.';
    if (empty($iconClass))        $errors[]='Icon class is required.';
    if (mb_strlen($shortDesc)<10) $errors[]='Short description must be at least 10 characters.';
    if (mb_strlen($fullDesc)<20)  $errors[]='Full description must be at least 20 characters.';
    if (empty($features))         $errors[]='Please enter at least one feature.';
    $uploadErr=uploadErrorMessage('image');
    if ($uploadErr) $errors[]=$uploadErr;

    if (empty($errors)) {
        $imgPath=$item['image_path'];
        if (!empty($_FILES['image']['name'])) {
            $new=handleImageUpload('image','services',$item['image_path']);
            if ($new===false) $errors[]='Image upload failed.';
            else $imgPath=$new;
        }
        if (empty($errors)) {
            try {
                $pdo->prepare('UPDATE services SET title=?,icon_class=?,image_path=?,short_description=?,full_description=?,features=?,display_order=? WHERE id=?')->execute([$title,$iconClass,$imgPath,$shortDesc,$fullDesc,$features,$order,$id]);
                setFlash('success','Service updated.');
                header('Location: services-list.php'); exit;
            } catch (PDOException $e) { $errors[]='Database error.'; }
        }
    }
}
$title     = $_POST['title']             ?? $item['title'];
$iconClass = $_POST['icon_class']        ?? $item['icon_class'];
$shortDesc = $_POST['short_description'] ?? $item['short_description'];
$fullDesc  = $_POST['full_description']  ?? $item['full_description'];
$features  = $_POST['features']          ?? $item['features'];
$order     = $_POST['display_order']     ?? $item['display_order'];
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-pencil me-2 text-primary"></i>Edit Service</h1>
        <a href="services-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?><div class="flash-alert alert-danger mb-3"><?= implode('<br>',array_map('h',$errors)) ?></div><?php endif; ?>
        <div class="admin-form-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Service Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= h($title) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="<?= (int)$order ?>" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bootstrap Icon Class <span class="text-danger">*</span></label>
                        <input type="text" name="icon_class" class="form-control" value="<?= h($iconClass) ?>">
                        <div class="form-hint"><a href="https://icons.getbootstrap.com" target="_blank">Browse Bootstrap Icons</a></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Replace Image <span class="text-muted">(optional)</span></label>
                        <?php if (!empty($item['image_path'])): ?>
                        <div class="mb-1"><div class="current-img-label">Current:</div>
                        <img src="<?= h(imgUrl($item['image_path'])) ?>" style="width:80px;height:60px;object-fit:cover;border-radius:6px;border:2px solid #E2E8F0;" alt=""></div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control file-preview-input" accept="image/*" data-preview="imgPreview">
                        <img id="imgPreview" src="" alt="" style="display:none;margin-top:.5rem;max-width:180px;border-radius:8px;border:2px solid #E2E8F0;">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Short Description <span class="text-danger">*</span></label>
                        <textarea name="short_description" class="form-control" rows="2" required><?= h($shortDesc) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Full Description <span class="text-danger">*</span></label>
                        <textarea name="full_description" class="form-control" rows="5" required><?= h($fullDesc) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Key Features <span class="text-danger">*</span></label>
                        <textarea name="features" class="form-control" rows="6" required><?= h($features) ?></textarea>
                        <div class="form-hint">One feature per line.</div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i>Save Changes</button>
                    <a href="services-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
