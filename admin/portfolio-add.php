<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/upload-helper.php';
$pageTitle = 'Add Portfolio Project — Admin';
$errors = [];
$industries = ['Healthcare','Finance','Manufacturing','Retail','Education','Logistics','Technology','Other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title     = trim($_POST['title']            ?? '');
    $industry  = trim($_POST['industry']         ?? '');
    $shortDesc = trim($_POST['short_description']?? '');
    $techTags  = trim($_POST['tech_tags']        ?? '');
    $challenge = trim($_POST['challenge']        ?? '');
    $solution  = trim($_POST['solution']         ?? '');
    $outcome   = trim($_POST['outcome']          ?? '');
    if (mb_strlen($title)<3)      $errors[]='Title must be at least 3 characters.';
    if (empty($industry))          $errors[]='Industry is required.';
    if (mb_strlen($shortDesc)<10)  $errors[]='Short description must be at least 10 characters.';
    if (mb_strlen($challenge)<10)  $errors[]='Challenge field must be at least 10 characters.';
    if (mb_strlen($solution)<10)   $errors[]='Solution field must be at least 10 characters.';
    if (mb_strlen($outcome)<10)    $errors[]='Outcome field must be at least 10 characters.';
    $uploadErr=uploadErrorMessage('cover_image');
    if ($uploadErr) $errors[]=$uploadErr;

    if (empty($errors)) {
        $imgPath='';
        if (!empty($_FILES['cover_image']['name'])) {
            $imgPath=handleImageUpload('cover_image','portfolio');
            if ($imgPath===false){$errors[]='Image upload failed.';}
        }
        if (empty($errors)) {
            try {
                $pdo->prepare('INSERT INTO portfolio_items (title,industry,cover_image_path,short_description,tech_tags,challenge,solution,outcome) VALUES (?,?,?,?,?,?,?,?)')->execute([$title,$industry,$imgPath,$shortDesc,$techTags,$challenge,$solution,$outcome]);
                setFlash('success','Portfolio project added successfully.');
                header('Location: portfolio-list.php'); exit;
            } catch (PDOException $e){$errors[]='Database error.';}
        }
    }
}
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Portfolio Project</h1>
        <a href="portfolio-list.php" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="admin-content">
        <?php if (!empty($errors)): ?><div class="flash-alert alert-danger mb-3"><?= implode('<br>',array_map('h',$errors)) ?></div><?php endif; ?>
        <div class="admin-form-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Project Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= h($_POST['title']??'') ?>" placeholder="e.g. NHS AI Triage Assistant" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Industry <span class="text-danger">*</span></label>
                        <select name="industry" class="form-select" required>
                            <option value="">Select industry</option>
                            <?php foreach ($industries as $ind): ?>
                            <option value="<?= h($ind) ?>" <?= ($_POST['industry']??'')===$ind?'selected':'' ?>><?= h($ind) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Short Description <span class="text-danger">*</span></label>
                        <textarea name="short_description" class="form-control" rows="2" placeholder="Shown on the portfolio card (1–2 sentences)." required><?= h($_POST['short_description']??'') ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control file-preview-input" accept="image/*" data-preview="imgPreview">
                        <div class="form-hint">Max 5 MB. Recommended: 800×500px.</div>
                        <img id="imgPreview" src="" alt="" style="display:none;margin-top:.5rem;max-width:180px;border-radius:8px;border:2px solid #E2E8F0;">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Technologies Used</label>
                        <input type="text" name="tech_tags" class="form-control" value="<?= h($_POST['tech_tags']??'') ?>" placeholder="e.g. Python, NLP, React, REST API (comma-separated)">
                    </div>
                    <div class="col-12">
                        <label class="form-label">The Challenge <span class="text-danger">*</span></label>
                        <textarea name="challenge" class="form-control" rows="4" placeholder="Describe the problem the client faced before AI-Solutions got involved." required><?= h($_POST['challenge']??'') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Our Solution <span class="text-danger">*</span></label>
                        <textarea name="solution" class="form-control" rows="4" placeholder="Describe what AI-Solutions built and how it addressed the challenge." required><?= h($_POST['solution']??'') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">The Outcome <span class="text-danger">*</span></label>
                        <textarea name="outcome" class="form-control" rows="4" placeholder="Describe the measurable results and impact for the client." required><?= h($_POST['outcome']??'') ?></textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i>Save Project</button>
                    <a href="portfolio-list.php" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
