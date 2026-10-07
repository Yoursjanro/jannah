<?php
require_once 'config.php';
if (is_logged_in()) { header('Location: activities.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $account = $stmt->fetch();
        if ($account && password_verify($password, $account['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$account['id'];
            $_SESSION['username'] = $account['username'];
            $_SESSION['role'] = $account['role'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            flash('Welcome, ' . $account['username'] . '!');
            header('Location: activities.php'); exit;
        }
        $error = 'Invalid username or password.';
    }
}
$flash = get_flash();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Log In</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'partials/nav.php'; ?><main class="auth-page"><section class="panel auth-card"><span class="eyebrow">Account Access</span><h1>Log In</h1><p>Sign in to access the activity system.</p>
<?php if($flash): ?><div class="notice"><?= e($flash['message']) ?></div><?php endif; ?>
<?php if($error): ?><div class="error-box"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Username<input required name="username" autocomplete="username" value="<?= e($_POST['username']??'') ?>"></label><label>Password<input required type="password" name="password" autocomplete="current-password"></label><button class="btn primary">Log In</button></form>
<p class="auth-link">No account? <a href="register.php">Create a user account</a></p>
</section></main><?php include 'partials/footer.php'; ?></body></html>
