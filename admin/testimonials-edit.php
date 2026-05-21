<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle='Edit Testimonial — Admin';
$id=validId($_GET['id']??0);
if (!$id){setFlash('danger','Invalid ID.');header('Location: testimonials-list.php');exit;}
try{$stmt=$pdo->prepare('SELECT * FROM testimonials WHERE id=?');$stmt->execute([$id]);$item=$stmt->fetch();}catch(PDOException $e){$item=null;}
if (!$item){setFlash('danger','Testimonial not found.');header('Location: testimonials-list.php');exit;}
$errors=[];
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $name    =trim($_POST['client_name']      ??'');
    $jobTitle=trim($_POST['job_title']        ??'');
    $company =trim($_POST['company_name']     ??'');
    $rating  =(int)($_POST['rating']          ??5);
    $text    =trim($_POST['testimonial_text'] ??'');
    if (mb_strlen($name)<2)  $errors[]='Name required.';
    if (empty($jobTitle))     $errors[]='Job title required.';
    if (empty($company))      $errors[]='Company required.';
    if ($rating<1||$rating>5) $errors[]='Rating must be 1–5.';
    if (mb_strlen($text)<20)  $errors[]='Testimonial must be at least 20 characters.';
    $uploadErr=uploadErrorMessage('avatar');if($uploadErr)$errors[]=$uploadErr;
    if (empty($errors)){
        $avatarPath=$item['avatar_path'];
        if (!empty($_FILES['avatar']['name'])){$new=handleImageUpload('avatar','testimonials',$item['avatar_path']);if($new===false)$errors[]='Avatar upload failed.';else $avatarPath=$new;}
        if (empty($errors)){
            try{$pdo->prepare('UPDATE testimonials SET client_name=?,job_title=?,company_name=?,rating=?,testimonial_text=?,avatar_path=? WHERE id=?')->execute([$name,$jobTitle,$company,$rating,$text,$avatarPath,$id]);setFlash('success','Testimonial updated.');header('Location: testimonials-list.php');exit;}
            catch(PDOException $e){$errors[]='Database error.';}
        }
    }
}
$f=function($key)use($item){return $_POST[$key]??$item[$key];};
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-pencil me-2 text-primary"></i>Edit Testimonial</h1>
        <a href="testimonials-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?><div class="flash-alert alert-danger mb-3"><?= implode('<br>',array_map('h',$errors)) ?></div><?php endif; ?>
        <div class="admin-form-card" style="max-width:700px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Client Name <span class="text-danger">*</span></label>
                        <input type="text" name="client_name" class="form-control" value="<?= h($f('client_name')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="job_title" class="form-control" value="<?= h($f('job_title')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" value="<?= h($f('company_name')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Star Rating <span class="text-danger">*</span></label>
                        <select name="rating" class="form-select" required>
                            <?php for($r=5;$r>=1;$r--): ?>
                            <option value="<?= $r ?>" <?= (int)$f('rating')===$r?'selected':'' ?>><?= $r ?> Star<?= $r>1?'s':'' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Testimonial Text <span class="text-danger">*</span></label>
                        <textarea name="testimonial_text" class="form-control" rows="5" required><?= h($f('testimonial_text')) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Replace Avatar <span class="text-muted">(optional)</span></label>
                        <?php if (!empty($item['avatar_path'])): ?>
                        <div class="mb-2"><div class="current-img-label">Current:</div>
                        <img src="<?= h(imgUrl($item['avatar_path'])) ?>" style="width:52px;height:52px;object-fit:cover;border-radius:50%;border:2px solid #E2E8F0;" alt=""></div>
                        <?php endif; ?>
                        <input type="file" name="avatar" class="form-control file-preview-input" accept="image/*" data-preview="avatarPreview">
                        <div class="form-hint">Leave blank to keep current image. Max 5 MB.</div>
                        <img id="avatarPreview" src="" alt="" style="display:none;margin-top:.5rem;width:60px;height:60px;object-fit:cover;border-radius:50%;border:2px solid #E2E8F0;">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i>Save Changes</button>
                    <a href="testimonials-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
