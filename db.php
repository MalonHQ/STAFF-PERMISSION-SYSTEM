<?php
// Database configuration (no .env)
$DB_HOST = 'localhost';
$DB_NAME = 'staff_permission_system';
$DB_USER = 'root';
$DB_PASS = '';
$SESSION_NAME = 'SPS_SESSION';

// Create MySQL connection
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_error) {
    die('DB Connection failed: ' . $mysqli->connect_error);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name($SESSION_NAME);
    session_start();
}

// Base URL helper (hardcoded, no .env)
function base_url(string $path = ''): string {
    $base = 'http://localhost/STAFF-PERMISSION-SYSTEM';
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}
?>
