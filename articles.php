<?php
$pageTitle = 'Articles & Insights — AI-Solutions';
require_once 'includes/db.php';

try {
    $articles = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC')->fetchAll();
} catch (PDOException $e) {
    $articles = [];
}

$featured = !empty($articles) ? array_shift($articles) : null;

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container page-hero-content">
        <nav aria-label="breadcrumb" class="page-hero-breadcrumb mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Articles</li>
            </ol>
        </nav>
        <h1 class="page-hero-title">Insights & Articles</h1>
        <p class="page-hero-subtitle">Thought leadership on AI, the digital workplace, and industry innovation</p>
    </div>
</section>

<!-- ARTICLES CONTENT -->
<section class="section-white">
    <div class="container">

        <?php if (empty($featured) && empty($articles)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-newspaper fs-1 d-block mb-3"></i>
            <p>No articles published yet. Check back soon!</p>
        </div>
        <?php else: ?>

        <!-- FEATURED ARTICLE -->
        <?php if ($featured): ?>
        <div class="row g-0 mb-5 rounded-4 overflow-hidden border"
             style="background:#fff;box-shadow:0 4px 20px rgba(0,0,0,.06);">
            <div class="col-md-5">
                <?php if (!empty($featured['thumbnail_path'])): ?>
                    <img src="<?= h(imgUrl($featured['thumbnail_path'])) ?>"
                         alt="<?= h($featured['title']) ?>"
                         class="w-100 h-100" style="object-fit:cover;min-height:300px;">
                <?php else: ?>
                    <div class="img-placeholder h-100" style="min-height:300px;">
                        <i class="bi bi-newspaper"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-7 d-flex flex-column justify-content-center p-4 p-md-5">
                <span class="article-category mb-2"><?= h($featured['category']) ?></span>
                <span class="article-date mb-2"><?= formatDate($featured['created_at']) ?></span>
                <h3 class="mb-3" style="font-size:1.4rem;"><?= h($featured['title']) ?></h3>
                <p class="text-muted mb-4" style="font-size:.92rem;line-height:1.75;"><?= h($featured['excerpt']) ?></p>
                <div>
                    <button class="btn btn-primary rounded-pill px-4"
                            data-bs-toggle="modal" data-bs-target="#articleModal"
                            data-title="<?= h($featured['title']) ?>"
                            data-category="<?= h($featured['category']) ?>"
                            data-date="<?= formatDate($featured['created_at']) ?>"
                            data-content="<?= h($featured['content']) ?>">
                        Read Full Article <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- REMAINING ARTICLES GRID -->
        <?php if (!empty($articles)): ?>
        <div class="row g-4">
            <?php foreach ($articles as $art): ?>
            <div class="col-lg-4 col-md-6">
                <div class="article-card h-100">
                    <?php if (!empty($art['thumbnail_path'])): ?>
                        <img class="article-img" src="<?= h(imgUrl($art['thumbnail_path'])) ?>" alt="<?= h($art['title']) ?>">
                    <?php else: ?>
                        <div class="article-img-placeholder img-placeholder"><i class="bi bi-newspaper"></i></div>
                    <?php endif; ?>
                    <div class="article-body d-flex flex-column h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="article-category"><?= h($art['category']) ?></span>
                            <span class="article-date"><?= formatDate($art['created_at']) ?></span>
                        </div>
                        <div class="article-title"><?= h($art['title']) ?></div>
                        <p class="article-excerpt flex-grow-1"><?= h(mb_substr($art['excerpt'], 0, 140)) ?>…</p>
                        <button class="btn btn-sm btn-outline-primary rounded-pill mt-3 align-self-start"
                                data-bs-toggle="modal" data-bs-target="#articleModal"
                                data-title="<?= h($art['title']) ?>"
                                data-category="<?= h($art['category']) ?>"
                                data-date="<?= formatDate($art['created_at']) ?>"
                                data-content="<?= h($art['content']) ?>">
                            Read Article
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<!-- ARTICLE MODAL -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <span class="article-category mb-1 d-inline-block" id="am-category"></span>
                    <span class="article-date ms-2" id="am-date"></span>
                    <h5 class="modal-title mt-1" id="am-title"></h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="am-content" style="font-size:.92rem;line-height:1.85;color:#475569;"></div>
            <div class="modal-footer border-0">
                <a href="contact.php" class="btn btn-primary rounded-pill px-4">Contact Us</a>
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('articleModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('am-title').textContent    = btn.dataset.title    || '';
    document.getElementById('am-category').textContent = btn.dataset.category || '';
    document.getElementById('am-date').textContent     = btn.dataset.date     || '';
    // Render HTML content safely (already server-escaped, re-decode for display)
    const tmp = document.createElement('div');
    tmp.innerHTML = btn.dataset.content || '';
    document.getElementById('am-content').innerHTML = tmp.innerText;
});
</script>

<?php include 'includes/footer.php'; ?>
