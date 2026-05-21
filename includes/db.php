<?php
// ============================================================
// DATABASE CONNECTION & GLOBAL CONSTANTS
// ============================================================

define('DB_HOST',    'localhost');
define('DB_NAME',    'ai_solutions');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// Auto-detect base URL from script path (works at any folder depth)
$_protocol  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$_host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$_parts     = array_values(array_filter(explode('/', $_scriptDir)));
$_folder    = isset($_parts[0]) ? '/' . $_parts[0] : '';

define('BASE_URL',   $_protocol . '://' . $_host . $_folder);
define('UPLOAD_URL', BASE_URL . '/uploads/');
define('UPLOAD_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);

unset($_protocol, $_host, $_scriptDir, $_parts, $_folder);

// PDO connection
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('<h2 style="font-family:sans-serif;color:#c0392b">Database Connection Failed</h2><p style="font-family:sans-serif">'
        . htmlspecialchars($e->getMessage()) . '</p>'
        . '<p style="font-family:sans-serif">Please check your database credentials in <code>includes/db.php</code> and ensure the <code>ai_solutions</code> database exists.</p>');
}

// ============================================================
// GLOBAL HELPER FUNCTIONS
// ============================================================

/**
 * Safely output a string — escapes HTML special chars.
 */
function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Format a date string for display.
 */
function formatDate(string $dateStr, string $format = 'd M Y'): string {
    $ts = strtotime($dateStr);
    return $ts ? date($format, $ts) : '';
}

/**
 * Render star icons for a given rating (1–5).
 */
function renderStars(int $rating): string {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $cls   = $i <= $rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary';
        $html .= '<i class="bi ' . $cls . '"></i>';
    }
    return $html;
}

/**
 * Return a full UPLOAD_URL path for a stored image, or a fallback placeholder URL.
 * $fallback is returned as-is (should be a BASE_URL-based path).
 */
function imgUrl(string $path, string $fallback = ''): string {
    if (!empty($path)) {
        return UPLOAD_URL . $path;
    }
    return $fallback ?: BASE_URL . '/assets/images/placeholder.png';
}

/**
 * Set a flash message in session.
 * $type: 'success' | 'danger' | 'warning' | 'info'
 */
function setFlash(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Retrieve and clear a flash message from session.
 * Returns null if none set.
 */
function getFlash(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Redirect to a URL and exit.
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Validate a positive integer (for URL ?id= params).
 */
function validId(mixed $val): int {
    $id = (int) $val;
    return $id > 0 ? $id : 0;
}
