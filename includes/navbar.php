<?php
// Determine which nav link is active
$currentPage = basename($_SERVER['PHP_SELF']);
function isActive(string $page): string {
    global $currentPage;
    return $currentPage === $page ? 'active' : '';
}
?>
<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/index.php">
            <div class="brand-icon-wrap">
                <i class="bi bi-cpu-fill"></i>
            </div>
            <span class="brand-name">AI<span class="brand-accent">-Solutions</span></span>
        </a>

        <!-- Mobile toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain" aria-controls="navbarMain"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= isActive('index.php') ?>" href="<?= BASE_URL ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActive('services.php') ?>" href="<?= BASE_URL ?>/services.php">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActive('portfolio.php') ?>" href="<?= BASE_URL ?>/portfolio.php">Portfolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActive('testimonials.php') ?>" href="<?= BASE_URL ?>/testimonials.php">Testimonials</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActive('articles.php') ?>" href="<?= BASE_URL ?>/articles.php">Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActive('gallery.php') ?>" href="<?= BASE_URL ?>/gallery.php">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActive('events.php') ?>" href="<?= BASE_URL ?>/events.php">Events</a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary btn-sm px-3 rounded-pill <?= isActive('contact.php') ?>"
                       href="<?= BASE_URL ?>/contact.php">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
