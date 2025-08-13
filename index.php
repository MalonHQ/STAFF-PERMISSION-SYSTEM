<?php
// Simple DB connection (no config folder, no .env)
$DB_HOST = 'localhost';
$DB_NAME = 'staff_permission_system';
$DB_USER = 'root';
$DB_PASS = '';
$SESSION_NAME = 'SPS_SESSION';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_error) {
    die('DB Connection failed: ' . $mysqli->connect_error);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name($SESSION_NAME);
    session_start();
}

// Base URL helper
function base_url(string $path = ''): string {
    return 'http://localhost/STAFF-PERMISSION-SYSTEM/' . ltrim($path, '/');
}

// Redirect logged-in users to dashboard
if (!empty($_SESSION['user'])) {
    header('Location: ' . base_url('dashboard.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = strval($_POST['password'] ?? '');

    $stmt = $mysqli->prepare('SELECT id, username, password, role, full_name FROM users WHERE username=? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = [
                'id'       => $row['id'],
                'username' => $row['username'],
                'role'     => $row['role'],
                'full_name'=> $row['full_name'],
            ];
            header('Location: ' . base_url('dashboard.php'));
            exit;
        }
    }

    $error = 'Invalid credentials';
}
?>
