<?php
require_once __DIR__ . '/db.php';

// Redirect to login if user not logged in
function require_login(): void {
    if (empty($_SESSION['user'])) {
        header('Location: ' . base_url('index.php'));
        exit;
    }
}

// Require specific role for access
function require_role(string $role): void {
    require_login();
    if (($_SESSION['user']['role'] ?? '') !== $role) {
        // Allow admin to access staff/user pages by default
        if ($role !== 'admin' && ($_SESSION['user']['role'] ?? '') === 'admin') return;
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

// Get current logged-in user ID
function current_user_id(): int {
    return intval($_SESSION['user']['id'] ?? 0);
}

// Get current logged-in user role
function current_role(): string {
    return strval($_SESSION['user']['role'] ?? '');
}

// Base URL helper (hardcoded, no .env)
function base_url(string $path = ''): string {
    $base = 'http://localhost/STAFF-PERMISSION-SYSTEM';
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}
?>
