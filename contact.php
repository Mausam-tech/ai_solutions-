<?php
$pageTitle = 'Contact Us — AI-Solutions';
require_once 'includes/db.php';

// Retrieve and clear flash message
$flash = getFlash();

// Countries list
$countries = ['Afghanistan','Albania','Algeria','Andorra','Angola','Argentina','Armenia','Australia',
'Austria','Azerbaijan','Bahrain','Bangladesh','Belarus','Belgium','Bolivia','Bosnia and Herzegovina',
'Brazil','Brunei','Bulgaria','Cambodia','Cameroon','Canada','Chile','China','Colombia','Croatia',
'Cuba','Cyprus','Czech Republic','Denmark','Egypt','Estonia','Ethiopia','Finland','France','Georgia',
'Germany','Ghana','Greece','Hungary','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy',
'Japan','Jordan','Kazakhstan','Kenya','Kuwait','Latvia','Lebanon','Libya','Lithuania','Luxembourg',
'Malaysia','Malta','Mexico','Moldova','Morocco','Nepal','Netherlands','New Zealand','Nigeria','Norway',
'Oman','Pakistan','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Saudi Arabia',
'Serbia','Singapore','Slovakia','Slovenia','South Africa','South Korea','Spain','Sri Lanka','Sudan',
'Sweden','Switzerland','Syria','Taiwan','Thailand','Tunisia','Turkey','Ukraine','United Arab Emirates',
'United Kingdom','United States','Uruguay','Uzbekistan','Venezuela','Vietnam','Yemen','Zimbabwe'];

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container page-hero-content">
        <nav aria-label="breadcrumb" class="page-hero-breadcrumb mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Contact Us</li>
            </ol>
        </nav>
        <h1 class="page-hero-title">Get in Touch</h1>
        <p class="page-hero-subtitle">Tell us about your challenge — we'll respond within one business day</p>
    </div>
</section>

<!-- CONTACT SECTION -->
<section class="section-white">
    <div class="container">

        <!-- Flash message -->
        <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
            <?php if ($flash['type'] === 'success'): ?>
                <i class="bi bi-check-circle-fill me-2"></i>
            <?php else: ?>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php endif; ?>
            <?= h($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row g-5">

            <!-- LEFT: Contact Info -->
            <div class="col-lg-4">
                <h4 class="mb-4" style="font-family:var(--font-heading,'inherit');">Contact Information</h4>

                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="contact-info-text">
                        <h6>Our Office</h6>
                        <p>15 Derwent Street<br>Sunderland, SR1 2BB<br>United Kingdom</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-envelope-fill"></i></div>
                    <div class="contact-info-text">
                        <h6>Email Us</h6>
                        <p><a href="mailto:info@ai-solutions.co.uk">info@ai-solutions.co.uk</a></p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div class="contact-info-text">
                        <h6>Call Us</h6>
                        <p><a href="tel:+441915550100">+44 (0)191 555 0100</a></p>
                        <small class="text-muted">Mon–Fri, 9am–5pm GMT</small>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon"><i class="bi bi-clock-fill"></i></div>
                    <div class="contact-info-text">
                        <h6>Response Time</h6>
                        <p>We respond to all enquiries within one business day.</p>
                    </div>
                </div>

                <!-- Social links -->
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-facebook"></i>
                    </a>
                </div>
            </div>

            <!-- RIGHT: Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form-card">
                    <h5 class="mb-1" style="font-family:var(--font-heading,'inherit');">Send Us Your Enquiry</h5>
                    <p class="text-muted mb-4" style="font-size:.88rem;">
                        Fill in the form below and a member of our team will be in touch shortly.
                        All fields marked <span class="text-danger">*</span> are required.
                    </p>

                    <form method="POST" action="submit-contact.php" id="contactForm" novalidate>

                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="e.g. Jane Smith" maxlength="100">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="e.g. jane@company.com" maxlength="150">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                       placeholder="e.g. +44 7700 900000" maxlength="30">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Company -->
                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                       placeholder="e.g. Acme Corp" maxlength="150">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Country -->
                            <div class="col-md-6">
                                <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                <select class="form-select" id="country" name="country">
                                    <option value="" disabled selected>Select your country</option>
                                    <?php foreach ($countries as $c): ?>
                                    <option value="<?= h($c) ?>"><?= h($c) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Job Title -->
                            <div class="col-md-6">
                                <label for="job_title" class="form-label">Your Job Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="job_title" name="job_title"
                                       placeholder="e.g. Head of IT" maxlength="100">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Job Details -->
                            <div class="col-12">
                                <label for="job_details" class="form-label">
                                    Tell Us About Your Requirement <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="job_details" name="job_details"
                                          rows="5" maxlength="3000"
                                          placeholder="Describe the challenge or project you'd like our help with. The more detail you provide, the better we can prepare for our initial conversation."></textarea>
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Max 3,000 characters</small>
                            </div>

                            <!-- Submit -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                    <i class="bi bi-send me-2"></i>Send Enquiry
                                </button>
                                <p class="text-muted mt-3 mb-0" style="font-size:.78rem;">
                                    <i class="bi bi-shield-lock me-1"></i>
                                    Your information is kept strictly confidential and will only be used to respond to your enquiry.
                                </p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Map embed -->
        <div class="mt-5 rounded-4 overflow-hidden" style="height:300px;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2300.0!2d-1.3831!3d54.9069!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTTCsDU0JzI0LjgiTiAxwrAyMyczOC4yIlc!5e0!3m2!1sen!2suk!4v1700000000000!5m2!1sen!2suk"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="AI-Solutions office location in Sunderland">
            </iframe>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
