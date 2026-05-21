<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
$pageTitle = 'Dashboard — AI-Solutions Admin';

// ─── Stats ───────────────────────────────────────────────────
try {
    $totalInquiries  = (int)$pdo->query('SELECT COUNT(*) FROM contact_inquiries')->fetchColumn();
    $unreadInquiries = (int)$pdo->query('SELECT COUNT(*) FROM contact_inquiries WHERE is_read = 0')->fetchColumn();
    $todayInquiries  = (int)$pdo->query('SELECT COUNT(*) FROM contact_inquiries WHERE DATE(submitted_at) = CURDATE()')->fetchColumn();
    $monthInquiries  = (int)$pdo->query('SELECT COUNT(*) FROM contact_inquiries WHERE MONTH(submitted_at)=MONTH(NOW()) AND YEAR(submitted_at)=YEAR(NOW())')->fetchColumn();
    $galleryCount    = (int)$pdo->query('SELECT COUNT(*) FROM gallery_images')->fetchColumn();
    $articlesCount   = (int)$pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn();
    $servicesCount   = (int)$pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();
    $portfolioCount  = (int)$pdo->query('SELECT COUNT(*) FROM portfolio_items')->fetchColumn();
    $testimonialsCount = (int)$pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn();
    $eventsCount     = (int)$pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();

    $recentInquiries = $pdo->query('SELECT id,name,email,company_name,country,submitted_at,is_read FROM contact_inquiries ORDER BY submitted_at DESC LIMIT 5')->fetchAll();
} catch (PDOException $e) {
    $totalInquiries=$unreadInquiries=$todayInquiries=$monthInquiries=0;
    $galleryCount=$articlesCount=$servicesCount=$portfolioCount=$testimonialsCount=$eventsCount=0;
    $recentInquiries=[];
}

$flash = getFlash();
include '../includes/admin-head.php';
include '../includes/admin-sidebar.php';
?>

<div class="admin-main">
    <!-- Topbar -->
    <div class="admin-topbar">
        <h1 class="admin-page-title"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/index.php" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="bi bi-box-arrow-up-right me-1"></i>View Site
            </a>
        </div>
    </div>

    <div class="admin-content">

        <?php if ($flash): ?>
        <div class="flash-alert alert-<?= h($flash['type']) ?> mb-3">
            <?= h($flash['message']) ?>
        </div>
        <?php endif; ?>

        <!-- ── Inquiry Stats ─────────────────────────── -->
        <p class="text-muted fw-500 mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;">Inquiries Overview</p>
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon blue"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <div class="stat-card-label">Total Inquiries</div>
                        <div class="stat-card-value"><?= $totalInquiries ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon orange"><i class="bi bi-envelope-exclamation"></i></div>
                    <div>
                        <div class="stat-card-label">Unread</div>
                        <div class="stat-card-value"><?= $unreadInquiries ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon green"><i class="bi bi-calendar-day"></i></div>
                    <div>
                        <div class="stat-card-label">Today</div>
                        <div class="stat-card-value"><?= $todayInquiries ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon purple"><i class="bi bi-calendar-month"></i></div>
                    <div>
                        <div class="stat-card-label">This Month</div>
                        <div class="stat-card-value"><?= $monthInquiries ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Content Stats ─────────────────────────── -->
        <p class="text-muted fw-500 mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;">Content Management</p>
        <div class="row g-3 mb-4">
            <?php
            $contentItems = [
                ['icon'=>'bi-images',      'label'=>'Gallery Images', 'count'=>$galleryCount,      'link'=>'gallery-list.php',      'color'=>'teal'],
                ['icon'=>'bi-newspaper',   'label'=>'Articles',       'count'=>$articlesCount,     'link'=>'articles-list.php',     'color'=>'blue'],
                ['icon'=>'bi-gear',        'label'=>'Services',       'count'=>$servicesCount,     'link'=>'services-list.php',     'color'=>'purple'],
                ['icon'=>'bi-briefcase',   'label'=>'Portfolio Items', 'count'=>$portfolioCount,    'link'=>'portfolio-list.php',    'color'=>'orange'],
                ['icon'=>'bi-chat-quote',  'label'=>'Testimonials',   'count'=>$testimonialsCount, 'link'=>'testimonials-list.php', 'color'=>'green'],
                ['icon'=>'bi-calendar-event','label'=>'Events',       'count'=>$eventsCount,       'link'=>'events-list.php',       'color'=>'pink'],
            ];
            foreach ($contentItems as $ci):
            ?>
            <div class="col-6 col-lg-4 col-xl-2">
                <a href="<?= $ci['link'] ?>" class="content-card">
                    <div class="content-card-left">
                        <div class="stat-card-icon <?= $ci['color'] ?> p-0" style="width:36px;height:36px;font-size:1rem;flex-shrink:0;">
                            <i class="bi <?= $ci['icon'] ?>"></i>
                        </div>
                        <div class="content-card-title"><?= $ci['label'] ?></div>
                    </div>
                    <div class="content-card-count"><?= $ci['count'] ?></div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ── Recent Inquiries ──────────────────────── -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h6><i class="bi bi-clock-history me-2 text-primary"></i>Recent Inquiries</h6>
                <a href="inquiries.php" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
            </div>
            <?php if (!empty($recentInquiries)): ?>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Country</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentInquiries as $inq): ?>
                        <tr>
                            <td class="text-muted"><?= (int)$inq['id'] ?></td>
                            <td class="fw-500"><?= h($inq['name']) ?></td>
                            <td><?= h($inq['email']) ?></td>
                            <td><?= h($inq['company_name']) ?></td>
                            <td><?= h($inq['country']) ?></td>
                            <td><?= formatDate($inq['submitted_at'], 'd M Y H:i') ?></td>
                            <td>
                                <?php if ($inq['is_read']): ?>
                                    <span class="badge-read">Read</span>
                                <?php else: ?>
                                    <span class="badge-unread">Unread</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="view-inquiry.php?id=<?= (int)$inq['id'] ?>"
                                   class="btn-action btn-action-view" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>No inquiries yet. They will appear here when customers submit the contact form.</p>
            </div>
            <?php endif; ?>
        </div>

    </div><!-- end admin-content -->
</div><!-- end admin-main -->

<?php include '../includes/admin-foot.php'; ?>
