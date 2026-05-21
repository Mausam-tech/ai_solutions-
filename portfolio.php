<?php
$pageTitle = 'Portfolio — AI-Solutions';
require_once 'includes/db.php';

try {
    $portfolioItems = $pdo->query('SELECT * FROM portfolio_items ORDER BY created_at DESC')->fetchAll();
    $industries = $pdo->query('SELECT DISTINCT industry FROM portfolio_items ORDER BY industry')->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $portfolioItems = [];
    $industries = [];
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container page-hero-content">
        <nav aria-label="breadcrumb" class="page-hero-breadcrumb mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Portfolio</li>
            </ol>
        </nav>
        <h1 class="page-hero-title">Our Portfolio</h1>
        <p class="page-hero-subtitle">Delivering AI innovation across industries — real projects, real outcomes</p>
    </div>
</section>

<!-- FILTER + GRID -->
<section class="section-white">
    <div class="container">

        <!-- Filter buttons -->
        <?php if (!empty($industries)): ?>
        <div class="filter-btn-group mb-5" id="portfolioFilters">
            <button class="filter-btn active" data-filter="all"
                    data-container="#portfolioGrid" onclick="filterPortfolio('all', this)">All</button>
            <?php foreach ($industries as $ind): ?>
            <button class="filter-btn" data-filter="<?= h($ind) ?>"
                    onclick="filterPortfolio('<?= h($ind) ?>', this)"><?= h($ind) ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Grid -->
        <div class="row g-4" id="portfolioGrid">
            <?php if (!empty($portfolioItems)): ?>
                <?php foreach ($portfolioItems as $item): ?>
                <div class="col-lg-4 col-md-6 portfolio-grid-item" data-category="<?= h($item['industry']) ?>">
                    <div class="portfolio-card"
                         data-bs-toggle="modal" data-bs-target="#portfolioModal"
                         data-title="<?= h($item['title']) ?>"
                         data-industry="<?= h($item['industry']) ?>"
                         data-tech="<?= h($item['tech_tags']) ?>"
                         data-challenge="<?= h($item['challenge']) ?>"
                         data-solution="<?= h($item['solution']) ?>"
                         data-outcome="<?= h($item['outcome']) ?>"
                         data-img="<?= !empty($item['cover_image_path']) ? h(imgUrl($item['cover_image_path'])) : '' ?>"
                         style="cursor:pointer;">
                        <?php if (!empty($item['cover_image_path'])): ?>
                            <img src="<?= h(imgUrl($item['cover_image_path'])) ?>" alt="<?= h($item['title']) ?>">
                        <?php else: ?>
                            <div class="img-placeholder w-100 h-100"><i class="bi bi-briefcase"></i></div>
                        <?php endif; ?>
                        <div class="portfolio-overlay">
                            <span class="portfolio-badge"><?= h($item['industry']) ?></span>
                            <h5><?= h($item['title']) ?></h5>
                            <p><?= h(mb_substr($item['short_description'], 0, 90)) ?>…</p>
                        </div>
                        <span class="portfolio-view-btn">View Case Study</span>
                    </div>
                    <!-- Tech tags below card -->
                    <div class="mt-2 d-flex flex-wrap gap-1">
                        <?php foreach (array_filter(array_map('trim', explode(',', $item['tech_tags']))) as $tag): ?>
                        <span class="badge" style="background:rgba(37,99,235,.1);color:#2563EB;font-size:.68rem;font-weight:500;"><?= h($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-briefcase fs-1 d-block mb-3"></i>
                <p>No portfolio items yet. Check back soon!</p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<!-- PORTFOLIO DETAIL MODAL -->
<div class="modal fade" id="portfolioModal" tabindex="-1" aria-labelledby="portfolioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <span class="badge bg-primary mb-2" id="pm-industry"></span>
                    <h5 class="modal-title" id="pm-title"></h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <img id="pm-img" src="" alt="" class="img-fluid rounded mb-3 w-100"
                     style="max-height:240px;object-fit:cover;display:none;">
                <div class="mb-3">
                    <small class="text-muted fw-bold text-uppercase" style="font-size:.7rem;letter-spacing:.06em;">Technologies Used</small>
                    <p id="pm-tech" class="mt-1 text-muted" style="font-size:.88rem;"></p>
                </div>
                <hr>
                <div class="mb-3">
                    <h6 class="fw-bold" style="font-size:.9rem;color:#2563EB;">The Challenge</h6>
                    <p id="pm-challenge" style="font-size:.88rem;color:#475569;"></p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold" style="font-size:.9rem;color:#2563EB;">Our Solution</h6>
                    <p id="pm-solution" style="font-size:.88rem;color:#475569;"></p>
                </div>
                <div>
                    <h6 class="fw-bold" style="font-size:.9rem;color:#2563EB;">The Outcome</h6>
                    <p id="pm-outcome" style="font-size:.88rem;color:#475569;"></p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <a href="contact.php" class="btn btn-primary rounded-pill px-4">
                    Discuss a Similar Project <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<section class="cta-section text-center">
    <div class="container">
        <h2 class="mb-3">Want Similar Results?</h2>
        <p class="mb-4">Tell us about your challenge and let's explore what AI can do for your organisation.</p>
        <a href="contact.php" class="btn btn-light btn-lg rounded-pill px-5">Start a Conversation</a>
    </div>
</section>

<script>
function filterPortfolio(category, btn) {
    // Update active button
    document.querySelectorAll('#portfolioFilters .filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    // Filter items
    document.querySelectorAll('.portfolio-grid-item').forEach(item => {
        item.style.display = (category === 'all' || item.dataset.category === category) ? '' : 'none';
    });
}
</script>

<?php include 'includes/footer.php'; ?>
