<?php
// ============================================================
// SUBMIT-CONTACT.PHP — Form processor
// Only handles POST requests. Redirects back to contact.php.
// ============================================================
require_once 'includes/db.php';

// Only process POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/contact.php');
}

// ─── Collect and sanitise input ─────────────────────────────
$name        = trim($_POST['name']        ?? '');
$email       = trim($_POST['email']       ?? '');
$phone       = trim($_POST['phone']       ?? '');
$companyName = trim($_POST['company_name']?? '');
$country     = trim($_POST['country']     ?? '');
$jobTitle    = trim($_POST['job_title']   ?? '');
$jobDetails  = trim($_POST['job_details'] ?? '');

// ─── Server-side validation ──────────────────────────────────
$errors = [];

if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    $errors[] = 'Full name must be between 2 and 100 characters.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    $errors[] = 'Please provide a valid email address.';
}

if (!preg_match('/^[\d\s\+\-\(\)]{7,20}$/', $phone)) {
    $errors[] = 'Please provide a valid phone number.';
}

if (empty($companyName) || mb_strlen($companyName) > 150) {
    $errors[] = 'Company name is required (max 150 characters).';
}

if (empty($country) || mb_strlen($country) > 100) {
    $errors[] = 'Please select a country.';
}

if (mb_strlen($jobTitle) < 2 || mb_strlen($jobTitle) > 100) {
    $errors[] = 'Job title must be between 2 and 100 characters.';
}

if (mb_strlen($jobDetails) < 10) {
    $errors[] = 'Job details must be at least 10 characters.';
}

if (!empty($errors)) {
    setFlash('danger', implode(' ', $errors));
    redirect(BASE_URL . '/contact.php');
}

// ─── Insert into database ────────────────────────────────────
try {
    $stmt = $pdo->prepare('
        INSERT INTO contact_inquiries
            (name, email, phone, company_name, country, job_title, job_details)
        VALUES
            (?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([$name, $email, $phone, $companyName, $country, $jobTitle, $jobDetails]);

    setFlash('success', 'Thank you, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Your enquiry has been received. We\'ll be in touch within one business day.');
    redirect(BASE_URL . '/contact.php');

} catch (PDOException $e) {
    setFlash('danger', 'Sorry, there was a technical problem submitting your enquiry. Please try again or email us directly at info@ai-solutions.co.uk.');
    redirect(BASE_URL . '/contact.php');
}
