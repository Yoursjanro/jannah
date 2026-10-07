<?php
require_once 'config.php';
if (is_logged_in()) { header('Location: activities.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!preg_match('/^[A-Za-z0-9_.-]{3,80}$/', $username)) $error = 'Username must be 3-80 characters and may contain letters, numbers, dot, underscore, or hyphen.';
    elseif (strlen($password) < 6) $error = 'Password must be at least 6 characters.';
    elseif ($password !== $confirm) $error = 'Passwords do not match.';
    else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        if ($stmt->fetch()) $error = 'Username is already taken.';
        else {
            try {
                $stmt = $pdo->prepare('INSERT INTO users(username,password,role) VALUES(?,?,?)');
                $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), 'user']);
                flash('Registration successful. You can now log in.');
                header('Location: login.php'); exit;
            } catch (PDOException $e) {
                $error = 'Registration could not be completed. Please try another username.';
            }
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Register</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'partials/nav.php'; ?><main class="auth-page"><section class="panel auth-card"><span class="eyebrow">User Registration</span><h1>Create Account</h1><p>New accounts are created as User accounts.</p>
<?php if($error): ?><div class="error-box"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Username<input required name="username" minlength="3" maxlength="80" autocomplete="username" value="<?= e($_POST['username']??'') ?>"></label><label>Password<input required type="password" name="password" minlength="6" autocomplete="new-password"></label><label>Confirm Password<input required type="password" name="confirm_password" minlength="6" autocomplete="new-password"></label><button class="btn primary">Register</button></form>
<p class="auth-link">Already registered? <a href="login.php">Log in</a></p></section></main><?php include 'partials/footer.php'; ?></body></html>
