<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle='Edit Event — Admin';
$id=validId($_GET['id']??0);
if (!$id){setFlash('danger','Invalid ID.');header('Location: events-list.php');exit;}
try{$stmt=$pdo->prepare('SELECT * FROM events WHERE id=?');$stmt->execute([$id]);$item=$stmt->fetch();}catch(PDOException $e){$item=null;}
if (!$item){setFlash('danger','Event not found.');header('Location: events-list.php');exit;}
$eventTypes=['Conference','Webinar','Workshop','Exhibition','Networking','Other'];
$errors=[];
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $title   =trim($_POST['title']        ??'');
    $type    =trim($_POST['event_type']   ??'');
    $date    =trim($_POST['event_date']   ??'');
    $location=trim($_POST['location']     ??'');
    $desc    =trim($_POST['description']  ??'');
    $regLink =trim($_POST['register_link']??'');
    if (mb_strlen($title)<3)  $errors[]='Title required.';
    if (empty($type))          $errors[]='Event type required.';
    if (empty($date)||!strtotime($date)) $errors[]='Valid date required.';
    if (empty($location))      $errors[]='Location required.';
    if (mb_strlen($desc)<10)   $errors[]='Description required.';
    $uploadErr=uploadErrorMessage('image');if($uploadErr)$errors[]=$uploadErr;
    if (empty($errors)){
        $imgPath=$item['image_path'];
        if (!empty($_FILES['image']['name'])){$new=handleImageUpload('image','events',$item['image_path']);if($new===false)$errors[]='Image upload failed.';else $imgPath=$new;}
        if (empty($errors)){
            try{$pdo->prepare('UPDATE events SET title=?,event_type=?,event_date=?,location=?,description=?,register_link=?,image_path=? WHERE id=?')->execute([$title,$type,$date,$location,$desc,$regLink,$imgPath,$id]);setFlash('success','Event updated.');header('Location: events-list.php');exit;}
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
        <h1 class="admin-page-title"><i class="bi bi-pencil me-2 text-primary"></i>Edit Event</h1>
        <a href="events-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?><div class="flash-alert alert-danger mb-3"><?= implode('<br>',array_map('h',$errors)) ?></div><?php endif; ?>
        <div class="admin-form-card" style="max-width:700px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Event Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= h($f('title')) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Event Type <span class="text-danger">*</span></label>
                        <select name="event_type" class="form-select" required>
                            <?php foreach ($eventTypes as $et): ?>
                            <option value="<?= h($et) ?>" <?= $f('event_type')===$et?'selected':'' ?>><?= h($et) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Event Date <span class="text-danger">*</span></label>
                        <input type="date" name="event_date" class="form-control" value="<?= h($f('event_date')) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control" value="<?= h($f('location')) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" required><?= h($f('description')) ?></textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Registration Link <span class="text-muted">(optional)</span></label>
                        <input type="url" name="register_link" class="form-control" value="<?= h($f('register_link')) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Replace Image <span class="text-muted">(optional)</span></label>
                        <?php if (!empty($item['image_path'])): ?>
                        <div class="mb-1"><div class="current-img-label">Current:</div>
                        <img src="<?= h(imgUrl($item['image_path'])) ?>" style="width:80px;height:56px;object-fit:cover;border-radius:6px;border:2px solid #E2E8F0;" alt=""></div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control file-preview-input" accept="image/*" data-preview="imgPreview">
                        <img id="imgPreview" src="" alt="" style="display:none;margin-top:.5rem;max-width:160px;border-radius:8px;border:2px solid #E2E8F0;">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i>Save Changes</button>
                    <a href="events-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
