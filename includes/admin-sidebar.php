<?php
// Determine active section from current filename
$_cf = basename($_SERVER['PHP_SELF']);
function sidebarActive(string ...$pages): string {
    global $_cf;
    foreach ($pages as $p) {
        if (strpos($_cf, $p) !== false) return 'active';
    }
    return '';
}

// Unread inquiry count for badge
$_unread = 0;
try {
    $_unread = (int) $pdo->query('SELECT COUNT(*) FROM contact_inquiries WHERE is_read = 0')->fetchColumn();
} catch (Exception $e) { /* silent */ }
?>
<aside class="admin-sidebar d-flex flex-column">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon-wrap brand-icon-sm me-2">
            <i class="bi bi-cpu-fill"></i>
        </div>
        <span class="brand-name text-white">AI<span class="brand-accent">-Solutions</span></span>
    </div>

    <!-- Admin identity -->
    <div class="sidebar-user">
        <div class="sidebar-avatar"><i class="bi bi-person-fill"></i></div>
        <div>
            <div class="sidebar-username"><?= h($_SESSION['admin_username'] ?? 'Admin') ?></div>
            <div class="sidebar-role">Administrator</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav flex-grow-1">
        <div class="sidebar-section-label">Main</div>

        <a href="dashboard.php" class="sidebar-link <?= sidebarActive('dashboard') ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="inquiries.php" class="sidebar-link <?= sidebarActive('inquiries', 'view-inquiry') ?>">
            <i class="bi bi-envelope"></i> Inquiries
            <?php if ($_unread > 0): ?>
                <span class="sidebar-badge"><?= $_unread ?></span>
            <?php endif; ?>
        </a>

        <div class="sidebar-section-label mt-3">Content</div>

        <a href="gallery-list.php" class="sidebar-link <?= sidebarActive('gallery') ?>">
            <i class="bi bi-images"></i> Gallery
        </a>
        <a href="articles-list.php" class="sidebar-link <?= sidebarActive('articles') ?>">
            <i class="bi bi-newspaper"></i> Articles
        </a>
        <a href="services-list.php" class="sidebar-link <?= sidebarActive('services') ?>">
            <i class="bi bi-gear"></i> Services
        </a>
        <a href="portfolio-list.php" class="sidebar-link <?= sidebarActive('portfolio') ?>">
            <i class="bi bi-briefcase"></i> Portfolio
        </a>
        <a href="testimonials-list.php" class="sidebar-link <?= sidebarActive('testimonials') ?>">
            <i class="bi bi-chat-quote"></i> Testimonials
        </a>
        <a href="events-list.php" class="sidebar-link <?= sidebarActive('events') ?>">
            <i class="bi bi-calendar-event"></i> Events
        </a>

        <div class="sidebar-section-label mt-3">Account</div>

        <a href="<?= BASE_URL ?>/index.php" target="_blank" class="sidebar-link">
            <i class="bi bi-box-arrow-up-right"></i> View Website
        </a>
        <a href="logout.php" class="sidebar-link sidebar-logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>
</aside>
