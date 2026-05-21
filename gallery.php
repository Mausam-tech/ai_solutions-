<?php
$pageTitle = 'Photo Gallery — AI-Solutions';
require_once 'includes/db.php';

try {
    $images     = $pdo->query('SELECT * FROM gallery_images ORDER BY created_at DESC')->fetchAll();
    $categories = $pdo->query('SELECT DISTINCT category FROM gallery_images ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $images = [];
    $categories = [];
}

$categoryLabels = [
    'promotional' => 'Promotional Events',
    'team'        => 'Team Photos',
    'partner'     => 'Partner Events',
    'product'     => 'Product Launches',
    'general'     => 'General',
];

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container page-hero-content">
        <nav aria-label="breadcrumb" class="page-hero-breadcrumb mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Gallery</li>
            </ol>
        </nav>
        <h1 class="page-hero-title">Photo Gallery</h1>
        <p class="page-hero-subtitle">Behind the scenes at AI-Solutions — events, team moments, and milestones</p>
    </div>
</section>

<!-- GALLERY -->
<section class="section-white">
    <div class="container">

        <!-- Filter -->
        <?php if (count($categories) > 1): ?>
        <div class="filter-btn-group mb-5" id="galleryFilters">
            <button class="filter-btn active" onclick="filterGallery('all', this)">All</button>
            <?php foreach ($categories as $cat): ?>
            <button class="filter-btn" onclick="filterGallery('<?= h($cat) ?>', this)">
                <?= h($categoryLabels[$cat] ?? ucfirst($cat)) ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Grid -->
        <?php if (!empty($images)): ?>
        <div class="gallery-grid" id="galleryGrid">
            <?php foreach ($images as $img): ?>
            <div class="gallery-item" data-category="<?= h($img['category']) ?>">
                <?php if (!empty($img['image_path'])): ?>
                    <img src="<?= h(imgUrl($img['image_path'])) ?>" alt="<?= h($img['title']) ?>" loading="lazy">
                <?php else: ?>
                    <div class="gallery-item-placeholder">
                        <i class="bi bi-image"></i>
                        <span><?= h($img['title']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="gallery-overlay">
                    <div class="gallery-caption"><?= h($img['title']) ?></div>
                    <small style="color:rgba(255,255,255,.6);font-size:.7rem;">
                        <?= h($categoryLabels[$img['category']] ?? ucfirst($img['category'])) ?>
                    </small>
                </div>
                <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-images fs-1 d-block mb-3"></i>
            <p>Gallery images are being added. Check back soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
function filterGallery(category, btn) {
    document.querySelectorAll('#galleryFilters .filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('#galleryGrid .gallery-item').forEach(item => {
        item.style.display = (category === 'all' || item.dataset.category === category) ? '' : 'none';
    });
}
</script>

<?php include 'includes/footer.php'; ?>
