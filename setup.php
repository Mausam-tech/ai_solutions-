<?php
// ============================================================
// SETUP.PHP — Run once to create the admin account.
// DELETE THIS FILE immediately after successful setup.
// Access at: http://localhost/ai_solutions/setup.php
// ============================================================

// Basic security: prevent running if admin already exists
require_once 'includes/db.php';

$adminExists = false;
try {
    $count = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    $adminExists = $count > 0;
} catch (PDOException $e) {
    // Table may not exist yet — DB not imported
}

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username        = trim($_POST['username']         ?? '');
    $password        = $_POST['password']              ?? '';
    $confirmPassword = $_POST['confirm_password']      ?? '';

    if (mb_strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (mb_strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one number.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            // Use REPLACE to allow re-running setup if needed
            $stmt = $pdo->prepare(
                'INSERT INTO admin_users (username, password)
                 VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE password = ?'
            );
            $stmt->execute([$username, $hash, $hash]);
            $success = 'Admin account created successfully! You can now log in at <a href="admin/login.php">admin/login.php</a>.<br><strong style="color:#dc2626">Please delete this file (setup.php) immediately.</strong>';
            $adminExists = true;
        } catch (PDOException $e) {
            $error = 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup — AI-Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg,#0F172A,#1e3a5f); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Inter',sans-serif; }
        .setup-card { background:#fff; border-radius:16px; padding:2.5rem; width:100%; max-width:440px; box-shadow:0 20px 60px rgba(0,0,0,.4); }
        h2 { font-size:1.3rem; margin-bottom:.3rem; color:#1E293B; }
        .subtitle { font-size:.85rem; color:#64748B; margin-bottom:1.75rem; }
        .form-label { font-size:.82rem; font-weight:600; color:#1E293B; }
        .form-control { border:1.5px solid #E2E8F0; border-radius:8px; font-size:.9rem; }
        .form-control:focus { border-color:#2563EB; box-shadow:0 0 0 3px rgba(37,99,235,.12); }
        .btn-setup { background:linear-gradient(135deg,#2563EB,#7C3AED); border:none; border-radius:50px; font-weight:600; width:100%; padding:.7rem; color:#fff; }
        .alert-success-box { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.3); border-radius:8px; padding:1rem; color:#15803d; font-size:.88rem; }
        .alert-error-box   { background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2); border-radius:8px; padding:1rem; color:#b91c1c; font-size:.88rem; }
        .warning-box { background:rgba(245,158,11,.08); border:1px solid rgba(245,158,11,.2); border-radius:8px; padding:.85rem; color:#92400e; font-size:.82rem; margin-bottom:1.5rem; }
    </style>
</head>
<body>
<div class="setup-card">
    <h2>🔧 AI-Solutions Setup</h2>
    <p class="subtitle">Create the administrator account for your admin panel.</p>

    <?php if ($success): ?>
        <div class="alert-success-box"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert-error-box mb-3"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <div class="warning-box">
        ⚠️ <strong>Security notice:</strong> Delete this file immediately after creating your admin account. Leaving it accessible is a security risk.
    </div>

    <?php if ($adminExists && empty($_POST)): ?>
    <div class="alert-success-box mb-3">
        An admin account already exists. You can use this form to reset the password if needed.
    </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label class="form-label">Admin Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                   placeholder="e.g. admin" autocomplete="off" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control"
                   placeholder="Min 8 chars, 1 uppercase, 1 number" required>
            <small class="text-muted" style="font-size:.75rem;">
                Requirements: 8+ characters, 1 uppercase letter, 1 number.
            </small>
        </div>
        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-setup">Create Admin Account</button>
    </form>
    <?php else: ?>
    <div class="mt-3 text-center">
        <a href="admin/login.php" class="btn btn-primary rounded-pill px-4">Go to Admin Login →</a>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
