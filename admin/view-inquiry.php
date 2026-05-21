<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle = 'View Inquiry — AI-Solutions Admin';

$id = validId($_GET['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid inquiry ID.'); header('Location: inquiries.php'); exit; }

try {
    $stmt = $pdo->prepare('SELECT * FROM contact_inquiries WHERE id = ?');
    $stmt->execute([$id]);
    $inq = $stmt->fetch();
} catch (PDOException $e) { $inq = null; }

if (!$inq) { setFlash('danger','Inquiry not found.'); header('Location: inquiries.php'); exit; }

// Mark as read
if (!$inq['is_read']) {
    try { $pdo->prepare('UPDATE contact_inquiries SET is_read = 1 WHERE id = ?')->execute([$id]); $inq['is_read'] = 1; }
    catch (PDOException $e) {}
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    try { $pdo->prepare('DELETE FROM contact_inquiries WHERE id = ?')->execute([$id]); }
    catch (PDOException $e) {}
    setFlash('success','Inquiry deleted.');
    header('Location: inquiries.php'); exit;
}

include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-envelope-open me-2 text-primary"></i>Inquiry #<?= $id ?></h1>
        <div class="admin-topbar-actions">
            <a href="inquiries.php" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-1"></i>Back to Inquiries
            </a>
        </div>
    </div>

    <div class="admin-content">
        <div class="row g-4">

            <!-- Inquiry Details -->
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h6><i class="bi bi-person me-2"></i>Inquiry Details</h6>
                        <span class="badge-read">Read</span>
                    </div>
                    <div class="p-4">
                        <div class="inquiry-detail-row">
                            <div class="inquiry-detail-label">Full Name</div>
                            <div class="inquiry-detail-value fw-600"><?= h($inq['name']) ?></div>
                        </div>
                        <div class="inquiry-detail-row">
                            <div class="inquiry-detail-label">Email</div>
                            <div class="inquiry-detail-value">
                                <a href="mailto:<?= h($inq['email']) ?>"><?= h($inq['email']) ?></a>
                            </div>
                        </div>
                        <div class="inquiry-detail-row">
                            <div class="inquiry-detail-label">Phone</div>
                            <div class="inquiry-detail-value">
                                <a href="tel:<?= h($inq['phone']) ?>"><?= h($inq['phone']) ?></a>
                            </div>
                        </div>
                        <div class="inquiry-detail-row">
                            <div class="inquiry-detail-label">Company</div>
                            <div class="inquiry-detail-value"><?= h($inq['company_name']) ?></div>
                        </div>
                        <div class="inquiry-detail-row">
                            <div class="inquiry-detail-label">Country</div>
                            <div class="inquiry-detail-value"><?= h($inq['country']) ?></div>
                        </div>
                        <div class="inquiry-detail-row">
                            <div class="inquiry-detail-label">Job Title</div>
                            <div class="inquiry-detail-value"><?= h($inq['job_title']) ?></div>
                        </div>
                        <div class="inquiry-detail-row">
                            <div class="inquiry-detail-label">Job Details</div>
                            <div class="inquiry-detail-value" style="white-space:pre-wrap;background:#F8FAFC;padding:.75rem;border-radius:8px;border:1px solid #E2E8F0;">
                                <?= h($inq['job_details']) ?>
                            </div>
                        </div>
                        <div class="inquiry-detail-row">
                            <div class="inquiry-detail-label">Submitted</div>
                            <div class="inquiry-detail-value text-muted">
                                <?= formatDate($inq['submitted_at'], 'l, d F Y \a\t H:i') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card-header"><h6>Actions</h6></div>
                    <div class="p-3 d-flex flex-column gap-2">
                        <a href="mailto:<?= h($inq['email']) ?>?subject=Re: Your Enquiry to AI-Solutions"
                           class="btn btn-primary rounded-pill w-100">
                            <i class="bi bi-envelope me-2"></i>Reply via Email
                        </a>
                        <a href="tel:<?= h($inq['phone']) ?>" class="btn btn-outline-primary rounded-pill w-100">
                            <i class="bi bi-telephone me-2"></i>Call
                        </a>
                        <hr>
                        <form method="POST" onsubmit="return confirm('Permanently delete this inquiry?')">
                            <input type="hidden" name="delete" value="1">
                            <button type="submit" class="btn btn-outline-danger rounded-pill w-100">
                                <i class="bi bi-trash me-2"></i>Delete Inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin-foot.php'; ?>
