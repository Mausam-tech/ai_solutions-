<?php
http_response_code(404);
$pageTitle = 'Page Not Found — AI-Solutions';
require_once 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="error-404" style="background:var(--body-bg,#F8FAFC);">
    <div class="container text-center py-5">
        <div class="error-404-number">404</div>
        <h2 class="mb-3 mt-2">Page Not Found</h2>
        <p class="text-muted mb-5" style="max-width:480px;margin:0 auto;">
            The page you're looking for doesn't exist or may have been moved.
            Let's get you back on track.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-house me-2"></i>Back to Home
            </a>
            <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline-primary rounded-pill px-4">
                Contact Us
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
