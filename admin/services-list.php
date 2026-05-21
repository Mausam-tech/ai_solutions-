<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle = 'Services — AI-Solutions Admin';
try { $services = $pdo->query('SELECT id,title,icon_class,display_order,created_at FROM services ORDER BY display_order ASC')->fetchAll(); }
catch (PDOException $e) { $services = []; }
$flash = getFlash();
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-gear me-2 text-primary"></i>Services</h1>
        <a href="services-add.php" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-plus-lg me-1"></i>Add Service</a>
    </div>
    <div class="admin-content">
        <?php if ($flash): ?><div class="flash-alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div><?php endif; ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <h6><i class="bi bi-gear me-2"></i>All Services <span class="text-muted fw-normal" style="font-size:.8rem;">(<?= count($services) ?>)</span></h6>
            </div>
            <?php if (!empty($services)): ?>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Icon</th><th>Title</th><th>Display Order</th><th>Added</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($services as $svc): ?>
                        <tr>
                            <td><div class="thumb-placeholder" style="font-size:1.3rem;color:#2563EB;background:rgba(37,99,235,.1);"><i class="bi <?= h($svc['icon_class']) ?>"></i></div></td>
                            <td class="fw-500"><?= h($svc['title']) ?></td>
                            <td><?= (int)$svc['display_order'] ?></td>
                            <td style="font-size:.8rem;"><?= formatDate($svc['created_at']) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="services-edit.php?id=<?= (int)$svc['id'] ?>" class="btn-action btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="services-delete.php" onsubmit="return confirm('Delete this service?')">
                                        <input type="hidden" name="id" value="<?= (int)$svc['id'] ?>">
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
            <div class="empty-state"><i class="bi bi-gear"></i><p>No services yet. <a href="services-add.php">Add the first service.</a></p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
