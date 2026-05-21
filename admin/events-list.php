<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle='Events — AI-Solutions Admin';
try{$events=$pdo->query('SELECT id,title,event_type,event_date,location,image_path FROM events ORDER BY event_date DESC')->fetchAll();}
catch(PDOException $e){$events=[];}
$flash=getFlash();
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>
<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-calendar-event me-2 text-primary"></i>Events</h1>
        <a href="events-add.php" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-plus-lg me-1"></i>Add Event</a>
    </div>
    <div class="admin-content">
        <?php if ($flash): ?><div class="flash-alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div><?php endif; ?>
        <div class="admin-card">
            <div class="admin-card-header"><h6><i class="bi bi-calendar-event me-2"></i>All Events <span class="text-muted fw-normal" style="font-size:.8rem;">(<?= count($events) ?>)</span></h6></div>
            <?php if (!empty($events)): ?>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Image</th><th>Title</th><th>Type</th><th>Date</th><th>Location</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($events as $ev): ?>
                        <?php $isUpcoming = strtotime($ev['event_date']) >= strtotime(date('Y-m-d')); ?>
                        <tr>
                            <td>
                                <?php if (!empty($ev['image_path'])): ?>
                                    <img src="<?= h(imgUrl($ev['image_path'])) ?>" class="thumb-img" alt="">
                                <?php else: ?>
                                    <div class="thumb-placeholder"><i class="bi bi-calendar-event"></i></div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-500"><?= h($ev['title']) ?></td>
                            <td><span class="badge bg-light text-secondary"><?= h($ev['event_type']) ?></span></td>
                            <td style="font-size:.82rem;white-space:nowrap;"><?= formatDate($ev['event_date'],'d M Y') ?></td>
                            <td style="font-size:.82rem;"><?= h($ev['location']) ?></td>
                            <td>
                                <?php if ($isUpcoming): ?>
                                    <span style="font-size:.72rem;font-weight:600;color:#16a34a;background:rgba(34,197,94,.1);padding:.2rem .55rem;border-radius:50px;">Upcoming</span>
                                <?php else: ?>
                                    <span style="font-size:.72rem;font-weight:600;color:#64748B;background:#F1F5F9;padding:.2rem .55rem;border-radius:50px;">Past</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="events-edit.php?id=<?= (int)$ev['id'] ?>" class="btn-action btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="events-delete.php" onsubmit="return confirm('Delete this event?')">
                                        <input type="hidden" name="id" value="<?= (int)$ev['id'] ?>">
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
            <div class="empty-state"><i class="bi bi-calendar-event"></i><p>No events yet. <a href="events-add.php">Add the first event.</a></p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/admin-foot.php'; ?>
