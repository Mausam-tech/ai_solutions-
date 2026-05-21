<?php
// ============================================================
// ADMIN LOGIN
// ============================================================
if (session_status() === PHP_SESSION_NONE) session_start();

// If already logged in, go to dashboard
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter your username and password.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id, username, password FROM admin_users WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent fixation
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username']  = $user['username'];
                $_SESSION['admin_id']        = $user['id'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — AI-Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        body { background: linear-gradient(135deg, #0F172A 0%, #1e3a5f 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,.4); }
        .login-logo { display: flex; align-items: center; justify-content: center; gap: .6rem; margin-bottom: 1.75rem; }
        .login-logo .brand-icon-wrap { width: 40px; height: 40px; background: linear-gradient(135deg,#2563EB,#7C3AED); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; }
        .login-logo .brand-name { font-family: 'Space Grotesk', sans-serif; font-size: 1.4rem; color: #1E293B; }
        .login-logo .brand-accent { color: #2563EB; }
        .login-title { text-align: center; font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; color: #1E293B; margin-bottom: .3rem; }
        .login-sub   { text-align: center; font-size: .82rem; color: #64748B; margin-bottom: 1.75rem; }
        .form-label  { font-size: .82rem; font-weight: 600; color: #1E293B; }
        .form-control { border: 1.5px solid #E2E8F0; border-radius: 8px; font-size: .9rem; padding: .6rem .9rem; }
        .form-control:focus { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
        .btn-login { background: linear-gradient(135deg, #2563EB, #7C3AED); border: none; border-radius: 50px; font-weight: 600; width: 100%; padding: .7rem; font-size: .95rem; transition: opacity .2s; }
        .btn-login:hover { opacity: .9; }
        .back-link { text-align: center; margin-top: 1.25rem; font-size: .8rem; color: #64748B; }
        .back-link a { color: #2563EB; }
        .alert-danger { background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2); border-radius: 8px; color: #b91c1c; font-size: .85rem; padding: .7rem 1rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <div class="brand-icon-wrap"><i class="bi bi-cpu-fill"></i></div>
            <span class="brand-name">AI<span class="brand-accent">-Solutions</span></span>
        </div>
        <div class="login-title">Admin Panel</div>
        <div class="login-sub">Sign in to manage your website content</div>

        <?php if ($error): ?>
        <div class="alert-danger mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= h($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Enter your username"
                       value="<?= h($_POST['username'] ?? '') ?>"
                       autocomplete="username" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Enter your password"
                       autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="back-link">
            <a href="<?= BASE_URL ?>/index.php"><i class="bi bi-arrow-left me-1"></i>Back to Website</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
