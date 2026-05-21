<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle = 'Gallery — AI-Solutions Admin';
try { $images = $pdo->query('SELECT * FROM gallery_images ORDER BY created_at DESC')->fetchAll(); }
catch (PDOException $e) { $images = []; }
$flash = getFlash();
$catLabels = ['promotional'=>'Promotional','team'=>'Team','partner'=>'Partner','product'=>'Product','general'=>'General'];
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-images me-2 text-primary"></i>Gallery</h1>
        <a href="gallery-add.php" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-plus-lg me-1"></i>Add Image</a>
    </div>
    <div class="admin-content">
        <?php if ($flash): ?><div class="flash-alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div><?php endif; ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <h6><i class="bi bi-images me-2"></i>All Gallery Images <span class="text-muted fw-normal" style="font-size:.8rem;">(<?= count($images) ?>)</span></h6>
            </div>
            <?php if (!empty($images)): ?>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Added</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($images as $img): ?>
                        <tr>
                            <td>
                                <?php if (!empty($img['image_path'])): ?>
                                    <img src="<?= h(imgUrl($img['image_path'])) ?>" class="thumb-img" alt="">
                                <?php else: ?>
                                    <div class="thumb-placeholder"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-500"><?= h($img['title']) ?></td>
                            <td><span class="badge bg-light text-secondary"><?= h($catLabels[$img['category']] ?? $img['category']) ?></span></td>
                            <td style="font-size:.8rem;"><?= formatDate($img['created_at']) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="gallery-edit.php?id=<?= (int)$img['id'] ?>" class="btn-action btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="gallery-delete.php" onsubmit="return confirm('Delete this image?')">
                                        <input type="hidden" name="id" value="<?= (int)$img['id'] ?>">
                                        <button type="submit" class="btn-action btn-action-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state"><i class="bi bi-images"></i><p>No gallery images yet. <a href="gallery-add.php">Add the first one.</a></p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
