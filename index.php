<?php
$pageTitle = 'AI-Solutions — The Future of the Digital Employee Experience';
require_once 'includes/db.php';

// ─── Fetch homepage content ────────────────────────────────
try {
    $homeServices   = $pdo->query('SELECT id,title,icon_class,short_description FROM services ORDER BY display_order ASC LIMIT 6')->fetchAll();
    $homePortfolio  = $pdo->query('SELECT id,title,industry,cover_image_path,short_description FROM portfolio_items ORDER BY created_at DESC LIMIT 3')->fetchAll();
    $homeTestimonials = $pdo->query('SELECT id,client_name,job_title,company_name,rating,testimonial_text FROM testimonials ORDER BY RAND() LIMIT 3')->fetchAll();
    $homeArticles   = $pdo->query('SELECT id,title,category,excerpt,thumbnail_path,created_at FROM articles ORDER BY created_at DESC LIMIT 3')->fetchAll();
    $homeEvents     = $pdo->query('SELECT id,title,event_type,event_date,location FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3')->fetchAll();
} catch (PDOException $e) {
    $homeServices = $homePortfolio = $homeTestimonials = $homeArticles = $homeEvents = [];
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="hero-section" id="hero">
    <div class="hero-bg-pattern"></div>
    <div class="container position-relative z-1 py-5">
        <div class="row align-items-center min-vh-100 py-5">

            <!-- Left: Text -->
            <div class="col-lg-7 hero-content">
                <div class="hero-badge mb-4">
                    <i class="bi bi-lightning-charge-fill"></i>
                    AI-Powered Digital Workplace Solutions
                </div>
                <h1 class="hero-title mb-4">
                    Transform Your<br>
                    <span class="text-highlight">Digital Workplace</span><br>
                    with AI
                </h1>
                <p class="hero-subtitle mb-5">
                    AI-Solutions leverages cutting-edge artificial intelligence to rapidly address
                    digital employee experience challenges — speeding up design, engineering, and
                    innovation for organisations worldwide.
                </p>
                <div class="hero-buttons d-flex flex-wrap gap-3">
                    <a href="services.php" class="btn btn-primary btn-lg rounded-pill px-4">
                        Explore Services <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        Get in Touch
                    </a>
                </div>
            </div>

            <!-- Right: AI Dashboard Mockup -->
            <div class="col-lg-5 d-none d-lg-flex justify-content-center align-items-center">
                <div class="hero-visual">
                    <div class="hero-card">
                        <div class="hero-card-header">
                            <span class="hc-dot red"></span>
                            <span class="hc-dot yellow"></span>
                            <span class="hc-dot green"></span>
                            <span class="hc-title">AI Analytics Dashboard</span>
                        </div>
                        <div class="hero-card-body">
                            <div class="hc-metric">
                                <span class="hc-label">Process Efficiency</span>
                                <span class="hc-value text-success">+78%</span>
                            </div>
                            <div class="hc-bar-wrapper">
                                <div class="hc-bar" style="width:78%"></div>
                            </div>
                            <div class="hc-metric">
                                <span class="hc-label">Employee Satisfaction</span>
                                <span class="hc-value text-info">94%</span>
                            </div>
                            <div class="hc-bar-wrapper">
                                <div class="hc-bar info" style="width:94%"></div>
                            </div>
                            <div class="hc-metric">
                                <span class="hc-label">Automation Rate</span>
                                <span class="hc-value text-warning">+61%</span>
                            </div>
                            <div class="hc-bar-wrapper">
                                <div class="hc-bar warning" style="width:61%"></div>
                            </div>
                            <div class="hc-ai-badge mt-3">
                                <i class="bi bi-cpu-fill me-1"></i>
                                AI Processing Active <span class="hc-pulse"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <a href="#stats" class="scroll-btn"><i class="bi bi-chevron-double-down"></i></a>
    </div>
</section>

<!-- ============================================================
     STATS
     ============================================================ -->
<section class="stats-section" id="stats">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><span data-count="30" data-suffix="+">0+</span></div>
                    <div class="stat-label">Solutions Delivered</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><span data-count="12" data-suffix="+">0+</span></div>
                    <div class="stat-label">Industries Served</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><span data-count="80" data-suffix="+">0+</span></div>
                    <div class="stat-label">Happy Clients</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><span data-count="5">0</span></div>
                    <div class="stat-label">Countries Reached</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SERVICES OVERVIEW
     ============================================================ -->
<section class="section-white" id="services">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">What We Offer</span>
            <h2 class="section-title text-center">Our Core Services</h2>
            <p class="section-subtitle mx-auto mt-2">
                From intelligent virtual assistants to end-to-end process automation — we deliver
                practical AI solutions that make a measurable difference.
            </p>
        </div>
        <div class="row g-4">
            <?php if (!empty($homeServices)): ?>
                <?php foreach ($homeServices as $svc): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card h-100">
                        <div class="service-icon">
                            <i class="bi <?= h($svc['icon_class']) ?>"></i>
                        </div>
                        <h5><?= h($svc['title']) ?></h5>
                        <p><?= h($svc['short_description']) ?></p>
                        <a href="services.php" class="service-link">
                            Learn more <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">
                    <i class="bi bi-gear fs-2 d-block mb-2"></i>
                    Services are being configured. Check back soon.
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5">
            <a href="services.php" class="btn btn-outline-primary rounded-pill px-4">
                View All Services <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     FEATURED PORTFOLIO
     ============================================================ -->
<section class="section-light" id="portfolio">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">Our Work</span>
            <h2 class="section-title text-center">Featured Projects</h2>
            <p class="section-subtitle mx-auto mt-2">
                Real solutions delivered to real organisations — across healthcare, finance, manufacturing, and more.
            </p>
        </div>
        <div class="row g-4">
            <?php if (!empty($homePortfolio)): ?>
                <?php foreach ($homePortfolio as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio-card">
                        <?php if (!empty($item['cover_image_path'])): ?>
                            <img src="<?= h(imgUrl($item['cover_image_path'])) ?>" alt="<?= h($item['title']) ?>">
                        <?php else: ?>
                            <div class="img-placeholder w-100 h-100">
                                <i class="bi bi-briefcase"></i>
                            </div>
                        <?php endif; ?>
                        <div class="portfolio-overlay">
                            <span class="portfolio-badge"><?= h($item['industry']) ?></span>
                            <h5><?= h($item['title']) ?></h5>
                            <p><?= h(mb_substr($item['short_description'], 0, 80)) ?>…</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">
                    <i class="bi bi-briefcase fs-2 d-block mb-2"></i>
                    Portfolio items coming soon.
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5">
            <a href="portfolio.php" class="btn btn-outline-primary rounded-pill px-4">
                View Full Portfolio <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     TESTIMONIALS PREVIEW
     ============================================================ -->
<section class="section-white">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">Client Feedback</span>
            <h2 class="section-title text-center">What Our Clients Say</h2>
        </div>
        <div class="row g-4">
            <?php if (!empty($homeTestimonials)): ?>
                <?php foreach ($homeTestimonials as $t): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-quote">"</div>
                        <p class="testimonial-text"><?= h(mb_substr($t['testimonial_text'], 0, 200)) ?>…</p>
                        <div class="testimonial-stars">
                            <?= renderStars((int)$t['rating']) ?>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-auto">
                            <div class="testimonial-avatar">
                                <?= strtoupper(mb_substr($t['client_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="testimonial-name"><?= h($t['client_name']) ?></div>
                                <div class="testimonial-role"><?= h($t['job_title']) ?>, <?= h($t['company_name']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">No testimonials yet.</div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5">
            <a href="testimonials.php" class="btn btn-outline-primary rounded-pill px-4">
                Read All Testimonials <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     LATEST ARTICLES
     ============================================================ -->
<section class="section-light">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">Insights</span>
            <h2 class="section-title text-center">Latest Articles</h2>
        </div>
        <div class="row g-4">
            <?php if (!empty($homeArticles)): ?>
                <?php foreach ($homeArticles as $art): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="article-card h-100">
                        <?php if (!empty($art['thumbnail_path'])): ?>
                            <img class="article-img" src="<?= h(imgUrl($art['thumbnail_path'])) ?>" alt="<?= h($art['title']) ?>">
                        <?php else: ?>
                            <div class="article-img-placeholder img-placeholder"><i class="bi bi-newspaper"></i></div>
                        <?php endif; ?>
                        <div class="article-body">
                            <span class="article-category"><?= h($art['category']) ?></span>
                            <span class="article-date ms-2"><?= formatDate($art['created_at']) ?></span>
                            <div class="article-title"><?= h($art['title']) ?></div>
                            <p class="article-excerpt"><?= h(mb_substr($art['excerpt'], 0, 130)) ?>…</p>
                            <a href="articles.php" class="btn btn-sm btn-outline-primary rounded-pill mt-2">Read More</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">Articles coming soon.</div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5">
            <a href="articles.php" class="btn btn-outline-primary rounded-pill px-4">
                All Articles <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     UPCOMING EVENTS
     ============================================================ -->
<?php if (!empty($homeEvents)): ?>
<section class="section-white">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">What's On</span>
            <h2 class="section-title text-center">Upcoming Events</h2>
        </div>
        <div class="row g-3 justify-content-center">
            <?php foreach ($homeEvents as $ev): ?>
            <div class="col-lg-4 col-md-6">
                <div class="event-card">
                    <div class="event-date-badge">
                        <div class="event-date-day"><?= date('d', strtotime($ev['event_date'])) ?></div>
                        <div class="event-date-month"><?= date('M', strtotime($ev['event_date'])) ?></div>
                        <div class="event-date-year"><?= date('Y', strtotime($ev['event_date'])) ?></div>
                    </div>
                    <div>
                        <span class="event-type-badge"><?= h($ev['event_type']) ?></span>
                        <div class="event-title"><?= h($ev['title']) ?></div>
                        <div class="event-location"><i class="bi bi-geo-alt me-1"></i><?= h($ev['location']) ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="events.php" class="btn btn-outline-primary rounded-pill px-4">
                All Events <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     CTA BANNER
     ============================================================ -->
<section class="cta-section text-center">
    <div class="container">
        <h2 class="mb-3">Ready to Transform Your Digital Workplace?</h2>
        <p class="mb-4">
            Tell us about your challenge and we'll show you how AI-Solutions can help —
            no obligation, no jargon, just practical advice.
        </p>
        <a href="contact.php" class="btn btn-light btn-lg rounded-pill px-5 fw-600">
            Get in Touch Today <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
