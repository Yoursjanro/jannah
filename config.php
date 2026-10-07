<?php
// Central application configuration.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

$host = 'localhost';
$db   = 'data_structures_db';
$user = 'root';
$pass = '12345';
$charset = 'utf8mb4';

function app_db_error(string $message): void {
    http_response_code(500);
    die('<!doctype html><html><head><meta charset="utf-8"><title>Database Setup</title><link rel="stylesheet" href="assets/style.css"></head><body><main class="auth-page"><section class="panel auth-card"><h1>Database connection error</h1><p>' . htmlspecialchars($message) . '</p><p>Start MySQL in XAMPP and make sure the database settings in <code>config.php</code> match your MySQL account.</p></section></main></body></html>');
}

try {
    // First try the configured database.
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $firstError) {
    // For a normal XAMPP root account, create the local database automatically if it does not exist.
    try {
        $server = new PDO("mysql:host=$host;charset=$charset", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $server->exec("CREATE DATABASE IF NOT EXISTS `" . str_replace('`', '``', $db) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        app_db_error('Unable to connect to the configured database. If you are using InfinityFree, replace $db, $user and $pass in config.php with the MySQL database credentials provided by InfinityFree.');
    }
}

function ensure_schema(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(80) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS activities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(150) NOT NULL,
        type ENUM('Singly','Doubly','Circular') NOT NULL,
        description TEXT NOT NULL,
        code TEXT NOT NULL,
        input_hint VARCHAR(255) DEFAULT '',
        reference_pdf VARCHAR(120) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Add the reference_pdf column if an older database was used.
    $cols = $pdo->query("SHOW COLUMNS FROM activities LIKE 'reference_pdf'")->fetch();
    if (!$cols) {
        $pdo->exec("ALTER TABLE activities ADD reference_pdf VARCHAR(120) DEFAULT '' AFTER input_hint");
    }

    // Create default accounts only when they do not already exist.
    $count = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE username='admin'")->fetchColumn();
    if ($count === 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username,password,role) VALUES (?,?, 'admin')");
        $stmt->execute(['admin', password_hash('Admin@123', PASSWORD_DEFAULT)]);
    }
    $count = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE username='user'")->fetchColumn();
    if ($count === 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username,password,role) VALUES (?,?, 'user')");
        $stmt->execute(['user', password_hash('User@123', PASSWORD_DEFAULT)]);
    }
}

try { ensure_schema($pdo); } catch (PDOException $e) { app_db_error('The database was found, but the application could not create its tables. Check that your MySQL user has permission to create tables. Details: ' . $e->getMessage()); }

function is_logged_in(): bool { return isset($_SESSION['user_id'], $_SESSION['role']); }
function is_admin(): bool { return is_logged_in() && $_SESSION['role'] === 'admin'; }
function require_login(): void {
    if (!is_logged_in()) { header('Location: login.php'); exit; }
}
function require_admin(): void {
    require_login();
    if (!is_admin()) { header('Location: activities.php?error=admin_only'); exit; }
}
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(419); die('Invalid form token. Please go back and try again.');
    }
}
function flash(string $message, string $type='success'): void { $_SESSION['flash'] = ['message'=>$message,'type'=>$type]; }
function get_flash(): ?array { $f=$_SESSION['flash']??null; unset($_SESSION['flash']); return $f; }
?>
