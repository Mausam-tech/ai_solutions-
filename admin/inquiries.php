<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle = 'Inquiries — AI-Solutions Admin';

// ─── Handle delete ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delId = validId($_POST['delete_id']);
    if ($delId) {
        try {
            $pdo->prepare('DELETE FROM contact_inquiries WHERE id = ?')->execute([$delId]);
            setFlash('success', 'Inquiry deleted successfully.');
        } catch (PDOException $e) {
            setFlash('danger', 'Failed to delete inquiry.');
        }
    }
    header('Location: inquiries.php');
    exit;
}

// ─── Pagination & Search ────────────────────────────────────
$perPage = 10;
$page    = max(1, (int)($_GET['page'] ?? 1));
$search  = trim($_GET['search'] ?? '');
$offset  = ($page - 1) * $perPage;

try {
    if ($search) {
        $like  = '%' . $search . '%';
        $total = (int)$pdo->prepare('SELECT COUNT(*) FROM contact_inquiries WHERE name LIKE ? OR email LIKE ? OR company_name LIKE ?')->execute([$like,$like,$like]) ? $pdo->query("SELECT FOUND_ROWS()")->fetchColumn() : 0;
        $countStmt = $pdo->prepare('SELECT COUNT(*) FROM contact_inquiries WHERE name LIKE ? OR email LIKE ? OR company_name LIKE ?');
        $countStmt->execute([$like,$like,$like]);
        $total = (int)$countStmt->fetchColumn();
        $stmt = $pdo->prepare('SELECT * FROM contact_inquiries WHERE name LIKE ? OR email LIKE ? OR company_name LIKE ? ORDER BY submitted_at DESC LIMIT ? OFFSET ?');
        $stmt->execute([$like,$like,$like,$perPage,$offset]);
    } else {
        $countStmt = $pdo->query('SELECT COUNT(*) FROM contact_inquiries');
        $total = (int)$countStmt->fetchColumn();
        $stmt  = $pdo->prepare('SELECT * FROM contact_inquiries ORDER BY submitted_at DESC LIMIT ? OFFSET ?');
        $stmt->execute([$perPage, $offset]);
    }
    $inquiries = $stmt->fetchAll();
    $totalPages = (int)ceil($total / $perPage);
} catch (PDOException $e) {
    $inquiries = [];
    $total = $totalPages = 0;
}

$flash = getFlash();
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-envelope me-2 text-primary"></i>Customer Inquiries</h1>
    </div>

    <div class="admin-content">
        <?php if ($flash): ?>
        <div class="flash-alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
        <?php endif; ?>

        <!-- Search bar -->
        <form method="GET" action="inquiries.php" class="mb-4">
            <div class="input-group" style="max-width:400px;">
                <input type="text" class="form-control" name="search"
                       placeholder="Search by name, email or company…"
                       value="<?= h($search) ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                <?php if ($search): ?>
                <a href="inquiries.php" class="btn btn-outline-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="admin-card">
            <div class="admin-card-header">
                <h6><i class="bi bi-list-ul me-2"></i>
                    <?= $search ? 'Search results for "' . h($search) . '"' : 'All Inquiries' ?>
                    <span class="text-muted fw-normal" style="font-size:.8rem;">(<?= $total ?> total)</span>
                </h6>
            </div>

            <?php if (!empty($inquiries)): ?>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Country</th>
                            <th>Job Title</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inquiries as $inq): ?>
                        <tr style="<?= !$inq['is_read'] ? 'font-weight:500;' : '' ?>">
                            <td class="text-muted"><?= (int)$inq['id'] ?></td>
                            <td><?= h($inq['name']) ?></td>
                            <td style="font-size:.8rem;"><?= h($inq['email']) ?></td>
                            <td style="font-size:.8rem;"><?= h($inq['phone']) ?></td>
                            <td><?= h($inq['company_name']) ?></td>
                            <td><?= h($inq['country']) ?></td>
                            <td><?= h($inq['job_title']) ?></td>
                            <td style="font-size:.78rem;white-space:nowrap;"><?= formatDate($inq['submitted_at'], 'd M Y') ?></td>
                            <td>
                                <?= $inq['is_read'] ? '<span class="badge-read">Read</span>' : '<span class="badge-unread">Unread</span>' ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="view-inquiry.php?id=<?= (int)$inq['id'] ?>"
                                       class="btn-action btn-action-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form method="POST" action="inquiries.php" class="d-inline"
                                          onsubmit="return confirm('Delete this inquiry? This cannot be undone.')">
                                        <input type="hidden" name="delete_id" value="<?= (int)$inq['id'] ?>">
                                        <button type="submit" class="btn-action btn-action-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="d-flex justify-content-center py-3">
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($search) ?>"><?= $p ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p><?= $search ? 'No inquiries match your search.' : 'No inquiries received yet.' ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/admin-foot.php'; ?>
