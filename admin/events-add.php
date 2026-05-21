<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle='Add Event — Admin';
$errors=[];
$eventTypes=['Conference','Webinar','Workshop','Exhibition','Networking','Other'];

if ($_SERVER['REQUEST_METHOD']==='POST'){
    $title    =trim($_POST['title']        ??'');
    $type     =trim($_POST['event_type']   ??'');
    $date     =trim($_POST['event_date']   ??'');
    $location =trim($_POST['location']     ??'');
    $desc     =trim($_POST['description']  ??'');
    $regLink  =trim($_POST['register_link']??'');
    if (mb_strlen($title)<3)  $errors[]='Title must be at least 3 characters.';
    if (empty($type))          $errors[]='Event type is required.';
    if (empty($date)||!strtotime($date)) $errors[]='Please enter a valid date.';
    if (empty($location))      $errors[]='Location is required.';
    if (mb_strlen($desc)<10)   $errors[]='Description must be at least 10 characters.';
    $uploadErr=uploadErrorMessage('image');if($uploadErr)$errors[]=$uploadErr;

    if (empty($errors)){
        $imgPath='';
        if (!empty($_FILES['image']['name'])){$imgPath=handleImageUpload('image','events');if($imgPath===false){$errors[]='Image upload failed.';}}
        if (empty($errors)){
            try{
                $pdo->prepare('INSERT INTO events (title,event_type,event_date,location,description,register_link,image_path) VALUES (?,?,?,?,?,?,?)')->execute([$title,$type,$date,$location,$desc,$regLink,$imgPath]);
                setFlash('success','Event added successfully.');header('Location: events-list.php');exit;
            }catch(PDOException $e){$errors[]='Database error.';}
        }
    }
}
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Event</h1>
        <a href="events-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?><div class="flash-alert alert-danger mb-3"><?= implode('<br>',array_map('h',$errors)) ?></div><?php endif; ?>
        <div class="admin-form-card" style="max-width:700px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Event Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= h($_POST['title']??'') ?>" placeholder="e.g. AI Innovation Summit 2026" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Event Type <span class="text-danger">*</span></label>
                        <select name="event_type" class="form-select" required>
                            <option value="">Select type</option>
                            <?php foreach ($eventTypes as $et): ?>
                            <option value="<?= h($et) ?>" <?= ($_POST['event_type']??'')===$et?'selected':'' ?>><?= h($et) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Event Date <span class="text-danger">*</span></label>
                        <input type="date" name="event_date" class="form-control" value="<?= h($_POST['event_date']??'') ?>" required>
                        <div class="form-hint">Past dates appear under "Past Events" automatically.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control" value="<?= h($_POST['location']??'') ?>" placeholder="e.g. Birmingham, UK or Online" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Event Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe the event, what attendees can expect, speakers, agenda highlights…" required><?= h($_POST['description']??'') ?></textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Registration Link <span class="text-muted">(optional)</span></label>
                        <input type="url" name="register_link" class="form-control" value="<?= h($_POST['register_link']??'') ?>" placeholder="https://… (leave blank if not available yet)">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Event Image <span class="text-muted">(optional)</span></label>
                        <input type="file" name="image" class="form-control file-preview-input" accept="image/*" data-preview="imgPreview">
                        <div class="form-hint">Max 5 MB.</div>
                        <img id="imgPreview" src="" alt="" style="display:none;margin-top:.5rem;max-width:160px;border-radius:8px;border:2px solid #E2E8F0;">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i>Save Event</button>
                    <a href="events-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
