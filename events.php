<?php
$pageTitle = 'Events — AI-Solutions';
require_once 'includes/db.php';

try {
    $upcoming = $pdo->query('SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC')->fetchAll();
    $past     = $pdo->query('SELECT * FROM events WHERE event_date < CURDATE()  ORDER BY event_date DESC')->fetchAll();
} catch (PDOException $e) {
    $upcoming = [];
    $past     = [];
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
                <li class="breadcrumb-item active">Events</li>
            </ol>
        </nav>
        <h1 class="page-hero-title">Events</h1>
        <p class="page-hero-subtitle">Conferences, webinars, workshops, and exhibitions — find us near you</p>
    </div>
</section>

<!-- EVENTS CONTENT -->
<section class="section-white">
    <div class="container">

        <!-- Bootstrap Tabs -->
        <ul class="nav nav-pills mb-5" id="eventsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="pill"
                        data-bs-target="#upcoming-pane" type="button" role="tab">
                    Upcoming Events
                    <?php if (!empty($upcoming)): ?>
                    <span class="badge bg-primary ms-1"><?= count($upcoming) ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="past-tab" data-bs-toggle="pill"
                        data-bs-target="#past-pane" type="button" role="tab">
                    Past Events
                </button>
            </li>
        </ul>

        <div class="tab-content" id="eventsTabsContent">

            <!-- UPCOMING -->
            <div class="tab-pane fade show active" id="upcoming-pane" role="tabpanel">
                <?php if (!empty($upcoming)): ?>
                <div class="d-flex flex-column gap-4">
                    <?php foreach ($upcoming as $i => $ev): ?>
                    <div class="event-card <?= $i === 0 ? 'border-primary' : '' ?>" style="<?= $i === 0 ? 'border-width:2px!important;' : '' ?>">
                        <!-- Date badge -->
                        <div class="event-date-badge flex-shrink-0">
                            <div class="event-date-day"><?= date('d', strtotime($ev['event_date'])) ?></div>
                            <div class="event-date-month"><?= date('M', strtotime($ev['event_date'])) ?></div>
                            <div class="event-date-year"><?= date('Y', strtotime($ev['event_date'])) ?></div>
                        </div>
                        <!-- Details -->
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <span class="event-type-badge"><?= h($ev['event_type']) ?></span>
                                <?php if ($i === 0): ?>
                                <span class="badge" style="background:rgba(37,99,235,.15);color:#1d4ed8;font-size:.65rem;">Featured</span>
                                <?php endif; ?>
                            </div>
                            <div class="event-title"><?= h($ev['title']) ?></div>
                            <div class="event-location mb-2">
                                <i class="bi bi-geo-alt me-1"></i><?= h($ev['location']) ?>
                            </div>
                            <p class="event-desc"><?= h($ev['description']) ?></p>
                            <?php if (!empty($ev['register_link']) && $ev['register_link'] !== '#'): ?>
                            <a href="<?= h($ev['register_link']) ?>" class="btn btn-sm btn-primary rounded-pill px-3">
                                Register Now <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <?php elseif (!empty($ev['register_link'])): ?>
                            <a href="contact.php" class="btn btn-sm btn-primary rounded-pill px-3">
                                Register / Enquire <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <?php else: ?>
                            <a href="contact.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Enquire About This Event
                            </a>
                            <?php endif; ?>
                        </div>
                        <!-- Image -->
                        <?php if (!empty($ev['image_path'])): ?>
                        <div class="d-none d-md-block flex-shrink-0">
                            <img src="<?= h(imgUrl($ev['image_path'])) ?>" alt="<?= h($ev['title']) ?>"
                                 style="width:140px;height:100%;object-fit:cover;border-radius:8px;">
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                    <p>No upcoming events at the moment. <a href="contact.php">Contact us</a> to be notified when new events are announced.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- PAST -->
            <div class="tab-pane fade" id="past-pane" role="tabpanel">
                <?php if (!empty($past)): ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($past as $ev): ?>
                    <div class="event-card past-event-card">
                        <div class="event-date-badge flex-shrink-0"
                             style="background:linear-gradient(135deg,#64748B,#475569);">
                            <div class="event-date-day"><?= date('d', strtotime($ev['event_date'])) ?></div>
                            <div class="event-date-month"><?= date('M', strtotime($ev['event_date'])) ?></div>
                            <div class="event-date-year"><?= date('Y', strtotime($ev['event_date'])) ?></div>
                        </div>
                        <div class="flex-grow-1">
                            <span class="event-type-badge" style="background:rgba(100,116,139,.1);color:#64748B;">
                                <?= h($ev['event_type']) ?>
                            </span>
                            <div class="event-title"><?= h($ev['title']) ?></div>
                            <div class="event-location">
                                <i class="bi bi-geo-alt me-1"></i><?= h($ev['location']) ?>
                            </div>
                            <p class="event-desc mt-2"><?= h(mb_substr($ev['description'], 0, 180)) ?>…</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar2 fs-1 d-block mb-3"></i>
                    <p>No past events recorded yet.</p>
                </div>
                <?php endif; ?>
            </div>

        </div><!-- end tab-content -->
    </div>
</section>

<!-- CTA -->
<section class="cta-section text-center">
    <div class="container">
        <h2 class="mb-3">Want to Meet Us in Person?</h2>
        <p class="mb-4">Get in touch to arrange a meeting or find out where we'll be next.</p>
        <a href="contact.php" class="btn btn-light btn-lg rounded-pill px-5">Contact Us</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
