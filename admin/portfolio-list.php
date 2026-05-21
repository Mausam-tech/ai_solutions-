<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle = 'Portfolio — AI-Solutions Admin';
try { $items = $pdo->query('SELECT id,title,industry,cover_image_path,created_at FROM portfolio_items ORDER BY created_at DESC')->fetchAll(); }
catch (PDOException $e) { $items = []; }
$flash = getFlash();
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-briefcase me-2 text-primary"></i>Portfolio</h1>
        <a href="portfolio-add.php" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-plus-lg me-1"></i>Add Project</a>
    </div>
    <div class="admin-content">
        <?php if ($flash): ?><div class="flash-alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div><?php endif; ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <h6><i class="bi bi-briefcase me-2"></i>All Portfolio Items <span class="text-muted fw-normal" style="font-size:.8rem;">(<?= count($items) ?>)</span></h6>
            </div>
            <?php if (!empty($items)): ?>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Image</th><th>Project Title</th><th>Industry</th><th>Added</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['cover_image_path'])): ?>
                                    <img src="<?= h(imgUrl($item['cover_image_path'])) ?>" class="thumb-img" alt="">
                                <?php else: ?>
                                    <div class="thumb-placeholder"><i class="bi bi-briefcase"></i></div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-500"><?= h($item['title']) ?></td>
                            <td><span class="badge bg-light text-secondary"><?= h($item['industry']) ?></span></td>
                            <td style="font-size:.8rem;"><?= formatDate($item['created_at']) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="portfolio-edit.php?id=<?= (int)$item['id'] ?>" class="btn-action btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="portfolio-delete.php" onsubmit="return confirm('Delete this project?')">
                                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
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
            <div class="empty-state"><i class="bi bi-briefcase"></i><p>No portfolio items yet. <a href="portfolio-add.php">Add the first project.</a></p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
