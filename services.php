<?php
$pageTitle = 'Our Services — AI-Solutions';
require_once 'includes/db.php';

try {
    $services = $pdo->query('SELECT * FROM services ORDER BY display_order ASC')->fetchAll();
} catch (PDOException $e) {
    $services = [];
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
                <li class="breadcrumb-item active">Services</li>
            </ol>
        </nav>
        <h1 class="page-hero-title">Our Services</h1>
        <p class="page-hero-subtitle">Practical AI solutions designed to transform how your organisation works</p>
    </div>
</section>

<!-- SERVICES GRID -->
<section class="section-white">
    <div class="container">
        <?php if (!empty($services)): ?>
        <div class="row g-4">
            <?php foreach ($services as $svc): ?>
            <div class="col-lg-6">
                <div class="service-card h-100" style="padding: 2rem;">
                    <div class="d-flex align-items-start gap-4">
                        <div class="service-icon flex-shrink-0" style="width:64px;height:64px;font-size:1.8rem;">
                            <i class="bi <?= h($svc['icon_class']) ?>"></i>
                        </div>
                        <div class="flex-grow-1">
                            <?php if (!empty($svc['image_path'])): ?>
                            <img src="<?= h(imgUrl($svc['image_path'])) ?>"
                                 alt="<?= h($svc['title']) ?>"
                                 class="img-fluid rounded mb-3" style="max-height:160px;width:100%;object-fit:cover;">
                            <?php endif; ?>
                            <h4 class="mb-2" style="font-size:1.15rem;"><?= h($svc['title']) ?></h4>
                            <p class="text-muted mb-3" style="font-size:.9rem;"><?= h($svc['full_description']) ?></p>
                            <?php
                                $featureLines = array_filter(array_map('trim', explode("\n", $svc['features'])));
                            ?>
                            <?php if (!empty($featureLines)): ?>
                            <ul class="list-unstyled mb-3">
                                <?php foreach ($featureLines as $feat): ?>
                                <li class="d-flex align-items-start gap-2 mb-1" style="font-size:.85rem;">
                                    <i class="bi bi-check-circle-fill text-primary mt-1 flex-shrink-0"></i>
                                    <span class="text-muted"><?= h($feat) ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                            <a href="contact.php" class="btn btn-sm btn-primary rounded-pill px-3">
                                Request This Service <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-gear fs-1 d-block mb-3"></i>
            <p>Services are being updated. Please check back soon or <a href="contact.php">contact us</a> directly.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="section-light">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">Why AI-Solutions</span>
            <h2 class="section-title text-center">Built for Real Results</h2>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="why-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                <h5 class="mb-2">Speed</h5>
                <p class="text-muted" style="font-size:.9rem;">We prototype in days, not months — so you see real value fast and can iterate based on what actually works.</p>
            </div>
            <div class="col-md-4">
                <div class="why-icon"><i class="bi bi-currency-pound"></i></div>
                <h5 class="mb-2">Affordability</h5>
                <p class="text-muted" style="font-size:.9rem;">AI-grade solutions without enterprise-grade price tags. We're built for organisations that need ROI, not just innovation theatre.</p>
            </div>
            <div class="col-md-4">
                <div class="why-icon"><i class="bi bi-people-fill"></i></div>
                <h5 class="mb-2">People-First</h5>
                <p class="text-muted" style="font-size:.9rem;">Technology that genuinely helps employees do their jobs better. We measure success by adoption and outcomes, not go-live dates.</p>
            </div>
        </div>
    </div>
</section>

<!-- INDUSTRIES -->
<section class="section-white">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">Sectors</span>
            <h2 class="section-title text-center">Industries We Serve</h2>
        </div>
        <div class="row g-3 justify-content-center text-center">
            <?php
            $industries = [
                ['bi-heart-pulse','Healthcare'],
                ['bi-bank','Finance'],
                ['bi-gear-wide-connected','Manufacturing'],
                ['bi-bag','Retail'],
                ['bi-mortarboard','Education'],
                ['bi-truck','Logistics'],
            ];
            foreach ($industries as [$icon, $label]):
            ?>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-3 border bg-white text-center h-100 d-flex flex-column align-items-center justify-content-center"
                     style="transition:.2s ease;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)'"
                     onmouseout="this.style.boxShadow=''">
                    <i class="bi <?= $icon ?> text-primary fs-3 mb-2"></i>
                    <span style="font-size:.82rem;font-weight:600;color:#1E293B;"><?= $label ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section text-center">
    <div class="container">
        <h2 class="mb-3">Not Sure Which Service You Need?</h2>
        <p class="mb-4">Tell us about your challenge and we'll recommend the right approach — completely free of charge.</p>
        <a href="contact.php" class="btn btn-light btn-lg rounded-pill px-5">Get Free Consultation</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
