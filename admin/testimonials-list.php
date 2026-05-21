<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle='Testimonials — AI-Solutions Admin';
try{$items=$pdo->query('SELECT id,client_name,company_name,rating,avatar_path,created_at FROM testimonials ORDER BY created_at DESC')->fetchAll();}
catch(PDOException $e){$items=[];}
$flash=getFlash();
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-chat-quote me-2 text-primary"></i>Testimonials</h1>
        <a href="testimonials-add.php" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-plus-lg me-1"></i>Add Testimonial</a>
    </div>
    <div class="admin-content">
        <?php if ($flash): ?><div class="flash-alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div><?php endif; ?>
        <div class="admin-card">
            <div class="admin-card-header"><h6><i class="bi bi-chat-quote me-2"></i>All Testimonials <span class="text-muted fw-normal" style="font-size:.8rem;">(<?= count($items) ?>)</span></h6></div>
            <?php if (!empty($items)): ?>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Avatar</th><th>Client Name</th><th>Company</th><th>Rating</th><th>Added</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($items as $t): ?>
                        <tr>
                            <td>
                                <?php if (!empty($t['avatar_path'])): ?>
                                    <img src="<?= h(imgUrl($t['avatar_path'])) ?>" class="thumb-img" style="border-radius:50%;" alt="">
                                <?php else: ?>
                                    <div class="thumb-placeholder" style="border-radius:50%;background:linear-gradient(135deg,#2563EB,#7C3AED);color:#fff;font-weight:700;">
                                        <?= strtoupper(mb_substr($t['client_name'],0,1)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-500"><?= h($t['client_name']) ?></td>
                            <td><?= h($t['company_name']) ?></td>
                            <td><?= renderStars((int)$t['rating']) ?></td>
                            <td style="font-size:.8rem;"><?= formatDate($t['created_at']) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="testimonials-edit.php?id=<?= (int)$t['id'] ?>" class="btn-action btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="testimonials-delete.php" onsubmit="return confirm('Delete this testimonial?')">
                                        <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
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
            <div class="empty-state"><i class="bi bi-chat-quote"></i><p>No testimonials yet. <a href="testimonials-add.php">Add the first one.</a></p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
