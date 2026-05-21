<?php
$pageTitle = 'Client Testimonials — AI-Solutions';
require_once 'includes/db.php';

try {
    $testimonials = $pdo->query('SELECT * FROM testimonials ORDER BY created_at DESC')->fetchAll();
    $stats = $pdo->query('SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM testimonials')->fetch();
    $ratingCounts = $pdo->query('SELECT rating, COUNT(*) as cnt FROM testimonials GROUP BY rating ORDER BY rating DESC')->fetchAll();
} catch (PDOException $e) {
    $testimonials = [];
    $stats = ['avg_rating' => 0, 'total' => 0];
    $ratingCounts = [];
}

$avgRating = round((float)($stats['avg_rating'] ?? 0), 1);
$total     = (int)($stats['total'] ?? 0);

// Build rating distribution map
$ratingMap = [];
foreach ($ratingCounts as $r) { $ratingMap[(int)$r['rating']] = (int)$r['cnt']; }

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container page-hero-content">
        <nav aria-label="breadcrumb" class="page-hero-breadcrumb mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Testimonials</li>
            </ol>
        </nav>
        <h1 class="page-hero-title">Client Testimonials</h1>
        <p class="page-hero-subtitle">What the organisations we work with say about us</p>
    </div>
</section>

<!-- OVERALL RATING SUMMARY -->
<?php if ($total > 0): ?>
<section class="section-light py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="overall-rating-box">
                    <div class="overall-score"><?= $avgRating ?></div>
                    <div class="overall-stars my-2"><?= renderStars((int)round($avgRating)) ?></div>
                    <p class="text-muted mb-3" style="font-size:.88rem;">Based on <?= $total ?> client review<?= $total !== 1 ? 's' : '' ?></p>
                    <?php for ($s = 5; $s >= 1; $s--): ?>
                    <?php $cnt = $ratingMap[$s] ?? 0; $pct = $total > 0 ? round(($cnt / $total) * 100) : 0; ?>
                    <div class="rating-bar-row">
                        <span><?= $s ?>★</span>
                        <div class="rating-bar-track">
                            <div class="rating-bar-fill" style="width:<?= $pct ?>%"></div>
                        </div>
                        <span><?= $cnt ?></span>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FILTER + GRID -->
<section class="section-white">
    <div class="container">

        <!-- Filter buttons -->
        <div class="filter-btn-group mb-5" id="ratingFilters">
            <button class="filter-btn active" onclick="filterRating('all', this)">All Reviews</button>
            <button class="filter-btn" onclick="filterRating('5', this)">5 ★</button>
            <button class="filter-btn" onclick="filterRating('4', this)">4 ★</button>
            <button class="filter-btn" onclick="filterRating('3', this)">3 ★</button>
        </div>

        <div class="row g-4" id="testimonialsGrid">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $t): ?>
                <div class="col-lg-4 col-md-6 testimonial-item" data-rating="<?= (int)$t['rating'] ?>">
                    <div class="testimonial-card h-100 d-flex flex-column">
                        <div class="testimonial-quote">"</div>
                        <p class="testimonial-text flex-grow-1"><?= h($t['testimonial_text']) ?></p>
                        <div class="testimonial-stars"><?= renderStars((int)$t['rating']) ?></div>
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <div class="testimonial-avatar">
                                <?php if (!empty($t['avatar_path'])): ?>
                                    <img src="<?= h(imgUrl($t['avatar_path'])) ?>" alt="<?= h($t['client_name']) ?>">
                                <?php else: ?>
                                    <?= strtoupper(mb_substr($t['client_name'], 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="testimonial-name"><?= h($t['client_name']) ?></div>
                                <div class="testimonial-role"><?= h($t['job_title']) ?>, <?= h($t['company_name']) ?></div>
                                <div style="font-size:.72rem;color:#94A3B8;margin-top:.15rem;"><?= formatDate($t['created_at']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-chat-quote fs-1 d-block mb-3"></i>
                <p>No testimonials yet. Check back soon!</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section text-center">
    <div class="container">
        <h2 class="mb-3">Become Our Next Success Story</h2>
        <p class="mb-4">Join a growing list of organisations transforming their operations with AI-Solutions.</p>
        <a href="contact.php" class="btn btn-light btn-lg rounded-pill px-5">Get Started Today</a>
    </div>
</section>

<script>
function filterRating(rating, btn) {
    document.querySelectorAll('#ratingFilters .filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.testimonial-item').forEach(item => {
        item.style.display = (rating === 'all' || item.dataset.rating === rating) ? '' : 'none';
    });
}
</script>

<?php include 'includes/footer.php'; ?>
