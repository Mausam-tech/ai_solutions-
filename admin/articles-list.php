<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle = 'Articles — AI-Solutions Admin';
try { $articles = $pdo->query('SELECT id,title,category,thumbnail_path,created_at FROM articles ORDER BY created_at DESC')->fetchAll(); }
catch (PDOException $e) { $articles = []; }
$flash = getFlash();
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-newspaper me-2 text-primary"></i>Articles</h1>
        <a href="articles-add.php" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-plus-lg me-1"></i>Add Article</a>
    </div>
    <div class="admin-content">
        <?php if ($flash): ?><div class="flash-alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div><?php endif; ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <h6><i class="bi bi-newspaper me-2"></i>All Articles <span class="text-muted fw-normal" style="font-size:.8rem;">(<?= count($articles) ?>)</span></h6>
            </div>
            <?php if (!empty($articles)): ?>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Thumbnail</th><th>Title</th><th>Category</th><th>Published</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($articles as $art): ?>
                        <tr>
                            <td>
                                <?php if (!empty($art['thumbnail_path'])): ?>
                                    <img src="<?= h(imgUrl($art['thumbnail_path'])) ?>" class="thumb-img" alt="">
                                <?php else: ?>
                                    <div class="thumb-placeholder"><i class="bi bi-newspaper"></i></div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-500"><?= h($art['title']) ?></td>
                            <td><span class="badge bg-light text-secondary"><?= h($art['category']) ?></span></td>
                            <td style="font-size:.8rem;"><?= formatDate($art['created_at']) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="articles-edit.php?id=<?= (int)$art['id'] ?>" class="btn-action btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="articles-delete.php" onsubmit="return confirm('Delete this article?')">
                                        <input type="hidden" name="id" value="<?= (int)$art['id'] ?>">
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
            <div class="empty-state"><i class="bi bi-newspaper"></i><p>No articles yet. <a href="articles-add.php">Write the first one.</a></p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
