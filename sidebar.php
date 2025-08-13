<?php
// Simple DB connection and session (no config folder, no .env)
$DB_HOST = 'localhost';
$DB_NAME = 'staff_permission_system';
$DB_USER = 'root';
$DB_PASS = '';
$SESSION_NAME = 'SPS_SESSION';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_error) {
    die('DB Connection failed: ' . $mysqli->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_name($SESSION_NAME);
    session_start();
}

// Base URL helper
function base_url(string $path = ''): string {
    return 'http://localhost/STAFF-PERMISSION-SYSTEM/' . ltrim($path, '/');
}

// Get current user role
function current_role(): string {
    return strval($_SESSION['user']['role'] ?? '');
}
?>

<aside class="sidebar">
  <h2>Staff Permission</h2>
  <?php if (!empty($_SESSION['user'])): ?>
    <div class="userbox">
      <div class="name"><?php echo htmlspecialchars($_SESSION['user']['full_name']); ?></div>
      <div class="role badge"><?php echo htmlspecialchars($_SESSION['user']['role']); ?></div>
    </div>
    <nav>
      <a href="<?php echo base_url('dashboard.php'); ?>">Dashboard</a>
      <a href="<?php echo base_url('profile.php'); ?>">My Profile</a>
      <?php if (in_array(current_role(), ['user','staff'])): ?>
        <a href="<?php echo base_url('user/request_new.php'); ?>">New Request</a>
        <a href="<?php echo base_url('user/requests.php'); ?>">My Requests</a>
      <?php endif; ?
